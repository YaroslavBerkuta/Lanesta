import Post from './post';
import wp from 'wp';
import _ from '_';
import $ from 'jQuery';
import Config_Values from './config-values';
import {EventTarget} from "event-target-shim";

class ClassicEditor extends EventTarget {
	constructor() {
		super();

		this.init();
	}

	init() {
		$(document)
			.on('input', 'input#title,textarea#content,textarea#excerpt', this.get_debounced_change_callback())
			.on('after-autosave.smartcrawl', () => this.dispatch_autosave_event());

		$(window)
			.on('load', () => this.hook_tinymce_change_listener());
	}

	/**
	 * @returns {Post}
	 */
	get_data() {
		let data = wp.autosave.getPostData(),
			post = new Post(),
			tags = $('#tax-input-post_tag').val();

		return post.set_id(data.post_id)
			.set_type(data.post_type)
			.set_author_id(data.post_author)
			.set_title(data.post_title)
			.set_content(data.content)
			.set_excerpt(data.excerpt)
			.set_category_ids(data.catslist.split(','))
			.set_tag_ids(tags ? tags.split(',') : [])
			.set_slug(data.post_name)
			.set_date(new Date())
			.set_modified(new Date())
			.set_permalink(Config_Values.get('post_url', 'metabox'));
	}

	dispatch_content_change_event() {
		this.dispatchEvent(new Event('content-change'));
	}

	hook_tinymce_change_listener() {
		let editor = typeof tinymce !== 'undefined' && tinymce.get('content');
		if (editor) {
			editor.on('change', this.get_debounced_change_callback());
		}
	}

	get_debounced_change_callback() {
		return _.debounce(() => this.dispatch_content_change_event(), 2000);
	}

	dispatch_autosave_event() {
		this.dispatchEvent(new Event('autosave'));
	}

	/**
	 * When the classic editor is active and we trigger an autosave programmatically,
	 * the heartbeat API is used for the autosave.
	 *
	 * To provide a seamless experience, this method temporarily removes our usual handler
	 * and hooks a handler to the heartbeat event.
	 */
	autosave() {
		let handle_heartbeat = () => {
			this.dispatch_autosave_event();

			// Re-hook our regular autosave handler
			$(document).on('after-autosave.smartcrawl', () => this.dispatch_autosave_event());
		};

		// We are already hooked to autosave so let's disable our regular autosave handler momentarily to avoid multiple calls ...
		$(document).off('after-autosave.smartcrawl');
		// hook a new handler to heartbeat-tick.autosave
		$(document).one('heartbeat-tick.autosave', handle_heartbeat);

		// Save any changes pending in the editor to the textarea
		this.trigger_tinymce_save();
		// Actually trigger the autosave
		wp.autosave.server.triggerSave();
	}

	trigger_tinymce_save() {
		let editorSync = (tinyMCE || {}).triggerSave;
		if (editorSync) {
			editorSync();
		}
	}

	is_post_dirty() {
		return wp.autosave.server.postChanged();
	}
}

export default ClassicEditor;
