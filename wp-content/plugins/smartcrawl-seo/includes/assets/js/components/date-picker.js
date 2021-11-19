import React from 'react';
import $ from 'jQuery';
import {__} from '@wordpress/i18n';

export default class DatePicker extends React.Component {
	static defaultProps = {
		placeholder: __('Select a date', 'wds'),
		format: 'yy-mm-dd',
		value: '',
		onChange: () => false,
	}

	constructor(props) {
		super(props);

		this.pickerElement = React.createRef();
	}

	componentDidMount() {
		const $picker = $(this.pickerElement.current);
		$picker.datepicker({
			beforeShow: () => {
				$('#ui-datepicker-div').addClass('sui-calendar');
			},
			'onSelect': (date) => {
				this.props.onChange(date);
			},
			'dateFormat': this.props.format
		});
	}

	componentWillUnmount() {
		const $picker = $(this.pickerElement.current);
		$picker.datepicker("destroy");
	}

	render() {
		return <div className="sui-date">
			<input
				type="text"
				placeholder={this.props.placeholder}
				className="sui-form-control"
				ref={this.pickerElement}
				onChange={() => true}
				value={this.props.value}
			/>
			<span className="sui-icon-calendar" aria-hidden="true"/>
		</div>;
	}
}
