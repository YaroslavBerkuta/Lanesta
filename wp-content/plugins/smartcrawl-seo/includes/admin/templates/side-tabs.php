<?php
$id = empty( $id ) ? '' : $id;
$name = empty( $name ) ? '' : $name;
$value = empty( $value ) ? '' : $value;
$tabs = empty( $tabs ) || ! is_array( $tabs ) ? array() : $tabs;
?>

<div id="<?php echo esc_attr( $id ); ?>"
     class="sui-side-tabs sui-tabs">

	<div data-tabs>
		<?php foreach ( $tabs as $tab ): ?>
			<?php
			$tab_value = smartcrawl_get_array_value( $tab, 'value' );
			$tab_label = smartcrawl_get_array_value( $tab, 'label' );
			?>

			<label class="<?php echo $value === $tab_value ? 'active' : ''; ?>">
				<?php echo esc_html( $tab_label ); ?>

				<input name="<?php echo esc_attr( $name ); ?>"
				       value="<?php echo esc_attr( $tab_value ); ?>" <?php checked( $value === $tab_value ); ?>
				       type="radio"
				       class="hidden"
				/>
			</label>
		<?php endforeach; ?>
	</div>

	<div data-panes>
		<?php foreach ( $tabs as $tab ): ?>
			<?php
			$tab_value = smartcrawl_get_array_value( $tab, 'value' );
			$tab_template = smartcrawl_get_array_value( $tab, 'template' );
			$tab_template_args = smartcrawl_get_array_value( $tab, 'template_args' );
			?>

			<div class="sui-tab-boxed <?php echo $value === $tab_value ? 'active' : ''; ?>">
				<?php if ( $tab_template ) {
					$this->_render(
						$tab_template,
						empty( $tab_template_args ) ? array() : $tab_template_args
					);
				} ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
