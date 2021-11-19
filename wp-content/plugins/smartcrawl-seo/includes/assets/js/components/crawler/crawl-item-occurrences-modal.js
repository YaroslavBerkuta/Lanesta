import React from "react";
import Modal from "../modal";
import {__, sprintf} from "@wordpress/i18n";
import {createInterpolateElement} from "@wordpress/element";
import Button from "../button";

export default class CrawlItemOccurrencesModal extends React.Component {
	static defaultProps = {
		path: '',
		origin: [],
		onClose: () => false
	};

	render() {
		const origins = [...new Set(this.props.origin)];
		const description = createInterpolateElement(
			sprintf(
				__('We found links to <strong>%s</strong> in these locations, you might want to remove these links or direct them somewhere else.', 'wds'),
				this.props.path
			),
			{strong: <strong/>}
		);

		return <Modal id="wds-issue-occurrences"
					  title={__('Broken URL Locations', 'wds')}
					  description={description}
					  onClose={() => this.props.onClose()}
					  focusAfterOpen="wds-close-occurrences-modal-button"
					  small={true}>
			<div className="wds-issue-occurrences">
				<ul>
					{origins.map(origin => <li key={origin}>
						<a href={origin}>{origin}</a>
					</li>)}
				</ul>
			</div>

			<Button id="wds-close-occurrences-modal-button"
					onClick={() => this.props.onClose()}
					ghost={true}
					text={__('Close', 'wds')}
			/>
		</Modal>;
	}
}
