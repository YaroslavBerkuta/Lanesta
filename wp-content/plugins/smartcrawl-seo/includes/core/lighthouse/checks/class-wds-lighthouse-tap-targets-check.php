<?php

class Smartcrawl_Lighthouse_Tap_Targets_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'tap-targets';

	public function prepare() {
		$this->set_success_title( esc_html__( 'Tap targets are sized appropriately', 'wds' ) );
		$this->set_failure_title( esc_html__( 'Tap targets are not sized appropriately', 'wds' ) );
		$this->set_success_description( $this->format_success_description() );
		$this->set_failure_description( $this->format_failure_description() );
		$this->set_copy_description( $this->format_copy_description() );
	}

	function get_id() {
		return self::ID;
	}

	private function print_common_description() {
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Overview', 'wds' ); ?></strong>
			<p>
				<?php printf(
					esc_html__( 'Interactive elements like buttons and links should be large enough (%s), and have enough space around them (%s), to be easy enough to tap without overlapping onto other elements.', 'wds' ),
					'<strong>48x48px</strong>',
					'<strong>8px</strong>'
				); ?>
			</p>
			<p><?php esc_html_e( 'Many search engines rank pages based on how mobile-friendly they are. Making sure tap targets are big enough and far enough apart from each other makes your page more mobile-friendly and accessible.', 'wds' ); ?></p>
		</div>
		<?php
	}

	private function format_success_description() {
		ob_start();
		$this->print_common_description();
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'wds' ); ?></strong>
			<?php Smartcrawl_Simple_Renderer::render( 'notice', array(
				'class'   => 'sui-notice-success',
				'message' => esc_html__( 'Tap targets are sized appropriately.', 'wds' ),
			) ); ?>
		</div>
		<?php
		return ob_get_clean();
	}

	private function format_failure_description() {
		ob_start();
		$this->print_common_description();
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'wds' ); ?></strong>
			<?php Smartcrawl_Simple_Renderer::render( 'notice', array(
				'class'   => 'sui-notice-warning',
				'message' => esc_html__( 'Tap targets are not sized appropriately.', 'wds' ),
			) ); ?>
		</div>

		<div class="wds-lh-section">
			<p><?php esc_html_e( 'Targets that are smaller than 48 px by 48 px or closer than 8 px apart fail the audit.', 'wds' ); ?></p>
			<?php $this->print_details_table(); ?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'How to fix your tap targets', 'wds' ); ?></strong>
			<ul>
				<li><?php printf(
						esc_html__( "%s: Increase the size of tap targets that are too small. Tap targets that are %s never fail the audit. If you have elements that shouldn't appear any bigger (for example, icons), try increasing the padding property.", 'wds' ),
						'<strong>' . esc_html__( 'Step 1', 'wds' ) . '</strong>',
						'<strong>' . esc_html__( '48 px by 48 px', 'wds' ) . '</strong>'
					); ?>
				</li>
				<li><?php printf(
						esc_html__( '%s: Increase the spacing between tap targets that are too close together using properties like margin. There should be at least %s between tap targets.', 'wds' ),
						'<strong>' . esc_html__( 'Step 2', 'wds' ) . '</strong>',
						'<strong>' . esc_html__( '8px', 'wds' ) . '</strong>'
					); ?>
				</li>
			</ul>
		</div>

		<?php
		return ob_get_clean();
	}

	public function parse_details( $raw_details ) {
		$table = new Smartcrawl_Lighthouse_Tap_Targets_Table( array(
			esc_html__( 'Tap Target', 'wds' ),
			esc_html__( 'Size', 'wds' ),
			esc_html__( 'Overlapping Target', 'wds' ),
		), $this->get_report() );

		$items = smartcrawl_get_array_value( $raw_details, 'items' );
		foreach ( $items as $item ) {
			$tap_target_node_id = smartcrawl_get_array_value( $item, array( 'tapTarget', 'lhId' ) );
			$overlapping_node_id = smartcrawl_get_array_value( $item, array( 'overlappingTarget', 'lhId' ) );

			$table->add_row( array(
				smartcrawl_get_array_value( $item, array( 'tapTarget', 'snippet' ) ),
				smartcrawl_get_array_value( $item, 'size' ),
				smartcrawl_get_array_value( $item, array( 'overlappingTarget', 'snippet' ) ),
			), $tap_target_node_id, $overlapping_node_id );
		}

		return $table;
	}

	private function format_copy_description() {
		$parts = array(
			__( 'Tested Device: ', 'wds' ) . $this->get_device_label(),
			__( 'Audit Type: Responsive audits', 'wds' ),
			"",
			__( 'Failing Audit: Tap targets are not sized appropriately', 'wds' ),
			"",
			__( 'Status: Tap targets are not sized appropriately.', 'wds' ),
			__( 'Targets that are smaller than 48 px by 48 px or closer than 8 px apart fail the audit.', 'wds' ),
			"",
			__( 'Overview:', 'wds' ),
			__( 'Interactive elements like buttons and links should be large enough (48x48px), and have enough space around them (8px), to be easy enough to tap without overlapping onto other elements.', 'wds' ),
			__( 'Many search engines rank pages based on how mobile-friendly they are. Making sure tap targets are big enough and far enough apart from each other makes your page more mobile-friendly and accessible.', 'wds' ),
			"",
			__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'wds' ),
		);
		return implode( "\n", $parts );
	}
}
