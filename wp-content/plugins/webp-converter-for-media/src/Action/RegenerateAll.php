<?php

namespace WebpConverter\Action;

use WebpConverter\Conversion\Endpoint\PathsEndpoint;
use WebpConverter\HookableInterface;
use WebpConverter\PluginData;

/**
 * Initializes conversion of all image sizes in all directories.
 */
class RegenerateAll implements HookableInterface {

	/**
	 * @var PluginData
	 */
	private $plugin_data;

	public function __construct( PluginData $plugin_data ) {
		$this->plugin_data = $plugin_data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function init_hooks() {
		add_action( 'webpc_regenerate_all', [ $this, 'regenerate_all_images' ] );
	}

	/**
	 * Converts all images in directories set in options to output formats.
	 *
	 * @return void
	 * @internal
	 */
	public function regenerate_all_images() {
		do_action( 'webpc_convert_paths', ( new PathsEndpoint( $this->plugin_data ) )->get_paths( true ) );
	}
}
