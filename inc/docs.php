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
}
add_action( 'init', 'mbf_register_doc_article_meta_fields' );
