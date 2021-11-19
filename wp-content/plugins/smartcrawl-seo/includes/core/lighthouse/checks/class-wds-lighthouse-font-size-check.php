<?php

class Smartcrawl_Lighthouse_Font_Size_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'font-size';

	public function prepare() {
		$this->set_success_title( esc_html__( 'Document uses legible font sizes', 'wds' ) );
		$this->set_failure_title( esc_html__( "Document doesn't use legible font sizes", 'wds' ) );
		$this->set_success_description( $this->format_success_description() );
		$this->set_failure_description( $this->format_failure_description() );
		$this->set_copy_description( $this->format_copy_description() );
	}

	private function print_common_description() {
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Overview', 'wds' ); ?></strong>
			<p><?php esc_html_e( 'Many search engines rank pages based on how mobile-friendly they are. Font sizes smaller than 12px are often difficult to read on mobile devices and may require users to zoom in to display text at a comfortable reading size.', 'wds' ); ?></p>
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
				'message' => esc_html__( 'Document uses legible font sizes, nice work!', 'wds' ),
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
				'message' => esc_html__( "Document doesn't use legible font sizes.", 'wds' ),
			) ); ?>
		</div>

		<div class="wds-lh-section wds-lh-font-sizes-table">
			<p><?php esc_html_e( 'Lighthouse flags pages on which 60% or more of the text has a font size smaller than 12px.', 'wds' ); ?></p>
			<?php $this->print_details_table(); ?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'How to fix illegible fonts', 'wds' ); ?></strong>
			<p><?php esc_html_e( 'If Lighthouse reports Text is illegible because of a missing viewport config, add a <meta name="viewport" content="width=device-width, initial-scale=1"> tag to the <head> of your document.', 'wds' ); ?></p>
		</div>
		<?php
		return ob_get_clean();
	}

	function get_id() {
		return self::ID;
	}

	public function parse_details( $raw_details ) {
		$table = new Smartcrawl_Lighthouse_Table( array(
			esc_html__( 'Selector', 'wds' ),
			esc_html__( 'Font Size', 'wds' ),
			esc_html__( '% of Page Text', 'wds' ),
		), $this->get_report() );

		$items = smartcrawl_get_array_value( $raw_details, 'items' );
		foreach ( $items as $item ) {
			$table->add_row( array(
				smartcrawl_get_array_value( $item, array( 'selector', 'snippet' ) ),
				smartcrawl_get_array_value( $item, 'fontSize' ),
				smartcrawl_get_array_value( $item, 'coverage' ),
			) );
		}

		return $table;
	}

	private function format_copy_description() {
		$parts = array(
			__( 'Tested Device: ', 'wds' ) . $this->get_device_label(),
			__( 'Audit Type: Responsive audits', 'wds' ),
			"",
			__( "Failing Audit: Document doesn't use legible font sizes", 'wds' ),
			"",
			__( "Status: Document doesn't use legible font sizes.", 'wds' ),
			__( 'Lighthouse flags pages on which 60% or more of the text has a font size smaller than 12px.', 'wds' ),
			"",
			__( 'Overview:', 'wds' ),
			__( 'Many search engines rank pages based on how mobile-friendly they are. Font sizes smaller than 12px are often difficult to read on mobile devices and may require users to zoom in to display text at a comfortable reading size.', 'wds' ),
			"",
			__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'wds' ),
		);
		return implode( "\n", $parts );
	}
}
