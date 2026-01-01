<?php
/**
 * Template Name: Docs Landing
 */

get_header();
$doc_type_exists     = post_type_exists( 'doc_article' );
$doc_category_exists = taxonomy_exists( 'doc_category' );
$archive_link        = $doc_type_exists ? get_post_type_archive_link( 'doc_article' ) : '';

$doc_cards = array();

if ( $doc_type_exists && $doc_category_exists ) {
	$doc_categories = get_terms(
		array(
			'taxonomy'   => 'doc_category',
			'hide_empty' => false,
			'number'     => 8,
		)
	);

	if ( ! is_wp_error( $doc_categories ) ) {
		foreach ( $doc_categories as $doc_category ) {
			$term_link = get_term_link( $doc_category );

			if ( is_wp_error( $term_link ) ) {
				continue;
			}

			$doc_cards[] = array(
				'title'       => $doc_category->name,
				'description' => $doc_category->description,
				'url'         => $term_link,
			);
		}
	}
}

if ( empty( $doc_cards ) ) {
	$doc_cards = array(
		array(
			'title'       => __( 'AI Agents', 'apparel' ),
			'description' => __( 'Create and deploy advanced voice and chat AI agents to sell, market and support your customers', 'apparel' ),
			'url'         => $archive_link ? $archive_link : home_url(),
		),
		array(
			'title'       => __( 'Verify', 'apparel' ),
			'description' => __( 'Securely authenticate users with multi-channel OTP delivery and Fraud Protection', 'apparel' ),
			'url'         => $archive_link ? $archive_link : home_url(),
		),
		array(
			'title'       => __( 'Programmable APIs', 'apparel' ),
			'description' => __( 'Suite of APIs and SDKs to integrate real-time communication features into your applications', 'apparel' ),
			'url'         => $archive_link ? $archive_link : home_url(),
		),
		array(
			'title'       => __( 'Go to Plivo Platform', 'apparel' ),
			'description' => __( 'Get started with Plivo and transform your communication and customer engagement across multiple channels.', 'apparel' ),
			'url'         => $archive_link ? $archive_link : home_url(),
		),
	);
}

$docs_query = null;

