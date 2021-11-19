<?php
$service = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_CHECKUP );
$in_progress = empty( $in_progress ) ? false : $in_progress;
?>
<a href="#"
   class="sui-button sui-button-blue wds-start-checkup-button <?php echo $in_progress ? 'disabled' : ''; ?>">
	<span class="sui-icon-plus" aria-hidden="true"></span>

	<?php esc_html_e( 'Run checkup', 'wds' ); ?>
</a>
