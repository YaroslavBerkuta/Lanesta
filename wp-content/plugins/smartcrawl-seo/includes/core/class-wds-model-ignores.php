<?php

class Smartcrawl_Model_Ignores extends Smartcrawl_Model {

	const IGNORES_SEO_STORAGE = 'wds-ignores';
	const IGNORES_CHECKUP_STORAGE = 'wds-checkup-ignores';

	private $_ignores = array();

	private $_ignores_storage;

	public function __construct( $storage = self::IGNORES_SEO_STORAGE ) {
		$this->_ignores_storage = $storage;

		$this->load();
	}

	/**
	 * Loads the ignores list
	 *
	 * @return bool Status
	 */
	public function load() {
		$this->_ignores = array();

		$ignores = get_option( $this->get_ignores_storage() );

		if ( ! empty( $ignores ) && is_array( $ignores ) ) {
			$this->_ignores = array_filter( array_unique( $ignores ) );

			return true;
		}

		return false;
	}

	public function get_type() {
		return 'ignores';
	}

	/**
	 * Clears the persisted ignores list
	 *
	 * @return bool Status
	 */
	public function clear() {
		return update_option( $this->get_ignores_storage(), array() );
	}

	/**
	 * Adds ignored item to ignores list
	 *
	 * @param string $key Item key to ignore
	 *
	 * @return bool Status
	 */
	public function set_ignore( $key ) {
		if ( empty( $key ) ) {
			return false;
		}
		if ( ! $this->is_valid_ignore_key( $key ) ) {
			return false;
		}

		$this->_ignores[] = $key;

		return $this->set_ignores( $this->_ignores );
	}

	public function set_ignores( $keys ) {
		$this->_ignores = array_filter( array_unique( $keys ) );

		return update_option( $this->get_ignores_storage(), $this->_ignores );
	}

	/**
	 * Check if a string is valid ignored issue identifier
	 *
	 * @param string $key String to check
	 *
	 * @return bool Valid state
	 */
	public function is_valid_ignore_key( $key ) {
		if ( ! is_string( $key ) ) {
			return false;
		}

		return ! ! preg_match( '/^[a-z0-9-_]+$/i', $key );
	}

	/**
	 * Removes ignored item from ignores list
	 *
	 * @param string $key Item key to remove from ignores
	 *
	 * @return bool Status
	 */
	public function unset_ignore( $key ) {
		if ( empty( $key ) ) {
			return false;
		}
		if ( ! $this->is_valid_ignore_key( $key ) ) {
			return false;
		}

		$index = array_search( $key, $this->_ignores, true );
		if ( false !== $index ) {
			unset( $this->_ignores[ $index ] );
		}

		$this->_ignores = array_filter( array_unique( $this->_ignores ) );

		return update_option( $this->get_ignores_storage(), $this->_ignores );
	}

	/**
	 * Checks if an issue is to be ignored
	 *
	 * @return bool
	 */
	public function is_ignored( $key ) {
		return (bool) in_array( $key, $this->get_all(), true );
	}

	public function is_not_ignored( $key ) {
		return ! $this->is_ignored( $key );
	}

	/**
	 * Gets a list of ignored items
	 *
	 * @return array List of ignored items unique IDs
	 */
	public function get_all() {
		return array_unique( $this->_ignores );
	}

	private function get_ignores_storage() {
		return $this->_ignores_storage;
	}
}
