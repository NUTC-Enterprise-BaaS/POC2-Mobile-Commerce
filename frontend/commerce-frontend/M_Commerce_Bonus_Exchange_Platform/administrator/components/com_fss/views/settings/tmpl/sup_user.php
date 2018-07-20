	
	
		<fieldset class="adminform">
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("USER_CAN_CHANGE_CLOSE_OPEN_TICKETS"); ?>:
					
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='support_user_can_close' value='1' <?php if ($this->settings['support_user_can_close'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_user_can_close'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("USER_CAN_REOPEN_CLOSED_TICKETS"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_user_can_reopen' value='1' <?php if ($this->settings['support_user_can_reopen'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_user_can_reopen'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("support_user_can_change_status"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_user_can_change_status' value='1' <?php if ($this->settings['support_user_can_change_status'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_user_can_change_status'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("support_user_show_close_reply"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_user_show_close_reply' value='1' <?php if ($this->settings['support_user_show_close_reply'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_user_show_close_reply'); ?></div>
					</td>
				</tr>

					<tr>
					<td align="left" class="key">
						<?php echo JText::_("support_restrict_prod_view"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_restrict_prod_view' value='1' <?php if ($this->settings['support_restrict_prod_view'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_restrict_prod_view'); ?></div>
					</td>
				</tr>
		
			</table>
		</fieldset>
	
	
		<fieldset class="adminform">
			<legend><?php echo JText::_("SUPPORT_SIMPLE_USERLIST"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("SUPPORT_SIMPLE_USERLIST_TABS"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_simple_userlist_tabs' value='1' <?php if ($this->settings['support_simple_userlist_tabs'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SUPPORT_SIMPLE_USERLIST_TABS'); ?></div>
					</td>
				</tr>			
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("SUPPORT_SIMPLE_USERLIST_SEARCH"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_simple_userlist_search' value='1' <?php if ($this->settings['support_simple_userlist_search'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SUPPORT_SIMPLE_USERLIST_SEARCH'); ?></div>
					</td>
				</tr>			
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("HIDE_ON_TICKET_DETAILS"); ?>:
					</td>
					<td class="sub_selects">
						<input type='checkbox' name='user_hide_all_details' value='1' <?php if ($this->settings['user_hide_all_details'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test1"><?php echo JText::_('ALL_TICKET_DETAILS'); ?></label>
					
						<input type='checkbox' name='user_hide_title' value='1' <?php if ($this->settings['user_hide_title'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('TITLE'); ?></label>
					
						<input type='checkbox' name='user_hide_id' value='1' <?php if ($this->settings['user_hide_id'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('TICKET_REFERENCE'); ?></label>
					
						<input type='checkbox' name='user_hide_user' value='1' <?php if ($this->settings['user_hide_user'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('USER'); ?></label>
					
						<input type='checkbox' name='user_hide_cc' value='1' <?php if ($this->settings['user_hide_cc'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('INCLUDED_USERS'); ?></label>
					
						<input type='checkbox' name='user_hide_product' value='1' <?php if ($this->settings['user_hide_product'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('PRODUCT'); ?></label>
					
						<input type='checkbox' name='user_hide_department' value='1' <?php if ($this->settings['user_hide_department'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('DEPARTMENT'); ?></label>
					
						<input type='checkbox' name='user_hide_category' value='1' <?php if ($this->settings['user_hide_category'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('CATEGORY'); ?></label>
					
						<input type='checkbox' name='user_hide_updated' value='1' <?php if ($this->settings['user_hide_updated'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('LAST_UPDATED'); ?></label>
					
						<input type='checkbox' name='user_hide_handler' value='1' <?php if ($this->settings['user_hide_handler'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('HANDLER'); ?></label>
					
						<input type='checkbox' name='user_hide_status' value='1' <?php if ($this->settings['user_hide_status'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('STATUS'); ?></label>
					
						<input type='checkbox' name='user_hide_priority' value='1' <?php if ($this->settings['user_hide_priority'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('PRIORITY'); ?></label>
					
						<input type='checkbox' name='user_hide_custom' value='1' <?php if ($this->settings['user_hide_custom'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('CUSTOM_FIELDS'); ?></label>
					
						<input type='checkbox' name='user_hide_print' value='1' <?php if ($this->settings['user_hide_print'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('PRINT_BUTTON'); ?></label>
					
						<input type='checkbox' name='user_hide_key' value='1' <?php if ($this->settings['user_hide_key'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('MESSAGE_KEY'); ?></label>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_HIDE_ON_TICKET_DETAILS'); ?></div>
					</td>
				</tr>	
				
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("support_user_show_reply_always"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_user_show_reply_always' value='1' <?php if ($this->settings['support_user_show_reply_always'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_user_show_reply_always'); ?></div>
					</td>
				</tr>	
				
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("support_user_reply_under"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_user_reply_under' value='1' <?php if ($this->settings['support_user_reply_under'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_user_reply_under'); ?></div>
					</td>
				</tr>	
				
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("support_user_reverse_messages"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_user_reverse_messages' value='1' <?php if ($this->settings['support_user_reverse_messages'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_user_reverse_messages'); ?></div>
					</td>
				</tr>	
										
			</table>
		</fieldset>
	