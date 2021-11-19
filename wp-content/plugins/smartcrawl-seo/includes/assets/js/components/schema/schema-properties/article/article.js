import {__} from '@wordpress/i18n';
import uniqueId from "lodash-es/uniqueId";
import ArticleComment from "./article-comment";
import ArticlePublisher from "./article-publisher";
import ArticleAuthor from "./article-author";

const id = uniqueId;
const Article = {
	headline: {
		id: id(),
		label: __('Headline', 'wds'),
		type: 'TextFull',
		source: 'seo_meta',
		value: 'seo_title',
		required: true,
		description: __('The headline of the article. Headlines should not exceed 110 characters.', 'wds'),
	},
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'post_data',
		value: 'post_title',
		description: __('The name of the article.', 'wds'),
	},
	description: {
		id: id(),
		label: __('Description', 'wds'),
		type: 'TextFull',
		source: 'seo_meta',
		value: 'seo_description',
		description: __('The description of the article.', 'wds'),
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'post_data',
		value: 'post_permalink',
		description: __('The permanent URL of the article.', 'wds'),
	},
	thumbnailUrl: {
		id: id(),
		label: __('Thumbnail URL', 'wds'),
		type: 'ImageURL',
		source: 'post_data',
		value: 'post_thumbnail_url',
		description: __('The thumbnail URL of the article.', 'wds'),
	},
	dateModified: {
		id: id(),
		label: __('Date Modified', 'wds'),
		type: 'DateTime',
		source: 'post_data',
		value: 'post_modified',
		description: __('The date and time the article was most recently modified, in ISO 8601 format.', 'wds'),
	},
	datePublished: {
		id: id(),
		label: __('Date Published', 'wds'),
		type: 'DateTime',
		source: 'post_data',
		required: true,
		description: __('The date and time the article was first published, in ISO 8601 format.', 'wds'),
		value: 'post_date'
	},
	articleBody: {
		id: id(),
		label: __('Article Body', 'wds'),
		type: 'TextFull',
		source: 'post_data',
		value: 'post_content',
		optional: true,
		description: __('The content of the article.', 'wds'),
	},
	alternativeHeadline: {
		id: id(),
		label: __('Alternative Headline', 'wds'),
		type: 'TextFull',
		source: 'post_data',
		value: 'post_title',
		optional: true,
		description: __('Alternative headline for the article.', 'wds'),
	},
	image: {
		id: id(),
		label: __('Images', 'wds'),
		labelSingle: __('Image', 'wds'),
		required: true,
		description: __('Images related to the article.', 'wds'),
		properties: {
			0: {
				id: id(),
				label: __('Image', 'wds'),
				type: 'ImageObject',
				source: 'post_data',
				value: 'post_thumbnail'
			}
		}
	},
	author: {
		id: id(),
		label: __('Author', 'wds'),
		type: 'Person',
		required: true,
		description: __('The author of the article.', 'wds'),
		properties: ArticleAuthor
	},
	publisher: {
		id: id(),
		label: __('Publisher', 'wds'),
		type: 'Organization',
		required: true,
		description: __('The publisher of the article.', 'wds'),
		properties: ArticlePublisher
	},
	comment: {
		id: id(),
		label: __('Comments', 'wds'),
		type: 'Comment',
		loop: 'post-comments',
		loopDescription: __('The following block will be repeated for each post comment'),
		properties: ArticleComment,
		optional: true,
		description: __('Comments, typically from users.', 'wds'),
	},
};
export default Article;
