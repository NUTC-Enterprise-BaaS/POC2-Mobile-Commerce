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
	subject: {
		title: "<?php echo JText::_("SUBJECT"); ?>",
		type: 'input'
		},
	body: {
		title: "<?php echo JText::_("TEMPLATE"); ?>",
		type: '<?php if ($this->email->ishtml == 1) { echo "html";} else { echo "textarea"; } ?>'
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
		echo $editor->save( 'body_html' );
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

//-->
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
		echo $editor->save( 'body_html' );
    ?>
        submitform(pressbutton);
}
//-->

function toggleHtmlEmail()
{
	if ($('ishtml').checked)
	{
		$('email_body_html').style.display = 'block';
		$('email_body_text').style.display = 'none';
	} else {
		$('email_body_html').style.display = 'none';
		$('email_body_text').style.display = 'block';
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
				<label for="question">
					<?php echo JText::_("TEMPLATE"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->email->tmpl; ?>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_("DESCRIPTION"); ?>:
				</label>
			</td>
			<td>
				<?php echo JText::_($this->email->description); ?>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_("IS_HTML"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' onclick="toggleHtmlEmail()" id="ishtml" name='ishtml' value='1' <?php if ($this->email->ishtml == 1) { echo " checked='yes' "; } ?>>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_("SUBJECT"); ?>:
				</label>
			</td>
			<td>
				<input name="subject" id="subject" size="80" value="<?php echo $this->email->subject; ?>">
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="answer">
					<?php echo JText::_("TEMPLATE"); ?>:
				</label>
			</td>
			<td>
				<div id="email_body_html" <?php if ($this->email->ishtml == 0) { echo " style='display:none;' "; } ?>>
				<?php
				    $editor = JFactory::getEditor();
				echo $editor->display('body_html', htmlspecialchars($this->email->body, ENT_COMPAT, 'UTF-8'), '550', '400', '60', '20', array('pagebreak'));
				?>
				</div>
				<div id="email_body_text" <?php if ($this->email->ishtml == 1) { echo " style='display:none;' "; } ?>>
					<textarea name="body" id="body" cols="100" rows="20" style="width:100%"><?php 
					JRequest::setVar('body',$this->email->body);
					$this->email->body = JRequest::getVar('body');
					echo $this->email->body; 
					?></textarea>
				</div>
            </td>

		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_("HELP"); ?>:
				</label>
			</td>
			<td>
				<?php echo IncludeHelp($this->email->tmpl); ?>
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->email->id; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="email" />
<input type="hidden" name="tmpl" value="<?php echo $this->email->tmpl; ?>" />
<input type="hidden" name="description" value="<?php echo $this->email->description; ?>" />
<input type="hidden" name="translation" id="translation" value="<?php echo htmlEntities($this->email->translation,ENT_QUOTES,"utf-8"); ?>" />

</form>

<?php 
function IncludeHelp($tmpl)
{
	if ($tmpl == "comment")
	{
		return FSSAdminHelper::IncludeHelp('email_comment.html');
	} elseif ($tmpl == "messagerow")
	{
		return FSSAdminHelper::IncludeHelp('email_messagerow.html');
	} else {
		return FSSAdminHelper::IncludeHelp('email_support.html');
	}
}