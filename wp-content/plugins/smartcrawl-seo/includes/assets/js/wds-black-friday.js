import {render} from "react-dom";
import domReady from '@wordpress/dom-ready';
import React from "react";
import {NoticeBlack} from '@wpmudev/shared-notifications-black-friday';
import {createInterpolateElement} from '@wordpress/element';
import {__} from "@wordpress/i18n";
import RequestUtil from "./components/utils/request-util";
import Config_Values from "./es6/config-values";

class BlackFriday extends React.Component {
	render() {
		if (!this.isWithinTimeFrame()) {
			return <div/>;
		}

		const buildType = Config_Values.get('build_type', 'black_friday');
		const utmSource = buildType === 'full'
			? 'smartcrawl_pro'
			: 'smartcrawl_free';
		const link = 'https://wpmudev.com/black-friday/?coupon=BFP-2021&utm_source=' + utmSource + '&utm_medium=referral&utm_campaign=bf2021';

		return <NoticeBlack
			link={link}
			sourceLang={{
				discount: __('50% Off', 'wds'),
				closeLabel: __('Close', 'wds'),
				linkLabel: __('See the deal', 'wds')
			}}
			onCloseClick={() => this.dismissBFNotice()}
		>
			<p>{createInterpolateElement(
				__('<strong>Black Friday Offer!</strong> Get 11 Pro plugins on unlimited sites and much more with 50% OFF WPMU DEV Agency plan FOREVER.'),
				{strong: <strong style={{color: "#FFF"}}/>}
			)}</p>
			<p><small>{__('* Only admin users can see this message', 'wds')}</small></p>
		</NoticeBlack>;
	}

	isWithinTimeFrame() {
		const date = new Date();
		if (date.getFullYear() !== 2021) {
			return false;
		}

		const isNovember = date.getMonth() === 10;
		const isDecember = date.getMonth() === 11;

		return isNovember || (isDecember && date.getDate() < 6);
	}

	dismissBFNotice() {
		RequestUtil.post('wds-dismiss-black-friday-notice', '');
	}
}


domReady(() => {
	const blackFriday = document.getElementById('wds-black-friday-2021');
	if (blackFriday) {
		render(<BlackFriday/>, blackFriday);
	}
});
