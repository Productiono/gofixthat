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
 * Default options for the Docs system.
 *
 * @return array
 */
function mbf_docs_default_options() {
	return array(
		'permalink_base'      => 'docs',
		'show_admin_columns'  => true,
		'show_admin_filters'  => true,
	);
}

/**
 * Fetch docs options merged with defaults.
 *
 * @return array
 */
function mbf_docs_get_options() {
	$options = get_option( 'mbf_docs_system_options', array() );

	return wp_parse_args( $options, mbf_docs_default_options() );
}

/**
 * Sanitize options for the Docs system.
 *
 * @param array $options Raw option values.
 * @return array
 */
function mbf_docs_sanitize_options( $options ) {
	$defaults = mbf_docs_default_options();

	$clean = array();

	$clean['permalink_base'] = isset( $options['permalink_base'] ) ? sanitize_title_with_dashes( $options['permalink_base'] ) : $defaults['permalink_base'];

	if ( empty( $clean['permalink_base'] ) ) {
		$clean['permalink_base'] = $defaults['permalink_base'];
	}

	$clean['show_admin_columns'] = ! empty( $options['show_admin_columns'] );
	$clean['show_admin_filters'] = ! empty( $options['show_admin_filters'] );

	return $clean;
}

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
	$options        = mbf_docs_get_options();
	$permalink_base = trailingslashit( $options['permalink_base'] );

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
			'capabilities'      => array(
				'manage_terms' => 'manage_doc_terms',
				'edit_terms'   => 'manage_doc_terms',
				'delete_terms' => 'manage_doc_terms',
				'assign_terms' => 'edit_doc_articles',
			),
			'rewrite'           => array(
				'slug'       => 'docs-category',
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
			'capabilities'      => array(
				'manage_terms' => 'manage_doc_terms',
				'edit_terms'   => 'manage_doc_terms',
				'delete_terms' => 'manage_doc_terms',
				'assign_terms' => 'edit_doc_articles',
			),
			'rewrite'           => array(
				'slug'       => 'docs-subcategory',
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
			'map_meta_cap'        => true,
			'capability_type'     => array( 'doc_article', 'doc_articles' ),
			'capabilities'        => array(
				'create_posts' => 'edit_doc_articles',
			),
			'rewrite'             => array(
				'slug'       => $permalink_base . '%doc_category%/%doc_subcategory%',
				'with_front' => false,
			),
			'publicly_queryable'  => true,
			'query_var'           => 'doc_article',
			'show_in_menu'        => false,
			'has_archive'         => $options['permalink_base'],
		)
	);

	add_rewrite_rule(
		'^' . $permalink_base . '([^/]+)/([^/]+)/([^/]+)/?$',
		'index.php?post_type=doc_article&name=$matches[3]&doc_category=$matches[1]&doc_subcategory=$matches[2]',
		'top'
	);
}
add_action( 'init', 'mbf_register_doc_article' );

/**
 * Add doc-specific capabilities and dedicated role.
 */
function mbf_register_doc_capabilities() {
	$doc_caps = array(
		'edit_doc_article',
		'read_doc_article',
		'delete_doc_article',
		'edit_doc_articles',
		'edit_others_doc_articles',
		'publish_doc_articles',
		'read_private_doc_articles',
		'delete_doc_articles',
		'delete_others_doc_articles',
		'manage_doc_terms',
	);

	$administrator = get_role( 'administrator' );
	if ( $administrator ) {
		foreach ( $doc_caps as $cap ) {
			$administrator->add_cap( $cap );
		}
	}

	$docs_role = get_role( 'docs_manager' );
	if ( ! $docs_role ) {
		$docs_role = add_role(
			'docs_manager',
			esc_html__( 'Docs Manager', 'apparel' ),
			array(
				'read'                    => true,
				'upload_files'            => true,
				'read_doc_article'        => true,
				'delete_doc_article'      => true,
				'edit_doc_articles'       => true,
				'publish_doc_articles'    => true,
				'edit_others_doc_articles' => true,
				'delete_doc_articles'     => true,
				'delete_others_doc_articles' => true,
				'read_private_doc_articles' => true,
				'manage_doc_terms'        => true,
			)
		);
	}

	if ( $docs_role ) {
		foreach ( $doc_caps as $cap ) {
			$docs_role->add_cap( $cap );
		}
	}
}
add_action( 'init', 'mbf_register_doc_capabilities', 5 );

/**
 * Map doc capabilities to primitive caps.
 *
 * @param array  $caps    Primitive caps.
 * @param string $cap     Requested cap.
 * @param int    $user_id User ID.
 * @param array  $args    Context args.
 * @return array
 */
