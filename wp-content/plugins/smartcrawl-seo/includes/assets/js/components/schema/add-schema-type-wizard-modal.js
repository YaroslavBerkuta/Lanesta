import React from 'react';
import {first, last, uniqueId} from "lodash-es";
import Modal from "../modal";
import {__, sprintf} from "@wordpress/i18n";
import BoxSelector from "../box-selector";
import Button from "../button";
import SchemaTypeCondition from "./schema-type-condition";
import cloneDeep from "lodash-es/cloneDeep";
import {createInterpolateElement} from '@wordpress/element';
import SchemaTypes from "./schema-types";

export default class AddSchemaTypeWizardModal extends React.Component {
	static defaultProps = {
		onClose: () => false,
		onAdd: () => false,
	};

	constructor(props) {
		super(props);

		this.MODAL_STATE = {
			TYPE: 'type',
			LABEL: 'label',
			CONDITION: 'condition',
		};

		this.state = {
			modalState: this.MODAL_STATE.TYPE,
			selectedTypes: [],
			addedTypes: [],
			typeLabel: '',
			searchTerm: '',
			typeConditions: []
		};
	}

	switchModalState(newModalState) {
		this.setState({
			modalState: newModalState
		});
	}

	isStateType() {
		return this.state.modalState === this.MODAL_STATE.TYPE;
	}

	isStateLabel() {
		return this.state.modalState === this.MODAL_STATE.LABEL;
	}

	isStateCondition() {
		return this.state.modalState === this.MODAL_STATE.CONDITION;
	}

	switchToType() {
		this.switchModalState(this.MODAL_STATE.TYPE);
	}

	switchToLabel() {
		this.switchModalState(this.MODAL_STATE.LABEL);
	}

	switchToCondition() {
		this.switchModalState(this.MODAL_STATE.CONDITION);
	}

	clearSearchTerm() {
		this.setState({
			searchTerm: ''
		});
	}

	setSearchTerm(searchTerm) {
		this.setState({
			searchTerm: searchTerm
		});
	}

	handleNextButtonClick() {
		if (this.isStateType()) {
			if (this.hasSubTypeOptions()) {
				this.loadSubTypes();
			} else {
				this.setNewLabel(this.getDefaultTypeLabel());
				this.switchToLabel();
			}
		} else if (this.isStateLabel()) {
			this.setDefaultCondition(this.getTypeToAdd());
			this.switchToCondition();
		} else {
			this.addType();
		}
	}

	handleBackButtonClick() {
		this.clearSearchTerm();
		if (this.isStateType()) {
			if (this.typesAdded()) {
				this.loadPreviousTypes();
			} else {
				this.props.onClose();
			}
		} else if (this.isStateLabel()) {
			this.switchToType();
		} else {
			this.switchToLabel();
		}
	}

	getTypeToAdd() {
		let selected = false;
		if (this.state.selectedTypes.length) {
			selected = first(this.state.selectedTypes);
		} else if (this.typesAdded()) {
			selected = last(this.state.addedTypes);
		}

		return selected;
	}

	addType() {
		const defaultValue = this.getDefaultTypeLabel();

		this.props.onAdd(
			this.getTypeToAdd(),
			this.state.typeLabel.trim() || defaultValue,
			this.state.typeConditions
		);
	}

	loadSubTypes() {
		const selectedTypes = this.state.selectedTypes;
		const addedTypes = this.state.addedTypes.slice();

		addedTypes.push(
			first(selectedTypes)
		);

		this.setState({
			selectedTypes: [],
			addedTypes: addedTypes
		});
	}

	hasSubTypeOptions() {
		const selectedTypes = this.state.selectedTypes;
		if (!selectedTypes.length) {
			return false;
		}

		return !!this.getSubTypes(first(selectedTypes));
	}

	loadPreviousTypes() {
		const addedTypes = this.state.addedTypes.slice();
		const popped = addedTypes.pop();

		this.setState({
			selectedTypes: [popped],
			addedTypes: addedTypes
		});
	}

