<?php
$error = empty( $error ) ? '' : $error;
$items = empty( $items ) ? array() : $items;
$active_items = empty( $active_items ) ? array() : $active_items;
$ignored_items = empty( $ignored_items ) ? array() : $ignored_items;
?>

<?php if ( ! empty( $error ) ) { ?>
	<?php
	// We have encountered an error. So let's show that.
	$this->_render( 'notice', array(
		'message' => esc_html( wp_strip_all_tags( $error ) ),
		'class'   => 'sui-notice-error',
	) );
	?>
<?php } elseif ( empty( $items ) ) { ?>
	<?php $this->_render( 'checkup/checkup-no-data' ); ?>
<?php } else { ?>
	<!--
		This is where we store the actual result items.
		Let's iterate through them.
	-->
	<div class="wds-accordion sui-accordion wds-draw-left">
		<?php foreach ( $active_items as $idx => $item ) {
			$this->_render( 'checkup/checkup-item', array(
				'idx'  => $idx,
				'item' => $item,
			) );
		} ?>
	</div>
	<?php if ( $ignored_items ): ?>
		<div class="wds-checkup-ignored-items">
			<small>
				<strong><?php esc_html_e( 'Ignored', 'wds' ); ?></strong>
			</small>
			<div class="wds-accordion sui-accordion wds-draw-left">
				<?php foreach ( $ignored_items as $ignored_idx => $ignored_item ) {
					$this->_render( 'checkup/checkup-item', array(
						'idx'     => $ignored_idx,
						'item'    => $ignored_item,
						'ignored' => true,
					) );
				} ?>
			</div>
		</div>
	<?php endif; ?>
<?php } ?>
