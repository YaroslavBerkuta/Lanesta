<?php
$is_member = (boolean) smartcrawl_get_array_value( $_view, 'is_member' );
$footer_text = sprintf( esc_html__( 'Made with %s by WPMU DEV', 'wds' ), '<span class="sui-icon-heart" aria-hidden="true" aria-label="love"></span>' );
$filtered_footer_text = Smartcrawl_White_Label::get()->get_wpmudev_footer_text( $footer_text );
?>

<div role="contentinfo">
	<div class="sui-footer">
		<?php echo wp_kses_post( $filtered_footer_text ); ?>
	</div>

	<?php if ( $filtered_footer_text !== $footer_text ) {
		// If the user has added custom footer text, don't show anything except that text
		return;
	} ?>

	<?php if ( $is_member ): ?>
		<!-- PRO Navigation -->
		<ul class="sui-footer-nav">
			<li><a href="https://wpmudev.com/hub2/" target="_blank"><?php esc_html_e( 'The Hub', 'wds' ); ?></a>
			</li>
			<li><a href="https://wpmudev.com/projects/category/plugins/"
			       target="_blank"><?php esc_html_e( 'Plugins', 'wds' ); ?></a></li>
			<li><a href="https://wpmudev.com/roadmap/"
			       target="_blank"><?php esc_html_e( 'Roadmap', 'wds' ); ?></a>
			</li>
			<li><a href="https://wpmudev.com/hub2/support"
			       target="_blank"><?php esc_html_e( 'Support', 'wds' ); ?></a>
			</li>
			<li><a href="https://wpmudev.com/docs/" target="_blank"><?php esc_html_e( 'Docs', 'wds' ); ?></a>
			</li>
			<li><a href="https://wpmudev.com/hub2/community/"
			       target="_blank"><?php esc_html_e( 'Community', 'wds' ); ?></a></li>
			<li><a href="https://wpmudev.com/terms-of-service/"
			       target="_blank"><?php esc_html_e( 'Terms of Service', 'wds' ); ?></a></li>
			<li><a href="https://incsub.com/privacy-policy/"
			       target="_blank"><?php esc_html_e( 'Privacy Policy', 'wds' ); ?></a>
			</li>
		</ul>
	<?php else: ?>
		<ul class="sui-footer-nav">
			<li><a href="https://profiles.wordpress.org/wpmudev#content-plugins"
			       target="_blank"><?php esc_html_e( 'Free Plugins', 'wds' ); ?></a></li>
			<li><a href="https://wpmudev.com/features/"
			       target="_blank"><?php esc_html_e( 'Membership', 'wds' ); ?></a>
			</li>
			<li><a href="https://wpmudev.com/roadmap/"
			       target="_blank"><?php esc_html_e( 'Roadmap', 'wds' ); ?></a>
			</li>
			<li><a href="https://wordpress.org/support/plugin/smartcrawl-seo"
			       target="_blank"><?php esc_html_e( 'Support', 'wds' ); ?></a></li>
			<li><a href="https://wpmudev.com/docs/" target="_blank"><?php esc_html_e( 'Docs', 'wds' ); ?></a>
			</li>
			<li><a href="https://wpmudev.com/hub-welcome/"
			       target="_blank"><?php esc_html_e( 'The Hub', 'wds' ); ?></a></li>
			<li><a href="https://wpmudev.com/terms-of-service/"
			       target="_blank"><?php esc_html_e( 'Terms of Service', 'wds' ); ?></a></li>
			<li><a href="https://incsub.com/privacy-policy/"
			       target="_blank"><?php esc_html_e( 'Privacy Policy', 'wds' ); ?></a>
			</li>
		</ul>
	<?php endif; ?>

	<ul class="sui-footer-social">
		<li><a href="https://www.facebook.com/wpmudev" target="_blank">
				<span class="sui-icon-social-facebook" aria-hidden="true"></span>
				<span class="sui-screen-reader-text"><?php esc_html_e( 'Facebook', 'wds' ); ?></span>
			</a></li>
		<li><a href="https://twitter.com/wpmudev" target="_blank">
				<span class="sui-icon-social-twitter" aria-hidden="true"></span>
				<span class="sui-screen-reader-text"><?php esc_html_e( 'Twitter', 'wds' ); ?></span>
			</a>
		</li>
		<li><a href="https://www.instagram.com/wpmu_dev/" target="_blank">
				<span class="sui-icon-instagram" aria-hidden="true"></span>
				<span class="sui-screen-reader-text"><?php esc_html_e( 'Instagram', 'wds' ); ?></span>
			</a></li>
	</ul>
</div>
