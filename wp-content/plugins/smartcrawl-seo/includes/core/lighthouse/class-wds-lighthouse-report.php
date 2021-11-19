<?php

class Smartcrawl_Lighthouse_Report {
	const GROUP_CONTENT = 'content';
	const GROUP_VISIBILITY = 'visibility';
	const GROUP_RESPONSIVE = 'responsive';
	const GROUP_MANUAL = 'manual';
	/**
	 * @var int
	 */
	private $score = 0;
	/**
	 * @var int
	 */
	private $total_audits_count = 0;
	/**
	 * @var int
	 */
	private $passed_audits_count = 0;

	/**
	 * @var Smartcrawl_Lighthouse_Group[]
	 */
	private $groups = array();
	/**
	 * @var string
	 */
	private $timestamp = '';
	/**
	 * @var WP_Error
	 */
	private $errors;
	/**
	 * @var string
	 */
	private $screenshot = '';
	/**
	 * @var int
	 */
	private $screenshot_width = 0;
	/**
	 * @var int
	 */
	private $screenshot_height = 0;
	/**
	 * @var array
	 */
	private $screenshot_nodes = array();
	/**
	 * @var string
	 */
	private $device;

	public function __construct( $device ) {
		$this->errors = new WP_Error();
		$this->device = $device;

		$this->populate_groups();
	}

	public function populate( $raw_report ) {
		$this->score = empty( $raw_report['seo_score'] ) ? 0 : $raw_report['seo_score'];
		$metrics = empty( $raw_report['metrics'] ) ? array() : $raw_report['metrics'];
		$passed_checks = 0;
		$total_checks = 0;

		$this->populate_screenshot_data( $metrics );

		foreach ( $this->groups as $group ) {
			foreach ( $group->get_checks() as $check ) {
				$metric = smartcrawl_get_array_value( $metrics, $check->get_id() );

				$score = smartcrawl_get_array_value( $metric, 'score' );
				$passed = $score === null || $score === 1; // Set passed to true when score is either not available or is 1
				$check->set_passed( $passed );

				$details = smartcrawl_get_array_value( $metric, 'details' );
				$check->set_raw_details( $details );

				$weight = smartcrawl_get_array_value( $metric, 'weight' );
				$check->set_weight( $weight );

				$check->prepare();

				if ( $passed ) {
					$passed_checks ++;
				}
				$total_checks ++;
			}
		}

		$this->total_audits_count = $total_checks;
		$this->passed_audits_count = $passed_checks;
	}

	private function populate_groups() {
		$this->groups[ self::GROUP_CONTENT ] = new Smartcrawl_Lighthouse_Group(
			self::GROUP_CONTENT,
			esc_html__( 'Content audits', 'wds' ),
			esc_html__( 'Make sure search engines understand your content.', 'wds' ),
			$this,
			array(
				Smartcrawl_Lighthouse_Document_Title_Check::ID,
				Smartcrawl_Lighthouse_Meta_Description_Check::ID,
				Smartcrawl_Lighthouse_Link_Text_Check::ID,
				Smartcrawl_Lighthouse_Hreflang_Check::ID,
				Smartcrawl_Lighthouse_Canonical_Check::ID,
				Smartcrawl_Lighthouse_Image_Alt_Check::ID,
			)
		);

		$this->groups[ self::GROUP_VISIBILITY ] = new Smartcrawl_Lighthouse_Group(
			self::GROUP_VISIBILITY,
			esc_html__( 'Crawling and indexing audits', 'wds' ),
			esc_html__( 'Make sure search engines can crawl and index your page.', 'wds' ),
			$this,
			array(
				Smartcrawl_Lighthouse_Http_Status_Code_Check::ID,
				Smartcrawl_Lighthouse_Is_Crawlable_Check::ID,
				Smartcrawl_Lighthouse_Robots_Txt_Check::ID,
				Smartcrawl_Lighthouse_Plugins_Check::ID,
				Smartcrawl_Lighthouse_Crawlable_Anchors_Check::ID,
			)
		);

		$this->groups[ self::GROUP_RESPONSIVE ] = new Smartcrawl_Lighthouse_Group(
			self::GROUP_RESPONSIVE,
			esc_html__( 'Responsive audits', 'wds' ),
			esc_html__( 'Make your page mobile friendly.', 'wds' ),
			$this,
			array(
				Smartcrawl_Lighthouse_Viewport_Check::ID,
				Smartcrawl_Lighthouse_Font_Size_Check::ID,
				Smartcrawl_Lighthouse_Tap_Targets_Check::ID,
			)
		);

		$this->groups[ self::GROUP_MANUAL ] = new Smartcrawl_Lighthouse_Group(
			self::GROUP_MANUAL,
			esc_html__( 'Manual audits', 'wds' ),
			esc_html__( 'The Lighthouse structured data audit is manual, so it does not affect your Lighthouse SEO score.', 'wds' ),
			$this,
			array( Smartcrawl_Lighthouse_Structured_Data_Check::ID )
		);
	}

	public function get_groups() {
		return $this->groups;
	}

