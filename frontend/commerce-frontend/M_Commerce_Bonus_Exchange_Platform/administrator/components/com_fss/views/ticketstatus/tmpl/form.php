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


var to_translate = {
	title: {
		title: "<?php echo JText::_("TITLE"); ?>",
		type: 'input'
		},
	userdisp: {
		title: "<?php echo JText::_("DISPLAY_TO_USER_AS"); ?>",
		type: 'input'
		}
}

jQuery(document).ready( function () {
	displayTranslations();
});

<?php 

$langs = array();
$jl = JLanguage::getKnownLanguages();
foreach ($jl as $key => $language)
{
	$langs[] = str_replace("-", "", $language['tag']) . ": '" . $language['name'] . "'";
}

?>

var tr_langs = { <?php echo implode(", ", $langs); ?> };

function doTranslate()
{
	var url = '<?php echo JRoute::_('index.php?option=com_fss&view=translate&tmpl=component', false); ?>&data=' + encodeURIComponent(JSON.stringify(to_translate));
	TINY.box.show({iframe:url, width:820,height:640});
}

Joomla.submitbutton = function(pressbutton) {
	if (pressbutton == "translate") {
		return doTranslate();
	}
	Joomla.submitform(pressbutton);
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
					<?php echo JText::_("TITLE"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->ticketstatus->title);?>" />
			</td>
			<td>
				<div id="trprev_title"></div>
			</td>

		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
					<?php echo JText::_("DISPLAY_TO_USER_AS"); ?>:<br/>
					<span style="color:#666666;font-size:80%">(Leave blank to use title)</span>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="userdisp" id="userdisp" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->ticketstatus->userdisp);?>" />
			</td>
			<td>
				<div id="trprev_userdisp"></div>
			</td>		
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="section">
					<?php echo JText::_("COLOR"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="color" id="color" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->ticketstatus->color);?>" />
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="section">
					<?php echo JText::_("Combine for users with"); ?>:
				</label>
			</td>
			<td>
				<select name="combine_with">
					<option value="0">Dont Combine</option>
					<?php foreach (SupportHelper::getStatuss() as $status): ?>
						<?php if ($status->id == $this->ticketstatus->id) continue; ?>
						<option value="<?php echo $status->id; ?>" <?php if ($this->ticketstatus->combine_with == $status->id) echo "selected"; ?>><?php echo $status->title; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
			<td>
			If this is set, then users will see the all tickets of this status type as if they were tickets of the selected type. Allows the creating of "Internal" type status where only the ticket handlers can see the actual status.
			</td>
		</tr>

	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->ticketstatus->id; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="ticketstatus" />
<input type="hidden" name="translation" id="translation" value="<?php echo htmlEntities($this->ticketstatus->translation,ENT_QUOTES,"utf-8"); ?>" />
<input type="hidden" name="ordering" value="<?php echo $this->ticketstatus->ordering; ?>" />
</form>

