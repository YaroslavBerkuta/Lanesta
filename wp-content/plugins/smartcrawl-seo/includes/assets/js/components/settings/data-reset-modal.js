import * as React from "react";
import Modal from "../modal";
import Button from "../button";
import {__} from "@wordpress/i18n";
import Config_Values from "../../es6/config-values";
import RequestUtil from "../utils/request-util";

export default class DataResetModal extends React.Component {
	static defaultProps = {
		onClose: () => false,
		afterReset: () => false,
		onError: () => false,
	};

	constructor(props) {
		super(props);

		this.state = {
			requestInProgress: false
		};
	}

	resetData() {
		this.requestInProgress(true).then(() => {
			this.post('wds_data_reset')
				.then(() => {
					this.props.afterReset();
				})
				.finally(() => this.requestInProgress(false))
				.catch(() => this.props.onError());
		});
	}

	requestInProgress(inProgress) {
		return new Promise((resolve) => {
			this.setState({requestInProgress: inProgress}, resolve);
		});
	}

	render() {
		return <Modal id="wds-data-reset-modal"
					  title={__('Reset Settings & Data', 'wds')}
					  description={__('Are you sure you want to reset SmartCrawl’s settings and data back to the factory defaults?', 'wds')}
					  focusAfterOpen="wds-data-reset-cancel-button"
					  focusAfterClose="wds-data-reset-button"
					  disableCloseButton={this.state.requestInProgress}
					  onClose={() => this.props.onClose()}
					  small={true}>
			<Button id="wds-data-reset-cancel-button"
					text={__('Cancel', 'wds')}
					ghost={true}
					disabled={this.state.requestInProgress}
					onClick={() => this.props.onClose()}
			/>

			<Button ghost={true}
					color="red"
					icon="sui-icon-refresh"
					loading={this.state.requestInProgress}
					onClick={() => this.resetData()}
					text={__('Reset', 'wds')}
			/>
		</Modal>;
	}

	post(action) {
		const nonce = Config_Values.get('nonce', 'reset');

		return RequestUtil.post(action, nonce);
	}
}
