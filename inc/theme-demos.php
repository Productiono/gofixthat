<?php
/**
 * Theme Demos
 *
 * @package Apparel
 */

/**
 * Register Demos of Theme
 */
function mbf_demos_list() {

	$plugins = array(
		array(
			'name'     => 'WooCommerce',
			'slug'     => 'woocommerce',
			'path'     => 'woocommerce/woocommerce.php',
			'required' => false,
			'recommended' => true,
			'desc'     => esc_html__( 'An eCommerce toolkit that helps you sell anything. Beautifully.', 'apparel' ),
		),
		array(
			'name'     => 'Variation Swatches for WooCommerce',
			'slug'     => 'woo-variation-swatches',
			'path'     => 'woo-variation-swatches/woo-variation-swatches.php',
			'required' => false,
			'recommended' => true,
			'desc'     => esc_html__( 'Beautiful Color, Image and Buttons Variation Swatches For WooCommerce Product Attributes', 'apparel' ),
		),
	);

	$demos = array(
		'apparel' => array(
			'name'      => esc_html__( 'Apparel', 'apparel' ),
			'preview'   => 'https://apparel.merchantsbestfriends.com/',
			'thumbnail' => get_template_directory_uri() . '/screenshot.png',
			'plugins'   => $plugins,
			'import'    => array(
				'customizer' => 'https://cloud.merchantsbestfriends.com/demo-content/apparel/apparel-customizer.dat',
				'widgets'    => 'https://cloud.merchantsbestfriends.com/demo-content/apparel/widgets.wie',
				'options'    => 'https://cloud.merchantsbestfriends.com/demo-content/apparel/options.json',
				'content'    => array(
					array(
						'label' => esc_html__( 'Demo Content', 'apparel' ),
						'url'   => 'https://cloud.merchantsbestfriends.com/demo-content/apparel/content.xml',
						'desc'  => esc_html__( 'Enabling this option will import demo posts, categories, and secondary pages. It\'s recommended to disable this option for existing', 'apparel' ),
					),
					array(
						'label' => esc_html__( 'Homepage', 'apparel' ),
						'url'   => 'https://cloud.merchantsbestfriends.com/demo-content/apparel/apparel-homepage.xml',
						'type'  => 'homepage',
					),
				),
			),
		),
	);

	return $demos;
}
add_filter( 'mbf_register_demos_list', 'mbf_demos_list' );

/**
 * Import Homepage
 *
 * @param int   $post_id New post ID.
 * @param array $data    Raw data imported for the post.
 */
function mbf_hook_import_homepage( $post_id, $data ) {
	if ( isset( $data['post_title'] ) && 'Homepage' === $data['post_title'] ) {
		// Set show_on_front.
		update_option( 'show_on_front', 'page' );

		// Set page_on_front.
		update_option( 'page_on_front', (int) $post_id );
	}
}
add_action( 'wxr_importer.db.post', 'mbf_hook_import_homepage', 10, 2 );

/**
 * Import Latest Posts
 *
 * @param int   $post_id New post ID.
 * @param array $data    Raw data imported for the post.
 */
function mbf_hook_import_latest_posts( $post_id, $data ) {
	if ( isset( $data['post_title'] ) && 'Latest Posts' === $data['post_title'] ) {
		// Set show_on_front.
		update_option( 'show_on_front', 'page' );

		// Set page_for_posts.
		update_option( 'page_for_posts', (int) $post_id );

		wp_update_post( wp_slash( array(
			'ID'           => $post_id,
			'post_content' => $post_content,
		) ) );
	}
}
add_action( 'wxr_importer.db.post', 'mbf_hook_import_latest_posts', 10, 2 );

/**
 * Finish Import
 */
