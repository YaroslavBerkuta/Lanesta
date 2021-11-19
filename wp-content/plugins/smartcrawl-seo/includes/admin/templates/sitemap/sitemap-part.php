<?php
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$item_label = empty( $item_label ) ? '' : $item_label;
$part_excluded = ! empty( $_view['options'][ $item ] );
$part_checked = $part_excluded ? 'checked' : '';
$inverted = empty( $inverted ) ? false : $inverted;
$class = empty( $class ) ? '' : $class;
$tooltip_text = sprintf( esc_html__( 'Include/exclude %s from your sitemap' ), strtolower( $item_label ) );
?>
<tr class="<?php echo esc_attr( $class ); ?>">
	<td colspan="<?php echo empty( $item_name ) ? 2 : 1; ?>">
		<label for="<?php echo esc_attr( $item ); ?>">
			<small><strong><?php echo esc_html( $item_label ); ?></strong></small>
		</label>
	</td>
	<?php if ( ! empty( $item_name ) ) : ?>
		<td>
			<?php echo esc_html( $item_name ); ?>
		</td>
	<?php endif; ?>
	<td>
		<span class="sui-tooltip sui-tooltip-top-right"
		      data-tooltip="<?php echo esc_attr( $tooltip_text ); ?>">

			<?php $this->_render( 'toggle-item', array(
				'inverted'   => $inverted,
				'checked'    => $part_excluded,
				'field_id'   => $item,
				'field_name' => "{$option_name}[{$item}]",
			) ) ?>
		</span>
	</td>
</tr>
