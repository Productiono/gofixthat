<?php
/**
 * WooCommerce theme offcanvas
 *
 * @package Apparel
 */

/**
 * Add my account to offcanvas
 *
 * @param array $settings The advanced settings.
 */
function mbf_off_canvas_my_account( $settings = array() ) {

	if ( ! get_theme_mod( 'woocommerce_hide_my_account_button', false ) ) {
		$myaccount_page = wc_get_page_id( 'myaccount' );

		if ( $myaccount_page ) {
			?>
			<a class="mbf-offcanvas__my-account widget" href="<?php echo esc_url( get_permalink( $myaccount_page ) ); ?>">
				<div class="mbf-offcanvas__my-account-info">
					<div class="mbf-offcanvas__my-account-label"><?php esc_html_e( 'Account', 'apparel' ); ?></div>

					<?php if ( ! is_user_logged_in() ) { ?>
						<div class="mbf-offcanvas__my-account-val"><?php esc_html_e( 'Log In', 'apparel' ); ?></div>
					<?php
					} else {
						$current_user = wp_get_current_user();
						?>
						<div class="mbf-offcanvas__my-account-val">
							<?php esc_html_e( 'Welcome, ', 'apparel' ); ?>

							<?php echo esc_html( $current_user->display_name ); ?>
						</div>
					<?php } ?>
				</div>

				<div class="mbf-offcanvas__my-account-icon">
					<i class="mbf-icon mbf-icon-account"></i>
				</div>
			</a>
			<?php
		}
	}
}
