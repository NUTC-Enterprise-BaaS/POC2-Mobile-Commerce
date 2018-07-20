<?php
/**
 * @package    JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2007 - 2015 CMS Junkie. All rights reserved.
 * @license     GNU General Public License version 2 or later; 
 */

defined('_JEXEC') or die('Restricted access');

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
require_once JPATH_COMPONENT_SITE.'/classes/attributes/attributeservice.php';

JHTML::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/bootstrap-tagsinput.css');
JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/bootstrap-tagsinput.min.js');

// Load the tooltip behavior.
//JHtml::_('behavior.tooltip');

$maxPictures = isset($this->item->package)?$this->item->package->max_pictures:$this->appSettings->max_pictures;
$nrPictures = count($this->item->pictures);
$allowedNr = $maxPictures - $nrPictures;
$allowedNr=($allowedNr<0)?0:$allowedNr;
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		var defaultLang="<?php echo JFactory::getLanguage()->getTag() ?>";

		jQuery("#item-form").validationEngine('detach');
		var evt = document.createEvent("HTMLEvents");
		evt.initEvent("click", true, true);
		var tab = ("tab-"+defaultLang);
		if(!(document.getElementsByClassName(tab)[0] === undefined || document.getElementsByClassName(tab)[0] === null))
			document.getElementsByClassName(tab)[0].dispatchEvent(evt);
		if (task == 'offer.cancel' || task == 'offer.aprove' || task == 'offer.disaprove' || !validateCmpForm()) {
			Joomla.submitform(task, document.getElementById('item-form'));
		}
		jQuery("#item-form").validationEngine('attach');
	}
</script>

<?php 
$user = JFactory::getUser();

$options = array(
	'onActive' => 'function(title, description){
		description.setStyle("display", "block");
		title.addClass("open").removeClass("closed");
	}',
	'onBackground' => 'function(title, description){
		description.setStyle("display", "none");
		title.addClass("closed").removeClass("open");
	}',
	'startOffset' => 0,  // 0 starts on the first tab, 1 starts the second, etc...
	'useCookie' => true, // this must not be a string. Don't use quotes.
);
?>

<?php 
if(isset($isProfile)) { ?>
	<div class="button-row">
		<button type="button" class="ui-dir-button ui-dir-button-green" onclick="saveCompanyInformation();">
			<span class="ui-button-text"><i class="dir-icon-edit"></i> <?php echo JText::_("LNG_SAVE")?></span>
		</button>
		<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="cancel()">
			<span class="ui-button-text"><i class="dir-icon-remove-sign red"></i> <?php echo JText::_("LNG_CANCEL")?></span>
		</button>
	</div>
	<div class="clear"></div>		
<?php 
} ?>

