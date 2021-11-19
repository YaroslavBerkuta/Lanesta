<?php
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$items_per_sitemap = Smartcrawl_Sitemap_Utils::get_items_per_sitemap();
?>
<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label"><?php esc_html_e( 'Sitemap Structure', 'wds' ); ?></label>
		<p class="sui-description">
			<?php esc_html_e( 'Your sitemap will be split into multiple files as your site grows larger. Sitemap files are all listed in your sitemap index.', 'wds' ); ?>
		</p>
	</div>
	<div class="sui-box-settings-col-2">
		<div class="sui-row">
			<div class="sui-form-field sui-col">
				<label for="items-per-sitemap" class="sui-label">
					<?php echo esc_html__( 'Number of links per sitemap', 'wds' ); ?>
				</label>
				<input type="number"
				       id="items-per-sitemap"
				       class="sui-form-control sui-input-sm"
				       value="<?php echo esc_attr( $items_per_sitemap ); ?>"
				       name="<?php echo esc_attr( $option_name ); ?>[items-per-sitemap]"/>
				<p class="sui-description">
					<?php printf(
						esc_html__( 'Choose how many URLs each sitemap has, up to %s. A higher number will use more server resources to generate.', 'wds' ),
						Smartcrawl_Sitemap_Utils::get_max_items_per_sitemap()
					); ?>
				</p>
			</div>
		</div>
	</div>
</div>
