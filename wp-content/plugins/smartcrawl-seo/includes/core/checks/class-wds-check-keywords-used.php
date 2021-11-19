<?php

class Smartcrawl_Check_Keywords_Used extends Smartcrawl_Check_Post_Abstract {

	/**
	 * Holds post IDs where the focus keywords have been used before
	 *
	 * @var array
	 */
	private $_pids = array();

	/**
	 * Holds check state
	 *
	 * @var int
	 */
	private $_state;

	public function get_status_msg() {
		return false === $this->_state
			? __( "You've used the focus keywords in another article", 'wds' )
			: __( "You haven't used this focus keyword before", 'wds' );
	}

	public function apply() {
		$kws = $this->get_focus();
		if ( empty( $kws ) ) {
			return true;
		}

		global $wpdb;
		$wild = '%';
		$likes_array = array();
		foreach ( $kws as $kw_id => $kw ) {
			$likes_array[] = 'meta_value LIKE %s';
			$kws[ $kw_id ] = $wild . $wpdb->esc_like( $kw ) . $wild;
		}

		$subject = $this->get_subject();
		$subject_id = $this->get_subject_post_id( $subject );

		$likes = join( ' AND ', $likes_array );
		$query = "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_wds_focus-keywords' AND post_id != {$subject_id} AND {$likes}";
		$meta_rows = $wpdb->get_results( $wpdb->prepare( $query, ...$kws ), ARRAY_A );

		$meta_rows = empty( $meta_rows ) ? array() : $meta_rows;
		$post_ids = $this->filter_out_supersets( $meta_rows );

		$this->_state = count( $post_ids ) <= 0;

		if ( ! $this->_state ) {
			$this->_pids = $post_ids;

			return false;
		}

		return true;
	}

	/**
	 * "iphone access france" is a superset of "iphone access" and it should be ignored
	 *
	 * @param $meta_rows array
	 *
	 * @return array
	 */
	private function filter_out_supersets( $meta_rows ) {
		$filtered = array();
		foreach ( $meta_rows as $meta_row ) {
			$post_id = (int) smartcrawl_get_array_value( $meta_row, 'post_id' );
			$raw_focus = smartcrawl_get_array_value( $meta_row, 'meta_value' );
			$focus = $this->prepare_focus( array( $raw_focus ) );

			if ( count( $this->get_focus() ) === count( $focus ) ) {
				$filtered[] = $post_id;
			}
		}

		return $filtered;
	}

	public function get_post_id() {
		$subject = $this->get_subject();

		return is_object( $subject ) ? $subject->ID : $subject;
	}

	public function get_recommendation() {
		if ( $this->_state ) {
			$message = __( "Focus keywords are intended to be unique so you're less likely to write duplicate content. So far all your focus keywords are unique, way to go!", 'wds' );
		} else {
			$message = __( "Focus keywords are intended to be unique so you're less likely to write duplicate content. Consider changing the focus keywords to something unique.", 'wds' );
		}

		return $message;
	}

	public function get_more_info() {
		ob_start();
		?>
		<?php esc_html_e( "Whilst duplicate content isn't technically penalized it presents three rather niggly issues for search engines:", 'wds' ); ?>
		<br/><br/>

		<?php esc_html_e( "1. It's unclear which versions to include/exclude from their indexes.", 'wds' ); ?><br/>
		<?php esc_html_e( "2. They don't know whether to direct the link metrics (trust, authority, anchor text, link equity, etc.) to one page, or keep it separated between multiple versions.", 'wds' ); ?>
		<br/>
		<?php esc_html_e( '3. The engine is unsure which version to rank for query results.', 'wds' ); ?><br/><br/>

		<?php esc_html_e( "So whilst there's no direct penalty, if your content isn't unique then search engine algorithms could be filtering out your articles from their results. The easiest way to make sure this doesn't happen is to try and make each of your posts and pages as unique as possible, hence specifying different focus keywords for each article you write.", 'wds' ); ?>
		<br/><br/>

		<?php printf(
			"%s <a href='https://wpmudev.com/blog/wordpress-canonicalization-guide/' target='_blank'>%s</a>.",
			esc_html__( "Note: If you happen to have two pages with the same content, it's important to tell search engines which one to show in search results using the Canonical URL feature. You can read more about this", 'wds' ),
			esc_html__( 'here' )
		); ?>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param $subject
	 *
	 * @return int
	 */
	private function get_subject_post_id( $subject ) {
		if ( is_a( $subject, 'WP_Post' ) ) {
			$post_parent = wp_is_post_revision( $subject->ID );
			if ( $post_parent ) {
				$subject_id = $post_parent;
			} else {
				$subject_id = $subject->ID;
			}
		} else {
			$subject_id = - 1;
		}

		return $subject_id;
	}

}
