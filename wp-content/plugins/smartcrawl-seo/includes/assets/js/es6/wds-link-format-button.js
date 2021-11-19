import wp from 'wp';
import {link} from './link-format-button';

if (((wp || {}).blockEditor || {}).__experimentalLinkControl) {
	const {registerFormatType, unregisterFormatType} = wp.richText;
	[link].forEach(({name, ...settings}) => {
		unregisterFormatType(name);
		registerFormatType(name, settings);
	});
} else {
	console.log('SmartCrawl: wp.blockEditor.__experimentalLinkControl not found');
}
