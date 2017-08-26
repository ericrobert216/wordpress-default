(function ($) {
    'use strict';
    jQuery(document).ready(function () {
        jQuery('.product.product-type-variable').each(function () {
            var product_li = jQuery(this);
            var product_id = jQuery(this).find('.zoo-cw-wrap-shop').data('product_id');
            var selected_options = {};
            product_li.find('.zoo-cw-group-attribute').each(function () {
                var product_attribute_wrapper = jQuery(this);
                product_attribute_wrapper.find('.zoo-cw-attribute-option').click(function () {
                    product_attribute_wrapper.find('.zoo-cw-attribute-option.active').removeClass('active');
                    jQuery(this).addClass('active');
                    var selected_value = jQuery(this).find('input').val();
                    var attribute_name = jQuery(this).find('input').data('attribute_name');
                    selected_options[attribute_name] = selected_value;
                    updateProductImage(product_li, product_id, selected_options);
                });
            });
        });

        function updateProductImage(product_li, product_id, selected_options) {
            console.log(selected_options);
            var ajax_url = zoo_cw_params.ajax_url;

            jQuery.ajax({
                url: ajax_url,
                cache: false,
                type: "POST",
                data: {
                    'action': 'zoo_cw_shop_page_swatch',
                    'selected_options': selected_options,
                    'product_id': product_id
                }, success: function (response) {
                    var data = response;

                    console.log(data);
                    if (data['result'] == 'done') {
                        product_li.find('.wp-post-image')
                            .attr('src', data['image_src'])
                            .attr('srcset', data['image_srcset']);
                    }
                }
            });
        }

        $('.zoo-cw-attr-item>span, .zoo-cw-attr-item>img').tooltip({
            tooltipClass: "zoo-cw-tooltip",
            items: "[data-tooltip]",
            content: function () {
                return $(this).data('tooltip');
            }
        });
    });
})(jQuery);