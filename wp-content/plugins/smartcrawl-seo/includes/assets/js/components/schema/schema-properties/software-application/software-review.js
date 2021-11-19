import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";
import ReviewAuthorPerson from "../review/review-author-person";
import ReviewAuthorOrganization from "../review/review-author-organization";
import ReviewRating from "../review/review-rating";

const id = uniqueId;
const SoftwareReview = {
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
				source: 'custom_text',
				value: '',
				disallowDeletion: true,
				required: true,
				description: __('Name of the item that is being rated.', 'wds'),
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
		required: true,
		properties: {
			Person: {
				id: id(),
				label: __('Author', 'wds'),
				disallowDeletion: true,
				disallowAddition: true,
				type: 'Person',
				properties: ReviewAuthorPerson,
				required: true,
				description: __("The author of the review. The reviewer's name must be a valid name.", 'wds'),
				isAnAltVersion: true,
			},
			Organization: {
				id: id(),
				label: __('Author Organization', 'wds'),
				disallowDeletion: true,
				disallowAddition: true,
				type: 'Organization',
				properties: ReviewAuthorOrganization,
				required: true,
				description: __("The author of the review. The reviewer's name must be a valid name.", 'wds'),
				isAnAltVersion: true,
			}
		},
	},
	reviewRating: {
		id: id(),
		label: __('Rating', 'wds'),
		description: __('The rating given in this review.', 'wds'),
		type: 'Rating',
		disallowAddition: true,
		disallowDeletion: true,
		required: true,
		properties: ReviewRating,
	},
};

export default SoftwareReview;
