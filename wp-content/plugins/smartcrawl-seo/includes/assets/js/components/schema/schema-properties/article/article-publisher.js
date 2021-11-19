import {__} from '@wordpress/i18n';
import uniqueId from "lodash-es/uniqueId";
import ArticlePostalAddress from "./article-postal-address";
import ArticleContactPoint from "./article-contact-point";

const id = uniqueId;
const ArticlePublisher = {
	logo: {
		id: id(),
		label: __('Logo', 'wds'),
		type: 'ImageObject',
		source: 'schema_settings',
		value: 'organization_logo',
		description: __('The logo of the publisher.', 'wds'),
		required: true,
	},
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'schema_settings',
		value: 'organization_name',
		description: __('The name of the publisher.', 'wds'),
		required: true,
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'site_settings',
		value: 'site_url',
		description: __('The URL of the publisher.', 'wds'),
	},
	address: {
		id: id(),
		label: __('Address', 'wds'),
		optional: true,
		description: __('The addresses of the publisher.', 'wds'),
		properties: {
			0: {
				id: id(),
				type: 'PostalAddress',
				properties: ArticlePostalAddress
			}
		}
	},
	contactPoint: {
		id: id(),
		label: __('Contact Point', 'wds'),
		optional: true,
		description: __('The contact points of the publisher.', 'wds'),
		properties: {
			0: {
				id: id(),
				type: 'ContactPoint',
				properties: ArticleContactPoint
			}
		}
	}
};
export default ArticlePublisher;
