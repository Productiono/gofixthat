<?php
/**
 * Archive template for Doc Articles.
 *
 * @package Apparel
 */

get_header();

$sidebar_items = mbf_get_doc_sidebar_items();
$archive_title = post_type_archive_title( '', false );
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

		<header class="docs-main__header">
			<h1 class="docs-main__title"><?php echo esc_html( $archive_title ? $archive_title : __( 'Documentation', 'apparel' ) ); ?></h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="docs-list">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'docs-list__item' ); ?>>
						<h2 class="docs-list__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<div class="docs-list__meta">
							<?php
							$category_links    = get_the_term_list( get_the_ID(), 'doc_category', '', ', ' );
							$subcategory_links = get_the_term_list( get_the_ID(), 'doc_subcategory', '', ', ' );

							if ( $category_links ) {
								echo '<span class="docs-list__term">' . wp_kses_post( $category_links ) . '</span>';
							}

							if ( $subcategory_links ) {
								echo '<span class="docs-list__term docs-list__term--sub">' . wp_kses_post( $subcategory_links ) . '</span>';
							}
							?>
						</div>
						<div class="docs-list__excerpt">
							<?php the_excerpt(); ?>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<div class="docs-list__empty" role="status" aria-live="polite">
				<p><?php esc_html_e( 'No documentation available yet.', 'apparel' ); ?></p>
				<div class="docs-list__empty-actions">
					<?php echo mbf_get_doc_search_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<a class="docs-list__empty-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php esc_html_e( 'Return to the homepage', 'apparel' ); ?>
					</a>
				</div>
			</div>
		<?php endif; ?>
	</main>
</div>

<?php
get_footer();
