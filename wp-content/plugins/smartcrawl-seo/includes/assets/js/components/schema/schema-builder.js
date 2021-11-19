import React from 'react';
import SchemaTypeCondition from "./schema-type-condition";
import update from 'immutability-helper';
import {first, parseInt, uniqueId} from "lodash-es";
import {__, _n, sprintf} from '@wordpress/i18n';
import Modal from "../modal";
import Button from "../button";
import classnames from 'classnames';
import $ from 'jQuery';
import SUI from 'SUI';
import Config_Values from "../../es6/config-values";
import BoxSelectorModal from "../box-selector-modal";
import AddSchemaTypeWizardModal from "./add-schema-type-wizard-modal";
import SchemaTypeLocations from "./schema-type-locations";
import {createInterpolateElement} from '@wordpress/element';
import UrlUtil from "../utils/url-util";
import SchemaTypeDropdown from "./schema-type-dropdown";
import SchemaTypeRenameModal from "./schema-type-rename-modal";
import Notice from "../notice";
import SchemaTypePropertiesTable from "./schema-type-properties-table";
import SchemaTypesBoxFooter from "./schema-types-box-footer";
import SchemaProperties from "./schema-properties";
import SchemaTypeTransformer from "./schema-type-transformer";
import SchemaTypes from "./schema-types";

export default class SchemaBuilder extends React.Component {
	constructor(props) {
		super(props);

		this.props = props;
		this.state = {
			initialized: false,
			types: {},
			deletingProperty: '',
			deletingPropertyId: 0,
			addingProperties: '',
			addingNestedForProperty: 0,
			addingSchemaTypes: false,
			resettingProperties: '',
			renamingType: '',
			deletingType: '',
			changingPropertyTypeForId: 0,
			openTypes: [],
			invalidTypes: [],
		};
		this.accordionElement = React.createRef();
	}

	componentDidMount() {
		this.hookNestedAccordions();
		this.maybeInitializeComponent();
		this.maybeStartAddingSchemaType();
	}

	hookNestedAccordions() {
		const $accordion = $(this.accordionElement.current);
		$accordion.on('click', '.sui-accordion-item-body .sui-accordion-item-header', function (event) {
			const clickedTarget = $(event.target);
			if (clickedTarget.closest('.sui-accordion-item-action').length) {
				return true;
			}

			$(this).closest('.sui-accordion-item').toggleClass('sui-accordion-item--open');
		});
	}

	maybeInitializeComponent() {
		if (this.state.initialized) {
			return;
		}

		const schemaTypes = {};
		const savedSchemaTypes = Config_Values.get('types', 'schema_types');
		const initializedTypeIds = [];
		const transformer = new SchemaTypeTransformer();
		Object.keys(savedSchemaTypes).forEach((schemaTypeKey) => {
			const savedSchemaType = savedSchemaTypes[schemaTypeKey];
			const typeId = this.generateTypeId(savedSchemaType.type);
			const sourceProperties = this.getPropertiesForType(savedSchemaType.type);
			const properties = this.initializeProperties(
				transformer.transformProperties(savedSchemaType.type, savedSchemaType.properties),
				sourceProperties
			);
			const conditions = this.cloneConditions(savedSchemaType.conditions);

			schemaTypes[typeId] = Object.assign({}, savedSchemaType, {
				conditions: conditions,
				properties: properties
			});

			initializedTypeIds.push(typeId);
		});

		this.setState({
			types: schemaTypes,
			initialized: true
		}, () => {
			const invalidTypes = [];
			initializedTypeIds.forEach((initializedTypeId) => {
				if (this.typeHasMissingRequiredProperties(initializedTypeId)) {
					invalidTypes.push(initializedTypeId);
				}
			});
			this.setState({invalidTypes: invalidTypes});
			if (
				invalidTypes.length
				&& Config_Values.get('settings_updated', 'schema_types')
			) {
				this.showInvalidTypesNotice();
			}
		});
	}

	formatSpec(keys, operation) {
		keys.slice().reverse().forEach(key => {
			operation = {[key]: operation};
		});

		return operation;
	}

	defaultCondition(typeKey) {
		const type = SchemaTypes.getType(this.getType(typeKey).type);
		const fallback = {id: uniqueId(), lhs: 'post_type', operator: '=', rhs: 'post'};

		return type.condition
			? Object.assign({}, type.condition, {id: uniqueId()})
			: fallback;
	}

	addGroup(typeKey) {
		const newIndex = this.getType(typeKey).conditions.length;
		const spec = this.formatSpec([typeKey, 'conditions'], {
			$splice: [
				[newIndex, 0, [this.defaultCondition(typeKey)]]
			]
		});
		this.updateTypes(spec);
	}

	updateTypes(spec) {
		return new Promise(resolve => {
			this.setState({types: update(this.state.types, spec)}, () => {
				resolve();
			});
		});
	}

	addCondition(typeKey, id) {
		const type = this.getType(typeKey);
		const groupIndex = this.conditionGroupIndex(type.conditions, id);
		const conditionIndex = this.conditionIndex(type.conditions[groupIndex], id);
		const newConditionIndex = conditionIndex + 1;
		const spec = this.formatSpec(
			[typeKey, 'conditions', groupIndex],
			{
				$splice: [[newConditionIndex, 0, this.defaultCondition(typeKey)]]
			}
		);

		this.updateTypes(spec);
	}

	updateCondition(typeKey, id, lhs, operator, rhs) {
		const type = this.getType(typeKey);
		const groupIndex = this.conditionGroupIndex(type.conditions, id);
		const conditionIndex = this.conditionIndex(type.conditions[groupIndex], id);
		const spec = this.formatSpec(
			[typeKey, 'conditions', groupIndex, conditionIndex],
			{
				lhs: {$set: lhs},
				operator: {$set: operator},
				rhs: {$set: rhs}
			}
		);

		this.updateTypes(spec);
	}

