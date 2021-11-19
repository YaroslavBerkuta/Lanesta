import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";
import currencies from "../currencies";

const id = uniqueId;
const ProductAggregateOffer = {
	availability: {
		id: id(),
		label: __('Availability', 'wds'),
		type: 'Text',
		source: 'options',
		value: 'InStock',
		customSources: {
			options: {
				label: __('Availability', 'wds'),
				values: {
					InStock: __('In Stock', 'wds'),
					SoldOut: __('Sold Out', 'wds'),
					PreOrder: __('PreOrder', 'wds'),
				}
			}
		},
		description: __('The availability of this item.', 'wds'),
	},
	lowPrice: {
		id: id(),
		label: __('Low Price', 'wds'),
		type: 'Number',
		source: 'number',
		value: '',
		required: true,
		description: __('The lowest price of all offers available. Use a floating point number.', 'wds'),
	},
	highPrice: {
		id: id(),
		label: __('High Price', 'wds'),
		type: 'Number',
		source: 'number',
		value: '',
		description: __('The highest price of all offers available. Use a floating point number.', 'wds'),
	},
	priceCurrency: {
		id: id(),
		label: __('Price Currency', 'wds'),
		type: 'Text',
		source: 'options',
		value: 'USD',
		customSources: {
			options: {
				label: __('Currencies', 'wds'),
				values: currencies,
			},
		},
		required: true,
		description: __('The currency used to describe the price, in three-letter ISO 4217 format.', 'wds'),
	},
	offerCount: {
		id: id(),
		label: __('Offer Count', 'wds'),
		type: 'Number',
		source: 'number',
		value: '',
		description: __('The number of offers for the item.', 'wds'),
	},
};
export default ProductAggregateOffer;
