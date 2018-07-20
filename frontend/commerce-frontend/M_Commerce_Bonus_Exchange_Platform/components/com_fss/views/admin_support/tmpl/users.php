<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
	<?php echo FSS_Helper::PageStylePopup(true); ?>
	<?php if ($this->mode == "user"): ?>
		<?php echo FSS_Helper::PageTitlePopup("INCLUDE_USER"); ?>
	<?php elseif ($this->mode == "admin"): ?>
		<?php echo FSS_Helper::PageTitlePopup("INCLUDE_HANDLER"); ?>
	<?php else: ?>
		<?php echo FSS_Helper::PageTitlePopup("CHANGE_USER"); ?>
	<?php endif; ?>

	<form action="<?php echo FSSRoute::_( 'index.php?option=com_fss&what=pickuser&view=admin_support&layout=users&tmpl=component', false );?>" method="post" name="fssForm" id="fssForm">
	
		<div class="input-append">
			<input type="text" class="input-medium" placeholder="<?php echo JText::_("SEARCH"); ?>" name="search" id="filter" value="<?php echo FSS_Helper::escape($this->search);?>" onchange="document.fssForm.submit();"/>
			<button class="btn btn-primary" onclick="this.form.submit();"><?php echo JText::_("GO"); ?></button>
			<button class="btn btn-default" onclick="document.getElementById('filter').value='';this.form.submit();"><?php echo JText::_("RESET"); ?></button>
		</div>
		
		<?php if ($this->mode != "admin"): ?>
			
			<!-- Group dropdown here -->
			<?php echo $this->jgroup_select; ?>
		
			<!-- Ticket group dropdown here -->
			<?php echo $this->ticketgroup_select; ?>
			
		<?php endif; ?>

		<table class="table table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<?php if ($this->mode == "user" || $this->mode == "admin"): ?>
				<th width="5"></th>
				<?php endif; ?>
				<th width="5">#</th>

				<th nowrap="nowrap" style="text-align:left;">
					<?php echo JText::_('User_ID'); ?>
				</th>

				<th nowrap="nowrap" style="text-align:left;">
					<?php echo JText::_('User_Name'); ?>
				</th>

				<th nowrap="nowrap" style="text-align:left;">
					<?php echo JText::_('EMail'); ?>
				</th>

				<?php foreach ($this->plugins as $plugin): ?>
					<th nowrap="nowrap" style="text-align:left;" class="<?php echo $plugin->getHeaderClass(); ?>" <?php echo $plugin->getHeaderAttrs(); ?>>
						<?php echo $plugin->getHeader(); ?>
					</th>
				<?php endforeach; ?>

				<th nowrap="nowrap" style="text-align:left;">
					<?php echo JText::_("PICK"); ?>
				</th>
			</tr>
		</thead>
		<?php
		foreach ($this->plugins as $plugin)
		{
			$plugin->loadData($this->users);
		}

		$k = 0;
		foreach ($this->users as $user)
		{
			//$link = FSSRoute::_( 'index.php?option=com_fss&controller=faq&task=edit&cid[]='. $row->id );

    		$found = false;
    		if ($this->mode == "user" || $this->mode == "admin")
    		{
				if ($this->mode == "user")
    			{
    				foreach ($this->ticket->user_cc as $cc)
    				{
    					if ($cc->id == $user->id)
    					{
    						$found = true;
    						break;
    					}
    				}	
    			} else if ($this->mode == "admin")
    			{
    				foreach ($this->ticket->admin_cc as $cc)
    				{
    					if ($cc->id == $user->id)
    					{
    						$found = true;
    						break;
    					}
    				}	
   			
    			}
    		}

			?>
			<tr class="<?php echo "row$k"; ?>">
 				<?php if ($this->mode == "user" || $this->mode == "admin"): ?>
					<th width="5">
						<?php if (!$found): ?>
							<input type="checkbox" class="user_check" value="<?php echo $user->id; ?>" />
						<?php endif; ?>
					</th>
				<?php endif; ?>
				<td>
					<?php echo $user->id; ?>
				</td>
				<td>
					<?php echo $user->username; ?>
				</td>
				<td>
					<?php echo $user->name; ?>
				</td>
				<td>
					<?php echo $user->email; ?>
				</td>

				<?php foreach ($this->plugins as $plugin): ?>
					<td class="<?php echo $plugin->getHeaderClass(); ?>" <?php echo $plugin->getHeaderAttrs(); ?>>
						<?php echo $plugin->displayUser($user); ?>
					</td>
				<?php endforeach; ?>

				<td>
					<?php if ($found) : ?>
						<?php echo JText::_('ALREADY_INCLUDED'); ?>
					<?php elseif ($this->mode == "user"): ?>
						<a href="#" class='btn btn-mini btn-success pick_user'
							onclick="window.parent.addCC('<?php echo $user->id; ?>', 0, 0);return false;">
							<?php echo JText::_("ADD_USER"); ?>
						</a>
						<a href="#" class='btn btn-mini btn-warning pick_user_ro'
							onclick="window.parent.addCC('<?php echo $user->id; ?>', 0, 1);return false;">
							<?php echo JText::_("ADD_READ_ONLY"); ?>
						</a>
					<?php elseif ($this->mode == "admin"): ?>
						<a href="#" class='btn btn-mini btn-success pick_user'
							onclick="window.parent.addCC('<?php echo $user->id; ?>', 1, 0);return false;">
							<?php echo JText::_("ADD_ADMIN"); ?>
						</a>
						<!--<a href="#" class='btn btn-mini btn-warning pick_user_ro'
							onclick="window.parent.addCC('<?php echo $user->id; ?>', 1, 1);return false;">
							<?php echo JText::_("ADD_READ_ONLY"); ?>
						</a>-->
					<?php else: ?>
						<a class="btn btn-default btn-mini" href="#" onclick="window.parent.PickUser('<?php echo $user->id; ?>','<?php echo FSS_Helper::escapeJavaScriptText($user->username); ?>','<?php echo FSS_Helper::escapeJavaScriptText($user->name); ?>');return false;"><?php echo JText::_("PICK"); ?></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>

		</table>
	
		<?php echo $this->pagination->getListFooter(); ?>

		<input type="hidden" name="filter_order" value="<?php echo FSS_Helper::escape($this->lists['order']); ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo FSS_Helper::escape($this->lists['order_Dir']); ?>" />
		<input type="hidden" name="mode" value="<?php echo FSS_Helper::escape(FSS_Input::getCmd('mode')); ?>" />
		<input type="hidden" name="ticketid" value="<?php echo (int)FSS_Input::getInt('ticketid'); ?>" />
	</form>


</div>

<div class="modal-footer">
	<?php if ($this->mode == "user"): ?>
	<input type="text" placeholder='EMail' value="" name="email" id='add_email' style='margin: 0'>
		<a href="#" class='btn btn-info' id="user_<?php echo $user->id; ?>" onclick='ccUsersEMail(0, 0);return false;'><?php echo JText::_("ADD_EMAIL"); ?></a>

		<a href="#" class='btn btn-success' id="user_<?php echo $user->id; ?>" onclick='ccUsers(0, 0);return false;'><?php echo JText::_("ADD_USERS"); ?></a>
		<a href="#" class='btn btn-warning' id="user_<?php echo $user->id; ?>" onclick='ccUsers(0, 1);return false;'><?php echo JText::_("ADD_READ_ONLY"); ?></a>
	<?php elseif ($this->mode == "admin"): ?>
		<a href="#" class='btn btn-success' id="user_<?php echo $user->id; ?>" onclick='ccUsers(1, 0);return false;'><?php echo JText::_("ADD_ADMINS"); ?></a>
		<!--<a href="#" class='btn btn-warning' id="user_<?php echo $user->id; ?>" onclick='ccUsers(1, 1);return false;'><?php echo JText::_("ADD_READ_ONLY"); ?></a>-->
	<?php else: ?>
		<a id="pick_user" class="btn btn-default show_modal_iframe" href="<?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&layout=createuser&tmpl=component"); ?>" data_modal_width="700">
				<?php echo JText::_("CREATE_NEW_USER"); ?>
		</a>
	<?php endif; ?>	
	<a href='#' class="btn btn-default" onclick='parent.fss_modal_hide(); return false;'><?php echo JText::_('CANCEL'); ?></a>
</div>

<script>

function ccUsers(is_admin, is_readonly)
{
	var user_ids = new Array();
	
	jQuery('.user_check:checked').each( function () {
		var id = jQuery(this).attr('value');
		user_ids.push(id);
	});
	
	if (user_ids.length < 1)
	{
		alert("Please select some users first");
	} else {
		window.parent.addCC(user_ids, is_admin, is_readonly);			
		return false;
	}
	
}

function ccUsersEMail()
{
	var email = jQuery('#add_email').val();
	if (email == "")
    {
		alert("You must enter an email");						
	} else {
		window.parent.addEMailCC(email);
	}
}

</script>