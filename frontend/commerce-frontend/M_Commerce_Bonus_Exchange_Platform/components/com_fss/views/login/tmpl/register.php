<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>
<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("FSS_REGISTER"); ?>

<div class="<?php echo $this->pageclass_sfx?>">


	<form id="member-registration" action="<?php echo JRoute::_('index.php?option=com_fss&view=login&layout=doregister'); ?>" method="post" class="form-validate form-horizontal form-condensed" enctype="multipart/form-data">
		<?php foreach ($this->form->getFieldsets() as $fieldset): // Iterate through the form fieldsets and display each one.?>
			<?php $fields = $this->form->getFieldset($fieldset->name);?>
			<?php if (count($fields)):?>
				<?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.?>
					<?php echo FSS_Helper::PageSubTitle(JText::_($fieldset->label)); ?>
				<?php endif;?>
				<?php foreach ($fields as $field) :// Iterate through the fields in the set and display them.?>
					<?php if ($field->hidden):// If the field is hidden, just display the input.?>
						<?php echo $field->input;?>
					<?php else:?>
						<div class="control-group">
							<div class="control-label">
							<?php echo $field->label; ?>
							<?php if (!$field->required && $field->type != 'Spacer') : ?>
								<span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL');?></span>
							<?php endif; ?>
							</div>
							<div class="controls">
								<?php echo $field->input;?>
							</div>
						</div>
					<?php endif;?>
				<?php endforeach;?>
			<?php endif;?>
		<?php endforeach;?>
		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn btn-primary validate"><?php echo JText::_('JREGISTER');?></button>
				<a class="btn" href="<?php echo JRoute::_('');?>" title="<?php echo JText::_('JCANCEL');?>"><?php echo JText::_('JCANCEL');?></a>
				<input type="hidden" name="option" value="com_fss" />
				<input type="hidden" name="view" value="login" />
				<input type="hidden" name="layout" value="doregister" />
				<input type="hidden" name="return" value="<?php echo JRequest::getVar('return'); ?>" />
			</div>
		</div>
		<?php echo JHtml::_('form.token');?>
	</form>
</div>
<?php echo FSS_Helper::PageStyleEnd(); ?>