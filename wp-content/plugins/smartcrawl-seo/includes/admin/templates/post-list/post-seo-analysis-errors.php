<?php
$focus_missing = empty( $focus_missing ) ? false : $focus_missing;
$errors = empty( $errors ) ? array() : $errors;
$status_class = empty( $status_class ) ? 'wds-status-warning' : $status_class;
?>

<div class="wds-analysis <?php echo esc_attr( $status_class ); ?>">
	<span>
		<?php echo $focus_missing
			? esc_html__( 'N/A', 'wds' )
			: esc_html( count( $errors ) ); ?>
	</span>
</div>
<div class="wds-analysis-details">
	<?php foreach ( $errors as $key => $error ): ?>
		<div class="wds-error <?php echo esc_attr( $key ); ?>">
			<?php echo esc_html( $error ); ?>
		</div>
	<?php endforeach; ?>
</div>
