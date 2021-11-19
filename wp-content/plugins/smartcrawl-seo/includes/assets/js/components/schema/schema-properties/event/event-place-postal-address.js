import merge from "lodash-es/merge";
import EventPostalAddress from "./event-postal-address";

const EventPlacePostalAddress = merge({}, EventPostalAddress, {
	streetAddress: {disallowDeletion: false},
	addressLocality: {disallowDeletion: false},
	addressRegion: {disallowDeletion: false},
	addressCountry: {disallowDeletion: false},
	postalCode: {disallowDeletion: false},
	postOfficeBoxNumber: {disallowDeletion: false},
});
export default EventPlacePostalAddress;