function mbf_map_doc_meta_cap( $caps, $cap, $user_id, $args ) {
	switch ( $cap ) {
		case 'edit_doc_article':
		case 'delete_doc_article':
		case 'read_doc_article':
			$post = get_post( isset( $args[0] ) ? $args[0] : 0 );

			if ( ! $post || 'doc_article' !== $post->post_type ) {
				return $caps;
			}

			if ( 'read_doc_article' === $cap ) {
				if ( 'private' !== $post->post_status || user_can( $user_id, 'read_private_doc_articles' ) ) {
					return array( 'read' );
				}

				return array( 'do_not_allow' );
			}

			$is_author_cap = (int) $post->post_author === (int) $user_id ? 'edit_doc_articles' : 'edit_others_doc_articles';
			$delete_cap    = (int) $post->post_author === (int) $user_id ? 'delete_doc_articles' : 'delete_others_doc_articles';

			return 'edit_doc_article' === $cap ? array( $is_author_cap ) : array( $delete_cap );

		case 'edit_doc_articles':
		case 'edit_others_doc_articles':
		case 'publish_doc_articles':
		case 'read_private_doc_articles':
		case 'delete_doc_articles':
		case 'delete_others_doc_articles':
		case 'manage_doc_terms':
			return array( $cap );
	}

	return $caps;
}
add_filter( 'map_meta_cap', 'mbf_map_doc_meta_cap', 10, 4 );

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
 * Register Docs admin menu and submenu pages.
 */
function mbf_register_docs_menu_pages() {
	$capability = current_user_can( 'manage_options' ) ? 'manage_options' : 'edit_doc_articles';

	add_menu_page(
		esc_html__( 'Docs', 'apparel' ),
		esc_html__( 'Docs', 'apparel' ),
		$capability,
		'docs-admin',
		'mbf_render_docs_menu_page',
		'dashicons-media-document',
		6
	);

	add_submenu_page(
		'docs-admin',
		esc_html__( 'All Docs', 'apparel' ),
		esc_html__( 'All Docs', 'apparel' ),
		'edit_doc_articles',
		'edit.php?post_type=doc_article'
	);

	add_submenu_page(
		'docs-admin',
		esc_html__( 'Add New', 'apparel' ),
		esc_html__( 'Add New', 'apparel' ),
		'edit_doc_articles',
		'post-new.php?post_type=doc_article'
	);

	add_submenu_page(
		'docs-admin',
		esc_html__( 'Docs Settings', 'apparel' ),
		esc_html__( 'Settings', 'apparel' ),
		'manage_options',
		'docs-system-settings',
		'mbf_render_docs_settings_page'
	);
}
add_action( 'admin_menu', 'mbf_register_docs_menu_pages' );

/**
 * Render the Docs landing page.
 */
function mbf_render_docs_menu_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Docs', 'apparel' ); ?></h1>
		<p><?php esc_html_e( 'Manage documentation articles, categories, and settings from the menu options on the left.', 'apparel' ); ?></p>
	</div>
	<?php
}

/**
 * Register Docs settings.
 */
function mbf_register_docs_settings() {
	register_setting(
		'mbf_docs_system',
		'mbf_docs_system_options',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'mbf_docs_sanitize_options',
			'default'           => mbf_docs_default_options(),
		)
	);

	add_settings_section(
		'mbf_docs_permalink_section',
		esc_html__( 'Docs Permalink Settings', 'apparel' ),
		'__return_null',
		'docs-system-settings'
	);

	add_settings_field(
		'mbf_docs_permalink_base',
		esc_html__( 'Docs Permalink Base', 'apparel' ),
		'mbf_render_docs_permalink_field',
		'docs-system-settings',
		'mbf_docs_permalink_section'
	);

	add_settings_section(
		'mbf_docs_admin_section',
		esc_html__( 'Admin Display', 'apparel' ),
		'__return_null',
		'docs-system-settings'
	);

	add_settings_field(
		'mbf_docs_show_columns',
		esc_html__( 'Show Admin Columns', 'apparel' ),
		'mbf_render_docs_columns_field',
		'docs-system-settings',
		'mbf_docs_admin_section'
	);

	add_settings_field(
		'mbf_docs_show_filters',
		esc_html__( 'Show Admin Filters', 'apparel' ),
		'mbf_render_docs_filters_field',
		'docs-system-settings',
		'mbf_docs_admin_section'
	);
}
add_action( 'admin_init', 'mbf_register_docs_settings' );

/**
 * Render the settings page for Docs.
 */
