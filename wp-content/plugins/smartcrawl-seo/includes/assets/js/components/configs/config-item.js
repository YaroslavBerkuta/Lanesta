import React from "react";
import {__, sprintf} from "@wordpress/i18n";
import AccordionItem from "../accordion-item";
import ConfigItemDropdown from "./config-item-dropdown";
import Button from "../button";
import {DateTime} from "luxon";
import Config_Values from "../../es6/config-values";
import classnames from "classnames";

export default class ConfigItem extends React.Component {
	static defaultProps = {
		id: '',
		name: '',
		description: '',
		timestamp: '',
		strings: {},
		editable: true,
		removable: true,
		showDescription: true,
		showApplyButton: true,
		showDate: true,
		onApply: () => false,
		onDownload: () => false,
		onUpdate: () => false,
		onDelete: () => false,
	};

	formatDateTime(timestamp) {
		const millis = timestamp * 1000;
		const dateTime = new DateTime.fromMillis(millis);
		const n = 'numeric';
		const s = 'short';

		return dateTime
			.setZone(Config_Values.get('timezone', 'config'))
			.toLocaleString({
				year: n,
				month: s,
				day: n,
				hour: n,
				minute: n,
				hour12: true,
			});
	}

	titleColClass() {
		let colWidth = 9;
		if (this.props.showDescription) {
			colWidth -= 4;
		}
		if (this.props.showDate) {
			colWidth -= 2;
		}
		return 'sui-accordion-col-' + colWidth;
	}

	render() {
		const defaultIcon = Config_Values.get('default_icon', 'config');
		return <AccordionItem header={
			<React.Fragment>
				<div className={classnames(
					'sui-accordion-item-title',
					this.titleColClass())}>
					<span className="sui-icon-smart-crawl" aria-hidden="true"/>
					{this.props.name} {this.props.official && <img src={defaultIcon} alt=""/>}
				</div>

				{this.props.showDescription &&
				<div className="wds-config-description sui-accordion-col-4">
					{this.props.description}
				</div>
				}

				{this.props.showDate &&
				<div className="wds-config-timestamp sui-accordion-col-2">
					{this.props.timestamp && this.formatDateTime(this.props.timestamp)}
				</div>
				}

				<div className="sui-accordion-col-3" style={{justifyContent: 'flex-end'}}>
					{this.props.showApplyButton &&
					<button type="button"
							onClick={this.props.onApply}
							className="sui-button sui-button-ghost sui-accordion-item-action wds-config-apply-button">
						<span aria-hidden="true"
							  className="sui-icon-check sui-no-margin-right"/> {__('Apply', 'wds')}
					</button>
					}

					<ConfigItemDropdown editable={this.props.editable}
										removable={this.props.removable}
										onApply={this.props.onApply}
										onDownload={this.props.onDownload}
										onUpdate={this.props.onUpdate}
										onDelete={this.props.onDelete}
					/>

					<span className="sui-button-icon sui-accordion-open-indicator sui-no-margin-left">
						<span aria-hidden="true" className="sui-icon-chevron-down"/>
						<button type="button"
								className="sui-screen-reader-text">
			
							{sprintf(
								__('Expand %s', 'wds'),
								this.props.name
							)}
						</button>
					</span>
				</div>
			</React.Fragment>
		}>
			<div className="wds-config-details">
				<div>
					<strong>{this.props.name}</strong>
					<p className="sui-description">
						{this.props.description}
					</p>
				</div>

				<div>
					{this.props.editable &&
					<span className="sui-tooltip" data-tooltip={__('Edit Name and Description', 'wds')}>
						<Button icon="sui-icon-pencil"
								ghost={true}
								onClick={this.props.onUpdate}/>
					</span>
					}
				</div>
			</div>

			<table className="sui-table">
				<tbody>
				{Object.keys(this.props.strings).map((stringKey) => {
					const string = this.props.strings[stringKey];
					return <tr key={stringKey} className={stringKey}>
						<th>{this.getLabel(stringKey)}</th>
						<td>{string.split("\n").map(function (item, idx) {
							return <React.Fragment key={idx}>
								<span>{item}</span><br/>
							</React.Fragment>
						})}</td>
					</tr>;
				})}
				</tbody>
			</table>
		</AccordionItem>;
	}

	getLabel(key) {
		const labels = {
			'health': __('SEO Health', 'wds'),
			'onpage': __('Title & Meta', 'wds'),
			'schema': __('Schema', 'wds'),
			'social': __('Social', 'wds'),
			'sitemap': __('Sitemap', 'wds'),
			'advanced': __('Advanced Tools', 'wds'),
			'settings': __('Settings', 'wds'),
		};

		return labels.hasOwnProperty(key) ? labels[key] : '';
	}
}
