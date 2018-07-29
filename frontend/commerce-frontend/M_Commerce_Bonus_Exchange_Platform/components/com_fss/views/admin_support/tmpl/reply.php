<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php echo FSS_Helper::PageStyle(); ?>

<?php echo FSS_Helper::PageTitle('SUPPORT_ADMIN',JText::_($this->reply_title)); ?>

<form id='newticket' action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=reply');?>" method="post"  enctype="multipart/form-data" class="form-horizontal form-condensed">
	<input type="hidden" name='ticketid' id='ticketid' value='<?php echo FSS_Helper::escape($this->ticketid); ?>'>
	<input type="hidden" name='what' id='what' value='savereply'>
	<input type="hidden" name='reply_type' id='reply_type' value='<?php echo FSS_Helper::escape($this->reply_type); ?>'>
	<input type="hidden" name='draft' id='draft' value='<?php echo FSS_Helper::escape($this->draft); ?>'>
	<input type="hidden" name='source' id='source' value=''>

	<div class="control-group">
		<label class="control-label"><?php echo JText::_("SUBJECT"); ?></label>
		<div class="controls">
			<input name='subject' type="text" id='subject' size='<?php echo FSS_Settings::get('support_subject_size'); ?>' value="Re: <?php echo FSS_Helper::escape($this->ticket->title); ?>">
		</div>
	</div>

	<?php if (FSS_Settings::Get('time_tracking') != ""): ?>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_("TIME_TAKEN"); ?></label>
				<div class="controls">
					<?php if (FSS_Settings::Get('time_tracking_type') == "se"): ?>
						<span class="help-inline"><?php echo JText::_('TIME_START'); ?>: </span>
						<div class="input-append bootstrap-timepicker">
							<input name="timetaken_start" id="timetaken_start" type="text" style="width: 40px" value="<?php echo FSS_Helper::escape($this->time_start); ?>">
							<span class="add-on">
								<i class="icon-clock"></i>
							</span>
						</div>
						<span class="help-inline"><?php echo JText::_('TIME_END'); ?>: </span>
						<div class="input-append bootstrap-timepicker">
							<input name="timetaken_end" id="timetaken_end" type="text" style="width: 40px" value="<?php echo FSS_Helper::escape($this->time_end); ?>">
							<span class="add-on">
								<i class="icon-clock"></i>
							</span>
						</div>
						<a class="btn btn-default" onclick="var dt = new Date();var time = dt.getHours() + ':' + dt.getMinutes();jQuery('#timetaken_start').val(time);jQuery('#timetaken_end').val(time);return false;"><i class="icon-cancel"></i></a>		
					<?php elseif (FSS_Settings::Get('time_tracking_type') == "tm"): ?>
						<span class="help-inline"><?php echo JText::_('TIME_START'); ?>: </span>
						<input name="timetaken_start" id="timetaken_start" type="text" style="width: 130px" value="<?php echo FSS_Helper::escape($this->time_start); ?>">
						<span class="help-inline"><?php echo JText::_('TIME_END'); ?>: </span>
						<input name="timetaken_end" id="timetaken_end" type="text" style="width: 130px" value="<?php echo FSS_Helper::escape($this->time_end); ?>">
						<a class="btn btn-default" onclick="var dt = new Date();var time = dt.getHours() + ':' + dt.getMinutes();jQuery('#timetaken_start').val(time);jQuery('#timetaken_end').val(time);return false;"><i class="icon-cancel"></i></a>		
						
