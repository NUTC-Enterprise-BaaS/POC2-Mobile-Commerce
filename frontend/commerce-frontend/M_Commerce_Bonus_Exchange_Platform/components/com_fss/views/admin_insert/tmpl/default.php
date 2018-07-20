<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<form id="fssForm" name="fssForm" action="<?php echo JRoute::_('index.php?option=com_fss&view=admin_insert&tmpl=component&type=' . FSS_Input::getCmd('type') . "&editor=" . FSS_Input::getCmd('editor')); ?>" method='post'>
<?php echo FSS_Helper::PageStylePopup(true); ?>
<?php echo FSS_Helper::PageTitlePopup(JText::_($this->addbtntext)); ?>

<?php $this->OutputTable(); ?>

</div>

<div class="modal-footer">
	<a href='#' class="btn btn-default" onclick='parent.fss_modal_hide(); return false;'><?php echo JText::_('CANCEL'); ?></a>
</div>
</form>