<?php
/**
 * Template for Docs category archives.
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

$docs_nav_links      = mbf_get_docs_nav_links( $docs_categories, get_queried_object_id() );
$docs_category_tree  = mbf_get_docs_category_tree( $docs_categories );
$docs_search_markup  = mbf_get_docs_search_markup();
$docs_utility_markup = mbf_get_docs_utility_markup();

add_filter(
	'body_class',
	function( $classes ) {
		$classes[] = 'docs-category-page';
		return $classes;
	}
);

add_action( 'wp_head', 'mbf_docs_render_header_styles' );
add_action(
	'wp_head',
	function() {
		?>
		<style>
			body.docs-category-page .mbf-header,
			body.docs-category-page .mbf-header-before {
				display: none;
			}

			body.docs-category-page .mbf-site-content {
				margin-top: 0;
			}

			body.docs-category-page .docs-category-shell {
				padding: 24px 18px 32px;
			}

			@media (max-width: 960px) {
				body.docs-category-page .docs-category-shell {
					padding-top: 18px;
				}
			}
		</style>
		<?php
	}
);

get_header(); ?>

<div class="docs-page docs-category-layout">
	<?php mbf_render_docs_header( array( 'nav_links' => $docs_nav_links, 'search_markup' => $docs_search_markup, 'utility_markup' => $docs_utility_markup, 'mobile_categories' => $docs_category_tree ) ); ?>
	<?php mbf_site_search(); ?>

	<div class="docs-category-shell">
		<div id="primary" class="mbf-content-area">

		<?php
		/**
		 * The mbf_main_before hook.
		 *
	 * @since 1.0.0
	 */
	do_action( 'mbf_main_before' );
	?>

	<?php
	if ( have_posts() ) {
		$options = mbf_get_archive_options();

		$main_classes  = ' mbf-posts-area__' . $options['location'];
		$main_classes .= ' mbf-posts-area__' . $options['layout'];
		?>
		<div class="mbf-posts-area mbf-posts-area-posts">
			<div class="mbf-posts-area__outer">
				<header class="mbf-posts-area-header">
					<div class="mbf-posts-area-header-label">
						<?php esc_html_e( 'Docs', 'apparel' ); ?>
					</div>
					<div class="mbf-posts-area-header-title">
						<h1 class="page-title"><?php single_term_title(); ?></h1>
						<?php if ( term_description() ) : ?>
							<div class="mbf-term-description">
								<?php echo wp_kses_post( wpautop( term_description() ) ); ?>
							</div>
						<?php endif; ?>
					</div>
				</header>

				<div class="mbf-posts-area__main mbf-archive-<?php echo esc_attr( $options['layout'] ); ?> <?php echo esc_attr( $main_classes ); ?>">
					<?php
					while ( have_posts() ) {
						the_post();

						set_query_var( 'options', $options );

						if ( 'full' === $options['layout'] ) {
							get_template_part( 'template-parts/archive/content-full' );
						} else {
							get_template_part( 'template-parts/archive/entry' );
						}
					}
					?>
				</div>
			</div>

			<?php
			if ( 'standard' === get_theme_mod( mbf_get_archive_option( 'pagination_type' ), 'load-more' ) ) {
				?>
				<div class="mbf-posts-area__pagination">
					<?php
					the_posts_pagination(
						array(
							'prev_text' => '',
							'next_text' => '',
						)
					);
					?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	} else {
		?>
		<div class="entry-content mbf-content-not-found">
			<div class="mbf-content-not-found-content">
				<?php esc_html_e( 'No docs are available in this category yet.', 'apparel' ); ?>
			</div>

			<?php get_search_form(); ?>
		</div>
		<?php
	}
	?>

	<?php
	/**
	 * The mbf_main_after hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'mbf_main_after' );
	?>
</div>

	<?php get_sidebar(); ?>
	</div>
</div>

<?php mbf_docs_render_header_script(); ?>
<?php get_footer(); ?>
