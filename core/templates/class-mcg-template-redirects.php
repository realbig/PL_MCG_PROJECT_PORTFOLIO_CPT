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
		
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		
		add_filter( 'single_template', array( $this, 'single_template' ) );
		
		add_filter( 'archive_template', array( $this, 'archive_template' ) );
		
		add_filter( 'get_post_metadata', array( $this, 'get_post_metadata' ), 10, 4 );
		
		add_filter( 'mk_theme_page_header_title', array( $this, 'mk_theme_page_header_title' ) );
		
	}
	
	/**
	 * Add our CSS to the Projects Pages
	 * 
	 * @access		public
	 * @since		1.0.0
	 * @return		void
	 */
	public function wp_enqueue_scripts() {
		
		if ( ! is_admin() &&
			MCGPROJECTPORTFOLIOCPT()->cpt->post_type == get_post_type() ) {
		
			wp_enqueue_style( 'mcg-project-portfolio-cpt' );
			
		}
		
	}
	
	/**
	 * Include WP Template File for Single Projects from the Plugin Directory. This can be overridden by the Theme
	 * 
	 * @param		string $template Single Template File Path determined automatically by WordPress
	 *        
	 * @access		public
	 * @since		1.0.0
	 * @return		string Template Path
	 */
	public function single_template( $template ) {
		
		global $post;
		
		if ( MCGPROJECTPORTFOLIOCPT()->cpt->post_type == $post->post_type ) {
			
			$theme_template = substr( $template, strrpos( $template, '/' ) + 1 );
			
			// If the Template Path used is not single.php (AKA, not default fallback), then use that. It is an override.
			if ( $theme_template !== 'single.php' ) return $template;
			
			return PL_MCG_PROJECT_PORTFOLIO_CPT_DIR . 'core/templates/single-' . $post->post_type . '.php';
			
		}
		
		return $template;
		
	}
	
	/**
	 * Include WP Template File for Projects Archives from the Plugin Directory. This can be overridden by the Theme
	 * 
	 * @param		string $template Archive Template File Path determined automatically by WordPress
	 *                                                                                 
	 * @access		public
	 * @since		1.0.0
	 * @return		string Template Path
	 */
	public function archive_template( $template ) {
		
		global $post;
		
		if ( MCGPROJECTPORTFOLIOCPT()->cpt->post_type == $post->post_type ) {
			
			$theme_template = substr( $template, strrpos( $template, '/' ) + 1 );
			
			// If the Template Path used is not archive.php (AKA, not default fallback), then use that. It is an override.
			if ( $theme_template !== 'archive.php' ) return $template;
			
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
	 * @access		public
	 * @since		1.0.0
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
	 * @access		public
	 * @since		1.0.0
	 * @return		string Header Title
	 */
	public function mk_theme_page_header_title( $title ) {
		
		if ( MCGPROJECTPORTFOLIOCPT()->cpt->post_type == get_post_type() ) {
			return __( 'Case Studies', 'mcg-project-portfolio-cpt' );
		}
		
		return $title;
		
	}
	
}