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

if ( ! function_exists( 'mbf_get_docs_nav_links' ) ) {
	/**
	 * Build docs navigation links.
	 *
	 * @param array|null $docs_categories Optional docs categories list to avoid duplicate queries.
	 * @param int|null   $active_term_id  Optional term ID to mark active.
	 *
	 * @return array
	 */
	function mbf_get_docs_nav_links( $docs_categories = null, $active_term_id = null ) {
		if ( null === $docs_categories ) {
			$docs_categories = get_terms(
				array(
					'taxonomy'   => 'docs_category',
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
				)
			);
		}

		$nav_links = array(
			array(
				'label' => __( 'Docs Home', 'apparel' ),
				'url'   => get_post_type_archive_link( 'docs' ),
				'class' => null === $active_term_id ? 'is-active' : '',
			),
		);

		if ( ! empty( $docs_categories ) && ! is_wp_error( $docs_categories ) ) {
			foreach ( $docs_categories as $category ) {
				$nav_links[] = array(
					'label' => $category->name,
					'url'   => get_term_link( $category ),
					'class' => ( (int) $category->term_id === (int) $active_term_id ) ? 'is-active' : '',
				);
			}
		}

		$nav_links[] = array(
			'label' => __( 'All Articles', 'apparel' ),
			'url'   => '#docs-articles',
		);

		return $nav_links;
	}
}

if ( ! function_exists( 'mbf_get_docs_search_markup' ) ) {
	/**
	 * Get the docs search form markup.
	 *
	 * @return string
	 */
	function mbf_get_docs_search_markup() {
		return '<form role="search" aria-label="' . esc_attr__( 'Search documentation', 'apparel' ) . '" action="' . esc_url( home_url( '/' ) ) . '">
	<span class="docs-search-icon" aria-hidden="true">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
			<path d="m15.5 15.5 4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
			<circle cx="11" cy="11" r="5.5" stroke="currentColor" stroke-width="1.6" />
		</svg>
	</span>
	<input type="search" name="s" placeholder="' . esc_attr__( 'Search docs...', 'apparel' ) . '" aria-label="' . esc_attr__( 'Search query', 'apparel' ) . '" />
	<input type="hidden" name="post_type" value="docs" />
	<span class="docs-search-hint" aria-hidden="true">Ctrl K</span>
</form>';
	}
}

if ( ! function_exists( 'mbf_get_docs_utility_markup' ) ) {
	/**
	 * Get docs header utility markup.
	 *
	 * @return string
	 */
	function mbf_get_docs_utility_markup() {
		ob_start();
		mbf_component( 'header_scheme_toggle' );
		return '<div class="docs-utility">' . ob_get_clean() . '</div>';
	}
}

