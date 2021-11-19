<?php

class Smartcrawl_Schema_Type_Conditions {
	private $conditions;
	/**
	 * @var WP_Post
	 */
	private $post;

	/**
	 * Smartcrawl_Schema_Condition constructor.
	 *
	 * @param $rules
	 * @param $post
	 */
	public function __construct( $rules, $post ) {
		$this->conditions = $rules;
		$this->post = $post;
	}

	public function met() {
		$met = false;
		foreach ( $this->conditions as $group ) {
			$and = null;
			foreach ( $group as $condition ) {
				$lhs = smartcrawl_get_array_value( $condition, 'lhs' );
				$lhs_value = $this->lhs_value( $lhs );
				$rhs = smartcrawl_get_array_value( $condition, 'rhs' );
				$operator = smartcrawl_get_array_value( $condition, 'operator' );

				if ( is_null( $lhs ) || is_null( $rhs ) || is_null( $operator ) ) {
					// Omit the condition because data is somehow missing
					continue;
				}

				if ( $lhs_value == $rhs && empty( $lhs_value ) && empty( $rhs ) ) {
					// Edge case where the lhs is empty because post is not available
					// in the current context and rhs is empty because the user left
					// the search field blank. Omit the condition.
					continue;
				}

				if ( is_null( $and ) ) {
					$and = true;
				}

				if ( is_bool( $lhs_value ) ) {
					$and = $and && $lhs_value;
				} elseif ( is_array( $lhs_value ) ) {
					$and = $and &&
					       (
						       ( $operator === '=' && in_array( $rhs, $lhs_value ) ) ||
						       ( $operator === '!=' && ! in_array( $rhs, $lhs_value ) )
					       );
				} else {
					$and = $and &&
					       (
						       ( $operator === '=' && $rhs == $lhs_value ) ||
						       ( $operator === '!=' && $rhs != $lhs_value )
					       );
				}
			}
			if ( ! is_null( $and ) ) {
				$met = $met || $and;
			}
		}

		return $met;
	}

	private function lhs_value( $lhs ) {
		$resolver = Smartcrawl_Endpoint_Resolver::resolve();

		switch ( $lhs ) {
			case 'post_type':
				return $this->post
					? (string) get_post_type( $this->post )
					: '';

			case 'show_globally':
				return true;

			case 'homepage':
				return $resolver->get_query_context()->is_front_page();

			case 'author_role':
				if ( ! $this->post ) {
					return array();
				}
				$user = get_user_by( 'ID', $this->post->post_author );
				return $user->roles;

			case 'post_category':
				return $this->get_post_terms( 'category' );

			case 'post_format':
				return (string) get_post_format( $this->post );

			case 'page_template':
				return (string) get_page_template_slug( $this->post );

			case 'product_type':
				return $this->get_product_class_name();
		}

		$post_types = smartcrawl_frontend_post_types();
		if ( isset( $post_types[ $lhs ] ) ) {
			return $this->post
				? $this->post->ID
				: 0;
		}

		$taxonomies = smartcrawl_frontend_taxonomies();
		if ( isset( $taxonomies[ $lhs ] ) ) {
			return $this->get_post_terms( $lhs );
		}

		return '';
	}

	private function get_product_class_name() {
		if ( ! function_exists( 'wc_get_product' ) ) {
			return '';
		}

		$product = wc_get_product( $this->post );
		if ( ! $product ) {
			return '';
		}

		return get_class( $product );
	}

	private function get_post_terms( $taxonomy ) {
		if ( ! $this->post ) {
			return array();
		}

		$terms = wp_get_object_terms( array( $this->post->ID ), $taxonomy, array(
			'fields' => 'ids',
		) );

		if ( is_wp_error( $terms ) ) {
			return array();
		}

		return $terms;
	}
}
