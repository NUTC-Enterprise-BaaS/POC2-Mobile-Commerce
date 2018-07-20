<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
FSS_Helper::ModuleStart("mod_fss_support mod_fss_support_admin");
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_tickets.php');
?> 

<?php if (FSS_Permission::auth("fss.handler", "com_fss.support_admin") && !$params->get('hide_tickets')): ?>
	<?php if (FSS_Permission::CanModerate() && !$params->get('hide_moderate')): ?>
		<h4><?php echo JText::_("SUPPORT_TICKETS"); ?></h4>
	<?php endif; ?>
	
	<ul>	
		<?php

		FSS_Ticket_Helper::GetStatusList();
		$counts = SupportTickets::getTicketCount(true, $tickets_user_only);
		FSS_Translate_Helper::Tr(FSS_Ticket_Helper::$status_list);
		foreach (FSS_Ticket_Helper::$status_list as $status)
		{
			//if (!$tickets_archived_status && $status->def_archive) continue;
			if (!$tickets_closed_status && $status->is_closed) continue;
			if (!array_key_exists($status->id, $counts)) continue;
			if ($counts[$status->id] < 1) continue;
		?>
			<li class="fss_module_support_item">
				<a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&tickets=' . $status->id ); ?>">
					<?php echo $status->title . " (" . $counts[$status->id] . ")"; ?>
				</a>
			</li>
		<?php
		}
			
		foreach (SupportSource::getMainMenu_Module_Admin_ListItems() as $item)
		{
		?>
			<li class="fss_module_support_item">
				<a href="<?php echo FSSRoute::_( $item->link ); ?>">
					<?php echo $item->name . " (" . $item->count . ")"; ?>
				</a>
			</li>
		<?php
		}
		?>
	</ul>

	<?php if ($tickets_show_my_tickets): ?>
		<div class="center btn-group fssTip" title="View my tickets" style="display:block;">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				<span class="label label-success">
					<?php echo JText::_('MY_TICKETS'); ?>
					<span class="caret" style="position: relative;top: 5px;"></span>
				</span>
			</a>
			<ul class="dropdown-menu">
				<li>
					<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&tickets=-1&what=search&searchtype=advanced&showbasic=1&handler=-1&status=allopen'); ?>'><?php echo JText::_('MY_TICKETS'); ?></a>
				</li>
				<li>
					<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&tickets=-1&what=search&searchtype=advanced&showbasic=1&handler=-4&status=allopen'); ?>'><?php echo JText::_('MY_CC_TICKETS'); ?></a>
				</li>
				<li>
					<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&tickets=-1&what=search&searchtype=advanced&showbasic=1&handler=-5&status=allopen'); ?>'><?php echo JText::_('MY_ASSIGNED_TICKETS'); ?></a>
				</li>
			</ul>
		</div>
	<?php endif; ?>

	<?php if ($params->get('tickets_open_ticket')): ?>
	<div class="center margin-small">
			<a class="btn btn-default" href="<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&layout=open'); ?>"><?php echo JText::_('OPEN_NEW_TICKET'); ?></a>
		</div>
	<?php endif; ?>

	<?php if (($params->get('tickets_open_ticket_reg') || $params->get('tickets_open_ticket_unreg')) && !FSS_Settings::get('support_no_admin_for_user_open')): ?>
		<div>
			<?php echo JText::_("CREATE_TICKET_FOR"); ?>:
		</div>

		<div>
			<?php if ($params->get('tickets_open_ticket_reg')): ?>
				<a class="btn btn-default btn-small margin-small" href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=new&type=registered' ); ?>"><?php echo JText::_("REGISTERED_USER"); ?></a>
			<?php endif; ?>
			<?php if ($params->get('tickets_open_ticket_unreg')): ?>
				<a class="btn btn-default btn-small margin-small" href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=new&type=unregistered' ); ?>"><?php echo JText::_("UNREGISTERED_USER"); ?></a>	
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php endif; ?>

<?php if (FSS_Permission::CanModerate() && !$params->get('hide_moderate')): ?>
	<?php if (FSS_Permission::auth("fss.handler", "com_fss.support_admin") && !$params->get('hide_tickets')): ?>
		<h4><?php echo  JText::_("MODERATE"); ?></h4>
	<?php endif; ?>
	
	<ul>
		<?php $comments->DisplayModStatus("modstatus_module.php"); ?>
	</ul>
	
<?php endif; ?>

<?php FSS_Helper::ModuleEnd(); ?>