<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FsssControllerPrefs extends FsssController
{
	function __construct()
	{
		parent::__construct();
	}

	function reset()
	{
		$db = JFactory::getDBO();
		
		$sql = "UPDATE #__fss_users SET settings = ''";
		$db->setQuery($sql);
		$db->Query();
		$count = $db->getAffectedRows();
		
		$msg = JText::_("Preferences reset for $count users");
		$this->setRedirect( 'index.php?option=com_fss&view=plugins&layout=configure&type=gui&name=default_prefs', $msg );
	}

}
