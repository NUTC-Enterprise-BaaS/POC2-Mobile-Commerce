<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

/**
 * THIS NEEDS CHANGING OVER TO USE THE FSS_Multi_Col CLASS
 **/
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'table.php');
FSS_Table::$cols = FSS_Settings::get('support_info_cols');
FSS_Table::$curcol = 1;

?>
<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td valign="top">

<?php FSS_Table::TableOpen(); ?>

<?php FSS_Table::ColStart("tr_subject"); ?>
	<th><?php echo JText::_("SUBJECT"); ?></th>
	<td>
		<?php if ($this->can_ChangeTicket() && $this->can_EditTicket()): ?>
			<div id="title_show">
				<a class="pull-right padding-left-small" href='#' onclick='title_edit_start();return false;'>
					<i class="icon-edit fssTip" title="<?php echo JText::_("CHANGE_TICKET_TITLE"); ?>"></i>
				</a>		
				<span id="title_value">
					<?php echo $this->ticket->title; ?>
				</span>
			</div>
			<div id='title_edit' class="input-append" style="display:none;">
				<input type="text" class='fss_support_custom_edit input-medium' id="title_input" size="30" value='<?php echo FSS_Helper::escape($this->ticket->title); ?>' />
				<button class="btn btn-success" type="button" onclick='title_edit_save();return false;'><i class="icon-save"></i></button>
				<button class="btn btn-danger" type="button" onclick='title_edit_end();return false;'><i class="icon-cancel"></i></button>
			</div>
		<?php else: ?>
			<?php echo $this->ticket->title; ?>
		<?php endif; ?>
	</td>
<?php FSS_Table::ColEnd(); ?>


<?php FSS_Table::ColStart("tr_reference"); ?>
	<th><?php echo JText::_("TICKET_ID"); ?></th>
	<td><?php echo $this->ticket->reference; ?></td>
<?php FSS_Table::ColEnd(); ?>


<?php if (count($this->ticket->related) > 0): ?>
<?php FSS_Table::ColStart("tr_related"); ?>
	<th><?php echo JText::_("RELATED_TICKETS"); ?></th>
	<td>
		<?php if ($this->can_ChangeTicket()): ?>
			<a class="pull-right padding-left-small" href='<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=list&merge=related&ticketid=' . $this->ticket->id, false); ?>'>
				<i class="icon-plus fssTip" title="<?php echo JText::_("ADD_RELATED_TICKET"); ?>"></i>
			</a>		
		<?php endif; ?>	

		<?php if ($this->print): ?>
			<?php foreach ($this->ticket->related as $relt): ?>
				<div>
					<?php echo $relt->title; ?>
				</div>	
			<?php endforeach; ?>		<?php else: ?>
			<?php foreach ($this->ticket->related as $relt): ?>
				<div>
					<a href="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&task=merge.removerelated&source_id=' . $this->ticket->id . '&dest_id=' . $relt->id, false); ?>"><i class="icon-cancel fssTip" title="Remove Related Ticket"></i></a>
				
					<a href="<?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=" . $relt->id, false); ?>">
						<?php echo $relt->title; ?>
					</a>
				</div>	
			<?php endforeach; ?>
		<?php endif; ?>
	</td>
<?php FSS_Table::ColEnd(); ?>
<?php endif; ?>


<?php if (count(SupportHelper::getProducts()) > 0): ?>
<?php FSS_Table::ColStart("tr_product"); ?>
	<th><?php echo JText::_("PRODUCT"); ?></th>
	<td>
		<?php if ($this->can_ChangeTicket() && $this->can_Forward()): ?>
			<a class="pull-right padding-left-small" href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=reply&type=product&ticketid=' . $this->ticket->id ); ?>'>
				<i class="icon-arrow-right fssTip" title="<?php echo JText::_("FORWARD_TO_ANOTHER_PRODUCT"); ?>"></i>
			</a>		
		<?php endif; ?>	

		<?php echo FSS_Translate_Helper::TrF('title', $this->ticket->product, $this->ticket->prtr); ?>
	</td>
<?php FSS_Table::ColEnd(); ?>
<?php endif; ?>


