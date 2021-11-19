<?php

class Smartcrawl_Social_Value_Helper extends Smartcrawl_Type_Traverser {
	private $title = '';
	private $description = '';
	private $images = array();
	private $enabled = false;
	private $key;
	private $post_meta_key;
	private $term_meta_key;

	public function __construct( $key, $post_meta_key, $term_meta_key ) {
		$this->key = $key;
		$this->post_meta_key = $post_meta_key;
		$this->term_meta_key = $term_meta_key;

		$this->traverse();
	}

	private function include_key( $format ) {
		return sprintf( $format, $this->key );
	}

	public function get_title() {
		return apply_filters( $this->include_key( 'wds_custom_%s_title' ), $this->title );
	}

	public function get_description() {
		return apply_filters( $this->include_key( 'wds_custom_%s_description' ), $this->description );
	}

	public function get_images() {
		return apply_filters( $this->include_key( 'wds_custom_%s_image' ), $this->images );
	}

	public function is_enabled() {
		return $this->enabled;
	}

	private function get_options() {
		return Smartcrawl_Settings::get_options();
	}

	private function from_options( $location ) {
		$options = $this->get_options();

		$title = smartcrawl_get_array_value( $options, $this->include_key( '%s-title-' . $location ) );
		$description = smartcrawl_get_array_value( $options, $this->include_key( '%s-description-' . $location ) );
		$images = smartcrawl_get_array_value( $options, $this->include_key( '%s-images-' . $location ) );
		$enabled = smartcrawl_get_array_value( $options, $this->include_key( '%s-active-' . $location ) );

		$title = $this->prepare_value( $title );
		$description = $this->prepare_value( $description );

		$this->title = empty( $title ) ? Smartcrawl_Meta_Value_Helper::get()->get_title() : $title;
		$this->description = empty( $description ) ? Smartcrawl_Meta_Value_Helper::get()->get_description() : $description;
		$this->images = $this->prepare_option_images( $images );
		$this->enabled = (bool) $enabled;
	}

	private function from_post_meta( $post ) {
		$post_meta = smartcrawl_get_value( $this->post_meta_key, $post->ID );
		$title = smartcrawl_get_array_value( $post_meta, 'title' );
		$description = smartcrawl_get_array_value( $post_meta, 'description' );
		$images = smartcrawl_get_array_value( $post_meta, 'images' );
		$disabled = smartcrawl_get_array_value( $post_meta, 'disabled' );

		if ( ! empty( $title ) ) {
			$this->title = $this->prepare_value( $title );
		}
		if ( ! empty( $description ) ) {
			$this->description = $this->prepare_value( $description );
		}
		if ( is_array( $images ) && ! empty( $images ) ) {
			$this->images = $this->prepare_meta_images( $images );
		}

		// Add featured image as the last resort
		if ( has_post_thumbnail( $post ) ) {
			$this->images = $this->prepare_image(
				$this->images,
				get_post_thumbnail_id( $post )
			);
		}

		$this->enabled = ! $disabled;
	}

	/**
	 * @param $post WP_Post
	 */
	private function from_post_content( $post ) {
		if (
			! empty( $this->images ) // Already have some images
			|| empty( $post->post_content ) // We don't even have post content
		) {
			return;
		}

		$options = $this->get_options();
		if ( ! empty( $options[ $this->include_key( "%s-disable-first-image-{$post->post_type}" ) ] ) ) {
			return;
		}

		$attributes = Smartcrawl_Html::find_attributes( 'img', 'src', $post->post_content );
		if ( ! empty( $attributes ) ) {
			$image_source = array_shift( $attributes );
			$this->images = $this->prepare_image(
				$this->images,
				$image_source
			);
		}
	}

