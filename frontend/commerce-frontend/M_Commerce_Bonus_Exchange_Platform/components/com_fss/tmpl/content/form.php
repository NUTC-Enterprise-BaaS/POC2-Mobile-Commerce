<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php echo  FSS_Helper::PageSubTitle($this->descs . ': ' . ($this->item['id'] > 0 ? JText::_('EDIT') : JText::_('CREATE'))); ?>

<p class="pull-right">
	<a href="#" id='fss_form_apply' class="btn btn-success"><i class="icon-apply icon-white"></i> <?php echo JText::_('SAVE');?></a> 
	<a href="#" id='fss_form_save' class="btn btn-default"><i class="icon-apply"></i> <?php echo JText::_('SAVE_AND_CLOSE');?></a> 
	<a href="#" id='fss_form_savenew' class="btn btn-default"><i class="icon-save-new"></i> <?php echo JText::_('SAVE_A_ADD');?></a> 
	<a href="#" id='fss_form_cancel' class="btn btn-default"><i class="icon-cancel"></i> <?php echo JText::_('CANCEL');?></a> 
<?php if ($this->item['id'] > 0) : ?>
	<a href="#" id='fss_form_view' class="btn btn-default"><i class="icon-arrow-right"></i> <?php echo JText::_('VIEW');?></a> 
<?php endif; ?>
</p>

<div class="clearfix"></div>
<div id="system">
	<form id="fss_form" action="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_content&type=' . $this->id); ?>" method='post' class='form-horizontal form-condensed'>
		<input type="hidden" name="return" value="<?php echo FSS_Input::getString("return",""); ?>" />
		<input type="hidden" name="id" value="<?php echo $this->item['id']; ?>" />
		<input type="hidden" name="what" value="" />

		<?php if (FSS_Permission::auth("core.edit.state", $this->getAsset())) : ?>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('PUBLISHED'); ?></label>
				<div class="controls">
					<input type='checkbox' name='published' value='1' <?php if ($this->item['published'] == 1) { echo " checked='yes' "; } ?>>
				</div>
			</div>
		<?php endif; ?>
		<?php if (FSS_Permission::auth("core.edit", $this->getAsset())) : ?>
			<?php if ($this->has_author): ?>
				<div class="control-group">
					<label class="control-label"><?php echo JText::_('AUTHOR'); ?></label>
					<div class="controls">
						<?php echo $this->authorselect; ?>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
			
		<?php foreach ($this->edit as $edit):
			$field = $this->GetField($edit);
			//print_p($field);
		?>
		<?php if ($field->type == "products"): ?>
			<div class="control-group">
				<label class="control-label"><?php echo $field->desc; ?></label>
				<?php echo $field->products_yesno; ?>
			</div>
			
			<div class="control-group"
					 id="prodlist_<?php echo $field->input_name; ?>" <?php if ($this->item[$field->field]) echo 'style="display:none;"'; ?>>
				<label class="control-label"><?php echo JText::_('PRODUCTS'); ?></label>
				<div class="controls">
					<?php echo $field->products_check; ?>
				</div>
			</div>
		<?php else : ?>
			<div class="control-group <?php if (FSS_Helper::ShowError($this->errors,$field->field) != "") echo "error"; ?> <?php if ($field->hide) echo "hide"; ?> ">
				<label class="control-label"><?php echo $field->desc; ?></label>
				<div class="controls">

					<?php if ($field->type == "string") : ?>
						<input type="text" name="<?php echo $field->input_name; ?>" size="32" value="<?php echo FSS_Helper::escape($this->item[$field->field]); ?>" />
					<?php elseif ($field->type == "checkbox"):?>
						<input type="checkbox" name="<?php echo $field->input_name; ?>" value="1" <?php if ($this->item[$field->field]) echo "CHECKED"; ?> />
					<?php elseif ($field->type == "text"):?>
						<?php
						$text = $this->item[$field->field];
						if ($field->more)
						{
							if ($this->item[$field->more])
							{
								$text .= '<hr id="system-readmore" />';
								$text .= $this->item[$field->more];                     
							}
						}
						$editor = JFactory::getEditor();
						echo $editor->display($field->input_name, htmlspecialchars($text, ENT_COMPAT, 'UTF-8'), '550', '400', '60', '20', true);
					
						?>
					<?php elseif ($field->type == "long"):?>
						<textarea name="<?php echo $field->input_name; ?>" id="<?php echo $field->field; ?>" rows="4" class="input-xxlarge" style="height:150px"><?php echo FSS_Helper::escape($this->item[$field->field]); ?></textarea>
					<?php elseif ($field->type == "tags"):?>
						<textarea name="<?php echo $field->input_name; ?>" id="<?php echo $field->field; ?>" rows="4" class="input-xxlarge" style="height:150px"><?php echo FSS_Helper::escape($this->item[$field->field]); ?></textarea>
					<?php elseif ($field->type == "related"):?>
						<p>
							<button class="btn btn-default fss_content_related_button" id="relbtn_<?php echo $field->field; ?>">
								<?php echo $field->rel_button_txt; ?>
							</button>
						</p>
						<div id="related_items">
							<?php foreach ($field->rel_ids as $id => $title): ?>
								<div class="well well-mini pull-left fss_content_related_item" id="relitem_<?php echo $field->field; ?>_<?php echo $id; ?>">
									<?php echo $title; ?>&nbsp;<button class="close">&times;</button>
								</div>
							<?php endforeach; ?>
						</div>
						
						<input type="hidden" name="<?php echo $field->field; ?>" value="<?php echo $field->rel_id_list; ?>">
					<?php elseif ($field->type == "lookup"):?>
						<?php echo $this->LookupInput($field, $this->item);	?>
					<?php endif; ?>
					
					<span class="help-inline"><?php echo FSS_Helper::ShowError($this->errors,$field->field); ?></span>
				</div>
			</div>
			<?php endif; ?>
		<?php endforeach; ?>		
	</form>