<?php if (count(SupportHelper::getDepartments()) > 0): ?>
<?php FSS_Table::ColStart("tr_department"); ?>
	<th><?php echo JText::_("DEPARTMENT"); ?></th>
	<td>		
		<?php if ($this->can_ChangeTicket() && $this->can_Forward()): ?>
			<a class="pull-right padding-left-small" href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=reply&type=product&ticketid=' . $this->ticket->id ); ?>'>
				<i class="icon-arrow-right fssTip" title="<?php echo JText::_("FORWARD_TO_ANOTHER_DEPARTMENT"); ?>"></i> 
			</a>		
		<?php endif; ?>
		
		<?php echo FSS_Translate_Helper::TrF('title', $this->ticket->department, $this->ticket->dtr); ?>
	</td>
<?php FSS_Table::ColEnd(); ?>
<?php endif; ?>


<?php if (FSS_Settings::get('support_hide_category') != 1): ?>
<?php FSS_Table::ColStart("tr_category"); ?>
	<th><?php echo JText::_("CATEGORY"); ?></th>
	<td>
		<?php if ($this->can_ChangeTicket() && $this->can_EditMisc()): ?>
			<div id="cat_show">
				<div class="pull-right padding-left-small">
					<a href='#' onclick='category_edit_toggle();return false;'>
						<i class="icon-edit fssTip" title="<?php echo JText::_("CHANGE_TICKET_CATEGORY"); ?>"></i> 
					</a>		
				</div>
				<div id="cat_value">
					<?php echo FSS_Translate_Helper::TrF('title', $this->ticket->category, $this->ticket->ctr); ?>
				</div>
			</div>
			<div id='cat_edit' style="display:none;" class="input-append">
				<select id='catid' name='catid'>
					<?php $sect = ""; $open = false; ?>
					<?php $cats = SupportHelper::getAllowedCategories($this->ticket); ?>
					<?php foreach ($cats as $cat): ?>
						<?php 
							if ($cat->section != $sect) {
								if ($open)
									echo "</optgroup>";
								$open = true;
								echo "<optgroup label='" . $cat->section . "'>";
								$sect = $cat->section;	
							}								
						?>
						<option value='<?php echo $cat->id; ?>' <?php if ($cat->id == $this->ticket->ticket_cat_id) echo " SELECTED"; ?> ><?php echo $cat->title; ?></option>
					<?php endforeach; ?>
					<?php if ($open) echo "</optgroup>"; ?>
				</select>
				<button class="btn btn-success" type="button" onclick='category_edit_save();return false;'><i class="icon-save"></i></button>
				<button class="btn btn-danger" type="button" onclick='category_edit_toggle();return false;'><i class="icon-cancel"></i></button>
			</div>
		<?php else: ?>
			<?php echo FSS_Translate_Helper::TrF('title', $this->ticket->category, $this->ticket->ctr); ?>
		<?php endif; ?>
	</td>
<?php FSS_Table::ColEnd(); ?>
<?php endif; ?>

<?php FSS_Table::ColStart("tr_user"); ?>
	<th><?php echo JText::_("USER"); ?></th>
	<td>
		<?php if ($this->can_ChangeTicket() && $this->can_ChangeUser()): ?>
			<a class="pull-right padding-left-small" href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=reply&type=user&ticketid=' . $this->ticket->id ); ?>'>
				<i class="icon-arrow-right fssTip" title="<?php echo JText::_("FORWARD_TO_ANOTHER_USER"); ?>"></i> 
			</a>		
		<?php endif; ?>	
		<?php if (count($this->ticket->user_cc) < 1 && $this->can_ChangeTicket() && $this->can_ChangeUser()): ?>
			<a class="pull-right show_modal_iframe" style="margin-left: 2px;" data_modal_width="800" href="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=users&tmpl=component&mode=user&ticketid=' . $this->ticket->id); ?>" id="fss_show_userlist">
				<i class="icon-new fssTip" title="<?php echo JText::_('CC_USER'); ?>"></i> 
			</a>
		<?php endif; ?>
			
		<?php if ($this->ticket->user_id == 0): ?>

			<?php echo $this->ticket->unregname; ?> (<?php echo JText::_("UNREGISTERED"); ?>)
			
		<?php else: ?>
			<?php 
			$close_a = false; $itemid = "";
			if (FSS_Settings::get('support_profile_itemid') > 0)
				$itemid = "&Itemid=" . FSS_Settings::get('support_profile_itemid')
			?>
			<?php 
				$link = 'javaScript:void(0);';
				if (file_exists(JPATH_SITE.DS.'components'.DS.'com_community'))
					$link = JRoute::_('index.php?option=com_community&view=profile&userid='. $this->ticket->user_id . $itemid);
				if (file_exists(JPATH_SITE.DS.'components'.DS.'com_comprofiler'))
					$link = JRoute::_('index.php?option=com_comprofiler&task=userprofile&user='. $this->ticket->user_id . $itemid);
			?>
			<a href='<?php echo $link;?>' class='fssTip' title="<?php echo htmlentities($this->ticket->useremail); ?>">
				<?php echo $this->ticket->name; ?> (<?php echo $this->ticket->username; ?>)
			</a>
		<?php endif; ?>
	</td>
