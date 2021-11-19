<?php
$is_member = empty( $_view['is_member'] ) ? false : true;
if ( $is_member ) {
	return;
}
?>
<span class="sui-tag sui-tag-pro"><?php esc_html_e( 'Pro', 'wds' ); ?></span>
