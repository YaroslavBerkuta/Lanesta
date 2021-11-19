<?php
/**
 * Periodical execution module
 *
 * @package wpmu-dev-seo
 */

/**
 * Cron controller
 */
class Smartcrawl_Controller_Cron {

	const ACTION_CRAWL = 'wds-cron-start_service';
	const ACTION_CHECKUP = 'wds-cron-start_checkup';
	const ACTION_CHECKUP_RESULT = 'wds-cron-checkup_result';

	/**
	 * Singleton instance
	 *
	 * @var Smartcrawl_Controller_Cron
	 */
	private static $_instance;

	/**
	 * Controller actively running flag
	 *
	 * @var bool
	 */
	private $_is_running = false;

	/**
	 * Constructor
	 */
	private function __construct() {
	}

	/**
	 * Singleton instance getter
	 *
	 * @return object Smartcrawl_Controller_Cron instance
	 */
	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Boots controller interface
	 *
	 * @return bool
	 */
	public function run() {
		if ( ! $this->is_running() ) {
			$this->_add_hooks();
		}

		return $this->is_running();
	}

	/**
	 * Check whether controller interface is active
	 *
	 * @return bool
	 */
	public function is_running() {
		return ! ! $this->_is_running;
	}

	/**
	 * Sets up controller listening interface
	 *
	 * Also sets up controller running flag approprietly.
	 *
	 * @return void
	 */
	private function _add_hooks() {

		add_filter( 'cron_schedules', array( $this, 'add_cron_schedule_intervals' ) );

		$copts = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SITEMAP );
		if ( ! empty( $copts['crawler-cron-enable'] ) ) {
			add_action( $this->get_filter( self::ACTION_CRAWL ), array( $this, 'start_crawl' ) );
		}

		if ( Smartcrawl_Checkup_Options::is_cron_enabled() ) {
			add_action( $this->get_filter( self::ACTION_CHECKUP ), array( $this, 'start_checkup' ) );
			add_action( $this->get_filter( self::ACTION_CHECKUP_RESULT ), array( $this, 'check_checkup_result' ) );
		}

