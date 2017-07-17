<?php
/**
 * Redirects Project Templates if there's no Theme Override
 *
 * @since		1.0.0
 * @package		PL_MCG_PROJECT_PORTFOLIO_CPT
 * @subpackage	PL_MCG_PROJECT_PORTFOLIO_CPT/core/templates
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class MCG_Project_Template_Redirects {
	
	function __construct() {
		
		add_filter( 'single_template', array( $this, 'single_template' ) );
		
		add_filter( 'archive_template', array( $this, 'archive_template' ) );
		
	}
	
	public function single_template( $template ) {
		
		global $post;
		
		if ( MCGPROJECTPORTFOLIOCPT()->cpt->post_type == $post->post_type ) {
			
			if ( $theme_template = locate_template( 'single-' . $post->post_type . '.php', false, false ) ) return $theme_template;
			
			return PL_MCG_PROJECT_PORTFOLIO_CPT_DIR . 'core/templates/single-' . $post->post_type . '.php';
			
		}
		
		return $template;
		
	}
	
	public function archive_template( $template ) {
		
		global $post;
		
		if ( MCGPROJECTPORTFOLIOCPT()->cpt->post_type == $post->post_type ) {
			
			if ( $theme_template = locate_template( 'archive-' . $post->post_type . '.php', false, false ) ) return $theme_template;
			
			return PL_MCG_PROJECT_PORTFOLIO_CPT_DIR . 'core/templates/archive-' . $post->post_type . '.php';
			
		}
		
		return $template;
		
	}
	
}