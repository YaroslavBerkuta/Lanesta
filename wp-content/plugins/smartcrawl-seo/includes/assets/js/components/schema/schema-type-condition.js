import React from 'react';
import {__} from '@wordpress/i18n';
import Config_Values from "../../es6/config-values";

import Select from "../select";
import SchemaTypeConditionOperator from "./schema-type-condition-operator";
import $ from 'jQuery';

export default class SchemaTypeCondition extends React.Component {
	constructor(props) {
		super(props);

		this.props = props;
	}

	render() {
		const lhs = this.props.lhs,
			operator = this.props.operator,
			rhs = this.props.rhs,
			lhsOptions = this.getLhsSelectOptions(),
			rhsProps = this.getRhsSelectProps(lhs);

		return <div className="wds-schema-type-condition">
			<div className="wds-schema-type-condition-lhs">
				<Select options={lhsOptions}
						selectedValue={lhs}
						minimumResultsForSearch="-1"
						templateResult={state => this.addLhsOptionMarkup(state)}
						templateSelection={state => this.addLhsOptionMarkup(state)}
						onSelect={lhs => this.handleLhsChange(lhs)}
				/>
			</div>

			{
				this.objectNotEmpty(rhsProps) &&
				<SchemaTypeConditionOperator operator={operator}
											 onChange={operator => this.handleOperatorChange(operator)}/>
			}

			{
				this.objectNotEmpty(rhsProps) &&
				<div className="wds-schema-type-condition-rhs" key={`${lhs}-options`}>
					<Select {...rhsProps}
							selectedValue={rhs}
							onSelect={rhs => this.handleRhsChange(rhs)}
					/>
				</div>
			}

			<div className="wds-schema-type-condition-and">
				<button role="button"
						onClick={e => this.handleAdd(e)}
						className="sui-button sui-button-ghost sui-tooltip sui-tooltip-constrained"
						style={{"--tooltip-width": "200px;"}}
						data-tooltip={__('Add a new rule conditioning the previous one.', 'wds')}>
					{__('AND', 'wds')}
				</button>
			</div>

			{
				!this.props.disableDelete &&
				<span className="wds-schema-type-condition-close" onClick={e => this.handleDelete(e)}>
					<span className="sui-icon-cross-close" aria-hidden="true"/>
				</span>
			}
		</div>;
	}

	addLhsOptionMarkup(state) {
		if (!state.id) {
			return state.text;
		}

		const taxonomies = this.getTaxonomies();
		const postTypes = this.getPostTypes();
		if (
			(taxonomies && taxonomies.hasOwnProperty(state.id))
			|| (postTypes && postTypes.hasOwnProperty(state.id))
		) {
			return $('<span>' + state.text + ' <span class="sui-tag sui-tag-sm sui-tag-disabled">' + state.id + '</span></span>');
		} else {
			return state.text;
		}
	}

	getRhsSelectProps(lhs) {
		const searchProps = {};

		const postTypes = this.getPostTypes();
		if (this.objectNotEmpty(postTypes)) {
			Object.keys(postTypes).forEach((postType) => {
				searchProps[postType] = this.searchSelectProps(
					sprintf(__('Search for %s', 'wds'), postTypes[postType]),
					postType,
					'wds-search-post'
				);
			});
		}

		const taxonomies = this.getTaxonomies();
		if (this.objectNotEmpty(taxonomies)) {
			Object.keys(taxonomies).forEach((taxonomy) => {
				searchProps[taxonomy] = this.searchSelectProps(
					sprintf(__('Search for %s', 'wds'), taxonomies[taxonomy]),
					taxonomy,
					'wds-search-schema-term'
				);
			});
		}

		if (searchProps.hasOwnProperty(lhs)) {
			return searchProps[lhs];
		}

		let selectOptions = this.getRhsSelectOptions(lhs);
		if (selectOptions) {
			return {options: selectOptions};
		}

		return {};
	}

