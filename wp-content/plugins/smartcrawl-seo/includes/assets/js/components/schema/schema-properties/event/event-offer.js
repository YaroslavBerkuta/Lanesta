import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const EventOffer = {
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
		description: __('The availability of event tickets.', 'wds'),
		disallowDeletion: true,
	},
	price: {
		id: id(),
		label: __('Price', 'wds'),
		type: 'Number',
		source: 'number',
		value: '',
		description: __("The lowest available price available for your tickets, including service charges and fees. Don't forget to update it as prices change or tickets sell out.", 'wds'),
		disallowDeletion: true,
	},
	priceCurrency: {
		id: id(),
		label: __('Price Currency Code', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The 3-letter ISO 4217 currency code.', 'wds'),
		disallowDeletion: true,
	},
	validFrom: {
		id: id(),
		label: __('Valid From', 'wds'),
		type: 'DateTime',
		source: 'datetime',
		value: '',
		description: __('The date and time when tickets go on sale (only required on date-restricted offers), in ISO-8601 format.', 'wds'),
		disallowDeletion: true,
	},
	priceValidUntil: {
		id: id(),
		label: __('Valid Until', 'wds'),
		type: 'DateTime',
		source: 'datetime',
		value: '',
		description: __('The date and time till when tickets will be on sale.', 'wds'),
		disallowDeletion: true,
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'post_data',
		value: 'post_permalink',
		description: __('The URL of a page providing the ability to buy tickets.', 'wds'),
		disallowDeletion: true,
	},
};

export default EventOffer;
