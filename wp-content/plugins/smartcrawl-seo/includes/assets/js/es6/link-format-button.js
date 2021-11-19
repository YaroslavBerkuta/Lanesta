import wp from 'wp';

const {__} = wp.i18n;
const {Component} = wp.element;
const {withSpokenMessages} = wp.components;
const {
	getTextContent,
	applyFormat,
	removeFormat,
	slice,
	isCollapsed,
} = wp.richText;
const {isURL, isEmail} = wp.url;
const {
	RichTextToolbarButton,
	RichTextShortcut,
} = wp.blockEditor;
const {decodeEntities} = wp.htmlEntities;
const linkIcon = 'admin-links';
const linkOff = 'editor-unlink';

import InlineLinkUI from './inline-link-ui';

const name = 'core/link';
const title = __('Link');

export const link = {
	name,
	title,
	tagName: 'a',
	className: null,
	attributes: {
		url: 'href',
		type: 'data-type',
		id: 'data-id',
		target: 'target',
		rel: 'rel'
	},
	__unstablePasteRule(value, {html, plainText}) {
		if (isCollapsed(value)) {
			return value;
		}

		const pastedText = ( html || plainText )
			.replace(/<[^>]+>/g, '')
			.trim();

		// A URL was pasted, turn the selection into a link
		if (!isURL(pastedText)) {
			return value;
		}

		// Allows us to ask for this information when we get a report.
		window.console.log('Created link:\n\n', pastedText);

		return applyFormat(value, {
			type: name,
			attributes: {
				url: decodeEntities(pastedText),
			},
		});
	},
	edit: withSpokenMessages(
		class LinkEdit extends Component {
			constructor() {
				super(...arguments);

				this.addLink = this.addLink.bind(this);
				this.stopAddingLink = this.stopAddingLink.bind(this);
				this.onRemoveFormat = this.onRemoveFormat.bind(this);
				this.state = {
					addingLink: false,
				};
			}

			addLink() {
				const {value, onChange} = this.props;
				const text = getTextContent(slice(value));

				if (text && isURL(text)) {
					onChange(
						applyFormat(value, {
							type: name,
							attributes: {url: text},
						})
					);
				} else if (text && isEmail(text)) {
					onChange(
						applyFormat(value, {
							type: name,
							attributes: {url: `mailto:${ text }`},
						})
					);
				} else {
					this.setState({addingLink: true});
				}
			}

			stopAddingLink() {
				this.setState({addingLink: false});
				this.props.onFocus();
			}

			onRemoveFormat() {
				const {value, onChange, speak} = this.props;

				onChange(removeFormat(value, name));
				speak(__('Link removed.'), 'assertive');
			}

			render() {
				const {
					isActive,
					activeAttributes,
					value,
					onChange,
				} = this.props;

				return (
					<>
					<RichTextShortcut
						type="primary"
						character="k"
						onUse={this.addLink}
					/>
					<RichTextShortcut
						type="primaryShift"
						character="k"
						onUse={this.onRemoveFormat}
					/>
					{isActive && (
						<RichTextToolbarButton
							name="link"
							icon={linkOff}
							title={__('Unlink')}
							onClick={this.onRemoveFormat}
							isActive={isActive}
							shortcutType="primaryShift"
							shortcutCharacter="k"
						/>
					)}
					{!isActive && (
						<RichTextToolbarButton
							name="link"
							icon={linkIcon}
							title={title}
							onClick={this.addLink}
							isActive={isActive}
							shortcutType="primary"
							shortcutCharacter="k"
						/>
					)}
					{( this.state.addingLink || isActive ) && (
						<InlineLinkUI
							addingLink={this.state.addingLink}
							stopAddingLink={this.stopAddingLink}
							isActive={isActive}
							activeAttributes={activeAttributes}
							value={value}
							onChange={onChange}
						/>
					)}
					</>
				);
			}
		}
	),
};
