<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Apparel
 */

get_header(); ?>

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
		// Set options.
		$options = mbf_get_archive_options();

		// Location.
		$main_classes = ' mbf-posts-area__' . $options['location'];

		// Layout.
		$main_classes .= ' mbf-posts-area__' . $options['layout'];
		?>

		<?php
		if ( is_search() ) {

			global $wp_query;
			?>
			<div class="mbf-posts-area-header">
				<div class="mbf-posts-area-header-label">
					<?php esc_html_e( 'Articles', 'apparel' ); ?>
				</div>
			</div>
		<?php } ?>

		<div class="mbf-posts-area mbf-posts-area-posts">
			<div class="mbf-posts-area__outer">

				<div class="mbf-posts-area__main mbf-archive-<?php echo esc_attr( $options['layout'] ); ?> <?php echo esc_attr( $main_classes ); ?>">
					<?php
					// Start the Loop.
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
			/* Posts Pagination */
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
	} elseif ( ! get_query_var( 'mbf_have_search' ) ) {
		?>
		<div class="entry-content mbf-content-not-found">

			<?php if ( is_search() ) { ?>
				<div class="mbf-content-not-found-content">
					<?php esc_html_e( 'It seems we cannot find what you are looking for. Perhaps searching can help.', 'apparel' ); ?>
				</div>
			<?php } else { ?>
				<div class="mbf-content-not-found-content">
					<?php esc_html_e( 'The page you were looking for could not be found. It might have been removed, renamed, or did not exist in the first place. Perhaps searching can help.', 'apparel' ); ?>
				</div>
			<?php } ?>

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
<?php get_footer(); ?>
