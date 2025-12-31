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
					<div class="mbf-entry__toc-inner">
						<div class="mbf-entry__toc-title"><?php esc_html_e( 'Inhalt', 'apparel' ); ?></div>
						<ol class="mbf-entry__toc-list"></ol>
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
