<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
	<fieldset class="adminform">
		<legend><?php echo JText::_("TEST_WHEN_SHOWING_PRODUCT_LIST"); ?></legend>

		<table class="table table-bordered table-condensed table-striped table-settings">
		
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("test_show_prod_mode"); ?>:
					
				</td>
				<td>
					<select name="test_test_show_prod_mode">
						<option value="list" <?php if ($this->settings['test_test_show_prod_mode'] == 'list') echo " SELECTED"; ?> ><?php echo JText::_('test_show_prod_mode_list'); ?></option>
						<option value="inline" <?php if ($this->settings['test_test_show_prod_mode'] == 'inline') echo " SELECTED"; ?> ><?php echo JText::_('test_show_prod_mode_inline'); ?></option>
						<option value="accordian" <?php if ($this->settings['test_test_show_prod_mode'] == 'accordian') echo " SELECTED"; ?> ><?php echo JText::_('test_show_prod_mode_accordian'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_test_show_prod_mode'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("test_pages"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='test_test_pages' value='1' <?php if ($this->settings['test_test_pages'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_test_pages'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("test_always_prod_select"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='test_test_always_prod_select' value='1' <?php if ($this->settings['test_test_always_prod_select'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_test_always_prod_select'); ?></div>
				</td>
			</tr>

		</table>
	</fieldset>
