import React from 'react';
import classnames from 'classnames';

export default class DropdownButton extends React.Component {
	static defaultProps = {
		text: '',
		icon: '',
		red: false,
		onClick: () => false,
	};

	render() {
		return <button
			className={classnames({'sui-option-red': this.props.red})}
			onClick={() => this.props.onClick()}
			type="button">

			<span className={this.props.icon} aria-hidden="true"/>
			{this.props.text}
		</button>;
	}
}