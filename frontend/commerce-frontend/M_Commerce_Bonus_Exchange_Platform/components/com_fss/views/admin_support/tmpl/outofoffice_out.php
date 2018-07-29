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

<?php echo FSS_Helper::PageTitle('SUPPORT_ADMIN',"OUT_OF_OFFICE"); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php'); ?>

<?php if ($this->userid != $this->current_userid): ?>
	<div class="alert alert-warning">
		<h4><?php echo JText::_('CHANGING_OUT_OF_OFFICE_FOR_USER'); ?> <?php echo $this->user->username; ?> - <?php echo $this->user->name; ?></h4>
	</div>
<?php endif; ?>

<form id="out_form" name="out_form" action="<?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&layout=outofoffice&task=outofoffice.in"); ?>" method="post" class="form-horizontal form-condensed">
	<input type="hidden" name="option" value="com_fss" />
	<input type="hidden" name="view" value="admin_support" />
	<input type="hidden" name="task" value="outofoffice.in" />
	<input type="hidden" name="layout" value="outofoffice" />
	<input type="hidden" name="user_id" value="<?php echo (int)$this->userid; ?>">
	
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('CURRENT_STATUS'); ?></label>
		<div class="controls" style="line-height: 26px;">
			<span class="label label-important"><?php echo JText::_('UNAVAILABLE'); ?></span>
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<a href='#' id="submit_button" class='btn btn-primary' onclick='jQuery("#out_form").submit(); return false;'><?php echo JText::_('SET_AS_AVAILABLE'); ?></a>
			<a href="<?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&layout=listhandlers", false); ?>" class='btn btn-default'><?php echo JText::_('CANCEL'); ?></a>
		</div>
	</div>

</form>

<div id="ooo_tickets">
	<?php echo FSS_Helper::PageSubTitle('CURRENT_TICKETS'); ?>
	
	<?php $this->displayTickets(); ?>
</div>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>

<script>

jQuery(document).ready(function () {
	jQuery('#init_button').click( function (ev) {
		ev.preventDefault();
		
		jQuery('#init_button').hide();
		jQuery('#ooo_form').removeClass('hide');
		jQuery('#ooo_tickets').removeClass('hide');
		jQuery('#out_form textarea[name="body"]').addClass('sceditor');
		
		init_sceditor();
	});
	
	jQuery('#assign').change(function () {
		fss_sort_View();
	});
	
	fss_sort_View();
});

function fss_sort_View()
{
	var val = jQuery('#assign').val();
	
	if (val == "none")
	{
		jQuery('#cg_handler').hide();
		jQuery('#cg_message').hide();		
	} else if (val == "auto")
	{
		jQuery('#cg_handler').hide();
		jQuery('#cg_message').show();
	} else if (val == "handler")
	{
		jQuery('#cg_handler').show();
		jQuery('#cg_message').show();	
	}
}
</script>
	 			 										