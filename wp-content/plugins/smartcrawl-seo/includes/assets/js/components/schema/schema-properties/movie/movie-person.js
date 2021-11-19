import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const MoviePerson = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'custom_text',
		value: '',
		description: __('The name of the person.', 'wds'),
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'custom_text',
		value: '',
		description: __("The URL of the person's profile.", 'wds'),
	},
	image: {
		id: id(),
		label: __('Image', 'wds'),
		type: 'ImageObject',
		source: 'image',
		value: '',
		description: __('The profile image of the person.', 'wds'),
	},
};
export default MoviePerson;
