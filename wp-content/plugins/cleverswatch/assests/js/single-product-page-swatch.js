jQuery( document ).ready(function() {
    var defaultVariationID = jQuery('form.variations_form input[name=variation_id]').val();
    console.log(defaultVariationID);
    updateGallery(defaultVariationID, 'clever_swatch_action');


    // change select for woocommerce product page
    jQuery('.variations_form .variations .value input').change(function(){
        var parent_wrap = jQuery(this).parent('.value');
        var selected_value = parent_wrap.find('input:checked').val();
        parent_wrap.find('select').val(selected_value).change();
    });

    // catch even change variation of product form
    jQuery('form.variations_form input[name=variation_id]').change(function () {
        var variationID = jQuery(this).val();

        updateGallery(variationID, 'clever_swatch_action');
    });

    function updateGallery(variationID, action) {
        if (variationID != '' && variationID != 'undefined' && variationID != null) {
            var ajax_url = zoo_cw_params.ajax_url;
            var form = jQuery('form.variations_form');
            var product = form.closest('.product');
            var imagesDiv = product.find('div.woocommerce-product-gallery');
            var productId = jQuery('input[name="product_id"]').val();

            jQuery.ajax({
                url: ajax_url,
                cache: false,
                type: "POST",
                data: {
                    'action': action,
                    'variation_id': variationID,
                    'product_id': productId
                }, success: function (response) {
                    if (response != '' && response != 'undefined' && response != null) {
                        if (jQuery(imagesDiv).length) {
                            jQuery(imagesDiv).replaceWith(response);
                            jQuery('.woocommerce-product-gallery').each(function () {
                                jQuery(this).wc_product_gallery();
                            });
                        }
                    }
                    if (jQuery.isFunction(jQuery.fn.prettyPhoto)) {
                        inititalize_preetyphoto();
                    }
                }
            });

        }
    }

    jQuery('.variations_form .variations .value .reset_variations').click(function(){
        jQuery('.variations_form .variations .value input').prop('checked', false);


        // reset gallery
        var ajax_url = zoo_cw_params.ajax_url;
        var form = jQuery('form.variations_form');
        var product = form.closest('.product');
        var imagesDiv = product.find('div.woocommerce-product-gallery');
        var productId = jQuery('input[name="product_id"]').val();

        jQuery.ajax({
            url: ajax_url,
            cache: false,
            type: "POST",
            data: {
                'action': 'clever_swatch_action_reset',
                'product_id': productId
            }, success: function (response) {
                if (response != '' && response != 'undefined' && response != null) {
                    if (jQuery(imagesDiv).length) {
                        jQuery(imagesDiv).replaceWith(response);
                        jQuery('.woocommerce-product-gallery').each(function () {
                            jQuery(this).wc_product_gallery();
                        });
                    }
                }
                if (jQuery.isFunction(jQuery.fn.prettyPhoto)) {
                    inititalize_preetyphoto();
                }
            }
        });
    });
});