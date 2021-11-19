import {__} from '@wordpress/i18n';
import uniqueId from "lodash-es/uniqueId";
import LocalReviewRating from "./local-review-rating";
import LocalReviewAuthorOrganization from "./local-review-author-organization";
import LocalReviewAuthorPerson from "./local-review-author-person";

const id = uniqueId;
const LocalReview = {
	itemReviewed: {
		id: id(),
		label: __('Reviewed Item', 'wds'),
		flatten: true,
		required: true,
		properties: {
			name: {
				id: id(),
				label: __('Reviewed Item', 'wds'),
				type: 'TextFull',
				source: 'site_settings',
				value: 'site_name',
				disallowDeletion: true,
				required: true,
				description: __('Name of the item that is being rated. In this case the local business.', 'wds'),
			}
		},
	},
	reviewBody: {
		id: id(),
		label: __('Review Body', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		disallowDeletion: true,
		description: __('The actual body of the review.', 'wds'),
	},
	datePublished: {
		id: id(),
		label: __('Date Published', 'wds'),
		type: 'DateTime',
		source: 'datetime',
		value: '',
		disallowDeletion: true,
		description: __('The date that the review was published, in ISO 8601 date format.', 'wds'),
	},
	author: {
		id: id(),
		label: __('Author', 'wds'),
		activeVersion: 'Person',
		properties: {
			Person: {
				id: id(),
				label: __('Author', 'wds'),
				disallowDeletion: true,
				disallowAddition: true,
				type: 'Person',
				properties: LocalReviewAuthorPerson,
				description: __("The author of the review. The reviewer's name must be a valid name.", 'wds'),
				required: true, // This is only going to be shown as required and not going to be validated because parent is not required
				isAnAltVersion: true,
			},
			Organization: {
				id: id(),
				label: __('Author Organization', 'wds'),
				disallowDeletion: true,
				disallowAddition: true,
				type: 'Organization',
				properties: LocalReviewAuthorOrganization,
				description: __("The author of the review. The reviewer's name must be a valid name.", 'wds'),
				required: true, // This is only going to be shown as required and not going to be validated because parent is not required
				isAnAltVersion: true,
			}
		},
	},
	reviewRating: {
		id: id(),
		label: __('Rating', 'wds'),
		type: 'Rating',
		disallowAddition: true,
		disallowDeletion: true,
		properties: LocalReviewRating,
		description: __('The rating given in this review.', 'wds'),
		required: true,
	},
};

export default LocalReview;
