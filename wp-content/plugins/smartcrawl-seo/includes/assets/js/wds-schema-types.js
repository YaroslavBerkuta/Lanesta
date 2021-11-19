import React from 'react';
import {render} from 'react-dom';
import SchemaBuilder from "./components/schema/schema-builder";
import ErrorBoundary from "./components/error-boundry";
import $ from 'jQuery';

$(() => {
	if (!$('#wds-schema-type-components').length) {
		return;
	}

	render(
		<ErrorBoundary><SchemaBuilder/></ErrorBoundary>,
		document.getElementById('wds-schema-type-components')
	);
});
