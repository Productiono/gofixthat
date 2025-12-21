<?php
/**
 * Customizer Heading
 *
 * @package Apparel
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MBF_Customize_Heading_Control' ) ) {
	/**
	 * Class Customize Heading
	 */
	class MBF_Customize_Heading_Control extends WP_Customize_Control {

		/**
		 * The field type.
		 *
		 * @var string
		 */
		public $type = 'heading';

		/**
		 * Render the control content.
		 */
		protected function render_content() {
			?>
				<label class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
			<?php
		}
	}
}
