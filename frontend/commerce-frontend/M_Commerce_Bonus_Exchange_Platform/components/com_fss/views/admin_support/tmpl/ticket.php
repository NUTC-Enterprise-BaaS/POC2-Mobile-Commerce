<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php FSS_Translate_Helper::CalenderLocale(); ?>

<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle('SUPPORT_ADMIN',"VIEW_SUPPORT_TICKET"); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php'); ?>
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_supportbar.php'); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_ticket_notices.php'); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_ticket_toolbar.php'); ?>

<?php 
if (FSS_Permission::auth("core.create", "com_fss.kb") || FSS_Permission::auth("core.create", "com_fss.faq")) 
	include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_export.php'); 
?>

<?php if (FSS_Settings::get("messages_at_top") == 2 || FSS_Settings::get("messages_at_top") == 3)
	include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_messages_cont.php'); ?>

<?php echo FSS_Helper::PageSubTitle("TICKET_DETAILS"); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_ticket_info.php'); ?>

<?php if (FSS_Settings::get("messages_at_top") == 0 || FSS_Settings::get("messages_at_top") == 1)
	include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_messages_cont.php'); ?>


<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>

<script>

// Editing title
function title_edit_start()
{
	jQuery('#title_input').val(jQuery('#title_value').text());
	jQuery('#title_input').val(jQuery('#title_input').val().replace(/^\s+|\s+$/g, ''));
	jQuery('#title_show').hide();
	jQuery('#title_edit').show();
}

function title_edit_end()
{
	jQuery('#title_edit').hide();
	jQuery('#title_show').show();
}

function title_edit_save()
{
	jQuery('#title_value').html("<?php echo JText::_('PLEASE_WAIT'); ?>");
	title_edit_end();
	var url = "<?php echo FSSRoute::x("&task=update.subject", false); ?>&subject=" + encodeURIComponent(jQuery('#title_input').val());
	jQuery('#title_value').load(url);
}

// editing email
function email_edit_start()
{
	jQuery('#email_input').val(jQuery('#email_value').text());
	jQuery('#email_input').val(jQuery('#email_input').val().replace(/^\s+|\s+$/g, ''));
	jQuery('#email_show').hide();
	jQuery('#email_edit').show();
}

function email_edit_end()
{
	jQuery('#email_edit').hide();
	jQuery('#email_show').show();
}

function email_edit_save()
{
	jQuery('#email_value').html("<?php echo JText::_('PLEASE_WAIT'); ?>");
	email_edit_end();

	var url = "<?php echo FSSRoute::x("&task=update.email", false); ?>&email=" + escape(jQuery('#email_input').val());

	jQuery('#email_value').load(url);
}

// changing priority
function priority_update()
{
	var new_pri_id = jQuery('#ticket_pri_id').val();
	jQuery('#priority_update_status').remove();
	var url = "<?php echo FSSRoute::x("&task=update.ticket_pri_id", false); ?>&ticket_pri_id=" + escape(new_pri_id);
	jQuery('#ticket_pri_id').parent().append("<span class='help-inline' id='priority_update_status'><?php echo JText::_('PLEASE_WAIT'); ?></span>");
	jQuery.get(url, function() {
		jQuery('#priority_update_status').text("<?php echo JText::_('Saved'); ?>");
		setTimeout("jQuery('#priority_update_status').remove();", 2000);
	});
}

// changing category
function category_edit_toggle()
{
	jQuery('#cat_edit').toggle();
	jQuery('#cat_show').toggle();
}