		$this->_is_running = true;
	}

	/**
	 * Gets prefixed filter action
	 *
	 * @param string $what Filter action suffix.
	 *
	 * @return string Full filter action
	 */
	public function get_filter( $what ) {
		return 'wds-controller-cron-' . $what;
	}

	/**
	 * Controller interface stop
	 *
	 * @return bool
	 */
	public function stop() {
		if ( $this->is_running() ) {
			$this->_remove_hooks();
		}

		return $this->is_running();
	}

	/**
	 * Tears down controller listening interface
	 *
	 * Also sets up controller running flag approprietly.
	 *
	 * @return void
	 */
	private function _remove_hooks() {

		remove_action( $this->get_filter( self::ACTION_CRAWL ), array( $this, 'start_crawl' ) );
		remove_action( $this->get_filter( self::ACTION_CHECKUP ), array( $this, 'start_checkup' ) );
		remove_filter( 'cron_schedules', array( $this, 'add_cron_schedule_intervals' ) );

		$this->_is_running = false;
	}

	/**
	 * Gets estimated next event time based on parameters
	 *
	 * @param int $pivot Pivot time - base estimation relative to this (UNIX timestamp).
	 * @param strng $frequency Valid frequency interval.
	 * @param int $dow Day of the week (0-6).
	 * @param int $tod Time of day (0-23).
	 *
	 * @return int Estimated next event time as UNIX timestamp
	 */
	public function get_estimated_next_event( $pivot, $frequency, $dow, $tod ) {
		$start = $this->get_initial_pivot_time( $pivot, $frequency );
		$offset = $start + ( $dow * DAY_IN_SECONDS );
		$time = strtotime( date( "Y-m-d {$tod}:00", $offset ) );
		$freqs = array(
			'daily'   => DAY_IN_SECONDS,
			'weekly'  => 7 * DAY_IN_SECONDS,
			'monthly' => 30 * DAY_IN_SECONDS,
		);
		if ( $time > $pivot ) {
			return $time;
		}

		$freq = $freqs[ $this->get_valid_frequency( $frequency ) ];

		return $time + $freq;
	}

	/**
	 * Gets primed pivot time for a given frequency value
	 *
	 * @param int $pivot Raw pivot UNIX timestamp.
	 * @param string $frequency Frequency interval.
	 *
	 * @return int Zeroed pivot time for given frequency interval
	 */
	public function get_initial_pivot_time( $pivot, $frequency ) {
		$frequency = $this->get_valid_frequency( $frequency );

		if ( 'daily' === $frequency ) {
			return strtotime( date( 'Y-m-d 00:00', $pivot ) );
		}

		if ( 'weekly' === $frequency ) {
			$monday = strtotime( 'this monday', $pivot );
			if ( $monday > $pivot ) {
				return $monday - ( 7 * DAY_IN_SECONDS );
			}

			return $monday;
		}

		if ( 'monthly' === $frequency ) {
			$day = (int) date( 'd', $pivot );
			$today = strtotime( date( 'Y-m-d H:i', $pivot ) );

			return $today - ( $day * DAY_IN_SECONDS );
		}

		return $pivot;
	}

	/**
	 * Gets validated frequency interval
	 *
	 * @param string $freq Raw frequency string.
	 *
	 * @return string
	 */
	public function get_valid_frequency( $freq ) {
		if ( in_array( $freq, array_keys( $this->get_frequencies() ), true ) ) {
			return $freq;
		}

		return $this->get_default_frequency();
	}

	/**
	 * Gets a list of frequency intervals
	 *
	 * @return array
	 */
	public function get_frequencies() {
		return array(
			'daily'   => __( 'Daily', 'wds' ),
			'weekly'  => __( 'Weekly', 'wds' ),
			'monthly' => __( 'Monthly', 'wds' ),
		);
	}

	/**
	 * Gets default frequency interval (fallback)
	 *
	 * @return string
	 */
	public function get_default_frequency() {
		return 'weekly';
	}

	/**
	 * Checks whether we have a next event scheduled
	 *
	 * @param string $event Optional event name, defaults to service start.
	 *
	 * @return bool
	 */
	public function has_next_event( $event = false ) {
		return ! ! $this->get_next_event( $event );
	}

	/**
	 * Gets next scheduled event time
	 *
	 * @param string $event Optional event name, defaults to service start.
	 *
	 * @return int|bool UNIX timestamp or false if no next event
	 */
	public function get_next_event( $event = false ) {
		$event = ! empty( $event ) ? $event : self::ACTION_CRAWL;

		return wp_next_scheduled( $this->get_filter( $event ) );
	}

	/**
	 * Unschedules a particular event
	 *
	 * @param string $event Optional event name, defaults to service start.
	 *
	 * @return bool
	 */
	public function unschedule( $event = false ) {
		return false; // Not in the free version.
	}

	/**
	 * Schedules a particular event
	 *
	 * @param string $event Event name.
	 * @param int $time UNIX timestamp.
	 * @param string $recurrence Event recurrence.
	 *
	 * @return bool
	 */
	public function schedule( $event, $time, $recurrence = false ) {
		return false; // Not in the free version.
	}

	/**
	 * Sets up overall schedules
	 *
	 * @return void
	 * @uses Smartcrawl_Controller_Cron::set_up_crawler_schedule()
	 *
	 * @uses Smartcrawl_Controller_Cron::set_up_checkup_schedule()
	 */
	public function set_up_schedule() {
		Smartcrawl_Logger::debug( 'Setting up schedules' );
		$this->set_up_crawler_schedule();
		$this->set_up_checkup_schedule();
	}

	/**
	 * Sets up crawl service schedule
	 *
	 * @return bool
	 */
	public function set_up_crawler_schedule() {
		return false; // Not in the free version.
	}

	/**
	 * Sets up checkup service schedule
	 *
	 * @return bool
	 */
	public function set_up_checkup_schedule() {
		return false; // Not in the free version.
	}

	/**
	 * Starts crawl
	 *
	 * @return bool
	 */
	public function start_crawl() {
		return false; // Not in the free version.
	}

	/**
	 * Starts checkup
	 *
	 * @return bool
	 */
	public function start_checkup() {
		return false; // Not in the free version.
	}

	/**
	 * Checks checkup result for cron action
	 *
	 * Schedules another singular check after timeout if not ready yet.
	 *
	 * @return bool
	 */
	public function check_checkup_result() {
		return false; // Not in the free version.
	}

	/**
	 * Gets time delay for checkup ping individual requests
	 *
	 * @return int
	 */
	public function get_checkup_ping_delay() {
		return 600;
	}

	/**
	 * Set up cron schedule intervals
	 *
	 * @param array $intervals Intervals known this far.
	 *
	 * @return array
	 */
	public function add_cron_schedule_intervals( $intervals ) {
		if ( ! is_array( $intervals ) ) {
			return $intervals;
		}

		if ( ! isset( $intervals['daily'] ) ) {
			$intervals['daily'] = array(
				'display'  => __( 'SmartCrawl Daily', 'wds' ),
				'interval' => DAY_IN_SECONDS,
			);
		}

		if ( ! isset( $intervals['weekly'] ) ) {
			$intervals['weekly'] = array(
				'display'  => __( 'SmartCrawl Weekly', 'wds' ),
				'interval' => 7 * DAY_IN_SECONDS,
			);
		}

		if ( ! isset( $intervals['monthly'] ) ) {
			$intervals['monthly'] = array(
				'display'  => __( 'SmartCrawl Monthly', 'wds' ),
				'interval' => 30 * DAY_IN_SECONDS,
			);
		}

		return $intervals;
	}

	/**
	 * Clone
	 */
	private function __clone() {
	}
}
