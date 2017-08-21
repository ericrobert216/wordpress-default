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
            $html = $product_name;
            echo($html);
            if (count($cart_item['variation'])) {

                $product_swatch_data_array = get_post_meta($cart_item['product_id'], 'zoo_cw_product_swatch_data', true);

                $variations = $cart_item['variation'];
                $attributes = array();
                foreach ($variations as $key => $variation) {
                    $attribue_name = str_replace('attribute_',"", $key);
                    //var_dump($attribue_name);
                    $attributes[$attribue_name][] = $variation;
                }

                if ($product_swatch_data_array != '') {
                    $zoo_clever_swatch_product_page = new Zoo_Clever_Swatch_Product_Page();
                    $product = wc_get_product( $cart_item['product_id'] );
                    $product_swatch_data_array = $zoo_clever_swatch_product_page->prepare_singele_page_data($product, $attributes, $product_swatch_data_array);
                    require(ZOO_CW_TEMPLATES_PATH . 'cart-page-swatch-variation.php');
                }
            }

        }
    }

}

$zoo_clever_swatch_cart_page = new Zoo_Clever_Swatch_Cart_Page();