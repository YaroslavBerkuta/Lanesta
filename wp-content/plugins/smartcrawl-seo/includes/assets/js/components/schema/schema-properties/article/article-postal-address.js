import {__} from '@wordpress/i18n';
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const ArticlePostalAddress = {
	streetAddress: {
		id: id(),
		label: __('Street Address', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The street address. For example, 1600 Amphitheatre Pkwy.', 'wds'),
		disallowDeletion: true,
	},
	addressLocality: {
		id: id(),
		label: __('Address Locality', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The locality in which the street address is, and which is in the region. For example, Mountain View.', 'wds'),
		disallowDeletion: true,
	},
	addressRegion: {
		id: id(),
		label: __('Address Region', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The region in which the locality is, and which is in the country. For example, California or another appropriate first-level administrative division.', 'wds'),
		disallowDeletion: true,
	},
	addressCountry: {
		id: id(),
		label: __('Country', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The country. For example, USA. You can also provide the two-letter ISO 3166-1 alpha-2 country code.', 'wds'),
		disallowDeletion: true,
	},
	postalCode: {
		id: id(),
		label: __('Postal Code', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The postal code. For example, 94043.', 'wds'),
		disallowDeletion: true,
	},
	postOfficeBoxNumber: {
		id: id(),
		label: __('P.O. Box Number', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The post office box number for PO box addresses.', 'wds'),
		disallowDeletion: true,
	},
};
export default ArticlePostalAddress;
