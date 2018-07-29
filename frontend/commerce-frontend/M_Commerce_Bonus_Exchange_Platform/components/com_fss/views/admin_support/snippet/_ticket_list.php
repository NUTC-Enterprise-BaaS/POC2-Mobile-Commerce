<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
	
	<?php if (count($this->tickets) < 1) : ?>
		
		<div class="alert alert-info">
			<?php echo JText::_("THERE_ARE_CURRENTLY_NO_TICKETS_THAT_MATCH_YOUR_SEARCH"); ?>
		</div>
		
	<?php else: ?>

	
		<?php
			$table_indent = 0;
			if (SupportUsers::getSetting("group_products")) $table_indent++;
			if (SupportUsers::getSetting("group_departments")) $table_indent++;
			if (SupportUsers::getSetting("group_cats")) $table_indent++;
			if (SupportUsers::getSetting("group_group")) $table_indent++;
			if (SupportUsers::getSetting("group_pri")) $table_indent++;
			$grp_prod = -1;
			$grp_dept = -1;
			$grp_cat = -1;
			$grp_group = -1;
			$grp_pri = -1;
			$grp_open = 1;
			$tab_style = "class='table-responsive' style='margin-left:". ($table_indent * 16) . "px;'";
		?>
			<div <?php echo $tab_style; ?>><table class='table table-bordered table-ticketborders table-condensed'>
			<?php $this->listHeader(); ?>
			
			<?php foreach ($this->tickets as $ticket): ?>
				<?php 
					if (SupportUsers::getSetting("group_products")) {
						if ($ticket->prod_id != $grp_prod)
						{
							if ($grp_open == 1)
								echo "</table></div>";
							$grp_open = 0;	
							
							echo $this->grouping("prod",$ticket->product,$ticket);
							$grp_prod = $ticket->prod_id;
							$grp_dept = -1;
							$grp_cat = -1;
							$grp_group = -1;
						}
					} 
					if (SupportUsers::getSetting("group_departments")) {
						if ($ticket->ticket_dept_id != $grp_dept)
						{
							if ($grp_open == 1)
								echo "</table></div>";
							$grp_open = 0;
							
							echo $this->grouping("dept",$ticket->department,$ticket);
							$grp_dept = $ticket->ticket_dept_id;
							$grp_cat = -1;
							$grp_group = -1;
						}
					} 
					if (SupportUsers::getSetting("group_cats")) {
						if ($ticket->ticket_cat_id != $grp_cat)
						{
							if ($grp_open == 1)
								echo "</table></div>";
							$grp_open = 0;
							
							echo $this->grouping("cat",$ticket->category,$ticket);
							$grp_cat = $ticket->ticket_cat_id;
							$grp_group = -1;
						}
					} 
					if (SupportUsers::getSetting("group_group")) {
						if ($ticket->group_id != $grp_group)
						{
							if ($grp_open == 1)
								echo "</table></div>";
							$grp_open = 0;
							
							echo $this->grouping("group",$ticket->groupname,0);
							$grp_group = $ticket->group_id;
						}
					} 
					if (SupportUsers::getSetting("group_pri")) {
						if ($ticket->ticket_pri_id != $grp_pri)
						{
							if ($grp_open == 1)
								echo "</table></div>";
							$grp_open = 0;
							
							echo $this->grouping("pri",$ticket->priority,0);
							$grp_pri = $ticket->ticket_pri_id;
						}
					} 
					
					if ($grp_open == 0)
					{
						?>
							<div <?php echo $tab_style; ?>><table class='table table-bordered table-ticketborders table-condensed' style="border-top: none;">
						<?php
						$grp_open = 1;
					}
					
						?>
				<?php $this->listRow($ticket); ?>
			<?php endforeach; ?>

</table></div>

<?php
$curstatus = FSS_Input::getCmd('tickets');
if ($curstatus == -1)
	$curstatus = FSS_Input::getCmd('status');
?>

<div id="batch_form" class="form-horizontal form-condensed" style='display: none'>

<div class="batch_print_hide">
	<?php echo FSS_Helper::PageSubTitle("BATCH_ACTIONS"); ?>
</div>
<div class="batch_action_hide">
	<?php echo FSS_Helper::PageSubTitle("BATCH_PRINT"); ?>
</div>

