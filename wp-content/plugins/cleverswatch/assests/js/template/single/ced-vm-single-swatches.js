var ajax_url = ced_vm_params.ajax_url;
jQuery(document).ready(function(){
	
	//----------------- single page swatches js---------------------------------//
	jQuery('form.variations_form')
		
		.on( 'change', '.variations input:radio', function( event ) {
			
			var attrName = jQuery(this).closest('td.value').attr('value_of');
			var attrSelect = jQuery('.variations').find("select#"+attrName);
			
			jQuery(attrSelect).trigger('focusin');
			var radioName = "attribute_"+attrName;
			var checkedRadio = jQuery(this).val();
			var $radio = jQuery(this);
			
			jQuery("input[name='"+radioName+"']").parent('.ced-vm-swatch-wrapper').removeClass('ced-vm-border');   
			jQuery(this).parent('.ced-vm-swatch-wrapper').addClass('ced-vm-border');
			
			jQuery(attrSelect).find('option').each(function(){
				
				if(jQuery(this).val()==checkedRadio){
					
					jQuery(this).attr('selected', 'selected');
					jQuery(this).closest("tr").addClass('ced-vm-checked');
					//console.log(jQuery($radio).parent('.ced-vm-swatch-wrapper').addClass('ced-vm-border'));
					
				}else{
					
					jQuery(this).removeAttr('selected');
				}
			})
			jQuery(attrSelect).change();
		})
		
		.on( 'change', '.variations select', function( event ) {
			
			jQuery('form.variations_form').find( '.variations select' ).each( function( i, e ) {
				
				var currentAttrSelect = jQuery( e );
				var currentAttrID = currentAttrSelect.attr('id');
				var attrNameArray = [];
				jQuery(e).trigger('focusin');
				jQuery(currentAttrSelect).find('option').each(function(index,element){
					if(element.value != '' && element.value != null && element.value != undefined){
						attrNameArray.push(element.value);
					}
				});
				
				var swatch = jQuery(this).closest('td').find('li label');
				jQuery(swatch).each(function(){
					var tmpVal = jQuery(this).find('input').val();
					
					if(jQuery.inArray( tmpVal, attrNameArray ) < 0){
						
						jQuery(this).find('input').attr('disabled','disabled');
						jQuery(this).find('div').addClass('ced-vm-disable');
					}else{
						
						jQuery(this).find('input').removeAttr('disabled');
						jQuery(this).find('div').removeClass('ced-vm-disable');
					}
				});
			})
		})
		
		.on( 'click', '.reset_variations', function( event ) {
		
		event.preventDefault();
		
		jQuery('form.variations_form').find( '.variations input:radio' ).each( function( i, e ) {
			
			jQuery(this).removeAttr('disabled');
			jQuery(this).removeAttr('checked');
			jQuery(this).closest('div').removeClass('ced-vm-border');
		});
		
		jQuery('form.variations_form').find('.variations tr.ced-vm-row-wrapper').each(function(){
			jQuery(this).removeClass('ced-vm-checked');
		});
		
	})
		
});