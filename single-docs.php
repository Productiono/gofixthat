<?php
/**
 * Single template for Docs.
 *
 * @package Apparel
 */

$docs_categories = get_terms(
	array(
		'taxonomy'   => 'docs_category',
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

if ( ! function_exists( 'mbf_render_docs_sidebar_term' ) ) {
	/**
	 * Render a docs category tree for the sidebar.
	 *
	 * @param WP_Term $term             The term to render.
	 * @param array   $active_term_ids  The active term and ancestor IDs.
	 * @param int     $current_post_id  Current docs ID.
	 * @param int     $level            Nesting depth.
	 */
	function mbf_render_docs_sidebar_term( $term, $active_term_ids, $current_post_id, $level = 0 ) {
		$is_active_branch = in_array( (int) $term->term_id, $active_term_ids, true );
		$child_terms      = get_terms(
			array(
				'taxonomy'   => 'docs_category',
				'hide_empty' => false,
				'parent'     => $term->term_id,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		$child_posts = get_posts(
			array(
				'post_type'      => 'docs',
				'posts_per_page' => 50,
				'orderby'        => array(
					'menu_order' => 'ASC',
					'title'      => 'ASC',
				),
				'tax_query'      => array(
					array(
						'taxonomy'         => 'docs_category',
						'terms'            => array( $term->term_id ),
						'include_children' => false,
					),
				),
				'fields'         => '',
			)
		);

		$has_children = ( ! empty( $child_terms ) && ! is_wp_error( $child_terms ) ) || ! empty( $child_posts );
		?>
		<li class="docs-sidebar-item docs-sidebar-term level-<?php echo (int) $level; ?> <?php echo $is_active_branch ? 'is-active-branch' : ''; ?>">
			<a class="docs-sidebar-link" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
				<span class="docs-sidebar-icon" aria-hidden="true">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
						<rect x="4" y="6" width="16" height="12" rx="2.6" stroke="currentColor" stroke-width="1.6" />
						<path d="M4 10.5h16" stroke="currentColor" stroke-width="1.6" />
					</svg>
				</span>
				<span class="docs-sidebar-text"><?php echo esc_html( $term->name ); ?></span>
				<?php if ( $has_children ) : ?>
					<span class="docs-sidebar-chevron" aria-hidden="true">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none">
							<path d="m10 8 4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</span>
				<?php endif; ?>
			</a>
			<?php if ( $has_children ) : ?>
				<ul class="docs-sidebar-sublist <?php echo $is_active_branch || 0 === $level ? 'is-expanded' : ''; ?>">
					<?php
					if ( ! empty( $child_posts ) ) {
						foreach ( $child_posts as $child_post ) {
							$is_current = (int) $child_post->ID === (int) $current_post_id;
							?>
							<li class="docs-sidebar-item docs-sidebar-doc <?php echo $is_current ? 'is-active' : ''; ?>">
								<a class="docs-sidebar-link" href="<?php echo esc_url( get_permalink( $child_post ) ); ?>">
									<span class="docs-sidebar-icon" aria-hidden="true">
										<svg width="16" height="16" viewBox="0 0 24 24" fill="none">
											<path d="M8 5h5.6c.4 0 .8.2 1 .4l2 2c.2.2.4.6.4 1V19c0 .6-.4 1-1 1H8c-.6 0-1-.4-1-1V6c0-.6.4-1 1-1Z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
											<path d="M14 5v3.2c0 .4.4.8.8.8H18" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
										</svg>
									</span>
									<span class="docs-sidebar-text"><?php echo esc_html( get_the_title( $child_post ) ); ?></span>
								</a>
							</li>
							<?php
						}
					}

					if ( ! empty( $child_terms ) && ! is_wp_error( $child_terms ) ) {
						foreach ( $child_terms as $child_term ) {
							mbf_render_docs_sidebar_term( $child_term, $active_term_ids, $current_post_id, $level + 1 );
						}
					}
					?>
				</ul>
			<?php endif; ?>
		</li>
		<?php
	}
	if ( ! function_exists( 'mbf_render_docs_sidebar_children' ) ) {
		/**
		 * Render only the child terms and docs for a given term.
		 *
		 * @param WP_Term $term            The parent term.
		 * @param array   $active_term_ids The active term and ancestor IDs.
		 * @param int     $current_post_id Current docs ID.
		 * @param int     $level           Nesting depth.
		 */
		function mbf_render_docs_sidebar_children( $term, $active_term_ids, $current_post_id, $level = 0 ) {
			$child_terms = get_terms(
				array(
					'taxonomy'   => 'docs_category',
					'hide_empty' => false,
					'parent'     => $term->term_id,
					'orderby'    => 'name',
					'order'      => 'ASC',
				)
			);

			$child_posts = get_posts(
				array(
					'post_type'      => 'docs',
					'posts_per_page' => 50,
					'orderby'        => array(
						'menu_order' => 'ASC',
						'title'      => 'ASC',
					),
					'tax_query'      => array(
						array(
							'taxonomy'         => 'docs_category',
							'terms'            => array( $term->term_id ),
							'include_children' => false,
						),
					),
					'fields'         => '',
				)
			);

			if ( ! empty( $child_posts ) ) {
				foreach ( $child_posts as $child_post ) {
					$is_current = (int) $child_post->ID === (int) $current_post_id;
					?>
					<li class="docs-sidebar-item docs-sidebar-doc level-<?php echo (int) $level; ?> <?php echo $is_current ? 'is-active' : ''; ?>">
						<a class="docs-sidebar-link" href="<?php echo esc_url( get_permalink( $child_post ) ); ?>">
							<span class="docs-sidebar-icon" aria-hidden="true">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none">
									<path d="M8 5h5.6c.4 0 .8.2 1 .4l2 2c.2.2.4.6.4 1V19c0 .6-.4 1-1 1H8c-.6 0-1-.4-1-1V6c0-.6.4-1 1-1Z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
									<path d="M14 5v3.2c0 .4.4.8.8.8H18" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</span>
							<span class="docs-sidebar-text"><?php echo esc_html( get_the_title( $child_post ) ); ?></span>
						</a>
					</li>
					<?php
				}
			}

			if ( ! empty( $child_terms ) && ! is_wp_error( $child_terms ) ) {
				foreach ( $child_terms as $child_term ) {
					mbf_render_docs_sidebar_term( $child_term, $active_term_ids, $current_post_id, $level );
				}
			}
		}
	}
}

while ( have_posts() ) :
	the_post();

	$doc_terms = get_the_terms( get_the_ID(), 'docs_category' );
	$primary_term = null;
	$root_term    = null;

	if ( ! empty( $doc_terms ) && ! is_wp_error( $doc_terms ) ) {
		usort(
			$doc_terms,
			function ( $a, $b ) {
				if ( $a->parent === $b->parent ) {
					return strcasecmp( $a->name, $b->name );
				}
				return $a->parent - $b->parent;
			}
		);

		$primary_term = $doc_terms[0];

		if ( $primary_term->parent ) {
			$ancestors = get_ancestors( $primary_term->term_id, 'docs_category' );
			$root_id   = $ancestors ? end( $ancestors ) : $primary_term->parent;
			$root_term = get_term( $root_id, 'docs_category' );
		} else {
			$root_term = $primary_term;
		}
	}

	if ( ! $root_term && ! empty( $docs_categories ) && ! is_wp_error( $docs_categories ) ) {
		$root_term = $docs_categories[0];
	}

	$active_term_ids = array();

	if ( ! empty( $doc_terms ) && ! is_wp_error( $doc_terms ) ) {
		foreach ( $doc_terms as $doc_term ) {
			$active_term_ids[] = (int) $doc_term->term_id;
			$active_term_ids   = array_merge( $active_term_ids, get_ancestors( $doc_term->term_id, 'docs_category' ) );
		}
	}

	$content        = apply_filters( 'the_content', get_the_content() );
	$toc_items      = array();
	$processed_html = $content;

	if ( ! empty( $content ) ) {
		$dom = new DOMDocument();
		libxml_use_internal_errors( true );
		$dom->loadHTML( '<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

		foreach ( $dom->getElementsByTagName( '*' ) as $node ) {
			if ( ! in_array( $node->nodeName, array( 'h2', 'h3' ), true ) ) {
				continue;
			}

			$text      = trim( $node->textContent );
			$generated = sanitize_title( $text );
			$current   = $node->getAttribute( 'id' );
			$heading_id = $current ? $current : $generated;

			if ( $heading_id ) {
				$node->setAttribute( 'id', $heading_id );
				$toc_items[] = array(
					'id'    => $heading_id,
					'label' => $text,
					'tag'   => $node->nodeName,
				);
			}
		}

		$processed_html = $dom->saveHTML();
		libxml_clear_errors();
	}

	$top_level_terms = get_terms(
		array(
			'taxonomy'   => 'docs_category',
			'hide_empty' => false,
			'parent'     => 0,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);

	$top_level_terms = array_filter(
		$top_level_terms,
		function ( $term ) {
			return ! is_wp_error( $term );
		}
	);

	if ( $root_term ) {
		usort(
			$top_level_terms,
			function ( $a, $b ) use ( $root_term ) {
				if ( $a->term_id === $root_term->term_id ) {
					return -1;
				}
				if ( $b->term_id === $root_term->term_id ) {
					return 1;
				}
				return strcasecmp( $a->name, $b->name );
			}
		);
	}

	$prev_doc = get_adjacent_post( true, '', true, 'docs_category' );
	$next_doc = get_adjacent_post( true, '', false, 'docs_category' );

	?>
	<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
	<style>
		:root {
			--docs-text: #1f1f1f;
			--docs-muted: #525252;
			--docs-border: #e6e6e6;
			--docs-surface: #f9f9f9;
			--docs-accent: #0f0f0f;
		}

		*,
		*::before,
		*::after {
			box-sizing: border-box;
		}

		body.docs-single-page {
			margin: 0;
			font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
			color: var(--docs-text);
			background: #ffffff;
			-webkit-font-smoothing: antialiased;
			line-height: 1.6;
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

		.docs-brand-nav .mbf-header__logo img {
			max-height: 32px;
			width: auto;
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

		.docs-single-shell {
			max-width: 1320px;
			margin: 0 auto;
			padding: 38px 36px 72px;
			display: grid;
			grid-template-columns: 260px 1fr 220px;
			gap: 40px;
			width: 100%;
		}

		.docs-sidebar-panel {
			position: relative;
		}

		.docs-sidebar-toggle {
			display: none;
			width: 100%;
			align-items: center;
			justify-content: space-between;
			gap: 10px;
			background: #f1f1f1;
			border: 1px solid #dedede;
			border-radius: 12px;
			padding: 12px 14px;
			font-weight: 600;
			font-size: 14px;
			cursor: pointer;
		}

		.docs-sidebar {
			position: sticky;
			top: 84px;
			max-height: calc(100vh - 100px);
			overflow: auto;
			padding-right: 10px;
			scrollbar-width: thin;
			scrollbar-color: #cfcfcf transparent;
		}

		.docs-sidebar::-webkit-scrollbar {
			width: 6px;
		}

		.docs-sidebar::-webkit-scrollbar-track {
			background: transparent;
		}

		.docs-sidebar::-webkit-scrollbar-thumb {
			background: #cfcfcf;
			border-radius: 999px;
		}

		.docs-sidebar-group + .docs-sidebar-group {
			margin-top: 30px;
		}

		.docs-sidebar-heading {
			font-size: 15px;
			font-weight: 700;
			margin-bottom: 14px;
			color: #1e1e1e;
			letter-spacing: -0.01em;
			line-height: 1.2;
		}

		.docs-sidebar-list,
		.docs-sidebar-sublist {
			list-style: none;
			margin: 0;
			padding: 0;
		}

		.docs-sidebar-item {
			margin: 1px 0;
		}

		.docs-sidebar-link {
			display: grid;
			grid-template-columns: auto 1fr auto;
			align-items: center;
			gap: 10px;
			text-decoration: none;
			color: inherit;
			font-size: 14px;
			font-weight: 600;
			padding: 10px 12px;
			border-radius: 12px;
			transition: background 0.15s ease, color 0.15s ease, box-shadow 0.15s ease;
		}

		.docs-sidebar-link:hover {
			background: #f1f1f1;
		}

		.docs-sidebar-doc.is-active .docs-sidebar-link,
		.docs-sidebar-term.is-active-branch > .docs-sidebar-link {
			background: #ebebeb;
			box-shadow: inset 0 0 0 1px #dedede;
			color: #0f0f0f;
			font-weight: 700;
		}

		.docs-sidebar-icon {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			color: #2a2a2a;
			opacity: 0.85;
		}

		.docs-sidebar-text {
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
			color: #161616;
		}

		.docs-sidebar-chevron svg {
			color: #8a8a8a;
		}

		.docs-sidebar-sublist {
			margin-left: 8px;
			padding-left: 10px;
			border-left: 1px solid #ededed;
			margin-top: 6px;
		}

		.docs-sidebar-sublist.is-expanded {
			display: block;
		}

		.docs-article {
			max-width: 840px;
			margin: 0 auto;
		}

		.docs-article-header {
			margin-bottom: 12px;
		}

		.docs-article-title {
			font-size: 32px;
			margin: 0 0 8px;
			font-weight: 700;
			letter-spacing: -0.01em;
			color: #121212;
		}

		.docs-article-meta {
			font-size: 13px;
			color: #6a6a6a;
			display: flex;
			gap: 8px;
			align-items: center;
			flex-wrap: wrap;
		}

		.docs-article-body {
			font-size: 16px;
			line-height: 1.68;
			color: #1f1f1f;
		}

		.docs-article-body h2 {
			margin: 26px 0 12px;
			font-size: 19px;
			font-weight: 700;
			letter-spacing: -0.01em;
			color: #131313;
		}

		.docs-article-body h3 {
			margin: 18px 0 8px;
			font-size: 16px;
			font-weight: 700;
			color: #151515;
			letter-spacing: -0.005em;
		}

		.docs-article-body p {
			margin: 0 0 14px;
		}

		.docs-article-body ul {
			padding-left: 22px;
			margin: 6px 0 18px;
		}

		.docs-article-body li + li {
			margin-top: 10px;
		}

		.docs-article-body strong {
			color: #0f0f0f;
		}

		.docs-article-body a {
			color: #1c1c1c;
			text-decoration: none;
			box-shadow: inset 0 -1px 0 #cfcfcf;
			transition: color 0.15s ease, box-shadow 0.15s ease;
		}

		.docs-article-body a:hover {
			color: #0c0c0c;
			box-shadow: inset 0 -2px 0 #bcbcbc;
		}

		.docs-article-body figure {
			margin: 18px 0;
		}

		.docs-article-body img,
		.docs-article-body picture,
		.docs-article-body video,
		.docs-article-body iframe {
			display: block;
			width: 100%;
			max-width: 100%;
			height: auto;
			border-radius: 12px;
			border: 1px solid #e4e4e4;
			background: #fbfbfb;
			box-shadow: 0 10px 24px rgba(0, 0, 0, 0.04);
			overflow: hidden;
			margin: 18px 0;
		}

		.docs-article-body iframe {
			min-height: 320px;
		}

		.docs-article-divider {
			margin: 32px 0 22px;
			height: 1px;
			background: #ededed;
		}

		.docs-article-nav {
			display: flex;
			justify-content: space-between;
			gap: 12px;
			margin-top: 8px;
			flex-wrap: wrap;
		}

		.docs-article-nav a {
			flex: 1 1 220px;
			display: inline-flex;
			align-items: center;
			justify-content: space-between;
			gap: 8px;
			text-decoration: none;
			color: #111;
			padding: 12px 14px;
			border: 1px solid #e4e4e4;
			border-radius: 12px;
			background: #fbfbfb;
			box-shadow: 0 8px 22px rgba(0, 0, 0, 0.03);
			font-weight: 700;
			letter-spacing: -0.01em;
		}

		.docs-article-nav a span {
			font-weight: 500;
			color: #4b4b4b;
		}

		.docs-article-nav strong {
			display: block;
			font-size: 15px;
			font-weight: 700;
			color: #0f0f0f;
			letter-spacing: -0.01em;
		}

		.docs-article-nav a:hover {
			border-color: #d8d8d8;
			background: #f6f6f6;
		}

		.docs-toc {
			position: sticky;
			top: 98px;
			max-height: calc(100vh - 120px);
			overflow: auto;
			font-size: 14px;
			color: #5a5a5a;
			padding-left: 6px;
		}

		.docs-toc h3 {
			margin: 0 0 12px;
			font-size: 14px;
			font-weight: 700;
			color: #222;
			display: flex;
			align-items: center;
			gap: 8px;
			letter-spacing: -0.01em;
		}

		.docs-toc h3 svg {
			color: #4a4a4a;
		}

		.docs-toc-list {
			list-style: none;
			margin: 0;
			padding: 0;
		}

		.docs-toc-list li {
			margin: 8px 0;
		}

		.docs-toc-list a {
			color: inherit;
			text-decoration: none;
			display: block;
			padding: 6px 2px;
			line-height: 1.4;
		}

		.docs-toc-list a:hover {
			color: #111;
		}

		.docs-toc-list .toc-item-strong {
			font-weight: 700;
			color: #1d1d1d;
		}

		@media (max-width: 1140px) {
			.docs-header-inner {
				grid-template-columns: 1fr;
				padding: 16px 18px 12px;
				gap: 12px;
			}

			.docs-brand-nav {
				justify-content: space-between;
			}

			.docs-nav {
				margin-left: 0;
			}

			.docs-single-shell {
				grid-template-columns: 240px 1fr;
				padding: 28px 22px 48px;
				gap: 26px;
			}

			.docs-toc {
				display: none;
			}
		}

		@media (max-width: 900px) {
			.docs-single-shell {
				grid-template-columns: 1fr;
				padding: 22px 18px 42px;
			}

			.docs-sidebar-toggle {
				display: inline-flex;
			}

			.docs-sidebar {
				position: relative;
				top: auto;
				max-height: none;
				overflow: hidden;
				border: 1px solid #e8e8e8;
				border-radius: 14px;
				padding: 10px 10px 14px;
				background: #fcfcfc;
				display: none;
				box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
			}

			.docs-sidebar-panel.is-open .docs-sidebar {
				display: block;
				margin-top: 10px;
			}
		}
	</style>
</head>
<body <?php body_class( 'docs-single-page' ); ?> <?php mbf_site_scheme(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>

<div class="docs-page">
	<header class="docs-header">
		<div class="docs-header-inner">
			<div class="docs-brand-nav">
				<?php mbf_component( 'header_logo' ); ?>
				<nav class="docs-nav" aria-label="<?php esc_attr_e( 'Documentation navigation', 'apparel' ); ?>">
					<a href="<?php echo esc_url( get_post_type_archive_link( 'docs' ) ); ?>" class="is-active"><?php esc_html_e( 'Docs Home', 'apparel' ); ?></a>
					<?php
					if ( ! empty( $docs_categories ) && ! is_wp_error( $docs_categories ) ) {
						foreach ( $docs_categories as $category ) {
							printf(
								'<a href="%1$s">%2$s</a>',
								esc_url( get_term_link( $category ) ),
								esc_html( $category->name )
							);
						}
					}
					?>
					<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Current Article', 'apparel' ); ?></a>
				</nav>
			</div>
			<div class="docs-search">
				<form role="search" aria-label="<?php esc_attr_e( 'Search documentation', 'apparel' ); ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<span class="docs-search-icon" aria-hidden="true">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
							<path d="m15.5 15.5 4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
							<circle cx="11" cy="11" r="5.5" stroke="currentColor" stroke-width="1.6" />
						</svg>
					</span>
					<input type="search" name="s" placeholder="<?php esc_attr_e( 'Search docs...', 'apparel' ); ?>" aria-label="<?php esc_attr_e( 'Search query', 'apparel' ); ?>" />
					<input type="hidden" name="post_type" value="docs" />
					<span class="docs-search-hint" aria-hidden="true">Ctrl K</span>
				</form>
			</div>
			<div class="docs-utility">
				<?php mbf_component( 'header_scheme_toggle' ); ?>
			</div>
		</div>
	</header>

	<?php mbf_site_search(); ?>

	<main class="docs-single-shell">
		<div class="docs-sidebar-panel" data-docs-sidebar>
			<button class="docs-sidebar-toggle" type="button" aria-expanded="false">
				<span><?php esc_html_e( 'Docs navigation', 'apparel' ); ?></span>
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
					<path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			</button>
			<aside class="docs-sidebar" aria-label="<?php esc_attr_e( 'Docs navigation', 'apparel' ); ?>">
				<?php
				if ( ! empty( $top_level_terms ) ) {
					foreach ( $top_level_terms as $top_term ) {
						?>
						<div class="docs-sidebar-group">
							<div class="docs-sidebar-heading"><?php echo esc_html( $top_term->name ); ?></div>
							<ul class="docs-sidebar-list">
								<?php mbf_render_docs_sidebar_children( $top_term, $active_term_ids, get_the_ID() ); ?>
							</ul>
						</div>
						<?php
					}
				}
				?>
			</aside>
		</div>

		<article class="docs-article">
			<header class="docs-article-header">
				<h1 class="docs-article-title"><?php the_title(); ?></h1>
				<div class="docs-article-meta">
					<?php
					if ( $primary_term ) {
						printf(
							'<span>%s</span>',
							esc_html( $primary_term->name )
						);
					}

					printf(
						'<time datetime="%1$s">%2$s</time>',
						esc_attr( get_the_date( DATE_W3C ) ),
						esc_html( get_the_date() )
					);
					?>
				</div>
			</header>

			<div class="docs-article-body">
				<?php echo $processed_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<div class="docs-article-divider" aria-hidden="true"></div>

			<?php if ( $prev_doc || $next_doc ) : ?>
				<nav class="docs-article-nav" aria-label="<?php esc_attr_e( 'Article navigation', 'apparel' ); ?>">
					<?php if ( $prev_doc ) : ?>
						<a class="docs-article-prev" href="<?php echo esc_url( get_permalink( $prev_doc ) ); ?>">
							<span><?php esc_html_e( 'Previous', 'apparel' ); ?></span>
							<strong><?php echo esc_html( get_the_title( $prev_doc ) ); ?></strong>
						</a>
					<?php endif; ?>

					<?php if ( $next_doc ) : ?>
						<a class="docs-article-next" href="<?php echo esc_url( get_permalink( $next_doc ) ); ?>">
							<span><?php esc_html_e( 'Next', 'apparel' ); ?></span>
							<strong><?php echo esc_html( get_the_title( $next_doc ) ); ?></strong>
						</a>
					<?php endif; ?>
				</nav>
			<?php endif; ?>
		</article>

		<aside class="docs-toc" aria-label="<?php esc_attr_e( 'On this page', 'apparel' ); ?>">
			<h3>
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
					<path d="M6 7h12M6 12h12M6 17h7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
				</svg>
				<?php esc_html_e( 'On this page', 'apparel' ); ?>
			</h3>
			<?php if ( ! empty( $toc_items ) ) : ?>
				<ol class="docs-toc-list">
					<?php foreach ( $toc_items as $index => $toc_item ) : ?>
						<li>
							<a href="#<?php echo esc_attr( $toc_item['id'] ); ?>" class="<?php echo 0 === $index ? 'toc-item-strong' : ''; ?>">
								<?php echo esc_html( $toc_item['label'] ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ol>
			<?php else : ?>
				<p class="docs-toc-empty"><?php esc_html_e( 'Add headings to see an outline.', 'apparel' ); ?></p>
			<?php endif; ?>
		</aside>
	</main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
	const sidebarPanel = document.querySelector('[data-docs-sidebar]');
	const toggleButton = sidebarPanel ? sidebarPanel.querySelector('.docs-sidebar-toggle') : null;

	if (toggleButton && sidebarPanel) {
		toggleButton.addEventListener('click', function () {
			const isOpen = sidebarPanel.classList.toggle('is-open');
			toggleButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		});
	}
});
</script>

<?php wp_footer(); ?>
</body>
</html>
<?php
endwhile;
