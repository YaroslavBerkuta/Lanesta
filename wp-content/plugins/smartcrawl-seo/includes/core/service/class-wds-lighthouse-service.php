<?php

class Smartcrawl_Lighthouse_Service extends Smartcrawl_Service {
	const OPTION_ID_START_TIME = 'wds-lighthouse-seo-start-timestamp';
	const OPTION_ID_LAST_REPORT = 'wds-lighthouse-seo-last-report';

	const VERB_SEO_CHECK = 'site/seo-check';
	const VERB_SEO_RESULT = 'site/seo-result/latest';
	const VERB_LIGHTHOUSE_STATUS = 'site/set-lighthouse-status';

	public function get_service_base_url() {
		$base_url = 'https://wpmudev.com/';
		if ( defined( 'WPMUDEV_CUSTOM_API_SERVER' ) && WPMUDEV_CUSTOM_API_SERVER ) {
			$base_url = trailingslashit( WPMUDEV_CUSTOM_API_SERVER );
		}

		$api = apply_filters(
			$this->get_filter( 'api-endpoint' ),
			'api'
		);

		$namespace = apply_filters(
			$this->get_filter( 'api-namespace' ),
			'performance/v2'
		);

		return trailingslashit( $base_url ) . trailingslashit( $api ) . trailingslashit( $namespace );
	}

	public function get_known_verbs() {
		return array( self::VERB_SEO_CHECK, self::VERB_SEO_RESULT, self::VERB_LIGHTHOUSE_STATUS );
	}

	public function is_cacheable_verb( $verb ) {
		return false;
	}

	public function get_request_url( $verb ) {
		if ( empty( $verb ) ) {
			return false;
		}

		$domain = apply_filters(
			$this->get_filter( 'domain' ),
			site_url()
		);
		if ( empty( $domain ) ) {
			return false;
		}

		$query_url = http_build_query( array(
			'domain' => $domain,
		) );
		$query_url = $query_url && preg_match( '/^\?/', $query_url ) ? $query_url : "?{$query_url}";

		return trailingslashit( $this->get_service_base_url() ) .
		       $verb .
		       $query_url;
	}

	public function get_request_arguments( $verb ) {
		if ( $verb === self::VERB_SEO_CHECK ) {
			$args = array(
				'method'    => 'POST',
				'blocking'  => false,
				'sslverify' => false,
				'timeout'   => 1,
			);
		}

		if ( $verb === self::VERB_LIGHTHOUSE_STATUS ) {
			$args = array(
				'method'    => 'POST',
				'timeout'   => 1,
				'blocking'  => false,
				'sslverify' => false,
				'body'      => array(
					'active' => Smartcrawl_Health_Settings::is_test_mode_lighthouse(),
				),
			);
		}

		if ( $verb === self::VERB_SEO_RESULT ) {
			$args = array(
				'method'    => 'GET',
				'timeout'   => $this->get_timeout(),
				'sslverify' => false,
			);
		}

		$key = (string) $this->get_dashboard_api_key();
		if ( $key ) {
			$args['headers']['Authorization'] = "Basic {$key}";
		}

		$args = apply_filters(
			$this->get_filter( "lighthouse-args" ),
			$args,
			$verb
		);

		return $args;
	}

	public function handle_error_response( $response, $verb ) {
		return false;
	}

	public function get_start_time() {
		return get_option( self::OPTION_ID_START_TIME, false );
	}

	public function start() {
		update_option(
			self::OPTION_ID_START_TIME,
			current_time( 'timestamp' ),
			false
		);

		$this->request( self::VERB_SEO_CHECK );
	}

	public function stop() {
		delete_option( self::OPTION_ID_START_TIME );
	}

	public function set_error( $code, $message ) {
		return update_option( self::OPTION_ID_LAST_REPORT, array(
			'error'   => true,
			'code'    => $code,
			'message' => $message,
		), false );
	}

	public function refresh_report() {
		$results = $this->request( self::VERB_SEO_RESULT );
		if ( ! $results ) {
			$this->set_error(
				'network-error',
				esc_html__( 'We were unable to connect to the API server.', 'wds' )
			);
			return false;
		}
		update_option( self::OPTION_ID_LAST_REPORT, $results, false );
		return true;
	}

	public function set_lighthouse_status() {
		return $this->request( self::VERB_LIGHTHOUSE_STATUS );
	}

