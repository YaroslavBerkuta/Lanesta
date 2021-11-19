import React from 'react';
import classnames from 'classnames';

export default class BoxSelector extends React.Component {
	static defaultProps = {
		id: '',
		selectedValues: [],
		cols: 2,
		options: {},
		onChange: () => false,
		multiple: true,
	};

	constructor(props) {
		super(props);

		this.props = props;
	}

	handleChange(e) {
		const selectedValues = this.props.selectedValues.slice();
		const checked = e.target.checked;
		const value = e.target.value;

		if (checked && !selectedValues.includes(value)) {
			selectedValues.push(value);

			this.props.onChange(this.props.multiple ? selectedValues : [value]);
		}

		if (!checked && selectedValues.includes(value)) {
			this.props.onChange(
				selectedValues.filter((value) => value !== value)
			);
		}
	}

	render() {
		const optionsProp = this.props.options;
		let boxes = optionsProp.map((option) =>
			<li key={option.id}>
				<label className={classnames({
					"sui-box-selector": true,
					"sui-disabled": option.disabled
				})}
					   htmlFor={this.props.id + '-' + option.id}>

					<input onChange={e => this.handleChange(e)}
						   id={this.props.id + '-' + option.id}
						   type="checkbox"
						   disabled={option.disabled}
						   checked={this.props.selectedValues.includes(option.id)}
						   value={option.id}
					/>
					<span>
						{option.icon && <span className={option.icon}/>}
						{option.label}
						{option.required &&
						<span className="wds-required-asterisk">*</span>
						}
					</span>
				</label>
			</li>
		);

		return <div className={'sui-box-selectors sui-box-selectors-col-' + this.props.cols}>
			<ul>{boxes}</ul>
		</div>;
	}
}
