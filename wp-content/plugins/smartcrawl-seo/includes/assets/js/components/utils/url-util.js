export default class UrlUtil {
	static getQueryParam(name) {
		const searchParams = location.search;
		const params = new URLSearchParams(searchParams);
		return params.get(name);
	}

	static removeQueryParam(name) {
		const searchParams = location.search;
		const params = new URLSearchParams(searchParams);
		if (!params.get(name)) {
			return;
		}

		params.delete(name);
		const newURL = location.href.replace(searchParams, '?' + params.toString());

		history.replaceState({}, "", newURL);
	}
}