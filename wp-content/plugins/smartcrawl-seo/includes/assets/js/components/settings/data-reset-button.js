import Button from "../button";
import {__} from "@wordpress/i18n";
import React from "react";
import DataResetModal from "./data-reset-modal";
import FloatingNoticePlaceholder from "../floating-notice-placeholder";
import NoticeUtil from "../utils/notice-util";

export default class DataResetButton extends React.Component {
	constructor(props) {
		super(props);

		this.state = {
			resetInProgress: false,
		};
	}

	startResetting() {
		this.setState({resetInProgress: true});
	}

	stopResetting() {
		this.setState({resetInProgress: false});
	}

	resetSuccessful() {
		this.stopResetting();
		NoticeUtil.showSuccessNotice(
			'wds-data-reset-notice',
			__('Data and settings have been reset successfully!', 'wds'),
			false
		);
		setTimeout(() => window.location.reload(), 1500);
	}

	showErrorMessage() {
		NoticeUtil.showErrorNotice(
			'wds-data-reset-notice',
			__('We could not reset your site due to an error.', 'wds'),
			false
		);
	}

	render() {
		return <React.Fragment>
			<FloatingNoticePlaceholder id="wds-data-reset-notice"/>

			<Button id="wds-data-reset-button"
					icon="sui-icon-refresh"
					ghost={true}
					text={__('Reset', 'wds')}
					onClick={() => this.startResetting()}
			/>

			{this.state.resetInProgress &&
			<DataResetModal onClose={() => this.stopResetting()}
							afterReset={() => this.resetSuccessful()}
							onError={() => this.showErrorMessage()}
			/>
			}
		</React.Fragment>;
	}
}
