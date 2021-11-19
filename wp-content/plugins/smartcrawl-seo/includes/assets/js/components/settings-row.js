import React from "react";

export default class SettingsRow extends React.Component {
	static defaultProps = {
		label: '',
		description: ''
	};

	render() {
		return (
			<div className="sui-box-settings-row">
				<div className="sui-box-settings-col-1">
					<span className="sui-settings-label">{this.props.label}</span>
					<span className="sui-description">{this.props.description}</span>
				</div>

				<div className="sui-box-settings-col-2">
					{this.props.children}
				</div>
			</div>
		);
	}
}