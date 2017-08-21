jQuery(document).ready(function(){
    jQuery('.zoo-cw-settings-wrapper .zoo_cw_display_size').on('change',function(){
        if (jQuery(this).val() == 'custom') {
            jQuery('.zoo-cw-settings-wrapper #zoo_cw_display_size_width').show();
            jQuery('.zoo-cw-settings-wrapper #zoo_cw_display_size_height').show();
        } else {
            jQuery('.zoo-cw-settings-wrapper #zoo_cw_display_size_width').hide();
            jQuery('.zoo-cw-settings-wrapper #zoo_cw_display_size_height').hide();
        }
    });
});