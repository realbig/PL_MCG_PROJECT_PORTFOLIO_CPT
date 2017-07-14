<?php
/**
 * Projects Meta
 *
 * @since		1.0.0
 * @package		PL_MCG_PROJECT_PORTFOLIO_CPT
 * @subpackage	PL_MCG_PROJECT_PORTFOLIO_CPT/core/cpt
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class MCG_Project_Meta {

	function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );

	}

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

	public function project_meta_metabox_content() {



	}

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

				<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="media-id" />
				
			</div>

		</div>

		<?php

	}

}