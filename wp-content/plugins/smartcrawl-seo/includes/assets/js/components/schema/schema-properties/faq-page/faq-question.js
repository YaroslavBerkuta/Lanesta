import {__} from '@wordpress/i18n';
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const FAQQuestion = {
	name: {
		id: id(),
		label: __('Question', 'wds'),
		type: 'TextFull',
		disallowDeletion: true,
		source: 'custom_text',
		value: '',
		description: __('The full text of the question. For example, "How long does it take to process a refund?".', 'wds'),
		required: true,
	},
	acceptedAnswer: {
		id: id(),
		label: __('Accepted Answer', 'wds'),
		type: 'Answer',
		flatten: true,
		properties: {
			text: {
				id: id(),
				label: __('Accepted Answer', 'wds'),
				type: 'TextFull',
				disallowDeletion: true,
				source: 'custom_text',
				value: '',
				description: __('The answer to the question.', 'wds'),
				required: true,
			}
		},
		required: true,
	},
	image: {
		id: id(),
		label: __('Image', 'wds'),
		type: 'ImageObject',
		source: 'image',
		value: '',
		description: __('An image associated with the question.'),
		disallowDeletion: true,
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'custom_text',
		value: '',
		description: __('Optional URL to the question.'),
		disallowDeletion: true,
	},
};
export default FAQQuestion;
