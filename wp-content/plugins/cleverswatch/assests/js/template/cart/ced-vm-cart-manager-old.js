var ajax_url = zoo_cw_params.ajax_url;

jQuery(document).ready(function(){
	
	zoo_cw_image_change_on_variation_popup();
	
	jQuery(".changemenow").on('click',function(){
		jQuery(".zoo-cw-overlay").show();
		jQuery("#zoo-cw-variation-container").hide();
		jQuery("#zoo-cw-cart-loader").show();
		var proID = jQuery(this).attr('vID');
		var vID = jQuery(this).attr('variation_id');
		var $thisTR = jQuery(this).closest('td').parent();
		var extdQty = jQuery($thisTR).find('.input-text').val();
		var $cartItem = jQuery(this).parent().parent();
		var variationID = jQuery(this).attr('variation_id');	
		
		if(Math.floor(extdQty) != extdQty || !jQuery.isNumeric(extdQty))
			extdQty = 1;
		
		
		jQuery.ajax({
			  url: ajax_url,
			  cache: false,
			  type: "POST",
			  headers : { "cache-control": "no-cache" },
			  data: {
				'action': 'zoo_cw_get_product_html',
				'product_id': proID,
				'variationID' : variationID,
			  },
			  success:function(response) {
				  jQuery("#zoo-cw-cart-loader").hide();
				  var html = '<input type="hidden" id="zoo_cw_prevproid" value="'+vID+'"><p class="close_icon"><span class="zoo-cw-close-btn">&times;</span></p>';
				  
				  response = html+response;
				  
				  jQuery( '#zoo-cw-variation-container' ).html(response);
				  $form = jQuery( '#zoo-cw-variation-container' ).find( '.variations_form' );
				  
				  if ($form) {
					  $form.wc_variation_form();
			      }
				  
				  //changing the add-to-cart button to update and hiding quantity field.
					var $cartDiv = jQuery('form.variations_form').find('div .woocommerce-variation-add-to-cart');
					jQuery($cartDiv).find('.input-text').hide();
					jQuery($cartDiv).find('.input-text').val(extdQty);
					$form.find( '.variations select' ).last().trigger('change');
					//$form.trigger( 'reset_data' );
					jQuery($cartDiv).find('.single_add_to_cart_button').html('Update');
					//jQuery($cartDiv).find('.single_add_to_cart_button').attr('disabled','disabled');
				  jQuery("#zoo-cw-variation-container").show();
				  
			  }
		});
		
	});
	
	jQuery(document).on('click','.reset_variations',function(){
		
		jQuery('form.variations_form').find('div .woocommerce-variation-add-to-cart .input-text').hide();
		jQuery(".zoo-cw-stock-error").html('');
		jQuery(".zoo-cw-stock-error").hide();
	});
	
	jQuery(document).on("click",".single_add_to_cart_button",function(e){
		
		e.preventDefault();
		
		var product_id = jQuery('#zoo-cw-variation-container').find('input[name="product_id"]').val();
		var quantity = jQuery('#zoo-cw-variation-container').find('input[name="quantity"]').val();
		var PrevProId = jQuery("#zoo_cw_prevproid").val();
		var variation_id = jQuery('#zoo-cw-variation-container').find('input[name="variation_id"]').val();
		
		var variation = {};
		
		variations_html = jQuery("#zoo-cw-variation-container").find( 'select[name^=attribute]' );
		
		variations_html.each( function() {
			
			var attrName = jQuery(this).attr('name');
			var attrValue = jQuery(this).val();
			variation[attrName] = attrValue;
		});
		
		jQuery.ajax({
			  url: ajax_url,
			  cache: false,
			  type: "POST",
			  headers : { "cache-control": "no-cache" },
			  data: {
				'action': 'zoo_cw_update_variation_in_cart',
				'product_id': product_id,
				'quantity':quantity,
				'PrevProId':PrevProId,
				'variation_id':variation_id,
				'variation':variation
			  },
			  success:function(response) {
				  
				  if(response==true){
					  
					  location.reload();
				  }else{
					  jQuery(".zoo-cw-stock-error").html(response);
					  jQuery(".zoo-cw-stock-error").show();
					  jQuery('form.variations_form').find('div .woocommerce-variation-add-to-cart .input-text').show();
				  }
			  }
		});
		
	});
	
	jQuery(document).on('click','.zoo-cw-close-btn',function(){
		
		jQuery("#zoo-cw-variation-container").html( ' ' );
		jQuery("#zoo-cw-variation-container").hide();
		jQuery(".zoo-cw-overlay").hide();
	});
});

