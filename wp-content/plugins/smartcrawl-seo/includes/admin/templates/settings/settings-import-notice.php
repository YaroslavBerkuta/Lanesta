<?php
$settings_errors = Smartcrawl_Controller_Third_Party_Import::get()->get_errors();
$import = smartcrawl_get_array_value( $_GET, 'import' );
?>
<div class="sui-floating-notices">
	<?php
	if ( $import === 'success' ) {
		$this->_render( 'floating-notice', array(
			'code'      => 'wds-crawl-started',
			'type'      => 'success',
			'message'   => esc_html__( 'Settings successfully imported', 'wds' ),
			'autoclose' => true,
		) );
	} elseif ( ! empty( $settings_errors ) ) {
		$this->_render( 'floating-notice', array(
			'code'      => 'wds-import-error',
			'type'      => 'error',
			'message'   => array_shift( $settings_errors ),
			'autoclose' => false,
		) );
	}
	?>
</div>
