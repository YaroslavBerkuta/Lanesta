<?php
$is_member = empty( $_view['is_member'] ) ? false : true;

$this->_render( 'progress-bar', array(
	'progress' => 0,
) );

if ( ! $is_member ) {
	$this->_render( 'mascot-message', array(
		'dismissible' => false,
		'image_name'  => 'graphic-seocheckup-modal',
		'message'     => sprintf(
			'%s <a target="_blank" class="sui-button sui-button-purple" href="https://wpmudev.com/project/smartcrawl-wordpress-seo/?utm_source=smartcrawl&utm_medium=plugin&utm_campaign=smartcrawl_seocheckup_modal_upsell_notice">%s</a>',
			esc_html__( 'Upgrade to Pro to schedule automated checkups and send white label email reports directly to your clients. Never miss a beat with your search engine optimization.', 'wds' ),
			esc_html__( 'Try it for FREE today', 'wds' )
		),
	) );
}