function zoo_cw_image_change_on_variation_popup()
{

	/* little trick to handle image change :: saving default image */
	var zoo_caf_default_img_src = jQuery( 'div.images img' ).attr( 'src' );
	var zoo_caf_default_img_alt = jQuery( 'div.images img' ).attr( 'alt' );

	/*global wc_add_to_cart_variation_params, wc_cart_fragments_params */
/*
* This code is used from WooCommerce. We are using this js to implement 
* single page variation change event
*/
(function ( $, window, document, undefined ) {

	$.fn.wc_variation_form = function() {
		var $form                  = this,
			$single_variation      = $form.find( '.single_variation' ),
			$product               = $form.closest( '.product' ),
			$product_id            = parseInt( $form.data( 'product_id' ), 10 ),
			$product_variations    = $form.data( 'product_variations' ),
			$use_ajax              = $product_variations === false,
			$xhr                   = false,
			$reset_variations      = $form.find( '.reset_variations' ),
			template               = wp.template( 'variation-template' ),
			unavailable_template   = wp.template( 'unavailable-variation-template' ),
			$single_variation_wrap = $form.find( '.single_variation_wrap' );

		// Always visible since 2.5.0
		$single_variation_wrap.show();

		// Unbind any existing events
		$form.unbind( 'check_variations update_variation_values found_variation' );
		$form.find( '.reset_variations' ).unbind( 'click' );
		$form.find( '.variations select' ).unbind( 'change focusin' );

		// Bind new events to form
		$form

		// On clicking the reset variation button
		.on( 'click', '.reset_variations', function( event ) {
			event.preventDefault();
			$form.find( '.variations select' ).val( '' ).change();
			$form.trigger( 'reset_data' );
		} )

		// When the variation is hidden
		.on( 'hide_variation', function( event ) {
			event.preventDefault();
			$form.find( '.single_add_to_cart_button' ).attr( 'disabled', 'disabled' ).attr( 'title', wc_add_to_cart_variation_params.i18n_make_a_selection_text );
		} )

		// When the variation is revealed
		.on( 'show_variation', function( event, variation, purchasable ) {
			event.preventDefault();
			if ( purchasable ) {
				$form.find( '.single_add_to_cart_button' ).removeAttr( 'disabled' ).removeAttr( 'title' );
			} else {
				$form.find( '.single_add_to_cart_button' ).attr( 'disabled', 'disabled' ).attr( 'title', wc_add_to_cart_variation_params.i18n_unavailable_text );
			}
		} )

		// Reload product variations data
		.on( 'reload_product_variations', function() {
			$product_variations = $form.data( 'product_variations' );
			$use_ajax           = $product_variations === false;
		} )

		// Reset product data
		.on( 'reset_data', function() {
			$('.sku').wc_reset_content();
			$('.product_weight').wc_reset_content();
			$('.product_dimensions').wc_reset_content();
			$form.trigger( 'reset_image' );
			$single_variation.slideUp( 200 ).trigger( 'hide_variation' );

			/* little trick to reset image */
			$( 'div.images .woocommerce-main-image img' ).attr( 'src', zoo_caf_default_img_src);
			$( 'div.images .woocommerce-main-image img' ).attr( 'title', '' );
			$( 'div.images .woocommerce-main-image img' ).attr( 'alt', zoo_caf_default_img_alt );
			$( 'div.images .woocommerce-main-image img' ).attr( 'srcset', '' );
			$( 'div.images .woocommerce-main-image img' ).attr( 'sizes', '' );
			$( 'div.images .woocommerce-main-image img' ).attr( 'href', '' );
			$( 'div.images .woocommerce-main-image img' ).attr( 'title', '' );
		
		} )

		// Reset product image
		.on( 'reset_image', function() {
			$form.wc_variations_image_update( false );
		} )

		// On changing an attribute
		.on( 'change', '.variations select', function() {
			$form.find( 'input[name="variation_id"], input.variation_id' ).val( '' ).change();
			$form.find( '.wc-no-matching-variations' ).remove();

			if ( $use_ajax ) {
				if ( $xhr ) {
					$xhr.abort();
				}

				var all_attributes_chosen  = true;
				var some_attributes_chosen = false;
				var data                   = {};

				$form.find( '.variations select' ).each( function() {
					var attribute_name = $( this ).data( 'attribute_name' ) || $( this ).attr( 'name' );

					if ( $( this ).val().length === 0 ) {
						all_attributes_chosen = false;
					} else {
						some_attributes_chosen = true;
					}

					data[ attribute_name ] = $( this ).val();
				});

				if ( all_attributes_chosen ) {
					// Get a matchihng variation via ajax
					data.product_id = $product_id;

					$xhr = $.ajax( {
						url: wc_cart_fragments_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'get_variation' ),
						type: 'POST',
						data: data,
						success: function( variation ) {
							if ( variation ) {
								$form.trigger( 'found_variation', [ variation ] );
							} else {
								$form.trigger( 'reset_data' );
								$form.find( '.single_variation' ).after( '<p class="wc-no-matching-variations woocommerce-info">' + wc_add_to_cart_variation_params.i18n_no_matching_variations_text + '</p>' );
								$form.find( '.wc-no-matching-variations' ).slideDown( 200 );
							}
						}
					} );
				} else {
					$form.trigger( 'reset_data' );
				}
				if ( some_attributes_chosen ) {
					if ( $reset_variations.css( 'visibility' ) === 'hidden' ) {
						$reset_variations.css( 'visibility', 'visible' ).hide().fadeIn();
					}
				} else {
					$reset_variations.css( 'visibility', 'hidden' );
				}
			} else {
				$form.trigger( 'woocommerce_variation_select_change' );
				$form.trigger( 'check_variations', [ '', false ] );
				$( this ).blur();
			}

			// added to get around variation image flicker issue
			$( '.product.has-default-attributes > .images' ).fadeTo( 200, 1 );

			// Custom event for when variation selection has been changed
			$form.trigger( 'woocommerce_variation_has_changed' );
		} )

		// Upon gaining focus
		.on( 'focusin touchstart', '.variations select', function() {
			$( this ).find( 'option:selected' ).attr( 'selected', 'selected' );

			if ( ! $use_ajax ) {
				$form.trigger( 'woocommerce_variation_select_focusin' );
				$form.trigger( 'check_variations', [ $( this ).data( 'attribute_name' ) || $( this ).attr( 'name' ), true ] );
			}
		} )

		// Show single variation details (price, stock, image)
		.on( 'found_variation', function( event, variation ) {
			var $sku        = $product.find( '.product_meta' ).find( '.sku' ),
				$weight     = $product.find( '.product_weight' ),
				$dimensions = $product.find( '.product_dimensions' ),
				$qty        = $single_variation_wrap.find( '.quantity' ),
				purchasable = true;

			// Display SKU
			if ( variation.sku ) {
				$sku.wc_set_content( variation.sku );
			} else {
				$sku.wc_reset_content();
			}

			// Display weight
			if ( variation.weight ) {
				$weight.wc_set_content( variation.weight );
			} else {
				$weight.wc_reset_content();
			}

			// Display dimensions
			if ( variation.dimensions ) {
				$dimensions.wc_set_content( variation.dimensions );
			} else {
				$dimensions.wc_reset_content();
			}

			// Show images
			$form.wc_variations_image_update( variation );
			

			// Output correct templates
			var $template_html = '';

			if ( ! variation.variation_is_visible ) {
				$template_html = unavailable_template();
				// w3 total cache inline minification adds CDATA tags around our HTML (sigh)
				$template_html = $template_html.replace( '/*<![CDATA[*/', '' );
				$template_html = $template_html.replace( '/*]]>*/', '' );
				$single_variation.html( $template_html );
				$form.find( 'input[name="variation_id"], input.variation_id' ).val( '' ).change();
			} else {
				$template_html = template( {
					variation:    variation
				} );
				// w3 total cache inline minification adds CDATA tags around our HTML (sigh)
				$template_html = $template_html.replace( '/*<![CDATA[*/', '' );
				$template_html = $template_html.replace( '/*]]>*/', '' );
				$single_variation.html( $template_html );
				$form.find( 'input[name="variation_id"], input.variation_id' ).val( variation.variation_id ).change();
			}

			// Hide or show qty input
			if ( variation.is_sold_individually === 'yes' ) {
				$qty.find( 'input.qty' ).val( '1' ).attr( 'min', '1' ).attr( 'max', '' );
				$qty.hide();
			} else {
				$qty.find( 'input.qty' ).attr( 'min', variation.min_qty ).attr( 'max', variation.max_qty );
				$qty.show();
			}

			// Enable or disable the add to cart button
			if ( ! variation.is_purchasable || ! variation.is_in_stock || ! variation.variation_is_visible ) {
				purchasable = false;
			}

			// Reveal
			if ( $.trim( $single_variation.text() ) ) {
				$single_variation.slideDown( 200 ).trigger( 'show_variation', [ variation, purchasable ] );
			} else {
				$single_variation.show().trigger( 'show_variation', [ variation, purchasable ] );
			}
		})

		// Check variations
		.on( 'check_variations', function( event, exclude, focus ) {
			if ( $use_ajax ) {
				return;
			}

			var all_attributes_chosen  = true,
				some_attributes_chosen = false,
				current_settings       = {},
				$form                  = $( this ),
				$reset_variations      = $form.find( '.reset_variations' );

			$form.find( '.variations select' ).each( function() {
				var attribute_name = $( this ).data( 'attribute_name' ) || $( this ).attr( 'name' );

				if ( $( this ).val().length === 0 ) {
					all_attributes_chosen = false;
				} else {
					some_attributes_chosen = true;
				}

				if ( exclude && attribute_name === exclude ) {
					all_attributes_chosen = false;
					current_settings[ attribute_name ] = '';
				} else {
					// Add to settings array
					current_settings[ attribute_name ] = $( this ).val();
				}
			});

			var matching_variations = wc_variation_form_matcher.find_matching_variations( $product_variations, current_settings );

			if ( all_attributes_chosen ) {

				var variation = matching_variations.shift();

				if ( variation ) {
					$form.trigger( 'found_variation', [ variation ] );
				} else {
					// Nothing found - reset fields
					$form.find( '.variations select' ).val( '' );

					if ( ! focus ) {
						$form.trigger( 'reset_data' );
					}

					window.alert( wc_add_to_cart_variation_params.i18n_no_matching_variations_text );
				}

			} else {

				$form.trigger( 'update_variation_values', [ matching_variations ] );

				if ( ! focus ) {
					$form.trigger( 'reset_data' );
				}

				if ( ! exclude ) {
					$single_variation.slideUp( 200 ).trigger( 'hide_variation' );
				}
			}
			if ( some_attributes_chosen ) {
				if ( $reset_variations.css( 'visibility' ) === 'hidden' ) {
					$reset_variations.css( 'visibility', 'visible' ).hide().fadeIn();
				}
			} else {
				$reset_variations.css( 'visibility', 'hidden' );
			}
		} )

		// Disable option fields that are unavaiable for current set of attributes
		.on( 'update_variation_values', function( event, variations ) {
			if ( $use_ajax ) {
				return;
			}
			// Loop through selects and disable/enable options based on selections
			$form.find( '.variations select' ).each( function( index, el ) {

				var current_attr_name, current_attr_select = $( el );

				// Reset options
				if ( ! current_attr_select.data( 'attribute_options' ) ) {
					current_attr_select.data( 'attribute_options', current_attr_select.find( 'option:gt(0)' ).get() );
				}

				current_attr_select.find( 'option:gt(0)' ).remove();
				current_attr_select.append( current_attr_select.data( 'attribute_options' ) );
				current_attr_select.find( 'option:gt(0)' ).removeClass( 'attached' );
				current_attr_select.find( 'option:gt(0)' ).removeClass( 'enabled' );
				current_attr_select.find( 'option:gt(0)' ).removeAttr( 'disabled' );

				// Get name from data-attribute_name, or from input name if it doesn't exist
				if ( typeof( current_attr_select.data( 'attribute_name' ) ) !== 'undefined' ) {
					current_attr_name = current_attr_select.data( 'attribute_name' );
				} else {
					current_attr_name = current_attr_select.attr( 'name' );
				}

				// Loop through variations
				for ( var num in variations ) {

					if ( typeof( variations[ num ] ) !== 'undefined' ) {

						var attributes = variations[ num ].attributes;

						for ( var attr_name in attributes ) {
							if ( attributes.hasOwnProperty( attr_name ) ) {
								var attr_val = attributes[ attr_name ];

								if ( attr_name === current_attr_name ) {

									var variation_active = '';

									if ( variations[ num ].variation_is_active ) {
										variation_active = 'enabled';
									}

									if ( attr_val ) {

										// Decode entities
										attr_val = $( '<div/>' ).html( attr_val ).text();

										// Add slashes
										attr_val = attr_val.replace( /'/g, '\\\'' );
										attr_val = attr_val.replace( /"/g, '\\\"' );

										// Compare the meerkat
										current_attr_select.find( 'option[value="' + attr_val + '"]' ).addClass( 'attached ' + variation_active );

									} else {

										current_attr_select.find( 'option:gt(0)' ).addClass( 'attached ' + variation_active );

									}
								}
							}
						}
					}
				}

				// Detach unattached
				current_attr_select.find( 'option:gt(0):not(.attached)' ).remove();

				// Grey out disabled
				current_attr_select.find( 'option:gt(0):not(.enabled)' ).attr( 'disabled', 'disabled' );

			});

			// Custom event for when variations have been updated
			$form.trigger( 'woocommerce_update_variation_values' );
		});

		$form.trigger( 'wc_variation_form' );

		return $form;
	};

	/**
	 * Matches inline variation objects to chosen attributes
	 * @type {Object}
	 */
	var wc_variation_form_matcher = {
		find_matching_variations: function( product_variations, settings ) {
			var matching = [];
			for ( var i = 0; i < product_variations.length; i++ ) {
				var variation    = product_variations[i];

				if ( wc_variation_form_matcher.variations_match( variation.attributes, settings ) ) {
					matching.push( variation );
				}
			}
			return matching;
		},
		variations_match: function( attrs1, attrs2 ) {
			var match = true;
			for ( var attr_name in attrs1 ) {
				if ( attrs1.hasOwnProperty( attr_name ) ) {
					var val1 = attrs1[ attr_name ];
					var val2 = attrs2[ attr_name ];
					if ( val1 !== undefined && val2 !== undefined && val1.length !== 0 && val2.length !== 0 && val1 !== val2 ) {
						match = false;
					}
				}
			}
			return match;
		}
	};

	/**
	 * Stores the default text for an element so it can be reset later
	 */
	$.fn.wc_set_content = function( content ) {
		if ( undefined === this.attr( 'data-o_content' ) ) {
			this.attr( 'data-o_content', this.text() );
		}
		this.text( content );
	};

	/**
	 * Stores the default text for an element so it can be reset later
	 */
	$.fn.wc_reset_content = function() {
		if ( undefined !== this.attr( 'data-o_content' ) ) {
			this.text( this.attr( 'data-o_content' ) );
		}
	};

	/**
	 * Stores a default attribute for an element so it can be reset later
	 */
	$.fn.wc_set_variation_attr = function( attr, value ) {

		if ( undefined === this.attr( 'data-o_' + attr ) ) {
			this.attr( 'data-o_' + attr, ( ! this.attr( attr ) ) ? '' : this.attr( attr ) );
		}
		this.attr( attr, value );
	};

	/**
	 * Reset a default attribute for an element so it can be reset later
	 */
	$.fn.wc_reset_variation_attr = function( attr ) {

		if ( undefined !== this.attr( 'data-o_' + attr ) ) {
			this.attr( attr, this.attr( 'data-o_' + attr ) );
		}

	};

	/**
	 * Sets product images for the chosen variation
	 */
	$.fn.wc_variations_image_update = function( variation ) {
		
		var $form             = this,
			$product          = $form.closest('.product'),
			$product_img      = $product.find( 'div.images img:eq(0)' ),
			$product_link     = $product.find( 'div.images a.zoom:eq(0)' );

		if ( variation && variation.image_src && variation.image_src.length > 1 ) {
			
			/* little trick to handle image change */
			$( 'div.images .woocommerce-main-image img' ).attr( 'src', variation.image_src );
			$( 'div.images .woocommerce-main-image img' ).attr( 'title', variation.image_title );
			$( 'div.images .woocommerce-main-image img' ).attr( 'alt', variation.image_title );
			$( 'div.images .woocommerce-main-image img' ).attr( 'srcset', variation.image_srcset );
			$( 'div.images .woocommerce-main-image img' ).attr( 'sizes', variation.image_sizes );
			$( 'div.images .woocommerce-main-image img' ).attr( 'href', variation.image_link );
			$( 'div.images .woocommerce-main-image img' ).attr( 'title', variation.image_caption );

		} else {
			$product_img.wc_reset_variation_attr( 'src' );
			$product_img.wc_reset_variation_attr( 'title' );
			$product_img.wc_reset_variation_attr( 'alt' );
			$product_img.wc_reset_variation_attr( 'srcset' );
			$product_img.wc_reset_variation_attr( 'sizes' );
			$product_link.wc_reset_variation_attr( 'href' );
			$product_link.wc_reset_variation_attr( 'title' );
		}

	};

	$( function() {
		if ( typeof wc_add_to_cart_variation_params !== 'undefined' ) {
			$( '.variations_form' ).each( function() {
				$( this ).wc_variation_form().find('.variations select:eq(0)').change();
			});
		}
	});

})( jQuery, window, document );

}