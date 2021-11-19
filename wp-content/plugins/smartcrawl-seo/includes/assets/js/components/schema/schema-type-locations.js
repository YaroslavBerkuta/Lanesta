import React from "react";
import Config_Values from "../../es6/config-values";
import $ from 'jQuery';
import {isEqual} from "lodash-es";

export default class SchemaTypeLocations extends React.Component {
	constructor(props) {
		super(props);

		this.state = {
			fullText: '',
			summaryText: ''
		};
	}

	componentDidMount() {
		this.loadLocationFromRemote();
	}

	componentDidUpdate(prevProps) {
		if (!isEqual(this.props.conditions, prevProps.conditions)) {
			this.loadLocationFromRemote();
		}
	}

	loadLocationFromRemote() {
		this.setState({
			fullText: '...',
			summaryText: '...'
		}, () => {
			let ajaxURL = Config_Values.get('ajax_url', 'schema_types');
			$.get(ajaxURL + '?action=wds-format-schema-location', {
				'conditions': this.props.conditions
			}).done((data) => {
				this.setState({
					fullText: data.full,
					summaryText: data.summary
				});
			});
		});
	}

	render() {
		return <span className="wds-schema-type-locations sui-tooltip sui-tooltip-constrained"
					 style={{"--tooltip-width": "170px"}}
					 data-tooltip={this.state.fullText}>
			<span className="sui-icon-link" aria-hidden="true"/>
			{this.state.summaryText}
		</span>
	}
};
