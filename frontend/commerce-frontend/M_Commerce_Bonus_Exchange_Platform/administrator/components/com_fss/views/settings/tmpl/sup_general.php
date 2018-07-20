	

		<fieldset class="adminform">
			<legend><?php echo JText::_("Features"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">

				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("HIDE_PRIORITY"); ?>:
					
					</td>
					<td style="width:250px;">
						<select name="support_hide_priority">
							<option value="0" <?php if ($this->settings['support_hide_priority'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('PRI_SHOWN'); ?></option>
							<option value="1" <?php if ($this->settings['support_hide_priority'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('PRI_HIDE'); ?></option>
							<option value="2" <?php if ($this->settings['support_hide_priority'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('PRI_ONLY_FOR_ADMINS'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_hide_priority'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("HIDE_HANDLER"); ?>:
					
					</td>
					<td>
						<select name="support_hide_handler">
							<option value="0" <?php if ($this->settings['support_hide_handler'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('HANDLER_SHOWN'); ?></option>
							<option value="1" <?php if ($this->settings['support_hide_handler'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('HANDLER_HIDE'); ?></option>
							<option value="2" <?php if ($this->settings['support_hide_handler'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('HANDLER_ONLY_FOR_ADMINS'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_hide_handler'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("HIDE_CATEGORY"); ?>:
					
					</td>
					<td>
						<select name="support_hide_category">
							<option value="0" <?php if ($this->settings['support_hide_category'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('HANDLER_SHOWN'); ?></option>
							<option value="1" <?php if ($this->settings['support_hide_category'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('HANDLER_HIDE'); ?></option>
							<option value="2" <?php if ($this->settings['support_hide_category'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('HANDLER_ONLY_FOR_ADMINS'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_hide_category'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("HIDE_USERS_OTHER_TICKET_SECTION"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_hide_users_tickets' value='1' <?php if ($this->settings['support_hide_users_tickets'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_hide_users_tickets'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("HIDE_TICKET_TAGS"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_hide_tags' value='1' <?php if ($this->settings['support_hide_tags'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_hide_tags'); ?></div>
					</td>
				</tr>


				</table>
		</fieldset>
	

		<fieldset class="adminform">
			<legend><?php echo JText::_("Links"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("CUSTOM_REGISTER_LINK"); ?>:
					
					</td>
					<td>
						<input type='text' name='support_custom_register' value='<?php echo $this->settings['support_custom_register']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_custom_register'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("support_custom_lost_username"); ?>:
					
					</td>
					<td>
						<input type='text' name='support_custom_lost_username' value='<?php echo $this->settings['support_custom_lost_username']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_custom_lost_username'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("support_custom_lost_password"); ?>:
					
					</td>
					<td>
						<input type='text' name='support_custom_lost_password' value='<?php echo $this->settings['support_custom_lost_password']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_custom_lost_password'); ?></div>
					</td>
				</tr>
				
			</table>
		</fieldset>
			
		<fieldset class="adminform">
			<legend><?php echo JText::_("Attachment_Settings"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("USER_CAN_ATTACH_FILES"); ?>:
					</td>
					<td>
						<select name="support_user_attach">
							<option value="0" <?php if ($this->settings['support_user_attach'] == "0") echo " SELECTED"; ?> ><?php echo JText::_('JNO'); ?></option>
							<option value="1" <?php if ($this->settings['support_user_attach'] == "1") echo " SELECTED"; ?> ><?php echo JText::_('REGISTERED_USERS_ONLY'); ?></option>
							<option value="2" <?php if ($this->settings['support_user_attach'] == "2") echo " SELECTED"; ?> ><?php echo JText::_('ALL_USERS'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_user_attach'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("support_attach_max_size"); ?>:
					</td>
					<td>
						<input type='text' name='support_attach_max_size' value='<?php echo $this->settings['support_attach_max_size']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_attach_max_size'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("support_attach_max_size_admins"); ?>:
					</td>
					<td>
						<input type='text' name='support_attach_max_size_admins' value='<?php echo $this->settings['support_attach_max_size_admins']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_attach_max_size_admins'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("current_upload_limits"); ?>:
					</td>
					<td colspan="2">
						<div>php.ini <code>upload_max_filesize</code> = <?php echo FSS_Helper::display_filesize(FSS_Helper::convertPHPSizeToBytes(ini_get('upload_max_filesize'))); ?></div>
						<div>php.ini <code>post_max_size</code> = <?php echo FSS_Helper::display_filesize(FSS_Helper::convertPHPSizeToBytes(ini_get('post_max_size'))); ?></div>
						<div><?php echo JText::_("support_attach_max_size"); ?> = <?php echo FSS_Helper::display_filesize(FSS_Helper::getMaximumFileUploadSize()); ?></div>
						<div><?php echo JText::_("support_attach_max_size_admins"); ?> = <?php echo FSS_Helper::display_filesize(FSS_Helper::getMaximumFileUploadSize(true)); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("support_attach_types"); ?>:
					</td>
					<td>
						<input type='text' name='support_attach_types' value='<?php echo $this->settings['support_attach_types']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_attach_types'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("support_attach_types_admins"); ?>:
					</td>
					<td>
						<input type='text' name='support_attach_types_admins' value='<?php echo $this->settings['support_attach_types_admins']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_attach_types_admins'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">				
							<?php echo JText::_("ATTACHMENT_FILENAME"); ?>:
					</td>
					<td>
						<select name="support_filename">
							<option value="0" <?php if ((int)$this->settings['support_filename'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('AF_FILENAME'); ?></option>
							<option value="1" <?php if ($this->settings['support_filename'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('AF_USER_FILENAME'); ?></option>
							<option value="2" <?php if ($this->settings['support_filename'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('AF_USER_DATE_FILENAME'); ?></option>
							<option value="3" <?php if ($this->settings['support_filename'] == 3) echo " SELECTED"; ?> ><?php echo JText::_('AF_DATE_USER_FILENAME'); ?></option>
							<option value="4" <?php if ($this->settings['support_filename'] == 4) echo " SELECTED"; ?> ><?php echo JText::_('AF_DATE_FILENAME'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SUPPORT_ATTACHMENT_FILENAME'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">				
							<?php echo JText::_("attach_storage_filename"); ?>:
					</td>
					<td>
						<select name="attach_storage_filename">
							<option value="0" <?php if ((int)$this->settings['attach_storage_filename'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('ASFN_FILENAME_UID_EXTENSION'); ?></option>
							<option value="1" <?php if ($this->settings['attach_storage_filename'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('ASFN_TICKETID_FILENAME_UID_EXTENSION'); ?></option>
							<option value="2" <?php if ($this->settings['attach_storage_filename'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('ASFN_YEAR_MONTH_FILENAME_UID_EXTENSION'); ?></option>
							<option value="3" <?php if ($this->settings['attach_storage_filename'] == 3) echo " SELECTED"; ?> ><?php echo JText::_('ASFN_YEAR_MONTH_DATE_FILENAME_UID_EXTENSION'); ?></option>
							<option value="4" <?php if ($this->settings['attach_storage_filename'] == 4) echo " SELECTED"; ?> ><?php echo JText::_('ASFN_USERNAME_FILENAME_UID_EXTENSION'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_attach_storage_filename'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("support_attach_use_old_system"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='support_attach_use_old_system' value='1' <?php if ($this->settings['support_attach_use_old_system'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_attach_use_old_system'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>
		
		
		<fieldset class="adminform">
			<legend><?php echo JText::_("SUPPORT_AUTOCLOSE_SETTINGS"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("AUTOMATICALLY_CLOSE"); ?>:
					
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='support_autoclose' value='1' <?php if ($this->settings['support_autoclose'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td rowspan="3">
						<div class='fss_help'>
						<?php echo JText::sprintf('CRON_AUTOCLOSE_MSG', JText::_('AUTOCLOSE_MIDDLE'), JURI::root() . 'index.php?option=com_fss&view=cron', JURI::root() . 'index.php?option=com_fss&view=cron'); ?><br />
						<a href="<?php echo FSSRoute::_('index.php?option=com_fss&view=cronlog'); ?>">
							<img style="float:none;margin:0px;" src='<?php echo JURI::base(); ?>/components/com_fss/assets/log.png'>
							<span style="position:relative;top:-2px;"><?php echo JText::_('VIEW_LOG'); ?></span>
						</a>
						</div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("AUTOCLOSE_DURATION"); ?>:
					</td>
					<td>
						<input type='text' name='support_autoclose_duration' value='<?php echo $this->settings['support_autoclose_duration']; ?>'>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("AUTOCLOSE_AUDITLOG"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_autoclose_audit' value='1' <?php if ($this->settings['support_autoclose_audit'] == 1) { echo " checked='yes' "; } ?>>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("AUTOCLOSE_EMAIL_USER"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_autoclose_email' value='1' <?php if ($this->settings['support_autoclose_email'] == 1) { echo " checked='yes' "; } ?>>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("KEEP_LOG_FOR"); ?>:
					</td>
					<td>
						<input type='text' name='support_cronlog_keep' value='<?php echo $this->settings['support_cronlog_keep']; ?>'>
					</td>
				</tr>				<tr>
					<td align="left" class="key">
							<?php echo JText::_("support_emaillog_keep"); ?>:
					</td>
					<td>
						<input type='text' name='support_emaillog_keep' value='<?php echo $this->settings['support_emaillog_keep']; ?>'>
					</td>
				</tr>

			</table>
		</fieldset>
	
		<fieldset class="adminform">
			<legend><?php echo JText::_("SUPPORT_MISC_SETTINGS"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("allow_raw_html_messages"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='allow_raw_html_messages' value='1' <?php if ($this->settings['allow_raw_html_messages'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'>
							<div style="color:red;font-weight:bold;">Enabling this can expose your site to many security issues, and Freestyle Joomla highly recommend
							NOT enabling this option. If you do enable this, Freestlye Joomla are not responsible for any problems this may cause.</div>
							You will also need to enable the "Import EMails as HTML" option within your ticket email account config.
						</div>
					</td>
				</tr>
			</table>
		</fieldset>
	
		<fieldset class="adminform">
			<legend><?php echo JText::_("TICKET_RATINGS"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("ratings_per_message"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='ratings_per_message' value='1' <?php if ($this->settings['ratings_per_message'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_ratings_per_message'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("ratings_per_message_change"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='ratings_per_message_change' value='1' <?php if ($this->settings['ratings_per_message_change'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_ratings_per_message_change'); ?></div>
					</td>
				</tr>			
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("ratings_per_message_admin_overview"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='ratings_per_message_admin_overview' value='1' <?php if ($this->settings['ratings_per_message_admin_overview'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_ratings_per_message_admin_overview'); ?></div>
					</td>
				</tr>			
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("ratings_ticket"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='ratings_ticket' value='1' <?php if ($this->settings['ratings_ticket'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_ratings_ticket'); ?></div>
					</td>
				</tr>					
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("ratings_ticket_change"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='ratings_ticket_change' value='1' <?php if ($this->settings['ratings_ticket_change'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_ratings_ticket_change'); ?></div>
					</td>
				</tr>			
			</table>
		</fieldset>
				 	 		   		 