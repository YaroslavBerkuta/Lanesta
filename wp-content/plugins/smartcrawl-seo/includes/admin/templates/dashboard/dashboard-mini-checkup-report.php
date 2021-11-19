<?php
$issue_count = empty( $issue_count ) ? 0 : $issue_count;
$page_url = ! empty( $page_url ) ? $page_url : Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_HEALTH );
$lighthouse_url = $page_url . '&tab=tab_settings#seo-test-mode';
$has_errors = ! empty( $error );
?>

<?php if ( $issue_count > 0 || $has_errors ) : ?>
	<div class="wds-report">
		<?php if ( ! $has_errors ): ?>
			<p>
				<?php esc_html_e( 'A comprehensive report on how optimized your website is for search engines and social media.', 'wds' ); ?>
			</p>
		<?php endif; ?>
		<?php $this->_render( 'checkup/checkup-results-inner' ); ?>
	</div>
<?php else : ?>
	<p><?php esc_html_e( 'A comprehensive report on how optimized your website is for search engines and social media.', 'wds' ); ?></p>
	<?php
	$this->_render( 'notice', array(
		'message' => esc_html__( 'You have no outstanding SEO issues. Awesome work!', 'wds' ),
		'class'   => 'sui-notice-success',
	) );
	?>
<?php endif; ?>

<?php if ( ! $has_errors ): ?>
	<div class="wds-space-between">
		<a href="<?php echo esc_attr( $page_url ); ?>"
		   aria-label="<?php esc_html_e( 'View SEO checkup report', 'wds' ); ?>"
		   class="sui-button sui-button-ghost">

			<span class="sui-icon-eye" aria-hidden="true"></span> <?php esc_html_e( 'View Report', 'wds' ); ?>
		</a>
		<small>
			<?php echo smartcrawl_format_link(
				esc_html__( 'Switch to %s.', 'wds' ),
				$lighthouse_url,
				esc_html__( 'Lighthouse SEO audits', 'wds' )
			); ?>
		</small>
	</div>
<?php endif; ?>
