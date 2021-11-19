import Config_Values from "../../es6/config-values";
import $ from 'jQuery';
import RequestUtil from "../utils/request-util";

export default class CrawlRequest {
	static redirect(source, destination) {
		return this.post('wds_redirect_crawl_item', {
			source: source,
			destination: destination
		});
	}

	static changeIssueStatus(issueId, action) {
		return this.post(action, {issue_id: issueId});
	}

	static ignoreIssue(issueId) {
		return this.changeIssueStatus(issueId, 'wds_ignore_crawl_item');
	}

	static restoreIssue(issueId) {
		return this.changeIssueStatus(issueId, 'wds_restore_crawl_item');
	}

	static restoreAll() {
		return this.post('wds_restore_all_crawl_items');
	}

	static addToSitemap(path) {
		return this.post('wds_sitemap_add_extra', {path: path});
	}

	static post(action, data) {
		const nonce = Config_Values.get('nonce', 'crawler');
		return RequestUtil.post(action, nonce, data);
	}
}
