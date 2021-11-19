<?php
$title_key = empty( $title_key ) ? '' : $title_key;
$description_key = empty( $description_key ) ? '' : $description_key;
$title_label_desc = empty( $title_label_desc ) ? '' : $title_label_desc;
$title_field_desc = empty( $title_field_desc ) ? '' : $title_field_desc;
$meta_label_desc = empty( $meta_label_desc ) ? '' : $meta_label_desc;
$meta_field_desc = empty( $meta_field_desc ) ? '' : $meta_field_desc;
$title = empty( $title ) ? '' : $title;
$title_placeholder = empty( $title_placeholder ) ? '' : $title_placeholder;
$description = empty( $description ) ? '' : $description;
$description_placeholder = empty( $description_placeholder ) ? '' : $description_placeholder;

$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$option_name_format = $option_name . '[%s]';
$macros = empty( $macros ) ? array() : $macros;
?>

<?php if ( $title_key ): ?>
	<div class="sui-box-settings-row wds-title-row">
		<div class="sui-box-settings-col-1">
			<label for="<?php echo esc_attr( $title_key ); ?>"
			       class="sui-settings-label"><?php esc_html_e( 'Title', 'wds' ); ?></label>
			<span class="sui-description"><?php echo esc_html( $title_label_desc ); ?></span>
		</div>
		<div class="sui-box-settings-col-2">
			<label class="sui-label" for="<?php echo esc_attr( sprintf( $option_name_format, $title_key ) ); ?>">
				<?php esc_html_e( 'SEO Title', 'wds' ); ?>
				<span><?php echo esc_html( sprintf( __( '- Minimum of %d characters, max %d.', 'wds' ), smartcrawl_title_min_length(), smartcrawl_title_max_length() ) ); ?></span>
			</label>

			<div class="sui-insert-variables wds-allow-macros">
				<input id="<?php echo esc_attr( $title_key ); ?>"
				       name="<?php echo esc_attr( sprintf( $option_name_format, $title_key ) ); ?>"
				       type="text" class="sui-form-control"
				       placeholder="<?php echo esc_attr( $title_placeholder ); ?>"
				       value="<?php echo esc_attr( $title ); ?>">
				<?php $this->_render( 'macros-dropdown', array( 'macros' => $macros ) ); ?>
			</div>

			<span class="sui-description"><?php echo esc_html( $title_field_desc ); ?></span>
		</div>
	</div>
<?php endif; ?>

<?php if ( $description_key ): ?>
	<div class="sui-box-settings-row wds-description-row">
		<div class="sui-box-settings-col-1">
			<label for="<?php echo esc_attr( $description_key ); ?>"
			       class="sui-settings-label"><?php esc_html_e( 'Description', 'wds' ); ?></label>
			<span class="sui-description"><?php echo esc_html( $meta_label_desc ); ?></span>
		</div>
		<div class="sui-box-settings-col-2">
			<label class="sui-label" for="<?php echo esc_attr( sprintf( $option_name_format, $title_key ) ); ?>">
				<?php esc_html_e( 'Description', 'wds' ); ?>
				<span><?php echo esc_html( sprintf( __( '- Minimum of %d characters, max %d.', 'wds' ), smartcrawl_metadesc_min_length(), smartcrawl_metadesc_max_length() ) ); ?></span>
			</label>

			<div class="sui-insert-variables wds-allow-macros">
				<textarea id="<?php echo esc_attr( $description_key ); ?>"
				          name="<?php echo esc_attr( sprintf( $option_name_format, $description_key ) ); ?>"
				          type="text"
				          placeholder="<?php echo esc_attr( $description_placeholder ); ?>"
				          class="sui-form-control"><?php echo esc_textarea( $description ); ?></textarea>
				<?php $this->_render( 'macros-dropdown', array( 'macros' => $macros ) ); ?>
			</div>

			<span class="sui-description"><?php echo esc_html( $meta_field_desc ); ?></span>
		</div>
	</div>
<?php endif; ?>
