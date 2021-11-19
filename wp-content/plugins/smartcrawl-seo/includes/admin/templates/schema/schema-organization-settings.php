<?php
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$options = empty( $options ) ? array() : $options;
$social_options = empty( $social_options ) ? array() : $social_options;
$pages = empty( $pages ) ? array() : $pages;

$organization_type = (string) smartcrawl_get_array_value( $options, 'organization_type' );
$organization_name = (string) smartcrawl_get_array_value( $social_options, 'organization_name' );
$organization_description = (string) smartcrawl_get_array_value( $options, 'organization_description' );
$organization_logo = (string) smartcrawl_get_array_value( $social_options, 'organization_logo' );
$organization_contact_type = (string) smartcrawl_get_array_value( $options, 'organization_contact_type' );
$organization_phone_number = (string) smartcrawl_get_array_value( $options, 'organization_phone_number' );
$organization_contact_page = (string) smartcrawl_get_array_value( $options, 'organization_contact_page' );

?>
<p class="sui-description"><?php esc_html_e( 'Fill out your organization details.', 'wds' ); ?></p>

<?php $this->_render( 'schema/schema-organization-types-dropdown', array(
	'organization_type' => $organization_type,
) ); ?>

<div class="sui-form-field">
	<label for="organization_name" class="sui-label">
		<?php esc_html_e( 'Organization name', 'wds' ); ?>
	</label>
	<input id="organization_name"
	       class="sui-form-control"
	       type="text"
	       name="<?php echo esc_attr( $option_name ); ?>[organization_name]"
	       placeholder="<?php echo get_bloginfo( 'name' ); ?>"
	       value="<?php echo esc_attr( $organization_name ); ?>"/>
</div>

<div class="sui-form-field">
	<label for="organization_description" class="sui-label">
		<?php esc_html_e( 'Description', 'wds' ); ?>
	</label>
	<textarea id="organization_description"
	          class="sui-form-control"
	          type="text"
	          placeholder="<?php echo get_bloginfo( 'description' ); ?>"
	          name="<?php echo esc_attr( $option_name ); ?>[organization_description]"
	><?php echo esc_textarea( $organization_description ); ?></textarea>
</div>

<div class="sui-form-field">
	<label for="organization_logo" class="sui-label">
		<?php esc_html_e( 'Logo', 'wds' ); ?>
	</label>

	<?php
	$this->_render( 'media-item-selector', array(
		'id'    => 'organization_logo',
		'value' => $organization_logo,
		'field' => 'url',
	) );
	?>
	<p class="sui-description">
		<?php esc_html_e( 'Specify the image of your organization’s logo to be used in Google Search results and in the Knowledge Graph.', 'wds' ); ?>
	</p>
</div>

<div id="wds-organization-contact" class="wds-separator-top">
	<strong><?php esc_html_e( 'Corporate contact', 'wds' ); ?></strong>
	<p class="sui-description">
		<?php esc_html_e( 'Add all corporate contact information to this website. This information will be used in the Knowledge Graph Card used by Google and its services to enhance search engine results.', 'wds' ); ?>
	</p>

	<div class="sui-form-field">
		<label for="organization_contact_type" class="sui-label">
			<?php esc_html_e( 'Contact type', 'wds' ); ?>
		</label>

		<select id="organization_contact_type"
		        name="<?php echo esc_attr( $option_name ); ?>[organization_contact_type]"
		        data-minimum-results-for-search="-1"
		        class="sui-select">
			<option value="" <?php selected( empty( $organization_contact_type ) ); ?>>
				<?php esc_html_e( 'Select (Optional)', 'wds' ); ?>
			</option>
			<?php foreach (
				array(
					'customer support'    => 'Customer Support',
					'technical support'   => 'Technical Support',
					'billing support'     => 'Billing Support',
					'bill payment'        => 'Bill payment',
					'sales'               => 'Sales',
					'reservations'        => 'Reservations',
					'credit card support' => 'Credit Card Support',
					'emergency'           => 'Emergency',
					'baggage tracking'    => 'Baggage Tracking',
					'roadside assistance' => 'Roadside Assistance',
					'package tracking'    => 'Package Tracking',
				) as $org_contact_value => $org_contact_label
			): ?>
				<option value="<?php echo esc_attr( $org_contact_value ); ?>" <?php selected( $organization_contact_type, $org_contact_value ); ?>>
					<?php echo esc_html( $org_contact_label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="sui-form-field">
		<label for="organization_phone_number" class="sui-label">
			<?php esc_html_e( 'Phone Number', 'wds' ); ?>
		</label>
		<input id="organization_phone_number"
		       class="sui-form-control"
		       type="text"
		       placeholder="E.g. +1 987 654 321"
		       name="<?php echo esc_attr( $option_name ); ?>[organization_phone_number]"
		       value="<?php echo esc_attr( $organization_phone_number ); ?>"/>
	</div>

	<?php $this->_render( 'post-search-dropdown-form-field', array(
		'field_name'       => 'organization_contact_page',
		'field_label'      => esc_html__( 'Contact page', 'wds' ),
		'first_option'     => esc_html__( 'Select Page', 'wds' ),
		'post_type'        => 'page',
		'selected_post_id' => $organization_contact_page,
		'pages'            => $pages,
	) ); ?>
</div>
