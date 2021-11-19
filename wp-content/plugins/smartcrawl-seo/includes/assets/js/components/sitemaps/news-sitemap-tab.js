import React from "react";
import {createInterpolateElement} from '@wordpress/element';
import SettingsRow from "../settings-row";
import {__} from "@wordpress/i18n";
import SideTabs from "../side-tabs";
import TextField from "../text-field";
import NewsPostType from "./news-post-type";
import update from 'immutability-helper';
import FloatingNoticePlaceholder from "../floating-notice-placeholder";
import Notice from "../notice";

export default class NewsSitemapTab extends React.Component {
	static defaultProps = {
		homeUrl: '',
		enabled: false,
		publication: '',
		schemaEnabled: '',
		postTypes: {}
	};

	constructor(props) {
		super(props);

		this.state = {
			requestInProgress: false,
			enabled: this.props.enabled,
			publication: this.props.publication,
			postTypes: this.props.postTypes
		};
	}

	render() {
		const enabled = this.state.enabled;
		const publication = this.state.publication;
		const postTypes = this.state.postTypes;

		return <div className="sui-box">
			<FloatingNoticePlaceholder id="wds-news-sitemap-notice"/>

			<div className="sui-box-header">
				<h2 className="sui-box-title">{__('News Sitemap', 'wds')}</h2>
			</div>

			<div className="sui-box-body">
				<p>
					{createInterpolateElement(
						__('Are you publishing newsworthy content? Use the Google News Sitemap to list news articles and posts published in the last 48 hours so that they show up in Google News. <a>Learn More</a>', 'wds'),
						{
							a: <a href="https://wpmudev.com/docs/wpmu-dev-plugins/smartcrawl/#news-sitemap"
								  target="_blank"/>
						}
					)}
				</p>

				{enabled &&
				<Notice type="info" message={createInterpolateElement(
					__('Your sitemap is available at <a>/news-sitemap.xml</a>', 'wds'),
					{a: <a target="_blank" href={this.props.homeUrl + 'news-sitemap.xml'}/>}
				)}/>
				}

				<SettingsRow label={__('Enable News Sitemap', 'wds')}
							 description={__('Use this option to enable or disable the Google News Sitemap feature.', 'wds')}>

					<div style={{marginBottom: "10px"}}>
						<SideTabs value={enabled ? 'enable' : 'disable'}
								  onChange={(value) => this.toggleNewsSitemap(value)}
								  tabs={{
									  enable: __('Enable', 'wds'),
									  disable: __('Disable', 'wds')
								  }}
						/>
					</div>

					{enabled && this.props.schemaEnabled &&
					<Notice type="" message={createInterpolateElement(
						__('SmartCrawl automatically changes the schema to <strong>NewsArticle</strong> for all included posts/pages to ensure your newsworthy content is properly crawled and indexed. Note that if some schema types have been added using the Types Builder, the <strong>NewsArticle</strong> schema will not be displayed.', 'wds'),
						{strong: <strong/>}
					)}/>
					}
				</SettingsRow>

				{enabled &&
				<SettingsRow label={__('News Publication', 'wds')}
							 description={__('Enter your Google News publication name.', 'wds')}>

					<TextField label={__('Publication Name', 'wds')}
							   description={createInterpolateElement(__('The publication name must match your publication name on <span>news.google.com</span>', 'wds'), {
								   span: <span style={{color: "#000"}}/>
							   })}
							   value={publication}
							   onChange={(value) => this.updatePublication(value)}
					/>
				</SettingsRow>
				}

				{enabled &&
				<SettingsRow label={__('Inclusions', 'wds')}
							 description={__('Select Post Types to include in your news sitemap.', 'wds')}>

					<strong>{__('Post types to include', 'wds')}</strong>
					<p className="sui-description" style={{margin: "10px 0 20px 0"}}>
						{__('Select post types to be included in the Google News sitemap. Expand a post type to exclude specific items or groups.', 'wds')}
					</p>

					<div className="sui-box-builder">
						<div className="sui-box-builder-body">
							<div className="sui-builder-fields sui-accordion">
								{Object.keys(postTypes).map(postTypeName => {
									const postType = postTypes[postTypeName];

									return <NewsPostType key={postTypeName} {...postType}
														 onPostTypeInclusionChange={(postType, included) => this.updatePostTypeInclusionStatus(postType, included)}
														 onTermExclusionChange={(postType, taxonomy, termId, excluded) => this.updateTermExclusionStatus(postType, taxonomy, termId, excluded)}
														 onPostExclusion={(postType, values) => this.updatePostExclusion(postType, values)}
									/>;
								})}
							</div>
						</div>
					</div>
				</SettingsRow>
				}
			</div>

			<div className="sui-box-footer">
				<input type="hidden"
					   name="wds_sitemap_options[news-settings]"
					   value={JSON.stringify(this.state)}
				/>

				<button name="submit"
						type="submit"
						className="sui-button sui-button-blue">
					<span className="sui-icon-save" aria-hidden="true"/>
					{__('Save Settings', 'wds')}
				</button>
			</div>
		</div>;
	}

	updatePostExclusion(postType, excludedIds) {
		const spec = this.formatSpec([postType, "excluded"], {$set: excludedIds});
		const postTypes = update(this.state.postTypes, spec);
		this.setState({postTypes: postTypes});
	}

	updatePostTypeInclusionStatus(postType, included) {
		const postTypes = update(this.state.postTypes, {
			[postType]: {included: {$set: included}}
		});
		this.setState({postTypes: postTypes});
	}

	updateTermExclusionStatus(postType, taxonomy, termId, excluded) {
		const spec = this.formatSpec([postType, "taxonomies", taxonomy, "terms", termId, "excluded"], {$set: excluded});
		const postTypes = update(this.state.postTypes, spec);
		this.setState({postTypes: postTypes});
	}

	formatSpec(keys, operation) {
		keys.slice().reverse().forEach(key => {
			operation = {[key]: operation};
		});

		return operation;
	}

	toggleNewsSitemap(value) {
		this.setState({
			enabled: value === 'enable'
		});
	}

	updatePublication(value) {
		this.setState({
			publication: value
		});
	}
}
