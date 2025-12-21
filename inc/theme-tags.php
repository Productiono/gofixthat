<?php
/**
 * Template Tags
 *
 * Functions that are called directly from template parts or within actions.
 *
 * @package Apparel
 */

if ( ! function_exists( 'mbf_header_nav_menu' ) ) {
	class MBF_NAV_Walker extends Walker_Nav_Menu {
		/**
		 * Starts the element output.
		 *
		 * @since 3.0.0
		 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
		 * @since 5.9.0 Renamed `$item` to `$data_object` and `$id` to `$current_object_id`
		 *              to match parent class for PHP 8 named parameter support.
		 *
		 * @see Walker::start_el()
		 *
		 * @param string   $output            Used to append additional content (passed by reference).
		 * @param WP_Post  $data_object       Menu item data object.
		 * @param int      $depth             Depth of menu item. Used for padding.
		 * @param stdClass $args              An object of wp_nav_menu() arguments.
		 * @param int      $current_object_id Optional. ID of the current menu item. Default 0.
		 */
		public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
			// Restores the more descriptive, specific name for use within this method.
			$menu_item = $data_object;

			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

			$classes   = empty( $menu_item->classes ) ? array() : (array) $menu_item->classes;
			$classes[] = 'menu-item-' . $menu_item->ID;

			/**
			 * Filters the arguments for a single nav menu item.
			 *
			 * @since 4.4.0
			 *
			 * @param stdClass $args      An object of wp_nav_menu() arguments.
			 * @param WP_Post  $menu_item Menu item data object.
			 * @param int      $depth     Depth of menu item. Used for padding.
			 */
			$args = apply_filters( 'nav_menu_item_args', $args, $menu_item, $depth );

			/**
			 * Filters the CSS classes applied to a menu item's list item element.
			 *
			 * @since 3.0.0
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param string[] $classes   Array of the CSS classes that are applied to the menu item's `<li>` element.
			 * @param WP_Post  $menu_item The current menu item object.
			 * @param stdClass $args      An object of wp_nav_menu() arguments.
			 * @param int      $depth     Depth of menu item. Used for padding.
			 */
			$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $menu_item, $args, $depth ) );

			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			/**
			 * Filters the ID applied to a menu item's list item element.
			 *
			 * @since 3.0.1
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param string   $menu_id   The ID that is applied to the menu item's `<li>` element.
			 * @param WP_Post  $menu_item The current menu item.
			 * @param stdClass $args      An object of wp_nav_menu() arguments.
			 * @param int      $depth     Depth of menu item. Used for padding.
			 */
			$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $menu_item->ID, $menu_item, $args, $depth );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $class_names . '>';

			$atts           = array();
			$atts['title']  = ! empty( $menu_item->attr_title ) ? $menu_item->attr_title : '';
			$atts['target'] = ! empty( $menu_item->target ) ? $menu_item->target : '';
			if ( '_blank' === $menu_item->target && empty( $menu_item->xfn ) ) {
				$atts['rel'] = 'noopener';
			} else {
				$atts['rel'] = $menu_item->xfn;
			}
			$atts['href']         = ! empty( $menu_item->url ) ? $menu_item->url : '';
			$atts['aria-current'] = $menu_item->current ? 'page' : '';

			if ( '#' === trim( $menu_item->url ) ) {
					$atts['class'] = 'menu-item-without-link';
			}

			/**
			 * Filters the HTML attributes applied to a menu item's anchor element.
			 *
			 * @since 3.6.0
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param array $atts {
			 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
			 *
			 *     @type string $title        Title attribute.
			 *     @type string $target       Target attribute.
			 *     @type string $rel          The rel attribute.
			 *     @type string $href         The href attribute.
			 *     @type string $aria-current The aria-current attribute.
			 * }
			 * @param WP_Post  $menu_item The current menu item object.
			 * @param stdClass $args      An object of wp_nav_menu() arguments.
			 * @param int      $depth     Depth of menu item. Used for padding.
			 */
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $menu_item, $args, $depth );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
					$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			/**
			 * The the_title hook.
			 *
			 * @since 1.0.0
			 */
			$title = apply_filters( 'the_title', $menu_item->title, $menu_item->ID );

			/**
			 * Filters a menu item's title.
			 *
			 * @since 4.4.0
			 *
			 * @param string   $title     The menu item's title.
			 * @param WP_Post  $menu_item The current menu item object.
			 * @param stdClass $args      An object of wp_nav_menu() arguments.
			 * @param int      $depth     Depth of menu item. Used for padding.
			 */
			$title = apply_filters( 'nav_menu_item_title', $title, $menu_item, $args, $depth );

			$link_tag = 'a';

			$item_output  = $args->before;
			$item_output .= '<' . $link_tag . $attributes . '>';
			$item_output .= $args->link_before . '<span>' . $title . '</span>' . $args->link_after;
			$item_output .= '</' . $link_tag . '>';
			$item_output .= $args->after;

			/**
			 * Filters a menu item's starting output.
			 *
			 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
			 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
			 * no filter for modifying the opening and closing `<li>` for a menu item.
			 *
			 * @since 3.0.0
			 *
			 * @param string   $item_output The menu item's starting HTML output.
			 * @param WP_Post  $menu_item   Menu item data object.
			 * @param int      $depth       Depth of menu item. Used for padding.
			 * @param stdClass $args        An object of wp_nav_menu() arguments.
			 */
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
		}
	}

	/**
	 * Header Nav Menu
	 *
	 * @param array $settings The advanced settings.
	 */
	function mbf_header_nav_menu( $settings = array() ) {
		if ( ! get_theme_mod( 'header_navigation_menu', true ) ) {
			return;
		}

		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu(
				array(
					'menu_class'      => 'mbf-header__nav-inner',
					'theme_location'  => 'primary',
					'container'       => 'nav',
					'container_class' => 'mbf-header__nav',
					'walker'          => new MBF_NAV_Walker(),
				)
			);
		}
	}
}