	deleteCondition(typeKey, id) {
		const type = this.getType(typeKey);
		const groupIndex = this.conditionGroupIndex(type.conditions, id);
		const group = type.conditions[groupIndex];
		let spec;
		if (group.length === 1) {
			spec = this.formatSpec([typeKey, 'conditions'], {
				$splice: [[groupIndex, 1]]
			});
		} else {
			const conditionIndex = this.conditionIndex(group, id);
			spec = this.formatSpec([typeKey, 'conditions', groupIndex], {
				$splice: [[conditionIndex, 1]]
			});
		}

		this.updateTypes(spec);
	}

	startAddingProperties(typeKey) {
		this.setState({
			addingProperties: typeKey,
		});
	}

	handleAddPropertiesButtonClick(typeKey, addedProperties) {
		this.addProperties(typeKey, addedProperties).then((newPropertyIds) => {
			newPropertyIds.forEach((newPropertyId) => {
				this.openAccordionItemForPropertyOrAlt(typeKey, newPropertyId);
			});

			this.checkTypeValidity(typeKey);

			this.showNotice(_n(
				'The property has been added. You need to save the changes to make them live.',
				'The properties have been added. You need to save the changes to make them live.',
				newPropertyIds.length,
				'wds'
			));
		});
		this.stopAddingProperties();
	}

	getPropertiesForType(typeKey) {
		const propertiesForType = SchemaTypes.getType(typeKey).properties;
		return propertiesForType;
	}

	openAccordionItemForPropertyOrAlt(typeKey, newPropertyId) {
		const property = this.getPropertyById(
			newPropertyId,
			this.getType(typeKey).properties
		);
		if (this.hasAltVersions(property)) {
			const activeAltVersion = this.getActiveAltVersion(property);
			this.openAccordionItemForProperty(activeAltVersion.id);
		} else {
			this.openAccordionItemForProperty(newPropertyId);
		}
	}

	addProperties(typeKey, propertyIds) {
		const type = this.getType(typeKey);
		const newPropertyIds = [];
		let updatedProperties = type.properties,
			newPropertyId;
		propertyIds.forEach(propertyId => {
			[updatedProperties, newPropertyId] = this.addProperty(
				propertyId,
				this.getPropertiesForType(type.type),
				updatedProperties
			);
			newPropertyIds.push(...newPropertyId);
		});

		const spec = this.formatSpec([typeKey, 'properties'], {$set: updatedProperties});
		return new Promise(resolve => {
			this.updateTypes(spec).then(() => resolve(newPropertyIds));
		});
	}

	typeHasMissingRequiredProperties(typeKey) {
		const type = this.getType(typeKey);
		return this.requiredPropertiesMissing(
			type.properties,
			this.getPropertiesForType(type.type)
		);
	}

	requiredPropertiesMissing(subjectProperties, sourceProperties) {
		let hasMissingProperties = false;

		Object.keys(sourceProperties).some(sourcePropertyKey => {
			const sourceProperty = sourceProperties[sourcePropertyKey];
			// We know that nested properties are not going to be required if the parent property itself is not required
			// An exception to this rule is local business -> review -> author but that doesn't matter because it is inside a repeatable and is always valid
			if (sourceProperty.required) {
				if (!subjectProperties.hasOwnProperty(sourcePropertyKey)) {
					hasMissingProperties = true;
					return true;
				} else if (
					this.isNestedProperty(sourceProperty)
					&& this.isNestedProperty(subjectProperties[sourcePropertyKey])
				) {
					const hasNestedMissingProperties = this.requiredPropertiesMissing(
						subjectProperties[sourcePropertyKey].properties,
						sourceProperty.properties
					);
					if (hasNestedMissingProperties) {
						hasMissingProperties = true;
						return true;
					}
				}
			}
		});

		return hasMissingProperties;
	}

	addProperty(sourcePropertyId, sourceProperties, targetProperties) {
		const newPropertyIds = [];
		let updatedProperties = targetProperties;
		Object.keys(sourceProperties).some(sourcePropertyKey => {
			const sourceProperty = sourceProperties[sourcePropertyKey];
			if (sourceProperty.id === sourcePropertyId) {
				const newProperty = this.getDefaultProperty(sourceProperty);
				updatedProperties = update(updatedProperties, {
					[sourcePropertyKey]: {$set: newProperty}
				});
				newPropertyIds.push(newProperty.id);
				return true;
			} else if (
				this.isNestedProperty(sourceProperty)
				&& targetProperties.hasOwnProperty(sourcePropertyKey)
				&& this.isNestedProperty(targetProperties[sourcePropertyKey])
			) {
				const [nestedUpdatedProperties, newNestedPropertyIds] = this.addProperty(
					sourcePropertyId,
					sourceProperty.properties,
					targetProperties[sourcePropertyKey].properties
				);
				updatedProperties = update(updatedProperties, {
					[sourcePropertyKey]: {properties: {$set: nestedUpdatedProperties}}
				});
				newPropertyIds.push(...newNestedPropertyIds);
			}
		});

		return [updatedProperties, newPropertyIds];
	}

	stopAddingProperties() {
		this.setState({
			addingProperties: '',
			addingNestedForProperty: 0,
		});
	}

	updateProperty(typeKey, id, source, value) {
		const type = this.getType(typeKey);
		const propertyKeys = this.propertyKeys(id, type.properties);
		const spec = this.formatSpec([typeKey, 'properties', ...propertyKeys], {
			source: {$set: source},
			value: {$set: value},
		});
		this.updateTypes(spec);
	}

	startDeletingProperty(typeKey, id) {
		this.setState({
			deletingProperty: typeKey,
			deletingPropertyId: id
		});
	}

