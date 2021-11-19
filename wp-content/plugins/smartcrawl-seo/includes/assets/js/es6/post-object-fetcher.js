import $ from 'jQuery';
import wp from 'wp';

class PostObjectFetcher {
	/**
	 * @param postObjectsCache {PostObjectsCache}
	 */
	constructor(postObjectsCache) {
		this.cache = postObjectsCache;

		this.category_promise = null;
		this.tag_promise = null;
		this.author_promise = null;
		this.term_promise = {};
	}

	fetch_category_data(ids) {
		if (!this.category_promise) {
			this.category_promise = new Promise((resolve, reject) => {
				if (!ids || !ids.length) {
					resolve([]);
					return;
				}

				let categories = this.cache.get_categories(ids);
				if (categories.length) {
					resolve(categories);
					return;
				}

				let params = $.param({include: ids}),
					path = '/wp/v2/categories?' + params;

				wp.apiFetch({path: path})
					.then((data) => {
						resolve(data);
					})
					.catch(reject);
			});

			this.category_promise.then(() => {
				this.category_promise = null;
			});
		}

		return this.category_promise;
	}

	fetch_tag_data(ids) {
		if (!this.tag_promise) {
			this.tag_promise = new Promise((resolve, reject) => {
				if (!ids || !ids.length) {
					resolve([]);
					return;
				}

				let args = {};
				if (this.ids_numeric(ids)) {
					args.include = ids;
				} else if (this.ids_slugs(ids)) {
					args.slug = ids;
				} else {
					reject('IDs malformed');
					return;
				}

				let tags = this.cache.get_tags(ids);
				if (tags.length) {
					resolve(tags);
					return;
				}

				let path = '/wp/v2/tags?' + $.param(args);
				wp.apiFetch({path: path})
					.then((data) => {
						resolve(data);
					})
					.catch(reject);
			});

			this.tag_promise.then(() => {
				this.tag_promise = null;
			});
		}

		return this.tag_promise;
	}

	fetch_taxonomy_terms(taxonomy, ids) {
		if (!this.term_promise.hasOwnProperty(taxonomy)) {
			this.term_promise[taxonomy] = new Promise((resolve, reject) => {
				if (!ids || !ids.length) {
					resolve([]);
					return;
				}

				let terms = this.cache.get_taxonomy_terms(taxonomy, ids);
				if (terms.length) {
					resolve(terms);
					return;
				}

				let params = $.param({include: ids}),
					path = '/wp/v2/' + taxonomy + '?' + params;

				wp.apiFetch({path: path})
					.then((data) => {
						resolve(data);
					})
					.catch(reject);
			});

			this.term_promise[taxonomy].then(() => {
				delete this.term_promise[taxonomy];
			});
		}

		return this.term_promise[taxonomy];
	}

	fetch_author_data(id) {
		if (!this.author_promise) {
			this.author_promise = new Promise((resolve, reject) => {
				if (!id) {
					resolve(false);
					return;
				}

				let author = this.cache.get_author(id);
				if (author) {
					resolve(author);
					return;
				}

				let path = '/wp/v2/users/' + id;
				wp.apiFetch({path: path})
					.then((data) => {
						resolve(data);
					})
					.catch(reject);
			});

			this.author_promise.then(() => {
				this.author_promise = null;
			});
		}
		return this.author_promise;
	}

	ids_numeric(ids) {
		if (!Array.isArray(ids)) {
			return false;
		}

		return ids.reduce((all_numeric, id) => {
			let is_numeric = !isNaN(id);

			return all_numeric && is_numeric;
		}, true);
	}

	ids_slugs(ids) {
		if (!Array.isArray(ids)) {
			return false;
		}

		return ids.reduce((all_slugs, id) => {
			let is_slug = isNaN(id);

			return all_slugs && is_slug;
		}, true);
	}
}

export default PostObjectFetcher;
