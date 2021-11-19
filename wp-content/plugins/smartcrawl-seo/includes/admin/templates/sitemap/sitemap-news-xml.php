<?php
/**
 * @var $news_items Smartcrawl_Sitemap_News_Item[]
 */
$news_items = empty( $news_items ) ? array() : $news_items;
$hide_branding = Smartcrawl_White_Label::get()->is_hide_wpmudev_branding();
$stylesheet_enabled = Smartcrawl_Sitemap_Utils::stylesheet_enabled();
$plugin_dir_url = SMARTCRAWL_PLUGIN_URL;

echo "<?xml version='1.0' encoding='UTF-8'?>";

if ( $stylesheet_enabled ) {
	if ( $hide_branding ) {
		$xsl = "xml-news-sitemap-whitelabel";
	} else {
		$xsl = 'xml-news-sitemap';
	}
	echo "<?xml-stylesheet type='text/xml' href='{$plugin_dir_url}admin/templates/xsl/{$xsl}.xsl'?>";
}
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
	<?php foreach ( $news_items as $news_item ): ?>
		<?php echo $news_item->to_xml(); ?>
	<?php endforeach; ?>
</urlset>