	/**
	 * @param $group_id
	 *
	 * @return Smartcrawl_Lighthouse_Group
	 */
	public function get_group( $group_id ) {
		return smartcrawl_get_array_value( $this->groups, $group_id );
	}

	public function get_check( $group_id, $check_id ) {
		$group = $this->get_group( $group_id );
		if ( ! $group ) {
			return null;
		}

		return $group->get_check( $check_id );
	}

	/**
	 * @return int
	 */
	public function get_score() {
		return $this->score;
	}

	public function get_score_grade() {
		$score = $this->get_score();
		if ( $score >= 90 ) {
			$grade = 'a';
		} else if ( $score >= 50 ) {
			$grade = 'c';
		} else {
			$grade = 'f';
		}

		return $grade;
	}

	public function get_failed_audits_count() {
		return $this->total_audits_count - $this->passed_audits_count;
	}

	/**
	 * @return int
	 */
	public function get_total_audits_count() {
		return $this->total_audits_count;
	}

	/**
	 * @return int
	 */
	public function get_passed_audits_count() {
		return $this->passed_audits_count;
	}

	public function is_cooling_down() {
		return $this->is_fresh();
	}

	public function is_fresh() {
		if ( ! $this->has_data() ) {
			return false;
		}

		$last_checked = $this->get_timestamp();
		return ( time() - $last_checked ) / 60 < 5;
	}

	public function get_remaining_cooldown_minutes() {
		if ( ! $this->is_cooling_down() ) {
			return 0;
		}

		$minutes_since_last_scan = ( time() - $this->get_timestamp() ) / 60;
		return ceil( 5 - $minutes_since_last_scan );
	}

	public function get_last_checked( $format = false ) {
		$time = $this->get_timestamp();
		if ( empty( $time ) ) {
			return '';
		}

		if ( empty( $format ) ) {
			return sprintf(
				esc_html__( '%s at %s', 'wds' ),
				wp_date( get_option( 'date_format' ), $time ),
				wp_date( get_option( 'time_format' ), $time )
			);
		}

		return wp_date( $format, $time );
	}

	public function get_status_message() {
		if ( $this->score === 100 ) {
			return esc_html__( 'Excellent! Your site is fully optimized!', 'wds' );
		} else if ( $this->score > 89 ) {
			return esc_html__( 'Follow the pending SEO audits for a perfect SEO score.', 'wds' );
		} else if ( $this->score > 49 ) {
			return esc_html__( 'You can improve your score by following the outstanding SEO audits.', 'wds' );
		} else {
			return esc_html__( 'You need to improve your score by following the outstanding SEO audits.', 'wds' );
		}
	}

	public function has_data() {
		return ! empty( $this->timestamp );
	}

	public function get_timestamp() {
		return (int) $this->timestamp;
	}

	public function set_timestamp( $timestamp ) {
		$this->timestamp = $timestamp;
	}

	public function has_errors() {
		return $this->errors->has_errors();
	}

	public function set_error( $code, $message, $data = null ) {
		$this->errors->add( $code, $message, $data );
	}

	public function get_error_code() {
		return $this->errors->get_error_code();
	}

	public function get_error_message() {
		return $this->errors->get_error_message();
	}

	public function set_screenshot( $screenshot ) {
		$this->screenshot = $screenshot;
	}

	public function get_screenshot() {
		return $this->screenshot;
	}

	/**
	 * @return int
	 */
	public function get_screenshot_width() {
		return $this->screenshot_width;
	}

	/**
	 * @param int $screenshot_width
	 */
	public function set_screenshot_width( $screenshot_width ) {
		$this->screenshot_width = $screenshot_width;
	}

	/**
	 * @return int
	 */
	public function get_screenshot_height() {
		return $this->screenshot_height;
	}

	/**
	 * @param int $screenshot_height
	 */
	public function set_screenshot_height( $screenshot_height ) {
		$this->screenshot_height = $screenshot_height;
	}

	private function set_screenshot_nodes( $nodes ) {
		$this->screenshot_nodes = empty( $nodes )
			? array()
			: $nodes;
	}

	public function get_screenshot_node( $id ) {
		$node = smartcrawl_get_array_value( $this->screenshot_nodes, $id );
		return empty( $node )
			? array()
			: $node;
	}

	private function populate_screenshot_data( $metrics ) {
		$screenshot_details = smartcrawl_get_array_value( $metrics, array(
			'full-page-screenshot',
			'details',
		) );

		$nodes = smartcrawl_get_array_value( $screenshot_details, 'nodes' );
		$this->set_screenshot_nodes( $nodes );

		$screenshot = smartcrawl_get_array_value( $screenshot_details, 'screenshot' );
		if (
			! empty( $screenshot['width'] )
			&& ! empty( $screenshot['height'] )
			&& ! empty( $screenshot['data'] )
		) {
			$this->set_screenshot_width( $screenshot['width'] );
			$this->set_screenshot_height( $screenshot['height'] );
			$this->set_screenshot( $screenshot['data'] );
		}
	}

	public function get_device() {
		return $this->device;
	}

	public function set_device( $device ) {
		$this->device = $device;
	}
}
