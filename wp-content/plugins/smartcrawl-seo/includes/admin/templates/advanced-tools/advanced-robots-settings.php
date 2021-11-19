<?php
$sitemap_enabled = Smartcrawl_Sitemap_Utils::sitemap_enabled();
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$options = Smartcrawl_Settings::get_specific_options( 'wds_robots_options' );
$sitemap_directive_disabled = smartcrawl_get_array_value( $options, 'sitemap_directive_disabled' );
$custom_sitemap_url = smartcrawl_get_array_value( $options, 'custom_sitemap_url' );
$custom_directives = Smartcrawl_Controller_Robots::get()->get_custom_directives();
$robots_output = Smartcrawl_Controller_Robots::get()->get_robot_file_contents();
?>

<form action="<?php echo esc_attr( $_view['action_url'] ); ?>" method="post" class="wds-form">
	<?php $this->settings_fields( $_view['option_name'] ); ?>
	<input type="hidden" value="1" name="<?php echo esc_attr( $option_name ); ?>[save_robots]"/>

	<p>
		<?php esc_html_e( 'Search engines use web crawlers (bots) to explore and index the internet. A robots.txt file is a critical text file that tells those bots what they can and can’t index, and where things are.', 'wds' ); ?>
	</p>

	<?php $this->_render( 'notice', array(
		'class'   => 'sui-notice-info',
		'message' => smartcrawl_format_link(
			esc_html__( 'Your robots.txt is active and visible to bots. You can view it at %s', 'wds' ),
			smartcrawl_get_robots_url(), smartcrawl_get_robots_url(), '_blank'
		),
	) ); ?>

	<div class="sui-box-settings-row">
		<div class="sui-box-settings-col-1">
			<label class="sui-settings-label"><?php esc_html_e( 'Output', 'wds' ); ?></label>
			<p class="sui-description"><?php esc_html_e( 'Here’s a preview of your current robots.txt output. Customize your robots.txt file below.', 'wds' ); ?></p>
		</div>

		<div class="sui-box-settings-col-2">
			<label for="robots-preview" class="sui-label"><?php esc_html_e( 'Robots.txt preview', 'wds' ); ?></label>
			<textarea id="robots-preview"
			          readonly="readonly"
			          class="sui-form-control"><?php echo esc_textarea( $robots_output ); ?></textarea>
		</div>
	</div>

	<div class="sui-box-settings-row">
		<div class="sui-box-settings-col-1">
			<label class="sui-settings-label"><?php esc_html_e( 'Include Sitemap', 'wds' ); ?></label>
			<p class="sui-description"><?php esc_html_e( 'It’s really good practice to instruct search engines where to find your sitemap. If enabled, we will automatically add the required code to your robots file.', 'wds' ); ?></p>
		</div>

		<div class="sui-box-settings-col-2">
			<?php $this->_render( 'toggle-item', array(
				'field_name'                 => sprintf( '%s[%s]', $option_name, 'sitemap_directive_disabled' ),
				'field_id'                   => 'sitemap_directive_disabled',
				'checked'                    => $sitemap_directive_disabled,
				'item_label'                 => esc_html__( 'Link to my Sitemap', 'wds' ),
				'inverted'                   => true,
				'sub_settings_template'      => 'advanced-tools/advanced-robots-custom-sitemap-url-setting',
				'sub_settings_template_args' => array(
					'option_name'        => $option_name,
					'sitemap_enabled'    => $sitemap_enabled,
					'custom_sitemap_url' => $custom_sitemap_url,
				),
			) ); ?>
		</div>
	</div>

	<div class="sui-box-settings-row">
		<div class="sui-box-settings-col-1">
			<label class="sui-settings-label"><?php esc_html_e( 'Customize', 'wds' ); ?></label>
			<p class="sui-description wds-documentation-link">
				<?php echo smartcrawl_format_link(
					esc_html__( 'Customize the robots.txt output here. We have %s on a range of examples and options for your robots.txt file.', 'wds' ),
					'https://wpmudev.com/docs/wpmu-dev-plugins/smartcrawl/#robots-txt-editor',
					esc_html__( 'full documentation' ),
					'_blank'
				); ?>
			</p>
		</div>

		<div class="sui-box-settings-col-2">
			<label for="robots-file-contents"
			       class="sui-label"><?php esc_html_e( 'Edit your robots.txt file', 'wds' ); ?></label>
			<textarea id="robots-file-contents"
			          name="<?php echo esc_attr( $option_name ); ?>[custom_directives]"
			          class="sui-form-control"><?php echo esc_textarea( $custom_directives ); ?></textarea>
		</div>
	</div>

	<div class="sui-box-settings-row">
		<div class="sui-box-settings-col-1">
			<label class="sui-settings-label">
				<?php esc_html_e( 'Deactivate', 'wds' ); ?>
			</label>
			<p class="sui-description">
				<?php esc_html_e( 'No longer need a Robots.txt file? This will deactivate this feature and remove the file.', 'wds' ); ?>
			</p>
		</div>
		<div class="sui-box-settings-col-2">
			<button type="submit" name="deactivate-robots-component"
			        class="sui-button sui-button-ghost">
				<span class="sui-icon-power-on-off" aria-hidden="true"></span>
				<?php esc_html_e( 'Deactivate', 'wds' ); ?>
			</button>
		</div>
	</div>

	<footer class="sui-box-footer">
		<button name="submit" type="submit" class="sui-button sui-button-blue">
			<span class="sui-icon-save" aria-hidden="true"></span>
			<?php esc_html_e( 'Save Settings', 'wds' ); ?>
		</button>
	</footer>
</form>
