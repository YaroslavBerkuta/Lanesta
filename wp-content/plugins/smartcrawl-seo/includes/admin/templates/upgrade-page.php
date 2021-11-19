<?php $this->_render( 'before-page-container' ); ?>

<div id="container" class="<?php smartcrawl_wrap_class( 'wds-upgrade-page' ); ?>">
	<?php if ( Smartcrawl_Controller_Black_Friday::get()->show_notice() ): ?>
		<div id="wds-black-friday-2021" style="padding-bottom: 30px;"></div>
	<?php endif; ?>

	<div class="sui-upgrade-page">
		<div class="sui-upgrade-page-header">
			<div class="sui-upgrade-page__container">
				<div class="sui-upgrade-page-header__content">
					<h1><?php esc_html_e( 'Upgrade to SmartCrawl Pro', 'wds' ); ?></h1>

					<p><?php esc_html_e( 'Get SmartCrawl Pro for our full WordPress search engine optimization suite, including scheduled SEO checkups and sitemap crawls, automatic linking, and white label reports for clients.', 'wds' ) ?></p>

					<p><?php esc_html_e( 'Plus – you’ll get WPMU DEV membership, which includes our award winning Smush Pro plugin for image optimization, 24/7 live WordPress support, and unlimited usage of all our premium plugins.', 'wds' ) ?></p>

					<a href="https://wpmudev.com/project/smartcrawl-wordpress-seo/?utm_source=smartcrawl&utm_medium=plugin&utm_campaign=smartcrawl_propage_topbutton"
					   target="_blank"
					   class="sui-button sui-button-lg sui-button-purple">
						<?php esc_html_e( 'Try SmartCrawl Pro for Free Today', 'wds' ); ?>
					</a>

					<div class="sui-reviews">
						<span class="sui-reviews__stars"></span>
						<div class="sui-reviews__rating">
							<span class="sui-reviews-rating">-</span> <?php esc_html_e( '/ 5.0 rating from', 'wds' ); ?>
							<span class="sui-reviews-customer-count">-</span> <?php esc_html_e( 'customers', 'wds' ); ?>
						</div>

						<a class="sui-reviews__link" href="https://www.reviews.io/company-reviews/store/wpmudev-org"
						   target="_blank">Reviews.io<span class="sui-icon-arrow-right" aria-hidden="true"></span></a>
					</div>
				</div>

				<div class="sui-upgrade-page-header__image"></div>
			</div>
		</div>

		<div class="sui-upgrade-page-features">
			<div class="sui-upgrade-page-features__header">
				<h2><?php esc_html_e( 'Pro Features', 'wds' ); ?></h2>
				<p><?php esc_html_e( 'Upgrading to Pro will get you the following benefits.', 'wds' ); ?></p>
			</div>
		</div>

		<div class="sui-upgrade-page__container">
			<div class="sui-upgrade-page-features__items">
				<div class="sui-upgrade-page-features__item">
					<span class="sui-icon-info" aria-hidden="true"></span>
					<h3><?php esc_html_e( 'Scheduled SEO Checkups & Sitemap Crawls', 'wds' ); ?></h3>
					<p><?php esc_html_e( 'Set up SmartCrawl to automatically run a comprehensive SEO Checkup daily, weekly or monthly and receive an email report to as many recipients as you like. Keep on top of issues and set yourself up for SEO success.', 'wds' ); ?></p>
				</div>

				<div class="sui-upgrade-page-features__item">
					<span class="sui-icon-info" aria-hidden="true"></span>
					<h3><?php esc_html_e( 'Automatic linking', 'wds' ); ?></h3>
					<p><?php esc_html_e( 'Configure SmartCrawl to automatically link certain key words to a page on your blog or even a whole new site all together. Internal linking can help boost SEO but giving search engines ample ways to index your site.', 'wds' ); ?></p>
				</div>

				<div class="sui-upgrade-page-features__item">
					<span class="sui-icon-graph-bar" aria-hidden="true"></span>
					<h3><?php esc_html_e( 'White label automated reporting', 'wds' ); ?></h3>
					<p><?php esc_html_e( "You'll get automated email reporting of your site's overall performance (or if you have a multisite network, we've still got you covered!). You can even white label this for your clients, and have the reports sent straight to them. You're informed and look great, and we do the work for you.", 'wds' ); ?></p>
				</div>

				<div class="sui-upgrade-page-features__item">
					<span class="sui-icon-smush" aria-hidden="true"></span>
					<h3><?php esc_html_e( 'Smush Pro for the best image optimization', 'wds' ); ?></h3>
					<p><?php esc_html_e( 'Hummingbird Pro + Smush Pro gives you the fastest possible WordPress site: Hummingbird’s performance optimization + Smush’s award-winning image optimization. It’s a powerful combination which your visitors, customers, and search engines will love.', 'wds' ); ?></p>
				</div>

				<div class="sui-upgrade-page-features__item">
					<span class="sui-icon-gdpr" aria-hidden="true"></span>
					<h3><?php esc_html_e( 'Premium WordPress plugins', 'wds' ); ?></h3>
					<p><?php esc_html_e( 'You’ll get our full suite of premium WordPress plugins, making sure from Security to Backups to Marketing and SEO you’ve got all the WordPress solutions you can possible need. You get unlimited usage on unlimited sites, and can join the millions using our plugins.', 'wds' ); ?></p>
				</div>

				<div class="sui-upgrade-page-features__item">
					<span class="sui-icon-hub" aria-hidden="true"></span>
					<h3><?php esc_html_e( 'Manage unlimited WordPress sites', 'wds' ); ?></h3>
					<p><?php esc_html_e( 'You can manage unlimited WordPress sites with automated updates, backups, security, and performance! – checks, all in one place. All of this can be white labeled for your clients, and you even get our 24/7 live WordPress support.', 'wds' ); ?></p>
				</div>

				<div class="sui-upgrade-page-features__item">
					<span class="sui-icon-help-support" aria-hidden="true"></span>
					<h3><?php esc_html_e( '24/7 live WordPress support', 'wds' ); ?></h3>
					<p><?php esc_html_e( 'We can’t stress this enough: our outstanding WordPress support is available with live chat 24/7, and we’ll help you with absolutely any WordPress issue – not just our products. It’s an expert WordPress team on call for you, whenever you need them.', 'wds' ); ?></p>
				</div>

				<div class="sui-upgrade-page-features__item">
					<span class="sui-icon-wpmudev-logo" aria-hidden="true"></span>
					<h3><?php esc_html_e( 'The WPMU DEV Guarantee', 'wds' ); ?></h3>
					<p><?php esc_html_e( "You'll be delighted with SmartCrawl Pro 😁 You can trial the plugin first with a WPMU DEV Membership, and if you continue but change your mind, you can cancel any time.", 'wds' ); ?></p>
				</div>
			</div>
		</div>

		<div class="sui-upgrade-page-cta">
			<div class="sui-upgrade-page-cta__inner">
				<h2><?php esc_html_e( 'Join 752,819 Happy Members', 'wds' ); ?></h2>

				<p><?php esc_html_e( "97% of customers are happy with WPMU DEV's service, and it’s a great time to join them: as a SmartCrawl user you’ll get a free trial period, so you can see what all the fuss is about.", 'wds' ); ?></p>

				<a href="https://wpmudev.com/project/smartcrawl-wordpress-seo/?utm_source=smartcrawl&utm_medium=plugin&utm_campaign=smartcrawl_propage_bottombutton"
				   target="_blank"
				   class="sui-button sui-button-lg sui-button-purple">
					<?php esc_html_e( 'Get SmartCrawl Pro, and get a better WordPress', 'wds' ); ?>
				</a>

				<a href="https://wpmudev.com/project/smartcrawl-wordpress-seo/?utm_source=smartcrawl&utm_medium=plugin&utm_campaign=smartcrawl_propage_bottombutton"
				   target="_blank"
				   class="sui-button sui-button-lg sui-button-purple sui-hidden-desktop">
					<?php esc_html_e( 'Get SmartCrawl Pro', 'wds' ); ?>
				</a>

				<a href="https://wpmudev.com/project/smartcrawl-wordpress-seo/?utm_source=smartcrawl&utm_medium=plugin&utm_campaign=smartcrawl_propage_bottombutton"
				   target="_blank">
					<?php esc_html_e( 'Try it free today', 'wds' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>
