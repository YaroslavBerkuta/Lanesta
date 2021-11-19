import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const ReviewAuthorPerson = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'custom_text',
		value: '',
		description: __('The name of the review author.', 'wds'),
		disallowDeletion: true,
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'custom_text',
		value: '',
		description: __("The URL to the review author's page.", 'wds'),
		disallowDeletion: true,
	},
	description: {
		id: id(),
		label: __('Description', 'wds'),
		type: 'TextFull',
		source: 'custom_text',
		value: '',
		optional: true,
		description: __('Short bio/description of the review author.', 'wds'),
		disallowDeletion: true,
	},
	image: {
		id: id(),
		label: __('Image', 'wds'),
		type: 'ImageObject',
		source: 'image',
		value: '',
		description: __('An image of the review author.', 'wds'),
		disallowDeletion: true,
	}
};

export default ReviewAuthorPerson;
