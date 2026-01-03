<?php
/**
 * These functions are used to load template parts (partials) or actions when used within action hooks,
 * and they probably should never be updated or modified.
 *
 * @package Apparel
 */

if ( ! function_exists( 'mbf_singular_post_type_before' ) ) {
	/**
	 * Add Before Singular Hooks for specific post type.
	 */
	function mbf_singular_post_type_before() {
		if ( 'post' === get_post_type() ) {
			/**
			 * The mbf_post_content_before hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'mbf_post_content_before' );
		}
		if ( 'page' === get_post_type() ) {
			/**
			 * The mbf_page_content_before hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'mbf_page_content_before' );
		}
	}
}

if ( ! function_exists( 'mbf_singular_post_type_after' ) ) {
	/**
	 * Add After Singular Hooks for specific post type.
	 */
	function mbf_singular_post_type_after() {
		if ( 'post' === get_post_type() ) {
			/**
			 * The mbf_post_content_after hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'mbf_post_content_after' );
		}
		if ( 'page' === get_post_type() ) {
			/**
			 * The mbf_page_content_after hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'mbf_page_content_after' );
		}
	}
}

if ( ! function_exists( 'mbf_notification_bar' ) ) {
	/**
	 * Notification Bar
	 */
	function mbf_notification_bar() {
		get_template_part( 'template-parts/notification-bar' );
	}
}

if ( ! function_exists( 'mbf_offcanvas' ) ) {
	/**
	 * Off-canvas
	 */
	function mbf_offcanvas() {
		get_template_part( 'template-parts/offcanvas' );
	}
}

if ( ! function_exists( 'mbf_site_scheme' ) ) {
	/**
	 * Site Scheme
	 */
	function mbf_site_scheme() {
		$site_scheme = mbf_site_scheme_data();

		if ( ! $site_scheme ) {
			return;
		}

		call_user_func( 'printf', '%s', "data-scheme='{$site_scheme}'" );
	}
}

if ( ! function_exists( 'mbf_site_search' ) ) {
	/**
	 * Site Search
	 */
	function mbf_site_search() {
		if ( ! get_theme_mod( 'header_search_button', true ) ) {
			return;
		}
		get_template_part( 'template-parts/site-search' );
	}
}

if ( ! function_exists( 'mbf_site_nav_mobile' ) ) {
	/**
	 * Site Nav Mobile
	 */
	function mbf_site_nav_mobile() {
		get_template_part( 'template-parts/site-nav-mobile' );
	}
}

if ( ! function_exists( 'mbf_theme_breadcrumbs' ) ) {
	/**
	 * Theme Breadcrumbs
	 */
	function mbf_theme_breadcrumbs() {

		/**
		 * The mbf_theme_breadcrumbs hook.
		 *
		 * @since 1.0.0
		 */
		if ( ! apply_filters( 'mbf_theme_breadcrumbs', true ) ) {
			return;
		}

		if ( is_front_page() ) {
			return;
		}

		if ( is_404() ) {
			return;
		}

		if ( ! is_user_logged_in() && function_exists( 'is_account_page' ) && is_account_page() ) {
			return;
		}

		mbf_breadcrumbs();
	}
}

if ( ! function_exists( 'mbf_page_header' ) ) {
	/**
	 * Page Header
	 */
	function mbf_page_header() {
		if ( ! ( is_home() || is_archive() || is_search() || is_404() ) ) {
			return;
		}
		get_template_part( 'template-parts/page-header' );
	}
}

if ( ! function_exists( 'mbf_page_pagination' ) ) {
	/**
	 * Post Pagination
	 */
	function mbf_page_pagination() {
		if ( ! is_singular() ) {
			return;
		}

		/**
		 * The mbf_pagination_before hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_pagination_before' );

		wp_link_pages(
			array(
				'before'           => '<div class="navigation pagination posts-navigation"><div class="nav-links">',
				'after'            => '</div></div>',
				'link_before'      => '<span class="page-number">',
				'link_after'       => '</span>',
				'next_or_number'   => 'number',
				'separator'        => ' ',
				'nextpagelink'     => esc_html__( 'Next page', 'apparel' ),
				'previouspagelink' => esc_html__( 'Previous page', 'apparel' ),
			)
		);

		/**
		 * The mbf_pagination_after hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_pagination_after' );
	}
}

if ( ! function_exists( 'mbf_entry_breadcrumbs' ) ) {
	/**
	 * Entry Breadcrumbs
	 */
	function mbf_entry_breadcrumbs() {
		mbf_breadcrumbs();
	}
}

if ( ! function_exists( 'mbf_entry_header' ) ) {
	/**
	 * Entry Header Simple and Standard
	 */
	function mbf_entry_header() {
		if ( ! is_singular() ) {
			return;
		}

		if ( is_singular( 'post' ) ) {
			return;
		}

		if ( 'none' === mbf_get_page_header_type() ) {
			return;
		}

		if ( function_exists( 'is_cart' ) && is_cart() ) {
			return;
		}

		if ( is_page_template( 'template-without-header.php' ) ) {
			return;
		}

		if ( is_page_template( 'template-blank.php' ) ) {
			return;
		}

		get_template_part( 'template-parts/entry/entry-header' );
	}
}

if ( ! function_exists( 'mbf_blog_post_cta' ) ) {
	/**
	 * Category-based CTA for blog posts.
	 */
	function mbf_blog_post_cta() {
		if ( ! is_singular( 'post' ) ) {
			return;
		}

		$cta_data = mbf_get_active_category_cta_data();

		if ( empty( $cta_data ) ) {
			return;
		}

		get_template_part( 'template-parts/entry/entry-blog-cta' );
	}
}

if ( ! function_exists( 'mbf_entry_tags' ) ) {
	/**
	 * Entry Tags
	 */
	function mbf_entry_tags() {
		if ( ! is_singular( 'post' ) ) {
			return;
		}
		if ( false === get_theme_mod( 'post_tags', true ) ) {
			return;
		}

		the_tags( '<div class="mbf-entry__tags"><ul><li>', '</li><li>', '</li></ul></div>' );
	}
}

if ( ! function_exists( 'mbf_entry_footer' ) ) {
	/**
	 * Entry Footer
	 */
	function mbf_entry_footer() {
		if ( ! is_singular( 'post' ) ) {
			return;
		}
		if ( false === get_theme_mod( 'post_footer', true ) ) {
			return;
		}
		get_template_part( 'template-parts/entry/entry-footer' );
	}
}

if ( ! function_exists( 'mbf_entry_comments' ) ) {
	/**
	 * Entry Comments
	 */
	function mbf_entry_comments() {
		if ( is_singular( 'post' ) ) {
			return;
		}

		if ( post_password_required() ) {
			return;
		}

		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	}
}

if ( ! function_exists( 'mbf_entry_prev_next' ) ) {
	/**
	 * Entry Prev Next
	 */
	function mbf_entry_prev_next() {
		if ( ! is_singular( 'post' ) ) {
			return;
		}
		if ( false === get_theme_mod( 'post_prev_next', true ) ) {
			return;
		}

		get_template_part( 'template-parts/entry/entry-prev-next' );
	}
}

if ( ! function_exists( 'mbf_list_categories' ) ) {
	/**
	 * List Categories
	 */
	function mbf_list_categories() {

		$page_blog = mbf_get_page_id_by_title( 'Blog' );

		if ( ! ( get_queried_object_id() === $page_blog ) && ! is_category()  ) {
			return;
		}

		$args = array(
			'taxonomy'   => 'category',
			'parent'     => 0,
			'hide_empty' => true,
		);

		$categories = get_terms( $args );

		if ( $categories ) {
			?>
			<section class="mbf-list-categories">
				<ul>
					<?php if ( $page_blog ) { ?>
						<li class="<?php echo esc_attr( get_queried_object_id() === (int) $page_blog ? 'is-active' : __return_empty_string() ); ?>">
							<a href="<?php echo esc_attr( get_the_permalink( $page_blog ) ); ?>">
								<?php esc_html_e( 'All Articles', 'apparel' ); ?>
							</a>
						</li>
					<?php } ?>

					<?php
					foreach ( $categories as $category ) {
						?>
							<li class="<?php echo esc_attr( get_queried_object_id() === (int) $category->term_id ? 'is-active' : __return_empty_string() ); ?>">
								<a href="<?php echo esc_attr( get_term_link( $category->term_id ) ); ?>">
									<?php echo esc_html( $category->name ); ?>
								</a>
							</li>
						<?php
					}
					?>
				</ul>
			</section>
			<?php
		}
	}
}

if ( ! function_exists( 'mbf_blog_exit_popup' ) ) {
	/**
	 * Blog Exit Popup markup.
	 */
	function mbf_blog_exit_popup() {
		if ( is_admin() ) {
			return;
		}

		if ( ! ( is_singular( 'post' ) || is_category() ) ) {
			return;
		}

		$title_id   = 'blog-exit-popup-title';
		$video_url  = get_template_directory_uri() . '/assets/static/background-video.webm';
		?>
		<div class="blog-exit-popup" aria-hidden="true" aria-modal="true" role="dialog" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
			<div class="blog-exit-popup__backdrop" aria-hidden="true">
				<video class="blog-exit-popup__video" autoplay muted loop playsinline>
					<source src="<?php echo esc_url( $video_url ); ?>" type="video/webm">
				</video>
			</div>
			<div class="blog-exit-popup__content">
				<button type="button" class="blog-exit-popup__close" aria-label="<?php esc_attr_e( 'Close popup', 'apparel' ); ?>">×</button>
				<div class="blog-exit-popup__card">
					<div class="blog-exit-popup__logo">
						<?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) { ?>
							<?php the_custom_logo(); ?>
						<?php } else { ?>
							<span class="blog-exit-popup__logo-text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
						<?php } ?>
					</div>
					<h2 class="blog-exit-popup__title" id="<?php echo esc_attr( $title_id ); ?>">
						<?php printf( esc_html__( 'Your business starts with %s', 'apparel' ), esc_html( get_bloginfo( 'name' ) ) ); ?>
					</h2>
					<p class="blog-exit-popup__subtitle">
						<?php esc_html_e( 'Try 3 days free, then 1 €/month for 3 months. What are you waiting for?', 'apparel' ); ?>
					</p>
				</div>
				<div class="blog-exit-popup__form-shell">
					<div class="blog-exit-popup__form-card">
						<p class="blog-exit-popup__form-title"><?php esc_html_e( 'Start for free', 'apparel' ); ?></p>
						<p class="blog-exit-popup__form-note"><?php esc_html_e( 'You agree to receive marketing emails.', 'apparel' ); ?></p>
						<div class="blog-exit-popup__form">
							<?php echo do_shortcode( '[fluentform id="2"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'mbf_cookie_consent_notice' ) ) {
	/**
	 * Cookie consent notice markup.
	 */
	function mbf_cookie_consent_notice() {
		if ( is_admin() ) {
			return;
		}
		?>
		<div class="mbf-cookie-consent" role="region" aria-label="<?php esc_attr_e( 'Cookie consent notice', 'apparel' ); ?>" hidden>
			<p class="mbf-cookie-consent__message">
				<?php esc_html_e( 'We use cookies to improve your experience. Choose whether to accept or reject them.', 'apparel' ); ?>
			</p>
			<div class="mbf-cookie-consent__actions" aria-label="<?php esc_attr_e( 'Cookie consent actions', 'apparel' ); ?>">
				<button type="button" class="mbf-cookie-consent__button mbf-cookie-consent__button--primary" data-cookie-consent="accept">
					<?php esc_html_e( 'Accept', 'apparel' ); ?>
				</button>
				<button type="button" class="mbf-cookie-consent__button" data-cookie-consent="reject">
					<?php esc_html_e( 'Reject', 'apparel' ); ?>
				</button>
			</div>
		</div>
		<?php
	}
}
