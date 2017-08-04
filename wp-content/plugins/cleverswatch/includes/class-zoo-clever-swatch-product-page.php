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

                wp_localize_script( 'zoo-cw-single-product-page-swatches', 'zoo_cw_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

                wp_enqueue_script('zoo-cw-single-product-page-swatches');

                wp_enqueue_style ( 'zoo-cw-single-product-page', ZOO_CW_CSSPATH . 'single-product-page.css' );
            }
        }

        public function prepare_singele_page_data($product, $attributes, $product_swatch_data_array) {
            $array_data = array();

            if ($product_swatch_data_array['disabled'] == 1) {
                return array();
            }

//            if ( $product && taxonomy_exists( $attribute ) ) {
//                // Get terms if this is a taxonomy - ordered. We need the names too.
//                $terms = wc_get_product_terms($product->get_id(), $attribute, array('fields' => 'all'));
//            }


            $attribute_keys = array_keys( $attributes );

            $attributes	=	$product->get_variation_attributes();
            echo('<pre/>');
//            var_dump('$product_swatch_data_array');
//            var_dump($product_swatch_data_array);

            foreach ( $product_swatch_data_array as $product_swatch_name => $product_swatch_data ) {
                if ($product_swatch_name == 'disabled') {
                    continue;
                }

                $options = array();
                var_dump('$product_swatch_name');
                $attribute_key = $product_swatch_name;
                var_dump($product_swatch_data['options_data']);

                if ( $product && taxonomy_exists( $attribute_key ) ) {
                    // Get terms if this is a taxonomy - ordered. We need the names too.
                    $swatch_options_data = wc_get_product_terms($product->get_id(), $attribute_key, array('fields' => 'all'));
                }
                var_dump($swatch_options_data[0]);

                die;
                foreach ($swatch_options_data as $option_data) {
                    $display_type = $product_swatch_data['options_data'][$option_data->slug]['dt'];
                    if ($display_type == 0) {
                        $options[] = array(
                            'name' => $option_data->name,
                            'slug' => $option_data->slug,
                            'display_type' => $display_type,
                            'color' => $product_swatch_data['options_data'],
                        );
                    }

                }

                $array_data[] = array(
                    'label' => $product_swatch_data['label'],
                    'name' => $product_swatch_name,
                    'display_type' => $product_swatch_data['dt'],
                    'display_size' => array(
                        'width' => $product_swatch_data['ds']['ds'],
                        'height' => $product_swatch_data['ds']['ds2']
                        ),
                    'display_name_yn' => $product_swatch_data['dn'],
                    'option' => array(
                        'width' => $product_swatch_data['ds']['ds'],
                        'height' => $product_swatch_data['ds']['ds2']
                    ),
                );
            }
            die;

            echo('<pre/>');
            var_dump($product_swatch_data_array);
            var_dump($attributes);
            die;


            return $array_data;
        }
    }



}

$zoo_clever_swatch_product_page = new Zoo_Clever_Swatch_Product_Page();