	searchSelectProps(placeholder, entity_type, ajax_action) {
		let ajaxURL = Config_Values.get('ajax_url', 'schema_types');
		let params = new URLSearchParams();

		params.append('action', ajax_action);
		params.append('type', entity_type);

		const props = {
			placeholder: placeholder,
			ajaxUrl: ajaxURL + '?' + params.toString(),
			options: {}
		};

		params.append('request_type', 'text');
		props['loadTextAjaxUrl'] = ajaxURL + '?' + params.toString();

		return props;
	}

	getRhsSelectOptions(lhs) {
		const options = {
			post_type: this.getPostTypes(),
			author_role: this.getUserRoles(),
			post_format: this.getPostFormats(),
			page_template: this.getPageTemplates(),
			product_type: {
				WC_Product_Variable: __('Variable Product', 'wds'),
				WC_Product_Simple: __('Simple Product', 'wds'),
				WC_Product_Grouped: __('Grouped Product', 'wds'),
				WC_Product_External: __('External Product', 'wds'),
			}
		};

		return options.hasOwnProperty(lhs)
			? options[lhs]
			: false;
	}

	getLhsSelectOptions() {
		const lhsOptions = {
			post_type: __('Post Type', 'wds'),
			show_globally: __('Show Globally', 'wds'),
			homepage: __('Homepage', 'wds'),
			author_role: __('Post Author Role', 'wds'),
		};

		let postFormats = this.getPostFormats();
		if (this.objectNotEmpty(postFormats)) {
			lhsOptions['post_format'] = __('Post Format', 'wds');
		}

		let pageTemplates = this.getPageTemplates();
		if (this.objectNotEmpty(pageTemplates)) {
			lhsOptions['page_template'] = __('Page Template', 'wds');
		}

		if (this.isWooCommerceActive()) {
			lhsOptions['product_type'] = __('Product Type', 'wds');
		}

		const postTypeTaxonomies = this.getPostTypeTaxonomies();
		if (this.objectNotEmpty(postTypeTaxonomies)) {
			Object.keys(postTypeTaxonomies).forEach((postType) => {
				lhsOptions[postType] = postTypeTaxonomies[postType];
			});
		}

		return lhsOptions;
	}

	isWooCommerceActive() {
		return !!Config_Values.get('woocommerce', 'schema_types');
	}

	getUserRoles() {
		return Config_Values.get('user_roles', 'schema_types') || {};
	}

	getPostTypes() {
		return Config_Values.get('post_types', 'schema_types') || {};
	}

	getPostTypeTaxonomies() {
		return Config_Values.get('post_type_taxonomies', 'schema_types') || {};
	}

	getTaxonomies() {
		return Config_Values.get('taxonomies', 'schema_types') || {};
	}

	getPageTemplates() {
		return Config_Values.get('page_templates', 'schema_types') || {};
	}

	getPostFormats() {
		return Config_Values.get('post_formats', 'schema_types') || {};
	}

	objectLength(object) {
		return Object.keys(object).length;
	}

	objectNotEmpty(object) {
		return !!this.objectLength(object);
	}

	handleLhsChange(lhs) {
		let rhs = '';
		const rhsOptions = this.getRhsSelectOptions(lhs);
		if (rhsOptions) {
			rhs = Object.keys(rhsOptions).shift();
		}

		this.props.onChange(this.props.id, lhs, this.props.operator, rhs);
	}

	handleOperatorChange(operator) {
		this.props.onChange(this.props.id, this.props.lhs, operator, this.props.rhs);
	}

	handleRhsChange(rhs) {
		this.props.onChange(this.props.id, this.props.lhs, this.props.operator, rhs);
	}

	handleAdd(e) {
		e.preventDefault();

		this.props.onAdd(this.props.id);
	}

	handleDelete() {
		this.props.onDelete(this.props.id);
	}
}
