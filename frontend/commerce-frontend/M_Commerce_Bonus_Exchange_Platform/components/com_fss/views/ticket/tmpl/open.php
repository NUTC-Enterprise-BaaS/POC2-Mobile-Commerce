<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

$ticket_user_id = JFactory::getUser()->id;
if ($this->admin_create == 2)
	$ticket_user_id = 0;
if ($this->admin_create == 1)
	$ticket_user_id = $this->user->id;
?>

<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("SUPPORT","NEW_SUPPORT_TICKET"); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_openheader.php'); ?>
	
<form id='newticket' action="<?php echo str_replace("&amp;","&",FSSRoute::_( '&layout=open' ));// FIX LINK?>" method="post"  enctype="multipart/form-data">
	<input type='hidden' name='prodid' id='prodid' value='<?php echo (int)$this->prodid; ?>'>
	<input type='hidden' name='deptid' id='deptid' value='<?php echo (int)$this->deptid; ?>'>
	<input type='hidden' name='what' id='what' value='add'>

<?php FSS_Helper::HelpText("support_open_main_header"); ?>

<?php if (FSS_Settings::get('support_sel_prod_dept') && (isset($this->product) || isset($this->dept))): ?>

	<?php if (isset($this->product) &&  isset($this->dept)): ?>
		<?php echo FSS_Helper::PageSubTitle("PRODUCT_AND_DEPARTMENT_INFORMATION"); ?>
	<?php elseif (isset($this->product)): ?>
		<?php echo FSS_Helper::PageSubTitle("PRODUCT_INFORMATION"); ?>
	<?php elseif (isset($this->dept)): ?>
		<?php echo FSS_Helper::PageSubTitle("DEPARTMENT_INFORMATION"); ?>
	<?php endif; ?>

	<?php FSS_Helper::HelpText("support_open_main_pd_header"); ?>

	<div class="form-horizontal form-condensed">
		<?php if (isset($this->product)): ?>
			<div class="control-group cg-product">
				<label class="control-label"><?php echo JText::_("PRODUCT"); ?></label>
				<div class="controls product-small">
					<?php if ($this->product->image) : ?>
						<img class="media-object" src="<?php echo JURI::root( true ); ?>/images/fss/products/<?php echo $this->product->image; ?>">
					<?php endif; ?>
					<?php echo $this->product->title ?>
				</div>
			</div>
		<?php endif; ?>
		<?php if (isset($this->dept)): ?>
			<div class="control-group cg-department">
				<label class="control-label"><?php echo JText::_("DEPARTMENT"); ?></label>
				<div class="controls department-small">
					<?php if ($this->dept->image) : ?>
						<img class="media-object" src="<?php echo JURI::root( true ); ?>/images/fss/departments/<?php echo $this->dept->image; ?>">
					<?php endif; ?>
					<?php echo $this->dept->title ?>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<?php FSS_Helper::HelpText("support_open_main_pd_footer"); ?>
	
<?php endif; ?>

<?php 
$grouping = "";
$open = false;

	foreach ($this->fields as $field)
{

	if ($field['grouping'] == "")
		continue;
		
	if ($field['reghide'] == 2 && $ticket_user_id > 0)
		continue;
		
	if ($field['reghide'] == 1 && $ticket_user_id < 1)
		continue;

	if ($field['openhide'] == 1)
		continue;
	
	if ($this->admin_create == 0) // not an admin created ticket
	{
		// if permission is user see only, or only admin, dont output the field
		if ($field['permissions'] == 1 || $field['permissions'] == 2)
		{
			continue;
		}
	}


	if ($field['grouping'] != $grouping)
	{
		if ($open)
		{
			?>
				</div>
			<?php
		}
		echo "<div id='cf_header_".strtolower(preg_replace("/[^A-Za-z0-9]/", '-', $field['grouping']))."'>";
		echo FSS_Helper::PageSubTitle($field['grouping']);
		echo "</div>";
			?>
			<div class="form-horizontal form-condensed" id="cf_group_<?php echo strtolower(preg_replace("/[^A-Za-z0-9]/", '-', $field['grouping'])); ?>">
		<?php	
		$open = true;	
		$grouping = $field['grouping'];
	}
	
		?>
			<div id="cf_input_<?php echo $field['id']; ?> cf_input_<?php echo $field['alias']; ?>" class="control-group <?php echo FSSCF::FieldClass($field); ?> <?php if (FSSCF::HasErrors($field, $this->errors)) echo "error"; ?>">
				<label class="control-label"><?php echo FSSCF::FieldHeader($field,true, false); ?></label>
				<div class="controls">
					<?php echo FSSCF::FieldInput($field,$this->errors,'ticket',array('ticketid' => 0, 'userid' => $ticket_user_id), true); ?>
				</div>
			</div>
	<?php
}

