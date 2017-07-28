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
?>


<div id="zoo-cw-variation-swatch-data" class="panel woocommerce_options_panel"><?php
	$attributes	=	$_product->get_variation_attributes();
	$visible_attributes = array();
	if(is_array($attributes) && count($attributes)): 
	$product_swatch_data_array = get_post_meta( $post->ID, 'zoo_cw_product_swatch_data', true );
	if(!is_array($product_swatch_data_array))
		$product_swatch_data_array = array();
	
	$disable_swatch = isset($product_swatch_data_array['disabled']) ? intval($product_swatch_data_array['disabled']) : 0;
	?>
	<div class="zoo-cw-enable-swatch">
		<?php _e('Disable Swatches','clever-swatch');?>
		<input type="checkbox" name="zoo-cw-disable_swatch" <?php checked($disable_swatch);?>>
	</div>
	<div id="zoo-cw-accordion">
		<?php foreach ( $attributes as $attribute_name => $options ) : ?>
			<?php $tmp_title =  $attribute_name; ?>
			<div class="zoo-cw-panel">
				<div class="zoo-cw-panel-heading">
					<h4><?php echo wc_attribute_label( $attribute_name ); ?></h4>
				</div>
				<div class="zoo-cw-collapse">
					<table class="zoo-cw-label-data">
						<tr>
							<td><?php _e('Label','clever-swatch');?></td>
							<?php $labelValue = isset($product_swatch_data_array[$tmp_title]['label']) ? $product_swatch_data_array[$tmp_title]['label'] : ''; ?>
							<td><input type="text" value="<?php echo $labelValue; ?>" name="zoo_cw_label_<?php echo  $attribute_name; ?>"></td>
						</tr>
						<tr>
							<td><?php _e('Display Type','clever-swatch');?></td>
							<?php $dtslct = isset($product_swatch_data_array[$tmp_title]['dt']) ? intval($product_swatch_data_array[$tmp_title]['dt']) : 1; ?>
							<td>
								<select name="zoo_cw_dt_<?php echo  $attribute_name; ?>">
									<option value="1" <?php if($dtslct == 1): echo 'selected=selected'; endif; ?> ><?php _e('Default(Select)','clever-swatch');?></option>
									<option value="2" <?php if($dtslct == 2): echo 'selected=selected'; endif; ?>><?php _e('Image/Color','clever-swatch');?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php _e('Display Size','clever-swatch');?></td>
							<?php $dslct = isset($product_swatch_data_array[$tmp_title]['ds']['ds']) ? intval($product_swatch_data_array[$tmp_title]['ds']['ds']) : 1; ?>
							<?php $ds2slct = isset($product_swatch_data_array[$tmp_title]['ds']['ds2']) ? intval($product_swatch_data_array[$tmp_title]['ds']['ds2']) : 1; ?>
							<td>
								<select class="zoo-cw-ds1" name="zoo_cw_ds_<?php echo  $attribute_name; ?>">
									<option value="1" <?php if($dslct == 1): echo 'selected=selected'; endif; ?>><?php _e('20px * 20px','clever-swatch');?></option>
									<option value="2" <?php if($dslct == 2): echo 'selected=selected'; endif; ?>><?php _e('40px * 40px','clever-swatch');?></option>
									<option value="3" <?php if($dslct == 3): echo 'selected=selected'; endif; ?>><?php _e('60px * 60px','clever-swatch');?></option>
									<option value="4" <?php if($dslct == 4): echo 'selected=selected'; endif; ?>><?php _e('Default global size','clever-swatch');?></option>
								</select>
								<select name="zoo_cw_ds2_<?php echo  $attribute_name; ?>" class="zoo-cw-selectsecnd">
									<option value="1" <?php if($ds2slct == 1): echo 'selected=selected'; endif; ?>><?php _e('square','clever-swatch');?></option>
									<option value="2" <?php if($ds2slct == 2): echo 'selected=selected'; endif; ?>><?php _e('circle','clever-swatch');?></option>
									<option value="3" <?php if($ds2slct == 3): echo 'selected=selected'; endif; ?>><?php _e('rectangle','clever-swatch');?></option>
								
								</select>
							</td>
						</tr>
						<tr>
							<td><?php _e('Display Name','clever-swatch');?></td>
							<?php $dnslct = isset($product_swatch_data_array[$tmp_title]['dn']) ? intval($product_swatch_data_array[$tmp_title]['dn']) : 1; ?>
							<td>
								<select name="zoo_cw_dn_<?php echo  $attribute_name; ?>">
									<option value="1" <?php if($dnslct == 1): echo 'selected=selected'; endif; ?>><?php _e('Yes','clever-swatch');?></option>
									<option value="0" <?php if($dnslct == 0): echo 'selected=selected'; endif; ?>><?php _e('No','clever-swatch');?></option>
								</select>
							</td>
						</tr>
					</table>
					<div class="zoo-cw-sub-accordion">
						<div class="zoo-cw-sub-panel">
						<?php $tmp_options_data_array = isset($product_swatch_data_array[$tmp_title]['options_data']) ? $product_swatch_data_array[$tmp_title]['options_data'] : array();?>
							<?php if ( is_array( $options ) ) { ?>
								<?php if ( taxonomy_exists(  $attribute_name ) ) { ?>
									<?php $terms = get_terms( $attribute_name, array('menu_order' => 'ASC') ); ?>
									<?php foreach ( $terms as $term ) { ?>
										<?php if ( in_array( $term->slug, $options ) ) :?>
											<div class="zoo-cw-sub-panel-heading">
												<h4><?php echo  $term->slug; ?></h4>
											</div>
											<?php $termName =  $term->slug; ?>
											<div class="zoo-cw-sub-collapse">
												<table class="zoo-cw-attr-option">
												<?php $tmpgat = isset($tmp_options_data_array[$termName]['gat']) ? $tmp_options_data_array[$termName]['gat'] : 0; ?>
														<?php $tmpdtslctType = isset($tmp_options_data_array[$termName]['dt'])  ? $tmp_options_data_array[$termName]['dt'] : 1; ?>
														<?php $tmpdtslctimg = isset($tmp_options_data_array[$termName]['image']) ? $tmp_options_data_array[$termName]['image'] : wc_placeholder_img_src(); ?>
														<?php $tmpdtslctclr = isset($tmp_options_data_array[$termName]['color']) ? $tmp_options_data_array[$termName]['color'] : ''; ?>
													<tr>
														<td><?php _e('Enable This','clever-swatch');?></td>
														<td>
															<select class="zoo_cw_global_at" name="zoo_cw_gat_<?php echo  $term->slug; ?>">
																<option value="0" <?php if($tmpgat == 0): echo 'selected=selected'; endif; ?> ><?php _e('Later','clever-swatch');?></option>
																<option value="1" <?php if($tmpgat == 1): echo 'selected=selected'; endif; ?>><?php _e('Yes','clever-swatch');?></option>
															</select>
														</td>
													</tr>
													<tr>
														<td><?php _e('Display Type','clever-swatch')?></td>
														<td>
															<select class="zoo-cw-dtslct" name="zoo_cw_sdt_<?php echo  $term->slug; ?>">
																<option value="1" <?php if($tmpdtslctType == 1): echo 'selected=selected'; endif; ?> ><?php _e('Image','clever-swatch');?></option>
																<option value="0" <?php if($tmpdtslctType == 0): echo 'selected=selected'; endif; ?>><?php _e('Color','clever-swatch');?></option>
															</select>
														</td>
													</tr>
													<tr class="zoo-cw-sci" <?php if(!$tmpdtslctType): echo 'style="display:none;"'; endif;?>>
														<td><?php _e('Select/Upload Image','clever-swatch')?></td>
														<td><img height="30px" width="30px" src="<?php echo $tmpdtslctimg;?>" alt="<?php _e('Select Image','clever-swatch');?>" class="zoo-cw-scimage_<?php echo  $term->slug; ?>">
															<input type="hidden" value="<?php echo $tmpdtslctimg;?>" class="zoo-cw-input-scimg-<?php echo  $term->slug; ?>" name="zoo-cw-input-scimg-<?php echo  $term->slug; ?>">
															<button type="button" data-attrname="<?php echo  $term->slug; ?>" class="zoo-cw-scimage-upload"><?php _e('Browse','clever-swatch')?></button>
														</td>
													</tr>
													<tr class="zoo-cw-scc" <?php if($tmpdtslctType): echo 'style="display:none;"'; endif;?>>
														<td><?php _e('Choose Color','clever-swatch')?></td>
														<td><input type="text" value="<?php echo $tmpdtslctclr;?>" name="zoo_cw_slctclr_<?php echo  $term->slug; ?>" class="zoo-cw-colorpicker" /></td>
													</tr>
												</table>
											</div>
										<?php endif;?>
									<?php } ?>
								<?php }else{?>
									<?php foreach ( $options as $option ) : ?>
										<div class="zoo-cw-sub-panel-heading">
											<h4><?php echo  $option; ?></h4>
										</div>
										<?php $termName =  $option; ?>
										<div class="zoo-cw-sub-collapse">
											<table class="zoo-cw-attr-option">
												<tr>
													<td><?php _e('Display Type','clever-swatch')?></td>
													<?php $tmpdtslctType = isset($tmp_options_data_array[$termName]['dt']) ? $tmp_options_data_array[$termName]['dt'] : 1; ?>
													<?php $tmpdtslctimg = isset($tmp_options_data_array[$termName]['image']) ? $tmp_options_data_array[$termName]['image'] : wc_placeholder_img_src(); ?>
													<?php $tmpdtslctclr = isset($tmp_options_data_array[$termName]['color']) ? $tmp_options_data_array[$termName]['color'] : ''; ?>
													<td>
														<select class="zoo-cw-dtslct" name="zoo_cw_sdt_<?php echo  $option; ?>">
															<option value="1" <?php if($tmpdtslctType == 1): echo 'selected=selected'; endif; ?>><?php _e('Image','clever-swatch');?></option>
															<option value="0" <?php if($tmpdtslctType == 0): echo 'selected=selected'; endif; ?>><?php _e('Color','clever-swatch');?></option>
														</select>
													</td>
												</tr>
												<tr class="zoo-cw-sci" <?php if(!$tmpdtslctType){echo 'style="display: none"';}?>>
													<td><?php _e('Select/Upload Image','clever-swatch')?></td>
													<td><img height="30px" width="30px" src="<?php echo $tmpdtslctimg;?>" alt="<?php _e('Select Image','clever-swatch');?>" class="zoo-cw-scimage_<?php echo  $option; ?>">
														<input type="hidden" value="<?php echo $tmpdtslctimg;?>" class="zoo-cw-input-scimg-<?php echo  $option; ?>" name="zoo-cw-input-scimg-<?php echo  $option; ?>">
														<button type="button" data-attrname="<?php echo  $option; ?>" class="zoo-cw-scimage-upload"><?php _e('Browse','clever-swatch')?></button>
													</td>
												</tr>
												<tr class="zoo-cw-scc" <?php if($tmpdtslctType){echo 'style="display: none"';}?>>
													<td><?php _e('Choose Color','clever-swatch')?></td>
													<td><input type="text" value="<?php echo $tmpdtslctclr;?>" name="zoo_cw_slctclr_<?php echo  $option; ?>" class="zoo-cw-colorpicker" /></td>
												</tr>
											</table>
										</div>
									<?php endforeach;?>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach;?>
	</div>
</div>
<?php else:?>
<div style="text-align: center">
	<h4><?php _e('No available variation for swath images','clever-swatch');?></h4>
</div>
<?php endif;?>