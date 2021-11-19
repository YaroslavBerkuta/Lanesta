<?php
$options = empty( $options ) ? $_view['options'] : $options;
?>

<div class="sui-box-settings-row wds-separator-top">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label"><?php esc_html_e( 'Twitter Cards', 'wds' ); ?></label>
		<p class="sui-description"><?php esc_html_e( 'With Twitter Cards, you can attach rich photos, videos and media experiences to Tweets, helping to drive traffic to your website.', 'wds' ); ?></p>
	</div>

	<?php $twitter_card_enabled = $options['twitter-card-enable']; ?>
	<div class="sui-box-settings-col-2">
		<?php
		$this->_render( 'toggle-item', array(
			'item_label'            => esc_html__( 'Enable Twitter Cards', 'wds' ),
			'checked'               => $twitter_card_enabled,
			'field_name'            => $_view['option_name'] . '[twitter-card-enable]',
			'sub_settings_template' => 'social/social-twitter-cards-toggle-sub-settings',
			'sub_settings_border'   => false,
		) );
		?>
	</div>
</div>
