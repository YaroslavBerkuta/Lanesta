import PostObjectsCache from "./post-objects-cache";
import PostObjectFetcher from "./post-object-fetcher";
import MacroReplacement from "./macro-replacement";

(function () {
	let postObjectsCache = new PostObjectsCache();
	let postObjectFetcher = new PostObjectFetcher(postObjectsCache);
	window.Wds.macroReplacement = new MacroReplacement(postObjectFetcher);
})(jQuery);
