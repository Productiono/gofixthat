<?php
/**
 * Template for Doc Article singles.
 *
 * @package Apparel
 */

get_header();

$sidebar_items = mbf_get_doc_sidebar_items();
?>

<div class="docs-shell" data-docs-sidebar>
	<div class="docs-sidebar__backdrop" data-docs-sidebar-backdrop hidden></div>
	<aside id="docs-sidebar" class="docs-sidebar" data-docs-sidebar-drawer aria-hidden="true">
		<?php get_template_part( 'template-parts/docs/sidebar', null, array( 'items' => $sidebar_items ) ); ?>
	</aside>

	<main class="docs-main" id="primary" role="main">
		<div class="docs-main__top">
			<button type="button" class="docs-sidebar__toggle" data-docs-sidebar-toggle aria-expanded="false" aria-controls="docs-sidebar">
				<span class="docs-sidebar__toggle-icon" aria-hidden="true">☰</span>
				<span class="docs-sidebar__toggle-label"><?php esc_html_e( 'Docs navigation', 'apparel' ); ?></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Toggle documentation navigation', 'apparel' ); ?></span>
			</button>

			<div class="docs-main__breadcrumbs">
				<?php mbf_theme_breadcrumbs(); ?>
			</div>

			<div class="docs-main__search">
				<?php echo mbf_get_doc_search_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>

		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'docs-article' ); ?>>
				<header class="docs-article__header">
					<?php the_title( '<h1 class="docs-article__title">', '</h1>' ); ?>
					<div class="docs-article__meta">
						<?php
						$category_links    = get_the_term_list( get_the_ID(), 'doc_category', '', ', ' );
						$subcategory_links = get_the_term_list( get_the_ID(), 'doc_subcategory', '', ', ' );

						if ( $category_links ) {
							echo '<div class="docs-article__term">' . wp_kses_post( $category_links ) . '</div>';
						}

						if ( $subcategory_links ) {
							echo '<div class="docs-article__term docs-article__term--sub">' . wp_kses_post( $subcategory_links ) . '</div>';
						}
						?>
					</div>
				</header>

				<div class="docs-article__content">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'apparel' ),
							'after'  => '</div>',
						)
					);
					?>
				</div>
			</article>
		<?php endwhile; ?>
	</main>
</div>

<?php
get_footer();
