<?php
/*------------------------------------------------------------------------
# venue.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class OSappscheduleVenue{
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
			case "venue_list":
				OSappscheduleVenue::venue_list($option);
			break;
			case "venue_add":
				OSappscheduleVenue::editVenue($option,0);
			break;
			case "venue_edit":
				OSappscheduleVenue::editVenue($option,$cid[0]);
			break;
			case "venue_save":
				OSappscheduleVenue::saveVenue($option,1);
			break;
			case "venue_apply":
				OSappscheduleVenue::saveVenue($option,0);
			break;
			case "venue_cancel":
				$mainframe->redirect("index.php?option=com_osservicesbooking&task=venue_list");
			break;
			case "venue_unpublish":
				OSappscheduleVenue::venue_state($option,$cid,0);
			break;
			case "venue_publish":
				OSappscheduleVenue::venue_state($option,$cid,1);
			break;	
			case "venue_remove":
				OSappscheduleVenue::venue_remove($option,$cid);
			break;
		}
	}
	
	/**
	 * Edit Venue
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function editVenue($option,$id){
		global $mainframe,$configClass,$languages;
		JHTML::_('behavior.tooltip');
		$db = JFactory::getDbo();
		$row = &JTable::getInstance('Venue','OsAppTable');
		if($id > 0){
			$row->load((int)$id);
		}else{
			$row->published = 1;
		}
		
		// creat published
		$lists['published'] = JHtml::_('select.booleanlist','published','class="inputbox"',$row->published);
		
		$db->setQuery("Select country_name as value, country_name as text from #__app_sch_countries order by country_name");
		$countries = $db->loadObjectList();
		$countryArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_COUNTRY'));
		$countryArr   =  array_merge($countryArr,$countries);
		$lists['country'] = JHTML::_('select.genericlist',$countryArr,'country','class="input-small" style="width:150px;" ','value','text',$row->country);
		
		$db->setQuery("Select id as value, service_name as text from #__app_sch_services where published = '1' order by service_name");
		$services = $db->loadObjectList();
		
		$db->setQuery("Select sid from #__app_sch_venue_services where vid = '$row->id'");
		$sids = $db->loadObjectList();
		$serviceArr = array();
		if(count($sids) > 0){
			for($j=0;$j<count($sids);$j++){
				$serviceArr[] = $sids[$j]->sid;
			}
		}
		$lists['service'] = JHTML::_('select.genericlist',$services,'sid[]','multiple style="height:150px;"','value','text',$serviceArr);
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		HTML_OSappscheduleVenue::editVenueHtml($option,$row,$lists,$translatable);
	}
	
	/**
	 * Save venue
	 *
	 * @param unknown_type $option
	 * @param unknown_type $save
	 */
	function saveVenue($option,$save){
		global $mainframe,$configClass,$languages;
		$db = JFactory::getDbo();
		$row = &JTable::getInstance('Venue','OsAppTable');
		$id = Jrequest::getVar('id',0);
		
		$remove_image = JRequest::getVar('remove_image',0);
		if(is_uploaded_file($_FILES['image']['tmp_name'])){
			$photo_name = time()."_".str_replace(" ","_",$_FILES['image']['name']);
			move_uploaded_file($_FILES['image']['tmp_name'],JPATH_ROOT.DS."images".DS."osservicesbooking".DS."venue".DS.$photo_name);
			$row->image = $photo_name;
		}elseif($remove_image == 1){
			$row->image = "";
		}
		
		$post = JRequest::get('post',JREQUEST_ALLOWHTML);
		$row->bind($post);
		if (!$row->store()){
		 	$msg = JText::_('OS_ERROR_SAVING');  			 	
		 	$mainframe->enqueueMessage($msg,'message');
		}
		if($id == 0){
			$id = $db->insertID();
		}
		
		$lat_add  = Jrequest::getVar('lat_add','');
		$long_add = Jrequest::getVar('long_add','');
		if(($lat_add == "") and ($long_add == "")){
			$addressArr = array();
			$addressArr[] = $row->address;
			if($row->city != ""){
				$addressArr[] = $row->city;
			}
			if($row->state != ""){
				$addressArr[] = $row->state;
			}
			if($row->country != ""){
				$addressArr[] = $row->country;
			}
			$address = implode(" ",$addressArr);
			$return = OSBHelper::findAddress($address);
			if($return[2] == "OK"){
				$lat_add = $return[0];
				$long_add = $return[1];
			}
			$db->setQuery("Update #__app_sch_venues set lat_add = '$lat_add',long_add='$long_add' where id = '$id'");
			$db->query();
		}
		
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		if($translatable){
			foreach ($languages as $language){	
				$sef = $language->sef;
				$address_language = JRequest::getVar('address_'.$sef,'');
				if($address_language == ""){
					$address_language = $row->address;
				}
				if($address_language != ""){
					$venue = &JTable::getInstance('Venue','OsAppTable');
					$venue->id = $id;
					$venue->{'address_'.$sef} = $address_language;
					$venue->store();
				}
				
				$city_language = JRequest::getVar('city_'.$sef,'');
				if($city_language == ""){
					$city_language = $row->city;
				}
				if($city_language != ""){
					$venue = &JTable::getInstance('Venue','OsAppTable');
					$venue->id = $id;
					$venue->{'city_'.$sef} = $city_language;
					$venue->store();
				}
				
				$state_language = JRequest::getVar('state_'.$sef,'');
				if($state_language == ""){
					$state_language = $row->state;
				}
				if($state_language != ""){
					$venue = &JTable::getInstance('Venue','OsAppTable');
					$venue->id = $id;
					$venue->{'state_'.$sef} = $state_language;
					$venue->store();
				}
				
			}
		}
		
		
		//update into #__app_sch_venue_services
		$sid = JRequest::getVar('sid');
		$db->setQuery("Delete from #__app_sch_venue_services where vid = '$id'");
		$db->query();
		if(count($sid) > 0){
			for($j=0;$j<count($sid);$j++){
				$service_id = $sid[$j];
				$db->setQuery("Insert into #__app_sch_venue_services (id,vid,sid) values (NULL,'$id','$service_id')");
				$db->query();
			}
		}
	
		
		if($save==1){
			$mainframe->redirect("index.php?option=com_osservicesbooking&task=venue_list",JText::_('OS_ITEM_HAS_BEEN_SAVED'));
		}else{
			$mainframe->redirect("index.php?option=com_osservicesbooking&task=venue_edit&cid[]=".$id,JText::_('OS_ITEM_HAS_BEEN_SAVED'));
		}
	}
	
	/**
	 * Venue list
	 *
	 * @param unknown_type $option
	 */
	function venue_list($option){
		global $mainframe,$configClass;
		$db 				= JFactory::getDbo();
		$limit 				= Jrequest::getVar('limit',20);
		$limitstart 		= Jrequest::getVar('limitstart',0);
		$filter_order 		= Jrequest::getVar('filter_order','a.address');
		$filter_order_Dir 	= Jrequest::getVar('filter_order_Dir','asc');
		
		$keyword 	= Jrequest::getVar('keyword','');
		$query 		= "Select count(id) from #__app_sch_venues where 1=1";
		if($keyword != ""){
			$query .= " and (address like '%$keyword%' or city like '%$keyword%' or state like '%$keyword%' or country like  '%$keyword%' or contact_email like '%$keyword%' or contact_name like '%$keyword%' or contact_phone like '%$keyword%')";
		}
		$db->setQuery($query);
		$total = $db->loadResult();
		jimport('joomla.html.pagination');
		$query 		= "Select a.* from #__app_sch_venues as a where 1=1 ";
		if($keyword != ""){
			$query .= " and (a.address like '%$keyword%' or a.city like '%$keyword%' or a.state like '%$keyword%' or a.country like  '%$keyword%' or a.contact_email like '%$keyword%' or a.contact_name like '%$keyword%' or a.contact_phone like '%$keyword%')";
		}
		$query .= " order by $filter_order $filter_order_Dir";
		$pageNav = new JPagination($total,$limitstart,$limit);
		$db->setQuery($query,$pageNav->limitstart,$pageNav->limit);
		$rows = $db->loadObjectList();
		$lists['keyword'] 			= $keyword;
		$lists['order'] 			= $filter_order;
		$lists['order_Dir'] 		= $filter_order_Dir;
		HTML_OSappscheduleVenue::listVenues($option,$pageNav,$rows,$lists);
	}
	
	/**
	 * publish or unpublish agent
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 * @param unknown_type $state
	 */
	function venue_state($option,$cid,$state){
		global $mainframe;
		$mainframe 	= JFactory::getApplication();
		$db 		= JFactory::getDBO();
		if(count($cid)>0)	{
			$cids 	= implode(",",$cid);
			$db->setQuery("UPDATE #__app_sch_venues SET `published` = '$state' WHERE id IN ($cids)");
			$db->query();
		}
		$mainframe->enqueueMessage(JText::_("OS_ITEMS_STATUS_HAS_BEEN_CHANGED"),'message');
		OSappscheduleVenue::venue_list($option);
	}
	
	/**
	 * remove agent
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function venue_remove($option,$cid){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			$cids = implode(",",$cid);
			$db->setQuery("DELETE FROM #__app_sch_venues WHERE id IN ($cids)");
			$db->query();
			
		}
		$mainframe->enqueueMessage(JText::_("OS_ITEMS_HAS_BEEN_DELETED"),'message');
		OSappscheduleVenue::venue_list($option);
	}
}
?>