	getOptions() {
		const addedTypes = this.state.addedTypes;
		let typeKeys;
		if (addedTypes.length) {
			typeKeys = this.getSubTypes(last(addedTypes));
			if (!typeKeys) {
				return [];
			}
		} else {
			typeKeys = SchemaTypes.getTopLevelTypeKeys();
		}

		return this.buildOptionsFromTypes(typeKeys);
	}

	getSubTypes(typeKey) {
		const type = SchemaTypes.getType(typeKey);
		return type.children.length
			? type.children
			: false;
	}

	buildOptionsFromTypes(typeKeys) {
		const options = [];
		typeKeys.forEach((typeKey) => {
			const type = SchemaTypes.getType(typeKey);
			if (
				!type.hidden &&
				(this.state.searchTerm.trim() === '' || this.typeOrSubtypeMatchesSearch(typeKey))
			) {
				options.push({
					id: typeKey,
					label: type.label,
					icon: type.icon,
					disabled: !!type.disabled
				});
			}
		});
		return options;
	}

	getTypeSection() {
		const options = this.getOptions();

		return <React.Fragment>
			{this.breadcrumbs()}

			{this.typesAdded() &&
			<div id="wds-search-sub-types">
				<div className="sui-control-with-icon">
					<span className="sui-icon-magnifying-glass-search"
						  aria-hidden="true"/>
					<input type="text"
						   placeholder={__('Search subtypes', 'wds')}
						   className="sui-form-control"
						   value={this.state.searchTerm}
						   onChange={e => this.setSearchTerm(e.target.value)}/>
				</div>
			</div>
			}

			<BoxSelector id="wds-add-schema-type-selector"
						 options={options}
						 selectedValues={this.state.selectedTypes}
						 multiple={false}
						 cols={3}
						 onChange={(items) => this.handleSelection(items)}
			/>

		</React.Fragment>;
	}

	setNewLabel(label) {
		this.setState({
			typeLabel: label
		});
	}

	getLabelSection() {
		const placeholder = this.getDefaultTypeLabel();

		return <div id="wds-add-schema-type-label">
			<div className="sui-form-field">
				<label className="sui-label">
					{__('Type Name', 'wds')}
				</label>

				<input className="sui-form-control"
					   onChange={e => this.setNewLabel(e.target.value)}
					   placeholder={placeholder}
					   value={this.state.typeLabel}/>
			</div>
		</div>;
	}

	handleSelection(selectedTypes) {
		this.setState({selectedTypes: selectedTypes});
	}

	getSubTypesNotice(typeKey) {
		const type = SchemaTypes.getType(typeKey);
		if (!type.children.length) {
			// No subtypes so no notice
			return '';
		}

		return type.subTypesNotice || '';
	}

	getTypeLabel(typeKey) {
		return SchemaTypes.getType(typeKey).label;
	}

	getTypeLabelFull(typeKey) {
		const type = SchemaTypes.getType(typeKey);

		return type.labelFull || type.label;
	}

	breadcrumbs() {
		const types = this.state.addedTypes.slice();
		const selectedTypes = this.state.selectedTypes;

		if (selectedTypes.length) {
			types.push(first(selectedTypes));
		}

		if (types.length) {
			return <div id="wds-add-schema-type-breadcrumbs">
				{types.map((type) =>
					<span key={type}>
						{this.getTypeLabelFull(type)}
						<span className="sui-icon-chevron-right"
							  aria-hidden="true"/>
					</span>
				)}
			</div>;
		}
	}

	isNextButtonDisabled() {
		if (this.isStateType()) {
			return !this.state.selectedTypes.length
				&& !this.typesAdded();
		} else {
			return false;
		}
	}

	typesAdded() {
		return !!this.state.addedTypes.length;
	}

