class Config_Values {
	static get(keys, scope = 'general') {
		if (!Array.isArray(keys)) {
			keys = [keys];
		}

		let value = window['_wds_' + scope] || {};
		keys.forEach((key) => {
			if (value && value.hasOwnProperty(key)) {
				value = value[key];
			} else {
				value = '';
			}
		});

		return value;
	}

	static get_bool(varname, scope = 'general') {
		return !!this.get(varname, scope);
	}
}

export default Config_Values;
