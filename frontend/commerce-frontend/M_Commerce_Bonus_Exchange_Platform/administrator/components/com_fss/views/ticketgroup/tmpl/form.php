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
<style>
label {
	width: auto !important;
	float: none !important;
	clear: none !important;
	display: inline !important;
}
input {
	float: none !important;
	clear: none !important;
	display: inline !important;
}
</style>

<script language="javascript" type="text/javascript">
<!--
function submitbutton(pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'cancel') {
                submitform( pressbutton );
                return;
        }
        submitform(pressbutton);
}
//-->

function DoAllProdChange()
{
	var form = document.adminForm;
	var prodlist = document.getElementById('prodlist');
		
	if (form.allprods[1].checked)
    {
		prodlist.style.display = 'none';
	} else {
		prodlist.style.display = 'inline';
	}
}

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div>
	<fieldset class="adminform">
		<legend><?php echo JText::_("DETAILS"); ?></legend>

		<table class="admintable">
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
					<?php echo JText::_("NAME"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="groupname" id="groupname" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->ticketgroup->groupname);?>" />
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
					<?php echo JText::_("DESCRIPTION"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="description" id="description" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->ticketgroup->description);?>" />
			</td>
		</tr>
		<tr>
			<td width="150" align="right" class="key">
				<label for="description">
					<?php echo JText::_("ALL_SEE"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->allsee; ?>
					<?php echo JText::_("ALL_SEE_HELP"); ?>
            </td>
		</tr>
		<tr>
			<td width="150" align="right" class="key">
				<label for="description">
					<?php echo JText::_("ALL_EMAIL"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='allemail' value='1' <?php if ($this->ticketgroup->allemail) { echo " checked='yes' "; } ?>>
					<?php echo JText::_("ALL_EMAIL_HELP"); ?>
            </td>
		</tr>
		<tr>
			<td width="150" align="right" class="key">
				<label for="description">
					<?php echo JText::_("CCEXCLUDE"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='ccexclude' value='1' <?php if ($this->ticketgroup->ccexclude) { echo " checked='yes' "; } ?>>
					<?php echo JText::_("CCEXCLUDE_HELP"); ?>
            </td>
		</tr>
			<tr>
			    <td width="135" align="right" class="key">
				    <label for="eh">
						<?php echo JText::_("PRODUCTS"); ?>:
				    </label>
			    </td>
			    <td>
					<div>
						<?php echo JText::_("SHOW_ALL_PRODUCTS_ON_TICKET_OPEN"); ?>
						<?php echo $this->allprod; ?>
					</div>
					<div id="prodlist" <?php if ($this->allprods) echo 'style="display:none;"'; ?>>
						<?php echo $this->products; ?>
					</div>
			    </td>
		    </tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->ticketgroup->id; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="ticketgroup" />
</form>

