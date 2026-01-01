<?php
/**
 * Docs sidebar template.
 *
 * @package Apparel
 */

$sidebar_items = isset( $args['items'] ) ? $args['items'] : mbf_get_doc_sidebar_items();
$sidebar_title = isset( $args['title'] ) ? $args['title'] : esc_html__( 'Documentation', 'apparel' );
$walker        = new MBF_Docs_Sidebar_Walker();
?>
<div class="docs-sidebar__inner">
	<div class="docs-sidebar__header">
		<span class="docs-sidebar__title"><?php echo esc_html( $sidebar_title ); ?></span>
		<button type="button" class="docs-sidebar__toggle docs-sidebar__toggle--close" data-docs-sidebar-toggle aria-expanded="true">
			<span aria-hidden="true">×</span>
			<span class="screen-reader-text"><?php esc_html_e( 'Close documentation navigation', 'apparel' ); ?></span>
		</button>
	</div>

	<?php if ( ! empty( $sidebar_items ) ) : ?>
		<nav class="docs-sidebar__nav" aria-label="<?php esc_attr_e( 'Documentation navigation', 'apparel' ); ?>">
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
