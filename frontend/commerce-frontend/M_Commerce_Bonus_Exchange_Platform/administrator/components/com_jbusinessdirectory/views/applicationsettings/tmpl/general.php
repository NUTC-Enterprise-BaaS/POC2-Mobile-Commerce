<div class="row-fluid">
	<div class="span6 general-settings">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_GENERAL_SETTINGS'); ?></legend>
			<div class="control-group">
				<div class="control-label"><label id="company_name-lbl" for="company_name" class="hasTooltip" title=""><?php echo JText::_('LNG_DATE_FORMAT'); ?></label></div>
				<div class="controls">
					<select id='date_format_id' name='date_format_id'>
						<?php foreach ($this->item->dateFormats as $dateFormat){?>
							<option value = '<?php echo $dateFormat->id?>' <?php echo $dateFormat->id==$this->item->date_format_id? "selected" : ""?>> <?php echo $dateFormat->name?></option>
						<?php }	?>
					</select>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="time_format-lbl" for="time_format" class="hasTooltip" title=""><?php echo JText::_('LNG_TIME_FORMAT'); ?></label></div>
				<div class="controls">
					<select id='time_format' name='time_format'>
						<option value = "h:i A" <?php echo $this->item->time_format=="h:i A"? "selected" : ""?>><?php echo "12"." ".JText::_("LNG_HOURS")?></option>
						<option value = "H:i" <?php echo $this->item->time_format=="H:i"? "selected" : ""?>><?php echo "24"." ".JText::_("LNG_HOURS")?></option>
					</select>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="enable_rss-lbl" for="enable_rss" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_RSS'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_rss_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="enable_rss" id="enable_rss1" value="1" <?php echo $this->item->enable_rss==true? 'checked="checked"' :""?> />
						<label class="btn" for="enable_rss1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="enable_rss" id="enable_rss0" value="0" <?php echo $this->item->enable_rss==false? 'checked="checked"' :""?> />
						<label class="btn" for="enable_rss0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>

			<div class="control-group">
				<div class="control-label"><label id="enable_socials-lbl" for="enable_socials" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_SOCIALS'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_socials" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="enable_socials" id="enable_socials1" value="1" <?php echo $this->item->enable_socials==true? 'checked="checked"' :""?> />
						<label class="btn" for="enable_socials1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="enable_socials" id="enable_socials0" value="0" <?php echo $this->item->enable_socials==false? 'checked="checked"' :""?> />
						<label class="btn" for="enable_socials0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="enable_multilingual-lbl" for="enable_multilingual" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_MULTILINGUAL'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_multilingual_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="enable_multilingual" id="enable_multilingual1" value="1" <?php echo $this->item->enable_multilingual==true? 'checked="checked"' :""?> />
						<label class="btn" for="enable_multilingual1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="enable_multilingual" id="enable_multilingual0" value="0" <?php echo $this->item->enable_multilingual==false? 'checked="checked"' :""?> />
						<label class="btn" for="enable_multilingual0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="captcha-lbl" for="captcha" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_CAPTCHA'); ?></label></div>
				<div class="controls">
					<fieldset id="captcha_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="captcha" id="captcha1" value="1" <?php echo $this->item->captcha==true? 'checked="checked"' :""?> />
						<label class="btn" for="captcha1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="captcha" id="captcha0" value="0" <?php echo $this->item->captcha==false? 'checked="checked"' :""?> />
						<label class="btn" for="captcha0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			<div class="control-group" style="display:none">
				<div class="control-label"><label id="allow_multiple_companies-lbl" for="allow_multiple_companies" class="hasTooltip" title=""><?php echo JText::_('LNG_ALLOW_MULTIPLE_COMPANIES_PER_USER'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_packages_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="allow_multiple_companies" id="allow_multiple_companies1" value="1" <?php echo $this->item->allow_multiple_companies==true? 'checked="checked"' :""?> />
						<label class="btn" for="allow_multiple_companies1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="allow_multiple_companies" id="allow_multiple_companies0" value="0" <?php echo $this->item->allow_multiple_companies==false? 'checked="checked"' :""?> />
						<label class="btn" for="allow_multiple_companies0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="enable_bookmarks-lbl" for="enable_bookmarks" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_BOOKMARKS'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_bookmarks_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="enable_bookmarks" id="enable_bookmarks1" value="1" <?php echo $this->item->enable_bookmarks==true? 'checked="checked"' :""?> />
						<label class="btn" for="enable_bookmarks1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="enable_bookmarks" id="enable_bookmarks0" value="0" <?php echo $this->item->enable_bookmarks==false? 'checked="checked"' :""?> />
						<label class="btn" for="enable_bookmarks0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="enable_attachments-lbl" for="enable_packages" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_ATTACHMENTS'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_attachments_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="enable_attachments" id="enable_attachments1" value="1" <?php echo $this->item->enable_attachments==true? 'checked="checked"' :""?> />
						<label class="btn" for="enable_attachments1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="enable_attachments" id="enable_attachments0" value="0" <?php echo $this->item->enable_attachments==false? 'checked="checked"' :""?> />
						<label class="btn" for="enable_attachments0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>

			<div class="control-group">
				<div class="control-label"><label id="max_attachments-lbl" for="max_attachments" class="hasTooltip" title=""><?php echo JText::_('LNG_MAX_ATTACHMENTS'); ?></label></div>
				<div class="controls">
					<input type="text" size="40" maxlength="20"  id="max_attachments" name="max_attachments" value="<?php echo $this->item->max_attachments?>">
				</div>
			</div>
		
			<div class="control-group">
				<div class="control-label"><label id="metric-lbl" for="metric" class="hasTooltip" title=""><?php echo JText::_('LNG_METRIC'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_packages_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="metric" id="metric1" value="1" <?php echo $this->item->metric==true? 'checked="checked"' :""?> />
						<label class="btn" for="metric1"><?php echo JText::_('LNG_MILES')?></label> 
						<input type="radio" class="validate[required]" name="metric" id="metric0" value="0" <?php echo $this->item->metric==false? 'checked="checked"' :""?> />
						<label class="btn" for="metric0"><?php echo JText::_('LNG_KM')?></label> 
					</fieldset>
				</div>
			</div>

			<div class="control-group">
				<div class="control-label"><label id="expiration_day_notice-lbl" for="expiration_day_notice" class="hasTooltip" title=""><?php echo JText::_('LNG_EXPIRATION_DAYS_NOTICE'); ?></label></div>
				<div class="controls">
					<input type="text" size=40 maxlength=20  id="expiration_day_notice" name = "expiration_day_notice" value="<?php echo $this->item->expiration_day_notice?>">
				</div>
			</div>
			
			<div class="control-group" style="display:none">
				<div class="control-label"><label id="direct_processing-lbl" for="direct_processing" class="hasTooltip" title=""><?php echo JText::_('LNG_DIRECT_PROCESSING'); ?></label></div>
				<div class="controls">
					<fieldset id="direct_processing_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="direct_processing" id="direct_processing1" value="1" <?php echo $this->item->direct_processing==true? 'checked="checked"' :""?> />
						<label class="btn" for="direct_processing1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="direct_processing" id="direct_processing0" value="0" <?php echo $this->item->direct_processing==false? 'checked="checked"' :""?> />
						<label class="btn" for="direct_processing0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			
			
			
			<div class="control-group">
				<div class="control-label"><label id="front_end_acl-lbl" for="front_end_acl" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_FRONT_END_ACL'); ?></label></div>
				<div class="controls">
					<fieldset id="front_end_acl_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="front_end_acl" id="front_end_acl1" value="1" <?php echo $this->item->front_end_acl==true? 'checked="checked"' :""?> />
						<label class="btn" for="front_end_acl1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="front_end_acl" id="front_end_acl0" value="0" <?php echo $this->item->front_end_acl==false? 'checked="checked"' :""?> />
						<label class="btn" for="front_end_acl0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="usergroup-lbl" for="usergroup" class="hasTooltip" title=""><?php echo JText::_('LNG_CHOOSE_USERGROUP'); ?></label></div>
				<div class="controls">
					<select	id="usergroup" name="usergroup" class="chzn-color">
						<?php echo JHtml::_('select.options',$this->userGroups, 'value', 'name', $this->item->usergroup);?>
					</select>
				</div>
			</div>
		</fieldset>
	</div>
	<div class="span6 general-settings">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_CURRENCY'); ?></legend>
			<div class="control-group">
				<div class="control-label"><label id="company_name-lbl" for="company_name" class="hasTooltip" title=""><?php echo JText::_('LNG_NAME'); ?></label></div>
				<div class="controls">
					<select	id="currency_id" name="currency_id" class="chzn-color">
						<?php
							for($i = 0; $i <  count( $this->item->currencies ); $i++){
								$currency = $this->item->currencies[$i]; 
						?>
							<option value = '<?php echo $currency->currency_id?>' <?php echo $currency->currency_id==$this->item->currency_id? "selected" : ""?>> <?php echo $currency->currency_name." - ". $currency->currency_description ?></option>
						<?php }	?>
					</select>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_CURRENCY_SYMBOL'); ?></strong><br />Enter your business email" id="company_email-lbl" for="company_email" class="hasTooltip required" title=""><?php echo JText::_('LNG_CURRENCY_SYMBOL'); ?><span class="star">&nbsp;</span></label></div>
				<div class="controls"><input name="currency_symbol" id="currency_symbol" value="<?php echo $this->item->currency_symbol?>" size="50" type="text"></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="currency_display-lbl" for="enable_packages" class="hasTooltip" title=""><?php echo JText::_('LNG_CURRENCY_DISPLAY'); ?></label></div>
				<div class="controls">
					<fieldset id="currency_display_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="currency_display" id="currency_display1" value="1" <?php echo $this->item->currency_display==1? 'checked="checked"' :""?> />
						<label class="btn" for="currency_display1"><?php echo JText::_('LNG_NAME')?></label> 
						<input type="radio" class="validate[required]" name="currency_display" id="currency_display2" value="2" <?php echo $this->item->currency_display==2? 'checked="checked"' :""?> />
						<label class="btn" for="currency_display2"><?php echo JText::_('LNG_SYMBOL')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="currency_location-lbl" for="enable_packages" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_CURRENCY'); ?></label></div>
				<div class="controls">
					<fieldset id="currency_location_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="currency_location" id="currency_location1" value="1" <?php echo $this->item->currency_location==1? 'checked="checked"' :""?> />
						<label class="btn" for="currency_location1"><?php echo JText::_('LNG_BEFORE_PRICE')?></label> 
						<input type="radio" class="validate[required]" name="currency_location" id="currency_location2" value="2" <?php echo $this->item->currency_location==2? 'checked="checked"' :""?> />
						<label class="btn" for="currency_location2"><?php echo JText::_('LNG_AFTER_PRICE')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="amount_separator-lbl" for="enable_packages" class="hasTooltip" title=""><?php echo JText::_('LNG_AMOUNT_SEPARATOR'); ?></label></div>
				<div class="controls">
					<fieldset id="amount_separator_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="amount_separator" id="amount_separator1" value="1" <?php echo $this->item->amount_separator==1? 'checked="checked"' :""?> />
						<label class="btn" for="amount_separator1"><?php echo JText::_('LNG_DOT_SEPARATOR')?></label> 
						<input type="radio" class="validate[required]" name="amount_separator" id="amount_separator2" value="2" <?php echo $this->item->amount_separator==2? 'checked="checked"' :""?> />
						<label class="btn" for="amount_separator2"><?php echo JText::_('LNG_COMMA_SEPARATOR')?></label> 
					</fieldset>
				</div>
			</div>
		</fieldset>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
			<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_TERMS_AND_CONDITIONS'); ?></legend>
			<div class="control-group">
				<?php 
					$editor = JFactory::getEditor();
					echo $editor->display('terms_conditions', $this->item->terms_conditions, '550', '200', '80', '10', false);
				?>
			</div>
		</fieldset>
	</div>
</div>	
			
<?php include JPATH_COMPONENT_SITE.'/assets/uploader.php'; ?>			
<script>
	var appImgFolder = '<?php echo APP_PICTURES_PATH ?>';
	var appImgFolderPath = '<?php echo JURI::root()?>components/<?php echo JBusinessUtil::getComponentName()?>/assets/upload.php?t=<?php echo strtotime("now")?>&picture_type=<?php echo PICTURE_TYPE_LOGO?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".PICTURES_PATH) ?>&_target=<?php echo urlencode(APP_PICTURES_PATH)?>';
	var removePath = '<?php echo JURI::root()?>/components/<?php echo JBusinessUtil::getComponentName()?>/assets/remove.php?_root_app=<?php echo urlencode(JPATH_COMPONENT_SITE)?>&_filename=';
	
	imageUploader(appImgFolder, appImgFolderPath);
</script>