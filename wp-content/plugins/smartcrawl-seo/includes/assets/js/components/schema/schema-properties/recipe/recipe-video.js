import merge from "lodash-es/merge";
import RecipeVideoBasic from "./recipe-video-basic";
import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";
import RecipeClip from "./recipe-clip";

const id = uniqueId;
const RecipeVideo = merge({}, RecipeVideoBasic, {
	thumbnailUrl: {
		id: id(),
		label: __('Thumbnail URLs', 'wds'),
		labelSingle: __('Thumbnail URL', 'wds'),
		description: __('URLs pointing to the video thumbnail image files. Images must be 60px x 30px, at minimum.', 'wds'),
		required: true,
		properties: {
			0: {
				id: id(),
				label: __('Thumbnail URL', 'wds'),
				type: 'ImageURL',
				source: 'image_url',
				value: '',
			}
		},
	},
	hasPart: {
		id: id(),
		label: __('Clips', 'wds'),
		labelSingle: __('Clip', 'wds'),
		description: __('Video clips that are included within the full video.', 'wds'),
		properties: {
			0: {
				id: id(),
				type: 'Clip',
				properties: RecipeClip,
			},
		},
	}
});

export default RecipeVideo;
