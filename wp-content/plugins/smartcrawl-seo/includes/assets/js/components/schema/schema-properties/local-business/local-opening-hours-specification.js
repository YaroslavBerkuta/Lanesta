import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";
import time from "./local-time";

const id = uniqueId;
const LocalOpeningHoursSpecification = {
	dayOfWeek: {
		id: id(),
		label: __('Days of Week', 'wds'),
		disallowDeletion: true,
		type: 'Array',
		allowMultipleSelection: true,
		source: 'options',
		value: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
		customSources: {
			options: {
				label: __('Days of Week', 'wds'),
				values: {
					Monday: __('Monday', 'wds'),
					Tuesday: __('Tuesday', 'wds'),
					Wednesday: __('Wednesday', 'wds'),
					Thursday: __('Thursday', 'wds'),
					Friday: __('Friday', 'wds'),
					Saturday: __('Saturday', 'wds'),
					Sunday: __('Sunday', 'wds'),
				}
			}
		},
		description: __('One or more days of the week.', 'wds'),
	},
	opens: {
		id: id(),
		label: __('Opens', 'wds'),
		type: 'Text',
		disallowDeletion: true,
		source: 'options',
		value: '09:00',
		description: __('The time the business location opens, in hh:mm:ss format.', 'wds'),
		customSources: {
			options: {
				label: __('Time', 'wds'),
				values: time,
			},
		},
	},
	closes: {
		id: id(),
		label: __('Closes', 'wds'),
		type: 'Text',
		disallowDeletion: true,
		source: 'options',
		value: '21:00',
		description: __('The time the business location closes, in hh:mm:ss format.', 'wds'),
		customSources: {
			options: {
				label: __('Time', 'wds'),
				values: time,
			},
		},
	},
};
export default LocalOpeningHoursSpecification;