	handleDeleteButtonClick(typeKey) {
		this.deleteProperty(typeKey, this.state.deletingPropertyId).then(() => {
			this.checkTypeValidity(typeKey);

			this.stopDeletingProperty();
		});
	}

	deleteProperty(typeKey, id) {
		const type = this.getType(typeKey);
		const spec = this.formatSpec([typeKey, 'properties'], {
			$set: this.deletePropertyById(id, type.properties)
		});

		this.showNotice(__('The property has been removed from this module.', 'wds'), 'info');
		return this.updateTypes(spec);
	}

	deletePropertyById(id, properties) {
		let updatedProperties = properties;
		Object.keys(properties).some((propertyKey) => {
			const property = properties[propertyKey];
			if (id === property.id) {
				updatedProperties = update(updatedProperties, {
					$unset: [propertyKey]
				});
				return true;
			} else if (this.isNestedProperty(property)) {
				const updatedNestedProperties = this.deletePropertyById(id, property.properties);
				const deletedAltVersion = this.hasAltVersions(property)
					&& Object.keys(updatedNestedProperties).length !== Object.keys(property.properties).length;
				let spec;
				if (deletedAltVersion || this.objectEmpty(updatedNestedProperties)) {
					spec = {$unset: [propertyKey]};
				} else {
					spec = {[propertyKey]: {properties: {$set: updatedNestedProperties}}};
				}
				updatedProperties = update(updatedProperties, spec);
			}
		});

		return updatedProperties;
	}

	objectEmpty(obj) {
		return !this.objectLength(obj);
	}

	objectLength(obj) {
		return Object.keys(obj).length;
	}

	stopDeletingProperty() {
		this.setState({
			deletingProperty: '',
			deletingPropertyId: 0
		});
	}

	getPropertyByKeys(propertyKeys, properties) {
		let property = properties;
		propertyKeys.forEach(key => {
			property = property[key];
		});
		return property;
	}

	/**
	 * @param id
	 * @param properties
	 *
	 * @return {Array}
	 */
	propertyKeys(id, properties) {
		return this.findPropertyKeys((property) => {
			return property.hasOwnProperty('id') && property.id === id;
		}, properties);
	}

	findPropertyKeys(callback, properties) {
		let keys = [];

		Object.keys(properties).some(propertyKey => {
			if (callback(properties[propertyKey])) {
				keys.unshift(propertyKey);
				return true;
			} else if (this.isNestedProperty(properties[propertyKey])) {
				const nestedKeys = this.findPropertyKeys(callback, properties[propertyKey].properties);
				if (nestedKeys.length) {
					keys.unshift(propertyKey, 'properties', ...nestedKeys);
					return true;
				}
			}
		});

		return keys;
	}

	conditionGroupIndex(conditions, id) {
		return conditions.findIndex(conditions => this.conditionIndex(conditions, id) > -1);
	}

	conditionIndex(conditions, id) {
		return conditions.findIndex(condition => condition.id === id);
	}

	render() {
		return (
			<React.Fragment>
				{!!this.state.invalidTypes.length
				&& this.getWarningElement(createInterpolateElement(
					__('One or more types have properties that are required by Google that have been removed. Please check your types and click on the <strong>Add Property</strong> button to add the missing <strong>required properties</strong> ( <span>*</span> ), for your content to be eligible for display as a rich result. To learn more about schema type properties, see our <DocLink>Schema Documentation</DocLink>.'),
					{
						strong: <strong/>,
						span: <span/>,
						DocLink: <a
							target="_blank"
							href="https://wpmudev.com/docs/wpmu-dev-plugins/smartcrawl/#schema"/>,
					}
				))}

				<div id="wds-schema-types-body" className={classnames({
					'hidden': !Object.keys(this.state.types).length
				})}>
					<div className="sui-row">
						<div className="sui-col-md-5">
							<small><strong>{__('Schema Type', 'wds')}</strong></small>
						</div>
						<div className="sui-col-md-7">
							<small><strong>{__('Location', 'wds')}</strong></small>
						</div>
					</div>
					<div className="sui-accordion sui-accordion-flushed" ref={this.accordionElement}>
						{Object.keys(this.state.types).map(typeKey => {
								const type = this.getType(typeKey);
								const typeSubText = this.getTypeSubText(type.type);
								return <React.Fragment key={typeKey}>
									<div className={classnames(
										'sui-accordion-item',
										this.getTypeAccordionItemClassName(typeKey),
										{
											'sui-accordion-item--open': this.state.openTypes.includes(typeKey),
											'sui-accordion-item--disabled': type.disabled || SchemaTypes.getType(type.type).disabled
										}
									)}>
										{this.getTypeAccordionItemHeaderElement(typeKey)}

										<div className="sui-accordion-item-body">
											{this.state.openTypes.includes(typeKey) &&
											<div>
												{this.getSchemaTypeRulesElement(typeKey, this.getType(typeKey).conditions)}
												{this.getPropertiesTableElement(typeKey, this.getType(typeKey).properties)}
											</div>
											}


											{typeSubText &&
											<span className="wds-type-sub-text">
												{typeSubText}
											</span>
											}
										</div>
									</div>

									{this.state.deletingProperty === typeKey && this.getPropertyDeletionModalElement(typeKey)}
									{this.state.addingProperties === typeKey && this.getAddTypePropertyModalElement(typeKey)}
									{this.state.resettingProperties === typeKey && this.getPropertyResetModalElement(typeKey)}
									{this.state.renamingType === typeKey && this.getTypeRenameModalElement(typeKey)}
									{this.state.deletingType === typeKey && this.getTypeDeleteModalElement(typeKey)}
								</React.Fragment>;
							}
						)}
					</div>
				</div>

				<div id="wds-schema-types-footer">
					<Button onClick={() => this.startAddingSchemaType()}
							dashed={true}
							icon="sui-icon-plus"
							text={__('Add New Type', 'wds')}/>

					<p className="sui-description">
						{__('Add additional schema types you want to output on this site.', 'wds')}
					</p>

					<SchemaTypesBoxFooter/>
				</div>

				{this.state.addingSchemaTypes && this.getAddSchemaModalElement()}
				{this.getStateInput()}
			</React.Fragment>
		);
	}