<?php FSS_Table::ColEnd(); ?>

<?php if (count($this->ticket->user_cc) > 0): ?>
	<?php FSS_Table::ColStart("tr_cc"); ?>
		<th><?php echo JText::_("CC_USERS"); ?></th>
		<td>
			<?php if ($this->can_ChangeTicket()): ?>
				<a class="pull-right show_modal_iframe" data_modal_width="800" href="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=users&tmpl=component&mode=user&ticketid=' . $this->ticket->id); ?>" id="fss_show_userlist">
					<i class="icon-new fssTip" title="<?php echo JText::_('CC_USER'); ?>"></i> 
				</a>
			<?php endif; ?>

			<?php foreach($this->ticket->user_cc as $cc): ?>
				<div class="fss_tag label user_cc label-small-close  <?php echo $cc->readonly || $cc->uremail ? 'label-warning' : 'label-success'; ?>" id="user_cc_<?php echo $cc->id; ?>">
					<a class="close" href="<?php echo FSSRoute::x("&ticketid=" . $this->ticket->id . "&task=ticket.removecc&is_admin=0&ids=" . $cc->id . ($cc->uremail ? '&urid=' . $cc->urid : ''), false); ?>">&times;</a>
					<span class="fssTip" title="<?php echo htmlentities($cc->email . "<br />"); ?><?php echo $cc->readonly ? JText::_('READ_ONLY') : JText::_('FULL_ACCESS'); ?>">
						<?php if ($cc->name): ?>
							<?php echo $cc->name; ?>
						<?php else: ?>
							<?php echo $cc->uremail; ?>
						<?php endif; ?>
					</span>
				</div>
			<?php endforeach; ?>
		</td>
	<?php FSS_Table::ColEnd(); ?>
<?php endif; ?>

<?php if (count($this->ticket->groups) > 0): ?>
<?php FSS_Table::ColStart(); ?>
	<th><?php echo JText::_("USER_GROUPS"); ?></th>
	<td>
		<?php $gl = array();
		foreach ($this->ticket->groups as $group)
			echo "<span class='label'>".$group->groupname . "</span>&nbsp;";
		?>
	</td>
<?php FSS_Table::ColEnd(); ?>
<?php endif; ?>

<?php if ($this->ticket->email): ?>
<?php FSS_Table::ColStart("tr_email"); ?>
	<th><?php echo JText::_("EMAIL"); ?></th>
	<td>
		<?php if ($this->can_ChangeTicket() && $this->can_ChangeUser()): ?>
			<div id="email_show">
				<a class="pull-right padding-left-small" href='#' onclick='email_edit_start();return false;'>
					<i class="icon-edit fssTip" title="<?php echo JText::_("CHANGE_TICKET_EMAIL"); ?>"></i> 
				</a>		
				<a href="#" class="fssTip pull-right padding-left-small" title="<?php echo JText::_("CLICK_TO_SHOW_PASSWORD"); ?>" onclick='jQuery(".ticket_password").show(); jQuery(this).hide(); return false;'>
					<i class="icon-key"></i>
				</a>
				<span id="email_value">
					<?php echo $this->ticket->email; ?> 
				</span>
				<div class='ticket_password' style="display: none;"><?php echo $this->ticket->password; ?></div>
			</div>
			
			<div id='email_edit' style="display:none;" class="input-append">
				<input type="text" class='fss_support_custom_edit' id="email_input" size="30" value='<?php echo FSS_Helper::escape($this->ticket->email); ?>' />
				<button class="btn btn-success" href='#' onclick='email_edit_save();return false;'><i class="icon-save"></i></button>
				<button class="btn btn-danger" href='#' onclick='email_edit_end();return false;'><i class="icon-cancel"></i></button>		
			</div>
		<?php else: ?>
			<a href="#" class="fssTip pull-right padding-left-small" title="<?php echo JText::_("CLICK_TO_SHOW_PASSWORD"); ?>" onclick='jQuery(".ticket_password").show(); jQuery(this).hide(); return false;'>
				<i class="icon-key"></i>
			</a>
			<?php echo $this->ticket->email; ?>
			<div class='ticket_password' style="display: none;"><?php echo $this->ticket->password; ?></div>
		<?php endif; ?>
	</td>

