import React from "react";
import Button from "../button";
import {__} from "@wordpress/i18n";
import classnames from 'classnames';

export default class ConfigsBoxHeader extends React.Component {
	static defaultProps = {
		uploadInProgress: false,
		disabled: false,
		onUpload: () => false,
		onSave: () => false,
	};

	handleFileChange(e) {
		const file = e.target.files[0];
		this.props.onUpload(file);
	}

	render() {
		return <div className="sui-box-header">
			<h2 className="sui-box-title">{__('Configs', 'wds')}</h2>

			<div className="sui-actions-right">
				<label className={classnames("sui-button sui-button-ghost", {
					'sui-button-onload': this.props.uploadInProgress,
					'disabled': this.props.disabled
				})}
					   htmlFor="wds-upload-configs-input">
					<span className="sui-loading-text">
						<span className="sui-icon-upload-cloud" aria-hidden="true"/> {__('Upload', 'wds')}
					</span>

					<span className="sui-icon-loader sui-loading" aria-hidden="true"/>
				</label>

				<input id="wds-upload-configs-input"
					   type="file"
					   name="config_file"
					   className="sui-hidden"
					   readOnly=""
					   accept=".json"
					   onChange={(e) => this.handleFileChange(e)}
					   value=""/>

				<Button color="blue"
						onClick={() => this.props.onSave()}
						disabled={this.props.uploadInProgress || this.props.disabled}
						text={__('Save config', 'wds')}
				/>
			</div>
		</div>;
	}
}
