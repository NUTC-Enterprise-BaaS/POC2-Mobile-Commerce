<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class="fss_main">
	<form id="mainform" action="<?php echo JRoute::_("index.php"); ?>" method="post" class="form-horizontal form-condensed">

		<?php echo FSS_Helper::PageStylePopup(true); ?>
		<?php echo FSS_Helper::PageTitlePopup('SUPPORT_ADMIN',$this->sig_item->id > 0 ? "EDIT_SIGNATURE" : "NEW_SIGNATURE"); ?>

			<input type="hidden" name="option" value="com_fss" />
			<input type="hidden" name="view" value="admin_support" />
			<input type="hidden" name="layout" value="signature" />
			<input type="hidden" name="tmpl" value="component" />
			<input type="hidden" name="task" value="signature.save" />
			<input type="hidden" name="saveid" value="<?php echo $this->sig_item->id; ?>" />
		
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('DESCRIPTION'); ?></label>
				<div class="controls">
					<input type="text" name="description" value="<?php echo FSS_Helper::escape($this->sig_item->description); ?>" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('PERSONAL'); ?></label>
				<div class="controls">
					<input type="checkbox" name="personal" value="1" <?php if ($this->sig_item->personal) echo "checked"; ?> />
				</div>
			</div>
			<p>
				<textarea style='width:97%; height:270px;' name='content' id='content' class="sceditor" rows='7' cols='40'><?php echo FSS_Helper::escape($this->sig_item->content); ?></textarea>
			</p>

		</div>

		<div class="modal-footer">
			<button class="btn btn-primary" onclick="jQuery('#mainform').submit(); return false;"><?php echo JText::_('SAVE'); ?></button>
			<a class="btn btn-default" href="<?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&tmpl=component&layout=signature" ); ?>"><?php echo JText::_('CANCEL'); ?></a>
		</div>
	</form>
</div>