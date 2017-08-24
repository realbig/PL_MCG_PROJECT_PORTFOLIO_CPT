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
		
		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
		
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		
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
			
			wp_enqueue_script( 'mcg-project-portfolio-cpt' );
			
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
	 * Replace Title in Header Area on Single Projects to be Projects
	 * 
	 * @param		string $title Header Title
	 *     
	 * @access		public
	 * @since		1.0.0
	 * @return		string Header Title
	 */
	public function mk_theme_page_header_title( $title ) {
		
		if ( MCGPROJECTPORTFOLIOCPT()->cpt->post_type == get_post_type() ) {
			return __( 'Projects', 'mcg-project-portfolio-cpt' );
		}
		
		return $title;
		
	}
	
	/**
	 * Modify WP_Query for Project Archives as appropriate
	 * 
	 * @param		object $wp_query WP_Query Object
	 *                                   
	 * @access		public
	 * @since		1.0.0
	 * @return		void
	 */
	public function pre_get_posts( $wp_query ) {
		
		if ( ! $wp_query->is_archive && 
			$wp_query->query_vars->post_type !== MCGPROJECTPORTFOLIOCPT()->cpt->post_type ) return;
		
		if ( isset( $_REQUEST['project_category'] ) &&
			! empty( $_REQUEST['project_category'] ) ) {
			
			$wp_query->set( 'tax_query', array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'mcg-project-industry-sector',
					'field' => 'slug',
					'terms' => $_REQUEST['project_category'],
				),
				array(
					'taxonomy' => 'mcg-project-tech-application',
					'field' => 'slug',
					'terms' => $_REQUEST['project_category'],
				),
			) );
			
		}
		
		// Highest Menu Order Number First, then by Title A-Z
		$wp_query->set( 'orderby', array(
			'menu_order' => 'DESC',
			'title' => 'ASC',
		) );
		
		$wp_query->set( 'posts_per_page', 12 );
		
	}
	
	/**
	 * Register Project Footer Widget Areas
	 * 
	 * @access		public
	 * @since		1.0.0
	 * @return		void
	 */
	public function widgets_init() {
		
		// Footer
		$footer_columns = 5;
		for ( $index = 0; $index < $footer_columns; $index++ ) {
			register_sidebar(
				array(
					'name'          =>  'Project Footer ' . ( $index + 1 ),
					'id'            =>  'mcg-project-footer-' . ( $index + 1 ),
					'description'   =>  sprintf( __( 'This is Project Footer Widget Area %d', 'mcg-project-portfolio-cpt' ), ( $index + 1 ) ),
					'before_widget' =>  '<aside id="%1$s" class="widget %2$s">',
					'after_widget'  =>  '</aside>',
					'before_title'  =>  '<h3 class="widget-title">',
					'after_title'   =>  '</h3>',
				)
			);
		}
		
	}
	
}