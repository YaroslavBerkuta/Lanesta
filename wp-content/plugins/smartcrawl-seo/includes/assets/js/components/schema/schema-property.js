import React from 'react';
import SchemaPropertyRepeatable from "./schema-property-repeatable";
import SchemaProperties from "./schema-properties";
import SchemaPropertyNested from "./schema-property-nested";
import SchemaPropertySimple from "./schema-property-simple";

export default class SchemaProperty extends React.Component {
	static defaultProps = {
		id: '',									// Unique identifier of the property
		label: '',								// Human readable label
		description: '',						// Human readable description
		type: '',								// Data type of the value. Decides what will be shown under source and value
		source: '',								// Data source e.g. 'Post' or 'Author'
		value: '',								// The value to use from the data source e.g. 'Post Title' or 'Author Name'
		required: false,						// Whether or not the current property is required by Google
		labelSingle: '',						// Label of a single block in a repeatable
		disallowDeletion: false,				// Whether or not to show a delete button
		disallowAddition: false,				// Whether or not to show a button for adding nested properties
		customSources: {},						// Custom source options for this property only
		placeholder: '',						// Text input placeholder
		disallowFirstItemDeletionOnly: false,	// If true a delete button is not shown for the first item but is shown on all subsequent items
		loop: false,							// Identifier of the loop
		loopDescription: '',					// Description of the loop
		requiredNotice: '',						// Shown as a tooltip of '*'
		requiredInBlock: false,					// Deprecated.
		allowMultipleSelection: false,			// Allow selection of multiple values in a select field
		isAnAltVersion: false,					// Indicates whether this property is an alternate version of some other property
		activeVersion: false,					// Which of the available alternate versions is currently active
		flatten: false, 						// Shows child properties without nesting
		properties: {},							// Nested properties/available alt versions
		methods: {},							// Listeners and methods
	};

	render() {
		if (this.hasAltVersions()) {
			return <SchemaProperty {...this.getActiveVersion()} methods={this.props.methods}/>
		} else if (this.isFlattened()) {
			return <SchemaProperties properties={this.props.properties} methods={this.props.methods}/>
		} else if (this.isRepeatable()) {
			return <SchemaPropertyRepeatable {...this.props}/>
		} else if (this.isNested()) {
			return <SchemaPropertyNested {...this.props}/>
		} else {
			return <SchemaPropertySimple {...this.props}/>
		}
	}

	hasAltVersions() {
		return !!this.getActiveVersion();
	}

	getActiveVersion() {
		const activeVersion = this.props.activeVersion;
		if (
			!this.isNested() ||
			!activeVersion ||
			!this.props.properties.hasOwnProperty(activeVersion)
		) {
			return false;
		}

		return this.props.properties[activeVersion];
	}

	isNested() {
		return Object.keys(this.props.properties).length;
	}

	isRepeatable() {
		if (!this.isNested()) {
			return false;
		}

		const nonNumericKeys = Object.keys(this.props.properties).filter(key => isNaN(key));
		return nonNumericKeys.length === 0;
	}

	isFlattened() {
		return this.isNested() && this.props.flatten;
	}
}
