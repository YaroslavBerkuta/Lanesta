import React from "react";

const fieldWithValidation = function (WrappedComponent, validator) {
	return class extends React.Component {
		constructor(props) {
			super(props);

			this.state = {
				initial: true
			};
		}

		isValueValid(value) {
			if (this.state.initial) {
				// Give the user a chance to make changes before marking the field as invalid
				return true;
			}

			if (Array.isArray(validator)) {
				let isValid = true;
				validator.forEach((validate) => {
					isValid = isValid && validate(value);
				});
				return isValid;
			} else {
				return validator(value);
			}
		}

		handleChange(value) {
			this.setState({initial: false}, () => {
				this.props.onChange(
					value,
					this.isValueValid(value)
				);
			});
		}

		render() {
			return <WrappedComponent {...this.props}
									 isValid={this.isValueValid(this.props.value)}
									 onChange={value => this.handleChange(value)}/>;
		}
	}
};

export default fieldWithValidation;