function category_edit_save()
{
	jQuery('#cat_value').html("<?php echo JText::_('PLEASE_WAIT'); ?>");
	category_edit_toggle();
	var url = "<?php echo FSSRoute::x("&task=update.ticket_cat_id", false); ?>&ticket_cat_id=" + escape(jQuery('#catid').val());
	jQuery('#cat_value').load(url);
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


// automatic ticket lock update
<?php if (FSS_Settings::get('support_lock_time') > 0 && !$this->ticket->isLocked()): ?>
function lock_update(do_get)
{
	if (do_get)
	{
		var url = '<?php echo FSSRoute::_("&task=update.lock"); ?>'; // FIX LINK
		jQuery('#lock_ticket').load(url);
	}
	
	
	setTimeout ( 'lock_update(true)', <?php echo FSS_Settings::get('support_lock_time') < 10 ? 10000 : FSS_Settings::get('support_lock_time')*1000 ; ?> );
}

lock_update(false);
<?php endif; ?>


// ticket time taken

function time_set(time)
{
	var hours = ~~(time / 60);
	var mins = time % 60;
	
	jQuery('#taken_mins').val(mins);
	jQuery('#taken_hours').val(hours);
}

function time_set_hour(time)
{
	jQuery('#taken_hours').val(time);
}

function time_set_min(time)
{
	jQuery('#taken_mins').val(time);
}

function time_add(removed)
{
	if (typeof(removed) == "undefined")
		removed = 0;

	var notes = jQuery('#taken_notes').val();

<?php if (FSS_Settings::get('time_tracking_require_note')): ?>
	if (notes.length < 5)
	{
		alert("<?php echo JText::_('TIME_TRACK_NOTE_REQUIRED'); ?>");
		return;
	}
<?php endif; ?>

	var hours = jQuery('#taken_hours').val();
	var mins = jQuery('#taken_mins').val();
	
	try {
		hours = parseInt(hours);
	} catch (e) {
		hours = 0;
	}
		
	try {
		mins = parseInt(mins);
	} catch (e) {
	    mins = 0;
	}
	
	var time = hours * 60 + mins;
	
	if (removed)
		time = 0 - time;
	
	if (time == 0)
	{
		alert("<?php echo JText::_('TIME_TRACK_NO_TIME_ENTERED'); ?>");
		return;
	}
		
	// reset the form
	jQuery('#taken_mins').val("0");
	jQuery('#taken_hours').val("0");
	jQuery('#taken_notes').val("");
	
	jQuery('#time_take_popup').modal("hide");	
	
	// submit additional ticket time to server
	var url = '<?php echo FSSRoute::_("task=update.time", false); ?>&time=' + time + '&notes=' + encodeURIComponent(notes); // FIX LINK
	
	jQuery.get(url);
	
	// update display
	var curtime = parseInt(jQuery('#time_taken_value').text());
	curtime += time;
	
	if (curtime < 0)
		curtime = 0;
	
	time_update(curtime);	
}

function time_update(time)
{
	var display = '<?php echo JText::sprintf("TIME_TAKEN_DISP", 'XXHXX', 'XXMXX'); ?>';
	
	jQuery('#time_taken_value').text(time);
	
	var hours = ~~(time / 60);
	var mins = time % 60;
	if (mins < 10)
		mins = "0" + mins;
	
	display = display.replace("XXHXX", hours);
	display = display.replace("XXMXX", mins);
	
	jQuery('#time_taken_disp').html(display);	
}

// old javascript below here!!!
var edit_id = null;
var edit_title = null;
var edit_body = null;
var edit_ok = null;
var edit_cancel = null;
var edit_orig_message = null;
var edit_orig_title = null;
var edit_button = null;
var edit_scedit = null;
var edit_datemode = '';
var edit_CalendarFrom = null;
var edit_CalendarTo = null;
var edit_time = 0;

jQuery(document).ready(function () {
	jQuery('.editmessage').click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		if (edit_id != null)
		{
			if (confirm("Do you want to save the current edit?"))
			{
				SaveEdit();
			} else {
				CancelEdit();
			}			
		}
		
		if (jQuery(this).hasClass('timeonly'))
		{
			edit_time = 1;
		} else {
			edit_time = 0;
		}
	
		edit_id = jQuery(this).attr('id').replace('edit_','');
		edit_scedit = null;
		
		var textareawidth = jQuery('#message_' + edit_id).parent().width();
		var textareaheight = jQuery('#message_' + edit_id).parent().height();
		
		if (!edit_time)
		{
		
			edit_orig_title = jQuery('#subject_' + edit_id).text();
		
			var html = "<span class='input-append'>";
			html += "<input type='text' class='input-xlarge' name='input_title_" + edit_id + "' id='input_title_" + edit_id + "'>";
			html += "<button onclick='SaveEdit();' class='fssTip btn btn-success' title='Save Changes'><i class='icon-save'></i> Save</button>";
			html += "<button onclick='CancelEdit();' class='fssTip btn btn-danger' title='Cancel Edit'><i class='icon-cancel'></i> Cancel</button>";
			html += "</span>";

			<?php if (FSS_Settings::get('allow_edit_no_audit')): ?>
			html += "<span style='font-size: 85%;font-weight: normal;position: absolute;'>";
			html += "<input type='checkbox' name='no_audit' id='no_audit' style='margin-left: 6px;margin-top: 0px;' /> <?php echo JText::_('NO_LOG'); ?></span>";
			<?php endif; ?>

			html += "<button onclick='DeleteMessage();' class='fssTip btn btn-warning pull-right' title='Delete Message' style='margin-right: 4px;'><i class='icon-delete'></i></button>";
				
			jQuery('#subject_' + edit_id).html(html);
		
			jQuery('#subject_' + edit_id + ' .fssTip').fss_tooltip();
		
			jQuery('#input_title_' + edit_id).val(edit_orig_title);

			// need to replace #message_id with message edit
			edit_orig_message = jQuery('#message_raw_' + edit_id).html();
		
			jQuery('#message_' + edit_id).html("");
		} else {
			html = "<button onclick='SaveEdit();' class='fssTip btn btn-success' title='Save Changes'><i class='icon-save'></i> Save</button>&nbsp;";
			html += "<button onclick='CancelEdit();' class='fssTip btn btn-danger' title='Cancel Edit'><i class='icon-cancel'></i> Cancel</button>";
		
			jQuery('#message_' + edit_id).html(html);
		}
		
		// add time tracking stuff if needed
		if (jQuery('.ticket_message_id_' + edit_id + ' .ticket_time_date').length > 0)
		{
			edit_datemode = "date";
			var html = '<div class="form-horizontal form-condensed"><div class="control-group">';
			html = html + '<label class="control-label">Time Taken</label>';
			html = html + '<div class="controls">';
			html = html + '<span class="help-inline">Start: </span>';
			html = html + '<input name="datetaken_start" id="datetaken_start" type="text" style="width: 130px" value="">';
			html = html + '<span class="help-inline">End: </span>';
			html = html + '<input name="datetaken_end" id="datetaken_end" type="text" style="width: 130px" value="">';
			html = html + '</div></div></div>';
			
			jQuery('#message_' + edit_id).append(html);
			
			edit_CalendarFrom = new dhtmlXCalendarObject('datetaken_start','omega');
			edit_CalendarFrom.setDateFormat('%Y-%m-%d, %H:%i');
			edit_CalendarFrom.setDate(new Date(jQuery('.ticket_message_id_' + edit_id + ' .ticket_time_start').text()*1000));
			edit_CalendarFrom.loadUserLanguage(fss_calendar_locale);
			jQuery('#datetaken_start').val(edit_CalendarFrom.getDate(true));
			
			edit_CalendarTo = new dhtmlXCalendarObject('datetaken_end','omega');
			edit_CalendarTo.setDateFormat('%Y-%m-%d, %H:%i');
			edit_CalendarTo.setDate(new Date(jQuery('.ticket_message_id_' + edit_id + ' .ticket_time_end').text()*1000));
			edit_CalendarTo.loadUserLanguage(fss_calendar_locale);
			jQuery('#datetaken_end').val(edit_CalendarTo.getDate(true));
		} else if (jQuery('.ticket_message_id_' + edit_id + ' .ticket_time_time').length > 0)
		{
	
			edit_datemode = "time";
			var html = '<div class="form-horizontal form-condensed"><div class="control-group">';
			html = html + '<label class="control-label">Time Taken</label>';
			html = html + '<div class="controls">';
			html = html + '<span class="help-inline">Start: </span>';
			html = html + '	<div class="input-append bootstrap-timepicker">';
			html = html + '		<input name="timetaken_start" id="timetaken_start" type="text" style="width: 40px" value="' + jQuery('.ticket_message_id_' + edit_id + ' .ticket_time_start').text() + '">';
			html = html + '		<span class="add-on">';
			html = html + '			<i class="icon-clock"></i>';
			html = html + '		</span>';
			html = html + '	</div>';
			html = html + '<span class="help-inline">End: </span>';
			html = html + '	<div class="input-append bootstrap-timepicker">';
			html = html + '		<input name="timetaken_end" id="timetaken_end" type="text" style="width: 40px" value="' + jQuery('.ticket_message_id_' + edit_id + ' .ticket_time_end').text() + '">';
			html = html + '		<span class="add-on">';
			html = html + '			<i class="icon-clock"></i>';
			html = html + '		</span>';
			html = html + '	</div>';
			html = html + '</div></div></div>';
			
			jQuery('#message_' + edit_id).append(html);
			
			jQuery('#timetaken_start').timepicker({minuteStep:5, showMeridian: false});
			jQuery('#timetaken_end').timepicker({minuteStep:5, showMeridian: false});
		} else if (jQuery('.ticket_message_id_' + edit_id + ' .ticket_time_duration').length > 0)
		{
	
			edit_datemode = "dur";
			var html = '<div class="form-horizontal form-condensed"><div class="control-group">';
			html = html + '<label class="control-label">Time Taken</label>';
			html = html + '<div class="controls">';
			html = html + '<span class="help-inline">Hours: </span>';
			html = html + '<input name="timetaken_hours" id="timetaken_hours" type="text" style="width: 40px" value="' + jQuery('.ticket_message_id_' + edit_id + ' .ticket_time_hours').text() + '">';
			html = html + '<span class="help-inline">Mins: </span>';
			html = html + '<input name="timetaken_mins" id="timetaken_mins" type="text" style="width: 40px" value="' + jQuery('.ticket_message_id_' + edit_id + ' .ticket_time_mins').text() + '">';
			html = html + '</div></div></div>';
			
			jQuery('#message_' + edit_id).append(html);
		}
		
		if (!edit_time)
		{
			var input = jQuery('<textarea>');
			input.attr('id','input_body_' + edit_id);
			input.attr('name','input_body_' + edit_id);
			input.html(edit_orig_message);
			input.addClass('sceditor_hidden');
			input.css('width', textareawidth + 'px');
			input.css('height', textareaheight + 'px');
			edit_body = input;
		
			jQuery('#message_' + edit_id).append(input);
		
			// if we have the option to scedit this, then do so
			if (typeof sceditor_emoticons_root != 'undefined')
			{
				var height = parseInt(input.css('height')) + 100;
				if (height > 450)
					height = 450;
				input.css('height', height + 'px');
				
				var filter = true;
				if (sceditor_paste == "raw") filter = false;

				edit_scedit = jQuery("textarea.sceditor_hidden").sceditor({
					plugins: "bbcode",
					style: sceditor_style_root + "jquery.sceditor.default.css",
					emoticonsRoot: sceditor_emoticons_root,
					toolbarExclude: sceditor_toolbar_exclude,
					enablePasteFiltering: filter
				});
		
				jQuery("textarea.sceditor_hidden").removeClass('sceditor_hidden');
				
				jQuery('.sceditor-toolbar').css('overflow', 'inherit');
				
				sceditor_clipboard();
			}	
		}
	
		return false;
	});

	jQuery('.attach_delete').click(function (ev) {
		if (confirm("<?php echo str_replace("\"","\\\"",JText::_('ATTACH_DELETE_WARN')); ?>"))
		{
			return true;
		}

		return false;
	});
});

