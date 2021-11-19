<?php
$id = empty( $id ) ? '' : $id;
if ( empty( $url ) ) {
	$url = is_numeric( $id )
		? smartcrawl_get_array_value( wp_get_attachment_image_src( $id, 'thumbnail' ), 0 )
		: $id;
}
$field_name = empty( $field_name ) ? '' : $field_name;
?>
<div class="og-image item">
	<img src="<?php echo esc_attr( $url ); ?>"/>
	<input type="hidden"
	       value="<?php echo esc_attr( $id ); ?>"
	       name="<?php echo esc_attr( $field_name ); ?>"/>
	<a href="#remove" class="remove-action">
		<span class="sui-icon-close" aria-hidden="true"></span>
	</a>
</div>
