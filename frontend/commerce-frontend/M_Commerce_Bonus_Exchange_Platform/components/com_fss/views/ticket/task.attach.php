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

class Task_Attach extends Task_Helper
{
	function thumbnail()
	{
		// load in tickets to do	
		$ticketid = FSS_Input::getInt('ticketid'); 
		$fileid = FSS_Input::getInt('fileid'); 
		
		SupportHelper::attachThumbnail($ticketid, $fileid, true);
	}
	
	function view()
	{
		// load in tickets to do	
		$ticketid = FSS_Input::getInt('ticketid'); 
		$fileid = FSS_Input::getInt('fileid'); 
		
		SupportHelper::attachView($ticketid, $fileid, true);
	}
	
	function download()
	{
		// load in tickets to do	
		$ticketid = FSS_Input::getInt('ticketid'); 
		$fileid = FSS_Input::getInt('fileid'); 
		
		SupportHelper::attachDownload($ticketid, $fileid, true);
	}
}