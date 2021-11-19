import React from "react";
import ConfigsBoxHeader from "./configs-box-header";
import {__} from "@wordpress/i18n";
import classnames from "classnames";
import Notice from "../notice";
import ConfigItem from "./config-item";
import ConfigsHubSyncLink from "./configs-hub-sync-link";
import {createInterpolateElement} from "@wordpress/element";
import Pagination from "../pagination";
import Config_Values from "../../es6/config-values";
import PaginationUtil from "../utils/pagination-util";

export default class ConfigsTab extends React.Component {
	static defaultProps = {
		syncing: false,
		uploadInProgress: false,
		configs: {},
		onSave: () => false,
		onUpload: () => false,
		onApply: () => false,
		onUpdate: () => false,
		onDownload: () => false,
		onDelete: () => false,
		triggerSync: () => false,
	};

	constructor(props) {
		super(props);

		this.configsPerPage = 10;
		this.props.triggerSync();
		this.state = {
			currentPageNumber: 1,
		};
	}

	componentDidUpdate(prevProps) {
		const configLength = Object.keys(this.getConfigs()).length;
		const prevConfigLength = Object.keys(prevProps.configs || {}).length;

		if (configLength > prevConfigLength) {
			// Config added, switch to first page
			this.setState({
				currentPageNumber: 1,
			});
		} else if (configLength < prevConfigLength) {
			// Config deleted, maybe switch to previous page
			this.setState({
				currentPageNumber: this.newPageNumberAfterDeletion(),
			});
		}
	}

	render() {
		const page = this.getConfigsPage();
		const isMember = Config_Values.get('is_member', 'config');
		const configs = this.getConfigs();
		const configsExist = Object.keys(configs).length > 0;

		return <div className="sui-box">
			<ConfigsBoxHeader uploadInProgress={this.props.uploadInProgress}
							  disabled={this.props.syncing}
							  onSave={() => this.props.onSave()}
							  onUpload={(file) => this.props.onUpload(file)}
			/>
			<div className="sui-box-body">
				<p>
					{__('Use configs to save preset configurations of Smartcrawl’s settings, then upload and apply them to your other sites in just a few clicks! You can easily apply configs to multiple sites at once via the Hub.', 'wds')}
				</p>

				<div id="wds-configs-list" className={classnames({'syncing': this.props.syncing})}>
					<div id="wds-configs-list-loader">
						<span className="sui-description">
							<span className="sui-icon-loader sui-loading sui-md"
								  aria-hidden="true"/> {__('Updating the configs list ...', 'wds')}
						</span>
					</div>

					{!configsExist &&
					<Notice type="info"
							message={__('You don’t have any available config. Save preset configurations of Smartcrawl’s settings, then upload and apply them to your other sites in just a few clicks!', 'wds')}/>
					}

					<div id="wds-configs-list-inner">
						{configsExist &&
						<React.Fragment>
							{this.getPagination()}

							<div className="sui-row">
								<div className="sui-col-md-3">
									<small><strong>{__('Config Name', 'wds')}</strong></small>
								</div>
								<div className="sui-col-md-4">
									<small><strong>{__('Description', 'wds')}</strong></small>
								</div>
								<div className="sui-col-md-5">
									<small><strong>{__('Date Created', 'wds')}</strong></small>
								</div>
							</div>

							<div className="sui-accordion sui-accordion-flushed">
								{Object.keys(page).map((configKey) => {
									const config = page[configKey];

									return <ConfigItem {...config}
													   key={config.id}
													   onApply={() => this.props.onApply(config.id)}
													   onUpdate={() => this.props.onUpdate(config.id)}
													   onDownload={() => this.props.onDownload(config.id)}
													   onDelete={() => this.props.onDelete(config.id)}
									/>
								})}
							</div>

							{this.getPagination()}
						</React.Fragment>
						}

						{isMember && <ConfigsHubSyncLink onClick={() => this.props.triggerSync()}/>}
						{!isMember && <Notice type="purple" message={createInterpolateElement(
							__('Tired of saving, downloading and uploading your configs across your sites? WPMU DEV members use The Hub to easily apply configs to multiple sites at once... Try it free today!<br/> <a>Try The Hub</a>', 'wds'),
							{
								'br': <br/>,
								'a': <a target="_blank"
										className="sui-button sui-button-purple"
										href="https://wpmudev.com/project/smartcrawl-wordpress-seo/?utm_source=smartcrawl&utm_medium=plugin&utm_campaign=smartcrawl_configs_upsell_notice">{__('Try The Hub', 'wds')}</a>
							}
						)}/>}
					</div>
				</div>
			</div>
		</div>;
	}

	newPageNumberAfterDeletion() {
		const currentPageNumber = this.getCurrentPageNumber();
		return currentPageNumber > this.getPageCount()
			? currentPageNumber - 1
			: currentPageNumber;
	}

	getPageCount() {
		const totalCount = Object.keys(this.props.configs).length;
		const perPage = this.configsPerPage;

		return PaginationUtil.getPageCount(totalCount, perPage);
	}

	getConfigsPage() {
		return PaginationUtil.getPage(
			this.getConfigs(),
			this.getCurrentPageNumber(),
			this.configsPerPage
		);
	}

	getPagination() {
		const configs = this.getConfigs();
		const totalCount = Object.keys(configs).length;
		const perPage = this.configsPerPage;
		const currentPageNumber = this.getCurrentPageNumber();

		if (totalCount > perPage) {
			return <Pagination
				count={totalCount}
				perPage={perPage}
				onClick={(pageNumber) => {
					this.setState({
						currentPageNumber: pageNumber
					});
				}}
				currentPage={currentPageNumber}
			/>;
		}
	}

	getConfigs() {
		return this.props.configs || {};
	}

	getCurrentPageNumber() {
		return this.state.currentPageNumber;
	}
}
