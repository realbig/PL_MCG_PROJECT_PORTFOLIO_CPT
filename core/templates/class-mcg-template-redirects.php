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
	
	public function get_post_metadata( $metadata, $object_id, $meta_key, $single ) {
		
		if ( $meta_key == '_disable_breadcrumb' ) {
			
			if ( MCGPROJECTPORTFOLIOCPT()->cpt->post_type == get_post_type( $object_id ) ) {
				return 'false';
			}
			
		}
		
		return $metadata;
		
	}
	
}