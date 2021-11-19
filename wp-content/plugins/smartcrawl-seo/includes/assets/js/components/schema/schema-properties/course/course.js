import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";
import CourseProviderOrganization from "./course-provider-organization";
import CourseInstance from "./course-instance";
import AggregateRating from "../aggregate-rating";
import Review from "../review/review";

const id = uniqueId;
const Course = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'post_data',
		value: 'post_title',
		required: true,
		description: __('The title of the course.', 'wds'),
	},
	description: {
		id: id(),
		label: __('Description', 'wds'),
		type: 'TextFull',
		source: 'seo_meta',
		value: 'seo_description',
		required: true,
		description: __('A description of the course. Display limit of 60 characters.', 'wds'),
	},
	courseCode: {
		id: id(),
		label: __('Course Code', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The identifier for the Course used by the course provider.', 'wds'),
		placeholder: __('E.g. CS101'),
	},
	numberOfCredits: {
		id: id(),
		label: __('Number Of Credits', 'wds'),
		type: 'Number',
		source: 'number',
		value: '',
		description: __('The number of credits or units awarded by the course.', 'wds'),
	},
	provider: {
		id: id(),
		label: __('Provider', 'wds'),
		type: 'Organization',
		description: __('The organization that publishes the source content of the course. For example, UC Berkeley.', 'wds'),
		properties: CourseProviderOrganization,
	},
	hasCourseInstance: {
		id: id(),
		label: __('Course Instances', 'wds'),
		labelSingle: __('Course Instance', 'wds'),
		description: __('An offering of the course at a specific time and place or through specific media or mode of study or to a specific section of students.', 'wds'),
		optional: true,
		properties: {
			0: {
				id: id(),
				type: 'CourseInstance',
				properties: CourseInstance
			},
		},
	},
	aggregateRating: {
		id: id(),
		label: __('Aggregate Rating', 'wds'),
		type: 'AggregateRating',
		properties: AggregateRating,
		description: __('A nested aggregateRating of the course.', 'wds'),
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
		description: __('Reviews of the course.', 'wds'),
		optional: true,
	},
};
export default Course;