if ( ! function_exists( 'mbf_get_docs_header_css' ) ) {
	/**
	 * Docs header styles shared across templates.
	 *
	 * @return string
	 */
	function mbf_get_docs_header_css() {
		return trim(
			':root {
			--docs-text: #1d1d1d;
			--docs-muted: #4a4a4a;
			--docs-border: #e3e3e3;
			--docs-surface: #f7f7f7;
			--docs-card: #ffffff;
			--docs-accent: #181818;
		}

		* {
			box-sizing: border-box;
		}

		.docs-page {
			min-height: 100vh;
			display: flex;
			flex-direction: column;
			background: transparent;
		}

		.docs-header {
			position: sticky;
			top: 0;
			z-index: 50;
			background: #fbfbfb;
			border-bottom: 1px solid #ededed;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
		}

		.docs-header-inner {
			max-width: 1320px;
			margin: 0 auto;
			padding: 16px 32px 14px;
			display: grid;
			grid-template-columns: auto 1fr auto;
			align-items: center;
			gap: 22px;
		}

		.docs-brand-nav {
			display: flex;
			align-items: center;
			min-width: 0;
			gap: 20px;
		}

		.docs-nav {
			display: flex;
			align-items: center;
			gap: 18px;
			font-weight: 600;
			font-size: 14px;
			margin-left: 6px;
			flex-wrap: wrap;
		}

		.docs-nav a {
			text-decoration: none;
			color: #2c2c2c;
			padding: 10px 4px 12px;
			border-bottom: 2px solid transparent;
			transition: color 0.15s ease, border-color 0.15s ease;
			display: inline-flex;
			align-items: center;
			gap: 8px;
		}

		.docs-nav a.is-active {
			color: #111;
			border-color: #1f1f1f;
		}

		.docs-nav a:hover {
			color: #000;
			border-color: rgba(0, 0, 0, 0.12);
		}

		.docs-search {
			display: flex;
			justify-content: center;
			align-items: center;
		}

		.docs-search form {
			width: min(720px, 100%);
			position: relative;
		}

		.docs-search input {
			width: 100%;
			padding: 12px 110px 12px 44px;
			border-radius: 14px;
			border: 1px solid #dcdcdc;
			background: #fff;
			box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7), 0 10px 30px rgba(0, 0, 0, 0.06);
			font-size: 14px;
			color: #262626;
			outline: none;
		}

		.docs-search input::placeholder {
			color: #7a7a7a;
		}

		.docs-search .docs-search-icon {
			position: absolute;
			left: 16px;
			top: 50%;
			transform: translateY(-50%);
			color: #9a9a9a;
		}

		.docs-search .docs-search-hint {
			position: absolute;
			right: 14px;
			top: 50%;
			transform: translateY(-50%);
			font-size: 12px;
			color: #5e5e5e;
			background: #f1f1f1;
			border: 1px solid #d9d9d9;
			border-radius: 8px;
			padding: 6px 8px;
			font-weight: 600;
		}

		.docs-utility {
			display: flex;
			justify-content: flex-end;
			align-items: center;
			gap: 16px;
			font-size: 13px;
			color: #3f3f3f;
			font-weight: 600;
		}

		.docs-utility a {
			color: inherit;
			text-decoration: none;
		}

		.docs-utility .mbf-site-scheme-toggle {
			display: inline-flex;
			align-items: center;
			gap: 8px;
			padding: 8px 10px;
			border-radius: 12px;
			border: 1px solid #e5e5e5;
			background: #fff;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
			cursor: pointer;
		}

		.docs-utility .mbf-site__scheme-toggle-element {
			background: #0f0f0f;
			color: #fff;
		}

		.docs-mobile-menu {
			display: none;
			align-items: center;
			justify-content: center;
			width: 42px;
			height: 42px;
			padding: 10px;
			border: none;
			background: transparent;
			color: #1f1f1f;
			cursor: pointer;
			justify-self: end;
		}

		.docs-mobile-menu:focus-visible {
			outline: 2px solid #1f1f1f;
			outline-offset: 2px;
		}

		.docs-mobile-menu__lines {
			display: grid;
			gap: 6px;
			width: 100%;
		}

		.docs-mobile-menu__line {
			display: block;
			height: 2px;
			border-radius: 999px;
			background: currentColor;
		}

		.docs-mobile-panel {
			display: none;
			padding: 12px 18px 18px;
			background: #ffffff;
			border-bottom: 1px solid #ededed;
			box-shadow: 0 14px 30px rgba(0, 0, 0, 0.06);
			gap: 14px;
		}

		.docs-mobile-panel[hidden] {
			display: none !important;
		}

		.docs-header.is-mobile-open .docs-mobile-panel {
			display: grid;
		}

		.docs-nav-mobile {
			display: grid;
			gap: 8px;
			font-weight: 700;
		}

		.docs-nav-mobile a {
			display: block;
			padding: 10px 6px;
			border-radius: 10px;
			text-decoration: none;
			color: #1f1f1f;
			background: #f7f7f7;
			border: 1px solid #ececec;
			box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
		}

		.docs-nav-mobile a:hover,
		.docs-nav-mobile a.is-active {
			background: #f0f0f0;
			border-color: #e0e0e0;
		}

		@media (max-width: 960px) {
			.docs-header {
				position: fixed;
				inset: 0 0 auto 0;
			}

			.docs-header-inner {
				grid-template-columns: 1fr auto;
				padding: 14px 18px 12px;
				gap: 10px;
			}

			.docs-brand-nav {
				justify-content: flex-start;
				gap: 12px;
			}

			.docs-nav {
				display: none;
				margin-left: 0;
			}

			.docs-utility {
				display: none;
				justify-content: flex-start;
			}

			.docs-search {
				display: none;
			}

			.docs-mobile-panel {
				grid-template-columns: 1fr;
			}

			.docs-mobile-menu {
				display: inline-flex;
				margin-left: auto;
			}
		}
		'
		);
	}
}

