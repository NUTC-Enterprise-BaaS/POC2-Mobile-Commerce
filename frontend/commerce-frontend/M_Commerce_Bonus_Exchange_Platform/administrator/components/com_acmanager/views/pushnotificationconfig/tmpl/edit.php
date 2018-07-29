<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Acmanager
 * @author     Parth Lawate <contact@techjoomla.com>
 * @copyright  Copyright (C) 2016. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JPATH_ROOT . 'media/com_acmanager/css/edit.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	});

	Joomla.submitbutton = function (task) {
		if (task == 'pushnotificationconfig.cancel') {
			Joomla.submitform(task, document.getElementById('pushnotificationconfig-form'));
		}
		else {
			
			if (task != 'pushnotificationconfig.cancel' && document.formvalidator.isValid(document.id('pushnotificationconfig-form'))) {
				
				Joomla.submitform(task, document.getElementById('pushnotificationconfig-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_acmanager&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="pushnotificationconfig-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_ACMANAGER_TITLE_PUSHNOTIFICATIONCONFIG', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">

									<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('type'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('type'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('active'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('active'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('is_prod'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('is_prod'); ?></div>
			</div>
<!--		<div class="control-group">
				<div class="control-label"><?php //echo $this->form->getLabel('server_key'); ?></div>
				<div class="controls"><?php //echo $this->form->getInput('server_key'); ?></div>
			</div>
-->
<!--	<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('params'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('params'); ?></div>
			</div>
-->
				<div id="server_config">
					<?php echo $this->form->getControlGroups('server_config'); ?>
				</div>

				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>
