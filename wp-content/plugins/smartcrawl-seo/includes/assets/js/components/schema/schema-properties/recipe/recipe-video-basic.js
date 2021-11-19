import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const RecipeVideoBasic = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The title of the video.', 'wds'),
		required: true,
	},
	description: {
		id: id(),
		label: __('Description', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The description of the video. HTML tags are ignored.', 'wds'),
		required: true,
	},
	uploadDate: {
		id: id(),
		label: __('Upload Date', 'wds'),
		type: 'DateTime',
		source: 'datetime',
		value: '',
		description: __('The date the video was first published, in ISO 8601 format.', 'wds'),
		required: true,
	},
	contentUrl: {
		id: id(),
		label: __('Content URL', 'wds'),
		type: 'URL',
		source: 'custom_text',
		value: '',
		description: __('A URL pointing to the actual video media file. One or both of the following properties are recommended: contentUrl and embedUrl', 'wds'),
	},
	embedUrl: {
		id: id(),
		label: __('Embed URL', 'wds'),
		type: 'URL',
		source: 'custom_text',
		value: '',
		description: __('A URL pointing to a player for the specific video. One or both of the following properties are recommended: contentUrl and embedUrl', 'wds'),
	},
	duration: {
		id: id(),
		label: __('Duration', 'wds'),
		type: 'Duration',
		source: 'duration',
		value: '',
		description: __('The duration of the video in ISO 8601 format. For example, PT00H30M5S represents a duration of "thirty minutes and five seconds".', 'wds'),
		placeholder: __('E.g. PT00H30M5S', 'wds'),
	},
};
export default RecipeVideoBasic;
