<?php
/**
 * Template part singular content
 *
 * @package Apparel
 */

?>

<?php $is_blog_post = is_singular( 'post' ); ?>

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
		/**
		 * The mbf_entry_container_start hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_entry_container_start' );
		?>

		<?php if ( $is_blog_post ) : ?>
			<div class="mbf-entry__content-layout">
				<aside class="mbf-entry__toc" aria-label="<?php esc_attr_e( 'Table of contents', 'apparel' ); ?>">
					<button class="mbf-entry__toc-toggle" type="button" aria-expanded="true" aria-controls="mbf-entry-toc-list">
						<?php esc_html_e( 'Table of contents', 'apparel' ); ?>
						<span class="mbf-entry__toc-toggle-icon" aria-hidden="true">+</span>
					</button>
					<div class="mbf-entry__toc-inner">
						<div class="mbf-entry__toc-title"><?php esc_html_e( 'Inhalt', 'apparel' ); ?></div>
						<ol class="mbf-entry__toc-list" id="mbf-entry-toc-list"></ol>
					</div>
				</aside>

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

						<?php if ( has_post_thumbnail() ) : ?>
							<figure class="mbf-entry__article-media">
								<?php the_post_thumbnail( 'mbf-large-uncropped' ); ?>
							</figure>
						<?php endif; ?>
					</div>

					<div class="entry-content">
						<?php the_content(); ?>
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

				<aside class="mbf-entry__ad" aria-label="<?php esc_attr_e( 'Advertisement', 'apparel' ); ?>">
					<div class="mbf-entry__ad-inner">
						<img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=480&q=80" alt="<?php esc_attr_e( 'Advertisement', 'apparel' ); ?>" loading="lazy" />
					</div>
				</aside>
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
					<?php the_content(); ?>
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
