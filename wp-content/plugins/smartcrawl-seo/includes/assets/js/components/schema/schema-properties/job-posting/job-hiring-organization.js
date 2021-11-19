import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const JobHiringOrganization = {
	logo: {
		id: id(),
		label: __('Logo', 'wds'),
		type: 'ImageObject',
		source: 'schema_settings',
		value: 'organization_logo',
		description: __('The logo of the organization.', 'wds'),
	},
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'schema_settings',
		value: 'organization_name',
		description: __('The name of the organization.', 'wds'),
		required: true,
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'site_settings',
		value: 'site_url',
		description: __('The URL of the organization.', 'wds'),
	},
	sameAs: {
		id: id(),
		label: __('Same As', 'wds'),
		labelSingle: __('URL', 'wds'),
		description: __("URL of reference web pages that unambiguously indicate the item's identity.", 'wds'),
		properties: {
			0: {
				id: id(),
				label: __('URL', 'wds'),
				type: 'URL',
				source: 'custom_text',
				value: '',
			},
		},
	},
};
export default JobHiringOrganization;
