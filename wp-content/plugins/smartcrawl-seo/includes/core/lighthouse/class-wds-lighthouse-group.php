<?php

class Smartcrawl_Lighthouse_Group {
	/**
	 * @var Smartcrawl_Lighthouse_Check[]
	 */
	private $checks = array();
	private $label;
	private $description;
	private $id;

	public function __construct( $id, $label, $description, $report, $checks ) {
		$this->id = $id;
		$this->label = $label;
		$this->description = $description;

		foreach ( $checks as $check_id ) {
			$check = Smartcrawl_Lighthouse_Check::create( $check_id, $report );
			$this->checks[ $check_id ] = $check;
		}
	}

	public function get_checks() {
		return $this->checks;
	}

	/**
	 * @param $check_id
	 *
	 * @return Smartcrawl_Lighthouse_Check
	 */
	public function get_check( $check_id ) {
		return smartcrawl_get_array_value( $this->checks, $check_id );
	}

	public function get_label() {
		return $this->label;
	}

	public function get_description() {
		return $this->description;
	}

	public function get_failing_count() {
		$failing_count = 0;
		foreach ( $this->checks as $check ) {
			if ( ! $check->is_passed() ) {
				$failing_count ++;
			}
		}
		return $failing_count;
	}

	public function get_id() {
		return $this->id;
	}
}
