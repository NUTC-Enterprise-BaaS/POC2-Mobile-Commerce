<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'guiplugins.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_ticket.php');

class FSS_GUIPlugin_Default_Prefs extends FSS_Plugin_GUI
{
	var $title = "Default Handler Preferences";
	var $description = "Change the default handler preferences. Includes tool to reset the handler preferences for all users.";
}