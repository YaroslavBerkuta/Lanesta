import React from 'react';
import classnames from "classnames";
import {__, sprintf} from "@wordpress/i18n";

export default class SchemaPropertyAccordionHeader extends React.Component {
	static defaultProps = {
		id: '',
		label: '',
		description: '',
		required: false,
		requiredNotice: '',
		isAnAltVersion: false,
		labelSingle: '',
		isRepeatable: false,
		disallowDeletion: false,
		methods: {},
	};

	render() {
		const requiredNotice = this.props.requiredNotice
			? this.props.requiredNotice
			: __('This property is required by Google.', 'wds');

		return <div className="sui-accordion-item-header">
			<div className="sui-accordion-item-title">
				<span className={classnames({'sui-tooltip sui-tooltip-constrained': !!this.props.description})}
					  style={{"--tooltip-width": "300px"}}
					  data-tooltip={this.props.description}>
					{this.props.label}
				</span>

				{this.props.required &&
				<span className="wds-required-asterisk sui-tooltip sui-tooltip-constrained"
					  data-tooltip={requiredNotice}>*</span>
				}

				{this.props.methods.requiredNestedPropertiesMissing(this.props) &&
				<span className="sui-tooltip sui-tooltip-constrained"
					  data-tooltip={__('This section has missing properties that are required by Google.', 'wds')}>
					<span className="wds-invalid-type-icon sui-icon-warning-alert sui-md"
						  aria-hidden="true"/>
				</span>
				}
			</div>

			<div className="sui-accordion-col-auto">
				{this.props.isAnAltVersion &&
				<div className="sui-accordion-item-action">
					<button onClick={() => this.props.methods.onChangeActiveVersion(this.props.id)}
							data-tooltip={__('Change the type of this property', 'wds')}
							type="button"
							className="sui-button-icon sui-tooltip">
						<span className="sui-icon-defer" aria-hidden="true"/>
					</button>
				</div>
				}

				{this.props.isRepeatable &&
				<div className="sui-accordion-item-action">
					<button onClick={() => this.props.methods.onRepeat(this.props.id)}
							type="button"
							data-tooltip={sprintf(
								__('Add another %s', 'wds'),
								this.props.labelSingle || this.props.label
							)}
							className="sui-button-icon sui-tooltip">
						<span className="sui-icon-plus" aria-hidden="true"/>
					</button>
				</div>
				}

				{!this.props.disallowDeletion &&
				<div className="sui-accordion-item-action wds-delete-accordion-item-action">
					<span className="sui-icon-trash"
						  onClick={() => this.props.methods.onDelete(this.props.id)}
						  aria-hidden="true"/>
				</div>
				}

				<button className="sui-button-icon sui-accordion-open-indicator"
						type="button"
						aria-label={__('Open item', 'wds')}>
					<span className="sui-icon-chevron-down" aria-hidden="true"/>
				</button>
			</div>
		</div>;
	}
}
