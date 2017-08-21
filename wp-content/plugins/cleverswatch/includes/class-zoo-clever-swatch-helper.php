<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main Plugin class for managing admin interfaces.
 *
 * @class    Zoo_Clever_Swatch_Helper
 *
 * @version  1.0.0
 * @package  clever-swatch/includes
 * @category Class
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if( !class_exists( 'Zoo_Clever_Swatch_Helper' ) ){

    class Zoo_Clever_Swatch_Helper {

        public function get_display_type_by_attribute_taxonomy_name( $taxonomy_name) {
            //get attribute id by taxonomy name
            $attribute_id = wc_attribute_taxonomy_id_by_name($taxonomy_name);

            global $wpdb;
            $table_name = $wpdb->prefix . "zoo_cw_product_attribute_swatch_type";

            $mylink = $wpdb->get_row( "SELECT * FROM $table_name WHERE attribute_id = $attribute_id" );

            $display_type = '';
            if (isset($mylink)) {
                $display_type = $mylink->swatch_type;
            }

            return $display_type;
        }

        public function get_default_value_of_attribute_option($term) {
            $default_array = array();

            $id = $term->term_id;
            $default_array['display_type'] = $this->get_display_type_by_attribute_taxonomy_name( $term->taxonomy );
            $default_array['default_color'] = get_woocommerce_term_meta( $id, 'slctd_clr', true );
            $default_image = get_woocommerce_term_meta( $id, 'slctd_img', true );
            if(empty($default_image))
                $default_image = wc_placeholder_img_src();

            $default_array['default_image'] = $default_image;

            return $default_array;
        }
    }
}

$zoo_cw_helper =  new Zoo_Clever_Swatch_Helper();