function mbf_render_docs_settings_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Docs Settings', 'apparel' ); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'mbf_docs_system' );
			do_settings_sections( 'docs-system-settings' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Render the permalink base field.
 */
function mbf_render_docs_permalink_field() {
	$options        = mbf_docs_get_options();
	$permalink_base = $options['permalink_base'];
	?>
	<input type="text" name="mbf_docs_system_options[permalink_base]" value="<?php echo esc_attr( $permalink_base ); ?>" class="regular-text" />
	<p class="description"><?php esc_html_e( 'Base slug used for Doc Article URLs.', 'apparel' ); ?></p>
	<?php
}

/**
 * Render the admin columns toggle.
 */
function mbf_render_docs_columns_field() {
	$options = mbf_docs_get_options();
	?>
	<label for="mbf_docs_show_admin_columns">
		<input type="checkbox" id="mbf_docs_show_admin_columns" name="mbf_docs_system_options[show_admin_columns]" value="1" <?php checked( $options['show_admin_columns'] ); ?> />
		<?php esc_html_e( 'Display Doc Category and Doc Subcategory columns in the Doc Article list.', 'apparel' ); ?>
	</label>
	<?php
}

/**
 * Render the admin filters toggle.
 */
function mbf_render_docs_filters_field() {
	$options = mbf_docs_get_options();
	?>
	<label for="mbf_docs_show_admin_filters">
		<input type="checkbox" id="mbf_docs_show_admin_filters" name="mbf_docs_system_options[show_admin_filters]" value="1" <?php checked( $options['show_admin_filters'] ); ?> />
		<?php esc_html_e( 'Display Doc Category and Doc Subcategory filters above the Doc Article list.', 'apparel' ); ?>
	</label>
	<?php
}

/**
 * Add custom list table columns for Doc Articles.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function mbf_doc_article_posts_columns( $columns ) {
	$options = mbf_docs_get_options();

	if ( ! $options['show_admin_columns'] ) {
		return $columns;
	}

	$insert_after = array_slice( $columns, 0, 2, true );
	$rest         = array_slice( $columns, 2, null, true );

	$insert_after['doc_category']    = esc_html__( 'Doc Category', 'apparel' );
	$insert_after['doc_subcategory'] = esc_html__( 'Doc Subcategory', 'apparel' );

	return array_merge( $insert_after, $rest );
}
add_filter( 'manage_doc_article_posts_columns', 'mbf_doc_article_posts_columns' );

/**
 * Render custom column content.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function mbf_doc_article_custom_column( $column, $post_id ) {
	if ( 'doc_category' === $column ) {
		$terms = get_the_term_list( $post_id, 'doc_category', '', ', ' );
		echo $terms ? wp_kses_post( $terms ) : '&mdash;';
		return;
	}

	if ( 'doc_subcategory' === $column ) {
		$terms = get_the_term_list( $post_id, 'doc_subcategory', '', ', ' );
		echo $terms ? wp_kses_post( $terms ) : '&mdash;';
	}
}
add_action( 'manage_doc_article_posts_custom_column', 'mbf_doc_article_custom_column', 10, 2 );

/**
 * Add taxonomy filters to the Doc Article list table.
 *
 * @param string $post_type Current post type.
 */
function mbf_doc_article_filters( $post_type ) {
	$options = mbf_docs_get_options();

	if ( 'doc_article' !== $post_type || ! $options['show_admin_filters'] ) {
		return;
	}

	$taxonomies = array(
		'doc_category'    => esc_html__( 'All Doc Categories', 'apparel' ),
		'doc_subcategory' => esc_html__( 'All Doc Subcategories', 'apparel' ),
	);

	foreach ( $taxonomies as $taxonomy => $label ) {
		$current = isset( $_GET[ $taxonomy ] ) ? sanitize_text_field( wp_unslash( $_GET[ $taxonomy ] ) ) : '';

		wp_dropdown_categories(
			array(
				'show_option_all' => $label,
				'taxonomy'        => $taxonomy,
				'name'            => $taxonomy,
				'orderby'         => 'name',
				'selected'        => $current,
				'hierarchical'    => true,
				'show_count'      => false,
				'hide_empty'      => false,
			)
		);
	}
}
add_action( 'restrict_manage_posts', 'mbf_doc_article_filters' );

/**
 * Apply list table filters to the query.
 *
 * @param WP_Query $query Query instance.
 */
function mbf_doc_article_filter_query( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( 'doc_article' !== $query->get( 'post_type' ) ) {
		return;
	}

	$tax_query = array();

	if ( ! empty( $_GET['doc_category'] ) && is_numeric( $_GET['doc_category'] ) ) {
		$tax_query[] = array(
			'taxonomy' => 'doc_category',
			'field'    => 'term_id',
			'terms'    => absint( $_GET['doc_category'] ),
		);
	}

	if ( ! empty( $_GET['doc_subcategory'] ) && is_numeric( $_GET['doc_subcategory'] ) ) {
		$tax_query[] = array(
			'taxonomy' => 'doc_subcategory',
			'field'    => 'term_id',
			'terms'    => absint( $_GET['doc_subcategory'] ),
		);
	}

	if ( ! empty( $tax_query ) ) {
		$query->set( 'tax_query', $tax_query );
	}
}
add_action( 'pre_get_posts', 'mbf_doc_article_filter_query' );

/**
 * Order frontend doc archives by display order then title.
 *
 * @param WP_Query $query Query instance.
 */
function mbf_doc_article_order_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! ( $query->is_post_type_archive( 'doc_article' ) || $query->is_tax( array( 'doc_category', 'doc_subcategory' ) ) ) ) {
		return;
	}

	$query->set(
		'orderby',
		array(
			'meta_value_num' => 'ASC',
			'title'          => 'ASC',
		)
	);
	$query->set( 'meta_key', 'doc_display_order' );
}
add_action( 'pre_get_posts', 'mbf_doc_article_order_query' );

