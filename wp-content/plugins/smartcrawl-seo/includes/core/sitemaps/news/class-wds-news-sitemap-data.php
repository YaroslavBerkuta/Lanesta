<?php

class Smartcrawl_News_Sitemap_Data {
	public function settings_to_data( $settings ) {
		$news_sitemap_enabled = (bool) smartcrawl_get_array_value( $settings, 'enable-news-sitemap' );

		$included_post_types = smartcrawl_get_array_value( $settings, 'news-sitemap-included-post-types' );
		$included_post_types = empty( $included_post_types ) ? array() : $included_post_types;

		$ignored_post_ids = smartcrawl_get_array_value( $settings, 'news-sitemap-excluded-post-ids' );
		$ignored_post_ids = empty( $ignored_post_ids ) ? array() : $ignored_post_ids;

		$post_types = smartcrawl_frontend_post_types();
		unset( $post_types['attachment'] );
		$frontend_taxonomies = smartcrawl_frontend_taxonomies( 'names' );

		$post_type_data = array();
		foreach ( $post_types as $post_type ) {
			$post_type_object = get_post_type_object( $post_type );
			$post_type_data[ $post_type ] = array(
				'included' => array_search( $post_type, $included_post_types ) !== false,
				'name'     => $post_type_object->name,
				'label'    => $post_type_object->label,
				'excluded' => array(),
			);

			$post_type_taxonomies = get_object_taxonomies( $post_type, 'objects' );
			$taxonomy_data = array();

			foreach ( $post_type_taxonomies as $post_type_taxonomy ) {
				if ( ! in_array( $post_type_taxonomy->name, $frontend_taxonomies, true ) ) {
					continue;
				}

				$terms = get_terms( array( 'taxonomy' => $post_type_taxonomy->name ) );
				if ( empty( $terms ) || is_wp_error( $terms ) ) {
					continue;
				}

				$excluded_term_ids = smartcrawl_get_array_value( $settings, "news-sitemap-{$post_type}-excluded-term-ids" );
				$excluded_term_ids = empty( $excluded_term_ids ) ? array() : $excluded_term_ids;

				$term_data = array();
				foreach ( $terms as $term ) {
					$term_data[ $term->term_id ] = array(
						'id'       => $term->term_id,
						'name'     => $term->slug,
						'label'    => $term->name,
						'excluded' => array_search( $term->term_id, $excluded_term_ids ) !== false,
					);
				}

				$taxonomy_data[ $post_type_taxonomy->name ] = array(
					'name'  => $post_type_taxonomy->name,
					'label' => $post_type_taxonomy->label,
					'terms' => $term_data,
				);
			}
			$post_type_data[ $post_type ]['taxonomies'] = $taxonomy_data;
		}

		foreach ( $ignored_post_ids as $ignored_post_id ) {
			$ignored_post_type = get_post_type( $ignored_post_id );
			if ( ! $ignored_post_type ) {
				continue;
			}

			$post_type_data[ $ignored_post_type ]['excluded'][] = $ignored_post_id;
		}

		return array(
			'enabled'     => $news_sitemap_enabled,
			'publication' => (string) smartcrawl_get_array_value( $settings, 'news-publication' ),
			'post_types'  => $post_type_data,
		);
	}

	public function data_to_settings( $data ) {
		$included_post_types = array();
		$excluded_post_ids = array();
		$settings = array();

		$post_types = smartcrawl_get_array_value( $data, 'postTypes' );
		$post_types = empty( $post_types ) ? array() : $post_types;
		foreach ( $post_types as $post_type => $post_type_data ) {
			if ( (bool) smartcrawl_get_array_value( $post_type_data, 'included' ) ) {
				$included_post_types[] = $post_type;
			}

			$excluded_post_type_ids = smartcrawl_get_array_value( $post_type_data, 'excluded' );
			$excluded_post_ids = array_merge(
				$excluded_post_ids,
				empty( $excluded_post_type_ids ) ? array() : array_map( 'intval', $excluded_post_type_ids )
			);

			$post_type_taxonomies = smartcrawl_get_array_value( $post_type_data, 'taxonomies' );
			$post_type_taxonomies = empty( $post_type_taxonomies ) ? array() : $post_type_taxonomies;
			$post_type_excluded_term_ids = array();
			foreach ( $post_type_taxonomies as $post_type_taxonomy ) {
				$post_type_taxonomy_terms = smartcrawl_get_array_value( $post_type_taxonomy, 'terms' );
				$post_type_taxonomy_terms = empty( $post_type_taxonomy_terms ) ? array() : $post_type_taxonomy_terms;

				foreach ( $post_type_taxonomy_terms as $post_type_taxonomy_term_id => $post_type_taxonomy_term ) {
					if ( (bool) smartcrawl_get_array_value( $post_type_taxonomy_term, 'excluded' ) ) {
						$post_type_excluded_term_ids[] = (int) $post_type_taxonomy_term_id;
					}
				}
			}
			if ( $post_type_excluded_term_ids ) {
				$settings["news-sitemap-$post_type-excluded-term-ids"] = $post_type_excluded_term_ids;
			}
		}

		return array_merge( $settings, array(
			'enable-news-sitemap'              => (bool) smartcrawl_get_array_value( $data, 'enabled' ),
			'news-publication'                 => (string) smartcrawl_get_array_value( $data, 'publication' ),
			'news-sitemap-included-post-types' => $included_post_types,
			'news-sitemap-excluded-post-ids'   => $excluded_post_ids,
		) );
	}
}
