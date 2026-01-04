<?php
/**
 * Template part singular content
 *
 * @package Apparel
 */

?>

<?php
$is_blog_post       = is_singular( 'post' );
$primary_media_html = '';
$entry_content_html = '';
$video_caption_html = '';
$promo_data         = array();
$promo_has_content  = false;
$promo_image_html   = '';
$promo_description  = '';
$promo_form_id      = 3;

if ( $is_blog_post ) {
	$promo_data        = mbf_get_active_category_promo_data();
	$promo_has_content = ! empty( $promo_data );
	$promo_description = isset( $promo_data['right_description'] ) ? wp_kses_post( wpautop( $promo_data['right_description'] ) ) : '';

	if ( ! empty( $promo_data['left_form_id'] ) ) {
		$promo_form_id = absint( $promo_data['left_form_id'] );
	}

	if ( ! empty( $promo_data['right_image_id'] ) ) {
		$promo_image_alt  = ! empty( $promo_data['right_title'] ) ? $promo_data['right_title'] : get_the_title();
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

	$raw_content       = get_the_content();
	$formatted_content = apply_filters( 'the_content', $raw_content );
	$formatted_content = str_replace( ']]>', ']]&gt;', $formatted_content );

	$video_block_match   = '';
	$video_block_pattern = '';
	$video_iframe_html   = '';
	$inline_image_html   = has_post_thumbnail() ? get_the_post_thumbnail( null, 'mbf-large-uncropped', array( 'loading' => 'lazy', 'class' => 'mbf-inline-featured-image' ) ) : '';

	$figure_pattern = '#<figure[^>]*>(?:(?!</figure>).)*<iframe[^>]+youtube(?:-nocookie)?\\.com[^>]*>.*?</iframe>.*?</figure>#is';
	$iframe_pattern = '#<iframe[^>]+youtube(?:-nocookie)?\\.com[^>]*>.*?</iframe>#is';

	if ( preg_match( $figure_pattern, $formatted_content, $matches ) ) {
		$video_block_pattern = $figure_pattern;
		$video_block_match   = $matches[0];
		if ( preg_match( '#<figcaption[^>]*>.*?</figcaption>#is', $video_block_match, $caption_matches ) ) {
			$video_caption_html = $caption_matches[0];
		}
	} elseif ( preg_match( $iframe_pattern, $formatted_content, $matches ) ) {
		$video_block_pattern = $iframe_pattern;
		$video_block_match   = $matches[0];
	}

	if ( $video_block_match && preg_match( $iframe_pattern, $video_block_match, $iframe_matches ) ) {
		$video_iframe_html = $iframe_matches[0];
	}

	if ( $video_iframe_html ) {
		$primary_media_html = '<figure class="mbf-entry__article-media mbf-entry__article-media--video"><div class="mbf-video-embed">' . $video_iframe_html . '</div>' . $video_caption_html . '</figure>';
	} elseif ( has_post_thumbnail() ) {
		$primary_media_html = '<figure class="mbf-entry__article-media">' . get_the_post_thumbnail( null, 'mbf-large-uncropped' ) . '</figure>';
	}

	$entry_content_html = $formatted_content;

	if ( $video_block_pattern && $video_block_match ) {
		if ( $inline_image_html ) {
			$replacement_html   = '<figure class="wp-block-image mbf-entry__inline-featured">' . $inline_image_html . $video_caption_html . '</figure>';
			$entry_content_html = preg_replace( $video_block_pattern, $replacement_html, $formatted_content, 1 );
		} else {
			$entry_content_html = preg_replace( $video_block_pattern, '', $formatted_content, 1 );
		}
	}
} else {
	$entry_content_html = apply_filters( 'the_content', get_the_content() );
	$entry_content_html = str_replace( ']]>', ']]&gt;', $entry_content_html );
}
?>

<div class="mbf-entry__wrap <?php echo $is_blog_post ? 'mbf-entry__wrap--post' : ''; ?>">

	<?php
	/**
	 * The mbf_entry_wrap_start hook.
	 *
	 * @since 1.0.0
	 */
		do_action( 'mbf_entry_wrap_start' );
	?>

	<div class="mbf-entry__container <?php echo $is_blog_post ? 'mbf-entry__container--with-toc' : ''; ?>">

	<?php
	if ( $is_blog_post ) {
		$sidebar_cta_url   = apply_filters( 'mbf_single_post_sidebar_cta_url', home_url( '/' ) );
		$sidebar_cta_image = apply_filters( 'mbf_single_post_sidebar_cta_image', 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=640&q=80' );
		$sidebar_cta_title = apply_filters( 'mbf_single_post_sidebar_cta_title', __( 'The point of sale for all your sales.', 'apparel' ) );
		$sidebar_cta_label = apply_filters( 'mbf_single_post_sidebar_cta_label', __( 'Start for free', 'apparel' ) );
	}
	?>

		<?php
		/**
		 * The mbf_entry_container_start hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_entry_container_start' );
		?>

		<?php if ( $is_blog_post ) : ?>
			<?php $layout_class = $promo_has_content ? 'mbf-entry__content-layout mbf-entry__content-layout--promo' : 'mbf-entry__content-layout mbf-entry__content-layout--no-promo'; ?>
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
						<?php the_title( '<h1 class="mbf-entry__title entry-title">', '</h1>' ); ?>
						<?php
						$article_meta = mbf_get_post_meta(
							array( 'category', 'date', 'author' ),
							false,
							array( 'category', 'date', 'author' ),
							array( 'category_type' => 'line' )
						);

						if ( $article_meta ) :
							?>
							<div class="mbf-entry__article-meta">
								<?php echo wp_kses_post( $article_meta ); ?>
							</div>
						<?php endif; ?>

						<?php if ( $is_blog_post && $primary_media_html ) : ?>
							<?php echo $primary_media_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php elseif ( has_post_thumbnail() ) : ?>
							<figure class="mbf-entry__article-media">
								<?php the_post_thumbnail( 'mbf-large-uncropped' ); ?>
							</figure>
						<?php endif; ?>
					</div>

					<?php if ( $is_blog_post ) : ?>
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
					<?php endif; ?>

					<div class="entry-content">
						<?php echo $entry_content_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
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
		<?php else : ?>
			<div class="mbf-entry__content-wrap">
				<?php
				/**
				 * The mbf_entry_content_before hook.
				 *
				 * @since 1.0.0
				 */
				do_action( 'mbf_entry_content_before' );
				?>

				<div class="entry-content">
					<?php echo $entry_content_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
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
		<?php endif; ?>

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
