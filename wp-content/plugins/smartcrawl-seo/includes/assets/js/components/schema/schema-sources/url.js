import {__} from '@wordpress/i18n';

const URL = {
	id: 'URL',
	sources: {
		author: {
			label: __('Post Author', 'wds'),
			values: {
				author_url: __('Profile URL', 'wds'),
			},
		},
		post_data: {
			label: __('Post Data', 'wds'),
			values: {
				post_permalink: __('Post Permalink', 'wds'),
			},
		},
		post_meta: {
			label: __('Post Meta', 'wds'),
		},
		site_settings: {
			label: __('Site Settings', 'wds'),
			values: {
				site_url: __('Site URL', 'wds'),
			},
		},
		custom_text: {
			label: __('Custom URL', 'wds'),
		}
	}
};
export default URL;
