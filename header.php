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
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '3748572805275182');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=3748572805275182&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
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