</div>

<script>
function DoAllProdChange(field)
{
	var field_input = jQuery('input[name="' + field+ '"]');
	var pldiv = jQuery('#prodlist_' + field);

	if (field_input.attr('checked') == "checked")
	{
		pldiv.css('display','block');
	} else {
		pldiv.css('display','none');
	}						
}

function FormButton(task)
{
	jQuery('#fss_form').find('input[name="what"]').val(task);
	jQuery('#fss_form').submit();
}

jQuery(document).ready(function () {
	jQuery('#fss_form_cancel').click(function (ev) {
		ev.preventDefault();
		FormButton("cancel")
	});
	jQuery('#fss_form_save').click(function (ev) {
		ev.preventDefault();
		FormButton("save")
	});
	jQuery('#fss_form_apply').click(function (ev) {
		ev.preventDefault();
		FormButton("apply")
	});
	jQuery('#fss_form_savenew').click(function (ev) {
		ev.preventDefault();
		FormButton("savenew")
	});
	
	jQuery('#fss_form_view').click(function (ev) {
		ev.preventDefault();
		window.open('<?php echo $this->viewurl; ?>','article');
	});
	
	/*jQuery('.fss_content_toolbar_item').mouseenter(function () {
		jQuery(this).css('background-color', '#f0f0f0');
	});
	jQuery('.fss_content_toolbar_item').mouseleave(function () {
		jQuery(this).css('background-color' ,'white');
	});*/

	ResetRemoveRelated();
	
	jQuery('.fss_content_related_button').click(function (ev) {
		ev.preventDefault();
		var id = jQuery(this).attr('id').split("_")[1];
		
		// add related item
		fss_modal_show('<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_content&tmpl=component&type=' . $this->id . '&what=pick&field='); ?>&field=' + id, true, 800);
		//TINY.box.show({iframe:'<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_content&tmpl=component&type=' . $this->id . '&what=pick&field='); ?>&field=' + id,width:800,height:500})
		// show popup for id
	});
	
	jQuery('#change_author').click(function(ev) {
		ev.preventDefault();
		fss_modal_show('<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_content&tmpl=component&type=' . $this->id . '&what=author'); ?>', true);
		//TINY.box.show({iframe:'<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_content&tmpl=component&type=' . $this->id . '&what=author'); ?>',width:800,height:500})
	});
});

function ResetRemoveRelated()
{
	jQuery('.fss_content_related_item button').unbind('click');
			
	jQuery('.fss_content_related_item button').click(function (ev) {
		ev.preventDefault();
		
		// remove link clicked
		var id = jQuery(this).parent().attr('id');
		var parts = id.split("_");
		var field = parts[1];
		var id = parts[2];
		RemoveRelatedItem(field, id);
	});
}

function RemoveRelatedItem(field, id)
{
	var values = jQuery('input[name="' + field + '"]').val();
	values = values.split(':');
	RemoveFromArray(values, id);
	values = values.join(":");
	jQuery('input[name="' + field + '"]').val(values);
	
	jQuery('#relitem_' + field + '_' + id).remove();
}

function RemoveFromArray(originalArray, itemToRemove)
{
	var j = 0;
	while (j < originalArray.length) 
	{
		// alert(originalArray[j]);
		if (originalArray[j] == itemToRemove) {
			originalArray.splice(j, 1);
		} else { 
			j++; 
		}
	}				
}

function PickUser(id, username, name)
{
	fss_modal_hide();
	jQuery('#content_authname').text(name);
	jQuery('#content_author').val(id);
}

function AddRelatedItem(field, id, title)
{
	fss_modal_hide();
	
	var values = jQuery('input[name="' + field + '"]').val();
	if (values == "")
	{
		values = new Array();
	} else {
		values = values.split(':');
	}
	var added = AddToArray(values, id);
	values = values.join(":");
	jQuery('input[name="' + field + '"]').val(values);
	
	if (added)
	{
	
		var html = "&lt;div class='well well-mini pull-left fss_content_related_item'  id='relitem_" + field + "_" + id  + "' &gt;";
		html += title;
		html += "&nbsp;&lt;button class='close'&gt;&times;&lt;/button&gt;&lt;/div&gt;";

		html = html.replace(/&lt;/g, "<");
		html = html.replace(/&gt;/g, ">");
		
		jQuery('#related_items').append(html);
		
		ResetRemoveRelated();
	}
	
}

function AddToArray(ar, id)
{
	for (var i = 0 ; i < ar.length ; i++)
	{
		if (ar[i] == id)
			return false;
	}				
	
	// store new value
	ar[ar.length] = id;
	return true;
}
</script>
