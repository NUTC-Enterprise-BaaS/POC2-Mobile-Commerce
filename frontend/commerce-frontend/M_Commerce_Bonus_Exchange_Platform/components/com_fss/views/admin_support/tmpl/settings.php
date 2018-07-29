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

<form id='fssForm' action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=settings' );?>" name="fssForm" method="post">
<input type='hidden' name='action' id='action' value='' />
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php'); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_supportbar.php'); ?>

<div class="pull-right">
	<a href="#" id='fss_form_apply' class="btn btn-success"><i class="icon-apply icon-white"></i> <?php echo JText::_('SAVE');?></a> 
	<a href="#" id='fss_form_save' class="btn btn-default"><i class="icon-save"></i> <?php echo JText::_('SAVE_AND_CLOSE');?></a> 
	<a href="#" id='fss_form_cancel' class="btn btn-default"><i class="icon-cancel"></i> <?php echo JText::_('CANCEL');?></a> 
</div>
		
<?php echo  FSS_Helper::PageSubTitle("MY_SETTINGS"); ?>

<div class="form-horizontal form-condensed">
	<h4><?php echo JText::_("TICKET_LIST"); ?></h4>
		
	<div class="control-group">
		<label class="control-label"><?php echo JText::_("DEFAULT_PER_PAGE"); ?></label>
		<div class="controls">
			<input type="text" class="input-mini" name='per_page' value='<?php echo FSS_Helper::escape(SupportUsers::getSetting('per_page')); ?>' size="5">
			<span class="help-inline"><?php echo JText::_('MYSETHELP_PER_PAGE'); ?></span>
		</div>
	</div>

	<h4><?php echo JText::_("TICKET_VIEW"); ?></h4>
	
	<div class="control-group">
		<label class="control-label"><?php echo JText::_("REVERSE_ORDER_MESSAGES"); ?></label>
		<div class="controls">
			<input type='checkbox' name='reverse_order' value='1' <?php if (SupportUsers::getSetting('reverse_order') == 1) { echo " checked='yes' "; } ?>>
			<span class="help-inline"><?php echo JText::_('MYSETHELP_REVERSE_ORDER'); ?></span>
		</div>
	</div>

	<h4><?php echo JText::_("REPLY_SETTINGS"); ?></h4>

	<div class="control-group">
		<label class="control-label"><?php echo JText::_("RETURN_TO_OPEN_ON_REPLY"); ?></label>
		<div class="controls">
			<select name="return_on_reply">
				<?php echo $this->getReturnViewOptions(SupportUsers::getSetting('return_on_reply')); ?>
			</select>
			<span class="help-inline"><?php echo JText::_('MYSETHELP_RETURN_TO_OPEN_ON_REPLY'); ?></span>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label"><?php echo JText::_("RETURN_TO_OPEN_ON_CLOSE"); ?></label>
		<div class="controls">
			<select name="return_on_close">
				<?php echo $this->getReturnViewOptions(SupportUsers::getSetting('return_on_close')); ?>
			</select>
			<span class="help-inline"><?php echo JText::_('MYSETHELP_RETURN_TO_OPEN_ON_CLOSE'); ?></span>
		</div>
	</div>

	<h4><?php echo JText::_("TICKET_LIST_GROUPING"); ?></h4>
	
	<div class="control-group">
		<label class="control-label"><?php echo JText::_("GROUP_BY_PRODUCT"); ?></label>
		<div class="controls">
			<input type='checkbox' name='group_products' value='1' <?php if (SupportUsers::getSetting('group_products') == 1) { echo " checked='yes' "; } ?>>
			<span class="help-inline"><?php echo JText::_('MYSETHELP_GROUP_PRODUCTS'); ?></span>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label"><?php echo JText::_("GROUP_BY_DEPARTMENT"); ?></label>
		<div class="controls">
			<input type='checkbox' name='group_departments' value='1' <?php if (SupportUsers::getSetting('group_departments') == 1) { echo " checked='yes' "; } ?>>
			<span class="help-inline"><?php echo JText::_('MYSETHELP_GROUP_DEPARTMENTS'); ?></span>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label"><?php echo JText::_("GROUP_BY_CATEGORY"); ?></label>
		<div class="controls">
			<input type='checkbox' name='group_cats' value='1' <?php if (SupportUsers::getSetting('group_cats') == 1) { echo " checked='yes' "; } ?>>
			<span class="help-inline"><?php echo JText::_('MYSETHELP_GROUP_CATS'); ?></span>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label"><?php echo JText::_("GROUP_BY_TICKET_GROUP"); ?></label>
		<div class="controls">
			<input type='checkbox' name='group_group' value='1' <?php if (SupportUsers::getSetting('group_group') == 1) { echo " checked='yes' "; } ?>>
			<span class="help-inline"><?php echo JText::_('MYSETHELP_GROUP_GROUP'); ?></span>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label"><?php echo JText::_("GROUP_BY_PRIORITY"); ?></label>
		<div class="controls">
			<input type='checkbox' name='group_pri' value='1' <?php if (SupportUsers::getSetting('group_pri') == 1) { echo " checked='yes' "; } ?>>
			<span class="help-inline"><?php echo JText::_('MYSETHELP_GROUP_PRI'); ?></span>
		</div>
	</div>

	<h4><?php echo JText::_("REPORTS_SETTINGS"); ?></h4>
	
	<div class="control-group">
		<label class="control-label"><?php echo JText::_("CSV_SEPARATOR"); ?></label>
		<div class="controls">
			<select name="reports_separator">
				<option value="" <?php if (SupportUsers::getSetting('reports_separator') == "") echo " SELECTED"; ?> ><?php echo JText::_('DEFAULT'); ?></option>
				<option value="," <?php if (SupportUsers::getSetting('reports_separator') == ",") echo " SELECTED"; ?> >,</option>
				<option value=";" <?php if (SupportUsers::getSetting('reports_separator') == ";") echo " SELECTED"; ?> >;</option>
			</select>
		</div>
	</div>

</div>
</form>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>

<script>

function FormButton(task)
{
	jQuery('#fssForm').find('input[name="action"]').val(task);
	jQuery('#fssForm').submit();
}

jQuery(document).ready(function () {
	jQuery('#fss_form_cancel').click(function (ev) {
		ev.preventDefault();
		FormButton("cancel")
	});
	jQuery('#fss_form_save').click(function (ev) {
		ev.preventDefault();
		FormButton("save")
	});
	jQuery('#fss_form_apply').click(function (ev) {
		ev.preventDefault();
		FormButton("apply")
	});
});
</script>