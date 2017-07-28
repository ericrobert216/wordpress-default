<?php
/**
 * @version  1.0.0
 * @package  clever-swatch/templates/admin
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="form-field term-display-type-wrap">
    <label for="zoo-cw-display-type"><?php _e( 'Display type', 'clever-swatch' ); ?></label>
    <select id="zoo-cw-display-type" name="zoo-cw-display-type" class="postform">
        <option value="0"><?php _e( 'None', 'clever-swatch' ); ?></option>
        <option value="1"><?php _e( 'Image', 'clever-swatch' ); ?></option>
        <option value="2"><?php _e( 'Color', 'clever-swatch' ); ?></option>
    </select>
    <div class="zoo-cw-attr-image-uploader zoo-cw-dt-option" style="display: none;">
        <input type="hidden" class="zoo-cw-selected-attr-img" name="zoo-cw-selected-attr-img">
        <img class="zoo-cw-slctd-img" src="<?php echo wc_placeholder_img_src();?>" alt="<?php _e('Select Image','clever-swatch')?>" height="50px" width="50px">
        <button class="zoo-cw-image-picker" type="button"><?php _e('Browse','clever-swatch');?></button>
    </div>
    <div class="zoo-cw-attr-colorpickerdiv zoo-cw-dt-option"  style="display: none;">
        <input type="text" name="zoo_cw_slctdclr" class="zoo-cw-colorpicker" />
    </div>
</div>
