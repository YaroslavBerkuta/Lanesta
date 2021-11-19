import React from "react";
import {__} from "@wordpress/i18n";
import Button from "../button";
import classnames from 'classnames';
import RedirectItem from "./redirect-item";
import uniqueId from "lodash-es/uniqueId";
import RedirectModal from "./redirect-modal";
import update from 'immutability-helper';
import BulkUpdateRedirectsModal from "./bulk-update-redirects-modal";
import Pagination from "../pagination";
import PaginationUtil from "../utils/pagination-util";
import SUI from 'SUI';
import UrlUtil from "../utils/url-util";

export default class Redirects extends React.Component {
	constructor(props) {
		super(props);

		const redirects = this.prepareInternalData(this.props.redirects, this.props.types);
		this.state = {
			redirects: redirects,
			bulkItems: new Set(),
			addingRedirect: false,
			editingRedirect: false,
			bulkUpdating: false,
			currentPageNumber: 1,
		};

		this.redirectsPerPage = 10;
	}

	componentDidMount() {
		this.maybeStartAddingRedirect();
	}

	prepareInternalData(redirects, types) {
		const data = {};
		Object.keys(redirects).forEach(key => {
			if (!types.hasOwnProperty(key)) {
				return;
			}

			const id = this.getUniqueId();
			data[id] = {
				source: key,
				destination: redirects[key],
				type: types[key] + ''
			};
		});
		return data;
	}

	getUniqueId() {
		return uniqueId('redirect-');
	}

	render() {
		const redirects = this.state.redirects;
		const redirectsCount = this.objectLength(redirects);
		const redirectsExist = redirectsCount > 0;
		const page = this.getRedirectsPage();
		const pageLength = this.objectLength(page);
		const bulkCount = this.state.bulkItems.size;
		const allChecked = pageLength > 0 && bulkCount === pageLength;

		return <div>
			<div className="sui-box-builder">
				<div className="sui-box-builder-header"
					 style={{display: "flex", justifyContent: "space-between", flexWrap: "wrap"}}>
					<Button
						text={__('Add Redirect', 'wds')}
						color="purple"
						icon="sui-icon-plus"
						onClick={() => this.startAddingRedirect()}
					/>

					<div>
						{redirectsCount > this.redirectsPerPage &&
						<Pagination count={redirectsCount}
									currentPage={this.state.currentPageNumber}
									perPage={this.redirectsPerPage}
									onClick={(pageNumber) => this.changePage(pageNumber)}/>
						}
					</div>
				</div>

				<div className={classnames('sui-box-builder-body', {"wds-no-redirects": !redirectsExist})}>
					<div className="wds-redirect-controls">
						<label className="sui-checkbox">
							<input type="checkbox"
								   checked={allChecked}
								   onChange={e => this.toggleAll(e.target.checked)}
							/>
							<span aria-hidden="true"/>
						</label>

						<Button text={__('Bulk Update', 'wds')}
								onClick={() => this.startBulkUpdate()}
								disabled={!bulkCount}
						/>
						{this.state.bulkUpdating &&
						<BulkUpdateRedirectsModal
							onSave={(destination, type) => this.bulkUpdate(destination, type)}
							onClose={() => this.stopBulkUpdate()}/>
						}

						<Button text={__('Remove Redirects', 'wds')}
								onClick={() => this.bulkDeleteRedirects()}
								disabled={!bulkCount}
						/>
					</div>

					<div className="sui-builder-fields">
						{Object.keys(page).map((key) => {
								const item = page[key];
								return <React.Fragment key={key}>
									<RedirectItem {...item}
												  selected={this.state.bulkItems.has(key)}
												  onToggle={(selected) => this.toggleItem(key, selected)}
												  onEdit={() => this.startEditingRedirect(key)}
												  onDelete={() => this.deleteSingleRedirect(key)}
									/>

									{this.state.editingRedirect === key &&
									<RedirectModal
										editMode={true}
										source={item.source}
										destination={item.destination}
										type={item.type}
										onSave={(source, destination, type) => this.editRedirect(key, source, destination, type)}
										onClose={() => this.stopEditingRedirect()}/>
									}
								</React.Fragment>
							}
						)}
					</div>

					<Button id="wds-add-redirect-dashed-button"
							dashed={true}
							icon="sui-icon-plus"
							text={__('Add Redirect', 'wds')}
							onClick={() => this.startAddingRedirect()}/>

					<p className="wds-no-redirects-message">
						<small>{__('You can add as many redirects as you like. Add your first above!', 'wds')}</small>
					</p>
				</div>
			</div>

			{this.state.addingRedirect &&
			<RedirectModal onSave={(source, destination, type) => this.addRedirect(source, destination, type)}
						   onClose={() => this.stopAddingRedirect()}/>
			}

			<input type="hidden"
				   name="wds_autolinks_options[urls]"
				   value={JSON.stringify(this.state.redirects)}/>
		</div>;
	}

	changePage(pageNumber) {
		this.setState({
			currentPageNumber: pageNumber,
			bulkItems: new Set(),
		});
	}

