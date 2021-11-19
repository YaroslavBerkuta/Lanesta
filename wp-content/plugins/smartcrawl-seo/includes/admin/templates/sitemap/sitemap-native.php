<?php
$post_types = empty( $post_types ) ? array() : $post_types;
$taxonomies = empty( $taxonomies ) ? array() : $taxonomies;
$smartcrawl_buddypress = empty( $smartcrawl_buddypress ) ? array() : $smartcrawl_buddypress;
$extra_urls = empty( $extra_urls ) ? '' : $extra_urls;
$ignore_urls = empty( $ignore_urls ) ? '' : $ignore_urls;
$ignore_post_ids = empty( $ignore_post_ids ) ? '' : $ignore_post_ids;
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$items_per_sitemap = Smartcrawl_Sitemap_Utils::get_items_per_sitemap();
$max_items_per_sitemap = Smartcrawl_Sitemap_Utils::get_max_items_per_sitemap();

$this->_render( 'notice', array(
	'message' => smartcrawl_format_link(
		esc_html__( "Your sitemap is available at %s. Note that you're using the default WordPress sitemap but can switch to SmartCrawl's advanced sitemaps at any time.", 'wds' ),
		home_url( '/wp-sitemap.xml' ),
		'/wp-sitemap.xml',
		'_blank'
	),
	'class'   => 'sui-notice-info',
) );
?>
	<div class="sui-box-settings-row">
		<div class="sui-box-settings-col-1">
			<label class="sui-settings-label">
				<?php esc_html_e( 'Switch to SmartCrawl Sitemap', 'wds' ); ?>
			</label>
			<p class="sui-description">
				<?php esc_html_e( 'Switch to the powerful and styled SmartCrawl sitemap to ensure that search engines index all your posts and pages.', 'wds' ); ?>
			</p>
		</div>
		<div class="sui-box-settings-col-2">
			<button type="button"
			        id="wds-switch-to-smartcrawl-sitemap"
			        class="sui-button sui-button-ghost">

				<span class="sui-icon-defer" aria-hidden="true"></span>
				<?php esc_html_e( 'Switch', 'wds' ); ?>
			</button>

			<p class="sui-description">
				<?php esc_html_e( 'Note: Wordpress core sitemaps will be disabled.', 'wds' ); ?>
			</p>
		</div>
	</div>

	<div class="sui-box-settings-row">
		<div class="sui-box-settings-col-1">
			<label class="sui-settings-label">
				<?php esc_html_e( 'Number of links', 'wds' ); ?>
			</label>
			<p class="sui-description">
				<?php esc_html_e( 'Change the number of links in a single sitemap.', 'wds' ); ?>
			</p>
		</div>
		<div class="sui-box-settings-col-2">
			<div class="sui-form-field sui-col">
				<label for="native-items-per-sitemap" class="sui-label">
					<?php esc_html_e( 'Number of links per sitemap', 'wds' ); ?>
				</label>
				<input type="number"
				       id="native-items-per-sitemap"
				       class="sui-form-control sui-input-sm"
				       value="<?php echo esc_attr( $items_per_sitemap ); ?>"
				       name="<?php echo esc_attr( $option_name ); ?>[items-per-sitemap]">

				<p class="sui-description">
					<?php printf(
						esc_html__( 'Choose how many links each sitemap has, up to %d.', 'wds' ),
						$max_items_per_sitemap
					); ?>
				</p>
			</div>
		</div>
	</div>
<?php
$this->_render( 'sitemap/sitemap-switch-to-smartcrawl-modal', array() );

$this->_render( 'sitemap/sitemap-common-settings', array(
	'post_types'            => $post_types,
	'taxonomies'            => $taxonomies,
	'smartcrawl_buddypress' => $smartcrawl_buddypress,
	'extra_urls'            => $extra_urls,
	'ignore_urls'           => $ignore_urls,
	'ignore_post_ids'       => $ignore_post_ids,
) );

$this->_render( 'sitemap/sitemap-deactivate-button', array(
	'label_description'  => esc_html__( 'If you no longer wish to customize the Wordpress core sitemaps  you can deactivate it.', 'wds' ),
	'button_description' => esc_html__( 'Note: By clicking this button you are disabling SmartCrawl’s sitemap module. The Wordpress core sitemap will still be available afterwards.', 'wds' ),
) );
