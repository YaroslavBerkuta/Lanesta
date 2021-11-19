<?php
$options = empty( $_view['options'] ) ? array() : $_view['options'];
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$keep_settings = (boolean) smartcrawl_get_array_value( $options, 'keep_settings_on_uninstall' );
$keep_data = (boolean) smartcrawl_get_array_value( $options, 'keep_data_on_uninstall' );
$show_subsite_controls = is_multisite();
?>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label"><?php esc_html_e( 'Uninstallation', 'wds' ); ?></label>
		<p class="sui-description">
			<?php esc_html_e( 'When you uninstall this plugin, what do you want to do with your settings and stored data?', 'wds' ); ?>
		</p>
	</div>
	<div class="sui-box-settings-col-2">
		<div>
			<label class="sui-label"><?php esc_html_e( 'Settings', 'wds' ); ?></label>
			<p class="sui-description">
				<?php esc_html_e( 'Choose whether to save your settings for next time, or reset them. Settings include all configuration you have done for titles & meta, sitemaps etc.', 'wds' ); ?>
			</p>
		</div>
		<div class="sui-side-tabs sui-tabs">
			<div class="sui-tabs-menu">
				<label class="sui-tab-item <?php echo $keep_settings ? 'active' : ''; ?>">
					<?php esc_html_e( 'Preserve', 'wds' ); ?>
					<input name="<?php echo esc_attr( $option_name ); ?>[keep_settings_on_uninstall]" <?php checked( $keep_settings ); ?>
					       value="1" type="radio"
					       class="hidden"/>
				</label>
				<label class="sui-tab-item <?php echo $keep_settings ? '' : 'active'; ?>">
					<?php esc_html_e( 'Reset', 'wds' ); ?>
					<input name="<?php echo esc_attr( $option_name ); ?>[keep_settings_on_uninstall]" <?php checked( $keep_settings, false ); ?>
					       value="0" type="radio"
					       class="hidden"/>
				</label>
			</div>
		</div>

		<div>
			<label class="sui-label"><?php esc_html_e( 'Data', 'wds' ); ?></label>
			<p class="sui-description">
				<?php esc_html_e( 'Choose whether to keep or remove stored transient data such as logs and SEO Checkup and Sitemap Crawler data.', 'wds' ); ?>
			</p>
		</div>
		<div class="sui-side-tabs sui-tabs">
			<div class="sui-tabs-menu">
				<label class="sui-tab-item <?php echo $keep_data ? 'active' : ''; ?>">
					<?php esc_html_e( 'Keep', 'wds' ); ?>
					<input name="<?php echo esc_attr( $option_name ); ?>[keep_data_on_uninstall]" <?php checked( $keep_data ); ?>
					       value="1" type="radio"
					       class="hidden"/>
				</label>
				<label class="sui-tab-item <?php echo $keep_data ? '' : 'active'; ?>">
					<?php esc_html_e( 'Reset', 'wds' ); ?>
					<input name="<?php echo esc_attr( $option_name ); ?>[keep_data_on_uninstall]" <?php checked( $keep_data, false ); ?>
					       value="0" type="radio"
					       class="hidden"/>
				</label>
			</div>
		</div>

		<?php
		if ( $show_subsite_controls ) {
			$this->_render( 'notice', array(
				'class'   => 'sui-notice-info',
				'message' => esc_html__( 'This option only affects the main site. If you want to delete settings and data for subsites before uninstalling the plugin, please use the setting below.', 'wds' ),
			) );
		}
		?>
	</div>
</div>

<?php if ( $show_subsite_controls ): ?>
	<div class="sui-box-settings-row">
		<div class="sui-box-settings-col-1">
			<label class="sui-settings-label"><?php esc_html_e( 'Subsites', 'wds' ); ?></label>
			<p class="sui-description">
				<?php esc_html_e( 'Manage the settings and data stored in each subsite here.', 'wds' ); ?>
			</p>
		</div>
		<div class="sui-box-settings-col-2">
			<div>
				<label class="sui-label"><?php esc_html_e( 'Delete Settings & Data', 'wds' ); ?></label>
				<p class="sui-description">
					<?php esc_html_e( 'Use this option to manually delete settings and data from all the subsites.', 'wds' ); ?>
				</p>
			</div>

			<div id="wds-multisite-reset-button-placeholder"></div>
		</div>
	</div>
<?php endif; ?>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label"><?php esc_html_e( 'Factory Reset', 'wds' ); ?></label>
		<p class="sui-description">
			<?php esc_html_e( 'Needing to start fresh? Use this button to roll back to the default settings and remove data.', 'wds' ); ?>
		</p>
	</div>
	<div class="sui-box-settings-col-2">
		<div id="wds-data-reset-button-placeholder"></div>

		<p class="sui-description">
			<?php esc_html_e( 'Note: This will instantly revert all settings to their default states and will remove all data.', 'wds' ); ?>
		</p>
	</div>
</div>
