import {__} from '@wordpress/i18n';
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const FAQComment = {
	"text": {
		id: id(),
		label: __('Text', 'wds'),
		type: 'Text',
		source: 'comment',
		value: 'comment_text',
		customSources: {
			comment: {
				label: __('Comment', 'wds'),
				values: {
					comment_text: __('Comment Content', 'wds'),
				}
			},
		},
		description: __('The body of the comment.', 'wds'),
	},
	"dateCreated": {
		id: id(),
		label: __('Date Created', 'wds'),
		type: 'Text',
		source: 'comment',
		value: 'comment_date',
		customSources: {
			comment: {
				label: __('Comment', 'wds'),
				values: {
					comment_date: __('Comment Date', 'wds'),
				}
			},
		},
		description: __('The date when this comment was created in ISO 8601 format.', 'wds'),
	},
	"url": {
		id: id(),
		label: __('URL', 'wds'),
		type: 'Text',
		source: 'comment',
		value: 'comment_url',
		customSources: {
			comment: {
				label: __('Comment', 'wds'),
				values: {
					comment_url: __('Comment URL', 'wds'),
				}
			},
		},
		description: __('The permanent URL of the comment.', 'wds'),
	},
	"author": {
		id: id(),
		label: __('Author Name', 'wds'),
		type: 'Person',
		flatten: true,
		properties: {
			name: {
				id: id(),
				label: __('Author Name', 'wds'),
				type: 'Text',
				source: 'comment',
				value: 'comment_author_name',
				customSources: {
					comment: {
						label: __('Comment', 'wds'),
						values: {
							comment_author_name: __('Comment Author Name', 'wds'),
						}
					},
				},
				description: __('The name of the comment author.', 'wds'),
			},
		},
	},
};
export default FAQComment;
