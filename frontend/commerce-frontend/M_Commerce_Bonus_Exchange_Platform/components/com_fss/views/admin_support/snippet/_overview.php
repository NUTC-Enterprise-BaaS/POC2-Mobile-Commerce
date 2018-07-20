<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'translate.php'); 
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_tickets.php'); 
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_source.php'); 
?>

<?php echo FSS_Helper::PageSubTitle("<a href='".FSSRoute::_( 'index.php?option=com_fss&view=admin_support' )."'><img src='". JURI::root( true ) ."/components/com_fss/assets/images/support/support_24.png'>&nbsp;" . JText::_("SUPPORT_TICKETS"). "</a>",false); ?>

<ul>
	<?php
	FSS_Ticket_Helper::GetStatusList();
	$counts = SupportTickets::getTicketCount();
	FSS_Translate_Helper::Tr(FSS_Ticket_Helper::$status_list);
	foreach (FSS_Ticket_Helper::$status_list as $status)
	{
		if ($status->def_archive) continue;
		if ($status->is_closed) continue;
		if (!array_key_exists($status->id, $counts)) continue;
		if ($counts[$status->id] < 1) continue;
		echo "<li>" . $status->title . ": <b>" . $counts[$status->id] . "</b> - <a href='".FSSRoute::_( 'index.php?option=com_fss&view=admin_support&tickets=' . $status->id ) . "'>" . JText::_("VIEW_NOW") . "</a></li>";	
	}
	
	foreach (SupportSource::getOverview_ListItems() as $item)
	{
		echo "<li>" . $item->name . ": <b>" . $item->count . "</b> - <a href='".FSSRoute::_( $item->link ) . "'>" . JText::_("VIEW_NOW") . "</a></li>";	
	}
	?>

</ul>

<?php if (!FSS_Settings::get('support_no_admin_for_user_open')): ?>
 
<div class="form-horizontal form-condensed">
	<div class="control-group">
		<label class="control-label"><?php echo JText::_("CREATE_TICKET_FOR"); ?></label>
		<div class="controls">
			<a class="btn btn-default btn-small" href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=new&type=registered' ); ?>"><?php echo JText::_("REGISTERED_USER"); ?></a>
			<?php if (FSS_settings::get('support_allow_unreg')): ?>
				<a class="btn btn-default btn-small" href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=new&type=unregistered' ); ?>"><?php echo JText::_("UNREGISTERED_USER"); ?></a>	
			<?php endif; ?>
			<div style="line-height: 25px;display: inline-block;">&nbsp;</div>
		</div>
	</div>
</div>

<?php endif; ?>

<?php foreach (SupportSource::getOverview_Appends() as $html): ?>

<?php endforeach; ?>
	   	 			 		  