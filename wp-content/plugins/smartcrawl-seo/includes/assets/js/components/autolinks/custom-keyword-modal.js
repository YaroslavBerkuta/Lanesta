import React from "react";
import Modal from "../modal";
import {__} from "@wordpress/i18n";
import {createInterpolateElement} from "@wordpress/element";
import Button from "../button";
import fieldWithValidation from "../field-with-validation";
import TextField from "../text-field";
import Validator from "../utils/validator";

const KeywordField = fieldWithValidation(TextField, [Validator.isNonEmpty, Validator.isValuePlainText]);
const UrlField = fieldWithValidation(TextField, [Validator.isNonEmpty, Validator.isValuePlainText]);

export default class CustomKeywordModal extends React.Component {
	static defaultProps = {
		keyword: '',
		url: '',
		editMode: false,
		onClose: () => false,
		onSave: () => false,
	};

	constructor(props) {
		super(props);

		// Assume that values being edited are valid, whereas new empty values are invalid
		const initiallyValid = this.props.editMode;

		this.state = {
			keyword: this.props.keyword,
			keywordIsValid: initiallyValid,
			url: this.props.url,
			urlIsValid: initiallyValid,
		};
	}

	handleKeywordChange(keyword, isValid) {
		this.setState({
			keyword: keyword,
			keywordIsValid: isValid
		});
	}

	handleUrlChange(url, isValid) {
		this.setState({
			url: url,
			urlIsValid: isValid
		});
	}

	render() {
		const onSubmit = () => this.props.onSave(this.state.keyword, this.state.url);
		const submissionDisabled = !this.state.keywordIsValid || !this.state.urlIsValid;

		return <Modal id="wds-custom-keywords"
					  title={__('Add Custom Keywords', 'wds')}
					  description={__('Choose your keywords, and then specify the URL to auto-link to.', 'wds')}
					  enterDisabled={submissionDisabled}
					  focusAfterOpen="wds-custom-keyword"
					  focusAfterClose="wds-keyword-pair-new-button"
					  onEnter={onSubmit}
					  onClose={this.props.onClose}
					  footer={
						  <React.Fragment>
							  <div className="sui-flex-child-right">
								  <Button text={__('Cancel', 'wds')}
										  ghost={true}
										  onClick={this.props.onClose}
								  />
							  </div>

							  <div className="sui-actions-right">
								  <Button text={__('Save', 'wds')}
										  color="blue"
										  onClick={onSubmit}
										  icon="sui-icon-save"
										  disabled={submissionDisabled}
								  />
							  </div>
						  </React.Fragment>
					  }>
			<KeywordField
				id="wds-custom-keyword"
				label={createInterpolateElement(__('Keyword group <span>Usually related terms</span>', 'wds'), {
					'span': <span/>
				})}
				value={this.state.keyword}
				placeholder={__('E.g. Cats, Kittens, Felines', 'wds')}
				onChange={(keyword, isValid) => this.handleKeywordChange(keyword, isValid)}
			/>

			<UrlField
				label={createInterpolateElement(__('Link URL <span>Both internal and external links are supported</span>', 'wds'), {
					'span': <span/>
				})}
				value={this.state.url}
				placeholder={__('E.g. /cats', 'wds')}
				description={createInterpolateElement(__('Formats include relative (E.g. <strong>/cats</strong>) or absolute URLs (E.g. <strong>https://www.website.com/cats</strong> or <strong>https://website.com/cats</strong>).', 'wds'), {
					'strong': <strong/>
				})}
				onChange={(url, isValid) => this.handleUrlChange(url, isValid)}
			/>
		</Modal>;
	}
}