<?php FSS_Table::ColEnd(); ?>
<?php endif; ?>


<?php FSS_Table::ColStart("tr_lastupdate"); ?>
	<th><?php echo JText::_("LAST_UPDATE"); ?></th>
	<td>
		<?php echo FSS_Helper::TicketTime($this->ticket->lastupdate, FSS_DATETIME_MID); ?>
	</td>
<?php FSS_Table::ColEnd(); ?>

<?php $st = FSS_Ticket_Helper::GetStatusByID($this->ticket->ticket_status_id);
if ($st->is_closed && strtotime($this->ticket->closed) > 0) : ?>
<?php FSS_Table::ColStart(); ?>
	<th><?php echo JText::_("CLOSED"); ?></th>
	<td>
		<?php echo FSS_Helper::TicketTime($this->ticket->closed, FSS_DATETIME_MID); ?>
	</td>
<?php FSS_Table::ColEnd(); ?>
<?php endif; ?>

<?php if (FSS_Settings::get('support_hide_handler') != 1) : ?>
	<?php FSS_Table::ColStart("tr_handler"); ?>
		<th><?php echo JText::_("HANDLER"); ?></th>
		<td>
			<?php if ($this->can_ChangeTicket() && $this->can_Forward()): ?>
			<a class="pull-right padding-left-small" href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=reply&type=handler&ticketid=' . $this->ticket->id ); ?>'>
				<i class="icon-arrow-right fssTip" title="<?php echo JText::_("FORWARD_TO_ANOTHER_HANDLER"); ?>"></i> 
			</a>		
			<?php endif; ?>

			<?php if (count($this->ticket->admin_cc) == 0 && $this->can_ChangeTicket() && $this->can_Forward()): ?>
				<a class="pull-right show_modal_iframe" data_modal_width="800" href="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=users&tmpl=component&mode=admin&ticketid=' . $this->ticket->id); ?>" id="fss_show_userlist">
					<i class="icon-new fssTip" title="<?php echo JText::_('ADD_ADMIN'); ?>"></i> 
				</a>
			<?php endif; ?>

			<?php if (isset($this->ticket->assigned) && $this->ticket->assigned) { echo $this->ticket->assigned; } else { echo JText::_("UNASSIGNED"); } ?>
		</td>
	<?php FSS_Table::ColEnd(); ?>
<?php endif; ?>

<?php if (count($this->ticket->admin_cc) > 0): ?>
	<?php FSS_Table::ColStart("tr_admincc"); ?>
		<th><?php echo JText::_("CC_HANDLERS"); ?></th>
		<td>
		
			<?php if ($this->can_ChangeTicket()): ?>
				<a class="pull-right show_modal_iframe" data_modal_width="800" href="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=users&tmpl=component&mode=admin&ticketid=' . $this->ticket->id); ?>" id="fss_show_userlist">
					<i class="icon-new fssTip" title="<?php echo JText::_('CC_HANDLER'); ?>"></i> 
				</a>
			<?php endif; ?>
			
			<?php foreach($this->ticket->admin_cc as $cc): ?>
				<div class="fss_tag label admin_cc label-small-close label-info" id="admin_cc_<?php echo $cc->id; ?>">
					<?php if ($this->can_ChangeTicket()): ?>
						<a class="close" href="<?php echo FSSRoute::x("&ticketid=" . $this->ticket->id . "&task=ticket.removecc&is_admin=1&ids=" . $cc->id, false); ?>">&times;</a>
					<?php endif; ?>
					<span>
						<?php echo $cc->name; ?>
					</span>
				</div>
			<?php endforeach; ?>
		</td>
	<?php FSS_Table::ColEnd(); ?>
