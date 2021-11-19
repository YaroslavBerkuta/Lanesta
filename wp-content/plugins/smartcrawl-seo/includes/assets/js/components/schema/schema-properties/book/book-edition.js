import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";
import BookPerson from "./book-person";

const id = uniqueId;
const BookEdition = {
	"@id": {
		id: id(),
		label: __('@id', 'wds'),
		type: 'URL',
		source: 'custom_text',
		value: '',
		required: true,
		disallowDeletion: true,
		description: __("A globally unique ID for the edition in URL format. It must be unique to your organization. The ID must be stable and not change over time. URL format is suggested though not required. It doesn't have to be a working link. The domain used for the @id value must be owned by your organization.", 'wds'),
	},
	bookFormat: {
		id: id(),
		label: __('Book Format', 'wds'),
		type: 'Text',
		source: 'options',
		value: 'Paperback',
		disallowDeletion: true,
		description: __('The format of the edition.', 'wds'),
		required: true,
		customSources: {
			options: {
				label: __('Book Formats', 'wds'),
				values: {
					Paperback: __('Paperback', 'wds'),
					Hardcover: __('Hardcover', 'wds'),
					EBook: __('EBook', 'wds'),
					AudiobookFormat: __('Audiobook', 'wds'),
				},
			},
		},
	},
	inLanguage: {
		id: id(),
		label: __('Language', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		disallowDeletion: true,
		required: true,
		description: __("The main language of the content in the edition. Use one of the two-letter codes from the list of ISO 639-1 alpha-2 codes.", 'wds'),
		placeholder: __('E.g. en', 'wds'),
	},
	isbn: {
		id: id(),
		label: __('ISBN', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		disallowDeletion: true,
		required: true,
		description: __("The ISBN-13 of the edition. If you have ISBN-10, convert it into ISBN-13. If there's no ISBN for the ebook or audiobook, use the ISBN of the print book instead. For example, if the ebook edition doesn't have an ISBN, use the ISBN for the associated print edition.", 'wds'),
	},
	bookEdition: {
		id: id(),
		label: __('Book Edition', 'wds'),
		type: 'Text',
		source: 'custom_text',
		value: '',
		disallowDeletion: true,
		description: __("The edition information of the book in free text format. For example, 2nd Edition.", 'wds'),
		placeholder: __('E.g. 2nd Edition', 'wds'),
	},
	datePublished: {
		id: id(),
		label: __('Date Published', 'wds'),
		type: 'DateTime',
		source: 'datetime',
		value: '',
		disallowDeletion: true,
		description: __('The date of publication of the edition in YYYY-MM-DD or YYYY format. This can be either a specific date or only a specific year.', 'wds')
	},
	name: {
		id: id(),
		label: __('Name', 'wds'),
		type: 'TextFull',
		source: 'custom_text',
		value: '',
		disallowDeletion: true,
		description: __('The title of the edition. Only use this when the title of the edition is different from the title of the work.', 'wds'),
	},
	url: {
		id: id(),
		label: __('URL', 'wds'),
		type: 'URL',
		source: 'custom_text',
		value: '',
		disallowDeletion: true,
		description: __('The URL on your website where the edition is introduced or described.', 'wds'),
	},
	identifier: {
		id: id(),
		label: __('Identifiers', 'wds'),
		labelSingle: __('Identifier', 'wds'),
		description: __("The external or other ID that unambiguously identifies this edition.", 'wds'),
		disallowDeletion: true,
		properties: {
			0: {
				id: id(),
				type: 'PropertyValue',
				disallowDeletion: true,
				disallowFirstItemDeletionOnly: true,
				properties: {
					propertyID: {
						id: id(),
						label: __('Type', 'wds'),
						type: 'Text',
						source: 'options',
						value: '',
						description: __('The identifier type.', 'wds'),
						disallowDeletion: true,
						customSources: {
							options: {
								label: __('Identifier Types', 'wds'),
								values: {
									"": __('None', 'wds'),
									OCLC_NUMBER: __('OCLC_NUMBER', 'wds'),
									LCCN: __('LCCN', 'wds'),
									"JP_E-CODE": __('JP_E-CODE', 'wds'),
								},
							},
						},
					},
					value: {
						id: id(),
						label: __('Value', 'wds'),
						type: 'Text',
						source: 'custom_text',
						value: '',
						description: __('The identifier value. The external ID that unambiguously identifies this edition. Remove all non-numeric prefixes of the external ID.', 'wds'),
						disallowDeletion: true,
					},
				},
			},
		},
	},
	author: {
		id: id(),
		label: __('Authors', 'wds'),
		labelSingle: __('Author', 'wds'),
		description: __('The author(s) of the edition. Only use this when the author of the edition is different from the work author information.', 'wds'),
		disallowDeletion: true,
		properties: {
			0: {
				id: id(),
				type: 'Person',
				disallowDeletion: true,
				disallowFirstItemDeletionOnly: true,
				properties: BookPerson,
			},
		},
	},
	sameAs: {
		id: id(),
		label: __('Same As', 'wds'),
		labelSingle: __('URL', 'wds'),
		description: __("The URL of a reference web page that unambiguously indicates the edition. For example, a Wikipedia page for this specific edition. Don't reuse the sameAs of the Work.", 'wds'),
		disallowDeletion: true,
		properties: {
			0: {
				id: id(),
				label: __('URL', 'wds'),
				type: 'URL',
				source: 'custom_text',
				value: '',
				disallowDeletion: true,
				disallowFirstItemDeletionOnly: true,
			},
		},
	},
};
export default BookEdition;
