import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";
import currencies from "../currencies";
import merge from "lodash-es/merge";

const id = uniqueId;
const CourseOffer = {
	price: {
		id: id(),
		label: __('Price Value', 'wds'),
		type: 'Number',
		source: 'number',
		value: '',
		description: __("The price for attending this course.", 'wds'),
		disallowDeletion: true,
	},
	priceCurrency: {
		id: id(),
		label: __('Price Currency Code', 'wds'),
		type: 'Text',
		source: 'options',
		value: '',
		description: __('The 3-letter ISO 4217 currency code.', 'wds'),
		disallowDeletion: true,
		customSources: {
			options: {
				label: __('Currencies', 'wds'),
				values: merge({"": __('None', 'wds')}, currencies)
			}
		},
	},
};
export default CourseOffer;
