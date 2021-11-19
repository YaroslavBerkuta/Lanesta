import React from "react";
import Dropdown from "../dropdown";
import DropdownButton from "../dropdown-button";
import {__} from "@wordpress/i18n"
import Button from "../button";

export default class CrawlItemSitemap extends React.Component {
	static defaultProps = {
		path: '',
		loading: false,
		disabled: false,
		onAddToSitemap: () => false,
		onIgnore: () => false,
		onRestore: () => false,
	};

	render() {
		return <tr>
			<td>
				{!this.props.ignored &&
				<span aria-hidden="true" className="sui-icon-warning-alert"/>
				}
				<small>
					<strong>{this.props.path}</strong>
				</small>
			</td>

			<td>
				{!this.props.ignored &&
				<Dropdown loading={this.props.loading}
						  disabled={this.props.disabled}
						  buttons={[
							  <DropdownButton icon="sui-icon-plus"
											  text={__('Add to Sitemap', 'wds')}
											  onClick={() => this.props.onAddToSitemap()}/>,

							  <DropdownButton icon="sui-icon-eye-hide"
											  text={__('Ignore', 'wds')}
											  onClick={() => this.props.onIgnore()}/>,
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
			</td>
		</tr>;
	}
}
