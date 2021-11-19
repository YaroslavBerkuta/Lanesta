<?php
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$social_options = empty( $social_options ) ? array() : $social_options;
?>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label">
			<?php esc_html_e( 'Social Accounts', 'wds' ); ?>
		</label>
		<p class="sui-description">
			<?php esc_html_e( "Add all your social accounts so search engines know which profiles to attribute your web content to. Specify as many accounts as you can.", 'wds' ); ?>
		</p>
	</div>

	<div class="sui-box-settings-col-2">
		<div class="sui-border-frame">
			<div class="sui-form-field">
				<label for="twitter_username"
				       class="sui-label"><?php esc_html_e( 'Twitter Username', 'wds' ); ?></label>

				<div class="sui-control-with-icon">
					<span class="sui-icon-social-twitter" aria-hidden="true"></span>
					<input type="text" id="twitter_username" class="sui-form-control"
					       name="<?php echo esc_attr( $option_name ); ?>[twitter_username]"
					       value="<?php echo (string) smartcrawl_get_array_value( $social_options, 'twitter_username' ); ?>"
					       placeholder="<?php echo esc_attr( 'username' ); ?>">
				</div>
			</div>

			<div class="sui-form-field">
				<label for="fb-app-id" class="sui-label"><?php esc_html_e( 'Facebook App ID', 'wds' ); ?></label>

				<div class="sui-control-with-icon">
					<span class="sui-icon-social-facebook" aria-hidden="true"></span>
					<input type="text" id="fb-app-id" name="<?php echo esc_attr( $option_name ); ?>[fb-app-id]"
					       class="sui-form-control"
					       value="<?php echo (string) smartcrawl_get_array_value( $social_options, 'fb-app-id' ); ?>"
					       placeholder="<?php echo esc_attr( 'App ID' ); ?>">
				</div>
			</div>

			<div class="sui-form-field">
				<label for="facebook_url" class="sui-label"><?php esc_html_e( 'Facebook Page URL', 'wds' ); ?></label>

				<div class="sui-control-with-icon">
					<span class="sui-icon-social-facebook" aria-hidden="true"></span>
					<input type="text" id="facebook_url" name="<?php echo esc_attr( $option_name ); ?>[facebook_url]"
					       class="sui-form-control"
					       value="<?php echo (string) smartcrawl_get_array_value( $social_options, 'facebook_url' ); ?>"
					       placeholder="<?php echo esc_attr( 'https://facebook.com/pagename' ); ?>">
				</div>
			</div>

			<div class="sui-form-field">
				<label for="instagram_url" class="sui-label"><?php esc_html_e( 'Instagram URL', 'wds' ); ?></label>

				<div class="sui-control-with-icon">
					<span class="sui-icon-instagram" aria-hidden="true"></span>
					<input type="text" id="instagram_url" name="<?php echo esc_attr( $option_name ); ?>[instagram_url]"
					       class="sui-form-control"
					       value="<?php echo (string) smartcrawl_get_array_value( $social_options, 'instagram_url' ); ?>"
					       placeholder="<?php echo esc_attr( 'https://instagram.com/username' ); ?>">
				</div>
			</div>

			<div class="sui-form-field">
				<label for="linkedin_url" class="sui-label"><?php esc_html_e( 'Linkedin URL', 'wds' ); ?></label>

				<div class="sui-control-with-icon">
					<span class="sui-icon-social-linkedin" aria-hidden="true"></span>
					<input type="text" id="linkedin_url" name="<?php echo esc_attr( $option_name ); ?>[linkedin_url]"
					       class="sui-form-control"
					       value="<?php echo (string) smartcrawl_get_array_value( $social_options, 'linkedin_url' ); ?>"
					       placeholder="<?php echo esc_attr( 'https://linkedin.com/username' ); ?>">
				</div>
			</div>

			<div class="sui-form-field">
				<label for="pinterest_url" class="sui-label"><?php esc_html_e( 'Pinterest URL', 'wds' ); ?></label>

				<div class="sui-control-with-icon">
					<span class="sui-icon-pin" aria-hidden="true"></span>
					<input type="text" id="pinterest_url" name="<?php echo esc_attr( $option_name ); ?>[pinterest_url]"
					       class="sui-form-control"
					       value="<?php echo (string) smartcrawl_get_array_value( $social_options, 'pinterest_url' ); ?>"
					       placeholder="<?php echo esc_attr( 'https://pinterest.com/username' ); ?>">
				</div>
			</div>

			<div class="sui-form-field">
				<label for="youtube_url" class="sui-label"><?php esc_html_e( 'Youtube URL', 'wds' ); ?></label>

				<div class="sui-control-with-icon">
					<span class="sui-icon-social-youtube" aria-hidden="true"></span>
					<input type="text" id="youtube_url" name="<?php echo esc_attr( $option_name ); ?>[youtube_url]"
					       class="sui-form-control"
					       value="<?php echo (string) smartcrawl_get_array_value( $social_options, 'youtube_url' ); ?>"
					       placeholder="<?php echo esc_attr( 'https://www.youtube.com/user/username' ); ?>">
				</div>
			</div>
		</div>
	</div>
</div>
