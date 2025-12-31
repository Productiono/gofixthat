<?php
/**
 * Template part entry header
 *
 * @package Apparel
 */

$entry_header_class = 'mbf-entry__header';
$entry_header_attr  = __return_empty_string();

$header_type = mbf_get_page_header_type();

if ( has_post_thumbnail() && 'title' !== $header_type ) {
	$entry_header_class .= ' mbf-entry__header-overlay';
	$entry_header_attr  .= 'data-scheme="inverse"';
}

if ( 'title' === $header_type ) {
	?>

	<div class="<?php echo esc_attr( $entry_header_class ); ?> mbf-entry__header-simple">
		<div class="mbf-entry__header-inner">
			<?php the_title( '<h1 class="mbf-entry__title entry-title">', '</h1>' ); ?>
		</div>
	</div>

<?php } else { ?>

	<div class="<?php echo esc_attr( $entry_header_class ); ?> mbf-entry__header-standard" <?php echo wp_kses( $entry_header_attr, 'post' ); ?>>
		<?php if ( has_post_thumbnail() ) { ?>
			<div class="mbf-entry__media">
				<div class="mbf-entry__media-inner">
					<?php the_post_thumbnail( 'mbf-large-uncropped' ); ?>
				</div>
			</div>
		<?php } ?>

		<div class="mbf-entry__header-inner">
			<?php
			// Title.
			the_title( '<h1 class="mbf-entry__title entry-title">', '</h1>' );

			// Post Meta.
			if ( is_singular( 'post' ) ) {

				global $post;

				setup_postdata( $post );

				mbf_get_post_meta( array( 'category', 'date', 'author' ), true, 'post_meta' );

				wp_reset_postdata();
			}

			// Subtitle.
			mbf_post_subtitle();

			// List Categories.
			if ( is_singular( 'page' ) ) {
				mbf_list_categories();
			}
			?>
		</div>
	</div>

	<?php
}
