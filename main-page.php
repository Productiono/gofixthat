<?php
/**
 * Template Name: Main Page
 *
 * @package Apparel
 */

get_header(); ?>

<div id="primary" class="mbf-content-area">

	<?php
	/**
	 * The mbf_main_before hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'mbf_main_before' );
	?>

	<?php
	while ( have_posts() ) :
		the_post();
		$hero = apparel_main_page_get_hero_data( get_the_ID() );
		?>

		<?php
		/**
		 * The mbf_page_before hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_page_before' );
		?>

		<section class="mbf-main-page-hero">
			<div class="mbf-main-page-hero__inner">
				<div class="mbf-main-page-hero__content">
					<h1 class="mbf-main-page-hero__title mbf-entry__title"><?php echo esc_html( $hero['headline'] ); ?></h1>
					<p class="mbf-main-page-hero__subtitle"><?php echo esc_html( $hero['subheadline'] ); ?></p>
				</div>
				<div class="mbf-main-page-hero__cards">
					<?php foreach ( $hero['cards'] as $card ) : ?>
						<a class="mbf-main-page-hero__card" href="<?php echo esc_url( $card['link'] ); ?>">
							<span class="mbf-main-page-hero__card-icon" aria-hidden="true"></span>
							<span class="mbf-main-page-hero__card-title"><?php echo esc_html( $card['title'] ); ?></span>
							<span class="mbf-main-page-hero__card-text"><?php echo esc_html( $card['text'] ); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
				<?php if ( ! empty( $hero['cta']['label'] ) && ! empty( $hero['cta']['link'] ) ) : ?>
					<div class="mbf-main-page-hero__cta">
						<a class="mbf-button mbf-button--solid" href="<?php echo esc_url( $hero['cta']['link'] ); ?>"><?php echo esc_html( $hero['cta']['label'] ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</section>

		<div class="mbf-entry__wrap">

			<?php
			/**
			 * The mbf_entry_wrap_start hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'mbf_entry_wrap_start' );
			?>

			<div class="mbf-entry__container">

				<?php
				/**
				 * The mbf_entry_container_start hook.
				 *
				 * @since 1.0.0
				 */
				do_action( 'mbf_entry_container_start' );
				?>

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

		<?php
		/**
		 * The mbf_page_after hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_page_after' );
		?>

	<?php endwhile; ?>

	<?php
	/**
	 * The mbf_main_after hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'mbf_main_after' );
	?>

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
