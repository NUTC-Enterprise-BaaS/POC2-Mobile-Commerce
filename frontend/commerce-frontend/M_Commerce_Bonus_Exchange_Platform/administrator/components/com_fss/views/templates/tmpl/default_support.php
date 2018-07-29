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
		<legend><?php echo JText::_("SUPPORT_LIST_SETTINGS"); ?></legend>
		<table class="table table-bordered table-condensed table-striped table-settings">
			<tr>
				<td align="right" class="key" style="width:120px;">
						<?php echo JText::_("LIST_TEMPLATE"); ?>:
				</td>
				<td style="width:450px;">
					<select name='support_list_template' id="support_list_template" onchange="showhide_customize()">
						<option value="classic" <?php if ($this->settings['support_list_template'] == "classic") echo " SELECTED"; ?> ><?php echo JText::_('CLASSIC'); ?></option>
						<option value="custom" <?php if ($this->settings['support_list_template'] == "custom") echo " SELECTED"; ?> ><?php echo JText::_('Custom'); ?></option>
						<option value="withpriority" <?php if ($this->settings['support_list_template'] == "withpriority") echo " SELECTED"; ?> ><?php echo JText::_('CLASSIC_WITH_PRIORITY'); ?></option>
						<option value="withcustomfields" <?php if ($this->settings['support_list_template'] == "withcustomfields") echo " SELECTED"; ?> ><?php echo JText::_('WITH_CUSTOM_FIELDS_LISTED'); ?></option>
						<option value="withproddept" <?php if ($this->settings['support_list_template'] == "withproddept") echo " SELECTED"; ?> ><?php echo JText::_('WITH_DEPARTMENT_PRODUCT_CATEGORY_LISTED'); ?></option>
						<option value="withall" <?php if ($this->settings['support_list_template'] == "withall") echo " SELECTED"; ?> ><?php echo JText::_('WITH_ALL_DETAILS_LISTED'); ?></option>
						<option value="minimal" <?php if ($this->settings['support_list_template'] == "minimal") echo " SELECTED"; ?> ><?php echo JText::_('MINIMAL'); ?></option>
					</select>
				</td>
				<td>
					<button id="customize_button" class="btn btn-default"><?php echo JText::_('CUSTOMIZE_THIS_TEMPLATE'); ?></button>
					<!--<button onclick="slt_preview(); return false;"><?php echo JText::_('PREVIEW_TEMPLATE'); ?></button>-->
				</td>
			</tr>
			<tr id="customtemplaterow">
				<td valign="top" align="right" class="key" style="width:120px;">
						<?php echo JText::_("LIST_CUSTOM"); ?>:
				</td>
				<td style="width:450px;" valign="top">
					<span class='fss_custom_warn'><?php echo JText::_('TMPLHELP_WARN1'); ?></span><br>
					<strong><?php echo JText::_("HEADER"); ?></strong><br>
					<textarea name='support_list_head' id="support_list_head" rows="20" cols="80" style="float:none;"><?php echo $this->settings['support_list_head']; ?></textarea><br>
					<strong><?php echo JText::_("TICKET_ROW"); ?></strong><br>
					<textarea name='support_list_row' id="support_list_row" rows="20" cols="80" style="float:none;"><?php echo $this->settings['support_list_row']; ?></textarea><br>
				</td>
				<td valign="top">
					<div class="fss_help">
						<?php echo FSSAdminHelper::IncludeHelp("support_template.htm"); ?>
						<?php echo FSSAdminHelper::IncludeHelp("support_template_admin.htm"); ?>
					</div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:120px;">
						<?php echo JText::_("LIST_USER_TEMPLATE"); ?>:
				</td>
				<td style="width:450px;">
					<select name='support_user_template' id="support_user_template" onchange="showhide_customize()">
						<option value="userclassic" <?php if ($this->settings['support_user_template'] == "userclassic") echo " SELECTED"; ?> ><?php echo JText::_('CLASSIC'); ?></option>
						<option value="usercustom" <?php if ($this->settings['support_user_template'] == "usercustom") echo " SELECTED"; ?> ><?php echo JText::_('Custom'); ?></option>
					</select>
				</td>
				<td><button id="user_customize_button" class="btn btn-default"><?php echo JText::_('CUSTOMIZE_THIS_TEMPLATE'); ?></button></td>
			</tr>
			<tr id="customusertemplaterow">
				<td valign="top" align="right" class="key" style="width:120px;">
						<?php echo JText::_("LIST_USER_CUSTOM"); ?>:
				</td>
				<td style="width:450px;" valign="top">
					<span class='fss_custom_warn'><?php echo JText::_('TMPLHELP_WARN1'); ?></span><br>
					<strong><?php echo JText::_("HEADER"); ?></strong><br>
					<textarea name='support_user_head' id="support_user_head" rows="20" cols="80" style="float:none;"><?php echo $this->settings['support_user_head']; ?></textarea><br>
					<strong><?php echo JText::_("TICKET_ROW"); ?></strong><br>
					<textarea name='support_user_row' id="support_user_row" rows="20" cols="80" style="float:none;"><?php echo $this->settings['support_user_row']; ?></textarea><br>
				</td>
				<td valign="top">
					<div class="fss_help">
						<?php echo FSSAdminHelper::IncludeHelp("support_template.htm"); ?>
						<?php echo FSSAdminHelper::IncludeHelp("support_template_user.htm"); ?>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>