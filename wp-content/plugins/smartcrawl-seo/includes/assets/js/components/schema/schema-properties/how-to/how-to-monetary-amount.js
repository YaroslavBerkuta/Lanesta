import {__} from '@wordpress/i18n';
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const HowToMonetaryAmount = {
	value: {
		id: id(),
		label: __('Value', 'wds'),
		type: 'Number',
		source: 'number',
		value: '',
		disallowDeletion: true,
		description: __('The monetary amount value.', 'wds'),
	},
	currency: {
		id: id(),
		label: __('Currency', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		disallowDeletion: true,
		description: __('The currency in which the monetary amount is expressed.', 'wds')
	},
	maxValue: {
		id: id(),
		label: __('Max Value', 'wds'),
		type: 'Number',
		source: 'number',
		value: '',
		disallowDeletion: true,
		description: __('The upper limit of the value.', 'wds'),
	},
	minValue: {
		id: id(),
		label: __('Min Value', 'wds'),
		type: 'Number',
		source: 'number',
		value: '',
		disallowDeletion: true,
		description: __('The lower limit of the value.', 'wds'),
	}
};
export default HowToMonetaryAmount;
