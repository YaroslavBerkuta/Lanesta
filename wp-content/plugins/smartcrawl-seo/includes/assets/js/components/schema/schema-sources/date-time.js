import {__} from '@wordpress/i18n';

const DateTime = {
	id: 'DateTime',
	sources: {
		post_data: {
			label: __('Post Data', 'wds'),
			values: {
				post_date: __('Post Date', 'wds'),
				post_date_gmt: __('Post Date GMT', 'wds'),
				post_modified: __('Post Modified', 'wds'),
				post_modified_gmt: __('Post Modified GMT', 'wds'),
			}
		},
		post_meta: {
			label: __('Post Meta', 'wds'),
		},
		datetime: {
			label: __('Custom Date', 'wds'),
		},
		custom_text: {
			label: __('Custom Text', 'wds'),
		}
	}
};
export default DateTime;
