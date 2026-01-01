<?php
/**
 * Docs custom post type and taxonomy registration.
 *
 * @package Apparel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Docs categories taxonomy.
 */
function mbf_register_docs_taxonomy() {
	$labels = array(
		'name'                       => _x( 'Docs Categories', 'taxonomy general name', 'apparel' ),
		'singular_name'              => _x( 'Docs Category', 'taxonomy singular name', 'apparel' ),
		'search_items'               => __( 'Search Docs Categories', 'apparel' ),
		'all_items'                  => __( 'All Docs Categories', 'apparel' ),
		'parent_item'                => __( 'Parent Docs Category', 'apparel' ),
		'parent_item_colon'          => __( 'Parent Docs Category:', 'apparel' ),
		'edit_item'                  => __( 'Edit Docs Category', 'apparel' ),
		'update_item'                => __( 'Update Docs Category', 'apparel' ),
		'add_new_item'               => __( 'Add New Docs Category', 'apparel' ),
		'new_item_name'              => __( 'New Docs Category Name', 'apparel' ),
		'menu_name'                  => __( 'Docs Categories', 'apparel' ),
		'separate_items_with_commas' => __( 'Separate docs categories with commas', 'apparel' ),
		'add_or_remove_items'        => __( 'Add or remove docs categories', 'apparel' ),
		'choose_from_most_used'      => __( 'Choose from the most used docs categories', 'apparel' ),
	);

	register_taxonomy(
		'docs_category',
		'docs',
		array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'public'            => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'docs-category',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'mbf_register_docs_taxonomy', 9 );

/**
 * Register the Docs custom post type.
 */
function mbf_register_docs_post_type() {
	$labels = array(
		'name'                  => _x( 'Docs', 'post type general name', 'apparel' ),
		'singular_name'         => _x( 'Doc', 'post type singular name', 'apparel' ),
		'menu_name'             => _x( 'Docs', 'admin menu', 'apparel' ),
		'name_admin_bar'        => _x( 'Doc', 'add new on admin bar', 'apparel' ),
		'add_new'               => __( 'Add New', 'apparel' ),
		'add_new_item'          => __( 'Add New Doc', 'apparel' ),
		'new_item'              => __( 'New Doc', 'apparel' ),
		'edit_item'             => __( 'Edit Doc', 'apparel' ),
		'view_item'             => __( 'View Doc', 'apparel' ),
		'all_items'             => __( 'All Docs', 'apparel' ),
		'search_items'          => __( 'Search Docs', 'apparel' ),
		'parent_item_colon'     => __( 'Parent Docs:', 'apparel' ),
		'not_found'             => __( 'No docs found.', 'apparel' ),
		'not_found_in_trash'    => __( 'No docs found in Trash.', 'apparel' ),
		'archives'              => __( 'Docs Archive', 'apparel' ),
		'featured_image'        => __( 'Doc Featured Image', 'apparel' ),
		'set_featured_image'    => __( 'Set featured image', 'apparel' ),
		'remove_featured_image' => __( 'Remove featured image', 'apparel' ),
		'use_featured_image'    => __( 'Use as featured image', 'apparel' ),
		'items_list'            => __( 'Docs list', 'apparel' ),
		'items_list_navigation' => __( 'Docs list navigation', 'apparel' ),
		'filter_items_list'     => __( 'Filter docs list', 'apparel' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'has_archive'        => 'docs',
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'menu_icon'          => 'dashicons-media-document',
		'rewrite'            => array(
			'slug'       => 'docs',
			'with_front' => false,
		),
		'supports'           => array(
			'title',
			'editor',
			'excerpt',
			'thumbnail',
			'author',
			'revisions',
		),
		'publicly_queryable' => true,
		'exclude_from_search'=> false,
		'capability_type'    => 'post',
		'map_meta_cap'       => true,
		'show_in_nav_menus'  => true,
	);

	register_post_type( 'docs', $args );
}
add_action( 'init', 'mbf_register_docs_post_type' );
