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
		title: "<?php echo JText::_("PRIORITY"); ?>",
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
					<?php echo JText::_("PRIORITY"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->ticketpri->title);?>" />
			</td>
			<td>
				<div id="trprev_title"></div>
			</td>
		</tr>
		<?php FSSAdminHelper::LA_Form($this->ticketpri, true); ?>
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
					<?php echo JText::_("COLOR"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="color" id="color" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->ticketpri->color);?>" />
			</td>
			<td>
				<span id="color_example" style="padding: 4px;background-color: white; color: <?php echo $this->ticketpri->color; ?>">Example</span>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
					<?php echo JText::_("BACKGROUNDCOLOR"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="backcolor" id="backcolor" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->ticketpri->backcolor);?>" />
			</td>
			<td>
				<span id="backcolor_example" style="padding: 4px;background-color: <?php echo $this->ticketpri->backcolor; ?>">Example</span>
			</td>
		</tr>	
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->ticketpri->id; ?>" />
<input type="hidden" name="ordering" value="<?php echo $this->ticketpri->ordering; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="ticketpri" />
<input type="hidden" name="translation" id="translation" value="<?php echo htmlEntities($this->ticketpri->translation,ENT_QUOTES,"utf-8"); ?>" />
</form>

<script>
jQuery(document).ready( function () {
	jQuery('#color').keyup( function () {
		jQuery('#color_example').css('color', 'black');
		var color = jQuery('#color').val();
		jQuery('#color_example').css('color', color);
	});
	jQuery('#backcolor').keyup( function () {
		jQuery('#backcolor_example').css('background-color', 'white');
		var color = jQuery('#backcolor').val();
		jQuery('#backcolor_example').css('background-color', color);
	});
});
</script>

