<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
FSS_Helper::ModuleStart("mod_fss_support mod_fss_support_user");
?>
<ul>
	<?php
	//echo JText::sprintf("TICKET_STATUS",$this->ticketopen,$this->ticketfollow,$this->ticketuser,FSSRoute::_( '&layout=support' )); 
	FSS_Ticket_Helper::GetStatusList();
	$counts = SupportHelper::getUserTicketCount();

	FSS_Translate_Helper::Tr(FSS_Ticket_Helper::$status_list);
	$output = 0;
	foreach (FSS_Ticket_Helper::$status_list as $status)
	{
		if ($status->def_archive) continue;
		if ($status->is_closed) continue;
		if (!array_key_exists($status->id, $counts)) continue;
		if ($counts[$status->id] < 1) continue;
		$output++;
	?>
		<li>
			<a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=support&tickets=' . $status->id ); ?>">
				<?php echo FSS_Translate_Helper::TrF("userdisp", $status->title, $status->translation) . " (" . $counts[$status->id] . ")"; ?>
			</a>
		</li>
	<?php
	}
	?>
	<?php if ($output == 0): ?>
		<li>
			<?php echo JText::_('YOU_CURRENTLY_HAVE_NO_SUPPORT_TICKETS'); ?>
		</li>
	<?php endif; ?>
</ul>

<?php if ($tickets_open_ticket): ?>
	<div class="center">
		<a class="btn btn-default" href="<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&layout=open'); ?>"><?php echo JText::_('OPEN_NEW_TICKET'); ?></a>
	</div>
<?php endif; ?>

<?php FSS_Helper::ModuleEnd(); ?>