<?php
/**
 * Archive template for Doc Articles.
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
$archive_title = post_type_archive_title( '', false );
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
					<?php
					if ( function_exists( 'mbf_get_doc_search_form' ) ) {
						echo mbf_get_doc_search_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					} else {
						get_search_form();
					}
					?>
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
