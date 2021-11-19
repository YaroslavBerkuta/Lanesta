import merge from "lodash-es/merge";
import WooProduct from "./woo-product";

const WooSimpleProduct = merge({}, WooProduct, {
	offers: {
		activeVersion: 'Offer',
	}
});

export default WooSimpleProduct;