if ($open)
{
	?>
		</div>
	<?php
}

?>

<div class="fss_message_details_header">
<?php echo FSS_Helper::PageSubTitle("MESSAGE_DETAILS"); ?>
</div>

<?php $has_message_details = false; ?>

	<?php FSS_Helper::HelpText("support_open_main_md_header"); ?>

	<div class="form-horizontal form-condensed">
		
		<?php if (FSS_Settings::get('support_subject_message_hide') != "subject" && FSS_Settings::get('support_subject_message_hide') != "both" && FSS_Settings::get('support_subject_at_top')): ?>
			<div class="control-group cg-subject <?php echo $this->errors['subject'] ? 'error' : ''; ?>">
				<label class="control-label"><?php echo JText::_("SUBJECT"); ?></label>
<div class="controls">
					<input type="text" class="input-xlarge" name='subject' id='subject' size='<?php echo FSS_Settings::get('support_subject_size'); ?>' value="<?php echo FSS_Helper::escape($this->ticket['title']) ?>" required>
					<span class="help-inline"><?php echo $this->errors['subject'] ? $this->errors['subject'] : FSS_Helper::HelpText("support_open_main_field_subject"); ?></span>
				</div>
			</div>

			<?php $has_message_details = true; ?>
		<?php endif; ?>
		
		
		<?php if (!FSS_Settings::get('support_hide_tags') && $this->admin_create > 0): ?>
			<div class="control-group cg-tags <?php echo $this->errors['tags'] ? 'error' : ''; ?>">
				<?php 
				$tags_input = FSS_Input::getString('tags');
				$parts = explode("|", $tags_input);
				$tags = array();
				foreach ($parts as $part)
				{
					$tag = trim($part);
					if (!$tag || $tag == "") continue;
					
					$tags[] = $tag;	
				}
				 ?>
				<label class="control-label"><?php echo JText::_("TAGS"); ?></label>
				<input name="tags" type='hidden' id="tags_input" value="<?php echo $tags_input; ?>" />
				<div class="controls">
					<div style="position: relative;" id="tag_list_container" class="pull-left">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#" style="top: 5px;position: relative;padding-right:8px;">
							<i class="icon-new fssTip" title="<?php echo JText::_("ADD_TAGS"); ?>"></i> 
						</a>
						<ul class="dropdown-menu">
							<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_tag_list.php'); ?>
						</ul>
					</div>
					
					<div id="tags">
						<?php foreach ($tags as $tag): ?>
							<div class="fss_tag label label-info" id="tag_<?php echo preg_replace("/[^a-zA-Z0-9]+/", "-", $tag); ?>">
								<button class="close" onclick="tag_remove('<?php echo $tag; ?>');return false;">&times;</button>
								<?php echo $tag; ?>
							</div>
						<?php endforeach; ?>
					</div>

				</div>
			</div>
			<?php $has_message_details = true; ?>
		<?php endif; ?>
<?php

