<?php
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$options = empty( $options ) ? array() : $options;
$social_options = empty( $social_options ) ? array() : $social_options;
$pages = empty( $pages ) ? array() : $pages;

$schema_about_page = (int) smartcrawl_get_array_value( $options, 'schema_about_page' );
$schema_contact_page = (int) smartcrawl_get_array_value( $options, 'schema_contact_page' );
$schema_main_navigation_menu = (string) smartcrawl_get_array_value( $options, 'schema_main_navigation_menu' );
$schema_wp_header_footer = (bool) smartcrawl_get_array_value( $options, 'schema_wp_header_footer' );
$schema_enable_comments = (bool) smartcrawl_get_array_value( $options, 'schema_enable_comments' );
$schema_default_image = (int) smartcrawl_get_array_value( $options, 'schema_default_image' );

$schema_enable_author_url = (bool) smartcrawl_get_array_value( $options, 'schema_enable_author_url' );
$schema_enable_author_gravatar = (bool) smartcrawl_get_array_value( $options, 'schema_enable_author_gravatar' );
$schema_archive_main_entity_type = (string) smartcrawl_get_array_value( $options, 'schema_archive_main_entity_type' );

$schema_enable_author_archives = (bool) smartcrawl_get_array_value( $options, 'schema_enable_author_archives' );
$schema_enable_search = (string) smartcrawl_get_array_value( $options, 'schema_enable_search' );
$schema_enable_date_archives = (string) smartcrawl_get_array_value( $options, 'schema_enable_date_archives' );
$schema_enable_post_type_archives = (string) smartcrawl_get_array_value( $options, 'schema_enable_post_type_archives' );
$schema_enable_taxonomy_archives = (string) smartcrawl_get_array_value( $options, 'schema_enable_taxonomy_archives' );

$schema_enable_audio = (bool) smartcrawl_get_array_value( $options, 'schema_enable_audio' );
$schema_enable_video = (bool) smartcrawl_get_array_value( $options, 'schema_enable_video' );
$schema_enable_yt_api = (bool) smartcrawl_get_array_value( $options, 'schema_enable_yt_api' );
$schema_yt_api_key = (string) smartcrawl_get_array_value( $options, 'schema_yt_api_key' );

$post_types = empty( $post_types ) ? array() : $post_types;
$taxonomies = empty( $taxonomies ) ? array() : $taxonomies;
?>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label">
			<?php esc_html_e( 'Special Pages', 'wds' ); ?>
		</label>
		<p class="sui-description">
			<?php esc_html_e( "Add the structured data of your global schema settings and identify your About Page, Contact Page, and your main navigation menu.", 'wds' ); ?>
		</p>
	</div>

	<div class="sui-box-settings-col-2">
		<?php $this->_render( 'post-search-dropdown-form-field', array(
			'field_name'        => 'schema_about_page',
			'field_label'       => esc_html__( 'About Page', 'wds' ),
			'field_description' => esc_html__( 'Choose the page that has the most general information about your company or personal brand.', 'wds' ),
			'first_option'      => esc_html__( 'Select Page', 'wds' ),
			'selected_post_id'  => $schema_about_page,
			'pages'             => $pages,
		) );

		$this->_render( 'post-search-dropdown-form-field', array(
			'field_name'        => 'schema_contact_page',
			'field_label'       => esc_html__( 'Contact Page', 'wds' ),
			'field_description' => esc_html__( 'Select the page that contains the best contact information on it.', 'wds' ),
			'first_option'      => esc_html__( 'Select Page', 'wds' ),
			'selected_post_id'  => $schema_contact_page,
			'pages'             => $pages,
		) );

		$this->_render( 'schema/schema-nav-menus-dropdown-form-field', array(
			'schema_main_navigation_menu' => $schema_main_navigation_menu,
		) )
		?>
	</div>
</div>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label">
			<?php esc_html_e( 'Structured Data', 'wds' ); ?>
		</label>
		<p class="sui-description">
			<?php esc_html_e( "Choose additional schema markup you want to enable for this website.", 'wds' ); ?>
		</p>
	</div>

	<div class="sui-box-settings-col-2">
		<?php
		$this->_render( 'toggle-item', array(
			'field_name'       => "{$option_name}[schema_wp_header_footer]",
			'item_label'       => esc_html__( 'Enable WpHeader and WpFooter', 'wds' ),
			'item_description' => esc_html__( 'Enabling this setting adds markup for the header and footer sections which contain general information about your website. For example: the copyright year, website name, and website tagline.', 'wds' ),
			'checked'          => $schema_wp_header_footer,
		) );

		$this->_render( 'toggle-item', array(
			'field_name'       => "{$option_name}[schema_enable_comments]",
			'item_label'       => esc_html__( 'Enable Comments', 'wds' ),
			'item_description' => esc_html__( 'Enable this setting to include comments in markup. The plugin will add comments that a page has received nested in the output markup using Comment as a value.', 'wds' ),
			'checked'          => $schema_enable_comments,
		) );
		?>
	</div>
