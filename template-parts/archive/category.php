<?php
/**
 * Template part for category archives.
 *
 * @package Apparel
 */

$category = get_queried_object();

if ( ! ( $category instanceof WP_Term ) ) {
	return;
}

$options = mbf_get_archive_options();

$main_classes  = ' mbf-posts-area__' . $options['location'];
$main_classes .= ' mbf-posts-area__' . $options['layout'];

$category_description = category_description();
$featured_post        = mbf_get_category_featured_post( $category->term_id );
?>

<div class="mbf-entry__wrap mbf-entry__wrap--post mbf-category-archive">
	<div class="mbf-entry__container">
		<div class="mbf-entry__content-wrap">
			<div class="mbf-entry__article-header">
				<h1 class="mbf-entry__title entry-title"><?php echo esc_html( single_cat_title( '', false ) ); ?></h1>
			</div>

			<?php if ( $category_description ) : ?>
				<div class="entry-content mbf-category__description">
					<?php echo wp_kses_post( $category_description ); ?>
				</div>
			<?php endif; ?>

			<?php mbf_list_categories(); ?>

			<?php if ( $featured_post ) : ?>
				<section class="mbf-category__featured">
					<div class="mbf-category__featured-header">
						<h2 class="mbf-entry__subtitle"><?php esc_html_e( 'Start here', 'apparel' ); ?></h2>
					</div>
					<div class="mbf-category__featured-card">
						<?php
						set_query_var( 'options', $options );
						setup_postdata( $featured_post );
						get_template_part( 'template-parts/archive/entry' );
						wp_reset_postdata();
						?>
					</div>
				</section>
			<?php endif; ?>

			<?php if ( have_posts() ) : ?>
				<section class="mbf-category__posts">
					<div class="mbf-posts-area mbf-posts-area-posts mbf-posts-area__archive">
						<div class="mbf-posts-area__outer">

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

						<?php if ( 'standard' === get_theme_mod( mbf_get_archive_option( 'pagination_type' ), 'load-more' ) ) : ?>
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
						<?php endif; ?>
					</div>
				</section>
			<?php else : ?>
				<div class="entry-content mbf-content-not-found">
					<div class="mbf-content-not-found-content">
						<?php esc_html_e( 'There are no posts in this category yet.', 'apparel' ); ?>
					</div>

					<?php get_search_form(); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