	private function from_term_meta( $term ) {
		$term_meta = smartcrawl_get_term_meta( $term, $term->taxonomy, $this->term_meta_key );
		$title = smartcrawl_get_array_value( $term_meta, 'title' );
		$description = smartcrawl_get_array_value( $term_meta, 'description' );
		$images = smartcrawl_get_array_value( $term_meta, 'images' );
		$disabled = smartcrawl_get_array_value( $term_meta, 'disabled' );

		if ( ! empty( $title ) ) {
			$this->title = $this->prepare_value( $title );
		}
		if ( ! empty( $description ) ) {
			$this->description = $this->prepare_value( $description );
		}
		if ( is_array( $images ) && ! empty( $images ) ) {
			$this->images = $this->prepare_meta_images( $images );
		}
		$this->enabled = ! $disabled;
	}

	public function handle_bp_groups() {
		$this->from_options( 'bp_groups' );
	}

	public function handle_bp_profile() {
		$this->from_options( 'bp_profile' );
	}

	public function handle_woo_shop() {
		$this->handle_singular( wc_get_page_id( 'shop' ) );
	}

	public function handle_blog_home() {
		$this->from_options( 'home' );
	}

	public function handle_static_home() {
		$this->handle_singular( get_option( 'page_for_posts' ) );
	}

	public function handle_search() {
		$this->from_options( 'search' );
	}

	public function handle_404() {
		// No OG for 404 page
	}

	public function handle_date_archive() {
		$this->from_options( 'date' );
	}

	public function handle_pt_archive() {
		$post_type = $this->get_queried_object();
		if ( is_a( $post_type, 'WP_Post_Type' ) ) {
			$location = Smartcrawl_Onpage_Settings::PT_ARCHIVE_PREFIX . $post_type->name;
			$this->from_options( $location );
		}
	}

	public function handle_tax_archive() {
		$term = $this->get_queried_object();
		if ( is_a( $term, 'WP_Term' ) ) {
			$this->from_options( $term->taxonomy );

			if ( $this->enabled ) {
				// Now apply any overrides from the term taxonomy
				$this->from_term_meta( $term );
			}
		}
	}

	public function handle_author_archive() {
		$this->from_options( 'author' );
	}

	public function handle_archive() {
		// TODO: Implement handle_archive() method.
	}

	public function handle_singular( $post_id = 0 ) {
		$post = $this->get_post_or_fallback( $post_id );
		if ( is_a( $post, 'WP_Post' ) ) {
			$this->from_options( $post->post_type );

			if ( $this->enabled ) {
				// Now apply any overrides from the individual post's meta
				$this->from_post_meta( $post );
				$this->from_post_content( $post );
			}
		}
	}

	private function prepare_value( $value ) {
		$value = wp_strip_all_tags( trim( strval( $value ) ) );

		return Smartcrawl_Replacement_Helper::replace( $value );
	}

	private function prepare_option_images( $image_ids ) {
		return $this->prepare_images( $image_ids, 'wp_get_attachment_image_src' );
	}

	private function prepare_meta_images( $image_ids ) {
		return $this->prepare_images( $image_ids, 'wp_get_attachment_image_src' );
	}

	private function prepare_images( $image_ids, $attachment_function ) {
		$image_ids = is_array( $image_ids ) || ! empty( $image_ids )
			? $image_ids
			: array();

		$images = array();
		foreach ( $image_ids as $image_id ) {
			$images = $this->prepare_image( $images, $image_id, $attachment_function );
		}

		return $images;
	}

	private function prepare_image( $images, $image_id, $attachment_function = 'wp_get_attachment_image_src' ) {
		if ( empty( $images ) || ! is_array( $images ) ) {
			$images = array();
		}

		if ( is_numeric( $image_id ) ) {
			$attachment = call_user_func( $attachment_function, $image_id, 'full' );
			$attachment_url = smartcrawl_get_array_value( $attachment, 0 );
			if ( $attachment_url ) {
				$images[ $attachment_url ] = $attachment;
			}
		} else {
			$images[ $image_id ] = array( $image_id );
		}

		return $images;
	}
}
