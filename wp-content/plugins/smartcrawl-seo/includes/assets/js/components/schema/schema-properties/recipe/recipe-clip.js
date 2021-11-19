import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const RecipeClip = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The name of the clip.', 'wds'),
		required: true,
		disallowDeletion: true,
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'custom_text',
		value: '',
		description: __('A link to the start of the clip.', 'wds'),
		required: true,
		disallowDeletion: true,
	},
	startOffset: {
		id: id(),
		label: __('Start Offset', 'wds'),
		type: 'Number',
		source: 'number',
		value: '',
		description: __('The start time of the clip expressed as the number of seconds from the beginning of the video.', 'wds'),
		required: true,
		disallowDeletion: true,
	},
	endOffset: {
		id: id(),
		label: __('End Offset', 'wds'),
		type: 'Number',
		source: 'number',
		value: '',
		description: __('The end time of the clip expressed as the number of seconds from the beginning of the video.', 'wds'),
		disallowDeletion: true,
	},
};
export default RecipeClip;
