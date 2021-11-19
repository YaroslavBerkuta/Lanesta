<?php
$code = empty( $code ) ? '' : $code;
$message = empty( $message ) ? '' : $message;
$type = empty( $type ) ? 'error' : $type;
$autoclose = empty( $autoclose ) ? false : $autoclose;
$dismiss = ! $autoclose;
?>

<div role="alert"
     id="<?php echo esc_attr( $code ); ?>"
     class="sui-notice"
     aria-live="assertive">
</div>

<button class="wds-floating-notice-trigger"
        data-notice-type="<?php echo esc_attr( $type ); ?>"
        data-notice-open="<?php echo esc_attr( $code ); ?>"
        data-notice-message="<p><?php echo esc_attr( $message ); ?></p>"
        data-notice-icon="info"
        data-notice-dismiss="<?php echo $dismiss ? 'true' : 'false'; ?>"
        data-notice-autoclose="<?php echo $autoclose ? 'true' : 'false'; ?>"
        data-notice-autoclose-timeout="5000">
</button>
