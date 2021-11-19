<?php
$lighthouse_start_time = empty( $lighthouse_start_time ) ? false : $lighthouse_start_time;
$active_tab = empty( $active_tab ) ? '' : $active_tab;
?>
	<div>
		<?php $this->_render( 'lighthouse/lighthouse-progress-modal' ); ?>
	</div>
<?php
$this->_render( 'vertical-tab', array(
	'tab_id'       => 'tab_lighthouse',
	'tab_name'     => esc_html__( 'Get Started', 'wds' ),
	'is_active'    => 'tab_lighthouse' === $active_tab,
	'button_text'  => false,
	'tab_sections' => array(
		array(
			'section_template' => 'disabled-component-inner',
			'section_args'     => array(
				'content'         => sprintf(
					'%s<br/>%s',
					esc_html__( 'Let’s find out what can be improved!', 'wds' ),
					esc_html__( 'Smartcrawl will run a quick SEO test against your Homepage, and then give you the tools to drastically improve your SEO.', 'wds' )
				),
				'image'           => 'graphic-lighthouse-disabled.svg',
				'component'       => 'lighthouse',
				'button_text'     => esc_html__( 'Test My Homepage', 'wds' ),
				'button_disabled' => $lighthouse_start_time,
			),
		),
	),
) );
