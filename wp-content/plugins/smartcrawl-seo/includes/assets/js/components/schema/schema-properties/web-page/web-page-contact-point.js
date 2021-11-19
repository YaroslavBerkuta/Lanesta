import {__} from '@wordpress/i18n';
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const WebPageContactPoint = {
	telephone: {
		id: id(),
		label: __('Phone Number', 'wds'),
		type: 'Phone',
		source: 'schema_settings',
		value: 'organization_phone_number',
	},
	email: {
		id: id(),
		label: __('Email', 'wds'),
		type: 'Email',
		source: 'site_settings',
		value: 'site_admin_email',
	},
	url: {
		id: id(),
		label: __('Contact URL', 'wds'),
		type: 'URL',
		source: 'site_settings',
		value: 'site_url',
	},
	contactType: {
		id: id(),
		label: __('Contact Type', 'wds'),
		type: 'Text',
		source: 'options',
		value: 'customer support',
		customSources: {
			options: {
				label: __('Contact Type', 'wds'),
				values: {
					"customer support": __('Customer Support', 'wds'),
					"technical support": __('Technical Support', 'wds'),
					"billing support": __('Billing Support', 'wds'),
					"bill payment": __('Bill payment', 'wds'),
					"sales": __('Sales', 'wds'),
					"reservations": __('Reservations', 'wds'),
					"credit card support": __('Credit Card Support', 'wds'),
					"emergency": __('Emergency', 'wds'),
					"baggage tracking": __('Baggage Tracking', 'wds'),
					"roadside assistance": __('Roadside Assistance', 'wds'),
					"package tracking": __('Package Tracking', 'wds'),
				}
			}
		},
	}
};
export default WebPageContactPoint;
