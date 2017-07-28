<?php



if( !class_exists( 'cs_clever_swatch' ) ){

    /**
     * Main Variation Master Class.
     *
     * @class cs_clever_swatch
     *
     * @since      1.0.0
     * @package    clever-swatch
     * @author     Eric Robert <ericrobert216@gmail.com>
     */
    class cs_clever_swatch{

        /**
         * define all necessary constants of the plugin.
         *
         * @since 1.0.0
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
         * @since  1.0.0
         * @param  string $name
         * @param  string|bool $value
         */
        private function define( $name, $value ) {
            if ( ! defined( $name ) ) {
                define( $name, $value );
            }
        }

        /**
         * clever-swatch version.
         *
         * @var string
         */
        public $version = '1.0.0';

        /**
         * Constructor.
         *
         * @access public
         * @since 1.0.0
         * @return bool
         */
        function __construct() {

            $this->define_constants();

            add_action('init', array($this, 'load_plugin_textdomain'));

            register_activation_hook( __FILE__, array( $this, 'cs_cw_install' ) );

            if ($this->check_woocommerce_active()) {

                add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this,'cs_cw_add_settings_link' ) );

                // checking the woocommerce template if existed in the plugin.
                //add_filter( 'woocommerce_locate_template', array( $this,'cs_pwv_locate_template' ), 10, 3 );

                if(is_admin()){
                    $this->__admin_constructor();
                }else{

                    //$this->__front_end_constructor();
                }

            } else {

                add_action( 'admin_init', 'cs_clever_swatch_plugin_deactivate' );

                /**
                 * callback function for deactivating the plugin if woocommerce is not activated.
                 *
                 * @since 1.0.0
                 */
                function cs_clever_swatch_plugin_deactivate(){

                    deactivate_plugins( plugin_basename( __FILE__ ) );
                    add_action('admin_notices', 'cs_clever_swatch_woo_missing_notice' );
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
                function cs_clever_swatch_woo_missing_notice(){

                    echo '<div class="error"><p>' . sprintf(__('Variation master requires WooCommerce to be installed and active. You can download %s here.', 'clever-swatch'), '<a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a>') . '</p></div>';
                }
            }
            return true;
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

        /**
         * function for adding settings link on plugins page
         * after activation of the plugin.
         *
         * @since 1.0.0
         * @param array $links
         * @return array:
         */
        function cs_cw_add_settings_link ( $links ) {

            $mylinks = array(
                '<a href="' . admin_url( 'admin.php?page=cs-cw-settings' ) . '">Settings</a>',
            );
            return array_merge( $mylinks,$links );
        }

        /**
         * Cloning is forbidden.
         * @since 1.0.0
         */
        public function __clone() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'clever-swatch' ), '2.1' );
        }

        /**
         * Unserializing instances of this class is forbidden.
         * @since 1.0.0
         */
        public function __wakeup() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'clever-swatch' ), '2.1' );
        }

        /**
         * load the plugin text domain for translation.
         *
         * @since 1.0.0
         * @return bool
         */
        public function load_plugin_textdomain(){

            $domain = 'clever-swatch';
            $locale = apply_filters( 'plugin_locale', get_locale(), 'clever-swatch' );

            load_textdomain( $domain, CS_VM_DIRPATH .'i18n/languages/'.$domain.'-' . $locale . '.mo' );
            $var = load_plugin_textdomain( 'clever-swatch', false, plugin_basename( dirname( __FILE__ ) ) . '/i18n/languages' );
        }

        /**
         * check that woocommerce is installed or not.
         *
         * @since 1.0.0
         * @return bool
         */
        public function check_woocommerce_active(){

            if ( function_exists('is_multisite') && is_multisite() ){

                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
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

        /**
         * callback function for initializing admin functions manager class.
         *
         * since 1.0.0
         */
        function __admin_constructor(){

            do_action('cs-cw-before-admin-constructor');

            include_once CS_VM_DIRPATH.'includes/admin/class-admin_manager.php';
            $cs_clever_swatch_adminInstance=CS_clever_swatch_admin_interface::getInstance();

            do_action('cs-cw-after-admin-constructor');
        }

        /**
         * callback function for initializing front-end functions manager class.
         *
         * since 1.0.0
         */
        function __front_end_constructor(){

            do_action('cs-cw-before-front-end-constructor');

            include_once CS_VM_DIRPATH.'includes/front-end/class-cs-cw-front-end-manager.php';
            $cs_clever_swatch_frontEndManager = CS_clever_swatch_shop_interface::getInstance();

            do_action('cs-cw-after-front-end-constructor');
        }

        /**
         * checking that the used template is available in our plugin or not.
         *
         * since 1.0.0
         */
        function cs_pwv_locate_template( $template, $template_name, $template_path ) {

            do_action('cs-cw-locate-template');

            global $woocommerce;

            $_template = $template;
            if ( ! $template_path ) $template_path = $woocommerce->template_url;
            $plugin_path  = CS_VM_ABSPATH . '/woocommerce/';

            // check the template is available in theme or not.
            $template = locate_template(
                array(
                    $template_path . $template_name,
                    $template_name
                )
            );

            // check that the template is there in plugin or not.
            if ( file_exists( $plugin_path . $template_name ) )
                $template = $plugin_path . $template_name;

            // return the default template.
            if ( ! $template )
                $template = $_template;

            // replace with our plugin template.
            return $template;
        }
    }

    add_action('plugins_loaded', 'cs_clever_swatch_init', 0);

    /**
     * Init function.
     *
     * @since 1.0.0
     * @return bool
     */
    function cs_clever_swatch_init(){

        do_action('cs-cw-init');

        new cs_clever_swatch();
        return true;
    }

    add_action( "wp_ajax_cs_cw_get_product_html", 'cs_cw_get_variation_html'  );
    add_action( "wp_ajax_nopriv_cs_cw_get_product_html", 'cs_cw_get_variation_html' );

    /**
     * function for product html displaying on cart page.
     *
     * since 1.0.0
     */
    function cs_cw_get_variation_html(){

        do_action('cs-cw-before-product-html');

        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : '';
        $variation_id = isset($_POST['variationID']) ? intval($_POST['variationID']) : '';

        if(empty($product_id))
            die('unknown problem! please try again later.');

        /* setting global things on our own for getting things in woocommerce manner ::start */
        global $product;
        global $post;

        $product = wc_get_product($product_id);
        $post = get_post($product_id);

        $variation_attributes = wc_get_product_variation_attributes($variation_id);

        $selected_attributes = isset($variation_attributes) ? $variation_attributes : $product->get_variation_default_attributes();

        /* setting global things on our own for getting things in woocommerce manner ::end */
        ?>
        <div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php
            /*
             * woocommerce_before_single_product_summary hook.
             * @hooked woocommerce_show_product_sale_flash - 10 , @hooked woocommerce_show_product_images - 20
             */
            do_action( 'woocommerce_before_single_product_summary' );
            ?>
            <div class="summary entry-summary">
                <?php
                //get single product name.
                wc_get_template( 'single-product/title.php' );
                //get single product price.
                wc_get_template( 'single-product/price.php' );

                //woocommerce_template_single_add_to_cart();
                //update for selected attributes.
                global $product;

                // Enqueue variation scripts
                wp_enqueue_script( 'wc-add-to-cart-variation' );

                // Get Available variations?
                $get_variations = sizeof( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );

                // Load the template
                wc_get_template( 'single-product/add-to-cart/variable.php', array(
                    'available_variations' => $get_variations ? $product->get_available_variations() : false,
                    'attributes'           => $product->get_variation_attributes(),
                    'selected_attributes'  => $selected_attributes,
                ) );
                ?>
            </div>
            <meta itemprop="url" content="<?php the_permalink(); ?>" />
        </div>
        <div class="cs-cw-stock-error"></div>
        <?php

        do_action('cs-cw-after-product-html');

        wp_die();
    }

    add_action( "wp_ajax_cs_cw_get_variation_gallery_for_single",'cs_cw_get_variation_gallery_for_single' );
    add_action( "wp_ajax_nopriv_cs_cw_get_variation_gallery_for_single",'cs_cw_get_variation_gallery_for_single' );

    /**
     * function for fetching single variation gallery.
     * @deprecated
     *
     * since 1.0.0
     */
    function cs_cw_get_variation_gallery_for_single(){

        do_action('cs-cw-before-single-variation-gallery');

        $woo_ver = WC()->version;
        if($woo_ver < '3.0.0'){

            if(isset($_POST['variation_id'])){

                $variation_id = intval($_POST['variation_id']);
                $reset = isset($_POST['reset']) ? intval($_POST['reset']) : 0 ;
                $tmp_post = get_post($variation_id);

                if(!$reset){

                    $gallery_images_id = get_post_meta( $variation_id, 'cs-cw-variation-gallery', true );
                    $attachment_ids = array_filter( explode( ',', $gallery_images_id ) );
                }else{

                    $_product = wc_get_product($variation_id);
                    $attachment_ids = $_product->get_gallery_attachment_ids();
                }

                if ( is_array($attachment_ids) && count($attachment_ids) ) {
                    $loop 		= 0;
                    $columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
                    ?>
                    <div class="thumbnails <?php echo 'columns-' . $columns; ?>"><?php

                        foreach ( $attachment_ids as $attachment_id ) {

                            $classes = array( 'zoom' );

                            if ( $loop === 0 || $loop % $columns === 0 ) {
                                $classes[] = 'first';
                            }

                            if ( ( $loop + 1 ) % $columns === 0 ) {
                                $classes[] = 'last';
                            }

                            $image_class = implode( ' ', $classes );

                            $props = array(
                                'title'   => '',
                                'caption' => '',
                                'url'     => '',
                                'alt'     => '',
                            );
                            if ( $attachment_id ) {
                                $attachment       = get_post( $attachment_id );
                                $props['title']   = trim( strip_tags( $attachment->post_title ) );
                                $props['caption'] = trim( strip_tags( $attachment->post_excerpt ) );
                                $props['url']     = wp_get_attachment_url( $attachment_id );
                                $props['alt']     = trim( strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );

                                // Alt text fallbacks
                                $props['alt']     = empty( $props['alt'] ) ? $props['caption'] : $props['alt'];
                                $props['alt']     = empty( $props['alt'] ) ? trim( strip_tags( $attachment->post_title ) ) : $props['alt'];
                                $props['alt']     = empty( $props['alt'] ) && $post ? trim( strip_tags( get_the_title( $post->ID ) ) ) : $props['alt'];
                            }

                            if ( ! $props['url'] ) {
                                continue;
                            }

                            echo apply_filters(
                                'woocommerce_single_product_image_thumbnail_html',
                                sprintf(
                                    '<a href="%s" class="%s" title="%s" data-rel="prettyPhoto[product-gallery]">%s</a>',
                                    esc_url( $props['url'] ),
                                    esc_attr( $image_class ),
                                    esc_attr( $props['caption'] ),
                                    wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), 0, $props )
                                ),
                                $attachment_id,
                                $post->ID,
                                esc_attr( $image_class )
                            );

                            $loop++;
                        }

                        ?></div>
                    <?php
                }
                exit;
            }else{
                exit;
            }
        }
        else
        {
            if(isset($_POST['variation_id'])){

                global $post, $product;

                $variation_id = intval($_POST['variation_id']);
                $product_id = intval($_POST['product_id']);
                $reset = isset($_POST['reset']) ? intval($_POST['reset']) : 0 ;
                $tmp_post = get_post($variation_id);

                if(!$reset){

                    $gallery_images_id = get_post_meta( $variation_id, 'cs-cw-variation-gallery', true );
                    $attachment_ids = array_filter( explode( ',', $gallery_images_id ) );
                }else{

                    $_product = wc_get_product($variation_id);
                    $attachment_ids = $_product->get_gallery_attachment_ids();
                }

                $columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
                $post_thumbnail_id = get_post_thumbnail_id( $variation_id );
                $full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
                $image_title       = get_post_field( 'post_excerpt', $post_thumbnail_id );
                $placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
                $wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
                    'woocommerce-product-gallery',
                    'woocommerce-product-gallery--' . $placeholder,
                    'woocommerce-product-gallery--columns-' . absint( $columns ),
                    'images',
                ) );
                ?>
                <div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
                    <figure class="woocommerce-product-gallery__wrapper">
                        <?php
                        $attributes = array(
                            'title'                   => $image_title,
                            'data-src'                => $full_size_image[0],
                            'data-large_image'        => $full_size_image[0],
                            'data-large_image_width'  => $full_size_image[1],
                            'data-large_image_height' => $full_size_image[2],
                        );

                        if ( has_post_thumbnail($variation_id) ) {
                            $html  = '<div data-thumb="' . get_the_post_thumbnail_url( $variation_id, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
                            $html .= get_the_post_thumbnail( $variation_id, 'shop_single', $attributes );
                            $html .= '</a></div>';
                        }
                        elseif(has_post_thumbnail($product_id)){
                            $html  = '<div data-thumb="' . get_the_post_thumbnail_url( $product_id, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
                            $html .= get_the_post_thumbnail( $product_id, 'shop_single', $attributes );
                            $html .= '</a></div>';
                        }

                        else {
                            $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
                            $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
                            $html .= '</div>';
                        }

                        echo $html;
                        ////////////////////////////////////////////
                        if ( $attachment_ids && has_post_thumbnail($variation_id) ) {

                            foreach ( $attachment_ids as $attachment_id ) {
                                $full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
                                $thumbnail       = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
                                $image_title     = get_post_field( 'post_excerpt', $attachment_id );

                                $attributes = array(
                                    'title'                   => $image_title,
                                    'data-src'                => $full_size_image[0],
                                    'data-large_image'        => $full_size_image[0],
                                    'data-large_image_width'  => $full_size_image[1],
                                    'data-large_image_height' => $full_size_image[2],
                                );

                                $html  = '<div data-thumb="' . esc_url( $thumbnail[0] ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
                                $html .= wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
                                $html .= '</a></div>';

                                echo $html;
                            }
                        }
                        ?>
                    </figure>
                </div>

                <?php

            }
            exit;
        }


    }
    add_action( "wp_ajax_cs_cw_get_variation_gallery_for_reset_single",'cs_cw_get_variation_gallery_for_reset_single' );
    add_action( "wp_ajax_nopriv_cs_cw_get_variation_gallery_for_reset_single",'cs_cw_get_variation_gallery_for_reset_single' );
    /**
     * function for fetching single variation gallery.
     * @deprecated
     *
     * since 1.0.0
     */
    function cs_cw_get_variation_gallery_for_reset_single(){

        do_action('cs-cw-before-single-variation-gallery');

        if(isset($_POST['productId'])){

            global $post, $product;


            $product_id = intval($_POST['productId']);



            $_product = wc_get_product($product_id);
            $attachment_ids = $_product->get_gallery_attachment_ids();




            $columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
            $post_thumbnail_id = get_post_thumbnail_id( $product_id );
            $full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
            $image_title       = get_post_field( 'post_excerpt', $post_thumbnail_id );
            $placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
            $wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
                'woocommerce-product-gallery',
                'woocommerce-product-gallery--' . $placeholder,
                'woocommerce-product-gallery--columns-' . absint( $columns ),
                'images',
            ) );
            ?>
            <div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
                <figure class="woocommerce-product-gallery__wrapper">
                    <?php
                    $attributes = array(
                        'title'                   => $image_title,
                        'data-src'                => $full_size_image[0],
                        'data-large_image'        => $full_size_image[0],
                        'data-large_image_width'  => $full_size_image[1],
                        'data-large_image_height' => $full_size_image[2],
                    );

                    if ( has_post_thumbnail($product_id) ) {
                        $html  = '<div data-thumb="' . get_the_post_thumbnail_url( $product_id, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
                        $html .= get_the_post_thumbnail( $product_id, 'shop_single', $attributes );
                        $html .= '</a></div>';
                    }
                    else {
                        $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
                        $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
                        $html .= '</div>';
                    }

                    echo $html;
                    ////////////////////////////////////////////
                    if ( $attachment_ids && has_post_thumbnail($product_id) ) {

                        foreach ( $attachment_ids as $attachment_id ) {
                            $full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
                            $thumbnail       = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
                            $image_title     = get_post_field( 'post_excerpt', $attachment_id );

                            $attributes = array(
                                'title'                   => $image_title,
                                'data-src'                => $full_size_image[0],
                                'data-large_image'        => $full_size_image[0],
                                'data-large_image_width'  => $full_size_image[1],
                                'data-large_image_height' => $full_size_image[2],
                            );

                            $html  = '<div data-thumb="' . esc_url( $thumbnail[0] ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
                            $html .= wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
                            $html .= '</a></div>';

                            echo $html;
                        }
                    }
                    ?>
                </figure>
            </div>

            <?php

        }
        exit;

    }
    add_action( "wp_ajax_cs_cw_update_variation_in_cart",'update_variation_in_cart_function' );
    add_action( "wp_ajax_nopriv_cs_cw_update_variation_in_cart",'update_variation_in_cart_function' );

    /**
     * updating variation in cart.
     *
     * since 1.0.0
     */
    function update_variation_in_cart_function(){

        global $woocommerce;

        do_action('cs-cw-before-updating-product-in-cart');

        if(isset($_POST['product_id'])){

            if(!empty($_POST['product_id'])){

                $product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
                $quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );

                /* addition setup for variable product ::start */
                $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : '';
                $variation = isset($_POST['variation']) ? $_POST['variation'] : array();
                /* addition setup for variable product ::end */
                $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

                $_variationProduct = wc_get_product($variation_id);
                $hasEnoughStock = $_variationProduct->has_enough_stock($quantity);

                if(!$hasEnoughStock){
                    _e('There is no enough stock for this product! please reduce your stock for the variation and try again!','clever-swatch');
                    wp_die();
                }

                if($passed_validation && $hasEnoughStock){

                    if(isset($_POST['PrevProId'])){

                        $product_to_remove = sanitize_text_field($_POST['PrevProId']);

                        foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {

                            if ($cart_item['variation_id'] == $product_to_remove) {

                                //remove single product
                                WC()->cart->remove_cart_item($cart_item_key);
                            }
                        }
                    }
                    ob_start();

                    $product_status    = get_post_status( $product_id );

                    if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) && 'publish' === $product_status ) {

                        do_action( 'woocommerce_ajax_added_to_cart', $product_id );

                        $success = true;

                    } else {

                        $success = false;
                    }

                    wp_die($success);

                }else{

                    $success = false;
                    wp_die($success);
                }

            }else{
                _e('unexpected error occured while updating your choice! please try again later by reducing your product stock.','clever-swatch');
            }
        }else{
            _e('unexpected error occured while updating your choice! please try again later.','clever-swatch');
        }
        wp_die($success);
    }

    /**
     * function for printing conditional label.
     *
     * since 1.0.0
     */
    function cs_print_attribue_label_on_simple( $term_name, $is_print){

        if( $is_print ){
            return apply_filters( 'woocommerce_variation_option_name', $term_name );
        }else{
            // nothing has to print..
        }
    }

    /**
     * function for image html for swatches on single page.
     *
     * since 1.0.0
     */
    function get_image_html($imageUrl,$circle,$size = 1){

        return '<img src="'.$imageUrl.'" >';
    }

    function get_color_html($colorCode){

    }

    /**
     * function for style for swatches on single page.
     *
     * since 1.0.0
     */
    function get_custom_style($image,$colorcode,$circle,$size){

        if($image == 1){
            if($circle == 2){
                return 'border-radius : 100%;';
            }else{
                return 'border-radius : 0%;';
            }
        }else{
            if($circle == 2){
                return 'background-color:'.$colorcode.'; border-radius : 100%;';
            }else{
                return 'background-color:'.$colorcode.'; border-radius : 0%;';
            }
        }
    }


    /**
     * function for updating variation gallery.
     *
     * @since 1.0.0
     */
    function cs_cw_update_add_variation( $data ){
        $message = '';

        $parent_post_id = isset($data[0]) ? $data[0]  : '';
        $id = isset($data[1]) ? $data[1]  : '';

        $variation_name = isset($data[3]) ? $data[3]  : '';

        $variation_gallery  = isset($data[5]) ? $data[5]  : '';
        $variation_gallery_urls_array = array();

        if(!empty($variation_gallery)){
            $variation_gallery_urls_array = explode("|", $variation_gallery);

            if(count($variation_gallery_urls_array)){

                $message = update_variation_gallery($variation_gallery_urls_array,$id,$variation_name);
            }else{
                $message = $variation_name.__(' Error while updating varaiation gallery, please try again later.','clever-swatch');
            }
        }
        return $message;
    }


    /**
     * function for updating attr term meta data.
     *
     * @since 1.0.0
     */
    function cs_cw_update_add_attr_terms($term_metadata){
        $message = '';

        if(is_array($term_metadata)){

            $atchmntUrl = wc_placeholder_img_src();
            $taxonomy_id = isset($term_metadata[0]) ? intval($term_metadata[0]) : '';

            $taxonomy_name = isset($term_metadata[1]) ? esc_attr($term_metadata[1]) : '';

            $term_to_update = isset($term_metadata[2]) ? esc_attr($term_metadata[2]) : '';

            $termdt = isset($term_metadata[3]) ? intval($term_metadata[3]) : '';

            $termimgurl = isset($term_metadata[4]) ? esc_attr($term_metadata[4]) : '';

            if(isset($termimgurl) && !empty($termimgurl)){
                $termatchmntid = cs_cw_upload_image($termimgurl);

                if(isset($termatchmntid) || $termatchmntid){

                    $atchmntUrl = wp_get_attachment_url( $termatchmntid );
                }
            }

            $termclr = isset($term_metadata[5]) ? esc_attr($term_metadata[5]) : '';

            if( empty($taxonomy_name) || empty($term_to_update)){
                $message = $taxonomy_name.' '.$term_to_update.__(' Taxonomy or Term Field Is Empty Or Invalid For','clever_swatch');
                return;
            }

            $attribute_name = wc_attribute_taxonomy_name ( $taxonomy_name );

            if(taxonomy_exists($attribute_name)){

                $existed_terms = get_terms( array( 'taxonomy' => $attribute_name ), array('hide_empty' => false) );

                if(is_wp_error($existed_terms)){
                    $message = $term_to_update.__(' Not Updated','clever_swatch');
                    return;
                }

                $existed_term_array = array();
                $existed_term_array_assoc = array();
                if(isset($existed_terms)){

                    foreach($existed_terms as $termData){

                        $existed_term_array_assoc[$termData->term_id] = $termData->name;
                        $existed_term_array[] = $termData->name;
                    }
                }

                if(in_array($term_to_update, $existed_term_array)){

                    //term is existed need to update it.
                    $term_id = array_search($term_to_update, $existed_term_array_assoc);

                    if(!$term_id){
                        $message = $term_to_update.__(' Not Updated','clever_swatch');
                        return;
                    }

                    update_woocommerce_term_meta( $term_id, 'display_type', $termdt );

                    update_woocommerce_term_meta( $term_id, 'slctd_clr', esc_attr( $termclr ) );

                    update_woocommerce_term_meta( $term_id, 'slctd_img', esc_attr( $atchmntUrl ) );

                    $message = $taxonomy_name.' '. $term_to_update. __(' Updated Successfully','clever_swatch');

                }else{
                    $term_id = 0;

                    //not an exister term need to add new term.
                    $term_id_array = wp_insert_term($term_to_update, $attribute_name );

                    if(isset($term_id_array['term_id']))
                        $term_id = intval($term_id_array['term_id']);

                    if(!$term_id){
                        $message = $term_to_update.__(' Not Updated','clever_swatch');
                        return;
                    }

                    update_woocommerce_term_meta( $term_id, 'display_type', $termdt );

                    update_woocommerce_term_meta( $term_id, 'slctd_clr', esc_attr( $termclr ) );

                    update_woocommerce_term_meta( $term_id, 'slctd_img', esc_attr( $atchmntUrl ) );

                    $message = $taxonomy_name.' '. $term_to_update.__(' Updated Successfully','clever_swatch');
                }
            }else{
                //not an existing taxonomy..
                $message = $taxonomy_name.__(' Not Existed','clever_swatch');
            }
        }else{
            $message = __('Importing Error','clever_swatch');
        }
        return $message;
    }

    /**
     * upload image and get uploaded image url.
     *
     * @return int attachment_id.
     * @since 1.0.0
     */
    function cs_cw_upload_image($url){
        global $wpdb;

        $thumbid = 0;
        $url = esc_url($url);

        $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$url'";
        $row = $wpdb->get_row($query);
        if(isset($row->ID)){
            return $row->ID;
        }else{

            $tmp = download_url( $url );

            preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $url, $matches);

            $file_array['name'] = basename($matches[0]);
            $file_array['tmp_name'] = $tmp;

            if ( is_wp_error( $tmp ) ) {
                @unlink($file_array['tmp_name']);
                $file_array['tmp_name'] = '';
                return false;
            }else{
                $thumbid = media_handle_sideload( $file_array, $id );
                if($thumbid){
                    return $thumbid;
                }
            }
        }
        return $thumbid;
    }

    /**
     * function for updating variation gallery.
     *
     * @since 1.0.0
     */
    function update_variation_gallery($url_arrays = array(), $variation_id, $variation_name=''){
        global $wpdb;

        if(count($url_arrays)){

            if(empty($variation_name))
                $variation_name = $variation_id;

            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            $thumbnailIdsArray = array();

            foreach($url_arrays as $url){
                $atchmnt_id = cs_cw_upload_image($url);
                if(empty($atchmnt_id) || !$atchmnt_id){
                    return $variation_name.__(' Please provide valid image url','clever-swatch');
                }else{
                    $thumbnailIdsArray[] = $atchmnt_id;
                }
            }
            $imploded_ids = implode(',',$thumbnailIdsArray);
            update_post_meta($variation_id, 'cs-cw-variation-gallery', $imploded_ids);
            return $variation_name.__(' Gallery Updated Successfully.','clever-swatch');
        }
    }
}
?>