<?php

class Smartcrawl_Checkup_Options {
	const OPTION_ID = 'wds_checkup_options';

	const CRON_ENABLE = 'checkup-cron-enable';
	const REPORTING_FREQUENCY = 'checkup-frequency';
	const REPORTING_DOW = 'checkup-dow';
	const REPORTING_TOD = 'checkup-tod';
	const RECIPIENTS = 'checkup-email-recipients';

	public static function is_cron_enabled() {
		return smartcrawl_get_array_value( self::get_options(), self::CRON_ENABLE );
	}

	public static function reporting_frequency() {
		return smartcrawl_get_array_value( self::get_options(), self::REPORTING_FREQUENCY );
	}

	public static function reporting_dow() {
		return smartcrawl_get_array_value( self::get_options(), self::REPORTING_DOW );
	}

	public static function reporting_tod() {
		return smartcrawl_get_array_value( self::get_options(), self::REPORTING_TOD );
	}

	public static function email_recipients() {
		$recipients = smartcrawl_get_array_value( self::get_options(), self::RECIPIENTS );
		return empty( $recipients )
			? array()
			: $recipients;
	}

	public static function get_all_recipients() {
		$email_recipients = array();
		$options = Smartcrawl_Settings::get_specific_options( self::OPTION_ID );
		$new_recipients = Smartcrawl_Checkup_Options::email_recipients();
		$old_recipients = empty( $options['email-recipients'] )
			? array()
			: $options['email-recipients'];

		foreach ( $old_recipients as $user_id ) {
			if ( ! is_numeric( $user_id ) ) {
				continue;
			}
			$old_recipient = self::get_email_recipient( $user_id );
			if ( self::recipient_exists( $old_recipient, $new_recipients ) ) {
				continue;
			}

			$email_recipients[] = $old_recipient;
		}

		return array_merge(
			$email_recipients,
			$new_recipients
		);
	}

	private static function recipient_exists( $recipient, $recipient_array ) {
		$emails = array_column( $recipient_array, 'email' );
		$needle = (string) smartcrawl_get_array_value( $recipient, 'email' );

		return in_array( $needle, $emails, true );
	}

	public static function save_defaults() {
		$options = Smartcrawl_Settings::get_specific_options( self::OPTION_ID );
		$options = is_array( $options ) ? $options : array();
		$defaults = array_merge(
			self::get_defaults(),
			array(
				self::RECIPIENTS    => array( self::get_email_recipient() ),
				self::REPORTING_DOW => rand( 0, 6 ),
				self::REPORTING_TOD => rand( 0, 23 ),
			)
		);
		foreach ( $defaults as $opt => $default ) {
			if ( ! isset( $options[ $opt ] ) ) {
				$options[ $opt ] = $default;
			}
		}
		Smartcrawl_Settings::update_specific_options( self::OPTION_ID, $options );
	}

	private static function get_defaults() {
		return array(
			self::CRON_ENABLE         => false,
			self::REPORTING_FREQUENCY => 'weekly',
			self::REPORTING_DOW       => 0,
			self::REPORTING_TOD       => 0,
			self::RECIPIENTS          => array(),
		);
	}

	private static function get_email_recipient( $user_id = false ) {
		if ( $user_id ) {
			$user = Smartcrawl_Model_User::get( $user_id );
		} else {
			$user = Smartcrawl_Model_User::owner();
		}

		return array(
			'name'  => $user->get_display_name(),
			'email' => $user->get_email(),
		);
	}

	private static function get_options() {
		$options = Smartcrawl_Settings::get_specific_options( self::OPTION_ID );

		return array_merge(
			self::get_defaults(),
			empty( $options ) ? array() : $options
		);
	}

	public static function save_form_data( array $input ) {
		$result = array();
		$email_recipients = smartcrawl_get_array_value( $input, self::RECIPIENTS );
		$sanitized_recipients = smartcrawl_sanitize_recipients( $email_recipients );
		$result[ self::RECIPIENTS ] = $sanitized_recipients;

		if ( empty( $sanitized_recipients ) ) {
			$result[ self::RECIPIENTS ] = array( self::get_email_recipient() );
		}

		if ( empty( $input[ self::CRON_ENABLE ] ) || empty( $sanitized_recipients ) ) {
			$result[ self::CRON_ENABLE ] = false;
		} else {
			$result[ self::CRON_ENABLE ] = true;
		}
		$frequency = ! empty( $input[ self::REPORTING_FREQUENCY ] )
			? Smartcrawl_Controller_Cron::get()->get_valid_frequency( $input[ self::REPORTING_FREQUENCY ] )
			: Smartcrawl_Controller_Cron::get()->get_default_frequency();
		$result[ self::REPORTING_FREQUENCY ] = $frequency;

		$result[ self::REPORTING_DOW ] = self::validate_dow(
			$frequency,
			(int) smartcrawl_get_array_value( $input, self::REPORTING_DOW )
		);

		$tod = isset( $input[ self::REPORTING_TOD ] ) && is_numeric( $input[ self::REPORTING_TOD ] )
			? (int) $input[ self::REPORTING_TOD ]
			: 0;
		$result[ self::REPORTING_TOD ] = in_array( $tod, range( 0, 23 ), true ) ? $tod : 0;

		Smartcrawl_Settings::update_specific_options( self::OPTION_ID, $result );
	}

	private static function validate_dow( $frequency, $dow ) {
		if ( $frequency === 'monthly' ) {
			return in_array( $dow, range( 1, 28 ), true ) ? $dow : 1;
		} else {
			return in_array( $dow, range( 0, 6 ), true ) ? $dow : 0;
		}
	}
}