<div class="control-group">
		<label class="control-label"><?php echo JText::_("SELECT"); ?></label>
		<div class="controls">
			<div class="btn-group">
				<button class="btn btn-default" id="batch_select_all"><?php echo JText::_('ALL'); ?></button>
				<button class="btn btn-default" id="batch_select_none"><?php echo JText::_('FSS_NONE'); ?></button>
				<button class="btn btn-default" id="batch_select_invert"><?php echo JText::_('INVERT'); ?></button>
			</div>
		</div>
	</div>

	<div class="control-group batch_print_hide">
		<label class="control-label"><?php echo JText::_("STATUS"); ?></label>
		<div class="controls">
			<select name="batch_status">
				<option value=""><?php echo JText::_('UNCHANGED'); ?></option>
				<optgroup label="<?php echo JText::_('NEW_STATUS_'); ?>">
					<?php
						$status_list = SupportHelper::getStatuss();
						foreach ($status_list as $status)
						{
							echo "<option value='{$status->id}' style='color: {$status->color};'>{$status->title}</option>";
						}
					?>
						
				</optgroup>
					
				<?php if (FSS_Settings::get('support_delete')): ?>
					<optgroup label="<?php echo JText::_('DELETE_TICKET'); ?>:">
						<option value='delete' style='color: red;'><?php echo JText::_('DELETE_TICKET'); ?></option>
					</optgroup>
				<?php endif; ?>
			</select>
		</div>
	</div>

	<div class="control-group batch_print_hide">
		<label class="control-label"><?php echo JText::_("PRIORITY"); ?></label>
		<div class="controls">
			<select name="batch_priority">
				<option value=""><?php echo JText::_('UNCHANGED'); ?></option>
				<optgroup label="<?php echo JText::_('NEW_PRIORITY_'); ?>">
					<?php
					$pri_list = SupportHelper::getPriorities();
						foreach ($pri_list as $pri)
						{
							echo "<option value='{$pri->id}' style='color: {$pri->color};'>{$pri->title}</option>";
						}
					?>	
				</optgroup>
			</select>
		</div>
	</div>
	
	<?php
		/*if (array_key_exists('REQUEST_URI',$_SERVER))
		{
			$url = $_SERVER['REQUEST_URI'];//JURI::current() . "?" . $_SERVER['QUERY_STRING'];
		} else {
			$option = FSS_Input::getCmd('option');
			$view = FSS_Input::getCmd('view');
			$layout = FSS_Input::getCmd('layout');
			$Itemid = FSS_Input::getInt('Itemid');
			$url = FSSRoute::_("index.php?option=" . $option . "&view=" . $view . "&layout=" . $layout . "&Itemid=" . $Itemid); 	
		}*/
		$url = FSS_Helper::getCurrentURL();
	?>

	<input type="hidden" name="return" value="<?php echo $url; ?>" />

	<div class="control-group batch_print_hide">
		<label class="control-label"><?php echo JText::_("HANDLER"); ?></label>
		<div class="controls">
			<select name="batch_handler">
				<option value=""><?php echo JText::_('UNCHANGED'); ?></option>
				<optgroup label="<?php echo JText::_('NEW_HANDLER_'); ?>">
					<option value="0"><?php echo JText::_('UNASSIGNED__TICKET_HEADER'); ?></option>
					<?php
						$handlers = SupportUsers::getHandlers(false, true);
						foreach ($handlers as $handler)
						{
							echo "<option value='{$handler->id}'>{$handler->name} ({$handler->username})</option>";
						}
					?>	
				</optgroup>
			</select>
		</div>
	</div>

	<!--<div class="control-group">
		<label class="control-label"><?php echo JText::_("Send EMails"); ?></label>
		<div class="controls">
			<input type="checkbox" checked />
		</div>
	</div>-->
	
	<div class="control-group">
		<label class="control-label"></label>
		<div class="controls">
			<a href="#" class="btn btn-default batch_print_hide" onclick="processBatch();return false;"><?php echo JText::_('PROCESS'); ?></a>
			
			<div class="btn-group batch_action_hide">
				<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="icon-print"></i>
					<?php echo JText::_("Print"); ?>
					<span class="caret" style="margin-bottom: 4px;"></span>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=multiprint&print=all&tmpl=component'); ?>' target='_new' onclick='batch_print(this);'>
							<?php echo JText::_('ALL_DETAILS'); ?>
						</a>
					</li>
					<li>
						<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=multiprint&print=clean&tmpl=component'); ?>' target='_new' onclick='batch_print(this);'>
							<?php echo JText::_('NO_PRIVATE_MESSAGES'); ?>
						</a>
					</li>
					<?php $prints = Support_Print::getPrintList(true, null, true); ?>			
					<?php foreach ($prints as $name => $title): ?>
					<li>
						<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=multiprint&print=' . $name . '&tmpl=component'); ?>' target='_new' onclick='batch_print(this);'>
							<?php echo JText::_($title); ?>
						</a>
					</li>			
					<?php endforeach; ?>
					
					
				</ul>
			</div>
		</div>
	</div>

	<input name="task" value="" type="hidden" id="batch" />
</div>

<?php if (!empty($this->show_key) && $this->show_key): ?>
	<div class='ticket_key'>
	
		<div class="btn-group fssTip" title="<?php echo JText::_('VIEW_MY_TICKETS'); ?>" style="display: inline-block;">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				<span class="label label-success">
					<?php echo JText::_('MY_TICKETS'); ?>
					<span class="caret" style="position: relative;top: 5px;"></span>
				</span>
			</a>
			<ul class="dropdown-menu">
				<li>
					<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&tickets=-1&what=search&searchtype=advanced&showbasic=1&handler=-1&status=' . $curstatus); ?>'><?php echo JText::_('MY_TICKETS'); ?></a>
				</li>
				<li>
					<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&tickets=-1&what=search&searchtype=advanced&showbasic=1&handler=-4&status=' . $curstatus); ?>'><?php echo JText::_('MY_CC_TICKETS'); ?></a>
				</li>
				<li>
					<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&tickets=-1&what=search&searchtype=advanced&showbasic=1&handler=-5&status=' . $curstatus); ?>'><?php echo JText::_('MY_ASSIGNED_TICKETS'); ?></a>
				</li>
			</ul>
		</div>
		&nbsp;
		<div class="btn-group fssTip" title="<?php echo JText::_('VIEW_OTHER_HANDLERS_TICKETS'); ?>" style="display: inline-block;">
			<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&tickets=-1&what=search&searchtype=advanced&showbasic=1&handler=-2&status=' . $curstatus); ?>'><span class="label label-info" style="cursor: pointer"><?php echo JText::_('OTHER_HANDLERS_TICKETS'); ?></span></a>
		</div>
		&nbsp;
		<div class="btn-group fssTip" title="<?php echo JText::_('VIEW_UNASSIGNED_TICKETS'); ?>" style="display: inline-block;">
			<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&tickets=-1&what=search&searchtype=advanced&showbasic=1&handler=-3&status=' . $curstatus); ?>'><span class="label label-warning" style="cursor: pointer"><?php echo JText::_('UNASSIGNED'); ?></span></a>
		</div>
	</div>
<?php endif; ?>

<?php endif; ?>