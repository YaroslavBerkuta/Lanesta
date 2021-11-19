import {__} from '@wordpress/i18n';
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const LocalAggregateRating = {
	itemReviewed: {
		id: id(),
		label: __('Reviewed Item', 'wds'),
		flatten: true,
		properties: {
			name: {
				id: id(),
				label: __('Reviewed Item', 'wds'),
				type: 'TextFull',
				source: 'site_settings',
				value: 'site_name',
				required: true,
				description: __('Name of the item that is being rated. In this case the local business.', 'wds'),
			},
		},
		required: true,
	},
	ratingCount: {
		id: id(),
		label: __('Rating Count', 'wds'),
		type: 'Number',
		source: 'number',
		value: '',
		customSources: {
			post_data: {
				label: __('Post Data', 'wds'),
				values: {
					post_comment_count: __('Post Comment Count', 'wds'),
				}
			},
		},
		required: true,
		description: __('The total number of ratings for the local business.', 'wds'),
	},
	reviewCount: {
		id: id(),
		label: __('Review Count', 'wds'),
		type: 'Number',
		source: 'number',
		value: '',
		customSources: {
			post_data: {
				label: __('Post Data', 'wds'),
				values: {
					post_comment_count: __('Post Comment Count', 'wds'),
				}
			},
		},
		required: true,
		description: __('Specifies the number of people who provided a review with or without an accompanying rating.', 'wds'),
	},
	ratingValue: {
		id: id(),
		label: __('Rating Value', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		required: true,
		requiredInBlock: true,
		description: __('A numerical quality rating for the local business, either a number, fraction, or percentage (for example, "4", "60%", or "6 / 10").', 'wds'),
	},
	bestRating: {
		id: id(),
		label: __('Best Rating', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The highest value allowed in this rating system. If omitted, 5 is assumed.', 'wds'),
	},
	worstRating: {
		id: id(),
		label: __('Worst Rating', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The lowest value allowed in this rating system. If omitted, 1 is assumed.', 'wds'),
	}
};

export default LocalAggregateRating;
