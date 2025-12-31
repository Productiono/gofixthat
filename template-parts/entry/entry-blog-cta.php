<?php
/**
 * Blog static promotional section.
 *
 * @package Apparel
 */

$primary_cta_label      = esc_html__( 'Kostenlos starten', 'apparel' );
$secondary_cta_label    = esc_html__( 'So funktioniert Shopify', 'apparel' );
$primary_cta_url        = apply_filters( 'mbf_blog_cta_primary_url', home_url( '/' ) );
$posts_page_id          = get_option( 'page_for_posts' );
$secondary_default_url  = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/' );
$secondary_cta_url      = apply_filters( 'mbf_blog_cta_secondary_url', $secondary_default_url );
?>

<section class="mbf-blog-cta" aria-labelledby="mbf-blog-cta-title">
	<div class="mbf-blog-cta__inner">
		<div class="mbf-blog-cta__content">
			<p class="mbf-blog-cta__eyebrow"><?php esc_html_e( 'Guided resources for your next launch', 'apparel' ); ?></p>
			<h2 id="mbf-blog-cta-title" class="mbf-blog-cta__title">
				<?php esc_html_e( 'Noch heute mit Shopify verkaufen', 'apparel' ); ?>
			</h2>
			<p class="mbf-blog-cta__description">
				<?php esc_html_e( 'Teste Shopify kostenlos und nutze die Ressourcen, die dich Schritt für Schritt auf deinem Weg begleiten.', 'apparel' ); ?>
			</p>
			<div class="mbf-blog-cta__actions">
				<a class="mbf-button mbf-button--solid" href="<?php echo esc_url( $primary_cta_url ); ?>">
					<?php echo esc_html( $primary_cta_label ); ?>
				</a>
				<a class="mbf-button mbf-button--ghost" href="<?php echo esc_url( $secondary_cta_url ); ?>">
					<span class="mbf-blog-cta__icon" aria-hidden="true">►</span>
					<?php echo esc_html( $secondary_cta_label ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
