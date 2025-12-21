<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Apparel
 */

/**
 * The mbf_sidebar hook.
 *
 * @since 1.0.0
 */
$sidebar = apply_filters( 'mbf_sidebar', 'sidebar-main' );

if ( 'disabled' !== mbf_get_page_sidebar() ) {
	?>
	<aside id="secondary" class="mbf-widget-area mbf-sidebar__area">
		<div class="mbf-sidebar__inner">

			<?php
			/**
			 * The mbf_sidebar_start hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'mbf_sidebar_start' );
			?>

			<?php dynamic_sidebar( $sidebar ); ?>

			<?php
			/**
			 * The mbf_sidebar_end hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'mbf_sidebar_end' );
			?>

		</div>
	</aside>
	<?php
}