if ( ! function_exists( 'mbf_header_logo' ) ) {
	/**
	 * Header Logo
	 *
	 * @param array $settings The advanced settings.
	 */
	function mbf_header_logo( $settings = array() ) {

		$logo_default_name = 'logo';
		$logo_dark_name    = 'logo_dark';
		$logo_class        = null;

		$settings = array_merge(
			array(
				'variant' => null,
			),
			$settings
		);

		// For hide logo.
		if ( 'hide' === $settings['variant'] ) {
			$logo_class = 'mbf-logo-hide';
		}

		// Get default logo.
		$logo_url = get_theme_mod( $logo_default_name );

		$logo_id = attachment_url_to_postid( $logo_url );

		// Set mode of logo.
		$logo_mode = 'mbf-logo-once';

		// Check display mode.
		if ( $logo_id ) {
			$logo_mode = 'mbf-logo-default';
		}
		?>
		<div class="mbf-logo">
			<a class="mbf-header__logo <?php echo esc_attr( $logo_mode ); ?> <?php echo esc_attr( $logo_class ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php
				if ( $logo_id ) {
					mbf_get_retina_image( $logo_id, array( 'alt' => get_bloginfo( 'name' ) ) );
				} else {
					bloginfo( 'name' );
				}
				?>
			</a>

			<?php
			if ( 'mbf-logo-default' === $logo_mode ) {

				$logo_dark_url = get_theme_mod( $logo_dark_name ) ? get_theme_mod( $logo_dark_name ) : $logo_url;

				$logo_dark_id = attachment_url_to_postid( $logo_dark_url );

				if ( $logo_dark_id ) {
					?>
						<a class="mbf-header__logo mbf-logo-dark <?php echo esc_attr( $logo_class ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<?php mbf_get_retina_image( $logo_dark_id, array( 'alt' => get_bloginfo( 'name' ) ) ); ?>
						</a>
					<?php
				}
			}
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'mbf_header_offcanvas_toggle' ) ) {
	/**
	 * Header Offcanvas Toggle
	 *
	 * @param array $settings The advanced settings.
	 */
	function mbf_header_offcanvas_toggle( $settings = array() ) {

		if ( mbf_offcanvas_exists() ) {

			if ( ! isset( $settings['mobile'] ) ) {
				if ( ! get_theme_mod( 'header_offcanvas', false ) ) {
					return;
				}
			}

			$class = __return_empty_string();
			?>
				<span class="mbf-header__offcanvas-toggle <?php echo esc_attr( $class ); ?>" role="button">
					<i class="mbf-icon mbf-icon-menu"></i>
				</span>
			<?php
		}
	}
}

