<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * class for managing gallery images per variation.
 *
 * @class    Cs_Cw_Admin_Variation_Gallery
 *
 * @version  1.0.0
 * @package  clever-swatch/includes
 * @category Class
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if( !class_exists( 'Cs_Cw_Config' ) ){
    class Cs_Cw_Config {

        /**
         * clever-swatch version.
         *
         * @var string
         */
        public $version = '1.0.0';

        public function __construct() {
            $this->define_constants();
            register_activation_hook( __FILE__, array( $this, 'cs_cw_install' ) );
        }

        /**
         * define all necessary constants of the plugin.
         */
        private function define_constants() {
            $plugin_path = dirname(plugin_dir_path( __FILE__ ))."/";
            $this->define( 'CS_VM_VERSION', $this->version );
            $this->define( 'CS_VM_DIRPATH', $plugin_path );
            $this->define( 'CS_VM_TEMPLATES_PATH', $plugin_path."templates/" );
            $this->define( 'CS_VM_URL', plugin_dir_url( __FILE__ ) );
            $this->define( 'CS_VM_JSPATH', $plugin_path."assests/js/" );
            $this->define( 'CS_VM_CSSPATH', $plugin_path."assests/css/" );
            $this->define( 'CS_VM_GALLERYPATH', $plugin_path."assests/images/" );
            $this->define( 'CS_VM_ABSPATH', untrailingslashit( $plugin_path));
        }

        /**
         * Define constant if not already set.
         *
         * @param  string $name
         * @param  string|bool $value
         */
        private function define( $name, $value ) {
            if ( ! defined( $name ) ) {
                define( $name, $value );
            }
        }

        /**
         * install function, perform all necessary operation
         * on plugin activation.
         *
         * @since 1.0.0
         */
        function cs_cw_install(){

            $general_settings_array = array();
            $enableThis = 1;
            $enableSwatch = 1;
            $enablePWG = 1;
            $attrthumb = 0;

            $attbds = array();
            $ds1 = 1;
            $ds2 = 1;

            $attbds['ds1'] = $ds1;
            $attbds['ds2'] = $ds2;

            $general_settings_array['this'] = $enableThis;
            $general_settings_array['swatch'] = $enableSwatch;
            $general_settings_array['pwg'] = $enablePWG;
            $general_settings_array['at'] = $attrthumb;
            $general_settings_array['atds'] = $attbds;

            if(is_array($general_settings_array))
                update_option('cs-cw-settings',$general_settings_array);
        }
    }
}

$cs_cw_config = new cs_cw_config();

?>