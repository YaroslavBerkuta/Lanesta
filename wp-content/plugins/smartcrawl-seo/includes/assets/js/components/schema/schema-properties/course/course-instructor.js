import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const CourseInstructor = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'custom_text',
		value: '',
		description: __('The name of the course instructor.', 'wds'),
		disallowDeletion: true,
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'custom_text',
		value: '',
		description: __('The URL of the course instructor.', 'wds'),
		disallowDeletion: true,
	},
	image: {
		id: id(),
		label: __('Image', 'wds'),
		type: 'ImageObject',
		source: 'image',
		value: '',
		description: __('The profile image of the course instructor.', 'wds'),
		disallowDeletion: true,
	},
};
export default CourseInstructor;
