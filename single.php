<?php
/**
 * The template for displaying all single posts and attachments.
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
	while ( have_posts() ) :
		the_post();
		?>

		<?php
		/**
		 * The mbf_post_before hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_post_before' );
		?>

			<?php get_template_part( 'template-parts/content-singular' ); ?>

		<?php
		/**
		 * The mbf_post_after hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_post_after' );
		?>

	<?php endwhile; ?>

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
