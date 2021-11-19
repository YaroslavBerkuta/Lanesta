import React from 'react';
import Modal from "./modal";
import {__} from "@wordpress/i18n";
import BoxSelector from "./box-selector";
import Button from "./button";
import {noop} from "lodash-es";

export default class BoxSelectorModal extends React.Component {
	static defaultProps = {
		id: '',
		title: '',
		description: '',
		actionButtonText: '',
		actionButtonIcon: '',
		onClose: noop,
		onAction: noop,
		options: {},
		multiple: true,
		noOptionsMessage: false,
		generalMessage: false,
		requiredNotice: '',
	};

	constructor(props) {
		super(props);

		this.state = {
			selectedValues: []
		};
	}

	handleSelection(selectedValues) {
		this.setState({selectedValues: selectedValues});
	}

	handleAction() {
		this.props.onAction(this.state.selectedValues);
	}

	hasRequiredOption() {
		let hasRequiredOption = false;
		this.props.options.some(option => {
			if (option.required) {
				hasRequiredOption = true;
				return true;
			}
		});

		return hasRequiredOption;
	}

	render() {
		return <Modal small={true}
					  id={this.props.id + '-modal'}
					  title={this.props.title}
					  onClose={() => this.props.onClose()}
					  dialogClasses={{
						  'sui-modal-lg': true,
						  'sui-modal-sm': false
					  }}
					  description={this.props.description}>

			{this.hasRequiredOption() && this.props.requiredNotice}

			{!!Object.keys(this.props.options).length &&
			<BoxSelector id={this.props.id + '-selector'}
						 options={this.props.options}
						 selectedValues={this.state.selectedValues}
						 multiple={this.props.multiple}
						 onChange={(items) => this.handleSelection(items)}
			/>
			}
			{!Object.keys(this.props.options).length && this.props.noOptionsMessage}

			{this.props.generalMessage}

			<div style={{
				display: "flex",
				justifyContent: "space-between"
			}}>
				<Button text={__('Cancel', 'wds')}
						onClick={() => this.props.onClose()}
						ghost={true}
				/>

				<Button text={this.props.actionButtonText}
						icon={this.props.actionButtonIcon}
						id={this.props.id + '-action-button'}
						onClick={() => this.handleAction()}
						disabled={!Object.keys(this.state.selectedValues).length}
				/>
			</div>
		</Modal>;
	}
}
