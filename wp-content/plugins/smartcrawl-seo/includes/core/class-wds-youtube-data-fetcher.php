<?php

class Smartcrawl_Youtube_Data_Fetcher {
	static $api_base = 'https://www.googleapis.com/youtube/v3/videos';
	static $thumbnail_base = 'https://i.ytimg.com/vi/';

	public function __construct() {
	}

	public static function get_video_info( $url, $api_key = false ) {
		if ( empty( $api_key ) ) {
			$api_key = self::get_api_key();
		}

		if ( empty( $api_key ) ) {
			return false;
		}

		$vid = self::get_video_id( $url );
		if ( ! $vid ) {
			return false;
		}

		// Get duration
		$params = array(
			'part' => 'contentDetails,snippet',
			'id'   => $vid,
			'key'  => $api_key,
		);

		$api_url = self::$api_base . '?' . http_build_query( $params );
		$response = wp_remote_get( $api_url );
		if ( is_wp_error( $response ) ) {
			Smartcrawl_Logger::error( $response->get_error_message() );
			return null;
		}

		$result = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( $result === false ) {
			Smartcrawl_Logger::error( "YouTube API response incorrect for video: {$vid}" );
			return null;
		}

		if ( empty( $result['items'][0]['contentDetails'] ) ) {
			return null;
		}
		$video_details = $result['items'][0]['contentDetails'];

		$interval = new DateInterval( $video_details['duration'] );
		$video_details['duration_sec'] = $interval->h * 3600 + $interval->i * 60 + $interval->s;

		$video_details['thumbnail']['default'] = self::$thumbnail_base . $vid . '/default.jpg';
		$video_details['thumbnail']['mqDefault'] = self::$thumbnail_base . $vid . '/mqdefault.jpg';
		$video_details['thumbnail']['hqDefault'] = self::$thumbnail_base . $vid . '/hqdefault.jpg';
		$video_details['thumbnail']['sdDefault'] = self::$thumbnail_base . $vid . '/sddefault.jpg';
		$video_details['thumbnail']['maxresDefault'] = self::$thumbnail_base . $vid . '/maxresdefault.jpg';

		$snippet = array();
		if ( ! empty( $result['items'][0]['snippet'] ) ) {
			$snippet = $result['items'][0]['snippet'];
		}

		$video_details['url'] = $url;

		return array_merge( $video_details, $snippet );
	}

	private static function get_api_key() {
		$options = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SCHEMA );
		$connect_yt = (bool) smartcrawl_get_array_value( $options, 'schema_enable_yt_api' );
		$api_key = (string) smartcrawl_get_array_value( $options, 'schema_yt_api_key' );
		if ( $connect_yt && $api_key ) {
			return trim( $api_key );
		}

		return false;
	}

	private static function is_short_url( $url ) {
		return parse_url( $url, PHP_URL_HOST ) === 'youtu.be';
	}

	private static function get_video_id( $url ) {
		if ( self::is_short_url( $url ) ) {
			$url_parts = explode( '/', $url );
			return array_pop( $url_parts );
		} else {
			parse_str( parse_url( $url, PHP_URL_QUERY ), $youtube_id );

			return smartcrawl_get_array_value( $youtube_id, 'v' );
		}
	}
}
