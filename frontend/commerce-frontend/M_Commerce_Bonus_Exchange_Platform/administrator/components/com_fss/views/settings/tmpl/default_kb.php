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
			<legend><?php echo JText::_("KNOWLEDGE_BASE_SETTINGS_COMMENTS"); ?></legend>

			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("USE_RATING_SYSTEM"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='kb_rate' value='1' <?php if ($this->settings['kb_rate'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_rate'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("ALLOW_ARTICLE_COMMENTS"); ?>:
					</td>
					<td>
						<select name="kb_comments">
							<option value="0" <?php if ($this->settings['kb_comments'] == "0") echo " SELECTED"; ?> ><?php echo JText::_('Disabled'); ?></option>
							<option value="1" <?php if ($this->settings['kb_comments'] == "1") echo " SELECTED"; ?> ><?php echo JText::_('Enabled'); ?></option>
							<?php if (file_exists(JPATH_SITE . '/components/com_jcomments/jcomments.php')): ?>
								<option value="2" <?php if ($this->settings['kb_comments'] == "2") echo " SELECTED"; ?> ><?php echo JText::_('JComments'); ?></option>
							<?php endif; ?>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_comments'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("KB_USE_CONTENT_PLUGINS"); ?>:					
					</td>
					<td>
						<input type='checkbox' name='kb_use_content_plugins' value='1' <?php if ($this->settings['kb_use_content_plugins'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_use_content_plugins'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_("KNOWLEDGE_BASE_SETTINGS_VIEWS"); ?></legend>

			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("SHOW_VIEWS"); ?>:
					
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='kb_show_views' value='1' <?php if ($this->settings['kb_show_views'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_views'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("SHOW_VIEWS_TOP"); ?>:
					
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='kb_view_top' value='1' <?php if ($this->settings['kb_view_top'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_view_top'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("SHOW_MOST_RECENT_VIEW"); ?>:
					</td>
					<td>
						<input type='checkbox' name='kb_show_recent' value='1' <?php if ($this->settings['kb_show_recent'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_recent'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("SHOW_MOST_RECENT_STATISTICS"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='kb_show_recent_stats' value='1' <?php if ($this->settings['kb_show_recent_stats'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_recent_stats'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("SHOW_HIGHEST_RATED_VIEW"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='kb_show_rated' value='1' <?php if ($this->settings['kb_show_rated'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_rated'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("SHOW_HIGHEST_RATED_STATISTICS"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='kb_show_rated_stats' value='1' <?php if ($this->settings['kb_show_rated_stats'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_rated_stats'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("SHOW_MOST_VIEWED_VIEW"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='kb_show_viewed' value='1' <?php if ($this->settings['kb_show_viewed'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_viewed'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("SHOW_MOST_VIEWED_STATISTICS"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='kb_show_viewed_stats' value='1' <?php if ($this->settings['kb_show_viewed_stats'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_viewed_stats'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_("KNOWLEDGE_BASE_SETTINGS_VIEWING_ARTICLE"); ?></legend>

			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SHOW_CREATED_AND_MODIFIED_DATES"); ?>:
					</td>
					<td style="width:250px;">
						<select name="kb_show_dates">
							<option value="0" <?php if ($this->settings['kb_show_dates'] == "0") echo " SELECTED"; ?> ><?php echo JText::_('JNO'); ?></option>
							<option value="1" <?php if ($this->settings['kb_show_dates'] == "1") echo " SELECTED"; ?> ><?php echo JText::_('AS_SECTION'); ?></option>
							<option value="2" <?php if ($this->settings['kb_show_dates'] == "2") echo " SELECTED"; ?> ><?php echo JText::_('IN_DETAILS'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_dates'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SHOW_KB_ART_RELATED"); ?>:
					</td>
					<td>
						<select name="kb_show_art_related">
							<option value="0" <?php if ($this->settings['kb_show_art_related'] == "0") echo " SELECTED"; ?> ><?php echo JText::_('JNO'); ?></option>
							<option value="1" <?php if ($this->settings['kb_show_art_related'] == "1") echo " SELECTED"; ?> ><?php echo JText::_('AS_SECTION'); ?></option>
							<option value="2" <?php if ($this->settings['kb_show_art_related'] == "2") echo " SELECTED"; ?> ><?php echo JText::_('IN_DETAILS'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_art_related'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SHOW_KB_ART_APPLYS"); ?>:
					</td>
					<td>
						<select name="kb_show_art_products">
							<option value="0" <?php if ($this->settings['kb_show_art_products'] == "0") echo " SELECTED"; ?> ><?php echo JText::_('JNO'); ?></option>
							<option value="1" <?php if ($this->settings['kb_show_art_products'] == "1") echo " SELECTED"; ?> ><?php echo JText::_('AS_SECTION'); ?></option>
							<option value="2" <?php if ($this->settings['kb_show_art_products'] == "2") echo " SELECTED"; ?> ><?php echo JText::_('IN_DETAILS'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_art_products'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SHOW_KB_ART_ATTACH"); ?>:
					</td>
					<td>
						<input type='checkbox' name='kb_show_art_attach' value='1' <?php if ($this->settings['kb_show_art_attach'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_art_attach'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("kb_show_art_attach_filenames"); ?>:
					</td>
					<td>
						<input type='checkbox' name='kb_show_art_attach_filenames' value='1' <?php if ($this->settings['kb_show_art_attach_filenames'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_art_attach_filenames'); ?></div>
					</td>
				</tr>				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("KB_COMMENTS_PER_PAGE"); ?>:
					</td>
					<td>
						<?php $this->PerPage('kb_comments_per_page'); ?>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_comments_per_page'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_("KNOWLEDGE_BASE_SETTINGS_GENERAL"); ?></legend>

			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SMALLER_IMAGES_ON_SUBCATEGORIES"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='kb_smaller_subcat_images' value='1' <?php if ($this->settings['kb_smaller_subcat_images'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_smaller_subcat_images'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("KBS_PER_PAGE"); ?>:
					</td>
					<td>
						<?php $this->PerPage('kb_art_per_page'); ?>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_art_per_page'); ?></div>
					</td>
				</tr>	
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("KB_PRODS_PER_PAGE"); ?>:
					</td>
					<td>
						<?php $this->PerPage('kb_prod_per_page'); ?>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_prod_per_page'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("kb_print"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='kb_print' value='1' <?php if ($this->settings['kb_print'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_print'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("kb_contents_auto"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='kb_contents_auto' value='1' <?php if ($this->settings['kb_contents_auto'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_contents_auto'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("AUTOMATICALLY_REDIRECT_TO_SINGLE_CATEGORY"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='kb_auto_open_single_cat' value='1' <?php if ($this->settings['kb_auto_open_single_cat'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_("THIS_ONLY_APPLIES_WHEN_A_PRODUCT_HAS_BEEN_SELECTED__IF_A_SINGLE_CATEGORY_IS_DISPLAYED_THEN_THE_SYSTEM_WILL_REDIRECT_THE_PAGE_TO_DISPLAY_THAT_CATEGORY_"); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("kb_popup_width"); ?>:
					</td>
					<td>
						<input name='kb_popup_width' type="text" value='<?php echo $this->settings['kb_popup_width']; ?>' />
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_kb_popup_width'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>
		   	  	  	 			