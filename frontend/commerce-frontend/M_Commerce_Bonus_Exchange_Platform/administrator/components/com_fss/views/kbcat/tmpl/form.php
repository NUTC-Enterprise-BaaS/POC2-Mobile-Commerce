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
//-->
</script>

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
				<input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->kbcat->title);?>" />
			</td>
		</tr>
		<?php FSSAdminHelper::LA_Form($this->kbcat); ?>
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
					<?php echo JText::_("PARENT_CATEGORY"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['parcatid']; ?>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="image">
					<?php echo JText::_("IMAGE"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['images']; ?>
				<?php echo JText::_("FOUND_IN_IMAGES_FSS_KBCATS"); ?>
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
				echo $editor->display('description', htmlspecialchars($this->kbcat->description, ENT_COMPAT, 'UTF-8'), '550', '200', '60', '20', array('pagebreak', 'readmore'));
				?>
            </td>

		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
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
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->kbcat->id; ?>" />
<input type="hidden" name="ordering" value="<?php echo $this->kbcat->ordering; ?>" />
<input type="hidden" name="published" value="<?php echo $this->kbcat->published; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="kbcat" />
</form>

