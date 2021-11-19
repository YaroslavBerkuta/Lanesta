import {__} from '@wordpress/i18n';
import uniqueId from "lodash-es/uniqueId";
import ProductOffer from "./product-offer";
import ProductAggregateOffer from "./product-aggregate-offer";
import ProductAggregateRating from "./product-aggregate-rating";
import ProductReview from "./product-review";
import ProductOrganization from "./product-organization";
import ProductBrand from "./product-brand";

const id = uniqueId;
const Product = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'post_data',
		value: 'post_title',
		required: true,
		description: __('The name of the product.', 'wds'),
	},
	description: {
		id: id(),
		label: __('Description', 'wds'),
		type: 'TextFull',
		source: 'seo_meta',
		value: 'seo_description',
		description: __('The product description.', 'wds'),
	},
	sku: {
		id: id(),
		label: __('SKU', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('Merchant-specific identifier for product.', 'wds'),
	},
	gtin: {
		id: id(),
		label: __('GTIN', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		optional: true,
		description: __('A Global Trade Item Number (GTIN). GTINs identify trade items, including products and services, using numeric identification codes.', 'wds'),
	},
	gtin8: {
		id: id(),
		label: __('GTIN-8', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		optional: true,
		description: __('The GTIN-8 code of the product. This code is also known as EAN/UCC-8 or 8-digit EAN.', 'wds'),
	},
	gtin12: {
		id: id(),
		label: __('GTIN-12', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		optional: true,
		description: __('The GTIN-12 code of the product. The GTIN-12 is the 12-digit GS1 Identification Key composed of a U.P.C. Company Prefix, Item Reference, and Check Digit used to identify trade items.', 'wds'),
	},
	gtin13: {
		id: id(),
		label: __('GTIN-13', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		optional: true,
		description: __('The GTIN-13 code of the product. This is equivalent to 13-digit ISBN codes and EAN UCC-13.', 'wds'),
	},
	gtin14: {
		id: id(),
		label: __('GTIN-14', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		optional: true,
		description: __('The GTIN-14 code of the product.', 'wds'),
	},
	mpn: {
		id: id(),
		label: __('MPN', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		optional: true,
		description: __('The Manufacturer Part Number (MPN) of the product.', 'wds')
	},
	image: {
		id: id(),
		label: __('Images', 'wds'),
		labelSingle: __('Image', 'wds'),
		description: __('The images associated with the product.', 'wds'),
		properties: {
			0: {
				id: id(),
				label: __('Image', 'wds'),
				type: 'ImageObject',
				source: 'post_data',
				value: 'post_thumbnail'
			}
		}
	},
	brand: {
		id: id(),
		label: __('Brand', 'wds'),
		activeVersion: 'Brand',
		properties: {
			Brand: {
				id: id(),
				label: __('Brand', 'wds'),
				description: __('The brand of the product.', 'wds'),
				type: 'Brand',
				properties: ProductBrand,
				isAnAltVersion: true,
			},
			Organization: {
				id: id(),
				label: __('Organization', 'wds'),
				description: __('The brand of the product.', 'wds'),
				type: 'Organization',
				properties: ProductOrganization,
				isAnAltVersion: true,
			},
		},
	},
	review: {
		id: id(),
		label: __('Reviews', 'wds'),
		labelSingle: __('Review', 'wds'),
		properties: {
			0: {
				id: id(),
				type: 'Review',
				properties: ProductReview
			}
		},
		required: true,
		requiredNotice: __('This property is required by Google. You must include at least one of the following properties: review, aggregateRating or offers.', 'wds'),
		description: __('A nested Review of the product.', 'wds'),
	},
	aggregateRating: {
		id: id(),
		label: __('Aggregate Rating', 'wds'),
		type: 'AggregateRating',
		properties: ProductAggregateRating,
		required: true,
		requiredNotice: __('This property is required by Google. You must include at least one of the following properties: review, aggregateRating or offers.', 'wds'),
		description: __('A nested aggregateRating of the product.', 'wds'),
	},
	offers: {
		id: id(),
		label: __('Offers', 'wds'),
		activeVersion: 'Offer',
		properties: {
			Offer: {
				id: id(),
				label: __('Offers', 'wds'),
				labelSingle: __('Offer', 'wds'),
				properties: {
					0: {
						id: id(),
						type: 'Offer',
						properties: ProductOffer,
					}
				},
				required: true,
				requiredNotice: __('This property is required by Google. You must include at least one of the following properties: review, aggregateRating or offers.', 'wds'),
				description: __('A nested Offer to sell the product.', 'wds'),
				isAnAltVersion: true,
			},
			AggregateOffer: {
				id: id(),
				type: 'AggregateOffer',
				label: __('Aggregate Offer', 'wds'),
				properties: ProductAggregateOffer,
				required: true,
				requiredNotice: __('This property is required by Google. You must include at least one of the following properties: review, aggregateRating or offers.', 'wds'),
				description: __('A nested AggregateOffer to sell the product.', 'wds'),
				isAnAltVersion: true,
			}
		},
		required: true,
	},
};
export default Product;
