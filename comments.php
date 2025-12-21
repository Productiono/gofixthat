<?php
/**
 * The template for displaying comments
 *
 * @package Apparel
 */

?>

<?php
/**
 * The mbf_comments_before hook.
 *
 * @since 1.0.0
 */
do_action( 'mbf_comments_before' );
?>

<div class="mbf-entry__comments" id="comments">

	<div class="mbf-container">

		<div class="mbf-entry__comments-inner">

			<?php if ( have_comments() ) { ?>

				<h2 class="comment-list-title">
					<?php esc_html_e( 'Comments', 'apparel' ); ?>
				</h2>

				<?php the_comments_navigation(); ?>

				<ol class="comment-list">
					<?php
					wp_list_comments(
						array(
							'style'       => 'ol',
							'short_ping'  => true,
							'avatar_size' => 60,
						)
					);
					?>
				</ol>

				<?php the_comments_navigation(); ?>

			<?php } ?>

			<?php
			// If comments are closed and there are comments, let's leave a little note, shall we?
			if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) {
				?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'apparel' ); ?></p>
			<?php } ?>

			<?php
			comment_form(
				array(
					'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
					'title_reply_after'  => '</h3>',
					'submit_button'      => '<button name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />' . esc_html__( 'Post Comment', 'apparel' ) . ' </button>',
				)
			);
			?>

		</div>

	</div>

</div>

<?php
/**
 * The mbf_comments_after hook.
 *
 * @since 1.0.0
 */
do_action( 'mbf_comments_after' );
?>
