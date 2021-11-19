import {__} from "@wordpress/i18n";
import JobPostalAddress from "./job-postal-address";
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const JobPlace = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'custom_text',
		value: '',
		description: __("The name of the place where the employee will report to work.", 'wds'),
		disallowDeletion: true,
	},
	address: {
		id: id(),
		label: __('Address', 'wds'),
		type: 'PostalAddress',
		properties: JobPostalAddress,
		description: __("The address of the place where the employee will report to work.", 'wds'),
		disallowAddition: true,
		disallowDeletion: true,
		required: true,
	},
};

export default JobPlace;
