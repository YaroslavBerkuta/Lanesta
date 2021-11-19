<?php

/**
 * Plugin Name: WebP Converter for Media
 * Description: Speed up your website by serving WebP images instead of standard formats JPEG, PNG and GIF.
 * Version: 3.2.3
 * Author: Mateusz Gbiorczyk
 * Author URI: https://gbiorczyk.pl/
 * Text Domain: webp-converter-for-media
 * Network: true
 */

require_once __DIR__ . '/vendor/autoload.php';

new WebpConverter\WebpConverter(
	new WebpConverter\PluginInfo( __FILE__, '3.2.3' )
);
