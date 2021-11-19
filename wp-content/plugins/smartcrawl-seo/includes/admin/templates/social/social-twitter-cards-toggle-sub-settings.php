<?php
$options = empty( $options ) ? $_view['options'] : $options;
$card_type = smartcrawl_get_array_value( $options, 'twitter-card-type' );
$card_type_summary = Smartcrawl_Twitter_Printer::CARD_SUMMARY === $card_type;
$card_type_image = empty( $card_type ) // Image card used by default in twitter printer
                   || Smartcrawl_Twitter_Printer::CARD_IMAGE === $card_type;
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
?>
<div>
	<p></p>

	<div class="sui-side-tabs sui-tabs">
		<div data-tabs>
			<label class="<?php echo $card_type_image ? 'active' : ''; ?>">

				<?php esc_html_e( 'Image', 'wds' ); ?>
				<input name="<?php echo esc_attr( $option_name ); ?>[twitter-card-type]"
				       value="<?php echo esc_attr( Smartcrawl_Twitter_Printer::CARD_IMAGE ); ?>"
				       type="radio" <?php checked( $card_type_image ); ?>
				       class="hidden"
				/>
			</label>

			<label class="<?php echo $card_type_summary ? 'active' : ''; ?>">

				<?php esc_html_e( 'No Image', 'wds' ); ?>
				<input name="<?php echo esc_attr( $option_name ); ?>[twitter-card-type]"
				       value="<?php echo esc_attr( Smartcrawl_Twitter_Printer::CARD_SUMMARY ); ?>"
				       type="radio" <?php checked( $card_type_summary ); ?>
				       class="hidden"
				/>
			</label>
		</div>

		<div data-panes>
			<div class="<?php echo $card_type_image ? 'active' : ''; ?>">
				<?php
				$this->_render( 'social/social-twitter-embed', array(
					'tweet_url' => 'https://twitter.com/NatGeo/status/1087380060473049091',
					'large'     => true,
				) );
				?>
			</div>

			<div class="<?php echo $card_type_summary ? 'active' : ''; ?>">
				<?php
				$this->_render( 'social/social-twitter-embed', array(
					'tweet_url' => 'https://twitter.com/WordPress/status/1046731890244374528',
				) );
				?>
			</div>
		</div>
	</div>
	<p class="sui-description"><?php esc_html_e( 'A preview of how your Homepage will appear as a Twitter Card.', 'wds' ); ?></p>
</div>
