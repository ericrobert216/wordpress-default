<?php
/**
 * @version  1.0.0
 * @package  clever-swatch/templates/admin
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */
?>


<tr class="form-field">
    <th scope="row" valign="top"><label><?php _e( 'Display type', 'variation-master' ); ?></label></th>
    <td>
        <select id="cs-cw-display-type" name="cs-cw-display-type" class="postform">
            <option value="0" <?php selected($dt,0);?>><?php _e( 'None', 'variation-master' ); ?></option>
            <option value="1" <?php selected($dt,1);?>><?php _e( 'Image', 'variation-master' ); ?></option>
            <option value="2" <?php selected($dt,2);?>><?php _e( 'Color', 'variation-master' ); ?></option>
        </select>
    </td>
</tr>
<tr class="form-field cs-cw-attr-colorpickerdiv cs-cw-dt-option" <?php if($dt != 2): echo 'style="display:none";'; endif;?>>
    <th scope="row" valign="top"><label><?php _e( 'Select Color', 'variation-master' ); ?></label></th>
    <td>
        <input type="text" name="cs_cw_slctdclr" class="cs-cw-colorpicker" value="<?php echo $color_code; ?>"/>
    </td>
</tr>
<tr class="form-field cs-cw-attr-image-uploader cs-cw-dt-option" <?php if($dt != 1): echo 'style="display:none";'; endif;?>>
    <th scope="row" valign="top"><label><?php _e( 'Select/Upload Image', 'variation-master' ); ?></label></th>
    <td>
        <input type="hidden" class="cs-cw-selected-attr-img" name="cs-cw-selected-attr-img" value="<?php echo $image;?>">
        <img class="cs-cw-slctd-img" src="<?php echo $image;?>" alt="<?php _e('Select Image','variation-master')?>" height="50px" width="50px">
        <button class="cs-cw-image-picker" type="button"><?php _e('Browse','variation-master');?></button>
    </td>
</tr>
