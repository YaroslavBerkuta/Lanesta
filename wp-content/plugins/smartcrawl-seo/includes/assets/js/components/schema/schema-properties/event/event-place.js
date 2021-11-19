import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";
import EventPlacePostalAddress from "./event-place-postal-address";

const id = uniqueId;
const EventPlace = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'custom_text',
		value: '',
		description: __("The detailed name of the place or venue where the event is being held. This property is only recommended for events that take place at a physical location.", 'wds'),
	},
	address: {
		id: id(),
		label: __('Address', 'wds'),
		type: 'PostalAddress',
		properties: EventPlacePostalAddress,
		required: true,
		description: __("The venue's detailed street address. This property is only required for events that take place at a physical location.", 'wds'),
	},
};
export default EventPlace;
