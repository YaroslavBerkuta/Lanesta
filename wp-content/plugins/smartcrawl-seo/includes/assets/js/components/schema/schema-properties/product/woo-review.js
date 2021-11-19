import {__} from '@wordpress/i18n';
import {merge} from "lodash-es";
import ProductReview from "./product-review";

const WooReview = merge({}, ProductReview, {
	reviewBody: {
		source: 'woocommerce_review',
		value: 'comment_text',
		customSources: {
			woocommerce_review: {
				label: __('WooCommerce Review', 'wds'),
				values: {
					comment_text: __('Review Text', 'wds'),
				}
			}
		}
	},
	datePublished: {
		source: 'woocommerce_review',
		value: 'comment_date',
		customSources: {
			woocommerce_review: {
				label: __('WooCommerce Review', 'wds'),
				values: {
					comment_date: __('Date Published', 'wds'),
				}
			}
		}
	},
	author: {
		properties: {
			Person: {
				properties: {
					name: {
						source: 'woocommerce_review',
						value: 'comment_author_name',
						customSources: {
							woocommerce_review: {
								label: __('WooCommerce Review', 'wds'),
								values: {
									comment_author_name: __('Author Name', 'wds'),
								}
							}
						}
					}
				}
			},
			Organization: {
				properties: {
					name: {
						source: 'woocommerce_review',
						value: 'comment_author_name',
						customSources: {
							woocommerce_review: {
								label: __('WooCommerce Review', 'wds'),
								values: {
									comment_author_name: __('Author Name', 'wds'),
								}
							}
						}
					}
				}
			}
		}
	},
	reviewRating: {
		
		properties: {
			ratingValue: {
				source: 'woocommerce_review',
				value: 'rating_value',
				customSources: {
					woocommerce_review: {
						label: __('WooCommerce Review', 'wds'),
						values: {
							rating_value: __('Rating Value', 'wds'),
						}
					}
				}
			},
			bestRating: {
				value: '5',
			},
			worstRating: {
				value: '1',
			}
		}
	}
});

export default WooReview;
