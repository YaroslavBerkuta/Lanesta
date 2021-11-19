import {__} from "@wordpress/i18n";
import EventPostalAddress from "./event-postal-address";
import EventOrganizerContactPoint from "./event-organizer-contact-point";
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const EventOrganizerOrganization = {
	logo: {
		id: id(),
		label: __('Logo', 'wds'),
		type: 'ImageObject',
		source: 'schema_settings',
		value: 'organization_logo',
		description: __('The logo of the organization.', 'wds'),
	},
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'schema_settings',
		value: 'organization_name',
		description: __('The name of the organization.', 'wds'),
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'site_settings',
		value: 'site_url',
		description: __('The URL of the organization.', 'wds'),
	},
	address: {
		id: id(),
		label: __('Address', 'wds'),
		optional: true,
		description: __('The addresses of the organization.', 'wds'),
		properties: {
			0: {
				id: id(),
				type: 'PostalAddress',
				properties: EventPostalAddress
			}
		}
	},
	contactPoint: {
		id: id(),
		label: __('Contact Point', 'wds'),
		optional: true,
		description: __('The contact points of the organization.', 'wds'),
		properties: {
			0: {
				id: id(),
				type: 'ContactPoint',
				properties: EventOrganizerContactPoint,
			}
		}
	}
};
export default EventOrganizerOrganization;
