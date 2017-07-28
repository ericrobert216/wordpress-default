var ajaxUrl = zoo_cw_gallery_params.ajax_url;
var defaultGallery = '';
jQuery(document).ready(function(){
	
	defaultGallery = jQuery('form.variations_form').closest('.product').find( 'div.thumbnails' );
	
	//----------------- single page swatches js---------------------------------//
	jQuery('form.variations_form').on( 'click', '.reset_variations', function( event ) {
		
		var form             = jQuery('form.variations_form');
		var product          = form.closest('.product');
		var imagesDiv 		= product.find('div.images');
		var thumbnailDiv 	=  product.find( 'div.thumbnails' );
		
		if(jQuery(thumbnailDiv).length){
			  jQuery(thumbnailDiv).replaceWith(defaultGallery);
		  }else{
			  jQuery(imagesDiv).append(defaultGallery);
		 }
		if ( jQuery.isFunction(jQuery.fn.prettyPhoto) ) {
			inititalize_preetyphoto();
		}
		
	})
	
	//disabling the swathces for fields that are unavaiable for current set of attributes
	
//------------------swathces images js end-------------------------------//
	
//------------------------variation gallery images management start------------//
	
	jQuery('form.variations_form').on('change','input[name=variation_id]',function(){
		
		var variationID = jQuery(this).val();
		var form             = jQuery('form.variations_form');
		var product          = form.closest('.product');
		var imagesDiv 		= product.find('div.images');
		var thumbnailDiv 	=  product.find( 'div.thumbnails' );
		var thumb_images     = product.find( 'div.thumbnails img' );
		var productId = jQuery('input[name="product_id"]').val();
		
		if(variationID != '' && variationID != 'undefined' && variationID != null ){
			
			jQuery.ajax({
				  url: ajaxUrl,
				  cache: false,
				  type: "POST",
				  data: {
					'action': 'zoo_cw_get_variation_gallery_for_single',
					'reset': 0,
					'variation_id': variationID,
					'product_id': productId
				  },success:function(response) {
					  
					  var form             = jQuery('form.variations_form');
					  var product          = form.closest('.product');
					  
					  if(response != '' && response != 'undefined' && response != null){
						  
						  if(jQuery(thumbnailDiv).length){
							  jQuery(thumbnailDiv).replaceWith(response);
						  }else{
							  jQuery(imagesDiv).append(response);
						  }
						  
					  }else{
						  if(jQuery(thumbnailDiv).length){
							  jQuery(thumbnailDiv).replaceWith(defaultGallery);
						  }else{
							  jQuery(imagesDiv).append(defaultGallery);
						 }
					  }
					  if ( jQuery.isFunction(jQuery.fn.prettyPhoto) ) {
							inititalize_preetyphoto();
					  }
				  }
			});
			
		}else{
			
			//alert('null');
		}
	});
	
	
//-------------------------end of variation gallery images handling-------------//
});

function inititalize_preetyphoto(){
	jQuery("a.zoom").prettyPhoto({
		hook: 'data-rel',
		social_tools: false,
		theme: 'pp_woocommerce',
		horizontal_padding: 20,
		opacity: 0.8,
		deeplinking: false
	});
	jQuery("a[data-rel^='prettyPhoto']").prettyPhoto({
		hook: 'data-rel',
		social_tools: false,
		theme: 'pp_woocommerce',
		horizontal_padding: 20,
		opacity: 0.8,
		deeplinking: false
	});
}