import React from 'react';
import {Duration} from "luxon";

export default class DurationSelector extends React.Component {
	static defaultProps = {
		value: '',
		onChange: () => false
	};

	constructor(props) {
		super(props);
	}

	handleValueChange(part, newValue) {
		const duration = !this.props.value
			? new Duration({values: {}})
			: Duration.fromISO(this.props.value);

		if (newValue < 0) {
			newValue = 0;
		} else if (newValue > 59 && ['minutes', 'seconds'].includes(part)) {
			newValue = 59;
		}

		const newISO = duration.set({[part]: newValue ? newValue : 0}).toISO();

		this.props.onChange(newISO === 'PT0S' ? '' : newISO);
	}

	render() {
		const duration = Duration.fromISO(this.props.value);

		return <React.Fragment>
			<span>
				<input type="number"
					   value={duration.isValid ? duration.hours : 0}
					   onChange={(event) => this.handleValueChange('hours', event.target.value)}
				/>
			</span>

			<span>
				<input type="number"
					   value={duration.isValid ? duration.minutes : 0}
					   onChange={(event) => this.handleValueChange('minutes', event.target.value)}
				/>
			</span>

			<span>
				<input type="number"
					   value={duration.isValid ? duration.seconds : 0}
					   onChange={(event) => this.handleValueChange('seconds', event.target.value)}
				/>
			</span>
		</React.Fragment>;
	}
}
