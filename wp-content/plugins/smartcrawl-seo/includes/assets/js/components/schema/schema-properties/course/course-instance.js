import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";
import CourseInstructor from "./course-instructor";
import CoursePlace from "./course-place";
import CourseOffer from "./course-offer";

const id = uniqueId;
const CourseInstance = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'post_data',
		value: 'post_title',
		description: __('The title of the course instance.', 'wds'),
		disallowDeletion: true,
	},
	description: {
		id: id(),
		label: __('Description', 'wds'),
		type: 'TextFull',
		source: 'seo_meta',
		value: 'seo_description',
		description: __('A description of the course instance.', 'wds'),
		disallowDeletion: true,
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'post_data',
		value: 'post_permalink',
		description: __('The URL of the course instance.', 'wds'),
		disallowDeletion: true,
	},
	courseMode: {
		id: id(),
		label: __('Course Mode', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The medium or means of delivery of the course instance or the mode of study, either as a text label (e.g. "online", "onsite" or "blended"; "synchronous" or "asynchronous"; "full-time" or "part-time").', 'wds'),
		placeholder: __('E.g. onsite', 'wds'),
		disallowDeletion: true,
	},
	courseWorkload: {
		id: id(),
		label: __('Course Workload', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The amount of work expected of students taking the course, often provided as a figure per week or per month, and may be broken down by type. For example, "2 hours of lectures, 1 hour of lab work and 3 hours of independent study per week".', 'wds'),
		placeholder: __('E.g. 2 hours of lectures', 'wds'),
		disallowDeletion: true,
	},
	eventStatus: {
		id: id(),
		label: __('Status', 'wds'),
		type: 'Text',
		source: 'options',
		value: 'EventScheduled',
		customSources: {
			options: {
				label: __('Course Status', 'wds'),
				values: {
					EventScheduled: __('Scheduled', 'wds'),
					EventMovedOnline: __('Moved Online', 'wds'),
					EventRescheduled: __('Rescheduled', 'wds'),
					EventPostponed: __('Postponed', 'wds'),
					EventCancelled: __('Cancelled', 'wds'),
				},
			},
		},
		description: __('The status of the course.', 'wds'),
		disallowDeletion: true,
	},
	eventAttendanceMode: {
		id: id(),
		label: __('Attendance Mode', 'wds'),
		type: 'Text',
		source: 'options',
		value: 'MixedEventAttendanceMode',
		customSources: {
			options: {
				label: __('Event Attendance Mode', 'wds'),
				values: {
					MixedEventAttendanceMode: __('Mixed Attendance Mode', 'wds'),
					OfflineEventAttendanceMode: __('Offline Attendance Mode', 'wds'),
					OnlineEventAttendanceMode: __('Online Attendance Mode', 'wds'),
				}
			}
		},
		description: __('Indicates whether the course will be conducted online, offline at a physical location, or a mix of both online and offline.', 'wds'),
		disallowDeletion: true,
	},
	startDate: {
		id: id(),
		label: __('Start Date', 'wds'),
		type: 'DateTime',
		source: 'datetime',
		value: '',
		description: __('The start date and start time of the course in ISO-8601 format.', 'wds'),
		disallowDeletion: true,
	},
	endDate: {
		id: id(),
		label: __('End Date', 'wds'),
		type: 'DateTime',
		source: 'datetime',
		value: '',
		description: __('The end date and end time of the course in ISO-8601 format.', 'wds'),
		disallowDeletion: true,
	},
	instructor: {
		id: id(),
		label: __('Instructors', 'wds'),
		labelSingle: __('Instructor', 'wds'),
		description: __('A person assigned to instruct or provide instructional assistance for the course instance.', 'wds'),
		disallowDeletion: true,
		properties: {
			0: {
				id: id(),
				type: 'Person',
				disallowDeletion: true,
				disallowFirstItemDeletionOnly: true,
				properties: CourseInstructor,
			},
		},
	},
	image: {
		id: id(),
		label: __('Images', 'wds'),
		labelSingle: __('Image', 'wds'),
		description: __('Images related to the course instance.', 'wds'),
		disallowDeletion: true,
		properties: {
			0: {
				id: id(),
				label: __('Image', 'wds'),
				type: 'ImageObject',
				source: 'post_data',
				value: 'post_thumbnail',
				disallowDeletion: true,
				disallowFirstItemDeletionOnly: true,
			},
		},
	},
	location: {
		id: id(),
		label: __('Location', 'wds'),
		activeVersion: 'Place',
		properties: {
			Place: {
				id: id(),
				label: __('Location', 'wds'),
				type: 'Place',
				properties: CoursePlace,
				description: __('The physical location where the course will be held.', 'wds'),
				disallowDeletion: true,
				disallowAddition: true,
				isAnAltVersion: true,
			},
			VirtualLocation: {
				id: id(),
				label: __('Virtual Location', 'wds'),
				type: 'VirtualLocation',
				disallowAddition: true,
				disallowDeletion: true,
				isAnAltVersion: true,
				properties: {
					url: {
						id: id(),
						label: __('URL', 'wds'),
						type: 'URL',
						source: 'post_data',
						disallowDeletion: true,
						value: 'post_permalink',
						description: __('The URL of the web page, where people can attend the course.', 'wds'),
					},
				},
				description: __('The virtual location of the course.', 'wds'),
			}
		},
	},
	offers: {
		id: id(),
		label: __('Price', 'wds'),
		description: __('Price information for the course.', 'wds'),
		properties: CourseOffer,
		disallowAddition: true,
		disallowDeletion: true,
	},
};
export default CourseInstance;
