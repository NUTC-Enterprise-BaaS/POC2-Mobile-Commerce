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
<?php echo FSS_Helper::PageTitle("SUPPORT","VIEW_SUPPORT_TICKET"); ?>

<?php 

$table_cols = FSS_Settings::get('support_info_cols_user');

$table_classes = "table table-borderless table-valign table-condensed table-narrow";
if ($table_cols > 1)
	$table_classes = "table table-borderless table-valign table-condensed";
?>

<div class="hide" style="display: none;" id="fss_ticket_base_url"><?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&layout=view&ticketid=' . $this->ticket->id); ?></div>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_tabbar.php'); ?>

<?php FSS_Helper::HelpText("support_user_view_header"); ?>

<?php
$session = JFactory::getSession();
$value = $session->get("ticket_open_message");
$session->clear("ticket_open_message");
if ($value): ?>
<div class="alert alert-success fss_ticket_reply_message">
	<a class="close" data-dismiss="alert">&times;</a>
	<?php echo $value; ?>
</div>
<?php endif; ?>

<?php if (FSS_Permission::auth("fss.handler", "com_fss.support_admin")): ?>
	<?php 
		$adminticket = new SupportTicket();
	if ($adminticket->canLoad($this->ticket->id)): ?>
		<div class="alert alert-info">
			<?php echo JText::sprintf("FSS_YOU_HAVE_PERMISSION_TO_VIEW_THIS_TICKET_AS_A_HANDLER_WITH_MORE_FUNCTIONALITY", FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $this->ticket->id, false)); ?>
		</div>
	<?php endif; ?>
<?php endif; ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ticket_rate.php'); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ticket_print.php'); ?>

<?php if (FSS_Settings::get("messages_at_top") == 1 || FSS_Settings::get("messages_at_top") == 3)
	include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_messages_cont.php'); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ticket_merged.php'); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ticket_info.php'); ?>

<?php if (FSS_Settings::get("messages_at_top") == 0 || FSS_Settings::get("messages_at_top") == 2)
	include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_messages_cont.php'); ?>

<?php if (count($this->ticket->attach) > 0) : ?>
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ticket_attach.php'); ?>
<?php endif; ?>

<script>

function doPrint(link)
{
	printWindow = window.open(jQuery(link).attr('href')); 
	return false;
}

function CreateEvents()
{
	jQuery('#addcomment').click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		jQuery('#messagereply').hide();
		jQuery('#messagepleasewait').show();

		jQuery('#inlinereply').submit();
		
		jQuery('#new_status').removeAttr('disabled');
		jQuery('#new_pri').removeAttr('disabled');

	});	
	
	jQuery('#replyclose').click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		
		jQuery('#should_close').val("1");
		jQuery('#messagereply').hide();
		jQuery('#messagepleasewait').show();
		
		jQuery('#inlinereply').submit();
		
		jQuery('#new_status').removeAttr('disabled');
		jQuery('#new_pri').removeAttr('disabled');
	});	
	
	jQuery('#replycancel').click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		jQuery('#messagereply').hide();
		jQuery('.post_reply').show();
		jQuery('#body').val("");

		jQuery('#new_status').removeAttr('disabled');
		jQuery('#new_pri').removeAttr('disabled');
	});
}

jQuery(document).ready(function () {
	jQuery('.post_reply').click(function(ev) {
		try {
			jQuery('#messagereply').show();
			jQuery('.post_reply').hide();
			jQuery('.fss_ticket_reply_message').hide();
			jQuery('#new_status').attr('disabled', 'disabled');
			jQuery('#new_pri').attr('disabled', 'disabled');
		
<?php if (FSS_Settings::Get('support_sceditor')): ?>		
			if (typeof sceditor_emoticons_root != 'undefined')
			{
				var rows = parseInt(jQuery("textarea.sceditor_hidden").attr('rows'));
				jQuery("textarea.sceditor_hidden").attr('rows', rows + 8);
				jQuery("textarea.sceditor_hidden").addClass('sceditor');
				jQuery("textarea.sceditor_hidden").removeClass('sceditor_hidden');
			
				init_sceditor();
			}
<?php endif; ?>
			ev.stopPropagation();
			ev.preventDefault();
		} catch (e) {
		}
	});

	jQuery('.ticketrefresh').click(function(ev) {
		ev.preventDefault();
		
		jQuery('#messagepleasewait').show();
		
		// fake height on please wat to stop page flickering so much
		try {
			var height = jQuery('#ticket_messages').height() - jQuery('#messagepleasewait').height() - 6;
			jQuery('#messagepleasewait').css('margin-bottom', height + 'px');
		} catch (e) {
		}
		
		jQuery('#ticket_messages').html("");
		//alert("Load");
		
		jQuery('#ticket_messages').load(jQuery(this).attr('href') + "&rand=" + Date.now(), function () {
			jQuery('#messagepleasewait').hide();
			//alert("Done");
		});
	});	

	CreateEvents();	
});

function AddCCUser(userid, readonly)
{
	fss_modal_hide();
	
	jQuery('#ccusers').html('<?php echo JText::_('PLEASE_WAIT'); ?>');
	
	var url = jQuery('#fss_ticket_base_url').text();
	url = fss_url_append(url, 'task', 'update.addccuser');
	url = fss_url_append(url, 'userid', userid);
	url = fss_url_append(url, 'readonly', readonly);
	
	jQuery.ajax({
		url: url,
		context: document.body,
		success: function(result){
			jQuery('#ccusers').html(result);
		}
	});
}

function removecc(userid)
{
	jQuery('#ccusers').html('<?php echo JText::_('PLEASE_WAIT'); ?>');
	
	var url = jQuery('#fss_ticket_base_url').text();
	url = fss_url_append(url, 'task', 'update.removeccuser');
	url = fss_url_append(url, 'userid', userid);

	jQuery.ajax({
		url: url,
		context: document.body,
		success: function(result){
			jQuery('#ccusers').html(result);
		}
	});
}

var sneaky = new ScrollSneak('freestyle-support');
function refreshPage()
{
	sneaky.sneak();
	window.location = window.location;
}

</script>


<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>
