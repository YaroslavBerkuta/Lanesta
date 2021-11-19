import Config_Values from './config-values';

class String_Utils {
	static truncate_string(string, limit) {
		if (string.length > limit) {
			let stringArray = Array.from(string);
			string = stringArray.splice(0, limit - 4).join('').trim() + ' ...';
		}

		return string;
	}

	static normalize_whitespace(string) {
		// Replace whitespace characters with simple spaces
		string = string.replace(/(\r\n|\n|\r|\t)/gm, " ");
		// Replace each set of multiple consecutive spaces with a single space
		string = string.replace(/[ ]+/g, " ");

		return string.trim();
	}

	static remove_shortcodes(string) {
		string = string || '';
		if (string.indexOf('[') === -1) {
			return string;
		}

		// Modified version of regex from PHP function get_shortcode_regex()
		let regex = /\[(\[?)([a-zA-Z0-9_-]*)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*(?:\[(?!\/\2\])[^\[]*)*)\[\/\2\])?)(\]?)/g,
			self = this;

		return string.replace(regex, function (match, start_bracket, shortcode, attributes, match_4, content, end_bracket, offset, string) {
			if (arguments.length < 7) {
				// Not the expected regex for some reason. Try returning the full match or fall back to empty string.
				return match || '';
			}

			// Allow [[foo]] syntax for escaping a tag.
			if ('[' === start_bracket && ']' === end_bracket) {
				// Return the whole matched string without the surrounding square brackets that were there for escaping
				return match.substring(1, match.length - 1);
			}

			let omitted = Config_Values.get('omitted_shortcodes', 'replacement');
			if (!!content && !omitted.includes(shortcode)) {
				// Call the removal method on the content nested in the current shortcode
				// This will continue recursively until we have removed all shortcodes
				return self.remove_shortcodes(content.trim());
			}

			// Just remove the content-less, non-escaped shortcodes
			return '';
		});
	}

	static strip_html(html) {
		let div = document.createElement("DIV");
		div.innerHTML = html;
		return div.textContent || div.innerText || "";
	}

	static process_string(string, limit = false) {
		string = String_Utils.strip_html(string);
		string = String_Utils.normalize_whitespace(string);
		if (limit) {
			string = String_Utils.truncate_string(string, limit);
		}

		return string;
	}
}

export default String_Utils;
