import $ from 'jQuery';
import String_Utils from "./string-utils";
import Config_Values from "./config-values";

export default class OptimumLengthIndicator {
	/**
	 * @param $field jQuery element
	 * @param macroReplacementFunction
	 * @param options
	 */
	constructor($field, macroReplacementFunction, options) {
		if (!this.is_field_valid($field)) {
			return;
		}

		this.$el = $field
		this.macroReplacementFunction = macroReplacementFunction;

		this.upper = options.upper || 0;
		this.lower = options.lower || 0;
		this.default_value = options.default_value || '';
		let offset = 8 / 100 * this.upper;
		this.almost_lower = this.lower + offset;
		this.almost_upper = this.upper - offset;

		this.init_element($field);
		this.init_listeners();
		this.update_indicator();
	}

	init_listeners() {
		this.$el
			.on('input propertychange', _.debounce(() => this.update_indicator(), 200))
			.one('input propertychange', () => this.add_resolving_class());
	}

	is_field_valid($element) {
		return !$element.is(this.field_selector()) // Already initialized
			&& $element.is('input,textarea');
	}

	init_element($element) {
		$element.addClass(this.field_class());

		$('<div><span></span><span></span></div>')
			.addClass(this.indicator_class())
			.insertAfter($element);

		return $element;
	}

	get_indicator() {
		return this.$el.next(this.indicator_selector());
	}

	get_bar() {
		return this.get_indicator().find('span').first();
	}

	get_char_count_container() {
		return this.get_indicator().find('span').last();
	}

	reset_classes() {
		this.$el.removeClass('over almost-over just-right almost-under under');
	}

	remove_resolving_class() {
		this.$el.removeClass('resolving');
	}

	add_resolving_class() {
		this.$el.addClass('resolving');
	}

	update_indicator() {
		let value = this.$el.val();

		value = !!value ? value : this.default_value;
		if (!value) {
			this.get_char_count_container().hide();
			this.reset_classes();
			this.remove_resolving_class();
			return;
		}

		this.macroReplacementFunction(value).then((final_value) => {
			final_value = String_Utils.strip_html(final_value);
			final_value = String_Utils.normalize_whitespace(final_value);

			let length = Array.from(final_value).length,
				ideal_length = (this.upper + this.lower) / 2,
				percentage = length / ideal_length * 100;

			this.remove_resolving_class();
			this.$el.one('input propertychange', () => this.add_resolving_class());

			// When the length is equal to mean, the progress bar should be in the center instead of the end. Therefore:
			percentage = percentage / 2;
			percentage = percentage > 100 ? 100 : percentage;

			// Update width
			this.get_bar().width(percentage + '%');

			// Update count
			let count = this.get_char_count_container();
			count.html(length + ' / ' + this.lower + '-' + this.upper + ' ' + Config_Values.get(['strings', 'characters'], 'admin'));
			if (!!length) {
				count.show();
			} else {
				count.hide();
			}

			this.reset_classes();

			if (length > this.upper) {
				this.$el.addClass('over');
			} else if (this.almost_upper < length && length <= this.upper) {
				this.$el.addClass('almost-over');
			} else if (this.almost_lower <= length && length <= this.almost_upper) {
				this.$el.addClass('just-right');
			} else if (this.lower <= length && length < this.almost_lower) {
				this.$el.addClass('almost-under');
			} else if (this.lower > length) {
				this.$el.addClass('under');
			}
		});
	}

	field_class() {
		return 'wds-optimum-length-indicator-field';
	}

	field_selector() {
		return '.' + this.field_class();
	}

	indicator_class() {
		return 'wds-optimum-length-indicator';
	}

	indicator_selector() {
		return '.' + this.indicator_class();
	}
};
