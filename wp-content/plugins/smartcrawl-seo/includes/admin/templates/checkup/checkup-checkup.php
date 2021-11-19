<?php
$is_member = empty( $is_member ) ? false : true;
?>

<div class="wds-report">
	<?php $this->_render( 'checkup/checkup-checkup-results' ); ?>
	<?php if ( ! $is_member ) { ?>
		<?php
		$this->_render( 'mascot-message', array(
			'key'         => 'seo-checkup-upsell',
			'dismissible' => false,
			'message'     => sprintf(
				'%s <a target="_blank" class="sui-button sui-button-purple" href="https://wpmudev.com/project/smartcrawl-wordpress-seo/?utm_source=smartcrawl&utm_medium=plugin&utm_campaign=smartcrawl_seocheckup_checkup_upsell_notice">%s</a>',
				esc_html__( 'Upgrade to Pro to schedule automated checkups and send white label email reports directly to your clients. Never miss a beat with your search engine optimization.', 'wds' ),
				esc_html__( 'Try it for FREE today', 'wds' )
			),
		) );
		?>
	<?php } ?>
</div>
