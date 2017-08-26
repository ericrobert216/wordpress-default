<?php
/**
 * Shop page Swatch
 *
**/
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="zoo-cw-wrap-shop" data-product_id="<?php the_ID();?>">
    <?php foreach ($attributes as $attribute_name => $options) :
        $zoo_cw_attribute = $product_swatch_data_array[$attribute_name];
        $zoo_cw_attribute_options = $zoo_cw_attribute['options_data'];
        ?>
        <div class="zoo-cw-group-attribute <?php echo esc_attr('attribute_'.$attribute_name); ?>">
            <?php
            foreach ($zoo_cw_attribute_options as $zoo_cw_attribute_option): ?>
                <div class="zoo-cw-attribute-option">
                    <div class="zoo-cw-attr-item <?php echo esc_attr($zoo_cw_attribute['class_options']); ?>">
                        <?php if ($zoo_cw_attribute['display_type'] == 'color'): ?>
                            <span style="background-color: <?php echo esc_attr($zoo_cw_attribute_option['color']); ?>;"
                                  class="zoo-cw-label-color" data-tooltip="<?php echo esc_attr($zoo_cw_attribute_option['name']); ?>">
                                        </span>
                        <?php elseif ($zoo_cw_attribute['display_type'] == 'text'): ?>
                            <span class="zoo-cw-label-text">
                                            <?php echo esc_html($zoo_cw_attribute_option['name']); ?>
                                        </span>
                        <?php elseif ($zoo_cw_attribute['display_type'] == 'image'): ?>
                            <img src="<?php echo esc_url($zoo_cw_attribute_option['image']); ?>"  data-tooltip="<?php echo esc_attr($zoo_cw_attribute_option['name']); ?>" alt="<?php echo esc_attr($zoo_cw_attribute_option['name']); ?>"/>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="<?php echo esc_attr('attribute_'.$attribute_name); ?>" data-attribute_name="<?php echo esc_attr('attribute_'.strtolower($attribute_name)); ?>" value="<?php echo esc_attr($zoo_cw_attribute_option['value']); ?>">
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>