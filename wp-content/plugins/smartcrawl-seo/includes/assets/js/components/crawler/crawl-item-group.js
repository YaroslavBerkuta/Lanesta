import React from "react";
import AccordionItem from "../accordion-item";
import classnames from "classnames";
import {__, sprintf} from "@wordpress/i18n";

export default class CrawlItemGroup extends React.Component {
	static defaultProps = {
		type: '',
		activeIssues: {},
		ignoredIssues: {},
		singularTitle: '',
		pluralTitle: '',
		description: '',
		warningClass: 'sui-warning',
		renderIssue: () => false,
		renderControls: () => false,
	};

	constructor(props) {
		super(props);

		this.state = {
			activeTab: 'issues',
		};
	}

	render() {
		const activeIssues = this.props.activeIssues;
		const activeIssueCount = Object.keys(activeIssues).length;
		const ignoredIssues = this.props.ignoredIssues;
		const ignoredIssueCount = Object.keys(ignoredIssues).length;
		const totalIssueCount = activeIssueCount + ignoredIssueCount;
		const totalIssuesExist = totalIssueCount > 0;
		const tabs = this.getTabData(activeIssues, ignoredIssues);

		return <AccordionItem className={classnames({
			[this.props.warningClass]: activeIssueCount > 0,
			"sui-success": activeIssueCount < 1,
			"wds-no-crawl-issues": !totalIssuesExist
		})} header={
			<React.Fragment>
				<div className="sui-accordion-item-title">
					<span aria-hidden="true"
						  className={classnames({
							  "sui-icon-warning-alert": activeIssueCount > 0,
							  [this.props.warningClass]: activeIssueCount > 0,
							  "sui-success sui-icon-check-tick": activeIssueCount < 1,
						  })}/>
					{this.getTitle(activeIssueCount)}
				</div>

				{totalIssuesExist &&
				<div>
					<span className="sui-accordion-open-indicator">
						<span aria-hidden="true" className="sui-icon-chevron-down"/>
						<button type="button"
								className="sui-screen-reader-text">
							{__('Expand issue', 'wds')}
						</button>
					</span>
				</div>
				}
			</React.Fragment>
		}>
			{totalIssuesExist &&
			<React.Fragment>
				<small><strong>{__('Overview', 'wds')}</strong></small>
				<p>
					<small>{this.props.description}</small>
				</p>

				<div className="sui-tabs">
					<div data-tabs="">
						{Object.keys(tabs).map((tab) =>
							<div key={tab}
								 className={classnames({active: this.state.activeTab === tab})}
								 onClick={() => this.setState({activeTab: tab})}>

								{tabs[tab].label}
							</div>
						)}
					</div>

					<div data-panes="">
						{Object.keys(tabs).map((tab) =>
							<div key={tab}
								 className={classnames({active: this.state.activeTab === tab})}>
								{this.getPane(tabs[tab])}
							</div>
						)}
					</div>
				</div>
			</React.Fragment>
			}
		</AccordionItem>;
	}

	getTabData(activeIssues, ignoredIssues) {
		return {
			issues: {
				label: __('Issues', 'wds'),
				items: activeIssues,
				noItemsNotice: __('No active issues.', 'wds'),
				tableClass: "wds-crawl-issues-table",
			},
			ignored: {
				label: __('Ignored', 'wds'),
				items: ignoredIssues,
				noItemsNotice: __('No ignored issues.', 'wds'),
				tableClass: "wds-ignored-items-table"
			}
		};
	}

	getPane(tab) {
		const issues = tab.items;
		const issuesExist = Object.keys(issues).length > 0;

		return <React.Fragment>
			<table className={tab.tableClass}>
				<tbody>
				{issuesExist &&
				<React.Fragment>
					{Object.keys(issues).map(
						key => this.props.renderIssue(key, issues[key])
					)}

					<tr className="wds-controls-row">
						<td colSpan="4">
							{
								this.props.renderControls(this.props.type, this.state.activeTab)
							}
						</td>
					</tr>
				</React.Fragment>
				}

				{!issuesExist &&
				<tr className="wds-no-results-row">
					<td colSpan="2">
						<small>{tab.noItemsNotice}</small>
					</td>
				</tr>
				}
				</tbody>
			</table>
		</React.Fragment>;
	}

	getTitle(activeIssueCount) {
		return sprintf(
			activeIssueCount === 1 ? this.props.singularTitle : this.props.pluralTitle,
			activeIssueCount > 0 ? activeIssueCount : __('No', 'wds')
		);
	}
}
