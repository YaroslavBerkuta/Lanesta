import React from 'react';
import {__} from "@wordpress/i18n";

export default class SchemaTypesBoxFooter extends React.Component {
	render() {
		return (
			<div id="wds-save-schema-types" className="sui-box-footer">
				<button name="submit"
						type="submit"
						className="sui-button sui-button-blue">
					<span className="sui-icon-save" aria-hidden="true"/>

					{__('Save Settings', 'wds')}
				</button>
			</div>
		);
	}
}
