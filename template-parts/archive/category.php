<?php
/**
 * Template part for category archives.
 *
 * @package Apparel
 */

$category = get_queried_object();

if ( ! ( $category instanceof WP_Term ) ) {
	return;
}

$options = mbf_get_archive_options();

$main_classes  = ' mbf-posts-area__' . $options['location'];
$main_classes .= ' mbf-posts-area__' . $options['layout'];

$category_description = category_description();
$featured_post        = mbf_get_category_featured_post( $category->term_id );
?>

<?php
$promo_data        = mbf_get_category_promo_data( $category->term_id );
$promo_has_content = ! empty( $promo_data );
$promo_form_id     = 3;
$promo_image_html  = '';
$promo_description = '';

if ( $promo_has_content ) {
	$promo_description = isset( $promo_data['right_description'] ) ? wp_kses_post( wpautop( $promo_data['right_description'] ) ) : '';

	if ( ! empty( $promo_data['left_form_id'] ) ) {
		$promo_form_id = absint( $promo_data['left_form_id'] );
	}

	if ( ! empty( $promo_data['right_image_id'] ) ) {
		$promo_image_alt  = ! empty( $promo_data['right_title'] ) ? $promo_data['right_title'] : single_cat_title( '', false );
		$promo_image_html = wp_get_attachment_image(
			absint( $promo_data['right_image_id'] ),
			'mbf-thumbnail',
			false,
			array(
				'alt'     => $promo_image_alt,
				'loading' => 'lazy',
			)
		);
	}
}

$layout_class = $promo_has_content ? 'mbf-entry__content-layout mbf-entry__content-layout--promo' : 'mbf-entry__content-layout mbf-entry__content-layout--no-promo';
?>

