<?php
/**
 * Customizer Divider
 *
 * @package Apparel
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MBF_Customize_Divider_Control' ) ) {
	/**
	 * Class Customize Divider
	 */
	class MBF_Customize_Divider_Control extends WP_Customize_Control {

		/**
		 * The field type.
		 *
		 * @var string
		 */
		public $type = 'divider';

		/**
		 * Render the control content.
		 */
		protected function render_content() {
			?>
				<div class="customizer-divider"></div>
			<?php
		}
	}
}
