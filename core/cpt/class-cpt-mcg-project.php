<?php
/**
 * Projects CPT
 *
 * @since		1.0.0
 * @package		PL_MCG_PROJECT_PORTFOLIO_CPT
 * @subpackage	PL_MCG_PROJECT_PORTFOLIO_CPT/core/cpt
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class MCG_CPT_Project {
	
	public $post_type = 'mcg-project';
	
    public $post_label_singular = null;
    public $post_label_plural = null;
    public $post_labels = array();
	
	public $industry_sector_taxonomy_label_singular = null;
	public $industry_sector_taxonomy_label_plural = null;
	public $industry_sector_taxonomy_labels = array();
	
	public $technology_application_taxonomy_label_singular = null;
	public $technology_application_taxonomy_label_plural = null;
	public $technology_application_taxonomy_labels = array();
	
    public $icon = 'images-alt';
	public $supports = array( 'title', 'editor', 'excerpt', 'author', 'thumbnail' );
	
    public $post_args = array(
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
        'rewrite' => array(
            'slug' => 'project-portfolio',
            'with_front' => false,
            'feeds' => false,
            'pages' => true
        ),
    );
	
	public $industry_sector_taxonomy_args = array(
		'public' => true,
		'publically_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_in_rest' => true,
		'show_admin_column' => true,
		'hierarchical' => true,
	);
	
	public $technology_application_taxonomy_args = array(
		'public' => true,
		'publically_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_in_rest' => true,
		'show_admin_column' => true,
		'hierarchical' => true,
	);
	
	function __construct() {
		
        $this->post_label_singular = _x( 'Project', 'Projects Label Singular', 'mcg-project-portfolio-cpt' );
        $this->post_label_plural = _x( 'Projects', 'Projects Label Plural', 'mcg-project-portfolio-cpt' );
		
        $this->post_labels = array(
			'name'               => $this->post_label_plural,
			'singular_name'      => $this->post_label_singular,
			'menu_name'          => $this->post_label_plural,
			'name_admin_bar'     => $this->post_label_singular,
			'add_new'            => __( "Add New", 'mcg-project-portfolio-cpt' ),
			'add_new_item'       => sprintf( __( "Add New %s", 'mcg-project-portfolio-cpt' ), $this->post_label_singular ),
			'new_item'           => sprintf( __( "New %s", 'mcg-project-portfolio-cpt' ), $this->post_label_singular ),
			'edit_item'          => sprintf( __( "Edit %s", 'mcg-project-portfolio-cpt' ), $this->post_label_singular ),
			'view_item'          => sprintf( __( "View %s", 'mcg-project-portfolio-cpt' ), $this->post_label_singular ),
			'all_items'          => sprintf( __( "All %s", 'mcg-project-portfolio-cpt' ), $this->post_label_plural ),
			'search_items'       => sprintf( __( "Search %s", 'mcg-project-portfolio-cpt' ), $this->post_label_plural ),
			'parent_item_colon'  => sprintf( __( "Parent %s:", 'mcg-project-portfolio-cpt' ), $this->post_label_plural ),
			'not_found'          => sprintf( __( "No %s found.", 'mcg-project-portfolio-cpt' ), $this->post_label_plural ),
			'not_found_in_trash' => sprintf( __( "No %s found in Trash.", 'mcg-project-portfolio-cpt' ), $this->post_label_plural ),
        );
		
		$this->post_args['menu_icon'] = 'dashicons-' . $this->icon;
		$this->post_args['support'] = $this->supports;
		
        $this->post_args['labels'] = $this->post_labels;
		
		$this->industry_sector_taxonomy_label_singular = _x( 'Industry/Sector', 'Industry/Sector Taxonomy Label Singular', 'mcg-project-portfolio-cpt' );
		$this->industry_sector_taxonomy_label_plural = _x( 'Industries/Sectors', 'Industry/Sector Taxonomy Label Plural', 'mcg-project-portfolio-cpt' );
		
		$this->industry_sector_taxonomy_labels = array(
			'name' => $this->industry_sector_taxonomy_label_plural,
			'singular_name' => $this->industry_sector_taxonomy_label_singular,
			'menu_name' => $this->industry_sector_taxonomy_label_plural,
			'all_items' => sprintf( __( 'All %s', 'mcg-project-portfolio' ), $this->industry_sector_taxonomy_label_plural ),
			'edit_item' => sprintf( __( 'Edit %s', 'mcg-project-portfolio' ), $this->industry_sector_taxonomy_label_singular ),
			'view_item' => sprintf( __( 'View %s', 'mcg-project-portfolio' ), $this->industry_sector_taxonomy_label_singular ),
			'update_item' => sprintf( __( 'Update %s', 'mcg-project-portfolio' ), $this->industry_sector_taxonomy_label_singular ),
			'add_new_item' => sprintf( __( 'Add New %s', 'mcg-project-portfolio' ), $this->industry_sector_taxonomy_label_singular ),
			'new_item_name' => sprintf( __( 'New %s Name', 'mcg-project-portfolio' ), $this->industry_sector_taxonomy_label_singular ),
			'parent_item' => sprintf( __( 'Parent %s', 'mcg-project-portfolio' ), $this->industry_sector_taxonomy_label_singular ),
			'parent_item_colon' => sprintf( __( 'Parent %s:', 'mcg-project-portfolio' ), $this->industry_sector_taxonomy_label_singular ),
			'search_items' => sprintf( __( 'Search %s', 'mcg-project-portfolio' ), $this->industry_sector_taxonomy_label_plural ),
			'popular_items' => sprintf( __( 'Popular %s', 'mcg-project-portfolio' ), $this->industry_sector_taxonomy_label_plural ),
			'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'mcg-project-portfolio' ), $this->industry_sector_taxonomy_label_plural ),
			'add_or_remove_items' => sprintf( __( 'Add or Remove %s', 'mcg-project-portfolio' ), $this->industry_sector_taxonomy_label_plural ),
			'choose_from_most_used' => sprintf( __( 'Choose from the most used %s', 'mcg-project-portfolio' ), $this->industry_sector_taxonomy_label_plural ),
			'not_found' => sprintf( __( 'No %s found', 'mcg-project-portfolio' ), $this->industry_sector_taxonomy_label_plural ),
		);
		
		$this->industry_sector_taxonomy_args['label'] = $this->industry_sector_taxonomy_labels['name'];
		$this->industry_sector_taxonomy_args['labels'] = $this->industry_sector_taxonomy_labels;
		
		$this->technology_application_taxonomy_label_singular = _x( 'Technology/Application', 'Technology/Application Taxonomy Label Singular', 'mcg-project-portfolio-cpt' );
		$this->technology_application_taxonomy_label_plural = _x( 'Technologies/Applications', 'Technology/Application Taxonomy Label Plural', 'mcg-project-portfolio-cpt' );
		
		$this->technology_application_taxonomy_labels = array(
			'name' => $this->technology_application_taxonomy_label_plural,
			'singular_name' => $this->technology_application_taxonomy_label_singular,
			'menu_name' => $this->technology_application_taxonomy_label_plural,
			'all_items' => sprintf( __( 'All %s', 'mcg-project-portfolio' ), $this->technology_application_taxonomy_label_plural ),
			'edit_item' => sprintf( __( 'Edit %s', 'mcg-project-portfolio' ), $this->technology_application_taxonomy_label_singular ),
			'view_item' => sprintf( __( 'View %s', 'mcg-project-portfolio' ), $this->technology_application_taxonomy_label_singular ),
			'update_item' => sprintf( __( 'Update %s', 'mcg-project-portfolio' ), $this->technology_application_taxonomy_label_singular ),
			'add_new_item' => sprintf( __( 'Add New %s', 'mcg-project-portfolio' ), $this->technology_application_taxonomy_label_singular ),
			'new_item_name' => sprintf( __( 'New %s Name', 'mcg-project-portfolio' ), $this->technology_application_taxonomy_label_singular ),
			'parent_item' => sprintf( __( 'Parent %s', 'mcg-project-portfolio' ), $this->technology_application_taxonomy_label_singular ),
			'parent_item_colon' => sprintf( __( 'Parent %s:', 'mcg-project-portfolio' ), $this->industry_sector_taxonomy_label_singular ),
			'search_items' => sprintf( __( 'Search %s', 'mcg-project-portfolio' ), $this->technology_application_taxonomy_label_plural ),
			'popular_items' => sprintf( __( 'Popular %s', 'mcg-project-portfolio' ), $this->technology_application_taxonomy_label_plural ),
			'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'mcg-project-portfolio' ), $this->technology_application_taxonomy_label_plural ),
			'add_or_remove_items' => sprintf( __( 'Add or Remove %s', 'mcg-project-portfolio' ), $this->technology_application_taxonomy_label_plural ),
			'choose_from_most_used' => sprintf( __( 'Choose from the most used %s', 'mcg-project-portfolio' ), $this->technology_application_taxonomy_label_plural ),
			'not_found' => sprintf( __( 'No %s found', 'mcg-project-portfolio' ), $this->technology_application_taxonomy_label_plural ),
		);
		
		$this->technology_application_taxonomy_args['label'] = $this->technology_application_taxonomy_labels['name'];
		$this->technology_application_taxonomy_args['labels'] = $this->technology_application_taxonomy_labels;
		
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		
    }
	
	public function register_post_type() {
		
		register_post_type( $this->post_type, $this->post_args );
		
	}
	
	public function register_taxonomy() {
		
		register_taxonomy( 'mcg-project-industry-sector', $this->post_type, $this->industry_sector_taxonomy_args );
		register_taxonomy( 'mcg-project-tech-application', $this->post_type, $this->technology_application_taxonomy_args );
		
	}
	
}