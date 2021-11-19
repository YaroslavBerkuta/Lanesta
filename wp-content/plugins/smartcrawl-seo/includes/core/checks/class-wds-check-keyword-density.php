<?php

class Smartcrawl_Check_Keyword_Density extends Smartcrawl_Check_Abstract {

	/**
	 * Holds check state
	 *
	 * @var int
	 */
	private $_state;

	private $_density = null;

	public function get_status_msg() {
		return $this->choose_status_message(
			__( "You haven't used any keywords yet", 'wds' ),
			__( 'Your keyword density is between %1$d%% and %2$d%%', 'wds' ),
			__( 'Your keyword density is less than %1$d%%', 'wds' ),
			__( 'Your keyword density is greater than %2$d%%', 'wds' )
		);
	}

	private function choose_status_message( $no_keywords, $correct_density, $low_density, $high_density ) {
		$keyword_density = $this->_density ? round( $this->_density, 2 ) : 0;

		if ( $keyword_density === 0 ) {
			$message = $no_keywords;
		} elseif ( $this->_state ) {
			$message = $correct_density;
		} else {
			if ( $keyword_density < $this->get_min() ) {
				$message = $low_density;
			} else {
				$message = $high_density;
			}
		}

		return sprintf( $message, $this->get_min(), $this->get_max(), $keyword_density );
	}

	public function get_min() {
		return 1;
	}

	public function get_max() {
		return 3;
	}

	public function apply() {
		$markup = $this->get_markup();
		if ( empty( $markup ) ) {
			$this->_state = false;

			return ! ! $this->_state;
		}

		$kws = $this->get_focus();
		if ( empty( $kws ) ) {
			$this->_state = true;

			return ! ! $this->_state; // Can't determine kw density
		}
		$words = Smartcrawl_String::words( Smartcrawl_Html::plaintext( $markup ) );
		$freq = array_count_values( $words );
		$densities = array();
		if ( ! empty( $words ) ) {
			foreach ( $kws as $kw ) {
				$dns = isset( $freq[ $kw ] ) ? $freq[ $kw ] : 0;
				$densities[ $kw ] = ( $dns / count( $words ) ) * 100;
			}
		}
		$density = ! empty( $densities )
			? array_sum( array_values( $densities ) ) / count( $densities )
			: 0;
		$this->_density = $density;

		$this->_state = $density >= $this->get_min() && $density <= $this->get_max();

		return ! ! $this->_state;
	}

	public function get_recommendation() {
		return $this->choose_status_message(
			__( 'Currently you haven\'t used any keywords in your content. The recommended density is %1$d-%2$d%%. A low keyword density means your content has less chance of ranking highly for your chosen focus keywords.', 'wds' ),
			__( 'Your keyword density is %3$s%% which is within the recommended %1$d-%2$d%%, nice work! This means your content has a better chance of ranking highly for your chosen focus keywords, without appearing as spam.', 'wds' ),
			__( 'Currently your keyword density is %3$s%% which is below the recommended %1$d-%2$d%%. A low keyword density means your content has less chance of ranking highly for your chosen focus keywords.', 'wds' ),
			__( 'Currently your keyword density is %3$s%% which is greater than the recommended %1$d-%2$d%%. If your content is littered with too many focus keywords, search engines can penalize your content and mark it as spam.', 'wds' )
		);
	}

	public function get_more_info() {
		$message = __( 'Keyword density is all about making sure your content is populated with enough keywords to give it a better chance of appearing higher in search results. One way of making sure people will be able to find our content is using particular focus keywords, and using them as much as naturally possible in our content. In doing this we are trying to match up the keywords that people are likely to use when searching for this article or page, so try to get into your visitors mind and picture them typing a search into Google. While we recommend aiming for %1$d-%2$d%% density, remember content is king and you don\'t want your article to end up sounding like a robot. Get creative and utilize the page title, image caption, and subheadings.', 'wds' );

		return sprintf(
			$message,
			$this->get_min(),
			$this->get_max()
		);
	}
}
