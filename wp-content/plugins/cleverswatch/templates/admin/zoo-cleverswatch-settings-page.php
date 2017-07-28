<?php
/**
 * @version  1.0.0
 * @package  clever-swatch/templates/admin
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$woo_settings = new WC_Admin_Settings();

$current_tab = "general";

if(isset($_GET['tab'])){
	$current_tab = $_GET['tab'];
}
$authenticate_notice = array();

if(isset($_POST['save'])){
	if($current_tab=="general"){
		$general_settings_array = array();
		$enableThis = isset($_POST['cs_cw_enable_this']) ? 1 : 0;
		$enableSwatch = isset($_POST['cs-cw-enable-swatch']) ? 1: 0;
		$enablePWG = isset($_POST['cs_cw_enable_pwg']) ? 1 : 0;
		$attrthumb = isset($_POST['cs_cw_at']) ? 1 : 0;
		$useSizeGlobally = isset($_POST['cs_cw_usg']) ? 1 : 0;
		$cs_cw_featured = isset($_POST['cs_cw_featured']) ? 1 : 0;
		
		$attbds = array();
		$ds1 = isset($_POST['cs_cw_atds1']) ? intval($_POST['cs_cw_atds1']) : 1;
		$ds2 = isset($_POST['cs_cw_atds2']) ? intval($_POST['cs_cw_atds2']) : 1;
		$cv = isset($_POST['cs_cw_atds_cv']) ? intval($_POST['cs_cw_atds_cv']) : 0;
		$cv_w = isset($_POST['cs_cw_atds_cv_w']) ? intval($_POST['cs_cw_atds_cv_w']) : 0;
		
		$attbds['ds1'] = $ds1;
		$attbds['ds2'] = $ds2;
		$attbds['cv'] = $cv;
		$attbds['cv_w'] = $cv_w;
		
		$displayLabel = isset($_POST['dl']) ? 1 : 0;
		$displayvas = isset($_POST['vas']) ? 1 : 0;
		
		$general_settings_array['this'] = $enableThis;
		$general_settings_array['swatch'] = $enableSwatch;
		$general_settings_array['pwg'] = $enablePWG;
		$general_settings_array['at'] = $attrthumb;
		$general_settings_array['atds'] = $attbds;
		$general_settings_array['dl'] = $displayLabel;
		$general_settings_array['cs_cw_usg'] = $useSizeGlobally;
		$general_settings_array['vas'] = $displayvas;
		$general_settings_array['cs_cw_featured'] = $cs_cw_featured;
		
		if(is_array($general_settings_array))
			update_option('cs-cw-settings',$general_settings_array);
	}?>
	<div class="notice notice-success is-dismissible"> 
		<p><strong><?php _e('Settings Saved Successfully', 'variation-master'); ?></strong></p>
	</div>
	<?php }

	?>

	<div class="wrap woocommerce">
		<form novalidate="novalidate" action="" method="post">
			<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
				<a href	= "<?php get_admin_url()?>admin.php?page=cs-cw-settings&tab=general" class="nav-tab <?php if( $current_tab == 'general' ) : ?> nav-tab-active<?php endif; ?>"><?php _e('Clever Swatch','variation-master')?></a>
			</h2>
		</form>
	</div>

	<div class="cs-cw-settings-wrapper">
		<?php if($current_tab == "general"): ?>
			<?php $general_settings = get_option('cs-cw-settings',true); //print_r($general_settings); die("test");?>
			<?php if(!is_array($general_settings)): $general_settings = array(); endif;?>
			<form name="cs-cw-settings-form" action="" method="post">
				<div class="cs-cw-settings-container">
					<div class="cs-cw-settings-header">
						<h3 class="cs-cw-heading"><?php _e('Global Settings','variation-master')?></h3>
						<div class="cs-cw-div-wrapper">
							<table class="cs-cw-settings-table">
								<?php $enable = isset($general_settings['this']) ? intval($general_settings['this']) : 1;
								$swatch = isset($general_settings['swatch']) ? intval($general_settings['swatch']) : 1;
								$pwg = isset($general_settings['pwg']) ? intval($general_settings['pwg']) : 1;
								$at = isset($general_settings['at']) ? intval($general_settings['at']) : 0;
								$atds1 = isset($general_settings['atds']['ds1']) ? intval($general_settings['atds']['ds1']) : 1;
								$atds2 = isset($general_settings['atds']['ds2']) ? intval($general_settings['atds']['ds2']) : 1;
								$atds_cv = isset($general_settings['atds']['cv']) ? intval($general_settings['atds']['cv']) : 0;
								$atds_cv_w = isset($general_settings['atds']['cv_w']) ? intval($general_settings['atds']['cv_w']) : 0;
								$cs_cw_featured = isset($general_settings['cs_cw_featured']) ? intval($general_settings['cs_cw_featured']) : 0;

								$dl = isset($general_settings['dl']) ? intval($general_settings['dl']) : 1; 
								$cs_cw_usg = isset($general_settings['cs_cw_usg']) ? intval($general_settings['cs_cw_usg']) : 0; ?>
								<?php $vas = isset($general_settings['vas']) ? intval($general_settings['vas']) : 1; ?>

								<tr>
									<td><?php _e('Enable Swatches','variation-master'); ?></td>
									<td>
										<label class="toggle">
											<input type="checkbox" name="cs-cw-enable-swatch" <?php checked($swatch,1);?>>
											<span class="handle"></span>
										</label>
									</td>
									<td>
										<p class="description"><?php _e('Turn this to "Off" if you don\'t like to use variation swatches.','varition-master')?></p>
									</td>
								</tr>
								<tr>
									<td><?php _e('Enable Variation Wise Gallery','variation-master'); ?></td>
									<td>
										<label class="toggle">
											<input type="checkbox" name="cs_cw_enable_pwg" <?php checked($pwg,1);?>>
											<span class="handle"></span>
										</label>
									</td>
									<td>
										<p class="description"><?php _e('Turn this to "Off" if you don\'t like to use variation wise gallery.','varition-master')?></p>
									</td>
								</tr>
								<tr>
									<td><?php _e('Enable Variation Update On Cart','variation-master'); ?></td>
									<td>
										<label class="toggle">
											<input type="checkbox" name="cs_cw_enable_this" <?php checked($enable,1);?>>
											<span class="handle"></span>
										</label>
									</td>
									<td>
										<p class="description"><?php _e('Turn this to "Off" if you don\'t like to allow buyers to update product on cart.','varition-master')?></p>
									</td>
								</tr>
								<tr>
									<td><?php _e('Use Attributes Terms Thumbnails','variation-master'); ?></td>
									<td>
										<label class="toggle">
											<input type="checkbox" name="cs_cw_at" <?php checked($at,1);?>>
											<span class="handle"></span>
										</label>
									</td>
									<td>
										<p class="description"><?php _e('Turn this to "Off" if you don\'t like to use attributes terms thumbnails.','varition-master')?></p>
									</td>
								</tr>
								<tr>
									<td><?php _e('Show Attribute Term Label','variation-master'); ?></td>
									<td>
										<label class="toggle">
											<input type="checkbox" name="dl" <?php checked($dl,1);?>>
											<span class="handle"></span>
										</label>
									</td>
									<td>
										<p class="description"><?php _e('Turn this to "Off" if you want to hide attribute term label.','varition-master')?></p>
									</td>
								</tr>

								<tr>
									<td><?php _e('Attribute Term Label Over Swatches','variation-master'); ?></td>
									<td>
										<label class="toggle">
											<input type="checkbox" name="vas" <?php checked($vas,1);?>>
											<span class="handle"></span>
										</label>
									</td>
									<td>
										<p class="description"><?php _e('Turn this to "Off" if you want attribute text above swatches.','varition-master')?></p>
									</td>
								</tr>

								<tr>
									<td><?php _e('Attributes Terms Thumbnails Display Type','variation-master'); ?></td>
									<td>
										<select name="cs_cw_atds1" class="cs_cw_atds">
											<option value="1" <?php selected($atds1,1);?>><?php _e('20px * 20px','variation-master')?></option>
											<option value="2" <?php selected($atds1,2);?>><?php _e('40px * 40px','variation-master')?></option>
											<option value="3" <?php selected($atds1,3);?>><?php _e('60px * 60px','variation-master')?></option>
											<option value="4" <?php selected($atds1,4);?>><?php _e('Other','variation-master')?></option>
										</select>
										<input type="text" placeholder="size in px." id="cs_cw_atds_cv" name ="cs_cw_atds_cv" value="<?php echo $atds_cv; ?>" <?php if($atds1!=4):  echo 'style="display: none;"'; endif;?>>
										<input type="text" placeholder="size in px." id="cs_cw_atds_cv_w" name ="cs_cw_atds_cv_w" value="<?php echo $atds_cv_w; ?>" <?php if($atds1!=4):  echo 'style="display: none;"'; endif;?>>

										<select name="cs_cw_atds2" id="cs_cw_atds2">
											<option value="1" <?php selected($atds2,1);?>><?php _e('SQUARE','variation-master')?></option>
											<option value="2" <?php selected($atds2,2);?>><?php _e('CIRCLE','variation-master')?></option>
											<option value="3" <?php selected($atds2,3);?>><?php _e('RECTANGLE','variation-master')?></option>
										</select>
									</td>
									<td>
										<p class="description"><?php _e('Set the "size" and "display type" of attribute terms thumbnails.','varition-master')?></p>
									</td>
								</tr>
								<tr>
									<td><?php _e('Use Global Size Everywhere','variation-master'); ?></td>
									<td>
										<label class="toggle">
											<input type="checkbox" name="cs_cw_usg" <?php checked($cs_cw_usg,1);?>>
											<span class="handle"></span>
										</label>
									</td>
									<td>
										<p class="description"><?php _e('Turn this on if you want to use the global size for all swatches and labels, NOTE: This will override all the product wise swatch/image size settings.','varition-master')?></p>
									</td>
								</tr>
								<tr>
									<td><?php _e('Use Variation Featured Image','variation-master'); ?></td>
									<td>
										<label class="toggle">
											<input type="checkbox" name="cs_cw_featured" <?php checked($cs_cw_featured,1);?>>
											<span class="handle"></span>
										</label>
									</td>
									<td>
										<p class="description"><?php _e('Turn this on if you want to use variation\'s featured image as swatch image for select.','varition-master')?></p>
									</td>
								</tr>
							</table>
							<p class="description"><?php _e('Please go to the attribute settings for setting up global term thumbnails.','varition-master'); ?><a href="<?php echo admin_url( 'edit.php?post_type=product&page=product_attributes' );?>"><?php _e('Click Here','variation-master');?></a></p>
						</div>
					</div>
				</div>	
				<p class="submit">
					<input class="button-primary woocommerce-save-button" type="submit" value="Save changes" name="save">
				</p>
			</form>
		<?php elseif($current_tab == "import-export"): 

		?><div class="cs-cw-term-import"><h3 class="cs-cw-heading"><?php _e('Attributes Term','variation-master');?></h3><div class="cs-cw-wrapper"><?php 

		require_once CS_VM_DIRPATH.'includes/admin/settings/cs-cw-attrImport.php';

		?></div></div><div class="cs-cw-import-gallery"><h3 class="cs-cw-heading"><?php _e('Variations Gallery','variation-master');?></h3><div class="cs-cw-wrapper"><?php

		require_once CS_VM_DIRPATH.'includes/admin/settings/cs-cw-galleryImport.php';
		?></div></div><?php 
		endif; ?>
	</div>