	getPropertiesTableElement(typeKey, properties) {
		return <SchemaTypePropertiesTable onReset={() => this.startResettingProperties(typeKey)}
										  onAdd={() => this.startAddingProperties(typeKey)}>

			<SchemaProperties properties={properties}
							  methods={this.getSchemaPropertyMethods(typeKey)}/>
		</SchemaTypePropertiesTable>;
	}

	getSchemaPropertyMethods(typeKey) {
		return {
			beforePropertyRender: (propertyId) => this.renderPropertyModals(typeKey, propertyId),
			requiredNestedPropertiesMissing: (property) => this.nestedPropertyHasMissingRequiredProperties(typeKey, property),
			onChangeActiveVersion: (propertyId) => this.startChangingPropertyType(propertyId),
			onRepeat: (propertyId) => this.handleRepeatButtonClick(typeKey, propertyId),
			onAddNested: (propertyId) => this.startAddingNestedProperties(typeKey, propertyId),
			onChange: (propertyId, source, value) => this.updateProperty(typeKey, propertyId, source, value),
			onDelete: (propertyId) => this.startDeletingProperty(typeKey, propertyId),
		};
	}

	renderPropertyModals(typeKey, propertyId) {
		return <React.Fragment>
			{this.state.addingNestedForProperty === propertyId && this.getAddNestedPropertyModalElement(typeKey, propertyId)}
			{this.state.changingPropertyTypeForId === propertyId && this.getPropertyTypeChangeModalElement(typeKey, propertyId)}
		</React.Fragment>;
	}

	getSchemaTypeRulesElement(typeKey, conditions) {
		return <div className="wds-schema-type-rules">
			<span className="sui-icon-link" aria-hidden="true"/>
			<small>
				<strong>{__('Location', 'wds')}</strong>
			</small>
			<span className="sui-description">
				{__('Create a set of rules to determine where this schema.org type will be enabled or excluded.', 'wds')}
			</span>

			{this.getConditionGroupElements(typeKey, conditions)}

			<Button text={__('Add Rule (Or)', 'wds')}
					ghost={true}
					onClick={() => this.addGroup(typeKey)}
					icon="sui-icon-plus"/>
		</div>;
	}

	checkTypeValidity(typeKey) {
		const invalid = this.typeHasMissingRequiredProperties(typeKey);
		this.setTypeInvalid(typeKey, invalid);
	}

	isTypeInvalid(typeKey) {
		return this.state.invalidTypes.includes(typeKey);
	}

	setTypeInvalid(typeKey, invalid) {
		const alreadyInvalid = this.isTypeInvalid(typeKey);
		let newInvalidTypes = this.state.invalidTypes.slice();
		if (invalid && !alreadyInvalid) {
			newInvalidTypes.push(typeKey);
		} else if (!invalid && alreadyInvalid) {
			newInvalidTypes = this.state.invalidTypes.filter((element => element !== typeKey));
		}

		return this.setState({
			invalidTypes: newInvalidTypes
		});
	}

	handleTypeStatusChange(typeKey, checked) {
		const spec = this.formatSpec([typeKey, 'disabled'], {
			$set: !checked
		});
		this.updateTypes(spec).then(() => {
			let message;
			if (checked) {
				message = __('You have successfully activated the %s type.', 'wds');
			} else {
				message = __('You have successfully deactivated the %s type.', 'wds');
			}
			this.showNotice(sprintf(message, this.getType(typeKey).label));
		});
	}

	handleTypeAccordionItemToggle(event, typeKey) {
		const clickedTarget = $(event.target);
		if (clickedTarget.closest('.sui-accordion-item-action').length) {
			return true;
		}

		this.toggleType(typeKey);
	}

	toggleType(typeKey) {
		let newOpenTypes;
		if (this.state.openTypes.includes(typeKey)) {
			newOpenTypes = this.state.openTypes.filter((element => element !== typeKey));
		} else {
			newOpenTypes = this.state.openTypes.slice();
			newOpenTypes.push(typeKey);
		}

		return this.setState({
			openTypes: newOpenTypes
		});
	}

	getTypeAccordionItemHeaderElement(typeKey) {
		const type = this.getType(typeKey);

		return <div className="sui-accordion-item-header wds-type-accordion-item-header"
					onClick={(event) => this.handleTypeAccordionItemToggle(event, typeKey)}>
			<div className="sui-accordion-item-title sui-accordion-col-5">
				<span className={this.getSchemaTypeIcon(type.type)}/>
				<span>{type.label}</span>
				{this.isTypeInvalid(typeKey) &&
				<span className="sui-tooltip sui-tooltip-constrained"
					  data-tooltip={__('This type has missing properties that are required by Google.', 'wds')}>
					<span className="wds-invalid-type-icon sui-icon-warning-alert sui-md"
						  aria-hidden="true"/>
				</span>
				}
			</div>

			<div className="sui-accordion-col-3">
				<SchemaTypeLocations conditions={type.conditions}/>
			</div>

			<div className="sui-accordion-col-4">
				<label className="sui-toggle sui-accordion-item-action">
					<input type="checkbox" defaultChecked={!this.getType(typeKey).disabled}
						   onChange={(e) => this.handleTypeStatusChange(typeKey, e.target.checked)}
					/>
					<span aria-hidden="true" className="sui-toggle-slider"/>
				</label>
				<SchemaTypeDropdown onRename={() => this.startRenamingType(typeKey)}
									onDuplicate={() => this.duplicateType(typeKey)}
									onDelete={() => this.startDeletingType(typeKey)}/>

				<button className="sui-button-icon sui-accordion-open-indicator"
						type="button"
						onClick={(event) => this.handleTypeAccordionItemToggle(event, typeKey)}
						aria-label={__('Open item', 'wds')}>
					<span className="sui-icon-chevron-down" aria-hidden="true"/>
				</button>
			</div>
		</div>;
	}

