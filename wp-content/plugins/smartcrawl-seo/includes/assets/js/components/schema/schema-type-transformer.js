import schemaTypeProperties from "./schema-type-properties";
import update from "immutability-helper";
import {merge} from "lodash-es";

export default class SchemaTypeTransformer {
	constructor() {
		this.callbacks = {};

		this.register('Event', (schema) => this.addVirtualLocationToEvent(schema));
		this.register('Product', (schema) => this.removeReviewAuthorOrganizationDataFromProduct(schema));
		this.register('Product', (schema) => this.addAlternateBrandTypeToProduct(schema));
		this.register('WooProduct', (schema) => this.removeReviewAuthorOrganizationDataFromWooProduct(schema));
		this.register('WooProduct', (schema) => this.addAlternateBrandTypeToWooProduct(schema));
		this.register('WooSimpleProduct', (schema) => this.removeReviewAuthorOrganizationDataFromWooProduct(schema));
		this.register('WooSimpleProduct', (schema) => this.addAlternateBrandTypeToWooProduct(schema));
		this.register('WooVariableProduct', (schema) => this.removeReviewAuthorOrganizationDataFromWooProduct(schema));
		this.register('WooVariableProduct', (schema) => this.addAlternateBrandTypeToWooProduct(schema));
	}

	register(type, transformingCallback) {
		if (!this.callbacks.hasOwnProperty(type)) {
			this.callbacks[type] = [];
		}

		this.callbacks[type].push(transformingCallback);
	}

	transformProperties(type, initial) {
		let transformed = initial;
		const callbacks = this.callbacks[type] || [];

		callbacks.forEach(callback => {
			transformed = callback(transformed);
		});

		return transformed;
	}

	/**
	 * Event type in version 2.9 didn't have virtual location but it was added in 2.10
	 */
	addVirtualLocationToEvent(untransformed) {
		if (
			!untransformed?.location // Location not available, it was probably deleted
			|| untransformed?.location?.activeVersion // Virtual location already exists, no need for a transformation
		) {
			return untransformed;
		}

		const eventProperties = schemaTypeProperties['Event'];
		const newLocation = merge({}, eventProperties.location, {properties: {Place: untransformed.location}});
		return update(untransformed, {
			"location": {$set: newLocation}
		});
	}

	makeReviewAuthorOrganizationDataRemovalSpec(reviewProperties) {
		const spec = {};
		Object.keys(reviewProperties).forEach((repeatableKey) => {
			const organizationData = reviewProperties[repeatableKey]?.properties?.author?.properties?.Organization?.properties;
			const unset = [];

			if (organizationData?.contactPoint) {
				unset.push('contactPoint');
			}

			if (organizationData?.address) {
				unset.push('address');
			}

			if (!unset.length) {
				return;
			}

			spec[repeatableKey] = this.formatSpec(
				['properties', 'author', 'properties', 'Organization', 'properties'],
				{$unset: unset}
			);
		});

		return spec;
	}

	/**
	 * In 2.9 the review author organization property used to have contactPoint and address which were removed later on
	 */
	removeReviewAuthorOrganizationDataFromProduct(untransformed) {
		const reviewProperties = untransformed?.review?.properties;
		if (!reviewProperties) {
			return untransformed;
		}

		const spec = this.makeReviewAuthorOrganizationDataRemovalSpec(reviewProperties);
		if (!Object.keys(spec).length) {
			// Nothing to do
			return untransformed;
		}

		return update(untransformed, {"review": {"properties": spec}});
	}

	/**
	 * In 2.9 the review author organization property used to have contactPoint and address which were removed later on
	 */
	removeReviewAuthorOrganizationDataFromWooProduct(untransformed) {
		const reviewProperties = untransformed?.review?.properties?.Review?.properties;
		if (reviewProperties) {
			const reviewSpec = this.makeReviewAuthorOrganizationDataRemovalSpec(reviewProperties);
			if (Object.keys(reviewSpec).length) {
				untransformed = update(
					untransformed,
					this.formatSpec(["review", "properties", "Review", "properties"], reviewSpec)
				);
			}
		}

		const wooOrganizationData = untransformed?.review?.properties?.WooCommerceReviewLoop?.properties?.author?.properties?.Organization?.properties;
		const wooUnset = [];

		if (wooOrganizationData?.contactPoint) {
			wooUnset.push('contactPoint');
		}

		if (wooOrganizationData?.address) {
			wooUnset.push('address');
		}

		if (wooUnset.length) {
			untransformed = update(
				untransformed,
				this.formatSpec(["review", "properties", "WooCommerceReviewLoop", "properties", "author", "properties", "Organization", "properties"], {$unset: wooUnset})
			);
		}

		return untransformed;
	}

	formatSpec(keys, operation) {
		keys.slice().reverse().forEach(key => {
			operation = {[key]: operation};
		});

		return operation;
	}

	/**
	 * In version 2.16 Brand type was added an an available alternate version to Product brand property
	 */
	addAlternateBrandTypeToProduct(untransformed) {
		return this.addAlternateBrandType(
			untransformed,
			schemaTypeProperties.Product
		);
	}

	/**
	 * In version 2.16 Brand type was added an an available alternate version to WooProduct brand property
	 */
	addAlternateBrandTypeToWooProduct(untransformed) {
		return this.addAlternateBrandType(
			untransformed,
			schemaTypeProperties.WooProduct
		);
	}

	addAlternateBrandType(untransformed, sourceProperties) {
		if (
			!untransformed?.brand // No brand, it was probably deleted by the user
			|| untransformed?.brand?.activeVersion // Brand already has two versions, no need for a transformation
		) {
			return untransformed;
		}

		const optionalRemovalSpec = this.formatSpec(
			['properties', 'Organization', 'properties'],
			{$unset: ['address', 'contactPoint']}
		);
		const brandWithoutOptionalProperties = update(sourceProperties.brand, optionalRemovalSpec);

		const newBrand = merge(
			{},
			brandWithoutOptionalProperties,
			{activeVersion: 'Organization'},
			{properties: {Organization: untransformed.brand}}
		);
		return update(untransformed, {
			"brand": {$set: newBrand}
		});
	}
}
