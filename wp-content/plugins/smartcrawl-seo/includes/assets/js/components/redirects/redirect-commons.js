import Config_Values from "../../es6/config-values";

export function get_default_redirect_type() {
	return Config_Values.get('default_redirect_type', 'autolinks');
}
