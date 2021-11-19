import React from "react";
import NoticeUtil from "../utils/notice-util";
import {__} from "@wordpress/i18n";
import FloatingNoticePlaceholder from "../floating-notice-placeholder";
import Button from "../button";
import MultisiteResetModal from "./multisite-reset-modal";

export default class MultisiteResetButton extends React.Component {
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
			'wds-multisite-reset-notice',
			__('Data and settings for all subsites have been reset successfully!', 'wds'),
			false
		);
		setTimeout(() => window.location.reload(), 1500);
	}

	showErrorMessage() {
		NoticeUtil.showErrorNotice(
			'wds-multisite-reset-notice',
			__('We could not reset your network due to an unknown error. Please try again.', 'wds'),
			false
		);
	}

	render() {
		return <React.Fragment>
			<FloatingNoticePlaceholder id="wds-multisite-reset-notice"/>

			<Button color="red"
					ghost={true}
					id="wds-multisite-reset-button"
					text={__('Reset Subsites', 'wds')}
					onClick={() => this.startResetting()}
			/>

			{this.state.resetInProgress &&
			<MultisiteResetModal onClose={() => this.stopResetting()}
								 afterReset={() => this.resetSuccessful()}
								 onError={() => this.showErrorMessage()}
			/>
			}
		</React.Fragment>;
	}
}