	getPropertyDeletionModalElement(typeKey) {
		const property = this.getPropertyById(this.state.deletingPropertyId, this.getType(typeKey).properties);
		const description = property.required ?
			__('You are trying to delete a property that is required by Google. Are you sure you wish to delete it anyway?', 'wds') :
			__('Are you sure you wish to delete this property? You can add it again anytime.', 'wds');

		return <Modal small={true}
					  id="wds-confirm-property-deletion"
					  title={__('Are you sure?', 'wds')}
					  onClose={() => this.stopDeletingProperty()}
					  focusAfterOpen="wds-schema-property-delete-button"
					  description={description}>

			<Button text={__('Cancel', 'wds')}
					onClick={() => this.stopDeletingProperty()}
					ghost={true}
			/>

			<Button text={__('Delete', 'wds')}
					onClick={() => this.handleDeleteButtonClick(typeKey)}
					icon="sui-icon-trash"
					color="red"
					id="wds-schema-property-delete-button"
			/>
		</Modal>;
	}

	getAddTypePropertyModalElement(typeKey) {
		const type = this.getType(typeKey);
		const options = this.preparePropertySelectorOptions(type.properties, this.getPropertiesForType(type.type));

		return this.getAddPropertyModalElement(typeKey, options, sprintf(
			__('Choose the properties to insert into your %s type module.', 'wds'),
			type.label
		));
	}

	getAddNestedPropertyModalElement(typeKey, propertyId) {
		const type = this.getType(typeKey);
		const propertyKeys = this.propertyKeys(propertyId, type.properties);
		const targetProperty = this.getPropertyByKeys(propertyKeys, type.properties);
		const sourcePropertyKeys = this.prepareRepeatableSourcePropertyKeys(propertyKeys);
		const sourceProperty = this.getPropertyByKeys(sourcePropertyKeys, this.getPropertiesForType(type.type));
		const options = this.preparePropertySelectorOptions(targetProperty.properties, sourceProperty.properties);

		return this.getAddPropertyModalElement(typeKey, options, sprintf(
			__('Choose the properties to insert into the %s section of your %s schema.', 'wds'),
			sourceProperty.label,
			type.label
		));
	}

	getAddPropertyModalElement(typeKey, options, description) {
		return <BoxSelectorModal
			id="wds-add-property"
			title={__('Add Properties', 'wds')}
			description={description}
			actionButtonText={__('Add', 'wds')}
			actionButtonIcon="sui-icon-plus"
			onClose={() => this.stopAddingProperties()}
			onAction={(propertyIds) => this.handleAddPropertiesButtonClick(typeKey, propertyIds)}
			options={options}
			noOptionsMessage={
				<div className="wds-box-selector-message">
					<h3>{__('No properties to add', 'wds')}</h3>
					<p className="sui-description">{__('It seems that you have already added all the available properties.', 'wds')}</p>
				</div>
			}
			requiredNotice={this.getWarningElement(createInterpolateElement(
				__('You are missing properties that are required by Google ( <span>*</span> ). Make sure you include all of them so that your content will be eligible for display as a rich result. To learn more about schema type properties, see our <a>Schema Documentation</a>.'),
				{
					span: <span/>,
					a: <a
						target="_blank"
						href="https://wpmudev.com/docs/wpmu-dev-plugins/smartcrawl/#schema"/>,
				}
			))}
		/>;
	}

	getWarningElement(message) {
		return <div className="wds-missing-properties-notice sui-notice sui-notice-warning">
			<div className="sui-notice-content">
				<div className="sui-notice-message">
					<span className="sui-notice-icon sui-icon-warning-alert sui-md" aria-hidden="true"/>
					<p>{message}</p>
				</div>
			</div>
		</div>;
	}

	preparePropertySelectorOptions(typeProperties, sourceProperties) {
		const selectorOptions = [];
		Object.keys(sourceProperties).forEach((sourcePropertyKey) => {
			const sourceProperty = sourceProperties[sourcePropertyKey];

			if (!typeProperties.hasOwnProperty(sourcePropertyKey)) {
				selectorOptions.push({
					id: sourceProperty.id,
					label: sourceProperty.label,
					required: sourceProperty.required
				});
			}
		});

		return selectorOptions;
	}

	getConditionGroupElements(typeKey, conditions) {
		return conditions.map((conditionGroup, conditionGroupIndex) => {
				const firstCondition = first(conditionGroup);

				return <div key={'condition-group-' + firstCondition.id} className="wds-schema-type-condition-group">
					{conditionGroupIndex === 0 && <span>{__('Rule', 'wds')}</span>}
					{conditionGroupIndex !== 0 && <span>{__('Or', 'wds')}</span>}
					{this.getConditionElements(typeKey, conditionGroup, conditionGroupIndex)}
				</div>
			}
		);
	}

	getConditionElements(typeKey, conditionGroup, conditionGroupIndex) {
		return conditionGroup.map((condition, conditionIndex) =>
			<SchemaTypeCondition
				onChange={(id, lhs, operator, rhs) => this.updateCondition(typeKey, id, lhs, operator, rhs)}
				onAdd={(id) => this.addCondition(typeKey, id)}
				onDelete={(id) => this.deleteCondition(typeKey, id)}
				disableDelete={conditionGroupIndex === 0 && conditionIndex === 0}
				key={condition.id} id={condition.id}
				lhs={condition.lhs} operator={condition.operator}
				rhs={condition.rhs}/>
		);
	}

