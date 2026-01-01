<?php
/**
 * Docs sidebar template.
 *
 * @package Apparel
 */

$sidebar_args = isset( $args ) && is_array( $args ) ? $args : array();

if ( empty( $sidebar_args ) && function_exists( 'get_query_var' ) ) {
	$sidebar_args = get_query_var( 'mbf_docs_sidebar_args', array() );
}

if ( isset( $sidebar_args['items'] ) ) {
	$sidebar_items = is_array( $sidebar_args['items'] ) ? $sidebar_args['items'] : array();
} elseif ( function_exists( 'mbf_get_doc_sidebar_items' ) ) {
	$sidebar_items = mbf_get_doc_sidebar_items();
} else {
	$sidebar_items = array();
}

$sidebar_title = isset( $sidebar_args['title'] ) ? $sidebar_args['title'] : esc_html__( 'Documentation', 'apparel' );
$walker        = class_exists( 'MBF_Docs_Sidebar_Walker' ) ? new MBF_Docs_Sidebar_Walker() : null;
?>
<div class="docs-sidebar__inner">
	<div class="docs-sidebar__header">
		<span class="docs-sidebar__title"><?php echo esc_html( $sidebar_title ); ?></span>
		<button type="button" class="docs-sidebar__toggle docs-sidebar__toggle--close" data-docs-sidebar-toggle data-docs-sidebar-focus-target aria-expanded="true">
			<span aria-hidden="true">×</span>
			<span class="screen-reader-text"><?php esc_html_e( 'Close documentation navigation', 'apparel' ); ?></span>
		</button>
	</div>

	<?php if ( $walker instanceof Walker && ! empty( $sidebar_items ) ) : ?>
		<nav class="docs-sidebar__nav" aria-label="<?php esc_attr_e( 'Documentation navigation', 'apparel' ); ?>" aria-labelledby="docs-sidebar-heading">
			<h2 class="screen-reader-text" id="docs-sidebar-heading"><?php esc_html_e( 'Documentation navigation', 'apparel' ); ?></h2>
			<ul class="docs-sidebar__list">
				<?php
				echo $walker->walk( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$sidebar_items,
					0,
					array(
						'list_class' => 'docs-sidebar__list',
						'link_class' => 'docs-sidebar__link',
					)
				);
				?>
			</ul>
		</nav>
	<?php else : ?>
		<p class="docs-sidebar__empty"><?php esc_html_e( 'No documentation content found yet.', 'apparel' ); ?></p>
	<?php endif; ?>
</div>
