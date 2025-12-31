<?php
/**
 * The template part for displaying post prev next section.
 *
 * @package Apparel
 */

$options = array(
	'image_orientation' => get_theme_mod( 'post_prev_next_image_orientation', 'square' ),
	'image_size'        => get_theme_mod( 'post_prev_next_image_size', 'mbf-small' ),
);

$prev_post = get_previous_post();
$next_post = get_next_post();

if ( $prev_post || $next_post ) {
	?>
	<div class="mbf-entry__prev-next">
		<div class="mbf-entry__prev-next-inner">
			<?php
			// Prev post.
			if ( $prev_post ) {
				$query = new WP_Query( array(
					'posts_per_page' => 1,
					'p'              => $prev_post->ID,
				) );

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						?>
						<div class="mbf-entry__prev-next-item mbf-entry__prev">
							<a class="mbf-entry__prev-next-link" href="<?php the_permalink(); ?>"></a>

							<div class="mbf-entry__prev-next-label">
								<span class="mbf-entry__prev-next-text">
									<?php esc_html_e( 'Previous Post', 'apparel' ); ?>
								</span>
							</div>

							<div class="mbf-entry">
								<div class="mbf-entry__outer">
									<?php if ( has_post_thumbnail() ) { ?>
										<div class="mbf-entry__thumbnail mbf-entry__inner mbf-overlay-ratio mbf-ratio-<?php echo esc_attr( $options['image_orientation'] ); ?>">
											<div class="mbf-overlay-background mbf-overlay-transparent">
												<?php the_post_thumbnail( $options['image_size'] ); ?>
											</div>
											<a href="<?php the_permalink(); ?>" class="mbf-overlay-link"></a>
										</div>
									<?php } ?>

									<div class="mbf-entry__inner mbf-entry__content">
										<h2 class="mbf-entry__title">
											<span><?php echo esc_html( $prev_post->post_title ); ?></span>
										</h2>

										<?php
											mbf_get_post_meta( array( 'date', 'author' ), true, 'post_prev_next_meta' );
										?>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
				}
				wp_reset_postdata();
			}

			// Next post.
			if ( $next_post ) {
				$query = new WP_Query( array(
					'posts_per_page' => 1,
					'p'              => $next_post->ID,
				) );

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						?>
						<div class="mbf-entry__prev-next-item mbf-entry__next">
							<a class="mbf-entry__prev-next-link" href="<?php the_permalink(); ?>"></a>

							<div class="mbf-entry__prev-next-label">
								<span class="mbf-entry__prev-next-text">
									<?php esc_html_e( 'Next Post', 'apparel' ); ?>
								</span>
							</div>

							<div class="mbf-entry">
								<div class="mbf-entry__outer">
									<?php if ( has_post_thumbnail() ) { ?>
										<div class="mbf-entry__thumbnail mbf-entry__inner mbf-overlay-ratio mbf-ratio-<?php echo esc_attr( $options['image_orientation'] ); ?>">
											<div class="mbf-overlay-background mbf-overlay-transparent">
												<?php the_post_thumbnail( $options['image_size'] ); ?>
											</div>
											<a href="<?php the_permalink(); ?>" class="mbf-overlay-link"></a>
										</div>
									<?php } ?>

									<div class="mbf-entry__inner mbf-entry__content ">
										<h2 class="mbf-entry__title">
											<span><?php echo esc_html( $next_post->post_title ); ?></span>
										</h2>

										<?php
											mbf_get_post_meta( array( 'date', 'author' ), true, 'post_prev_next_meta' );
										?>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
				}
				wp_reset_postdata();
			}
			?>
		</div>
	</div>
	<?php
}
