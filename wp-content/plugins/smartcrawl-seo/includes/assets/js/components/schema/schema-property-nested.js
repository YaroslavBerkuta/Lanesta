import React from 'react';
import classnames from "classnames";
import Button from "../button";
import SchemaProperties from "./schema-properties";
import SchemaPropertyAccordionHeader from "./schema-property-accordion-header";
import {__} from "@wordpress/i18n";

export default class SchemaPropertyNested extends React.Component {
	static defaultProps = {
		id: '',
		loop: false,
		loopDescription: '',
		disallowAddition: false,
		properties: {},
		methods: {},
	};

	render() {
		const className = classnames('sui-accordion-item', 'wds-schema-property-' + this.props.id + '-accordion');

		return <tr key={'nested-property-row-' + this.props.id}>
			<td colSpan={4} className="wds-schema-nested-properties">
				{this.props.methods.beforePropertyRender(this.props.id)}

				<div className="sui-accordion">
					<div className={className}>
						<SchemaPropertyAccordionHeader {...this.props}/>

						<div className="sui-accordion-item-body">
							{this.props.loop &&
							<div>{this.props.loopDescription}</div>
							}

							<table className="sui-table">
								<tbody>
								<SchemaProperties properties={this.props.properties} methods={this.props.methods}/>
								</tbody>

								{!this.props.disallowAddition &&
								<tfoot>
								<tr>
									<td colSpan={4}>
										<Button onClick={() => this.props.methods.onAddNested(this.props.id)}
												ghost={true}
												icon="sui-icon-plus"
												text={__('Add Property', 'wds')}/>
									</td>
								</tr>
								</tfoot>
								}
							</table>
						</div>
					</div>
				</div>
			</td>
		</tr>;
	}
}
