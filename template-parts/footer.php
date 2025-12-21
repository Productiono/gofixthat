<?php
/**
 * The template for displaying the footer layout
 *
 * @package Apparel
 */

?>

<?php mbf_component( 'footer_topbar' ); ?>

<footer class="mbf-footer">
	<div class="mbf-container">
		<div class="mbf-footer__item ">
			<div class="mbf-footer__item-inner">
				<div class="mbf-footer__col mbf-col-left">
					<div class="mbf-footer__col-inner">
						<div class="mbf-footer__info mbf-footer__info-col1">
							<?php mbf_component( 'footer_logo' ); ?>
							<?php mbf_component( 'footer_copyright' ); ?>
						</div>

						<div class="mbf-footer__info mbf-footer__info-col2">
							<?php mbf_component( 'footer_description' ); ?>
							<?php mbf_component( 'footer_promo_image' ); ?>
						</div>
					</div>
				</div>

				<div class="mbf-footer__col mbf-col-right">
					<?php mbf_component( 'footer_nav_menu' ); ?>
				</div>
			</div>
		</div>
	</div>
</footer>
