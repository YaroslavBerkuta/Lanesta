import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";
import MoviePerson from "./movie-person";
import AggregateRating from "../aggregate-rating";
import Review from "../review/review";
import MovieProductionCompany from "./movie-production-company";
import MovieActor from "./movie-actor";

const id = uniqueId;
const Movie = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		description: __('The name of the movie.', 'wds'),
		type: 'TextFull',
		source: 'post_data',
		value: 'post_title',
		required: true,
	},
	dateCreated: {
		id: id(),
		label: __('Release Date', 'wds'),
		description: __('The date the movie was released.', 'wds'),
		type: 'DateTime',
		source: 'datetime',
		value: '',
	},
	image: {
		id: id(),
		label: __('Images', 'wds'),
		labelSingle: __('Image', 'wds'),
		description: __("An image that represents the movie. Images must have a high resolution and have a 6:9 aspect ratio. While Google can crop images that are close to a 6:9 aspect ratio, images largely deviating from this ratio aren't eligible for the feature.", 'wds'),
		required: true,
		properties: {
			0: {
				id: id(),
				label: __('Image', 'wds'),
				type: 'ImageObject',
				source: 'post_data',
				value: 'post_thumbnail',
			},
		},
	},
	director: {
		id: id(),
		label: __('Director', 'wds'),
		description: __('The director of the movie.', 'wds'),
		type: 'Person',
		properties: MoviePerson,
	},
	aggregateRating: {
		id: id(),
		label: __('Aggregate Rating', 'wds'),
		type: 'AggregateRating',
		properties: AggregateRating,
		description: __('A nested aggregateRating of the movie.', 'wds'),
		optional: true,
	},
	review: {
		id: id(),
		label: __('Reviews', 'wds'),
		labelSingle: __('Review', 'wds'),
		description: __('Reviews of the movie.', 'wds'),
		optional: true,
		properties: {
			0: {
				id: id(),
				type: 'Review',
				properties: Review,
			},
		},
	},
	actor: {
		id: id(),
		label: __('Actors', 'wds'),
		labelSingle: __('Actor', 'wds'),
		description: __('Actors working in the movie', 'wds'),
		optional: true,
		properties: {
			0: {
				id: id(),
				type: 'Person',
				properties: MovieActor,
			},
		},
	},
	countryOfOrigin: {
		id: id(),
		label: __('Country Of Origin', 'wds'),
		type: 'Country',
		flatten: true,
		optional: true,
		properties: {
			name: {
				id: id(),
				label: __('Country Of Origin', 'wds'),
				type: 'Text',
				source: 'custom_text',
				value: '',
				description: __("The country of the principal offices of the production company or individual responsible for the movie.", 'wds'),
				placeholder: __('E.g. USA', 'wds'),
			},
		},
	},
	duration: {
		id: id(),
		label: __('Duration', 'wds'),
		description: __('The duration of the item in ISO 8601 date format.', 'wds'),
		type: 'Duration',
		source: 'duration',
		value: '',
		optional: true,
		placeholder: __('E.g. PT00H30M5S', 'wds'),
	},
	musicBy: {
		id: id(),
		label: __('Music By', 'wds'),
		description: __('The composer of the soundtrack.', 'wds'),
		type: 'Person',
		optional: true,
		properties: MoviePerson,
	},
	productionCompany: {
		id: id(),
		label: __('Production Company', 'wds'),
		type: 'Organization',
		optional: true,
		description: __('The production company or studio responsible for the movie.', 'wds'),
		properties: MovieProductionCompany,
	},
};
export default Movie;
