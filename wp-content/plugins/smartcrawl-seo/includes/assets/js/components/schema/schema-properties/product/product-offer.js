import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";
import currencies from "../currencies";

const id = uniqueId;
const ProductOffer = {
	availability: {
		id: id(),
		label: __('Availability', 'wds'),
		type: 'Text',
		source: 'options',
		value: 'InStock',
		disallowDeletion: true,
		customSources: {
			options: {
				label: __('Availability', 'wds'),
				values: {
					InStock: __('In Stock', 'wds'),
					SoldOut: __('Sold Out', 'wds'),
					PreOrder: __('PreOrder', 'wds'),
				}
			}
		},
		description: __('The possible product availability options.', 'wds'),
	},
	price: {
		id: id(),
		label: __('Price', 'wds'),
		type: 'Number',
		source: 'number',
		value: '',
		required: true,
		disallowDeletion: true,
		description: __('The offer price of a product.', 'wds'),
	},
	priceCurrency: {
		id: id(),
		label: __('Price Currency Code', 'wds'),
		type: 'Text',
		source: 'options',
		value: 'USD',
		customSources: {
			options: {
				label: __('Currencies', 'wds'),
				values: currencies,
			},
		},
		disallowDeletion: true,
		description: __('The currency used to describe the product price, in three-letter ISO 4217 format.', 'wds'),
	},
	validFrom: {
		id: id(),
		label: __('Valid From', 'wds'),
		type: 'DateTime',
		source: 'datetime',
		value: '',
		disallowDeletion: true,
		description: __('The date when the item becomes valid.', 'wds'),
	},
	priceValidUntil: {
		id: id(),
		label: __('Valid Until', 'wds'),
		type: 'DateTime',
		source: 'datetime',
		value: '',
		disallowDeletion: true,
		description: __('The date after which the price is no longer available.', 'wds'),
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'post_data',
		value: 'post_permalink',
		disallowDeletion: true,
		description: __('A URL to the product web page (that includes the Offer).', 'wds'),
	},
	/*shippingDetails: {
	id: id(),
	label: __('Shipping Details', 'wds'),
	disallowDeletion: true,
	disallowAddition: true,
	properties: {
		shippingDestination: {
			id: id(),
			label: __('Shipping Destination', 'wds'),
			disallowDeletion: true,
			disallowAddition: true,
			properties: {
				addressCountry: {
					id: id(),
					label: __('Address Country', 'wds'),
					type: 'Text',
					source: 'custom_text',
					value: '',
					description: __('The 2-digit country code, in ISO 3166-1 format e.g. US', 'wds'),
					disallowDeletion: true,
				},
				addressRegion: {
					id: id(),
					label: __('Address Region', 'wds'),
					type: 'Text',
					source: 'custom_text',
					value: '',
					description: __('2- or 3-digit ISO 3166-2 subdivision code, without country prefix.', 'wds'),
					disallowDeletion: true,
				},
				postalCode: {
					id: id(),
					label: __('Postal Code', 'wds'),
					type: 'Text',
					source: 'custom_text',
					value: '',
					description: __('The postal code. For example, 94043.', 'wds'),
					disallowDeletion: true,
				},
				postalCodePrefix: {
					id: id(),
					label: __('Postal Code Prefix', 'wds'),
					type: 'Text',
					source: 'custom_text',
					value: '',
					description: __('A defined range of postal codes indicated by a common textual prefix. Use this property for non-numeric systems, such as the UK.', 'wds'),
					disallowDeletion: true,
				},
				postalCodeRange: {
					id: id(),
					label: __('Postal Code Range', 'wds'),
					disallowDeletion: true,
					disallowAddition: true,
					properties: {
						postalCodeBegin: {
							id: id(),
							label: __('First Postal Code', 'wds'),
							type: 'Text',
							source: 'custom_text',
							value: '',
							description: __('First postal code in a range.', 'wds'),
							disallowDeletion: true,
						},
						postalCodeEnd: {
							id: id(),
							label: __('Last Postal Code', 'wds'),
							type: 'Text',
							source: 'custom_text',
							value: '',
							description: __('Last postal code in the range. Needs to be after postalCodeBegin.', 'wds'),
							disallowDeletion: true,
						}
					}
				}
			}
		},
		deliveryTime: {
			id: id(),
			label: __('Delivery Time', 'wds'),
			disallowDeletion: true,
			disallowAddition: true,
			properties: {
				businessDays: {
					id: id(),
					label: __('Business Days', 'wds'),
					disallowDeletion: true,
					disallowAddition: true,
					properties: {
						dayOfWeek: {
							id: id(),
							label: __('Days of Week', 'wds'),
							labelSingle: __('Day of Week', 'wds'),
							disallowDeletion: true,
							properties: {
								0: {
									id: id(),
									label: __('Day of Week', 'wds'),
									type: 'Text',
									disallowDeletion: true,
									disallowFirstItemDeletionOnly: true,
									source: 'options',
									value: 'Monday',
									customSources: {
										options: {
											label: __('Day of Week', 'wds'),
											values: {
												Monday: __('Monday', 'wds'),
												Tuesday: __('Tuesday', 'wds'),
												Wednesday: __('Wednesday', 'wds'),
												Thursday: __('Thursday', 'wds'),
												Friday: __('Friday', 'wds'),
												Saturday: __('Saturday', 'wds'),
												Sunday: __('Sunday', 'wds'),
											}
										}
									}
								}
							}
						}
					}
				},
				cutOffTime: {
					id: id(),
					label: __('Cut Off Time', 'wds'),
					type: 'Text',
					source: 'custom_text',
					value: '',
					disallowDeletion: true,
				},
				handlingTime: {
					id: id(),
					label: __('Handling Time', 'wds'),
					disallowDeletion: true,
					disallowAddition: true,
					properties: {
						minValue: {
							id: id(),
							label: __('Min Number of Days', 'wds'),
							type: 'Text',
							source: 'custom_text',
							value: '',
							disallowDeletion: true,
						},
						maxValue: {
							id: id(),
							label: __('Max Number of Days', 'wds'),
							type: 'Text',
							source: 'custom_text',
							value: '',
							disallowDeletion: true,
						}
					}
				},
				transitTime: {
					id: id(),
					label: __('Transit Time', 'wds'),
					disallowDeletion: true,
					disallowAddition: true,
					properties: {
						minValue: {
							id: id(),
							label: __('Min Number of Days', 'wds'),
							type: 'Text',
							source: 'custom_text',
							value: '',
							disallowDeletion: true,
						},
						maxValue: {
							id: id(),
							label: __('Max Number of Days', 'wds'),
							type: 'Text',
							source: 'custom_text',
							value: '',
							disallowDeletion: true,
						}
					}
				},
			}
		},
		doesNotShip: {
			id: id(),
			label: __('Does Not Ship', 'wds'),
			type: 'Text',
			source: 'options',
			value: 'True',
			disallowDeletion: true,
			customSources: {
				options: {
					label: __('Boolean Value', 'wds'),
					values: {
						True: __('True', 'wds'),
						False: __('False', 'wds'),
					}
				}
			}
		},
		shippingRate: {
			id: id(),
			label: __('Shipping Rate', 'wds'),
			disallowDeletion: true,
			disallowAddition: true,
			properties: {
				currency: {
					id: id(),
					label: __('Currency Code', 'wds'),
					type: 'Text',
					source: 'custom_text',
					value: '',
					disallowDeletion: true,
				},
				value: {
					id: id(),
					label: __('Rate Value', 'wds'),
					type: 'Text',
					source: 'custom_text',
					value: '',
					disallowDeletion: true,
				}
			}
		},
	}
	}*/
};
export default ProductOffer;
