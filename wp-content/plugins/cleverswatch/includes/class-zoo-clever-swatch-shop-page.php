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

if( !class_exists( 'Zoo_Clever_Swatch_Shop_Page' ) ) {

    class Zoo_Clever_Swatch_Shop_Page {

        public function __construct() {
            $this->hook_action();
        }

        public function hook_action() {
            $general_settings = get_option('zoo-cw-settings', true);

            if ($general_settings['display_shop_page'] == 1) {
                add_action( 'woocommerce_after_shop_loop_item', array( $this,'zoo_cw_shop_page_add_swatch' ), 10, 3 );
                add_action( 'woocommerce_after_shop_loop', array( $this,'load_shop_page_assets' ), 10, 3 );
            }
        }

        public function zoo_cw_shop_page_add_swatch() {

            $post = get_post();

            $post_id = $post->ID;

            $product_swatch_data_array = get_post_meta($post_id, 'zoo_cw_product_swatch_data', true);

            if ($product_swatch_data_array != '') {


                $zoo_clever_swatch_product_page = new Zoo_Clever_Swatch_Product_Page();

                $product = wc_get_product( $post_id );

                $attributes = $product->get_variation_attributes()  ;

                $product_swatch_data_array = $zoo_clever_swatch_product_page->prepare_singele_page_data($product, $attributes, $product_swatch_data_array);

                require(ZOO_CW_TEMPLATES_PATH . 'shop-page-swatch-image.php');
            }

        }

        public function load_shop_page_assets() {
            wp_enqueue_style ( 'zoo-cw-single-product-page', ZOO_CW_CSSPATH . 'cleverswatch-style.css' );
            wp_enqueue_style ( 'jquery-ui-tooltip', '//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
            wp_enqueue_script('jquery-ui-tooltip');

            wp_register_script('zoo-cw-shop-page-swatches', ZOO_CW_JSPATH . "shop-page-swatch.js",
                array( 'jquery' ), ZOO_CW_VERSION , TRUE);

            wp_localize_script( 'zoo-cw-shop-page-swatches', 'zoo_cw_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

            wp_enqueue_script('zoo-cw-shop-page-swatches');
        }
    }
}

$zoo_clever_swatch_shop_page = new Zoo_Clever_Swatch_Shop_Page();