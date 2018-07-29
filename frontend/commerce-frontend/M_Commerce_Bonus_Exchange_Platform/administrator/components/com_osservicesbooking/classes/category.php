<?php
/*------------------------------------------------------------------------
# category.php - Ossolution emailss Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;

class OSappscheduleCategory{
	function display($option,$task){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$cid = JRequest::getVar( 'cid', array(0));
		JArrayHelper::toInteger($cid, array(0));		
		switch ($task){
			default:
			case "category_list":
				OSappscheduleCategory::category_list($option);
			break;
			case "category_unpublish":
				OSappscheduleCategory::category_state($option,$cid,0);
			break;
			case "category_publish":
				OSappscheduleCategory::category_state($option,$cid,1);
			break;	
			case "category_remove":
				OSappscheduleCategory::category_remove($option,$cid);
			break;
			case "category_add":
				OSappscheduleCategory::category_modify($option,0);
			break;	
			case "category_edit":
				OSappscheduleCategory::category_modify($option,$cid[0]);
			break;
			case "category_apply":
				OSappscheduleCategory::category_save($option,0);
			break;
			case "category_save":
				OSappscheduleCategory::category_save($option,1);
			break;
			case "goto_index":
				$mainframe = JFactory::getApplication();
				$mainframe->redirect("index.php");
			break;
		}
	}
	
	/**
	 * Category list
	 *
	 * @param unknown_type $option
	 */
	function category_list($option){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$limit = JRequest::getVar('limit',20);
		$limitstart = JRequest::getVar('limitstart',0);
		$keyword = JRequest::getVar('keyword','');
		$query = "Select count(id) from #__app_sch_categories where 1=1 ";
		if($keyword != ""){
			$query = " and category_name like '%$keyword%'";
		}
		$db->setQuery($query);
		$count = $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($count,$limitstart,$limit);
		$query = "Select * from #__app_sch_categories where 1=1 ";
		if($keyword != ""){
			$query = " and category_name like '%$keyword%'";
		}
		$db->setQuery($query, $pageNav->limitstart,$pageNav->limit);
		$rows = $db->loadObjectList();
		HTML_OsAppscheduleCategory::listCategories($option,$rows,$pageNav,$keyword);
	}
	
	/**
	 * Category modification/add new
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function category_modify($option,$id){
		global $mainframe,$languages;
		JHTML::_('behavior.tooltip');
		$db = JFactory::getDbo();
		$row = &JTable::getInstance('Category','OsAppTable');
		if($id > 0){
			$row->load((int)$id);
		}else{
			$row->published = 1;
		}
		// creat published
		$lists['published'] = JHtml::_('select.booleanlist','published','class="inputbox"',$row->published);
		$lists['show_desc'] = JHtml::_('select.booleanlist','show_desc','class="inputbox"',$row->show_desc);
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		HTML_OSappscheduleCategory::editCategory($option,$row,$lists,$translatable);
	}
	
	/**
	 * Category save
	 *
	 * @param unknown_type $option
	 * @param unknown_type $save
	 */
	function category_save($option,$save){
		global $mainframe,$configClass,$languages;
		$db = JFactory::getDbo();
		
		$post = JRequest::get('post',JREQUEST_ALLOWHTML);
		$row = &JTable::getInstance('Category','OsAppTable');
		
		$remove_image = JRequest::getVar('remove_image',0);
		if(is_uploaded_file($_FILES['image']['tmp_name'])){
			$photo_name = time()."_".str_replace(" ","_",$_FILES['image']['name']);
			move_uploaded_file($_FILES['image']['tmp_name'],JPATH_ROOT.DS."images".DS."osservicesbooking".DS."category".DS.$photo_name);
			$row->category_photo = $photo_name;
		}elseif($remove_image == 1){
			$row->category_photo = "";
		}
		
		$row->bind($post);
		$msg = JText::_('OS_ITEM_HAS_BEEN_SAVED'); 
	 	if (!$row->store()){
		 	$msg = JText::_('OS_ERROR_SAVING'); ;		 			 	
		}
		
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		if($translatable){
			foreach ($languages as $language){	
				$sef = $language->sef;
				$category_name = JRequest::getVar('category_name_'.$sef,'');
				if($category_name == ""){
					$category_name = $row->category_name;
				}
				if($category_name != ""){
					$category = &JTable::getInstance('Category','OsAppTable');
					$category->id = $row->id;
					$category->{'category_name_'.$sef} = $category_name;
					$category->store();
				}
				
				$category_description_language = $_POST['category_description_'.$sef];
				if($category_description_language == ""){
					$category_description_language = $row->category_description;
				}
				if($category_description_language != ""){
					$category = &JTable::getInstance('Category','OsAppTable');
					$category->id = $row->id;
					$category->{'category_description_'.$sef} = $category_description_language;
					$category->store();
				}
			}
		}
		
		if($save == 1){
			$mainframe->redirect("index.php?option=com_osservicesbooking&task=category_list",$msg);
		}else{
			$mainframe->redirect("index.php?option=com_osservicesbooking&task=category_edit&cid[]=$row->id",$msg);
		}
	}
	
	/**
	 * publish or unpublish agent
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 * @param unknown_type $state
	 */
	function category_state($option,$cid,$state){
		global $mainframe;
		$db 		= JFactory::getDBO();
		if(count($cid)>0)	{
			$cids 	= implode(",",$cid);
			$db->setQuery("UPDATE #__app_sch_categories SET `published` = '$state' WHERE id IN ($cids)");
			$db->query();
		}
		$mainframe->enqueueMessage(JText::_("OS_ITEMS_STATUS_HAS_BEEN_CHANGED"),'message');
		OSappscheduleCategory::category_list($option);
	}
	
	/**
	 * remove agent
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function category_remove($option,$cid){
		global $mainframe;
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			$cids = implode(",",$cid);
			$db->setQuery("DELETE FROM #__app_sch_categories WHERE id IN ($cids)");
			$db->query();
			
		}
		$mainframe->enqueueMessage(JText::_("OS_ITEMS_HAS_BEEN_DELETED"),'message');
		OSappscheduleCategory::category_list($option);
	}
}
?>