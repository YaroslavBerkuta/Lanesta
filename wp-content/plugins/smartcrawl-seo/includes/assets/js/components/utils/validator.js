import $ from 'jQuery';

export default class Validator {
	static isNonEmpty(value) {
		return value && value.trim();
	}

	static isValuePlainText(value) {
		return $('<div>').html(value).text() === value;
	}

	
}
