import React from "react";
import Configs from "./configs";
import ConfigsTab from "./configs-tab";

export default class ConfigsTabWrapper extends React.Component {
	render() {
		return <Configs mainComponent={ConfigsTab}/>;
	}
}
