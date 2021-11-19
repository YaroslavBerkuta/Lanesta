<?php

class Smartcrawl_Check_Metadesc_Length extends Smartcrawl_Check_Post_Abstract {

	/**
	 * Holds metadesc length
	 *
	 * @var int
	 */
	private $_length;

	/**
	 * Holds check state
	 *
	 * @var int
	 */
	private $_state;

	public function get_status_msg() {
		if ( ! is_numeric( $this->_state ) ) {
			return __( 'Your meta description is a good length', 'wds' );
		}

		return 0 === $this->_state
			? __( "You haven't specified a meta description yet", 'wds' )
			: ( $this->_state > 0
				? sprintf( __( 'Your meta description is greater than %d characters', 'wds' ), $this->get_max() )
				: sprintf( __( 'Your meta description is less than %d characters', 'wds' ), $this->get_min() )
			);
	}

	public function get_max() {
		return smartcrawl_metadesc_max_length();
	}

	public function get_min() {
		return smartcrawl_metadesc_min_length();
	}

	public function apply() {
		$post = $this->get_subject();
		$subject = false;
		$resolver = false;

		if ( ! is_object( $post ) || empty( $post->ID ) ) {
			$subject = $this->get_markup();
		} else {
			$resolver = Smartcrawl_Endpoint_Resolver::resolve();
			$resolver->simulate_post( $post->ID );

			$subject = Smartcrawl_Meta_Value_Helper::get()->get_description();
		}

		$this->_state = $this->is_within_char_length( $subject, $this->get_min(), $this->get_max() );
		$this->_length = Smartcrawl_String::len( $subject );

		if ( $resolver ) {
			$resolver->stop_simulation();
		}

		return ! is_numeric( $this->_state );
	}

	public function apply_html() {
		$subjects = Smartcrawl_Html::find_attributes( 'meta[name="description"]', 'content', $this->get_markup() );
		if ( empty( $subjects ) ) {
			$this->_length = 0;
			$this->_state = 0;

			return false;
		}

		$subject = reset( $subjects );
		$this->_state = $this->is_within_char_length( $subject, $this->get_min(), $this->get_max() );
		$this->_length = Smartcrawl_String::len( $subject );

		return ! is_numeric( $this->_state );
	}

	public function get_recommendation() {
		if ( ! is_numeric( $this->_state ) ) {
			return __( 'Your SEO description is a good length. Having an SEO description that is either too long or too short can harm your chances of ranking highly for this article.', 'wds' );
		}

		return 0 === $this->_state
			? __( "Because you haven't specified a meta description (or excerpt), search engines will automatically generate one using your content. While this is OK, you should create your own meta description making sure it contains your focus keywords.", 'wds' )
			: ( $this->_state > 0
				? __( "Your SEO description (or excerpt) is currently too long. Search engines generally don't like long descriptions and after a certain length the value of extra keywords drops significantly.", 'wds' )
				: __( 'Your SEO description (or excerpt) is currently too short which means it has less of a chance ranking for your chosen focus keywords.', 'wds' )
			);
	}

	public function get_more_info() {
		return sprintf(
			__( 'We recommend keeping your meta descriptions between %1$d and %2$d characters (including spaces). Doing so achieves a nice balance between populating your description with keywords to rank highly in search engines, and also keeping it to a readable length that won\'t be cut off in search engine results. Unfortunately there isn\'t a rule book for SEO meta descriptions, just remember to make your description great for SEO, but also (most importantly) readable and enticing for potential visitors to click on.', 'wds' ),
			$this->get_min(),
			$this->get_max()
		);
	}
}