<?php endif; ?>

<?php if (!FSS_Settings::get('support_hide_users_tickets')) : ?>
<?php 
	$userticketcount = SupportHelper::getUserTicketCountsForAdmin($this->ticket->user_id,$this->ticket->email);

	$statuss = SupportHelper::getStatuss(false);
	$tooltiptext = array();
	foreach ($statuss as $status)
	{
		$id = $status->id;
		if (array_key_exists($id, $userticketcount))
		{
			$tooltiptext[] = $userticketcount[$id] . " " . $status->title;
		}	
	}
	$tooltiptext = htmlspecialchars(implode("<br />",$tooltiptext), ENT_QUOTES, "UTF-8");
	
?>

<?php FSS_Table::ColStart("tr_userstickets"); ?>
	<th><?php echo JText::_("USERS_TICKETS"); ?></th>
	<td>
		<?php if (!empty($this->print) && $this->print): ?>
			<?php echo JText::sprintf("UC_TICKETS",$userticketcount['total']); ?>
		<?php else: ?>
			
			<span class="pull-right fssTip padding-left-small" title="<?php echo $tooltiptext; ?>" >
				<?php 
					if ($this->ticket->username)
					{
						$un = "&username=" . $this->ticket->username;
					} else {
						$un = "&useremail=" . $this->ticket->email;
					}
				?>
				<a href="<?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&showbasic=1&searchtype=advanced&what=search&status=" . $un );?>"><i class="icon-search"></i> </a>
			</span>
				
			<?php echo JText::sprintf("UC_TICKETS",$userticketcount['total']); ?>
		<?php endif; ?>
	</td>
<?php FSS_Table::ColEnd(); ?>
<?php endif; ?>


<?php if (!FSS_Settings::get('support_hide_tags')) : ?>
	<?php FSS_Table::ColStart("tr_tags"); ?>
		<th><?php echo JText::_("TAGS"); ?></th>
		<td>

		<?php if (!FSS_Settings::get('support_hide_tags') && $this->can_ChangeTicket() && $this->can_EditMisc()) : ?>	
			<div class="pull-right padding-left-small">
				<div style="position: relative;" id="tag_list_container">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="icon-new fssTip" title="<?php echo JText::_("ADD_TAGS"); ?>"></i> 
					</a>
					<ul class="dropdown-menu">
						<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_tag_list.php'); ?>
					</ul>
				</div>
			</div>
		<?php endif;?>

		<div id="tags">
			<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_tags.php'); ?>
		</div>

		</td>
	<?php FSS_Table::ColEnd(); ?>
<?php endif; ?>

<?php if (FSS_Settings::Get('time_tracking') != ""): ?>
	<?php FSS_Table::ColStart("tr_time"); ?>
		<th><?php echo JText::_("TIME_TAKEN"); ?></th>
	
		<td style="border-right:none;">
			<?php if ($this->can_ChangeTicket() && $this->can_EditMisc()) : ?>
				<a class="pull-right padding-left-small" href="#" onclick="jQuery('#time_take_popup').modal();return false;">
					<i class="icon-new fssTip" title="<?php echo JText::_("ADD_TIME_LABEL"); ?>"></i> 
				</a>
			<?php endif;?>
		
			<div id="time_taken_value" style="display: none;"><?php echo $this->ticket->timetaken; ?></div>
		
			<span id="time_taken_disp">
				<?php
				$time = $this->ticket->timetaken;
				$hours = floor($time / 60);
				$mins = $time % 60;
				$mins = sprintf("%02d", $mins);
			
				echo JText::sprintf("TIME_TAKEN_DISP", $hours, $mins);
				?>
			</span>
	
		</td>
	<?php FSS_Table::ColEnd(); ?>
<?php endif; ?>

