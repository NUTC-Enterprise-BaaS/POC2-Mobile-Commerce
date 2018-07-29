
<?php if (FSS_Settings::get('user_hide_all_details')): ?>
	
	<?php echo FSS_Helper::PageSubTitle(JText::sprintf("TICKET_TITLE_SHORT", $this->ticket->title)); ?>
	
<?php else: ?>

	<?php echo FSS_Helper::PageSubTitle("TICKET_DETAILS"); ?>

	<?php if ($this->ticket->user_id == 0 && FSS_Settings::get('support_unreg_password_highlight') == 1): ?>
		<div class="alert alert-info">
		<h4><?php echo JText::_('YOUR_TICKET_ACCESS_DETAILS_ARE_'); ?></h4>
			<ul style="margin-top: 6px;">
			<?php if (FSS_Settings::get('support_unreg_type') == 0): ?>
				<li><?php echo JText::_('EMAIL'); ?> : <strong><?php echo $this->ticket->email; ?></strong></li>
			<?php endif; ?>
			<?php if (in_array(FSS_Settings::get('support_unreg_type'), array(1,2)) ): ?>
				<li><?php echo JText::_('REFERENCE'); ?> : <strong><?php echo $this->ticket->reference; ?></strong></li>
			<?php endif; ?>
			<?php if (in_array(FSS_Settings::get('support_unreg_type'), array(0,1)) ): ?>
				<li><?php echo JText::_('PASSWORD'); ?> : <strong><?php echo $this->ticket->password; ?></strong></li>
			<?php endif; ?>
			</ul>
			<div><?php echo JText::_('UNREG_NOTICE'); ?></div>
		</div>
	<?php endif; ?>

	<?php FSS_Helper::HelpText("support_user_view_after_details"); ?>

	<?php 
	$mc = new FSS_Multi_Col();
	$mc->Init($table_cols, array('class' => $table_classes, 'rows_only' => 1, 'force_table' => 1));
	
	?>
	
		<?php if (!FSS_Settings::get('user_hide_title')): ?>
			<?php $mc->Item(); ?>
			<th><?php echo JText::_("TITLE"); ?></th>
			<td colspan="2"><?php echo $this->ticket->title; ?></td>
		<?php endif; ?>

		<?php if (!FSS_Settings::get('user_hide_id')): ?>
			<?php $mc->Item(); ?>
			<th><?php echo JText::_("TICKET_ID"); ?></th>
			<?php if (FSS_Settings::get('support_unreg_password_highlight') == 2 && in_array(FSS_Settings::get('support_unreg_type'), array(1,2))): ?>
				<td><strong><?php echo $this->ticket->reference; ?></strong></td>
				<td><div class="text text-info"><i class='icon-arrow-left-2'></i> <?php echo JText::_('TICKET_ACCESS_REFERENCE'); ?></div></td>
			<?php else: ?>
				<td colspan="2"><?php echo $this->ticket->reference; ?></td>
			<?php endif; ?>
					
		<?php endif; ?>


		<?php if (SupportHelper::userIdMultiUser($this->ticket->user_id) && !FSS_Settings::get('user_hide_user')) : ?>
			<?php $mc->Item(); ?>
			<th><?php echo JText::_("USER"); ?></th>
			<td colspan="2"><?php echo $this->ticket->name; ?></td>
		<?php endif; ?>

		<?php if (SupportHelper::userIdMultiUser($this->ticket->user_id) && !FSS_Settings::get('user_hide_cc')) : ?>
			<?php $mc->Item(); ?>
			<th><?php echo JText::_("CC_USERS"); ?></th>
			<td colspan="2">
				<?php if (JFactory::getUser()->id == $this->ticket->user_id): ?>
					<a class="pull-right show_modal_iframe" data_modal_width="700" href="<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&layout=user&tmpl=component&ticketid=' . $this->ticket->id); ?>" id="fss_show_userlist">
						<i class="icon-new fssTip" title="<?php echo JText::_('CC_USER'); ?>"></i> 
					</a>
				<?php endif; ?>
		
				<div id="ccusers">
					<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ccusers.php'); ?>
				</div>
			</td>
		<?php endif; ?>

		<?php if ($this->ticket->password): ?>
			<?php if (in_array(FSS_Settings::get('support_unreg_type'), array(0,1))): ?>
				<?php $mc->Item(); ?>
				<th><?php echo JText::_("PASSWORD"); ?></th>
				<?php if (FSS_Settings::get('support_unreg_password_highlight') == 2): ?>
					<td><strong><?php echo $this->ticket->password; ?></strong></td>
					<td><div class="text text-info"><i class='icon-arrow-left-2'></i> <?php echo JText::_('TICKET_ACCESS_PASSWORD'); ?></div></td>
				<?php else: ?>
					<td colspan="2"><?php echo $this->ticket->password; ?></td>
				<?php endif; ?>
			<?php endif; ?>
			
			<?php $mc->Item(); ?>
			<th><?php echo JText::_("EMAIL"); ?></th>
			<?php if (FSS_Settings::get('support_unreg_password_highlight') == 2 && FSS_Settings::get('support_unreg_type') == 0): ?>
				<td><strong><?php echo $this->ticket->email; ?></strong></td>
				<td><div class="text text-info"><i class='icon-arrow-left-2'></i> <?php echo JText::_('TICKET_ACCESS_EMAIL'); ?></div></td>
			<?php else: ?>
				<td colspan="2"><?php echo $this->ticket->email; ?></td>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ($this->ticket->product && !FSS_Settings::get('user_hide_product')): ?>
			<?php $mc->Item(); ?>
			<th><?php echo JText::_("PRODUCT"); ?></th>
			<td colspan="2"><?php echo $this->ticket->product; ?></td>
		<?php endif; ?>

			<?php if ($this->ticket->department && !FSS_Settings::get('user_hide_department')): ?>
			<?php $mc->Item(); ?>
			<th><?php echo JText::_("DEPARTMENT"); ?></th>
			<td colspan="2"><?php echo $this->ticket->department; ?></td>
		<?php endif; ?>

		<?php if ($this->ticket->category && !FSS_Settings::get('support_hide_category') && !FSS_Settings::get('user_hide_category')): ?>
			<?php $mc->Item(); ?>
			<th><?php echo JText::_("CATEGORY"); ?></th>
			<td colspan="2"><?php echo $this->ticket->category; ?></td>
		<?php endif; ?>

		<?php if (!FSS_Settings::get('user_hide_updated')): ?>
			<?php $mc->Item(); ?>
			<th><?php echo JText::_("LAST_UPDATE"); ?></th>
			<td colspan="2">
				<?php echo FSS_Helper::TicketTime($this->ticket->lastupdate, FSS_DATETIME_MID); ?>
			</td>
	
			<?php if ($this->ticket->is_closed && strtotime($this->ticket->closed) > 0) : ?>
				<?php $mc->Item(); ?>
				<th><?php echo JText::_("CLOSED"); ?></th>
				<td colspan="2">
					<?php echo FSS_Helper::TicketTime($this->ticket->closed, FSS_DATETIME_MID); ?>
				</td>
			<?php endif; ?>
		<?php endif; ?>

		<?php if (!FSS_Settings::get('support_hide_handler') && !FSS_Settings::get('user_hide_handler')) : ?>
			<?php $mc->Item(); ?>
			<th><?php echo JText::_("HANDLER"); ?></th>
			<td colspan="2"><?php if ($this->ticket->assigned) {echo $this->ticket->assigned;} else {echo JText::_("UNASSIGNED");} ?></td>
		<?php endif; ?>

		<?php if (!FSS_Settings::get('user_hide_custom')): ?>
			<?php foreach ($this->ticket->customfields as $field): ?>
				<?php 
					if ($field['grouping'] != "") continue;
					
					if ($field['reghide'] == 2 && $this->ticket->user_id > 0)
						continue;
		
					if ($field['reghide'] == 1 && $this->ticket->user_id < 1)
						continue;
					
					if ($field['permissions'] > 1 && $field['permissions'] != 5) 
						continue; 
						
					if (!in_array($field['access'], JFactory::getUser()->getAuthorisedViewLevels())) continue;
					
				?>
				<?php $mc->Item(); ?>
				<th width='<?php echo FSS_Settings::get('ticket_label_width'); ?>'><?php echo FSSCF::FieldHeader($field, false, false); ?></th>
				<td colspan="2">
					<?php if ($field['permissions'] == 0 && !$this->ticket->is_closed && $this->CanEditField($field) && $this->ticket->merged == 0): ?>
						<a class='pull-right show_modal_iframe padding-left-small' href="<?php echo FSSRoute::_("index.php?option=com_fss&view=ticket&layout=field&tmpl=component&ticketid=".$this->ticket->id. "&fieldid=" . $field['id'] );// FIX LINK ?>">
							<i class="icon-edit fssTip" title="<?php echo JText::_("EDIT_FIELD"); ?>"></i> 
						</a>
					<?php endif; ?>
					<?php echo FSSCF::FieldOutput($field, $this->ticket->custom, array('ticketid' => $this->ticket->id, 'userid' => $this->ticket->user_id, 'ticket' => $this->ticket)); ?>
				</td>	
			<?php endforeach; ?>
		<?php endif; ?>
		
		<?php if (!FSS_Settings::get('user_hide_status')): ?>
			<?php $mc->Item(); ?>
			<th style="vertical-align: middle"><?php echo JText::_("STATUS"); ?></th>

			<td colspan="2">
				<?php if (!$this->ticket->is_closed && FSS_Settings::get('support_user_can_close') && $this->ticket->canclose) : ?>
					<form id='status_change' action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&task=update.ticket_status_id' ); ?>" method="post" style="margin: 0px;">
						<input type="hidden" name="ticketid" value="<?php echo $this->ticket->id; ?>">

						<select id='ticket_status_id' name='ticket_status_id' class="input-medium" style="margin: 0px; color: <?php echo $this->ticket->color; ?>" onchange="jQuery('#status_change').submit();">
							<option value="<?php echo $this->ticket->ticket_status_id; ?>" style='color: <?php echo $this->ticket->color; ?>' selected><?php echo $this->ticket->status; ?></option>
							<?php foreach ($this->statuss as $status): ?>
								<?php if ( $status->def_closed): ?>
									<option value='<?php echo $status->id; ?>' style='color: <?php echo $status->color; ?>'><?php echo $status->title; ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
					
					</form>
				<?php else: ?>
					<span style='color: <?php echo $this->ticket->color; ?>'><?php echo $this->ticket->status; ?></span>
				<?php endif; ?>
			</td>
		<?php endif; ?>

		<?php if (!FSS_Settings::get('support_hide_priority') && !FSS_Settings::get('user_hide_priority')) : ?>
			<?php $mc->Item(); ?>
			<th style="vertical-align: middle"><?php echo JText::_("PRIORITY"); ?></th>
			<td colspan="2">
				<?php if (!$this->ticket->is_closed && !$this->ticket->readonly) : ?>
					<form id='pri_change' action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&task=update.ticket_pri_id' ); ?>" method="post" style="margin: 0px;">
						<input type="hidden" name="ticketid" value="<?php echo $this->ticket->id; ?>">

						<select id='ticket_pri_id' name='ticket_pri_id' class="input-medium" style="margin: 0px; color: <?php echo $this->ticket->pricolor; ?>" onchange="jQuery('#pri_change').submit();">
							<?php foreach ($this->pris as $pri): ?>
								<option value='<?php echo $pri->id; ?>' style='color: <?php echo $pri->color; ?>' <?php if ($pri->id == $this->ticket->ticket_pri_id) echo "selected='selected'"; ?>><?php echo $pri->title; ?></option>
							<?php endforeach; ?>
						</select>							
					</form>
				<?php else: ?>
					<span style='color:<?php echo $this->ticket->pricolor; ?>'><?php echo $this->ticket->priority; ?></span>
				<?php endif; ?>
			</td>
		<?php endif; ?>


		<?php if (FSS_Settings::get('ratings_ticket') && $this->ticket->isClosed() && $this->ticket->rating > 0): ?>
			<?php $mc->Item(); ?>
			<th style="vertical-align: middle"><?php echo JText::_("RATING"); ?></th>
			<td colspan="2">
				<?php echo SupportHelper::ticketRating($this->ticket, false, FSS_Settings::get('ratings_ticket_change'), true); ?>
			</td>
		<?php endif; ?>

	<?php $mc->End(); ?>

	<?php if (!FSS_Settings::get('user_hide_custom')) : ?>
		<?php $grouping = ""; $open = false; ?>

		<?php foreach ($this->ticket->customfields as $field) : ?>

			<?php 
				if ($field['grouping'] == "")	
					continue;
			
				if ($field['reghide'] == 2 && $this->ticket->user_id > 0)
					continue;
		
				if ($field['reghide'] == 1 && $this->ticket->user_id < 1)
					continue;

				if ($field['permissions'] > 1 && $field['permissions'] != 5) 
					continue; 
				
				if (!in_array($field['access'], JFactory::getUser()->getAuthorisedViewLevels())) continue;
				
			?>
		
			<?php if ($field['grouping'] != $grouping): ?>
				<?php if ($open) $mc->End();	?>
				<?php echo FSS_Helper::PageSubTitle($field['grouping']); ?>
				<?php
					$mc = new FSS_Multi_Col();
				$mc->Init($table_cols, array('class' => $table_classes, 'rows_only' => 1, 'force_table' => 1));
				?>
				<?php $open = true;	$grouping = $field['grouping']; ?>
			<?php endif; ?>

			<?php $mc->Item(); ?>
			<th width='<?php echo FSS_Settings::get('ticket_label_width'); ?>'><?php echo FSSCF::FieldHeader($field, false, false); ?></th>
			<td colspan="2">
				<?php if ($field['permissions'] == 0 && !$this->ticket->is_closed && $this->CanEditField($field) && $this->ticket->merged == 0): ?>
					<a class='pull-right show_modal_iframe padding-left-small' href="<?php echo FSSRoute::_("index.php?option=com_fss&view=ticket&layout=field&tmpl=component&ticketid=".$this->ticket->id. "&fieldid=" . $field['id'] );// FIX LINK ?>">
						<i class="icon-edit fssTip" title="<?php echo JText::_("EDIT_FIELD"); ?>"></i> 
					</a>
				<?php endif; ?>
				<?php echo FSSCF::FieldOutput($field,$this->ticket->custom, array('ticketid' => $this->ticket->id, 'userid' => $this->ticket->user_id, 'ticket' => $this->ticket)); ?>
			</td>	
	
		<?php endforeach; ?>

		<?php if ($open) $mc->End(); ?>
	<?php endif; ?>
	
	<?php FSS_Helper::HelpText("support_user_view_end_details"); ?>

<?php endif; ?>

<?php //print_p($this->ticket); ?>