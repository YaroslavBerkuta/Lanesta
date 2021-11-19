import React from "react";
import Dropdown from "../dropdown";
import DropdownButton from "../dropdown-button";
import {__} from "@wordpress/i18n";

export default class RedirectItem extends React.Component {
	static defaultProps = {
		source: '',
		destination: '',
		type: '',
		selected: false,
		onToggle: () => false,
		onEdit: () => false,
		onDelete: () => false,
	};

	render() {
		return <div className="sui-builder-field">
			<label className="sui-checkbox">
				<input type="checkbox"
					   checked={this.props.selected}
					   onChange={(e) => this.props.onToggle(e.target.checked)}/>
				<span aria-hidden="true"/>
			</label>

			<div className="sui-builder-field-label">{this.props.source}</div>

			<small>{this.props.destination}</small>

			<span className="wds-redirect-type-label">
				{this.props.type === '301' && <small>{__('Permanent', 'wds')}</small>}
				{this.props.type === '302' && <small>{__('Temporary', 'wds')}</small>}
			</span>

			<Dropdown buttons={[
				<DropdownButton icon="sui-icon-pencil"
								text={__('Edit', 'wds')}
								onClick={() => this.props.onEdit()}/>,
				<DropdownButton icon="sui-icon-trash"
								text={__('Remove', 'wds')}
								red={true}
								onClick={() => this.props.onDelete()}/>
			]}/>
		</div>;
	}
}
