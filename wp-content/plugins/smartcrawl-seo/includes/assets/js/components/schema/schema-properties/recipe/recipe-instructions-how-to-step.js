import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";
import RecipeVideo from "./recipe-video";
import RecipeHowToStepVideo from "./recipe-how-to-step-video";
import RecipeClip from "./recipe-clip";

const id = uniqueId;
const RecipeInstructionsHowToStep = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The word or short phrase summarizing the step (for example, "Preheat").', 'wds'),
		disallowDeletion: true,
	},
	text: {
		id: id(),
		label: __('Text', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The full instruction text of this step.', 'wds'),
		disallowDeletion: true,
	},
	image: {
		id: id(),
		label: __('Image', 'wds'),
		type: 'ImageObject',
		source: 'image',
		value: '',
		description: __('An image for the step.', 'wds'),
		disallowDeletion: true,
	},
	url: {
		id: id(),
		label: __('Url', 'wds'),
		type: 'URL',
		source: 'custom_text',
		value: '',
		description: __('A URL that directly links to the step (if one is available). For example, an anchor link fragment.', 'wds'),
		disallowDeletion: true,
	},
	video: {
		id: id(),
		label: __('Video', 'wds'),
		activeVersion: 'Video',
		properties: {
			Video: {
				id: id(),
				label: __('Video', 'wds'),
				description: __('A video for this step.', 'wds'),
				type: 'VideoObject',
				properties: RecipeHowToStepVideo,
				disallowDeletion: true,
				disallowAddition: true,
				isAnAltVersion: true,
			},
			Clip: {
				id: id(),
				label: __('Clip', 'wds'),
				description: __('A clip for this step.', 'wds'),
				type: 'Clip',
				properties: RecipeClip,
				disallowDeletion: true,
				disallowAddition: true,
				isAnAltVersion: true,
			}
		}
	},
};

export default RecipeInstructionsHowToStep;
