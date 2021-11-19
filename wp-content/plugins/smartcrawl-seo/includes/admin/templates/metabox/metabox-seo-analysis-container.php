<?php
$post = empty( $post ) ? null : $post;
$refresh_button_disabled = 'auto-draft' === get_post_status() ? 'disabled' : '';
if ( $post ) {
	$post_id = $post->ID;
} else {
	return;
}
$is_locale_english = Smartcrawl_Settings::is_locale_english();
?>

<div class="wds-metabox-section">
	<div class="wds-seo-analysis-container">
		<div class="wds-seo-analysis-label">
			<strong><?php esc_html_e( 'SEO Analysis', 'wds' ); ?></strong>

			<button <?php esc_attr( $refresh_button_disabled ); ?>
					class="sui-button sui-button-ghost wds-refresh-analysis wds-analysis-seo wds-disabled-during-request"
					type="button">
			<span class="sui-loading-text">
				<span class="sui-icon-update" aria-hidden="true"></span>

				<?php esc_html_e( 'Refresh', 'wds' ); ?>
			</span>

				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>
		</div>

		<div class="sui-box-body">
			<?php
			if ( ! $is_locale_english ) {
				$this->_render( 'dismissable-notice', array(
					'key'     => 'metabox-seo-analysis-unsupported-locale',
					'message' => esc_html__( 'SEO Analysis only supports English content. Therefore, some analysis items may report incorrectly and should be ignored.', 'wds' ),
					'class'   => 'info',
				) );
			}

			$this->_render( 'mascot-message', array(
				'key'     => 'metabox-seo-analysis',
				'message' => esc_html__( 'This tool helps you optimize your content to give it the best chance of being found in search engines when people are looking for it. Start by choosing a few focus keywords that best describe your article, then SmartCrawl will give you recommendations to make sure your content is highly optimized.', 'wds' ),
			) );
			?>
		</div>

		<?php if ( apply_filters( 'wds-metabox-visible_parts-focus_area', true ) ) : ?>
			<div class="wds-focus-keyword sui-border-frame sui-form-field">
				<label class="sui-label" for='wds_focus'>
					<?php esc_html_e( 'Focus keyword', 'wds' ); ?>
					<span>
						<?php esc_html_e( '- Choose a single word, phrase or part of a sentence that people will likely search for. You can also use multiple keywords separated by commas.', 'wds' ); ?>
						<span class="sui-tooltip sui-tooltip-constrained"
						      style="--tooltip-width: 250px;"
						      data-tooltip="<?php esc_html_e( 'As a general rule, using only one keyword per page/post is recommended.', 'wds' ); ?>">
							<span class="sui-icon-info">
							</span>
						</span>
					</span>
				</label>
				<input type='text'
				       id='wds_focus'
				       name='wds_focus'
				       value='<?php echo esc_html( smartcrawl_get_value( 'focus-keywords', $post_id ) ); ?>'
				       class='wds-disabled-during-request sui-form-control'
				       placeholder="<?php esc_html_e( 'E.g. broken iphone screen', 'wds' ); ?>"/>
			</div>
		<?php endif; ?>

		<?php do_action( 'wds-editor-metabox-seo-analysis', $post ); ?>
	</div>
</div>
