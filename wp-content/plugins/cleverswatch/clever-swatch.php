<?php
/**
 * Plugin Name: Clever Swatch
 * Description: Swatch color for your woocommerce variable products.
 * Version: 1.0.0
 * Author: cleversoft.co <hello.cleversoft@gmail.com>
 * Requires at least: 4.3.5
 * Tested up to: 4.6.1
 *
 * Text Domain: clever-swatch
 * Domain Path: /i18n/languages/
 *
 * @package clever-swatch
 * @author cleversoft.co
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// load config
require plugin_dir_path( __FILE__ ) . 'includes/class-zoo-clever-swatch-config.php';

//add_action('init', array($this, 'load_plugin_textdomain'));

// install default config
register_activation_hook( __FILE__, array( $zoo_clever_swatch_config, 'zoo_cw_install' ) );

if (check_woocommerce_active()) {
    //add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this,'zoo_cw_add_settings_link' ) );

    // checking the woocommerce template if existed in the plugin.
    //add_filter( 'woocommerce_locate_template', array( $this,'zoo_pwv_locate_template' ), 10, 3 );

    if(is_admin()){
        require_once(ZOO_CW_DIRPATH . 'includes/class-zoo-clever-swatch-admin-manager.php');
    }else{

        require_once(ZOO_CW_DIRPATH . 'includes/class-zoo-clever-swatch-product-page.php');
    }

} else {

    add_action( 'admin_init', 'zoo_clever_swatch_plugin_deactivate' );

    /**
     * callback function for deactivating the plugin if woocommerce is not activated.
     *
     * @since 1.0.0
     */
    function zoo_clever_swatch_plugin_deactivate(){

        deactivate_plugins( plugin_basename( __FILE__ ) );
        add_action('admin_notices', 'zoo_clever_swatch_woo_missing_notice' );
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }

    /**
     * callback function for sending notice if woocommerce is not activated.
     *
     * @since 1.0.0
     * @return string
     */
    function zoo_clever_swatch_woo_missing_notice(){
        echo '<div class="error"><p>' . sprintf(__('Clever Swatch requires WooCommerce to be installed and active. You can download %s here.', 'clever-swatch'), '<a href="http://www.google.com/" target="_blank">WooCommerce</a>') . '</p></div>';
    }
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
//function run_plugin_name() {
//    $plugin = new Plugin_Name();
//    $plugin->run();
//}
//run_plugin_name();


function check_woocommerce_active(){

    if ( function_exists('is_multisite') && is_multisite() ){

        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ){

            return true;
        }
        return false;
    }else{

        if ( in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) ){

            return true;
        }
        return false;
    }
}

?>