import Post from "./post";
import Config_Values from "./config-values";
import OptimumLengthIndicator from "./optimum-length-indicator";
import Term from "./term";
import String_Utils from "./string-utils";

(function () {
	window.Wds.randomPosts = {};
	window.Wds.randomTerms = {};

	let random_posts = Config_Values.get('random_posts', 'onpage_components');
	Object.keys(random_posts).forEach((post_type) => {
		let post_data = random_posts[post_type];
		let post = new Post();

		post
			.set_id(post_data.ID)
			.set_type(post_data.post_type)
			.set_author_id(post_data.post_author)
			.set_title(post_data.post_title)
			.set_content(post_data.post_content)
			.set_excerpt(post_data.post_excerpt)
			.set_slug(post_data.post_name)
			.set_date(new Date(post_data.post_date))
			.set_modified(new Date(post_data.post_modified))
			.set_permalink(post_data.permalink)
		;

		if (post_data.taxonomy_terms) {
			Object.keys(post_data.taxonomy_terms).forEach((taxonomy) => {

				post.set_taxonomy_terms(taxonomy, post_data.taxonomy_terms[taxonomy]);
			});
		}

		window.Wds.randomPosts[post_type] = post;
	});

	let random_terms = Config_Values.get('random_terms', 'onpage_components');
	Object.keys(random_terms).forEach((taxonomy) => {
		let term_data = random_terms[taxonomy];
		let term = new Term();
		term
			.set_id(term_data.term_id)
			.set_title(term_data.name)
			.set_slug(term_data.slug)
			.set_description(term_data.description)
			.set_permalink(term_data.permalink)
			.set_taxonomy(taxonomy);

		window.Wds.randomTerms[taxonomy] = term;
	});

	window.Wds.OptimumLengthIndicator = OptimumLengthIndicator;
	window.Wds.String_Utils = String_Utils;
})(jQuery);
