<?php
$option_name = empty( $option_name ) ? '' : $option_name;
$slugs = empty( $slugs ) ? array() : $slugs;
$subsite_manager_role = empty( $subsite_manager_role ) ? false : $subsite_manager_role;
$subsite_config_id = empty( $subsite_config_id ) ? '' : $subsite_config_id;
$blog_tabs = empty( $blog_tabs ) ? array() : $blog_tabs;
$dashboard_url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings_Admin::TAB_DASHBOARD );
$per_site_notice = empty( $per_site_notice ) ? '' : $per_site_notice;
$collection = Smartcrawl_Config_Collection::get();

$this->_render( 'before-page-container' );
?>
<div id="container" class="<?php smartcrawl_wrap_class( 'wds-page-network-settings' ); ?>">

	<?php $this->_render( 'page-header', array(
		'title' => esc_html__( 'Network Settings', 'wds' ),
	) ); ?>

	<?php $this->_render( 'floating-notices' ); ?>

	<form method="post">
		<div class="wds-vertical-tabs-container sui-row-with-sidenav">
			<div class="wds-vertical-tabs sui-sidenav">
				<ul class="sui-vertical-tabs">
					<li class="sui-vertical-tab tab_network_settings current">
						<a role="button" data-target="tab_network_settings" href="#">
							<?php esc_html_e( 'Permissions', 'wds' ); ?>
						</a>
					</li>
				</ul>
			</div>

			<div class="wds-vertical-tab-section sui-box tab_network_settings " id="tab_network_settings">
				<div class="sui-box-header">
					<h2 class="sui-box-title"><?php esc_html_e( 'Permissions', 'wds' ); ?></h2>
				</div>

				<div class="sui-box-body">
					<input type="hidden" name="<?php echo esc_attr( $option_name ); ?>[save_blog_tabs]" value="1"/>

					<div class="sui-box-settings-row">
						<div class="sui-box-settings-col-1">
							<label class="sui-settings-label"><?php esc_html_e( 'Sub-site Settings', 'wds' ); ?></label>
							<p class="sui-description">
								<?php esc_html_e( 'Configure how much control your sub-site admins have over their sites.', 'wds' ); ?>
							</p>
						</div>

						<div class="sui-box-settings-col-2">
							<label class="sui-settings-label"
							       for="wds-subsite-access">
								<?php esc_html_e( 'Admin Access', 'wds' ); ?>
							</label>
							<p class="sui-description">
								<?php esc_html_e( 'Choose whether Super Admins or Site Admins should control sub-site settings.', 'wds' ); ?>
							</p>
							<div class="sui-row">
								<div class="sui-col-md-6">
									<select id="wds-subsite-access"
									        name="<?php echo esc_attr( $option_name ); ?>[wds_subsite_manager_role]"
									        autocomplete="off"
									        data-minimum-results-for-search="-1"
									        class="sui-select">
										<option <?php selected( 'admin', $subsite_manager_role ) ?>
												value="admin">
											<?php esc_html_e( 'Site Admins', 'wds' ); ?>
										</option>
										<option <?php selected( 'superadmin', $subsite_manager_role ) ?>
												value="superadmin">
											<?php esc_html_e( 'Super Admins', 'wds' ); ?>
										</option>
									</select>
								</div>
							</div>

							<div class="wds-separator-top">
								<label class="sui-settings-label"
								       for="wds-subsite-access">
									<?php esc_html_e( 'Modules', 'wds' ); ?>
								</label>
								<p class="sui-description">
									<?php esc_html_e( 'Choose which modules should be available on sub-sites.', 'wds' ); ?>
								</p>
								<?php
								foreach ( $slugs as $item => $label ) {
									$checkbox_name = sprintf( '%s[wds_blog_tabs][%s]', $option_name, $item );
									?>
									<label for="<?php echo esc_attr( $checkbox_name ); ?>"
									       class="sui-checkbox">
										<input type="checkbox" <?php checked( ! empty( $blog_tabs[ $item ] ) ); ?>
										       name="<?php echo esc_attr( $checkbox_name ); ?>"
										       value="yes"
										       id="<?php echo esc_attr( $checkbox_name ); ?>"
										       aria-labelledby="label-<?php echo esc_attr( $checkbox_name ); ?>"/>
										<span aria-hidden="true"></span>
										<span id="label-<?php echo esc_attr( $checkbox_name ); ?>">
											<?php echo esc_html( $label ); ?>
										</span>
									</label><br/>
									<?php
								}
								?>
							</div>
							<br/>

							<div class="wds-separator-top">
								<label class="sui-settings-label"
								       for="wds-subsite-config-id">
									<?php esc_html_e( 'New Sub-sites', 'wds' ); ?>
								</label>
								<p class="sui-description">
									<?php echo smartcrawl_format_link(
										esc_html__( 'Select a config that you would like to apply to new sub-sites. Visit the %s page to manage all your configs.', 'wds' ),
										admin_url( 'admin.php?page=wds_settings&tab=tab_configs' ),
										esc_html__( 'Configs', 'wds' ),
										'_blank'
									); ?>
								</p>

								<select id="wds-subsite-config-id"
								        name="<?php echo esc_attr( $option_name ); ?>[wds_subsite_config_id]"
								        data-minimum-results-for-search="-1"
								        class="sui-select">
									<option <?php selected( ! $subsite_config_id ); ?>
											value="">
										<?php esc_html_e( 'None', 'wds' ); ?>
									</option>

									<?php foreach ( $collection->get_sorted_configs() as $config ): ?>
										<option <?php selected( $subsite_config_id, $config->get_id() ) ?>
												value="<?php echo $config->get_id(); ?>">

											<?php echo $config->get_name(); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
				</div>

				<div class="sui-box-footer">
					<button name="submit"
					        type="submit"
					        class="sui-button sui-button-blue">
						<span class="sui-icon-save" aria-hidden="true"></span>

						<?php esc_html_e( 'Save Changes', 'wds' ); ?>
					</button>
				</div>
			</div>
		</div>
		<?php wp_nonce_field( 'wds-network-settings-nonce', '_wds_nonce' ); ?>
	</form>
</div>
