<?php
/**
 * Template Name: Lead Gen
 * Template Post Type: page
 *
 * @package Apparel
 */

get_header();

$lead_gen_video  = get_post_meta( get_the_ID(), 'lead_gen_video_url', true );
$lead_gen_poster = get_post_meta( get_the_ID(), 'lead_gen_poster_url', true );
$default_video   = get_template_directory_uri() . '/assets/static/background-video.webm';
$video_url       = $lead_gen_video ? $lead_gen_video : $default_video;
?>

<div class="lead-gen-page">
	<section class="lead-gen-hero">
		<div class="lead-gen-hero__media" aria-hidden="true">
			<video
				class="lead-gen-hero__video"
				data-lead-gen-video
				preload="none"
				autoplay
				muted
				loop
				playsinline
				<?php if ( $lead_gen_poster ) : ?>
					poster="<?php echo esc_url( $lead_gen_poster ); ?>"
				<?php endif; ?>
			>
				<source data-src="<?php echo esc_url( $video_url ); ?>" type="video/webm" />
			</video>
			<span class="lead-gen-hero__overlay"></span>
		</div>
		<div class="lead-gen-hero__content lead-gen-content">
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
			?>
		</div>
	</section>
</div>

<?php
get_footer();
?>
