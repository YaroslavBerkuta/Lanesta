import React from "react";
import classNames from "classnames";

export default class SideTabs extends React.Component {
	static defaultProps = {
		tabs: {},
		value: '',
		onChange: () => false
	};

	render() {
		const tabs = this.props.tabs;

		return <div className="sui-side-tabs">
			<div className="sui-tabs-menu">
				{Object.keys(tabs).map(tabKey => {
					return <div className={classNames('sui-tab-item', {
						active: this.props.value === tabKey
					})} key={tabKey} onClick={() => this.props.onChange(tabKey)}>

						{tabs[tabKey]}
					</div>
				})}
			</div>

			{this.props.children && <div className="sui-tab-content sui-tab-boxed">
				{this.props.children}
			</div>}
		</div>;
	}
}
