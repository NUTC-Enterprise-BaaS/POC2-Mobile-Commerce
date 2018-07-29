<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'task.php');

class Task_Navigate extends Task_Helper
{
	function next()
	{
		$ticket_id = FSS_Input::getInt('ticketid');
		$this->navigateTicket($ticket_id,"next");
	}
	
	function prev()
	{
		$ticket_id = FSS_Input::getInt('ticketid');
		$this->navigateTicket($ticket_id, "prev");
	}
	
	function navigateTicket($ticket_id, $dir)
	{
		$session = JFactory::getSession();
		$data = $session->get("last_admin_tickets");

		//print_p($data);

		$app = JFactory::getApplication();
		
		//print_p($data);
		
		if (!is_array($data) || count($data) < 1)
			return $this->navigateFail();
		
		$found = -1;
		
		foreach ($data as $offset => $ticket)	
		{
			if ($ticket == $ticket_id)
				$found = $offset;
		}
		
		//echo "Found $found<br />";
		
		if ($found == -1)
			return $this->navigateFail();
		
		if ($dir == "prev")
			$found--;
		else
			$found++;
		
		//echo "Target $found<br />";

		if (!array_key_exists($found, $data))
			return $this->navigateFail();
	
		//echo "Target : " . $data[$found] . "<br/>";
		//exit;
	
		//echo "Redirect to " . FSSRoute::_("index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=" . $data[$found]) . "<br>";
		//exit;
	
		return $app->redirect(FSSRoute::_("index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=" . $data[$found], false));	
	}
		
	function navigateFail()
	{
		//echo "FAIL!";
		//exit;
		
		$session = JFactory::getSession();

		$last_url = $session->get("last_admin_list");
		$posted = $session->get("last_admin_post");
		
		if (stripos($last_url, "refresh=1") !== false) $last_url = "";
		
		if ($last_url == "")
		{
			$last_url = FSSRoute::_("index.php?option=com_fss&view=admin_support");
			$posted = array();
		}
?>
<form action='<?php echo $last_url; ?>' method='post' name='frm'>
	<?php
	foreach ($posted as $a => $b) {
		echo "<input type='hidden' name='".htmlentities($a,ENT_QUOTES,"utf-8")."' value='".htmlentities($b,ENT_QUOTES,"utf-8")."'>";
	}
	?>
</form>
<script language="JavaScript">
	document.frm.submit();
</script>
<?php
		exit;
	}
}