import React from 'react';
import {__} from "@wordpress/i18n";
import Button from "../button";

export default class SchemaTypePropertiesTable extends React.Component {
	static defaultProps = {
		onReset: () => false,
		onAdd: () => false
	};

	render() {
		return <table className="sui-table">
			<thead>
			<tr>
				<th>{__('Property', 'wds')}</th>
				<th>{__('Source', 'wds')}</th>
				<th colSpan={2}>{__('Value', 'wds')}</th>
			</tr>
			</thead>

			<tbody>{this.props.children}</tbody>

			<tfoot>
			<tr>
				<td colSpan={4}>
					<div>
						<span className="sui-tooltip" data-tooltip={__('Reset the properties list to default.', 'wds')}>
						<Button ghost={true}
								onClick={() => this.props.onReset()}
								icon="sui-icon-refresh"
								text={__('Reset Properties', 'wds')}
						/>
						</span>

						<Button icon="sui-icon-plus"
								onClick={() => this.props.onAdd()}
								text={__('Add Property', 'wds')}
						/>
					</div>
				</td>
			</tr>
			</tfoot>
		</table>;
	}
}