if ( ! function_exists( 'mbf_header_search_toggle' ) ) {
	/**
	 * Header Search Toggle
	 *
	 * @param array $settings The advanced settings.
	 */
	function mbf_header_search_toggle( $settings = array() ) {
		if ( ! get_theme_mod( 'header_search_button', true ) ) {
			return;
		}
		?>
		<span class="mbf-header__search-toggle" role="button">
			<span class="mbf-header__search-label"><span><?php esc_html_e( 'Search', 'apparel' ); ?></span></span> <i class="mbf-icon mbf-icon-search"></i>
		</span>
		<?php
	}
}

if ( ! function_exists( 'mbf_header_scheme_toggle' ) ) {
	/**
	 * Header Scheme Toggle
	 *
	 * @param array $settings The advanced settings.
	 */
	function mbf_header_scheme_toggle( $settings = array() ) {
		if ( ! get_theme_mod( 'color_scheme_toggle', true ) ) {
			return;
		}
		?>
			<span role="button" class="mbf-site-scheme-toggle mbf-header__scheme-toggle">
				<span class="mbf-site__scheme-toggle-title">
					<span><?php echo esc_html__( 'Dark Mode', 'apparel' ); ?></span>
				</span>
				<span class="mbf-site__scheme-toggle-element">
					<span class="mbf-site__scheme-toggle-element-text"><?php echo esc_html__( 'On', 'apparel' ); ?></span>
					<span class="mbf-site__scheme-toggle-element-text"><?php echo esc_html__( 'Off', 'apparel' ); ?></span>
				</span>
			</span>
		<?php
	}
}

