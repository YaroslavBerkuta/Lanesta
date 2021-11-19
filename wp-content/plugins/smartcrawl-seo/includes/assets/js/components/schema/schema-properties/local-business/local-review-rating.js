import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const LocalReviewRating = {
	ratingValue: {
		id: id(),
		label: __('Rating Value', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		disallowDeletion: true,
		description: __('A numerical quality rating for the item, either a number, fraction, or percentage (for example, "4", "60%", or "6 / 10").', 'wds'),
		required: true,
	},
	bestRating: {
		id: id(),
		label: __('Best Rating', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		disallowDeletion: true,
		description: __('The highest value allowed in this rating system. If omitted, 5 is assumed.', 'wds'),
	},
	worstRating: {
		id: id(),
		label: __('Worst Rating', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		disallowDeletion: true,
		description: __('The lowest value allowed in this rating system. If omitted, 1 is assumed.', 'wds'),
	},
};
export default LocalReviewRating;
