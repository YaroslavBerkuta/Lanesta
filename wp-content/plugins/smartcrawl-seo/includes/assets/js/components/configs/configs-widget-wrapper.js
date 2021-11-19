import React from "react";
import Configs from "./configs";
import ConfigsWidget from "./configs-widget";

export default class ConfigsWidgetWrapper extends React.Component {
	render() {
		return <Configs mainComponent={ConfigsWidget}/>;
	}
}
