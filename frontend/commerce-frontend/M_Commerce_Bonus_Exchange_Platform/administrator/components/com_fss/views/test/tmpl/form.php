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
		
		if (!checkFormOK())
        {
			return;
		}
		
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
			<td width="200" align="right" class="key">
				<label for="question">
					<?php echo JText::_("MOD_STATUS"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="question">
					<?php echo JText::_("SECTION"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['sections']; ?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="question" id="type_label">
					<?php if ($this->test->ident): ?>
						<?php echo $this->lists['comments']->handler->email_article_type ?>:
					<?php endif;?>
				</label>
			</td>
			<td id="tr_items">
				<?php echo $this->lists['itemid']; ?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="name">
					<?php echo JText::_("CREATED"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="created" id="created" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->test->created);?>" /> 
				<div class='pull-right' style="position: relative;top: 6px;">Please use MySQL date format (YYYY-MM-DD HH:MM:SS)</div>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="name">
					<?php echo JText::_("NAME"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->test->name);?>" />
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="email">
					<?php echo JText::_("EMAIL"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="email" id="email" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->test->email);?>" />
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="website">
					<?php echo JText::_("WEBSITE"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="website" id="website" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->test->website);?>" />
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="body">
					<?php echo JText::_("BODY"); ?>:
				</label>
			</td>
			<td>
				<textarea id="body" name="body" rows="20" cols="60" style='width: 500px'><?php echo htmlspecialchars($this->test->body, ENT_COMPAT, 'UTF-8'); ?></textarea>
            </td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->test->id; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="test" />
</form>

<script>

jQuery(document).ready( function () {
	jQuery('#toolbar-save a').unbind('click');
	jQuery('#toolbar-save a').attr('onclick','');
	jQuery('#toolbar-save a').click(function (ev) {
		ev.preventDefault();
		ev.stopPropagation();
		if (checkFormOK())
			submitbutton('save');
	});
});

function change_section()
{
	var ident = jQuery('#ident');
	var newval = ident.val();
	
	var url = '<?php echo FSSRoute::_('index.php?option=com_fss&controller=test&task=ident&ident=XXX', false); ?>';
	url = url.replace("XXX",newval);
	jQuery('#type_label').html("");
	jQuery('#tr_items').html("<?php echo JText::_('PLEASE_WAIT');?>");
	jQuery.get(url, function (data) {
		jsonObj = JSON.decode(data); 		
		jQuery('#type_label').html(jsonObj.title + ":");
		jQuery('#tr_items').html(jsonObj.select);
		
	});
}


function checkFormOK()
{
	var ident = jQuery('#ident');
	var newval = ident.val();
	if (newval < 1)
	{
		alert("You must select a section before saving");
		return false;			
	}
				
	var itemid = jQuery('#itemid');
	if (!itemid)
	{
		alert("You must select a section before saving");
		return false;	
	}

	return true;
}

</script>