/**
 * Flush rewrite rules when Docs settings change.
 *
 * @param mixed $old_value Previous value.
 * @param mixed $value     New value.
 * @param string $option   Option name.
 */
function mbf_docs_maybe_flush_rewrite( $old_value, $value, $option ) {
	unset( $option );

	$old_base = isset( $old_value['permalink_base'] ) ? $old_value['permalink_base'] : mbf_docs_default_options()['permalink_base'];
	$new_base = isset( $value['permalink_base'] ) ? $value['permalink_base'] : mbf_docs_default_options()['permalink_base'];

	if ( $old_base !== $new_base ) {
		flush_rewrite_rules();
	}
}
add_action( 'update_option_mbf_docs_system_options', 'mbf_docs_maybe_flush_rewrite', 10, 3 );

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

	$term_meta_permissions = function() {
		return current_user_can( 'manage_categories' );
	};

	register_term_meta(
		'doc_category',
		'doc_display_order',
		array(
			'type'              => 'integer',
			'default'           => 0,
			'description'       => esc_html__( 'Controls ordering within doc categories.', 'apparel' ),
			'single'            => true,
			'sanitize_callback' => 'absint',
			'show_in_rest'      => true,
			'auth_callback'     => $term_meta_permissions,
		)
	);

	register_term_meta(
		'doc_subcategory',
		'doc_display_order',
		array(
			'type'              => 'integer',
			'default'           => 0,
			'description'       => esc_html__( 'Controls ordering within doc subcategories.', 'apparel' ),
			'single'            => true,
			'sanitize_callback' => 'absint',
			'show_in_rest'      => true,
			'auth_callback'     => $term_meta_permissions,
		)
	);
}
add_action( 'init', 'mbf_register_doc_article_meta_fields' );

/**
 * Add Doc Article settings metabox.
 */
