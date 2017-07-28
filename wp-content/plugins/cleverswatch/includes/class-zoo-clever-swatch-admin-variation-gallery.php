<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * class for managing gallery images per variation.
 *
 * @class    Zoo_Clever_Swatch_Admin_Variation_Gallery
 * 
 * @version  1.0.0
 * @package  clever-swatch/includes
 * @category Class
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if( !class_exists( 'Zoo_Clever_Swatch_Admin_Variation_Gallery' ) ){
	
	class Zoo_Clever_Swatch_Admin_Variation_Gallery{
		
		private static $_instance;
		
		public static function getInstance() {
		
			if( !self::$_instance instanceof self )
				self::$_instance = new self;
		
			return self::$_instance;
		
		}
		
		public function __construct(){

			$general_settings = get_option('zoo-cw-settings',true);
				
			if(!is_array($general_settings)){
				return;
			}else{
				$is_gallery_enabled = isset($general_settings['pwg']) ? intval($general_settings['pwg']) : 1;
					
				if(!$is_gallery_enabled){
					return;
				}else{
					add_action( 'woocommerce_variation_options', array($this,'zoo_cw_add_variation_gallery_option'), 10, 3 );
					add_action( 'woocommerce_save_product_variation', array($this,'zoo_cw_save_variation_meta'), 10, 2 );
				}
			}
		}
		
		/**
		 * saving variation gallery details.
		 * 
		 * @since 1.0.0
		 */
		public function zoo_cw_save_variation_meta($post_id){
			
			$images_gallery = '';
			$images_gallery = $_POST['zoo-cw-variation-gallery'][ $post_id ];
			
			update_post_meta( $post_id, 'zoo-cw-variation-gallery', sanitize_text_field(
				$images_gallery) );
			
		}
		
		/**
		 * adding option for variation gallery.
		 * 
		 * @since 1.0.0
		 */
		public function zoo_cw_add_variation_gallery_option( $loop, $variation_data, $variation ){
		    ?>
			</br>
            <label><?php _e('Variation Gallery Images','clever-swatch') ?></label>
			<div class="zoo-cw-variation-gallery-wrapper">
				<div class="zoo-cw-variation-gallery-container product_images">
					<ul class="product_images ui-sortable">
					<?php echo $this->variation_gallery_output($variation->ID); ?>
					</ul>
					<input class="zoo-cw-variation-gallery" type="hidden" value="<?php echo get_post_meta( $variation->ID, 'zoo-cw-variation-gallery', true ) ?>" name="zoo-cw-variation-gallery[<?php echo $variation->ID; ?>]">
				</div>
				
				<div data-loop="<?php echo $loop; ?>" class="add-variation-gallery-image">
					<a href="#" data-text="Delete" data-delete="Delete image" data-update="Add to gallery" data-choose="Add Images to Product Variation"><?php _e('Add Variation Gallery Images','clever-swatch');?></a>
				</div>
			</div>
			<?php 
		}
		
		/**
		 * displaying gallery images variation wise.
		 * 
		 * @since 1.0.0
		 */
		public function variation_gallery_output($variation_id){
			
			$product_image_gallery = get_post_meta( $variation_id, 'zoo-cw-variation-gallery', true );

			$attachments = array_filter( explode( ',', $product_image_gallery ) );

			$update_meta = false;
			
			if ( ! empty( $attachments ) ) {
				foreach ( $attachments as $attachment_id ) {
					$attachment = wp_get_attachment_image( $attachment_id, 'thumbnail' );

					// if attachment is empty skip
					if ( empty( $attachment ) ) {
						$update_meta = true;

						continue;
					}
					echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
						' . $attachment . '
						<ul class="actions">
							<li><a href="javascrip:void(0)" class="zoo-cw-delete-gallery-image tips" data-tip="' . esc_attr__( 'Delete image', 'clever-swatch' ) . '">' . __( 'Delete', 'clever-swatch' ) . '</a></li>
						</ul>
					</li>';

					// rebuild ids to be saved
					$updated_gallery_ids[] = $attachment_id;
				}

				// need to update product meta to set new gallery ids
				if ( $update_meta ) {
					update_post_meta( $variation_id, 'zoo-cw-variation-gallery', implode( ',', $updated_gallery_ids ) );
				}
			}
		}
	}
}