	/**
	 * @param string $device
	 *
	 * @return Smartcrawl_Lighthouse_Report
	 */
	public function get_last_report( $device = 'desktop' ) {
		$report = new Smartcrawl_Lighthouse_Report( $device );
		$last_report = get_option( self::OPTION_ID_LAST_REPORT, false );
		if ( empty( $last_report ) ) {
			return $report;
		}

		if ( ! empty( $last_report['error'] ) ) {
			$report->set_error(
				smartcrawl_get_array_value( $last_report, 'code' ),
				smartcrawl_get_array_value( $last_report, 'message' ),
				smartcrawl_get_array_value( $last_report, 'data' )
			);
			return $report;
		}

		$device_report = smartcrawl_get_array_value( $last_report, array( 'data', $device ) );
		if ( ! $device_report ) {
			$report->set_error(
				'unexpected-error',
				esc_html__( 'An unexpected error occurred', 'wds' )
			);
			return $report;
		}

		$time = smartcrawl_get_array_value( $last_report, array( 'data', 'time' ) );
		$report->set_timestamp( $time );
		$report->populate( $device_report );
		return $report;
	}

	public function maybe_send_emails() {
		if ( ! $this->is_member() || ! Smartcrawl_Lighthouse_Options::is_cron_enabled() ) {
			return;
		}

		$desktop_report = $this->get_last_report( 'desktop' );
		if ( ! $desktop_report->has_data() || $desktop_report->has_errors() ) {
			Smartcrawl_Logger::debug( "Not sending Lighthouse emails because a valid report is not available." );
			return;
		}

		if ( ! $desktop_report->is_fresh() ) {
			Smartcrawl_Logger::debug( "Not sending Lighthouse emails because the latest report is not fresh." );
			return;
		}

		$reporting_condition = Smartcrawl_Lighthouse_Options::reporting_condition();
		$mobile_report = $this->get_last_report( 'mobile' );
		if (
			Smartcrawl_Lighthouse_Options::reporting_condition_enabled()
			&& $reporting_condition
		) {
			$reporting_device = Smartcrawl_Lighthouse_Options::reporting_device();
			$score_higher_than_condition = true;
			if ( $reporting_device === 'both' || $reporting_device === 'desktop' ) {
				$score_higher_than_condition = $score_higher_than_condition && $desktop_report->get_score() >= $reporting_condition;
			}

			if ( $reporting_device === 'both' || $reporting_device === 'mobile' ) {
				$score_higher_than_condition = $score_higher_than_condition && $mobile_report->get_score() >= $reporting_condition;
			}

			if ( $score_higher_than_condition ) {
				Smartcrawl_Logger::debug( "Not sending Lighthouse emails because the required score condition is not met." );
				return;
			}
		}

		Smartcrawl_Logger::debug( "Sending Lighthouse emails." );
		$this->send_emails();
	}

	private function send_emails() {
		$recipients = Smartcrawl_Lighthouse_Options::email_recipients();
		$desktop_report = $this->get_last_report( 'desktop' );
		$mobile_report = $this->get_last_report( 'mobile' );

		foreach ( $recipients as $recipient ) {
			$recipient_name = smartcrawl_get_array_value( $recipient, 'name' );
			$recipient_email = smartcrawl_get_array_value( $recipient, 'email' );
			$reporting_device = Smartcrawl_Lighthouse_Options::reporting_device();
			if ( $reporting_device === 'desktop' || $reporting_device === 'mobile' ) {
				$subject = sprintf(
					esc_html__( 'SEO Report for %s - Score %s', 'wds' ),
					site_url(),
					$reporting_device === 'desktop'
						? $desktop_report->get_score()
						: $mobile_report->get_score()
				);
			} else {
				$subject = sprintf(
					esc_html__( 'SEO Report for %s - Desktop score %s / Mobile score %s', 'wds' ),
					site_url(),
					$desktop_report->get_score(),
					$mobile_report->get_score()
				);
			}
			$email_content = Smartcrawl_Simple_Renderer::load( 'emails/email-body', array(
				'email_template'      => 'emails/lighthouse-email',
				'email_template_args' => array(
					'desktop_report' => $desktop_report,
					'mobile_report'  => $mobile_report,
					'username'       => $recipient_name,
					'device'         => $reporting_device,
				),
			) );
			$email_content = stripslashes( $email_content );
			$no_reply_email = 'noreply@' . wp_parse_url( get_site_url(), PHP_URL_HOST );
			$headers = array(
				'From: Smartcrawl <' . $no_reply_email . '>',
				'Content-Type: text/html; charset=UTF-8',
			);

			wp_mail( $recipient_email, $subject, $email_content, $headers );
		}
	}

	public function clear_last_report() {
		return delete_option( self::OPTION_ID_LAST_REPORT );
	}
}
