import React from "react";
import Modal from "../modal";
import {__} from "@wordpress/i18n";
import classnames from 'classnames';
import Button from "../button";
import Validator from "../utils/validator";

export default class ConfigSaveModal extends React.Component {
	static defaultProps = {
		configName: '',
		configDescription: '',
		inProgress: false,
		onClose: () => false,
		onSave: () => false,
	};

	constructor(props) {
		super(props);

		this.props = props;
		this.state = {
			configName: this.props.configName,
			configNameValid: true,
			configDescription: this.props.configDescription,
			configDescriptionValid: true,
			saveButtonDisabled: true,
		};
	}

	handleNameChange(value) {
		const valueValid = this.isNameValid(value);
		this.setState({
			configName: value,
			configNameValid: valueValid,
		}, () => {
			this.updateSaveButtonState();
		});
	}

	isNameValid(value) {
		return Validator.isNonEmpty(value) && Validator.isValuePlainText(value) && this.hasWhitelistCharactersOnly(value);
	}

	hasWhitelistCharactersOnly(value) {
		return !!value.match(/^[@.'_\-\sa-zA-Z0-9]+$/);
	}

	handleDescriptionChange(value) {
		const valueValid = this.isDescriptionValid(value);
		this.setState({
			configDescription: value,
			configDescriptionValid: valueValid,
		}, () => {
			this.updateSaveButtonState();
		});
	}

	isDescriptionValid(value) {
		return Validator.isValuePlainText(value);
	}

	updateSaveButtonState() {
		const nameValid = this.isNameValid(this.state.configName);
		const descriptionValid = this.isDescriptionValid(this.state.configDescription);

		this.setState({
			saveButtonDisabled: !nameValid || !descriptionValid,
		});
	}

	render() {
		let modalTitle, modalDescription, nameFieldLabel;
		if (this.props.configName) {
			modalTitle = __('Rename Config', 'wds');
			modalDescription = __('Change your config name to something recognizable.', 'wds');
			nameFieldLabel = __('New Config Name', 'wds');
		} else {
			modalTitle = __('Save Config', 'wds');
			modalDescription = __("Save your current Smartcrawl settings configurations. You'll be able to then download and apply it to your other sites with Smartcrawl installed.", 'wds');
			nameFieldLabel = __('Config Name', 'wds');
		}

		const onSubmit = () => this.props.onSave(this.state.configName, this.state.configDescription);
		const submissionDisabled = this.state.saveButtonDisabled;

		return <Modal id="wds-config-modal"
					  title={modalTitle}
					  description={modalDescription}
					  onClose={() => this.props.onClose()}
					  disableCloseButton={this.props.inProgress}
					  small={true}
					  enterDisabled={submissionDisabled}
					  onEnter={onSubmit}
					  focusAfterOpen="wds-config-name"
					  footer={
						  <React.Fragment>
							  <div className="sui-flex-child-right">
								  <Button text={__('Cancel', 'wds')}
										  ghost={true}
										  disabled={this.props.inProgress}
										  onClick={this.props.onClose}
								  />
							  </div>

							  <div className="sui-actions-right">
								  <Button text={__('Save', 'wds')}
										  color="blue"
										  disabled={submissionDisabled}
										  onClick={onSubmit}
										  loading={this.props.inProgress}
										  icon="sui-icon-save"
								  />
							  </div>
						  </React.Fragment>
					  }>
			<div className={classnames('sui-form-field', {
				'sui-form-field-error': !this.state.configNameValid
			})}>
				<label htmlFor="wds-config-name" className="sui-label">
					{nameFieldLabel} <span className="wds-required-asterisk">*</span>
				</label>
				<input id="wds-config-name"
					   type="text"
					   onChange={event => this.handleNameChange(event.target.value)}
					   value={this.state.configName}
					   className="sui-form-control"/>
				{!this.state.configNameValid &&
				<span className="sui-error-message" role="alert">
					{__("Invalid config name. Use only alphanumeric characters (a-z, A-Z, 0-9) and allowed special characters (@.'_-).", 'wds')}
				</span>
				}
			</div>

			<div className={classnames('sui-form-field', {
				'sui-form-field-error': !this.state.configDescriptionValid
			})}>
				<label htmlFor="wds-config-description"
					   id="wds-config-description-label" className="sui-label">
					{__('Config Description', 'wds')}
				</label>
				<textarea id="wds-config-description"
						  aria-labelledby="wds-config-description-label"
						  onChange={event => this.handleDescriptionChange(event.target.value)}
						  className="sui-form-control"
						  value={this.state.configDescription}
				/>
			</div>
		</Modal>;
	}
}
