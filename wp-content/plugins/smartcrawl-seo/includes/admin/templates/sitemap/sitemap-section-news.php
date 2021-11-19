<div class="wds-vertical-tab-section sui-box tab_news <?php echo $is_active ? '' : 'hidden'; ?>"
     id="tab_news">
	<div id="wds-news-sitemap-tab">
		<div class="sui-box">
			<div class="sui-box-header">
				<h2 class="sui-box-title"><?php esc_html_e( 'News Sitemap', 'wds' ); ?></h2>
			</div>

			<div class="sui-box-body">
				<p>
					<?php echo smartcrawl_format_link(
						esc_html__( 'Are you publishing newsworthy content? Use the Google News Sitemap to list news articles and posts published in the last 48 hours so that they show up in Google News. %s', 'wds' ),
						'https://wpmudev.com/docs/wpmu-dev-plugins/smartcrawl/#news-sitemap',
						esc_html__( 'Learn More', 'wds' )
					); ?>
				</p>
			</div>
		</div>
	</div>
</div>
