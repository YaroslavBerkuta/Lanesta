import React from "react";
import Modal from "../modal";
import {__} from "@wordpress/i18n";
import {createInterpolateElement} from "@wordpress/element";
import fieldWithValidation from "../field-with-validation";
import TextField from "../text-field";
import Validator from "../utils/validator";
import SelectField from "../select-field";
import Button from "../button";
import {get_default_redirect_type} from "./redirect-commons";

const SourceField = fieldWithValidation(TextField, [Validator.isNonEmpty, Validator.isValuePlainText]);
const DestinationField = fieldWithValidation(TextField, [Validator.isNonEmpty, Validator.isValuePlainText]);

export default class RedirectModal extends React.Component {
	static defaultProps = {
		editMode: false,
		source: '',
		destination: '',
		type: get_default_redirect_type(),
		onSave: () => false,
		onClose: () => false,
	};

	constructor(props) {
		super(props);

		const fieldsInitiallyValid = this.props.editMode;

		this.state = {
			source: this.props.source,
			isSourceValid: fieldsInitiallyValid,
			destination: this.props.destination,
			isDestinationValid: fieldsInitiallyValid,
			type: this.props.type,
		};
	}

	handleSourceChange(source, isValid) {
		this.setState({
			source: source,
			isSourceValid: isValid,
		});
	}

	handleDestinationChange(destination, isValid) {
		this.setState({
			destination: destination,
			isDestinationValid: isValid,
		});
	}

	render() {
		const onSubmit = () => this.props.onSave(
			this.state.source.trim(),
			this.state.destination.trim(),
			this.state.type
		);
		const submissionDisabled = !this.state.isSourceValid || !this.state.isDestinationValid;

		return <Modal
			id="wds-add-redirect-form"
			title={__('Add Redirect', 'wds')}
			description={createInterpolateElement(__('Allowed formats include relative URLs like <strong>/cats</strong> or absolute URLs such as <strong>https://website.com/cats</strong>.', 'wds'), {
				strong: <strong/>
			})}
			onEnter={onSubmit}
			onClose={this.props.onClose}
			enterDisabled={submissionDisabled}
			focusAfterOpen="wds-source-field"
			focusAfterClose="wds-add-redirect-dashed-button"
			small={true}>
			<SourceField id="wds-source-field"
						 label={__('Old URL', 'wds')}
						 value={this.state.source}
						 placeholder={__('E.g. /cats', 'wds')}
						 onChange={(source, isValid) => this.handleSourceChange(source, isValid)}
			/>

			<DestinationField label={__('New URL', 'wds')}
							  value={this.state.destination}
							  placeholder={__('E.g. /cats-new', 'wds')}
							  onChange={(destination, isValid) => this.handleDestinationChange(destination, isValid)}
			/>

			<SelectField label={__('Redirect Type', 'wds')}
						 description={__('This tells search engines whether to keep indexing the old page, or replace it with the new page.', 'wds')}
						 options={{
							 '302': __('Temporary', 'wds'),
							 '301': __('Permanent', 'wds'),
						 }}
						 selectedValue={this.state.type}
						 onSelect={(type) => this.setState({type: type})}
			/>

			<div style={{display: "flex", justifyContent: "space-between"}}>
				<Button text={__('Cancel', 'wds')}
						ghost={true}
						onClick={this.props.onClose}
				/>

				<Button text={__('Save', 'wds')}
						color="blue"
						onClick={onSubmit}
						icon="sui-icon-save"
						disabled={submissionDisabled}
				/>
			</div>
		</Modal>;
	}
}
