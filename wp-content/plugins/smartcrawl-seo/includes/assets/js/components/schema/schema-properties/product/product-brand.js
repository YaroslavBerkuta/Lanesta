import uniqueId from "lodash-es/uniqueId";
import {__} from "@wordpress/i18n";

const id = uniqueId;
const ProductBrand = {
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'schema_settings',
		value: 'organization_name',
		description: __('The name of the brand.', 'wds'),
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'site_settings',
		value: 'site_url',
		description: __('The URL of the brand.', 'wds'),
	},
	logo: {
		id: id(),
		label: __('Logo', 'wds'),
		type: 'ImageObject',
		source: 'schema_settings',
		value: 'organization_logo',
		description: __('The logo of the brand.', 'wds'),
		optional: true
	},
};
export default ProductBrand;
