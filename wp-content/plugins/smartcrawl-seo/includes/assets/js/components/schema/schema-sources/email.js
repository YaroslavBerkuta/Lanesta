import {__} from '@wordpress/i18n';

const Email = {
	id: 'Email',
	sources: {
		author: {
			label: __('Post Author', 'wds'),
			values: {
				author_email: __('Email', 'wds'),
			},
		},
		post_meta: {
			label: __('Post Meta', 'wds'),
		},
		site_settings: {
			label: __('Site Settings', 'wds'),
			values: {
				site_admin_email: __('Site Admin Email', 'wds')
			},
		},
		custom_text: {
			label: __('Custom Email', 'wds'),
		},
	},
};
export default Email;