foreach ($this->fields as $field)
{
	if ($field['grouping'] != "")
		continue;
		
	if ($field['reghide'] == 2 && $ticket_user_id > 0)
		continue;
		
	if ($field['reghide'] == 1 && $ticket_user_id < 1)
		continue;

	if ($field['openhide'] == 1)
		continue;

	// not an admin created ticket
	// if permission is user see only, or only admin, dont output the field
	if ($this->admin_create == 0 && ($field['permissions'] == 1 || $field['permissions'] == 2)) 
		continue;
		?>
			<div id="cf_input_<?php echo $field['id']; ?> cf_input_<?php echo $field['alias']; ?>" class="control-group <?php echo FSSCF::FieldClass($field); ?> <?php if (FSSCF::HasErrors($field, $this->errors)) echo "error"; ?>">
				<label class="control-label"><?php echo FSSCF::FieldHeader($field,true, false); ?></label>
				<div class="controls">
					<?php echo FSSCF::FieldInput($field,$this->errors,'ticket',array('ticketid' => 0, 'userid' => $ticket_user_id), true); ?>
				</div>
			</div>
			<?php $has_message_details = true; ?>
	<?php
}
?>	
	
<?php if (count($this->cats) > 0 && !FSS_Settings::get('support_hide_category')): ?>	
	<div class="control-group cg-category <?php echo $this->errors['cat'] ? 'error' : ''; ?>">
		<label class="control-label"><?php echo JText::_("CATEGORY"); ?></label>
		<div class="controls">

			<select id='catid' class='input-large' name='catid' <?php if (FSS_Settings::get('support_altcat')) echo "required" ?>>
				<?php if (FSS_Settings::get('support_altcat')): ?>
					<option value=""><?php echo JText::_("SELECT_CATEGORY"); ?></option>
				<?php endif; ?>
				<?php $sect = ""; $open = false; ?>
				<?php foreach ($this->cats as $cat): ?>
					<?php 
						if ($cat->section != $sect && $cat->section != "") {
							if ($open)
								echo "</optgroup>";
							$open = true;
							echo "<optgroup label='" . $cat->section . "'>";
							$sect = $cat->section;	
						}								
					?>
					<option value='<?php echo $cat->id; ?>' <?php if ($this->catid == $cat->id) echo "selected='selected'"; ?>><?php echo $cat->title; ?></option>
				<?php endforeach; ?>
				<?php if ($open) echo "</optgroup>"; ?>
			</select>
			<span class="help-inline"><?php echo $this->errors['cat'] ? $this->errors['cat'] : FSS_Helper::HelpText("support_open_main_field_category"); ?></span>
		</div>
	</div>
	<?php $has_message_details = true; ?>
<?php endif; ?>


<?php if (!FSS_Settings::get('support_hide_priority') && !FSS_Settings::get('user_hide_priority')) : ?>
	<div class="control-group cg-priority">
		<label class="control-label"><?php echo JText::_("PRIORITY"); ?></label>
		<div class="controls">
			<select class='input-large' id='priid' name='priid'>
				<?php foreach ($this->pris as $pri): ?>
					<option value='<?php echo $pri->id; ?>'
						style='color: <?php echo $pri->color; ?>'
						<?php if ($pri->id == $this->ticket['ticket_pri_id']) echo "selected='selected'"; ?>>
					<?php echo $pri->title; ?>
				</option>
				<?php endforeach; ?>
			</select>
			<span class="help-inline"><?php FSS_Helper::HelpText("support_open_main_field_priority");?></span>
		</div>
	</div>
	<?php $has_message_details = true; ?>
<?php endif; ?>	

<?php if ($this->captcha != ""): ?>
	<div class="control-group cg-captcha <?php echo $this->errors['captcha'] ? 'error' : ''; ?>">
		<label class="control-label"><?php echo JText::_("FSS_CAPTCHA"); ?></label>
		<div class="controls">
			<span id="captcha_cont"><?php echo $this->captcha ?></span>
			<span class="help-inline"><?php echo $this->errors['captcha'] ? $this->errors['captcha'] : FSS_Helper::HelpText("support_open_main_field_captcha"); ?></span>
		</div>
	</div>
<?php endif; ?>

