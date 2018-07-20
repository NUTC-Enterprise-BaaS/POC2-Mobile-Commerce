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
			<legend><?php echo JText::_("FAQ_SETTINGS"); ?></legend>

			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("FAQS_POPUP_WIDTH"); ?>:
					
					</td>
					<td style="width:250px;">
						<input name='faq_popup_width' type="text" value='<?php echo $this->settings['faq_popup_width'] ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_faq_popup_width'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("FAQS_POPUP_HEIGHT"); ?>:
					
					</td>
					<td>
						<input name='faq_popup_height' type="text" value='<?php echo $this->settings['faq_popup_height'] ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_faq_popup_height'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("FAQ_USE_CONTENT_PLUGINS"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='faq_use_content_plugins' value='1' <?php if ($this->settings['faq_use_content_plugins'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_faq_use_content_plugins'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("FAQ_USE_CONTENT_PLUGINS_LIST"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='faq_use_content_plugins_list' value='1' <?php if ($this->settings['faq_use_content_plugins_list'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_faq_use_content_plugins_list'); ?><div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("FAQS_PER_PAGE"); ?>:
					</td>
					<td>
						<?php $this->PerPage('faq_per_page'); ?>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_faq_per_page'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("MULTI_COLUMN_RESPONSIVE_LAYOUT"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='faq_multi_col_responsive' value='1' <?php if ($this->settings['faq_multi_col_responsive'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('EXPERIMENTAL_RESPONSIVE_LAYOUT_AND_STYLES_FOR_MULTI_COLUMN_CATEGORY_LISTING'); ?><div>
					</td>
				</tr>		
			</table>
		</fieldset>