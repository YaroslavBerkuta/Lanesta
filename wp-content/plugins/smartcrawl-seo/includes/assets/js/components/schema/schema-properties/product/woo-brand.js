import {__} from '@wordpress/i18n';
import {merge} from "lodash-es";
import ProductBrand from "./product-brand";

const WooBrand = merge({}, ProductBrand, {
	name: {
		source: 'woocommerce',
		value: 'product_category',
		customSources: {
			woocommerce: {
				label: __('WooCommerce', 'wds'),
				values: {
					product_category: __('Product Category', 'wds'),
					product_tag: __('Product Tag', 'wds'),
				}
			}
		}
	},
	url: {
		source: 'woocommerce',
		value: 'product_category_url',
		customSources: {
			woocommerce: {
				label: __('WooCommerce', 'wds'),
				values: {
					product_category_url: __('Product Category URL', 'wds'),
					product_tag_url: __('Product Tag URL', 'wds'),
				}
			}
		}
	},
	logo: {
		source: 'image',
		value: '',
	},
});

export default WooBrand;
