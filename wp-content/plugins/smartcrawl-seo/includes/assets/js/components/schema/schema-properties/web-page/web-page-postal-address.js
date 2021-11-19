import {__} from '@wordpress/i18n';
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const WebPagePostalAddress = {
	streetAddress: {
		id: id(),
		label: __('Street Address', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
	},
	addressLocality: {
		id: id(),
		label: __('Address Locality', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
	},
	addressRegion: {
		id: id(),
		label: __('Province/State', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
	},
	addressCountry: {
		id: id(),
		label: __('Country', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
	},
	postalCode: {
		id: id(),
		label: __('Postal Code', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
	},
	postOfficeBoxNumber: {
		id: id(),
		label: __('P.O. Box Number', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
	}
};
export default WebPagePostalAddress;
