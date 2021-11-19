<?php
$id = empty( $id ) ? '' : $id;
?>

<button type="button"
        class="sui-button sui-button-ghost wds-cancel-button"
        data-modal-close>
	<?php esc_html_e( 'Cancel', 'wds' ); ?>
</button>

<div class="sui-actions-right">
	<button type="button"
	        class="sui-button wds-add-email-recipient">
		<?php esc_html_e( 'Add', 'wds' ); ?>
	</button>
</div>
