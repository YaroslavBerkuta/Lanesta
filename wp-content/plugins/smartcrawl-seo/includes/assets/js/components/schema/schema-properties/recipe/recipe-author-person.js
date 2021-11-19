import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const RecipeAuthorPerson = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'author',
		value: 'author_full_name',
		description: __('The name of the recipe author.', 'wds'),
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'author',
		value: 'author_url',
		description: __("The URL of the recipe author.", 'wds'),
	},
	description: {
		id: id(),
		label: __('Description', 'wds'),
		type: 'TextFull',
		source: 'author',
		value: 'author_description',
		optional: true,
		description: __('Short bio/description of the recipe author.', 'wds'),
	},
	image: {
		id: id(),
		label: __('Image', 'wds'),
		type: 'ImageObject',
		source: 'author',
		value: 'author_gravatar',
		description: __('An image of the recipe author.', 'wds'),
	}
};
export default RecipeAuthorPerson;
