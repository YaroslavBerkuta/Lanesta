<?php
$component = empty( $component ) ? '' : $component;
if ( ! $component ) {
	return;
}
$tod_value = empty( $tod_value ) ? false : $tod_value;

$is_member = empty( $_view['is_member'] ) ? false : true;
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$disabled = $is_member ? '' : 'disabled';

$midnight = strtotime( 'today' );

$select_id = "wds-{$component}-tod";
$select_name = "{$option_name}[{$component}-tod]";
?>

<label for="<?php echo esc_attr( $select_id ); ?>"
       class="sui-label"><?php esc_html_e( 'Time of day', 'wds' ); ?></label>

<select <?php echo esc_attr( $disabled ); ?>
		class="sui-select"
		id="<?php echo esc_attr( $select_id ); ?>"
		data-minimum-results-for-search="-1"
		name="<?php echo esc_attr( $select_name ); ?>">

	<?php foreach ( range( 0, 23 ) as $tod ) : ?>
		<option value="<?php echo esc_attr( $tod ); ?>" <?php selected( $tod, $tod_value ); ?>>
			<?php echo esc_html( date_i18n( get_option( 'time_format' ), $midnight + ( $tod * HOUR_IN_SECONDS ) ) ); ?>
		</option>
	<?php endforeach; ?>
</select>
