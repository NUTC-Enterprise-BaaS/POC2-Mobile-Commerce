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
		<legend><?php echo JText::_("MODERATION_COMMENTS"); ?></legend>
		<table class="table table-bordered table-condensed table-striped table-settings">
			<tr>
				<td align="right" class="key" style="width:150px;">
						<?php echo JText::_("Use_Custom_Template"); ?>:
				</td>
				<td width="450">
					<input type='checkbox' name='comments_general_use_custom' id='comments_general_use_custom' value='1' <?php if ($this->settings['comments_general_use_custom'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td><div class="fss_help"><?php echo JText::_('TMPLHELP_comments_general_use_custom'); ?></div></td>
			</tr>

			<tr id="comments_general_row">
				<td valign="top" align="right" class="key" style="width:150px;">
					<?php echo JText::_("Custom_Template"); ?>:<br />
					<button id='comments_general_reset' style='float:none;'><?php echo JText::_('Reset');?></button><br />
					<span class='fss_custom_warn'>
						<?php echo JText::_('TMPLHELP_WARN1'); ?>
					</span>
				</td>
				<td valign="top" width="450" id="comments_general_row">
					<textarea name='comments_general' id="comments_general" rows="12" cols="80" style="float:none;"><?php echo $this->settings['comments_general']; ?></textarea><br>
					<textarea id="comments_general_default" rows="12" cols="80" style="display:none;"><?php echo $this->settings['comments_general_default']; ?></textarea><br>
				</td>
				<td><div class="fss_help"><?php echo FSSAdminHelper::IncludeHelp("comment.htm"); ?></div></td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset class="adminform">
		<legend><?php echo JText::_("ANNOUNCEMENT_COMMENTS"); ?></legend>
		<table class="table table-bordered table-condensed table-striped table-settings">
			<tr>
				<td align="right" class="key" style="width:150px;">
						<?php echo JText::_("Use_Custom_Template"); ?>:
				</td>
				<td width="450">
					<input type='checkbox' name='comments_announce_use_custom' id='comments_announce_use_custom' value='1' <?php if ($this->settings['comments_announce_use_custom'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td><div class="fss_help"><?php echo JText::_('TMPLHELP_comments_announce_use_custom'); ?></div></td>
			</tr>

			<tr id="comments_announce_row">
				<td valign="top" align="right" class="key" style="width:150px;">
					<?php echo JText::_("Custom_Template"); ?>:<br />
					<button id='comments_announce_reset' class="btn btn-default" style='float:none;'><?php echo JText::_('Reset');?></button><br />
					<span class='fss_custom_warn'>
						<?php echo JText::_('TMPLHELP_WARN1'); ?>
					</span>
				</td>
				<td valign="top" width="450" id="comments_announce_row">
					<textarea name='comments_announce' id="comments_announce" rows="12" cols="80" style="float:none;"><?php echo $this->settings['comments_announce']; ?></textarea><br>
					<textarea id="comments_announce_default" rows="12" cols="80" style="display:none;"><?php echo $this->settings['comments_announce_default']; ?></textarea><br>
				</td>
				<td><div class="fss_help"><?php echo FSSAdminHelper::IncludeHelp("comment.htm"); ?></div></td>
			</tr>
		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_("KB_COMMENTS"); ?></legend>
		<table class="table table-bordered table-condensed table-striped table-settings">
			<tr>
				<td align="right" class="key" style="width:150px;">
						<?php echo JText::_("Use_Custom_Template"); ?>:
				</td>
				<td width="450">
					<input type='checkbox' name='comments_kb_use_custom' id='comments_kb_use_custom' value='1' <?php if ($this->settings['comments_kb_use_custom'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td><div class="fss_help"><?php echo JText::_('TMPLHELP_comments_kb_use_custom'); ?></div></td>
			</tr>

			<tr id="comments_kb_row">
				<td valign="top" align="right" class="key" style="width:150px;">
					<?php echo JText::_("Custom_Template"); ?>:<br />
					<button id='comments_kb_reset' class="btn btn-default" style='float:none;'><?php echo JText::_('Reset');?></button><br />
					<span class='fss_custom_warn'>
						<?php echo JText::_('TMPLHELP_WARN1'); ?>
					</span>
				</td>
				<td valign="top" width="450" id="comments_kb_row">
					<textarea name='comments_kb' id="comments_kb" rows="12" cols="80" style="float:none;"><?php echo $this->settings['comments_kb']; ?></textarea><br>
					<textarea id="comments_kb_default" rows="12" cols="80" style="display:none;"><?php echo $this->settings['comments_kb_default']; ?></textarea><br>
				</td>
				<td><div class="fss_help"><?php echo FSSAdminHelper::IncludeHelp("comment.htm"); ?></div></td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset class="adminform">
		<legend><?php echo JText::_("TESTIMONIAL_TEMPLATES"); ?></legend>
		<table class="table table-bordered table-condensed table-striped table-settings">
			<tr>
				<td align="right" class="key" style="width:150px;">
						<?php echo JText::_("Use_Custom_Template"); ?>:
				</td>
				<td width="450">
					<input type='checkbox' name='comments_test_use_custom' id='comments_test_use_custom' value='1' <?php if ($this->settings['comments_test_use_custom'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td><div class="fss_help"><?php echo JText::_('TMPLHELP_comments_test_use_custom'); ?></div></td>
			</tr>

			<tr id="comments_test_row">
				<td valign="top" align="right" class="key" style="width:150px;">
					<?php echo JText::_("Custom_Template"); ?>:<br />
					<button id='comments_test_reset' class="btn btn-default" style='float:none;'><?php echo JText::_('Reset');?></button><br />
					<span class='fss_custom_warn'>
						<?php echo JText::_('TMPLHELP_WARN1'); ?>
					</span>
				</td>
				<td valign="top" width="450" id="comments_test_row">
					<textarea name='comments_test' id="comments_test" rows="12" cols="80" style="float:none;"><?php echo $this->settings['comments_test']; ?></textarea><br>
					<textarea id="comments_test_default" rows="12" cols="80" style="display:none;"><?php echo $this->settings['comments_test_default']; ?></textarea><br>
				</td>
				<td><div class="fss_help"><?php echo FSSAdminHelper::IncludeHelp("comment.htm"); ?></div></td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:150px;">
						<?php echo JText::_("Use_Custom_Module_Template"); ?>:
				</td>
				<td width="450">
					<input type='checkbox' name='comments_testmod_use_custom' id='comments_testmod_use_custom' value='1' <?php if ($this->settings['comments_testmod_use_custom'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td><div class="fss_help"><?php echo JText::_('TMPLHELP_comments_testmod_use_custom'); ?></div></td>
			</tr>

			<tr id="comments_testmod_row">
				<td valign="top" align="right" class="key" style="width:150px;">
					<?php echo JText::_("Custom_Template"); ?>:<br />
					<button id='comments_testmod_reset' class="btn btn-default" style='float:none;'><?php echo JText::_('Reset');?></button><br />
					<span class='fss_custom_warn'>
						<?php echo JText::_('TMPLHELP_WARN1'); ?>
					</span>
				</td>
				<td valign="top" width="450" id="comments_testmod_row">
					<textarea name='comments_testmod' id="comments_testmod" rows="12" cols="80" style="float:none;"><?php echo $this->settings['comments_testmod']; ?></textarea><br>
					<textarea id="comments_testmod_default" rows="12" cols="80" style="display:none;"><?php echo $this->settings['comments_testmod_default']; ?></textarea><br>
				</td>
				<td><div class="fss_help"><?php echo FSSAdminHelper::IncludeHelp("comment.htm"); ?></div></td>
			</tr>
		</table>
	</fieldset>