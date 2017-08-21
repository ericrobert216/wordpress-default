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
            if ($template_name == 'single-product/add-to-cart/variable.php') {

                $general_settings = get_option('zoo-cw-settings',true);

                if(!is_array($general_settings) || $general_settings['swatch'] == 0){
                    return $template;
                }

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
                
                    $this->load_product_page_assets();

                // check that the template is there in plugin or not.
                if ( file_exists( $plugin_path . $template_name ) )
                    $template = $plugin_path . $template_name;

                // return the default template.
                if ( ! $template )
                    $template = $_template;

            }

            // replace with our plugin template.
            return $template;
        }

        public function load_product_page_assets(){

            if(is_single()){
                wp_register_script('zoo-cw-single-product-page-swatches', ZOO_CW_JSPATH . "single-product-page-swatch.js",
                    array( 'jquery' ), ZOO_CW_VERSION , TRUE);

                wp_localize_script( 'zoo-cw-single-product-page-swatches', 'zoo_cw_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

                wp_enqueue_script('zoo-cw-single-product-page-swatches');

                wp_enqueue_style ( 'zoo-cw-single-product-page', ZOO_CW_CSSPATH . 'single-product-page.css' );
            }
        }

        public function prepare_singele_page_data($product, $attributes, $product_swatch_data_array) {

            $general_settings = get_option('zoo-cw-settings', true);

            foreach ($product_swatch_data_array as $attribute_name => $data) {
                if ($attribute_name == 'disabled') {
                    continue;
                }

                $attribute_enabled_options = $attributes[$attribute_name];

                $terms = wc_get_product_terms( $product->get_id(), $attribute_name, array( 'fields' => 'all' ) );
                $options_data = $data['options_data'];
                foreach ($terms as $term) {
                    if (in_array($term->slug,$attribute_enabled_options)) {
                        $options_data[$term->slug]['name'] = $term->name;
                        $options_data[$term->slug]['value'] = $term->slug;
                    } else {
                        unset($options_data[$term->slug]);
                    }
                }

                $product_swatch_data_array[$attribute_name]['options_data'] = $options_data;

                //render class
                $class_attribute = '';
                if ($data['display_type'] != 'default') {
                    $class_attribute .= ' zoo-cw-active zoo-cw-type-'.$data['display_type'];
                }
                $product_swatch_data_array[$attribute_name]['class_attribute'] = $class_attribute;

                $display_size = $data['display_size'];
                if ($data['display_size'] == 'default') {
                    $display_size = $general_settings['display_size'];
                }

                if ($display_size == 'custom') {
                    $custom_style = 'style="width: '.$general_settings['display_size_width'].'px;height: '.$general_settings['display_size_height'].'px;"';
                    $product_swatch_data_array[$attribute_name]['custom_style'] = $custom_style;
                }

                $display_shape = $data['display_shape'];
                if ($data['display_shape'] == 'default') {
                    $display_shape = $general_settings['display_shape'];
                    $product_swatch_data_array[$attribute_name]['display_shape'] = $display_shape;
                }

                $display_name_yn = $data['display_name_yn'];
                if ($data['display_name_yn'] == 'default') {
                    $display_name_yn = $general_settings['display_name'];
                    $product_swatch_data_array[$attribute_name]['display_name_yn'] = $display_name_yn;
                }

                $class_options = 'zoo-cw-option-display-size-'.$display_size;
                $class_options .= ' zoo-cw-option-display-shape-'.$display_shape;
                $product_swatch_data_array[$attribute_name]['class_options'] = $class_options;
            }

            return $product_swatch_data_array;
        }
    }



}

$zoo_clever_swatch_product_page = new Zoo_Clever_Swatch_Product_Page();