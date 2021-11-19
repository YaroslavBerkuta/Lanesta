import React from "react";
import {__} from "@wordpress/i18n";
import CrawlItemGroup from "./crawl-item-group";

export default class CrawlItemGroupSitemap extends React.Component {
	render() {
		return <CrawlItemGroup
			{...this.props}
			singularTitle={__('%s URL is missing from the sitemap', 'wds')}
			pluralTitle={__('%s URLs are missing from the sitemap', 'wds')}
			description={__('SmartCrawl couldn’t find these URLs in your Sitemap. You can choose to add them to your Sitemap, or ignore the warning if you don’t want them included.', 'wds')}
			warningClass="sui-default"
		/>;
	}
}
