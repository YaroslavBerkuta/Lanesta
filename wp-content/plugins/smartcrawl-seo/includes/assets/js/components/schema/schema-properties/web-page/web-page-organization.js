import {__} from '@wordpress/i18n';
import uniqueId from "lodash-es/uniqueId";
import WebPagePostalAddress from "./web-page-postal-address";
import WebPageContactPoint from "./web-page-contact-point";

const id = uniqueId;
const WebPageOrganization = {
	logo: {
		id: id(),
		label: __('Logo', 'wds'),
		type: 'ImageObject',
		source: 'schema_settings',
		value: 'organization_logo'
	},
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'schema_settings',
		value: 'organization_name'
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'site_settings',
		value: 'site_url',
	},
	address: {
		id: id(),
		label: __('Address', 'wds'),
		optional: true,
		properties: {
			0: {
				id: id(),
				type: 'PostalAddress',
				properties: WebPagePostalAddress
			}
		}
	},
	contactPoint: {
		id: id(),
		label: __('Contact Point', 'wds'),
		optional: true,
		properties: {
			0: {
				id: id(),
				type: 'ContactPoint',
				properties: WebPageContactPoint
			}
		}
	}
};
export default WebPageOrganization;
