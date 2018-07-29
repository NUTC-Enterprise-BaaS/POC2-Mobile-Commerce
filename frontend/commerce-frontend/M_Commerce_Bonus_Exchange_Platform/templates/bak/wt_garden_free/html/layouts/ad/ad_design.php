<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die;
JHtml::_('behavior.modal');
$paymentMode = $displayData->sa_params->get('payment_mode');
?>

<div class="sa-create" id="sa-create-id">
	<div class="row-fluid">
		<fieldset class="sa-fieldset">
			<legend class="hidden-desktop">
				<?php echo JText::_('COM_SOCIALADS_DESIGN');?>
			</legend>
			<div class="span6 sa-border-right">
				<div class="row-fluid">
					<!-- ad-info start here -->
					<div class="sa-info span11">
						<div id="default_zone" >
							<?php
							if (!$displayData->app->isSite())
							{ ?>
								<div class="control-group ">
									<label for="ad_creator_name" class="control-label">
										<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_AD_CREATOR_TITLE'), JText::_('COM_SOCIALADS_AD_CREATOR_LB'), '', '* ' . JText::_('COM_SOCIALADS_AD_CREATOR_LB')); ?>
									</label>
									<div class="controls">
										<?php
										if (JVERSION >= "3.5")
										{
											// Set the link for the user selection page
											$required = "required='required'";
											$userName = "";
											$class = "";
											$size = 0;
											$readonly = "";
											$id = "ad_creator_id";
											$onchange = "";
											$value = (isset($displayData->addata_for_adsumary_edit->created_by)) ? JFactory::getUser($displayData->addata_for_adsumary_edit->created_by)->id : JFactory::getUser()->id;
											$userName = JFactory::getUser($value)->name;
											$name = "ad_creator_id";
											$link = 'index.php?option=com_users&amp;view=users&amp;layout=modal&amp;tmpl=component&amp;required='
												. ($required ? 1 : 0) . '&amp;field={field-user-id}';

											// Invalidate the input value if no user selected
											if (JText::_('JLIB_FORM_SELECT_USER') == htmlspecialchars($userName, ENT_COMPAT, 'UTF-8'))
											{
												$userName = "";
											}

											JHtml::script('jui/fielduser.min.js', false, true, false, false, true);
											?>
											<?php
											// Create a dummy text field with the user name. ?>
											<div class="field-user-wrapper"
												data-url="<?php echo $link; ?>"
												data-modal=".modal"
												data-modal-width="100%"
												data-modal-height="400px"
												data-input=".field-user-input"
												data-input-name=".field-user-input-name"
												data-button-select=".button-select"
											>
												<div class="input-append">
													<input
														type="text" id="<?php echo $id; ?>"
														value="<?php echo  htmlspecialchars($userName, ENT_COMPAT, 'UTF-8'); ?>"
														placeholder="<?php echo JText::_('JLIB_FORM_SELECT_USER'); ?>"
														readonly
														class="field-user-input-name <?php echo $class ? (string) $class : ''?>"
														<?php echo $size ? ' size="' . (int) $size . '"' : ''; ?>
														<?php echo $required ? 'required' : ''; ?>/>
														<?php
														if (!$readonly) : ?>
															<a class="btn btn-primary button-select" title="<?php echo JText::_('JLIB_FORM_CHANGE_USER') ?>">
																<span class="icon-user"></span>
															</a>
															<?php echo JHtml::_(
																'bootstrap.renderModal',
																'userModal_' . $id,
																array(
																	'title'  => JText::_('JLIB_FORM_CHANGE_USER'),
																	'closeButton' => true,
																	'footer' => '<button class="btn" data-dismiss="modal">' . JText::_('JCANCEL') . '</button>'
																)
															); ?>
														<?php
														endif; ?>
												</div>
												<?php // Create the real field, hidden, that stored the user id. ?>
												<input type="hidden" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo (int) $value; ?>"
													class="field-user-input <?php echo $class ? (string) $class : ''?>"
													data-onchange="<?php echo $this->escape($onchange); ?>"/>
											</div>
										<?php
										}
										else
										{ ?>
											<div class="input-append">
											<input type="text" id="ad_creator_name" name="ad_creator_name" class="input-medium required" disabled="disabled"
												placeholder="<?php echo JText::_('COM_SOCIALADS_AD_CREATOR');?>" value="<?php echo  (isset( $displayData->addata_for_adsumary_edit->created_by)) ? JFactory::getUser($displayData->addata_for_adsumary_edit->created_by)->name : JFactory::getUser()->name; ?>">
											<a class="modal button btn btn-info modal_jform_created_by"
												rel="{handler: 'iframe', size: {x: 800, y: 500}}"
												href="index.php?option=com_users&amp;view=users&amp;layout=modal&amp;tmpl=component&amp;field=jform_created_by"
												title="<?php echo JText::_('COM_SOCIALADS_AD_SELECT_CREATOR_LB'); ?>">
												<i class="icon-user"></i>
											</a>
										</div>
										<?php
										}?>
									</div>
								</div>
							<?php
							}
							?>
							<input type="hidden" id="ad_creator_id" name="ad_creator_id" class="required" value="<?php echo (isset($displayData->addata_for_adsumary_edit->created_by)) ? $displayData->addata_for_adsumary_edit->created_by : JFactory::getUser()->id; ?>">
							<div class="control-group">
								<label for="adtype" class="control-label">
									<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_ADTYPE_DESC'), JText::_('COM_SOCIALADS_ADTYPE'), '', '* ' . JText::_('COM_SOCIALADS_ADTYPE')); ?>
								</label>
								<div class="controls">

									<?php if ($displayData->zone_adtype_disabled != 'disabled="disabled"'):?>
										<?php echo JHtml::_('select.genericlist', $displayData->ad_types, "adtype", 'class="ad-type chzn-done input-medium" size="1" onchange="sa.create.changeAdType()"', "value", "text", $displayData->ad_type); ?>
									<?php else: ?>
										<input type="text" id="adtype" name="adtype"  value="<?php echo $displayData->ad_type; ?>" readonly="readOnly"  />
									<?php endif;?>

								</div>
							</div>

							<div class="control-group">
								<label for="adzone" class="control-label">
									<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_ADZONE_DESC'), JText::_('COM_SOCIALADS_ADZONE'), '', '* ' . JText::_('COM_SOCIALADS_ADZONE')); ?>
								</label>
								<div class="controls">
									<select size="1" class="chzn-done input-medium" id="zone" name="adzone" onchange="sa.create.getZonesData( <?php $paymentMode; ?>)"
											<?php echo $displayData->zone_adtype_disabled; ?> >
											<?php
											if ($displayData->edit_ad_id)
											{ ?>
												<option selected="selected" value="<?php echo $displayData->zone->id; ?>">
													<?php echo $displayData->zone->zone_name ?>
												</option> <?php
											}	?>
									</select>
									<input type="hidden" name="ad_zone_id" id="ad_zone_id" value="<?php if (isset($displayData->zone)){ echo $displayData->zone->id; }?>"/>
								</div>
							</div>
						</div>
						<!-- End div default_zone-->
						<div id="defaulturl">
							<?php
							$promotion_plugins = $displayData->sa_params->get('promotion_plugins');

							if ($promotion_plugins == 1)
							{
								$display_dest='display:none;';
							}
							else
							{
								$display_dest='display:block;';
							}?>
							<div class="control-group" id="destination_url"  style="<?php echo $display_dest; ?>">
								<label for="url2">
									<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_ADDEST_URL_DESC'), JText::_('COM_SOCIALADS_ADDEST_URL'), '', JText::_('COM_SOCIALADS_ADDEST_URL')); ?>
									<span class="help-inline"><?php echo JText::_('COM_SOCIALADS_ADDEST_URL_EXAMPLE');?></span>
								</label>
								<div id="defaulturl1">
									<div id="urlcontentlable">
										<div></div>
									</div>
									<!--enterlink-->
									<div class="" id="sa-form-spantxt">
										<div id="enterlink">
											<?php echo JHtml::_('select.genericlist',  $displayData->ad_url, 'addata[][ad_url1]', 'class="inputbox input-mini"', 'value', 'text',$displayData->url1_edit); ?>
											<input class="inputbox url" type="text" id="url2" name="addata[][ad_url2]" value="<?php echo $displayData->url2_edit;  ?>"  />
											<div class="clearfix"></div>
										</div>
									</div>
									<!--enterlink-->
									<?php
									JPluginHelper::importPlugin('socialadspromote');
									$dispatcher = JDispatcher::getInstance();
									$results = $dispatcher->trigger('onPromoteList');

									// Added by aniket for config for promote plugin to see by defult.
									if (empty($results))
									{ ?>
										<div id="selectlink" class="hide-filed"></div>
									<?php
									}
									else
									{
										// If edit ad from adsummary page dont show promote plugin link..
										if (!$displayData->edit_ad_id)
										{ ?>
											<!--selectlink-->
											<div class="control-group show-filed" id="selectlink">
												<span id="sa-form-span">
													<a class="preview-title-lnk" href="javascript:selectapplist();">
														<?php echo JText::sprintf('COM_SOCIALADS_AD_FRM_SITE_LINK', $displayData->sitename); ?>
													</a>
												</span>
											</div>
											<!--selectlink-->
										<?php
										}
									} ?>
								</div>
								<!--div#defaulturl1-->
							</div>
							<!--div.control-group#defaulturl-->
							<?php
							if ($promotion_plugins == 1)
							{
								$display_td="display:block;";
							}
							else
							{
								$display_td="display:none;";
							}
							?>
							<!--promotplugin-->
							<div id="promotplugin" class="promotplugin control-group" style="<?php echo $display_td; ?>">
								<div id="contentlable">
									<label for="addatapluginlist" title="<?php echo JText::_('COM_SOCIALADS_AD_FRM_CONTENT');?>">
										<?php
										echo $displayData->sitename . ' ' . JText::_('COM_SOCIALADS_AD_FRM_CONTENT');
										?>
									</label>
									<div id="promote_plg_select" class="controls ">
									</div>
									<!--div.controls-->
								</div>
								<!--div#contentlable-->
								<div id="webpagelink">
									<div>
										<span id="sa-form-span">
											<a  class="preview-title-lnk" href="javascript:sa.create.insertUrl();">
												<?php echo JText::_('COM_SOCIALADS_AD_FRM_WEBPAGE');?>
											</a>
										</span>
									</div>
								</div>
								<!--div#webpagelink-->
							</div>
							<!--div#promotplugin-->
						</div>
						<!--div#defaulturl-->

						<div class="control-group" id='ad_title_name'>
							<label for="ad_title">
								<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_AD_TITLE_DESC'), JText::_('COM_SOCIALADS_AD_TITLE'), '', JText::_('COM_SOCIALADS_AD_TITLE')); ?>
							</label>
							<div class="controls" id='ad_title_box'>
								<input class="inputbox" type="text" id="ad_title" value="<?php echo $displayData->ad_title_edit; ?>" name="addata[][ad_title]" size="28" onKeyUp="sa.create.countChars('ad_title','ad_title_charsText','{CHAR} <?php echo JText::_('COM_SOCIALADS_TEXT_LEFT_CHAR_MSG');?>',max_tit.value, this.value,event);" >
								<div class="sa_charlimit help-inline">
									<span id="ad_title_charsText" ><span id="ad_title_chars" >0</span> <?php echo JText::_('COM_SOCIALADS_TEXT_LEFT_CHAR_MSG');?></span>
									<input type="hidden" name="max_tit" class="max_tit" id="max_tit" value="<?php  ?>"/>
								</div>
								<!--div.sa_charlimit-->
							</div>
							<!--div.controls#ad_title_box-->
						</div>
						<!--div.control-group#ad_title_name-->
						<div class="control-group" id='ad_body_name'>
							<label for="ad_body">
								<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_AD_BODY_TEXT_DESC'), JText::_('COM_SOCIALADS_AD_BODY_TEXT'), '', JText::_('COM_SOCIALADS_AD_BODY_TEXT')); ?>
							</label>
							<div class="controls" id='ad_body_box'>
								<textarea id="body" name="addata[][ad_body]" rows="3"  onKeyUp="sa.create.countChars('body','ad_body_charsText','{CHAR} <?php echo JText::_('COM_SOCIALADS_TEXT_LEFT_CHAR_MSG');?>',max_body.value, this.value,event);"><?php echo $displayData->ad_body_edit; ?></textarea>
								<div class="sa_charlimit help-inline">
									<span id="ad_body_charsText" >
										<span id="ad_body_chars" >0</span>
										<?php echo JText::_('COM_SOCIALADS_TEXT_LEFT_CHAR_MSG');?>
									</span>
									<input type="hidden" name="max_body" class="max_body" id="max_body" value="<?php  ?>"/>
								</div>
								<!--div.sa_charlimit-->
							</div>
							<!--div.controls#ad_body_box-->

							<!--Extra code for zone pricing -->
							<input type="hidden" name="char_text" id="char_text" value="<?php echo JText::_('COM_SOCIALADS_TEXT_LEFT_CHAR_MSG');?>"/>
							<input type="hidden" name="pric_imp" id="pric_imp" value="<?php  ?>"/>
							<input type="hidden" name="pric_click" id="pric_click" value="<?php  ?>"/>
							<input type="hidden" name="pric_day" id="pric_day" value="<?php  ?>"/>
							<!--Extra code for zone pricing -->
						</div>
						<!--div.control-group#ad_body_name-->
						<!-- image upload-->
						<div class="control-group" id='ad_img_name'>
							<label for="ad_image">
								<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_AD_UPLOAD_MEDIA_DESC'), JText::_('COM_SOCIALADS_AD_UPLOAD_MEDIA'), '', JText::_('COM_SOCIALADS_AD_UPLOAD_MEDIA')); ?>
							</label>
							<!--ad_img_box-->
							<div class="controls" id='ad_img_box'>
								<!--ajax upload-->
								<span id="direct_upload">
									<div class="input-append">
										<div class="uneditable-input">
											<i class="icon-file hide-filed"></i>
											<span class="fileupload-preview"></span>
										</div>
										<span class="btn fileinput-button">
											<span class="fileupload-new">
												<?php echo JText::_('COM_SOCIALADS_AD_SELECT_FILE');?>
											</span>
											<input type="file" name="ad_image" id="ad_image" value="<?php echo $displayData->ad_image; ?>" onchange="ajaxUpload(this.form,'&filename=ad_image','upload_area','<?php echo JText::_('COM_SOCIALADS_AD_IMG_WAIT');?><img src=\'<?php echo JUri::root(true);?>/media/com_sa/images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' />','<img src=\'<?php echo JUri::root(true);?>/media/com_sa/images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /> <?php echo JText::_('COM_SOCIALADS_AD_IMG_ERR_MSG');?>'); return false;">
										</span>
										<div class="clearfix"></div>
									</div>
									<div class="alert alert-info msg_support_type alert-help-inline">
										<div class="sa_charlimit">
											<?php echo JText::_('COM_SOCIALADS_AD_NEED_MEDIA_SIZE');?>
											<span id='img_wid'> </span> px X <span id='img_ht'> </span> px
										</div>
										<div>
											<?php
											echo JText::_('COM_SOCIALADS_AD_SUPPOERTED_FORMATS');
											$flashUploads = $displayData->sa_params->get('flash_uploads');
											$videoUploads = $displayData->sa_params->get('video_uploads');

											if ($flashUploads)
											{
												echo ', ' . JText::_('COM_SOCIALADS_AD_SUPPOERTED_FORMATS_FLASH');
											}

											if ($videoUploads)
											{
												echo ', ' . JText::_('COM_SOCIALADS_AD_SUPPOERTED_FORMATS_VID');
											}
											?>
										</div>
										<div>
											<?php
											$mediaSize = $displayData->sa_params->get('media_size');

											if ($mediaSize)
											{
												echo JText::sprintf('COM_SOCIALADS_AD_MEDIA_MAX_ALLOWED_SIZE', $mediaSize);
											}
											?>
										</div>
									</div>
									<!--div.msg_support_typed-->
									<div class="clearfix"></div>
								</span>
								<!--span#direct_upload-->
								<!--ajax upload-->
								<input type="hidden" name="upimg" id="upimg" class= 'abc' value="<?php echo $displayData->ad_image; ?>" />
								<?php
								if (isset($displayData->ad_image))
								{ ?>
									<input type="hidden" name="upimgcopy" id="upimgcopy" value="<?php echo $displayData->ad_image; ?>" />
								<?php
								}
								else
								{ ?>
									<input type="hidden" name="upimgcopy" id="upimgcopy" value=" "/>
								<?php
								}
								?>
							</div>
							<!--div.controls-->
						</div>
						<!--div.control-group#ad_img_name-->
						<!-- image upload-->
						<!-- for alternative ad checkbox-->
						<?php
						if ($displayData->special_access)
						{
							$checked = "";

							if ($displayData->edit_ad_id) // == 1)
							{
								if ($displayData->addata_for_adsumary_edit->ad_alternative)
								{
									$checked = 'checked="checked"';
								}
							} ?>

							<div class="control-group">
								<label>
									<input type="checkbox" name="altadbutton" id="altadbutton"
									onclick="sa.create.switchCheckboxalt(
									this, guestbutton ,'<?php echo JText::_("COM_SOCIALADS_BTN_SAVEANDNEXT");?>',
									'<?php echo JText::_("COM_SOCIALADS_BTN_SAVEANDEXIT");?>'
									)"  <?php echo $checked; ?> />
									<?php echo '<strong>' . JText::_('COM_SOCIALADS_ALT_AD') . '</strong>'; ?>
								</label>
								<div class="alert alert-info alert-help-inline">
									<?php echo JText::_('COM_SOCIALADS_ALT_AD_DESC');?>
								</div>
							</div>
							<div class="clearfix"></div>
						<?php
						}
						?>
						<!-- For alternative ad checkbox-->
						<!-- For guest ad checkbox-->
						<?php $guest_dis = 'style="display:none;"'; ?>
						<div class="control-group" <?php echo $guest_dis; ?>>
							<?php
							$buildadsession = JFactory::getSession();
							$guest = $buildadsession->get('guestbutton');
							$socialIntegration = $displayData->sa_params->get('social_integration');

							if (isset($guest) || $socialIntegration == 'Joomla')
							{
								$checked = "checked=checked";
							}
							else
							{
								$checked = "";
							}
							?>

							<div class="altbutton controls">
								<input type="checkbox" name="guestbutton" id="guestbutton" onclick="sa.create.switchCheckboxguest(this, 'altadbutton');" <?php echo $checked;?> />
								<span class="sa_labels"><?php echo JText::_('COM_SOCIALADS_SKIP_TARGET_AD'); ?></span>
								<?php echo JText::sprintf('COM_SOCIALADS_SKIP_TARGET_AD_DESC', $displayData->sitename); ?>
							</div>
						</div>
						<!-- for guest ad checkbox-->
						<div id="sa_ad_more_credit_radio" class="control-group" <?php echo $displayData->addMoreCredit ? "":"style='display:none'"; ?>>
							<label class="control-label" for="">
								<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_AD_MORE_CREDIT_TOOLTIP'), JText::_('COM_SOCIALADS_AD_MORE_CREDIT_TITLE'), '', JText::_('COM_SOCIALADS_AD_MORE_CREDIT_TITLE')); ?>
							</label>
							<div class="controls input-append targetting_yes_no">
								<input type="radio"
									name="add_more_credit"
									id="add_more_credit1" value="1">
								<label class="first btn " type="button" for="add_more_credit1">
									<?php echo JText::_('JYES');?>
								</label>

								<input type="radio"
									name="add_more_credit"
									id="add_more_credit2" value="0" checked="checked">
								<label class="last btn btn-danger" type="button" for="add_more_credit2">
									<?php echo JText::_('JNO');?>
								</label>

							</div>

						</div>


						<div class="control-group">
							<div id="sa-form-button"></div>
						</div>
					</div>
					<!--div.ad_info-->
				</div>
			</div>
			<!-- span6 ENDS-->
			<div class="form-horizontal">
				<div class="span6">
					<!--preview-->
					<div class="preview" >
						<!--start for layouts-->
						<div id="layout_div" class="control-group">
							<label for="layout1" title="<?php echo JText::_("COM_SOCIALADS_AD_LAYOUT_DESC");?>">
								<?php echo JHtml::tooltip(
								JText::_('COM_SOCIALADS_AD_LAYOUT_DESC'),
								JText::_('COM_SOCIALADS_AD_LAYOUT'), '', JText::_('COM_SOCIALADS_AD_LAYOUT')
								); ?>
							</label>
							<div class="controls">
								<span id="layout1" class="row-fluid"></span>
								<input type="hidden" name="ad_layout_nm" id="ad_layout_nm"
								value="<?php echo !empty($displayData->addata_for_adsumary_edit->layout) ? $displayData->addata_for_adsumary_edit->layout : ''; ?>"/>
							</div>
						</div>
						<!--end for layouts-->

						<!--sa_preview-->
						<div id="sa_preview" class="preview_sa">
							<div><span class="sa_labels"><?php echo JText::_('COM_SOCIALADS_AD_PREVIEW_MSG'); ?></span></div>
							<div class="ad-preview1" id="preview_sa"></div>
							<div style="clear:both;"></div>
						</div>
						<!--sa_preview-->
					</div>
					<!--preview ends here-->
				</div>
				<!-- span6 ad_preview ENDS-->
			</div>
		</fieldset>
	</div>
</div>
