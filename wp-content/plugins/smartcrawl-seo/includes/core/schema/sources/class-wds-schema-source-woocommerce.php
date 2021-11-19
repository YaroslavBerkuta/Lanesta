<?php

class Smartcrawl_Schema_Source_Woocommerce extends Smartcrawl_Schema_Property_Source {
	const ID = 'woocommerce';

	const STOCK_STATUS = 'stock_status';
	const CURRENCY = 'currency';
	const PRICE = 'price';
	const SALE_START_DATE = 'date_on_sale_from';
	const SALE_END_DATE = 'date_on_sale_to';
	const MIN_PRICE = 'min_price';
	const MAX_PRICE = 'max_price';
	const PRODUCT_CHILDREN_COUNT = 'product_children_count';
	const PRODUCT_ID = 'product_id';
	const SKU = 'sku';
	const REVIEW_COUNT = 'review_count';
	const AVERAGE_RATING = 'average_rating';
	const PRODUCT_CATEGORY = 'product_category';
	const PRODUCT_CATEGORY_URL = 'product_category_url';
	const PRODUCT_TAG = 'product_tag';
	const PRODUCT_TAG_URL = 'product_tag_url';

	/**
	 * @var WC_Product
	 */
	private $product = false;
	private $field;

	public function __construct( $post, $field ) {
		parent::__construct();

		if ( ! $this->woocommerce_active() ) {
			return;
		}

		$this->product = wc_get_product( $post );
		$this->field = $field;
	}

	public function get_value() {
		if ( ! $this->woocommerce_active() || ! $this->product ) {
			return '';
		}

		$price = $this->product->get_price();
		$is_variable_product = is_a( $this->product, 'WC_Product_Variable' );

		switch ( $this->field ) {
			case self::PRODUCT_ID:
				return $this->product->get_id();

			case self::SKU:
				return $this->product->get_sku();

			case self::STOCK_STATUS:
				return $this->product->is_in_stock() ? 'InStock' : 'OutOfStock';

			case self::CURRENCY:
				return get_woocommerce_currency();

			case self::PRICE:
				return $price;

			case self::SALE_START_DATE:
				return $this->format_date( $this->product->get_date_on_sale_from() );

			case self::SALE_END_DATE:
				if ( $this->product->is_on_sale() && $this->product->get_date_on_sale_to() ) {
					return $this->format_date( $this->product->get_date_on_sale_to() );
				} else {
					return gmdate( 'Y-12-31', time() + YEAR_IN_SECONDS );
				}

			case self::MIN_PRICE:
				return $is_variable_product
					? $this->format_price( $this->product->get_variation_price( 'min', false ) )
					: $price;

			case self::MAX_PRICE:
				return $is_variable_product
					? $this->format_price( $this->product->get_variation_price( 'max', false ) )
					: $price;

			case self::PRODUCT_CHILDREN_COUNT:
				return count( $this->product->get_children() );

			case self::REVIEW_COUNT:
				return $this->product->get_review_count();

			case self::AVERAGE_RATING:
				return $this->product->get_average_rating();

			case self::PRODUCT_CATEGORY:
				$product_category = $this->get_object_term( 'product_cat' );
				return $product_category
					? $product_category->name
					: '';

			case self::PRODUCT_CATEGORY_URL:
				$product_category = $this->get_object_term( 'product_cat' );
				return $product_category
					? get_term_link( $product_category->term_id )
					: '';

			case self::PRODUCT_TAG:
				$product_tag = $this->get_object_term( 'product_tag' );
				return $product_tag
					? $product_tag->name
					: '';

			case self::PRODUCT_TAG_URL:
				$product_tag = $this->get_object_term( 'product_tag' );
				return $product_tag
					? get_term_link( $product_tag->term_id )
					: '';

			default:
				return '';
		}
	}

	private function format_price( $price ) {
		return wc_format_decimal( $price, wc_get_price_decimals() );
	}

	/**
	 * @param $date WC_DateTime
	 *
	 * @return string
	 */
	private function format_date( $date ) {
		return $date ? gmdate( 'Y-m-d', $date->getTimestamp() ) : '';
	}

	/**
	 * @return bool
	 */
	private function woocommerce_active() {
		return class_exists( 'woocommerce' );
	}

	/**
	 * @param $taxonomy
	 *
	 * @return WP_Term|null
	 */
	private function get_object_term( $taxonomy ) {
		$terms = wp_get_object_terms( $this->product->get_id(), $taxonomy );
		if ( is_wp_error( $terms ) ) {
			return null;
		}
		return smartcrawl_get_array_value( $terms, 0 );
	}
}
