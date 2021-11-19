<?php
$in_progress = empty( $in_progress ) ? false : $in_progress;

$this->_render( 'before-page-container' );
?>

<div id="container" class="<?php smartcrawl_wrap_class( 'wds-seo-health-settings wds-checkup-settings' ); ?>">
	<?php $this->_render( 'checkup/lighthouse-notice' ); ?>

	<?php $this->_render( 'page-header', array(
		'title'                 => esc_html__( 'SEO Health', 'wds' ),
		'documentation_chapter' => 'seo-health',
		'utm_campaign'          => 'smartcrawl_seo-health_docs',
		'extra_actions'         => 'checkup/checkup-header-actions',
		'extra_actions_args'    => array(
			'in_progress' => $in_progress,
		),
	) ); ?>

	<?php $this->_render( 'floating-notices', array(
		'keys' => array(
			'wds-checkup-notice',
			'wds-email-recipient-notice',
		),
	) ); ?>

	<?php if ( $in_progress ): ?>
		<?php $this->_render( 'checkup/checkup-progress-modal' ); ?>
	<?php endif; ?>

	<?php $this->_render( 'checkup/checkup-settings-inner' ); ?>

	<?php $this->_render( 'footer' ); ?>
</div>
