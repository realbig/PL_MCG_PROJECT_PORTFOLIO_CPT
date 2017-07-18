<?php
/**
 * Projects Meta
 *
 * @since		1.0.0
 * @package		PL_MCG_PROJECT_PORTFOLIO_CPT
 * @subpackage	PL_MCG_PROJECT_PORTFOLIO_CPT/core/meta
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class MCG_Project_Meta {

	function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		
		add_action( 'wp_loaded', array( $this, 'add_jupiter_meta_box' ), 999 );
		
		add_action( 'save_post', array( $this, 'save_post' ) );

	}

	/**
	 * Add Meta Boxes for Projects
	 * 
	 * @access		public
	 * @since		1.0.0
	 * @return		void
	 */
	public function add_meta_box() {

		add_meta_box(
			'mcg-project-meta',
			_x( 'Project Meta', 'Project Meta Metabox Title', 'mcg-project-portfolio-cpt' ),
			array( $this, 'project_meta_metabox_content' ),
			'mcg-project',
			'normal',
			'high'
		);

		add_meta_box(
			'mcg-project-thumbnail',
			_x( 'Thumbnail Image', 'Project Thumbnail Metabox Title', 'mcg-project-portfolio-cpt' ),
			array( $this, 'project_thumbnail_metabox_content' ),
			'mcg-project',
			'side'
		);

	}

	/**
	 * Add Fields for the generic "Project Meta" Meta Box
	 * 
	 * @param		object $post    WP_Post Object
	 * @param		array  $metabox Meta Box $args
	 *                                  
	 * @access		public
	 * @since		1.0.0
	 * @return		void
	 */
	public function project_meta_metabox_content( $post, $metabox ) {
		
		?>

		<p>
			<strong><?php _e( 'Project Client', 'mcg-project-portfolio-cpt' ); ?></strong><br />
			<input type="text" name="project_client" class="widefat regular-text" value="<?php echo get_post_meta( $post->ID, 'project_client', true ); ?>" />
		</p>
		
		<p>
			<strong><?php _e( 'Project Grade', 'mcg-project-portfolio-cpt' ); ?></strong><br />
			<?php $project_grade = get_post_meta( $post->ID, 'project_grade', true ); ?>
			<select name="project_grade">
				<option value="" disabled<?php echo ( $project_grade === false || $project_grade == '' || (int) $project_grade < 0 || (int) $project_grade > 10 ) ? ' selected' : ''?>>
					<?php _e( 'Select a Grade', 'mcg-project-portfolio-cpt' ); ?>
				</option>
				<?php for ( $index = 0; $index <= 10; $index++ ) :  ?>
					<option value="<?php echo $index; ?>"<?php echo ( $project_grade !== '' && $index === (int) $project_grade ) ? ' selected' : ''; ?>>
						<?php echo $index; ?>
					</option>
				<?php endfor; ?>
			</select>
		</p>

		<p class="mcg-field-datepicker">
			
			<?php
		
				$format = get_option( 'date_format', 'F j, Y' );
				$value = get_post_meta( $post->ID, 'project_date', true );
				$default = current_time( $format );
				$preview = date( $format, strtotime( $value ? $value : $default ) );
		
			?>

			<label>
				<strong><?php _e( 'Project Date', 'mcg-project-portfolio-cpt' ); ?></strong><br />

				<input type="text" class="mcg-field-datepicker-preview" value="<?php echo $preview; ?>" />

				<input type="hidden" name="project_date" value="<?php echo $value ? $value : $default; ?>" />
					   
			</label>
			
		</p>

		<?php

	}

	/**
	 * Add Fields for the Project Thumbnail Meta Box
	 * 
	 * @param		object $post    WP_Post Object
	 * @param		array  $metabox Meta Box $args
	 *                                  
	 * @access		public
	 * @since		1.0.0
	 * @return		void
	 */
	public function project_thumbnail_metabox_content( $post, $metabox ) {
		
		$value = get_post_meta( $post->ID, 'project_thumbnail', true );

		if ( $media_item_src = wp_get_attachment_image_src( $value, $args['preview_size'] ) ) {
			$media_preview_url = $media_item_src[0];
		}
		else {
			$media_preview_url = wp_get_attachment_url( $value );
		}

		?>
		<div class="mcg-field-media">
			
			<div class="mcg-media-uploader" data-type="image" data-title="<?php _e( 'Choose a Thumbnail Image', 'mcg-project-portfolio-cpt'); ?>" data-button-text="<?php _e( 'Set Thumbnail Image', 'mcg-project-portfolio-cpt' ); ?>">

				<img src="<?php echo $value ? esc_attr( $media_preview_url ) : '' ?>" class="image-preview" data-placeholder="" />

				<br/>

				<input type="button" class="button upload-media" value="<?php _e( 'Upload Thumbnail Image', 'mcg-project-portfolio-cpt' ); ?>" <?php echo $value ? 'style="display: none;"' : ''; ?> />
				
				<input type="button" class="button remove-media" value="<?php _e( 'Remove Thumbnail Image', 'mcg-project-portfolio-cpt' ); ?>" <?php echo ! $value ? 'style="display: none;"' : ''; ?> />

				<input type="hidden" name="project_thumbnail" value="<?php echo esc_attr( $value ); ?>" class="media-id" />
				
			</div>

		</div>

		<?php

	}
	
	/**
	 * Adds ability to override global styling. Included as part of Jupiter, but with no way to Filter applicable Post Types. So I copied it.
	 * 
	 * @access		public
	 * @since		1.0.0
	 * @return		void
	 */
	public function add_jupiter_meta_box() {
		
		$config = array(
			'title' => sprintf('%s Styling Options', THEME_NAME) ,
			'id' => 'mk-metaboxes-styling',
			'pages' => array(
				MCGPROJECTPORTFOLIOCPT()->cpt->post_type,
			) ,
			'callback' => '',
			'context' => 'normal',
			'priority' => 'default'
		);
		$options = array(

			array(
				"name" => __("Override Global Settings", "mk_framework") ,
				"desc" => __("You should enable this option if you want to override global background values defined in Theme Options.", "mk_framework") ,
				"id" => "_enable_local_backgrounds",
				"default" => 'false',
				"type" => "toggle"
			) ,

			array(
				"name" => __("Header Styles", "mk_framework") ,
				"desc" => __("Using this option you can choose your header style, elements align and toggle off/on header toolbar.", "mk_framework") ,
				"id" => "theme_header_style",
				"default" => '1',
				"type" => 'header_styles',
				"dependency" => array(
					'element' => "_enable_local_backgrounds",
					'value' => array(
						'true',
					)
				) ,
			) ,
			array(
				"id" => "theme_header_align",
				"default" => "left",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "theme_toolbar_toggle",
				"default" => "true",
				"type" => 'hidden_input'
			) ,

			array(
				"name" => __("Upload Logo (Dark & default)", "mk_framework") ,
				"desc" => __("This logo will be used when transparent header is enabled and your header skin is dark.", "mk_framework") ,
				"id" => "logo",
				"default" => "",
				"type" => "upload",
				"dependency" => array(
					'element' => "_enable_local_backgrounds",
					'value' => array(
						'true',
					)
				) ,
			) ,
			array(
				"name" => __("Upload Logo (Light Skin)", "mk_framework") ,
				"desc" => __("This logo will be used when transparent header is enabled and your header is light skin.", "mk_framework") ,
				"id" => "light_logo",
				"default" => "",
				"type" => "upload",
				"dependency" => array(
					'element' => "_enable_local_backgrounds",
					'value' => array(
						'true',
					)
				) ,
			) ,
			array(
				"name" => __("Upload Logo (Header Sticky State)", "mk_framework") ,
				"desc" => __("Use this option upload the sticky header logo.", "mk_framework") ,
				"id" => "sticky_header_logo",
				"default" => "",
				"type" => "upload",
				"dependency" => array(
					'element' => "_enable_local_backgrounds",
					'value' => array(
						'true',
					)
				) ,
			) ,
			array(
				"name" => __("Upload Logo (Mobile Version)", "mk_framework") ,
				"desc" => __("Use this option to change your logo for mobile devices if your logo width is quite long to fit in mobile device screen.", "mk_framework") ,
				"id" => "responsive_logo",
				"default" => "",
				"type" => "upload",
				"dependency" => array(
					'element' => "_enable_local_backgrounds",
					'value' => array(
						'true',
					)
				) ,
			) ,

			array(
				"name" => __("Transparent Header", "mk_framework") ,
				"desc" => __("You can Enable/Disable transparent header capability using this option.", "mk_framework") ,
				"id" => "_transparent_header",
				"default" => 'false',
				"type" => "toggle",
				"dependency" => array(
					'element' => "_enable_local_backgrounds",
					'value' => array(
						'true',
					)
				) ,
			) ,
			array(
				"name" => __("Enable Transparent Header Skin", "mk_framework") ,
				"desc" => __("If this option is enabled, transparent header background will be removed, main navigation as well as other header elements will be controlled by below option. Edge Slider and Edge One Pager shortcodes slides will also be able to control header skin. If disabled none of these will be applied to header background and its elements.", "mk_framework") ,
				"id" => "_trans_header_remove_bg",
				"default" => 'true',
				"type" => "toggle",
				"dependency" => array(
					'element' => "_enable_local_backgrounds",
					'value' => array(
						'true',
					)
				) ,
			) ,
			array(
				"name" => __("Transparent header Skin", 'mk_framework') ,
				"desc" => __("Use this option to decide about the skin of transparent header.", 'mk_framework') ,
				"id" => "_transparent_header_skin",
				"default" => "light",
				"options" => array(
					"light" => __("Light", "mk_framework") ,
					"dark" => __("Dark", "mk_framework") ,
				) ,
				"type" => "select",
				"dependency" => array(
					'element' => "_enable_local_backgrounds",
					'value' => array(
						'true',
					)
				) ,
			) ,

			array(
				"name" => __('Transparent Header Bottom Border Color', 'mk_framework') ,
				"desc" => __("This border will appear in the bottom of the transparent header. Please note that this options has nothing to do with \"header bottom border\" and \"Header Border Bottom Color\" and this border will only appear in transparent header (will disappear in sticky header).", "mk_framework") ,
				"id" => "_trans_header_border_bottom",
				"default" => "",
				"type" => "color",
				"dependency" => array(
					'element' => "_enable_local_backgrounds",
					'value' => array(
						'true',
					)
				) ,
			) ,
			array(
				"name" => __("Sticky Header Offset", "mk_framework") ,
				"desc" => __("Set this option to decide when the sticky state of header will trigger. This option does not apply to header style No2.", "mk_framework") ,
				"id" => "_sticky_header_offset",
				"default" => 'header',
				"options" => array(
					"header" => __('Header height', "mk_framework") ,
					"25%" => __('25% Of Viewport', "mk_framework") ,
					"30%" => __('30% Of Viewport', "mk_framework") ,
					"40%" => __('40% Of Viewport', "mk_framework") ,
					"50%" => __('50% Of Viewport', "mk_framework") ,
					"60%" => __('60% Of Viewport', "mk_framework") ,
					"70%" => __('70% Of Viewport', "mk_framework") ,
					"80%" => __('80% Of Viewport', "mk_framework") ,
					"90%" => __('90% Of Viewport', "mk_framework") ,
					"100%" => __('100% Of Viewport', "mk_framework") ,
				) ,
				"type" => "select",
				"dependency" => array(
					'element' => "_enable_local_backgrounds",
					'value' => array(
						'true',
					)
				) ,
			) ,

			array(
				"name" => __("Choose between boxed and full width layout", 'mk_framework') ,
				"desc" => __("Choose between a full or a boxed layout to set how your website's layout will look like.", 'mk_framework') ,
				"id" => "background_selector_orientation",
				"default" => "full_width_layout",
				"options" => array(
					"boxed_layout" => 'boxed-layout',
					"full_width_layout" => 'full-width-layout'
				) ,
				"type" => "visual_selector",
				"dependency" => array(
					'element' => "_enable_local_backgrounds",
					'value' => array(
						'true',
					)
				) ,
			) ,

			array(
				"name" => __("Boxed Layout Outer Shadow Size", "mk_framework") ,
				"desc" => __("You can have a outer shadow around the box. using this option you in can modify its range size", "mk_framework") ,
				"id" => "boxed_layout_shadow_size",
				"default" => "0",
				"min" => "0",
				"max" => "60",
				"step" => "1",
				"unit" => 'px',
				"type" => "range",
				"dependency" => array(
					'element' => "_enable_local_backgrounds",
					'value' => array(
						'true',
					)
				) ,
			) ,

			array(
				"name" => __("Boxed Layout Outer Shadow Intensity", "mk_framework") ,
				"desc" => __("determines how darker the shadow to be.", "mk_framework") ,
				"id" => "boxed_layout_shadow_intensity",
				"default" => "0",
				"min" => "0",
				"max" => "1",
				"step" => "0.01",
				"unit" => 'alpha',
				"type" => "range",
				"dependency" => array(
					'element' => "_enable_local_backgrounds",
					'value' => array(
						'true',
					)
				) ,
			) ,

			array(
				"name" => __("Background color & texture", 'mk_framework') ,
				"desc" => __("Please click on the different sections to modify their backgrounds.", 'mk_framework') ,
				"id" => 'general_backgounds',
				"type" => "general_background_selector",
				"dependency" => array(
					'element' => "_enable_local_backgrounds",
					'value' => array(
						'true',
					)
				) ,
			) ,

			array(
				"id" => "body_color",
				"default" => "",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "body_color_gradient",
				"default" => "single",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "body_color_2",
				"default" => "",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "body_color_gradient_style",
				"default" => "linear",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "body_color_gradient_angle",
				"default" => "vertical",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "body_image",
				"default" => "",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "body_size",
				"default" => "false",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "body_position",
				"default" => "",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "body_attachment",
				"default" => "",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "body_repeat",
				"default" => "",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "body_source",
				"default" => "no-image",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "body_parallax",
				"default" => "false",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "page_color",
				"default" => "",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "page_color_gradient",
				"default" => "single",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "page_color_2",
				"default" => "",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "page_color_gradient_style",
				"default" => "linear",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "page_color_gradient_angle",
				"default" => "vertical",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "page_image",
				"default" => "",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "page_size",
				"default" => "false",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "page_position",
				"default" => "",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "page_attachment",
				"default" => "",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "page_repeat",
				"default" => "",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "page_source",
				"default" => "no-image",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "page_parallax",
				"default" => "false",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "header_color",
				"default" => "",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "header_color_gradient",
				"default" => "single",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "header_color_2",
				"default" => "",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "header_color_gradient_style",
				"default" => "linear",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "header_color_gradient_angle",
				"default" => "vertical",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "header_size",
				"default" => "false",
				"type" => 'hidden_input'
			) ,    
			array(
				"id" => "header_image",
				"default" => "",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "header_position",
				"default" => "",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "header_attachment",
				"default" => "",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "header_repeat",
				"default" => "",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "header_source",
				"default" => "no-image",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "header_parallax",
				"default" => "false",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "banner_color",
				"default" => "",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "banner_color_gradient",
				"default" => "single",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "banner_color_2",
				"default" => "",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "banner_color_gradient_style",
				"default" => "linear",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "banner_color_gradient_angle",
				"default" => "vertical",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "banner_image",
				"default" => "",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "banner_size",
				"default" => "false",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "banner_position",
				"default" => "",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "banner_attachment",
				"default" => "",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "banner_repeat",
				"default" => "",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "banner_source",
				"default" => "no-image",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "banner_parallax",
				"default" => "false",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "footer_color",
				"default" => "",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "footer_color_gradient",
				"default" => "single",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "footer_color_2",
				"default" => "",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "footer_color_gradient_style",
				"default" => "linear",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "footer_color_gradient_angle",
				"default" => "vertical",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "footer_image",
				"default" => "",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "footer_size",
				"default" => "false",
				"type" => 'hidden_input'
			) ,    
			array(
				"id" => "footer_position",
				"default" => "",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "footer_attachment",
				"default" => "",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "footer_repeat",
				"default" => "",
				"type" => 'hidden_input'
			) ,

			array(
				"id" => "footer_source",
				"default" => "no-image",
				"type" => 'hidden_input'
			) ,
			array(
				"id" => "footer_parallax",
				"default" => "false",
				"type" => 'hidden_input'
			) ,

			array(
				"name" => __('Page Title Color', 'mk_framework') ,
				"desc" => __("You can set the page title text color here.", "mk_framework") ,
				"id" => "_page_title_color",
				"default" => "",
				"type" => "color",
				"dependency" => array(
					'element' => "_enable_local_backgrounds",
					'value' => array(
						'true',
					)
				) ,
			) ,

			array(
				"name" => __('Page Subtitle Color', 'mk_framework') ,
				"desc" => __("You can set the page subtitle text color here.", "mk_framework") ,
				"id" => "_page_subtitle_color",
				"default" => "",
				"type" => "color",
				"dependency" => array(
					'element' => "_enable_local_backgrounds",
					'value' => array(
						'true',
					)
				) ,
			) ,

			array(
				"name" => __("Breadcrumb Skin", "mk_framework") ,
				"desc" => __("You can set breadcrumbs skin for dark or light backgrounds.", "mk_framework") ,
				"id" => "_breadcrumb_skin",
				"default" => '',
				"options" => array(
					"light" => __('For Light Background', "mk_framework") ,
					"dark" => __('For Dark Background', "mk_framework")
				) ,
				"type" => "select",
				"dependency" => array(
					'element' => "_enable_local_backgrounds",
					'value' => array(
						'true',
					)
				) ,
			) ,

			array(
				"name" => __('Header Border Bottom Color', 'mk_framework') ,
				"desc" => __("You can set the color of bottom border of banner section.", "mk_framework") ,
				"id" => "_banner_border_color",
				"default" => "",
				"type" => "color",
				"dependency" => array(
					'element' => "_enable_local_backgrounds",
					'value' => array(
						'true',
					)
				) ,
			)
		);
		new mkMetaboxesGenerator($config, $options);
		
	}
	
	/**
	 * Save our Meta Fields
	 * 
	 * @param		integer $post_id Post ID
	 *                               
	 * @access		public
	 * @since		1.0.0
	 * @return		integer Post ID
	 */
	public function save_post( $post_id ) {
		
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
		
		if ( MCGPROJECTPORTFOLIOCPT()->cpt->post_type !== get_post_type( $post_id ) ) return $post_id;
		
		$fields = array(
			'project_client',
			'project_grade',
			'project_date',
			'project_thumbnail',
		);
		
		foreach ( $fields as $field ) {
			
			$value = $_POST[ $field ];
			
			update_post_meta( $post_id, $field, $_POST[ $field ] );
			
		}
		
	}

}