<div class="category-form-container offer-form-container">
	<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-horizontal">
		<div class="clr mandatory oh">
			<p><?php echo JText::_("LNG_REQUIRED_INFO")?></p>
		</div>
		<div class="jbd-admin-column span6">
			<fieldset class="boxed">
				<h2> <?php echo JText::_('LNG_OFFER_DETAILS');?></h2>
				<p><?php echo JText::_('LNG_DISPLAY_INFO_TXT');?></p>
				<div class="form-box">
					<div class="detail_box">
						<div  class="form-detail req"></div>
						<label for="subject"><?php echo JText::_('LNG_SUBJECT')?> </label>
						<?php
							if($this->appSettings->enable_multilingual){
								echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
								foreach( $this->languages as $k=>$lng ){
									echo JHtml::_('tabs.panel', $lng, 'tab-'.$lng );
									$langContent = isset($this->translations[$lng."_name"])?$this->translations[$lng."_name"]:"";
									if($lng == JFactory::getLanguage()->getTag() && empty($langContent)){
										$langContent = $this->item->subject;
									}
									echo "<input type='text' name='subject_$lng' id='subject_$lng' class='input_txt validate[required]' value=\"".stripslashes($langContent)."\"  maxLength='110'>";
									echo "<div class='clear'></div>";
								}
								echo JHtml::_('tabs.end');
							} else { ?>
								<input type="text" name="subject" id="subject" class="input_txt validate[required]"  value="<?php echo $this->item->subject ?>" maxLength="110">
							<?php } ?>
						<div class="clear"></div>
						<span class="error_msg" id="frmCompanySubject_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
					</div>
					<div class="detail_box" style="<?php echo isset($isProfile)?"display:none":""?>">
						<label for="name"><?php echo JText::_('LNG_ALIAS')?> </label> 
						<input type="text"	name="alias" id="alias"  placeholder="<?php echo JText::_('LNG_AUTO_GENERATE_FROM_NAME')?>" class="input_txt text-input" value="<?php echo $this->item->alias ?>"  maxLength="100">
						<div class="clear"></div>
					</div>
					<div class="detail_box">
						<label for="categories"><?php echo JText::_('LNG_CATEGORY')?> </label>
						<select name="categories[]" id="categories" data-placeholder="<?php echo JText::_("LNG_SELECT_CAT") ?>" multiple class="chosen-select-categories">
							<?php echo JHtml::_('select.options', $this->categoryOptions, 'value', 'text', $this->item->selectedCategories); ?>
						</select>
						<div class="clear"></div>
					</div>
					<div class="detail_box">
						<label for="short_description"><?php echo JText::_('LNG_SHORT_DESCRIPTION')?>  &nbsp;&nbsp;&nbsp;</label>
						<?php 
						if($this->appSettings->enable_multilingual) {
							echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
							foreach( $this->languages  as $k=>$lng ) {
								echo JHtml::_('tabs.panel', $lng, 'tab'.$k );						
								$langContent = isset($this->translations[$lng."_short"])?$this->translations[$lng."_short"]:"";
								if($lng==JFactory::getLanguage()->getTag() && empty($langContent)) {
									$langContent = $this->item->short_description;
								}
								echo "<textarea id='short_description_$lng' name='short_description_$lng' class='input_txt' cols='75' rows='4' maxLength='250'>$langContent</textarea>";
								echo "<div class='clear'></div>";
							}
							echo JHtml::_('tabs.end');
						}
						else { ?>
							<textarea name="short_description" id="short_description" class="input_txt"  cols="75" rows="4"  maxLength="250"><?php echo $this->item->short_description ?></textarea>
						<?php 
						} ?>
					</div>
					<div class="detail_box">
						<div class="form-detail req"></div>
						<label for="description_id"><?php echo JText::_('LNG_DESCRIPTION')?>  &nbsp;&nbsp;&nbsp;</label>
						<?php 
						if($this->appSettings->enable_multilingual) {
							echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
							foreach( $this->languages  as $k=>$lng ) {
								echo JHtml::_('tabs.panel', $lng, 'tab_description_'.$lng );
								$langContent = isset($this->translations[$lng])?$this->translations[$lng]:"";
								if($lng==JFactory::getLanguage()->getTag() && empty($langContent)) {
									$langContent = $this->item->description;
								}
								$editor = JFactory::getEditor();
								echo $editor->display('description_'.$lng, $langContent, '95%', '200', '70', '10', false);
							}
							echo JHtml::_('tabs.end');
						}
						else {
							$editor = JFactory::getEditor();
							echo $editor->display('description', $this->item->description, '95%', '200', '70', '10', false);
						} ?>
					</div>
					<div class="detail_box">
						<label for="startDate"><?php echo JText::_('LNG_OFFER_START_DATE')?> </label> 
						<?php 
							$this->item->startDate = $this->item->startDate=="0000-00-00"?"":$this->item->startDate;
							echo JHTML::_('calendar', $this->item->startDate, 'startDate', 'startDate', $this->appSettings->calendarFormat, array('class'=>'inputbox calendar-date', 'size'=>'10',  'maxlength'=>'10')); 
						?>
						<div class="clear"></div>
					</div>
					<div class="detail_box">
						<label for="endDate"><?php echo JText::_('LNG_OFFER_END_DATE')?> </label>
						<?php 
							$this->item->endDate = $this->item->endDate=="0000-00-00"?"":$this->item->endDate;
							echo JHTML::_('calendar', $this->item->endDate, 'endDate', 'endDate', $this->appSettings->calendarFormat, array('class'=>'inputbox calendar-date', 'size'=>'10',  'maxlength'=>'10')); 
						?>
						<div class="clear"></div>
					</div>
					<div class="detail_box">
						<label for="startDate"><?php echo JText::_('LNG_PUBLISH_START_DATE')?> </label> 
						<?php 
						   $this->item->publish_start_date = $this->item->publish_start_date=="0000-00-00"?"":$this->item->publish_start_date;
						   echo JHTML::_('calendar', $this->item->publish_start_date, 'publish_start_date', 'publish_start_date', $this->appSettings->calendarFormat, array('class'=>'inputbox calendar-date', 'size'=>'10',  'maxlength'=>'10')); 
						?>
						<div class="clear"></div>
					</div>
					<div class="detail_box">
						<label for="endDate"><?php echo JText::_('LNG_PUBLISH_END_DATE')?> </label>
						<?php 
	    				   $this->item->publish_end_date= $this->item->publish_end_date=="0000-00-00"?"":$this->item->publish_end_date;
						   echo JHTML::_('calendar', $this->item->publish_end_date, 'publish_end_date', 'publish_end_date', $this->appSettings->calendarFormat, array('class'=>'inputbox calendar-date', 'size'=>'10',  'maxlength'=>'10')); ?>
						<div class="clear"></div>
					</div>
					<div class="detail_box" >
						<div class="form-detail req"></div>
						<label for="article_id"><?php echo JText::_('LNG_SHOW_REMAINING_TIME')?> </label> 
						<div>
							<fieldset id="show_time_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="show_time" id="show_time1" value="1" <?php echo $this->item->show_time==1? 'checked="checked"' :""?> />
								<label class="btn" for="show_time1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="show_time" id="show_time0" value="0" <?php echo $this->item->show_time==0? 'checked="checked"' :""?> />
								<label class="btn" for="show_time0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
						<div class="clear"></div>
					</div>
					<div class="detail_box">
						<label for="price"><?php echo JText::_('LNG_PRICE')?> </label> 
						<input type="text" name="price" id="price" class="input_txt" value="<?php echo $this->item->price ?>">
						<div class="clear"></div>
					</div>
					<div class="detail_box">
						<label for="specialPrice"><?php echo JText::_('LNG_SPECIAL_PRICE')?> </label> 
						<input type="text" name=specialPrice id="specialPrice" class="input_txt" value="<?php echo $this->item->specialPrice ?>">
						<div class="clear"></div>
					</div>
					<?php if($this->appSettings->enable_offer_coupons){?>
						<div class="detail_box">
							<label for="total_coupons"><?php echo JText::_('LNG_COUPONS_NUMBER')?> </label> 
							<input type="number" name="total_coupons" id="total_coupons" class="input_txt" value="<?php echo $this->item->total_coupons ?>">
							<div class="clear"></div>
						</div>
					<?php } ?>
					<div class="detail_box">
						<label for="currencyId"><?php echo JText::_('LNG_CURRENCY')?></label>
						<select data-placeholder="<?php echo JText::_("LNG_SELECT_CURRENCY") ?>" class="inputbox input-medium chosen-select" name="currencyId" id="currencyId">
							<option value=""><?php echo JText::_("LNG_SELECT_CURRENCY") ?></option>
							<?php echo JHtml::_('select.options', $this->currencies, 'currency_id', 'currency_name', $this->item->currencyId); ?>
						</select>
						<div class="clear"></div>
					</div>
					<div class="detail_box">
						<div class="form-detail req"></div>
						<label for="companyId"><?php echo JText::_('LNG_ASSOCIATED_COMPANY')?></label>
						<select data-placeholder="<?php echo JText::_("LNG_SELECT_COMPANY") ?>" class="inputbox input-medium validate[required]" name="companyId" id="companyId">
							<option value=""><?php echo JText::_("LNG_SELECT_COMPANY")?></option>
							<?php echo JHtml::_('select.options', $this->companies, 'id', 'name', $this->item->companyId);?>
						</select>
						<div class="clear"></div>
					</div>
					<div class="detail_box">
						<label for="state"><?php echo JText::_('LNG_STATE')?></label>
						<select class="inputbox input-medium validate[required]" name="state" id="state">
							<option value=""><?php echo JText::_("LNG_JOPTION_SELECT_STATUS") ?></option>
							<?php echo JHtml::_('select.options', $this->states, 'value', 'text', $this->item->state);?>
						</select>
						<div class="clear"></div>
					</div>
				</div>
			</fieldset>
			<fieldset class="boxed" style="display:none">
				<h2> <?php echo JText::_('LNG_CONFIGURATION');?></h2>
				<div class="form-box">
					<div class="detail_box" >
						<div class="form-detail req"></div>
						<label for="article_id"><?php echo JText::_('LNG_VIEW_TYPE')?> </label> 
						<div>
							<fieldset id="view_type_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="view_type" id="view_type1" value="1" <?php echo $this->item->view_type==1 || empty($this->item->view_type) ? 'checked="checked"' :""?> />
								<label class="btn" for="view_type1"><?php echo JText::_('LNG_OFFER')?></label> 
								<input type="radio" class="validate[required]" name="view_type" id="view_type2" value="2" <?php echo $this->item->view_type==2? 'checked="checked"' :""?> />
								<label class="btn" for="view_type2"><?php echo JText::_('LNG_ARTICLE')?></label> 
								<input type="radio" class="validate[required]" name="view_type" id="view_type3" value="3" <?php echo $this->item->view_type==3? 'checked="checked"' :""?> />
								<label class="btn" for="view_type3"><?php echo JText::_('LNG_URL')?></label> 
							</fieldset>
						</div>
						<div class="clear"></div>
					</div>
					<div class="detail_box">
						<label for="article_id"><?php echo JText::_('LNG_ARTICLE_ID')?> </label> 
						<input class="input_txt" type="text" name="article_id" id="article_id" value="<?php echo $this->item->article_id ?>">
						<div class="clear"></div>
					</div>
					<div class="detail_box">
						<label for="url"><?php echo JText::_('LNG_URL')?> </label>
						<input class="input_txt" type="text" name="url" id="url" value="<?php echo $this->item->url ?>">
						<div class="clear"></div>
					</div>
				</div>	
			</fieldset>
			<?php
			if(!empty($this->item->customFields)){?>
				<fieldset class="boxed">
					<h2> <?php echo JText::_('LNG_ADDITIONAL_INFO');?></h2>
					<p><?php echo JText::_('LNG_ADDITIONAL_INFO_TEXT');?></p>
					<div class="form-box">
						<?php
							$renderedContent = AttributeService::renderAttributes($this->item->customFields, false, array());
							echo $renderedContent;
						?>
					</div>
				</fieldset>
				<?php
			} ?>
			<fieldset class="boxed">
				<h2> <?php echo JText::_('LNG_LOCATION');?></h2>
				<div class="form-box">
					<div class="detail_box">
						<label for="address_id"><?php echo JText::_('LNG_ADDRESS')?></label> 
						<input type="text" id="autocomplete" class="input_txt" placeholder="<?php echo JText::_("LNG_ENTER_ADDRESS") ?>" onFocus="" ></input>
						<div class="clear"></div>
					</div>
					<div class="detail_box">
						<label for="subject"><?php echo JText::_('LNG_ADDRESS')?> </label> 
						<input type="text" name="address" id="route" class="input_txt" value="<?php echo $this->item->address ?>">
						<div class="clear"></div>					
					</div>
					<div class="detail_box">
						<label for="subject"><?php echo JText::_('LNG_CITY')?> </label> 
						<input type="text" name="city" id="locality" class="input_txt" value="<?php echo $this->item->city ?>">
						<div class="clear"></div>					
					</div>
					<div class="detail_box">
						<label for="subject"><?php echo JText::_('LNG_REGION')?> </label> 
						<input type="text" name="county" id="administrative_area_level_1" class="input_txt" value="<?php echo $this->item->county ?>">
						<div class="clear"></div>					
					</div>
					<div class="detail_box">
						<label for="latitude"><?php echo JText::_('LNG_LATITUDE')?> </label> 
						<p class="small"><?php echo JText::_('LNG_MAP_INFO')?></p>
						<input class="input_txt" type="text" name="latitude" id="latitude" value="<?php echo $this->item->latitude ?>">
						<div class="clear"></div>
					</div>
					<div class="detail_box">
						<label for="longitude"><?php echo JText::_('LNG_LONGITUDE')?> </label>
						<p class="small"><?php echo JText::_('LNG_MAP_INFO')?></p>
						<input class="input_txt" type="text" name="longitude" id="longitude" value="<?php echo $this->item->longitude ?>">
						<div class="clear"></div>
					</div>
					<div id="map-container">
						<div id="company_map"></div>
					</div>	
				</div>
			</fieldset>
			<fieldset class="boxed">
				<h2> <?php echo JText::_('LNG_OFFER_PICTURES');?></h2>
				<p> <?php echo JText::_('LNG_OFFER_PICTURES_INFORMATION_TEXT');?>.</p>
				<div class="form-box">
					<div class="detail_box">
						<input type='button' name='btn_removefile' id='btn_removefile' value='x' style='display:none'>
						<input type='hidden' name='crt_pos' id='crt_pos' value=''>
						<input type='hidden' name='crt_path' id='crt_path' value=''>
						<TABLE class='picture-table' align='left' border='0'>
							<TR>
								<TD>
									<TABLE class="admintable" align='center' id='table_pictures' name='table_pictures'>
										<?php 
										foreach( $this->item->pictures as $picture ) { ?>
											<TR>
												<td>
													<img class='img_picture' src='<?php echo JURI::root().PICTURES_PATH.$picture['picture_path']?>'/>
													<?php echo substr(basename($picture['picture_path']),0,30)?>
													<input type='hidden' value='<?php echo $picture['picture_enable']?>' name='picture_enable[]' id='picture_enable'>
													<input type='hidden' value='<?php echo $picture['picture_path']?>' name='picture_path[]' id='picture_path'>
													<br>
													<textarea cols='50' rows='1' name='picture_info[]' id='picture_info'><?php echo $picture['picture_info']?></textarea>
												</td>
												<td align='center'>
													<img class='btn_picture_delete' src='<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/del_options.gif"?>'
														onclick="
															if(!confirm('<?php echo JText::_('LNG_CONFIRM_DELETE_PICTURE',true)?>')) 
																return; 
															var row = jQuery(this).parents('tr:first');
															var row_idx = row.prevAll().length;
															jQuery('#crt_pos').val(row_idx);
															jQuery('#crt_path').val('<?php echo $picture['picture_path']?>');
															jQuery('#btn_removefile').click();"/>
												</td>
												<td align='center'>
													<img class='btn_picture_status' src='<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/".($picture['picture_enable'] ? 'checked' : 'unchecked').".gif"?>'
														onclick="
															var form = document.adminForm;
															var v_status = null;
															var pos = jQuery(this).closest('tr')[0].sectionRowIndex;
		
															if( form.elements['picture_enable[]'].length == null ) {
																v_status  = form.elements['picture_enable[]'];
															}
															else {
																v_status  = form.elements['picture_enable[]'][pos];
															}
															if( v_status.value == '1') {
																jQuery(this).attr('src', '<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/unchecked.gif"?>');
																v_status.value ='0';
															}
															else {
																jQuery(this).attr('src', '<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/checked.gif"?>');
																v_status.value ='1';
															}"/>
												</td>
												<td align="center">
													<span class="span_up" onclick='var row = jQuery(this).parents("tr:first");  row.insertBefore(row.prev());'>
														<img src="<?php echo JURI::root()?>administrator/components/<?php echo JBusinessUtil::getComponentName()?>/assets/img/up-icon.png">
													</span>
													<span class="span_down" onclick='var row = jQuery(this).parents("tr:first"); row.insertAfter(row.next());'>
														<img src="<?php echo JURI::root()?>administrator/components/<?php echo JBusinessUtil::getComponentName()?>/assets/img/down-icon.png">
													</span>
												</td>
											</TR>
										<?php 
										} ?>
									</TABLE>
								</TD>
							</TR>
							<?php if($allowedNr!=0) { ?>
							<TR>
								<TD>
									<div class="dropzone dropzone-previews" id="file-upload">
										<div id="actions" style="margin-left:-15px;margin-top:-15px;" class="row">
											<div class="col-lg-7">
												<!-- The fileinput-button span is used to style the file input field as button -->
															 <span class="btn btn-success fileinput-button dz-clickable">
																<i class="glyphicon glyphicon-plus"></i>
																<span><?php echo JText::_('LNG_ADD_FILES'); ?></span>
															 </span>
												<button  class="btn btn-primary start" id="submitAll">
													<i class="glyphicon glyphicon-upload"></i>
													<span><?php echo JText::_('LNG_UPLOAD_ALL'); ?></span>
												</button>
											</div>
										</div>
									</div>
								</TD>
							</TR>
							<?php } ?>
						</TABLE>
					</div>
				</div>
			</fieldset>
			<?php 
			if($this->appSettings->enable_attachments == 1) { ?>
				<fieldset class="boxed">
					<h2> <?php echo JText::_('LNG_ATTACHMENTS');?></h2>
					<p> <?php echo JText::_('LNG_ATTACHMENTS_INFORMATION_TEXT');?>.</p>
					<div class="form-box">
						<div class="detail_box">
							<input type='button' name='btn_removefile_at' id='btn_removefile_at' value='x' style='display:none'>
							<input type='hidden' name='crt_pos_a' id='crt_pos_a' value=''>
							<input type='hidden' name='crt_path_a' id='crt_path_a' value=''>
							<table class='picture-table' align='left' border='0'>
								<tr>
									<td align='left' class="key">
										<?php echo JText::_('LNG_ATTACHMENTS'); ?>:
									</td>
								</tr>
								<TR>
									<TD>
										<TABLE class="admintable" align='center' id='table_attachments' name='table_attachments' >
											<?php 
											if(!empty($this->item->attachments))
												foreach( $this->item->attachments as $attachment ) { ?>
													<TR>
														<TD align='left'>
															<input type="text" name='attachment_name[]' id='attachment_name' value="<?php echo $attachment->name ?>" /><br/>
															<?php echo basename($attachment->path)?>
														</TD>
														<td align='center'>
															<img class='btn_attachment_delete' src='<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/del_options.gif"?>'
																onclick="
																	if(!confirm('<?php echo JText::_('LNG_CONFIRM_DELETE_ATTACHMENT',true)?>')) 
																		return; 
																	var row = jQuery(this).parents('tr:first');
																	var row_idx = row.prevAll().length;
																	jQuery('#crt_pos_a').val(row_idx);
																	jQuery('#crt_path_a').val('<?php echo $attachment->path?>');
																	jQuery('#btn_removefile_at').click();"/>
														</td>
														<td align='center'>
															<input type='hidden' value='<?php echo $attachment->status?>' name='attachment_status[]' id='attachment_status'>
															<input type='hidden' value='<?php echo $attachment->path?>' name='attachment_path[]' id='attachment_path'>
															<img class='btn_attachment_status' src='<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/".($attachment->status ? 'checked' : 'unchecked').".gif"?>'
																onclick="
																	var form = document.adminForm;
																	var v_status = null;
																	var pos = jQuery(this).closest('tr')[0].sectionRowIndex;
		
																	if( form.elements['attachment_status[]'].length == null ) {
																		v_status  = form.elements['attachment_status[]'];
																	}
																	else {
																		v_status  = form.elements['attachment_status[]'][pos];
																	}
																	if( v_status.value == '1') {
																		jQuery(this).attr('src', '<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/unchecked.gif"?>');
																		v_status.value ='0';
																	} 
																	else {
																		jQuery(this).attr('src', '<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/checked.gif"?>');
																		v_status.value ='1';
																	}"/>
														</td>
														<td align="center">
															<span class="span_up" onclick='var row = jQuery(this).parents("tr:first");  row.insertBefore(row.prev());'>
																<img src="<?php echo JURI::root()?>administrator/components/<?php echo JBusinessUtil::getComponentName()?>/assets/img/up-icon.png">
															</span>
															<span class="span_down" onclick='var row = jQuery(this).parents("tr:first"); row.insertAfter(row.next());'>
																<img src="<?php echo JURI::root()?>administrator/components/<?php echo JBusinessUtil::getComponentName()?>/assets/img/down-icon.png">
															</span>
														</td>
													</TR>
												<?php 
												} ?>
										</TABLE>
									</TD>
								</TR>
								<TR>
									<TD colspan ="2">
										<?php echo JText::_('LNG_PLEASE_CHOOSE_A_FILE'); ?> <input name="uploadAttachment" id="multiFileUploader" size="50" type="file" />
									</TD>
								</TR>
							</TABLE>
						</div>
					</div>
				</fieldset>
			<?php 
			} ?>
		</div>
		<div class="jbd-admin-column span6 padding-left-15">
			<fieldset class="boxed">
				<h2> <?php echo JText::_('LNG_METADATA_INFORMATION');?></h2>
				<p> <?php echo JText::_('LNG_METADATA_INFORMATION_TEXT');?>.</p>
				<div class="form-box">
					<div class="detail_box">
						<label for="meta_title"><?php echo JText::_('LNG_META_TITLE')?></label> 
						<input type="text" name="meta_title" id="meta_title" class="input_txt" value="<?php echo $this->item->meta_title ?>">
						<div class="clear"></div>
					</div>
				</div>
				<div class="form-box">
					<div class="detail_box">
						<label for="meta_description"><?php echo JText::_('LNG_META_DESCRIPTION')?></label>
						<textarea  name="meta_description" id="meta_description" rows="4"><?php echo $this->item->meta_description ?></textarea>
						<div class="clear"></div>
					</div>
				</div>
				<div class="form-box">
					<div class="bootstrap-tags">
						<label for="meta_keywords"><?php echo JText::_('LNG_META_KEYWORDS')?></label>
						<input type="text" data-role="tagsinput" name="meta_keywords" class="input_txt" id="meta_keywords" value="<?php echo $this->item->meta_keywords ?>"/>
						<div class="clear"></div>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="jbd-admin-column span12">
			<?php 
			if(isset($isProfile)) { ?>
				<div class="button-row">
					<button type="button" class="ui-dir-button ui-dir-button-green" onclick="saveCompanyInformation();">
						<span class="ui-button-text"><i class="dir-icon-edit"></i> <?php echo JText::_("LNG_SAVE")?></span>
					</button>
					<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="cancel()">
						<span class="ui-button-text"><i class="dir-icon-remove-sign red"></i> <?php echo JText::_("LNG_CANCEL")?></span>
					</button>
				</div>
			<?php 
			} ?>
		</div>

		<script type="text/javascript">
			function saveCompanyInformation() {
				var defaultLang="<?php echo JFactory::getLanguage()->getTag() ?>";
				var evt = document.createEvent("HTMLEvents");
				evt.initEvent("click", true, true);
				var tab = ("tab-"+defaultLang);
				if(!(document.getElementsByClassName(tab)[0] === undefined || document.getElementsByClassName(tab)[0] === null))
					document.getElementsByClassName(tab)[0].dispatchEvent(evt);
				if(validateCmpForm())
					return false;
				jQuery("#task").val('managecompanyoffer.save');
				var form = document.adminForm;
				form.submit();

			}
			
			function cancel() {
				jQuery("#task").val('managecompanyoffer.cancel');
				var form = document.adminForm;
				form.submit();
			}
			
			function validateCmpForm() {
				validateRichTextEditors();
				var isError = jQuery("#item-form").validationEngine('validate');
				return !isError;
			}

			function validateRichTextEditors(){
				var lang = '';
				<?php if($this->appSettings->enable_multilingual){ ?>
				lang = '_<?php echo JFactory::getLanguage()->getTag() ?>';
				var evt = document.createEvent("HTMLEvents");
				evt.initEvent("click", true, true);
				var tab = ("tab_description"+lang);
				if(!(document.getElementsByClassName(tab)[0] === undefined || document.getElementsByClassName(tab)[0] === null))
					document.getElementsByClassName(tab)[0].dispatchEvent(evt);
				<?php } ?>
				jQuery(".editor").each(function(){
					var textarea = jQuery(this).find('textarea');
					tinyMCE.triggerSave();
					if(textarea.attr('id') == 'description'+lang) {
						if (jQuery.trim(textarea.val()).length > 0) {
							if (jQuery(this).hasClass("validate[required]"))
								jQuery(this).removeClass("validate[required]");
						}
						else {
							if (!jQuery(this).hasClass("validate[required]"))
								jQuery(this).addClass("validate[required]");
						}
					}
				});
			}
		</script>
		<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" /> 
		<input type="hidden" name="task" id="task" value="" /> 
		<input type="hidden" name="id" value="<?php echo $this->item->id ?>" /> 
	
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>