	getModalTitle() {
		if (this.isStateType() && this.typesAdded()) {
			return <React.Fragment>
				<span className="sui-tag sui-tag-sm sui-tag-blue">{__('Optional', 'wds')}</span>
				<br/>
				{__('Select Sub Type', 'wds')}
			</React.Fragment>;
		} else {
			return __('Add Schema Type', 'wds')
		}
	}

	getModalDescription() {
		if (this.isStateType()) {
			if (this.typesAdded()) {
				const selected = last(this.state.addedTypes);
				return <React.Fragment>
					{sprintf(
						__('You can specify a subtype of %s, or you can skip this to add the generic type.', 'wds'),
						this.getTypeLabel(selected)
					)}
					<br/>
					{this.getSubTypesNotice(selected)}
				</React.Fragment>;
			} else {
				return __('Start by selecting the schema type you want to use. By default, all of the types will include the properties required and recommended by Google.', 'wds');
			}
		} else if (this.isStateLabel()) {
			return sprintf(
				__('Name your %s type so you can easily identify it.', 'wds'),
				this.getDefaultTypeLabel()
			);
		} else {
			return __('Create a set of rules to determine where this schema type will be enabled or excluded.', 'wds');
		}
	}

	getDefaultTypeLabel() {
		return this.getTypeLabel(this.getTypeToAdd());
	}

	getConditionSection() {
		const conditions = this.state.typeConditions;
		const typeKey = this.getTypeToAdd();

		return <div id="wds-add-schema-type-conditions">
			{this.getConditionGroupElements(typeKey, conditions)}

			<Button text={__('Add Rule (Or)', 'wds')}
					ghost={true}
					onClick={() => this.addGroup(typeKey)}
					icon="sui-icon-plus"/>
		</div>;
	}

	addGroup(typeKey) {
		const updatedConditions = cloneDeep(this.state.typeConditions);
		updatedConditions.push([this.getDefaultCondition(typeKey)]);

		this.setState({
			typeConditions: updatedConditions
		});
	}

	setDefaultCondition(type) {
		const defaultCondition = this.getDefaultCondition(type);

		this.setState({
			typeConditions: [[defaultCondition]]
		});
	}

	getDefaultCondition(typeKey) {
		const type = SchemaTypes.getType(typeKey);
		const fallback = {id: uniqueId(), lhs: 'post_type', operator: '=', rhs: 'post'};

		return type.condition
			? Object.assign({}, type.condition, {id: uniqueId()})
			: fallback;
	}

	getConditionGroupElements(typeKey, conditions) {
		return conditions.map((conditionGroup, conditionGroupIndex) => {
			const firstCondition = first(conditionGroup);

			return <div key={firstCondition.id} className="wds-schema-type-condition-group">
				{conditionGroupIndex === 0 && <span>{__('Rule', 'wds')}</span>}
				{conditionGroupIndex !== 0 && <span>{__('Or', 'wds')}</span>}
				{this.getConditionElements(typeKey, conditionGroup, conditionGroupIndex)}
			</div>;
		});
	}

	getConditionElements(typeKey, conditionGroup, conditionGroupIndex) {
		return conditionGroup.map((condition, conditionIndex) =>
			<SchemaTypeCondition
				onChange={(id, lhs, operator, rhs) => this.updateCondition(id, lhs, operator, rhs)}
				onAdd={(id) => this.addCondition(typeKey, id)}
				onDelete={(id) => this.deleteCondition(id)}
				disableDelete={conditionGroupIndex === 0 && conditionIndex === 0}
				key={condition.id} id={condition.id}
				lhs={condition.lhs} operator={condition.operator}
				rhs={condition.rhs}/>
		);
	}