if ( ! function_exists( 'mbf_render_docs_header' ) ) {
	/**
	 * Render the shared docs header.
	 *
	 * @param array $args Header arguments.
	 */
	function mbf_render_docs_header( $args = array() ) {
		$defaults = array(
			'nav_links'      => array(),
			'search_markup'  => '',
			'utility_markup' => '',
		);

		$args = wp_parse_args( $args, $defaults );
		?>
		<header class="docs-header" data-docs-header>
			<div class="docs-header-inner">
				<div class="docs-brand-nav">
					<?php mbf_component( 'header_logo' ); ?>
					<nav class="docs-nav" aria-label="<?php esc_attr_e( 'Documentation navigation', 'apparel' ); ?>">
						<?php foreach ( $args['nav_links'] as $nav_link ) : ?>
							<a class="<?php echo esc_attr( isset( $nav_link['class'] ) ? $nav_link['class'] : '' ); ?>" href="<?php echo esc_url( $nav_link['url'] ); ?>"><?php echo esc_html( $nav_link['label'] ); ?></a>
						<?php endforeach; ?>
					</nav>
				</div>
				<div class="docs-search">
					<?php echo $args['search_markup']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<?php echo $args['utility_markup']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<button class="docs-mobile-menu" type="button" aria-label="<?php esc_attr_e( 'Open menu', 'apparel' ); ?>" aria-expanded="false" data-docs-mobile-toggle>
					<span class="docs-mobile-menu__lines" aria-hidden="true">
						<span class="docs-mobile-menu__line"></span>
						<span class="docs-mobile-menu__line"></span>
						<span class="docs-mobile-menu__line"></span>
					</span>
				</button>
			</div>
			<div class="docs-mobile-panel" data-docs-mobile-panel hidden>
				<nav class="docs-nav docs-nav-mobile" aria-label="<?php esc_attr_e( 'Documentation navigation', 'apparel' ); ?>">
					<?php foreach ( $args['nav_links'] as $nav_link ) : ?>
						<a class="<?php echo esc_attr( isset( $nav_link['class'] ) ? $nav_link['class'] : '' ); ?>" href="<?php echo esc_url( $nav_link['url'] ); ?>"><?php echo esc_html( $nav_link['label'] ); ?></a>
					<?php endforeach; ?>
				</nav>
				<div class="docs-search docs-search-mobile">
					<?php echo $args['search_markup']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div class="docs-utility docs-utility-mobile">
					<?php echo $args['utility_markup']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</header>
		<?php
	}
}

if ( ! function_exists( 'mbf_docs_render_header_styles' ) ) {
	/**
	 * Print the shared docs header styles once per request.
	 */
	function mbf_docs_render_header_styles() {
		static $printed = false;

		if ( $printed ) {
			return;
		}

		$printed = true;

		printf( '<style>%s</style>', mbf_get_docs_header_css() );
	}
}

if ( ! function_exists( 'mbf_docs_header_script' ) ) {
	/**
	 * Docs header mobile toggle script.
	 */
	function mbf_docs_header_script() {
		return 'document.addEventListener("DOMContentLoaded", function () {
	const header = document.querySelector("[data-docs-header]");
	const mobileToggle = document.querySelector("[data-docs-mobile-toggle]");
	const mobilePanel = document.querySelector("[data-docs-mobile-panel]");

	if (!header || !mobileToggle || !mobilePanel) {
		return;
	}

	const openLabel = "' . esc_js( __( 'Open menu', 'apparel' ) ) . '";
	const closeLabel = "' . esc_js( __( 'Close menu', 'apparel' ) ) . '";

	const closeMenu = () => {
		header.classList.remove("is-mobile-open");
		mobilePanel.setAttribute("hidden", "hidden");
		mobileToggle.setAttribute("aria-expanded", "false");
		mobileToggle.setAttribute("aria-label", openLabel);
	};

	const openMenu = () => {
		header.classList.add("is-mobile-open");
		mobilePanel.removeAttribute("hidden");
		mobileToggle.setAttribute("aria-expanded", "true");
		mobileToggle.setAttribute("aria-label", closeLabel);
	};

	mobileToggle.addEventListener("click", () => {
		if (header.classList.contains("is-mobile-open")) {
			closeMenu();
		} else {
			openMenu();
		}
	});

	mobilePanel.addEventListener("click", (event) => {
		if (event.target.closest("a")) {
			closeMenu();
		}
	});

	document.addEventListener("click", (event) => {
		if (!header.contains(event.target) && header.classList.contains("is-mobile-open")) {
			closeMenu();
		}
	});

	window.addEventListener("resize", () => {
		if (window.innerWidth > 960 && header.classList.contains("is-mobile-open")) {
			closeMenu();
		}
	});

	document.addEventListener("keyup", (event) => {
		if (event.key === "Escape" && header.classList.contains("is-mobile-open")) {
			closeMenu();
		}
	});
});';
	}
}

if ( ! function_exists( 'mbf_docs_render_header_script' ) ) {
	/**
	 * Echo docs header script once per request.
	 */
	function mbf_docs_render_header_script() {
		static $printed = false;

		if ( $printed ) {
			return;
		}

		$printed = true;

		printf( '<script>%s</script>', mbf_docs_header_script() );
	}
}