if ( ! function_exists( 'mbf_footer_topbar' ) ) {
	/**
	 * Footer Topbar
	 *
	 * @param array $settings The advanced settings.
	 */
	function mbf_footer_topbar( $settings = array() ) {

		$footer_subscribe = get_theme_mod( 'footer_subscribe', false );

		if ( ! $footer_subscribe ) {
			return;
		}
		?>
		<div class="mbf-footer__topbar">
			<div class="mbf-container">
				<div class="mbf-footer__subscribe">
					<div class="mbf-footer__subscribe-container">
						<?php
						$subscribe_title = get_theme_mod( 'footer_subscribe_title' );

						if ( $subscribe_title ) {
							?>
							<h2 class="mbf-footer__subscribe-title">
								<?php echo esc_html( $subscribe_title ); ?>
							</h2>
						<?php } ?>

						<?php
						$subscribe_mailchimp = get_theme_mod( 'footer_subscribe_mailchimp' );

						if ( $subscribe_mailchimp ) {
							?>
							<form class="mbf-footer__subscribe-form" action="<?php echo esc_url( $subscribe_mailchimp ); ?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate="novalidate">
								<div class="mbf-footer__subscribe-form-group">
									<input type="email" placeholder="<?php esc_attr_e( 'Your E-mail', 'apparel' ); ?>" name="EMAIL" id="mce-EMAIL">
									<button type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe"><?php esc_html_e( 'Subscribe', 'apparel' ); ?></button>
								</div>
								<div class="mbf-footer__subscribe-form-response clear" id="mce-responses">
									<div class="response" id="mce-error-response" style="display:none"></div>
									<div class="response" id="mce-success-response" style="display:none"></div>
								</div>
							</form>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'mbf_footer_logo' ) ) {
	/**
	 * Footer Logo
	 *
	 * @param array $settings The advanced settings.
	 */
	function mbf_footer_logo( $settings = array() ) {
		$logo_url = get_theme_mod( 'footer_logo' );

		$logo_id = attachment_url_to_postid( $logo_url );

		$logo_mode = 'mbf-logo-once';

		if ( $logo_id ) {
			$logo_mode = 'mbf-logo-default';
		}
		?>
		<div class="mbf-logo">
			<a class="mbf-footer__logo <?php echo esc_attr( $logo_mode ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php
				if ( $logo_id ) {
					mbf_get_retina_image( $logo_id, array( 'alt' => get_bloginfo( 'name' ) ) );
				} else {
					bloginfo( 'name' );
				}
				?>
			</a>

			<?php
			if ( 'mbf-logo-default' === $logo_mode ) {

				$logo_dark_url = get_theme_mod( 'footer_logo_dark' ) ? get_theme_mod( 'footer_logo_dark' ) : $logo_url;

				$logo_dark_id = attachment_url_to_postid( $logo_dark_url );

				if ( $logo_dark_id ) {
					?>
						<a class="mbf-footer__logo mbf-logo-dark" href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<?php mbf_get_retina_image( $logo_dark_id, array( 'alt' => get_bloginfo( 'name' ) ) ); ?>
						</a>
					<?php
				}
			}
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'mbf_footer_description' ) ) {
	/**
	 * Footer Description
	 *
	 * @param array $settings The advanced settings.
	 */
	function mbf_footer_description( $settings = array() ) {
		$footer_text = get_theme_mod( 'footer_text' );
		if ( $footer_text ) {
			?>
			<div class="mbf-footer__desc">
				<?php echo do_shortcode( $footer_text ); ?>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'mbf_footer_copyright' ) ) {
	/**
	 * Footer Copyright
	 *
	 * @param array $settings The advanced settings.
	 */
	function mbf_footer_copyright( $settings = array() ) {
		$footer_copyright = get_theme_mod( 'footer_copyright', '©️ 2023 - All Rights Reserved.' );
		if ( $footer_copyright ) {
			?>
			<div class="mbf-footer__copyright">
				<?php echo do_shortcode( $footer_copyright ); ?>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'mbf_footer_promo_image' ) ) {
	/**
	 * Footer Promo Image
	 *
	 * @param array $settings The advanced settings.
	 */
	function mbf_footer_promo_image( $settings = array() ) {
		$promo_url = get_theme_mod( 'footer_promo_image' );

		if ( $promo_url ) {
			$promo_id = attachment_url_to_postid( $promo_url );

			if ( $promo_id ) {
				?>
				<div class="mbf-footer__info-promo">
					<?php mbf_get_retina_image( $promo_id, array( 'alt' => 'promo' ) ); ?>
				</div>
				<?php
			}
		}
	}
}

if ( ! function_exists( 'mbf_footer_nav_menu' ) ) {
	/**
	 * Footer Nav Menu
	 *
	 * @param array $settings The advanced settings.
	 */
	function mbf_footer_nav_menu( $settings = array() ) {

		$settings = array_merge(
			array(
				'menu_class' => null,
			),
			$settings
		);

		if ( has_nav_menu( 'footer' ) ) {
			?>
			<div class="mbf-footer__nav-menu">
				<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'footer',
						'container_class' => '',
						'menu_class'      => sprintf( 'mbf-footer__nav %s', $settings['menu_class'] ),
						'container'       => '',
						'depth'           => 2,
					)
				);
				?>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'mbf_off_canvas_secondary_menu' ) ) {
	/**
	 * Offcanvas Secondary Menu
	 *
	 * @param array $settings The advanced settings.
	 */
	function mbf_off_canvas_secondary_menu( $settings = array() ) {

		$settings = array_merge(
			array(
				'menu_class' => null,
			),
			$settings
		);

		if ( has_nav_menu( 'secondary' ) ) {
			?>
			<div class="mbf-offcanvas__secondary-menu">
				<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'secondary',
						'container_class' => '',
						'menu_class'      => sprintf( '%s', $settings['menu_class'] ),
						'container'       => '',
						'depth'           => 1,
					)
				);
				?>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'mbf_off_canvas_additional_menu' ) ) {
	/**
	 * Offcanvas Additional Menu
	 *
	 * @param array $settings The advanced settings.
	 */
	function mbf_off_canvas_additional_menu( $settings = array() ) {

		$settings = array_merge(
			array(
				'menu_class' => null,
			),
			$settings
		);

		if ( has_nav_menu( 'additional' ) ) {
			?>
			<div class="mbf-offcanvas__additional-menu">
				<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'additional',
						'container_class' => '',
						'menu_class'      => sprintf( '%s', $settings['menu_class'] ),
						'container'       => '',
						'depth'           => 1,
					)
				);
				?>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'mbf_off_canvas_scheme_toggle' ) ) {
	/**
	 * Offcanvas Scheme Toggle
	 *
	 * @param array $settings The advanced settings.
	 */
	function mbf_off_canvas_scheme_toggle( $settings = array() ) {
		if ( ! get_theme_mod( 'color_scheme_toggle', true ) ) {
			return;
		}
		?>
			<span role="button" class="mbf-site-scheme-toggle mbf-offcanvas__scheme-toggle">
				<span class="mbf-site__scheme-toggle-title">
					<span><?php echo esc_html__( 'Dark Mode', 'apparel' ); ?></span>
				</span>
				<span class="mbf-site__scheme-toggle-element">
					<span class="mbf-site__scheme-toggle-element-text"><?php echo esc_html__( 'On', 'apparel' ); ?></span>
					<span class="mbf-site__scheme-toggle-element-text"><?php echo esc_html__( 'Off', 'apparel' ); ?></span>
				</span>
			</span>
		<?php
	}
}

