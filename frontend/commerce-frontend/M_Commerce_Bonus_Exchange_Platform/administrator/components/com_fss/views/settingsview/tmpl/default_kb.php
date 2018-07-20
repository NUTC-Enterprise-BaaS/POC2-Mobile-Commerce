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
		<legend><?php echo JText::_("KB_MAIN_PAGE"); ?></legend>

		<table class="table table-bordered table-condensed table-striped table-settings">
		
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_main_show_prod"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_main_show_prod' value='1' <?php if ($this->settings['kb_main_show_prod'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_main_show_prod'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_main_show_cat"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_main_show_cat' value='1' <?php if ($this->settings['kb_main_show_cat'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_main_show_cat'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_main_show_sidebyside"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_main_show_sidebyside' value='1' <?php if ($this->settings['kb_main_show_sidebyside'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_main_show_sidebyside'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_main_show_search"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_main_show_search' value='1' <?php if ($this->settings['kb_main_show_search'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_main_show_search'); ?></div>
				</td>
			</tr>

		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_("KB_MAIN_PAGE_PROD"); ?></legend>

		<table class="table table-bordered table-condensed table-striped table-settings">
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("kb_main_prod_colums"); ?>:
					
				</td>
				<td>
					<select name="kb_main_prod_colums">
						<option value="1" <?php if ($this->settings['kb_main_prod_colums'] == 1) echo " SELECTED"; ?> >1</option>
						<option value="2" <?php if ($this->settings['kb_main_prod_colums'] == 2) echo " SELECTED"; ?> >2</option>
						<option value="3" <?php if ($this->settings['kb_main_prod_colums'] == 3) echo " SELECTED"; ?> >3</option>
						<option value="4" <?php if ($this->settings['kb_main_prod_colums'] == 4) echo " SELECTED"; ?> >4</option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_main_prod_colums'); ?></div>
				</td>
			</tr>
		
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_main_prod_search"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_main_prod_search' value='1' <?php if ($this->settings['kb_main_prod_search'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_main_prod_search'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_main_prod_pages"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_main_prod_pages' value='1' <?php if ($this->settings['kb_main_prod_pages'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_main_prod_pages'); ?></div>
				</td>
			</tr>

		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_("KB_MAIN_PAGE_CAT"); ?></legend>

		<table class="table table-bordered table-condensed table-striped table-settings">
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("kb_main_cat_mode"); ?>:
					
				</td>
				<td>
					<select name="kb_main_cat_mode">
						<option value="normal" <?php if ($this->settings['kb_main_cat_mode'] == 'normal') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_mode_normal'); ?></option>
						<option value="accordian" <?php if ($this->settings['kb_main_cat_mode'] == 'accordian') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_mode_accordian'); ?></option>
						<option value="links" <?php if ($this->settings['kb_main_cat_mode'] == 'links') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_mode_links'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_main_cat_mode'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_main_cat_arts"); ?>:
				</td>
				<td>
					<select name="kb_main_cat_arts">
						<option value="normal" <?php if ($this->settings['kb_main_cat_arts'] == 'normal') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_arts_normal'); ?></option>
						<option value="popup" <?php if ($this->settings['kb_main_cat_arts'] == 'popup') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_arts_popup'); ?></option>
					</select>
				</td>
			</tr>


			<tr>
				<td align="right" class="key">
					<?php echo JText::_("kb_main_prod_colums"); ?>:
					
				</td>
				<td>
					<select name="kb_main_cat_colums">
						<option value="1" <?php if ($this->settings['kb_main_cat_colums'] == 1) echo " SELECTED"; ?> >1</option>
						<option value="2" <?php if ($this->settings['kb_main_cat_colums'] == 2) echo " SELECTED"; ?> >2</option>
						<option value="3" <?php if ($this->settings['kb_main_cat_colums'] == 3) echo " SELECTED"; ?> >3</option>
						<option value="4" <?php if ($this->settings['kb_main_cat_colums'] == 4) echo " SELECTED"; ?> >4</option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_main_cat_colums'); ?></div>
				</td>
			</tr>
		

		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_("KB_PROD_SEL"); ?></legend>

		<table class="table table-bordered table-condensed table-striped table-settings">
		
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("kb_prod_cat_mode"); ?>:
					
				</td>
				<td>
					<select name="kb_prod_cat_mode">
						<option value="normal" <?php if ($this->settings['kb_prod_cat_mode'] == 'normal') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_mode_normal'); ?></option>
						<option value="accordian" <?php if ($this->settings['kb_prod_cat_mode'] == 'accordian') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_mode_accordian'); ?></option>
						<option value="links" <?php if ($this->settings['kb_prod_cat_mode'] == 'links') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_mode_links'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_prod_cat_mode'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_prod_cat_arts"); ?>:
				</td>
				<td>
					<select name="kb_prod_cat_arts">
						<option value="normal" <?php if ($this->settings['kb_prod_cat_arts'] == 'normal') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_arts_normal'); ?></option>
						<option value="popup" <?php if ($this->settings['kb_prod_cat_arts'] == 'popup') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_arts_popup'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_prod_cat_arts'); ?></div>
				</td>
			</tr>


			<tr>
				<td align="right" class="key">
					<?php echo JText::_("KB_prod_cat_colums"); ?>:
					
				</td>
				<td>
					<select name="kb_prod_cat_colums">
						<option value="1" <?php if ($this->settings['kb_prod_cat_colums'] == 1) echo " SELECTED"; ?> >1</option>
						<option value="2" <?php if ($this->settings['kb_prod_cat_colums'] == 2) echo " SELECTED"; ?> >2</option>
						<option value="3" <?php if ($this->settings['kb_prod_cat_colums'] == 3) echo " SELECTED"; ?> >3</option>
						<option value="4" <?php if ($this->settings['kb_prod_cat_colums'] == 4) echo " SELECTED"; ?> >4</option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_prod_cat_colums'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_prod_search"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_prod_search' value='1' <?php if ($this->settings['kb_prod_search'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_prod_search'); ?></div>
				</td>
			</tr>

		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_("KB_PROD_CAT_SEL"); ?></legend>

		<table class="table table-bordered table-condensed table-striped table-settings">
		
		
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_cat_cat_mode"); ?>:
				</td>
				<td>
					<select name="kb_cat_cat_mode">
						<option value="normal" <?php if ($this->settings['kb_cat_cat_mode'] == 'normal') echo " SELECTED"; ?> ><?php echo JText::_('kb_cat_cat_mode_normal'); ?></option>
						<option value="accordian" <?php if ($this->settings['kb_cat_cat_mode'] == 'accordian') echo " SELECTED"; ?> ><?php echo JText::_('kb_cat_cat_mode_accordian'); ?></option>
						<option value="links" <?php if ($this->settings['kb_cat_cat_mode'] == 'links') echo " SELECTED"; ?> ><?php echo JText::_('kb_cat_cat_mode_links'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_cat_cat_mode'); ?></div>
				</td>
			</tr>
		
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_cat_cat_arts"); ?>:
				</td>
				<td>
					<select name="kb_cat_cat_arts">
						<option value="normal" <?php if ($this->settings['kb_cat_cat_arts'] == 'normal') echo " SELECTED"; ?> ><?php echo JText::_('kb_cat_cat_arts_normal'); ?></option>
						<option value="popup" <?php if ($this->settings['kb_cat_cat_arts'] == 'popup') echo " SELECTED"; ?> ><?php echo JText::_('kb_cat_cat_arts_popup'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_cat_cat_arts'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_cat_art_pages"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_cat_art_pages' value='1' <?php if ($this->settings['kb_cat_art_pages'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_cat_art_pages'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_cat_search"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_cat_search' value='1' <?php if ($this->settings['kb_cat_search'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_cat_search'); ?></div>
				</td>
			</tr>	
					
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_cat_desc"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_cat_desc' value='1' <?php if ($this->settings['kb_cat_desc'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_cat_desc'); ?></div>
				</td>
			</tr>

		</table>
	</fieldset>
