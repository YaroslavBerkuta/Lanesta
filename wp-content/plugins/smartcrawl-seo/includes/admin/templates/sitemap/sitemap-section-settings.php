<?php
/**
 * Sitemaps admin page, Sitemap vertical tab
 *
 * @package wpmu-dev-seo
 */
$post_types = empty( $post_types ) ? array() : $post_types;
$taxonomies = empty( $taxonomies ) ? array() : $taxonomies;
$smartcrawl_buddypress = empty( $smartcrawl_buddypress ) ? array() : $smartcrawl_buddypress;
$extra_urls = empty( $extra_urls ) ? '' : $extra_urls;
$ignore_urls = empty( $ignore_urls ) ? '' : $ignore_urls;
$ignore_post_ids = empty( $ignore_post_ids ) ? '' : $ignore_post_ids;
$override_native = empty( $override_native ) ? false : $override_native;

$arguments = array(
	'post_types'            => $post_types,
	'taxonomies'            => $taxonomies,
	'smartcrawl_buddypress' => $smartcrawl_buddypress,
	'extra_urls'            => $extra_urls,
	'ignore_urls'           => $ignore_urls,
	'ignore_post_ids'       => $ignore_post_ids,
);

if ( $override_native ) {
	$this->_render( 'sitemap/sitemap-smartcrawl', $arguments );
} else {
	$this->_render( 'sitemap/sitemap-native', $arguments );
}
