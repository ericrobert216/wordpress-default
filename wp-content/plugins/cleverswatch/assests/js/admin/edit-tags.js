jQuery(document).ready(function(){
	jQuery('.ced-vm-colorpicker').wpColorPicker();
	
	jQuery(document).on('click','.ced-vm-image-picker',function(e){
		e.preventDefault();
		var image = wp.media({ 
            title: 'Upload Image',
            multiple: false
        }).open()
        .on('select', function(e){
            
            var uploaded_image = image.state().get('selection').first();
            var image_url = uploaded_image.toJSON().url;
            
            jQuery('.ced-vm-slctd-img').attr('src',image_url);
            jQuery('.ced-vm-selected-attr-img').val(image_url);
        });
	});
	
	jQuery(document).on('change','#ced-vm-display-type',function(){
		
		var slctd = jQuery(this).val();
		ced_vm_show_option(slctd);
	});
	
	var value = jQuery("#ced-vm-display-type").val();
	ced_vm_show_option(value);
	
});

function ced_vm_show_option(value){
	if(value == 1){
		jQuery(".ced-vm-attr-image-uploader").show();
		jQuery(".ced-vm-attr-colorpickerdiv").hide();
	}else if(value == 2){
		jQuery(".ced-vm-attr-image-uploader").hide();
		jQuery(".ced-vm-attr-colorpickerdiv").show();
	}else{
		jQuery(".ced-vm-attr-image-uploader").hide();
		jQuery(".ced-vm-attr-colorpickerdiv").hide();
	}
}