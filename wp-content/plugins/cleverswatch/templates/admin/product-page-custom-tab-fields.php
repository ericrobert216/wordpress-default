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

$zoo_cw_helper =  new Zoo_Clever_Swatch_Helper();
?>

<div id="zoo-cw-variation-swatch-data" class="panel woocommerce_options_panel"><?php
	$attributes	=	$_product->get_variation_attributes();
	$visible_attributes = array();
	if(is_array($attributes) && count($attributes)): 
	$product_swatch_data_array = get_post_meta( $post->ID, 'zoo_cw_product_swatch_data', true );
	if(!is_array($product_swatch_data_array))
		$product_swatch_data_array = array();
	?>
	<div id="zoo-cw-accordion">
		<?php foreach ( $attributes as $attribute_name => $options ) : ?>
            <?php
                $default_display_type = $zoo_cw_helper->get_display_type_by_attribute_taxonomy_name($attribute_name);
                if (!isset($default_display_type) || $default_display_type == '') $default_display_type = 'default';
            ?>
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
						<tr class="zoo-cw-display-type">
							<td><?php _e('Display Type','clever-swatch');?></td>
							<?php $display_type = isset($product_swatch_data_array[$tmp_title]['display_type']) ? $product_swatch_data_array[$tmp_title]['display_type'] : $default_display_type; ?>
                            <td>
								<select name="zoo_cw_display_type_<?php echo  $attribute_name; ?>">
									<option value="default" <?php if($display_type == 'default'): echo 'selected=selected'; endif; ?> ><?php _e('Default(Select)','clever-swatch');?></option>
									<option value="image" <?php if($display_type == 'image'): echo 'selected=selected'; endif; ?>><?php _e('Image','clever-swatch');?></option>
                                    <option value="color" <?php if($display_type == 'color'): echo 'selected=selected'; endif; ?>><?php _e('Color','clever-swatch');?></option>
                                    <option value="text" <?php if($display_type == 'text'): echo 'selected=selected'; endif; ?>><?php _e('Text','clever-swatch');?></option>
								</select>
							</td>
						</tr>
						<tr class="zoo_cw_display_size" <?php if ($display_type == 'default') echo('style="display:none;"'); ?> >
							<td><?php _e('Display Size','clever-swatch');?></td>
							<?php $display_size = isset($product_swatch_data_array[$tmp_title]['display_size']) ? $product_swatch_data_array[$tmp_title]['display_size'] : 'default'; ?>
							<td>
								<select name="zoo_cw_display_size_<?php echo  $attribute_name; ?>">
                                    <option value="default" <?php if($display_size == "default"): echo 'selected=selected'; endif; ?>><?php _e('Default Global','clever-swatch');?></option>
									<option value="1" <?php if($display_size == 1): echo 'selected=selected'; endif; ?>><?php _e('20px * 20px','clever-swatch');?></option>
									<option value="2" <?php if($display_size == 2): echo 'selected=selected'; endif; ?>><?php _e('40px * 40px','clever-swatch');?></option>
									<option value="3" <?php if($display_size == 3): echo 'selected=selected'; endif; ?>><?php _e('60px * 60px','clever-swatch');?></option>
								</select>
							</td>
						</tr>
                        <tr class="zoo_cw_display_shape" <?php if ($display_type == 'default') echo('style="display:none;"'); ?> >
                            <td><?php _e('Display Shape','clever-swatch');?></td>
                            <?php $display_shape = isset($product_swatch_data_array[$tmp_title]['display_shape']) ? $product_swatch_data_array[$tmp_title]['display_shape'] : 'default'; ?>
                            <td>
                                <select name="zoo_cw_display_shape_<?php echo  $attribute_name; ?>">
                                    <option value="default" <?php if($display_shape == "default"): echo 'selected=selected'; endif; ?>><?php _e('Default Global','clever-swatch');?></option>
                                    <option value="square" <?php if($display_shape == 'square'): echo 'selected=selected'; endif; ?>><?php _e('Square','clever-swatch');?></option>
                                    <option value="circle" <?php if($display_shape == 'circle'): echo 'selected=selected'; endif; ?>><?php _e('Circle','clever-swatch');?></option>
                                </select>
                            </td>
                        </tr>
						<tr class="zoo_cw_display_name" <?php if ($display_type == 'default' || $display_type == 'text') echo('style="display:none;"'); ?>>
							<td><?php _e('Show Attribute Name?','clever-swatch');?></td>
							<?php $display_name = isset($product_swatch_data_array[$tmp_title]['display_name_yn']) ? $product_swatch_data_array[$tmp_title]['display_name_yn'] : 'default'; ?>
							<td>
								<select name="zoo_cw_display_name_<?php echo  $attribute_name; ?>">
                                    <option value="default" <?php if($display_name == "default"): echo 'selected=selected'; endif; ?>><?php _e('Default Global','clever-swatch');?></option>
									<option value="1" <?php if($display_name == '1'): echo 'selected=selected'; endif; ?>><?php _e('Yes','clever-swatch');?></option>
									<option value="0" <?php if($display_name == '0'): echo 'selected=selected'; endif; ?>><?php _e('No','clever-swatch');?></option>
								</select>
							</td>
						</tr>
					</table>
					<div class="zoo-cw-sub-accordion" <?php if ($display_type == 'default' || $display_type == 'text') echo('style="display:none;"'); ?> >
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
                                                    <?php $default_value = $zoo_cw_helper->get_default_value_of_attribute_option($term); ?>
                                                    <?php $image = isset($tmp_options_data_array[$termName]['image']) ? $tmp_options_data_array[$termName]['image'] : $default_value['default_image']; ?>
                                                    <?php $color = isset($tmp_options_data_array[$termName]['color']) ? $tmp_options_data_array[$termName]['color'] : $default_value['default_color']; ?>
													<tr class="zoo-cw-sci" <?php if($display_type != 'image'): echo 'style="display:none;"'; endif;?>>
														<td><?php _e('Select/Upload Image','clever-swatch')?></td>
														<td><img height="30px" width="30px" src="<?php echo $image;?>" alt="<?php _e('Select Image','clever-swatch');?>" class="zoo-cw-scimage_<?php echo  $term->slug; ?>">
															<input type="hidden" value="<?php echo $image;?>" class="zoo-cw-input-scimg-<?php echo  $term->slug; ?>" name="zoo-cw-input-scimg-<?php echo  $term->slug; ?>">
															<button type="button" data-attrname="<?php echo  $term->slug; ?>" class="zoo-cw-scimage-upload"><?php _e('Browse','clever-swatch')?></button>
														</td>
													</tr>
													<tr class="zoo-cw-scc" <?php if($display_type != 'color'): echo 'style="display:none;"'; endif;?>>
														<td><?php _e('Choose Color','clever-swatch')?></td>
														<td><input type="text" value="<?php echo $color;?>" name="zoo_cw_slctclr_<?php echo  $term->slug; ?>" class="zoo-cw-colorpicker" /></td>
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
										<?php $termName = $option; ?>
                                        <?php $image = isset($tmp_options_data_array[$termName]['image']) ? $tmp_options_data_array[$termName]['image'] : wc_placeholder_img_src(); ?>
                                        <?php $color = isset($tmp_options_data_array[$termName]['color']) ? $tmp_options_data_array[$termName]['color'] : ''; ?>
										<div class="zoo-cw-sub-collapse">
											<table class="zoo-cw-attr-option">

												<tr class="zoo-cw-sci" <?php if($display_type != 'image'){echo 'style="display: none"';}?>>
													<td><?php _e('Select/Upload Image','clever-swatch')?></td>
													<td><img height="30px" width="30px" src="<?php echo($image);?>" alt="<?php _e('Select Image','clever-swatch');?>" class="zoo-cw-scimage_<?php echo  $option; ?>">
														<input type="hidden" value="<?php echo($image);?>" class="zoo-cw-input-scimg-<?php echo  $option; ?>" name="zoo-cw-input-scimg-<?php echo  $option; ?>">
														<button type="button" data-attrname="<?php echo  $option; ?>" class="zoo-cw-scimage-upload"><?php _e('Browse','clever-swatch')?></button>
													</td>
												</tr>
												<tr class="zoo-cw-scc" <?php if($display_type != 'color'){echo 'style="display: none"';}?>>
													<td><?php _e('Choose Color','clever-swatch')?></td>
													<td><input type="text" value="<?php echo($color);?>" name="zoo_cw_slctclr_<?php echo  $option; ?>" class="zoo-cw-colorpicker" /></td>
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