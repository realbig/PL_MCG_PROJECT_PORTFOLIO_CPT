<?php
/**
 * Archive Template for Projects
 * Override this File by adding it to your Parent/Child Theme
 * 
 * Some of the mk_-prefixed functions from Jupiter (Parent Theme) had to be removed from the default single.php in order to make this workable
 * Their functions basically assume that you'll always be in a Theme, but we're in a Plugin. We cannot use their same folder structure
 * It only changes how the "wrapper" <divs> are added really
 *
 * @since		1.0.0
 * @package		PL_MCG_PROJECT_PORTFOLIO_CPT
 * @subpackage	PL_MCG_PROJECT_PORTFOLIO_CPT/core/templates
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

global $post, $mk_options;

// I just want to point out how horribly backwards and misleading this option is.
// "false" for disable_breadcrumbs... disables the breadcrumbs. They're shown on 'true'
$mk_options['disable_breadcrumb'] = 'false';

get_header();

$view_params = array(
    'shortcode_name' => 'mk_blog',
    'style' => 'modern',
    'layout' => 'right',
    'column' => 3,
    'disable_meta' => 'true',
    'grid_image_height' => '350',
    'comments_share' => 'true',
    'full_content' => 'false',
    'image_size' => 'crop',
    'excerpt_length' => 200,
    'thumbnail_align' => 'left',
    'lazyload' => 'false',
    'disable_lazyload' => 'false',
    //'image_quality' => $image_quality,
    'i' => 0
);

if ($view_params['layout'] == 'full') {
	$image_width = $mk_options['grid_width'] - 40;
} else {
	$image_width = (($mk_options['content_width'] / 100) * $mk_options['grid_width']) - 40;
}

?>

<div id="theme-page" class="master-holder  clearfix" role="main" itemprop="mainContentOfPage">

	<div class="master-holder-bg-holder">
		<div id="theme-page-bg" class="master-holder-bg js-el"></div>
	</div>

	<div class="mk-main-wrapper-holder">

		<div class="theme-page-wrapper mk-main-wrapper mk-grid full-layout  ">
			<div class="theme-content " itemprop="mainContentOfPage">
				
				<section class="project-loop">
					
					<?php if ( have_posts() ) : 
					
						while( have_posts() ) : the_post(); ?>

							<article id="<?php the_ID(); ?>" class="mk-blog-modern-item mk-isotop-item <?php echo get_post_type(); ?>-post-type">
								<?php
								$media_atts = array(
									'image_size'    => $view_params['image_size'],
									'image_width'   => $image_width,
									'image_height'  => $view_params['grid_image_height'],
									'lazyload'      => $view_params['lazyload'],
									'disable_lazyload'      => $view_params['disable_lazyload'],
									'post_type'     => get_post_type(),
									//'image_quality' => $view_params['image_quality']
								);
								echo  mk_get_shortcode_view('mk_blog', 'components/featured-media', true, $media_atts); ?>

								<div class="mk-blog-meta">
									<?php
									
									echo mk_get_shortcode_view('mk_blog', 'components/title', true);
									echo mk_get_shortcode_view('mk_blog', 'components/excerpt', true, ['excerpt_length' => $view_params['excerpt_length'], 'full_content' => $view_params['full_content']]); 
									?>

									<?php
									echo do_shortcode( '[mk_button dimension="flat" corner_style="rounded" bg_color="'.$mk_options['skin_color'].'" btn_hover_bg="'.hexDarker($mk_options['skin_color'], 30).'" text_color="light" btn_hover_txt_color="#ffffff" size="medium" target="_self" align="none" url="' . esc_url( get_permalink() ) . '"]'.__('READ MORE', 'mk_framework').'[/mk_button]' );
									?>

									<div class="clearboth"></div>
								</div>

								<div class="clearboth"></div>
							</article>
					
						<?php endwhile;
					
						wp_reset_postdata();
					
					endif; ?>
					
				</section>
				
			</div>
		</div>
		
	</div>
	
</div>

<?php get_footer();