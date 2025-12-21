<?php
/**
 * The template part for displaying page header.
 *
 * @package Apparel
 */

// Init clasfor header.
$class = null;

// If description exists.
if ( get_the_archive_description() ) {
	$class = 'mbf-page__header-has-description';
}
?>

<div class="mbf-page__header <?php echo esc_attr( $class ); ?>">

		<?php
		/**
		 * The mbf_page_header_before hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_page_header_before' );

		if ( is_author() ) {

			$author_id = get_queried_object_id();
			?>
			<div class="mbf-page__author">
				<div class="mbf-page__author-photo">
					<div class="mbf-page__author-thumbnail">
						<?php echo get_avatar( $author_id, 100 ); ?>
					</div>
				</div>
				<div class="mbf-page__author-info">
					<?php
						the_archive_title( '<h1 class="mbf-page__title">', '</h1>' );
						mbf_author_subtitle();
						mbf_archive_description();
						mbf_archive_posts_count();
					?>
				</div>
			</div>

			<?php
		} elseif ( is_home() || is_archive() ) {

			if ( is_home() ) {
				if ( 'page' === get_option( 'show_on_front' ) && get_option('page_for_posts') ) {
					?>
						<h1 class="mbf-page__title">
							<?php echo esc_html( get_the_title( get_queried_object_id() ) ); ?>
						</h1>
					<?php
				} else {
					?>
						<h1 class="mbf-page__title">
							<?php esc_html_e( 'Latest Posts', 'apparel' ); ?>
						</h1>
					<?php
				}
			} else {
				the_archive_title( '<h1 class="mbf-page__title">', '</h1>' );
			}

			mbf_archive_description();

		} elseif ( is_search() ) {
			?>
			<h1 class="mbf-page__title"><?php esc_html_e( 'Search Results', 'apparel' ); ?>: <span><?php echo get_search_query(); ?></span></h1>
			<?php
		} elseif ( is_404() ) {
			?>
			<h1 class="mbf-page__title"><?php esc_html_e( '404. Page Not Found', 'apparel' ); ?></h1>
			<?php
		}

		/**
		 * The mbf_page_header_after hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_page_header_after' );
		?>


</div>
