import {__} from '@wordpress/i18n';
import {merge} from "lodash-es";
import ProductOffer from "./product-offer";

const WooOffer = merge({}, ProductOffer, {
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
	price: {
		source: 'woocommerce',
		value: 'price',
		customSources: {
			woocommerce: {
				label: __('WooCommerce', 'wds'),
				values: {
					price: __('Price', 'wds'),
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
	validFrom: {
		source: 'woocommerce',
		value: 'date_on_sale_from',
		customSources: {
			woocommerce: {
				label: __('WooCommerce', 'wds'),
				values: {
					date_on_sale_from: __('Product Sale Start Date', 'wds'),
				}
			}
		}
	},
	priceValidUntil: {
		source: 'woocommerce',
		value: 'date_on_sale_to',
		customSources: {
			woocommerce: {
				label: __('WooCommerce', 'wds'),
				values: {
					date_on_sale_to: __('Product Sale End Date', 'wds'),
				}
			}
		}
	}
});

export default WooOffer;
