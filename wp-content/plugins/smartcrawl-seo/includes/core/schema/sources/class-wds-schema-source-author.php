<?php

class Smartcrawl_Schema_Source_Author extends Smartcrawl_Schema_Property_Source {
	const ID = 'author';

	const FULL_NAME = 'author_full_name';
	const FIRST_NAME = 'author_first_name';
	const LAST_NAME = 'author_last_name';
	const URL = 'author_url';
	const DESCRIPTION = 'author_description';
	const GRAVATAR = 'author_gravatar';
	const GRAVATAR_URL = 'author_gravatar_url';
	const PROFILE_URLS = 'author_profile_urls';
	const EMAIL = 'author_email';

	private $post;
	private $field;

	public function __construct( $post, $field ) {
		parent::__construct();

		$this->post = $post;
		$this->field = $field;
	}

	public function get_value() {
		$user = Smartcrawl_Model_User::get( $this->post->post_author );
		$user_url = $this->get_user_url( $user );

		switch ( $this->field ) {
			case self::FULL_NAME:
				return $this->get_user_full_name( $user );

			case self::FIRST_NAME:
				return $user->get_first_name();

			case self::LAST_NAME:
				return $user->get_last_name();

			case self::URL:
				return $user_url;

			case self::DESCRIPTION:
				return $user->get_description();

			case self::GRAVATAR_URL:
				return $user->get_avatar_url( 100 );

			case self::EMAIL:
				return $user->get_email();

			case self::GRAVATAR:
				return $this->utils->get_image_schema(
					$this->utils->url_to_id( $user_url, '#schema-author-gravatar' ),
					$user->get_avatar_url( 100 ),
					100,
					100
				);

			case self::PROFILE_URLS:
				return $this->get_user_urls( $user );

			default:
				return '';
		}
	}

	/**
	 * @param $user Smartcrawl_Model_User
	 *
	 * @return string
	 */
	private function get_user_full_name( $user ) {
		return $this->utils->apply_filters( 'user-full_name', $user->get_full_name(), $user );
	}

	/**
	 * @param $user Smartcrawl_Model_User
	 *
	 * @return string
	 */
	private function get_user_url( $user ) {
		return $this->utils->apply_filters( 'user-url', $user->get_user_url(), $user );
	}

	/**
	 * @param $user Smartcrawl_Model_User
	 */
	private function get_user_urls( $user ) {
		return $this->utils->apply_filters( 'user-urls', $user->get_user_urls(), $user );
	}
}
