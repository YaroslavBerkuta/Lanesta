<?php

class Smartcrawl_Lighthouse_Link_Text_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'link-text';

	public function prepare() {
		$this->set_success_title( esc_html__( 'Links have descriptive text', 'wds' ) );
		$this->set_failure_title( esc_html__( 'Links do not have descriptive text', 'wds' ) );
		$this->set_success_description( $this->format_success_description() );
		$this->set_failure_description( $this->format_failure_description() );
		$this->set_copy_description( $this->format_copy_description() );
	}

	private function print_common_description() {
		?>
		<strong><?php esc_html_e( 'Overview', 'wds' ); ?></strong>
		<p><?php esc_html_e( 'Link text is the clickable word or phrase in a hyperlink. When link text clearly conveys a hyperlink\'s target, both users and search engines can more easily understand your content and how it relates to other pages.', 'wds' ); ?></p>
		<?php
	}

	private function format_success_description() {
		ob_start();
		?>
		<div class="wds-lh-section">
			<?php $this->print_common_description(); ?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'wds' ); ?></strong>
			<?php Smartcrawl_Simple_Renderer::render( 'notice', array(
				'class'   => 'sui-notice-success',
				'message' => esc_html__( "All your links have descriptive text, nice work.", 'wds' ),
			) ); ?>
		</div>
		<?php
		return ob_get_clean();
	}

	private function format_failure_description() {
		ob_start();
		?>
		<div class="wds-lh-section cf">
			<?php $this->print_common_description(); ?>

			<p><?php esc_html_e( 'Lighthouse flags the following generic link text:', 'wds' ); ?></p>
			<ul style="float: left; width: 30%; margin-bottom: 0;">
				<li><?php esc_html_e( 'click here', 'wds' ); ?></li>
				<li><?php esc_html_e( 'click this', 'wds' ); ?></li>
				<li><?php esc_html_e( 'go', 'wds' ); ?></li>
			</ul>
			<ul style="float: left; width: 30%; margin-bottom: 0;">
				<li><?php esc_html_e( 'here', 'wds' ); ?></li>
				<li><?php esc_html_e( 'this', 'wds' ); ?></li>
				<li><?php esc_html_e( 'start', 'wds' ); ?></li>
			</ul>
			<ul style="float: left; width: 30%; margin-bottom: 0;">
				<li><?php esc_html_e( 'right here', 'wds' ); ?></li>
				<li><?php esc_html_e( 'more', 'wds' ); ?></li>
				<li><?php esc_html_e( 'learn more', 'wds' ); ?></li>
			</ul>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'wds' ); ?></strong>
			<?php Smartcrawl_Simple_Renderer::render( 'notice', array(
				'class'   => 'sui-notice-warning',
				'message' => esc_html__( 'Some links are empty and without helpful descriptive text.', 'wds' ),
			) ); ?>

			<?php $this->print_details_table(); ?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'How to add descriptive link text', 'wds' ); ?></strong>
			<p>
				<?php esc_html_e( 'Replace generic phrases like "click here" and "learn more" with specific descriptions. In general, write link text that clearly indicates what type of content users will get if they follow the hyperlink.', 'wds' ); ?>
			</p>
		</div>

		<div class="wds-lh-toggle-container">
			<a class="wds-lh-toggle" href="#">
				<?php esc_html_e( 'Read More - Best practices' ); ?>
			</a>

			<div class="wds-lh-section">
				<strong><?php esc_html_e( 'Link text best practices', 'wds' ); ?></strong>
				<ul>
					<li><?php esc_html_e( "Stay on topic. Don't use link text that has no relation to the page's content.", 'wds' ); ?></li>
					<li><?php esc_html_e( "Don't use the page's URL as the link description unless you have a good reason to do so, such as referencing a site's new address.", 'wds' ); ?></li>
					<li><?php esc_html_e( 'Keep descriptions concise. Aim for a few words or a short phrase.', 'wds' ); ?></li>
					<li><?php esc_html_e( 'Pay attention to your internal links too. Improving the quality of internal links can help both users and search engines navigate your site more easily.', 'wds' ); ?></li>
				</ul>

				<div class="wds-lh-highlight-container">
					<p>
						<strong class="wds-lh-red-word"><?php esc_html_e( 'Don’t. ' ); ?></strong>
						<?php esc_html_e( '"Click here" doesn\'t convey where the hyperlink will take users.', 'wds' ); ?>
					</p>
					<div class="wds-lh-highlight wds-lh-highlight-error">
						<?php echo join( '', array(
							$this->tag( '<p>' ),
							esc_html__( 'To see all of our basketball videos, ', 'wds' ),
							$this->tag( '<a ' ),
							$this->attr( 'href=' ),
							$this->tag( '"videos.html">' ),
							esc_html__( 'click here', 'wds' ),
							$this->tag( '</a>.</p>' ),
						) ); ?>
					</div>

					<p>
						<strong class="wds-lh-green-word"><?php esc_html_e( 'Do. ' ); ?></strong>
						<?php esc_html_e( '"Basketball videos" clearly conveys that the hyperlink will take users to a page of videos.' ); ?>
					</p>
					<div class="wds-lh-highlight wds-lh-highlight-success">
						<?php echo join( '', array(
							$this->tag( '<p>' ),
							esc_html__( 'Check out all of our ', 'wds' ),
							$this->tag( '<a ' ),
							$this->attr( 'href=' ),
							$this->tag( '"videos.html">' ),
							esc_html__( 'basketball videos', 'wds' ),
							$this->tag( '</a>.</p>' ),
						) ); ?>
					</div>
				</div>

				<?php printf(
					esc_html__( "See the %s section of %s for more tips.", 'wds' ),
					sprintf(
						'<a target="%s" href="%s">%s</a>',
						"_blank",
						esc_url_raw( 'https://developers.google.com/search/docs/beginner/seo-starter-guide#use-links-wisely' ),
						esc_html__( 'Use links wisely', 'wds' )
					),
					sprintf(
						'<a target="%s" href="%s">%s</a>',
						"_blank",
						esc_url_raw( 'https://developers.google.com/search/docs/beginner/seo-starter-guide' ),
						esc_html__( "Google's Search Engine Optimization (SEO) Starter Guide", 'wds' )
					)
				);
				?>
			</div>
		</div>

		<?php
		return ob_get_clean();
	}

	function get_id() {
		return self::ID;
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

	public function parse_details( $raw_details ) {
		$table = new Smartcrawl_Lighthouse_Table( array(
			esc_html__( 'Link Text', 'wds' ) . $this->get_link_text_tooltip(),
			esc_html__( 'Link Destination', 'wds' ),
		), $this->get_report() );

		$items = smartcrawl_get_array_value( $raw_details, 'items' );
		foreach ( $items as $item ) {
			$table->add_row( array(
				smartcrawl_get_array_value( $item, 'text' ),
				smartcrawl_get_array_value( $item, 'href' ),
			) );
		}

		return $table;
	}

	public function get_action_button() {
		return $this->edit_homepage_button();
	}

	private function format_copy_description() {
		$parts = array_merge( array(
			__( 'Tested Device: ', 'wds' ) . $this->get_device_label(),
			__( 'Audit Type: Content audits', 'wds' ),
			"",
			__( 'Failing Audit: Links do not have descriptive text', 'wds' ),
			"",
			__( 'Status: Some links are empty and without helpful descriptive text.', 'wds' ),
			"",
		), $this->get_flattened_details(), array(
			"",
			__( 'Overview:', 'wds' ),
			__( "Link text is the clickable word or phrase in a hyperlink. When link text clearly conveys a hyperlink's target, both users and search engines can more easily understand your content and how it relates to other pages.", 'wds' ),
			__( 'Lighthouse flags the following generic link text: click here, click this, go,here,this,start,right here,more and learn more', 'wds' ),
			"",
			__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'wds' ),
		) );

		return implode( "\n", $parts );
	}
}
