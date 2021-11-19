import merge from "lodash-es/merge";
import RecipeVideoBasic from "./recipe-video-basic";
import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const propertyOrder = {
	name: null,
	description: null,
	uploadDate: null,
	thumbnailUrl: null
};
const RecipeHowToStepVideo = merge(propertyOrder, RecipeVideoBasic, {
	name: {
		disallowDeletion: true,
	},
	description: {
		disallowDeletion: true,
	},
	uploadDate: {
		disallowDeletion: true,
	},
	thumbnailUrl: {
		id: id(),
		label: __('Thumbnail URL', 'wds'),
		type: 'ImageURL',
		source: 'image_url',
		value: '',
		disallowDeletion: true,
		required: true,
	},
	contentUrl: {
		disallowDeletion: true,
	},
	embedUrl: {
		disallowDeletion: true,
	},
	duration: {
		disallowDeletion: true,
	},
});
export default RecipeHowToStepVideo;
