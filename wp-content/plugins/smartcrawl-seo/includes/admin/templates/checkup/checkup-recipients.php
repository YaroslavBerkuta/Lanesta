<?php
$checkup_freq = empty( $checkup_freq ) ? false : $checkup_freq;
$option_name = empty( $option_name ) ? '' : $option_name;
$email_recipients = Smartcrawl_Checkup_Options::get_all_recipients();
?>
<small><strong><?php esc_html_e( 'Recipients', 'wds' ); ?></strong></small>

<div class="wds-recipients-notice <?php echo empty( $email_recipients ) ? '' : 'hidden'; ?>">
	<?php $this->_render( 'notice', array(
		'message' => esc_html__( "You've removed all recipients. If you save without a recipient, we'll automatically turn off reports.", 'wsd' ),
	) ); ?>
</div>

<?php
$this->_render( 'email-recipients', array(
	'id'               => 'wds-seo-checkup-email-recipients',
	'email_recipients' => $email_recipients,
	'field_name'       => $option_name . '[checkup-email-recipients]',
) );
?>

<p></p>
<small><strong><?php esc_html_e( 'Schedule', 'wds' ); ?></strong></small>
<?php $this->_render( 'reporting-schedule', array(
	'component' => 'checkup',
	'frequency' => $checkup_freq,
	'dow_value' => Smartcrawl_Checkup_Options::reporting_dow(),
	'tod_value' => Smartcrawl_Checkup_Options::reporting_tod(),
) ); ?>
