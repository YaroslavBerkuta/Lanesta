import React from 'react';
import Dropdown from "../dropdown";
import DropdownButton from "../dropdown-button";
import {__} from "@wordpress/i18n";

export default class SchemaTypeDropdown extends React.Component {
	static defaultProps = {
		onRename: () => false,
		onDuplicate: () => false,
		onDelete: () => false,
	};

	render() {
		return <Dropdown buttons={[
			<DropdownButton onClick={() => this.props.onRename()}
							icon="sui-icon-pencil"
							text={__('Rename', 'wds')}/>,
			<DropdownButton onClick={() => this.props.onDuplicate()}
							icon="sui-icon-copy"
							text={__('Duplicate', 'wds')}/>,
			<DropdownButton onClick={() => this.props.onDelete()}
							icon="sui-icon-trash"
							text={__('Delete', 'wds')}
							red={true}/>,
		]}/>;
	}
}
