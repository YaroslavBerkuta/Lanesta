import {__} from '@wordpress/i18n';
import uniqueId from "lodash-es/uniqueId";
import HowToMonetaryAmount from "./how-to-monetary-amount";
import HowToComment from "./how-to-comment";
import AggregateRating from "../aggregate-rating";
import Review from "../review/review";

const id = uniqueId;
const HowTo = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'post_data',
		value: 'post_title',
		required: true,
		description: __('The title of the how-to. For example, "How to tie a tie".', 'wds'),
	},
	description: {
		id: id(),
		label: __('Description', 'wds'),
		type: 'TextFull',
		source: 'seo_meta',
		value: 'seo_description',
		description: __('A description of the how-to.', 'wds'),
	},
	totalTime: {
		id: id(),
		label: __('Total Time', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The total time required to perform all instructions or directions (including time to prepare the supplies), in ISO 8601 duration format.', 'wds'),
	},
	image: {
		id: id(),
		label: __('Images', 'wds'),
		labelSingle: __('Image', 'wds'),
		properties: {
			0: {
				id: id(),
				label: __('Image', 'wds'),
				type: 'ImageObject',
				source: 'post_data',
				value: 'post_thumbnail'
			}
		},
		description: __('Images of the completed how-to.', 'wds'),
	},
	supply: {
		id: id(),
		label: __('Supplies', 'wds'),
		labelSingle: __('Supply', 'wds'),
		properties: {
			0: {
				id: id(),
				label: __('Supply', 'wds'),
				type: 'HowToSupply',
				properties: {
					name: {
						id: id(),
						label: __('Name', 'wds'),
						type: 'Text',
						source: 'custom_text',
						value: '',
						description: __('The name of the supply.', 'wds'),
						disallowDeletion: true,
					},
					image: {
						id: id(),
						label: __('Image', 'wds'),
						type: 'ImageObject',
						source: 'image',
						value: '',
						description: __('An image of the supply.', 'wds'),
						disallowDeletion: true,
					},
				}
			}
		},
		description: __('Supplies consumed when performing instructions or a direction.', 'wds'),
	},
	tool: {
		id: id(),
		label: __('Tools', 'wds'),
		labelSingle: __('Tool', 'wds'),
		properties: {
			0: {
				id: id(),
				label: __('Tool', 'wds'),
				type: 'HowToTool',
				properties: {
					name: {
						id: id(),
						label: __('Name', 'wds'),
						type: 'Text',
						source: 'custom_text',
						value: '',
						description: __('The name of the tool.', 'wds'),
						disallowDeletion: true,
					},
					image: {
						id: id(),
						label: __('Image', 'wds'),
						type: 'ImageObject',
						source: 'image',
						value: '',
						description: __('An image of the tool.', 'wds'),
						disallowDeletion: true,
					},
				}
			}
		},
		description: __('Objects used (but not consumed) when performing instructions or a direction.', 'wds'),
	},
	estimatedCost: {
		id: id(),
		label: __('Estimated Cost', 'wds'),
		type: 'MonetaryAmount',
		disallowAddition: true,
		properties: HowToMonetaryAmount,
		description: __('The estimated cost of the supplies consumed when performing instructions.', 'wds'),
	},
	step: {
		id: id(),
		label: __('Steps', 'wds'),
		labelSingle: __('Step', 'wds'),
		properties: {
			0: {
				id: id(),
				label: __('Step', 'wds'),
				type: 'HowToStep',
				disallowDeletion: true,
				disallowFirstItemDeletionOnly: true,
				properties: {
					name: {
						id: id(),
						label: __('Name', 'wds'),
						type: 'Text',
						source: 'custom_text',
						value: '',
						description: __('The word or short phrase summarizing the step (for example, "Attach wires to post" or "Dig").', 'wds'),
						disallowDeletion: true,
					},
					text: {
						id: id(),
						label: __('Text', 'wds'),
						type: 'Text',
						source: 'custom_text',
						value: '',
						required: true,
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
						type: 'Text',
						source: 'custom_text',
						value: '',
						description: __('A URL that directly links to the step (if one is available). For example, an anchor link fragment.', 'wds'),
						disallowDeletion: true,
					},
				}
			}
		},
		required: true,
		description: __("An array of elements which comprise the full instructions of the how-to. Each step element should correspond to an individual step in the instructions. Don't mark up non-step data such as a summary or introduction section, using this property.", 'wds'),
	},
	comment: {
		id: id(),
		label: __('Comments', 'wds'),
		type: 'Comment',
		loop: 'post-comments',
		loopDescription: __('The following block will be repeated for each post comment'),
		properties: HowToComment,
		optional: true,
		description: __('Comments, typically from users.', 'wds'),
	},
	aggregateRating: {
		id: id(),
		label: __('Aggregate Rating', 'wds'),
		type: 'AggregateRating',
		properties: AggregateRating,
		description: __('A nested aggregateRating of the how-to.', 'wds'),
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
				properties: Review
			}
		},
		description: __('Reviews of the how-to.', 'wds'),
		optional: true,
	},
};
export default HowTo;