	isNestedProperty(property) {
		return property.properties;
	}

	getActiveAltVersion(property) {
		return property.properties[property.activeVersion];
	}

	hasAltVersions(property) {
		return this.isNestedProperty(property) && !!property.activeVersion && property.properties.hasOwnProperty(property.activeVersion);
	}

	getDefaultProperties(properties) {
		const defaultProperties = {};
		Object.keys(properties).forEach((propertyKey) => {
			const property = properties[propertyKey];
			if (!property.optional) {
				defaultProperties[propertyKey] = this.getDefaultProperty(property);
			}
		});

		return defaultProperties;
	}

	getDefaultProperty(property) {
		const args = [{}, property];
		if (this.isNestedProperty(property)) {
			args.push({
				properties: this.getDefaultProperties(property.properties)
			});
		}
		args.push({id: uniqueId()});
		return Object.assign({}, ...args);
	}

	cloneConditions(conditionGroups) {
		const clonedConditionGroup = [];
		conditionGroups.forEach((conditions) => {
			const clonedConditions = [];
			conditions.forEach((condition) => {
				clonedConditions.push(Object.assign(
					{},
					condition,
					{id: uniqueId()}
				));
			});
			clonedConditionGroup.push(clonedConditions);
		});
		return clonedConditionGroup;
	}

	initializeProperties(savedProperties, sourceProperties) {
		const initializedProperties = {};
		Object.keys(savedProperties).forEach((propertyKey) => {
			const savedProperty = savedProperties[propertyKey];
			const sourcePropertyKeys = this.prepareRepeatableSourcePropertyKeys(this.propertyKeys(savedProperty.id, savedProperties));
			const sourceProperty = this.getPropertyByKeys(sourcePropertyKeys, sourceProperties);
			initializedProperties[propertyKey] = this.initializeProperty(savedProperty, sourceProperty);
		});

		return initializedProperties;
	}

	initializeProperty(savedProperty, sourceProperty) {
		const args = [];
		args.push(sourceProperty);
		args.push({
			type: savedProperty.type,
			source: savedProperty.source,
			value: savedProperty.value
		});
		if (this.hasAltVersions(savedProperty)) {
			args.push({activeVersion: savedProperty.activeVersion});
		}
		if (this.isNestedProperty(savedProperty) && this.isNestedProperty(sourceProperty)) {
			args.push({
				properties: this.initializeProperties(savedProperty.properties, sourceProperty.properties)
			});
		}
		args.push({id: uniqueId()});

		return Object.assign({}, ...args);
	}

	cloneProperties(properties) {
		const clonedProperties = {};
		Object.keys(properties).forEach((propertyKey) => {
			clonedProperties[propertyKey] = this.cloneProperty(properties[propertyKey]);
		});

		return clonedProperties;
	}

	cloneProperty(property) {
		const args = [{}, property];
		if (this.isNestedProperty(property)) {
			args.push({
				properties: this.cloneProperties(property.properties)
			});
		}
		args.push({id: uniqueId()});
		return Object.assign({}, ...args);
	}

	startAddingSchemaType() {
		this.setState({
			addingSchemaTypes: true,
		});
	}

	stopAddingSchemaType() {
		this.removeAddTypeQueryVar();

		this.setState({
			addingSchemaTypes: false,
		});
	}

	handleAddSchemaTypesButtonClick(schemaType, label, conditions) {
		this.addSchemaType(schemaType, label, conditions)
			.then((typeKey) => {
				this.stopAddingSchemaType();
				this.showNotice(__('The type has been added. You need to save the changes to make them live.', 'wds'));
				this.showAfterAdditionNotice(schemaType);
				this.toggleType(typeKey);
			});
	}

	showInvalidTypesNotice() {
		let message = __('One or more properties that are required by Google have been removed. Please check your types and click on the <strong>Add Property</strong> button to see the missing <strong>required properties</strong> ( <span>*</span> ).');
		SUI.openNotice('wds-schema-types-invalid-notice', '<p>' + message + '</p>', {
			type: 'warning', icon: 'warning-alert', dismiss: {show: true}
		});
	}

	showAfterAdditionNotice(typeKey) {
		let message = SchemaTypes.getType(typeKey).afterAdditionNotice || false;
		if (!message) {
			return;
		}

		SUI.openNotice('wds-schema-types-local-business-notice', '<p>' + message + '</p>', {
			type: 'grey', icon: 'info', dismiss: {show: true}
		});
	}

	getAddSchemaModalElement() {
		return <AddSchemaTypeWizardModal
			onClose={() => this.stopAddingSchemaType()}
			onAdd={(type, label, conditions) => this.handleAddSchemaTypesButtonClick(type, label, conditions)}
		/>;
	}

	getSchemaTypeIcon(typeKey) {
		return SchemaTypes.getType(typeKey).icon;
	}

	generateTypeId(type) {
		return uniqueId(type + '-');
	}

	addSchemaType(type, label, conditions) {
		const spec = {};
		const typeKey = this.generateTypeId(type);

		spec[typeKey] = {
			$set: {
				label: label,
				type: type,
				version: this.getPluginVersion(),
				conditions: conditions,
				properties: this.getDefaultProperties(this.getPropertiesForType(type))
			}
		};

		return new Promise(resolve => {
			this.updateTypes(spec).then(() => resolve(typeKey));
		});
	}

	getPluginVersion() {
		return Config_Values.get('plugin_version', 'schema_types');
	}

	getType(typeKey) {
		return this.state.types[typeKey];
	}