	updateCondition(id, lhs, operator, rhs) {
		const updatedConditions = cloneDeep(this.state.typeConditions);
		const groupIndex = this.conditionGroupIndex(updatedConditions, id);
		const conditionIndex = this.conditionIndex(updatedConditions[groupIndex], id);

		updatedConditions[groupIndex][conditionIndex].lhs = lhs;
		updatedConditions[groupIndex][conditionIndex].operator = operator;
		updatedConditions[groupIndex][conditionIndex].rhs = rhs;

		this.setState({
			typeConditions: updatedConditions
		});
	}

	addCondition(typeKey, id) {
		const updatedConditions = cloneDeep(this.state.typeConditions);
		const groupIndex = this.conditionGroupIndex(updatedConditions, id);
		const conditionIndex = this.conditionIndex(updatedConditions[groupIndex], id);
		const newConditionIndex = conditionIndex + 1;
		const defaultCondition = this.getDefaultCondition(typeKey);

		updatedConditions[groupIndex].splice(newConditionIndex, 0, defaultCondition);

		this.setState({
			typeConditions: updatedConditions
		});
	}

	deleteCondition(id) {
		const updatedConditions = cloneDeep(this.state.typeConditions);
		const groupIndex = this.conditionGroupIndex(updatedConditions, id);
		const group = updatedConditions[groupIndex];
		if (group.length === 1) {
			updatedConditions.splice(groupIndex, 1);
		} else {
			const conditionIndex = this.conditionIndex(group, id);
			updatedConditions[groupIndex].splice(conditionIndex, 1);
		}

		this.setState({
			typeConditions: updatedConditions
		});
	}

	conditionGroupIndex(conditions, id) {
		return conditions.findIndex(conditions => this.conditionIndex(conditions, id) > -1);
	}

	conditionIndex(conditions, id) {
		return conditions.findIndex(condition => condition.id === id);
	}

	stringIncludesSubstring(string, subString) {
		return string.toLowerCase().includes(subString.toLowerCase());
	}

	typeMatchesSearch(typeKey) {
		const typeMatches = this.stringIncludesSubstring(typeKey, this.state.searchTerm);
		if (typeMatches) {
			return true;
		}

		return this.stringIncludesSubstring(this.getTypeLabel(typeKey), this.state.searchTerm);
	}

	typeOrSubtypeMatchesSearch(typeKey) {
		if (this.typeMatchesSearch(typeKey)) {
			return true;
		}

		const subTypeKeys = this.getSubTypes(typeKey);

		if (!subTypeKeys) {
			return false;
		} else {
			let subtypeMatched = false;
			subTypeKeys.forEach(subTypeKey => {
				if (this.typeMatchesSearch(subTypeKey)) {
					subtypeMatched = true;
				}
			});

			return subtypeMatched;
		}
	}

	render() {
		return <Modal id="wds-add-schema-type-modal"
					  title={this.getModalTitle()}
					  onClose={() => this.props.onClose()}
					  small={true}
					  dialogClasses={{
						  'sui-modal-lg': true,
						  'sui-modal-sm': false
					  }}
					  description={this.getModalDescription()}>

			{this.isStateType() && this.getTypeSection()}
			{this.isStateLabel() && this.getLabelSection()}
			{this.isStateCondition() && this.getConditionSection()}

			<div style={{
				display: "flex",
				justifyContent: "space-between"
			}}>
				<Button text={__('Back', 'wds')}
						icon="sui-icon-arrow-left"
						id="wds-add-schema-type-back-button"
						onClick={() => this.handleBackButtonClick()}
						ghost={true}
				/>

				{!this.isStateCondition() &&
				<Button text={__('Continue', 'wds')}
						icon="sui-icon-arrow-right"
						id="wds-add-schema-type-action-button"
						onClick={() => this.handleNextButtonClick()}
						disabled={this.isNextButtonDisabled()}
				/>
				}

				{this.isStateCondition() &&
				<Button text={__('Add', 'wds')}
						icon="sui-icon-plus"
						id="wds-add-schema-type-action-button"
						color="blue"
						onClick={() => this.handleNextButtonClick()}
				/>
				}
			</div>
		</Modal>
	}
}
