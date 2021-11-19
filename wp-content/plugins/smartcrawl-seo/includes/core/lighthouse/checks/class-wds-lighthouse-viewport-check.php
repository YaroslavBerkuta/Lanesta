<?php

class Smartcrawl_Lighthouse_Viewport_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'viewport';

	public function prepare() {
		$this->set_success_title( esc_html__( 'Has a <meta name="viewport"> tag with width or initial-scale', 'wds' ) );
		$this->set_failure_title( esc_html__( 'Does not have a <meta name="viewport"> tag with width or initial-scale', 'wds' ) );
		$this->set_success_description( $this->format_success_description() );
		$this->set_failure_description( $this->format_failure_description() );
		$this->set_copy_description( $this->format_copy_description() );
	}

	public function print_common_description() {
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Overview', 'wds' ); ?></strong>
			<p><?php esc_html_e( 'Many search engines rank pages based on how mobile-friendly they are. Without a viewport meta tag, mobile devices render pages at typical desktop screen widths and then scale the pages down, making them difficult to read.', 'wds' ); ?></p>
			<p><?php echo smartcrawl_format_link(
					esc_html__( "Setting the %s lets you control the width and scaling of the viewport so that it's sized correctly on all devices.", 'wds' ),
					'https://developer.mozilla.org/en-US/docs/Mozilla/Mobile/Viewport_meta_tag',
					esc_html__( 'viewport meta tag', 'wds' ),
					'_blank'
				); ?></p>
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
				'message' => sprintf(
					esc_html__( 'Has a %s tag with %s or %s', 'wds' ),
					'<strong>' . esc_html( '<meta name="viewport">' ) . '</strong>',
					'<strong>width</strong>',
					'<strong>initial-scale</strong>'
				),
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
				'message' => sprintf(
					esc_html__( "We couldn't find any %s.", 'wds' ),
					'<strong>' . esc_html__( 'viewport metatag', 'wds' ) . '</strong>'
				),
			) ); ?>
		</div>

		<div class="wds-lh-section">
			<p><?php esc_html_e( 'A page fails the audit unless all of these conditions are met:', 'wds' ); ?></p>
			<ul>
				<li><?php esc_html_e( "The document's <head> contains a <meta name=\"viewport\"> tag.", 'wds' ); ?></li>
				<li><?php esc_html_e( 'The viewport meta tag contains a content attribute.', 'wds' ); ?></li>
				<li><?php esc_html_e( "The content attribute's value includes the text width=.", 'wds' ); ?></li>
			</ul>

			<p><?php esc_html_e( "Lighthouse doesn't check that width equals device-width. It also doesn't check for an initial-scale key-valuepair. However, you still need to include both for your page to render correctly on mobile devices.", 'wds' ); ?></p>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'How to add a viewport meta tag', 'wds' ); ?></strong>
			<p><?php esc_html_e( 'Add a viewport <meta> tag with the appropriate key-value pairs to the <head> of your page:', 'wds' ); ?></p>

			<div class="wds-lh-highlight">
				<?php echo $this->tag( '<!DOCTYPE html>' ); ?><br/>
				<?php echo join( '', array(
					$this->tag( '<html ' ),
					$this->attr( 'lang="en"' ),
					$this->tag( '>' ),
				) ); ?><br/>
				&nbsp;&nbsp;<?php echo $this->tag( '<head>' ); ?><br/>
				&nbsp;&nbsp;&nbsp;&nbsp;…<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;<?php echo join( '', array(
					$this->tag( '<meta ' ),
					$this->attr( 'name=' ),
					'"viewport" ',
					$this->attr( 'content=' ),
					'"width=device-width, initial-scale=1"',
					$this->tag( '>' ),
				) ); ?><br/>
				&nbsp;&nbsp;&nbsp;&nbsp;…<br/>
				&nbsp;&nbsp;<?php echo $this->tag( '</head>' ); ?><br/>
				&nbsp;&nbsp;…<br/>
			</div>

			<p><?php esc_html_e( "Here's what each key-value pair does:", 'wds' ); ?></p>
			<ul>
				<li><?php esc_html_e( 'width=device-width sets the width of the viewport to the width of the device.', 'wds' ); ?></li>
				<li><?php esc_html_e( 'initial-scale=1 sets the initial zoom level when the user visits the page.', 'wds' ); ?></li>
			</ul>

			<?php Smartcrawl_Simple_Renderer::render( 'notice', array(
				'class'   => 'sui-notice-grey',
				'message' => sprintf(
					esc_html__( "This audit should be fixed by your theme developer. Click the %s button below to save and send them the required info.", 'wds' ),
					'<strong>' . esc_html__( 'Copy Audit', 'wds' ) . '</strong>'
				),
			) ); ?>
		</div>
		<?php
		return ob_get_clean();
	}

	function get_id() {
		return self::ID;
	}

	private function format_copy_description() {
		$parts = array(
			__( 'Tested Device: ', 'wds' ) . $this->get_device_label(),
			__( 'Audit Type: Responsive audits', 'wds' ),
			"",
			__( 'Failing Audit: Does not have a <meta name="viewport"> tag with width or initial-scale', 'wds' ),
			"",
			__( "Status: We couldn't find any viewport metatag.", 'wds' ),
			"",
			__( 'Overview:', 'wds' ),
			__( 'Many search engines rank pages based on how mobile-friendly they are. Without a viewport meta tag, mobile devices render pages at typical desktop screen widths and then scale the pages down, making them difficult to read.', 'wds' ),
			__( "Setting the viewport meta tag lets you control the width and scaling of the viewport so that it's sized correctly on all devices.", 'wds' ),
			"",
			__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'wds' ),
		);
		return implode( "\n", $parts );
	}
}
