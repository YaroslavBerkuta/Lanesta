import React from "react";
import DropdownButton from "../dropdown-button";
import {__} from "@wordpress/i18n";
import Dropdown from "../dropdown";

export default class ConfigItemDropdown extends React.Component {
	static defaultProps = {
		editable: true,
		removable: true,
		onApply: () => false,
		onDownload: () => false,
		onUpdate: () => false,
		onDelete: () => false,
	}

	render() {
		return <Dropdown icon="sui-icon-more"
						 buttons={this.getDropdownButtons()}/>;
	}

	getDropdownButtons() {
		const dropdownButtons = [
			<DropdownButton onClick={() => this.props.onApply()}
							icon="sui-icon-check"
							text={__('Apply', 'wds')}
			/>,
			<DropdownButton onClick={() => this.props.onDownload()}
							icon="sui-icon-download"
							text={__('Download', 'wds')}
			/>,
		];
		if (this.props.editable) {
			dropdownButtons.push(
				<DropdownButton onClick={() => this.props.onUpdate()}
								icon="sui-icon-pencil"
								text={__('Name and Description', 'wds')}
				/>
			);
		}
		if (this.props.removable) {
			dropdownButtons.push(
				<DropdownButton onClick={() => this.props.onDelete()}
								icon="sui-icon-trash"
								red={true}
								text={__('Delete', 'wds')}
				/>
			);
		}
		return dropdownButtons;
	}
}
