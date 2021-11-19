<?php
$frequency = empty( $frequency ) ? false : $frequency;
$dow_value = empty( $dow_value ) ? false : $dow_value;
$tod_value = empty( $tod_value ) ? false : $tod_value;
$component = empty( $component ) ? '' : $component;
if ( ! $component ) {
	return;
}

$cron = Smartcrawl_Controller_Cron::get();
// This does the actual rescheduling
$cron->set_up_schedule();
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];

$frequency_radio_name = "{$option_name}[{$component}-frequency]";
$frequency_radio_id = "wds-{$component}-frequency-radio";
$pane_id = "wds-{$component}-frequency-pane";
?>

<div id="wds-<?php echo esc_attr( $component ); ?>-frequency-tabs"
     class="wds-frequency-tabs sui-side-tabs sui-tabs">

	<div class="sui-tabs-menu">
		<?php foreach ( $cron->get_frequencies() as $key => $label ) : ?>
			<label class="sui-tab-item <?php echo $key === $frequency ? 'active' : ''; ?>">

				<?php echo esc_html( $label ); ?>
				<input name="<?php echo esc_attr( $frequency_radio_name ); ?>"
				       value="<?php echo esc_attr( $key ); ?>"
				       type="radio" <?php checked( $key, $frequency ); ?>
				       class="<?php echo esc_attr( $frequency_radio_id ); ?>"
				/>
			</label>
		<?php endforeach; ?>
	</div>

	<div id="<?php esc_attr( $pane_id ); ?>" class="sui-border-frame">
		<div class="sui-row">
			<div class="sui-col wds-dow weekly">
				<div class="sui-form-field">
					<?php $this->_render( 'reporting-dow-select', array(
						'component' => $component,
						'dow_value' => $dow_value,
					) ); ?>
				</div>
			</div>

			<div class="sui-col wds-dow monthly">
				<div class="sui-form-field">
					<?php $this->_render( 'reporting-dow-select', array(
						'component' => $component,
						'dow_value' => $dow_value,
						'monthly'   => true,
					) ); ?>
				</div>
			</div>

			<div class="sui-col">
				<div class="sui-form-field">
					<?php $this->_render( 'reporting-tod-select', array(
						'component' => $component,
						'tod_value' => $tod_value,
					) ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