	setRedirect(key, source, destination, type) {
		const redirects = update(this.state.redirects, {
			[key]: {
				$set: {
					source: source,
					destination: destination,
					type: type,
				}
			}
		});
		return new Promise(resolve => {
			this.setState({redirects: redirects}, resolve);
		});
	}

	startAddingRedirect() {
		this.setState({addingRedirect: true});
	}

	addRedirect(source, destination, type) {
		if (this.sourceExists(source)) {
			this.showErrorNotice(__('That URL already exists, please try again.', 'wds'));
		} else {
			this.setRedirect(this.getUniqueId(), source, destination, type)
				.then(() => {
					this.showSuccessNotice(
						__("The redirect has been added. You need to save the changes to make them live.", 'wds')
					);
					this.setState({
						currentPageNumber: this.getPageCount()
					});
					this.stopAddingRedirect();
				});
		}
	}

	sourceExists(source) {
		return Object.values(this.state.redirects).filter(redirect => redirect.source === source).length > 0;
	}

	stopAddingRedirect() {
		this.maybeRemoveQueryParam();
		this.setState({addingRedirect: false});
	}

	startEditingRedirect(key) {
		this.setState({editingRedirect: key});
	}

	editRedirect(key, source, destination, type) {
		this.setRedirect(key, source, destination, type);
		this.stopEditingRedirect();
		this.showSuccessNotice(
			__("The redirect has been updated. You need to save the changes to make them live.", 'wds')
		);
	}

	stopEditingRedirect() {
		this.setState({editingRedirect: false});
	}

	startBulkUpdate() {
		this.setState({bulkUpdating: true});
	}

	bulkUpdate(destination, type) {
		const keys = this.state.bulkItems;
		const spec = {};
		keys.forEach((key) => {
			spec[key] = {
				destination: {$set: destination},
				type: {$set: type},
			}
		});
		this.setState({
			redirects: update(this.state.redirects, spec),
			bulkUpdating: false
		});
		this.showSuccessNotice(
			__("The redirects have been updated. You need to save the changes to make them live.", 'wds')
		);
	}

	stopBulkUpdate() {
		this.setState({bulkUpdating: false});
	}

	bulkDeleteRedirects() {
		this.deleteRedirect(Array.from(this.state.bulkItems));
		this.showSuccessNotice(
			__("The redirects have been removed. You need to save the changes to make them live.", 'wds')
		);
	}

	deleteSingleRedirect(key) {
		this.deleteRedirect(key);
		this.showSuccessNotice(
			__("The redirect has been removed. You need to save the changes to make them live.", 'wds')
		);
	}

	deleteRedirect(keys) {
		if (!Array.isArray(keys)) {
			keys = [keys];
		}

		const redirects = update(
			this.state.redirects,
			{$unset: keys}
		);

		const bulkItemSet = update(
			this.state.bulkItems,
			{$remove: keys}
		);

		this.setState({redirects: redirects}, () => {
			this.setState({
				currentPageNumber: this.newPageNumberAfterDeletion(),
				bulkItems: bulkItemSet
			});
		});
	}

	toggleItem(key, selected) {
		const set = this.state.bulkItems;
		if (selected) {
			set.add(key);
		} else {
			set.delete(key);
		}
		this.setState({
			bulkItems: set
		});
	}

	toggleAll(selected) {
		let bulkItems;
		if (selected) {
			bulkItems = Object.keys(this.getRedirectsPage());
		} else {
			bulkItems = [];
		}
		this.setState({
			bulkItems: new Set(bulkItems),
		});
	}

	getPageCount() {
		return PaginationUtil.getPageCount(
			this.objectLength(this.state.redirects),
			this.redirectsPerPage
		);
	}

	getRedirectsPage() {
		return PaginationUtil.getPage(
			this.state.redirects,
			this.state.currentPageNumber,
			this.redirectsPerPage
		);
	}

	newPageNumberAfterDeletion() {
		const currentPageNumber = this.state.currentPageNumber;
		return currentPageNumber > this.getPageCount()
			? currentPageNumber - 1
			: currentPageNumber;
	}

	objectLength(collectionObject) {
		return Object.keys(collectionObject).length;
	}

	showNotice(message, type = 'success') {
		const icons = {
			error: 'warning-alert',
			info: 'info',
			warning: 'warning-alert',
			success: 'check-tick'
		};

		SUI.closeNotice('wds-redirect-notice');
		SUI.openNotice('wds-redirect-notice', '<p>' + message + '</p>', {
			type: type,
			icon: icons[type],
			dismiss: {show: false}
		});
	}

	showSuccessNotice(message) {
		this.showNotice(message, 'info');
	}

	showErrorNotice(message) {
		this.showNotice(message, 'error');
	}

	maybeStartAddingRedirect() {
		if (UrlUtil.getQueryParam('add_redirect') === '1') {
			this.startAddingRedirect();
		}
	}

	maybeRemoveQueryParam() {
		UrlUtil.removeQueryParam('add_redirect');
	}
}
