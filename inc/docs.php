<?php
/**
 * Documentation content model.
 *
 * Registers the Doc Article post type, related taxonomies, rewrite rules,
 * and meta fields used to control ordering and feature toggles.
 *
 * @package Apparel
 */

/**
 * Get the first taxonomy term slug for a post.
 *
 * @param int    $post_id  Post ID.
 * @param string $taxonomy Taxonomy name.
 * @param string $fallback Fallback slug when no term is assigned.
 * @return string
 */
function mbf_get_doc_article_term_slug( $post_id, $taxonomy, $fallback ) {
	$terms = get_the_terms( $post_id, $taxonomy );

	if ( is_wp_error( $terms ) || ! $terms ) {
		return $fallback;
	}

	$primary = array_shift( $terms );

	return $primary->slug;
}

/**
 * Register Doc Article content model and taxonomies.
 */
function mbf_register_doc_article() {
	add_rewrite_tag( '%doc_category%', '([^/]+)', 'doc_category=' );
	add_rewrite_tag( '%doc_subcategory%', '([^/]+)', 'doc_subcategory=' );

	register_taxonomy(
		'doc_category',
		'doc_article',
		array(
			'labels'            => array(
				'name'          => esc_html__( 'Doc Categories', 'apparel' ),
				'singular_name' => esc_html__( 'Doc Category', 'apparel' ),
				'add_new_item'  => esc_html__( 'Add New Doc Category', 'apparel' ),
				'edit_item'     => esc_html__( 'Edit Doc Category', 'apparel' ),
				'view_item'     => esc_html__( 'View Doc Category', 'apparel' ),
				'search_items'  => esc_html__( 'Search Doc Categories', 'apparel' ),
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'docs',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		'doc_subcategory',
		'doc_article',
		array(
			'labels'            => array(
				'name'          => esc_html__( 'Doc Subcategories', 'apparel' ),
				'singular_name' => esc_html__( 'Doc Subcategory', 'apparel' ),
				'add_new_item'  => esc_html__( 'Add New Doc Subcategory', 'apparel' ),
				'edit_item'     => esc_html__( 'Edit Doc Subcategory', 'apparel' ),
				'view_item'     => esc_html__( 'View Doc Subcategory', 'apparel' ),
				'search_items'  => esc_html__( 'Search Doc Subcategories', 'apparel' ),
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'docs/subcategory',
				'with_front' => false,
			),
		)
	);

	register_post_type(
		'doc_article',
		array(
			'labels'              => array(
				'name'                  => esc_html__( 'Doc Articles', 'apparel' ),
				'singular_name'         => esc_html__( 'Doc Article', 'apparel' ),
				'add_new_item'          => esc_html__( 'Add New Doc Article', 'apparel' ),
				'edit_item'             => esc_html__( 'Edit Doc Article', 'apparel' ),
				'new_item'              => esc_html__( 'New Doc Article', 'apparel' ),
				'view_item'             => esc_html__( 'View Doc Article', 'apparel' ),
				'search_items'          => esc_html__( 'Search Doc Articles', 'apparel' ),
				'not_found'             => esc_html__( 'No doc articles found.', 'apparel' ),
				'not_found_in_trash'    => esc_html__( 'No doc articles found in Trash.', 'apparel' ),
				'all_items'             => esc_html__( 'All Doc Articles', 'apparel' ),
				'archives'              => esc_html__( 'Doc Article Archives', 'apparel' ),
				'insert_into_item'      => esc_html__( 'Insert into doc article', 'apparel' ),
				'uploaded_to_this_item' => esc_html__( 'Uploaded to this doc article', 'apparel' ),
			),
			'description'         => esc_html__( 'Documentation articles with category-aware URLs.', 'apparel' ),
			'public'              => true,
			'show_ui'             => true,
			'show_in_rest'        => true,
			'show_in_nav_menus'   => true,
			'supports'            => array(
				'title',
				'editor',
				'excerpt',
				'revisions',
				'thumbnail',
			),
			'taxonomies'          => array(
				'doc_category',
				'doc_subcategory',
			),
			'has_archive'         => 'docs',
			'map_meta_cap'        => true,
			'capability_type'     => array( 'doc_article', 'doc_articles' ),
			'capabilities'        => array(
				'create_posts' => 'edit_doc_articles',
			),
			'rewrite'             => array(
				'slug'       => 'docs/%doc_category%/%doc_subcategory%',
				'with_front' => false,
			),
			'publicly_queryable'  => true,
			'query_var'           => 'doc_article',
			'show_in_menu'        => true,
		)
	);

	add_rewrite_rule(
		'^docs/([^/]+)/([^/]+)/([^/]+)/?$',
		'index.php?post_type=doc_article&name=$matches[3]&doc_category=$matches[1]&doc_subcategory=$matches[2]',
		'top'
	);
}
add_action( 'init', 'mbf_register_doc_article' );

/**
 * Replace taxonomy placeholders in doc article permalinks.
 *
 * @param string  $permalink Post permalink.
 * @param WP_Post $post      Post object.
 * @return string
 */
function mbf_doc_article_permalink( $permalink, $post ) {
	if ( 'doc_article' !== $post->post_type ) {
		return $permalink;
	}

	$category_slug    = mbf_get_doc_article_term_slug( $post->ID, 'doc_category', 'uncategorized' );
	$subcategory_slug = mbf_get_doc_article_term_slug( $post->ID, 'doc_subcategory', 'general' );

	$permalink = str_replace( '%doc_category%', $category_slug, $permalink );
	$permalink = str_replace( '%doc_subcategory%', $subcategory_slug, $permalink );

	return $permalink;
}
add_filter( 'post_type_link', 'mbf_doc_article_permalink', 10, 2 );

/**
 * Canonical redirect for doc article permalinks.
 */
function mbf_redirect_doc_article_canonical() {
	if ( ! is_singular( 'doc_article' ) ) {
		return;
	}

	global $wp;

	$canonical_url  = get_permalink();
	$canonical_path = trailingslashit( wp_parse_url( $canonical_url, PHP_URL_PATH ) );
	$request_path   = isset( $wp->request ) ? trailingslashit( '/' . ltrim( $wp->request, '/' ) ) : '';

	if ( $canonical_path && $request_path && $canonical_path !== $request_path ) {
		wp_safe_redirect( $canonical_url, 301 );
		exit;
	}
}
add_action( 'template_redirect', 'mbf_redirect_doc_article_canonical' );

/**
 * Ensure meta fields for doc articles are registered with strict types.
 */
function mbf_register_doc_article_meta_fields() {
	$meta_permissions = function( $allowed, $meta_key, $post_id, $user_id, $cap, $caps ) {
		unset( $allowed, $meta_key, $cap, $caps );
		return current_user_can( 'edit_post', $post_id ) && get_current_user_id() === $user_id;
	};

	register_post_meta(
		'doc_article',
		'doc_display_order',
		array(
			'type'              => 'integer',
			'default'           => 0,
			'single'            => true,
			'sanitize_callback' => 'absint',
			'show_in_rest'      => true,
			'auth_callback'     => $meta_permissions,
			'description'       => esc_html__( 'Controls ordering within documentation listings.', 'apparel' ),
		)
	);

	register_post_meta(
		'doc_article',
		'doc_feature_flag',
		array(
			'type'              => 'boolean',
			'default'           => false,
			'single'            => true,
			'sanitize_callback' => 'rest_sanitize_boolean',
			'show_in_rest'      => true,
			'auth_callback'     => $meta_permissions,
			'description'       => esc_html__( 'Feature flag toggle for Doc Article experiments.', 'apparel' ),
		)
	);

	register_post_meta(
		'doc_article',
		'doc_show_hero',
		array(
			'type'              => 'boolean',
			'default'           => true,
			'single'            => true,
			'sanitize_callback' => 'rest_sanitize_boolean',
			'show_in_rest'      => true,
			'auth_callback'     => $meta_permissions,
			'description'       => esc_html__( 'Controls hero visibility on Doc Article templates.', 'apparel' ),
		)
	);
}
add_action( 'init', 'mbf_register_doc_article_meta_fields' );