<div class="mbf-entry__wrap mbf-entry__wrap--post mbf-category-archive">
	<?php
	/**
	 * The mbf_entry_wrap_start hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'mbf_entry_wrap_start' );
	?>

	<div class="mbf-entry__container mbf-entry__container--with-toc">
		<?php
		/**
		 * The mbf_entry_container_start hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_entry_container_start' );
		?>

		<div class="<?php echo esc_attr( $layout_class ); ?>">
			<?php if ( $promo_has_content ) : ?>
				<div class="mbf-entry__left-rail">
					<aside class="mbf-entry__lead-form" aria-label="<?php esc_attr_e( 'Start your online business form', 'apparel' ); ?>">
						<div class="mbf-entry__lead-form-card">
							<div class="mbf-entry__lead-form-heading">
								<?php if ( ! empty( $promo_data['left_headline'] ) ) : ?>
									<p><?php echo esc_html( $promo_data['left_headline'] ); ?></p>
								<?php endif; ?>
								<?php if ( ! empty( $promo_data['left_subtext'] ) ) : ?>
									<p><?php echo esc_html( $promo_data['left_subtext'] ); ?></p>
								<?php endif; ?>
							</div>
							<?php echo do_shortcode( sprintf( '[fluentform id="%d"]', $promo_form_id ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</aside>
				</div>
			<?php endif; ?>

			<div class="mbf-entry__content-wrap">
				<?php
				/**
				 * The mbf_entry_content_before hook.
				 *
				 * @since 1.0.0
				 */
				do_action( 'mbf_entry_content_before' );
				?>

				<div class="mbf-entry__article-header">
					<h1 class="mbf-entry__title entry-title"><?php echo esc_html( single_cat_title( '', false ) ); ?></h1>
				</div>

				<nav class="mbf-entry__toc is-collapsed" aria-label="<?php esc_attr_e( 'Table of contents', 'apparel' ); ?>" aria-expanded="false">
					<div class="mbf-entry__toc-header">
						<span class="mbf-entry__toc-title"><?php esc_html_e( 'Table of contents', 'apparel' ); ?></span>
						<span class="mbf-entry__toc-toggle" aria-hidden="true">+</span>
					</div>
					<div class="mbf-entry__toc-divider" aria-hidden="true"></div>
					<div class="mbf-entry__toc-inner" hidden>
						<ol class="mbf-entry__toc-list"></ol>
					</div>
				</nav>

				<div class="entry-content">
					<?php if ( $category_description ) : ?>
						<div class="mbf-category__description">
							<?php echo wp_kses_post( $category_description ); ?>
						</div>
					<?php endif; ?>

					<?php mbf_list_categories(); ?>

					<?php if ( $featured_post ) : ?>
						<section class="mbf-category__featured">
							<div class="mbf-category__featured-header">
								<h2 class="mbf-entry__subtitle"><?php esc_html_e( 'Start here', 'apparel' ); ?></h2>
							</div>
							<div class="mbf-category__featured-card">
								<?php
								set_query_var( 'options', $options );
								setup_postdata( $featured_post );
								get_template_part( 'template-parts/archive/entry' );
								wp_reset_postdata();
								?>
							</div>
						</section>
					<?php endif; ?>

					<?php if ( have_posts() ) : ?>
						<section class="mbf-category__posts">
							<div class="mbf-posts-area mbf-posts-area-posts mbf-posts-area__archive">
								<div class="mbf-posts-area__outer">

									<div class="mbf-posts-area__main mbf-archive-<?php echo esc_attr( $options['layout'] ); ?> <?php echo esc_attr( $main_classes ); ?>">
										<?php
										while ( have_posts() ) {
											the_post();

											set_query_var( 'options', $options );

											if ( 'full' === $options['layout'] ) {
												get_template_part( 'template-parts/archive/content-full' );
											} else {
												get_template_part( 'template-parts/archive/entry' );
											}
										}
										?>
									</div>
								</div>

								<?php if ( 'standard' === get_theme_mod( mbf_get_archive_option( 'pagination_type' ), 'load-more' ) ) : ?>
									<div class="mbf-posts-area__pagination">
										<?php
											the_posts_pagination(
												array(
													'prev_text' => '',
													'next_text' => '',
												)
											);
										?>
									</div>
								<?php endif; ?>
							</div>
						</section>
					<?php else : ?>
						<div class="mbf-content-not-found">
							<div class="mbf-content-not-found-content">
								<?php esc_html_e( 'There are no posts in this category yet.', 'apparel' ); ?>
							</div>

							<?php get_search_form(); ?>
						</div>
					<?php endif; ?>
				</div>

				<?php
				/**
				 * The mbf_entry_content_after hook.
				 *
				 * @since 1.0.0
				 */
				do_action( 'mbf_entry_content_after' );
				?>
			</div>

			<?php if ( $promo_has_content ) : ?>
				<div class="mbf-entry__sidebar">
					<aside class="mbf-entry__ad" aria-label="<?php esc_attr_e( 'Advertisement', 'apparel' ); ?>">
						<div class="mbf-entry__sidebar-cta">
							<?php if ( $promo_image_html ) : ?>
								<figure class="mbf-entry__sidebar-cta-media">
									<?php echo $promo_image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</figure>
							<?php endif; ?>
							<?php if ( ! empty( $promo_data['right_title'] ) ) : ?>
								<h3 class="mbf-entry__sidebar-cta-title"><?php echo esc_html( $promo_data['right_title'] ); ?></h3>
							<?php endif; ?>
							<?php if ( $promo_description ) : ?>
								<div class="mbf-entry__sidebar-cta-description"><?php echo $promo_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
							<?php endif; ?>
							<?php if ( ! empty( $promo_data['button_text'] ) && ! empty( $promo_data['button_url'] ) ) : ?>
								<a class="mbf-entry__sidebar-cta-button" href="<?php echo esc_url( $promo_data['button_url'] ); ?>">
									<?php echo esc_html( $promo_data['button_text'] ); ?>
								</a>
							<?php endif; ?>
						</div>
					</aside>
				</div>
			<?php endif; ?>
		</div>

		<?php
		/**
		 * The mbf_entry_container_end hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_entry_container_end' );
		?>
	</div>

	<?php
	/**
	 * The mbf_entry_wrap_end hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'mbf_entry_wrap_end' );
	?>
</div>
