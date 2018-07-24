<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

?>

<?php echo JHTML::_( 'form.token' ); ?>

<script language="javascript" type="text/javascript">
<!--
function submitbutton(pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'cancel') {
                submitform( pressbutton );
                return;
        }

        <?php
																        ?>
        submitform(pressbutton);
}
//-->
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable table table-striped table-condensed">
		<tr>
			<td width="250" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'ACCOUNT_NAME' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<input class="text_area" type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->item->name);?>" />
			</td>
		</tr>		<tr>
			<td align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'SERVER_ADDRESS' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<input class="text_area" type="text" name="server" id="server" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->item->server);?>" />
			</td>
		</tr>		
		<tr>
			<td align="right" class="key">
				<label for="answer">
					<?php echo JText::_( 'SERVER_TYPE' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['type']; ?>
            </td>
			<td><span style='color: red; font-weight: bold;'>Please use an IMAP account here for optimum functionality.</span><br ></span>Only use POP3 if an IMAP account is not available. POP3 is only still listed for compatability reasons, and can cause issues with email loops.</span></td>
		</tr>		
		<tr>
			<td align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'Port' ); ?>:
				</label>
			</td colspan="2">
			<td colspan="2">
				<input class="text_area" type="text" name="port" id="port" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->item->port);?>" />
			</td>
		</tr>		<tr>
			<td align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'Username' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<input class="text_area" type="text" name="username" id="username" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->item->username);?>" />
			</td>
		</tr>		
		<tr>
			<td align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'Password' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<input class="text_area" type="password" name="password" id="password" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->item->password);?>" />
			</td>
		</tr>		
		<tr>
			<td align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'CHECK_INTERVAL_IN_MINUTES' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<input class="text_area" type="text" name="checkinterval" id="checkinterval" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->item->checkinterval);?>" />
			</td>
		</tr>
		
		<tr>
			<td align="right" class="key">
				<label for="answer">
					<?php echo JText::_( 'ALLOW_TICKETS_FROM' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<?php echo $this->lists['newticketsfrom']; ?>
            </td>

		</tr>
		<tr>
			<td align="right" class="key">
				<label for="answer">
					<?php echo JText::_( 'ALLOW_RESPONSES_TO_TICKETS_FROM_ANY_ADDRESS' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<input type='checkbox' name='allowunknown' id='allowunknown' value='1' <?php if ($this->item->allowunknown == 1) { echo " checked='yes' "; } ?>>
            </td>

		</tr>
		<tr>
			<td align="right" class="key">
				<label for="allowrepliesonly">
					<?php echo JText::_( 'ONLY_IMPORT_REPLIES_TO_TICKETS' ); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='allowrepliesonly' id='allowrepliesonly' value='1' <?php if ($this->item->allowrepliesonly == 1) { echo " checked='yes' "; } ?>>
            </td>
			<td>
				<?php echo JText::_( 'NO_NEW_TICKETS_WILL_BE_CREATED__ONLY_REPLIES_TO_EXISTING_TICEKTS_WILL_BE_IMPORTED_' ); ?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="answer">
					<?php echo JText::_( 'CONFIRM_NEW_TICKETS' ); ?>:
				</label>
			</td>
			<td>
				<select name="confirmnew">
					<option value="0" <?php if ($this->item->confirmnew == "0") echo " SELECTED"; ?> ><?php echo JText::_('JNONE'); ?></option>
					<option value="1" <?php if ($this->item->confirmnew == "1") echo " SELECTED"; ?> ><?php echo JText::_('JALL'); ?></option>
					<option value="2" <?php if ($this->item->confirmnew == "2") echo " SELECTED"; ?> ><?php echo JText::_('ONLY_UNREGISTERED'); ?></option>
				</select>
            </td>
			<td>
				<?php echo JText::_('NEW_TICKETS_WILL_HAVE_TO_BE'); ?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="answer">
					<?php echo JText::_( 'AFTER_IMPORTING_AN_EMAIL' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['onimport']; ?>
            </td>
			<td>	
				<?php echo JText::_('THIS_OPTION_IS_ALWAYS_DELETE'); ?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'email_import_closed_tickets' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<select name="closedticket">
					<option value="0" <?php if ($this->item->closedticket == "0") echo " SELECTED"; ?> ><?php echo JText::_('EMAIL_IMPORT_CLOSED_TICKETS_0'); ?></option>
					<option value="1" <?php if ($this->item->closedticket == "1") echo " SELECTED"; ?> ><?php echo JText::_('EMAIL_IMPORT_CLOSED_TICKETS_1'); ?></option>
					<option value="2" <?php if ($this->item->closedticket == "2") echo " SELECTED"; ?> ><?php echo JText::_('EMAIL_IMPORT_CLOSED_TICKETS_2'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'Product' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<?php echo $this->lists['prod_id']; ?>
			</td>
		</tr>			<tr>
			<td align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'DEPARTMENT' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<?php echo $this->lists['dept_id']; ?>
			</td>
		</tr>			<tr>
			<td align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'Category' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<?php echo $this->lists['cat_id']; ?>
			</td>
		</tr>			<tr>
			<td align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'Priority' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<?php echo $this->lists['pri_id']; ?>
			</td>
		</tr>			<tr>
			<td align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'Handler' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<?php echo $this->lists['handler']; ?>
			</td>
		</tr>			
		<tr>
			<td align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'USE_SSL' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<input type='checkbox' name='usessl' id='usessl' value='1' <?php if ($this->item->usessl == 1) { echo " checked='yes' "; } ?>>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'USE_TLS' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<?php echo $this->lists['usetls']; ?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'VALIDATE_SERVER_CERTIFICATE' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<select name="validatecert">
					<option value="0" <?php if ($this->item->validatecert == "0") echo " SELECTED"; ?> ><?php echo JText::_('Automatic'); ?></option>
					<option value="1" <?php if ($this->item->validatecert == "1") echo " SELECTED"; ?> ><?php echo JText::_('Yes'); ?></option>
					<option value="2" <?php if ($this->item->validatecert == "2") echo " SELECTED"; ?> ><?php echo JText::_('No'); ?></option>
					<option value="3" <?php if ($this->item->validatecert == "3") echo " SELECTED"; ?> ><?php echo JText::_('Do not specify'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'ALLOW_FROM_JOOMLA_ADDRESS' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<input type='checkbox' name='allow_joomla' id='allow_joomla' value='1' <?php if ($this->item->allow_joomla == 1) { echo " checked='yes' "; } ?>>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="answer">
					<?php echo JText::_( 'LIMIT_TO_RECIEVED_ADDRESS' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<textarea rows="10" cols="60" name='toaddress' style="width: 100%"><?php echo $this->item->toaddress; ?></textarea>
				<div><?php echo JText::_('LEAVE_BLANK_TO_ACCEPT_EMAIL_SENT_TO'); ?></div>
            </td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="answer">
					<?php echo JText::_( 'IGNORE_SENDER_ADDRESS' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<textarea rows="10" cols="60" name='ignoreaddress' style="width: 100%"><?php echo $this->item->ignoreaddress; ?></textarea>
				<div>
					<?php echo JText::_('LEAVE_BLANK_TO_HAVE_NO_RESTRICTIONS_ON_SENDER'); ?><br>
				</div>
            </td>
			
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="answer">
					<?php echo JText::_( 'IGNORE_SUBJECT' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<textarea rows="10" cols="60" name='ignoresubject' style="width: 100%"><?php echo $this->item->ignoresubject; ?></textarea>
				<div>
					<?php echo JText::_('LEAVE_BLANK_TO_HAVE_NO_RESTRICTIONS_ON_SUBJECT_ADDRESS'); ?>
				</div>
            </td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'CONNECT_STRING_OVERRIDE' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<input class="text_area" type="text" name="connectstring" id="connectstring" style="width: 100%" size="80" maxlength="250" value="<?php echo FSS_Helper::escape($this->item->connectstring);?>" />
				<?php echo JText::_('DO_NOT_SET_THIS_UNLESS_YOU_KNOW_WHAT_YOU_ARE_DOING'); ?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="question">
					<?php echo JText::_( 'Import EMails as HTML' ); ?>:
				</label>
			</td>
			
			<?php if (empty($this->item->import_html)) $this->item->import_html = 0; ?>
						
			<td colspan="2">
				<input type='checkbox' name='import_html' id='import_html' value='1' <?php if ($this->item->import_html == 1) { echo " checked='yes' "; } ?>>
				<div style='color:red;font-weight:bold;'>Enabling this can expose your site to many security issues, and Freestyle Joomla highly recommend
				NOT enabling this option. If you do enable this, Freestlye Joomla are not responsible for any problems this may cause.</div>
				<div>You will also need to enable the "Allow display of html from imported emails" option in the settings pages for this to work</div>
			</td>
		</tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>

<!---->

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
<input type="hidden" name="cronid" value="<?php echo $this->item->cronid; ?>" />
<input type="hidden" name="published" value="<?php echo $this->item->published; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="ticketemail" />
</form>
