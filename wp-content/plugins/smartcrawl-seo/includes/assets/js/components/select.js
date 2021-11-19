import React from 'react';
import $ from 'jQuery';

export default class Select extends React.Component {
	static defaultProps = {
		small: false,
		tagging: false,
		placeholder: '',
		ajaxUrl: '',
		loadTextAjaxUrl: '',
		selectedValue: '',
		minimumResultsForSearch: 10,
		minimumInputLength: 3,
		multiple: false,
		options: {},
		templateResult: false,
		templateSelection: false,
		onSelect: () => false,
	};

	constructor(props) {
		super(props);

		this.props = props;
		this.state = {
			loadingText: false
		};
		this.selectElement = React.createRef();
		this.selectElementContainer = React.createRef();
	}

	componentDidMount() {
		const $select = $(this.selectElement.current);
		$select
			.addClass(this.props.small ? 'sui-select sui-select-sm' : 'sui-select')
			.SUIselect2(this.getSelect2Args());

		this.includeSelectedValueAsDynamicOption();

		$select.on('change', (e) => this.handleChange(e));
	}

	includeSelectedValueAsDynamicOption() {
		if (this.props.selectedValue && this.noOptionsAvailable()) {
			if (this.props.tagging) {
				if (Array.isArray(this.props.selectedValue)) {
					this.props.selectedValue.forEach(selected => {
						this.addOption(selected, selected, true);
					});
				} else {
					this.addOption(this.props.selectedValue, this.props.selectedValue, true);
				}
			} else if (this.props.loadTextAjaxUrl) {
				this.loadTextFromRemote();
			}
		}
	}

	loadTextFromRemote() {
		if (this.state.loadingText) {
			// Already in the middle of a remote call
			return;
		}

		this.setState({loadingText: true});

		$.get(this.props.loadTextAjaxUrl, {
			'id': this.props.selectedValue
		}).done((data) => {
			if (data && data.results && data.results.length) {
				data.results.forEach((result) => {
					this.addOption(result.id, result.text, true);
				});
			}

			this.setState({loadingText: false});
		});
	}

	addOption(value, text, selected) {
		let newOption = new Option(text, value, false, selected);
		$(this.selectElement.current).append(newOption).trigger('change');
	}

	noOptionsAvailable() {
		return !Object.keys(this.props.options).length;
	}

	componentWillUnmount() {
		const $select = $(this.selectElement.current);
		$select.off().SUIselect2('destroy');
	}

	getSelect2Args() {
		const $container = $(this.selectElementContainer.current);

		let args = {
			dropdownParent: $container,
			dropdownCssClass: 'sui-select-dropdown',
			minimumResultsForSearch: this.props.minimumResultsForSearch,
			multiple: this.props.multiple,
			tagging: this.props.tagging
		};

		if (this.props.placeholder) {
			args['placeholder'] = this.props.placeholder;
		}

		if (this.props.ajaxUrl) {
			args['ajax'] = {url: this.props.ajaxUrl};
			args['minimumInputLength'] = this.props.minimumInputLength;
		}

		if (this.props.templateResult) {
			args['templateResult'] = this.props.templateResult;
		}

		if (this.props.templateSelection) {
			args['templateSelection'] = this.props.templateSelection;
		}

		if (this.props.ajaxUrl && this.props.tagging) {
			args['ajax']['processResults'] = (response, request) => {
				if (response.results && !response.results.length) {
					return {
						results: [{
							id: request.term,
							text: request.term
						}]
					}
				}

				return response;
			};
		}

		return args;
	}

	handleChange(e) {
		let value = e.target.value;
		if (this.props.multiple) {
			value = Array.from(e.target.selectedOptions, option => option.value);
		}

		this.props.onSelect(value);
	}

	isOptGroup(value) {
		return typeof value === 'object'
			&& value.label
			&& value.options;
	}

	printOptions(options) {
		return Object.keys(options).map((key) => {
			const value = options[key];

			if (this.isOptGroup(value)) {
				return <optgroup key={key}
								 label={value.label}>
					{this.printOptions(value.options)}
				</optgroup>;
			} else {
				return <option key={key}
							   value={key}>
					{value}
				</option>;
			}
		});
	}

	render() {
		const optionsProp = this.props.options;
		let options;
		if (Object.keys(optionsProp).length) {
			options = this.printOptions(optionsProp);
		} else {
			options = <option/>;
		}

		return <div ref={this.selectElementContainer}>
			<select disabled={this.state.loadingText}
					value={this.props.selectedValue}
					onChange={() => true}
					ref={this.selectElement}
					multiple={this.props.multiple}>{options}</select>
		</div>;
	}
}
