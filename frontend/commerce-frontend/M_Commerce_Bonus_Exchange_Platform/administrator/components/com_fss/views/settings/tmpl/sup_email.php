
		<fieldset class="adminform">
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("EMAIL_ADDRESS_TO_EMAIL_FOR_UNASSIGNED_TICKETS_LEAVE_BLANK_FOR_NO_EMAIL"); ?>:
					
					</td>
					<td style="width:250px;">
						<input name='support_email_unassigned' type="text" size="40" value='<?php echo $this->settings['support_email_unassigned']; ?>' >
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_unassigned'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("CC_ALL_TICKET_HANLDER_EMAILS_ADDRESS"); ?>:
					
					</td>
					<td style="width:250px;">
						<input name='support_email_admincc' type="text" size="40" value='<?php echo $this->settings['support_email_admincc']; ?>' >
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_admincc'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("EMAIL_FROM_ADDRESS_LEAVE_BLANK_TO_USE_DEFAULT_JOOMLA_ONE"); ?>:
					
					</td>
					<td>
						<input name='support_email_from_address' type="text" size="40" value='<?php echo $this->settings['support_email_from_address']; ?>' >
						</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_from_address'); ?></div>
				</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("EMAIL_FROM_NAME_LEAVE_BLANK_TO_USE_DEFAULT_JOOMLA_ONE"); ?>:
					
					</td>
					<td>
						<input name='support_email_from_name' type="text" size="40" value='<?php echo $this->settings['support_email_from_name']; ?>' >
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_from_name'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("OVERRIDE_SITE_NAME_IN_EMAIL_LEAVE_BLANK_TO_USE_DEFAULT_JOOMLA_ONE"); ?>:
					
					</td>
					<td>
						<input name='support_email_site_name' type="text" size="40" value='<?php echo $this->settings['support_email_site_name']; ?>' >
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_site_name'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("EMAIL_USER_ON_TICKET_CREATE"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_on_create' value='1' <?php if ($this->settings['support_email_on_create'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_on_create'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("EMAIL_HANDLER_ON_CREATE_IF_ONE_IS_ASSIGNED"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_handler_on_create' value='1' <?php if ($this->settings['support_email_handler_on_create'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_handler_on_create'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("EMAIL_HANDLER_ON_PENDING_IF_ONE_IS_ASSIGNED"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_email_handler_on_pending' value='1' <?php if ($this->settings['support_email_handler_on_pending'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_handler_on_create'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("EMAIL_USER_ON_HANDLER_REPLY"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_on_reply' value='1' <?php if ($this->settings['support_email_on_reply'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_on_reply'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("EMAIL_HANDER_ON_USER_REPLY"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_handler_on_reply' value='1' <?php if ($this->settings['support_email_handler_on_reply'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_handler_on_reply'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("EMAIL_NEW_HANDLER_ON_FORWARD"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_handler_on_forward' value='1' <?php if ($this->settings['support_email_handler_on_forward'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_handler_on_forward'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("support_email_handler_on_private"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_handler_on_private' value='1' <?php if ($this->settings['support_email_handler_on_private'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_handler_on_private'); ?></div>
					</td>
				</tr>				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("EMAIL_USER_ON_CLOSE"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_on_close' value='1' <?php if ($this->settings['support_email_on_close'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_on_close'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="padding-left: 8px;">					
							<img src='<?php echo JURI::root(); ?>administrator/components/com_fss/assets/images/arrow_indent.gif'>
							<?php echo JText::_("support_email_on_close_no_dropdown"); ?>:					
					</td>
					<td>
						<input type='checkbox' name='support_email_on_close_no_dropdown' value='1' <?php if ($this->settings['support_email_on_close_no_dropdown'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_on_close_no_dropdown'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("support_email_all_admins"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_all_admins' value='1' <?php if ($this->settings['support_email_all_admins'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_all_admins'); ?></div>
					</td>
				</tr>			
				<tr>
					<td align="left" class="key" style="padding-left: 8px;">
					
							<img src='<?php echo JURI::root(); ?>administrator/components/com_fss/assets/images/arrow_indent.gif'>
							<?php echo JText::_("support_email_all_admins_only_unassigned"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_all_admins_only_unassigned' value='1' <?php if ($this->settings['support_email_all_admins_only_unassigned'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_all_admins_only_unassigned'); ?></div>
					</td>
				</tr>			<tr>
					<td align="left" class="key" style="padding-left: 8px;">
					
							<img src='<?php echo JURI::root(); ?>administrator/components/com_fss/assets/images/arrow_indent.gif'>
							<?php echo JText::_("support_email_all_admins_ignore_auto"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_all_admins_ignore_auto' value='1' <?php if ($this->settings['support_email_all_admins_ignore_auto'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_all_admins_ignore_auto'); ?></div>
					</td>
				</tr>			<tr>
					<td align="left" class="key" style="padding-left: 8px;">
					
							<img src='<?php echo JURI::root(); ?>administrator/components/com_fss/assets/images/arrow_indent.gif'>
							<?php echo JText::_("support_email_all_admins_can_view"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_all_admins_can_view' value='1' <?php if ($this->settings['support_email_all_admins_can_view'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_all_admins_can_view'); ?></div>
					</td>
				</tr>	
							<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("support_email_file_user"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_file_user' value='1' <?php if ($this->settings['support_email_file_user'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_file_user'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("support_email_file_handler"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_email_file_handler' value='1' <?php if ($this->settings['support_email_file_handler'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_file_handler'); ?></div>
					</td>
				</tr>	
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("support_email_bcc_handler"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_email_bcc_handler' value='1' <?php if ($this->settings['support_email_bcc_handler'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_bcc_handler'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("support_email_send_empty_handler"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_email_send_empty_handler' value='1' <?php if ($this->settings['support_email_send_empty_handler'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_send_empty_handler'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("support_email_include_autologin"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_email_include_autologin' value='1' <?php if ($this->settings['support_email_include_autologin'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_include_autologin'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("support_email_include_autologin_handler"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_email_include_autologin_handler' value='1' <?php if ($this->settings['support_email_include_autologin_handler'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_include_autologin_handler'); ?></div>
					</td>
				</tr>				<tr>
					<td align="left" class="key" style='font-weight: bold;'>
						<?php echo JText::_("support_email_link"); ?>:
					</td>
					<td>
						<?php echo JText::_("support_email_link_below"); ?></td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_link'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<img src='<?php echo JURI::root(); ?>administrator/components/com_fss/assets/images/arrow_indent.gif'>
							<?php echo JText::_("support_email_link_unreg"); ?>:
					</td>
					<td>
						<input name='support_email_link_unreg' type="text" size="40" value='<?php echo $this->settings['support_email_link_unreg']; ?>' >
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_link_unreg'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<img src='<?php echo JURI::root(); ?>administrator/components/com_fss/assets/images/arrow_indent.gif'>
							<?php echo JText::_("support_email_link_reg"); ?>:
					</td>
					<td>
						<input name='support_email_link_reg' type="text" size="40" value='<?php echo $this->settings['support_email_link_reg']; ?>' >
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_link_reg'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<img src='<?php echo JURI::root(); ?>administrator/components/com_fss/assets/images/arrow_indent.gif'>
							<?php echo JText::_("support_email_link_admin"); ?>:
					</td>
					<td>
						<input name='support_email_link_admin' type="text" size="40" value='<?php echo $this->settings['support_email_link_admin']; ?>' >
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_link_admin'); ?></div>
					</td>
				</tr>			
				<tr>
					<td align="left" class="key">	
							<img src='<?php echo JURI::root(); ?>administrator/components/com_fss/assets/images/arrow_indent.gif'>
							<?php echo JText::_("support_email_link_pending"); ?>:
					</td>
					<td>
						<input name='support_email_link_pending' type="text" size="40" value='<?php echo $this->settings['support_email_link_pending']; ?>' >
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_link_pending'); ?></div>
					</td>
				</tr>	
				<tr>
					<td align="left" class="key">		
						<?php echo JText::_("support_email_no_domain"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_email_no_domain' value='1' <?php if ($this->settings['support_email_no_domain'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SUPPORT_EMAIL_NO_DOMAIN'); ?></div>
					</td>
				</tr>	
			</table>
		</fieldset>
