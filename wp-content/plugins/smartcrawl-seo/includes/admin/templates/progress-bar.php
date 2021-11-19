<?php
$progress = empty( $progress ) ? 0 : $progress;
$progress_state = empty( $progress_state ) ? '' : $progress_state;
?>

<div class="wds-progress sui-progress-block" data-progress="<?php echo (int) $progress; ?>">
	<div class="sui-progress">
		<span class="sui-progress-icon" aria-hidden="true">
			<span class="sui-icon-loader sui-loading"></span>
		</span>

		<div class="sui-progress-text">
			<span><?php echo (int) $progress; ?>%</span>
		</div>
		<div class="sui-progress-bar">
			<span style="width:<?php echo (int) $progress; ?>%;"></span>
		</div>
	</div>
</div>
<div class="sui-progress-state">
	<span><?php echo wp_kses_post( $progress_state ); ?></span>
</div>
