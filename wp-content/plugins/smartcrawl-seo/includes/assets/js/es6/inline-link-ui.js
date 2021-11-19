import wp from 'wp';

import {uniqueId} from 'lodash-es';

const {useMemo, useState} = wp.element;
const {__} = wp.i18n;
const {withSpokenMessages, Popover} = wp.components;
const {prependHTTP} = wp.url;
const {create, insert, isCollapsed, applyFormat} = wp.richText;
const {__experimentalLinkControl} = wp.blockEditor;
const LinkControl = __experimentalLinkControl;

const customLinkControlSettings = [
	{
		id: 'opensInNewTab',
		title: __('Open in new tab'),
	},
	{
		id: 'relSponsored',
		title: __('Sponsored link'),
	},
	{
		id: 'relUgc',
		title: __('User generated content'),
	},
	{
		id: 'relNofollow',
		title: __('Nofollow'),
	}
];

import {createLinkFormat, isValidHref} from './inline-link-ui-utils';

function InlineLinkUI({
						  isActive,
						  activeAttributes,
						  addingLink,
						  value,
						  onChange,
						  speak,
						  stopAddingLink,
					  }) {
	const mountingKey = useMemo(uniqueId, [addingLink]);

	const [nextLinkValue, setNextLinkValue] = useState();

	const anchorRef = useMemo(() => {
		const selection = window.getSelection();

		if (!selection.rangeCount) {
			return;
		}

		const range = selection.getRangeAt(0);

		if (addingLink && !isActive) {
			return range;
		}

		let element = range.startContainer;

		// If the caret is right before the element, select the next element.
		element = element.nextElementSibling || element;

		while (element.nodeType !== window.Node.ELEMENT_NODE) {
			element = element.parentNode;
		}

		return element.closest('a');
	}, [addingLink, value.start, value.end]);

	const linkValue = {
		url: activeAttributes.url,
		type: activeAttributes.type,
		id: activeAttributes.id,
		opensInNewTab: activeAttributes.target === '_blank',
		relSponsored: !!activeAttributes.rel && activeAttributes.rel.includes('sponsored'),
		relUgc: !!activeAttributes.rel && activeAttributes.rel.includes('ugc'),
		relNofollow: !!activeAttributes.rel && activeAttributes.rel.includes('nofollow'),
		...nextLinkValue,
	};

	function onChangeLink(nextValue) {
		nextValue = {
			...nextLinkValue,
			...nextValue,
		};

		const didToggleSetting =
			(
				linkValue.opensInNewTab !== nextValue.opensInNewTab ||
				linkValue.relSponsored !== nextValue.relSponsored ||
				linkValue.relUgc !== nextValue.relUgc ||
				linkValue.relNofollow !== nextValue.relNofollow
			) &&
			linkValue.url === nextValue.url;

		const didToggleSettingForNewLink =
			didToggleSetting && nextValue.url === undefined;

		setNextLinkValue(didToggleSettingForNewLink ? nextValue : undefined);

		if (didToggleSettingForNewLink) {
			return;
		}

		const newUrl = prependHTTP(nextValue.url);
		const format = createLinkFormat({
			url: newUrl,
			type: nextValue.type,
			id:
				nextValue.id !== undefined && nextValue.id !== null
					? String( nextValue.id )
					: undefined,
			opensInNewWindow: nextValue.opensInNewTab,
			relSponsored: nextValue.relSponsored,
			relUgc: nextValue.relUgc,
			relNofollow: nextValue.relNofollow
		});

		if (isCollapsed(value) && !isActive) {
			const newText = nextValue.title || newUrl;
			const toInsert = applyFormat(
				create({text: newText}),
				format,
				0,
				newText.length
			);
			onChange(insert(value, toInsert));
		} else {
			const newValue = applyFormat(value, format);
			newValue.start = newValue.end;
			newValue.activeFormats = [];
			onChange(newValue);
		}

		if (!didToggleSetting) {
			stopAddingLink();
		}

		if (!isValidHref(newUrl)) {
			speak(
				__(
					'Warning: the link has been inserted but may have errors. Please test it.'
				),
				'assertive'
			);
		} else if (isActive) {
			speak(__('Link edited.'), 'assertive');
		} else {
			speak(__('Link inserted.'), 'assertive');
		}
	}

	return (
		<Popover
			key={mountingKey}
			anchorRef={anchorRef}
			focusOnMount={addingLink ? 'firstElement' : false}
			onClose={stopAddingLink}
			position="bottom center"
		>
			<LinkControl
				value={linkValue}
				onChange={onChangeLink}
				settings={customLinkControlSettings}
				forceIsEditingLink={addingLink}
			/>
		</Popover>
	);
}

export default withSpokenMessages(InlineLinkUI);
