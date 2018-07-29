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


class OSappscheduleCoupon{
	function display($option,$task){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$cid = JRequest::getVar( 'cid', array(0));
		JArrayHelper::toInteger($cid, array(0));		
		switch ($task){
			default:
			case "coupon_list":
				OSappscheduleCoupon::coupon_list($option);
			break;
			case "coupon_unpublish":
				OSappscheduleCoupon::coupon_state($option,$cid,0);
			break;
			case "coupon_publish":
				OSappscheduleCoupon::coupon_state($option,$cid,1);
			break;	
			case "coupon_remove":
				OSappscheduleCoupon::coupon_remove($option,$cid);
			break;
			case "coupon_add":
				OSappscheduleCoupon::coupon_modify($option,0);
			break;	
			case "coupon_edit":
				OSappscheduleCoupon::coupon_modify($option,$cid[0]);
			break;
			case "coupon_apply":
				OSappscheduleCoupon::coupon_save($option,0);
			break;
			case "coupon_save":
				OSappscheduleCoupon::coupon_save($option,1);
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
	function coupon_list($option){
		global $mainframe;
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$limit = JRequest::getVar('limit',20);
		$limitstart = JRequest::getVar('limitstart',0);
		$keyword = JRequest::getVar('keyword','');
		$query = "Select count(id) from #__app_sch_coupons where 1=1 ";
		if($keyword != ""){
			$query = " and coupon_name like '%$keyword%'";
		}
		$db->setQuery($query);
		$count = $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($count,$limitstart,$limit);
		$query = "Select * from #__app_sch_coupons where 1=1 ";
		if($keyword != ""){
			$query = " and coupon_name like '%$keyword%'";
		}
		$db->setQuery($query, $pageNav->limitstart,$pageNav->limit);
		$rows = $db->loadObjectList();
		HTML_OsAppscheduleCoupon::listCoupons($option,$rows,$pageNav,$keyword);
	}
	
	/**
	 * Add/edit coupon
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function coupon_modify($option,$id){
		global $mainframe;
		JHTML::_('behavior.tooltip');
		$db = JFactory::getDbo();
		$row = &JTable::getInstance('Coupon','OsAppTable');
		if($id > 0){
			$row->load((int)$id);
		}else{
			$row->published = 1;
		}
		// creat published
		$lists['published'] = JHtml::_('select.booleanlist','published','class="inputbox"',$row->published);
		
		$discountType = array();
		$discountType[] = JHTML::_('select.option','0',JText::_('OS_PERCENT'));
		$discountType[] = JHTML::_('select.option','1',JText::_('OS_FIXED'));
		$lists['discount_type'] = JHTML::_('select.genericlist',$discountType,'discount_type','class="input-small"','value','text',$row->discount_type);
		
		HTML_OsAppscheduleCoupon::editCoupon($option,$row,$lists);
	}
	
	/**
	 * Coupon saving
	 *
	 * @param unknown_type $option
	 * @param unknown_type $save
	 */
	function coupon_save($option,$save){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$post = JRequest::get('post',JREQUEST_ALLOWHTML);
		$row = &JTable::getInstance('Coupon','OsAppTable');
		$row->bind($post);
		$msg = JText::_('OS_ITEM_HAS_BEEN_SAVED'); 
	 	if (!$row->store()){
		 	$msg = JText::_('OS_ERROR_SAVING');	 			 	
		}
		$id = JRequest::getInt('id',0);
		if($id == 0){
			$id = $db->insertid();
		}
		if($save == 1){
			$mainframe->redirect("index.php?option=com_osservicesbooking&task=coupon_list",$msg);
		}else{
			$mainframe->redirect("index.php?option=com_osservicesbooking&task=coupon_edit&cid[]=$row->id",$msg);
		}
	}
	
	/**
	 * publish or unpublish agent
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 * @param unknown_type $state
	 */
	function coupon_state($option,$cid,$state){
		global $mainframe;
		$db 		= JFactory::getDBO();
		if(count($cid)>0)	{
			$cids 	= implode(",",$cid);
			$db->setQuery("UPDATE #__app_sch_coupons SET `published` = '$state' WHERE id IN ($cids)");
			$db->query();
		}
		$mainframe->enqueueMessage(JText::_("OS_ITEMS_STATUS_HAS_BEEN_CHANGED"),'message');
		OsAppscheduleCoupon::coupon_list($option);
	}
	
	/**
	 * remove agent
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function coupon_remove($option,$cid){
		global $mainframe;
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			$cids = implode(",",$cid);
			$db->setQuery("DELETE FROM #__app_sch_coupons WHERE id IN ($cids)");
			$db->query();
			
		}
		$mainframe->enqueueMessage(JText::_("OS_ITEMS_HAS_BEEN_DELETED"),'message');
		OsAppscheduleCoupon::coupon_list($option);
	}
}
?>