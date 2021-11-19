import React from "react";
import Dropdown from "../dropdown";
import DropdownButton from "../dropdown-button";
import {__} from "@wordpress/i18n"
import CrawlItemOccurrencesModal from "./crawl-item-occurrences-modal";
import Button from "../button";

export default class CrawlItem extends React.Component {
	static defaultProps = {
		path: '',
		origin: [],
		loading: false,
		disabled: false,
		onRedirect: () => false,
		onIgnore: () => false,
		onRestore: () => false,
	};

	constructor(props) {
		super(props);

		this.state = {
			showingOccurrences: false,
		};
	}

	render() {
		return <tr>
			<td>
				{!this.props.ignored &&
				<span aria-hidden="true" className="sui-warning sui-icon-warning-alert"/>
				}
				<small>
					<strong>{this.props.path}</strong>
				</small>
			</td>

			{!this.props.ignored &&
			<td>
				<span className="sui-tag sui-tag-warning">
					{!!this.props.origin && this.props.origin.length}
				</span>
			</td>
			}

			<td>
				{!this.props.ignored &&
				<Dropdown loading={this.props.loading}
						  disabled={this.props.disabled}
						  buttons={[
							  <DropdownButton text={__('List Occurrences', 'wds')}
											  icon="sui-icon-list-bullet"
											  onClick={() => this.startShowingOccurrences()}
							  />,
							  <DropdownButton text={__('Redirect', 'wds')}
											  icon="sui-icon-arrow-right"
											  onClick={() => this.props.onRedirect()}
							  />,
							  <DropdownButton text={__('Ignore', 'wds')}
											  icon="sui-icon-eye-hide"
											  onClick={() => this.props.onIgnore()}
							  />
						  ]}/>
				}

				{this.props.ignored &&
				<Button icon="sui-icon-plus"
						text={__('Restore', 'wds')}
						ghost={true}
						loading={this.props.loading}
						disabled={this.props.disabled}
						onClick={() => this.props.onRestore()}
				/>
				}

				{this.state.showingOccurrences &&
				<CrawlItemOccurrencesModal path={this.props.path}
										   origin={this.props.origin}
										   onClose={() => this.stopShowingOccurrences()}/>
				}
			</td>
		</tr>;
	}

	startShowingOccurrences() {
		this.setState({
			showingOccurrences: true
		});
	}

	stopShowingOccurrences() {
		this.setState({
			showingOccurrences: false
		});
	}
}
