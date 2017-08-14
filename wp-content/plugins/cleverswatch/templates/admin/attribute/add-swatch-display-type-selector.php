<?php
/**
 * @version  1.0.0
 * @package  clever-swatch/templates/admin/attribute
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>


<div class="form-field">
    <label for="zoo_cw_swatch_display_type">Clerver Swatch Type</label>
    <select name="zoo_cw_swatch_display_type" id="" class="zoo_cw_swatch_display_type">
        <option value="select">Select</option>
        <option value="image">Image</option>
        <option value="color">Color</option>
        <option value="text">Text</option>
    </select>
    <p class="description">Display type of swatch attribute</p>
</div>