<?php
/**
 * Metabox main class
 *
 * @package wpmu-dev-seo
 */

/**
 * Metabox rendering / handling class
 */
class Smartcrawl_Metabox extends Smartcrawl_Base_Controller {

	/**
	 * Static instance
	 *
	 * @var Smartcrawl_Metabox
	 */
	private static $_instance;

	/**
	 * @param $post_id
	 * @param $input
	 */
	public function save_opengraph_meta( $post_id, $input ) {
		$result = array();

		$og_disabled = ! empty( $input['disabled'] );
		if ( $og_disabled ) {
			$result['disabled'] = true;
		}
		if ( ! empty( $input['title'] ) ) {
			$result['title'] = smartcrawl_sanitize_preserve_macros( $input['title'] );
		}
		if ( ! empty( $input['description'] ) ) {
			$result['description'] = smartcrawl_sanitize_preserve_macros( $input['description'] );
		}
		if ( ! empty( $input['images'] ) && is_array( $input['images'] ) ) {
			$result['images'] = array();
			foreach ( $input['images'] as $img ) {
				$result['images'][] = is_numeric( $img ) ? intval( $img ) : esc_url_raw( $img );
			}
		}

		if ( empty( $result ) ) {
			delete_post_meta( $post_id, '_wds_opengraph' );
		} else {
			update_post_meta( $post_id, '_wds_opengraph', $result );
		}
	}

	/**
	 * @param $post_id
	 * @param $input
	 */
	public function save_twitter_post_meta( $post_id, $input ) {
		$twitter = array();

		$twitter_disabled = ! empty( $input['disabled'] );
		if ( $twitter_disabled ) {
			$twitter['disabled'] = true;
		}
		if ( ! empty( $input['title'] ) ) {
			$twitter['title'] = smartcrawl_sanitize_preserve_macros( $input['title'] );
		}
		if ( ! empty( $input['description'] ) ) {
			$twitter['description'] = smartcrawl_sanitize_preserve_macros( $input['description'] );
		}
		if ( ! empty( $input['images'] ) && is_array( $input['images'] ) ) {
			$twitter['images'] = array();
			foreach ( $input['images'] as $img ) {
				$twitter['images'][] = is_numeric( $img ) ? intval( $img ) : esc_url_raw( $img );
			}
		}

		if ( empty( $twitter ) ) {
			delete_post_meta( $post_id, '_wds_twitter' );
		} else {
			update_post_meta( $post_id, '_wds_twitter', $twitter );
		}
	}

	/**
	 * @param $post WP_Post
	 * @param $request_data
	 */
	public function save_robots_meta( $post, $request_data ) {
		$all_options = Smartcrawl_Settings::get_options();
		$post_type_noindexed = (bool) smartcrawl_get_array_value( $all_options, sprintf( 'meta_robots-noindex-%s', get_post_type( $post ) ) );
		$post_type_nofollowed = (bool) smartcrawl_get_array_value( $all_options, sprintf( 'meta_robots-nofollow-%s', get_post_type( $post ) ) );
		/**
		 * If the user un-checks a checkbox and saves the post, the value for that checkbox will not be included inside $_POST array
		 * so we may have to delete the corresponding meta value manually.
		 */
		$checkbox_meta_items = array( 'wds_meta-robots-adv' );
		$checkbox_meta_items[] = $post_type_nofollowed ? 'wds_meta-robots-follow' : 'wds_meta-robots-nofollow';
		$checkbox_meta_items[] = $post_type_noindexed ? 'wds_meta-robots-index' : 'wds_meta-robots-noindex';

		foreach ( $checkbox_meta_items as $item ) {
			$meta_key = "_{$item}";
			if ( ! isset( $request_data[ $item ] ) ) {
				delete_post_meta( $post->ID, $meta_key );
			} else {
				$value = $request_data[ $item ];
				if ( is_array( $value ) ) {
					$value = join( ',', $value );
				}
				update_post_meta( $post->ID, $meta_key, sanitize_text_field( $value ) );
			}
		}
	}