<?php // fields with no grouping should be in main table
if ($this->ticket->customfields && count($this->ticket->customfields) > 0)
{
	foreach ($this->ticket->customfields as $field)
	{
		if ($field['grouping'] != "")
			continue;
			
		if ($field['adminhide'])
			continue;
				
		if ($field['reghide'] == 2 && $this->ticket->email == "") continue; // unregistered field only
		if ($field['reghide'] == 1 && $this->ticket->user_id < 1) continue; // registered field only

		FSS_Table::ColStart("tr_cf" . $field['id'] . " tr_cf_" . $field['alias']);
?>
		<th width='<?php echo FSS_Settings::get('ticket_label_width'); ?>'>
			<?php echo FSSCF::FieldHeader($field); ?>
		</th>
		<td>
			<?php if ($this->can_ChangeTicket() && $this->canEditField($field) && $this->can_EditFields()): ?>
				<a class='pull-right show_modal_iframe padding-left-small' href="<?php echo FSSRoute::_("&tmpl=component&layout=field&editfield=" . $field['id'] ); ?>">
					<i class="icon-edit fssTip" title="<?php echo JText::_("EDIT_FIELD"); ?>"></i> 
				</a>
			<?php endif; ?>

			<?php if ($field['advancedsearch'] == 1): ?>
				<?php if (array_key_exists($field['id'], $this->ticket->custom)): ?>
					<a class='pull-right padding-left-small' href="<?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&what=search&searchtype=advanced&custom_" . $field['id'] . "=" . $this->ticket->custom[$field['id']]['value']); ?>">
						<i class="icon-search fssTip" title="<?php echo JText::_("SIMILAR_TICKETS"); ?>"></i> 
					</a>
				<?php endif; ?>
			<?php endif; ?>

			<?php echo FSSCF::FieldOutput($field,$this->ticket->custom,array('ticketid' => $this->ticket->id, 'userid' => $this->ticket->user_id, 'ticket' => $this->ticket)); ?>
		</td>
		<?php
		FSS_Table::ColEnd();
	}
} ?>

<?php FSS_Table::ColStart("tr_status"); ?>
	<th style="vertical-align: middle"><?php echo JText::_("STATUS"); ?></th>
	<td>

		<?php if ($this->can_ChangeTicket()): ?>
			<form id='status_form' action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&task=update.ticket_status_id&ticketid=' . $this->ticket->id ); ?>" method="post" style="margin: 0px;">
				<?php $statuss = SupportHelper::getStatuss(); ?>
				<select id='ticket_status_id' name='ticket_status_id' class="input-medium select-color" style="margin: 0px; color: <?php echo FSS_Helper::escape($this->ticket->color); ?>" onchange="jQuery('#status_form').submit();">
					<?php foreach ($statuss as $status): ?>
						<?php if (!$this->can_Close() && $status->is_closed) continue; ?>
						<option value='<?php echo $status->id; ?>' style='color: <?php echo $status->color; ?>' <?php if ($status->id == $this->ticket->ticket_status_id) echo "selected='selected'"; ?>><?php echo $status->title; ?></option>
					<?php endforeach; ?>
				</select>
			</form>
		<?php else: ?>
			<span style='color: <?php echo FSS_Helper::escape($this->ticket->color); ?>'>
				<?php echo $this->ticket->status; ?>
			</span>
		<?php endif; ?>
	</td>
<?php FSS_Table::ColEnd(); ?>

<?php if (FSS_Settings::get('support_hide_priority') != 1) : ?>
	<?php FSS_Table::ColStart("tr_pri"); ?>
		<th style="vertical-align: middle"><?php echo JText::_("PRIORITY"); ?></th>
		<td>
			<?php if ($this->can_ChangeTicket()): ?>
				<select id='ticket_pri_id' name='ticket_pri_id' class="input-medium select-color" style="margin: 0px; color:<?php echo FSS_Helper::escape($this->ticket->pricolor); ?>" onchange="priority_update();">
					<?php $priorities = SupportHelper::getPriorities(); ?>
						<?php foreach ($priorities as $pri): ?>
						<option value='<?php echo $pri->id; ?>' style='color: <?php echo FSS_Helper::escape($pri->color); ?>' <?php if ($pri->id == $this->ticket->ticket_pri_id) echo "selected='selected'"; ?>><?php echo $pri->title; ?></option>
					<?php endforeach; ?>
				</select>
			<?php else: ?>	
				<span style='color:<?php echo FSS_Helper::escape($this->ticket->pcolor); ?>'>
					<?php echo $this->ticket->priority; ?>
				</span>
			<?php endif; ?>
		</td>
	<?php FSS_Table::ColEnd(); ?>
<?php endif; ?>