	handleRepeatButtonClick(typeKey, propertyId) {
		this.repeatProperty(typeKey, propertyId);
		this.openAccordionItemForProperty(propertyId);

		const type = this.getType(typeKey);
		const propertyKeys = this.propertyKeys(propertyId, type.properties);
		const property = this.getPropertyByKeys(propertyKeys, type.properties);
		this.showNotice(sprintf(
			__('A new %s has been added.', 'wds'),
			property.hasOwnProperty('labelSingle')
				? property.labelSingle
				: property.label
		));
	}

	prepareRepeatableSourcePropertyKeys(propertyKeys) {
		const newKeys = [];
		propertyKeys.forEach(propertyKey => {
			if (parseInt(propertyKey) > 0) {
				// A numeric key indicates a repeatable and the source properties only have 0 as repeatable key
				newKeys.push('0');
			} else {
				newKeys.push(propertyKey);
			}
		});
		return newKeys;
	}

	repeatProperty(typeKey, propertyId) {
		const type = this.getType(typeKey);
		const propertyKeys = this.propertyKeys(propertyId, type.properties);
		const property = this.getPropertyByKeys(propertyKeys, type.properties);
		const sourcePropertyKeys = this.prepareRepeatableSourcePropertyKeys(propertyKeys);
		const sourceProperty = this.getPropertyByKeys(sourcePropertyKeys, this.getPropertiesForType(type.type));
		const repeatableKey = Object.keys(sourceProperty.properties).shift();
		const repeatable = sourceProperty.properties[repeatableKey];
		const newKey = Math.max(...Object.keys(property.properties)) + 1;

		let cloned = this.getDefaultProperty(repeatable);
		if (repeatable.disallowDeletion && repeatable.disallowFirstItemDeletionOnly) {
			delete cloned.disallowDeletion;
		}

		if (repeatable.updateLabelNumber && repeatable.label) {
			cloned.label = repeatable.label.replace('1', newKey + 1);
		}

		const spec = this.formatSpec([typeKey, 'properties', ...propertyKeys, 'properties', newKey], {
			$set: cloned
		});

		this.updateTypes(spec);
	}

	startAddingNestedProperties(typeKey, propertyId) {
		this.openAccordionItemForProperty(propertyId);

		this.setState({
			addingNestedForProperty: propertyId,
		});
	}

	getTypeAccordionItemClassName(typeKey) {
		return 'wds-schema-type-' + typeKey + '-accordion';
	}

	getPropertyAccordionItemClassName(propertyId) {
		return 'wds-schema-property-' + propertyId + '-accordion';
	}

	openAccordionItemForProperty(propertyId) {
		const className = this.getPropertyAccordionItemClassName(propertyId);
		$('.' + className).addClass('sui-accordion-item--open');
	}

	nestedPropertyHasMissingRequiredProperties(typeKey, property) {
		if (
			!this.isNestedProperty(property)
			|| !property.required
			// We know that nested properties are not going to be required if the parent property itself is not required 
			// An exception to this rule is local business -> review -> author but that doesn't matter because it is inside a repeatable and is always valid
		) {
			return false;
		}

		const type = this.getType(typeKey);
		const propertyKeys = this.propertyKeys(property.id, type.properties);
		const sourceKeys = this.prepareRepeatableSourcePropertyKeys(propertyKeys);
		const sourceProperty = this.getPropertyByKeys(sourceKeys, this.getPropertiesForType(type.type));

		return this.requiredPropertiesMissing(
			property.properties,
			sourceProperty.properties
		);
	}

	startResettingProperties(typeKey) {
		this.setState({
			resettingProperties: typeKey
		});
	}

	getPropertyResetModalElement(typeKey) {
		return <Modal small={true}
					  id="wds-confirm-property-reset"
					  title={__('Are you sure?', 'wds')}
					  onClose={() => this.stopResettingProperties()}
					  focusAfterOpen="wds-schema-property-reset-button"
					  description={__('Are you sure you want to dismiss your changes and turn back your properties list to default?', 'wds')}>

			<Button text={__('Cancel', 'wds')}
					onClick={() => this.stopResettingProperties()}
					ghost={true}
			/>

			<Button text={__('Reset Properties', 'wds')}
					onClick={() => this.resetProperties(typeKey)}
					icon="sui-icon-refresh"
					id="wds-schema-property-reset-button"
			/>
		</Modal>;
	}

	resetProperties(typeKey) {
		const type = this.getType(typeKey);
		const spec = this.formatSpec([typeKey, 'properties'], {
			$set: this.getDefaultProperties(this.getPropertiesForType(type.type))
		});

		this.updateTypes(spec).then(() => {
			this.showNotice(__('Properties have been reset to default', 'wds'));
			this.stopResettingProperties();
			this.checkTypeValidity(typeKey);
		});
	}

	stopResettingProperties() {
		this.setState({
			resettingProperties: ''
		});
	}

	startRenamingType(typeKey) {
		this.setState({
			renamingType: typeKey,
		});
	}

	stopRenamingType() {
		this.setState({
			renamingType: '',
		});
	}

	renameType(typeKey, newName) {
		const spec = this.formatSpec([typeKey, 'label'], {
			$set: newName
		});
		this.updateTypes(spec).then(() => {
			this.showNotice(__('The type has been renamed.', 'wds'));
			this.stopRenamingType();
		});
	}

	getTypeRenameModalElement(typeKey) {
		let replacementNotice = SchemaTypes.getType(this.getType(typeKey).type).schemaReplacementNotice;

		return <SchemaTypeRenameModal
			name={this.getType(typeKey).label}
			notice={
				replacementNotice
					? <Notice type="info" message={replacementNotice}/>
					: false
			}
			onRename={(newName) => this.renameType(typeKey, newName)}
			onClose={() => this.stopRenamingType()}
		/>;
	}

