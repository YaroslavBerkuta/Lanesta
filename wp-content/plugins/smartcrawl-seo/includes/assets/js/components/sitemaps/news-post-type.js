import React from "react";
import AccordionItem from "../accordion-item";
import AccordionItemOpenIndicator from "../accordion-item-open-indicator";
import Checkbox from "../checkbox";
import {__, sprintf} from "@wordpress/i18n";
import FormField from "../form-field";
import Select from "../select";
import ajaxUrl from 'ajaxUrl';

export default class NewsPostType extends React.Component {
	static defaultProps = {
		name: '',
		label: '',
		included: false,
		excluded: [],
		taxonomies: {},
		onPostTypeInclusionChange: () => false,
		onTermExclusionChange: () => false,
		onPostExclusion: () => false,
	};

	render() {
		const taxonomies = this.props.taxonomies || {};

		if (!this.props.included) {
			return <AccordionItem
				className="sui-builder-field disabled"
				header={this.getHeader()}
			/>;
		}

		return <AccordionItem
			className="sui-builder-field"
			header={
				<React.Fragment>
					{this.getHeader()}
					<AccordionItemOpenIndicator/>
				</React.Fragment>
			}>
			{Object.keys(taxonomies).map(taxonomyName => {
				const taxonomy = taxonomies[taxonomyName];
				const terms = taxonomy.terms || {};

				return <div className="wds-news-taxonomy" key={taxonomyName}>
					<strong>{sprintf(__('Exclude %s', 'wds'), taxonomy.label)}</strong>
					<p className="sui-description">{sprintf(__("Select %s that should be excluded from the Google News sitemap.", 'wds'), taxonomy.label)}</p>

					<div className="wds-news-taxonomy-terms">
						{Object.keys(terms).map((termName) => {
							const id = "wds-news-" + this.props.name + "-term-" + termName;
							const term = terms[termName];

							return <span className="wds-news-term" key={id}>
								<Checkbox id={id}
										  checked={term.excluded}
										  onChange={(checked) => this.props.onTermExclusionChange(this.props.name, taxonomyName, term.id, checked)}/>
								<label htmlFor={id}>{term.label}</label>
							</span>;
						})}
					</div>
				</div>;
			})}

			<FormField label={sprintf(__('%s to exclude', 'wds'), this.props.label)}
					   description={__('Search for and select posts that should be excluded from the Google News sitemap.', 'wds')}
					   formControl={Select}
					   placeholder={__('Start typing...', 'wds')}
					   selectedValue={this.props.excluded}
					   multiple={true}
					   ajaxUrl={this.getAjaxSearchUrl()}
					   loadTextAjaxUrl={this.getAjaxSearchUrl('text')}
					   onSelect={(values) => this.props.onPostExclusion(this.props.name, values)}
			/>
		</AccordionItem>;
	}

	getAjaxSearchUrl(requestType = '') {
		return sprintf('%s?action=wds-search-post&type=%s&request_type=%s', ajaxUrl, this.props.name, requestType);
	}

	getHeader() {
		return <React.Fragment>
			<Checkbox checked={this.props.included}
					  onChange={(checked) => this.props.onPostTypeInclusionChange(this.props.name, checked)}/>

			<div className="sui-builder-field-label">
				<span>{this.props.label}</span>
				<span>({this.props.name})</span>
			</div>
		</React.Fragment>;
	}
}
