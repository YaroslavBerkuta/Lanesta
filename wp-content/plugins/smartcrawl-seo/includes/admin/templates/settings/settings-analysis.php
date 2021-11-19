<?php
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$strong = '<strong>%s</strong>';
$analysis_strategy = Smartcrawl_Controller_Analysis_Content::get()->get_analysis_strategy();
$is_strategy_strict = $analysis_strategy === Smartcrawl_Controller_Analysis_Content::STRATEGY_STRICT;
$is_strategy_moderate = $analysis_strategy === Smartcrawl_Controller_Analysis_Content::STRATEGY_MODERATE;
$is_strategy_manual = $analysis_strategy === Smartcrawl_Controller_Analysis_Content::STRATEGY_MANUAL;
$is_strategy_loose = $analysis_strategy === Smartcrawl_Controller_Analysis_Content::STRATEGY_LOOSE;
?>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label"><?php esc_html_e( 'In-Post Analysis', 'wds' ); ?></label>
		<p class="sui-description">
			<?php esc_html_e( 'These modules appear inside the WordPress Post Editor and provide per-page SEO and Readability analysis to fine tune each post to focus keywords.', 'wds' ); ?>
		</p>
	</div>

	<div class="sui-box-settings-col-2">
		<label class="sui-settings-label"><?php esc_html_e( 'Visibility', 'wds' ); ?></label>
		<p class="sui-description"><?php esc_html_e( 'SEO Analysis benchmarks your content against recommend SEO practice and gives recommendations for improvement to make sure content is as optimized as possible. Readibility Analysis uses the Flesch-Kincaid test to determine how easy your content is to read.', 'wds' ); ?></p>
		<?php
		$this->_render( 'toggle-item', array(
			'item_value' => 'analysis-seo',
			'field_name' => "{$option_name}[analysis-seo]",
			'field_id'   => 'analysis-seo',
			'checked'    => ! empty( $_view['options']['analysis-seo'] ),
			'item_label' => esc_html__( 'Page Analysis', 'wds' ),
		) );
		?>
		<?php
		$this->_render( 'toggle-item', array(
			'item_value' => 'analysis-readability',
			'field_name' => "{$option_name}[analysis-readability]",
			'field_id'   => 'analysis-readability',
			'checked'    => ! empty( $_view['options']['analysis-readability'] ),
			'item_label' => esc_html__( 'Readability Analysis', 'wds' ),
		) );
		?>

		<label class="sui-settings-label"><?php esc_html_e( 'Engine', 'wds' ); ?></label>
		<p class="sui-description"><?php esc_html_e( 'Choose how you want SmartCrawl to analyze your content.', 'wds' ); ?></p>
		<p class="sui-description">
			<?php printf(
				esc_html__( '%s is recommended for most websites as it only reviews the_content() output.', 'wds' ),
				sprintf( $strong, esc_html__( 'Content', 'wds' ) )
			); ?>
		</p>
		<p class="sui-description">
			<?php printf(
				esc_html__( '%s includes everything, except for your header, nav, footer and sidebars. This can be helpful for page builders and themes with custom output.', 'wds' ),
				sprintf( $strong, esc_html__( 'Wide', 'wds' ) )
			); ?>
		</p>
		<p class="sui-description"><?php printf(
				esc_html__( '%s checks your entire page’s content including elements like nav and footer. Due to analysing everything you might miss key analysis of your real content so we don’t recommend this approach.', 'wds' ),
				sprintf( $strong, esc_html__( 'All', 'wds' ) )
			); ?>
		</p>
		<p class="sui-description"><?php printf(
				esc_html__( '%s only analyzes content you tell it to programmatically. If you have a fully custom setup, this is the option for you. Read the documentation.', 'wds' ),
				sprintf( $strong, esc_html__( 'None', 'wds' ) )
			); ?>
		</p>

		<div class="wds-analysis-strategy-tabs sui-side-tabs sui-tabs">
			<div class="sui-tabs-menu">
				<label class="wds-strategy-strict sui-tab-item <?php echo $is_strategy_strict ? 'active' : ''; ?>">
					<?php esc_html_e( 'Content', 'wds' ); ?>
					<input name="<?php echo esc_attr( $option_name ); ?>[analysis_strategy]"
					       value="<?php echo esc_attr( Smartcrawl_Controller_Analysis_Content::STRATEGY_STRICT ); ?>"
					       type="radio" <?php checked( $is_strategy_strict ); ?>
					       class="hidden"/>
				</label>
				<label class="wds-strategy-moderate sui-tab-item <?php echo $is_strategy_moderate ? 'active' : ''; ?>">
					<?php esc_html_e( 'Wide', 'wds' ); ?>
					<input name="<?php echo esc_attr( $option_name ); ?>[analysis_strategy]"
					       value="<?php echo esc_attr( Smartcrawl_Controller_Analysis_Content::STRATEGY_MODERATE ); ?>"
					       type="radio" <?php checked( $is_strategy_moderate ); ?>
					       class="hidden"/>
				</label>
				<label class="wds-strategy-loose sui-tab-item <?php echo $is_strategy_loose ? 'active' : ''; ?>">
					<?php esc_html_e( 'All', 'wds' ); ?>
					<input name="<?php echo esc_attr( $option_name ); ?>[analysis_strategy]"
					       value="<?php echo esc_attr( Smartcrawl_Controller_Analysis_Content::STRATEGY_LOOSE ); ?>"
					       type="radio" <?php checked( $is_strategy_loose ); ?>
					       class="hidden"/>
				</label>
				<label class="wds-strategy-manual sui-tab-item <?php echo $is_strategy_manual ? 'active' : ''; ?>">
					<?php esc_html_e( 'None', 'wds' ); ?>
					<input name="<?php echo esc_attr( $option_name ); ?>[analysis_strategy]"
					       value="<?php echo esc_attr( Smartcrawl_Controller_Analysis_Content::STRATEGY_MANUAL ); ?>"
					       type="radio" <?php checked( $is_strategy_manual ); ?>
					       class="hidden"/>
				</label>
			</div>
		</div>

		<?php $this->_render( 'notice', array(
			'message' => sprintf(
				esc_html__( 'Custom setup? Choose the %s method and add the class %s to container elements you want to include in the SEO and Readability Analysis.', 'wds' ),
				'<strong>' . esc_html__( 'None', 'wds' ) . '</strong>',
				'<strong>' . esc_html( '.smartcrawl-checkup-included' ) . '</strong>'
			),
			'class'   => 'grey',
		) ); ?>
	</div>
</div>
