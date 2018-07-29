<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php 
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'translate.php'); 
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_tickets.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_source.php'); 
?>

<?php if (FSS_Permission::auth("fss.handler", "com_fss.support_admin") && FSS_Settings::Get('mainmenu_support')): ?>
	
	<div class="<?php if ($this->info_well): ?>well well-mini<?php else: ?>margin-medium<?php endif; ?> fss_mainmenu_support_panel" id="main_menu_support_box">
		<table class="table-borderless">
			<tr>
				<td valign="middle" width="52" style="vertical-align: middle;" class="hidden-phone">
					<img class="fss_menu_support_image" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/support/support_48.png'>
				</td>
				<td valign="middle" width="200" style="vertical-align: middle;" class="hidden-phone">
					<h3 class="margin-none">
						<?php echo  JText::_("SUPPORT_TICKETS"); ?>
					</h3>
				</td>
				<td valign="middle" style="vertical-align: middle;">
					<div class="visible-phone">
						<h3 class="margin-none">
							<img class="fss_menu_support_image" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/support/support_48.png' width='24' height='24'>
							<?php echo  JText::_("SUPPORT_TICKETS"); ?>
						</h3>
					</div>
					<p>
						<?php
						FSS_Ticket_Helper::GetStatusList();
						$counts = SupportTickets::getTicketCount();
		
						$displayed = 0;
					
						FSS_Translate_Helper::Tr(FSS_Ticket_Helper::$status_list);
						foreach (FSS_Ticket_Helper::$status_list as $status)
						{
							if ($status->def_archive) continue;
							if ($status->is_closed) continue;
							if (!array_key_exists($status->id, $counts)) continue;
							if ($counts[$status->id] < 1) continue;
						
							$displayed++;
						?>
							<h4 class="margin-mini">
								<a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&tickets=' . $status->id ); ?>">
									<?php echo $status->title; ?> (<?php echo $counts[$status->id]?>)
								</a>
							</h4>
						<?php
						}
						
						foreach (SupportSource::getMainMenu_ListItems() as $item)
						{
						?>
							<h4 class="margin-mini">
								<a href="<?php echo FSSRoute::_( $item->link ); ?>">
									<?php echo $item->name; ?> (<?php echo $item->count;?>)
								</a>
							</h4>
						<?php
						}
						?>
						
						<?php if ($displayed == 0): ?>
							<h4 class="margin-mini">
								<?php echo JText::_('THERE_ARE_NO_OPEN_SUPPORT_TICKETS'); ?>
							</h4>
						<?php endif; ?>

					</p>
				</td>
			</tr>
		</table>
	</div>
<?php endif; ?>
	
<?php if (FSS_Permission::CanModerate() && FSS_Settings::Get('mainmenu_moderate')): ?>

<div class="<?php if ($this->info_well): ?>well well-mini<?php else: ?>margin-medium<?php endif; ?> fss_mainmenu_moderate_panel">
	<table class="table-borderless">
		<tr>
			<td valign="middle" width="52" style="vertical-align: middle;" class="hidden-phone">
				<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/support/moderate_48.png'>
			</td>
			<td valign="middle" width="200" style="vertical-align: middle;" class="hidden-phone">
				<h3 class="margin-none">	
					<?php echo  JText::_("MODERATE"); ?>
				</h3>
			</td>
			<td valign="middle" style="vertical-align: middle;">
					<div class="visible-phone">
						<h3 class="margin-none">
							<img class="fss_menu_support_image" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/support/moderate_48.png' width='24' height='24'>
							<?php echo  JText::_("MODERATE"); ?>
						</h3>
					</div>
					<?php $this->comments->DisplayModStatus("modstatus_menu.php"); ?>
			</td>
		</tr>
	</table>
</div>

<?php endif; ?>