function SaveEdit()
{
	if (edit_id == null)
		return;
	
	if (edit_scedit)
	{
		var editor = edit_scedit.sceditor("instance");
		editor.updateOriginal();
	}
		
	
	var url = '<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&task=update.comment&messageid=XXMIDXX', false ); ?>';
	url = url.replace('XXMIDXX', encodeURIComponent(edit_id));
	
	if (jQuery('#no_audit').is(':checked')) {
		url += "&noaudit=1";
	}

	if (!edit_time)
	{
		var title = jQuery('#input_title_' + edit_id).val();
		var body = jQuery('#input_body_' + edit_id).val();
		url += "&subject=" + encodeURIComponent(title);
		url += "&body=" + encodeURIComponent(body);
	}
	
	if (jQuery('#datetaken_start').val())
	{
		edit_CalendarFrom.setDateFormat('%Y-%m-%d %H:%i');
		var from = edit_CalendarFrom.getDate(true);
		edit_CalendarTo.setDateFormat('%Y-%m-%d %H:%i');
		var to = edit_CalendarTo.getDate(true);
		url = url + "&datefrom=" + encodeURIComponent(from) + "&dateto=" + encodeURIComponent(to);
		
	} else if (jQuery('#timetaken_start').val())
	{
		url += "&timefrom=" + encodeURIComponent(jQuery('#timetaken_start').val());
		url += "&timeto=" + encodeURIComponent(jQuery('#timetaken_end').val());
		
	} else if (jQuery('#timetaken_hours').val())
	{
		url += "&timehours=" + encodeURIComponent(jQuery('#timetaken_hours').val());
		url += "&timemins=" + encodeURIComponent(jQuery('#timetaken_mins').val());
	}

	//window.open(url, '_blank');
	
	jQuery.ajax({
	  url: url
	}).done(function( data ) {
		if (data == "{reload}")
		{
			location.reload(); 
		}
	});

	jQuery('.fss_main .fssTip').fss_tooltip('hide');

	jQuery('#message_raw_' + edit_id).html(body);
	ReBuildMessage(edit_id, title, body)
		
	edit_id = null;
}