if ( $doc_type_exists ) {
	$docs_query = new WP_Query(
		array(
			'post_type'           => 'doc_article',
			'post_status'         => 'publish',
			'posts_per_page'      => 6,
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
		)
	);
}
?>
<style>
.mbf-docs-page {
	font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
	background: #f6f7f9;
	color: #1b1b1b;
}
.mbf-docs-outer {
	max-width: 1200px;
	margin: 0 auto 96px;
	padding: 0 32px;
}
.mbf-docs-hero {
	background: linear-gradient(180deg, #f7f7f7 0%, #eef0f3 100%);
	border: 1px solid #e3e6eb;
	border-radius: 18px;
	padding: 28px 32px;
	margin: 48px auto 32px;
	position: relative;
	overflow: hidden;
}
.mbf-docs-hero:before {
	content: "";
	position: absolute;
	inset: 0;
	background: radial-gradient(120% 80% at 50% 10%, rgba(255,255,255,0.7) 0%, rgba(255,255,255,0) 60%);
	pointer-events: none;
}
.mbf-docs-hero h1 {
	font-size: 28px;
	line-height: 1.2;
	margin: 8px 0 10px;
	font-weight: 700;
	color: #1b1b1b;
}
.mbf-docs-hero p {
	margin: 0;
	color: #4d5157;
	font-size: 16px;
	line-height: 1.6;
	max-width: 760px;
}
.mbf-docs-search {
	display: flex;
	align-items: center;
	gap: 10px;
	margin: 0 auto 14px;
	max-width: 720px;
	padding: 10px 12px;
	border: 1px solid #d6dbe3;
	border-radius: 12px;
	background: #fff;
	box-shadow: 0 8px 20px rgba(20, 33, 61, 0.04);
	position: relative;
	z-index: 1;
}
.mbf-docs-search svg {
	width: 18px;
	height: 18px;
	color: #8a8f99;
	flex-shrink: 0;
}
.mbf-docs-search input {
	border: none;
	background: transparent;
	width: 100%;
	font-size: 14px;
	color: #1b1b1b;
	outline: none;
}
.mbf-docs-search .mbf-docs-kbd {
	font-size: 12px;
	color: #6c7077;
	border: 1px solid #d6dbe3;
	border-radius: 6px;
	padding: 4px 8px;
	background: #f3f5f8;
	font-weight: 600;
	line-height: 1.2;
}
.mbf-docs-grid {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: 16px;
}
.mbf-doc-card {
	background: #fff;
	border: 1px solid #e1e5eb;
	border-radius: 14px;
	padding: 18px 20px;
	display: flex;
	flex-direction: column;
	gap: 10px;
	min-height: 122px;
	box-shadow: 0 10px 28px rgba(0, 0, 0, 0.03);
	transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}
.mbf-doc-card:hover {
	transform: translateY(-2px);
	box-shadow: 0 16px 36px rgba(0, 0, 0, 0.06);
	border-color: #d5dae2;
}
.mbf-doc-card__top {
	display: flex;
	gap: 12px;
	align-items: center;
}
.mbf-doc-card__icon {
	width: 36px;
	height: 36px;
	border-radius: 10px;
	background: #eef1f6;
	display: grid;
	place-items: center;
	color: #20222a;
	flex-shrink: 0;
}
.mbf-doc-card__icon svg {
	width: 20px;
	height: 20px;
}
.mbf-doc-card__title {
	font-size: 16px;
	font-weight: 700;
	margin: 0;
	color: #1f2024;
}
.mbf-doc-card__desc {
	margin: 0;
	color: #4d5157;
	font-size: 14px;
	line-height: 1.55;
}
.mbf-doc-card__arrow {
	margin-left: auto;
	color: #9aa1ac;
}
.mbf-docs-layout {
	display: flex;
	flex-direction: column;
	gap: 18px;
}
.mbf-doc-card__header {
	display: flex;
	align-items: center;
	gap: 10px;
	width: 100%;
}
.mbf-doc-card__link {
	color: inherit;
	text-decoration: none;
	display: block;
	height: 100%;
}
.mbf-doc-card__link:focus {
	outline: 2px solid #1b76ff;
	outline-offset: 4px;
	border-radius: 14px;
}
.mbf-docs-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 18px 0 6px;
}
.mbf-docs-nav {
	display: flex;
	gap: 22px;
	font-size: 14px;
	color: #42464d;
}
.mbf-docs-nav a {
	color: inherit;
	text-decoration: none;
	font-weight: 600;
	padding-bottom: 6px;
	border-bottom: 2px solid transparent;
}
.mbf-docs-nav a.active {
	color: #101010;
	border-color: #101010;
}
.mbf-docs-actions {
	display: flex;
	gap: 16px;
	align-items: center;
	font-size: 14px;
	color: #42464d;
}
.mbf-docs-actions a {
	color: inherit;
	text-decoration: none;
	font-weight: 600;
}
@media (max-width: 900px) {
	.mbf-docs-outer {
		padding: 0 20px;
	}
	.mbf-docs-grid {
		grid-template-columns: 1fr;
	}
	.mbf-docs-header {
		flex-direction: column;
		align-items: flex-start;
		gap: 10px;
	}
	.mbf-docs-actions {
		align-self: flex-end;
	}
	.mbf-docs-search {
		width: 100%;
	}
}
@media (max-width: 640px) {
	.mbf-docs-outer {
		padding: 0 16px;
	}
	.mbf-docs-hero {
		margin: 32px auto 22px;
		padding: 22px 18px;
	}
	.mbf-docs-hero h1 {
		font-size: 24px;
	}
}
</style>

