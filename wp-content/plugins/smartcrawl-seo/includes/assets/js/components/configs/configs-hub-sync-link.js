import React from "react";
import {createInterpolateElement} from "@wordpress/element";
import {__} from "@wordpress/i18n";

export default class ConfigsHubSyncLink extends React.Component {
	static defaultProps = {
		onClick: () => false,
	};

	handleClick(e) {
		e.preventDefault();

		this.props.onClick();
	}

	render() {
		return <p className="sui-description">
			{createInterpolateElement(
				__('Created or updated configs via the Hub? <a>Check again</a>', 'wds'),
				{a: <a onClick={(e) => this.handleClick(e)} href="#"/>}
			)}
		</p>;
	}
}
