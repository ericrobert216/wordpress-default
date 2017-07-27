var ajax_url = ced_vm_params.ajax_url;

jQuery(document).ready(function(){
	jQuery('.ced-vm-colorpicker').wpColorPicker();
	
	var SelectedVal = jQuery('.ced_vm_atds').val();
	var SelectedValue = jQuery('#ced_vm_atds2').val();
	if(SelectedVal==4 && SelectedValue == 3){
		
		jQuery('#ced_vm_atds_cv_w').show();
	}else{
		
		jQuery('#ced_vm_atds_cv_w').hide();
	}
	
	//custom size..
	jQuery('.ced_vm_atds').on('click',function(){
		var SelectedVal = jQuery(this).val();
		var SelectedValue = jQuery('#ced_vm_atds2').val();
		if(SelectedVal==4){
			jQuery('#ced_vm_atds_cv').show();
			if(SelectedValue == 3)
				jQuery('#ced_vm_atds_cv_w').show();
			
		}else{
			jQuery('#ced_vm_atds_cv').hide();
			if(SelectedValue == 3)
				jQuery('#ced_vm_atds_cv_w').hide();
			
		}
	});
	
	jQuery('#ced_vm_atds2').on('change',function(){
		var SelectedVal = jQuery('.ced_vm_atds').val();
		var SelectedValue = jQuery(this).val();
		if(SelectedVal==4 && SelectedValue == 3){
			
			jQuery('#ced_vm_atds_cv_w').show();
		}else{
			
			jQuery('#ced_vm_atds_cv_w').hide();
		}
	});
});
