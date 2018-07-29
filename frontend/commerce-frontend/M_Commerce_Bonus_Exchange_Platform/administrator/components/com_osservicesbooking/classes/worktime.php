<?php
/*------------------------------------------------------------------------
# worktime.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;

/**
 * Enter description here...
 *
 */
class OSappscheduleWorktime{
	/**
	 * Default function
	 *
	 * @param unknown_type $option
	 */
	function display($option,$task){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$cid = JRequest::getVar( 'cid', array(0));
		JArrayHelper::toInteger($cid, array(0));		
		switch ($task){
			default:
			case "worktime_list":
				OSappscheduleWorktime::worktime_list($option);
			break;
			case "worktime_save":
				OSappscheduleWorktime::worktime_save($option,$cid);
			break;
		}
	}
	
	/**
	 * agent list
	 *
	 * @param unknown_type $option
	 */
	function worktime_list($option){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();
		$list  						= " SELECT * FROM #__app_sch_working_time ORDER BY id ASC";
		$db->setQuery($list);
		$rows 						= $db->loadObjectList();
		HTML_OSappscheduleWorktime::worktime_list($option,$rows);
	}
	
	/**
	 * save service
	 *
	 * @param unknown_type $option
	 */
	function worktime_save($option,$cid){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$post 				= JRequest::get('post',JREQUEST_ALLOWHTML);
		$row 				= &JTable::getInstance('Worktime','OsAppTable');
		$msg 				= JText::_('OS_ITEM_HAS_BEEN_SAVED'); 
		if(count($cid)>0)	
		foreach ($cid as $id) {
			$row->load((int)$id);
			if (isset($post['is_day_off_'.$row->id])){
				$row->is_day_off = 1;
				$row->start_time='00:00:00';
				$row->end_time='00:00:00';
			}
			else {
				$row->is_day_off = 0;
				$row->start_time 	= $post['start_time_hour_'.$row->id].':'.$post['start_time_minutes_'.$row->id].':00';
				$row->end_time 		= $post['end_time_hour_'.$row->id].':'.$post['end_time_minutes_'.$row->id].':00';
			}
			
			if (!$row->store()){
			 	$msg 			= JText::_('OS_ERROR_SAVING'); ;		 			 	
			}
		}
	 	
		$mainframe->enqueueMessage($msg,'message');
		OSappscheduleWorktime::worktime_list($option);
	}
}
?>