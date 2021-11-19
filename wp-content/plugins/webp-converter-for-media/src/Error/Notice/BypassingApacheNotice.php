<?php

namespace WebpConverter\Error\Notice;

use WebpConverter\PluginInfo;

/**
 * {@inheritdoc}
 */
class BypassingApacheNotice implements ErrorNotice {

	const ERROR_KEY = 'bypassing_apache';

	/**
	 * @var PluginInfo
	 */
	private $plugin_info;

	public function __construct( PluginInfo $plugin_info ) {
		$this->plugin_info = $plugin_info;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_key(): string {
		return self::ERROR_KEY;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_message(): array {
		return [
			sprintf(
			/* translators: %1$s: open anchor tag, %2$s: close anchor tag, %3$s: open anchor tag, %4$s: close anchor tag */
				__( 'Requests to images are processed by your server bypassing Apache. When loading images, rules from the .htaccess file are not executed. Occasionally, this only applies to known file extensions: .jpg, .png, etc. and when e.g. .png2 extension is loaded, then the redirections from the .htaccess file work, because the server does not understand this format and does not treat it as image files. Check the redirects for %1$s.png file%2$s (for which the redirection does not work) and for %3$s.png2 file%4$s (for which the redirection works correctly). Change the server settings to stop ignoring the rules from the .htaccess file.', 'webp-converter-for-media' ),
				'<a href="' . $this->plugin_info->get_plugin_directory_url() . 'assets/img/debug/icon-before.png" target="_blank">',
				'</a>',
				'<a href="' . $this->plugin_info->get_plugin_directory_url() . 'assets/img/debug/icon-before.png2" target="_blank">',
				'</a>'
			),
			__( 'In this case, please contact your server administrator.', 'webp-converter-for-media' ),
			sprintf(
			/* translators: %1$s: open strong tag, %2$s: close strong tag, %3$s: loader name */
				__( '%1$sAlso try changing option "Image loading mode" to a different one.%2$s Issues about rewrites can often be resolved by setting this option to "%3$s". You can do this in plugin settings below. After changing settings, remember to flush cache if you use caching plugin or caching via hosting.', 'webp-converter-for-media' ),
				'<strong>',
				'</strong>',
				'Pass Thru'
			),
		];
	}
}
