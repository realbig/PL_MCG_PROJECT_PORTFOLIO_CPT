<?php
/**
 * Single Template for Projects
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

global $mk_options; 

get_header();

Mk_Static_Files::addAssets( 'mk_blog' );

$blog_style = 'blog-style-' . mk_get_blog_single_style();
$blog_type = 'blog-post-type-' . mk_get_blog_single_type();

?>

<div id="theme-page" class="master-holder <?php echo $blog_type; ?> <?php echo $blog_style; ?> clearfix" itemscope="itemscope" itemtype="https://schema.org/Blog">

	<div class="master-holder-bg-holder">
		<div id="theme-page-bg" class="master-holder-bg js-el"></div>
	</div>

	<div class="mk-main-wrapper-holder">

		<div id="mk-page-id-<?php echo get_the_ID(); ?>" class="theme-page-wrapper mk-main-wrapper mk-grid full-layout  ">
			<div class="theme-content " itemprop="mainEntityOfPage">

				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

					<article id="<?php the_ID(); ?>" <?php post_class( array( 'mk-blog-single' ) ); ?> <?php echo get_schema_markup( 'blog_posting' ); ?>>

						<?php

						do_action( 'blog_single_before_the_content' );

						mk_get_view( 'blog/components', 'blog-single-content' );

						do_action( 'blog_single_after_the_content' ); 

						?>

					</article>

				<?php endwhile; ?>
				
			</div>
		</div>
		
	</div>
	
</div>

<?php get_footer();