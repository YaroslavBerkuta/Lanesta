import wp from 'wp';
import Config_Values from './config-values';

class PostObjectsCache {
	constructor() {
		this.authors = {};
		this.taxonomy_terms = {};

		this.register_api_fetch_middleware();
	}

	key(prefix, id) {
		id = '' + id;
		return prefix + id.trim();
	}

	add_categories(cats) {
		this.add_taxonomy_terms('category', cats);
	}

	get_categories(ids) {
		return this.get_taxonomy_terms('category', ids);
	}

	add_tags(tags) {
		this.add_taxonomy_terms('post_tag', tags);
		this.add_taxonomy_terms('post_tag', tags, 'name');
	}

	get_tags(ids) {
		return this.get_taxonomy_terms('post_tag', ids);
	}

	author_key(id) {
		return this.key('author-', id);
	}

	add_authors(authors) {
		if (Array.isArray(authors)) {
			authors.forEach((author) => {
				this.add_author(author);
			});
		} else if (!!authors.id) {
			this.add_author(authors);
		}
	}

	add_author(author) {
		let key = this.author_key(author.id);
		this.authors[key] = author;
	}

	get_author(id) {
		let key = this.author_key(id);
		if (this.authors.hasOwnProperty(key)) {
			return this.authors[key];
		}

		return false;
	}

	get_query_var(url, variable) {
		let query = url.substring(1);
		let vars = query.split(/[&?]/);
		for (let i = 0; i < vars.length; i++) {
			let pair = vars[i].split('=');
			if (decodeURIComponent(pair[0]) === variable) {
				return decodeURIComponent(pair[1]);
			}
		}

		return false;
	}

	register_api_fetch_middleware() {
		wp.apiFetch.use((options, next) => {
			if (this.is_term_request(options)) {
				let fields = this.get_query_var(options.path, '_fields');
				if (fields) {
					options.path = options.path.replace(
						encodeURIComponent(fields),
						encodeURIComponent(fields + ',description')
					);
				}
			}
			let result = next(options);
			result.then((values) => {
				if (this.is_category_request(options)) {
					this.add_categories(values);
				}

				if (this.is_tag_request(options)) {
					this.add_tags(values);
				}

				if (this.is_get_request(options, '/wp/v2/users/')) {
					this.add_authors(values);
				}

				let taxonomy = this.get_taxonomy_slug(options);
				if (taxonomy) {
					this.add_taxonomy_terms(taxonomy, values);
				}
			}).catch((error) => {
				console.log(error);
			});

			return result;
		});
	}

	get_taxonomy_slug(request) {
		if (
			(request.method && request.method !== 'GET')
			|| !request.path
		) {
			return false;
		}

		let taxonomies = this.get_taxonomies(),
			matches = request.path.match(/\/wp\/v2\/([a-z_-]*).*/);

		if (!matches || matches.length < 2 || !matches[1]) {
			return false;
		}

		return taxonomies.includes(matches[1])
			? matches[1]
			: false;
	}

	is_term_request(request) {
		return this.is_category_request(request)
			|| this.is_tag_request(request)
			|| !!this.get_taxonomy_slug(request);
	}

	is_category_request(request) {
		return this.is_get_request(request, '/wp/v2/categories');
	}

	is_tag_request(request) {
		return this.is_get_request(request, '/wp/v2/tags');
	}

	is_get_request(request, keyword) {
		return request
			&& (!request.method || request.method === 'GET')
			&& request.path
			&& request.path.includes(keyword);
	}

	taxonomy_key(taxonomy, id) {
		return this.key('taxonomy-' + taxonomy + '-term-', id);
	}

	get_taxonomy_terms(taxonomy, terms_ids) {
		let terms = [];
		terms_ids.forEach((id) => {
			let key = this.taxonomy_key(taxonomy, id);
			if (this.taxonomy_terms.hasOwnProperty(key)) {
				terms.push(this.taxonomy_terms[key]);
			}
		});

		if (terms.length !== terms_ids.length) {
			return [];
		}

		return terms;
	}

	add_taxonomy_terms(taxonomy, terms, field = 'id') {
		if (Array.isArray(terms)) {
			terms.forEach((term) => {
				this.add_taxonomy_term(taxonomy, term, field);
			});
		} else if (!!terms.id) {
			this.add_taxonomy_term(taxonomy, terms, field);
		}
	}

	add_taxonomy_term(taxonomy, term, field = 'id') {
		let key = this.taxonomy_key(taxonomy, term[field]);

		this.taxonomy_terms[key] = term;
	}

	get_taxonomies() {
		return Config_Values.get('taxonomies', 'replacement');
	}
}

export default PostObjectsCache;