<?php if (FSS_Settings::get('support_choose_handler') == "user" || ($this->admin_create > 0 && FSS_Settings::get('support_choose_handler') == "admin") || (FSS_Settings::get('support_choose_handler') == "handlers" && FSS_Permission::auth("fss.handler", "com_fss.support_admin"))) : ?>
	<?php if (count($this->handlers) > 1): ?>
		<div class="control-group cg-handler">
			<label class="control-label"><?php echo JText::_("HANDLER"); ?></label>
			<div class="controls">
				<select class='input-large' id='handler' name='handler'>

					<?php if ($this->admin_create > 0): ?>

						<option value="0" <?php if (!FSS_Settings::get('support_assign_for_user')) echo " selected='selected'"; ?> ><?php echo JText::_("AUTO_ASSIGN"); ?></option>
						<option value="<?php echo JFactory::getUser()->id; ?>" <?php if (FSS_Settings::get('support_assign_for_user')) echo " selected='selected'"; ?> ><?php echo JFactory::getUser()->name; ?></option>

						<optgroup label="<?php echo JText::_("ASSIGNED_HANDLERS"); ?>">
							<?php foreach ($this->handlers as $handler): ?>
								<?php if ($handler['id'] == 0) continue; ?>
								<?php if (!in_array($handler['id'], $this->autohandlers)) continue; ?>
								<option value='<?php echo $handler['id']; ?>'
								<?php if ($handler['id'] == $this->ticket['admin_id'] && $this->ticket['admin_id'] > 0) echo "selected='selected'"; ?>>
								<?php echo $handler['name']; ?>
								</option>
							<?php endforeach; ?>
						</optgroup>

						<optgroup label="<?php echo JText::_("OTHER_HANDLERS"); ?>">					
							<?php foreach ($this->handlers as $handler): ?>
								<?php if ($handler['id'] == 0) continue; ?>
								<?php if (in_array($handler['id'], $this->autohandlers)) continue; ?>
								<option value='<?php echo $handler['id']; ?>'
								<?php if ($handler['id'] == $this->ticket['admin_id'] && $this->ticket['admin_id'] > 0) echo "selected='selected'"; ?>>
								<?php echo $handler['name']; ?>
								</option>
							<?php endforeach; ?>
						</optgroup>

					<?php else: ?>
					
						<?php foreach ($this->handlers as $handler): ?>
							<?php if ($handler['id'] == 0) continue; ?>
							<option value='<?php echo $handler['id']; ?>'
							<?php if ($handler['id'] == $this->ticket['admin_id'] && $this->ticket['admin_id'] > 0) echo "selected='selected'"; ?>>
							<?php echo $handler['name']; ?>
							</option>
						<?php endforeach; ?>

					<?php endif; ?>
				</select>
				<span class="help-inline"><?php FSS_Helper::HelpText("support_open_main_field_handler"); ?></span>
			</div>
		</div>
	<?php $has_message_details = true; ?>
	<?php else: ?>
		<input type='hidden' name="handler" id="handler" value="0" />
	<?php endif; ?>
<?php else: ?>	
	<?php if (FSS_Settings::get('support_assign_for_user') && $this->admin_create > 0): ?>
		<input type='hidden' name="handler" id="handler" value="<?php echo JFactory::getUser()->id; ?>" />
	<?php endif; ?>
<?php endif; ?>

<?php if (FSS_Settings::get('support_subject_message_hide') != "subject" && FSS_Settings::get('support_subject_message_hide') != "both" && !FSS_Settings::get('support_subject_at_top')): ?>
	<div class="control-group <?php echo $this->errors['subject'] ? 'error' : ''; ?>">
		<label class="control-label"><?php echo JText::_("SUBJECT"); ?></label>
		<div class="controls">
			<input type="text" class="input-xlarge" name='subject' id='subject' size='<?php echo FSS_Settings::get('support_subject_size'); ?>' value="<?php echo FSS_Helper::escape($this->ticket['title']) ?>" required>
			<span class="help-inline"><?php echo $this->errors['subject'] ? $this->errors['subject'] : FSS_Helper::HelpText("support_open_main_field_subject"); ?></span>
		</div>
	</div>
	<?php $has_message_details = true; ?>
<?php endif; ?>

