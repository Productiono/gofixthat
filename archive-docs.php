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
		:root {
			--docs-text: #1d1d1d;
			--docs-muted: #4a4a4a;
			--docs-border: #e3e3e3;
			--docs-surface: #f7f7f7;
			--docs-card: #ffffff;
			--docs-accent: #181818;
		}

		* {
			box-sizing: border-box;
		}

		body.docs-landing-page {
			margin: 0;
			background: linear-gradient(180deg, #f6f6f6 0%, #f5f5f5 38%, #ffffff 80%);
			font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
			color: var(--docs-text);
			-webkit-font-smoothing: antialiased;
		}

		.docs-page {
			min-height: 100vh;
			display: flex;
			flex-direction: column;
			background: transparent;
		}

		.docs-header {
			position: sticky;
			top: 0;
			z-index: 50;
			background: #fbfbfb;
			border-bottom: 1px solid #ededed;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
		}

		.docs-header-inner {
			max-width: 1320px;
			margin: 0 auto;
			padding: 16px 32px 14px;
			display: grid;
			grid-template-columns: auto 1fr auto;
			align-items: center;
			gap: 22px;
		}

		.docs-brand-nav {
			display: flex;
			align-items: center;
			min-width: 0;
			gap: 20px;
		}

		.docs-brand-nav .mbf-logo {
			display: flex;
			align-items: center;
		}

		.docs-brand-nav .mbf-header__logo img {
			max-height: 32px;
			width: auto;
		}

		.docs-nav {
			display: flex;
			align-items: center;
			gap: 18px;
			font-weight: 600;
			font-size: 14px;
			margin-left: 6px;
			flex-wrap: wrap;
		}

		.docs-nav a {
			text-decoration: none;
			color: #2c2c2c;
			padding: 10px 4px 12px;
			border-bottom: 2px solid transparent;
			transition: color 0.15s ease, border-color 0.15s ease;
			display: inline-flex;
			align-items: center;
			gap: 8px;
		}

		.docs-nav a.is-active {
			color: #111;
			border-color: #1f1f1f;
		}

		.docs-nav a:hover {
			color: #000;
			border-color: rgba(0, 0, 0, 0.12);
		}

		.docs-search {
			display: flex;
			justify-content: center;
			align-items: center;
		}

		.docs-search form {
			width: min(720px, 100%);
			position: relative;
		}

		.docs-search input {
			width: 100%;
			padding: 12px 110px 12px 44px;
			border-radius: 14px;
			border: 1px solid #dcdcdc;
			background: #fff;
			box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7), 0 10px 30px rgba(0, 0, 0, 0.06);
			font-size: 14px;
			color: #262626;
			outline: none;
		}

		.docs-search input::placeholder {
			color: #7a7a7a;
		}

		.docs-search .docs-search-icon {
			position: absolute;
			left: 16px;
			top: 50%;
			transform: translateY(-50%);
			color: #9a9a9a;
		}

		.docs-search .docs-search-hint {
			position: absolute;
			right: 14px;
			top: 50%;
			transform: translateY(-50%);
			font-size: 12px;
			color: #5e5e5e;
			background: #f1f1f1;
			border: 1px solid #d9d9d9;
			border-radius: 8px;
			padding: 6px 8px;
			font-weight: 600;
		}

		.docs-utility {
			display: flex;
			justify-content: flex-end;
			align-items: center;
			gap: 16px;
			font-size: 13px;
			color: #3f3f3f;
			font-weight: 600;
		}

		.docs-utility a {
			color: inherit;
			text-decoration: none;
		}

		.docs-utility .mbf-site-scheme-toggle {
			display: inline-flex;
			align-items: center;
			gap: 8px;
			padding: 8px 10px;
			border-radius: 12px;
			border: 1px solid #e5e5e5;
			background: #fff;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
			cursor: pointer;
		}

		.docs-utility .mbf-site__scheme-toggle-element {
			background: #0f0f0f;
			color: #fff;
		}

		.docs-hero {
			position: relative;
			padding: 78px 24px 40px;
			overflow: hidden;
		}

		.docs-hero::before {
			content: '';
			position: absolute;
			inset: 0;
			background: radial-gradient(1200px circle at 8% -10%, rgba(255, 197, 171, 0.35), transparent 45%), radial-gradient(1200px circle at 86% 4%, rgba(171, 197, 255, 0.35), transparent 38%);
			opacity: 0.7;
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
			border-color: #d6d6d6;
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
			color: #555;
			background: #f0f0f0;
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
			grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
			gap: 14px;
		}

		.docs-article {
			border: 1px solid var(--docs-border);
			border-radius: 14px;
			padding: 14px 14px 16px;
			background: #fff;
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
			color: #555;
		}

		.docs-article-meta a {
			color: inherit;
			text-decoration: none;
			background: #f5f5f5;
			border-radius: 10px;
			padding: 6px 8px;
			border: 1px solid #e4e4e4;
		}

		.docs-pagination {
			margin-top: 18px;
			text-align: center;
		}

		.docs-pagination .page-numbers {
			display: inline-block;
			margin: 0 4px;
			padding: 8px 12px;
			border: 1px solid #e0e0e0;
			border-radius: 10px;
			color: inherit;
			text-decoration: none;
			font-size: 13px;
			font-weight: 600;
		}

		.docs-pagination .page-numbers.current {
			background: #111;
			color: #fff;
			border-color: #111;
		}

		.docs-empty-state {
			color: var(--docs-muted);
			font-size: 14px;
			background: #fff;
			padding: 16px;
			border-radius: 12px;
			border: 1px solid #e5e5e5;
			box-shadow: 0 8px 20px rgba(0, 0, 0, 0.03);
		}

		@media (max-width: 960px) {
			.docs-header-inner {
				grid-template-columns: 1fr;
				padding: 16px 18px 12px;
				gap: 12px;
			}

			.docs-brand-nav {
				justify-content: space-between;
			}

			.docs-nav {
				margin-left: 0;
			}

			.docs-utility {
				justify-content: flex-start;
			}

			.docs-articles-header {
				align-items: flex-start;
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
	<header class="docs-header">
		<div class="docs-header-inner">
			<div class="docs-brand-nav">
				<?php mbf_component( 'header_logo' ); ?>
				<nav class="docs-nav" aria-label="<?php esc_attr_e( 'Documentation navigation', 'apparel' ); ?>">
					<a href="<?php echo esc_url( get_post_type_archive_link( 'docs' ) ); ?>" class="is-active"><?php esc_html_e( 'Docs Home', 'apparel' ); ?></a>
					<?php
					if ( ! empty( $docs_categories ) && ! is_wp_error( $docs_categories ) ) {
						foreach ( $docs_categories as $category ) {
							printf(
								'<a href="%1$s">%2$s</a>',
								esc_url( get_term_link( $category ) ),
								esc_html( $category->name )
							);
						}
					}
					?>
					<a href="#docs-articles"><?php esc_html_e( 'All Articles', 'apparel' ); ?></a>
				</nav>
			</div>
			<div class="docs-search">
				<form role="search" aria-label="<?php esc_attr_e( 'Search documentation', 'apparel' ); ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<span class="docs-search-icon" aria-hidden="true">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none">
							<path d="m15.5 15.5 4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
							<circle cx="11" cy="11" r="5.5" stroke="currentColor" stroke-width="1.6" />
						</svg>
					</span>
					<input type="search" name="s" placeholder="<?php esc_attr_e( 'Search docs...', 'apparel' ); ?>" aria-label="<?php esc_attr_e( 'Search query', 'apparel' ); ?>" />
					<input type="hidden" name="post_type" value="docs" />
					<span class="docs-search-hint" aria-hidden="true">Ctrl K</span>
				</form>
			</div>
			<div class="docs-utility">
				<?php mbf_component( 'header_scheme_toggle' ); ?>
			</div>
		</div>
	</header>

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

			<?php if ( have_posts() ) : ?>
				<div class="docs-articles-grid">
					<?php
					while ( have_posts() ) :
						the_post();
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

				<div class="docs-pagination">
					<?php
					the_posts_pagination(
						array(
							'prev_text' => '',
							'next_text' => '',
						)
					);
					?>
				</div>
			<?php else : ?>
				<p class="docs-empty-state"><?php esc_html_e( 'No docs have been published yet. Add a new doc from the WordPress admin to get started.', 'apparel' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

</div>

<?php wp_footer(); ?>
</body>
</html>
