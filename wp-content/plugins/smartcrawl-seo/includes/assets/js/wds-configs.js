import React from 'react';
import {render} from 'react-dom';
import ErrorBoundary from "./components/error-boundry";
import domReady from '@wordpress/dom-ready';
import ConfigsTabWrapper from "./components/configs/configs-tab-wrapper";
import ConfigsWidgetWrapper from "./components/configs/configs-widget-wrapper";

domReady(() => {
	const settingsPageConfigs = document.getElementById('wds-config-components');
	if (settingsPageConfigs) {
		render(<ErrorBoundary><ConfigsTabWrapper/></ErrorBoundary>, settingsPageConfigs);
	}

	const dashboardWidget = document.getElementById('wds-config-widget');
	if (dashboardWidget) {
		render(<ErrorBoundary><ConfigsWidgetWrapper/></ErrorBoundary>, dashboardWidget);
	}
});
