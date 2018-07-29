
		<fieldset class="adminform">
			<legend><?php echo JText::_("SUPPORT_GENERAL_SETTINGS"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">

				<tr>
					<td align="left" class="key">
						<?php echo JText::_("TICKET_LABEL_WIDTH"); ?>:
					</td>
					<td>
						<input type='text' name='ticket_label_width' value='<?php echo $this->settings['ticket_label_width']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_TICKET_LABEL_WIDTH'); ?></div>
					</td>
				</tr>

				<tr>
					<td align="left" class="key">
						<?php echo JText::_("SUBJECT_INPUT_SIZE"); ?>:
					</td>
					<td>
						<input type='text' name='support_subject_size' value='<?php echo $this->settings['support_subject_size']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_subject_size'); ?></div>
					</td>
				</tr>	
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("SETTING_SUPPORT_SCEDITOR"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_sceditor' value='1' <?php if ($this->settings['support_sceditor'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SUPPORT_SCEDITOR'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("SETTING_TICKET_LINK_TARGET"); ?>:
					</td>
					<td>
						<input type='checkbox' name='ticket_link_target' value='1' <?php if ($this->settings['ticket_link_target'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_TICKET_LINK_TARGET'); ?></div>
					</td>
				</tr>

			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_("USER_VISUAL"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">

				<tr>
					<td align="left" class="key">
						<?php echo JText::_("USER_REPLY_WIDTH"); ?>:
					</td>
					<td>
						<input type='text' name='support_user_reply_width' value='<?php echo $this->settings['support_user_reply_width']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_user_reply_width'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("USER_REPLY_HEIGHT"); ?>:
					</td>
					<td>
						<input type='text' name='support_user_reply_height' value='<?php echo $this->settings['support_user_reply_height']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_user_reply_height'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("ticket_per_page"); ?>:
					</td>
					<td>
						<?php $this->PerPage('ticket_per_page'); ?>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_ticket_per_page'); ?></div>
					</td>
				</tr>		

				<tr>
					<td align="left" class="key">				
							<?php echo JText::_("HIGHLIGHT_PASSWORD_INFO"); ?>:	
					</td>
					<td>
						<select name="support_unreg_password_highlight">
							<option value="0" <?php if ($this->settings['support_unreg_password_highlight'] == "0") echo " SELECTED"; ?> ><?php echo JText::_('JNO'); ?></option>
							<option value="1" <?php if ($this->settings['support_unreg_password_highlight'] == "1") echo " SELECTED"; ?> ><?php echo JText::_('ALERT_BOX'); ?></option>
							<option value="2" <?php if ($this->settings['support_unreg_password_highlight'] == "2") echo " SELECTED"; ?> ><?php echo JText::_('BY_FIELD'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('FOR_UNREGISTERED_TICKETS__HIGHLIGHT_THE_PASSWORD_INFORMATION_NEEDED_TO_ACCESS_THE_TICKET_'); ?></div>
					</td>
				</tr>			
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("support_sel_prod_dept"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_sel_prod_dept' value='1' <?php if ($this->settings['support_sel_prod_dept'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_sel_prod_dept'); ?></div>
					</td>
				</tr>					
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("NO_COLS_IN_TICKET_INFO_USER"); ?>:
					
					</td>
					<td>
						<input name='support_info_cols_user' type="text" value='<?php echo $this->settings['support_info_cols_user']; ?>' />
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_info_cols_user'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_("HANDLER_VISUAL"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">

				<tr>
					<td align="left" class="key">
						<?php echo JText::_("ADMIN_REPLY_WIDTH"); ?>:
					</td>
					<td>
						<input type='text' name='support_admin_reply_width' value='<?php echo $this->settings['support_admin_reply_width']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_admin_reply_width'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("ADMIN_REPLY_HEIGHT"); ?>:
					</td>
					<td>
						<input type='text' name='support_admin_reply_height' value='<?php echo $this->settings['support_admin_reply_height']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_admin_reply_height'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("SHOW_MESSGAE_COUNTS"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_show_msg_counts' value='1' <?php if ($this->settings['support_show_msg_counts'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_show_msg_counts'); ?></div>
					</td>
				</tr>

				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("MESSAGES_AT_TOP"); ?>:
					
					</td>
					<td>
						<select name="messages_at_top">
							<option value="0" <?php if ($this->settings['messages_at_top'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('JNO'); ?></option>
							<option value="1" <?php if ($this->settings['messages_at_top'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('MESSAGES_AT_TOP_USERS'); ?></option>
							<option value="2" <?php if ($this->settings['messages_at_top'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('MESSAGES_AT_TOP_ADMINS'); ?></option>
							<option value="3" <?php if ($this->settings['messages_at_top'] == 3) echo " SELECTED"; ?> ><?php echo JText::_('JYES'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_messages_at_top'); ?></div>
					</td>
				</tr>

				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("absolute_last_open"); ?>:
					</td>
					<td>
						<select name="absolute_last_open">
							<option value="0" <?php if ($this->settings['absolute_last_open'] < 1 ) echo " SELECTED"; ?> >"XX days ago" with tooltip</option>
							<option value="1" <?php if ($this->settings['absolute_last_open'] == 1) echo " SELECTED"; ?> >"2015-03-05" with tooltip</option>
							<option value="2" <?php if ($this->settings['absolute_last_open'] == 2) echo " SELECTED"; ?> >"XX days ago"</option>
							<option value="3" <?php if ($this->settings['absolute_last_open'] == 3) echo " SELECTED"; ?> >"2015-03-05"</option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_absolute_last_open'); ?></div>
					</td>
				</tr>			
				
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("support_insertpopup"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_insertpopup' value='1' <?php if ($this->settings['support_insertpopup'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_insertpopup'); ?></div>
					</td>
				</tr>
					<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("ENTIRE_ROW_TICKET"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_entire_row' value='1' <?php if ($this->settings['support_entire_row'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_entire_row'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SHOW_ALL_CLOSED_TAB"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='support_tabs_allclosed' value='1' <?php if ($this->settings['support_tabs_allclosed'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SHOW_ALL_CLOSED_TAB'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SHOW_ALL_OPEN_TAB"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_tabs_allopen' value='1' <?php if ($this->settings['support_tabs_allopen'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SHOW_ALL_OPEN_TAB'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SHOW_ALL_TICKETS_TAB"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_tabs_all' value='1' <?php if ($this->settings['support_tabs_all'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SHOW_ALL_TICKETS_TAB'); ?></div>
					</td>
				</tr>
	
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("NO_COLS_IN_TICKET_INFO"); ?>:
					
					</td>
					<td>
						<input name='support_info_cols' type="text" value='<?php echo $this->settings['support_info_cols']; ?>' />
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_info_cols'); ?></div>
					</td>
				</tr>	
			</table>
		</fieldset>
	
	
	
	