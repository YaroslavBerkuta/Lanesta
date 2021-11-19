<?php

class Smartcrawl_Check_Subheadings_Keywords extends Smartcrawl_Check_Abstract {

	private $_state = null;
	private $_count;

	public function get_status_msg() {
		if ( is_null( $this->_state ) ) {
			return __( "You don't have any subheadings", 'wds' );
		}

		return false === $this->_state
			? __( "You haven't used your focus keywords in any subheadings", 'wds' )
			: sprintf( __( 'Your focus keyword was found in %d subheadings', 'wds' ), $this->_count );
	}

	public function apply() {
		$subjects = Smartcrawl_Html::find_content( 'h1,h2,h3,h4,h5,h6', $this->get_markup() );
		if ( empty( $subjects ) ) {
			return false;
		} // No subheadings, nothing to check.

		$count = 0;
		foreach ( $subjects as $subject ) {
			if ( $this->has_focus( $subject ) ) {
				$count ++;
			}
		}

		$this->_state = (bool) $count;
		$this->_count = $count;

		return ! ! $this->_state;
	}

	public function get_recommendation() {
		if ( is_null( $this->_state ) ) {
			$message = __( "Using subheadings in your content (such as H2's or H3's) will help both the user and search engines quickly figure out what your article is about. It also helps visually section your content which in turn is great user experience. We recommend you have at least one subheading.", 'wds' );
		} elseif ( $this->_state ) {
			$message = sprintf( __( "You've used keywords in %d of your subheadings which will help both the user and search engines quickly figure out what your article is about, good work!", 'wds' ), $this->_count );
		} else {
			$message = __( "Using keywords in any of your subheadings (such as H2's or H3's) will help both the user and search engines quickly figure out what your article is about. It's best practice to include your focus keywords in at least one subheading if you can.", 'wds' );
		}

		return $message;
	}

	public function get_more_info() {
		return __( "When trying to rank for certain keywords, those keywords should be found in as many key places as possible. Given that you're writing about the topic it only makes sense that you mention it in at least one of your subheadings. Headings are important for users as they break up your content and help readers figure out what the text is about. Same goes for search engines. With that said, don't force keywords into all your titles - keep it natural, readable, and use moderation!", 'wds' );
	}
}