function CancelEdit()
{
	if (edit_id == null)
		return;

	jQuery('.fss_main .fssTip').fss_tooltip('hide');

	if (jQuery('#input_title_' + edit_id).length > 0)
	{	
		// normal message rebuild	
		ReBuildMessage(edit_id, edit_orig_title, edit_orig_message);
	} else {
		// time only message rebuild
		jQuery('#message_' + edit_id).html("<div class='bbcode'></div>");
	}
	
	edit_id = null;
}

function DeleteMessage()
{
	if (edit_id == null)
		return;

	if (confirm("Are you sure you want to delete this message?"))
	{
		var url = '<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&task=update.delete_message&messageid=XXMIDXX', false ); ?>';
		url = url.replace('XXMIDXX', encodeURIComponent(edit_id));

		jQuery.ajax({
		  url: url
		});	

		jQuery('.fss_main .fssTip').fss_tooltip('hide');

		jQuery('.ticket_message_id_' + edit_id).remove();
	
		edit_id = null;
	}
}

function ReBuildMessage(id, title, body)
{
	jQuery(edit_button).css('display','inline');
	jQuery(edit_ok).remove();
	jQuery(edit_cancel).remove();
		
	jQuery(edit_button).parent().css('width','40px');
	jQuery(edit_button).parent().attr('width','40');

	if (edit_scedit)
	{
		var editor = edit_scedit.sceditor("instance");
		body = editor.getBody().html();
	}
	
	if (!body) body = "";
		
	if (!edit_scedit)
	{
		body = body.replace(/</g,"&lt;");
		body = body.replace(/>/g,"&gt;");
		body = body.replace(/&amp;/g,"&");
		body = body.replace(/\n/g,"<br />");
	}
	
	jQuery('#subject_' + id).html(title);
	jQuery('#message_' + id).html("<div class='bbcode'>" + body + "</div>");
}

