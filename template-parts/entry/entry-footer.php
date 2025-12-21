<?php
/**
 * The template part for displaying post footer section.
 *
 * @package Apparel
 */

?>

<div class="mbf-entry__footer">
	<div class="mbf-entry__footer_item">
		<div class="mbf-entry__footer-title"><?php esc_html_e( 'Published:', 'apparel' ); ?></div>
		<time class="mbf-entry__footer-value"><?php echo esc_html( get_the_date( 'F d, Y' ) ); ?></time>
	</div>
	<div class="mbf-entry__footer_item">
		<div class="mbf-entry__footer-title"><?php esc_html_e( 'Updated:', 'apparel' ); ?></div>
		<time class="mbf-entry__footer-value"><?php echo esc_html( get_the_modified_date( 'F d, Y' ) ); ?></time>
	</div>
	<div class="mbf-entry__footer_item mbf-entry__footer_item-author">
		<?php mbf_get_post_meta( array( 'author' ), true, array( 'author' ) ); ?>
	</div>
</div>
