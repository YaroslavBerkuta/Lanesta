export default class Term {
	constructor() {
	}

	set_id(id) {
		this.id = id;

		return this;
	}

	get_id() {
		return this.id;
	}

	set_title(title) {
		this.title = title;

		return this;
	}

	get_title() {
		return this.title;
	}

	set_slug(slug) {
		this.slug = slug;

		return this;
	}

	get_slug() {
		return this.slug;
	}

	set_description(description) {
		this.description = description;

		return this;
	}

	get_description() {
		return this.description;
	}

	set_taxonomy(taxonomy) {
		this.taxonomy = taxonomy;

		return this;
	}

	get_taxonomy() {
		return this.taxonomy;
	}

	set_permalink(permalink) {
		this.permalink = permalink;

		return this;
	}

	get_permalink() {
		return this.permalink;
	}
};
