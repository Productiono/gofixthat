<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "mbf-site" div and all content after
 *
 * @package Apparel
 */

?>

							<?php
							/**
							 * The mbf_main_content_end hook.
							 *
							 * @since 1.0.0
							 */
							do_action( 'mbf_main_content_end' );
							?>

						</div>

						<?php
						/**
						 * The mbf_main_content_after hook.
						 *
						 * @since 1.0.0
						 */
						do_action( 'mbf_main_content_after' );
						?>

					</div>

					<?php
					/**
					 * The mbf_site_content_end hook.
					 *
					 * @since 1.0.0
					 */
					do_action( 'mbf_site_content_end' );
					?>

				</div>

				<?php
				/**
				 * The mbf_site_content_after hook.
				 *
				 * @since 1.0.0
				 */
				do_action( 'mbf_site_content_after' );
				?>

			</main>

		<?php
		/**
		 * The mbf_footer_before hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_footer_before' );
		?>

		<?php get_template_part( 'template-parts/footer' ); ?>

		<?php
		/**
		 * The mbf_footer_after hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_footer_after' );
		?>

	</div>

	<?php
	/**
	 * The mbf_site_end hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'mbf_site_end' );
	?>

</div>

<?php
/**
 * The mbf_site_after hook.
 *
 * @since 1.0.0
 */
do_action( 'mbf_site_after' );
?>

<?php wp_footer(); ?>
<!-- MatterCall Voice Widget -->
<script>
  (function(w,d,s,o,f,js){
    w[o]=w[o]||function(){(w[o].q=w[o].q||[]).push(arguments)};
    js=d.createElement(s);js.id=o;js.src=f;js.async=1;
    (d.head||d.body).appendChild(js);
  }(window,document,'script','vw','https://mattercall.com/widget/embed.js'));
  vw('init', 'wgt_8byBF2xjsWqepe0luYCmyPEM');
</script>
</body>
</html>
