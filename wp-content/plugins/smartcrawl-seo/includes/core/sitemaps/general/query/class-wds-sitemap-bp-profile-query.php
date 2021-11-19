<?php

class Smartcrawl_Sitemap_BP_Profile_Query extends Smartcrawl_Sitemap_Query {
	const TYPE = 'bp_profile';

	public function get_supported_types() {
		return array( self::TYPE );
	}

	public function get_items( $type = '', $page_number = 0 ) {
		if ( ! $this->can_return_items() ) {
			return array();
		}

		$users = $this->get_users( $page_number );
		$items = array();
		foreach ( $users as $user ) {
			$url = bp_core_get_user_domain( $user->id );
			if ( $this->is_role_excluded( $user ) || Smartcrawl_Sitemap_Utils::is_url_ignored( $url ) ) {
				continue;
			}

			$item = new Smartcrawl_Sitemap_Item();
			$item->set_location( $url )
			     ->set_priority( 0.2 )
			     ->set_change_frequency( Smartcrawl_Sitemap_Item::FREQ_WEEKLY )
			     ->set_last_modified( strtotime( $user->last_activity ) )
			     ->set_images( $this->get_user_images( $user->id ) );

			$items[] = $item;
		}

		return $items;
	}

	function can_handle_type( $type ) {
		return parent::can_handle_type( $type )
		       && $this->can_return_items();
	}

	private function get_users( $page_number ) {
		$per_page = $this->get_limit( $page_number );

		add_filter( 'bp_user_query_uid_clauses', array( $this, 'order_asc' ) );

		$users = bp_core_get_users( array(
			'per_page' => $per_page,
			'page'     => $page_number,
		) );

		remove_filter( 'bp_user_query_uid_clauses', array( $this, 'order_asc' ) );

		return ! empty( $users['users'] )
			? $users['users']
			: array();
	}

	public function order_asc( $sql ) {
		$sql['order'] = 'ASC';
		return $sql;
	}

	private function can_return_items() {
		return defined( 'BP_VERSION' )
		       && smartcrawl_is_main_bp_site()
		       && function_exists( 'bp_core_get_users' )
		       && function_exists( 'bp_core_get_user_domain' )
		       && $this->bp_profile_enabled();
	}

	public function get_filter_prefix() {
		return 'wds-sitemap-bp_profile';
	}

	private function get_options() {
		return Smartcrawl_Settings::get_options();
	}

	private function bp_profile_enabled() {
		$options = $this->get_options();
		return ! empty( $options['sitemap-buddypress-profiles'] );
	}

	private function is_role_excluded( $user ) {
		$wp_user = new WP_User( $user->id );
		$role = array_shift( $wp_user->roles );
		if ( empty( $role ) ) {
			return false;
		}
		$options = $this->get_options();

		return ! empty( $options["sitemap-buddypress-roles-exclude-profile-role-{$role}"] );
	}

	private function get_user_images( $id ) {
		if ( ! Smartcrawl_Sitemap_Utils::sitemap_images_enabled() ) {
			return array();
		}

		$avatar = $this->get_user_avatar( $id );
		$images = $this->find_images( $avatar );

		$cover = $this->get_user_cover_url( $id );
		if ( $cover ) {
			$images[] = array(
				'src'   => $cover,
				'title' => '',
				'alt'   => '',
			);
		}

		return $images;
	}

	private function get_user_avatar( $id ) {
		return function_exists( 'bp_core_fetch_avatar' )
			? bp_core_fetch_avatar( array(
				'item_id' => $id,
				'object'  => 'user',
				'type'    => 'full',
				'html'    => true,
			) )
			: '';
	}

	private function get_user_cover_url( $id ) {
		return function_exists( 'bp_attachments_get_attachment' )
			? bp_attachments_get_attachment( 'url', array(
				'object_dir' => 'members',
				'item_id'    => $id,
			) )
			: '';
	}
}
