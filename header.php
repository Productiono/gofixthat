<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "mbf-site" div.
 *
 * @package Apparel
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php mbf_site_scheme(); ?>>

<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>

<?php
/**
 * The mbf_site_before hook.
 *
 * @since 1.0.0
 */
do_action( 'mbf_site_before' );
?>

<div id="page" class="mbf-site">

	<?php
	/**
	 * The mbf_site_start hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'mbf_site_start' );
	?>

	<div class="mbf-site-inner">

		<?php
		/**
		 * The mbf_header_before hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_header_before' );
		?>

		<?php get_template_part( 'template-parts/header' ); ?>

		<?php
		/**
		 * The mbf_header_after hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_header_after' );
		?>

		<main id="main" class="mbf-site-primary">

			<?php
			/**
			 * The mbf_site_content_before hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'mbf_site_content_before' );
			?>

			<div <?php mbf_site_content_class(); ?>>

				<?php
				/**
				 * The mbf_site_content_start hook.
				 *
				 * @since 1.0.0
				 */
				do_action( 'mbf_site_content_start' );
				?>

				<div class="mbf-container">

					<?php
					/**
					 * The mbf_main_content_before hook.
					 *
					 * @since 1.0.0
					 */
					do_action( 'mbf_main_content_before' );
					?>

					<div id="content" class="mbf-main-content">

						<?php
						/**
						 * The mbf_main_content_start hook.
						 *
						 * @since 1.0.0
						 */
						do_action( 'mbf_main_content_start' );
						?>
