import {__} from "@wordpress/i18n";
import currencies from "../currencies";
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const JobSalaryMonetaryAmount = {
	currency: {
		id: id(),
		label: __('Currency', 'wds'),
		type: 'Text',
		source: 'options',
		value: 'USD',
		customSources: {
			options: {
				label: __('Currencies', 'wds'),
				values: currencies,
			},
		},
		description: __('The currency of the base salary.', 'wds'),
		disallowDeletion: true,
	},
	value: {
		id: id(),
		label: __('Currency', 'wds'),
		type: 'QuantitativeValue',
		flatten: true,
		disallowDeletion: true,
		properties: {
			value: {
				id: id(),
				label: __('Value', 'wds'),
				type: 'Number',
				source: 'number',
				value: '',
				disallowDeletion: true,
				description: __('To specify a salary range, define a minValue and a maxValue, rather than a single value.', 'wds'),
			},
			minValue: {
				id: id(),
				label: __('Minimum Value', 'wds'),
				type: 'Number',
				source: 'number',
				value: '',
				disallowDeletion: true,
				description: __('Use in combination with maxValue to provide a salary range.', 'wds'),
			},
			maxValue: {
				id: id(),
				label: __('Maximum Value', 'wds'),
				type: 'Number',
				source: 'number',
				value: '',
				disallowDeletion: true,
				description: __('Use in combination with minValue to provide a salary range.', 'wds'),
			},
			unitText: {
				id: id(),
				label: __('Unit', 'wds'),
				type: 'Text',
				source: 'options',
				value: 'HOUR',
				disallowDeletion: true,
				customSources: {
					options: {
						label: __('Unit', 'wds'),
						values: {
							HOUR: __('Hour', 'wds'),
							DAY: __('Day', 'wds'),
							WEEK: __('Week', 'wds'),
							MONTH: __('Month', 'wds'),
							YEAR: __('Year', 'wds'),
						},
					},
				},
			},
		},
	},
};
export default JobSalaryMonetaryAmount;
