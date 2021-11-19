<?php

class Smartcrawl_Controller_Media_Schema_Data extends Smartcrawl_Base_Controller {
	const META_KEY = '_wds_post_media_schema_cache';
	/**
	 * Singleton instance
	 *
	 * @var self
	 */
	private static $_instance;

	private $oembed_provider = false;
	private $embed_url = false;

	/**
	 * @return self instance
	 */
	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	protected function init() {
		add_action( 'save_post', array( $this, 'handle_post_save' ) );
		add_filter( 'oembed_fetch_url', array( $this, 'intercept_embed_response' ), 10, 3 );
	}

	public function intercept_embed_response( $provider, $url, $args ) {
		global $post;
		if (
			is_main_query()
			&& is_singular()
			&& ! empty( $post )
			&& $this->is_supported_media( $url )
		) {
			$this->oembed_provider = $provider;
			$this->embed_url = $url;
			add_filter( 'http_response', array( $this, 'save_intercepted_embed_response' ), 10, 3 );
		}

		return $provider;
	}

	public function save_intercepted_embed_response( $response, $parsed_args, $provider ) {
		global $post;

		$is_expected_provider = strpos( $provider, $this->oembed_provider ) !== false;
		if ( $is_expected_provider && ! is_wp_error( $response ) && ! empty( $post->ID ) ) {

			$data = json_decode( wp_remote_retrieve_body( $response ) );
			if ( $data !== false ) {
				$cache = $this->get_cache( $post->ID );

				$media_type = $this->is_supported_audio( $this->embed_url ) ? 'audio' : 'video';
				$key = $this->get_item_data_key( $this->embed_url );
				$data = $this->prepare_oembed_data( $this->embed_url, $data );
				if ( $data ) {
					$cache[ $media_type ][ $key ] = $data;
				}

				$this->set_cache( $post->ID, $cache );

				$this->embed_url = $this->oembed_provider = false;
			}
		}

		remove_filter( 'http_response', array( $this, 'save_intercepted_embed_response' ) );

		return $response;
	}

	public function handle_post_save( $post_id ) {
		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}

		$post = get_post( $post_id );
		$urls = $this->extract_supported_urls( $post->post_content );

		$previous_data = $this->get_cache( $post_id );
		$cache = array();
		foreach ( $urls as $key => $url ) {
			if ( $this->is_supported_audio( $url ) ) {
				$audio_data_key = $this->get_item_data_key( $url );
				$audio_data = smartcrawl_get_array_value( $previous_data, array( 'audio', $audio_data_key ) );
				if ( empty( $audio_data ) ) {
					$audio_data = $this->fetch_oembed_data( $url );
				}
				if ( $audio_data ) {
					$cache['audio'][ $audio_data_key ] = $audio_data;
				}
			}

			if ( $this->is_youtube_url( $url ) ) {
				$youtube_data_key = $this->get_item_data_key( $url );
				$youtube_data = smartcrawl_get_array_value( $previous_data, array( 'youtube', $youtube_data_key ) );
				if ( empty( $youtube_data ) ) {
					$youtube_data = Smartcrawl_Youtube_Data_Fetcher::get_video_info( $url );
				}
				if ( $youtube_data ) {
					$cache['youtube'][ $youtube_data_key ] = $youtube_data;
				}
			}

			if ( $this->is_supported_video( $url ) ) {
				$video_data_key = $this->get_item_data_key( $url );
				$video_data = smartcrawl_get_array_value( $previous_data, array( 'video', $video_data_key ) );
				if ( empty( $video_data ) ) {
					$video_data = $this->fetch_oembed_data( $url );
				}
				if ( $video_data ) {
					$cache['video'][ $video_data_key ] = $video_data;
				}
			}
		}

		if ( $cache ) {
			$this->set_cache( $post_id, $cache );
		} else {
			$this->delete_cache( $post_id );
		}
	}

	/**
	 * @param $post WP_Post
	 */
	public function maybe_refresh_wp_embeds_cache( $post ) {
		$supported_urls = $this->extract_supported_urls( $post->post_content );
		$cache = $this->get_cache( $post->ID );
		if ( ! empty( $supported_urls ) && empty( $cache ) ) {
			$this->refresh_wp_embeds_cache( $post );
		}
	}

	/**
	 * @param $post WP_Post
	 */
	private function refresh_wp_embeds_cache( $post ) {
		$zero_oembed_ttl = function () {
			return 0;
		};
		add_filter( 'oembed_ttl', $zero_oembed_ttl );

		global $wp_embed;
		$wp_embed->cache_oembed( $post->ID );

		remove_filter( 'oembed_ttl', $zero_oembed_ttl );
	}

	private function extract_supported_urls( $post_content ) {
		$urls = wp_extract_urls( $post_content );

		return array_filter( $urls, array( $this, 'is_supported_media' ) );
	}

	private function get_item_data_key( $url ) {
		return md5( trim( $url ) );
	}

	public function get_cache( $post_id ) {
		$cache = get_post_meta( $post_id, self::META_KEY, true );
		return empty( $cache ) ? array() : $cache;
	}

	private function set_cache( $post_id, $value ) {
		return update_post_meta( $post_id, self::META_KEY, $value );
	}

	private function delete_cache( $post_id ) {
		return delete_post_meta( $post_id, self::META_KEY );
	}

	private function fetch_oembed_data( $url ) {
		$url = trim( $url ); // remove white spaces if any
		$autoembed = $this->get_oembed();
		$provider = $autoembed->get_provider( $url );

		if ( filter_var( $provider, FILTER_VALIDATE_URL ) === false ) {
			return array();
		}

		return $this->prepare_oembed_data(
			$url,
			$autoembed->fetch( $provider, $url )
		);
	}

	private function prepare_oembed_data( $url, $data ) {
		if ( ! $data ) {
			return array();
		}

		$data->url = $url;
		return $data ? (array) $data : array();
	}

	private function get_oembed() {
		if ( ! class_exists( 'WP_oEmbed' ) ) {
			require_once( ABSPATH . WPINC . '/class-oembed.php' );
		}

		return new WP_oEmbed();
	}

	private function url_has_domain( $url, $domains ) {
		$domains = join( '|', array_map( 'preg_quote', $domains ) );

		return (bool) preg_match( "~http(s)?://([^.]*.)?({$domains}).*~", $url );
	}

	private function is_youtube_url( $url ) {
		return $this->url_has_domain( $url, array( 'youtube.com', 'youtu.be' ) );
	}

	private function is_supported_media( $url ) {
		return $this->url_has_domain( $url, array_merge(
			$this->get_supported_video_domains(),
			$this->get_supported_audio_domains()
		) );
	}

	private function is_supported_video( $url ) {
		return $this->url_has_domain( $url, $this->get_supported_video_domains() );
	}

	private function is_supported_audio( $url ) {
		return $this->url_has_domain( $url, $this->get_supported_audio_domains() );
	}

	private function get_supported_audio_domains() {
		return array(
			'soundcloud.com',
			'mixcloud.com',
			'spotify.com',
		);
	}

	private function get_supported_video_domains() {
		return array(
			'ted.com',
			'vimeo.com',
			'dailymotion.com',
			'videopress.com',
			'vine.com',
			'youtube.com',
			'youtu.be',
		);
	}
}
