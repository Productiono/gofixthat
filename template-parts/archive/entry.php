<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Apparel
 */

$options = get_query_var( 'options' );

// Set post class.
$post_class = __return_empty_string();
?>

<article <?php post_class( $post_class ); ?>>
	<div class="mbf-entry__outer">
		<?php
		if ( has_post_thumbnail() ) {
			?>
			<div class="mbf-entry__inner mbf-entry__thumbnail mbf-entry__overlay mbf-overlay-ratio mbf-ratio-<?php echo esc_attr( $options['image_orientation'] ); ?>" data-scheme="inverse">

				<div class="mbf-overlay-background mbf-overlay-transparent">
					<?php the_post_thumbnail( $options['image_size'] ); ?>
				</div>

				<?php mbf_the_post_format_icon(); ?>

				<a href="<?php echo esc_url( get_permalink() ); ?>" class="mbf-overlay-link"></a>
			</div>
		<?php } ?>

		<div class="mbf-entry__inner mbf-entry__content">

			<?php the_title( '<h2 class="mbf-entry__title"><a href="' . esc_url( get_permalink() ) . '"><span>', '</span></a></h2>' ); ?>

			<?php
			$post_excerpt = get_the_excerpt();
			if ( isset( $options['excerpt'] ) && $options['excerpt'] && $post_excerpt ) {
				?>
				<div class="mbf-entry__excerpt">
					<?php echo wp_kses_post( $post_excerpt ); ?>
				</div>
				<?php
			}
			?>

			<?php mbf_get_post_meta( array( 'date', 'author', 'category', 'comments' ), true, $options['meta'] ); ?>
		</div>
	</div>
</article>
