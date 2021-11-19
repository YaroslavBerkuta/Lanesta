import {__} from '@wordpress/i18n';

const TextFull = {
	id: 'TextFull',
	sources: {
		author: {
			label: __('Post Author', 'wds'),
			values: {
				author_full_name: __('Full Name', 'wds'),
				author_first_name: __('First Name', 'wds'),
				author_last_name: __('Last Name', 'wds'),
				author_description: __('Description', 'wds'),
			}
		},
		post_data: {
			label: __('Post Data', 'wds'),
			values: {
				post_title: __('Post Title', 'wds'),
				post_content: __('Post Content', 'wds'),
				post_excerpt: __('Post Excerpt', 'wds'),
			}
		},
		post_meta: {
			label: __('Post Meta', 'wds')
		},
		schema_settings: {
			label: __('Schema Settings', 'wds'),
			values: {
				organization_name: __('Organization Name', 'wds'),
				organization_description: __('Organization Description', 'wds'),
			}
		},
		seo_meta: {
			label: __('SEO Meta', 'wds'),
			values: {
				seo_title: __('SEO Title', 'wds'),
				seo_description: __('SEO Description', 'wds'),
			}
		},
		site_settings: {
			label: __('Site Settings', 'wds'),
			values: {
				site_name: __('Site Name', 'wds'),
				site_description: __('Site Description', 'wds'),
			}
		},
		custom_text: {
			label: __('Custom Text', 'wds')
		},
	}
};
export default TextFull;
