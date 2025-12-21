<?php
/**
 * Template part for displaying full posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Apparel
 */

// Thumbnail size.
$thumbnail_size = 'mbf-medium';

if ( 'disabled' === mbf_get_page_sidebar() ) {
	$thumbnail_size = 'mbf-large';
}

if ( 'uncropped' === mbf_get_page_preview() ) {
	$thumbnail_size = sprintf( '%s-uncropped', $thumbnail_size );
}
?>

<article <?php post_class(); ?>>
	<div class="mbf-entry__header mbf-entry__header-standard">
		<div class="mbf-entry__header-inner">
			<div class="mbf-entry__header-info">
				<?php
				the_title( '<h2 class="mbf-entry__title"><a href="' . esc_url( get_permalink() ) . '"><span>', '</span></a></h2>' );

				mbf_get_post_meta( array( 'author', 'date', 'comments' ), true, $options['meta'] );
				?>
			</div>

			<?php if ( has_post_thumbnail() ) { ?>
				<figure class="mbf-entry__post-media post-media">
					<a href="<?php echo esc_url( get_permalink() ); ?>">
						<?php the_post_thumbnail( $thumbnail_size ); ?>
					</a>
				</figure>
			<?php } ?>
		</div>
	</div>

	<!-- ENTRY WRAP HTML -->
	<div class="mbf-entry__wrap">
		<div class="mbf-entry__container">
			<div class="mbf-entry__content-wrap">
				<div class="mbf-entry-type-<?php echo esc_attr( $options['summary_type'] ); ?> ">
					<?php
					if ( 'summary' === $options['summary_type'] ) {
						the_excerpt();
					} else {
						$more_link_text = false;


						$more_link_text = sprintf(
							/* translators: %s: Name of current post */
							__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'apparel' ),
							get_the_title()
						);

						the_content( $more_link_text );
					}
					?>
				</div>
			</div>
		</div>
	</div>
</article>
