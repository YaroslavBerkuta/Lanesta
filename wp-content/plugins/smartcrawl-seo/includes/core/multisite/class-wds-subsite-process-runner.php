<?php

/**
 * Class Smartcrawl_Subsite_Process_Runner
 *
 * Runs a process (a simple callback) for each site in the network and keeps track of progress.
 */
class Smartcrawl_Subsite_Process_Runner {
	/**
	 * @var string To store the number of processed sites
	 */
	private $option_id;
	/**
	 * @var callable The "process" this class is supposed to run
	 */
	private $callback;

	const ALL_NETWORK_SITES = 'wds-all-network-sites-cache';

	public function __construct( $option_id, $callback ) {
		$this->option_id = $option_id;
		$this->callback = $callback;

		$this->maybe_init_all_sites_cache();
	}

	/**
	 * Runs the provided callback function for the next site.
	 * This method is supposed to be call repeatedly to run the "process" for the whole network, one site at a time.
	 *
	 * @param array ...$args The arguments to pass to the callback.
	 *
	 * @return int Number of sites for which processing has been completed.
	 */
	public function run( ...$args ) {
		$processed_sites = $this->get_processed_site_count();
		$total_site_count = $this->get_total_site_count();

		$next_site = $this->get_next_site_to_process();
		if ( ! $next_site ) {
			$this->reset_processed_site_count();

			/**
			 * Return total number of sites signifying that all sites have been processed
			 * After receiving this return value, the run method shouldn't be called again.
			 */
			return $total_site_count;
		}

		switch_to_blog( $next_site );
		$process_completed = call_user_func_array( $this->callback, $args );
		switch_to_blog( get_main_site_id() );
		if ( $process_completed ) {
			$processed_sites ++;
		}

		// All done?
		if ( $processed_sites === $total_site_count ) {
			// Yes, clear the site count and cache
			$this->reset_processed_site_count();
			$this->reset_all_sites_cache();
		} else {
			// No, update the site count
			$this->update_processed_site_count( $processed_sites );
		}

		return $processed_sites;
	}

	private function get_processed_site_count() {
		return (int) get_site_option( $this->get_option_id(), 0 );
	}

	private function update_processed_site_count( $count ) {
		update_site_option( $this->get_option_id(), $count );
	}

	private function reset_processed_site_count() {
		delete_site_option( $this->get_option_id() );
	}

	private function get_option_id() {
		return $this->option_id;
	}

	/**
	 * Get the latest site count
	 *
	 * @return array|int
	 */
	public function get_total_site_count() {
		return get_sites( array(
			'count'    => true,
			'site__in' => $this->get_all_sites_cache(),
			'number'   => PHP_INT_MAX,
		) );
	}

	/**
	 * Runs a fresh query and returns the next site to process
	 *
	 * @return bool|int
	 */
	public function get_next_site_to_process() {
		$processed_site_count = $this->get_processed_site_count();
		$next_site = get_sites( array(
			'fields'   => 'ids',
			'number'   => 1,
			'offset'   => $processed_site_count,
			'site__in' => $this->get_all_sites_cache(),
			'orderby'  => 'id',
			'order'    => 'DESC',
		) );

		return empty( $next_site ) ? false : $next_site[0];
	}

	private function maybe_init_all_sites_cache() {
		if ( ! empty( $this->get_all_sites_cache() ) ) {
			return false;
		}

		$all_sites = get_sites( array(
			'fields' => 'ids',
			'number' => PHP_INT_MAX,
		) );

		return update_site_option( self::ALL_NETWORK_SITES, $all_sites );
	}

	private function get_all_sites_cache() {
		return get_site_option( self::ALL_NETWORK_SITES, array() );
	}

	private function reset_all_sites_cache() {
		return delete_site_option( self::ALL_NETWORK_SITES );
	}
}