<?php if ($this->admin_create > 0): ?>
	
	<div id="canned_replies" style="display: none;">
		<?php echo SupportCanned::CannedList(null); ?>
	</div>
	
	<div class="control-group">
		<label class="control-label"><?php echo JText::_("MESSAGE"); ?></label>
		<div class="controls">
			<?php echo SupportCanned::CannedDropdown("body"); ?>
		</div>
	</div>
	
	<script>	
		function insertCanned(value, id)
		{
			var bbcode = jQuery('#canned_reply_' + value).html();
			bbcode = bbcode.replace(/\n/g, "");
			bbcode = bbcode.replace(/\r/g, "");
			bbcode = bbcode.replace(new RegExp(String.fromCharCode(182), "g"), "\n");
			bbcode = jQuery('<textarea />').html(bbcode).text();
	
			<?php if (FSS_Settings::Get('support_sceditor')): ?>
				jQuery('#' + id).sceditor('instance').insert(bbcode);	
			<?php else: ?>
				jQuery('#' + id).val(jQuery('#' + id).val() + bbcode);
			<?php endif; ?>
		}

		function insertLink(url, title, id)
		{
			<?php if (FSS_Settings::Get('support_sceditor')): ?>
				var bbcode = "[url=" + url + "]" + title + "[/url]";
				jQuery('#' + id).sceditor('instance').insert(bbcode);
			<?php else: ?>
				jQuery('#' + id).val(jQuery('#' + id).val() + url);
			<?php endif; ?>
			fss_modal_hide();
		}

		function cannedRefresh()
		{
			var url = '<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&layout=reply&task=canned.dolist&ticketid=' . 0, false); ?>';
			jQuery('#canned_replies').load(url);
	
	
			jQuery('.canned_list').html("<div class='pull-right'><?php echo JText::_('PLEASE_WAIT'); ?></div>");

			var cr_url = '<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&layout=reply&task=canned.dropdown&ticketid=' . 0, false); ?>';
			jQuery('.canned_list').each( function () {
				var target = jQuery(this);		
				var url = cr_url + "&elem=" + target.attr('editid');
				
				jQuery.get(url, function (data) {
					target.html(data);
			
					init_elements();
				});
			});		
		}
	</script>

	<?php FSS_Helper::IncludeModal(); ?>

<?php endif; ?>


<?php if (FSS_Settings::get('support_subject_message_hide') != "message" && FSS_Settings::get('support_subject_message_hide') != "both"): ?>
	
	<?php if (FSS_Settings::get('open_search_enabled') == 4): ?>
		
		<script>
		jQuery(document).ready(function () {
			jQuery('#search_overlay').width(jQuery('#body').width() + "px");
			jQuery('#search_overlay').height((jQuery('#body').height()-10) + "px");
		});
		</script>
		
		
		<div id="search_overlay_outer">
			<div id="search_overlay" class="well">
				Please enter your subject.
			</div>
		</div>
	<?php endif; ?>
	
	<?php if ($this->errors['body']): ?>
		<div class="control-group error">
			<span class='help-inline' id='error_subject'><?php echo $this->errors['body']; ?></span>
		</div>
	<?php endif; ?>
	
	<?php FSS_Helper::HelpText("support_open_main_message_before"); ?>
	<div class="control-group">
		<textarea name='body' id='body' class='sceditor' rows='<?php echo (int)FSS_Settings::get('support_user_reply_height'); ?>' cols='<?php echo (int)FSS_Settings::get('support_user_reply_width'); ?>' style='width:95%;height:<?php echo (int)((FSS_Settings::get('support_user_reply_height') * 15) + 80); ?>px'><?php echo FSS_Helper::escape($this->ticket['body']) ?></textarea>
	</div>
	<?php FSS_Helper::HelpText("support_open_main_message_after"); ?>
	<?php $has_message_details = true; ?>
<?php endif; ?>
</div>

<?php if (!$has_message_details): ?>
	<style>
	.fss_message_details_header {
		display: none;
	}
	</style>
<?php endif; ?>

<?php FSS_Helper::HelpText("support_open_main_md_footer"); ?>

