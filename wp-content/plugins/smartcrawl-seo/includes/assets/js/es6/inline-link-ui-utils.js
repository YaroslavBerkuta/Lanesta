import wp from 'wp';
import {startsWith} from 'lodash-es';

const {
	getProtocol, isValidProtocol,
	getAuthority, isValidAuthority,
	getPath, isValidPath,
	getQueryString, isValidQueryString,
	getFragment, isValidFragment,
} = wp.url;

export function isValidHref(href) {
	if (!href) {
		return false;
	}

	const trimmedHref = href.trim();

	if (!trimmedHref) {
		return false;
	}

	// Does the href start with something that looks like a URL protocol?
	if (/^\S+:/.test(trimmedHref)) {
		const protocol = getProtocol(trimmedHref);
		if (!isValidProtocol(protocol)) {
			return false;
		}

		// Add some extra checks for http(s) URIs, since these are the most common use-case.
		// This ensures URIs with an http protocol have exactly two forward slashes following the protocol.
		if (
			startsWith(protocol, 'http') &&
			!/^https?:\/\/[^\/\s]/i.test(trimmedHref)
		) {
			return false;
		}

		const authority = getAuthority(trimmedHref);
		if (!isValidAuthority(authority)) {
			return false;
		}

		const path = getPath(trimmedHref);
		if (path && !isValidPath(path)) {
			return false;
		}

		const queryString = getQueryString(trimmedHref);
		if (queryString && !isValidQueryString(queryString)) {
			return false;
		}

		const fragment = getFragment(trimmedHref);
		if (fragment && !isValidFragment(fragment)) {
			return false;
		}
	}

	// Validate anchor links.
	if (startsWith(trimmedHref, '#') && !isValidFragment(trimmedHref)) {
		return false;
	}

	return true;
}

export function createLinkFormat({url, type, id, opensInNewWindow, relSponsored, relUgc, relNofollow}) {
	const format = {
		type: 'core/link',
		attributes: {
			url,
		},
	};

	if (type) format.attributes.type = type;
	if (id) format.attributes.id = id;

	let rel = [];
	if (opensInNewWindow) {
		format.attributes.target = '_blank';
		rel.push('noreferrer');
		rel.push('noopener');
	}
	if (relSponsored) {
		rel.push('sponsored');
	}
	if (relUgc) {
		rel.push('ugc');
	}
	if (relNofollow) {
		rel.push('nofollow');
	}

	if (rel.length) {
		format.attributes.rel = rel.join(' ');
	}

	return format;
}
