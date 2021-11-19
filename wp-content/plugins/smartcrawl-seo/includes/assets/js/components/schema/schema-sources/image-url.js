import {__} from '@wordpress/i18n';

const ImageURL = {
	id: 'ImageURL',
	sources: {
		author: {
			label: __('Post Author', 'wds'),
			values: {
				author_gravatar_url: __('Gravatar URL', 'wds'),
			},
		},
		post_data: {
			label: __('Post Data', 'wds'),
			values: {
				post_thumbnail_url: __('Featured Image URL', 'wds'),
			},
		},
		post_meta: {
			label: __('Post Meta', 'wds'),
		},
		schema_settings: {
			label: __('Schema Settings', 'wds'),
			values: {
				organization_logo_url: __('Organization Logo URL', 'wds'),
			},
		},
		image_url: {
			label: __('Custom Image URL', 'wds'),
		},
		custom_text: {
			label: __('Custom URL', 'wds')
		}
	}
};
export default ImageURL;
