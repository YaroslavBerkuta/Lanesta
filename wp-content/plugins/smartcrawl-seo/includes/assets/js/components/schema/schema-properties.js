import React from 'react';
import SchemaProperty from "./schema-property";

export default class SchemaProperties extends React.Component {
	static defaultProps = {
		properties: {},
		methods: {},
	};

	render() {
		return <React.Fragment>
			{Object.keys(this.props.properties).map(
				propertyKey => {
					const property = this.props.properties[propertyKey];
					return <SchemaProperty {...property}
										   key={'schema-property-' + property.id}
										   methods={this.props.methods}
					/>;
				}
			)}
		</React.Fragment>;
	}
}
