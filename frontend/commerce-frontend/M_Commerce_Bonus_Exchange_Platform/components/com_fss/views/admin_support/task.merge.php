<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'task.php');

/**
 * Stuff related to archiving and deleting tickets
 **/

class Task_Merge extends Task_Helper
{
	function merge()
	{
		$source_id = FSS_Input::getInt('source_id');
		$dest_id = FSS_Input::getInt('dest_id');
		
		if ($source_id < 1)
			return $this->cancel();
		
		if ($dest_id < 1)
			return $this->cancel();
		
		$source = new SupportTicket();
		if (!$source->load($source_id))
			return $this->cancel();
		
		$dest = new SupportTicket();
		if (!$dest->load($dest_id))
			return $this->cancel();
	
		//print_p($source);
	
		// need to copy messages
		$db = JFactory::getDBO();
				
		$sql = "SELECT * FROM #__fss_ticket_messages WHERE ticket_ticket_id = " . $db->escape($source_id);
		$db->setQuery($sql);
		$messages = $db->loadObjectList();
		
		$msg_map = array();
		
		foreach ($messages as $message)
		{
			$old_id = $message->id;
			unset($message->id);
			$message->ticket_ticket_id = $dest_id;
			
			$new_id = $this->Insert("#__fss_ticket_messages", $message);
			
			$msg_map[$old_id] = $new_id;
			
		}
		
		// add time
		$qry = "UPDATE #__fss_ticket_ticket SET timetaken = timetaken + " . (int)$source->timetaken . " WHERE id = " . $dest_id;
		$db->setQuery($qry);
		$db->Query();
		
		if ($source->timetaken > 0)
		{
			$qry = "UPDATE #__fss_ticket_ticket SET timetaken = 0 WHERE id = " . $source_id;
			$db->setQuery($qry);
			$db->Query();
			$source->addAuditNote("Time taken cleared as merged with another ticket");
		}
		
		// copy files
		$sql = "SELECT * FROM #__fss_ticket_attach WHERE ticket_ticket_id = " . $db->escape($source_id);
		$db->setQuery($sql);
		$attachments = $db->loadObjectList();
		
		foreach ($attachments as $attachment)
		{

			unset($attachment->id);
			$attachment->ticket_ticket_id = $dest_id;
			
			// change id to that of new message
			if (array_key_exists($attachment->message_id, $msg_map))
				$attachment->message_id = $msg_map[$attachment->message_id];
			
			$new_id = $this->Insert("#__fss_ticket_attach", $attachment);
		}
		
		// add audit messages to both
		$source->addAuditNote("Ticket merged into another ticket then closed. Dest Ticket: " . $dest->reference . " - " . $dest->title);
		$dest->addAuditNote("Ticket merged into this one. Merged Ticket: " . $source->reference . " - " . $source->title);
		
		// close source ticket
		$closed = FSS_Ticket_Helper::GetStatusID('def_closed');
		
		// add merge tag to source ticket
		$now = FSS_Helper::CurDate();
		$qry = "UPDATE #__fss_ticket_ticket SET merged = " . (int)$dest_id . ", ticket_status_id = " . (int)$closed . ", lastupdate = '{$now}', closed = '{$now}' WHERE id = " . (int)$source_id;
		$db->setQuery($qry);
		$db->Query();
		
		// redirect to new ticket
		
		// TODO:
		
		/*
		Need to copy cc information on the ticket
		*/
		
		$session = JFactory::getSession();
		$session->clear('merge');
		$session->clear('merge_ticket_id');
		
?>
<script>
window.location = '<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $dest_id, false); ?>';
</script>
<?php
		exit;
	}
	
	function cancel()
	{
		JFactory::getApplication()->redirect(FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=list&merge=cancel', false));	
	}	
	
	function related()
	{
		$source_id = FSS_Input::getInt('source_id');
		$dest_id = FSS_Input::getInt('dest_id');
		
		if ($source_id < 1)
			return $this->cancel();
		
		if ($dest_id < 1)
			return $this->cancel();
		
		$source = new SupportTicket();
		if (!$source->load($source_id))
			return $this->cancel();
		
		$dest = new SupportTicket();
		if (!$dest->load($dest_id))
			return $this->cancel();

		$source->addRelated($dest->id);
		
		$session = JFactory::getSession();
		$session->clear('merge');
		$session->clear('merge_ticket_id');
		
?>
<script>
window.location = '<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $source_id, false); ?>';
</script>
<?php
		exit;
	}
	
	function removerelated()
	{
		$source_id = FSS_Input::getInt('source_id');
		$dest_id = FSS_Input::getInt('dest_id');
		
		if ($source_id < 1)
			return $this->cancel();
		
		if ($dest_id < 1)
			return $this->cancel();
		
		$source = new SupportTicket();
		if (!$source->load($source_id))
			return $this->cancel();
		
		$dest = new SupportTicket();
		if (!$dest->load($dest_id))
			return $this->cancel();

		$source->removeRelated($dest->id);
?>
<script>
window.location = '<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $source_id, false); ?>';
</script>
<?php
		exit;
	}
	
	
	static function Insert($table, $data)
	{		
		$db	= JFactory::getDBO();		
		
		$flist = array();
		$vlist = array();
		
		foreach ($data as $field => $value)
		{
			$flist[] = "`".$field."`";
			$vlist[] = "'".$db->escape($value)."'";
		}
		
		$qry = "INSERT INTO $table (" . implode(" ,", $flist) . ") VALUES (" . implode(" ,", $vlist) . ")";
		
		$db->setQuery($qry);
		//echo $qry . "<br>";
		$db->Query();
		
		return $db->insertid();
	}	
}