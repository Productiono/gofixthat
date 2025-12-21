<?php
/**
 * The template part for displaying notification bar.
 *
 * @package Apparel
 */

$notification_bar_text = get_theme_mod( 'misc_notification_bar' );

if ( $notification_bar_text ) {
	?>
	<div class="mbf-notification-bar">
		<div class="mbf-container">
			<div class="mbf-notification-bar__inner">
				<?php echo do_shortcode( $notification_bar_text ); ?>
			</div>
		</div>
	</div>
<?php } ?>
