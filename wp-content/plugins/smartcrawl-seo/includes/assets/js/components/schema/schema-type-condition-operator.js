import React from 'react';
import {__} from '@wordpress/i18n';

export default class SchemaTypeConditionOperator extends React.Component {
	constructor(props) {
		super(props);

		this.props = props;
	}

	handleChange(e) {
		this.props.onChange(e.target.checked ? '=' : '!=');
	}

	render() {
		return <div className="wds-schema-type-condition-operator">
			<div className="wds-comparison-operator sui-tooltip sui-tooltip-constrained"
				 style={{"--tooltip-width": "200px"}}
				 data-tooltip={__('Switch your condition rule between equal or unequal.', 'wds')}>
				<label>
					<input checked={this.props.operator === '='}
						   onChange={e => this.handleChange(e)}
						   type="checkbox"/>

					<span className="wds-equals">&#61;</span>
					<span className="wds-not-equals">&#8800;</span>
				</label>
			</div>
		</div>;
	}
}
