<?php

class Smartcrawl_Controller_Ajax_Search extends Smartcrawl_Base_Controller {
	private static $_instance;

	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	protected function init() {
		add_action( 'wp_ajax_wds-search-post', array( $this, 'search_post' ) );
	}

	public function search_post() {
		$search_query = smartcrawl_get_array_value( $_GET, 'term' );
		$post_type = smartcrawl_get_array_value( $_GET, 'type' );
		$request_type = smartcrawl_get_array_value( $_GET, 'request_type' );
		$post_id = smartcrawl_get_array_value( $_GET, 'id' );
		$results = array();
		if ( empty( $search_query ) && empty( $post_id ) ) {
			wp_send_json( array( 'results' => $results ) );
			return;
		}

		$args = array(
			'post_status'         => $post_type === 'attachment' ? 'inherit' : 'publish',
			'posts_per_page'      => 10,
			'ignore_sticky_posts' => true,
			'post_type'           => $post_type,
			's'                   => $search_query,
		);
		if ( $request_type === 'text' && $post_id ) {
			$args['post__in'] = is_array( $post_id ) ? $post_id : array( $post_id );
		}
		$posts = get_posts( $args );
		foreach ( $posts as $post ) {
			$results[] = array(
				'id'   => $post->ID,
				'text' => $post->post_title,
			);
		}
		wp_send_json( array( 'results' => $results ) );
	}
}
