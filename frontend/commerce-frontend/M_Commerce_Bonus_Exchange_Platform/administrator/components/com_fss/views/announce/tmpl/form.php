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
                $editor = JFactory::getEditor();
        echo $editor->save( 'body' );
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
				<label for="title">
					<?php echo JText::_("TITLE"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->announce->title);?>" />
			</td>
		</tr>
		<?php FSSAdminHelper::LA_Form($this->announce); ?>
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
					<?php echo JText::_("DESCRIPTION_FOR_MODULE"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="subtitle" id="subtitle" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->announce->subtitle);?>" />
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="body">
					<?php echo JText::_("DESCRIPTION"); ?>:
				</label>
			</td>
			<td>
				<?php
				$editor = JFactory::getEditor();
                $text = $this->announce->body;
                if ($this->announce->fulltext)
                {
                    $text .= '<hr id="system-readmore" />';
                    $text .= $this->announce->fulltext;                     
                }
				echo $editor->display('body', htmlspecialchars($text, ENT_COMPAT, 'UTF-8'), '550', '400', '60', '20', array('pagebreak'));
				?>
            </td>

		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->announce->id; ?>" />
<input type="hidden" name="published" value="<?php echo $this->announce->published; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="announce" />
</form>

