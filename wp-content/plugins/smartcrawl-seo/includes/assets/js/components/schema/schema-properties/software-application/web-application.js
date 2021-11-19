import merge from "lodash-es/merge";
import uniqueId from "lodash-es/uniqueId";
import {__} from "@wordpress/i18n";
import SoftwareApplication from "./software-application";

const id = uniqueId;
const WebApplication = merge({}, SoftwareApplication, {
	browserRequirements: {
		id: id(),
		label: __('Browser Requirements', 'wds'),
		description: __('Specifies browser requirements in human-readable text.', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		optional: true,
		placeholder: __('E.g. requires HTML5 support', 'wds'),
	},
});
export default WebApplication;
