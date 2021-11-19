import React from 'react';
import {__} from "@wordpress/i18n";
import Modal from "../modal";
import Button from "../button";
import TextField from "../text-field";
import Validator from "../utils/validator";
import fieldWithValidation from "../field-with-validation";

const NameField = fieldWithValidation(TextField, [Validator.isNonEmpty, Validator.isValuePlainText]);

export default class SchemaTypeRenameModal extends React.Component {
	static defaultProps = {
		name: '',
		notice: false,
		onRename: () => false,
		onClose: () => false,
	};

	constructor(props) {
		super(props);

		this.state = {
			name: this.props.name,
			isNameValid: true
		};
	}

	handleNameChange(name, isValid) {
		this.setState({
			name: name,
			isNameValid: isValid
		});
	}

	render() {
		const submissionDisabled = !this.state.isNameValid;
		const onSubmit = () => this.props.onRename(this.state.name);

		return <Modal
			id="wds-schema-type-rename-modal"
			title={__('Rename', 'wds')}
			description={__('Leave the default type name or change it for a recognizable one.', 'wds')}
			onClose={() => this.props.onClose()}
			dialogClasses={{'sui-modal-sm': true}}
			focusAfterOpen="wds-schema-rename-type-input"
			onEnter={onSubmit}
			enterDisabled={submissionDisabled}
			footer={
				<React.Fragment>
					<Button text={__('Cancel', 'wds')}
							onClick={() => this.props.onClose()}
							ghost={true}
					/>

					<Button text={__('Save', 'wds')}
							onClick={onSubmit}
							icon="sui-icon-check"
							id="wds-schema-rename-type-button"
							disabled={submissionDisabled}
					/>
				</React.Fragment>
			}
		>
			<NameField id="wds-schema-rename-type-input"
					   label={__('New Type Name', 'wds')}
					   value={this.state.name}
					   onChange={(name, isValid) => this.handleNameChange(name, isValid)}/>

			{this.props.notice}
		</Modal>;
	}
}
