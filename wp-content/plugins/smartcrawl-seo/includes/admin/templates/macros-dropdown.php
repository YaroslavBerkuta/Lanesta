<?php
$macros = empty( $macros ) ? array() : $macros;
?>

<select title="" class="sui-variables">
	<?php foreach ( $macros as $macro => $label ): ?>

		<option value="<?php echo esc_attr( $macro ); ?>"
		        data-content="<?php echo esc_attr( $macro ); ?>">
			<?php echo esc_html( $label ); ?>
		</option>
	<?php endforeach; ?>
</select>
