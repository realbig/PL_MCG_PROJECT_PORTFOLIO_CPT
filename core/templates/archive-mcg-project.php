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
    'grid_image_height' => '140',
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

							<article id="<?php the_ID(); ?>" class="mk-blog-modern-item mk-isotop-item any-post-type project-row">
								
								<?php
								$media_atts = array(
									'image_size'    => $view_params['image_size'],
									'image_width'   => '140px',
									'image_height'  => '140px',
									'lazyload'      => $view_params['lazyload'],
									'disable_lazyload'      => $view_params['disable_lazyload'],
									'post_type'     => 'image',
									//'image_quality' => $view_params['image_quality']
								);
								?>
								
								<div class="rbm-col-small-12 rbm-col-medium-2 project-image">
									
									<?php
									
									// This originally ran a Shortcode as part of the Theme, but it only worked for the Featured Image.
									// I copied it from the Parent Theme, cleaned up the code a tad, and changed it to not be specific to the Featured Image. Otherwise it is 100% the same as it is in the Parent Theme
									
									$thumbnail_id = get_post_meta( get_the_ID(), 'project_thumbnail', true );
									
									$image_src_array = wp_get_attachment_image_src( $thumbnail_id, 'full' );

									$image_permalink = isset( $view_params['single_post'] ) ? $image_src_array[0] : esc_url( get_permalink() );
									$image_permalink_class = isset( $view_params['single_post'] ) ? 'mk-lightbox' : '';

									// Do not output random placeholder images in single post if the post does not have a featured image!
									$dummy = isset( $view_params['single_post'] ) ? false : true;

									$featured_image_src = Mk_Image_Resize::resize_by_id_adaptive( $thumbnail_id, $view_params['image_size'], $view_params['image_width'], $view_params['image_height'], $crop = false, $dummy );
									
									$image_size_atts = Mk_Image_Resize::get_image_dimension_attr( $thumbnail_id, $view_params['image_size'], $view_params['image_width'], $view_params['image_height'] );
									
									if ( ! Mk_Image_Resize::is_default_thumb( $image_src_array[0] ) ) : ?>
            
									<div class="featured-image">
										
										<a class="full-cover-link '<?php echo $image_permalink_class; ?>" title="<?php the_title_attribute(); ?>" href="<?php echo $image_permalink; ?>">&nbsp;</a>
										
										<img class="blog-image" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>" src="<?php echo $featured_image_src['dummy']; ?>" <?php echo $featured_image_src['data-set']; ?> width="<?php echo esc_attr( $image_size_atts['width'] ); ?>" height="<?php echo esc_attr( $image_size_atts['height'] ); ?>" itemprop="image" />
            
										<div class="image-hover-overlay"></div>
										
            							<div class="post-type-badge" href="<?php echo esc_url( get_permalink() ); ?>">
											<?php echo Mk_SVG_Icons::get_svg_icon_by_class_name( false, 'mk-li-' . $view_params['post_type'], 48 ); ?>
										</div>
            						</div>
									
									<?php endif; ?>
									
								</div>

								<div class="rbm-col-small-12 rbm-col-medium-8 project-excerpt">
									<?php
									
									echo mk_get_shortcode_view('mk_blog', 'components/title', true);
									echo mk_get_shortcode_view('mk_blog', 'components/excerpt', true, ['excerpt_length' => $view_params['excerpt_length'], 'full_content' => $view_params['full_content']]); 
									?>
									
								</div>
								
								<div class="rbm-col-small-12 rbm-col-medium-2 project-read-more">
									
									<div class="mk-gradient-button fullwidth-false project-button">
										<a href="<?php the_permalink(); ?>" class="mk-button mk-button--dimension-two mk-button--size-medium mk-button--corner-rounded dark-skin" target="_self" title="<?php _e( 'View Project', 'mcg-project-portfolio-cpt' ); ?>">
											<span class="text"><?php _e( 'View Project &raquo;', 'mcg-project-portfolio-cpt' ); ?></span>
											<i class="darker-background"></i>
										</a>
									</div>
									
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