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
		<legend><?php echo JText::_("ANNOUNCEMENTS_TEMPLATES"); ?></legend>
		<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
				<td align="right" class="key" style="width:150px;">
						<?php echo JText::_("Custom_Template_Announce_List"); ?>:
				</td>
				<td width="450">
					<input type='checkbox' name='announce_use_custom' id='announce_use_custom' value='1' <?php if ($this->settings['announce_use_custom'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td><div class="fss_help"><?php echo JText::_('TMPLHELP_announce_use_custom'); ?></div></td>
			</tr>

			<tr id="announce_row">
				<td valign="top" align="right" class="key" style="width:150px;">
					<?php echo JText::_("Custom_Template"); ?>:<br />
					<button id='announce_reset' class="btn btn-default" style='float:none;'><?php echo JText::_('Reset');?></button><br />
					<span class='fss_custom_warn'>
						<?php echo JText::_('TMPLHELP_WARN1'); ?>
					</span>
				</td>
				<td valign="top" width="450" id="announce_row">
					<textarea name='announce' id="announce" rows="20" cols="80" style="float:none;"><?php echo $this->settings['announce']; ?></textarea><br>
					<textarea id="announce_default" rows="12" cols="80" style="display:none;"><?php echo $this->settings['announce_default']; ?></textarea><br>
				</td>
				<td>
					<div class="fss_help">
						<?php echo FSSAdminHelper::IncludeHelp("announce_list.htm"); ?>
					</div>
				</td>
			</tr>
		
			<tr>
				<td align="right" class="key" style="width:150px;">
						<?php echo JText::_("Use_Custom_Template_Announce_Single"); ?>:
				</td>
				<td width="450">
					<input type='checkbox' name='announcesingle_use_custom' id='announcesingle_use_custom' value='1' <?php if ($this->settings['announcesingle_use_custom'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td><div class="fss_help"><?php echo JText::_('TMPLHELP_announcesingle_use_custom'); ?></div></td>
			</tr>

			<tr id="announcesingle_row">
				<td valign="top" align="right" class="key" style="width:150px;">
					<?php echo JText::_("Custom_Template"); ?>:<br />
					<button id='announcesingle_reset' class="btn btn-default" style='float:none;'><?php echo JText::_('Reset');?></button><br />
					<span class='fss_custom_warn'>
						<?php echo JText::_('TMPLHELP_WARN1'); ?>
					</span>
				</td>
				<td valign="top" width="450" id="announcesingle_row">
					<textarea name='announcesingle' id="announcesingle" rows="12" cols="80" style="float:none;"><?php echo $this->settings['announcesingle']; ?></textarea><br>
					<textarea id="announcesingle_default" rows="12" cols="80" style="display:none;"><?php echo $this->settings['announcesingle_default']; ?></textarea><br>
				</td>
				<td>
					<div class="fss_help">
						<?php echo FSSAdminHelper::IncludeHelp("announce_single.htm"); ?>
					</div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:150px;">
						<?php echo JText::_("USE_CUSTOM_MODULE_TEMPLATE_ANNOUNCE"); ?>:
				</td>
				<td width="450">
					<input type='checkbox' name='announcemod_use_custom' id='announcemod_use_custom' value='1' <?php if ($this->settings['announcemod_use_custom'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td><div class="fss_help"><?php echo JText::_('TMPLHELP_announcemod_use_custom'); ?></div></td>
			</tr>

			<tr id="announcemod_row">
				<td valign="top" align="right" class="key" style="width:150px;">
					<?php echo JText::_("Custom_Template"); ?>:<br />
					<button id='announcemod_reset' class="btn btn-default" style='float:none;'><?php echo JText::_('Reset');?></button><br />
					<span class='fss_custom_warn'>
						<?php echo JText::_('TMPLHELP_WARN1'); ?>
					</span>
				</td>
				<td valign="top" width="450" id="announcemod_row">
					<textarea name='announcemod' id="announcemod" rows="12" cols="80" style="float:none;"><?php echo $this->settings['announcemod']; ?></textarea><br>
					<textarea id="announcemod_default" rows="12" cols="80" style="display:none;"><?php echo $this->settings['announcemod_default']; ?></textarea><br>
				</td>
				<td>
					<div class="fss_help">
						<?php echo FSSAdminHelper::IncludeHelp("announce_module.htm"); ?>
					</div>
				</td>
			</tr>
			
		</table>
	</fieldset>