	public function get_length_warnings( $title, $description ) {
		$warnings_markup = '';

		$title_length = mb_strlen( $title );
		$title_min_length = smartcrawl_title_min_length();
		if ( $title_length < $title_min_length ) {
			$warnings_markup .= Smartcrawl_Simple_Renderer::load( 'notice', array(
				'message' => sprintf(
					esc_html__( 'Your title is %d characters in length. The recommended minimum length is %d characters so you may want to consider adding a few words.' ),
					$title_length,
					$title_min_length
				),
			) );
		}
		$title_max_length = smartcrawl_title_max_length();
		if ( $title_length > $title_max_length ) {
			$warnings_markup .= Smartcrawl_Simple_Renderer::load( 'notice', array(
				'message' => sprintf(
					esc_html__( 'Your title is %d characters in length. The recommended max length is %d characters so you may want to consider trimming a few words.' ),
					$title_length,
					$title_max_length
				),
			) );
		}

		$desc_length = mb_strlen( $description );
		$desc_min_length = smartcrawl_metadesc_min_length();
		if ( $desc_length < $desc_min_length ) {
			$warnings_markup .= Smartcrawl_Simple_Renderer::load( 'notice', array(
				'message' => sprintf(
					esc_html__( 'Your description is %d characters in length. The recommended minimum length is %d characters so you may want to consider adding a few words.' ),
					$desc_length,
					$desc_min_length
				),
			) );
		}
		$desc_max_length = smartcrawl_metadesc_max_length();
		if ( $desc_length > $desc_max_length ) {
			$warnings_markup .= Smartcrawl_Simple_Renderer::load( 'notice', array(
				'message' => sprintf(
					esc_html__( 'Your description is %d characters in length. The recommended max length is %d characters so you may want to consider trimming a few words.' ),
					$desc_length,
					$desc_max_length
				),
			) );
		}

		return $warnings_markup;
	}

