import React from "react";
import CrawlItemGroup from "./crawl-item-group";
import {__} from "@wordpress/i18n";

export default class CrawlItemGroup4xx extends React.Component {
	render() {
		return <CrawlItemGroup
			{...this.props}
			singularTitle={__('%s URL is resulting in 4xx error', 'wds')}
			pluralTitle={__('%s URLs are resulting in 4xx errors', 'wds')}
			description={__('Some of your URLs are resulting in 4xx errors. Either the page no longer exists or the URL has changed. In the options menu you can List occurrences to see where these links can be found, and also set up and 301 redirects to a newer version of these pages.', 'wds')}
		/>
	}
}
