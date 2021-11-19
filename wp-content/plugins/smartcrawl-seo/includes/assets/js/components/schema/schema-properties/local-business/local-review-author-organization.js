import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const LocalReviewAuthorOrganization = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'custom_text',
		value: '',
		description: __('The name of the organization.', 'wds'),
		disallowDeletion: true,
	},
	logo: {
		id: id(),
		label: __('Logo', 'wds'),
		type: 'ImageObject',
		source: 'image',
		value: '',
		description: __('The logo of the organization.', 'wds'),
		disallowDeletion: true,
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'custom_text',
		value: '',
		description: __('The URL of the organization.', 'wds'),
		disallowDeletion: true,
	},
};
export default LocalReviewAuthorOrganization;
