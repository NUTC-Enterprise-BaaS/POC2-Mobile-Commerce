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
	section: {
		title: "<?php echo JText::_("LIST_HEADING"); ?>",
		type: 'textarea'
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

function DoAllDeptChange()
{
	var form = document.adminForm;
	var deptlist = document.getElementById('deptlist');
		
	if (form.alldepts[1].checked)
    {
		deptlist.style.display = 'none';
	} else {
		deptlist.style.display = 'inline';
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
					<?php echo JText::_("TITLE"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->ticketcat->title);?>" />
			</td>
			<td>
				<div id="trprev_title"></div>
			</td>
		</tr>
		<?php FSSAdminHelper::LA_Form($this->ticketcat, true); ?>
		<tr>
			<td width="135" align="right" class="key">
				<label for="section">
					<?php echo JText::_("LIST_HEADING"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="section" id="section" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->ticketcat->section);?>" />
			</td>
			<td>
				<div id="trprev_section"></div>
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
					<?php echo JText::_("SHOW_FOR_ALL_PRODUCTS"); ?>
					<?php echo $this->lists['allprod']; ?>
				</div>
				<div id="prodlist" <?php if ($this->allprods) echo 'style="display:none;"'; ?>>
					<?php echo $this->lists['products']; ?>
				</div>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="eh">
					<?php echo JText::_("DEPARTMENTS"); ?>:
				</label>
			</td>
			<td>
				<div>
					<?php echo JText::_("SHOW_FOR_ALL_DEPARTMENTS"); ?>
					<?php echo $this->lists['alldept']; ?>
				</div>
				<div id="deptlist" <?php if ($this->alldepts) echo 'style="display:none;"'; ?>>
					<?php echo $this->lists['departments']; ?>
				</div>
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->ticketcat->id; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="ticketcat" />
<input type="hidden" name="translation" id="translation" value="<?php echo htmlEntities($this->ticketcat->translation,ENT_QUOTES,"utf-8"); ?>" />
</form>

