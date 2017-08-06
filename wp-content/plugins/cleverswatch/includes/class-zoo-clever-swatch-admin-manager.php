<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main Plugin class for managing admin interfaces.
 *
 * @class    Zoo_Clever_Swatch_Admin_Manager
 *
 * @version  1.0.0
 * @package  clever-swatch/includes
 * @category Class
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

require_once ZOO_CW_DIRPATH.'includes/class-zoo-clever-swatch-admin-variation-gallery.php';

if( !class_exists( 'Zoo_Clever_Swatch_Admin_Manager' ) ){

    class Zoo_Clever_Swatch_Admin_Manager{

        private static $_instance;

        public static function getInstance() {

            if( !self::$_instance instanceof self )
                self::$_instance = new self;

            return self::$_instance;

        }

        public function __construct(){

            add_action( 'admin_enqueue_scripts', array($this,'zoo_cw_enqueue_product_page_scripts') );

            $zoo_cw_variation_gallery_manager = Zoo_Clever_Swatch_Admin_Variation_Gallery::getInstance();

            //adding tab in admin menu.
            add_action( 'admin_menu', array($this, 'zoo_cw_add_settings_page') );

            //for downloading exported csv..
            add_action('admin_init', array($this,'zoo_cw_export_csv_download') );

            //adding tab for variation swatch images.
            add_action('woocommerce_product_data_tabs', array( $this ,'zoo_cw_variation_product_tab' ) ) ;

            //custom tab fields.
            add_action('woocommerce_product_data_panels',  array( $this ,'zoo_cw_customTabFields' ) ) ;

            //saving custom tab data..
            add_action( 'woocommerce_process_product_meta', array( $this ,'zoo_cw_saveCustomTabFields' ) );

//            $attribute_taxonomies = wc_get_attribute_taxonomies();
//
//            if ( ! empty( $attribute_taxonomies ) ) {
//                foreach ( $attribute_taxonomies as $attribute ) {
//                    add_action( 'pa_' . $attribute->attribute_name . '_add_form_fields', array( $this, 'zoo_cw_add_image_color_selector' ) );
//                    add_filter('manage_edit-'.'pa_' . $attribute->attribute_name.'_columns', array( $this,'zoo_cw_add_preview_column') );
//                    add_filter('manage_pa_' . $attribute->attribute_name.'_custom_column', array( $this,'zoo_cw_preview_column_content'),10, 3 );
//                    add_action( 'pa_' . $attribute->attribute_name .'_edit_form_fields', array( $this, 'zoo_cw_edit_attr_fields' ), 10 );
//                }
//            }

            add_action( 'created_term', array( $this, 'zoo_cw_save_attr_extra_fields' ), 10, 3 );
            add_action( 'edit_term', array( $this, 'zoo_cw_save_attr_extra_fields' ), 10, 3 );

            //custom tab fields.
            add_action( "wp_ajax_zoo_cw_update_term_data", array( $this ,'zoo_cw_customTabFields' ) );
        }

        /**
         * function for exporting csv file.
         *
         * @since 1.0.0
         */
        function zoo_cw_export_csv_download(){

            if(isset($_GET["export"])){

                if($_GET["export"] == "gallerycsv"){

                    $filename = 'variations_gallery.csv';
                    $data_array = $this->get_gallery_export_data();

                    if(isset($data_array)){

                        $fp = fopen('php://memory', 'w');

                        if( is_array( $data_array ) && count($data_array) ) {
                            foreach( $data_array as $productData ){
                                if(is_array($productData))
                                    fputcsv($fp, $productData,',');
                            }
                        }
                        fseek($fp, 0);

                        header('Content-Type: application/csv');
                        header('Content-Disposition: attachment; filename="'.$filename.'";');
                        fpassthru($fp);
                    }
                    exit;
                }
                elseif($_GET["export"] == "attrcsv"){

                    $filename = 'attribute_terms.csv';

                    $attribute_taxonomies = wc_get_attribute_taxonomies();

                    $fp = fopen('php://memory', 'w');

                    $csv_header = array('Attribute ID','Attribute Name','Attribute Term','Diplay Type(0=>none|1=>image|2=>color code)','Image Url','Color Code');

                    fputcsv($fp, $csv_header,',');

                    foreach ( $attribute_taxonomies as $attribute ) {
                        $terms = get_terms( array( 'taxonomy' => 'pa_'.$attribute->attribute_name ), array('hide_empty' => false) );

                        $temp_array = array();
                        foreach ($terms as $val){

                            $temp_array['attribute_id']=$attribute->attribute_id;
                            $temp_array['attribute_name']=$attribute->attribute_name;
                            $temp_array['term_name']=$val->name;
                            $temp_array['term_display_type'] = get_woocommerce_term_meta( $val->term_id, 'display_type');
                            $temp_array['term_imgUrl'] = get_woocommerce_term_meta( $val->term_id, 'slctd_img');
                            $temp_array['term_color_code'] = get_woocommerce_term_meta( $val->term_id, 'slctd_clr');

                            fputcsv($fp, $temp_array,',');
                        }
                    }
                    fseek($fp, 0);

                    header('Content-Type: application/csv');
                    header('Content-Disposition: attachment; filename="'.$filename.'";');
                    fpassthru($fp);

                    exit;
                }
            }
        }

        /**
         * function for fetching variations data for exporting in csv.
         *
         * @since 1.0.0
         */
        function get_gallery_export_data(){

            $csv_array = array();

            require_once ZOO_CW_DIRPATH.'includes/library/class-zoo-cw-getvariationfields.php';

            $product_ids = get_posts(array('posts_per_page'=>-1,'post_type'=>'product','fields'=>'ids'));

            $counter = 0;
            foreach ($product_ids as $product_id) {

                $_product = wc_get_product($product_id);

                if($_product->is_type('variable')){

                    $variations		=	$_product->get_available_variations();

                    if(is_array($variations) && count($variations)){

                        foreach($variations as $variation){

                            $variation_id = $variation['variation_id'];
                            $csvfieldobject = new CSVMgetvariationfields($variation_id,$product_id,$variation['attributes']);

                            if(!$counter){
                                $csv_array[] = $csvfieldobject->get_csv_header();
                            }

                            $csv_array[] = $csvfieldobject->get_csv_fields();
                            $counter++;
                        }
                    }
                }
            }
            return $csv_array;
        }

        /**
         * adding attribute fields for swatches.
         *
         * @since 1.0.0
         */
        function zoo_cw_edit_attr_fields( $term ){
            $id = $term->term_id;
            $dt = get_woocommerce_term_meta( $id, 'display_type', true );
            $color_code = get_woocommerce_term_meta( $id, 'slctd_clr', true );
            $image = get_woocommerce_term_meta( $id, 'slctd_img', true );
            if(empty($image))
                $image = wc_placeholder_img_src();

            require_once ZOO_CW_TEMPLATES_PATH.'admin/product-page-edit-attr-fields.php';
        }

        /**
         * saving added attribute fields for swatches.
         *
         * @since 1.0.0
         */
        function zoo_cw_save_attr_extra_fields( $term_id, $tt_id = '', $taxonomy = '' ){
            if ( isset( $_POST['zoo-cw-display-type'] ) ) {

                $dt = absint( $_POST['zoo-cw-display-type'] );
                update_woocommerce_term_meta( $term_id, 'display_type', absint( $_POST['zoo-cw-display-type'] ) );

                if($dt == 2){
                    update_woocommerce_term_meta( $term_id, 'slctd_clr',  $_POST['zoo_cw_slctdclr'] );
                }else if($dt == 1){
                    update_woocommerce_term_meta( $term_id, 'slctd_img',  $_POST['zoo-cw-selected-attr-img'] );
                }
            }
        }

        /**
         * displaying table content on attribute edit for swatches.
         *
         * @since 1.0.0
         */
        function zoo_cw_preview_column_content( $columns, $column, $id ){

            if ( 'thumb' == $column ) {

                $dt = get_woocommerce_term_meta( $id, 'display_type', true );

                if($dt == 2){

                    $color_code = get_woocommerce_term_meta( $id, 'slctd_clr', true );

                    $columns .= '<div style="height:48px; width:48px; background-color:'.$color_code.';" ></div>';

                } else{

                    $img_url = get_woocommerce_term_meta( $id, 'slctd_img', true );

                    if ( $img_url ) {
                        $image = $img_url;
                    } else {
                        $image = wc_placeholder_img_src();
                    }
                    $image = str_replace( ' ', '%20', $image );

                    $columns .= '<img src="' . esc_url( $image ) . '" alt="' . __( 'Thumbnail', 'clever-swatch' ) . '" class="zoo-cw-attr-thumb" height="48" width="48" />';
                }

            }
            return $columns;
        }

        /**
         * adding table column on attribute listing for swatches.
         *
         * @since 1.0.0
         */
        function zoo_cw_add_preview_column( $columns ){
            $new_columns = array();

            if ( isset( $columns['cb'] ) ) {
                $new_columns['cb'] = $columns['cb'];
                unset( $columns['cb'] );
            }

            $new_columns['thumb'] = __( 'Image', 'clever-swatch' );

            return array_merge( $new_columns, $columns );
        }

        /**
         * adding custom fields on attribute term editing for swatches.
         *
         * @since 1.0.0
         */
        function zoo_cw_add_image_color_selector(){
            require_once ZOO_CW_TEMPLATES_PATH.'admin/product-page-add-image-color-selector.php';
        }

        /**
         * settings page of variation manager.
         *
         * @since 1.0.0
         */
        function zoo_cw_add_settings_page(){

            add_menu_page('Clever Swatch', 'Clever Swatch ', 'manage_woocommerce', 'zoo-cw-settings', array($this, 'zoo_cw_settings_callback'),ZOO_CW_GALLERYPATH.'variation.png',55.567 );
        }

        /**
         * callback function of settings page of clever swatch.
         *
         * @since 1.0.0
         */
        function zoo_cw_settings_callback(){

            require_once ZOO_CW_TEMPLATES_PATH.'admin/zoo-clever-watch-settings-page.php';
        }

        /**
         * loading conditional admin scripts.
         *
         * @since 1.0.0
         */
        function zoo_cw_enqueue_product_page_scripts(){

            global $wp_query, $post;

            $screen       = get_current_screen();
            $screen_id    = $screen ? $screen->id : '';
            $wc_screen_id = sanitize_title( __( 'WooCommerce', 'woocommerce' ) );
            $suffix       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
            $screen_page = $screen ? $screen->base : '';

            if( $screen_page == 'toplevel_page_zoo-cw-settings' )
                wp_enqueue_style( 'zoo-clever-swatch-style', ZOO_CW_CSSPATH. 'admin/clever-swatch-style.css' );

            //condition for the product edit page only.
            if ( in_array( $screen_id, array( 'product', 'edit-product' ) ) ){

                wp_enqueue_style( 'wp-color-picker' );
                wp_register_script( 'zoo-cw-product-edit', ZOO_CW_JSPATH . 'admin/product-edit-page-custom-js.js', array( 'jquery','wp-color-picker' ), ZOO_CW_VERSION );
                wp_localize_script( 'zoo-cw-product-edit', 'zoo_cw_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
                wp_enqueue_script( 'zoo-cw-product-edit' );

                wp_enqueue_style( 'zoo-cw-product-edit-style', ZOO_CW_CSSPATH. 'admin/zoo-cw-product-edit-style.css' );
            }

            if ( in_array( $screen_page, array( 'edit-tags' ) ) || in_array( $screen_page, array( 'term') ) ){

                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_media();

                wp_register_script( 'zoo-cw-tags-edit', ZOO_CW_JSPATH . 'admin/edit-tags.js', array( 'jquery','wp-color-picker' ), ZOO_CW_VERSION );
                wp_enqueue_script( 'zoo-cw-tags-edit' );
            }

            if( $screen_id == "toplevel_page_zoo-cw-settings"){

                wp_enqueue_style( 'wp-color-picker' );

                wp_register_script( 'zoo-cw-import-export', ZOO_CW_JSPATH . 'admin/import-export.js', array( 'jquery','wp-color-picker' ), ZOO_CW_VERSION );
                wp_localize_script( 'zoo-cw-import-export', 'zoo_cw_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
                wp_enqueue_script( 'zoo-cw-import-export' );

            }
        }

        /**
         * adding tab for variable products.
         *
         * @since 1.0.0
         */
        public function zoo_cw_variation_product_tab( $tabs ){

            $tabs['swatches'] = array(
                'label'  => __( 'Clever Swatch', 'clever-swatch' ),
                'target' => 'zoo-cw-variation-swatch-data',
                'class'  => array( 'show_if_variable','zoo-cw-term-swatches' ),
            );

            return $tabs;
        }

        /**
         * tab fields for swatches for variable products.
         *
         * @since 1.0.0
         */
        public function zoo_cw_customTabFields(){

            if(is_ajax()){
                if(isset($_POST['post_id']) && !empty($_POST['post_id'])){
                    $post_id = $_POST['post_id'];
                    $post = get_post($post_id);
                }
            }else{
                global $post;
            }

            $_product 		= 	wc_get_product($post->ID);

            if($_product->is_type('variable')){
                require_once ZOO_CW_TEMPLATES_PATH.'admin/product-page-custom-tab-fields.php';
            }else{
                // not an variable product.....
            }
            if(is_ajax()){
                exit;
            }
        }

        /**
         * saving custom fields of variable products for swatches.
         *
         * @since 1.0.0
         */
        function zoo_cw_saveCustomTabFields( $post_id ){

//            echo('<pre/>');
//            var_dump($_POST);
//            die;

            $_product = wc_get_product( $post_id );
            $zoo_cw_swatch_array = array();
            if( $_product->is_type('variable') ){
                $disabled_swatch = isset($_POST["zoo-cw-disable_swatch"]) ? 1 : 0;
                $zoo_cw_swatch_array['disabled'] = $disabled_swatch;
                $attributes	=	$_product->get_variation_attributes();
                if(is_array($attributes)){
                    foreach ( $attributes as $attribute_name => $options ){
                        $tmp_attr_data_array = array();
                        $attrName =  $attribute_name;
                        $tmp_attr_data_array['label'] = isset($_POST["zoo_cw_label_$attrName"])&& !empty($_POST["zoo_cw_label_$attrName"]) ?  $_POST["zoo_cw_label_$attrName"] : wc_attribute_label( $attribute_name ) ;
                        $tmp_attr_data_array['display_type'] = isset($_POST["zoo_cw_display_type_$attrName"]) ? $_POST["zoo_cw_display_type_$attrName"] : 'default' ;
                        $tmp_attr_data_array['display_size'] = isset($_POST["zoo_cw_display_size_$attrName"]) ? intval( $_POST["zoo_cw_display_size_$attrName"] ) : 1 ;
                        $tmp_attr_data_array['display_shape'] = isset($_POST["zoo_cw_display_shape_$attrName"]) ? $_POST["zoo_cw_display_shape_$attrName"] : 'square' ;
                        $tmp_attr_data_array['display_name_yn'] = isset($_POST["zoo_cw_display_name_$attrName"]) ?  $_POST["zoo_cw_display_name_$attrName"]  : 1 ;

                        if ( is_array( $options ) ) {
                            $tmp_option_array = array();
                            if ( taxonomy_exists(  $attribute_name ) ) {
                                $terms = get_terms( $attribute_name, array('menu_order' => 'ASC') );
                                foreach ( $terms as $term ) {
                                    if ( in_array( $term->slug, $options ) ){
                                        $tmp_term_name =  $term->slug;
                                        $tmp_option_array[$tmp_term_name]['gat'] = isset($_POST["zoo_cw_gat_$tmp_term_name"]) ? intval( $_POST["zoo_cw_gat_$tmp_term_name"] ) : 0 ;
                                        $tmp_option_array[$tmp_term_name]['dt'] = isset($_POST["zoo_cw_sdt_$tmp_term_name"]) ? intval( $_POST["zoo_cw_sdt_$tmp_term_name"] ) : 1 ;
                                        if( $tmp_option_array[$tmp_term_name]['dt'] == 1 ){
                                            $tmp_option_array[$tmp_term_name]['image'] = isset($_POST["zoo-cw-input-scimg-$tmp_term_name"]) ? sanitize_text_field( $_POST["zoo-cw-input-scimg-$tmp_term_name"] ) : '' ;
                                        }else{
                                            $tmp_option_array[$tmp_term_name]['color'] = isset($_POST["zoo_cw_slctclr_$tmp_term_name"]) ? sanitize_text_field( $_POST["zoo_cw_slctclr_$tmp_term_name"] ) : '' ;
                                        }
                                    }
                                }
                            }else{
                                foreach ( $options as $option ){
                                    $tmp_term_name =  $option;
                                    $tmp_option_array[$tmp_term_name]['dt'] = isset($_POST["zoo_cw_sdt_$tmp_term_name"]) ? intval( $_POST["zoo_cw_sdt_$tmp_term_name"] ) : 1 ;
                                    if( $tmp_option_array[$tmp_term_name]['dt'] == 1 ){
                                        $tmp_option_array[$tmp_term_name]['image'] = isset($_POST["zoo-cw-input-scimg-$tmp_term_name"]) ? sanitize_text_field( $_POST["zoo-cw-input-scimg-$tmp_term_name"] ) : '' ;
                                    }else{
                                        $tmp_option_array[$tmp_term_name]['color'] = isset($_POST["zoo_cw_slctclr_$tmp_term_name"]) ? sanitize_text_field( $_POST["zoo_cw_slctclr_$tmp_term_name"] ) : '' ;
                                    }
                                }
                            }
                            $tmp_attr_data_array['options_data'] = $tmp_option_array;
                        }
                        $zoo_cw_swatch_array[$attrName] = $tmp_attr_data_array;
                    }
                }else{
                    //not available attributes..
                }
                update_post_meta( $post_id, 'zoo_cw_product_swatch_data', $zoo_cw_swatch_array );
            }else{
                // not an variable product.....
            }
        }
    }
}

$zoo_cw_admin_manager_object = new Zoo_Clever_Swatch_Admin_Manager();