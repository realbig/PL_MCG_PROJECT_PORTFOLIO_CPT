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
		
		add_filter( 'get_post_metadata', array( $this, 'get_post_metadata' ), 10, 4 );
		
		add_filter( 'mk_theme_page_header_title', array( $this, 'mk_theme_page_header_title' ) );
		
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
	
	/**
	 * Force-hide Breadcrumbs. We're doing our own
	 * 
	 * @param		null|array|string $value     Defaults to null. Return anything else to override the value
	 * @param		integer           $object_id Post ID
	 * @param		string            $meta_key  Meta Key
	 * @param		boolean           $single    Whether to return only the first value of the specified $meta_key
	 *                                                                                              
	 * @return 		null|array|string Value
	 */
	public function get_post_metadata( $value, $object_id, $meta_key, $single ) {
		
		if ( $meta_key == '_disable_breadcrumb' ) {
			
			if ( MCGPROJECTPORTFOLIOCPT()->cpt->post_type == get_post_type( $object_id ) ) {
				return 'false';
			}
			
		}
		
		return $value;
		
	}
	
	/**
	 * GG, Jupiter Theme. I'm thoroughly impressed.
	 * Replace Title in Header Area on Single Projects to be Case Studies like in the Mock.
	 * 
	 * @param		string $title Header Title
	 *                              
	 * @return		string Header Title
	 */
	public function mk_theme_page_header_title( $title ) {
		
		if ( is_single() && 
			MCGPROJECTPORTFOLIOCPT()->cpt->post_type == get_post_type() ) {
			return __( 'Case Studies', 'mcg-project-portfolio-cpt' );
		}
		
		return $title;
		
	}
	
}