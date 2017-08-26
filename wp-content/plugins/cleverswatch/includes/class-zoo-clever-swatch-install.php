<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * class for install or uninstall Clever Swatch.
 *
 * @class    Zoo_Clever_Swatch_Install
 *
 * @version  1.0.0
 * @package  clever-swatch/includes
 * @category Class
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if( !class_exists( 'Zoo_Clever_Swatch_Install' ) ) {

    class Zoo_Clever_Swatch_Install {


        public function __construct() {
        }

        public function zoo_cw_active_action() {
            //install admin config
            $this->zoo_cw_admin_config_install();

            //create new table for product attribute swatch
            $this->zoo_cw_add_table_for_attribute_swatch();
        }

        public function zoo_cw_add_table_for_attribute_swatch() {
            global $wpdb;

            $table_name = $wpdb->prefix . "zoo_cw_product_attribute_swatch_type";

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                attribute_id bigint(20) DEFAULT 0 NOT NULL,
                swatch_type varchar(20) DEFAULT '' NULL,
                PRIMARY KEY  (id)
                ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }

        public function zoo_cw_deactive_action() {

            //remove table for product attribute swatch
        }

        /**
         * install function, perform all necessary operation
         * on plugin activation.
         *
         * @since 1.0.0
         */
        public function zoo_cw_admin_config_install(){

            $general_settings['swatch'] = 1;
            $general_settings['product_gallery'] = 1;
            $general_settings['product_swatch_display_shape'] = 'square';
            $general_settings['product_swatch_display_size'] = 1;
            $general_settings['product_swatch_display_size_width'] = 20;
            $general_settings['product_swatch_display_size_height'] = 20;
            $general_settings['product_swatch_display_name'] = 1;
            $general_settings['display_shop_page'] = 0;
            $general_settings['display_shop_page_hook'] = 'before';

            if(is_array($general_settings))
                update_option('zoo-cw-settings',$general_settings);
        }
    }
}

$zoo_clever_swatch_install = new Zoo_Clever_Swatch_Install();