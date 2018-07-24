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
			<legend><?php echo JText::_("GLOSSARY_SETTINGS"); ?></legend>

			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("USE_GLOSSARY_ON_FAQS"); ?>:
					
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='glossary_faqs' value='1' <?php if ($this->settings['glossary_faqs'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_glossary_faqs'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("USE_GLOSSARY_ON_KNOWELEDGE_BASE"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='glossary_kb' value='1' <?php if ($this->settings['glossary_kb'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_glossary_kb'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("USE_GLOSSARY_ON_ANNOUNCEMENTS"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='glossary_announce' value='1' <?php if ($this->settings['glossary_announce'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_glossary_announce'); ?></div>
					</td>
				</tr>				
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("glossary_support"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='glossary_support' value='1' <?php if ($this->settings['glossary_support'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_glossary_support'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("LINK_ITEMS_TO_GLOSSARY_PAGE"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='glossary_link' value='1' <?php if ($this->settings['glossary_link'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_glossary_link'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("SHOW_GLOSSARY_WORD_IN_TOOLTIP"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='glossary_title' value='1' <?php if ($this->settings['glossary_title'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_glossary_title'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("glossary_show_read_more"); ?>:
					</td>
					<td>
						<input type='checkbox' name='glossary_show_read_more' value='1' <?php if ($this->settings['glossary_show_read_more'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_glossary_show_read_more'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("glossary_all_letters"); ?>:
					</td>
					<td>
						<input type='checkbox' name='glossary_all_letters' value='1' <?php if ($this->settings['glossary_all_letters'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_glossary_all_letters'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("glossary_read_more_text"); ?>:
					
					</td>
					<td>
						<input type='text' name='glossary_read_more_text' size="60" value='<?php echo $this->settings['glossary_read_more_text']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_glossary_read_more_text'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("glossary_word_limit"); ?>:
					</td>
					<td style="width:250px;">
						<select name="glossary_word_limit">
							<option value="0" <?php if ($this->settings['glossary_word_limit'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('ALL_WORDS'); ?></option>
							<?php for ($i = 1 ; $i < 11 ; $i++): ?>
								<option value="<?php echo $i; ?>" <?php if ($this->settings['glossary_word_limit'] == $i) echo " SELECTED"; ?> ><?php echo $i; ?></option>
							<?php endfor; ?>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_glossary_word_limit'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("GLOSSARY_USE_CONTENT_PLUGINS"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='glossary_use_content_plugins' value='1' <?php if ($this->settings['glossary_use_content_plugins'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_glossary_use_content_plugins'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("GLOSSARY_CASE_SENSITIVE"); ?>:
					</td>
					<td>
						<select name="glossary_case_sensitive">
							<option value="" <?php if ($this->settings['glossary_case_sensitive'] == 0 || $this->settings['glossary_case_sensitive'] == '') echo " SELECTED"; ?> ><?php echo JText::_('JNo'); ?></option>
							<option value="1" <?php if ($this->settings['glossary_case_sensitive'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('UPPER_CASE_WORDS_ONLY'); ?></option>
							<option value="2" <?php if ($this->settings['glossary_case_sensitive'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('ALL_WORDS'); ?></option>
						</select>				
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_GLOSSARY_CASE_SENSITIVE'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("GLOSSARY_IGNORE"); ?>:
					
					</td>
					<td>
						<textarea name='glossary_ignore' id="glossary_ignore" rows="12" cols="40" style="float:none;"><?php echo $this->settings['glossary_ignore']; ?></textarea><br>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_glossary_ignore'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("GLOSSARY_EXCLUDE"); ?>:
					
					</td>
					<td>
						<input type='text' name='glossary_exclude' size="60" value='<?php echo $this->settings['glossary_exclude']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_GLOSSARY_EXCLUDE'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>