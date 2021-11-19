<?php
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$options = empty( $options ) ? array() : $options;
$social_options = empty( $social_options ) ? array() : $social_options;
$pages = empty( $pages ) ? array() : $pages;

$person_name = (string) smartcrawl_get_array_value( $social_options, 'override_name' );
$person_job_title = (string) smartcrawl_get_array_value( $options, 'person_job_title' );
$person_bio = (string) smartcrawl_get_array_value( $options, 'person_bio' );
$person_portrait = (string) smartcrawl_get_array_value( $options, 'person_portrait' );
$person_phone_number = (string) smartcrawl_get_array_value( $options, 'person_phone_number' );
$person_brand_name = (string) smartcrawl_get_array_value( $options, 'person_brand_name' );
$person_brand_logo = (string) smartcrawl_get_array_value( $options, 'person_brand_logo' );
$person_contact_page = (string) smartcrawl_get_array_value( $options, 'person_contact_page' );

$owner = Smartcrawl_Model_User::owner();
?>

<p class="sui-description"><?php esc_html_e( 'Fill out your personal details.', 'wds' ); ?></p>

<div class="sui-form-field">
	<label for="person_name" class="sui-label">
		<?php esc_html_e( 'Name', 'wds' ); ?>
	</label>
	<input id="person_name"
	       class="sui-form-control"
	       type="text"
	       name="<?php echo esc_attr( $option_name ); ?>[override_name]"
	       placeholder="<?php echo $owner->get_full_name(); ?>"
	       value="<?php echo esc_attr( $person_name ); ?>"/>
</div>

<div class="sui-form-field">
	<label for="person_job_title" class="sui-label">
		<?php esc_html_e( 'Job title', 'wds' ); ?>
	</label>
	<input id="person_job_title"
	       class="sui-form-control"
	       type="text"
	       name="<?php echo esc_attr( $option_name ); ?>[person_job_title]"
	       value="<?php echo esc_attr( $person_job_title ); ?>"
	       placeholder="<?php esc_attr_e( 'E.g. architect', 'wds' ); ?>"/>
</div>

<div class="sui-form-field">
	<label for="person_bio" class="sui-label">
		<?php esc_html_e( 'Bio', 'wds' ); ?>
	</label>
	<textarea id="person_bio"
	          class="sui-form-control"
	          type="text"
	          name="<?php echo esc_attr( $option_name ); ?>[person_bio]"
	          placeholder="<?php echo $owner->get_description(); ?>"
	><?php echo esc_textarea( $person_bio ); ?></textarea>
</div>

<div class="sui-form-field">
	<label for="person_portrait" class="sui-label">
		<?php esc_html_e( 'Portrait Photo', 'wds' ); ?>
	</label>

	<?php
	$this->_render( 'media-item-selector', array(
		'id'    => 'person_portrait',
		'value' => $person_portrait,
		'field' => 'id',
	) );
	?>
</div>

<div id="wds-personal-brand" class="wds-separator-top">
	<strong><?php esc_html_e( 'Personal Brand', 'wds' ); ?></strong>
	<p class="sui-description">
		<?php esc_html_e( 'Add your brand information to this website. This information is required by Google to be used in search results.', 'wds' ); ?>
	</p>

	<div class="sui-form-field">
		<label for="person_brand_name" class="sui-label">
			<?php esc_html_e( 'Brand Name', 'wds' ); ?>
		</label>
		<input id="person_brand_name"
		       class="sui-form-control"
		       type="text"
		       placeholder="<?php echo $owner->get_full_name(); ?>"
		       name="<?php echo esc_attr( $option_name ); ?>[person_brand_name]"
		       value="<?php echo esc_attr( $person_brand_name ); ?>"/>
		<span class="sui-description">
			<?php esc_html_e( 'In case your Brand name is different from your own name.', 'wds' ); ?>
		</span>
	</div>

	<div class="sui-form-field">
		<label for="person_brand_logo" class="sui-label">
			<?php esc_html_e( 'Logo', 'wds' ); ?>
		</label>

		<?php $this->_render( 'media-item-selector', array(
			'id'    => 'person_brand_logo',
			'value' => $person_brand_logo,
			'field' => 'id',
		) ); ?>

		<p class="sui-description">
			<?php esc_html_e( 'Specify the image of your Brand’s logo to be used in Google Search results and in the Knowledge Graph.', 'wds' ); ?>
		</p>
	</div>
</div>

<div id="wds-personal-contact" class="wds-separator-top">
	<strong><?php esc_html_e( 'Personal contact', 'wds' ); ?></strong>
	<p class="sui-description">
		<?php esc_html_e( 'Add your personal information to this website. This information will be used in the Knowledge Graph used by Google and its services to enhance search engine results.', 'wds' ); ?>
	</p>

	<div class="sui-form-field">
		<label for="person_phone_number" class="sui-label">
			<?php esc_html_e( 'Phone Number', 'wds' ); ?>
		</label>
		<input id="person_phone_number"
		       class="sui-form-control"
		       type="text"
		       placeholder="E.g. +1 987 654 321"
		       name="<?php echo esc_attr( $option_name ); ?>[person_phone_number]"
		       value="<?php echo esc_attr( $person_phone_number ); ?>"/>
	</div>

	<?php $this->_render( 'post-search-dropdown-form-field', array(
		'field_name'       => 'person_contact_page',
		'field_label'      => esc_html__( 'Contact page', 'wds' ),
		'first_option'     => esc_html__( 'Select Page', 'wds' ),
		'post_type'        => 'page',
		'selected_post_id' => $person_contact_page,
		'pages'            => $pages,
	) ); ?>
</div>
