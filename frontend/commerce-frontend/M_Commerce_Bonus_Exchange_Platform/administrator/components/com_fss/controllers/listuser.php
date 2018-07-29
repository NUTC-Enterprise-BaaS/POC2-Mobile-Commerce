<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_actions.php');

class FsssControllerlistuser extends JControllerLegacy
{
	var $messages = array();

	function __construct()
	{
		parent::__construct();
	}

	function cancellist()
	{
		$link = 'index.php?option=com_fss&view=fsss';
		$this->setRedirect($link, $msg);
	}

	function adduser()
	{
		$cid = JRequest::getVar('cid',  0, '', 'array');
		$groupid = JRequest::getVar('groupid');

		$this->AddMembership($cid,$groupid);

		$link = "index.php?option=com_fss&view=members&groupid=$groupid";
		if (count($this->messages) > 0)
			$link .= "&messages=" . implode("|", $this->messages);
		echo "<script>\n";
		echo "parent.location.href=\"$link\";\n";
		echo "</script>";
		//$this->setRedirect($link, $msg);
	}

	function AddMembership($userids, $groupid)
	{
		$db	= JFactory::getDBO();
		foreach ($userids as $userid)
		{
			$result = SupportActions::ActionResult("groupAdd", array('group_id' => $groupid, 'user_id' => $userid), true);	
			if ($result === true)
			{
				$qry = "REPLACE INTO #__fss_ticket_group_members (group_id, user_id) VALUES ('" . FSSJ3Helper::getEscaped($db, $groupid) . "', '" . FSSJ3Helper::getEscaped($db, $userid)."')";
				$db->setQuery($qry);
				$db->query($qry);
			} else {
				$this->messages[] = $result;
			}
		}
	}
}


