<?php
/**
 * Template for Doc Article singles.
 *
 * @package Apparel
 */

get_header();

$docs_file = get_theme_file_path( '/inc/docs.php' );

if ( ! function_exists( 'mbf_get_doc_sidebar_items' ) && file_exists( $docs_file ) ) {
	require_once $docs_file;
}

if ( function_exists( 'mbf_enqueue_doc_assets' ) ) {
	mbf_enqueue_doc_assets();
}

$sidebar_items = function_exists( 'mbf_get_doc_sidebar_items' ) ? mbf_get_doc_sidebar_items() : array();
?>

<div class="docs-shell" data-docs-sidebar>
	<div class="docs-sidebar__backdrop" data-docs-sidebar-backdrop hidden></div>
	<aside id="docs-sidebar" class="docs-sidebar" data-docs-sidebar-drawer aria-hidden="true">
		<?php if ( function_exists( 'mbf_render_docs_sidebar' ) ) : ?>
			<?php mbf_render_docs_sidebar( array( 'items' => $sidebar_items ) ); ?>
		<?php elseif ( ! empty( $sidebar_items ) ) : ?>
			<nav class="docs-sidebar__nav" aria-label="<?php esc_attr_e( 'Documentation navigation', 'apparel' ); ?>">
				<ul class="docs-sidebar__list">
					<?php foreach ( $sidebar_items as $item ) : ?>
						<li class="docs-sidebar__item">
							<a class="docs-sidebar__link" href="<?php echo esc_url( isset( $item['url'] ) ? $item['url'] : '' ); ?>"><?php echo esc_html( isset( $item['title'] ) ? $item['title'] : '' ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php else : ?>
			<p class="docs-sidebar__empty"><?php esc_html_e( 'No documentation content found yet.', 'apparel' ); ?></p>
		<?php endif; ?>
	</aside>

	<main class="docs-main" id="primary" role="main">
		<div class="docs-main__top">
			<button type="button" class="docs-sidebar__toggle" data-docs-sidebar-toggle aria-expanded="false" aria-controls="docs-sidebar">
				<span class="docs-sidebar__toggle-icon" aria-hidden="true">☰</span>
				<span class="docs-sidebar__toggle-label"><?php esc_html_e( 'Docs navigation', 'apparel' ); ?></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Toggle documentation navigation', 'apparel' ); ?></span>
			</button>

			<div class="docs-main__breadcrumbs">
				<?php if ( function_exists( 'mbf_theme_breadcrumbs' ) ) : ?>
					<?php mbf_theme_breadcrumbs(); ?>
				<?php endif; ?>
			</div>

			<div class="docs-main__search">
				<?php
				if ( function_exists( 'mbf_get_doc_search_form' ) ) {
					echo mbf_get_doc_search_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					get_search_form();
				}
				?>
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
