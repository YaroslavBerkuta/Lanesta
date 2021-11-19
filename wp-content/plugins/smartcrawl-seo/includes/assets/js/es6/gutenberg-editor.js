import Post from './post';
import wp from 'wp';
import _ from '_';
import $ from 'jQuery';
import Config_Values from './config-values';
import {EventTarget} from "event-target-shim";

class GutenbergEditor extends EventTarget {
	constructor() {
		super();

		this.init();
	}

	init() {
		this.hook_change_listener();
		this.register_api_fetch_middleware();
	}

	/**
	 * @returns {Post}
	 */
	get_data() {
		let post = new Post(),
			editor = this.get_editor();

		let postId = editor.getEditedPostAttribute('id');
		if (!postId) {
			postId = $('#post_ID').val() || 0;
		}

		post = post.set_id(postId)
			.set_type(editor.getEditedPostAttribute('type'))
			.set_author_id(editor.getEditedPostAttribute('author'))
			.set_title(editor.getEditedPostAttribute('title'))
			.set_content(editor.getEditedPostAttribute('content'))
			.set_excerpt(editor.getEditedPostAttribute('excerpt'))
			.set_slug(editor.getEditedPostAttribute('slug'))
			.set_date(new Date(editor.getEditedPostAttribute('date')))
			.set_modified(new Date(editor.getEditedPostAttribute('modified')))
			.set_permalink(editor.getPermalink());

		if (this.get_post_type() === 'post') {
			post.set_category_ids(editor.getEditedPostAttribute('categories'))
				.set_tag_ids(editor.getEditedPostAttribute('tags'));
		} else {
			let taxonomies = this.get_taxonomies();
			taxonomies.forEach((taxonomy) => {
				let taxonomy_terms = editor.getEditedPostAttribute(taxonomy);

				if (taxonomy_terms) {
					post.set_taxonomy_terms(taxonomy, taxonomy_terms);
				}
			});
		}

		return post;
	}

	get_post_type() {
		return Config_Values.get('post_type', 'metabox');
	}

	get_taxonomies() {
		return Config_Values.get('taxonomies', 'metabox');
	}

	get_editor() {
		return wp.data.select("core/editor");
	}

	dispatch_content_change_event() {
		this.dispatchEvent(new Event('content-change'));
	}

	dispatch_editor() {
		return wp.data.dispatch("core/editor");
	}

	hook_change_listener() {
		let debounced = _.debounce(() => this.dispatch_content_change_event(), 2000);

		wp.data.subscribe(() => {
			if (
				this.is_post_loaded()
				&& this.get_editor().isEditedPostDirty()
				&& !this.get_editor().isAutosavingPost()
				&& !this.get_editor().isSavingPost()
			) {
				debounced();
			}
		});
	}

	register_api_fetch_middleware() {
		wp.apiFetch.use((options, next) => {
			let result = next(options);
			result.then(() => {
				if (this.is_autosave_request(options) || this.is_post_save_request(options)) {
					this.dispatch_autosave_event();
				}
			}).catch(() => {
				this.dispatch_autosave_event();
			});

			return result;
		});
	}

	dispatch_autosave_event() {
		this.dispatchEvent(new Event('autosave'));
	}

	is_autosave_request(request) {
		return request && request.path
			&& request.path.includes('/autosaves');
	}

	is_post_save_request(request) {
		let post = this.get_data(),
			post_id = post.get_id(),
			post_type = post.get_type();

		return request && request.path
			&& request.method === 'PUT'
			&& request.path.includes('/' + post_id)
			&& request.path.includes('/' + post_type);
	}

	autosave() {
		// TODO: Keep track of this error: https://github.com/WordPress/gutenberg/issues/7416
		if (this.get_editor().isEditedPostAutosaveable()) {
			this.dispatch_editor().autosave();
		} else {
			this.dispatch_autosave_event();
		}
	}

	is_post_loaded() {
		return this.get_editor().getCurrentPostId && this.get_editor().getCurrentPostId();
	}

	is_post_dirty() {
		return this.is_post_loaded() && this.get_editor().isEditedPostDirty();
	}
}

export default GutenbergEditor;
