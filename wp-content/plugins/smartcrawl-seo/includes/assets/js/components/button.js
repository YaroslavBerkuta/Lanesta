import React from 'react';
import classnames from 'classnames';

export default class Button extends React.Component {
	static defaultProps = {
		id: '',
		text: '',
		color: '',
		dashed: false,
		icon: false,
		loading: false,
		ghost: false,
		disabled: false,
		className: '',
		onClick: () => false,
	}

	handleClick(e) {
		e.preventDefault();

		this.props.onClick();
	}

	render() {
		let icon = this.props.icon ? <span className={this.props.icon} aria-hidden="true"/> : '',
			text,
			loadingIcon;
		if (this.props.loading) {
			text = <span className="sui-loading-text">{icon} {this.props.text}</span>;
			loadingIcon = <span className="sui-icon-loader sui-loading" aria-hidden="true"/>;
		} else {
			text = <span>{icon} {this.props.text}</span>;
			loadingIcon = '';
		}

		return <button
			className={classnames(
				this.props.className,
				'sui-button',
				'sui-button-' + this.props.color,
				{
					'sui-button-onload': this.props.loading,
					'sui-button-ghost': this.props.ghost,
					'sui-button-icon': !this.props.text.trim(),
					'sui-button-dashed': this.props.dashed,
				}
			)}
			onClick={e => this.handleClick(e)}
			id={this.props.id}
			disabled={this.props.disabled}
		>
			{text}
			{loadingIcon}
		</button>
	}
}
