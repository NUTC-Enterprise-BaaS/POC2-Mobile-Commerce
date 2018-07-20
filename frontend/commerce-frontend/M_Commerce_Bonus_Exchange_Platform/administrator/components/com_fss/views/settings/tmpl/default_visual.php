<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
		<iframe class="iframe_load_wait" style="display: none;" id="csstest_component" src="<?php echo JURI::root(true).'/index.php?option=com_fss&view=csstest&type=test&tmpl=component'; ?>"></iframe>
		<iframe class="iframe_load_wait" style="display: none;" id="csstest_normal" src="<?php echo JURI::root(true).'/index.php?option=com_fss&view=csstest&type=test'; ?>"></iframe>

		<div id="css_alert" class="alert alert-<?php if (FSS_Settings::get('bootstrap_template') != FSS_Helper::GetTemplate()) {echo "danger";} else {echo "info";} ?>">
			<h4><?php echo JText::_('TEMPLATE_CONFIGURATION'); ?></h4>
			<?php echo JText::_('TEMPLATE_CONFIGURATION_MESSAGE'); ?>
		
			<?php if (FSS_Settings::get('bootstrap_template') != FSS_Helper::GetTemplate()): ?>
				<h5 id="css_must"><?php echo JText::_('YOU_MUST_DO_THIS_BEFORE_THE_COMPONENT_WILL_DISPLAY_CORRECTLY_WITH_YOUR_TEMPLATE'); ?></h5>
			<?php endif; ?>
			<p><a class="btn" href="#" id="csstest_btn"><?php echo JText::_('AUTO_DETECT_TEMPLATE_SETTINGS'); ?></a></p>
		</div>
	
		<div id="current_template" style="display: none;"><?php echo FSS_Helper::GetTemplate(); ?></div>
		<input name="bootstrap_template" id="bootstrap_template" type="hidden" value="<?php echo FSS_Settings::get('bootstrap_template'); ?>" />
		<fieldset class="adminform">
			<legend><?php echo JText::_("BOOTSTRAP_AND_CSS_INCLUSION"); ?></legend>
			<div class="alert"><?php echo JText::_("BOOTSTRAP_AND_CSS_INCLUSION_INFO"); ?></div>
			
			<table class="table table-bordered table-condensed table-striped table-settings" width="100%">	
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("INCLUDE_BOOTSTRAP_CSS_FILES"); ?>:
					</td>
					<td style="width:250px;">
						<select name="bootstrap_css" id="bootstrap_css">
							<option value="no" <?php if ($this->settings['bootstrap_css'] == "no") echo " SELECTED"; ?> ><?php echo JText::_('JNO'); ?></option>
							<option value="yes" <?php if ($this->settings['bootstrap_css'] == "yes") echo " SELECTED"; ?> ><?php echo JText::_('JYES'); ?></option>
							<option value="fssonly" <?php if ($this->settings['bootstrap_css'] == "fssonly") echo " SELECTED"; ?> ><?php echo JText::_('ONLY_FOR_FREESTYLE'); ?></option>
							<option value="fssonlyv3" <?php if ($this->settings['bootstrap_css'] == "fssonlyv3") echo " SELECTED"; ?> ><?php echo JText::_('ONLY_FOR_FREESTYLE_V3'); ?></option>
							<option value="partial" <?php if ($this->settings['bootstrap_css'] == "partial") echo " SELECTED"; ?> ><?php echo JText::_('PARTIAL'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'>
						
						</div>
					</td>
				</tr>
				<?php if (!FSSJ3Helper::IsJ3()): ?>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("INCLUDE_THE_BOOTSTRAP_JS_FILES"); ?>:
					</td>
					<td style="width:250px;">
						<select name="bootstrap_js" id="bootstrap_js">
							<option value="no" <?php if ($this->settings['bootstrap_js'] == "no") echo " SELECTED"; ?> ><?php echo JText::_('JNO'); ?></option>
							<option value="yes" <?php if ($this->settings['bootstrap_js'] == "yes") echo " SELECTED"; ?> ><?php echo JText::_('JYES'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SET_HELP_BOOTSTRAP_JS'); ?></div>
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("TABLE_BORDER_COLOR"); ?>:
					</td>
					<td style="width:250px;">
						<input type='text' name='bootstrap_border' id='bootstrap_border' value='<?php echo $this->settings['bootstrap_border']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('THE_COLOR_YOUR_TEMPLATES_USES_FOR_TABLE_BORDERS'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("ARTISTEER_FIXES"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='artisteer_fixes' id='artisteer_fixes' value='1' <?php if ($this->settings['artisteer_fixes'] == 1) { echo " checked='yes' "; } ?>>
						</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SET_HELP_ARTISTEER_FIXES'); ?></div>
					</td>
				</tr>				<tr>
					<td colspan="3">
						<p>
							<strong><?php echo JText::_('SET_HELP_BOOTSTRAP_BORDER'); ?></strong>
						</p>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("INCLUDE__TEXT_COLOR__STYLES"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='bootstrap_textcolor' id='bootstrap_textcolor' value='1' <?php if ($this->settings['bootstrap_textcolor'] == 1) { echo " checked='yes' "; } ?>>
						</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SET_HELP_BOOTSTRAP_TEXTCOLOR'); ?></div>
					</td>
				</tr>
			
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("INCLUDE__MODAL__STYLE"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='bootstrap_modal' id='bootstrap_modal' value='1' <?php if ($this->settings['bootstrap_modal'] == 1) { echo " checked='yes' "; } ?>>
						</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SET_HELP_BOOTSTRAP_MODAL'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("INCLUDE_ICOMOON"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='bootstrap_icomoon' id='bootstrap_icomoon' value='1' <?php if ($this->settings['bootstrap_icomoon'] == 1) { echo " checked='yes' "; } ?>>
						</td>
					<td>
						<div class='fss_help'><?php echo JText::_('INCLUDE_ICOMOON_ICONS_IN_STYLESHEET'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("bootstrap_v3"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='bootstrap_v3' id='bootstrap_v3' value='1' <?php if ($this->settings['bootstrap_v3'] == 1) { echo " checked='yes' "; } ?>>
						</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SET_HELP_bootstrap_v3'); ?></div>
					</td>
				</tr>
		
			</table>
		</fieldset>


		<fieldset class="adminform">
			<legend><?php echo JText::_("POPUP_WINDOW_CSS_AND_JS_FIXES"); ?></legend>
			<div class="alert"><?php echo JText::_('POPUP_WINDOW_CSS_AND_JS_FIXES_INFO'); ?></div>
			<table class="table table-bordered table-condensed table-striped table-settings">		
				<tr>
					<td colspan="2" width="60%">	
						<?php echo JText::_("CSS"); ?>:<br />
						<textarea name="popup_css" id="popup_css" rows="10" cols="80" style="width: 96% !important;"><?php echo $this->settings['popup_css'] ?></textarea>
					</td>
					<td>
						<div class="fss_help">
							<?php echo JText::_('SET_HELP_POPUP_CSS'); ?>
						</div>
						<div id="popup_css_outer" style="display:none;margin-top: 8px;width:100%;">
							<b><?php echo JText::_('AUTO_DETECTED_FILES'); ?></b>
							<pre id="popup_css_suggestions"></pre>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2" width="60%">	
						<?php echo JText::_("JS"); ?>:<br />
						<textarea name="popup_js" id="popup_js" rows="10" cols="80" style="width: 96% !important"><?php echo $this->settings['popup_js'] ?></textarea>
					</td>
					<td>
						<div class="fss_help">
							<?php echo JText::_('SET_HELP_POPUP_JS'); ?>						
						</div>
						<div id="popup_js_outer" style="display:none;margin-top: 8px;width:100%;">
							<b><?php echo JText::_('AUTO_DETECTED_FILES'); ?></b>
							<pre id="popup_js_suggestions"></pre>
						</div>				
					</td>
				</tr>
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_("VISUAL_SETTINGS"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">		
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("USE_SKIN_STYLING_FOR_PAGEINATION_CONTROLS"); ?>:
					
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='skin_style' value='1' <?php if ($this->settings['skin_style'] == 1) { echo " checked='yes' "; } ?>>
						</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_skin_style'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("title_prefix"); ?>:
					</td>
					<td style="width:250px;">
						<select name="title_prefix" id="title_prefix">
							<option value="0" <?php if ($this->settings['title_prefix'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('TITLE_OR_SUBTITLE'); ?></option>
							<option value="1" <?php if ($this->settings['title_prefix'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('TITLE___SUBTITLE'); ?></option>
							<option value="2" <?php if ($this->settings['title_prefix'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('TITLE'); ?></option>
							<option value="3" <?php if ($this->settings['title_prefix'] == 3) echo " SELECTED"; ?> ><?php echo JText::_('MENU_TITLE'); ?></option>
							<option value="4" <?php if ($this->settings['title_prefix'] == 4) echo " SELECTED"; ?> ><?php echo JText::_('MENU_TITLE___TITLE_OR_SUBTITLE'); ?></option>
							<option value="5" <?php if ($this->settings['title_prefix'] == 5) echo " SELECTED"; ?> ><?php echo JText::_('MENU_TITLE___TITLE___SUBTITLE'); ?></option>
							<option value="6" <?php if ($this->settings['title_prefix'] == 6) echo " SELECTED"; ?> ><?php echo JText::_('MENU_TITLE___TITLE'); ?></option>
						
							<option value="99" <?php if ($this->settings['title_prefix'] == 99) echo " SELECTED"; ?> ><?php echo JText::_('JNONE'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_TITLE_PREFIX'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("browser_prefix"); ?>:
					</td>
					<td style="width:250px;">
						<select name="browser_prefix" id="browser_prefix">
							<option value="-1" <?php if ($this->settings['browser_prefix'] == -1) echo " SELECTED"; ?> ><?php echo JText::_('SAME_AS_TITLE_PREFIX'); ?></option>
							<option value="0" <?php if ($this->settings['browser_prefix'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('TITLE_OR_SUBTITLE'); ?></option>
							<option value="1" <?php if ($this->settings['browser_prefix'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('TITLE___SUBTITLE'); ?></option>
							<option value="2" <?php if ($this->settings['browser_prefix'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('TITLE'); ?></option>
							<option value="3" <?php if ($this->settings['browser_prefix'] == 3) echo " SELECTED"; ?> ><?php echo JText::_('PAGE_TITLE'); ?></option>
							<option value="4" <?php if ($this->settings['browser_prefix'] == 4) echo " SELECTED"; ?> ><?php echo JText::_('PAGE_TITLE___TITLE_OR_SUBTITLE'); ?></option>
							<option value="5" <?php if ($this->settings['browser_prefix'] == 5) echo " SELECTED"; ?> ><?php echo JText::_('PAGE_TITLE___TITLE___SUBTITLE'); ?></option>
							<option value="6" <?php if ($this->settings['browser_prefix'] == 6) echo " SELECTED"; ?> ><?php echo JText::_('PAGE_TITLE___TITLE'); ?></option>
						
							<option value="99" <?php if ($this->settings['browser_prefix'] == 99) echo " SELECTED"; ?> ><?php echo JText::_('JNONE'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_BROWSER_PREFIX'); ?></div>
					</td>
				</tr>			<tr>
					<td align="left" class="key" style="width:250px;">
					
						<?php echo JText::_("USE_JOOMLA_SETTING_FOR_PAGE_TITLE_VISIBILITY"); ?>:
					
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='use_joomla_page_title_setting' value='1' <?php if ($this->settings['use_joomla_page_title_setting'] == 1) { echo " checked='yes' "; } ?>>
						</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_use_joomla_page_title_setting'); ?></div>
				</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("faq_cat_prefix"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='faq_cat_prefix' value='1' <?php if ($this->settings['faq_cat_prefix'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_faq_cat_prefix'); ?><div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("page_headingout"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='page_headingout' value='1' <?php if ($this->settings['page_headingout'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_page_headingout'); ?><div>
					</td>
				</tr>		
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("bootstrap_pribtn"); ?>:
					
					</td>
					<td>
						<input type='text' name='bootstrap_pribtn' value='<?php echo $this->settings['bootstrap_pribtn']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_bootstrap_pribtn'); ?><div>
					</td>
				</tr>		
			</table>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_("CSS_SETTINGS"); ?></legend>
			<table class="admintable" width="100%">
				<tr>
					<td colspan="2" width="60%">	
						<?php echo JText::_("MAIN_CSS"); ?>:<br />
						<textarea name="display_style" id="display_style" rows="10" cols="60"><?php echo $this->settings['display_style'] ?></textarea>
					</td>
					<td>
						<div class="fss_help"><?php echo JText::_('TMPLHELP_display_style'); ?>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">	
						<?php echo JText::_("POPUP_CSS"); ?>:<br />
						<textarea name="display_popup_style" id="display_popup_style" rows="10" cols="60"><?php echo $this->settings['display_popup_style'] ?></textarea>
					</td>
					<td>
						<div class="fss_help"><?php echo JText::_('TMPLHELP_display_popup_style'); ?>
					
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">	
						<?php echo JText::_("display_module_style"); ?>:<br />
						<textarea name="display_module_style" id="display_module_style" rows="10" cols="60"><?php echo $this->settings['display_module_style'] ?></textarea>
					</td>
					<td>
						<div class="fss_help"><?php echo JText::_('TMPLHELP_display_module_style'); ?>
					
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">	
						<?php echo JText::_("BOOTSTRAP_VARIABLES_OVERRIDES"); ?>:<br />
						<textarea name="bootstrap_variables" id="bootstrap_variables" rows="10" cols="60"><?php echo $this->settings['bootstrap_variables'] ?></textarea>
					</td>
					<td>
						<div class="fss_help">
							<?php echo JText::_('SET_HELP_BOOTSTRAP_VARIABLES'); ?>
						</div>
					</td>
				</tr>
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_("PAGE_TITLE_SETTINGS"); ?></legend>
			<table class="admintable" width="100%">
				<tr>
					<td colspan="2" width="60%">
						<?php echo JText::_("H1_STYLE"); ?>:<br />
						<textarea name="display_h1" id="display_h1" rows="5" cols="60"><?php echo $this->settings['display_h1'] ?></textarea>
					</td>
					<td>
						<div class="fss_help"><?php echo JText::_('TMPLHELP_display_h1'); ?></div>
					</td>
				</tr>
				<tr>
					<td colspan="2" width="60%">	
						<?php echo JText::_("H2_STYLE"); ?>:<br />
						<textarea name="display_h2" id="display_h2" rows="5" cols="60"><?php echo $this->settings['display_h2'] ?></textarea>
					</td>
					<td>
						<div class="fss_help"><?php echo JText::_('TMPLHELP_display_h2'); ?></div>
					</td>
				</tr>
				<tr>
					<td colspan="2" width="60%">
						<?php echo JText::_("H3_STYLE"); ?>:<br />
						<textarea name="display_h3" id="display_h3" rows="5" cols="60"><?php echo $this->settings['display_h3'] ?></textarea>
					</td>
						<td>
						<div class="fss_help"><?php echo JText::_('TMPLHELP_display_h3'); ?></div>
					</td>
				</tr>
				<tr>
					<td colspan="2" width="60%">	
						<?php echo JText::_("PAGE_HEADER"); ?>:<br />
						<textarea name="display_head" id="display_head" rows="10" cols="60"><?php echo $this->settings['display_head'] ?></textarea>
					</td>
					<td>
						<div class="fss_help"><?php echo JText::_('TMPLHELP_display_head'); ?>
						
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2" width="60%">	
						<?php echo JText::_("PAGE_FOOTER"); ?>:<br />
						<textarea name="display_foot" id="display_foot" rows="10" cols="60"><?php echo $this->settings['display_foot'] ?></textarea>
					</td>
					<td>
						<div class="fss_help"><?php echo JText::_('TMPLHELP_display_foot'); ?></div>
					</td>
				</tr>

			</table>
		</fieldset>	    			  	  	  