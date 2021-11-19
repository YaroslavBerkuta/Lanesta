import {__} from '@wordpress/i18n';
import uniqueId from "lodash-es/uniqueId";
import EventOffer from "./event-offer";
import EventPlace from "./event-place";
import EventAggregateOffer from "./event-aggregate-offer";
import EventPerformerPerson from "./event-performer-person";
import EventOrganizerOrganization from "./event-organizer-organization";
import AggregateRating from "../aggregate-rating";
import Review from "../review/review";

const id = uniqueId;
const Event = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'post_data',
		value: 'post_title',
		required: true,
		description: __('The full title of the event.', 'wds'),
	},
	description: {
		id: id(),
		label: __('Description', 'wds'),
		type: 'TextFull',
		source: 'seo_meta',
		value: 'seo_description',
		description: __('Description of the event. Describe all details of the event to make it easier for users to understand and attend the event.', 'wds'),
	},
	startDate: {
		id: id(),
		label: __('Start Date', 'wds'),
		type: 'DateTime',
		source: 'datetime',
		value: '',
		required: true,
		description: __('The start date and start time of the event in ISO-8601 format.', 'wds'),
	},
	endDate: {
		id: id(),
		label: __('End Date', 'wds'),
		type: 'DateTime',
		source: 'datetime',
		value: '',
		description: __('The end date and end time of the event in ISO-8601 format.', 'wds'),
	},
	eventAttendanceMode: {
		id: id(),
		label: __('Event Attendance Mode', 'wds'),
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
		description: __('Indicates whether the event occurs online, offline at a physical location, or a mix of both online and offline.', 'wds'),
	},
	eventStatus: {
		id: id(),
		label: __('Event Status', 'wds'),
		type: 'Text',
		source: 'options',
		value: 'EventScheduled',
		customSources: {
			options: {
				label: __('Event Status', 'wds'),
				values: {
					EventScheduled: __('Scheduled', 'wds'),
					EventMovedOnline: __('Moved Online', 'wds'),
					EventRescheduled: __('Rescheduled', 'wds'),
					EventPostponed: __('Postponed', 'wds'),
					EventCancelled: __('Cancelled', 'wds'),
				}
			},
		},
		description: __('The status of the event.', 'wds'),
	},
	image: {
		id: id(),
		label: __('Images', 'wds'),
		labelSingle: __('Image', 'wds'),
		properties: {
			0: {
				id: id(),
				label: __('Image', 'wds'),
				type: 'ImageObject',
				source: 'image',
				value: ''
			}
		},
		description: __('Image or logo for the event or tour. Including an image helps users understand and engage with your event.', 'wds'),
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
				properties: EventPlace,
				required: true,
				description: __('The physical location of the event.', 'wds'),
				isAnAltVersion: true,
			},
			VirtualLocation: {
				id: id(),
				label: __('Virtual Location', 'wds'),
				type: 'VirtualLocation',
				disallowAddition: true,
				properties: {
					url: {
						id: id(),
						label: __('URL', 'wds'),
						type: 'URL',
						source: 'post_data',
						disallowDeletion: true,
						value: 'post_permalink',
						required: true,
						description: __('The URL of the online event, where people can join. This property is required if your event is happening online.', 'wds'),
					},
				},
				required: true,
				description: __('The virtual location of the event.', 'wds'),
				isAnAltVersion: true,
			}
		},
		required: true,
	},
	organizer: {
		id: id(),
		label: __('Organizer', 'wds'),
		type: 'Organization',
		properties: EventOrganizerOrganization,
		description: __('The organization that is hosting the event.', 'wds'),
	},
	performer: {
		id: id(),
		label: __('Performers', 'wds'),
		labelSingle: __('Performer', 'wds'),
		properties: {
			0: {
				id: id(),
				type: 'Person',
				properties: EventPerformerPerson,
			}
		},
		description: __('The participants performing at the event, such as artists and comedians.', 'wds'),
	},
	offers: {
		id: id(),
		label: __('Offers', 'wds'),
		activeVersion: 'Offer',
		properties: {
			Offer: {
				id: id(),
				label: __('Offers', 'wds'),
				labelSingle: __('Offer', 'wds'),
				properties: {
					0: {
						id: id(),
						type: 'Offer',
						properties: EventOffer
					}
				},
				description: __('A nested Offer, one for each ticket type.', 'wds'),
				isAnAltVersion: true,
			},
			AggregateOffer: {
				id: id(),
				type: 'AggregateOffer',
				label: __('Aggregate Offer', 'wds'),
				properties: EventAggregateOffer,
				description: __('A nested AggregateOffer, representing all available offers.', 'wds'),
				isAnAltVersion: true,
			}
		}
	},
	aggregateRating: {
		id: id(),
		label: __('Aggregate Rating', 'wds'),
		type: 'AggregateRating',
		properties: AggregateRating,
		description: __('A nested aggregateRating of the event.', 'wds'),
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
		description: __('Reviews of the event.', 'wds'),
		optional: true,
	},
};

export default Event;
