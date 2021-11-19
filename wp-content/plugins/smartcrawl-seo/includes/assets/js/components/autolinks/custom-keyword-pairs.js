import React from "react";
import Button from "../button";
import Dropdown from "../dropdown";
import DropdownButton from "../dropdown-button";
import {__} from "@wordpress/i18n";
import CustomKeywordModal from "./custom-keyword-modal";

export default class CustomKeywordPairs extends React.Component {
	static defaultProps = {
		data: ''
	};

	constructor(props) {
		super(props);

		this.state = {
			pairs: this.dataPropToPairs(),
			addingPair: false,
			editingPair: false,
		};
	}

	render() {
		return <div className="wds-keyword-pairs">
			{this.state.pairs && !!this.state.pairs.length &&
			<table className="wds-keyword-pairs-existing sui-table">
				<tbody>
				<tr>
					<th>{__('Keyword', 'wds')}</th>
					<th colSpan="2">{__('Auto-Linked URL', 'wds')}</th>
				</tr>

				{this.state.pairs.map((pair, index) => {
					return <tr className="wds-keyword-pair">
						<td className="wds-pair-keyword">{pair.keyword}</td>
						<td className="wds-pair-url">{pair.url}</td>
						<td className="wds-pair-actions">
							<Dropdown buttons={[
								<DropdownButton onClick={() => this.startEditingPair(index)}
												icon="sui-icon-pencil"
												text={__('Edit', 'wds')}/>,
								<DropdownButton onClick={() => this.deletePair(index)}
												icon="sui-icon-trash"
												text={__('Delete', 'wds')} red={true}/>
							]}/>

							{this.state.editingPair === index &&
							<CustomKeywordModal
								keyword={pair.keyword}
								url={pair.url}
								editMode={true}
								onClose={() => this.stopEditingPair()}
								onSave={(keyword, url) => this.editPair(index, keyword, url)}
							/>
							}
						</td>
					</tr>;
				})}
				</tbody>
			</table>
			}

			<div className="wds-keyword-pair-new">
				<Button id="wds-keyword-pair-new-button"
						icon="sui-icon-plus"
						onClick={() => this.startAddingPair()}
						text={__('Add Link', 'wds')}/>
			</div>

			<textarea name="wds_autolinks_options[customkey]"
					  style={{display: "none"}}
					  value={this.pairsToText()}/>

			{this.state.addingPair &&
			<CustomKeywordModal
				onClose={() => this.stopAddingPair()}
				onSave={(keyword, url) => this.addPair(keyword, url)}
			/>
			}
		</div>;
	}

	dataPropToPairs() {
		return this.textToPairs(this.props.data);
	}

	textToPairs(text) {
		const lines = text.split(/\n/);
		const pairs = [];
		lines.forEach((line) => {
			if (!line.includes(',')) {
				return;
			}
			const parts = line.split(',').map(part => part.trim());
			pairs.push({
				keyword: parts.slice(0, -1).join(','),
				url: parts.slice(-1).pop()
			});
		});

		return pairs;
	}

	pairsToText() {
		const lines = [];
		this.state.pairs.forEach((pair) => {
			const keyword = pair.keyword?.trim();
			const url = pair.url?.trim();

			if (keyword && url) {
				lines.push(keyword + ',' + url);
			}
		});

		return lines.join("\n");
	}

	startEditingPair(index) {
		this.setState({
			editingPair: index
		});
	}

	editPair(index, keyword, url) {
		const pairs = this.state.pairs.slice();
		if (!keyword.trim() || !url.trim()) {
			return;
		}
		pairs[index] = {
			keyword: keyword,
			url: url
		};

		this.setState({
			pairs: pairs,
			editingPair: false
		});
	}

	stopEditingPair() {
		this.setState({
			editingPair: false
		});
	}

	startAddingPair() {
		this.setState({
			addingPair: true
		});
	}

	addPair(keyword, url) {
		const pairs = this.state.pairs.slice();
		if (!keyword.trim() || !url.trim()) {
			return;
		}
		pairs.push({
			keyword: keyword,
			url: url
		});

		this.setState({
			pairs: pairs,
			addingPair: false
		});
	}

	stopAddingPair() {
		this.setState({
			addingPair: false
		});
	}

	deletePair(index) {
		const pairs = this.state.pairs.filter((pair, idx) => idx !== index);
		this.setState({
			pairs: pairs
		});
	}
}
