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

if( !class_exists( 'Zoo_Clever_Swatch_Config' ) ){
    class Zoo_Clever_Swatch_Config {

        /**
         * clever-swatch version.
         *
         * @var string
         */
        public $version = '1.0.0';

        public function __construct() {
            $this->define_constants();
            register_activation_hook( __FILE__, array( $this, 'zoo_cw_install' ) );
        }

        /**
         * define all necessary constants of the plugin.
         */
        private function define_constants() {

            $plugin_path = dirname(plugin_dir_path( __FILE__ ))."/";
            $file_url = plugin_dir_url( __FILE__ );
            $plugin_url = substr($file_url, 0, strpos($file_url, 'includes'));

            $this->define( 'ZOO_CW_VERSION', $this->version );
            $this->define( 'ZOO_CW_DIRPATH', $plugin_path );
            $this->define( 'ZOO_CW_TEMPLATES_PATH', $plugin_path."templates/" );
            $this->define( 'ZOO_CW_URL', $plugin_url );
            $this->define( 'ZOO_CW_JSPATH', $plugin_url."assets/js/" );
            $this->define( 'ZOO_CW_CSSPATH', $plugin_url."assets/css/" );
            $this->define( 'ZOO_CW_GALLERYPATH', $plugin_url."assets/images/" );
            $this->define( 'ZOO_CW_ABSPATH', untrailingslashit( $plugin_path));
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
    }
}

$zoo_clever_swatch_config = new Zoo_Clever_Swatch_Config();

?>