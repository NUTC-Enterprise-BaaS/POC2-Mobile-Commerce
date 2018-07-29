<?php 
$db = JFactory::getDBO(); 
$sql = "SELECT * FROM #__viewlevels ORDER BY ordering";
$db->setQuery($sql);
$levels = $db->loadObjectList();
?>	
		<fieldset class="adminform">
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("support_access_level"); ?>:
					</td>
					<td style="width:250px;">
						<select name="support_access_level">
							<?php foreach ($levels as $level): ?>
							<option value="<?php echo $level->id; ?>" <?php if ($this->settings['support_access_level'] == $level->id) echo " SELECTED"; ?> ><?php echo $level->title; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_access_level'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("support_open_access_level"); ?>:
					</td>
					<td style="width:250px;">
						<select name="support_open_access_level">
							<?php foreach ($levels as $level): ?>
							<option value="<?php echo $level->id; ?>" <?php if ($this->settings['support_open_access_level'] == $level->id) echo " SELECTED"; ?> ><?php echo $level->title; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_open_access_level'); ?></div>
					</td>
				</tr>


				<tr>
					<td align="left" class="key">
						<?php echo JText::_("support_captcha_type"); ?>:
					</td>
					<td style="width:250px;">
						<select name="support_captcha_type">
							<option value="none" <?php if ($this->settings['support_captcha_type'] == "none") echo " SELECTED"; ?> ><?php echo JText::_('FNONE'); ?></option>
							<option value="fsj" <?php if ($this->settings['support_captcha_type'] == "fsj") echo " SELECTED"; ?> ><?php echo JText::_('BUILT_IN'); ?> - <?php echo JText::_('All Users'); ?></option>
							<option value="ur-fsj" <?php if ($this->settings['support_captcha_type'] == "ur-fsj") echo " SELECTED"; ?> ><?php echo JText::_('BUILT_IN'); ?> - <?php echo JText::_('Unregistered Only'); ?></option>
							<option value="recaptcha" <?php if ($this->settings['support_captcha_type'] == "recaptcha") echo " SELECTED"; ?> ><?php echo JText::_('RECAPTCHA'); ?> - <?php echo JText::_('All Users'); ?></option>
							<option value="ur-recaptcha" <?php if ($this->settings['support_captcha_type'] == "ur-recaptcha") echo " SELECTED"; ?> ><?php echo JText::_('RECAPTCHA'); ?> - <?php echo JText::_('Unregistered Only'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_captcha_type'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("SUPPORT_ONLY_ADMIN_OPEN"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_only_admin_open' value='1' <?php if ($this->settings['support_only_admin_open'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_only_admin_open'); ?></div>
					</td>
				</tr>				
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("NO_LOGIN_ON_OPEN"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_no_logon' value='1' <?php if ($this->settings['support_no_logon'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_no_logon'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("support_no_register"); ?>:
					
					</td>
					<td>
						<select name="support_no_register">
							<option value="1" <?php if ($this->settings['support_no_register'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('DONT_SHOW'); ?></option>
							<option value="0" <?php if ($this->settings['support_no_register'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('SHOW_ON_OPEN_TICKET'); ?></option>
							<option value="2" <?php if ($this->settings['support_no_register'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('SHOW_ON_OPEN_AND_VIEW_TICKET'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_no_register'); ?></div>
					</td>
				</tr>
				
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("SETTING_SUPPORT_ALTCAT"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_altcat' value='1' <?php if ($this->settings['support_altcat'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SUPPORT_ALTCAT'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("RESTRICT_TO_GROUPS_PRODUCTS"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_restrict_prod' value='1' <?php if ($this->settings['support_restrict_prod'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_restrict_prod'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("support_default_priority"); ?>:
					
					</td>
					<td style="width:250px;">
						<select name="support_default_priority">
							<option value="" <?php if ($this->settings['support_default_priority'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('support_default_priority_default'); ?></option>
							<?php
							$db = JFactory::getDBO();
							$qry = "SELECT * FROM #__fss_ticket_pri ORDER BY ordering";
							$db->setQuery($qry);
							$pris = $db->loadObjectList();
							FSS_Translate_Helper::Tr($pris);
							foreach ($pris as $pri): ?>
								<option value="<?php echo $pri->id; ?>" <?php if ($this->settings['support_default_priority'] == $pri->id) echo " SELECTED"; ?> ><?php echo $pri->title; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_default_priority'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>
		
	
	
		<fieldset class="adminform">
			<legend><?php echo JText::_("PRODUCT_AND_DEPARTMENT"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("support_advanced_department"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='support_advanced_department' value='1' <?php if ($this->settings['support_advanced_department'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_advanced_department'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("ticket_prod_per_page"); ?>:
					</td>
					<td>
						<?php $this->PerPage('ticket_prod_per_page'); ?>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_ticket_prod_per_page'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("support_product_manual_category_order"); ?>:
					
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='support_product_manual_category_order' value='1' <?php if ($this->settings['support_product_manual_category_order'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_product_manual_category_order'); ?></div>
					</td>
				</tr>
					<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("support_advanced_search"); ?>:
					
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='support_advanced_search' value='1' <?php if ($this->settings['support_advanced_search'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_advanced_search'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("support_open_accord"); ?>:
					
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='support_open_accord' value='1' <?php if ($this->settings['support_open_accord'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_open_accord'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("support_open_cat_prefix"); ?>:
					</td>
					<td>
						<input name='support_open_cat_prefix' type="text" value='<?php echo $this->settings['support_open_cat_prefix']; ?>' />
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_open_cat_prefix'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>



		<fieldset class="adminform">
			<legend><?php echo JText::_("OPEN_TICKET_SEARCH"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
	
							<?php echo JText::_("open_search_enabled"); ?>:	
					</td>
					<td>
						<select name="open_search_enabled">
							<option value="" <?php if ($this->settings['open_search_enabled'] < 1) echo " SELECTED"; ?> ><?php echo JText::_('JDisabled'); ?></option>
							<option value="1" <?php if ($this->settings['open_search_enabled'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('OPEN_SEARCH_ENABLED_1'); ?></option>
							<option value="2" <?php if ($this->settings['open_search_enabled'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('OPEN_SEARCH_ENABLED_2'); ?></option>
							<option value="3" <?php if ($this->settings['open_search_enabled'] == 3) echo " SELECTED"; ?> ><?php echo JText::_('OPEN_SEARCH_ENABLED_3'); ?></option>
							<!--<option value="4" <?php if ($this->settings['open_search_enabled'] == 4) echo " SELECTED"; ?> ><?php echo JText::_('OPEN_SEARCH_ENABLED_4'); ?></option>-->
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_open_search_enabled'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("open_search_live"); ?>:
					</td>
					<td>
						<input type='checkbox' name='open_search_live' value='1' <?php if ($this->settings['open_search_live'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_open_search_live'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>
	
	

		<fieldset class="adminform">
			<legend><?php echo JText::_("UNREGISTERED_USERS"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
	
							<?php echo JText::_("ALLOW_TICKETS_BY_UNREGISTERED_USERS"); ?>:	
					</td>
					<td>
						<select name="support_allow_unreg">
							<option value="0" <?php if ($this->settings['support_allow_unreg'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('JNO'); ?></option>
							<option value="1" <?php if ($this->settings['support_allow_unreg'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('JYES'); ?></option>
							<option value="2" <?php if ($this->settings['support_allow_unreg'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('support_allow_unreg_2'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_allow_unreg'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("support_unreg_type"); ?>:
					
					</td>
					<td>
						<select name="support_unreg_type">
							<option value="0" <?php if ($this->settings['support_unreg_type'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('SUPPORT_UNREG_TYPE_0'); ?></option>
							<option value="1" <?php if ($this->settings['support_unreg_type'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('SUPPORT_UNREG_TYPE_1'); ?></option>
							<option value="2" <?php if ($this->settings['support_unreg_type'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('SUPPORT_UNREG_TYPE_2'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_unreg_typeg'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("support_unreg_domain_restrict"); ?>:
					
					</td>
					<td style="width:250px;">
						<select name="support_unreg_domain_restrict">
							<option value="0" <?php if ($this->settings['support_unreg_domain_restrict'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('Unrestricted'); ?></option>
							<option value="1" <?php if ($this->settings['support_unreg_domain_restrict'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('Only Domains'); ?></option>
							<option value="2" <?php if ($this->settings['support_unreg_domain_restrict'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('Exclude Domains'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_unreg_domain_restrict'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("support_unreg_domain_list"); ?>:
					
					</td>
					<td>
						<textarea name='support_unreg_domain_list' id="support_unreg_domain_list" rows="6" cols="40" style="float:none;"><?php echo $this->settings['support_unreg_domain_list']; ?></textarea><br>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_unreg_domain_list'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("DONT_CHECK_EMAIL_ON_UNREG"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_dont_check_dupe' value='1' <?php if ($this->settings['support_dont_check_dupe'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_dont_check_dupe'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>
	
	
				
		<fieldset class="adminform">
			<legend><?php echo JText::_("TICKET_DETAILS_AND_VISUAL"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
			
	
				<tr>
					<td valign="top" align="left" class="key">
					
							<?php echo JText::_("TICKET_REFERENCE"); ?>:
					
					</td>
					<td valign="top" width="200">
						<p><input name='support_reference' type="text" id='support_reference' value='<?php echo $this->settings['support_reference']; ?>' /></p>
						<p><button class="btn" onclick="testreference();return false;"><?php echo JText::_("TEST_REFERENCE_NO"); ?></button></p>
						<p><div id="testref"></div></p>
					</td>
					<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_reference'); ?></div>
					</td>
				</tr>
				

				<tr>
					<td align="left" class="key">				
							<?php echo JText::_("HIDE_MESSAGE_SUBJECT"); ?>:
					</td>
					<td>
						<select name="support_subject_message_hide">
							<option value="none"><?php echo JText::_('SHOW_BOTH'); ?></option>
							<option value="subject" <?php if ($this->settings['support_subject_message_hide'] == "subject") echo " SELECTED"; ?> ><?php echo JText::_('HIDE_SUBJECT'); ?></option>
							<option value="message" <?php if ($this->settings['support_subject_message_hide'] == "message") echo " SELECTED"; ?> ><?php echo JText::_('HIDE_MESSAGE'); ?></option>
							<option value="both" <?php if ($this->settings['support_subject_message_hide'] == "both") echo " SELECTED"; ?> ><?php echo JText::_('HIDE_BOTH'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SUPPORT_SUBJECT_MESSAGE_HIDE'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("support_subject_format"); ?>:
					</td>
					<td>
						<input name='support_subject_format' type="text" value='<?php echo $this->settings['support_subject_format']; ?>' />
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_subject_format'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("support_subject_format_blank"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_subject_format_blank' value='1' <?php if ($this->settings['support_subject_format_blank'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_subject_format_blank'); ?></div>
					</td>
				</tr>								

				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("SUPPORT_SUBJECT_AT_TOP"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_subject_at_top' value='1' <?php if ($this->settings['support_subject_at_top'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_subject_at_top'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>
		