<?php
/**
 * Created by PhpStorm.
 * User: eric
 * Date: 7/27/17
 * Time: 4:32 PM
 */

class cs_cw_config {

    /**
     * clever-swatch version.
     *
     * @var string
     */
    public $version = '1.0.0';

    public function __construct() {
        $this->define_constants();
    }

    /**
     * define all necessary constants of the plugin.
     */
    private function define_constants() {
        $this->define( 'CS_VM_VERSION', $this->version );
        $this->define( 'CS_VM_DIRPATH', plugin_dir_path( __FILE__ ) );
        $this->define( 'CS_VM_URL', plugin_dir_url( __FILE__ ) );
        $this->define( 'CS_VM_JSPATH', plugin_dir_url( __FILE__ )."assests/js/" );
        $this->define( 'CS_VM_CSSPATH', plugin_dir_url( __FILE__ )."assests/css/" );
        $this->define( 'CS_VM_GALLERYPATH', plugin_dir_url( __FILE__ )."assests/images/" );
        $this->define( 'CS_VM_ABSPATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
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

$cs_cw_config = new cs_cw_config();

?>