if ( ! function_exists( 'mbf_the_post_format_icon' ) ) {
	/**
	 * Post Format Icon
	 *
	 * @param string $content After content.
	 */
	function mbf_the_post_format_icon( $content = null ) {
		$post_format = get_post_format();

		if ( 'gallery' === $post_format ) {
			$attachments = count(
				(array) get_children(
					array(
						'post_parent' => get_the_ID(),
						'post_type'   => 'attachment',
					)
				)
			);

			$content = $attachments ? sprintf( '<span>%s</span>', $attachments ) : '';
		}

		if ( $post_format ) {
			?>
			<span class="mbf-entry-format">
				<a class="mbf-format-icon mbf-format-<?php echo esc_attr( $post_format ); ?>" href="<?php the_permalink(); ?>">
					<?php echo wp_kses( $content, 'content' ); ?>
				</a>
			</span>
			<?php
		}
	}
}

if ( ! function_exists( 'mbf_post_subtitle' ) ) {
	/**
	 * Post Subtitle
	 */
	function mbf_post_subtitle() {
		if ( ! is_single() ) {
			return;
		}

		if ( get_theme_mod( 'post_subtitle', true ) ) {
			/**
			 * The plugins/wp_subtitle/get_subtitle hook.
			 *
			 * @since 1.0.0
			 */
			$subtitle = apply_filters( 'plugins/wp_subtitle/get_subtitle', '', array(
				'before'  => '',
				'after'   => '',
				'post_id' => get_the_ID(),
			) );

			if ( $subtitle ) {
				?>
				<div class="mbf-entry__subtitle">
					<?php echo wp_kses( $subtitle, 'content' ); ?>
				</div>
				<?php
			}
		}
	}
}

if ( ! function_exists( 'mbf_author_subtitle' ) ) {
	/**
	 * Author Subtitle
	 */
	function mbf_author_subtitle() {
		$user_subtitle = get_user_meta( get_the_author_meta( 'ID' ), '_user_subtitle', true );
		if ( $user_subtitle ) {
			?>
			<div class="mbf-page__archive-subtitle">
				<?php echo do_shortcode( $user_subtitle ); ?>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'mbf_archive_description' ) ) {
	/**
	 * Archive Description
	 */
	function mbf_archive_description() {
		$description = get_the_archive_description();
		if ( $description ) {
			?>
			<div class="mbf-page__archive-description">
				<?php echo do_shortcode( $description ); ?>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'mbf_archive_posts_count' ) ) {
	/**
	 * Archive Posts Count
	 */
	function mbf_archive_posts_count() {
		global $wp_query;
		$found_posts = $wp_query->found_posts;
		?>
		<div class="mbf-page__archive-count">
			<?php
			/* translators: 1: Singular, 2: Plural. */
			$found_posts_count = sprintf( _n( '%s post', '%s posts', $found_posts, 'apparel' ), $found_posts );

			/**
			 * The mbf_article_full_count hook.
			 *
			 * @since 1.0.0
			 */
			echo esc_html( apply_filters( 'mbf_article_full_count', $found_posts_count, $found_posts ) );
			?>
		</div>
		<?php
	}
}
