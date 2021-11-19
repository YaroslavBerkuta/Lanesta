<?php $separators = empty( $separators ) ? array() : $separators; ?>
	<div class="sui-box-settings-row">
		<div class="sui-box-settings-col-1">
			<label for="separator" class="sui-settings-label"><?php esc_html_e( 'Separator', 'wds' ); ?></label>
			<p class="sui-description">
				<?php echo sprintf(
					esc_html__( 'The separator refers to the break between variables which you can use by referencing the %s tag. You can choose a preset one or bake your own.', 'wds' ),
					'%%sep%%'
				); ?>
			</p>
		</div>
		<div class="sui-box-settings-col-2">
			<div class="wds-preset-separators">
				<?php foreach ( $separators as $key => $separator ) : ?>
					<input
						type="radio"
						name="<?php echo esc_attr( $_view['option_name'] ); ?>[preset-separator]"
						id="separator-<?php echo esc_attr( $key ); ?>"
						value="<?php echo esc_attr( $key ); ?>"
						autocomplete="off"
						<?php echo $_view['options']['preset-separator'] === $key ? 'checked="checked"' : ''; ?> />
					<label class="separator-selector" for="separator-<?php echo esc_attr( $key ); ?>">
						<?php echo esc_html( $separator ); ?>
					</label>
				<?php endforeach; ?>
			</div>
			<p class="wds-custom-separator-message"><?php esc_html_e( 'Or, choose your own custom separator.', 'wds' ); ?></p>
			<input
				id='separator'
				placeholder="<?php esc_attr_e( 'Enter custom separator', 'wds' ); ?>"
				name='<?php echo esc_attr( $_view['option_name'] ); ?>[separator]'
				type='text'
				class='sui-form-control'
				value='<?php echo esc_attr( $_view['options']['separator'] ); ?>'/>
		</div>
	</div>

<div class="sui-box-settings-row wds-onpage-character-lengths">
	<div class="sui-box-settings-col-1">
		<label for="separator" class="sui-settings-label"><?php esc_html_e( 'Character Lengths', 'wds' ); ?></label>
		<p class="sui-description">
			<?php esc_html_e( 'By default we recommend best practice characters lengths for your  meta titles and descriptions. However, you can adjust these settings to suit your own requirements.', 'wds' ); ?>
		</p>
	</div>
	<div class="sui-box-settings-col-2">
		<?php
		$this->_render( 'onpage/onpage-meta-character-lengths', array(
			'label'          => esc_html__( 'Meta Title', 'wds' ),
			'toggle_name'    => 'custom_title_char_lengths',
			'min_field_name' => 'custom_title_min_length',
			'max_field_name' => 'custom_title_max_length',
			'default_min'    => SMARTCRAWL_TITLE_DEFAULT_MIN_LENGTH,
			'default_max'    => SMARTCRAWL_TITLE_DEFAULT_MAX_LENGTH,
		) );

		$this->_render( 'onpage/onpage-meta-character-lengths', array(
			'label'          => esc_html__( 'Meta Description', 'wds' ),
			'toggle_name'    => 'custom_metadesc_char_lengths',
			'min_field_name' => 'custom_metadesc_min_length',
			'max_field_name' => 'custom_metadesc_max_length',
			'default_min'    => SMARTCRAWL_METADESC_DEFAULT_MIN_LENGTH,
			'default_max'    => SMARTCRAWL_METADESC_DEFAULT_MAX_LENGTH,
		) );
		?>
	</div>
</div>