<?php if (FSS_Settings::get('support_user_attach')): ?>

	<?php FSS_Helper::HelpText("support_open_main_file_header"); ?>
	<div class="form-horizontal form-condensed">
		<div class="control-group cg-upload <?php echo $this->errors['captcha'] ? 'error' : ''; ?>">
			<label class="control-label"><?php echo JText::sprintf("UPLOAD_FILE",FSS_Helper::display_filesize(FSS_Helper::getMaximumFileUploadSize())); ?></label>
			<div class="controls">
				<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'tmpl'.DS.'attach.php'; ?>
			</div>
		</div>
	</div>
	<?php FSS_Helper::HelpText("support_open_main_file_footer"); ?>
<?php endif; ?>

<?php FSS_Helper::HelpText("support_open_main_buttons_before"); ?>

<p>
<?php if ($this->prodid > 0 || $this->deptid > 0): ?>
	
	<?php 
	$use_prod = true;
	if ($this->prodid < 1)
	{
		$use_prod = false;
	} else {
		$depts = SupportHelper::getDepartmentsUserOpen($this->prodid);
		if (count($depts) < 2)
			$use_prod = false;
	}
	?>
	<?php if (FSS_Input::getInt('admincreate') > 0): ?>
		<?php if ($use_prod): ?>	
			<a class='btn btn-default backprod' href="<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&layout=open&prodid='.$this->prodid.'&admincreate=' . FSS_Input::getInt('admincreate')); ?>"><?php echo JText::_("BACK"); ?></a>
		<?php else: ?>
			<a class='btn btn-default backprod' href="<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&layout=open&admincreate=' . FSS_Input::getInt('admincreate')); ?>"><?php echo JText::_("BACK"); ?></a>
		<?php endif; ?>
	<?php else : ?>
		<?php if ($use_prod): ?>	
			<a class='btn btn-default backprod' href="<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&layout=open&prodid='.$this->prodid); ?>"><?php echo JText::_("BACK"); ?></a>
		<?php else: ?>
			<a class='btn btn-default backprod' href="<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&layout=open'); ?>"><?php echo JText::_("BACK"); ?></a>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>

<input class='btn btn-primary' type='submit' value='<?php echo JText::_("CREATE_NEW_TICKET"); ?>' id='addcomment'>
</p>
<?php FSS_Helper::HelpText("support_open_main_buttons_after"); ?>

</form>

<script>
jQuery(document).ready(function(){
<?php if ($this->prodid > 0 || $this->deptid > 0): ?>
	jQuery('#backprod').click(function(ev){
	
		ev.preventDefault();
		
		if (jQuery('#deptid').val() == '' || jQuery('#deptid').val() == 0)
			jQuery('#prodid').val('');		
			
		jQuery('#deptid').val('');		
	});
<?php endif; ?>
});

<?php foreach ($this->fields as $field): ?>
	<?php if ($field['javascript']) echo $field['javascript']; ?>
<?php endforeach; ?>	
			
			
// adding and removing tags
function tag_remove(tagname)
{
	if (tagname == "")
		return;
	
	try
	{
		var id = tagname.replace(/[^a-zA-Z0-9]+/g, '-');
		jQuery('#tag_' + id).remove();
	
		var val = jQuery('#tags_input').val();
		val = val.replace("|" + tagname + "|", "");
		jQuery('#tags_input').val(val);
	} catch (e) {}

	return false;
}

function tag_add(tagname)
{
	if (tagname == "")
		return;
	
	try
	{
		var id = tagname.replace(/[^a-zA-Z0-9]+/g, '-');
		html = '<div class="fss_tag label label-info" id="tag_' + id + '">';
		html += '<button class="close" onclick="tag_remove(\'' + tagname + '\');return false;">&times;<'+'/button>';
		html += tagname;
		html += '<'+'/div>';
			
		jQuery('#tags').append(html);
	
		var val = jQuery('#tags_input').val();
		val += "|" + tagname + "|";
		jQuery('#tags_input').val(val);
	} catch (e) {}

	return false;
}

</script>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>

<?php echo FSS_Helper::PageStyleEnd(); ?>
