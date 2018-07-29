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


	var to_translate = {
	message: {
		title: "<?php echo JText::_("Message"); ?>",
		type: 'html'
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

<script language="javascript" type="text/javascript">
<!--
function submitbutton(pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'cancel') {
                submitform( pressbutton );
                return;
        }

        <?php
                $editor = JFactory::getEditor();
        echo $editor->save( 'answer' );
        ?>
        submitform(pressbutton);
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
				<label for="question">
					<?php echo JText::_("Identifier"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->item->identifier; ?>
			</td>
		</tr>		
		<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_("Group"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->item->group; ?>
			</td>
		</tr>		
		<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_("Description"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->item->description; ?>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="answer">
					<?php echo JText::_("Text"); ?>:
				</label>
			</td>
			<td>
				<?php
				$editor = JFactory::getEditor();
				echo $editor->display('message', htmlspecialchars($this->item->message, ENT_COMPAT, 'UTF-8'), '550', '400', '60', '20', array('pagebreak'));
				?>
            </td>

		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="identifier" value="<?php echo $this->item->identifier; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="helptext" />
<input type="hidden" name="translation" id="translation" value="<?php echo htmlEntities($this->item->translation,ENT_QUOTES,"utf-8"); ?>" />

</form>

