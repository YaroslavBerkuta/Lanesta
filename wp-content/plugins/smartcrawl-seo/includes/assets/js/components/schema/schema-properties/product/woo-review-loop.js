import {merge} from "lodash-es";
import WooReview from "./woo-review";

const WooReviewLoop = merge({}, WooReview, {
	itemReviewed: {
		properties: {
			name: {
				disallowDeletion: false,
			}
		},
	},
	reviewBody: {
		disallowDeletion: false,
	},
	datePublished: {
		disallowDeletion: false,
	},
	author: {
		properties: {
			Person: {
				disallowDeletion: false,
				disallowAddition: false,
				properties: {
					name: {
						disallowDeletion: false,
					},
					url: {
						disallowDeletion: false,
					},
					description: {
						disallowDeletion: false,
					},
					image: {
						disallowDeletion: false,
					}
				},
			},
			Organization: {
				disallowDeletion: false,
				disallowAddition: false,
				properties: {
					logo: {
						disallowDeletion: false,
					},
					name: {
						disallowDeletion: false,
					},
					url: {
						disallowDeletion: false,
					},
				},
			}
		},
	},
	reviewRating: {
		disallowDeletion: false,
		disallowAddition: false,
		properties: {
			ratingValue: {
				disallowDeletion: false,
			},
			bestRating: {
				disallowDeletion: false,
			},
			worstRating: {
				disallowDeletion: false,
			},
		},
	},
});
export default WooReviewLoop;
