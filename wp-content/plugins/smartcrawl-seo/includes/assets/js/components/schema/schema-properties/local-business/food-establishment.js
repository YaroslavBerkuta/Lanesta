import {__} from '@wordpress/i18n';
import LocalBusiness from "./local-business";
import {merge} from "lodash-es";
import uniqueId from "lodash-es/uniqueId";

const id = uniqueId;
const FoodEstablishment = merge({}, {
	acceptsReservations: {
		id: id(),
		label: __('Accepts Reservations', 'wds'),
		type: 'Text',
		source: 'options',
		value: 'True',
		customSources: {
			options: {
				label: __('Boolean Value', 'wds'),
				values: {
					True: __('True', 'wds'),
					False: __('False', 'wds'),
				}
			}
		},
	},
	menu: {
		id: id(),
		label: __('Menu URL', 'wds'),
		type: 'URL',
		source: 'custom_text',
		value: ''
	},
	servesCuisine: {
		id: id(),
		label: __('Serves Cuisine', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: ''
	},
}, LocalBusiness);

export default FoodEstablishment;
