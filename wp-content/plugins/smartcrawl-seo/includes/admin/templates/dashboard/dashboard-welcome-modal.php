<?php
$id = 'wds-welcome-modal';
$types_builder_url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_SCHEMA ) . '&tab=tab_types&add_type=1';
$current_user = Smartcrawl_Model_User::get( get_current_user_id() );
?>

<div class="sui-modal sui-modal-md">
	<div role="dialog"
	     id="<?php echo esc_attr( $id ); ?>"
	     class="sui-modal-content <?php echo esc_attr( $id ); ?>-dialog"
	     aria-modal="true"
	     aria-labelledby="<?php echo esc_attr( $id ); ?>-dialog-title"
	     aria-describedby="<?php echo esc_attr( $id ); ?>-dialog-description">

		<div class="sui-box" role="document">
			<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--40">
				<div class="sui-box-banner" role="banner" aria-hidden="true">
					<img src="<?php echo esc_attr( SMARTCRAWL_PLUGIN_URL ); ?>assets/images/graphic-upgrade-header.svg"/>
				</div>

				<button class="sui-button-icon sui-button-float--right" data-modal-close
				        id="<?php echo esc_attr( $id ); ?>-close-button"
				        type="button">
					<span class="sui-icon-close sui-md" aria-hidden="true"></span>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'wds' ); ?></span>
				</button>

				<h3 class="sui-box-title sui-lg"
				    id="<?php echo esc_attr( $id ); ?>-dialog-title">

					<?php esc_html_e( 'New! Google News Sitemap', 'wds' ); ?>
				</h3>

				<div class="sui-box-body">
					<p class="sui-description"
					   id="<?php echo esc_attr( $id ); ?>-dialog-description">
						<span>
							<?php printf(
								esc_html__( "Hey, %s! Are you publishing newsworthy content? SmartCrawl now supports Google News sitemaps, enabling you to control which posts are submitted to Google News.", 'wds' ),
								$current_user->get_first_name()
							); ?>
						</span>
					</p>

					<button id="<?php echo esc_attr( $id ); ?>-get-started"
					        type="button"
					        class="sui-button">
						<span class="sui-loading-text">
							<?php esc_html_e( "Awesome, let's go!", 'wds' ); ?>
						</span>
						<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
					</button>
				</div>
			</div>
		</div>

		<a id="<?php echo esc_attr( $id ); ?>-skip"
		   href="#">

			<?php esc_html_e( 'Skip this', 'wds' ); ?>
		</a>
	</div>
</div>
