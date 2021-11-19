import {__} from "@wordpress/i18n";
import currencies from "../currencies";
import LocalPostalAddress from "./local-postal-address";
import LocalAggregateRating from "./local-aggregate-rating";
import LocalOpeningHoursSpecification from "./local-opening-hours-specification";
import LocalReview from "./local-review";
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const LocalBusiness = {
	"@id": {
		id: id(),
		label: __('@id', 'wds'),
		type: 'URL',
		source: 'site_settings',
		value: 'site_url',
		required: true,
		description: __('Globally unique ID of the specific business location in the form of a URL. The ID should be stable and unchanging over time. Google Search treats the URL as an opaque string and it does not have to be a working link. If the business has multiple locations, make sure the @id is unique for each location.', 'wds'),
	},
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'site_settings',
		value: 'site_name',
		required: true,
		description: __('The name of the business.', 'wds'),
	},
	logo: {
		id: id(),
		label: __('Logo', 'wds'),
		type: 'ImageObject',
		source: 'schema_settings',
		value: 'organization_logo',
		description: __('The logo of the business.', 'wds'),
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'site_settings',
		value: 'site_url',
		description: __('The fully-qualified URL of the specific business location. Unlike the @id property, this URL property should be a working link.', 'wds'),
	},
	priceRange: {
		id: id(),
		label: __('Price Range', 'wds'),
		type: 'Text',
		source: 'options',
		value: '$',
		customSources: {
			options: {
				label: __('Price Range', 'wds'),
				values: {
					$: '$',
					$$: '$$',
					$$$: __('$$$', 'wds'),
				}
			}
		},
		description: __('The relative price range of a business, commonly specified by either a numerical range (for example, "$10-15") or a normalized number of currency signs (for example, "$$$").', 'wds'),
	},
	telephone: {
		id: id(),
		label: __('Telephone', 'wds'),
		type: 'Phone',
		source: 'schema_settings',
		value: 'organization_phone_number',
		description: __('A business phone number meant to be the primary contact method for customers. Be sure to include the country code and area code in the phone number.', 'wds'),
	},
	currenciesAccepted: {
		id: id(),
		label: __('Currencies Accepted', 'wds'),
		type: 'Text',
		source: 'options',
		value: ['USD'],
		allowMultipleSelection: true,
		customSources: {
			options: {
				label: __('Currencies', 'wds'),
				values: currencies
			}
		},
		description: __('The currency accepted at the business.', 'wds'),
	},
	paymentAccepted: {
		id: id(),
		label: __('Payment Accepted', 'wds'),
		type: 'Text',
		source: 'options',
		value: ['Cash'],
		allowMultipleSelection: true,
		customSources: {
			options: {
				label: __('Payment Accepted', 'wds'),
				values: {
					"Cash": __('Cash', 'wds'),
					"Credit Card": __('Credit Card', 'wds'),
					"Cryptocurrency": __('Cryptocurrency', 'wds'),
				}
			}
		},
		description: __('Modes of payment accepted at the local business.', 'wds'),
	},
	address: {
		id: id(),
		label: __('Address', 'wds'),
		properties: {
			0: {
				id: id(),
				type: 'PostalAddress',
				properties: LocalPostalAddress
			}
		},
		required: true,
		description: __('The physical location of the business. Include as many properties as possible. The more properties you provide, the higher quality the result is to users.', 'wds'),
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
				source: 'schema_settings',
				value: 'organization_logo',
			}
		},
		description: __('One or more images of the local business.', 'wds'),
		required: true,
	},
	aggregateRating: {
		id: id(),
		label: __('Aggregate Rating', 'wds'),
		type: 'AggregateRating',
		properties: LocalAggregateRating,
		description: __('The average rating of the local business based on multiple ratings or reviews.', 'wds'),
	},
	geo: {
		id: id(),
		label: __('Geo Coordinates'),
		type: 'GeoCoordinates',
		disallowAddition: true,
		properties: {
			latitude: {
				id: id(),
				label: __('Latitude', 'wds'),
				type: 'Text',
				source: 'custom_text',
				value: '',
				disallowDeletion: true,
				description: __('The latitude of the business location. The precision should be at least 5 decimal places.', 'wds'),
				placeholder: __('E.g. 37.42242', 'wds'),
			},
			longitude: {
				id: id(),
				label: __('Longitude', 'wds'),
				type: 'Text',
				source: 'custom_text',
				value: '',
				disallowDeletion: true,
				description: __('The longitude of the business location. The precision should be at least 5 decimal places.'),
				placeholder: __('E.g. -122.08585', 'wds'),
			},
		},
		description: __('Give search engines the exact location of your business by adding the geographic latitude and longitude coordinates.', 'wds'),
	},
	openingHoursSpecification: {
		id: id(),
		label: __('Opening Hours', 'wds'),
		labelSingle: __('Opening Hours Specification', 'wds'),
		properties: {
			0: {
				id: id(),
				label: __('Opening Hours'),
				type: 'OpeningHoursSpecification',
				disallowAddition: true,
				properties: LocalOpeningHoursSpecification,
			}
		},
		description: __('Hours during which the business location is open.', 'wds'),
	},
	review: {
		id: id(),
		label: __('Reviews', 'wds'),
		labelSingle: __('Review', 'wds'),
		properties: {
			0: {
				id: id(),
				type: 'Review',
				properties: LocalReview
			}
		},
		description: __('Reviews of the local business.', 'wds'),
		optional: true,
	},
};
export default LocalBusiness;