	startDeletingType(typeKey) {
		this.setState({
			deletingType: typeKey
		});
	}

	stopDeletingType() {
		this.setState({
			deletingType: ''
		});
	}

	deleteType(typeKey) {
		return this.updateTypes({
			$unset: [typeKey]
		});
	}

	getTypeDeleteModalElement(typeKey) {
		return <Modal small={true}
					  id="wds-confirm-type-deletion"
					  title={__('Are you sure?', 'wds')}
					  onClose={() => this.stopDeletingType()}
					  focusAfterOpen="wds-schema-type-delete-button"
					  description={__('Are you sure you wish to delete this schema type? You can add it again anytime.', 'wds')}>

			<Button text={__('Cancel', 'wds')}
					onClick={() => this.stopDeletingType()}
					ghost={true}
			/>

			<Button text={__('Delete', 'wds')}
					onClick={() => this.handleTypeDeleteButtonClick(typeKey)}
					icon="sui-icon-trash"
					color="red"
					id="wds-schema-type-delete-button"
			/>
		</Modal>;
	}

	handleTypeDeleteButtonClick(typeKey) {
		this.deleteType(typeKey).then(() => {
			this.showNotice(__('The type has been removed. You need to save the changes to make them live.', 'wds'), 'info');
			this.stopDeletingType();
			this.setTypeInvalid(typeKey, false);
		});
	}

	showNotice(message, type = 'success', dismiss = false) {
		const icons = {
			error: 'warning-alert',
			info: 'info',
			warning: 'warning-alert',
			success: 'check-tick'
		};

		SUI.openNotice('wds-schema-types-notice', '<p>' + message + '</p>', {
			type: type,
			icon: icons[type],
			dismiss: {show: dismiss}
		});
	}

	getStateInput() {
		return <input type="hidden" name="wds-schema-types" value={JSON.stringify(this.state.types)}/>;
	}

	startChangingPropertyType(propertyId) {
		this.setState({changingPropertyTypeForId: propertyId});
	}

	stopChangingPropertyType() {
		this.setState({changingPropertyTypeForId: 0});
	}

	getPropertyTypeChangeModalElement(typeKey, propertyId) {
		const type = this.getType(typeKey);
		const parentProperty = this.getPropertyParent(propertyId, type.properties);
		let options = this.getAltVersionTypes(parentProperty);
		if (options) {
			options = options.filter((altVersion) => {
				return parentProperty.activeVersion !== altVersion.id;
			});
		}

		return <BoxSelectorModal
			id="wds-change-property-type"
			title={__('Change Property Type', 'wds')}
			description={__('Select one of the following types to switch.', 'wds')}
			actionButtonText={__('Change', 'wds')}
			actionButtonIcon="sui-icon-defer"
			onClose={() => this.stopChangingPropertyType()}
			onAction={(selectedType) => this.handlePropertyTypeChange(typeKey, parentProperty, selectedType)}
			options={options}
			multiple={false}
		/>;
	}

	handlePropertyTypeChange(schemaTypeKey, parentProperty, selectedPropertyTypes) {
		if (!selectedPropertyTypes.length) {
			return;
		}

		const selectedPropertyType = selectedPropertyTypes[0];
		const type = this.getType(schemaTypeKey);
		const propertyKeys = this.propertyKeys(parentProperty.id, type.properties);
		const sourcePropertyKeys = this.prepareRepeatableSourcePropertyKeys(propertyKeys);
		const property = this.getPropertyByKeys(sourcePropertyKeys, this.getPropertiesForType(type.type));
		const versions = this.getDefaultProperties(property.properties);
		const spec = this.formatSpec([schemaTypeKey, 'properties', ...propertyKeys], {
			activeVersion: {$set: selectedPropertyType},
			properties: {$set: versions},
		});

		this.updateTypes(spec).then(() => {
			const altVersion = this.getPropertyByKeys([selectedPropertyType], versions);
			this.openAccordionItemForProperty(altVersion.id);
			this.showNotice(sprintf(__('Property type has been changed to %s', 'wds'), selectedPropertyType));
			this.checkTypeValidity(schemaTypeKey);
		});
	}

	getPropertyParent(childId, properties) {
		const propertyKeys = this.propertyKeys(childId, properties);
		propertyKeys.pop(); // child key
		propertyKeys.pop(); // 'properties'
		return this.getPropertyByKeys(propertyKeys, properties);
	}

	getPropertyById(propertyId, properties) {
		const propertyKeys = this.propertyKeys(propertyId, properties);
		return this.getPropertyByKeys(propertyKeys, properties);
	}

	getAltVersionTypes(property) {
		if (!this.hasAltVersions(property)) {
			return false;
		}

		const types = [];
		Object.keys(property.properties).forEach((type) => {
			const altVersion = property.properties[type];

			types.push({
				id: type,
				label: altVersion.label
			});
		});

		return types;
	}

	duplicateType(typeKey) {
		const spec = {};
		const type = this.getType(typeKey);
		const typeId = this.generateTypeId(type.type);
		const properties = this.cloneProperties(type.properties);
		const conditions = this.cloneConditions(type.conditions);

		spec[typeId] = {
			$set: {
				label: type.label,
				type: type.type,
				version: type.version || false,
				conditions: conditions,
				properties: properties
			}
		};

		this.updateTypes(spec).then(() => {
			this.checkTypeValidity(typeId);
			this.showNotice(__('The type has been duplicated successfully.', 'wds'));
		});
	}

	removeAddTypeQueryVar() {
		UrlUtil.removeQueryParam('add_type');
	}

	maybeStartAddingSchemaType() {
		if (UrlUtil.getQueryParam('add_type') === '1') {
			this.startAddingSchemaType();
		}
	}

	getTypeSubText(typeKey) {
		return SchemaTypes.getType(typeKey).subText || '';
	}
}
