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
					<?php echo JText::_("CATEGORY"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['catid']; ?>
			</td>
		</tr>		
		<?php FSSAdminHelper::LA_Form($this->faq); ?>
		<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_("QUESTION"); ?>:
				</label>
			</td>
			<td>
				<textarea name="question" id="question" cols="80" rows="4" style="width:544px;"><?php echo FSS_Helper::escape($this->faq->question);?></textarea>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="answer">
					<?php echo JText::_("ANSWER"); ?>:
				</label>
			</td>
			<td>
				<?php
				$editor = JFactory::getEditor();
                $text = $this->faq->answer;
                if ($this->faq->fullanswer)
                {
                    $text .= '<hr id="system-readmore" />';
                    $text .= $this->faq->fullanswer;                     
                }
				echo $editor->display('answer', htmlspecialchars($text, ENT_COMPAT, 'UTF-8'), '550', '400', '60', '20', array('pagebreak'));
				?>
            </td>

		</tr>		<tr>
			<td width="135" align="right" class="key">
				<label for="answer">
					<?php echo JText::_("TAGS"); ?>:
				</label>
			</td>
			<td>
				<?php echo JText::_('TAG_HELP'); ?>
				<textarea name='tags' id='tags' cols="100" rows="10" style="width: 100%"><?php foreach ($this->tags as &$tag) echo $tag->tag . "\n"; ?></textarea>
            </td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->faq->id; ?>" />
<input type="hidden" name="ordering" value="<?php echo $this->faq->ordering; ?>" />
<input type="hidden" name="published" value="<?php echo $this->faq->published; ?>" />
<input type="hidden" name="author" value="<?php echo $this->faq->author; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="faq" />
</form>

