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

<form id="out_form" name="out_form" action="<?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&layout=outofoffice&task=outofoffice.out"); ?>" method="post" class="form-horizontal form-condensed">

	<input type="hidden" name="option" value="com_fss" />
	<input type="hidden" name="view" value="admin_support" />
	<input type="hidden" name="task" value="outofoffice.out" />
	<input type="hidden" name="layout" value="outofoffice" />
	<input type="hidden" name="user_id" value="<?php echo (int)$this->userid; ?>">
	
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('CURRENT_STATUS'); ?></label>
		<div class="controls" style="line-height: 26px;">
			<span class="label label-success"><?php echo JText::_('AVAILABLE'); ?></span>
		</div>
	</div>
	
	<div class="control-group" id="button_panel">
		<div class="controls">
			<a href='#' class='btn btn-primary' id="set_unavaiable"><?php echo JText::_('SET_AS_UNAVAILABLE'); ?></a>
			<a href="<?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&layout=listhandlers", false); ?>" class='btn btn-default'><?php echo JText::_('CANCEL'); ?></a>
		</div>
	</div>	
	
	<div class="hide" id="ooo_form">

		<div class="control-group">
			<label class="control-label"><?php echo JText::_('CURRENT_TICKETS'); ?></label>
			<div class="controls">
				<select name="assign" id="assign">
					<option value='none'><?php echo JText::_('DONT_REASSIGN'); ?></option>
					<option value='auto' selected><?php echo JText::_('AUTO_REASSIGN'); ?></option>
					<option value='handler'><?php echo JText::_('REASSIGN_TO_HANDLER'); ?></option>
				</select>
			</div>
		</div>
		<div class="control-group" id="cg_handler">
			<label class="control-label"><?php echo JText::_('HANDLER'); ?></label>
			<div class="controls">
				<select name="handler" id="handler">
					<?php foreach ($this->handlers as $handler): ?>
						<?php if ($handler->id == $this->userid) continue; ?>
						<option value="<?php echo $handler->id; ?>"><?php echo $handler->username; ?> - <?php echo $handler->name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="control-group" id="cg_message">
			<label class="control-label"><?php echo JText::_('MESSAGE_TO_HANDLERS'); ?></label>
			<div class="controls">
				<textarea style='width:95%;height:<?php echo (int)((FSS_Settings::get('support_admin_reply_height') * 15) + 80); ?>px' name='body' id='body' rows='<?php echo (int)FSS_Settings::get('support_admin_reply_height'); ?>' cols='<?php echo (int)FSS_Settings::get('support_admin_reply_width'); ?>'></textarea>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<a href='#' id="submit_button" class='btn btn-primary' onclick='jQuery("#out_form").submit(); return false;'><?php echo JText::_('SET_AS_UNAVAILABLE'); ?></a>
				<a href="<?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&layout=listhandlers", false); ?>" class='btn btn-default'><?php echo JText::_('CANCEL'); ?></a>
			</div>
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
	jQuery('#set_unavaiable').click( function (ev) {
		ev.preventDefault();
		
		jQuery('#button_panel').hide();
		jQuery('#ooo_form').show();
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
