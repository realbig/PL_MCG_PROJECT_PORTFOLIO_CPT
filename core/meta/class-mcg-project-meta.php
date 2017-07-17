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
			'normal'
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