function mbf_add_doc_article_metaboxes() {
	add_meta_box(
		'mbf-doc-article-settings',
		esc_html__( 'Doc Settings', 'apparel' ),
		'mbf_render_doc_article_metabox',
		'doc_article',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes_doc_article', 'mbf_add_doc_article_metaboxes' );

/**
 * Render doc article settings metabox.
 *
 * @param WP_Post $post Post instance.
 */
function mbf_render_doc_article_metabox( $post ) {
	wp_nonce_field( 'mbf_doc_article_meta', 'mbf_doc_article_meta_nonce' );

	$order        = get_post_meta( $post->ID, 'doc_display_order', true );
	$feature_flag = (bool) get_post_meta( $post->ID, 'doc_feature_flag', true );
	$show_hero    = (bool) get_post_meta( $post->ID, 'doc_show_hero', true );
	?>
	<p>
		<label for="mbf_doc_display_order"><?php esc_html_e( 'Display Order', 'apparel' ); ?></label><br />
		<input type="number" id="mbf_doc_display_order" name="mbf_doc_display_order" value="<?php echo esc_attr( absint( $order ) ); ?>" class="small-text" />
	</p>
	<p>
		<label>
			<input type="checkbox" name="mbf_doc_feature_flag" value="1" <?php checked( $feature_flag ); ?> />
			<?php esc_html_e( 'Enable feature flag', 'apparel' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="mbf_doc_show_hero" value="1" <?php checked( $show_hero ); ?> />
			<?php esc_html_e( 'Show hero on templates', 'apparel' ); ?>
		</label>
	</p>
	<?php
}

/**
 * Validate and persist doc article metadata.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 * @param bool    $update  Whether this is an update.
 */
function mbf_save_doc_article_meta( $post_id, $post, $update ) {
	unset( $update );

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['mbf_doc_article_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mbf_doc_article_meta_nonce'] ) ), 'mbf_doc_article_meta' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_doc_article', $post_id ) ) {
		return;
	}

	$order = isset( $_POST['mbf_doc_display_order'] ) ? absint( $_POST['mbf_doc_display_order'] ) : 0;
	update_post_meta( $post_id, 'doc_display_order', $order );

	$feature_flag = ! empty( $_POST['mbf_doc_feature_flag'] );
	update_post_meta( $post_id, 'doc_feature_flag', $feature_flag );

	$show_hero = ! empty( $_POST['mbf_doc_show_hero'] );
	update_post_meta( $post_id, 'doc_show_hero', $show_hero );

	$categories    = wp_get_post_terms( $post_id, 'doc_category' );
	$subcategories = wp_get_post_terms( $post_id, 'doc_subcategory' );

	if ( ! is_wp_error( $subcategories ) && ! empty( $subcategories ) && ( empty( $categories ) || is_wp_error( $categories ) ) ) {
		wp_set_object_terms( $post_id, array(), 'doc_subcategory' );
	}
}
add_action( 'save_post_doc_article', 'mbf_save_doc_article_meta', 10, 3 );

/**
 * Register REST endpoint for doc submissions.
 */