function mbf_hook_finish_import() {

	/* Set menu locations. */
	$nav_menu_locations = array();

	$main_menu = get_term_by( 'name', 'Primary', 'nav_menu' );
	if ( $main_menu ) {
		$nav_menu_locations['primary'] = $main_menu->term_id;
		$nav_menu_locations['mobile']  = $main_menu->term_id;
	}

	$footer_menu = get_term_by( 'name', 'Footer', 'nav_menu' );
	if ( $footer_menu ) {
		$nav_menu_locations['footer'] = $footer_menu->term_id;
	}

	$secondary_menu = get_term_by( 'name', 'Secondary', 'nav_menu' );
	if ( $secondary_menu ) {
		$nav_menu_locations['secondary'] = $secondary_menu->term_id;
	}

	$additional_menu = get_term_by( 'name', 'Additional', 'nav_menu' );
	if ( $additional_menu ) {
		$nav_menu_locations['additional'] = $additional_menu->term_id;
	}

	if ( $nav_menu_locations ) {
		set_theme_mod( 'nav_menu_locations', $nav_menu_locations );
	}

	/* Add items to main menu */
	$main_menu = get_term_by( 'name', 'Primary', 'nav_menu' );

	if ( $main_menu && ! get_option( 'once_finished_import' ) ) {

		if ( function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'shop' ) ) {
			wp_update_nav_menu_item( $main_menu->term_id, 0, array(
				'menu-item-title'     => esc_html__( 'Basic Collection', 'apparel' ),
				'menu-item-classes'   => '',
				'menu-item-url'       => get_permalink( wc_get_page_id( 'shop' ) ),
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-object'    => 'page',
				'menu-item-object-id' => wc_get_page_id( 'shop' ),
			) );

			wp_update_nav_menu_item( $main_menu->term_id, 0, array(
				'menu-item-title'     => esc_html__( 'Blog', 'apparel' ),
				'menu-item-classes'   => '',
				'menu-item-url'       => get_permalink( mbf_get_page_id_by_title( 'Blog' ) ),
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-object'    => 'page',
				'menu-item-object-id' => mbf_get_page_id_by_title( 'Blog' ),
			) );

			wp_update_nav_menu_item( $main_menu->term_id, 0, array(
				'menu-item-title'     => esc_html__( 'About', 'apparel' ),
				'menu-item-classes'   => '',
				'menu-item-url'       => get_permalink( mbf_get_page_id_by_title( 'About' ) ),
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-object'    => 'page',
				'menu-item-object-id' => mbf_get_page_id_by_title( 'About' ),
			) );
		}
	}

	/* Adaptive content */
	$search_pages = array(
		'Homepage',
		'Blog',
	);

	foreach ( $search_pages as $search_title ) {

		$query = new WP_Query();

		$pages = $query->query( array(
			'post_type' => 'page',
			'title'     => $search_title,
		) );

		foreach ( $pages as $find_page ) {
			$post_content = $find_page->post_content;

			if ( 'Homepage' === $find_page->post_title ) {
				if ( function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'shop' ) ) {
					$post_content = str_replace( '/shop/', get_permalink( wc_get_page_id( 'shop' ) ), $post_content );
				}
				if ( mbf_get_page_id_by_title( 'Blog' ) ) {
					$post_content = str_replace( '/blog/', get_permalink( mbf_get_page_id_by_title( 'Blog' ) ), $post_content );
				}
				if ( mbf_get_page_id_by_title( 'About Us' ) ) {
					$post_content = str_replace( '/about-us/', get_permalink( mbf_get_page_id_by_title( 'About' ) ), $post_content );
				}

				$categories_homepage = array(
					'49' => 'tops',
					'44' => 'leggings',
					'46' => 'bras',
					'45' => 'sets',
					'47' => 'swimwear',
					'70' => 'shorts',
				);

				foreach ( $categories_homepage as $id_replace => $term_slug ) {

					$term = get_term_by( 'slug', $term_slug, 'product_cat' );

					if ( $term ) {
						$post_content = str_replace( sprintf( '"categoryId":%s', $id_replace ), sprintf( '"categoryId":%s', $term->term_id ), $post_content );

						$post_content = str_replace( sprintf( '/product-category/%s/', $term_slug ), get_term_link( $term->term_id ), $post_content );
					}
				}
			}

			if ( 'Blog' === $find_page->post_title ) {
				if ( get_option( 'page_for_posts' ) && get_permalink( get_option( 'page_for_posts' ) ) ) {
					$post_content = str_replace( '/latest-posts/', get_permalink( get_option( 'page_for_posts' ) ), $post_content );
				}
			}

			wp_update_post( wp_slash( array(
				'ID'           => $find_page->ID,
				'post_content' => $post_content,
			) ) );
		}
	}

	/* Add items to main menu */
	update_option( 'once_finished_import', true );
}
add_action( 'mbf_finish_import', 'mbf_hook_finish_import' );
