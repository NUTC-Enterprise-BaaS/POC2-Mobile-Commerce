<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.5
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<div class="remind<?php echo $this->pageclass_sfx?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php endif; ?>

	<form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=remind.remind'); ?>" method="post" class="form-validate">

		<?php foreach ($this->form->getFieldsets() as $fieldset): ?>
			<fieldset>
				<div id="login-form-e4j">     
					<div class="login-descr"><?php echo JText::_($fieldset->label); ?></div>      
					<?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field): ?>
		            <div class="option-fields">
		            	<div class="label-field">
		            		<?php echo $field->label; ?>
		            	</div>
		           		<div>
		            		<?php echo $field->input; ?>
		            	</div>
		            </div>
					<?php endforeach; ?>
					<button type="submit" class="validate button"><?php echo JText::_('JSUBMIT'); ?></button>
					<?php echo JHtml::_('form.token'); ?>
				</div>
		</fieldset>
		<?php endforeach; ?>
		
	</form>
</div>