	protected function init() {
		// WPSC integration.
		add_action( 'wpsc_edit_product', array( $this, 'rebuild_sitemap' ) );
		add_action( 'wpsc_rate_product', array( $this, 'rebuild_sitemap' ) );

		add_action( 'admin_menu', array( $this, 'smartcrawl_create_meta_box' ) );

		add_action( 'save_post', array( $this, 'smartcrawl_save_postdata' ) );
		add_filter( 'attachment_fields_to_save', array( $this, 'smartcrawl_save_attachment_postdata' ) );

		add_action( 'init', array( $this, 'init_post_columns' ) );

		add_action( 'quick_edit_custom_box', array( $this, 'smartcrawl_quick_edit_dispatch' ), 20, 1 );
		add_action( 'wp_ajax_wds_get_meta_fields', array( $this, 'json_wds_postmeta' ) );

		add_action( 'admin_print_scripts-post.php', array( $this, 'js_load_scripts' ) );
		add_action( 'admin_print_scripts-post-new.php', array( $this, 'js_load_scripts' ) );
		/**
		 * TODO perhaps we can combine wds-analysis-get-editor-analysis wds-metabox-preview and wds_metabox_update
		 * since they are used together so frequently
		 */

		/*
		 * When running analysis in metabox, or rendering metabox preview, 
		 * always use overriding values passed in the request before values saved in the DB.
		 * 
		 * This is done by filtering the metadata.
		 */
		add_filter( 'get_post_metadata', array( $this, 'filter_meta_title' ), 10, 4 );
		add_filter( 'get_post_metadata', array( $this, 'filter_meta_desc' ), 10, 4 );
		add_filter( 'get_post_metadata', array( $this, 'filter_focus_keyword' ), 10, 4 );

		/**
		 * Similar for taxonomy meta
		 */
		add_filter( 'wds-taxonomy-meta-wds_title', array( $this, 'filter_term_meta_title' ) );
		add_filter( 'wds-taxonomy-meta-wds_desc', array( $this, 'filter_term_meta_desc' ) );

		add_action( 'default_hidden_columns', array( $this, 'hide_robots_column_by_default' ) );
		add_filter( 'page_row_actions', array( $this, 'post_row_actions' ), 10, 2 );
		add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );
	}

	public function init_post_columns() {
		foreach ( smartcrawl_frontend_post_types() as $type ) {
			add_filter( "manage_{$type}_posts_columns", array( $this, 'smartcrawl_meta_column_heading' ), 20 );
			add_action( "manage_{$type}_posts_custom_column", array( $this, 'smartcrawl_meta_column_content' ), 20, 2 );
		}
	}

	public function post_row_actions( $actions, $post ) {
		$onpage_active = Smartcrawl_Settings::get_setting( 'onpage' );
		if (
			$onpage_active
			&& ! empty( $actions )
			&& in_array( $post->post_type, smartcrawl_frontend_post_types() )
		) {
			Smartcrawl_Simple_Renderer::render( 'post-list/meta-details', array(
				'post' => $post,
			) );
		}

		return $actions;
	}

	public function hide_robots_column_by_default( $hidden ) {
		$hidden[] = 'smartcrawl-robots';

		return $hidden;
	}

	/**
	 * Static instance getter
	 */
	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Enqueues frontend dependencies
	 */
	public function js_load_scripts() {
		if ( $this->is_editing_private_post_type() ) {
			return;
		}

		wp_enqueue_script( Smartcrawl_Controller_Assets::METABOX_JS );

		wp_enqueue_media();

		wp_enqueue_style( Smartcrawl_Controller_Assets::APP_CSS );
	}

	/**
	 * Handles page body class
	 *
	 * @param string $string Body classes this far.
	 *
	 * @return string
	 */
	public function admin_body_class( $string ) {
		return str_replace( 'wpmud', '', $string );
	}

	/**
	 * Handles actual metabox rendering
	 *
	 * @param $post
	 */
	public function smartcrawl_meta_boxes( $post ) {
		Smartcrawl_Simple_Renderer::render( 'metabox/metabox-main', array(
			'post' => $post,
		) );
	}

	/**
	 * Adds the metabox to the queue
	 */
	public function smartcrawl_create_meta_box() {
		$show = user_can_see_seo_metabox();
		if ( function_exists( 'add_meta_box' ) ) {
			// Show branding for singular installs.
			$metabox_title = $this->get_metabox_title();
			$post_types = get_post_types( array(
				'show_ui' => true, // Only if it actually supports WP UI.
				'public'  => true, // ... and is public
			) );
			foreach ( $post_types as $posttype ) {
				if ( $this->is_private_post_type( $posttype ) ) {
					continue;
				}
				if ( $show ) {
					add_meta_box( 'wds-wds-meta-box', $metabox_title, array(
						&$this,
						'smartcrawl_meta_boxes',
					), $posttype, 'normal', 'high' );
				}
			}
		}
	}

	/**
	 * Handles attachment metadata saving
	 *
	 * @param array $data Data to save.
	 *
	 * @return array
	 */
	public function smartcrawl_save_attachment_postdata( $data ) {
		$request_data = $this->get_request_data();
		if ( empty( $request_data ) || empty( $data['post_ID'] ) || ! is_numeric( $data['post_ID'] ) ) {
			return $data;
		}
		$this->smartcrawl_save_postdata( (int) $data['post_ID'] );

		return $data;
	}

	private function get_post() {
		global $post;

		return $post;
	}

	/**
	 * Saves submitted metabox POST data
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return bool
	 */
	public function smartcrawl_save_postdata( $post_id ) {
		$request_data = $this->get_request_data();
		if ( ! $post_id || empty( $request_data ) ) {
			return;
		}

		$post = $this->get_post();
		if ( empty( $post ) ) {
			$post = get_post( $post_id );
		}

		// Determine posted type.
		$post_type_rq = ! empty( $request_data['post_type'] ) ? sanitize_key( $request_data['post_type'] ) : false;
		if ( 'page' === $post_type_rq && ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		$ptype = ! empty( $post_type_rq )
			? $post_type_rq
			: ( ! empty( $post->post_type ) ? $post->post_type : false );
		// Do not process post stuff for non-public post types.
		if ( ! in_array( $ptype, get_post_types( array( 'public' => true ) ), true ) ) {
			return $post_id;
		}

		if ( ! empty( $request_data['wds-opengraph'] ) ) {
			$this->save_opengraph_meta(
				$post_id,
				stripslashes_deep( $request_data['wds-opengraph'] )
			);
		}

		if ( ! empty( $request_data['wds-twitter'] ) ) {
			$this->save_twitter_post_meta(
				$post_id,
				stripslashes_deep( $request_data['wds-twitter'] )
			);
		}

		if ( isset( $request_data['wds_focus'] ) ) {
			$focus = stripslashes_deep( $request_data['wds_focus'] );
			if ( trim( $focus ) === '' ) {
				delete_post_meta( $post_id, '_wds_focus-keywords' );
			} else {
				update_post_meta( $post_id, '_wds_focus-keywords', smartcrawl_sanitize_preserve_macros( $focus ) );
			}
		}

		foreach ( $request_data as $key => $value ) {
			if ( in_array( $key, array( 'wds-opengraph', 'wds_focus', 'wds-twitter' ), true ) ) {
				continue;
			} // We already handled those.
			if ( ! preg_match( '/^wds_/', $key ) ) {
				continue;
			}

			$id = "_{$key}";
			$data = $value;
			if ( is_array( $value ) ) {
				$data = join( ',', $value );
			}

			if ( $data ) {
				$value = in_array( $key, array( 'wds_canonical', 'wds_redirect' ), true )
					? esc_url_raw( $data )
					: smartcrawl_sanitize_preserve_macros( $data );
				update_post_meta( $post_id, $id, $value );
			} else {
				delete_post_meta( $post_id, $id );
			}
		}

		$this->save_robots_meta( $post, $request_data );

		if ( ! isset( $request_data['wds_autolinks-exclude'] ) ) {
			delete_post_meta( $post_id, "_wds_autolinks-exclude" );
		}

		update_post_meta( $post->ID, '_wds_trimmed_excerpt', smartcrawl_get_trimmed_excerpt(
			$post->post_excerpt,
			$post->post_content
		) );

		do_action( 'wds_saved_postdata' );
	}

	/**
	 * Handles sitemap rebuilding
	 */
	public function rebuild_sitemap() {
		Smartcrawl_Sitemap_Cache::get()->invalidate();
	}

	/**
	 * Adds title and robots columns to post listing page
	 *
	 * @param array $columns Post list columns.
	 *
	 * @return array
	 */
	public function smartcrawl_meta_column_heading( $columns ) {
		$onpage_allowed = Smartcrawl_Settings::get_setting( Smartcrawl_Settings::COMP_ONPAGE )
		                  && Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_ONPAGE );

		if ( $onpage_allowed ) {
			$columns['smartcrawl-robots'] = __( 'Robots Meta', 'wds' );
		}
		return $columns;
	}

	/**
	 * Puts out actual column bodies
	 *
	 * @param string $column_name Column ID.
	 * @param int $id Post ID.
	 *
	 * @return void
	 */
	public function smartcrawl_meta_column_content( $column_name, $id ) {
		if ( 'smartcrawl-robots' === $column_name ) {
			$meta_robots_arr = array(
				( smartcrawl_get_value( 'meta-robots-noindex', $id ) ? 'noindex' : 'index' ),
				( smartcrawl_get_value( 'meta-robots-nofollow', $id ) ? 'nofollow' : 'follow' ),
			);
			$meta_robots = join( ',', $meta_robots_arr );
			if ( empty( $meta_robots ) ) {
				$meta_robots = 'index,follow';
			}
			echo esc_html( ucwords( str_replace( ',', ', ', $meta_robots ) ) );

			// Show additional robots data.
			$advanced = array_filter( array_map( 'trim', explode( ',', smartcrawl_get_value( 'meta-robots-adv', $id ) ) ) );
			if ( ! empty( $advanced ) && 'none' !== $advanced ) {
				$adv_map = array(
					'noodp'     => __( 'No ODP', 'wds' ),
					'noydir'    => __( 'No YDIR', 'wds' ),
					'noarchive' => __( 'No Archive', 'wds' ),
					'nosnippet' => __( 'No Snippet', 'wds' ),
				);
				$additional = array();
				foreach ( $advanced as $key ) {
					if ( ! empty( $adv_map[ $key ] ) ) {
						$additional[] = $adv_map[ $key ];
					}
				}
				if ( ! empty( $additional ) ) {
					echo '<br /><small>' . esc_html( join( ', ', $additional ) ) . '</small>';
				}
			}
		}
	}

	/**
	 * Dispatch quick edit areas
	 *
	 * @param string $column Column ID.
	 */
	public function smartcrawl_quick_edit_dispatch( $column ) {
		if ( $column === 'smartcrawl-robots' ) {
			Smartcrawl_Simple_Renderer::render( 'post-list/quick-edit-onpage', array() );
		}
	}

	/**
	 * Handle postmeta getting requests
	 */
	public function json_wds_postmeta() {
		$data = $this->get_request_data();
		$id = (int) $data['id'];
		$post = get_post( $id );
		die( wp_json_encode( array(
			'title'       => smartcrawl_get_value( 'title', $id ),
			'description' => smartcrawl_get_value( 'metadesc', $id ),
			'focus'       => smartcrawl_get_value( 'focus-keywords', $id ),
		) ) );
	}

	/**
	 * When we are rendering a preview, or refreshing analysis,
	 * we want to use the latest values from the request.
	 *
	 * @param $original
	 * @param $post_id
	 * @param $meta_key
	 * @param $single
	 *
	 * @return array|string|null
	 */
	public function filter_meta_title( $original, $post_id, $meta_key, $single ) {
		if ( $meta_key !== '_wds_title' ) {
			return $original;
		}

		return $this->use_request_param_value( 'wds_title', $original, $single );
	}

	public function filter_meta_desc( $original, $post_id, $meta_key, $single ) {
		if ( $meta_key !== '_wds_metadesc' ) {
			return $original;
		}

		return $this->use_request_param_value( 'wds_description', $original, $single );
	}

	public function filter_focus_keyword( $original, $post_id, $meta_key, $single ) {
		if ( $meta_key !== '_wds_focus-keywords' ) {
			return $original;
		}

		return $this->use_request_param_value( 'wds_focus_keywords', $original, $single );
	}

	public function filter_term_meta_title( $original ) {
		return $this->use_request_param_value( 'wds_title', $original, true );
	}

	public function filter_term_meta_desc( $original ) {
		return $this->use_request_param_value( 'wds_description', $original, true );
	}

	private function use_request_param_value( $request_param, $default, $single ) {
		$overridden = smartcrawl_get_array_value( $this->get_request_data(), $request_param );
		if ( is_null( $overridden ) ) {
			return $default;
		}

		$overridden = Smartcrawl_Replacement_Helper::replace( $overridden );

		if ( $single ) {
			return $overridden;
		} else {
			/**
			 * The WP function update_metadata doesn't update if the old value matches the new value.
			 * However, if the old value is an array and has more than one items it is not compared to the new value.
			 * So we are returning an empty string in the array to ensure that what we return here doesn't prevent meta from getting updated.
			 *
			 * @see update_metadata
			 */
			return array( $overridden, '' );
		}
	}

	private function get_request_data() {
		return isset( $_POST['_wds_nonce'] ) && wp_verify_nonce( $_POST['_wds_nonce'], 'wds-metabox-nonce' ) ? stripslashes_deep( $_POST ) : array();
	}

	private function is_private_post_type( $post_type_name ) {
		$post_type = get_post_type_object( $post_type_name );

		return $post_type->name === 'attachment'
		       || ! $post_type->show_ui
		       || ! $post_type->public;
	}

	private function is_editing_private_post_type() {
		$current_screen = get_current_screen();
		if ( empty( $current_screen->post_type ) ) {
			return false;
		}

		return $this->is_private_post_type( $current_screen->post_type );
	}

	/**
	 * @return string|void
	 */
	private function get_metabox_title() {
		return __( 'SmartCrawl', 'wds' );
	}
}
