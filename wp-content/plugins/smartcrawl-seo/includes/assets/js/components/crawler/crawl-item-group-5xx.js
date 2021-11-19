import React from "react";
import CrawlItemGroup from "./crawl-item-group";
import {__} from "@wordpress/i18n";

export default class CrawlItemGroup5xx extends React.Component {
	render() {
		return <CrawlItemGroup
			{...this.props}
			singularTitle={__('%s URL is resulting in 5xx server error', 'wds')}
			pluralTitle={__('%s URLs are resulting in 5xx server errors', 'wds')}
			description={__('Some of your URLs are resulting in 5xx server errors. These errors are indicative of errors in your server-side code. In the options menu you can List occurrences to see where these links can be found, and also set up and 301 redirects to a newer version of these pages.', 'wds')}
		/>
	}
}
