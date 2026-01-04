<?php
/**
 * Category archive template.
 *
 * @package Apparel
 */

remove_action( 'mbf_main_before', 'mbf_page_header', 100 );

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

	<?php get_template_part( 'template-parts/archive/category' ); ?>

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
