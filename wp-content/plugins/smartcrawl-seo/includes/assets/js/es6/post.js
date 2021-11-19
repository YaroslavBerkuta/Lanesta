class Post {
	constructor() {
		this.taxonomies = {};
	}

	set_id(id) {
		this.id = id;

		return this;
	}

	get_id() {
		return this.id;
	}

	set_type(type) {
		this.type = type;

		return this;
	}

	get_type() {
		return this.type;
	}

	set_author_id(author_id) {
		this.author_id = author_id;

		return this;
	}

	get_author_id() {
		return this.author_id;
	}

	set_title(title) {
		this.title = title;

		return this;
	}

	get_title() {
		return this.title;
	}

	set_content(content) {
		this.content = content;

		return this;
	}

	get_content() {
		return this.content;
	}

	set_excerpt(excerpt) {
		this.excerpt = excerpt;

		return this;
	}

	get_excerpt() {
		return this.excerpt;
	}

	set_category_ids(category_ids) {
		this.set_taxonomy_terms('category', category_ids);

		return this;
	}

	get_category_ids() {
		return this.get_taxonomy_terms('category');
	}

	set_tag_ids(ids) {
		this.set_taxonomy_terms('post_tag', ids);

		return this;
	}

	get_tag_ids() {
		return this.get_taxonomy_terms('post_tag');
	}

	set_slug(slug) {
		this.slug = slug;

		return this;
	}

	get_slug() {
		return this.slug;
	}

	/**
	 * @param date {Date}
	 */
	set_date(date) {
		this.date = date;

		return this;
	}

	get_date() {
		return this.date;
	}

	/**
	 * @param modified {Date}
	 */
	set_modified(modified) {
		this.modified = modified;

		return this;
	}

	get_modified() {
		return this.modified;
	}

	set_permalink(permalink) {
		this.permalink = permalink;

		return this;
	}

	get_permalink() {
		return this.permalink;
	}

	set_taxonomy_terms(taxonomy, terms) {
		this.taxonomies[taxonomy] = terms;
	}

	get_taxonomy_terms(taxonomy) {
		return this.taxonomies.hasOwnProperty(taxonomy)
			? this.taxonomies[taxonomy]
			: [];
	}
}

export default Post;
