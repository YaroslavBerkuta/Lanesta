import React from 'react';

export default class Text extends React.Component {
	static defaultProps = {
		placeholder: '',
		value: '',
		onChange: () => false
	}

	constructor(props) {
		super(props);

		this.props = props;
	}

	handleFocus(e) {
		this.adjustElementHeight(e.target);
	}

	handleChange(e) {
		const target = e.target;
		this.adjustElementHeight(target);
		this.props.onChange(target.value);
	}

	adjustElementHeight(element) {
		element.style.height = 0;

		const scrollHeight = element.scrollHeight;
		element.style.height = (scrollHeight < 30 ? 30 : scrollHeight) + 'px';
	}

	render() {
		return <textarea placeholder={this.props.placeholder}
						 value={this.props.value}
						 onFocus={e => this.handleFocus(e)}
						 onChange={e => this.handleChange(e)}/>
	}
}
