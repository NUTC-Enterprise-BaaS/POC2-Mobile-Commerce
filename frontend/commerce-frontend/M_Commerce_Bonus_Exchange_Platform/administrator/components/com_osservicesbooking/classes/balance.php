<?php
/*------------------------------------------------------------------------
# coupon.php - Ossolution emailss Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;


class OSappscheduleBalance{
	function display($option,$task){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$cid = JRequest::getVar( 'cid', array(0));
		JArrayHelper::toInteger($cid, array(0));		
		switch ($task){
			default:
			case "balance_list":
				OSappscheduleBalance::balance_list($option);
			break;
			case "balance_remove":
				OSappscheduleBalance::balance_remove($option,$cid);
			break;
			case "balance_add":
				OSappscheduleBalance::balance_modify($option,0);
			break;	
			case "balance_edit":
				OSappscheduleBalance::balance_modify($option,$cid[0]);
			break;
			case "balance_apply":
				OSappscheduleBalance::balance_save(0);
			break;
			case "balance_save":
				OSappscheduleBalance::balance_save(1);
			break;
			case "goto_index":
				$mainframe = JFactory::getApplication();
				$mainframe->redirect("index.php");
			break;
		}
	}
	
	/**
	 * List coupons
	 *
	 * @param unknown_type $option
	 */
	function balance_list($option){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$limit = JRequest::getVar('limit',20);
		$limitstart = JRequest::getVar('limitstart',0);

        $filter_order 				= $mainframe->getUserStateFromRequest($option.'.balance.filter_order','filter_order','created_date','string');
        $filter_order_Dir 			= $mainframe->getUserStateFromRequest($option.'.balance.filter_order_Dir','filter_order_Dir','desc','string');
        $lists['order'] 			= $filter_order;
        $lists['order_Dir'] 		= $filter_order_Dir;

		$query = "Select count(id) from #__app_sch_user_balance where 1=1 ";
		$db->setQuery($query);
		$count = $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($count,$limitstart,$limit);
		$query = "Select * from #__app_sch_user_balance where 1=1 order by $filter_order $filter_order_Dir";
		$db->setQuery($query, $pageNav->limitstart,$pageNav->limit);
		$rows = $db->loadObjectList();
		HTML_OsAppscheduleBalance::listBalances($option,$rows,$pageNav,$lists);
	}
	
	/**
	 * Add/edit coupon
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function balance_modify($option,$id){
		global $mainframe;
		JHTML::_('behavior.tooltip');
		$db = JFactory::getDbo();
		$row = &JTable::getInstance('UserBalance','OsAppTable');
		if($id > 0){
			$row->load((int)$id);
		}else{
			$row->published = 1;
		}
		HTML_OsAppscheduleBalance::editBalance($row);
	}
	
	/**
	 * Balance saving
	 *
	 * @param unknown_type $option
	 * @param unknown_type $save
	 */
	function balance_save($save){
		global $mainframe;
        $db = JFactory::getDbo();
        $id = JRequest::getInt('id',0);
		$db = JFactory::getDbo();
		$post = JRequest::get('post',JREQUEST_ALLOWHTML);
		$row = &JTable::getInstance('UserBalance','OsAppTable');
        $user_id = JRequest::getInt('user_id',0);
        if(($user_id > 0) and ($id == 0)){
            $db->setQuery("Select count(id) from #__app_sch_user_balance where user_id = '$user_id'");
            $count = $db->loadResult();
            if($count > 0){
                $mainframe->redirect("index.php?option=com_osservicesbooking&task=balance_list",JText::_('OS_USER_IS_EXISTING_IN_BALANCE_LIST'));
            }
        }
		$row->bind($post);
        $created_date = date("Y-m-d",time());
        $row->created_date = $created_date;
		$msg = JText::_('OS_ITEM_HAS_BEEN_SAVED'); 
	 	if (!$row->store()){
		 	$msg = JText::_('OS_ERROR_SAVING');	 			 	
		}
		$id = JRequest::getInt('id',0);
		if($id == 0){
			$id = $db->insertid();
		}
		if($save == 1){
			$mainframe->redirect("index.php?option=com_osservicesbooking&task=balance_list",$msg);
		}else{
			$mainframe->redirect("index.php?option=com_osservicesbooking&task=balance_edit&cid[]=$row->id",$msg);
		}
	}
	
	/**
	 * remove user balance
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function balance_remove($option,$cid){
		global $mainframe;
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			$cids = implode(",",$cid);
			$db->setQuery("DELETE FROM #__app_sch_user_balance WHERE id IN ($cids)");
			$db->query();
			
		}
		$mainframe->enqueueMessage(JText::_("OS_ITEMS_HAS_BEEN_DELETED"),'message');
		OSappscheduleBalance::balance_list($option);
	}
}
?>