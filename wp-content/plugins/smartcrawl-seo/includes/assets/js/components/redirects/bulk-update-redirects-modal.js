import React from "react";
import Modal from "../modal";
import {__} from "@wordpress/i18n";
import fieldWithValidation from "../field-with-validation";
import TextField from "../text-field";
import Validator from "../utils/validator";
import SelectField from "../select-field";
import Button from "../button";
import {get_default_redirect_type} from "./redirect-commons";

const DestinationField = fieldWithValidation(TextField, [Validator.isNonEmpty, Validator.isValuePlainText]);

export default class BulkUpdateRedirectsModal extends React.Component {
	static defaultProps = {
		onSave: () => false,
		onClose: () => false,
	};

	constructor(props) {
		super(props);

		this.state = {
			destination: '',
			isDestinationValid: false,
			type: get_default_redirect_type(),
		};
	}

	handleDestinationChange(destination, isValid) {
		this.setState({
			destination: destination,
			isDestinationValid: isValid,
		});
	}

	render() {
		const onSubmit = () => this.props.onSave(
			this.state.destination.trim(),
			this.state.type
		);
		const submissionDisabled = !this.state.isDestinationValid;

		return <Modal
			id="wds-bulk-update-redirects"
			title={__('Bulk Update', 'wds')}
			description={__('Choose which bulk update actions you wish to apply. This will override the existing values for the selected items.', 'wds')}
			onEnter={onSubmit}
			onClose={this.props.onClose}
			enterDisabled={submissionDisabled}
			focusAfterOpen="wds-destination-field"
			focusAfterClose="wds-add-redirect-dashed-button"
			small={true}>
			<DestinationField id="wds-destination-field"
							  label={__('New URL', 'wds')}
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
