<?php
$redirections = empty( $redirections ) ? array() : $redirections;
$types = empty( $types ) ? array() : $types;
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$plugin_settings = Smartcrawl_Settings::get_specific_options( 'wds_settings_options' );
$redirection_index = 0;
?>

<div id="wds-redirects-container"></div>
<input type="hidden" value="1" name="<?php echo esc_attr( $option_name ); ?>[save_redirects]"/>
