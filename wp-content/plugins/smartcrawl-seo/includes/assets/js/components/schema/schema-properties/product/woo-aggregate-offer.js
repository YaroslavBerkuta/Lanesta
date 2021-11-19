import {merge} from "lodash-es";
import {__} from '@wordpress/i18n';
import ProductAggregateOffer from "./product-aggregate-offer";

const WooAggregateOffer = merge({}, ProductAggregateOffer, {
	availability: {
		source: 'woocommerce',
		value: 'stock_status',
		customSources: {
			woocommerce: {
				label: __('WooCommerce', 'wds'),
				values: {
					stock_status: __('Stock Status', 'wds'),
				}
			}
		}
	},
	lowPrice: {
		source: 'woocommerce',
		value: 'min_price',
		customSources: {
			woocommerce: {
				label: __('WooCommerce', 'wds'),
				values: {
					min_price: __('Variable Product Minimum Price', 'wds'),
				}
			}
		}
	},
	highPrice: {
		source: 'woocommerce',
		value: 'max_price',
		customSources: {
			woocommerce: {
				label: __('WooCommerce', 'wds'),
				values: {
					max_price: __('Variable Product Maximum Price', 'wds'),
				}
			}
		}
	},
	priceCurrency: {
		source: 'woocommerce',
		value: 'currency',
		customSources: {
			woocommerce: {
				label: __('WooCommerce', 'wds'),
				values: {
					currency: __('Currency', 'wds'),
				}
			}
		}
	},
	offerCount: {
		source: 'woocommerce',
		value: 'product_children_count',
		customSources: {
			woocommerce: {
				label: __('WooCommerce', 'wds'),
				values: {
					product_children_count: __('Number of Variations', 'wds'),
				}
			}
		}
	}
});

export default WooAggregateOffer;
