import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const JobApplicantLocationRequirement = {
	"@type": {
		id: id(),
		label: __('Administrative Area Type', 'wds'),
		type: 'Text',
		source: 'options',
		value: 'Country',
		disallowDeletion: true,
		customSources: {
			options: {
				label: __('Administrative Area Type', 'wds'),
				values: {
					Country: __('Country', 'wds'),
					City: __('City', 'wds'),
					State: __('State', 'wds'),
				},
			},
		},
	},
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		description: __('The name of the administrative area.', 'wds'),
		disallowDeletion: true,
	},
};
export default JobApplicantLocationRequirement;
