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
			echo $editor->save( 'description' );
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
				<input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->faqcat->title);?>" />
			</td>
		</tr>
		<?php FSSAdminHelper::LA_Form($this->faqcat); ?>
		<tr>
			<td width="135" align="right" class="key">
				<label for="image">
					<?php echo JText::_("IMAGE"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['images']; ?>
				<?php echo JText::_("FOUND_IN_IMAGES_FSS_FAQCATS"); ?>
			</td>
		</tr>		
		<tr>
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("DESCRIPTION"); ?>:
				</label>
			</td>
			<td>
				<?php
				$editor = JFactory::getEditor();
				echo $editor->display('description', htmlspecialchars($this->faqcat->description, ENT_COMPAT, 'UTF-8'), '550', '200', '60', '20', array('pagebreak', 'readmore'));
				?>
            </td>

		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->faqcat->id; ?>" />
<input type="hidden" name="ordering" value="<?php echo $this->faqcat->ordering; ?>" />
<input type="hidden" name="published" value="<?php echo $this->faqcat->published; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="faqcat" />
</form>

			  				  	 			