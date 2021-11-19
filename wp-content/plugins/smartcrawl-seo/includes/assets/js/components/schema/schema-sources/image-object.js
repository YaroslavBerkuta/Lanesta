import {__} from '@wordpress/i18n';

const ImageObject = {
	id: 'ImageObject',
	sources: {
		author: {
			label: __('Post Author', 'wds'),
			values: {
				author_gravatar: __('Gravatar', 'wds'),
			},
		},
		post_data: {
			label: __('Post Data', 'wds'),
			values: {
				post_thumbnail: __('Featured Image', 'wds'),
			},
		},
		post_meta: {
			label: __('Post Meta', 'wds'),
		},
		schema_settings: {
			label: __('Schema Settings', 'wds'),
			values: {
				organization_logo: __('Organization Logo', 'wds'),
			},
		},
		image: {
			label: __('Custom Image', 'wds'),
		},
	}
};
export default ImageObject;
