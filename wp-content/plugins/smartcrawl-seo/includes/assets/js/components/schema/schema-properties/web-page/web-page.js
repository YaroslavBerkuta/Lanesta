import {__} from '@wordpress/i18n';
import uniqueId from "lodash-es/uniqueId";
import WebPageOrganization from "./web-page-organization";

const id = uniqueId;

const WebPage = {
	headline: {
		id: id(),
		label: __('Headline', 'wds'),
		type: 'TextFull',
		source: 'seo_meta',
		value: 'seo_title'
	},
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'post_data',
		value: 'post_title'
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'post_data',
		value: 'post_permalink'
	},
	description: {
		id: id(),
		label: __('Description', 'wds'),
		type: 'TextFull',
		source: 'seo_meta',
		value: 'seo_description'
	},
	primaryImageOfPage: {
		id: id(),
		label: __('Primary Image Of Page', 'wds'),
		type: 'ImageObject',
		source: 'post_data',
		value: 'post_thumbnail'
	},
	thumbnailUrl: {
		id: id(),
		label: __('Thumbnail URL', 'wds'),
		type: 'ImageURL',
		source: 'post_data',
		value: 'post_thumbnail_url'
	},
	lastReviewed: {
		id: id(),
		label: __('Last Reviewed', 'wds'),
		type: 'DateTime',
		source: 'post_data',
		value: 'post_modified',
		optional: true,
	},
	dateModified: {
		id: id(),
		label: __('Date Modified', 'wds'),
		type: 'DateTime',
		source: 'post_data',
		value: 'post_modified'
	},
	datePublished: {
		id: id(),
		label: __('Date Published', 'wds'),
		type: 'DateTime',
		source: 'post_data',
		value: 'post_date'
	},
	articleBody: {
		id: id(),
		label: __('Article Body', 'wds'),
		type: 'TextFull',
		source: 'post_data',
		value: 'post_content',
		optional: true,
	},
	alternativeHeadline: {
		id: id(),
		label: __('Alternative Headline', 'wds'),
		type: 'TextFull',
		source: 'post_data',
		value: 'post_title',
		optional: true,
	},
	relatedLink: {
		id: id(),
		label: __('Related Link', 'wds'),
		type: 'URL',
		source: 'custom_text',
		value: '',
		optional: true,
	},
	significantLink: {
		id: id(),
		label: __('Significant Link', 'wds'),
		type: 'URL',
		source: 'custom_text',
		value: '',
		optional: true,
	},
	image: {
		id: id(),
		label: __('Images', 'wds'),
		label_single: __('Image', 'wds'),
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
		properties: {
			name: {
				id: id(),
				label: __('Name', 'wds'),
				type: 'TextFull',
				source: 'author',
				value: 'author_full_name'
			},
			url: {
				id: id(),
				label: __('URL', 'wds'),
				type: 'URL',
				source: 'author',
				value: 'author_url'
			},
			description: {
				id: id(),
				label: __('Description', 'wds'),
				type: 'TextFull',
				source: 'author',
				value: 'author_description',
				optional: true
			},
			image: {
				id: id(),
				label: __('Image', 'wds'),
				type: 'ImageObject',
				source: 'author',
				value: 'author_gravatar'
			}
		}
	},
	publisher: {
		id: id(),
		label: __('Publisher', 'wds'),
		type: 'Organization',
		properties: WebPageOrganization,
	},
};
export default WebPage;
