
		<fieldset class="adminform">
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("ALLOW_DELETING_OF_TICKETS_BY_HANDLERS"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_delete' value='1' <?php if ($this->settings['support_delete'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_delete'); ?></div>
					</td>
				</tr>

				<tr>
					<td align="left" class="key">
						<?php echo JText::_("support_admin_refresh"); ?>:
					</td>
					<td>
						<input type='text' name='support_admin_refresh' value='<?php echo $this->settings['support_admin_refresh']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SUPPORT_ADMIN_REFRESH'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("support_hide_super_users"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_hide_super_users' value='1' <?php if ($this->settings['support_hide_super_users'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_hide_super_users'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("support_no_admin_for_user_open"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_no_admin_for_user_open' value='1' <?php if ($this->settings['support_no_admin_for_user_open'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_no_admin_for_user_open'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("support_profile_itemid"); ?>:
					</td>
					<td>
						<input type='text' name='support_profile_itemid' value='<?php echo $this->settings['support_profile_itemid']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_profile_itemid'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("allow_edit_no_audit"); ?>:
					</td>
					<td>
						<input type='checkbox' name='allow_edit_no_audit' value='1' <?php if ($this->settings['allow_edit_no_audit'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_allow_edit_no_audit'); ?></div>
					</td>
				</tr>
			
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("TICKET_LOCK_TIMEOUT"); ?>:
					
					</td>
					<td>
						<input name='support_lock_time' type="text" value='<?php echo $this->settings['support_lock_time']; ?>' />
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_lock_time'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("support_update_satatus_on_draft"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_update_satatus_on_draft' value='1' <?php if ($this->settings['support_update_satatus_on_draft'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_update_satatus_on_draft'); ?></div>
					</td>
				</tr>				
			</table>
		</fieldset>


		<fieldset class="adminform">
			<legend><?php echo JText::_("SUPPORT_TICKET_OWNERSHIP_SETTINGS"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("AUTO_ASSIGN_TICKETS_TO_HANDLER"); ?>:
					
					</td>
					<td style="width:250px;">
						<select name="support_autoassign">
							<option value="0" <?php if ($this->settings['support_autoassign'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('DONT_ASSIGN_TICKETS'); ?></option>
							<option value="1" <?php if ($this->settings['support_autoassign'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('AUTO_ASSIGN_ON_CREATE'); ?></option>
							<option value="2" <?php if ($this->settings['support_autoassign'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('ASSIGN_TICKET_ON_HANDLER_OPEN'); ?></option>
							<option value="3" <?php if ($this->settings['support_autoassign'] == 3) echo " SELECTED"; ?> ><?php echo JText::_('ASSIGN_TICKET_ON_HANDLER_REPLY'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_autoassign'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("support_handler_fallback"); ?>:
					</td>
					<td>
						<input type='text' name='support_handler_fallback' value='<?php echo $this->settings['support_handler_fallback']; ?>'>
					</td>
						<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_handler_fallback'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("TAKE_OWNERSHIP_ON_HANDLER_REPLY"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_assign_reply' value='1' <?php if ($this->settings['support_assign_reply'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_assign_reply'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">				
							<?php echo JText::_("ALLOW_HANDLER_TO_BE_CHOSEN"); ?>:	
					</td>
					<td>
						<select name="support_choose_handler">
							<option value="none" <?php if ($this->settings['support_choose_handler'] == "none") echo " SELECTED"; ?> ><?php echo JText::_('DISABLED'); ?></option>
							<option value="admin" <?php if ($this->settings['support_choose_handler'] == "admin") echo " SELECTED"; ?> ><?php echo JText::_('CREATE_FOR_USER'); ?></option>
							<option value="user" <?php if ($this->settings['support_choose_handler'] == "user") echo " SELECTED"; ?> ><?php echo JText::_('ALL_USERS'); ?></option>
							<option value="handlers" <?php if ($this->settings['support_choose_handler'] == "handlers") echo " SELECTED"; ?> ><?php echo JText::_('ADMINS_ONLY'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SUPPOER_CHOOSE_HANDLER'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("ASSIGN_TO_HANDLER_WHEN_OPENEING_FOR_USER"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_assign_for_user' value='1' <?php if ($this->settings['support_assign_for_user'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('WHEN_A_HANDLER_OPENS_A_TICKET_FOR_A_USER__SHOULD_THEY_BE_ASSIGNED_AS_THE_HANDLER'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("forward_product_handler"); ?>:
					</td>
					<td>
						<select name="forward_product_handler">
							<option value="unchanged" <?php if ($this->settings['forward_product_handler'] == "unchanged") echo " SELECTED"; ?> ><?php echo JText::_('Unchanged'); ?></option>
							<option value="auto" <?php if ($this->settings['forward_product_handler'] == "auto") echo " SELECTED"; ?> ><?php echo JText::_('AUTO_ASSIGN'); ?></option>
							<option value="unassigned" <?php if ($this->settings['forward_product_handler'] == "unassigned") echo " SELECTED"; ?> ><?php echo JText::_('UNASSIGNED'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_forward_product_handler'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("forward_handler_handler"); ?>:
					</td>
					<td>
						<select name="forward_handler_handler">
							<option value="unchanged" <?php if ($this->settings['forward_handler_handler'] == "unchanged") echo " SELECTED"; ?> ><?php echo JText::_('Unchanged'); ?></option>
							<option value="auto" <?php if ($this->settings['forward_handler_handler'] == "auto") echo " SELECTED"; ?> ><?php echo JText::_('AUTO_ASSIGN'); ?></option>
							<option value="unassigned" <?php if ($this->settings['forward_handler_handler'] == "unassigned") echo " SELECTED"; ?> ><?php echo JText::_('UNASSIGNED'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_forward_handler_handler'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("suport_dont_cc_handler"); ?>:
					</td>
					<td>
						<input type='checkbox' name='suport_dont_cc_handler' value='1' <?php if ($this->settings['suport_dont_cc_handler'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('sethelp_suport_dont_cc_handler'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>
		

		<fieldset class="adminform">
			<legend><?php echo JText::_("SUPPORT_SEARCH_SETTINGS"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SHOW_ADVANCED_SEARCH_BY_DEFAULT"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_advanced_default' value='1' <?php if ($this->settings['support_advanced_default'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_advanced_default'); ?></div>
					</td>
				</tr>
				
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SS_BASIC_NAME"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='support_basic_name' value='1' <?php if ($this->settings['support_basic_name'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_basic_name'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SS_BASIC_USERNAME"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_basic_username' value='1' <?php if ($this->settings['support_basic_username'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_basic_username'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SS_BASIC_EMAIL"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_basic_email' value='1' <?php if ($this->settings['support_basic_email'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_basic_email'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SS_BASIC_MESSAGES"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_basic_messages' value='1' <?php if ($this->settings['support_basic_messages'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_basic_messages'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset class="adminform">
			<legend><?php echo JText::_("TIME_TRACKING"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
	
				<tr>
					<td align="left" class="key">				
							<?php echo JText::_("TIME_TRACKING"); ?>:
					</td>
					<td>
						<select name="time_tracking">
							<option value="" <?php if ($this->settings['time_tracking'] == "") echo " SELECTED"; ?> ><?php echo JText::_('JNO'); ?></option>
							<option value="manual" <?php if ($this->settings['time_tracking'] == "manual") echo " SELECTED"; ?> ><?php echo JText::_('TIME_TRACKING_MANUAL'); ?></option>
							<option value="auto" <?php if ($this->settings['time_tracking'] == "auto") echo " SELECTED"; ?> ><?php echo JText::_('TIME_TRACKING_AUTOMATIC'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_time_tracking'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("time_tracking_require_note"); ?>:
					</td>
					<td>
						<input type='checkbox' name='time_tracking_require_note' value='1' <?php if ($this->settings['time_tracking_require_note'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_time_tracking_require_note'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("time_tracking_type"); ?>:
					</td>
					<td>
						<select name="time_tracking_type">
							<option value="" <?php if ($this->settings['time_tracking_type'] == "") echo " SELECTED"; ?> ><?php echo JText::_('TIME_TRACKING_TYPE_HM'); ?></option>
							<option value="se" <?php if ($this->settings['time_tracking_type'] == "se") echo " SELECTED"; ?> ><?php echo JText::_('TIME_TRACKING_TYPE_SE'); ?></option>
							<option value="tm" <?php if ($this->settings['time_tracking_type'] == "tm") echo " SELECTED"; ?> ><?php echo JText::_('TIME_TRACKING_TYPE_TM'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_time_tracking_type'); ?></div>
					</td>
				</tr>
				
			</table>
		</fieldset>
