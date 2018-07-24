<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

?>

<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle('SUPPORT_ADMIN',"CURRENT_SUPPORT_TICKETS"); ?>

<form id='fssForm' action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&tickets=' . $this->ticket_view ); ?>" name="fssForm" method="post">

	<?php if (JRequest::getVar('searchtype') != ""): ?>
		<div id="fss_showing_search" class="hide" style="display: none;"></div>
	<?php endif; ?>

	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php'); ?>

	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_supportbar.php'); ?>

	<?php if ($this->merge): ?>
		<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_merge_notice.php'); ?>
	<?php endif; ?>

	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_search.php'); ?>

	<?php
	$def_archive = FSS_Ticket_Helper::GetStatusID('def_archive');
	$closed = FSS_Ticket_Helper::GetClosedStatus();
	if (array_key_exists($this->ticket_view, $closed) || $this->ticket_view == "closed"): ?>
		<p>
			<a class="btn btn-default btn-small" href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&task=archive.archive&tickets=' . $this->ticket_view ); ?>" onclick="return confirm('<?php echo JText::_('ARCHIVE_CONFIRM'); ?>');">
				<?php echo JText::_("ARCHIVE_ALL_CLOSED_TICKETS"); ?>
			</a>
			<?php if (FSS_Settings::get('support_delete')): ?>
				<a class="btn btn-default btn-small" href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&task=archive.delete&tickets=' . $this->ticket_view ); ?>" onclick="return confirm('<?php echo JText::_('DELETE_ALL_CONFIRM'); ?>');">
					<?php echo JText::_("DELETE_ALL_CLOSED_TICKETS"); ?>
				</a>
			<?php endif; ?>
		</p>
	<?php elseif ($this->ticket_view == $def_archive): ?>
		<?php if (FSS_Settings::get('support_delete')): ?>
			<p>
				<a class="btn btn-default btn-small" href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&task=archive.delete&tickets=' . $this->ticket_view ); ?>" onclick="return confirm('<?php echo JText::_('DELETE_ALL_ARCHIVED_CONFIRM'); ?>');">
					<?php echo JText::_("DELETE_ALL_ARCHIVED_TICKETS"); ?>
				</a>
			</p>
		<?php endif; ?>
	<?php endif; ?>

	<div id="fss_ticket_list">
		<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_ticket_list.php'); ?>
	</div>

	<?php if (count($this->tickets)) : ?>
		<?php echo $this->pagination->getListFooter(); ?>
	<?php endif; ?>

</form>

<script>

<?php if (FSS_Input::getInt('batch') == '1'): ?>
toggleBatch();
<?php endif; ?>


<?php if ($this->do_refresh): ?>
jQuery(document).ready( function () {
	setInterval("fss_refresh_tickets()", <?php echo $this->do_refresh * 1000; ?> );
});
<?php endif; ?>

// DO NOT DELETE THESE!
function cannedRefresh()
{
		
}
// DO NOT DELETE THESE!
function sigsRefresh()
{
		
}
// DO NOT DELETE THESE!

</script>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>
