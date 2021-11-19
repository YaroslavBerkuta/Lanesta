import React from "react";
import {__} from "@wordpress/i18n";

export default class AccordionItemOpenIndicator extends React.Component {
	render() {
		return <button type="button" className="sui-button-icon sui-accordion-open-indicator">
			<span aria-hidden="true" className="sui-icon-chevron-down"/>
			<span className="sui-screen-reader-text">
				{__('Expand item', 'wds')}
			</span>
		</button>;
	}
}
