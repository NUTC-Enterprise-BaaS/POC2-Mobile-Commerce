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
		<legend><?php echo JText::_("FAQS_WHEN_SHOWING_LIST_OF_CATEGORIES"); ?></legend>

		<table class="table table-bordered table-condensed table-striped table-settings">
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("FAQS_HIDE_ALLFAQS"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='faqs_hide_allfaqs' value='1' <?php if ($this->settings['faqs_hide_allfaqs'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_FAQS_HIDE_ALLFAQS'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("FAQS_HIDE_TAGS"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='faqs_hide_tags' value='1' <?php if ($this->settings['faqs_hide_tags'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_FAQS_hide_tags'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("FAQS_HIDE_SEARCH"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='faqs_hide_search' value='1' <?php if ($this->settings['faqs_hide_search'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_FAQS_hide_search'); ?></div>
				</td>
			</tr>

			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("FAQS_SHOW_FEATURED"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='faqs_show_featured' value='1' <?php if ($this->settings['faqs_show_featured'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_FAQS_show_featured'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("FAQS_NUM_CAT_COLUMS"); ?>:
					
				</td>
				<td>
					<select name="faqs_num_cat_colums">
						<option value="1" <?php if ($this->settings['faqs_num_cat_colums'] == 1) echo " SELECTED"; ?> >1</option>
						<option value="2" <?php if ($this->settings['faqs_num_cat_colums'] == 2) echo " SELECTED"; ?> >2</option>
						<option value="3" <?php if ($this->settings['faqs_num_cat_colums'] == 3) echo " SELECTED"; ?> >3</option>
						<option value="4" <?php if ($this->settings['faqs_num_cat_colums'] == 4) echo " SELECTED"; ?> >4</option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_NUM_CAT_COLUMS'); ?></div>
				</td>
			</tr>

			<tr>
				<td align="right" class="key">
					<?php echo JText::_("FAQS_view_mode_cat"); ?>:
					
				</td>
				<td>
					<select name="faqs_view_mode_cat">
						<option value="list" <?php if ($this->settings['faqs_view_mode_cat'] == 'list') echo " SELECTED"; ?> ><?php echo JText::_('FAQS_VIEW_MODE_CAT_LIST'); ?></option>
						<option value="inline" <?php if ($this->settings['faqs_view_mode_cat'] == 'inline') echo " SELECTED"; ?> ><?php echo JText::_('FAQS_VIEW_MODE_CAT_INLINE'); ?></option>
						<option value="accordian" <?php if ($this->settings['faqs_view_mode_cat'] == 'accordian') echo " SELECTED"; ?> ><?php echo JText::_('FAQS_VIEW_MODE_CAT_ACCORDIAN'); ?></option>
						<option value="popup" <?php if ($this->settings['faqs_view_mode_cat'] == 'popup') echo " SELECTED"; ?> ><?php echo JText::_('FAQS_VIEW_MODE_CAT_POPUP'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_view_mode_cat'); ?></div>
				</td>
			</tr>

			<tr>
				<td align="right" class="key">
					<?php echo JText::_("FAQS_view_mode_incat"); ?>:
					
				</td>
				<td>
					<select name="faqs_view_mode_incat">
						<option value="allononepage" <?php if ($this->settings['faqs_view_mode_incat'] == 'allononepage') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_allononepage'); ?></option>
						<option value="accordian" <?php if ($this->settings['faqs_view_mode_incat'] == 'accordian') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_accordian'); ?></option>
						<option value="questionwithtooltip" <?php if ($this->settings['faqs_view_mode_incat'] == 'questionwithtooltip') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_questionwithtooltip'); ?></option>
						<option value="questionwithlink" <?php if ($this->settings['faqs_view_mode_incat'] == 'questionwithlink') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_questionwithlink'); ?></option>
						<option value="questionwithpopup" <?php if ($this->settings['faqs_view_mode_incat'] == 'questionwithpopup') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_questionwithpopup'); ?></option>
						<option value="questionnewwindow" <?php if ($this->settings['faqs_view_mode_incat'] == 'questionnewwindow') echo " SELECTED"; ?> ><?php echo JText::_('Question with new window'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_faqs_view_mode_incat'); ?></div>
				</td>
			</tr>


		</table>
	</fieldset>


	<fieldset class="adminform">
		<legend><?php echo JText::_("FAQS_WHEN_SHOWING_LIST_OF_FAQS"); ?></legend>

		<table class="table table-bordered table-condensed table-striped table-settings">
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("FAQS_view_mode"); ?>:
					
				</td>
				<td>
					<select name="faqs_view_mode">
						<option value="allononepage" <?php if ($this->settings['faqs_view_mode'] == 'allononepage') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_allononepage'); ?></option>
						<option value="accordian" <?php if ($this->settings['faqs_view_mode'] == 'accordian') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_accordian'); ?></option>
						<option value="questionwithtooltip" <?php if ($this->settings['faqs_view_mode'] == 'questionwithtooltip') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_questionwithtooltip'); ?></option>
						<option value="questionwithlink" <?php if ($this->settings['faqs_view_mode'] == 'questionwithlink') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_questionwithlink'); ?></option>
						<option value="questionwithpopup" <?php if ($this->settings['faqs_view_mode'] == 'questionwithpopup') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_questionwithpopup'); ?></option>
						<option value="questionnewwindow" <?php if ($this->settings['faqs_view_mode'] == 'questionnewwindow') echo " SELECTED"; ?> ><?php echo JText::_('Question with new window'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_faqs_view_mode'); ?></div>
				</td>
			</tr>

			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("FAQS_enable_pages"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='faqs_enable_pages' value='1' <?php if ($this->settings['faqs_enable_pages'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_FAQS_enable_pages'); ?></div>
				</td>
			</tr>

		</table>
	</fieldset>
