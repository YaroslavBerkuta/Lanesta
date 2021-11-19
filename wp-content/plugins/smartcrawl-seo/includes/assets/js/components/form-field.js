import React from "react";
import classnames from "classnames";

export default class FormField extends React.Component {
	static defaultProps = {
		label: '',
		description: '',
		isValid: true,
		formControl: false,
	};

	render() {
		const FormControl = this.props.formControl;

		return <div className={classnames('sui-form-field', {
			'sui-form-field-error': !this.props.isValid
		})}>
			<label className="sui-label">{this.props.label}</label>

			<FormControl {...this.props}/>

			{!!this.props.description &&
			<p className="sui-description">
				<small>{this.props.description}</small>
			</p>
			}
		</div>;
	}
}
