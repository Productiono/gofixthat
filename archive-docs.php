<?php
/**
 * Archive template for Docs.
 *
 * @package Apparel
 */

$docs_categories = get_terms(
	array(
		'taxonomy'   => 'docs_category',
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

$docs_nav_links      = mbf_get_docs_nav_links( $docs_categories );
$docs_search_markup  = mbf_get_docs_search_markup();
$docs_utility_markup = mbf_get_docs_utility_markup();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
	<style>
		body.docs-landing-page {
			margin: 0;
			background: var(--mbf-site-background);
			font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
			color: var(--docs-text);
			-webkit-font-smoothing: antialiased;
		}

		<?php echo mbf_get_docs_header_css(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

		.docs-hero {
			position: relative;
			padding: 78px 24px 40px;
			overflow: hidden;
		}

		.docs-hero::before {
			content: '';
			position: absolute;
			inset: 0;
			background: radial-gradient(1200px circle at 8% -10%, color-mix(in srgb, var(--docs-accent) 22%, transparent), transparent 45%), radial-gradient(1200px circle at 86% 4%, color-mix(in srgb, var(--docs-accent) 16%, transparent), transparent 38%);
			opacity: 0.5;
			filter: blur(20px);
		}

		.docs-hero-inner {
			position: relative;
			max-width: 1140px;
			margin: 0 auto;
			padding: 0 16px;
			text-align: center;
		}

		.docs-hero h1 {
			font-size: clamp(30px, 4vw, 42px);
			margin: 0 0 12px;
			font-weight: 700;
			letter-spacing: -0.01em;
		}

		.docs-hero p {
			font-size: 16px;
			margin: 0;
			color: var(--docs-muted);
			max-width: 780px;
			margin-left: auto;
			margin-right: auto;
			line-height: 1.6;
		}

		.docs-card-section {
			padding: 18px 18px 24px;
			position: relative;
		}

		.docs-card-grid {
			max-width: 1240px;
			margin: 0 auto;
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
			gap: 18px;
		}

		.docs-card {
			display: flex;
			gap: 16px;
			border-radius: 14px;
			border: 1px solid var(--docs-border);
			background: var(--docs-card);
			padding: 18px 18px 16px;
			box-shadow: 0 12px 30px rgba(0, 0, 0, 0.04);
			align-items: flex-start;
			text-decoration: none;
			color: inherit;
			transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
		}

		.docs-card:hover {
			border-color: var(--docs-border);
			box-shadow: 0 18px 40px rgba(0, 0, 0, 0.08);
			transform: translateY(-2px);
		}

		.docs-card h3 {
			font-size: 16px;
			margin: 2px 0 8px;
			display: flex;
			align-items: center;
			gap: 8px;
		}

		.docs-card h3 svg {
			opacity: 0.9;
		}

		.docs-card p {
			margin: 0 0 10px;
			color: var(--docs-muted);
			line-height: 1.55;
			font-size: 14px;
		}

		.docs-card .docs-card-meta {
			display: inline-flex;
			align-items: center;
			gap: 6px;
			font-size: 12px;
			font-weight: 600;
			color: var(--docs-muted);
			background: var(--docs-pill);
			border-radius: 10px;
			padding: 6px 10px;
		}

		.docs-card .docs-icon {
			width: 42px;
			height: 42px;
			border-radius: 12px;
			background: var(--docs-surface);
			border: 1px solid var(--docs-border);
			display: inline-flex;
			justify-content: center;
			align-items: center;
			color: var(--docs-accent);
			flex-shrink: 0;
		}

		.docs-card svg {
			width: 22px;
			height: 22px;
		}

		.docs-articles-section {
			padding: 16px 18px 36px;
		}

		.docs-articles-inner {
			max-width: 1240px;
			margin: 0 auto;
		}

		.docs-articles-header {
			display: flex;
			justify-content: space-between;
			gap: 12px;
			align-items: center;
			margin-bottom: 16px;
			flex-wrap: wrap;
		}

		.docs-articles-header h2 {
			margin: 0;
			font-size: 22px;
			letter-spacing: -0.01em;
		}

		.docs-articles-header p {
			margin: 0;
			color: var(--docs-muted);
			font-size: 14px;
		}

		.docs-articles-grid {
			display: grid;
			grid-template-columns: repeat(2, minmax(0, 1fr));
			gap: 14px;
		}

		.docs-article {
			border: 1px solid var(--docs-border);
			border-radius: 14px;
			padding: 14px 14px 16px;
			background: var(--docs-card);
			box-shadow: 0 12px 30px rgba(0, 0, 0, 0.04);
			display: flex;
			flex-direction: column;
			gap: 10px;
		}

		.docs-article h3 {
			margin: 0;
			font-size: 16px;
			line-height: 1.35;
		}

		.docs-article h3 a {
			color: inherit;
			text-decoration: none;
		}

		.docs-article h3 a:hover {
			text-decoration: underline;
		}

		.docs-article p {
			margin: 0;
			color: var(--docs-muted);
			font-size: 14px;
			line-height: 1.5;
		}

		.docs-article-meta {
			display: flex;
			gap: 8px;
			flex-wrap: wrap;
			font-size: 12px;
			font-weight: 600;
			color: var(--docs-muted);
		}

		.docs-article-meta a {
			color: inherit;
			text-decoration: none;
			background: var(--docs-surface);
			border-radius: 10px;
			padding: 6px 8px;
			border: 1px solid var(--docs-border);
		}

		.docs-pagination {
			margin-top: 18px;
			text-align: center;
		}

		.docs-pagination .page-numbers {
			display: inline-block;
			margin: 0 4px;
			padding: 8px 12px;
			border: 1px solid var(--docs-border);
			border-radius: 10px;
			color: inherit;
			text-decoration: none;
			font-size: 13px;
			font-weight: 600;
		}

		.docs-pagination .page-numbers.current {
			background: var(--docs-accent);
			color: var(--docs-contrast);
			border-color: var(--docs-accent);
		}

		.docs-empty-state {
			color: var(--docs-muted);
			font-size: 14px;
			background: var(--docs-card);
			padding: 16px;
			border-radius: 12px;
			border: 1px solid var(--docs-border);
			box-shadow: 0 8px 20px rgba(0, 0, 0, 0.03);
		}

		@media (max-width: 960px) {
			.docs-page {
				padding-top: 82px;
			}

			.docs-header {
				position: fixed;
				inset: 0 0 auto 0;
			}

			.docs-header-inner {
				grid-template-columns: auto 1fr auto;
				padding: 14px 18px 12px;
				gap: 10px;
			}

			.docs-brand-nav {
				justify-content: flex-start;
				gap: 12px;
			}

			.docs-nav {
				display: none;
				margin-left: 0;
			}

			.docs-utility {
				display: none;
				justify-content: flex-start;
			}

			.docs-articles-header {
				align-items: flex-start;
			}

			.docs-search {
				display: none;
			}

			.docs-mobile-panel {
				grid-template-columns: 1fr;
			}

			.docs-mobile-menu {
				display: inline-flex;
			}
		}

		@media (max-width: 640px) {
			.docs-articles-grid {
				grid-template-columns: 1fr;
			}
		}
	</style>
</head>
<body <?php body_class( 'docs-landing-page' ); ?> <?php mbf_site_scheme(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>

<div class="docs-page">
	<?php mbf_render_docs_header( array( 'nav_links' => $docs_nav_links, 'search_markup' => $docs_search_markup, 'utility_markup' => $docs_utility_markup ) ); ?>

	<?php mbf_site_search(); ?>

	<section class="docs-hero" aria-labelledby="docs-hero-title">
		<div class="docs-hero-inner">
			<h1 id="docs-hero-title"><?php esc_html_e( 'Documentation', 'apparel' ); ?></h1>
			<p><?php esc_html_e( 'Browse guides, references, and tutorials. Categories and descriptions are fully manageable from the WordPress admin.', 'apparel' ); ?></p>
		</div>
	</section>

	<section class="docs-card-section" aria-label="<?php esc_attr_e( 'Documentation categories', 'apparel' ); ?>">
		<div class="docs-card-grid">
			<?php
			if ( ! empty( $docs_categories ) && ! is_wp_error( $docs_categories ) ) {
				foreach ( $docs_categories as $category ) {
					$description = $category->description ? wp_trim_words( wp_strip_all_tags( $category->description ), 28, '…' ) : __( 'Explore the docs in this category.', 'apparel' );
					$article_meta = sprintf(
						/* translators: %s: number of docs */
						_n( '%s article', '%s articles', $category->count, 'apparel' ),
						number_format_i18n( $category->count )
					);
					?>
					<a class="docs-card" href="<?php echo esc_url( get_term_link( $category ) ); ?>">
						<div class="docs-icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none">
								<path d="M12 4c.5 0 2 1.2 2 2.7 0 .9-.6 1.6-.6 2.1 0 .4.3.7.6 1 .4.4.8.9 1 1.5.5 1.6-.8 3.4-3 3.4-2.3 0-3.7-1.9-3.1-3.6.2-.6.6-1 1-1.3.3-.3.5-.6.5-1 0-.6-.6-1.2-.6-2C9.8 5.2 11.3 4 12 4Zm-4.8 7.5H5.5c-.8 0-1.5.7-1.5 1.5V16c0 .8.7 1.5 1.5 1.5h1.7M16.8 11.5h1.7c.8 0 1.5.7 1.5 1.5V16c0 .8-.7 1.5-1.5 1.5h-1.7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
						</div>
						<div>
							<h3>
								<?php echo esc_html( $category->name ); ?>
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
									<path d="M8 16l6-6m0 0h-5m5 0v5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</h3>
							<p><?php echo esc_html( $description ); ?></p>
							<span class="docs-card-meta">
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
									<path d="M6.5 6.5h11v11h-11z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
									<path d="M9 3.5v3m7-3v3m-9 .5h11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
								</svg>
								<?php echo esc_html( $article_meta ); ?>
							</span>
						</div>
					</a>
					<?php
				}
			} else {
				?>
				<div class="docs-card">
					<div class="docs-icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none">
							<path d="M7 5h10c.6 0 1 .4 1 1v12c0 .6-.4 1-1 1H7c-.6 0-1-.4-1-1V6c0-.6.4-1 1-1Z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
							<path d="M10 8h6.2M10 11h6.2M10 14H14" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" />
						</svg>
					</div>
					<div>
						<h3><?php esc_html_e( 'Create your first docs category', 'apparel' ); ?></h3>
						<p><?php esc_html_e( 'Add a Docs category with a description from the WordPress dashboard to populate this page.', 'apparel' ); ?></p>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</section>

	<section id="docs-articles" class="docs-articles-section" aria-label="<?php esc_attr_e( 'Docs articles', 'apparel' ); ?>">
		<div class="docs-articles-inner">
			<div class="docs-articles-header">
				<div>
					<h2><?php esc_html_e( 'Docs articles', 'apparel' ); ?></h2>
					<p><?php esc_html_e( 'Browse the latest docs or filter by category.', 'apparel' ); ?></p>
				</div>
			</div>

			<?php
			$docs_articles = new WP_Query(
				array(
					'post_type'      => 'docs',
					'posts_per_page' => 4,
					'post_status'    => 'publish',
				)
			);

			if ( $docs_articles->have_posts() ) :
				?>
				<div class="docs-articles-grid">
					<?php
					while ( $docs_articles->have_posts() ) :
						$docs_articles->the_post();
						$article_terms = get_the_terms( get_the_ID(), 'docs_category' );
						?>
						<article class="docs-article">
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<?php if ( has_excerpt() || get_the_excerpt() ) : ?>
								<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28, '…' ) ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $article_terms ) && ! is_wp_error( $article_terms ) ) : ?>
								<div class="docs-article-meta">
									<?php
									foreach ( $article_terms as $article_term ) {
										printf(
											'<a href="%1$s">%2$s</a>',
											esc_url( get_term_link( $article_term ) ),
											esc_html( $article_term->name )
										);
									}
									?>
								</div>
							<?php endif; ?>
							</article>
						<?php endwhile; ?>
					</div>
					<?php
					wp_reset_postdata();
				else :
					?>
					<p class="docs-empty-state"><?php esc_html_e( 'No docs have been published yet. Add a new doc from the WordPress admin to get started.', 'apparel' ); ?></p>
				<?php endif; ?>
		</div>
	</section>

</div>

<script>
<?php echo mbf_docs_header_script(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</script>

<?php wp_footer(); ?>
</body>
</html>
