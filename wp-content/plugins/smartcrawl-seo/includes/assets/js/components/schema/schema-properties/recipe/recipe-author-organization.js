import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const RecipeAuthorOrganization = {
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
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'site_settings',
		value: 'site_url',
		description: __('The URL of the organization.', 'wds'),
	},
};

export default RecipeAuthorOrganization;
