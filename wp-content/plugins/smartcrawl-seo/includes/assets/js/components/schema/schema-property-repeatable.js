import React from 'react';
import classnames from "classnames";
import Button from "../button";
import SchemaPropertyAccordionHeader from "./schema-property-accordion-header";
import SchemaProperties from "./schema-properties";
import SchemaPropertySimple from "./schema-property-simple";

export default class SchemaPropertyRepeatable extends React.Component {
	static defaultProps = {
		id: '',
		label: '',
		labelSingle: '',
		properties: {},
		methods: {},
	};

	render() {
		const repeatables = this.props.properties;

		return <tr key={'repeating-property-row-' + this.props.id}>
			<td colSpan={4} className="wds-schema-repeating-properties">
				{this.props.methods.beforePropertyRender(this.props.id)}

				<div className="sui-accordion">
					<div
						className={classnames('sui-accordion-item', 'wds-schema-property-' + this.props.id + '-accordion')}>
						<SchemaPropertyAccordionHeader {...this.props} isRepeatable={true}/>

						<div className="sui-accordion-item-body">
							{Object.keys(repeatables).map(propertyKey => {
									const repeatable = repeatables[propertyKey];
									return <table className="sui-table" key={'repeatable-' + repeatable.id}>
										{repeatable.properties &&
										<thead>
										<tr>
											<td colSpan={2} className="sui-table-item-title">
												{this.props.labelSingle || this.props.label}
											</td>
											<td>
												{!repeatable.disallowDeletion &&
												<Button text=""
														ghost={true}
														icon="sui-icon-trash"
														color="red"
														onClick={() => this.props.methods.onDelete(repeatable.id)}
												/>
												}
											</td>
										</tr>
										</thead>
										}

										<tbody>
										{repeatable.properties &&
										<SchemaProperties properties={repeatable.properties} methods={this.props.methods}/>
										}

										{!repeatable.properties &&
										<SchemaPropertySimple {...repeatable} methods={this.props.methods}/>
										}
										</tbody>
									</table>;
								}
							)}
						</div>
					</div>
				</div>
			</td>
		</tr>;
	}
}
