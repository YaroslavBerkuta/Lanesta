import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";
import RecipeAuthorPerson from "./recipe-author-person";
import RecipeAuthorOrganization from "./recipe-author-organization";
import RecipeInstructionsHowToStep from "./recipe-instructions-how-to-step";
import RecipeVideo from "./recipe-video";
import AggregateRating from "../aggregate-rating";
import Review from "../review/review";

const id = uniqueId;
const Recipe = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'post_data',
		value: 'post_title',
		description: __('The name of the dish.', 'wds'),
		required: true,
	},
	datePublished: {
		id: id(),
		label: __('Date Published', 'wds'),
		type: 'DateTime',
		source: 'post_data',
		description: __('The date and time the recipe was first published, in ISO 8601 format.', 'wds'),
		value: 'post_date'
	},
	description: {
		id: id(),
		label: __('Description', 'wds'),
		type: 'TextFull',
		source: 'seo_meta',
		value: 'seo_description',
		description: __('A short summary describing the dish.', 'wds'),
	},
	recipeCategory: {
		id: id(),
		label: __('Recipe Category', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		placeholder: __('E.g. dessert', 'wds'),
		description: __('The type of meal or course your recipe is about. For example: "dinner", "main course", or "dessert, snack".', 'wds'),
	},
	recipeCuisine: {
		id: id(),
		label: __('Recipe Cuisine', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		placeholder: __('E.g. Mediterranean', 'wds'),
		description: __('The region associated with your recipe. For example, "French", Mediterranean", or "American".', 'wds'),
	},
	keywords: {
		id: id(),
		label: __('Keywords', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		placeholder: __('E.g. authentic', 'wds'),
		description: __('Other terms for your recipe such as the season ("summer"), the holiday ("Halloween"), or other descriptors ("quick", "easy", "authentic"). Don\'t use a tag that should be in recipeCategory or recipeCuisine.', 'wds'),
	},
	prepTime: {
		id: id(),
		label: __('Prep Time', 'wds'),
		type: 'Duration',
		source: 'duration',
		value: '',
		description: __('The length of time it takes to prepare the dish in ISO 8601 duration format. Always use in combination with cookTime.', 'wds'),
		placeholder: __('E.g. PT1M', 'wds'),
	},
	cookTime: {
		id: id(),
		label: __('Cook Time', 'wds'),
		type: 'Duration',
		source: 'duration',
		value: '',
		description: __('The time it takes to actually cook the dish in ISO 8601 duration format. Always use in combination with prepTime.', 'wds'),
		placeholder: __('E.g. PT2M', 'wds'),
	},
	totalTime: {
		id: id(),
		label: __('Total Time', 'wds'),
		type: 'Duration',
		source: 'duration',
		value: '',
		description: __('The total time it takes to prepare and cook the dish in ISO 8601 duration format. Use totalTime or a combination of both cookTime and prepTime.', 'wds'),
		placeholder: __('E.g. PT3M', 'wds'),
	},
	nutrition: {
		id: id(),
		label: __('Nutrition', 'wds'),
		type: 'NutritionInformation',
		flatten: true,
		properties: {
			calories: {
				id: id(),
				label: __('Calories Per Serving', 'wds'),
				type: 'Text',
				source: 'custom_text',
				value: '',
				description: __('The number of calories in each serving produced with this recipe. If calories is defined, recipeYield must be defined with the number of servings.', 'wds'),
				placeholder: __('E.g. 270 calories'),
			}
		},
	},
	recipeYield: {
		id: id(),
		label: __('Recipe Yield', 'wds'),
		type: 'Number',
		source: 'number',
		value: '',
		placeholder: __('E.g. 6', 'wds'),
		description: __('Specify the number of servings produced from this recipe with a number. This is required if you specify calories per serving.', 'wds'),
	},
	image: {
		id: id(),
		label: __('Images', 'wds'),
		labelSingle: __('Image', 'wds'),
		required: true,
		description: __('Images of the completed dish. For best results, provide multiple high-resolution images (minimum of 50K pixels when multiplying width and height) with the following aspect ratios: 16x9, 4x3, and 1x1.', 'wds'),
		properties: {
			0: {
				id: id(),
				label: __('Image', 'wds'),
				type: 'ImageObject',
				source: 'post_data',
				value: 'post_thumbnail'
			}
		}
	},
	recipeIngredient: {
		id: id(),
		label: __('Ingredients', 'wds'),
		labelSingle: __('Ingredient', 'wds'),
		description: __('Ingredients used in the recipe.', 'wds'),
		properties: {
			0: {
				id: id(),
				label: __('Ingredient', 'wds'),
				type: 'Text',
				source: 'custom_text',
				value: '',
				placeholder: __('E.g. 3/4 cup sugar', 'wds'),
			}
		}
	},
	recipeInstructions: {
		id: id(),
		label: __('Instructions', 'wds'),
		activeVersion: 'InstructionStepsText',
		properties: {
			InstructionStepsText: {
				id: id(),
				label: __('Instructions', 'wds'),
				labelSingle: __('Instruction', 'wds'),
				description: __('The steps to make the dish.', 'wds'),
				properties: {
					0: {
						id: id(),
						label: __('Step', 'wds') + ' 1',
						type: 'Text',
						source: 'custom_text',
						value: '',
						updateLabelNumber: true,
					}
				},
				isAnAltVersion: true,
			},
			InstructionStepsHowTo: {
				id: id(),
				label: __('Instruction HowTo Steps', 'wds'),
				labelSingle: __('Instruction Step', 'wds'),
				description: __("An array of elements which comprise the full instructions of the recipe. Each step element should correspond to an individual step in the recipe.", 'wds'),
				properties: {
					0: {
						id: id(),
						label: __('Instruction Step', 'wds'),
						type: 'HowToStep',
						properties: RecipeInstructionsHowToStep,
					}
				},
				isAnAltVersion: true,
			},
		},
	},
	author: {
		id: id(),
		label: __('Author', 'wds'),
		activeVersion: 'Person',
		properties: {
			Person: {
				id: id(),
				label: __('Author', 'wds'),
				type: 'Person',
				properties: RecipeAuthorPerson,
				description: __("The author of the recipe. The author's name must be a valid name.", 'wds'),
				isAnAltVersion: true,
			},
			Organization: {
				id: id(),
				label: __('Author Organization', 'wds'),
				type: 'Organization',
				properties: RecipeAuthorOrganization,
				description: __("The author of the recipe. The author's name must be a valid name.", 'wds'),
				isAnAltVersion: true,
			}
		},
	},
	aggregateRating: {
		id: id(),
		label: __('Aggregate Rating', 'wds'),
		type: 'AggregateRating',
		properties: AggregateRating,
		description: __('A nested aggregateRating of the recipe.', 'wds'),
		optional: true,
	},
	review: {
		id: id(),
		label: __('Reviews', 'wds'),
		labelSingle: __('Review', 'wds'),
		properties: {
			0: {
				id: id(),
				type: 'Review',
				properties: Review,
			}
		},
		description: __('Reviews of the recipe.', 'wds'),
		optional: true,
	},
	video: {
		id: id(),
		label: __('Video', 'wds'),
		description: __('A video depicting the steps to make the dish.', 'wds'),
		type: 'VideoObject',
		properties: RecipeVideo,
		optional: true,
	},
};
export default Recipe;