</div>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label">
			<?php esc_html_e( 'Default Image', 'wds' ); ?>
		</label>
		<p class="sui-description">
			<?php esc_html_e( "Set a default Featured Image, which will be used if no Featured Image is attached to a post.", 'wds' ); ?>
		</p>
	</div>

	<div class="sui-box-settings-col-2">
		<?php $this->_render( 'media-item-selector', array(
			'id'    => 'schema_default_image',
			'value' => $schema_default_image,
			'field' => 'id',
		) ); ?>

		<p class="sui-description">
			<?php esc_html_e( 'Specify a default image to be a fallback for missing Featured Images.', 'wds' ); ?>
		</p>
	</div>
</div>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label">
			<?php esc_html_e( 'Author', 'wds' ); ?>
		</label>
		<p class="sui-description">
			<?php esc_html_e( "Enable author options to help indicate to search engines who the author of the page is. Keep disabled if you don't want to expose the author profile.", 'wds' ); ?>
		</p>
	</div>

	<div class="sui-box-settings-col-2">
		<?php
		$this->_render( 'toggle-item', array(
			'field_id'         => 'schema_enable_author_url',
			'field_name'       => "{$option_name}[schema_enable_author_url]",
			'item_label'       => esc_html__( 'Enable Author URL', 'wds' ),
			'item_description' => esc_html__( 'Enabling this will add the author page URL in the author markup.', 'wds' ),
			'checked'          => $schema_enable_author_url,
		) );
		$this->_render( 'toggle-item', array(
			'field_id'         => 'schema_enable_author_gravatar',
			'field_name'       => "{$option_name}[schema_enable_author_gravatar]",
			'item_label'       => esc_html__( 'Enable Gravatar ImageObject ', 'wds' ),
			'item_description' => esc_html__( 'Use gravatar.com image in the author markup.', 'wds' ),
			'checked'          => $schema_enable_author_gravatar,
		) );
		?>
	</div>
