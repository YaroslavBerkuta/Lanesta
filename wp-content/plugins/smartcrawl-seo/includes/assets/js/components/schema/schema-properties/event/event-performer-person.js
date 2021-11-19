import uniqueId from "lodash-es/uniqueId";
import {__} from "@wordpress/i18n";

const id = uniqueId;
const EventPerformerPerson = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'custom_text',
		value: '',
		description: __('The name of the person.', 'wds'),
		disallowDeletion: true,
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'custom_text',
		value: '',
		description: __("The URL to the person's profile.", 'wds'),
		disallowDeletion: true,
	},
	description: {
		id: id(),
		label: __('Description', 'wds'),
		type: 'TextFull',
		source: 'custom_text',
		value: '',
		optional: true,
		description: __('Short bio/description of the person.', 'wds'),
		disallowDeletion: true,
	},
	image: {
		id: id(),
		label: __('Image', 'wds'),
		type: 'ImageObject',
		source: 'image',
		value: '',
		description: __('The profile image of the person.', 'wds'),
		disallowDeletion: true,
	}
};
export default EventPerformerPerson;
