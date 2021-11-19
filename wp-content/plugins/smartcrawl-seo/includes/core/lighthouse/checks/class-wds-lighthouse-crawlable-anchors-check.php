<?php

class Smartcrawl_Lighthouse_Crawlable_Anchors_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'crawlable-anchors';

	public function prepare() {
		$this->set_success_title( esc_html__( 'Links are crawlable', 'wds' ) );
		$this->set_failure_title( esc_html__( 'Links are not crawlable', 'wds' ) );
		$this->set_success_description( $this->format_success_description() );
		$this->set_failure_description( $this->format_failure_description() );
		$this->set_copy_description( $this->format_copy_description() );
	}

	private function print_common_description() {
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Overview', 'wds' ); ?></strong>
			<p><?php printf(
					esc_html__( "Google can follow links only if they are an %s. Links that use other formats won't be followed by Google's crawlers. Google cannot follow links without an href, or links created by script events.", 'wds' ),
					'<strong>' . esc_html__( '<a> tag with an href attribute', 'wds' ) . '</strong>'
				); ?>
			</p>
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
				'message' => esc_html__( "Way to go! It appears all your links are crawlable!", 'wds' ),
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
				'message' => esc_html__( "We've detected some of your links are not crawlable.", 'wds' ),
			) ); ?>

			<?php $this->print_details_table(); ?>
		</div>

		<div class="wds-lh-section">
			<p><?php esc_html_e( "Here are examples of links that Google can and can't follow:", 'wds' ); ?></p>

			<div class="wds-lh-highlight-container">
				<p>
					<strong class="wds-lh-green-word"><?php esc_html_e( 'Can follow:' ); ?></strong>
				</p>
				<div class="wds-lh-highlight wds-lh-highlight-success">
					<?php echo join( '', array(
						$this->tag( '<a ' ),
						$this->attr( 'href=' ),
						'"https://example.com"',
						$this->tag( '>' ),
					) ); ?><br/>

					<?php echo join( '', array(
						$this->tag( '<a ' ),
						$this->attr( 'href=' ),
						'"/relative/path/file"',
						$this->tag( '>' ),
					) ); ?>
				</div>

				<p>
					<strong class="wds-lh-red-word"><?php esc_html_e( "Can't follow:" ); ?></strong>
				</p>
				<div class="wds-lh-highlight wds-lh-highlight-error">
					<?php echo $this->tag( '<a>' ); ?><br/>
					<?php echo join( '', array(
						$this->tag( '<a ' ),
						$this->attr( 'routerLink=' ),
						'"some/path"',
						$this->tag( '>' ),
					) ) ?><br/>
					<?php echo join( '', array(
						$this->tag( '<span ' ),
						$this->attr( 'href=' ),
						'"https://example.com"',
						$this->tag( '>' ),
					) ) ?><br/>
					<?php echo join( '', array(
						$this->tag( '<span ' ),
						$this->attr( 'onclick=' ),
						'"goto(\'https://example.com\')"',
						$this->tag( '>' ),
					) ) ?><br/>
				</div>
			</div>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Link to resolvable URLs', 'wds' ); ?></strong>
			<p><?php esc_html_e( 'Ensure that the URL linked to by your <a> tag is an actual web address that Googlebot can send requests to, for example:', 'wds' ); ?></p>

			<div class="wds-lh-highlight-container">
				<p>
					<strong class="wds-lh-green-word"><?php esc_html_e( 'Can resolve:' ); ?></strong>
				</p>
				<div class="wds-lh-highlight wds-lh-highlight-success">
					<?php echo $this->tag( 'https://example.com/stuff' ); ?><br/>
					<?php echo $this->tag( '/products' ); ?><br/>
					<?php echo $this->tag( '/products.php?id=123' ); ?>
				</div>

				<p>
					<strong class="wds-lh-red-word"><?php esc_html_e( "Can't resolve:" ); ?></strong>
				</p>
				<div class="wds-lh-highlight wds-lh-highlight-error">
					<?php echo $this->tag( "javascript:goTo('products')" ); ?><br/>
					<?php echo $this->tag( "javascript:window.location.href='/products'" ); ?><br/>
					<?php echo $this->tag( '#' ); ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	function get_id() {
		return self::ID;
	}

	public function parse_details( $raw_details ) {
		$table = new Smartcrawl_Lighthouse_Table( array(
			esc_html__( 'Failing links', 'wds' ),
			esc_html__( 'Link text', 'wds' ) . $this->get_link_text_tooltip(),
		), $this->get_report() );

		$items = smartcrawl_get_array_value( $raw_details, 'items' );
		foreach ( $items as $item ) {
			$screenshot_node_id = smartcrawl_get_array_value( $item, array( 'node', 'lhId' ) );

			$table->add_row( array(
				smartcrawl_get_array_value( $item, array( 'node', 'snippet' ) ),
				smartcrawl_get_array_value( $item, array( 'node', 'nodeLabel' ) ),
			), $screenshot_node_id );
		}

		return $table;
	}

	private function get_link_text_tooltip() {
		ob_start();
		?>
		<span class="sui-tooltip sui-tooltip-constrained"
		      data-tooltip="<?php esc_html_e( 'To locate the Link text on your homepage, use the Find tool of your browser.', 'wds' ); ?>">
			<span class="sui-notice-icon sui-icon-info sui-sm" aria-hidden="true"></span>
		</span>
		<?php
		return ob_get_clean();
	}

	public function get_action_button() {
		return $this->edit_homepage_button();
	}

	private function format_copy_description() {
		$parts = array_merge( array(
			__( 'Tested Device: ', 'wds' ) . $this->get_device_label(),
			__( 'Audit Type: Indexing audits', 'wds' ),
			"",
			__( 'Failing Audit: Links are not crawlable', 'wds' ),
			"",
			__( "Status: We've detected some of your links are not crawlable.", 'wds' ),
			"",
		), $this->get_flattened_details(), array(
			"",
			__( 'Overview:', 'wds' ),
			__( "Google can follow links only if they are an <a> tag with an href attribute. Links that use other formats won't be followed by Google's crawlers. Google cannot follow links without an href, or links created by script events.", 'wds' ),
			"",
			__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'wds' ),
		) );

		return implode( "\n", $parts );
	}
}
