import ClassicEditor from './classic-editor';
import GutenbergEditor from './gutenberg-editor';
import PostObjectsCache from './post-objects-cache';
import PostObjectFetcher from './post-object-fetcher';
import MacroReplacement from './macro-replacement';
import MetaboxOnpage from './metabox-onpage';
import MetaboxAnalysis from './metabox-analysis';
import Config_Values from './config-values';
import String_Utils from './string-utils';
import OptimumLengthIndicator from "./optimum-length-indicator";

(function () {
	let editor;
	if (Config_Values.get_bool('gutenberg_active', 'metabox')) {
		editor = new GutenbergEditor();
	} else {
		editor = new ClassicEditor();
	}
	let postObjectsCache = new PostObjectsCache();
	let postObjectFetcher = new PostObjectFetcher(postObjectsCache);
	let macroReplacement = new MacroReplacement(postObjectFetcher);

	let metaboxOnpage = false;
	if (Config_Values.get_bool('onpage_active', 'metabox')) {
		metaboxOnpage = new MetaboxOnpage(editor, macroReplacement);
	}

	let metaboxAnalysis = false;
	if (Config_Values.get_bool('analysis_active', 'metabox')) {
		metaboxAnalysis = new MetaboxAnalysis(editor, metaboxOnpage);
	}

	window.Wds = window.Wds || {};
	window.Wds.postEditor = editor;
	window.Wds.metaboxAnalysis = metaboxAnalysis;
	window.Wds.metaboxOnpage = metaboxOnpage;
	window.Wds.stringUtils = String_Utils;
	window.Wds.OptimumLengthIndicator = OptimumLengthIndicator;
})(jQuery);
