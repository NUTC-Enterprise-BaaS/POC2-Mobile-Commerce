<?php
/*------------------------------------------------------------------------
# worktime_custom.php - Ossolution Services Booking
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
class OSappscheduleWorktimecustom{
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
			case "worktimecustom_list":
				OSappscheduleWorktimecustom::worktimecustom_list($option);
			break;
			case "worktimecustom_remove":
				OSappscheduleWorktimecustom::worktimecustom_remove($option,$cid);
			break;
			
			case "worktimecustom_add":
				OSappscheduleWorktimecustom::worktimecustom_modify($option,0);
			break;	
			case "worktimecustom_edit":
				OSappscheduleWorktimecustom::worktimecustom_modify($option,$cid[0]);
			break;
			case "worktimecustom_apply":
				OSappscheduleWorktimecustom::worktimecustom_save($option,0);
			break;
			case "worktimecustom_save":
				OSappscheduleWorktimecustom::worktimecustom_save($option,1);
			break;
		}
	}
	
	/**
	 * agent list
	 *
	 * @param unknown_type $option
	 */
	function worktimecustom_list($option){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();
		$lists = array();
		$condition = '';
		
		// filte sort
		$filter_order 				= $mainframe->getUserStateFromRequest($option.'.worktimecustom.filter_order','filter_order','worktime_date','string');
		$filter_order_Dir 			= $mainframe->getUserStateFromRequest($option.'.worktimecustom.filter_order_Dir','filter_order_Dir','','string');
		$lists['order'] 			= $filter_order;
		$lists['order_Dir'] 		= $filter_order_Dir;
		$order_by 					= " ORDER BY $filter_order $filter_order_Dir";
		
		// Get the pagination request variables
		$limit						= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart					= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
		
		// search 
		$keyword 			 		= $mainframe->getUserStateFromRequest($option.'.worktimecustom.keyword','keyword','','string');
		$lists['keyword']  			= $keyword;
		if($keyword != ""){
			$condition 			   .= " AND (";
			$condition 			   .= " `worktime_date` LIKE '%$keyword%' or";
			$condition 			   .= " `worktime_date_to` LIKE '%$keyword%' or";
			$condition 			   .= " `reason` LIKE '%$keyword%'";
			$condition 			   .= " )";
		}

		// get data	
		$count 						= "SELECT count(id) FROM #__app_sch_working_time_custom WHERE 1=1";
		$count 					   .= $condition;
		$db->setQuery($count);
		$total 						= $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav 					= new JPagination($total,$limitstart,$limit);
		
		$list  						= " SELECT * FROM #__app_sch_working_time_custom "
										."\n WHERE 1=1 ";
		$list 					   .= $condition;
		$list 					   .= $order_by;
		$db->setQuery($list,$pageNav->limitstart,$pageNav->limit);
		$rows 						= $db->loadObjectList();
		
		HTML_OSappscheduleWorktimecustom::worktimecustom_list($option,$rows,$pageNav,$lists);
	}
	
	/**
	 * remove agent
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function worktimecustom_remove($option,$cid){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();
		if(count($cid)>0){
			$cids = implode(",",$cid);
			$db->setQuery("DELETE FROM #__app_sch_working_time_custom WHERE id IN ($cids) ");
			$db->query();
		}
		$mainframe->enqueueMessage(JText::_("OS_ITEMS_HAS_BEEN_DELETED"),'message');
		OSappscheduleWorktimecustom::worktimecustom_list($option);
	}
	
	/**
	 * Service modify
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function worktimecustom_modify($option,$id){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDbo();
		$row = &JTable::getInstance('Worktimecustom','OsAppTable');
		if($id > 0){
			$row->load((int)$id);
			list($row->start_time_hour,$row->start_time_minutes) 	= explode(':',$row->start_time);
			list($row->end_time_hour,$row->end_time_minutes) 		= explode(':',$row->end_time);
		}else{
			$row->worktime_date			= null;
			$row->start_time_hour		= '00';
			$row->start_time_minutes	= '00';
			$row->end_time_hour			= '00';
			$row->end_time_minutes		= '00';
		}
		
		// start time
		$lists['start_time_hour'] 		= HelperDateTime::CreatDropHour('start_time_hour',$row->start_time_hour,'class="input-mini"');
		$lists['start_time_minutes'] 	= HelperDateTime::CreatDropMinuste('start_time_minutes',$row->start_time_minutes,'class="input-mini"');
		
		// end time
		$lists['end_time_hour'] 		= HelperDateTime::CreatDropHour('end_time_hour',$row->end_time_hour,'class="input-mini"');
		$lists['end_time_minutes'] 		= HelperDateTime::CreatDropMinuste('end_time_minutes',$row->end_time_minutes,'class="input-mini"');
		
		$db->setQuery("Select * from #__app_sch_services");
		$services = $db->loadObjectList();
			
		HTML_OSappscheduleWorktimecustom::worktimecustom_modify($option,$row,$lists,$services);
	}
	
	/**
	 * save service
	 *
	 * @param unknown_type $option
	 */
	function worktimecustom_save($option,$save){
		global $mainframe;
		$db = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$post 				= JRequest::get('post',JREQUEST_ALLOWHTML);
		$row 				= &JTable::getInstance('Worktimecustom','OsAppTable');
		$row->bind($post);
		$row->check();
		$row->start_time 	= $post['start_time_hour'].':'.$post['start_time_minutes'].':00';
		$row->end_time 		= $post['end_time_hour'].':'.$post['end_time_minutes'].':00';
		$row->is_day_off	= Jrequest::getVar('is_day_off',0);
		$msg 				= JText::_('OS_ITEM_HAS_BEEN_SAVED'); 
	 	if (!$row->store()){
		 	$msg 			= JText::_('OS_ERROR_SAVING'); ;		 			 	
		}
		$mainframe->enqueueMessage($msg,'message');
		if($save){
			OSappscheduleWorktimecustom::worktimecustom_list($option);
		}else{
			OSappscheduleWorktimecustom::worktimecustom_modify($option,$row->id);
		}
	}
}
?>