<?php if (FSS_Settings::get('ratings_per_message_admin_overview') && $this->ticket->message_rating > 0) : ?>
	<?php FSS_Table::ColStart("tr_rating"); ?>
		<th style="vertical-align: middle"><?php echo JText::_("MESSAGE_RATING"); ?></th>
		<td>
			<?php echo SupportHelper::displayRating($this->ticket->message_rating); ?>
		</td>
	<?php FSS_Table::ColEnd(); ?>
<?php endif; ?>

<?php if (FSS_Settings::get('ratings_ticket') && $this->ticket->rating > 0) : ?>
	<?php FSS_Table::ColStart("tr_ticketrating"); ?>
		<th style="vertical-align: middle"><?php echo JText::_("TICKET_RATING"); ?></th>
		<td>
			<?php echo SupportHelper::displayRating($this->ticket->rating); ?>
		</td>
	<?php FSS_Table::ColEnd(); ?>
<?php endif; ?>

<?php

FSS_Table::TableClose();

$grouping = "";
$open = false;
if ($this->ticket->customfields && count($this->ticket->customfields) > 0)
{
	foreach ($this->ticket->customfields as $field)
	{
		if ($field['grouping'] == "") continue;
		
		if ($field['adminhide']) continue;
		
		if ($field['reghide'] == 2 && $this->ticket->email == "") continue; // unregistered field only
		if ($field['reghide'] == 1 && $this->ticket->user_id < 1) continue; // registered field only
		
		if ($field['grouping'] != $grouping)
		{
			if ($open)
			{
				FSS_Table::TableClose();
			}
	
			echo FSS_Helper::PageSubTitle($field['grouping']);
			FSS_Table::TableOpen();
			$open = true;	
			$grouping = $field['grouping'];
		}
		
		FSS_Table::ColStart("tr_cf" . $field['id'] . " tr_cf_" . $field['alias']);	
		?>
		<th width='<?php echo FSS_Settings::get('ticket_label_width'); ?>'><?php echo FSSCF::FieldHeader($field); ?></th>
			<td>
			<?php if ($this->can_ChangeTicket() && $this->CanEditField($field) && $this->can_EditFields()): ?>
				<a class='pull-right show_modal_iframe padding-left-small' href="<?php echo FSSRoute::_("&tmpl=component&layout=field&editfield=" . $field['id'] ); ?>">
					<i class="icon-edit fssTip" title="<?php echo JText::_("EDIT_FIELD"); ?>"></i> 
				</a>
			<?php endif; ?>

			<?php if ($field['advancedsearch'] == 1): ?>
				<?php if (array_key_exists($field['id'], $this->ticket->custom)): ?>
					<a class='pull-right padding-left-small' href="<?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&what=search&searchtype=advanced&custom_" . $field['id'] . "=" . $this->ticket->custom[$field['id']]['value']); ?>">
						<i class="icon-search fssTip" title="<?php echo JText::_("SIMILAR_TICKETS"); ?>"></i> 
					</a>
				<?php endif; ?>
			<?php endif; ?>
			
			<?php echo FSSCF::FieldOutput($field,$this->ticket->custom,array('ticketid' => $this->ticket->id, 'userid' => $this->ticket->user_id, 'ticket' => $this->ticket)); ?>
		</td>
		<?php
		FSS_Table::ColEnd();
	}
}

if ($open)
{
	FSS_Table::TableClose();
}

?>
</td>
</tr>
</table>

<div class="modal fss_modal" id="time_take_popup" style='display: none'>
	<div class="modal-header">
		<button type="button" class="close simplemodal-close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('ADD_TIME_LABEL'); ?></h3>
	</div>
	<div class="modal-body">
		<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_time_taken.php'); ?>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-default simplemodal-close" data-dismiss="modal">Cancel</a>
		<a href="#" class="btn btn-primary" onclick="time_add();return false;"><?php echo JText::_('FSS_TICKET_ADD_TIME'); ?></a>
		<a href="#" class="btn btn-danger" onclick="time_add(1);return false;"><?php echo JText::_('FSS_TICKET_REMOVE_TIME'); ?></a>
	</div>
</div>

<script>
jQuery(document).ready(function () {
	jQuery('#fss_modal_container').append(jQuery('#time_take_popup'));
});
</script>