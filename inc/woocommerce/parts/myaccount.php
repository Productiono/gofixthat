<?php
/**
 * WooCommerce myaccount
 *
 * @package Apparel
 */

/**
 * Add tabs to customer login
 */
function mbf_woocommerce_customer_tabs() {
	?>
	<nav class="woocommerce-MyAccount-navigation">
		<ul>
			<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--login is-active">
				<a href="#login"><?php esc_html_e( 'Login', 'apparel' ); ?></a>
			</li>
			<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) { ?>
				<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--register">
					<a href="#register"><?php esc_html_e( 'Register', 'apparel' ); ?></a>
				</li>
			<?php } ?>
		</ul>
	</nav>
	<?php
}
add_action( 'woocommerce_before_customer_login_form', 'mbf_woocommerce_customer_tabs' );

/**
 * Add placeholders to login and register forms.
 */
add_action( 'woocommerce_before_customer_login_form', function() {
	ob_start(
		function( $html ) {
			$html = str_replace( 'id="username"', sprintf( 'id="username" placeholder="%s"', esc_html__( 'Username or email address *', 'apparel' ) ), $html );
			$html = str_replace( 'id="password"', sprintf( 'id="password" placeholder="%s"', esc_html__( 'Password *', 'apparel' ) ), $html );
			$html = str_replace( 'id="reg_username"', sprintf( 'id="reg_username" placeholder="%s"', esc_html__( 'Username *', 'apparel' ) ), $html );
			$html = str_replace( 'id="reg_email"', sprintf( 'id="reg_email" placeholder="%s"', esc_html__( 'Email address *', 'apparel' ) ), $html );
			$html = str_replace( 'id="reg_password" ', sprintf( 'id="reg_password"  placeholder="%s"', esc_html__( 'Password *', 'apparel' ) ), $html );
			return $html;
		}
	);
} );

add_action( 'woocommerce_after_customer_login_form', function() {
	ob_end_flush();
}, 0 );

/**
 * Add placeholders to login form.
 */
add_action( 'woocommerce_login_form_start', function() {
	ob_start(
		function( $html ) {
			$html = str_replace( 'id="username"', sprintf( 'id="username" placeholder="%s"', esc_html__( 'Username or email address *', 'apparel' ) ), $html );
			$html = str_replace( 'id="password"', sprintf( 'id="password" placeholder="%s"', esc_html__( 'Password *', 'apparel' ) ), $html );
			return $html;
		}
	);
} );

add_action( 'woocommerce_login_form_end', function() {
	ob_end_flush();
}, 0 );

/**
 * Add placeholders to lost password form.
 */
add_action( 'woocommerce_before_lost_password_form', function() {
	ob_start(
		function( $html ) {
			$html = str_replace( 'id="user_login"', sprintf( 'id="user_login" placeholder="%s"', esc_html__( 'Username or email *', 'apparel' ) ), $html );
			return $html;
		}
	);
} );

add_action( 'woocommerce_after_lost_password_form', function() {
	ob_end_flush();
}, 0 );

/**
 * Add placeholders to reset password form.
 */
add_action( 'woocommerce_before_reset_password_form', function() {
	ob_start(
		function( $html ) {
			$html = str_replace( 'id="password_1"', sprintf( 'id="password_1" placeholder="%s"', esc_html__( 'New password *', 'apparel' ) ), $html );
			$html = str_replace( 'id="password_2"', sprintf( 'id="password_2" placeholder="%s"', esc_html__( 'Re-enter new password *', 'apparel' ) ), $html );
			return $html;
		}
	);
} );

add_action( 'woocommerce_after_reset_password_form', function() {
	ob_end_flush();
}, 0 );

/**
 * Add placeholders to edit account form.
 */
add_action( 'woocommerce_before_edit_account_form', function() {
	ob_start(
		function( $html ) {

			$html = str_replace( 'id="account_first_name"', sprintf( 'id="account_first_name" placeholder="%s"', esc_html__( 'First name *', 'apparel' ) ), $html );
			$html = str_replace( 'id="account_last_name"', sprintf( 'id="account_last_name" placeholder="%s"', esc_html__( 'Last name *', 'apparel' ) ), $html );
			$html = str_replace( 'id="account_display_name"', sprintf( 'id="account_display_name" placeholder="%s"', esc_html__( 'Display name *', 'apparel' ) ), $html );
			$html = str_replace( 'id="account_email"', sprintf( 'id="account_email" placeholder="%s"', esc_html__( 'Email address *', 'apparel' ) ), $html );
			$html = str_replace( 'id="password_current"', sprintf( 'id="password_current" placeholder="%s"', esc_html__( 'Current password (leave blank to leave unchanged)', 'apparel' ) ), $html );
			$html = str_replace( 'id="password_1"', sprintf( 'id="password_1" placeholder="%s"', esc_html__( 'New password (leave blank to leave unchanged)', 'apparel' ) ), $html );
			$html = str_replace( 'id="password_2"', sprintf( 'id="password_2" placeholder="%s"', esc_html__( 'Confirm new password', 'apparel' ) ), $html );

			return $html;
		}
	);
} );

add_action( 'woocommerce_after_edit_account_form', function() {
	ob_end_flush();
}, 0 );
