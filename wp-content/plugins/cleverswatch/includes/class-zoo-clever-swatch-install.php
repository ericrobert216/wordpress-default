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

            $general_settings_array = array();
            $enableThis = 1;
            $enableSwatch = 1;
            $enable_product_gallery = 1;
            $attrthumb = 0;

            $attbds = array();
            $ds1 = 1;
            $ds2 = 1;

            $attbds['ds1'] = $ds1;
            $attbds['ds2'] = $ds2;

            $general_settings_array['this'] = $enableThis;
            $general_settings_array['swatch'] = $enableSwatch;
            $general_settings_array['product_gallery'] = $enable_product_gallery;
            $general_settings_array['at'] = $attrthumb;
            $general_settings_array['atds'] = $attbds;

            if(is_array($general_settings_array))
                update_option('zoo-cw-settings',$general_settings_array);
        }
    }
}

$zoo_clever_swatch_install = new Zoo_Clever_Swatch_Install();