<main class="mbf-docs-page">
	<div class="mbf-docs-outer">
		<header class="mbf-docs-header">
			<nav class="mbf-docs-nav" aria-label="<?php esc_attr_e( 'Docs navigation', 'apparel' ); ?>">
				<a href="#" class="active"><?php esc_html_e( 'Home', 'apparel' ); ?></a>
				<a href="#"><?php esc_html_e( 'AI Agents', 'apparel' ); ?></a>
				<a href="#"><?php esc_html_e( 'Verify', 'apparel' ); ?></a>
				<a href="#"><?php esc_html_e( 'Programmable APIs', 'apparel' ); ?></a>
			</nav>
			<div class="mbf-docs-actions">
				<a href="#"><?php esc_html_e( 'Support', 'apparel' ); ?></a>
				<a href="#"><?php esc_html_e( 'Log in', 'apparel' ); ?></a>
				<a href="#"><?php esc_html_e( 'Request Trial', 'apparel' ); ?></a>
			</div>
		</header>

		<section class="mbf-docs-hero" aria-labelledby="mbf-docs-title">
			<div class="mbf-docs-search" role="search">
				<svg aria-hidden="true" viewBox="0 0 24 24" fill="none">
					<path d="M16 16l4.5 4.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
					<circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2" />
				</svg>
				<input type="search" placeholder="<?php esc_attr_e( 'Search...', 'apparel' ); ?>" aria-label="<?php esc_attr_e( 'Search documentation', 'apparel' ); ?>" />
				<span class="mbf-docs-kbd"><?php esc_html_e( 'Ctrl K', 'apparel' ); ?></span>
			</div>
			<h1 id="mbf-docs-title"><?php esc_html_e( 'Welcome to Plivo Documentation', 'apparel' ); ?></h1>
			<p><?php esc_html_e( 'Explore comprehensive guides, API references, and discussions to integrate, manage, and optimize your Plivo solutions.', 'apparel' ); ?></p>
		</section>

		<div class="mbf-docs-layout" aria-label="<?php esc_attr_e( 'Documentation topics', 'apparel' ); ?>">
			<div class="mbf-docs-grid">
				<?php foreach ( $doc_cards as $doc_card ) : ?>
					<a class="mbf-doc-card__link" href="<?php echo esc_url( $doc_card['url'] ); ?>">
						<article class="mbf-doc-card">
							<div class="mbf-doc-card__header">
								<div class="mbf-doc-card__icon">
									<svg aria-hidden="true" viewBox="0 0 24 24" fill="none">
										<path d="M9 13v-2h6v2H9zm0-4V7h6v2H9zm-5 8V7l3 3 3-3v10H4zm12-6h6l-2 3 2 3h-6v-6z" fill="currentColor" />
									</svg>
								</div>
								<h2 class="mbf-doc-card__title"><?php echo esc_html( $doc_card['title'] ); ?></h2>
								<span class="mbf-doc-card__arrow" aria-hidden="true">↗</span>
							</div>
							<p class="mbf-doc-card__desc"><?php echo esc_html( $doc_card['description'] ); ?></p>
						</article>
					</a>
				<?php endforeach; ?>
			</div>
		</div>

		<section class="mbf-docs-layout" aria-label="<?php esc_attr_e( 'Latest documentation', 'apparel' ); ?>">
			<?php if ( $docs_query instanceof WP_Query && $docs_query->have_posts() ) : ?>
				<div class="mbf-docs-header">
					<h2 class="mbf-doc-card__title"><?php esc_html_e( 'Latest docs', 'apparel' ); ?></h2>
					<?php if ( $archive_link ) : ?>
						<a class="mbf-docs-actions" href="<?php echo esc_url( $archive_link ); ?>"><?php esc_html_e( 'Browse all docs', 'apparel' ); ?></a>
					<?php endif; ?>
				</div>
				<div class="mbf-docs-grid">
					<?php
					while ( $docs_query->have_posts() ) :
						$docs_query->the_post();
						?>
						<a class="mbf-doc-card__link" href="<?php the_permalink(); ?>">
							<article class="mbf-doc-card">
								<div class="mbf-doc-card__header">
									<div class="mbf-doc-card__icon">
										<svg aria-hidden="true" viewBox="0 0 24 24" fill="none">
											<path d="M4 6h16v2H4V6zm0 4h10v2H4v-2zm0 4h7v2H4v-2zm14.5-1.59l1.09-1.09L22 13.73 18.27 17.5 16 15.23l1.41-1.41 0.86 0.86 0.23 0.23 0.23-0.23 0.77-0.77z" fill="currentColor" />
										</svg>
									</div>
									<h3 class="mbf-doc-card__title"><?php the_title(); ?></h3>
								</div>
								<p class="mbf-doc-card__desc">
									<?php echo wp_kses_post( get_the_excerpt() ? get_the_excerpt() : esc_html__( 'Read the full article', 'apparel' ) ); ?>
								</p>
							</article>
						</a>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			<?php elseif ( $doc_type_exists ) : ?>
				<p><?php esc_html_e( 'No documentation is available yet. Check back soon.', 'apparel' ); ?></p>
			<?php else : ?>
				<p><?php esc_html_e( 'Documentation content is unavailable because the docs post type is not registered.', 'apparel' ); ?></p>
			<?php endif; ?>
		</section>
	</div>
</main>
<?php
get_footer();
