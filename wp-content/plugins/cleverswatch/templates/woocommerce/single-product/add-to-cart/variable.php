<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see    https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */
if (!defined('ABSPATH')) {
    exit;
}

global $product;

$product_swatch_data_array = get_post_meta($product->get_id(), 'zoo_cw_product_swatch_data', true);
$attribute_keys = array_keys($attributes);

$zoo_clever_swatch_product_page = new Zoo_Clever_Swatch_Product_Page();

$product_swatch_data_array = $zoo_clever_swatch_product_page->prepare_singele_page_data($product, $attributes, $product_swatch_data_array);

do_action('woocommerce_before_add_to_cart_form'); ?>

<form class="variations_form cart" method="post" enctype='multipart/form-data'
      data-product_id="<?php echo absint($product->get_id()); ?>"
      data-product_variations="<?php echo htmlspecialchars(wp_json_encode($available_variations)) ?>">
    <?php do_action('woocommerce_before_variations_form'); ?>

    <?php if (empty($available_variations) && false !== $available_variations) : ?>
        <p class="stock out-of-stock"><?php _e('This product is currently out of stock and unavailable.', 'woocommerce'); ?></p>
    <?php else : ?>
        <table class="variations" cellspacing="0">
            <tbody>
            <?php foreach ($attributes as $attribute_name => $options) : ?>
                <tr>
                    <td class="label">
                        <label for="<?php echo sanitize_title($attribute_name); ?>">
                            <?php echo wc_attribute_label($attribute_name); ?>
                        </label>
                    </td>
                    <td class="value <?php echo($product_swatch_data_array[$attribute_name]['class_attribute']); ?>">
                        <?php
                        $selected = isset($_REQUEST['attribute_' . sanitize_title($attribute_name)])
                            ? wc_clean(stripslashes(urldecode($_REQUEST['attribute_' . sanitize_title($attribute_name)])))
                            : $product->get_variation_default_attribute($attribute_name);
                        wc_dropdown_variation_attribute_options(array('options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected));
                        $zoo_cw_attribute = $product_swatch_data_array[$attribute_name];
                        $zoo_cw_attribute_options = $zoo_cw_attribute['options_data'];
                        ?>

                        <?php foreach ($zoo_cw_attribute_options as $zoo_cw_attribute_option): ?>
                            <div class="zoo-cw-attribute-option">
                                <div class="zoo-cw-attr-item  <?php echo($zoo_cw_attribute['class_options']); ?>">
                                    <?php if ($zoo_cw_attribute['display_type'] == 'color'): ?>
                                        <span style="background-color: <?php echo($zoo_cw_attribute_option['color']); ?>;"
                                              class="zoo-cw-label-color">
                                        </span>
                                    <?php elseif ($zoo_cw_attribute['display_type'] == 'text'): ?>
                                        <span class="zoo-cw-label-text">
                                            <?php echo($zoo_cw_attribute_option['name']); ?>
                                        </span>
                                    <?php elseif ($zoo_cw_attribute['display_type'] == 'image'): ?>
                                        <img src="<?php echo($zoo_cw_attribute_option['image']); ?>">
                                    <?php endif; ?>
                                </div>
                                <?php if ($zoo_cw_attribute['display_name_yn'] == 1): ?>
                                    <span  class="zoo-cw-attr-label"><?php echo($zoo_cw_attribute_option['name']); ?></span>
                                <?php endif; ?>
                                <input type="hidden" name="" value="<?php echo($zoo_cw_attribute_option['value']); ?>">
                            </div>
                        <?php endforeach; ?>

                        <?php echo end($attribute_keys) === $attribute_name ? apply_filters('woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__('Clear', 'woocommerce') . '</a>') : ''; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php do_action('woocommerce_before_add_to_cart_button'); ?>

        <div class="single_variation_wrap">
            <?php
            /**
             * woocommerce_before_single_variation Hook.
             */
            do_action('woocommerce_before_single_variation');

            /**
             * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
             * @since 2.4.0
             * @hooked woocommerce_single_variation - 10 Empty div for variation data.
             * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
             */
            do_action('woocommerce_single_variation');

            /**
             * woocommerce_after_single_variation Hook.
             */
            do_action('woocommerce_after_single_variation');
            ?>
        </div>

        <?php do_action('woocommerce_after_add_to_cart_button'); ?>
    <?php endif; ?>

    <?php do_action('woocommerce_after_variations_form'); ?>
</form>

<?php
do_action('woocommerce_after_add_to_cart_form');
?>
