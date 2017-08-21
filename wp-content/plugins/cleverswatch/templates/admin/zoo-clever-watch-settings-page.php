<?php
/**
 * @version  1.0.0
 * @package  clever-swatch/templates/admin
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$woo_settings = new WC_Admin_Settings();

$current_tab = "general";

if (isset($_GET['tab'])) {
    $current_tab = $_GET['tab'];
}
$authenticate_notice = array();

if (isset($_POST['save'])) {
    if ($current_tab == "general") {
        $general_settings_array = array();
        $enable_swatch = isset($_POST['zoo_cw_enable_swatch']) ? 1 : 0;
        $enable_product_gallery = isset($_POST['zoo_cw_enable_product_gallery']) ? 1 : 0;

        $display_shape = isset($_POST['zoo_cw_display_shape']) ? $_POST['zoo_cw_display_shape'] : 1;
        $display_size = isset($_POST['zoo_cw_display_size']) ? $_POST['zoo_cw_display_size'] : 1;
        if ($display_size == "custom") {
            $display_size_width = isset($_POST['zoo_cw_display_size_width']) ? $_POST['zoo_cw_display_size_width'] : 20;
            $display_size_height = isset($_POST['zoo_cw_display_size_height']) ? $_POST['zoo_cw_display_size_height'] : $display_size_width;
        }
        $display_name = isset($_POST['zoo_cw_display_name']) ? intval($_POST['zoo_cw_display_name']) : 1;

        $general_settings_array['swatch'] = $enable_swatch;
        $general_settings_array['product_gallery'] = $enable_product_gallery;
        $general_settings_array['display_shape'] = $display_shape;
        $general_settings_array['display_size'] = $display_size;
        if ($display_size == "custom") {
            $general_settings_array['display_size_width'] = $display_size_width;
            $general_settings_array['display_size_height'] = $display_size_height;
        }
        $general_settings_array['display_name'] = $display_name;

        if (is_array($general_settings_array))
            update_option('zoo-cw-settings', $general_settings_array);
    } ?>
    <div class="notice notice-success is-dismissible">
        <p><strong><?php _e('Settings Saved Successfully', 'clever-swatch'); ?></strong></p>
    </div>
<?php }

?>

<div class="wrap woocommerce">
    <form novalidate="novalidate" action="" method="post">
        <h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
            <a href="<?php get_admin_url() ?>admin.php?page=zoo-cw-settings&tab=general"
               class="nav-tab <?php if ($current_tab == 'general') : ?> nav-tab-active<?php endif; ?>"><?php _e('Clever Swatch', 'clever-swatch') ?></a>
        </h2>
    </form>
</div>

<div class="zoo-cw-settings-wrapper">
    <?php if ($current_tab == "general"): ?>
        <?php $general_settings = get_option('zoo-cw-settings', true); //print_r($general_settings); die("test");?>
        <?php if (!is_array($general_settings)): $general_settings = array(); endif; ?>
        <form name="zoo-cw-settings-form" action="" method="post">
            <div class="zoo-cw-settings-container">
                <div class="zoo-cw-settings-header">
                    <h3 class="zoo-cw-heading"><?php _e('Global Settings', 'clever-swatch') ?></h3>
                    <div class="zoo-cw-div-wrapper">
                        <table class="zoo-cw-settings-table">
                            <?php
                            $swatch = isset($general_settings['swatch']) ? intval($general_settings['swatch']) : 1;
                            $enable_product_gallery = isset($general_settings['product_gallery']) ? intval($general_settings['product_gallery']) : 1;
                            $display_shape = isset($general_settings['display_shape']) ? $general_settings['display_shape'] : 'square';
                            $display_size = isset($general_settings['display_size']) ? $general_settings['display_size'] : 1;
                            $display_size_width = isset($general_settings['display_size_width']) ? intval($general_settings['display_size_width']) : 20;
                            $display_size_height = isset($general_settings['display_size_height']) ? intval($general_settings['display_size_height']) : $display_size_width;
                            $display_name = isset($general_settings['display_name']) ? intval($general_settings['display_name']) : 1;
                            ?>

                            <tr>
                                <td><?php _e('Enable Clever Swatch', 'clever-swatch'); ?></td>
                                <td>
                                    <label class="toggle">
                                        <input type="checkbox"
                                               name="zoo_cw_enable_swatch" <?php checked($swatch, 1); ?>>
                                        <span class="handle"></span>
                                    </label>
                                </td>
                                <td>
                                    <p class="description"><?php _e('Turn this to "Off" if you don\'t like to use variation swatches.', 'varition-master') ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e('Enable Variation Gallery Images', 'clever-swatch'); ?></td>
                                <td>
                                    <label class="toggle">
                                        <input type="checkbox"
                                               name="zoo_cw_enable_product_gallery" <?php checked($enable_product_gallery, 1); ?>>
                                        <span class="handle"></span>
                                    </label>
                                </td>
                                <td>
                                    <p class="description"><?php _e('Turn this to "Off" if you don\'t like to use variation wise gallery.', 'varition-master') ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e('Swatch option display shape', 'clever-swatch'); ?></td>
                                <td>
                                    <select name="zoo_cw_display_shape" class="zoo_cw_atds zoo_cw_display_shape">
                                        <option value="square" <?php selected($display_shape, 'square'); ?>><?php _e('SQUARE', 'clever-swatch') ?></option>
                                        <option value="circle" <?php selected($display_shape, 'circle'); ?>><?php _e('CIRCLE', 'clever-swatch') ?></option>
                                    </select>

                                </td>
                                <td>
                                    <p class="description"><?php _e('Shape of attribute swatch option.', 'varition-master') ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e('Swatch option display size', 'clever-swatch'); ?></td>
                                <td>
                                    <select name="zoo_cw_display_size" class="zoo_cw_atds zoo_cw_display_size">
                                        <option value="1" <?php selected($display_size, 1); ?>><?php _e('20px * 20px', 'clever-swatch') ?></option>
                                        <option value="2" <?php selected($display_size, 2); ?>><?php _e('40px * 40px', 'clever-swatch') ?></option>
                                        <option value="3" <?php selected($display_size, 3); ?>><?php _e('60px * 60px', 'clever-swatch') ?></option>
                                        <option value="custom" <?php selected($display_size, 'custom'); ?>><?php _e('Custom', 'clever-swatch') ?></option>
                                    </select>

                                    <input type="text" placeholder="size in px." id="zoo_cw_display_size_width"
                                           name="zoo_cw_display_size_width"
                                           value="<?php echo $display_size_width; ?>" <?php if ($display_size != "custom"): echo 'style="display: none;"'; endif; ?>>
                                    <input type="text" placeholder="size in px." id="zoo_cw_display_size_height"
                                           name="zoo_cw_display_size_height"
                                           value="<?php echo $display_size_height; ?>" <?php if ($display_size != "custom"): echo 'style="display: none;"'; endif; ?>>

                                </td>
                                <td>
                                    <p class="description"><?php _e('Size of attribute swatch option.', 'varition-master') ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e('Swatch option show name?', 'clever-swatch'); ?></td>
                                <td>
                                    <select name="zoo_cw_display_name" class="zoo_cw_atds">
                                        <option value="1" <?php selected($display_name, 1); ?>><?php _e('Yes', 'clever-swatch') ?></option>
                                        <option value="0" <?php selected($display_name, 0); ?>><?php _e('No', 'clever-swatch') ?></option>
                                    </select>
                                </td>
                                <td>
                                    <p class="description"><?php _e('Show name of attribute swatch option.', 'varition-master') ?></p>
                                </td>
                            </tr>

                        </table>
                        <p class="description"><?php _e('Please go to the attribute settings for setting up global term thumbnails.', 'varition-master'); ?>
                            <a href="<?php echo admin_url('edit.php?post_type=product&page=product_attributes'); ?>"><?php _e('Click Here', 'clever-swatch'); ?></a>
                        </p>
                    </div>
                </div>
            </div>
            <p class="submit">
                <input class="button-primary woocommerce-save-button" type="submit" value="Save changes" name="save">
            </p>
        </form>
    <?php elseif ($current_tab == "import-export"):

        ?>
        <div class="zoo-cw-term-import"><h3 class="zoo-cw-heading"><?php _e('Attributes Term', 'clever-swatch'); ?></h3>
        <div class="zoo-cw-wrapper"><?php

            require_once ZOO_CW_DIRPATH . 'includes/admin/settings/zoo-cw-attrImport.php';

            ?></div></div>
        <div class="zoo-cw-import-gallery"><h3
                class="zoo-cw-heading"><?php _e('Variations Gallery', 'clever-swatch'); ?></h3>
        <div class="zoo-cw-wrapper"><?php

            require_once ZOO_CW_DIRPATH . 'includes/admin/settings/zoo-cw-galleryImport.php';
            ?></div></div><?php
    endif; ?>
</div>
