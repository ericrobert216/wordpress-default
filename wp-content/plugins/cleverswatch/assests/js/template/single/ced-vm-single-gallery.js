var ajaxUrl = ced_vm_gallery_params.ajax_url;
var defaultGallery = '';
jQuery(document).ready(function(){
	
	defaultGallery = jQuery('form.variations_form').closest('.product').find( 'div.woocommerce-product-gallery' );
	var all_data={};
	//----------------- single page swatches js---------------------------------//
	jQuery('form.variations_form').on( 'click', '.reset_variations', function( event ) {
		
		var form             = jQuery('form.variations_form');
		var product          = form.closest('.product');
		var imagesDiv 		= product.find('div.woocommerce-product-gallery');
		var productId = jQuery('input[name="product_id"]').val();
		
		jQuery.ajax({
			url: ajaxUrl,
			cache: false,
			type: "POST",
			data: {
				'action': 'ced_vm_get_variation_gallery_for_reset_single',				
				'productId': productId
			},success:function(response) {

				if(response != '' && response != 'undefined' && response != null){

					if(jQuery(imagesDiv).length){
						jQuery(imagesDiv).replaceWith(response);

						jQuery( '.woocommerce-product-gallery' ).each( function() {
							jQuery( this ).wc_product_gallery();
							
						} );						 
					}					  
				}
				if ( jQuery.isFunction(jQuery.fn.prettyPhoto) ) {
					inititalize_preetyphoto();
				}
			}
		});
		
		if ( jQuery.isFunction(jQuery.fn.prettyPhoto) ) {
			inititalize_preetyphoto();
		}
		

	});
	
	//disabling the swathces for fields that are unavaiable for current set of attributes
	
//------------------swathces images js end-------------------------------//

//------------------------variation gallery images management start------------//

jQuery('form.variations_form').on('change','input[name=variation_id]',function(){

	var variationID = jQuery(this).val();
	var form             = jQuery('form.variations_form');
	var product          = form.closest('.product');
	var imagesDiv 		= product.find('div.woocommerce-product-gallery');

	var productId = jQuery('input[name="product_id"]').val();

	if(variationID != '' && variationID != 'undefined' && variationID != null ){

		jQuery.ajax({
			url: ajaxUrl,
			cache: false,
			type: "POST",
			data: {
				'action': 'ced_vm_get_variation_gallery_for_single',
				'reset': 0,
				'variation_id': variationID,
				'product_id': productId
			},success:function(response) {

				if(response != '' && response != 'undefined' && response != null){

					if(jQuery(imagesDiv).length){
						jQuery(imagesDiv).replaceWith(response);

						jQuery( '.woocommerce-product-gallery' ).each( function() {
							jQuery( this ).wc_product_gallery();

						} );						 
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
	/*
	* This code is used from WooCommerce. We are using this js to implement 
	* single page gallery change with flexslider
	*
	*/
	var ProductGallery = function( $target, args ) {
		this.$current_theme = ced_vm_gallery_params.current_theme;
		console.log(this.$current_theme);
		if(this.$current_theme = 'Avada'){
			this.$target = $target;

			this.$images = jQuery( '.woocommerce-product-gallery__image', $target );


		// No images? Abort.
		if ( 0 === this.$images.length ) {
			this.$target.css( 'opacity', 1 );
			return;
		}
		jQuery(document).find('.avada-single-product-gallery-wrapper').addClass('vm-custom-class');
		// Make this object available.
		$target.data( 'product_gallery', this );

		// Pick functionality to initialize...
		this.flexslider_enabled = jQuery.isFunction( jQuery.fn.flexslider ) && ced_vm_gallery_params.flexslider_enabled;
		this.zoom_enabled       = jQuery.isFunction( jQuery.fn.zoom ) && ced_vm_gallery_params.zoom_enabled;
		this.photoswipe_enabled = typeof PhotoSwipe !== 'undefined' && ced_vm_gallery_params.photoswipe_enabled;

		// ...also taking args into account.
		if ( args ) {
			this.flexslider_enabled = false === args.flexslider_enabled ? false : this.flexslider_enabled;
			this.zoom_enabled       = false === args.zoom_enabled ? false : this.zoom_enabled;
			this.photoswipe_enabled = false === args.photoswipe_enabled ? false : this.photoswipe_enabled;
		}

		// Bind functions to this.
		this.initFlexslider       = this.initFlexslider.bind( this );
		this.initZoom             = this.initZoom.bind( this );
		this.onResetSlidePosition = this.onResetSlidePosition.bind( this );
		this.getGalleryItems      = this.getGalleryItems.bind( this );
		this.openPhotoswipe       = this.openPhotoswipe.bind( this );

		if ( this.flexslider_enabled ) {
			this.initFlexslider();
			$target.on( 'woocommerce_gallery_reset_slide_position', this.onResetSlidePosition );
		} else {
			this.$target.css( 'opacity', 1 );
		}

		if ( this.zoom_enabled ) {
			this.initZoom();
			$target.on( 'woocommerce_gallery_init_zoom', this.initZoom );
		}
		if ( this.photoswipe_enabled ) {
			this.initPhotoswipe();
		}
	}
	else
	{
			this.$target = $target;

			this.$images = jQuery( '.woocommerce-product-gallery__image', $target );


		// No images? Abort.
		if ( 0 === this.$images.length ) {
			this.$target.css( 'opacity', 1 );
			return;
		}
		// Make this object available.
		$target.data( 'product_gallery', this );

		// Pick functionality to initialize...
		this.flexslider_enabled = jQuery.isFunction( jQuery.fn.flexslider ) && ced_vm_gallery_params.flexslider_enabled;
		this.zoom_enabled       = jQuery.isFunction( jQuery.fn.zoom ) && ced_vm_gallery_params.zoom_enabled;
		this.photoswipe_enabled = typeof PhotoSwipe !== 'undefined' && ced_vm_gallery_params.photoswipe_enabled;

		// ...also taking args into account.
		if ( args ) {
			this.flexslider_enabled = false === args.flexslider_enabled ? false : this.flexslider_enabled;
			this.zoom_enabled       = false === args.zoom_enabled ? false : this.zoom_enabled;
			this.photoswipe_enabled = false === args.photoswipe_enabled ? false : this.photoswipe_enabled;
		}

		// Bind functions to this.
		this.initFlexslider       = this.initFlexslider.bind( this );
		this.initZoom             = this.initZoom.bind( this );
		this.onResetSlidePosition = this.onResetSlidePosition.bind( this );
		this.getGalleryItems      = this.getGalleryItems.bind( this );
		this.openPhotoswipe       = this.openPhotoswipe.bind( this );

		if ( this.flexslider_enabled ) {
			this.initFlexslider();
			$target.on( 'woocommerce_gallery_reset_slide_position', this.onResetSlidePosition );
		} else {
			this.$target.css( 'opacity', 1 );
		}

		if ( this.zoom_enabled ) {
			this.initZoom();
			$target.on( 'woocommerce_gallery_init_zoom', this.initZoom );
		}
		if ( this.photoswipe_enabled ) {
			this.initPhotoswipe();
		}
	}
};
ProductGallery.prototype.initFlexslider = function() {
	var images  = this.$images,
	$target = this.$target;

	$target.flexslider( {
		selector:       '.woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image',
		animation:      ced_vm_gallery_params.flexslider.animation,
		smoothHeight:   ced_vm_gallery_params.flexslider.smoothHeight,
		directionNav:   ced_vm_gallery_params.flexslider.directionNav,
		controlNav:     ced_vm_gallery_params.flexslider.controlNav,
		slideshow:      ced_vm_gallery_params.flexslider.slideshow,
		animationSpeed: ced_vm_gallery_params.flexslider.animationSpeed,
			animationLoop:  ced_vm_gallery_params.flexslider.animationLoop, // Breaks photoswipe pagination if true.
			start: function() {
				$target.css( 'opacity', 1 );

				var largest_height = 0;

				images.each( function() {
					var height = jQuery( this ).height();

					if ( height > largest_height ) {
						largest_height = height;
					}
				} );

				images.each( function() {
					jQuery( this ).css( 'min-height', largest_height );
				} );
				
			}
		} );
};
ProductGallery.prototype.initZoom = function() {
	var zoomTarget   = this.$images,
	galleryWidth = this.$target.width(),
	zoomEnabled  = false;

	if ( ! this.flexslider_enabled ) {
		zoomTarget = zoomTarget.first();
	}

	jQuery( zoomTarget ).each( function( index, target ) {
		var image = jQuery( target ).find( 'img' );

		if ( image.data( 'large_image_width' ) > galleryWidth ) {
			zoomEnabled = true;
			return false;
		}
	} );

		// But only zoom if the img is larger than its container.
		if ( zoomEnabled ) {
			var zoom_options = {
				touch: false
			};

			if ( 'ontouchstart' in window ) {
				zoom_options.on = 'click';
			}

			zoomTarget.trigger( 'zoom.destroy' );
			zoomTarget.zoom( zoom_options );
		}
	};
	ProductGallery.prototype.initPhotoswipe = function() {
		if ( this.zoom_enabled && this.$images.length > 0 ) {
			this.$target.prepend( '<a href="#" class="woocommerce-product-gallery__trigger">🔍</a>' );
			this.$target.on( 'click', '.woocommerce-product-gallery__trigger', this.openPhotoswipe );
		}
		this.$target.on( 'click', '.woocommerce-product-gallery__image a', this.openPhotoswipe );
	};
	/**
	 * Reset slide position to 0.
	 */
	 ProductGallery.prototype.onResetSlidePosition = function() {
	 	this.$target.flexslider( 0 );
	 };

	/**
	 * Get product gallery image items.
	 */
	 ProductGallery.prototype.getGalleryItems = function() {
	 	var $slides = this.$images,
	 	items   = [];

	 	if ( $slides.length > 0 ) {
	 		$slides.each( function( i, el ) {
	 			var img = jQuery( el ).find( 'img' ),
	 			large_image_src = img.attr( 'data-large_image' ),
	 			large_image_w   = img.attr( 'data-large_image_width' ),
	 			large_image_h   = img.attr( 'data-large_image_height' ),
	 			item            = {
	 				src: large_image_src,
	 				w:   large_image_w,
	 				h:   large_image_h,
	 				title: img.attr( 'title' )
	 			};
	 			items.push( item );
	 		} );
	 	}

	 	return items;
	 };

	/**
	 * Open photoswipe modal.
	 */
	 ProductGallery.prototype.openPhotoswipe = function( e ) {
	 	e.preventDefault();

	 	var pswpElement = jQuery( '.pswp' )[0],
	 	items       = this.getGalleryItems(),
	 	eventTarget = jQuery( e.target ),
	 	clicked;


	 	if ( ! eventTarget.is( '.woocommerce-product-gallery__trigger' ) ) {
	 		if ( ! this.flexslider_enabled ) {
	 			clicked = eventTarget.closest( '.woocommerce-product-gallery__image' );
	 		}
	 		else
	 		{
	 			clicked = eventTarget.parents().find('div.woocommerce-product-gallery__image.flex-active-slide');
	 		}			
	 	} else {

	 		clicked = this.$target.find( '.flex-active-slide' );
	 	}

	 	var options = {
	 		index:                 jQuery( clicked ).index(),
	 		shareEl:               false,
	 		closeOnScroll:         false,
	 		history:               false,
	 		hideAnimationDuration: 0,
	 		showAnimationDuration: 0
	 	};

		// Initializes and opens PhotoSwipe.
		var photoswipe = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options );
		photoswipe.init();
	};


	jQuery.fn.wc_product_gallery = function( args ) {
		new ProductGallery( this, args );
		return this;
	};

	
	
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