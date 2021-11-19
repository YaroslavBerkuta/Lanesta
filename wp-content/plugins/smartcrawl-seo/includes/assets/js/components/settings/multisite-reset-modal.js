import React from "react";
import Modal from "../modal";
import {__} from "@wordpress/i18n";
import Button from "../button";
import Config_Values from "../../es6/config-values";
import RequestUtil from "../utils/request-util";
import ProgressBar from "../progress-bar";
import {createInterpolateElement} from "@wordpress/element";

export default class MultisiteResetModal extends React.Component {
	static defaultProps = {
		onClose: () => false,
		afterReset: () => false,
		onError: () => false,
	};

	constructor(props) {
		super(props);

		this.state = {
			modalState: 'actions',
			requestInProgress: false,
			progress: 0,
			progressMessage: '',
		};
	}

	resetData() {
		this.setState({
			modalState: 'progress',
			requestInProgress: true,
		}, () => {
			this.post('wds_multisite_data_reset')
				.then((data) => {
					const totalSites = data.total_sites;
					const completedSites = data.completed_sites;
					const progressMessage = data.progress_message;
					const siteProgress = totalSites > 0
						? (completedSites / totalSites) * 100
						: 0;

					this.setState({
						progress: siteProgress,
						progressMessage: progressMessage,
					}, () => {
						if (totalSites !== completedSites) {
							this.resetData();
						} else {
							this.props.afterReset();
						}
					});
				})
				.catch(() => {
					this.setState({
						modalState: 'actions',
						requestInProgress: false,
					}, () => this.props.onError());
				});
		});
	}

	isModalState(state) {
		return this.state.modalState === state;
	}

	render() {
		const description = this.isModalState('progress')
			? __('Resetting your subsite settings, please keep this window open …', 'wds')
			: __('Are you sure you want to reset all the subsites?', 'wds');

		return <Modal id="wds-multisite-data-reset-modal"
					  title={__('Reset Subsites', 'wds')}
					  description={description}
					  disableCloseButton={this.state.requestInProgress}
					  focusAfterOpen="wds-multisite-reset-cancel-button"
					  focusAfterClose="wds-multisite-reset-button"
					  onClose={() => this.props.onClose()}
					  small={true}>
			{this.isModalState('actions') && this.getActionButtons()}
			{this.isModalState('progress') && this.getProgressBar()}
		</Modal>
	}

	getActionButtons() {
		return <React.Fragment>
			<Button id="wds-multisite-reset-cancel-button"
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
		</React.Fragment>;
	}

	getProgressBar() {
		const stateMessage = createInterpolateElement(this.state.progressMessage, {
			strong: <strong/>
		});

		return <ProgressBar progress={this.state.progress}
							stateMessage={stateMessage}/>;
	}

	post(action) {
		const nonce = Config_Values.get('multisite_nonce', 'reset');

		return RequestUtil.post(action, nonce);
	}
}
