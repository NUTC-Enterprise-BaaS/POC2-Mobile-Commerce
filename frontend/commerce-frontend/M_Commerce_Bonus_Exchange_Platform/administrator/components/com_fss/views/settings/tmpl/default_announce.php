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
			<legend><?php echo JText::_("ANNOUNCEMENTS_SETTINGS"); ?></legend>

			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("ALLOW_ARTICLE_COMMENTS"); ?>:
					</td>
					<td style="width:250px;">
						<select name="announce_comments_allow">
							<option value="0" <?php if ($this->settings['announce_comments_allow'] == "0") echo " SELECTED"; ?> ><?php echo JText::_('Disabled'); ?></option>
							<option value="1" <?php if ($this->settings['announce_comments_allow'] == "1") echo " SELECTED"; ?> ><?php echo JText::_('ENABLED'); ?></option>
							<?php if (file_exists(JPATH_SITE . '/components/com_jcomments/jcomments.php')): ?>
								<option value="2" <?php if ($this->settings['announce_comments_allow'] == "2") echo " SELECTED"; ?> ><?php echo JText::_('JCOMMENTS'); ?></option>
							<?php endif; ?>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_announce_comments_allow'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("ANNOUNCE_USE_CONTENT_PLUGINS"); ?>:
					</td>
					<td>
						<input type='checkbox' name='announce_use_content_plugins' value='1' <?php if ($this->settings['announce_use_content_plugins'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_announce_use_content_plugins'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("ANNOUNCE_USE_CONTENT_PLUGINS_LIST"); ?>:
					</td>
					<td>
						<input type='checkbox' name='announce_use_content_plugins_list' value='1' <?php if ($this->settings['announce_use_content_plugins_list'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_announce_use_content_plugins_list'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("ANNOUNCE_COMMENTS_PER_PAGE"); ?>:
					</td>
					<td>
						<?php $this->PerPage('announce_comments_per_page'); ?>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_announce_comments_per_page'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("ANNOUNCE_PER_PAGE"); ?>:
					</td>
					<td>
						<?php $this->PerPage('announce_per_page'); ?>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_announce_per_page'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>