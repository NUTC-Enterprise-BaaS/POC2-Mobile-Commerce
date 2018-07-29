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
			<legend><?php echo JText::_("GENERAL_SETTINGS"); ?></legend>

			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("HIDE_POWERED"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='hide_powered' value='1' <?php if ($this->settings['hide_powered'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_hide_powered'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("jquery_include"); ?>:
					</td>
					<td style="width:250px;">
						<select name="jquery_include">
							<option value="auto" <?php if ($this->settings['jquery_include'] == "auto") echo " SELECTED"; ?> ><?php echo JText::_('jquery_include_auto'); ?></option>
							<option value="yes" <?php if ($this->settings['jquery_include'] == "yes") echo " SELECTED"; ?> ><?php echo JText::_('jquery_include_yes'); ?></option>
							<option value="yesnonc" <?php if ($this->settings['jquery_include'] == "yesnonc") echo " SELECTED"; ?> ><?php echo JText::_('jquery_include_yesnonc'); ?></option>
							<option value="no" <?php if ($this->settings['jquery_include'] == "no") echo " SELECTED"; ?> ><?php echo JText::_('jquery_include_no'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_jquery_include'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("use_sef_compat"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='use_sef_compat' value='1' <?php if ($this->settings['use_sef_compat'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_use_sef_compat'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("css_indirect"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='css_indirect' value='1' <?php if ($this->settings['css_indirect'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_css_indirect'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("hide_warnings"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='hide_warnings' value='1' <?php if ($this->settings['hide_warnings'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_hide_warnings'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("attach_location"); ?>:
					</td>
					<td style="width:250px;">
						<input name='attach_location' type="text" size="40" value='<?php echo $this->settings['attach_location']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_attach_location'); ?></div>
					</td>
				</tr>		
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("debug_reports"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='debug_reports' value='1' <?php if ($this->settings['debug_reports'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_debug_reports'); ?></div>
					</td>
				</tr>			
				<tr>
					<td align="left" class="key">				
							<?php echo JText::_("reports_separator"); ?>:
					</td>
					<td>
						<select name="reports_separator">
							<option value="," <?php if ($this->settings['reports_separator'] == ",") echo " SELECTED"; ?> >,</option>
							<option value=";" <?php if ($this->settings['reports_separator'] == ";") echo " SELECTED"; ?> >;</option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_reports_separator'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("search_extra_like"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='search_extra_like' value='1' <?php if ($this->settings['search_extra_like'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_search_extra_like'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_("COMMENTS_SETTINGS"); ?></legend>

			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("CAPTCHA_TYPE"); ?>:
					</td>
					<td style="width:250px;">
						<select name="captcha_type">
							<option value="none" <?php if ($this->settings['captcha_type'] == "none") echo " SELECTED"; ?> ><?php echo JText::_('FNONE'); ?></option>
							<option value="fsj" <?php if ($this->settings['captcha_type'] == "fsj") echo " SELECTED"; ?> ><?php echo JText::_('BUILT_IN'); ?></option>
							<option value="recaptcha" <?php if ($this->settings['captcha_type'] == "recaptcha") echo " SELECTED"; ?> ><?php echo JText::_('RECAPTCHA'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_captcha_type'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("HIDE_ADD_COMMENT"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='comments_hide_add' value='1' <?php if ($this->settings['comments_hide_add'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_comments_hide_add'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("COMMENTS_ARE_MODERATED_BEFORE_DISPLAY"); ?>:
					
					</td>
					<td>
						<select name="comments_moderate">
							<option value="all" <?php if ($this->settings['comments_moderate'] == "all") echo " SELECTED"; ?> ><?php echo JText::_('ALL_COMMENTS_MODERATED'); ?></option>
							<option value="guests" <?php if ($this->settings['comments_moderate'] == "guests") echo " SELECTED"; ?> ><?php echo JText::_('GUEST_COMMENTS_MODERATED'); ?></option>
							<option value="registered" <?php if ($this->settings['comments_moderate'] == "registered") echo " SELECTED"; ?> ><?php echo JText::_('REGISTERED_AND_GUEST_COMMENTS_MODERATED'); ?></option>
							<option value="none" <?php if ($this->settings['comments_moderate'] == "none") echo " SELECTED"; ?> ><?php echo JText::_('NO_COMMENTS_ARE_MODERATED'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_comments_moderate'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("WHO_CAN_ADD_COMMENTS"); ?>:
					</td>
					<td>
						<select name="comments_who_can_add">
							<option value="anyone" <?php if ($this->settings['comments_who_can_add'] == "anyone") echo " SELECTED"; ?> ><?php echo JText::_('ANYONE'); ?></option>
							<option value="registered" <?php if ($this->settings['comments_who_can_add'] == "registered") echo " SELECTED"; ?> ><?php echo JText::_('REGISTERED_USERS_ONLY'); ?></option>
							<!--<option value="moderators" <?php if ($this->settings['comments_who_can_add'] == "moderators") echo " SELECTED"; ?> >Moderators Only</option>-->
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_comments_who_can_add'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("KB_EMAIL_ON_COMMENT"); ?>:
					
					</td>
					<td>
						<input name='email_on_comment' type="text" size="40" value='<?php echo $this->settings['email_on_comment']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_email_on_comment'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("COMMENT_USE_EMAIL"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='commnents_use_email' value='1' <?php if ($this->settings['commnents_use_email'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_commnents_use_email'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("COMMENT_USE_WEBSITE"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='commnents_use_website' value='1' <?php if ($this->settings['commnents_use_website'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_commnents_use_website'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_("RECAPTCHA_SETTINGS"); ?></legend>

			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("RECAPTCHA_PUBLIC_KEY"); ?>:
					</td>
					<td>
						<input name='recaptcha_public' type="text" size="40" value='<?php echo $this->settings['recaptcha_public'] ?>'>
					</td>
					<td rowspan="2">
						<div class='fss_help'><?php echo JText::_('SETHELP_recaptcha_public'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("RECAPTCHA_PRIVATE_KEY"); ?>:
					</td>
					<td>
						<input name='recaptcha_private' type="text" size="40" value='<?php echo $this->settings['recaptcha_private'] ?>'>
					</td>
				</tr>
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_("DATE_SETTINGS"); ?></legend>

			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("SHORT_DATETIME"); ?>:
					</td>
					<td style="width:350px;">
						<input name='date_dt_short' type="text" id='date_dt_short' size="40" value='<?php echo $this->settings['date_dt_short'] ?>'>
						<div class="fss_clear"></div>
						<div>Joomla : <b><?php echo JText::_('DATE_FORMAT_LC4') . ', H:i'; ?></b></div>
						<div id="test_date_dt_short"></div>
					</td>
					<td rowspan="4" valign="top">
						<div class='fss_help'>
						<?php echo JText::_('SETHELP_DATE_FORMATS'); ?>
						</div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("LONG_DATETIME"); ?>:
					</td>
					<td style="width:350px;">
						<input name='date_dt_long' type="text" id='date_dt_long' size="40" value='<?php echo $this->settings['date_dt_long'] ?>'>
						<div class="fss_clear"></div>
						<div>Joomla : <b><?php echo JText::_('DATE_FORMAT_LC3') . ', H:i'; ?></b></div>
						<div id="test_date_dt_long"></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("SHORT_DATE"); ?>:
					</td>
					<td style="width:350px;">
						<input name='date_d_short' type="text" id='date_d_short' size="40" value='<?php echo $this->settings['date_d_short'] ?>'>
						<div class="fss_clear"></div>
						<div>Joomla : <b><?php echo JText::_('DATE_FORMAT_LC4'); ?></b></div>
						<div id="test_date_d_short"></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("LONG_DATE"); ?>:
					</td>
					<td style="width:350px;">
						<input name='date_d_long' type="text" id='date_d_long' size="40" value='<?php echo $this->settings['date_d_long'] ?>'>
						<div class="fss_clear"></div>
						<div>Joomla : <b><?php echo JText::_('DATE_FORMAT_LC3'); ?></b></div>
						<div id="test_date_d_long"></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("TIMEZONE_OFFSET"); ?>:
					</td>
					<td>
						<input name='timezone_offset' type="text" id='timezone_offset' size="40" value='<?php echo $this->settings['timezone_offset'] ?>'>
						<div class="fss_clear"></div>
						<div id="test_timezone_offset"></div>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_timezone_offset'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("TEST_DATE_FORMATS"); ?>:
					</td>
					<td style="width:250px;">
						<button class="btn" id="test_date_formats"><?php echo JText::_('TEST_DATE_FORMATS_BUTTON'); ?></button>
					</td>
					<td valign="top">
						<div class='fss_help'>
						<?php echo JText::_('SETHELP_DATE_TEST'); ?>
						</div>
					</td>
				</tr>
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_("MAIN_MENU_SETTINGS"); ?></legend>

			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("SHOW__SUPPORT_TICKETS__SECTION"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='mainmenu_support' value='1' <?php if ($this->settings['mainmenu_support'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_("SET_HELP_MAINMENU_SUPPORT"); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("SHOW__MODERATE__SECTION"); ?>:
					</td>
					<td>
						<input type='checkbox' name='mainmenu_moderate' value='1' <?php if ($this->settings['mainmenu_moderate'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_("SET_HELP_MAINMENU_MODERATE"); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_("WYSIWYG_EDITOR_SETTINGS"); ?></legend>

			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("TOOLBAR_THEME"); ?>:
					</td>
					<td style="width:250px;">
						<select name="sceditor_theme">
							<option value="default" <?php if ($this->settings['sceditor_theme'] == "default") echo " SELECTED"; ?> ><?php echo JText::_('sceditor_theme_Default'); ?></option>
							<option value="modern" <?php if ($this->settings['sceditor_theme'] == "modern") echo " SELECTED"; ?> ><?php echo JText::_('sceditor_theme_Modern'); ?></option>
							<option value="office" <?php if ($this->settings['sceditor_theme'] == "office") echo " SELECTED"; ?> ><?php echo JText::_('sceditor_theme_Office'); ?></option>
							<option value="office-toolbar" <?php if ($this->settings['sceditor_theme'] == "office-toolbar") echo " SELECTED"; ?> ><?php echo JText::_('sceditor_theme_Office (Toolbar Only)'); ?></option>
							<option value="square" <?php if ($this->settings['sceditor_theme'] == "square") echo " SELECTED"; ?> ><?php echo JText::_('Square'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_("SET_HELP_sceditor_theme"); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("CONTENT_CSS_MODE"); ?>:
					</td>
					<td style="width:250px;">
						<select name="sceditor_content">
							<option value="default" <?php if ($this->settings['sceditor_content'] == "default") echo " SELECTED"; ?> ><?php echo JText::_('SCEDITOR_CONTENT_DEFAULT'); ?></option>
							<option value="dark" <?php if ($this->settings['sceditor_content'] == "dark") echo " SELECTED"; ?> ><?php echo JText::_('SCEDITOR_CONTENT_DARK'); ?></option>
							<option value="default-trans" <?php if ($this->settings['sceditor_content'] == "default-trans") echo " SELECTED"; ?> ><?php echo JText::_('SCEDITOR_CONTENT_TRANSPARENT'); ?></option>
							<option value="dark-trans" <?php if ($this->settings['sceditor_content'] == "dark-trans") echo " SELECTED"; ?> ><?php echo JText::_('SCEDITOR_CONTENT_DARK_TRANSPARENT'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_("SET_HELP_sceditor_content"); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("USE_EMOTICONS"); ?>:
					</td>
					<td>
						<input type='checkbox' name='sceditor_emoticons' value='1' <?php if ($this->settings['sceditor_emoticons'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_("SET_HELP_sceditor_emoticons"); ?></div>
					</td>
				</tr>	
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("sceditor_buttonhide"); ?>:
					</td>
					<td>
						<input name='sceditor_buttonhide' type="text" id='sceditor_buttonhide' size="40" value='<?php echo $this->settings['sceditor_buttonhide'] ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_("SET_HELP_sceditor_buttonhide"); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("sceditor_paste_user"); ?>:
					</td>
					<td style="width:250px;">
						<select name="sceditor_paste_user">
							<option value="" <?php if ($this->settings['sceditor_paste_user'] == "") echo " SELECTED"; ?> ><?php echo JText::_('sceditor_paste_filtered'); ?></option>
							<option value="raw" <?php if ($this->settings['sceditor_paste_user'] == "raw") echo " SELECTED"; ?> ><?php echo JText::_('sceditor_paste_raw'); ?></option>
							<option value="plaintext" <?php if ($this->settings['sceditor_paste_user'] == "plaintext") echo " SELECTED"; ?> ><?php echo JText::_('sceditor_paste_plain'); ?></option>
							<option value="plainimage" <?php if ($this->settings['sceditor_paste_user'] == "plainimage") echo " SELECTED"; ?> ><?php echo JText::_('sceditor_paste_plainimage'); ?></option>
							<option value="disabled" <?php if ($this->settings['sceditor_paste_user'] == "disabled") echo " SELECTED"; ?> ><?php echo JText::_('sceditor_paste_disabled'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_("SET_HELP_SCEDITOR_PASTE"); ?> <?php echo JText::_("SET_HELP_SCEDITOR_PASTE_RAW"); ?></div>
					</td>
				</tr>				
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("sceditor_paste_admin"); ?>:
					</td>
					<td style="width:250px;">
						<select name="sceditor_paste_admin">
							<option value="" <?php if ($this->settings['sceditor_paste_admin'] == "") echo " SELECTED"; ?> ><?php echo JText::_('sceditor_paste_filtered'); ?></option>
							<option value="raw" <?php if ($this->settings['sceditor_paste_admin'] == "raw") echo " SELECTED"; ?> ><?php echo JText::_('sceditor_paste_raw'); ?></option>
							<option value="plaintext" <?php if ($this->settings['sceditor_paste_admin'] == "plaintext") echo " SELECTED"; ?> ><?php echo JText::_('sceditor_paste_plain'); ?></option>
							<option value="plainimage" <?php if ($this->settings['sceditor_paste_admin'] == "plainimage") echo " SELECTED"; ?> ><?php echo JText::_('sceditor_paste_plainimage'); ?></option>
							<option value="disabled" <?php if ($this->settings['sceditor_paste_admin'] == "disabled") echo " SELECTED"; ?> ><?php echo JText::_('sceditor_paste_disabled'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_("SET_HELP_SCEDITOR_PASTE"); ?> <?php echo JText::_("SET_HELP_SCEDITOR_PASTE_RAW"); ?></div>
					</td>
				</tr>	
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_("EMAIL_SENDING_SETTINGS"); ?></legend>

			<div style="margin-bottom: 8px;"><a href="#" class="btn btn-default" id="send_test_email">Send test email</a> <input type="text" id="email_test_address" placeholder="Test email address" style='margin: 0;'/> <span id="email_test_result"></span></div>
			
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("email_send_multiple"); ?>:
					</td>
					<td>
						<select name="email_send_multiple">
							<option value="multi" <?php if ($this->settings['email_send_multiple'] == "multi") echo " SELECTED"; ?> ><?php echo JText::_('email_send_multiple_multi'); ?></option>
							<option value="to" <?php if ($this->settings['email_send_multiple'] == "to") echo " SELECTED"; ?> ><?php echo JText::_('email_send_multiple_to'); ?></option>
							<option value="bcc" <?php if ($this->settings['email_send_multiple'] == "bcc") echo " SELECTED"; ?> ><?php echo JText::_('email_send_multiple_bcc'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_("SET_HELP_email_send_multiple"); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("OVERRIDE_JOOMLA_EMAIL_SEND_SETTINGS"); ?>:
					</td>
					<td>
						<input type='checkbox' name='email_send_override' value='1' <?php if ($this->settings['email_send_override'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("COM_CONFIG_FIELD_MAIL_MAILER_LABEL"); ?>:
					</td>
					<td style="width:250px;">
						<select name="email_send_mailer">
							<option value="mail" <?php if ($this->settings['email_send_mailer'] == "mail") echo " SELECTED"; ?> ><?php echo JText::_('COM_CONFIG_FIELD_VALUE_PHP_MAIL'); ?></option>
							<option value="sendmail" <?php if ($this->settings['email_send_mailer'] == "sendmail") echo " SELECTED"; ?> ><?php echo JText::_('COM_CONFIG_FIELD_VALUE_SENDMAIL'); ?></option>
							<option value="smtp" <?php if ($this->settings['email_send_mailer'] == "smtp") echo " SELECTED"; ?> ><?php echo JText::_('COM_CONFIG_FIELD_VALUE_SMTP'); ?></option>
						</select>
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("COM_CONFIG_FIELD_MAIL_FROM_EMAIL_LABEL"); ?>:
					</td>
					<td style="width:250px;">
						<input name='email_send_from_email' type="text" id='email_send_from_email' size="40" value='<?php echo $this->settings['email_send_from_email'] ?>'>
					</td>
					<td>
					</td>
				</tr>		
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("COM_CONFIG_FIELD_MAIL_FROM_NAME_LABEL"); ?>:
					</td>
					<td style="width:250px;">
						<input name='email_send_from_name' type="text" id='email_send_from_name' size="40" value='<?php echo $this->settings['email_send_from_name'] ?>'>
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("COM_CONFIG_FIELD_MAIL_SMTP_AUTH_LABEL"); ?>:
					</td>
					<td>
						<input type='checkbox' name='email_send_smtp_auth' value='1' <?php if ($this->settings['email_send_smtp_auth'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
					</td>
				</tr>			
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("COM_CONFIG_FIELD_MAIL_SMTP_SECURE_LABEL"); ?>:
					</td>
					<td style="width:250px;">
						<select name="email_send_smtp_security">
							<option value="none" <?php if ($this->settings['email_send_smtp_security'] == "none") echo " SELECTED"; ?> ><?php echo JText::_('COM_CONFIG_FIELD_VALUE_NONE'); ?></option>
							<option value="ssl" <?php if ($this->settings['email_send_smtp_security'] == "ssl") echo " SELECTED"; ?> ><?php echo JText::_('COM_CONFIG_FIELD_VALUE_SSL'); ?></option>
							<option value="tls" <?php if ($this->settings['email_send_smtp_security'] == "tls") echo " SELECTED"; ?> ><?php echo JText::_('COM_CONFIG_FIELD_VALUE_TLS'); ?></option>
						</select>
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("COM_CONFIG_FIELD_MAIL_SMTP_PORT_LABEL"); ?>:
					</td>
					<td style="width:250px;">
						<input name='email_send_smtp_port' type="text" id='email_send_smtp_port' size="40" value='<?php echo $this->settings['email_send_smtp_port'] ?>'>
					</td>
					<td>
					</td>
				</tr>		
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("COM_CONFIG_FIELD_MAIL_SMTP_USERNAME_LABEL"); ?>:
					</td>
					<td style="width:250px;">
						<input name='email_send_smtp_un' autocomplete="off" type="text" id='email_send_smtp_un' size="40" value='<?php echo $this->settings['email_send_smtp_username'] ?>'>
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("COM_CONFIG_FIELD_MAIL_SMTP_PASSWORD_LABEL"); ?>:
					</td>
					<td style="width:250px;">
						<input name='email_send_smtp_pw' autocomplete="off" type="password" readonly onfocus="this.removeAttribute('readonly');" id='email_send_smtp_pw' size="40" value='<?php echo $this->settings['email_send_smtp_password'] ?>'>
					</td>
					<td>
						<span style='color:red;'>NOTE: Please click the field to change password.</span>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("COM_CONFIG_FIELD_MAIL_SMTP_HOST_LABEL"); ?>:
					</td>
					<td style="width:250px;">
						<input name='email_send_smtp_host' type="text" id='email_send_smtp_host' size="40" value='<?php echo $this->settings['email_send_smtp_host'] ?>'>
					</td>
					<td>
					</td>
				</tr>		
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("COM_CONFIG_FIELD_MAIL_SENDMAIL_PATH_LABEL"); ?>:
					</td>
					<td style="width:250px;">
						<input name='email_send_sendmail_path' type="text" id='email_send_sendmail_path' size="40" value='<?php echo $this->settings['email_send_sendmail_path'] ?>'>
					</td>
					<td>
					</td>
				</tr>		
			
			</table>
		</fieldset>


		<fieldset class="adminform">
			<legend><?php echo JText::_("MENU_ITEMS_LINK_GENERATION"); ?></legend>

	<!--
		  <field name="menuitem_use" type="fsjyesno" default="" display="custom" custom_yes="FSJ_INCLUDE" custom_no="FSJ_EXCLUDE" useglobal_text="FSJ_IGNORE" >
			<label>Menu Item</label>
			<description></description>
		  </field>
		  <field name="menuitem" type="FSJMenuItem" default="" hide_buttons="1">
			<label></label>
			<description></description>
		  </field>
	-->

			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;border:none;">
						<?php echo JText::_("STICKY_MENU_ITEMS"); ?>:
					</td>
					<td style="width:250px;border:none;">
						<select name="sticky_menus_type">
							<option value="" <?php if ($this->settings['sticky_menus_type'] == "") echo " SELECTED"; ?> ><?php echo JText::_('STICKY_IGNORE'); ?></option>
							<option value="1" <?php if ($this->settings['sticky_menus_type'] == "1") echo " SELECTED"; ?> ><?php echo JText::_('STICKY_INCLUDE'); ?></option>
							<option value="2" <?php if ($this->settings['sticky_menus_type'] == "2") echo " SELECTED"; ?> ><?php echo JText::_('STICKY_EXCLUDE'); ?></option>
						</select>
					</td>
					<td rowspan="2" valign="top">
						<div class='fss_help'>
							<?php echo JText::_('STICKY_HELP'); ?>
						</div>
					</td>			
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
					</td>
					<td style="width:250px;">
						<?php
						require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_fss'.DS.'models'.DS.'fields'.DS.'fsschecklist.php');
						require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_fss'.DS.'models'.DS.'fields'.DS.'fssmenuitem.php');
						$c = new JFormFieldFSSMenuItem();
						$c->setup(new SimpleXMLElement("<field name='sticky_menus' />"), $this->settings['sticky_menus']);
						echo $c->getInput();
						?>
					</td>
				</tr>

			</table>
		</fieldset>
