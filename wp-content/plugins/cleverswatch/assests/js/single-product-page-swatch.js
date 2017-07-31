jQuery( document ).ready(function() {
    jQuery('.variations_form .variations .value input').change(function(){
        var parent_wrap = jQuery(this).parent('.value');
        var selected_value = parent_wrap.find('input:checked').val();
        parent_wrap.find('select').val(selected_value).change();
    });

    jQuery('.variations_form .variations .value .reset_variations').click(function(){
        jQuery('.variations_form .variations .value input').prop('checked', false);
    });
});