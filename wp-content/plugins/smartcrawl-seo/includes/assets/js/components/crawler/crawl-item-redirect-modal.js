import React from "react";
import Modal from "../modal";
import {__, sprintf} from "@wordpress/i18n";
import {createInterpolateElement} from "@wordpress/element";
import fieldWithValidation from "../field-with-validation";
import TextField from "../text-field";
import Validator from "../utils/validator";
import Config_Values from "../../es6/config-values";
import Button from "../button";

const DestinationField = fieldWithValidation(TextField, [Validator.isNonEmpty, Validator.isValuePlainText]);

export default class CrawlItemRedirectModal extends React.Component {
	static defaultProps = {
		source: '',
		destination: '',
		requestInProgress: false,
		onSave: () => false,
		onClose: () => false
	};

	constructor(props) {
		super(props);

		this.state = {
			destination: this.props.destination,
			isDestinationValid: Validator.isNonEmpty(this.props.destination)
		};
	}

	handleDestinationChange(destination, isValid) {
		this.setState({
			destination: destination,
			isDestinationValid: isValid,
		});
	}

	render() {
		const modalDescription = createInterpolateElement(
			sprintf(__('Choose where to redirect <strong>%s</strong>', 'wds'), this.props.source),
			{strong: <strong/>}
		);
		const fieldDescription = createInterpolateElement(
			__('Formats include relative URLs like <strong>/cats</strong> or absolute URLs like <strong>https://website.com/cats</strong>. This feature will automatically redirect traffic from the broken URL to this new URL, you can view all your redirects under <strong><a>Advanced Tools</a></strong>.', 'wds'),
			{
				strong: <strong/>,
				a: <a href={Config_Values.get('advanced_tools_url', 'crawler')}/>
			}
		);
		const onSubmit = () => this.props.onSave(this.state.destination.trim());
		const submissionDisabled = !this.state.isDestinationValid;

		return <Modal id="wds-issue-redirect"
					  title={__('Redirect URL', 'wds')}
					  description={modalDescription}
					  focusAfterOpen="wds-crawler-redirect-destination"
					  onEnter={onSubmit}
					  enterDisabled={submissionDisabled}
					  onClose={() => this.props.onClose()}
					  disableCloseButton={this.props.requestInProgress}
					  small={true}>
			<DestinationField id="wds-crawler-redirect-destination"
							  label={__('New URL', 'wds')}
							  placeholder={__('Enter new URL', 'wds')}
							  description={fieldDescription}
							  value={this.state.destination}
							  onChange={(destination, isValid) => this.handleDestinationChange(destination, isValid)}
			/>

			<div style={{display: "flex", justifyContent: "space-between"}}>
				<Button text={__('Cancel', 'wds')}
						ghost={true}
						onClick={() => this.props.onClose()}
						disabled={this.props.requestInProgress}
				/>

				<Button text={__('Apply', 'wds')}
						onClick={onSubmit}
						icon="sui-icon-check"
						disabled={submissionDisabled}
						loading={this.props.requestInProgress}
				/>
			</div>
		</Modal>;
	}
}