<script>
	<?php FSS_Translate_Helper::CalenderLocale(); ?>
	jQuery(document).ready(function () {
		myCalendarFrom = new dhtmlXCalendarObject('timetaken_start','omega');
		myCalendarFrom.setDateFormat('<?php echo FSS_Helper::getCalFormat(); ?>');
		myCalendarFrom.loadUserLanguage(fss_calendar_locale);
		myCalendarTo = new dhtmlXCalendarObject('timetaken_end','omega');
		myCalendarTo.setDateFormat('<?php echo FSS_Helper::getCalFormat(); ?>');
		myCalendarTo.loadUserLanguage(fss_calendar_locale);
});
</script>
						
					<?php else: ?>
						<span class="help-inline"><?php echo JText::_('TIME_HOURS'); ?>: </span><input type="text" name="timetaken_hours" id="timetaken_hours" style="width: 40px" value="<?php echo FSS_Helper::escape($this->taken_hours); ?>" /> 
						<span class="help-inline"><?php echo JText::_('TIME_MINS'); ?>: </span><input type="text" name="timetaken_mins" id="timetaken_mins" style="width: 40px" value="<?php echo FSS_Helper::escape($this->taken_mins); ?>" /> 
						<a class="btn btn-default" onclick="jQuery('#timetaken_hours').val('0');jQuery('#timetaken_mins').val('0');return false;"><i class="icon-cancel"></i></a>
					<?php endif; ?>
				</div>
			</div>
	<?php endif; ?>
	
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_reply_' . $this->reply_type . '.php'); ?>
	
	<div class="control-group">
		<label class="control-label"><?php echo JText::_("TAGS"); ?></label>
		<div class="controls">
			<div class="pull-left">
				<div style="position: relative;">
					<a class="dropdown-toggle padding-right-small" data-toggle="dropdown" href="#">
						<i class="icon-new fssTip" title="<?php echo JText::_('ADD_TAGS'); ?>"></i>
					</a>
					<ul class="dropdown-menu">
						<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_tag_list.php'); ?>
					</ul>
				</div>
			</div>
			
			<div id="tags">
				<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_tags.php'); ?>
			</div>
		</div>
	</div>
		
	<div class="control-group">
		<label class="control-label"><?php echo JText::sprintf('UPLOAD_FILE',FSS_Helper::display_filesize(FSS_Helper::getMaximumFileUploadSize())); ?></label>
		<div class="controls">
			<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'tmpl'.DS.'attach.php'; ?>
		</div>
	</div>
		
	<div class="control-group">
		<label class="control-label">
			<?php echo JText::_('SIGNATURE'); ?>
			<a class="show_modal_iframe fssTip padding-left-mini" title="<?php echo JText::_('EDIT_SIGNATURES'); ?>" href="<?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&layout=signature&tmpl=component" ); ?>"><i class="icon-edit"></i></a>
			<a class="show_modal_iframe fssTip" onclick="previewSig(this);" title="<?php echo JText::_('PREVIEW_SIGNATURE'); ?>" href="<?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&task=signature.preview&tmpl=component&ticketid=" . $this->ticket->id ); ?>"><i class="icon-search"></i></a>
		</label>

		<div class="controls" id="signature_container">
			<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_signature_dropdown.php'); ?>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label"></label>
		<div class="controls">
			<input  class='btn btn-primary' type='submit' value='<?php echo JText::_($this->reply_button); ?>' id='addcomment'>
			<a class="btn btn-default" href="<?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=" . $this->ticket->id); ?>"><?php echo JText::_('JCANCEL'); ?></a>
			<a class="btn btn-info" href="#" onclick='jQuery("#what").val("draft");jQuery("#newticket").submit();return false;'><?php echo JText::_('SAVE_DRAFT'); ?></a>
		</div>
	</div>

</form>


<div id="canned_replies" style="display: none;">
<?php echo SupportCanned::CannedList($this->ticket); ?>
</div>

<ul class="nav nav-tabs <?php if (FSS_Input::getInt('ticketid') > 0) echo "nav-always"; ?>">

	<li class="active">
		<a href='#messages' data-toggle="tab">
			<?php echo JText::_('MESSAGES'); ?>
		</a>
	</li>

	<li>
		<a href='#details' data-toggle="tab">
			<?php echo JText::_('TICKET_DETAILS'); ?>
		</a>
	</li>

	<?php if (count($this->ticket->attach) > 0) : ?>
		<li>
			<a href='#attachments' data-toggle="tab">
				<?php echo JText::_('ATTACHEMNTS'); ?>
			</a>
		</li>
	<?php endif; ?>
</ul>

<?php $this->print = "all"; ?>
<?php $this->replying = true; ?>
<div class="tab-content">
	<div class="tab-pane active" id="messages">
		<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_messages.php'); ?>
	</div>
	
	<div class="tab-pane" id="details">
		<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_ticket_info.php'); ?>
	</div>
	
	<?php if (count($this->ticket->attach) > 0) : ?>
		<div class="tab-pane" id="attachments">
			<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_attachments.php'); ?>
		</div>
	<?php endif; ?>
</div>
<?php unset($this->print); ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>

<script>

jQuery(document).ready(function () {
	setTimeout("setup_empty_message();", 500);
});

