<?php
$label = empty( $label ) ? '' : $label;
$toggle_name = empty( $toggle_name ) ? '' : $toggle_name;
$min_field_name = empty( $min_field_name ) ? '' : $min_field_name;
$max_field_name = empty( $max_field_name ) ? '' : $max_field_name;
$default_min = empty( $default_min ) ? 0 : $default_min;
$default_max = empty( $default_max ) ? 0 : $default_max;

$options = empty( $_view['options'] ) ? array() : $_view['options'];
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];

$custom_char_lengths = (boolean) smartcrawl_get_array_value( $options, $toggle_name );
$custom_min_limit = (int) smartcrawl_get_array_value( $options, $min_field_name );
if ( $custom_min_limit <= 0 ) {
	$custom_min_limit = $default_min;
}
$custom_max_limit = (int) smartcrawl_get_array_value( $options, $max_field_name );
if ( $custom_max_limit <= 0 ) {
	$custom_max_limit = $default_max;
}
?>

<strong><?php echo esc_html( $label ); ?></strong>
<span class="sui-description">
	<?php printf(
		esc_html__( 'Recommended length is between %d and %d characters.', 'wds' ),
		$default_min,
		$default_max
	); ?>
</span>

<div class="sui-side-tabs sui-tabs">
	<div data-tabs>
		<label class="<?php echo $custom_char_lengths ? '' : 'active'; ?>">
			<?php esc_html_e( 'Default', 'wds' ); ?>

			<input name="<?php echo esc_attr( $option_name ); ?>[<?php echo esc_attr( $toggle_name ); ?>]" <?php checked( $custom_char_lengths, false ); ?>
			       value="0" type="radio"/>
		</label>

		<label class="<?php echo $custom_char_lengths ? 'active' : ''; ?>">
			<?php esc_html_e( 'Custom', 'wds' ); ?>

			<input name="<?php echo esc_attr( $option_name ); ?>[<?php echo esc_attr( $toggle_name ); ?>]" <?php checked( $custom_char_lengths ); ?>
			       value="1" type="radio"/>
		</label>
	</div>

	<div data-panes>
		<div class="<?php echo $custom_char_lengths ? '' : 'active'; ?>">
			<table class="sui-table">
				<tr>
					<td class="sui-table-item-title"><?php esc_html_e( 'Minimum', 'wds' ); ?></td>
					<td>
						<span class="sui-description">
							<strong>
								<?php printf( esc_html__( '%d characters', 'wds' ), $default_min ); ?>
							</strong>
						</span>
					</td>
				</tr>
				<tr>
					<td class="sui-table-item-title"><?php esc_html_e( 'Maximum', 'wds' ); ?></td>
					<td>
						<span class="sui-description">
							<strong>
								<?php printf( esc_html__( '%d characters', 'wds' ), $default_max ); ?>
							</strong>
						</span>
					</td>
				</tr>
			</table>
		</div>

		<div class="<?php echo $custom_char_lengths ? 'active' : ''; ?>">
			<table class="sui-table">
				<tr>
					<td class="sui-table-item-title">
						<label for="<?php echo esc_attr( $option_name ); ?>[<?php echo esc_attr( $min_field_name ); ?>]">
							<?php esc_html_e( 'Minimum', 'wds' ); ?>
						</label>
					</td>
					<td><input type="number"
					           id="<?php echo esc_attr( $option_name ); ?>[<?php echo esc_attr( $min_field_name ); ?>]"
					           name="<?php echo esc_attr( $option_name ); ?>[<?php echo esc_attr( $min_field_name ); ?>]"
					           value="<?php echo esc_attr( $custom_min_limit ); ?>"
					           class="sui-form-control sui-input-sm"/></td>
				</tr>
				<tr>
					<td class="sui-table-item-title">
						<label for="<?php echo esc_attr( $option_name ); ?>[<?php echo esc_attr( $max_field_name ); ?>]">
							<?php esc_html_e( 'Maximum', 'wds' ); ?>
						</label>
					</td>
					<td><label><input type="number"
					                  id="<?php echo esc_attr( $option_name ); ?>[<?php echo esc_attr( $max_field_name ); ?>]"
					                  name="<?php echo esc_attr( $option_name ); ?>[<?php echo esc_attr( $max_field_name ); ?>]"
					                  value="<?php echo esc_attr( $custom_max_limit ); ?>"
					                  class="sui-form-control sui-input-sm"/></label></td>
				</tr>
			</table>
		</div>
	</div>
</div>
