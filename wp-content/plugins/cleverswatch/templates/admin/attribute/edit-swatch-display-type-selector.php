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


<tr class="form-field form-required">
    <th scope="row" valign="top">
        <label for="attribute_type">Clerver Swatch Type</label>
    </th>
    <td>
        <select name="zoo_cw_swatch_display_type" id="" class="zoo_cw_swatch_display_type">
            <option value="select" <?php selected($selected_type, 'select'); ?>>Select</option>
            <option value="image" <?php selected($selected_type, 'image'); ?>>Image</option>
            <option value="color" <?php selected($selected_type, 'color'); ?>>Color</option>
            <option value="text" <?php selected($selected_type, 'text'); ?>>Text</option>
        </select>
        <p class="description">Display type of swatch attribute</p>
    </td>
</tr>