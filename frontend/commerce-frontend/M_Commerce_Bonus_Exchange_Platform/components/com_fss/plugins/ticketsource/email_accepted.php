<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class Ticket_Source_EMail_Accepted extends Ticket_Source
{
	var $name = "EMail";
	
	var $user_show = true;
	var $user_list = true;
	var $admin_show = true;	
	var $admin_list = true;
}
