import React from "react";
import Modal from "../modal";
import {__, sprintf} from "@wordpress/i18n";
import {createInterpolateElement} from "@wordpress/element";
import Button from "../button";

export default class ConfigDeleteModal extends React.Component {
	static defaultProps = {
		configName: '',
		inProgress: false,
		onClose: () => false,
		onDelete: () => false,
	};

	render() {
		return <Modal id="wds-delete-config-modal"
					  title={__('Delete Configuration File', 'wds')}
					  description={this.getDescription()}
					  small={true}
					  disableCloseButton={this.props.inProgress}
					  focusAfterOpen="wds-cancel-config-delete"
					  onClose={() => this.props.onClose()}>

			<Button id="wds-cancel-config-delete"
					ghost={true}
					disabled={this.props.inProgress}
					text={__('Cancel', 'wds')}
					onClick={() => this.props.onClose()}
			/>

			<Button color="red"
					loading={this.props.inProgress}
					icon="sui-icon-trash"
					text={__('Delete', 'wds')}
					onClick={() => this.props.onDelete()}
			/>
		</Modal>
	}

	getDescription() {
		return createInterpolateElement(
			sprintf(
				__('Are you sure you want to delete the <strong>%s</strong> config file? You will no longer be able to apply it to this or other connected sites.', 'wds'),
				this.props.configName
			),
			{strong: <strong/>}
		);
	}
}
