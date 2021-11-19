<?php
$meta_robots_main_blog_archive = empty( $meta_robots_main_blog_archive ) ? '' : $meta_robots_main_blog_archive;
$macros = Smartcrawl_Onpage_Settings::get_general_macros();
?>

<?php $this->_render( 'onpage/onpage-preview' ); ?>

<?php
$this->_render( 'onpage/onpage-general-settings', array(
	'title_key'        => 'title-home',
	'title_label_desc' => esc_html__( 'Define the main title of your website that Google will index.', 'wds' ),
	'title_field_desc' => esc_html__( 'This is generally your brand name, sometimes with a tagline.', 'wds' ),

	'description_key' => 'metadesc-home',
	'meta_label_desc' => esc_html__( 'Set the default description that will accompany your SEO title in search engine results.', 'wds' ),
	'meta_field_desc' => esc_html__( 'Remember to keep it simple, to the point, and include a bit about what your website can offer potential visitors.', 'wds' ),
		'macros'          => $macros,
) );

$this->_render( 'onpage/onpage-og-twitter', array(
	'for_type'          => 'home',
	'social_label_desc' => esc_html__( 'Enable or disable support for social platforms when your homepage is shared on them.', 'wds' ),
		'macros'            => $macros,
) );

$this->_render( 'onpage/onpage-meta-robots', array(
	'items' => $meta_robots_main_blog_archive,
) );
?>

<footer class="sui-box-footer">
	<button name="submit"
	        type="submit"
	        class="sui-button sui-button-blue">
		<span class="sui-icon-save" aria-hidden="true"></span>

		<?php echo esc_html__( 'Save Settings', 'wds' ); ?>
	</button>
</footer>
