import _ from '_';
import Wds from 'Wds';
import $ from 'jQuery';
import Config_Values from './config-values';
import String_Utils from './string-utils';
import {EventTarget} from "event-target-shim";

class MetaboxOnpageHelper {
	static get_title() {
		return $('#wds_title').val();
	}

	static get_description() {
		return $('#wds_metadesc').val();
	}

	static preview_loading(loading) {
		let $preview = this.get_preview_el().find('.wds-preview-container'),
			loading_class = 'wds-preview-loading';

		if (loading) {
			$preview.addClass(loading_class);
		} else {
			$preview.removeClass(loading_class);
		}
	}

	static get_preview_el() {
		return $('.wds-metabox-preview');
	}

	static replace_preview_markup(new_markup) {
		this.get_preview_el().replaceWith(new_markup)
	}

	static set_title_placeholder(placeholder) {
		$('#wds_title').prop('placeholder', placeholder);
	}

	static set_desc_placeholder(placeholder) {
		$('#wds_metadesc').prop('placeholder', placeholder);
	}
}

class MetaboxOnpage extends EventTarget {
	/**
	 * @param postEditor {ClassicEditor|GutenbergEditor}
	 * @param macroReplacement {MacroReplacement}
	 */
	constructor(postEditor, macroReplacement) {
		super();

		this.editor = postEditor;
		this.macroReplacement = macroReplacement;
		this.init();
	}

	init() {
		this.editor.addEventListener('autosave', (e) => this.handle_autosave_event(e));
		this.editor.addEventListener('content-change', (e) => this.handle_content_change_event(e));

		$(document)
			.on('input propertychange', '.wds-meta-field', _.debounce((e) => this.handle_meta_change(e), 200))
			.on('input propertychange', '.wds-meta-field', _.debounce((e) => this.dispatch_meta_change_deferred_event(e), 2000));

		$(window)
			.on('load', () => this.handle_page_load());
	}

	handle_autosave_event() {
		this.refresh_preview();
		this.refresh_placeholders();
	}

	handle_content_change_event() {
		this.refresh_preview();
		this.refresh_placeholders();
	}

	handle_meta_change() {
		this.dispatch_meta_change_event();
		this.refresh_preview();
	}

	handle_page_load() {
		this.refresh_preview();
		this.refresh_placeholders();
	}

	refresh_preview() {
		MetaboxOnpageHelper.preview_loading(true);

		let title = MetaboxOnpageHelper.get_title() || Config_Values.get('meta_title', 'metabox'),
			description = MetaboxOnpageHelper.get_description() || Config_Values.get('meta_desc', 'metabox'),
			post = this.editor.get_data(),
			promises = [];

		promises.push(this.macroReplacement.replace(title, post));
		promises.push(this.macroReplacement.replace(description, post));

		Promise.all(promises).then((values) => {
			MetaboxOnpageHelper.preview_loading(false);
			let template = Wds.tpl_compile(Wds.template('metabox', 'preview')),
				title_max_length = Config_Values.get('title_max_length', 'metabox'),
				metadesc_max_length = Config_Values.get('metadesc_max_length', 'metabox'),
				markup = template({
					link: post.get_permalink(),
					title: MetaboxOnpage.prepare_string(values[0], title_max_length),
					description: MetaboxOnpage.prepare_string(values[1], metadesc_max_length)
				});

			MetaboxOnpageHelper.replace_preview_markup(markup);
		}).catch((error) => {
			console.log(error);
		});
	}

	refresh_placeholders() {
		let post = this.editor.get_data();

		Promise.all([
			this.macroReplacement.replace(Config_Values.get('meta_title', 'metabox'), post),
			this.macroReplacement.replace(Config_Values.get('meta_desc', 'metabox'), post)
		]).then((values) => {
			MetaboxOnpageHelper.set_title_placeholder(MetaboxOnpage.prepare_string(values[0]));
			MetaboxOnpageHelper.set_desc_placeholder(MetaboxOnpage.prepare_string(values[1]));
		}).catch((error) => {
			console.log(error);
		});
	}

	static prepare_string(string, limit = false) {
		return String_Utils.process_string(string, limit);
	}

	dispatch_meta_change_event() {
		this.dispatchEvent(new Event('meta-change'));
	}

	dispatch_meta_change_deferred_event() {
		this.dispatchEvent(new Event('meta-change-deferred'));
	}

	post(action, data) {
		data = $.extend({
			action: action,
			_wds_nonce: Config_Values.get('nonce', 'metabox')
		}, data);

		return $.post(ajaxurl, data);
	}
}

export default MetaboxOnpage;
