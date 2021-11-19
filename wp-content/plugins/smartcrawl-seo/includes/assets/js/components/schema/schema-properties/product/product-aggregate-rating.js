import uniqueId from "lodash-es/uniqueId";
import {__} from "@wordpress/i18n";

const id = uniqueId;
const ProductAggregateRating = {
	itemReviewed: {
		id: id(),
		label: __('Reviewed Item', 'wds'),
		flatten: true,
		properties: {
			name: {
				id: id(),
				label: __('Reviewed Item', 'wds'),
				type: 'TextFull',
				source: 'post_data',
				value: 'post_title',
				required: true,
				description: __('The item that is being rated.', 'wds'),
			}
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
		requiredNotice: __('This property is required by Google. At least one of ratingCount or reviewCount is required.', 'wds'),
		description: __('The total number of ratings for the item on your site.', 'wds'),
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
		requiredNotice: __('This property is required by Google. At least one of ratingCount or reviewCount is required.', 'wds'),
		description: __('Specifies the number of people who provided a review with or without an accompanying rating.', 'wds'),
	},
	ratingValue: {
		id: id(),
		label: __('Rating Value', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		requiredInBlock: true,
		required: true,
		description: __('A numerical quality rating for the item, either a number, fraction, or percentage (for example, "4", "60%", or "6 / 10").', 'wds'),
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
	},
};
export default ProductAggregateRating;
