import React from "react";
import classnames from "classnames";

export default class TextField extends React.Component {
	static defaultProps = {
		id: '',
		label: '',
		description: '',
		value: '',
		isValid: true,
		placeholder: '',
		onChange: () => false,
	};

	constructor(props) {
		super(props);
	}

	render() {
		return <div className={classnames('sui-form-field', {
			'sui-form-field-error': !this.props.isValid
		})}>
			<label className="sui-label">{this.props.label}</label>
			<input id={this.props.id}
				   type="text"
				   className="sui-form-control"
				   onChange={(e) => this.props.onChange(e.target.value)}
				   value={this.props.value}
				   placeholder={this.props.placeholder}/>

			{!!this.props.description &&
			<p className="sui-description">
				<small>{this.props.description}</small>
			</p>
			}
		</div>;
	}
}
