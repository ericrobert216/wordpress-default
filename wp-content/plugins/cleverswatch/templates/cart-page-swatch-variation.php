<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>


<table class="variations" cellspacing="0" data-product_id="<?php echo($post_id);?>">
    <tbody>
    <?php foreach ($attributes as $attribute_name => $options) : ?>
        <tr>
            <td class="value <?php echo($product_swatch_data_array[$attribute_name]['class_attribute']); ?>">
                <?php
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
                            <?php elseif ($zoo_cw_attribute['display_type'] == 'text' || $zoo_cw_attribute['display_type'] == 'default'): ?>
                                <span class="zoo-cw-label-text">
                                            <?php echo($zoo_cw_attribute_option['name']); ?>
                                        </span>
                            <?php elseif ($zoo_cw_attribute['display_type'] == 'image'): ?>
                                <img src="<?php echo($zoo_cw_attribute_option['image']); ?>">
                            <?php endif; ?>
                        </div>
                        
                    </div>
                <?php endforeach; ?>

            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
