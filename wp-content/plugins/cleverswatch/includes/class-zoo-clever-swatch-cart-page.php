<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * class for managing gallery images per variation.
 *
 * @class    Zoo_Clever_Swatch_Cart_Page
 *
 * @version  1.0.0
 * @package  clever-swatch/includes
 * @category Class
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if( !class_exists( 'Zoo_Clever_Swatch_Cart_Page' ) ) {

    class Zoo_Clever_Swatch_Cart_Page {

        public function __construct() {

            add_filter( 'woocommerce_cart_item_name', array( $this,'zoo_wc_render_product_name_with_swatch' ), 10, 3 );
        }

        public function zoo_wc_render_product_name_with_swatch($product_name, $cart_item, $cart_item_key) {
            echo('<pre/>');

            $html = $product_name;
            if (count($cart_item['variation'])) {

                $product_swatch_data_array = get_post_meta($cart_item['product_id'], 'zoo_cw_product_swatch_data', true);

                $variations = $cart_item['variation'];
                foreach ($variations as $key => $variation) {
                    $attribue_name = str_replace('attribute_',"", $key);
                    echo($attribue_name);
                }

                if ($product_swatch_data_array != '') {


                    $zoo_clever_swatch_product_page = new Zoo_Clever_Swatch_Product_Page();

                    $product = wc_get_product( $cart_item['product_id'] );

                    $attributes = $product->get_variation_attributes()  ;

                    //var_dump($attributes);

                    $product_swatch_data_array = $zoo_clever_swatch_product_page->prepare_singele_page_data($product, $attributes, $product_swatch_data_array);

                    require(ZOO_CW_TEMPLATES_PATH . 'shop-page-swatch-image.php');
                }
            }
//            echo('<pre/>');
//            var_dump($product_name);
//            var_dump($cart_item);
//            var_dump($cart_item_key);
            die;
            echo($html);
        }

        public function load_template($template, $template_name, $template_path) {
            /*if ($template_name == 'cart/cart-item-data.php') {

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

            }*/

            // replace with our plugin template.
            return $template;
        }


    }



}

$zoo_clever_swatch_cart_page = new Zoo_Clever_Swatch_Cart_Page();