import {__} from '@wordpress/i18n';

const Phone = {
	id: 'Phone',
	sources: {
		post_meta: {
			label: __('Post Meta', 'wds'),
		},
		schema_settings: {
			label: __('Schema Settings', 'wds'),
			values: {
				organization_phone_number: __('Organization Phone Number', 'wds'),
			},
		},
		custom_text: {
			label: __('Custom Phone', 'wds'),
		},
	},
};
export default Phone;
