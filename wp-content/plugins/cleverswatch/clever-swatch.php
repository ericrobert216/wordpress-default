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
require plugin_dir_path( __FILE__ ) . 'includes/class-zoo-clever-swatch-install.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-zoo-clever-swatch-helper.php';

//add_action('init', array($this, 'load_plugin_textdomain'));

// install default config
register_activation_hook( __FILE__, array( $zoo_clever_swatch_install, 'zoo_cw_active_action' ) );
register_deactivation_hook( __FILE__, array( $zoo_clever_swatch_install, 'zoo_cw_deactive_action' ) );

if (check_woocommerce_active()) {
    //add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this,'zoo_cw_add_settings_link' ) );

    // checking the woocommerce template if existed in the plugin.
    //add_filter( 'woocommerce_locate_template', array( $this,'zoo_pwv_locate_template' ), 10, 3 );

    if(is_admin()){
        require_once(ZOO_CW_DIRPATH . 'includes/class-zoo-clever-swatch-admin-manager.php');
    }else{
        require_once(ZOO_CW_DIRPATH . 'includes/class-zoo-clever-swatch-product-page.php');
        require_once(ZOO_CW_DIRPATH . 'includes/class-zoo-clever-swatch-shop-page.php');
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

add_action( 'wp_ajax_clever_swatch_action', 'clever_swatch_action' );
add_action( 'wp_ajax_nopriv_clever_swatch_action', 'clever_swatch_action' );

function clever_swatch_action() {

    $variation_id = intval($_POST['variation_id']);
    $product_id = intval($_POST['product_id']);

    $columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
    $thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
    $post_thumbnail_id = get_post_thumbnail_id( $variation_id );
    $full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
    $placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
    $wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
        'woocommerce-product-gallery',
        'woocommerce-product-gallery--' . $placeholder,
        'woocommerce-product-gallery--columns-' . absint( $columns ),
        'images',
    ) );

    $gallery_images_id = get_post_meta($variation_id, 'zoo-cw-variation-gallery', true);
    $attachment_ids = array_filter(explode(',', $gallery_images_id));

    ?>
    <div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>"
         style="opacity: 0; transition: opacity .25s ease-in-out;">
        <figure class="woocommerce-product-gallery__wrapper">
            <?php
                $attributes = array(
                    'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
                    'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
                    'data-src'                => $full_size_image[0],
                    'data-large_image'        => $full_size_image[0],
                    'data-large_image_width'  => $full_size_image[1],
                    'data-large_image_height' => $full_size_image[2],
                );

                if ( has_post_thumbnail($variation_id) ) {
                    $html = '<div data-thumb="' . get_the_post_thumbnail_url( $variation_id, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image">';
                    $html .= '<a href="' . esc_url( $full_size_image[0] ) . '">';
                    $html .= get_the_post_thumbnail( $variation_id, 'shop_single', $attributes );
                    $html .= '</a>';
                    $html .= '</div>';
                } elseif (has_post_thumbnail($product_id)) {
                    $html = '<div data-thumb="' . get_the_post_thumbnail_url($product_id, 'shop_thumbnail') . '" class="woocommerce-product-gallery__image"><a href="' . esc_url($full_size_image[0]) . '">';
                    $html .= get_the_post_thumbnail($product_id, 'shop_single', $attributes);
                    $html .= '</a></div>';
                } else {
                    $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
                    $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
                    $html .= '</div>';
                }
                echo $html;

                //thumb image
                foreach ($attachment_ids as $attachment_id) {
                    $full_size_image = wp_get_attachment_image_src($attachment_id, 'full');
                    $thumbnail = wp_get_attachment_image_src($attachment_id, 'shop_thumbnail');
                    $image_title = get_post_field('post_excerpt', $attachment_id);

                    $attributes = array(
                        'title' => $image_title,
                        'data-src' => $full_size_image[0],
                        'data-large_image' => $full_size_image[0],
                        'data-large_image_width' => $full_size_image[1],
                        'data-large_image_height' => $full_size_image[2],
                    );

                    $html = '<div data-thumb="' . esc_url($thumbnail[0]) . '" class="woocommerce-product-gallery__image">';
                    $html .= '<a href="' . esc_url($full_size_image[0]) . '">';
                    $html .= wp_get_attachment_image($attachment_id, 'shop_single', false, $attributes);
                    $html .= '</a>';
                    $html .= '</div>';

                    echo $html;
                }
            ?>
        </figure>
    </div>
<?php

    wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_clever_swatch_action_reset', 'clever_swatch_action_reset' );

function clever_swatch_action_reset() {

    $product_id = intval($_POST['product_id']);



    require(ZOO_CW_TEMPLATES_PATH . 'woocommerce/single-product/product-image.php');

    wp_die(); // this is required to terminate immediately and return a proper response
}

?>