<?php
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$email_recipients = empty( $email_recipients ) ? array() : $email_recipients;
$lighthouse_frequency = empty( $lighthouse_frequency ) ? false : $lighthouse_frequency;
$reporting_condition = Smartcrawl_Lighthouse_Options::reporting_condition();
$reporting_device = Smartcrawl_Lighthouse_Options::reporting_device();
?>

<small><strong><?php esc_html_e( 'Recipients', 'wds' ); ?></strong></small>
<div class="wds-recipients-notice <?php echo empty( $email_recipients ) ? '' : 'hidden'; ?>">
	<?php $this->_render( 'notice', array(
		'message' => esc_html__( "You've removed all recipients. If you save without a recipient, we'll automatically turn off reports.", 'wsd' ),
	) ); ?>
</div>

<?php
$this->_render( 'email-recipients', array(
	'id'               => 'wds-lighthouse-email-recipients',
	'email_recipients' => $email_recipients,
	'field_name'       => $option_name . '[lighthouse-recipients]',
) );
?>

<p></p>
<small><strong><?php esc_html_e( 'Schedule', 'wds' ); ?></strong></small>
<?php $this->_render( 'reporting-schedule', array(
	'component' => 'lighthouse',
	'frequency' => $lighthouse_frequency,
	'dow_value' => Smartcrawl_Lighthouse_Options::reporting_dow(),
	'tod_value' => Smartcrawl_Lighthouse_Options::reporting_tod(),
) ); ?>

<p></p>
<div class="sui-form-field">
	<label for="wds-reporting-condition-checkbox" class="sui-checkbox">
		<input type="checkbox" <?php checked( Smartcrawl_Lighthouse_Options::reporting_condition_enabled() ); ?>
		       id="wds-reporting-condition-checkbox"
		       name="<?php echo esc_attr( $option_name ); ?>[lighthouse-reporting-condition-enabled]"
		       aria-labelledby="wds-reporting-condition-label"/>
		<span aria-hidden="true"></span>
		<span id="wds-reporting-condition-label"><?php esc_html_e( 'Only send report when your SEO score drops below:', 'wds' ); ?></span>
	</label>

	<div id="wds-reporting-condition-container">
		<select class="sui-select"
		        id="wds-reporting-condition"
		        data-minimum-results-for-search="-1"
		        data-placeholder="<?php echo esc_attr( 'Select' ); ?>"
		        name="<?php echo esc_attr( $option_name ); ?>[lighthouse-reporting-condition]">

			<option></option>

			<?php foreach ( array( 10, 20, 30, 40, 50, 60, 70, 80, 90, 100 ) as $value ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $reporting_condition ); ?>>
					<?php echo esc_html( $value ) . '%'; ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>
</div>

<div id="wds-lighthouse-reporting-device-container">
	<strong><?php esc_html_e( 'Device', 'wds' ); ?></strong>
	<p class="sui-description"><?php esc_html_e( 'Choose the device type you want to show the SEO test results for in your scheduled reports.', 'wds' ); ?></p>

	<?php $this->_render( 'side-tabs', array(
		'id'    => 'wds-lighthouse-reporting-device',
		'name'  => "{$option_name}[lighthouse-reporting-device]",
		'value' => $reporting_device,
		'tabs'  => array(
			array(
				'value' => 'both',
				'label' => esc_html__( 'Both', 'wds' ),
			),
			array(
				'value' => 'desktop',
				'label' => esc_html__( 'Desktop', 'wds' ),
			),
			array(
				'value' => 'mobile',
				'label' => esc_html__( 'Mobile', 'wds' ),
			),
		),
	) ); ?>
</div>
