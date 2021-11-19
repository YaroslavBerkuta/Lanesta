<?php

class Smartcrawl_Sitemap_Cache {
	const CACHE_FILE_NAME_FORMAT = '%s-sitemap%d.xml';
	const CACHE_PRISTINE_OPTION = 'wds_sitemap_cache_pristine';

	/**
	 * Static instance
	 *
	 * @var $this
	 */
	private static $_instance;

	/**
	 * Static instance getter
	 */
	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}


	public function set_cached( $type, $page, $sitemap ) {
		return $this->write_to_cache_file(
			$this->cache_file_name( $type, $page ),
			$sitemap
		);
	}

	public function get_cached( $type, $page ) {
		if ( $this->is_cache_pristine() ) {
			return $this->get_from_cache_file( $this->cache_file_name( $type, $page ) );
		}

		$this->drop_cache();
		return false;
	}

	public function drop_cache() {
		$file_system = $this->fs_direct();
		$cache_dir = $this->get_cache_dir();
		if ( empty( $cache_dir ) ) {
			Smartcrawl_Logger::error( "Sitemap cache could not be dropped because it does not exist" );
			return false;
		}

		$removed = $file_system->rmdir( $cache_dir, true );
		if ( ! $removed ) {
			Smartcrawl_Logger::error( "Sitemap cache directory could not be removed" );
			return false;
		}

		$this->set_cache_pristine( true ); // An empty cache is a pristine cache
		Smartcrawl_Logger::info( "Sitemap cache dropped" );
		return true;
	}

	private function fs_direct() {
		if ( ! class_exists( 'WP_Filesystem_Direct', false ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
			require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
		}
		return new WP_Filesystem_Direct( null );
	}

	private function cache_file_name( $type, $page ) {
		return sprintf( self::CACHE_FILE_NAME_FORMAT, $type, $page );
	}

	private function write_to_cache_file( $filename, $contents ) {
		$path = $this->get_cache_dir( $filename );
		if (
			empty( $path )
			|| ! smartcrawl_file_put_contents( $path, $contents, LOCK_EX )
		) {
			Smartcrawl_Logger::error( "Failed writing sitemap cache file to [{$path}]" );
			return false;
		}

		Smartcrawl_Logger::info( "Added file to sitemap cache: [{$path}]" );
		return true;
	}

	private function get_from_cache_file( $filename ) {
		$path = $this->get_cache_dir( $filename );

		if ( ! empty( $path ) && file_exists( $path ) ) {
			Smartcrawl_Logger::info( "Sitemap file read from cache: [{$path}]" );
			return smartcrawl_file_get_contents( $path );
		}

		Smartcrawl_Logger::info( "Sitemap file not found in cache: [{$path}]" );
		return false;
	}

	public function get_cache_dir( $postfix = '' ) {
		$path = smartcrawl_uploads_dir();
		$path = "{$path}sitemap/";

		// Attempt to create the dir in case it doesn't already exist
		$dir_exists = wp_mkdir_p( $path );
		if ( ! $dir_exists ) {
			Smartcrawl_Logger::error( "Sitemap cache directory could not be created at [{$path}]" );
			return false;
		}

		return "{$path}{$postfix}";
	}

	/**
	 * @return bool
	 */
	public function is_cache_pristine() {
		return in_array(
			get_current_blog_id(),
			$this->get_sitemap_pristine_option()
		);
	}

	public function invalidate() {
		$this->set_cache_pristine( false );
	}

	private function set_cache_pristine( $value ) {
		$pristine = $this->get_sitemap_pristine_option();
		$current_site_id = get_current_blog_id();

		if ( $value ) {
			if ( ! in_array( $current_site_id, $pristine ) ) {
				$pristine[] = $current_site_id;
				$this->update_sitemap_pristine_option( $pristine );
			}
		} else {
			if ( ! is_multisite() ) {
				// The single site is out of date now so drop everything
				$this->delete_sitemap_pristine_option();
			} else {
				$this->update_sitemap_pristine_option(
					array_diff( $pristine, array( $current_site_id ) )
				);
			}
		}
	}

	private function get_sitemap_pristine_option() {
		$value = get_site_option( self::CACHE_PRISTINE_OPTION, array() );
		return is_array( $value )
			? $value
			: array();
	}

	private function update_sitemap_pristine_option( $value ) {
		return update_site_option( self::CACHE_PRISTINE_OPTION, $value );
	}

	private function delete_sitemap_pristine_option() {
		return delete_site_option( self::CACHE_PRISTINE_OPTION );
	}

	public function is_writable() {
		return is_writeable( $this->get_cache_dir() );
	}

	public function is_index_cached() {
		if ( ! $this->is_cache_pristine() ) {
			// If cache is not pristine, we don't care if the file exists or not
			return false;
		}

		$file_name = $this->cache_file_name( Smartcrawl_Controller_Sitemap_Front::SITEMAP_TYPE_INDEX, 0 );
		$path = $this->get_cache_dir( $file_name );

		return ! empty( $path ) && file_exists( $path );
	}
}
