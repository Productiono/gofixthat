<?php
/**
 * Theme Demos
 *
 * @package Apparel
 */

if ( ! class_exists( 'MBF_Theme_Demos' ) ) {
	/**
	 * Theme Demos class.
	 */
	class MBF_Theme_Demos {
		/**
		 * The slug name to refer to this menu by.
		 *
		 * @var string $menu_slug The menu slug.
		 */
		public $menu_slug = 'theme-demos';

		/**
		 * The demos of page.
		 *
		 * @var array $demos The demos.
		 */
		public $demos = array();

		/**
		 * Constructor.
		 */
		public function __construct() {
			$self = $this;

			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			require_once get_theme_file_path( '/core/theme-demos/import/class-widget-importer.php' );
			require_once get_theme_file_path( '/core/theme-demos/import/class-customizer-importer.php' );

			// Include import.
			require_once get_theme_file_path( '/core/theme-demos/import/class-manager-import.php' );

			// Actions.
			add_action( 'init', array( $this, 'set_demos' ) );

			add_action( 'init', function () use ( $self ) {
				add_action( 'admin_menu', array( $self, 'add_menu_page' ) );
			} );

			add_action( 'wp_ajax_mbf_html_import_data', array( $this, 'html_import_data' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 5 );
		}

		/**
		 * Add menu page
		 */
		public function add_menu_page() {
			add_submenu_page( 'themes.php', esc_html__( 'Theme Demos', 'apparel' ), esc_html__( 'Theme Demos', 'apparel' ), 'manage_options', $this->menu_slug, array( $this, 'html_carcase' ), 2 );
		}

		/**
		 * Get plugin status.
		 *
		 * @param string $plugin_path Plugin path.
		 */
		public function get_plugin_status( $plugin_path ) {
			if ( ! current_user_can( 'install_plugins' ) ) {
				return;
			}

			if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin_path ) ) {
				return 'not_installed';
			} elseif ( in_array( $plugin_path, (array) get_option( 'active_plugins', array() ), true ) || is_plugin_active_for_network( $plugin_path ) ) {
				return 'active';
			} else {
				return 'inactive';
			}
		}

		/**
		 * Demos
		 *
		 * @param array $demos The demos.
		 */
		public function set_demos( $demos ) {
			/**
			 * The mbf_register_demos_list hook.
			 *
			 * @since 1.0.0
			 */
			$this->demos = apply_filters( 'mbf_register_demos_list', $this->demos );
		}

		/**
		 * Html Import Data
		 */
		public function html_import_data() {
			global $wpdb;

			check_ajax_referer( 'nonce', 'nonce' );

			$demo_id = isset( $_POST['demo_id'] ) ? sanitize_text_field( $_POST['demo_id'] ) : false;

			if ( $demo_id ) {

				if ( ! isset( $this->demos[ $demo_id ] ) ) {
					wp_send_json_error( esc_html__( 'Invalid demo content id.', 'apparel' ) );
					wp_die();
				}

				// Reset import data.
				$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like( 'mbf_importer_data_' ) . '%' ) ); // db call ok; no-cache ok.

				$demo_data = $this->demos[ $demo_id ];

				ob_start();

				$demo_plugins = isset( $demo_data['plugins'] ) ? $demo_data['plugins'] : array();

				if ( $demo_plugins ) {
					foreach ( $demo_plugins as $key => $plugin ) {
						if ( ! isset( $plugin['name'] ) ) {
							unset( $demo_plugins[ $key ] );
							continue;
						}
						if ( ! isset( $plugin['slug'] ) ) {
							unset( $demo_plugins[ $key ] );
							continue;
						}
						if ( ! isset( $plugin['path'] ) ) {
							unset( $demo_plugins[ $key ] );
							continue;
						}
						if ( 'active' === $this->get_plugin_status( $plugin['path'] ) ) {
							unset( $demo_plugins[ $key ] );
							continue;
						}
					}
				}
				?>
				<div class="mbf-import-data">
					<?php if ( $demo_plugins ) { ?>
						<div class="mbf-import-plugins">
							<div class="mbf-import-subheader">
								<?php esc_html_e( 'Install Plugins', 'apparel' ); ?>
							</div>

							<div class="mbf-msg-warning">
								<?php esc_html_e( 'Plugins are optional. You can import the demo now and install recommended plugins later.', 'apparel' ); ?>
							</div>

							<?php
							foreach ( $demo_plugins as $plugin ) {
								$recommended = isset( $plugin['recommended'] ) ? $plugin['recommended'] : false;
								?>
								<form>
									<div class="mbf-switcher">
										<?php echo esc_html( $plugin['name'] ); ?> <input class="mbf-checkbox" type="checkbox" name="<?php echo esc_attr( $plugin['slug'] ); ?>" value="1">

										<?php if ( $recommended ) { ?>
											<span class="mbf-badge"><?php esc_html_e( 'Recommended', 'apparel' ); ?></span>
										<?php } ?>

										<?php if ( isset( $plugin['desc'] ) && $plugin['desc'] ) { ?>
											<div class="mbf-tooltip-help"><i class="dashicons dashicons-editor-help"></i></div>

											<div class="mbf-tooltip-desc"><?php echo esc_html( $plugin['desc'] ); ?></div>
										<?php } ?>

										<div class="mbf-switch"><span class="mbf-switch-slider"></span></div>

										<div class="mbf-tooltip"><?php esc_html_e( 'Optional plugin installation (recommended but not required).', 'apparel' ); ?></div>
									</div>

									<input type="hidden" name="plugin_slug" value="<?php echo esc_attr( $plugin['slug'] ); ?>">

									<input type="hidden" name="plugin_path" value="<?php echo esc_attr( $plugin['path'] ); ?>">

									<input type="hidden" name="step_name" value="<?php esc_attr_e( 'Installing and activating', 'apparel' ); ?> <?php echo esc_attr( $plugin['name'] ); ?>...">

									<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'nonce' ) ); ?>">

									<input type="hidden" name="action" value="mbf_import_plugin">
								</form>
							<?php } ?>
						</div>
					<?php } ?>

					<div class="mbf-import-content">
						<div class="mbf-import-subheader">
							<?php esc_html_e( 'Import Content', 'apparel' ); ?>
						</div>

						<?php
						if ( isset( $demo_data['import']['content'] ) && is_array( $demo_data['import']['content'] ) && $demo_data['import']['content'] ) {
							$kits = $demo_data['import']['content'];
							?>
							<div class="mbf-import-kits">
								<?php foreach ( $kits as $kit ) { ?>
									<form>
										<div class="mbf-switcher">
											<?php echo esc_html( $kit['label'] ); ?> <input class="mbf-checkbox" type="checkbox" name="url" value="<?php echo esc_attr( $kit['url'] ); ?>" checked>

											<?php if ( isset( $kit['desc'] ) && $kit['desc'] ) { ?>
												<div class="mbf-tooltip-help"><i class="dashicons dashicons-editor-help"></i></div>

												<div class="mbf-tooltip-desc"><?php echo esc_html( $kit['desc'] ); ?></div>
											<?php } ?>

											<div class="mbf-switch"><span class="mbf-switch-slider"></span></div>

											<input type="hidden" name="type" value="<?php echo esc_attr( isset( $kit['type'] ) ? $kit['type'] : 'default' ); ?>">

											<input type="hidden" name="step_name" value="<?php esc_attr_e( 'Importing', 'apparel' ); ?> <?php echo esc_attr( $kit['label'] ); ?> ...">

											<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'nonce' ) ); ?>">

											<input type="hidden" name="action" value="mbf_import_contents">
										</div>
									</form>
								<?php } ?>
							</div>
						<?php } ?>

						<?php if ( isset( $demo_data['import']['customizer'] ) && $demo_data['import']['customizer'] ) { ?>
							<form>
								<div class="mbf-switcher">
									<?php esc_html_e( 'Customizer', 'apparel' ); ?> <input class="mbf-checkbox" type="checkbox" name="url" value="<?php echo esc_attr( $demo_data['import']['customizer'] ); ?>" checked>

									<div class="mbf-switch"><span class="mbf-switch-slider"></span></div>
								</div>

								<input type="hidden" name="step_name" value="<?php esc_attr_e( 'Importing customizer options...', 'apparel' ); ?>">

								<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'nonce' ) ); ?>">

								<input type="hidden" name="action" value="mbf_import_customizer">
							</form>
						<?php } ?>

						<?php if ( isset( $demo_data['import']['options'] ) && $demo_data['import']['options'] ) { ?>
							<form>
								<div class="mbf-switcher">
									<?php esc_html_e( 'Options', 'apparel' ); ?> <input class="mbf-checkbox" type="checkbox" name="url" value="<?php echo esc_attr( $demo_data['import']['options'] ); ?>" checked>

									<div class="mbf-switch"><span class="mbf-switch-slider"></span></div>
								</div>

								<input type="hidden" name="step_name" value="<?php esc_attr_e( 'Importing options...', 'apparel' ); ?>">

								<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'nonce' ) ); ?>">

								<input type="hidden" name="action" value="mbf_import_options">
							</form>
						<?php } ?>

						<?php if ( isset( $demo_data['import']['widgets'] ) && $demo_data['import']['widgets'] ) { ?>
							<form>
								<div class="mbf-switcher">
									<?php esc_html_e( 'Widgets', 'apparel' ); ?> <input class="mbf-checkbox" type="checkbox" name="url" value="<?php echo esc_attr( $demo_data['import']['widgets'] ); ?>" checked>

									<div class="mbf-switch"><span class="mbf-switch-slider"></span></div>
								</div>

								<input type="hidden" name="step_name" value="<?php esc_attr_e( 'Importing widgets...', 'apparel' ); ?>">

								<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'nonce' ) ); ?>">

								<input type="hidden" name="action" value="mbf_import_widgets">
							</form>
						<?php } ?>

						<form class="hidden">
							<div class="mbf-switcher">
								<?php esc_html_e( 'Finish', 'apparel' ); ?> <input class="mbf-checkbox" type="checkbox" name="finish" value="1" checked>

								<div class="mbf-switch"><span class="mbf-switch-slider"></span></div>
							</div>

							<input type="hidden" name="step_name" value="<?php esc_attr_e( 'Finishing setup...', 'apparel' ); ?>">

							<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'nonce' ) ); ?>">

							<input type="hidden" name="demo_id" value="<?php echo esc_attr( $demo_id ); ?>">

							<input type="hidden" name="action" value="mbf_import_finish">
						</form>
					</div>
				</div>

				<div class="mbf-import-actions">
					<div class="mbf-import-theme-cancel">
						<a href="#" class="mbf-button mbf-demo-import-close button">
							<?php esc_html_e( 'Cancel', 'apparel' ); ?>
						</a>
					</div>

					<div class="mbf-import-theme-start">
						<a href="#" class="mbf-demo-import-start button button-primary">
							<?php esc_html_e( 'Import', 'apparel' ); ?>
						</a>
					</div>
				</div>
				<?php
				wp_send_json_success( ob_get_clean() );
			} else {
				wp_send_json_error( esc_html__( 'Demo content id not set.', 'apparel' ) );
			}

			wp_die();
		}

		/**
		 * Html Carcase
		 */
		public function html_carcase() {
			?>
			<div class="mbf-wrap mbf-demos-page">
				<div class="mbf-header">
					<div class="mbf-header-left">
						<div class="mbf-header-col mbf-header-col-logo">
							<div class="mbf-logo">
								<a target="_blank" href="<?php echo esc_url( 'https://merchantsbestfriends.com/' ); ?>">
									Merchants’ Best Friends
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="wrap">
					<h1 class="hidden"><?php esc_html_e( 'Theme Demos', 'apparel' ); ?></h1>

					<?php
					if ( $this->demos ) {
						?>
						<div class="mbf-demos">
							<?php
							foreach ( $this->demos as $demo_id => $demo ) {
								// Demo Variables.
								$name    = isset( $demo['name'] ) && $demo['name'] ? $demo['name'] : null;
								$preview = isset( $demo['preview'] ) && $demo['preview'] ? $demo['preview'] : 'false';
								?>
								<div class="mbf-demo-item mbf-demo-item-active"
									data-id="<?php echo esc_attr( $demo_id ); ?>"
									data-name="<?php echo esc_attr( $name ); ?>"
									data-preview="<?php echo esc_url( $preview ); ?>">

									<div class="mbf-demo-outer">
										<div class="mbf-demo-thumbnail">
											<?php if ( isset( $demo['thumbnail'] ) && $demo['thumbnail'] ) { ?>
												<img src="<?php echo esc_url( $demo['thumbnail'] ); ?>">
											<?php } ?>

											<?php if ( isset( $demo['preview'] ) && $demo['preview'] ) { ?>
												<div class="mbf-demo-preview">
													<span>
														<?php esc_html_e( 'Preview Demo', 'apparel' ); ?>
													</span>
												</div>
											<?php } ?>
										</div>
										<div class="mbf-demo-data">
											<div class="mbf-demo-info">
												<?php if ( isset( $demo['name'] ) && $demo['name'] ) { ?>
													<div class="mbf-demo-name"><?php echo esc_html( $demo['name'] ); ?></div>
												<?php } ?>
											</div>

											<div class="mbf-demo-actions">
												<div class="mbf-demo-import">
													<a href="#" target="_blank" data-id="<?php echo esc_attr( $demo_id ); ?>" class="mbf-demo-import-open button button-primary">
														<?php esc_html_e( 'Import', 'apparel' ); ?>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>

				<div class="mbf-import-theme">
					<div class="mbf-import-overlay"></div>

					<div class="mbf-import-popup">
						<div class="mbf-import-container">
							<div class="mbf-import-step mbf-import-step-active mbf-import-start">
								<div class="mbf-import-header">
									<?php esc_html_e( 'Import Theme', 'apparel' ); ?>
								</div>

								<div class="mbf-import-output"></div>
							</div>

							<div class="mbf-import-step mbf-import-process">
								<div class="mbf-import-header">
									<?php esc_html_e( 'Installing', 'apparel' ); ?>
								</div>

								<div class="mbf-import-output">
									<div class="mbf-import-desc">
										<?php esc_html_e( 'Please be patient and don\'t refresh this page, the import process may take a while, this also depends on your server.', 'apparel' ); ?>
									</div>

									<div class="mbf-import-progress">
										<div class="mbf-import-progress-label"></div>

										<div class="mbf-import-progress-bar">
											<div class="mbf-import-progress-indicator"></div>
										</div>

										<div class="mbf-import-progress-sublabel">0%</div>
									</div>
								</div>
							</div>

							<div class="mbf-import-step mbf-import-finish">
								<div class="mbf-import-info">
									<div class="mbf-import-logo">
										<svg class="progress-icon" width="96" height="96" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
											<g class="tick-icon" stroke-width="1" stroke="#3FB28F" transform="translate(1, 1.2)">
											<path id="tick-outline-path" d="M14 28c7.732 0 14-6.268 14-14S21.732 0 14 0 0 6.268 0 14s6.268 14 14 14z"/>
											<path id="tick-path" d="M6.173 16.252l5.722 4.228 9.22-12.69"/>
											</g>
										</svg>
									</div>

									<div class="mbf-import-title">
										<?php esc_html_e( 'Imported Succefully', 'apparel' ); ?>
									</div>

									<div class="mbf-import-desc">
										<?php esc_html_e( 'Go ahead, customize the text, images and design to make it yours!', 'apparel' ); ?>
									</div>

									<div class="mbf-import-customize">
										<a href="<?php echo esc_url( admin_url( '/customize.php' ) ); ?>" class="button button-primary" target="_blank">
											<?php esc_html_e( 'Customize', 'apparel' ); ?>
										</a>
									</div>
								</div>

								<div class="mbf-import-actions">
									<a href="<?php echo esc_url( home_url() ); ?>" class="mbf-button button" target="_blank">
										<?php esc_html_e( 'View Site', 'apparel' ); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="mbf-preview">
					<div class="mbf-header">
						<div class="mbf-header-left">
							<div class="mbf-header-col mbf-header-logo">
								<div class="mbf-logo">
									<a target="_blank" href="<?php echo esc_url( 'https://merchantsbestfriends.com/' ); ?>">
										Merchants’ Best Friends
									</a>
								</div>
							</div>

							<div class="mbf-header-col mbf-header-arrow">
								<a href="#" class="mbf-arrow mbf-prev-demo"></a>
							</div>

							<div class="mbf-header-col mbf-header-arrow">
								<a href="#" class="mbf-arrow mbf-next-demo"></a>
							</div>

							<div class="mbf-header-col mbf-header-info"></div>
						</div>

						<div class="mbf-header-right">
							<div class="mbf-preview-cancel">
								<a href="#" class="button">
									<?php esc_html_e( 'Cancel', 'apparel' ); ?>
								</a>
							</div>

							<div class="mbf-preview-actions"></div>
						</div>
					</div>

					<i<?php echo esc_attr( 'frame' ); ?> id="mbf-preview-i<?php echo esc_attr( 'frame' ); ?>" class="mbf-preview-i<?php echo esc_attr( 'frame' ); ?>"></i<?php echo esc_attr( 'frame' ); ?>>
				</div>
			</div>
			<?php
		}

		/**
		 * This function will register scripts and styles for admin dashboard.
		 *
		 * @param string $page Current page.
		 */
		public function admin_enqueue_scripts( $page ) {
			wp_enqueue_script( 'mbf-theme-demos', get_theme_file_uri( '/core/theme-demos/assets/theme-demos.js' ), array( 'jquery' ), filemtime( get_theme_file_path( '/core/theme-demos/assets/theme-demos.js' ) ), true );

			wp_localize_script( 'mbf-theme-demos', 'cscoThemeDemosConfig', array(
				'ajax_url'       => admin_url( 'admin-ajax.php' ),
				'nonce'          => wp_create_nonce( 'nonce' ),
				'failed_message' => esc_html__( 'Something went wrong, contact support.', 'apparel' ),
			) );

			// Styles.
			wp_enqueue_style( 'mbf-theme-demos', get_theme_file_uri( '/core/theme-demos/assets/theme-demos.css' ), array(), filemtime( get_theme_file_path( '/core/theme-demos/assets/theme-demos.css' ) ) );

			// Add RTL support.
			wp_style_add_data( 'apparel', 'rtl', 'replace' );
		}
	}

	new MBF_Theme_Demos();
}
