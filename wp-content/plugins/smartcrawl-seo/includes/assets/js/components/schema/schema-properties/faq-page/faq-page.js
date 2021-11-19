import {__} from '@wordpress/i18n';
import uniqueId from "lodash-es/uniqueId";
import FAQQuestion from "./faq-question";
import FAQComment from "./faq-comment";

const id = uniqueId;
const FAQPage = {
	mainEntity: {
		id: id(),
		label: __('Questions', 'wds'),
		labelSingle: __('Question', 'wds'),
		disallowDeletion: true,
		properties: {
			0: {
				id: id(),
				type: 'Question',
				disallowDeletion: true,
				disallowFirstItemDeletionOnly: true,
				properties: FAQQuestion
			}
		},
		required: true,
		description: __('An array of Question elements which comprise the list of answered questions that this FAQPage is about.', 'wds'),
	},
	comment: {
		id: id(),
		label: __('Comments', 'wds'),
		type: 'Comment',
		loop: 'post-comments',
		loopDescription: __('The following block will be repeated for each post comment'),
		properties: FAQComment,
		optional: true,
		description: __('Comments, typically from users.', 'wds'),
	},
};

export default FAQPage;
