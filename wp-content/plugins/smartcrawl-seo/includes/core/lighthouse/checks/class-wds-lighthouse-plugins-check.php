<?php

class Smartcrawl_Lighthouse_Plugins_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'plugins';

	public function prepare() {
		$this->set_success_title( esc_html__( "Document avoids browser plugins", 'wds' ) );
		$this->set_failure_title( esc_html__( 'Document uses browser plugins', 'wds' ) );
		$this->set_success_description( $this->format_success_description() );
		$this->set_failure_description( $this->format_failure_description() );
		$this->set_copy_description( $this->format_copy_description() );
	}

	private function print_common_description() {
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Overview', 'wds' ); ?></strong>
			<p><?php esc_html_e( "Search engines often can't index content that relies on browser plugins, such as Java or Flash. That means browser plugin-based content doesn't show up in search results.", 'wds' ); ?></p>
			<p><?php esc_html_e( "Also, most mobile devices don't support browser plugins, which creates frustrating experiences for mobile users.", 'wds' ); ?></p>
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
				'message' => esc_html__( 'Document avoids browser plugins - Google is loving it!', 'wds' ),
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
				'message' => esc_html__( 'Document uses browser plugins - Search Engines can’t index browser plugins.', 'wds' ),
			) ); ?>
			<?php $this->print_details_table(); ?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( "Don't use browser plugins to display your content", 'wds' ); ?></strong>
			<p>
				<?php echo smartcrawl_format_link(
					esc_html__( 'To convert browser plugin-based content to HTML, refer to guidance for that plugin. For example, MDN explains %s.', 'wds' ),
					'https://developer.mozilla.org/en-US/docs/Plugins/Flash_to_HTML5/Video',
					esc_html__( 'how to convert Flash video to HTML5 video', 'wds' ),
					'_blank'
				); ?>
			</p>
		</div>
		<?php
		return ob_get_clean();
	}

	function get_id() {
		return self::ID;
	}

	public function parse_details( $raw_details ) {
		$table = new Smartcrawl_Lighthouse_Table(
			array( esc_html__( 'Element source', 'wds' ) ),
			$this->get_report()
		);

		$items = smartcrawl_get_array_value( $raw_details, 'items' );
		foreach ( $items as $item ) {
			$screenshot_node_id = smartcrawl_get_array_value( $item, array( 'source', 'lhId' ) );

			$table->add_row( array(
				smartcrawl_get_array_value( $item, array( 'source', 'snippet' ) ),
			), $screenshot_node_id );
		}

		return $table;
	}

	private function format_copy_description() {
		$parts = array_merge( array(
			__( 'Tested Device: ', 'wds' ) . $this->get_device_label(),
			__( 'Audit Type: Indexing audits', 'wds' ),
			"",
			__( 'Failing Audit: Document uses browser plugins', 'wds' ),
			"",
			__( 'Status: Document uses browser plugins - Search Engines can’t index browser plugins.', 'wds' ),
			"",
		), $this->get_flattened_details(), array(
			"",
			__( 'Overview:', 'wds' ),
			__( "Search engines often can't index content that relies on browser plugins, such as Java or Flash. That means browser plugin-based content doesn't show up in search results.", 'wds' ),
			__( "Also, most mobile devices don't support browser plugins, which creates frustrating experiences for mobile users.", 'wds' ),
			"",
			__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'wds' ),
		) );

		return implode( "\n", $parts );
	}
}
