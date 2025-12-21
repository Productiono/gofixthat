<?php
/**
 * The template part for displaying off-canvas area.
 *
 * @package Apparel
 */

if ( mbf_offcanvas_exists() ) {
	?>

	<div class="mbf-site-overlay"></div>

	<div class="mbf-offcanvas">
		<div class="mbf-offcanvas__header">
			<?php
			/**
			 * The mbf_offcanvas_header_start hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'mbf_offcanvas_header_start' );
			?>

			<nav class="mbf-offcanvas__nav">
				<?php if ( get_theme_mod( 'header_search_button', true ) ) { ?>
					<span class="mbf-offcanvas__search-toggle" role="button">
						<i class="mbf-icon mbf-icon-search"></i>
					</span>
				<?php } ?>

				<?php mbf_component( 'header_logo' ); ?>

				<span class="mbf-offcanvas__toggle" role="button"><i class="mbf-icon mbf-icon-x"></i></span>

				<div class="mbf-offcanvas__search">
					<form method="get" class="mbf-offcanvas__search-nav-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<div class="mbf-offcanvas__search-group">
							<input required class="mbf-offcanvas__search-input" type="search" value="<?php the_search_query(); ?>" name="s" placeholder="<?php echo esc_attr( esc_html__( 'What are You Looking for?', 'apparel' ) ); ?>">

							<button class="mbf-offcanvas__search-submit">
								<i class="mbf-icon mbf-icon-search"></i>
							</button>
						</div>
					</form>
				</div>
			</nav>

			<?php
			/**
			 * The mbf_offcanvas_header_end hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'mbf_offcanvas_header_end' );
			?>
		</div>
		<aside class="mbf-offcanvas__sidebar">
			<div class="mbf-offcanvas__inner mbf-offcanvas__area mbf-widget-area">
				<?php
				$locations = get_nav_menu_locations();

				// Get menu by location.
				if ( isset( $locations['primary'] ) || isset( $locations['mobile'] ) ) {

					if ( isset( $locations['primary'] ) ) {
						$location = $locations['primary'];
					}
					if ( isset( $locations['mobile'] ) ) {
						$location = $locations['mobile'];
					}

					the_widget( 'WP_Nav_Menu_Widget', array( 'nav_menu' => $location ), array(
						'before_widget' => '<div class="mbf-offcanvas__main-menu widget %s">',
						'after_widget'  => '</div>',
					) );
				}
				?>

				<?php mbf_component( 'off_canvas_my_account' ); ?>

				<?php dynamic_sidebar( 'sidebar-offcanvas' ); ?>

				<?php mbf_component( 'off_canvas_secondary_menu' ); ?>

				<div class="mbf-offcanvas__bottombar">
					<div class="mbf-offcanvas__bottombar-inner">
						<?php
							mbf_component( 'off_canvas_scheme_toggle' );
							mbf_component( 'off_canvas_additional_menu' );
						?>
					</div>
				</div>
			</div>
		</aside>
	</div>
	<?php
}
