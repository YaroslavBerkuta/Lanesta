<?php

class Smartcrawl_Lighthouse_Meta_Description_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'meta-description';

	public function prepare() {
		$this->set_success_title( esc_html__( 'Document has a meta description', 'wds' ) );
		$this->set_failure_title( esc_html__( 'Document does not have a meta description', 'wds' ) );
		$this->set_success_description( $this->format_success_description() );
		$this->set_failure_description( $this->format_failure_description() );
		$this->set_copy_description( $this->format_copy_description() );
	}

	private function print_common_description() {
		?>
		<strong><?php esc_html_e( 'Overview', 'wds' ); ?></strong>
		<p>
			<?php printf(
				esc_html__( "The %s element provides a summary of a page's content that search engines include in search results. A high-quality, unique meta description makes your page appear more relevant and can increase your search traffic.", 'wds' ),
				'<strong>' . esc_html( '<meta name="description">' ) . '</strong>'
			); ?>
		</p>
		<?php
	}

	private function format_success_description() {
		ob_start(); ?>

		<div class="wds-lh-section">
			<?php $this->print_common_description(); ?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'wds' ); ?></strong>
			<?php Smartcrawl_Simple_Renderer::render( 'notice', array(
				'class'   => 'sui-notice-success',
				'message' => esc_html__( 'Your homepage has a meta description, well done!', 'wds' ),
			) ); ?>
		</div>

		<?php
		return ob_get_clean();
	}

	private function format_failure_description() {
		ob_start(); ?>

		<div class="wds-lh-section">
			<?php $this->print_common_description(); ?>
			<p>
				<?php esc_html_e( 'The audit fails if:', 'wds' ); ?>
			</p>

			<ul>
				<li>
					<?php printf(
						esc_html__( "If your page doesn't have a %s element.", 'wds' ),
						'<strong>' . esc_html( '<meta name="description">' ) . '</strong>'
					); ?>
				</li>
				<li>
					<?php printf(
						esc_html__( "The %s attribute of the %s element is empty.", 'wds' ),
						'<strong>' . esc_html__( 'content', 'wds' ) . '</strong>',
						'<strong>' . esc_html( '<meta name="description">' ) . '</strong>'
					); ?>
				</li>
			</ul>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'wds' ); ?></strong>
			<?php Smartcrawl_Simple_Renderer::render( 'notice', array(
				'class'   => 'sui-notice-warning',
				'message' => esc_html__( "We couldn't find a meta description tag on your homepage.", 'wds' ),
			) ); ?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'How to add a meta description', 'wds' ); ?></strong>
			<p>
				<?php printf(
					esc_html__( "Open the %s editor and add a meta description (and title) for your homepage. While you're there, set up your default format for all other post types to ensure you always have a good quality <meta name=description> output.", 'wds' ),
					'<strong>' . esc_html__( 'Titles & Meta', 'wds' ) . '</strong>'
				); ?>
			</p>
		</div>

		<div class="wds-lh-toggle-container">
			<a class="wds-lh-toggle" href="#">
				<?php esc_html_e( 'Read More - Best practices' ); ?>
			</a>

			<div class="wds-lh-section">
				<strong><?php esc_html_e( 'Meta description best practices', 'wds' ); ?></strong>
				<ul>
					<li><?php esc_html_e( 'Use a unique description for each page.', 'wds' ); ?></li>
					<li><?php esc_html_e( 'Make descriptions relevant and concise. Avoid vague descriptions like "Home page”.', 'wds' ); ?></li>
					<li>
						<?php echo smartcrawl_format_link(
							esc_html__( "Avoid %s. It doesn't help users, and search engines may mark the page as spam.", 'wds' ),
							'https://developers.google.com/search/docs/advanced/guidelines/irrelevant-keywords',
							esc_html__( 'keyword stuffing', 'wds' ),
							'_blank'
						); ?>
					</li>
					<li><?php esc_html_e( "Descriptions don't have to be complete sentences; they can contain structured data.", 'wds' ); ?></li>
				</ul>

				<div class="wds-lh-highlight-container">
					<p>
						<strong class="wds-lh-red-word"><?php esc_html_e( 'Don’t. ' ); ?></strong>
						<?php esc_html_e( 'Too vague.' ); ?>
					</p>
					<div class="wds-lh-highlight wds-lh-highlight-error">
						<?php echo join( '', array(
							$this->tag( '<meta ' ),
							$this->attr( 'name="' ),
							'description',
							$this->attr( '" ' ),
							$this->attr( 'content="' ),
							esc_html__( 'Donut recipe', 'wds' ),
							$this->attr( '"' ),
							$this->tag( '/>' ),
						) ); ?>
					</div>

					<p>
						<strong class="wds-lh-green-word"><?php esc_html_e( 'Do. ' ); ?></strong>
						<?php esc_html_e( 'Descriptive yet concise.' ); ?>
					</p>
					<div class="wds-lh-highlight wds-lh-highlight-success">
						<?php echo join( '', array(
							$this->tag( '<meta ' ),
							$this->attr( 'name="' ),
							'description',
							$this->attr( '" ' ),
							$this->attr( 'content="' ),
							esc_html__( "Mary's simple recipe for maple bacon donuts makes a sticky, sweet treat with just a hint of salt that you'll keep coming back for.", 'wds' ),
							$this->attr( '"' ),
							$this->tag( '/>' ),
						) ); ?>
					</div>
				</div>

				<p>
					<?php echo smartcrawl_format_link(
						esc_html__( "See Google's %s page for more details about these tips.", 'wds' ),
						'https://developers.google.com/search/docs/advanced/appearance/good-titles-snippets',
						esc_html__( 'Create good titles and snippets in Search Results', 'wds' ),
						'_blank'
					); ?>
				</p>
			</div>
		</div>

		<?php
		return ob_get_clean();
	}

	public function get_action_button() {
		if ( ! Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_ONPAGE ) ) {
			return '';
		}

		return $this->button_markup(
			esc_html__( 'Add Description', 'wds' ), Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_ONPAGE ), 'sui-icon-plus'
		);
	}

	function get_id() {
		return self::ID;
	}

	private function format_copy_description() {
		$parts = array(
			__( 'Tested Device: ', 'wds' ) . $this->get_device_label(),
			__( 'Audit Type: Content audits', 'wds' ),
			"",
			__( 'Failing Audit: Document does not have a meta description', 'wds' ),
			"",
			__( "Status: We couldn't find a meta description tag on your homepage.", 'wds' ),
			"",
			__( 'Overview:', 'wds' ),
			__( 'The <meta name="description"> element provides a summary of a page\'s content that search engines include in search results. A high-quality, unique meta description makes your page appear more relevant and can increase your search traffic.', 'wds' ),
			__( 'The audit fails if:', 'wds' ),
			__( '- If your page doesn\'t have a <meta name="description"> element.', 'wds' ),
			__( '- The content attribute of the <meta name="description"> element is empty.', 'wds' ),
			"",
			__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'wds' ),
		);

		return implode( "\n", $parts );
	}
}