</div>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label">
			<?php esc_html_e( 'Archives', 'wds' ); ?>
		</label>
		<p class="sui-description">
			<?php esc_html_e( 'You can enable or disable schema markup from current page/post.', 'wds' ); ?>
		</p>
	</div>

	<div class="sui-box-settings-col-2">
		<table id="wds-schema-archives-table" class="sui-table">
			<tbody>
			<tr class="wds-schema-toggleable">
				<td><strong><?php esc_html_e( 'Author Archives', 'wds' ); ?></strong></td>
				<td>
					<?php $this->_render( 'toggle-item', array(
						'checked'    => $schema_enable_author_archives,
						'field_name' => "{$option_name}[schema_enable_author_archives]",
					) ); ?>
				</td>
			</tr>
			<tr style="display: none;"></tr>

			<tr class="wds-schema-toggleable">
				<td><strong><?php esc_html_e( 'Search Page', 'wds' ); ?></strong></td>
				<td>
					<?php $this->_render( 'toggle-item', array(
						'checked'    => $schema_enable_search,
						'field_name' => "{$option_name}[schema_enable_search]",
					) ); ?>
				</td>
			</tr>
			<tr style="display: none;"></tr>

			<tr class="wds-schema-toggleable">
				<td><strong><?php esc_html_e( 'Date Archives', 'wds' ); ?></strong></td>
				<td>
					<?php $this->_render( 'toggle-item', array(
						'checked'    => $schema_enable_date_archives,
						'field_name' => "{$option_name}[schema_enable_date_archives]",
					) ); ?>
				</td>
			</tr>
			<tr style="display: none;"></tr>

			<tr class="wds-schema-toggleable">
				<td><strong><?php esc_html_e( 'Post Type Archives', 'wds' ); ?></strong></td>
				<td>
					<?php $this->_render( 'toggle-item', array(
						'checked'    => $schema_enable_post_type_archives,
						'field_name' => "{$option_name}[schema_enable_post_type_archives]",
					) ); ?>
				</td>
			</tr>
			<tr style="<?php echo $schema_enable_post_type_archives ? '' : 'display:none;'; ?>">
				<?php if ( $post_types ): ?>
					<td colspan="2">
						<table class="sui-table">
							<?php foreach ( $post_types as $post_type_name => $post_type_label ): ?>
								<tr>
									<td><strong><?php echo esc_html( $post_type_label ); ?></strong></td>
									<td><?php echo esc_html( $post_type_name ); ?></td>
									<td>
										<?php $this->_render( 'toggle-item', array(
											'checked'    => (bool) smartcrawl_get_array_value(
												$options,
												array( 'schema_disabled_post_type_archives', $post_type_name )
											),
											'field_name' => "{$option_name}[schema_disabled_post_type_archives][{$post_type_name}]",
											'inverted'   => true,
										) ); ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</table>
					</td>
				<?php endif; ?>
			</tr>

			<tr class="wds-schema-toggleable">
				<td><strong><?php esc_html_e( 'Taxonomy Archives', 'wds' ); ?></strong></td>
				<td>
					<?php $this->_render( 'toggle-item', array(
						'checked'    => $schema_enable_taxonomy_archives,
						'field_name' => "{$option_name}[schema_enable_taxonomy_archives]",
					) ); ?>
				</td>
			</tr>

			<tr style="<?php echo $schema_enable_taxonomy_archives ? '' : 'display:none'; ?>">
				<?php if ( $taxonomies ): ?>
					<td colspan="2">
						<table class="sui-table">
							<?php foreach ( $taxonomies as $taxonomy_name => $taxonomy_label ): ?>
								<tr>
									<td><strong><?php echo esc_html( $taxonomy_label ); ?></strong></td>
									<td><?php echo esc_html( $taxonomy_name ); ?></td>
									<td>
										<?php $this->_render( 'toggle-item', array(
											'checked'    => (bool) smartcrawl_get_array_value(
												$options,
												array( 'schema_disabled_taxonomy_archives', $taxonomy_name )
											),
											'field_name' => "{$option_name}[schema_disabled_taxonomy_archives][{$taxonomy_name}]",
											'inverted'   => true,
										) ); ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</table>
					</td>
				<?php endif; ?>
			</tr>
			</tbody>
		</table>

		<div class="sui-form-field">
			<label for="schema_archive_main_entity_type" class="sui-label">
				<?php esc_html_e( 'List Type', 'wds' ); ?>
			</label>

			<div class="sui-col-md-6" style="padding: 0">
				<select id="schema_archive_main_entity_type"
				        name="<?php echo esc_attr( "{$option_name}[schema_archive_main_entity_type]" ); ?>"
				        class="sui-select"
				        data-allow-clear="0"
				        data-minimum-results-for-search="-1">

					<?php foreach (
						array(
							Smartcrawl_Schema_Value_Helper::TYPE_ITEM_LIST,
							'Blog',
						) as $archive_list_type
					): ?>
						<option <?php selected( $schema_archive_main_entity_type, $archive_list_type ); ?>
								value="<?php echo esc_attr( $archive_list_type ); ?>">
							<?php echo esc_html( $archive_list_type ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<span class="sui-description">
				<?php esc_html_e( 'Choose Blog, if your website contains blog posts only, or ItemList if you have mixed types of content.', 'wds' ); ?>
			</span>
		</div>
	</div>
</div>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label">
			<?php esc_html_e( 'Media objects', 'wds' ); ?>
		</label>
		<p class="sui-description">
			<?php esc_html_e( "Make sure that the media you host on your website, such as video or audio, looks attractive and has all the necessary information while being displayed in search engine results.", 'wds' ); ?>
		</p>
	</div>

	<div class="sui-box-settings-col-2">
		<?php $this->_render( 'toggle-item', array(
			'field_id'         => 'schema_enable_audio',
			'field_name'       => "{$option_name}[schema_enable_audio]",
			'item_label'       => esc_html__( 'Enable AudioObject', 'wds' ),
			'item_description' => esc_html__( 'Enable schema.org markup on audio files.', 'wds' ),
			'checked'          => $schema_enable_audio,
		) ); ?>

		<?php $this->_render( 'toggle-item', array(
			'field_id'                   => 'schema_enable_video',
			'field_name'                 => "{$option_name}[schema_enable_video]",
			'item_label'                 => esc_html__( 'Enable VideoObject', 'wds' ),
			'item_description'           => esc_html__( 'Enable schema.org markup on video files.', 'wds' ),
			'checked'                    => $schema_enable_video,
			'sub_settings_template'      => 'schema/schema-youtube-api-key',
			'sub_settings_template_args' => array(
				'schema_enable_yt_api' => $schema_enable_yt_api,
				'schema_yt_api_key'    => $schema_yt_api_key,
			),
			'sub_settings_border'        => false,
		) ); ?>
	</div>
</div>
