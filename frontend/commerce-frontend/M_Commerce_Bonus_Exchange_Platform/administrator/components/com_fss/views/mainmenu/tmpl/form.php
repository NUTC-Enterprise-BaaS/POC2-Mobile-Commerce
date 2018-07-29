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

var to_translate = {
	title: {
		title: "<?php echo JText::_("TITLE"); ?>",
		type: 'input'
		},
	description: {
		title: "<?php echo JText::_("DESCRIPTION"); ?>",
		type: 'html'
		},
}

jQuery(document).ready( function () {
	jQuery('[name=body]').attr('id','body');
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
    <?php
    $editor = JFactory::getEditor();
    echo $editor->save( 'description' );
    ?>
	
	var url = '<?php echo JRoute::_('index.php?option=com_fss&view=translate&tmpl=component', false); ?>&data=' + encodeURIComponent(JSON.stringify(to_translate));
	TINY.box.show({iframe:url, width:820,height:640});
}

Joomla.submitbutton = function(pressbutton) {
	if (pressbutton == "translate") {
		return doTranslate();
	}
	Joomla.submitform(pressbutton);
}

function changeMenuItem()
{
	var value = $('menuitem').value;
	var itemid = value.substr(0,value.indexOf('|'));
	var link = value.substr(value.indexOf('|')+1);
	
	$('itemid').value = itemid;
	$('link').value = link;
}
//-->
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
			<td colspan="2">
				<input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->mainmenu->title);?>" />
			</td>
			<td>
				<div id="trprev_title"></div>
			</td>
		</tr>
		<?php FSSAdminHelper::LA_Form($this->mainmenu); ?>
		<tr>
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("DESCRIPTION"); ?>:
				</label>
			</td>
			<td colspan="2">
				<?php
				$editor = JFactory::getEditor();
				echo $editor->display('description', htmlspecialchars($this->mainmenu->description, ENT_COMPAT, 'UTF-8'), '550', '200', '60', '20', array('pagebreak', 'readmore'));
				?>
            </td>
			<td>
				<div id="trprev_description"></div>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="image">
					<?php echo JText::_("IMAGE"); ?>:
				</label>
			</td>
			<td colspan="2">
				<?php echo $this->lists['images']; ?>
				<?php echo JText::_("FOUND_IN_IMAGES_FSS_MENU"); ?>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="image">
					<?php echo JText::_("TYPE"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['types']; ?>
			</td>
		</tr>
		<?php //if ($this->mainmenu->itemtype != 7): ?>
				
		<?php if (array_key_exists('menuitems', $this->lists)): ?>
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
					<?php echo JText::_("MENU_ITEM"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['menuitems']; ?>
			</td>
			<td><?php echo JText::_('MENU_ITEM_MULTI_ITEMS'); ?></tr>
		</tr>
		<?php endif; ?>
		<input type="hidden" name="itemid" id="itemid" value="<?php echo FSS_Helper::escape($this->mainmenu->itemid);?>" />
		<!--<input type="hidden" name="link" id="link" value="<?php echo FSS_Helper::escape($this->mainmenu->link);?>" />-->
		
		<?php //else: ?>
		
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
					<?php echo JText::_("LINK"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="link" id="link" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->mainmenu->link);?>" />
			</td>
			<td><?php echo JText::_('ONLY_WHEN_LINK'); ?></td>
		</tr>
		
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
					<?php echo JText::_("TARGET"); ?>:
				</label>
			</td>
			<td>
				<input type="text" name="target" id="target" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->mainmenu->target);?>" />
			</td>
			<td><?php echo JText::_('ONLY_WHEN_LINK'); ?></td>
		</tr>
		<?php //endif; ?>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->mainmenu->id; ?>" />
<input type="hidden" name="ordering" value="<?php echo $this->mainmenu->ordering; ?>" />
<input type="hidden" name="published" value="<?php echo $this->mainmenu->published; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="mainmenu" />
<input type="hidden" name="translation" id="translation" value="<?php echo htmlEntities($this->mainmenu->translation,ENT_QUOTES,"utf-8"); ?>" />
</form>

