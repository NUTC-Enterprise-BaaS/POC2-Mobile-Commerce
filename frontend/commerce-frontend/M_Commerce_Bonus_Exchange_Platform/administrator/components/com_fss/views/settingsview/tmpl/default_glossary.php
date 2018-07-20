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
		<legend><?php echo JText::_("GLOSSARY_VIEW_SETTINGS"); ?></legend>

		<table class="table table-bordered table-condensed table-striped table-settings">
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("glossary_use_letter_bar"); ?>:
				</td>
				<td style="width:250px;">
					<select name="glossary_use_letter_bar">
						<option value="0" <?php if ($this->settings['glossary_use_letter_bar'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('GLOSSARY_USE_LETTER_BAR_0'); ?></option>
						<option value="1" <?php if ($this->settings['glossary_use_letter_bar'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('GLOSSARY_USE_LETTER_BAR_1'); ?></option>
						<option value="2" <?php if ($this->settings['glossary_use_letter_bar'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('GLOSSARY_USE_LETTER_BAR_2'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('view_help_glossary_use_letter_bar'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("Long Description"); ?>:
				</td>
				<td style="width:250px;">
					<select name="glossary_long_desc">
						<option value="0" <?php if ($this->settings['glossary_long_desc'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('Inline'); ?></option>
						<option value="1" <?php if ($this->settings['glossary_long_desc'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('New Page'); ?></option>
						<option value="2" <?php if ($this->settings['glossary_long_desc'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('Read More Toggle'); ?></option>
						<option value="3" <?php if ($this->settings['glossary_long_desc'] == 3) echo " SELECTED"; ?> ><?php echo JText::_('Popup'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('How should the long description be displayed'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("glossary_show_search"); ?>:
				</td>
				<td style="width:250px;">
						<input type='checkbox' name='glossary_show_search' value='1' <?php if ($this->settings['glossary_show_search'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('sethelp_glossary_show_search'); ?></div>
				</td>
			</tr>		</table>
	</fieldset>