<?php include JPATH_COMPONENT_SITE.'/assets/uploader.php'; ?>

<script>
	var maxCategories = <?php echo isset($this->item->package)?$this->item->package->max_categories :$this->appSettings->max_categories ?>;

	var offerFolder = '<?php echo OFFER_PICTURES_PATH.((int)$this->item->id)."/"?>';
	var offerFolderPath = '<?php echo JURI::root()?>components/<?php echo JBusinessUtil::getComponentName()?>/assets/upload.php?t=<?php echo strtotime("now")?>&picture_type=<?php echo PICTURE_TYPE_OFFER?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".PICTURES_PATH)?>&_target=<?php echo urlencode(OFFER_PICTURES_PATH.((int)$this->item->id)."/")?>';
	var offerAttachFolderPath = '<?php echo JURI::root()?>components/<?php echo JBusinessUtil::getComponentName()?>/assets/uploadFile.php?t=<?php echo strtotime("now")?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".ATTACHMENT_PATH)?>&_target=<?php echo urlencode(OFFER_PICTURES_PATH.((int)$this->item->id)."/")?>'
	var removePath = '<?php echo JURI::root()?>/components/<?php echo JBusinessUtil::getComponentName()?>/assets/remove.php?_root_app=<?php echo urlencode(JPATH_COMPONENT_ADMINISTRATOR)?>&_filename=';
	var removePath_at = '<?php echo JURI::root()?>/components/<?php echo JBusinessUtil::getComponentName()?>/assets/remove.php?_root_app=<?php echo urlencode(JPATH_COMPONENT_SITE)?>&_filename=';

	jQuery(document).ready(function () {
		imageUploaderDropzone("#file-upload", '<?php echo JURI::root()?>components/<?php echo JBusinessUtil::getComponentName()?>/assets/upload.php?t=<?php echo strtotime("now")?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".PICTURES_PATH) ?>&_target=<?php echo urlencode(OFFER_PICTURES_PATH.($this->item->id+0)."/")?>',".fileinput-button","<?php echo JText::_('LNG_DRAG_N_DROP',true); ?>", offerFolder ,<?php echo $allowedNr ?>,"addPicture");
	});

	multiImageUploader(offerFolder, offerFolderPath);
	multiFileUploader(offerFolder, offerAttachFolderPath);
	btn_removefile(removePath);
	btn_removefile_at(removePath_at);

	jQuery(document).ready(function() {
		jQuery("#item-form").validationEngine('attach');

		jQuery(".chosen-select").chosen({width:"95%", disable_search_threshold: 5});
		jQuery(".chosen-select-categories").chosen({width:"95%", max_selected_options: maxCategories});

		initializeAutocomplete();
	});

	var map;
	var markers = [];

	function initialize() {
		<?php 	
			$map_latitude = $this->appSettings->map_latitude;
			if ((empty($map_latitude)) || (!is_numeric($map_latitude)))
				$map_latitude = 0;

			$map_longitude = $this->appSettings->map_longitude;
			if ((empty($map_longitude)) || (!is_numeric($map_longitude)))
				$map_longitude = 0;
			
			$map_zoom = $this->appSettings->map_zoom;
			if ((empty($map_zoom)) || (!is_numeric($map_zoom)))
				$map_zoom = 10;

			$latitude = !empty($this->item->latitude)?$this->item->latitude:$map_latitude;
			$longitude = !empty($this->item->longitude)?$this->item->longitude:$map_longitude;
		?>

		var companyLocation = new google.maps.LatLng(<?php echo $latitude ?>, <?php echo $longitude ?>);
		var mapOptions = {
			zoom: <?php echo empty($this->item->latitude)?$map_zoom:15?>,
			center: companyLocation,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		
		var mapdiv = document.getElementById("company_map");
		mapdiv.style.width = '100%';
		mapdiv.style.height = '400px';
		map = new google.maps.Map(mapdiv,  mapOptions);
		var latitude = '<?php echo  $this->item->latitude ?>';
		var longitude = '<?php echo  $this->item->longitude ?>';
		if(latitude && longitude)
	    	addMarker(new google.maps.LatLng(latitude, longitude ));
		google.maps.event.addListener(map, 'click', function(event) {
			deleteOverlays();
			addMarker(event.latLng);
		});
	}

	//Add a marker to the map and push to the array.
	function addMarker(location) {
		document.getElementById("latitude").value = location.lat();
		document.getElementById("longitude").value = location.lng();
		marker = new google.maps.Marker({
			position: location,
			map: map
		});
		markers.push(marker);
	}

	//Sets the map on all markers in the array.
	function setAllMap(map) {
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(map);
		}
	}

	//Removes the overlays from the map, but keeps them in the array.
	function clearOverlays() {
		setAllMap(null);
	}

	//Shows any overlays currently in the array.
	function showOverlays() {
		setAllMap(map);
	}

	//Deletes all markers in the array by removing references to them.
	function deleteOverlays() {
		clearOverlays();
		markers = [];
	}
	var initialized = false;  


	var placeSearch, autocomplete;
	var component_form = {
		'route': 'long_name',
		'locality': 'long_name',
		'administrative_area_level_1': 'long_name'
	};

	function initializeAutocomplete() {
		autocomplete = new google.maps.places.Autocomplete(document.getElementById('autocomplete'), { types: [ 'geocode' ] });
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
			fillInAddress();
		});
	}

	function fillInAddress() {
		var place = autocomplete.getPlace();

		for (var component in component_form) {
			var obj = document.getElementById(component);
			if(typeof maybeObject != "undefined") {
				document.getElementById(component).value = "";
				document.getElementById(component).disabled = false;
			}
		}

		for (var j = 0; j < place.address_components.length; j++) {
			var att = place.address_components[j].types[0];
			if (component_form[att]) {
				var val = place.address_components[j][component_form[att]];
				jQuery("#"+att).val(val);
				if(att=='country') {
					jQuery('#country option').filter(function () {
						return jQuery(this).text() === val;
					}).attr('selected', true);
				}
			}
		}

		if(typeof map != "undefined") {
			if (place.geometry.viewport) {
				map.fitBounds(place.geometry.viewport);
			}
			
				map.setCenter(place.geometry.location);
				map.setZoom(17); 
				addMarker(place.geometry.location);
			
		}
	}
  
	function geolocate() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				var geolocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
				autocomplete.setBounds(new google.maps.LatLngBounds(geolocation, geolocation));
			});
		}
	}

	window.onload = initialize;
</script>