function setup_empty_message()
{
	jQuery('#newticket').submit( function () {
		var has_reply = false;
		
		if (jQuery('#newticket textarea[name="body2"]').length > 0)
		{
			try {
				var editor = jQuery('#newticket textarea[name="body2"]').sceditor("instance");
				editor.updateOriginal();	
			} catch (e) {
			}
				
			if (jQuery('#newticket textarea[name="body2"]').val() == "")
			{
				has_reply = true;
			}
		}
		
		if (jQuery('#newticket textarea[name="body"]').length > 0)
		{
			try {
				var editor = jQuery('#newticket textarea[name="body"]').sceditor("instance");
				editor.updateOriginal();	
			} catch (e) {
			}

			if (jQuery('#newticket textarea[name="body"]').val() == "")
			{
				has_reply = true;
			}
		}

		<?php if ($this->reply_type == "handler"): ?>
			if (jQuery('#new_handler').val() == -1)
			{
				if (!confirm("<?php echo JText::_('HANDLER_IS_UNCHANGED_STILL_POST'); ?>"))
				{
					return false;
				}
			}
		<?php endif; ?>

		
		if (!has_reply)
			return true;
			
		return confirm("<?php echo JText::_('ARE_YOU_SURE_EMPTY_MESSAGE'); ?>");
	});
}

// adding and removing tags
function tag_remove(tagname)
{
	if (tagname == "")
		return;
	
	jQuery('#tags').html("<?php echo JText::_('PLEASE_WAIT'); ?>");
	var url = '<?php echo FSSRoute::x("&task=update.remove_tag", false); ?>&tag=' + encodeURIComponent(tagname);
	jQuery('#tags').load(url);
	return false;
}

function tag_add(tagname)
{
	if (tagname == "")
		return;
	
	jQuery('#tag_list_container').removeClass("open");	
	jQuery('#tags').html("<?php echo JText::_('PLEASE_WAIT'); ?>");
	var url = '<?php echo FSSRoute::x("&task=update.add_tag", false); ?>&tag=' + encodeURIComponent(tagname);
	jQuery('#tags').load(url);
	return false;
}

function sigsRefresh()
{
	jQuery('#signature_container').html("<?php echo JText::_('PLEASE_WAIT'); ?>");
	var url = '<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&tmpl=component&layout=signature&task=signature.dropdown', false); ?>';
	jQuery('#signature_container').load(url);
}

<?php if (FSS_Settings::get('time_tracking') == "auto"): ?>
// auto time tracking
jQuery(document).ready(function () {
	setInterval("increaseTime();", 60 * 1000);
});

function increaseTime()
{
	var mins = parseInt(jQuery('#timetaken_mins').val());
	mins++;
	
	if (mins < 60)
	{
		jQuery('#timetaken_mins').val(mins);
	} else {
		mins = mins - 60;
		jQuery('#timetaken_mins').val(mins);
		jQuery('#timetaken_hours').val(parseInt(jQuery('#timetaken_hours').val()) + 1);
	}
}
<?php endif; ?>

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

function insertCannedText(value, id)
{
	<?php if (FSS_Settings::Get('support_sceditor')): ?>
	jQuery('#' + id).sceditor('instance').insert(value);	
	<?php else: ?>
	jQuery('#' + id).val(jQuery('#' + id).val() + value);
	<?php endif; ?>
}

function insertSubject(text)
{
	jQuery('#subject').val(text);
}

function insertStatus(text)
{
	if (text != "" && text > 0)
		jQuery('#reply_status').val(text);
}

function insertSource(text)
{
	jQuery('#source').val(text);
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
	var url = '<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&layout=reply&task=canned.dolist&ticketid=' . $this->ticket->id, false); ?>';
	jQuery('#canned_replies').load(url);
	
	
	jQuery('.canned_list').html("<div class='pull-right'><?php echo JText::_('PLEASE_WAIT'); ?></div>");
	
	var cr_url = '<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&layout=reply&task=canned.dropdown&ticketid=' . $this->ticket->id, false); ?>';
	jQuery('.canned_list').each( function () {
		var target = jQuery(this);		
		var url = cr_url + "&elem=" + target.attr('editid');
				
		jQuery.get(url, function (data) {
			target.html(data);
			
			init_elements();
		});
	});		
}

function previewSig(el)
{
	var current = jQuery('#signature').val();
	var base_url = jQuery(el).attr('base_href');
	if (typeof base_url === "undefined")
	{	
		base_url = jQuery(el).attr('href');
		jQuery(el).attr('base_href', base_url);	
	}
	
	base_url += "&sigid=" + current;
	
	jQuery(el).attr('href', base_url);	
}
</script>
