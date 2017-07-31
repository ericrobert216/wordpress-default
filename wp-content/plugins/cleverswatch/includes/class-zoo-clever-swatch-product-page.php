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

if( !class_exists( 'Zoo_Clever_Swatch_Product_Page' ) ) {

    class Zoo_Clever_Swatch_Product_Page {

        public function __construct() {

            add_filter( 'woocommerce_locate_template', array( $this,'load_template' ), 10, 3 );
        }

        public function load_template($template, $template_name, $template_path) {

            global $woocommerce;

            $_template = $template;
            if ( ! $template_path ) $template_path = $woocommerce->template_url;
            $plugin_path  = ZOO_CW_TEMPLATES_PATH . 'woocommerce/';

            // check the template is available in theme or not.
            $template = locate_template(
                array(
                    $template_path . $template_name,
                    $template_name
                )
            );

            if ($template_name == 'single-product/add-to-cart/variable.php') {
                $this->load_product_page_assests();
            }

            // check that the template is there in plugin or not.
            if ( file_exists( $plugin_path . $template_name ) )
                $template = $plugin_path . $template_name;

            // return the default template.
            if ( ! $template )
                $template = $_template;

            // replace with our plugin template.
            return $template;
        }

        public function load_product_page_assests(){

            if(is_single()){
                wp_register_script('zoo-cw-single-product-page-swatches', ZOO_CW_JSPATH . "single-product-page-swatch.js",
                    array( 'jquery' ), ZOO_CW_VERSION , TRUE);

                wp_localize_script( 'zoo-cw-single-product-page-swatches', 'ced_vm_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

                wp_enqueue_script('zoo-cw-single-product-page-swatches');

                wp_enqueue_style ( 'zoo-cw-single-product-page', ZOO_CW_CSSPATH . 'single-product-page.css' );
            }
        }
    }
}

$zoo_clever_swatch_product_page = new Zoo_Clever_Swatch_Product_Page();