function mbf_register_doc_submission_endpoint() {
	register_rest_route(
		'docs/v1',
		'/submit',
		array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'mbf_handle_doc_submission',
			'permission_callback' => 'mbf_can_submit_docs',
			'args'                => array(
				'title'          => array(
					'type'              => 'string',
					'required'          => true,
					'sanitize_callback' => 'sanitize_text_field',
				),
				'content'        => array(
					'type'              => 'string',
					'required'          => true,
					'sanitize_callback' => 'wp_kses_post',
				),
				'doc_category'   => array(
					'type'     => 'integer',
					'required' => false,
				),
				'doc_subcategory' => array(
					'type'     => 'integer',
					'required' => false,
				),
				'display_order' => array(
					'type'     => 'integer',
					'default'  => 0,
				),
				'feature_flag'  => array(
					'type'     => 'boolean',
					'default'  => false,
				),
				'show_hero'     => array(
					'type'    => 'boolean',
					'default' => true,
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'mbf_register_doc_submission_endpoint' );

/**
 * Permission callback for doc submissions.
 *
 * @return bool
 */
function mbf_can_submit_docs() {
	return current_user_can( 'edit_doc_articles' );
}

/**
 * Handle doc submission REST requests.
 *
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response|WP_Error
 */
function mbf_handle_doc_submission( WP_REST_Request $request ) {
	$category_id    = $request->get_param( 'doc_category' );
	$subcategory_id = $request->get_param( 'doc_subcategory' );

	if ( $subcategory_id && ! $category_id ) {
		return new WP_Error( 'mbf_doc_missing_category', esc_html__( 'Doc submissions with a subcategory must include a category.', 'apparel' ), array( 'status' => 400 ) );
	}

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'doc_article',
			'post_status'  => 'draft',
			'post_title'   => $request->get_param( 'title' ),
			'post_content' => $request->get_param( 'content' ),
			'post_author'  => get_current_user_id(),
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		return $post_id;
	}

	if ( $category_id ) {
		wp_set_post_terms( $post_id, array( absint( $category_id ) ), 'doc_category' );
	}

	if ( $subcategory_id && $category_id ) {
		wp_set_post_terms( $post_id, array( absint( $subcategory_id ) ), 'doc_subcategory' );
	}

	update_post_meta( $post_id, 'doc_display_order', absint( $request->get_param( 'display_order' ) ) );
	update_post_meta( $post_id, 'doc_feature_flag', (bool) $request->get_param( 'feature_flag' ) );
	update_post_meta( $post_id, 'doc_show_hero', (bool) $request->get_param( 'show_hero' ) );

	return rest_ensure_response(
		array(
			'id'      => $post_id,
			'link'    => get_permalink( $post_id ),
			'message' => esc_html__( 'Doc article submitted.', 'apparel' ),
		)
	);
}

/**
 * Retrieve the preferred order value for a docs term.
 *
 * @param int $term_id Term ID.
 * @return int
 */
function mbf_get_doc_term_order( $term_id ) {
	$order = get_term_meta( $term_id, 'doc_display_order', true );

	return $order ? absint( $order ) : 0;
}

/**
 * Sort docs items by their provided order then title.
 *
 * @param array $items Items to sort in place.
 * @return void
 */
function mbf_sort_doc_items( &$items ) {
	usort(
		$items,
		function( $a, $b ) {
			$order_a = isset( $a['order'] ) ? absint( $a['order'] ) : 0;
			$order_b = isset( $b['order'] ) ? absint( $b['order'] ) : 0;
			$strtolower = function_exists( 'mb_strtolower' ) ? 'mb_strtolower' : 'strtolower';

			if ( $order_a === $order_b ) {
				return strcmp( $strtolower( $a['title'] ), $strtolower( $b['title'] ) );
			}

			return $order_a > $order_b ? 1 : -1;
		}
	);
}

/**
 * Build the hierarchical docs collection for use in sidebar navigation.
 *
 * @return array
 */
function mbf_get_doc_sidebar_items() {
	$categories = get_terms(
		array(
			'taxonomy'   => 'doc_category',
			'hide_empty' => false,
		)
	);

	if ( is_wp_error( $categories ) ) {
		return array();
	}

	$active_ids = mbf_get_active_doc_references();
	$items      = array();

	foreach ( $categories as $category ) {
		$category_node = array(
			'id'       => $category->term_id,
			'title'    => $category->name,
			'url'      => get_term_link( $category ),
			'order'    => mbf_get_doc_term_order( $category->term_id ),
			'type'     => 'category',
			'children' => array(),
		);

		if ( is_wp_error( $category_node['url'] ) ) {
			continue;
		}

		$posts = get_posts(
			array(
				'post_type'      => 'doc_article',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'tax_query'      => array(
					array(
						'taxonomy' => 'doc_category',
						'field'    => 'term_id',
						'terms'    => array( $category->term_id ),
					),
				),
			)
		);

		$subcategory_map      = array();
		$uncategorized_posts  = array();

		foreach ( $posts as $post ) {
			$article_node = array(
				'id'       => $post->ID,
				'title'    => get_the_title( $post ),
				'url'      => get_permalink( $post ),
				'order'    => get_post_meta( $post->ID, 'doc_display_order', true ),
				'type'     => 'article',
				'children' => array(),
			);

			$subcategories = get_the_terms( $post, 'doc_subcategory' );

			if ( ! empty( $subcategories ) && ! is_wp_error( $subcategories ) ) {
				foreach ( $subcategories as $subcategory ) {
					if ( ! isset( $subcategory_map[ $subcategory->term_id ] ) ) {
						$subcategory_map[ $subcategory->term_id ] = array(
							'id'       => $subcategory->term_id,
							'title'    => $subcategory->name,
							'url'      => get_term_link( $subcategory ),
							'order'    => mbf_get_doc_term_order( $subcategory->term_id ),
							'type'     => 'subcategory',
							'children' => array(),
						);
					}

					if ( is_wp_error( $subcategory_map[ $subcategory->term_id ]['url'] ) ) {
						continue;
					}

					$subcategory_map[ $subcategory->term_id ]['children'][] = $article_node;
				}
			} else {
				$uncategorized_posts[] = $article_node;
			}
		}

		foreach ( $subcategory_map as &$subcategory_node ) {
			mbf_sort_doc_items( $subcategory_node['children'] );
		}
		unset( $subcategory_node );

		$category_node['children'] = array_values( $subcategory_map );

		if ( $uncategorized_posts ) {
			$category_node['children'] = array_merge( $category_node['children'], $uncategorized_posts );
		}

		mbf_sort_doc_items( $category_node['children'] );

		$items[] = $category_node;
	}

	mbf_sort_doc_items( $items );

	return mbf_flag_active_docs( $items, $active_ids );
}

/**
 * Determine the active doc items for highlighting in the sidebar.
 *
 * @return array
 */
function mbf_get_active_doc_references() {
	$post_ids        = array();
	$category_ids    = array();
	$subcategory_ids = array();

	if ( is_singular( 'doc_article' ) ) {
		$post_ids[] = get_the_ID();

		$category_terms = get_the_terms( get_the_ID(), 'doc_category' );
		if ( ! empty( $category_terms ) && ! is_wp_error( $category_terms ) ) {
			$category_ids = wp_list_pluck( $category_terms, 'term_id' );
		}

		$subcategory_terms = get_the_terms( get_the_ID(), 'doc_subcategory' );
		if ( ! empty( $subcategory_terms ) && ! is_wp_error( $subcategory_terms ) ) {
			$subcategory_ids = wp_list_pluck( $subcategory_terms, 'term_id' );
		}
	} elseif ( is_tax( 'doc_category' ) ) {
		$term         = get_queried_object();
		$category_ids = array( $term->term_id );
	} elseif ( is_tax( 'doc_subcategory' ) ) {
		$term            = get_queried_object();
		$subcategory_ids = array( $term->term_id );
	}

	return array(
		'posts'        => $post_ids,
		'categories'   => $category_ids,
		'subcategories' => $subcategory_ids,
	);
}

/**
 * Apply active states to docs items.
 *
 * @param array $items      Sidebar items.
 * @param array $active_ids Active ids array.
 * @return array
 */
function mbf_flag_active_docs( $items, $active_ids ) {
	foreach ( $items as &$item ) {
		$item['is_active'] = false;

		if ( 'article' === $item['type'] && in_array( $item['id'], $active_ids['posts'], true ) ) {
			$item['is_active'] = true;
		}

		if ( 'category' === $item['type'] && in_array( $item['id'], $active_ids['categories'], true ) ) {
			$item['is_active'] = true;
		}

		if ( 'subcategory' === $item['type'] && in_array( $item['id'], $active_ids['subcategories'], true ) ) {
			$item['is_active'] = true;
		}

		if ( ! empty( $item['children'] ) ) {
			$item['children'] = mbf_flag_active_docs( $item['children'], $active_ids );
			foreach ( $item['children'] as $child ) {
				if ( ! empty( $child['is_active'] ) ) {
					$item['is_active'] = true;
					break;
				}
			}
		}
	}

	return $items;
}

if ( ! class_exists( 'MBF_Docs_Sidebar_Walker' ) ) {
	/**
	 * Sidebar walker for the docs navigation.
	 */
	class MBF_Docs_Sidebar_Walker extends Walker {
		/**
		 * What the class handles.
		 *
		 * @var string
		 */
		public $tree_type = array( 'doc_category', 'doc_subcategory', 'doc_article' );

		/**
		 * DB fields to use.
		 *
		 * @var array
		 */
		public $db_fields = array( 'parent' => 'parent', 'id' => 'id' );

		/**
		 * Starts the list before the elements are added.
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args   Additional arguments.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$indent     = str_repeat( "\t", $depth );
			$list_class = ! empty( $args['list_class'] ) ? $args['list_class'] : 'docs-sidebar__list';

			$output .= "\n{$indent}<ul class=\"" . esc_attr( $list_class ) . "\">\n";
		}

		/**
		 * Ends the list of after the elements are added.
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args   Additional arguments.
		 */
		public function end_lvl( &$output, $depth = 0, $args = array() ) {
			unset( $args );
			$indent  = str_repeat( "\t", $depth );
			$output .= "{$indent}</ul>\n";
		}

		/**
		 * Start the element output.
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param array  $item   Data object.
		 * @param int    $depth  Depth of menu item.
		 * @param array  $args   Additional arguments.
		 * @param int    $id     Current item ID.
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
			$indent     = ( $depth ) ? str_repeat( "\t", $depth ) : '';
			$link_class = ! empty( $args['link_class'] ) ? $args['link_class'] : 'docs-sidebar__link';
			$item_class = array( 'docs-sidebar__item', 'docs-sidebar__item--' . $item['type'] );

			if ( ! empty( $item['is_active'] ) ) {
				$item_class[] = 'is-active';
			}

			$output .= $indent . '<li class="' . esc_attr( implode( ' ', $item_class ) ) . '">';

			$is_current = ! empty( $item['is_active'] ) && ( 'article' === $item['type'] || ( 'category' === $item['type'] && is_tax( 'doc_category' ) ) || ( 'subcategory' === $item['type'] && is_tax( 'doc_subcategory' ) ) );

			$output .= '<a class="' . esc_attr( $link_class ) . '" href="' . esc_url( $item['url'] ) . '"';

			if ( $is_current ) {
				$output .= ' aria-current="page"';
			}

			$output .= '>' . esc_html( $item['title'] ) . '</a>';
		}

		/**
		 * Ends the element output, if needed.
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param array  $item   Data object.
		 * @param int    $depth  Depth of menu item.
		 * @param array  $args   Additional arguments.
		 */
		public function end_el( &$output, $item, $depth = 0, $args = array() ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
			unset( $item, $args, $depth );
			$output .= "</li>\n";
		}

		/**
		 * Display an individual element and its children.
		 *
		 * @param array  $element           Element data.
		 * @param array  $children_elements Children elements (unused).
		 * @param int    $max_depth         Max depth.
		 * @param int    $depth             Current depth.
		 * @param array  $args              Additional args.
		 * @param string $output            Output reference.
		 */
		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
			$this->start_el( $output, $element, $depth, $args[0] );

			if ( ( 0 === $max_depth || ( $depth + 1 ) < $max_depth ) && ! empty( $element['children'] ) ) {
				$this->start_lvl( $output, $depth, $args[0] );

				foreach ( $element['children'] as $child ) {
					$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
				}

				$this->end_lvl( $output, $depth, $args[0] );
			}

			$this->end_el( $output, $element, $depth, $args[0] );
		}

		/**
		 * Traverse elements to create list markup.
		 *
		 * @param array $elements Elements to list.
		 * @param int   $max_depth Max depth.
		 * @return string
		 */
		public function walk( $elements, $max_depth, ...$args ) {
			$output = '';

			if ( empty( $args[0] ) ) {
				$args[0] = array();
			}

			foreach ( (array) $elements as $e ) {
				$this->display_element( $e, array(), $max_depth, 0, $args, $output );
			}

			return $output;
		}
	}
}

/**
 * Render a scoped search form for documentation.
 *
 * @return string
 */
function mbf_get_doc_search_form() {
	$form  = '<form role="search" method="get" class="docs-search" action="' . esc_url( home_url( '/' ) ) . '">';
	$form .= '<label class="screen-reader-text" for="docs-search-field">' . esc_html__( 'Search documentation', 'apparel' ) . '</label>';
	$form .= '<input type="search" id="docs-search-field" class="docs-search__field" placeholder="' . esc_attr__( 'Search docs', 'apparel' ) . '" value="' . esc_attr( get_search_query() ) . '" name="s" />';
	$form .= '<input type="hidden" name="post_type" value="doc_article" />';
	$form .= '<button type="submit" class="docs-search__submit">' . esc_html__( 'Search', 'apparel' ) . '</button>';
	$form .= '</form>';

	return $form;
}

/**
 * Enqueue inline behaviors for documentation templates.
 */
function mbf_enqueue_doc_assets() {
	if ( ! ( is_singular( 'doc_article' ) || is_post_type_archive( 'doc_article' ) || is_tax( array( 'doc_category', 'doc_subcategory' ) ) ) ) {
		return;
	}

	$script = <<<'JS'
( function() {
	var sidebar = document.querySelector('[data-docs-sidebar]');
	if ( ! sidebar ) {
		return;
	}

	var drawer = sidebar.querySelector('[data-docs-sidebar-drawer]');
	var toggles = sidebar.querySelectorAll('[data-docs-sidebar-toggle]');
	var backdrop = sidebar.querySelector('[data-docs-sidebar-backdrop]');
	var mediaQuery = window.matchMedia('(min-width: 960px)');

	function setVisibility( isOpen ) {
		sidebar.classList.toggle('is-open', isOpen);

		if ( drawer ) {
			drawer.setAttribute('aria-hidden', isOpen || mediaQuery.matches ? 'false' : 'true');
		}

		if ( toggles.length ) {
			for ( var i = 0; i < toggles.length; i++ ) {
				toggles[i].setAttribute('aria-expanded', isOpen ? 'true' : 'false');
			}
		}

		if ( backdrop ) {
			if ( isOpen && ! mediaQuery.matches ) {
				backdrop.removeAttribute('hidden');
			} else {
				backdrop.setAttribute('hidden', 'hidden');
			}
		}
	}

	function toggleSidebar() {
		var isOpen = sidebar.classList.contains('is-open');
		setVisibility( ! isOpen );
	}

	function closeSidebar() {
		setVisibility( false );
	}

	setVisibility( mediaQuery.matches );

	for ( var i = 0; i < toggles.length; i++ ) {
		toggles[i].addEventListener('click', toggleSidebar );
	}

	if ( backdrop ) {
		backdrop.addEventListener('click', closeSidebar );
	}

	mediaQuery.addEventListener('change', function( event ) {
		setVisibility( event.matches );
	});

	document.addEventListener('keyup', function( event ) {
		if ( 'Escape' === event.key && sidebar.classList.contains('is-open') && ! mediaQuery.matches ) {
			closeSidebar();
		}
	});
} )();
JS;

	wp_add_inline_script( 'mbf-scripts', $script );
}
add_action( 'wp_enqueue_scripts', 'mbf_enqueue_doc_assets' );
