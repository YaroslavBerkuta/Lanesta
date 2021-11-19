<?php
/**
 * @var $items Smartcrawl_Sitemap_Item[]
 */
$items = empty( $items ) ? array() : $items;
if ( ! $items ) {
	return;
}
$stylesheet_enabled = Smartcrawl_Sitemap_Utils::stylesheet_enabled();
$sitemap_images_enabled = Smartcrawl_Sitemap_Utils::sitemap_images_enabled();
$hide_branding = Smartcrawl_White_Label::get()->is_hide_wpmudev_branding();
$plugin_dir_url = SMARTCRAWL_PLUGIN_URL;

echo '<?xml version="1.0" encoding="UTF-8"?>';

if ( $stylesheet_enabled ) {
	if ( $hide_branding ) {
		$xsl = "xml-sitemap-whitelabel";
	} else {
		$xsl = 'xml-sitemap';
	}
	echo "<?xml-stylesheet type='text/xml' href='{$plugin_dir_url}admin/templates/xsl/{$xsl}.xsl'?>";
}
?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 https://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd"
        xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        <?php if ( $sitemap_images_enabled ): ?>xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"<?php endif; ?>
>
	<?php foreach ( $items as $item ): ?>
		<?php echo $item->to_xml(); ?>
	<?php endforeach; ?>
</urlset>