function addCC(user_id, is_admin, is_readonly)
{
	var ids = user_id;
	
	if (typeof(user_id) != "string")
	{
		ids = user_id.join(",");	
	}
	// add users here, and reload the relevant cc or reload page if not there
	var url = "<?php echo FSSRoute::_("&ticketid=" . $this->ticket->id . "&task=ticket.addcc", false); ?>&ids=" + escape(ids) + "&is_readonly=" + escape(is_readonly) + "&is_admin=" + escape(is_admin) + "&nocache=" + new Date().getTime();
	
	window.location = url;
}

function addEMailCC(email)
{
	var url = "<?php echo FSSRoute::x("&ticketid=" . $this->ticket->id . "&task=ticket.addemailcc", false); ?>&email=" + escape(email) + "&nocache=" + new Date().getTime();
	window.location = url;
}

jQuery(document).ready( function () {
	jQuery('.user_cc a').click( function(ev) {
		ev.preventDefault();
		
		var url = jQuery(this).attr('href');
		url = url + "&nr=1" + "&nocache=" + new Date().getTime();
		
		jQuery.ajax({
			url: url
		});
		
		jQuery(this).parent().remove();
	});
});

<?php if ($this->do_refresh): ?>
jQuery(document).ready( function () {
	setInterval("fss_refresh_tickets()", <?php echo $this->do_refresh * 1000; ?> );
});

function fss_refresh_tickets() {
    var url = jQuery(location).attr('href');
    url += "&tmpl=component&refresh=2";

    jQuery.get(url, function (result) {
        if (!jQuery('#batch_form').is(":visible")) {

            jQuery('#fss_ticket_list').html(result.tickets);

            for (status in result.count) {
                var count = result.count[status];

                jQuery('.ticket_count_' + status).html(count);
            }
        }
    });
}

<?php endif; ?>


</script>

<div id='lock_ticket'></div>