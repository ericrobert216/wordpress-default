(function ($) {
    'use strict';
    jQuery(document).ready(function () {
        var defaultVariationID = jQuery('form.variations_form input[name=variation_id]').val();
        updateGallery(defaultVariationID, 'clever_swatch_action');

        // change select for woocommerce product page
        jQuery('.variations_form .variations .value').each(function () {
            var variation_wrap = jQuery(this);
            variation_wrap.find('.zoo-cw-attribute-option').click(function () {
                //add class active
                variation_wrap.find('.zoo-cw-attribute-option').removeClass('active');
                jQuery(this).addClass('active');

                var selected_value = jQuery(this).find('input').val();
                variation_wrap.find('select').val(selected_value).change();
            });
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
                var imagesDiv = product.find('div.woocommerce-product-gallery,div.images');
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
                                product.find('div.woocommerce-product-gallery,div.images').each(function () {
                                    jQuery(this).wc_product_gallery();
                                });
                            }
                        }
                    }
                });

            }
        }

        jQuery('.variations_form .variations .value .reset_variations').click(function () {
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
                            product.find('div.woocommerce-product-gallery,div.images').each(function () {
                                jQuery(this).wc_product_gallery();
                            });
                        }
                    }
                }
            });
        });

        $('.zoo-cw-attr-item>span, .zoo-cw-attr-item>img').tooltip({
            tooltipClass: "zoo-cw-tooltip",
            items: "[data-tooltip]",
            content: function () {
                return $(this).data('tooltip');
            }
        });
    });
})(jQuery);