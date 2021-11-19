<?php
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$schema_main_navigation_menu = empty( $schema_main_navigation_menu ) ? '' : $schema_main_navigation_menu;

/**
 * @var $nav_menus WP_Term[]
 */
$nav_menus = wp_get_nav_menus();
?>

<div class="sui-form-field">
	<label for="schema_main_navigation_menu" class="sui-label">
		<?php esc_html_e( 'Site Navigation', 'wds' ); ?>
	</label>
	<div class="sui-row">
		<div class="sui-col-md-6">
			<select id="schema_main_navigation_menu"
			        name="<?php echo esc_attr( "{$option_name}[schema_main_navigation_menu]" ); ?>"
			        class="sui-select"
			        data-allow-clear="0"
			        data-minimum-results-for-search="-1">

				<option value=""><?php esc_html_e( 'Select a Menu', 'wds' ); ?></option>

				<?php foreach ( $nav_menus as $nav_menu ): ?>
					<option <?php selected( $schema_main_navigation_menu, $nav_menu->slug ) ?>
							value="<?php echo esc_attr( $nav_menu->slug ); ?>">
						<?php echo esc_html( $nav_menu->name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<p class="sui-description">
		<?php esc_html_e( 'We recommend linking your main site navigation, or the menu that gives visitors the most general overview of your website.', 'wds' ); ?>
	</p>
</div>
