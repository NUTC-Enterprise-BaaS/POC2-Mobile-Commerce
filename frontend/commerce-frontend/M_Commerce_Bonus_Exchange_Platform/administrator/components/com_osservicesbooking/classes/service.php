<?php
/*------------------------------------------------------------------------
# service.php - Ossolution Services Booking
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
class OSappscheduleService{
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
			case "service_list":
				OSappscheduleService::service_list($option);
			break;
			case "service_unpublish":
				OSappscheduleService::service_state($option,$cid,0);
			break;
			case "service_publish":
				OSappscheduleService::service_state($option,$cid,1);
			break;	
			case "service_remove":
				OSappscheduleService::service_remove($option,$cid);
			break;
			case "service_orderup":
				OSappscheduleService::service_order($option,$cid[0],-1);
			break;
			case "service_orderdown":
				OSappscheduleService::service_order($option,$cid[0],1);
			break;
			case "service_saveorder":
				OSappscheduleService::service_saveorder($option,$cid);
			break;
			case "service_add":
				OSappscheduleService::service_modify($option,0);
			break;	
			case "service_edit":
				OSappscheduleService::service_modify($option,$cid[0]);
			break;
			case "service_apply":
				OSappscheduleService::service_save($option,0);
			break;
			case "service_save":
				OSappscheduleService::service_save($option,1);
			break;
			case "install_list":
				OSappscheduleService::confirmInstall($option);
			break;
			case "service_installdata":
				OSappscheduleService::installSampleData($option);
			break;
			case "goto_index":
				$mainframe = JFactory::getApplication();
				$mainframe->redirect("index.php");
			break;
			case "service_gotolist":
				$mainframe->redirect("index.php?option=com_osservicesbooking&task=service_list");
			break;
			case "service_manageavailability":
				OSappscheduleService::manageAvailability($option);
			break;
			case "service_addunvailabletime":
				OSappscheduleService::addUnavailableTime($option);
			break;
			case "service_removeunvailabletime":
				OSappscheduleService::removeUnavailableTime($option);
			break;
			case "service_managetimeslots":
				OSappscheduleService::manageTimeSlots();
			break;
			case "service_timeslotadd":
				OSappscheduleService::editTimeSlot(0);
			break;
			case "service_timeslotedit":
				OSappscheduleService::editTimeSlot($cid[0]);
			break;
			case "service_timeslotsave":
				OSappscheduleService::saveTimeSlot(1);
			break;
			case "service_timeslotapply":
				OSappscheduleService::saveTimeSlot(0);
			break;
			case "service_timeslotsavenew":
				OSappscheduleService::saveTimeSlot(2);
			break;
			case "service_removetimeslots":
				OSappscheduleService::removeTimeSlots($cid);
			break;
			case "service_gotolisttimeslot":
				$mainframe->redirect("index.php?option=com_osservicesbooking&task=service_managetimeslots&sid=".JRequest::getInt('sid',0));
			break;
			case "service_addcustomprice":
				OSappscheduleService::addcustomprice();
			break;
			case "service_removecustomprice":
				OSappscheduleService::removecustomprice();
			break;
			case "service_duplicate":
				OSappscheduleService::duplicateServices($cid[0]);
			break;
		}
	}
	
	/**
	 * Confirm install sample data
	 *
	 * @param unknown_type $option
	 */
	function confirmInstall($option){
		global $mainframe;
		HTML_OSappscheduleService::confirmInstallSampleDataForm($option);
	}
	
	/**
	 * Install sample data
	 *
	 * @param unknown_type $option
	 */
	function installSampleData($option){
		global $mainframe;
		jimport('joomla.filesystem.file');
		$db = JFactory::getDbo();
		$db->setQuery("DELETE FROM #__app_sch_employee");
		$db->query();
		$db->setQuery("DELETE FROM #__app_sch_employee_service");
		$db->query();
		$db->setQuery("DELETE FROM #__app_sch_field_data");
		$db->query();
		$db->setQuery("DELETE FROM #__app_sch_fields");
		$db->query();
		$db->setQuery("DELETE FROM #__app_sch_service_fields");
		$db->query();
		$db->setQuery("DELETE FROM #__app_sch_services");
		$db->query();
		$sampleSql = JPATH_COMPONENT_ADMINISTRATOR.DS.'sql'.DS.'sample.osservicesbooking.sql' ;
		$sql = JFile::read($sampleSql) ;
		$queries = $db->splitSql($sql);
		if (count($queries)) {
			foreach ($queries as $query) {
			$query = trim($query);
			if ($query != '' && $query{0} != '#') {
					$db->setQuery($query);
					$db->query();						
				}	
			}
		}
		$mainframe->redirect("index.php?option=com_osservicesbooking","Sample data have been installed succesfully");
	}
	
	/**
	 * agent list
	 *
	 * @param unknown_type $option
	 */
	function service_list($option){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();
		$lists = array();
		$condition = '';
		
		// filte sort
		$filter_order 				= $mainframe->getUserStateFromRequest($option.'.service.filter_order','filter_order','ordering','string');
		$filter_order_Dir 			= $mainframe->getUserStateFromRequest($option.'.service.filter_order_Dir','filter_order_Dir','','string');
		$lists['order'] 			= $filter_order;
		$lists['order_Dir'] 		= $filter_order_Dir;
		$order_by 					= " ORDER BY $filter_order $filter_order_Dir";
		
		// Get the pagination request variables
		$limit						= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart					= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
		
		// search 
		$keyword 			 		= $mainframe->getUserStateFromRequest($option.'.service.keyword','keyword','','string');
		$lists['keyword']  			= $keyword;
		if($keyword != ""){
			$condition 			   .= " AND (";
			$condition 			   .= " service_name LIKE '%$keyword%'";
			$condition 			   .= " OR service_description LIKE '%$keyword%'";
			$condition 			   .= " )";
		}
		// filter state
		$filter_state 				= $mainframe->getUserStateFromRequest($option.'.service.filter_state','filter_state','','string');				
		$lists['filter_state'] 		= JHtml::_('grid.state',$filter_state);
		$condition 				   .= ($filter_state == 'P')? " AND `published` = 1":(($filter_state == 'U')? " AND `published` = 0":"");

		// get data	
		$count 						= "SELECT count(id) FROM #__app_sch_services WHERE 1=1";
		$count 					   .= $condition;
		$db->setQuery($count);
		$total 						= $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav 					= new JPagination($total,$limitstart,$limit);
		
		$list  						= "SELECT * FROM #__app_sch_services "
										."\n WHERE 1=1 ";
		$list 					   .= $condition;
		$list 					   .= $order_by;
		$db->setQuery($list,$pageNav->limitstart,$pageNav->limit);
		$rows 						= $db->loadObjectList();
		
		
		HTML_OSappscheduleService::service_list($option,$rows,$pageNav,$lists);
	}
	
	/**
	 * publish or unpublish agent
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 * @param unknown_type $state
	 */
	function service_state($option,$cid,$state){
		global $mainframe;
		$mainframe 	= JFactory::getApplication();
		$db 		= JFactory::getDBO();
		if(count($cid)>0)	{
			$cids 	= implode(",",$cid);
			$db->setQuery("UPDATE #__app_sch_services SET `published` = '$state' WHERE id IN ($cids)");
			$db->query();
		}
		$mainframe->enqueueMessage(JText::_("OS_ITEMS_STATUS_HAS_BEEN_CHANGED"),'message');
		OSappscheduleService::service_list($option);
	}
	
	/**
	 * remove agent
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function service_remove($option,$cid){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			$cids = implode(",",$cid);
			$db->setQuery("DELETE FROM #__app_sch_services WHERE id IN ($cids)");
			$db->query();
			
		}
		$mainframe->enqueueMessage(JText::_("OS_ITEMS_HAS_BEEN_DELETED"),'message');
		OSappscheduleService::service_list($option);
	}
	
	/**
	 * change order price group
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 * @param unknown_type $direction
	 */
	function service_order($option,$id,$direction){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$row = &JTable::getInstance('Service','OsAppTable');
		$row->load($id);
		$row->move( $direction);
		$row->reorder();
		$mainframe->enqueueMessage(JText::_("OS_NEW_ORDERING_SAVED"),'message');
		OSappscheduleService::service_list($option);
	}
	
	/**
	 * save new order
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function service_saveorder($option,$cid){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$msg = JText::_("OS_NEW_ORDERING_SAVED");
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($order);
		$row = &JTable::getInstance('Service','OsAppTable');
		
		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );
			if ($row->ordering != $order[$i]){
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$msg = JText::_("OS_ERROR_SAVING_ORDERING");
					break;
				}
			}
		}
		// execute updateOrder
		$row->reorder();
		$mainframe->enqueueMessage($msg,'message');
		OSappscheduleService::service_list($option);
	}
	
	
	/**
	 * Service modify
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function service_modify($option,$id){
		global $mainframe,$languages;
		JHTML::_('behavior.tooltip');
		$db = JFactory::getDbo();
		$row = &JTable::getInstance('Service','OsAppTable');
		if($id > 0){
			$row->load((int)$id);
		}else{
			$row->published = 1;
			$row->service_before=0;
			$row->service_after=0;
			$row->service_total=0;
		}
		
		// creat published
		$lists['published'] = JHtml::_('select.booleanlist','published','class="inputbox"',$row->published);
			
		// build the html select list for ordering
		$query = " SELECT ordering AS value, service_name AS text "
				.' FROM #__app_sch_services '
				." WHERE `published` = '1'"
				." ORDER BY ordering";
		//$lists['ordering'] = JHTML::_('list.specificordering',  $row, $row->id, $query );
		$lists['ordering'] = JHTML::_('list.ordering', 'ordering', $query ,'',$row->ordering);
		
		if($id > 0){
			$db->setQuery("Select * from #__app_sch_fields where id in (Select field_id from #__app_sch_service_fields where service_id = '$id')");
			$fields = $db->loadObjectList();
			$lists['fields'] = $fields;
		}
		
		$timeArr[] = JHTML::_('select.option','0',JText::_('OS_NORMALLY_TIME_SLOT'));
		$timeArr[] = JHTML::_('select.option','1',JText::_('OS_CUSTOM_TIME_SLOT'));
		$lists['time_slot'] = JHTML::_('select.genericlist',$timeArr,'service_time_type','class="input-large" onChange="javascript:showDiv();"','value','text',$row->service_time_type);
		
		$hourArr =  array();
		$hourArr[] = JHTML::_('select.option','','');
		for($i=0;$i<24;$i++){
			if($i<10){
				$value = "0".$i;
			}else{
				$value = $i;
			}
			$hourArr[] = JHTML::_('select.option',$i,$value);
		}
		$lists['hours'] = $hourArr;
		$minArr = array();
		$minArr[] = JHTML::_('select.option','','');
		for($i=0;$i<60;$i=$i+5){
			if($i<10){
				$value = "0".$i;
			}else{
				$value = $i;
			}
			$minArr[] = JHTML::_('select.option',$i,$value);
		}
		$lists['mins'] = $minArr;
		
		$db->setQuery("Select * from #__app_sch_custom_time_slots where sid = '$row->id'");
		$lists['custom_time'] = $db->loadObjectList();
		
		$db->setQuery("Select id as value, category_name as text from #__app_sch_categories where published = '1' order by category_name");
		$categories     = $db->loadObjectList();
		$categoryArr[]  = JHTML::_('select.option','',JText::_('OS_SELECT_CATEGORY'));
		$categoryArr    = array_merge($categoryArr,$categories);
		$lists['category'] = JHTML::_('select.genericlist',$categoryArr,'category_id','class="input-large"','value','text',$row->category_id);
		
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option','0',JText::_('OS_INHERIT_FROM_CONFIGURATION'));
		$optionArr[] = JHTML::_('select.option','1',JText::_('OS_IS_SERVICE_TIME_LENGTH'));
		$format_steps = explode('|','5|10|15|20|25|30|35|40|45|50|55|60|90|120');
		foreach ($format_steps as $format_step) {
			$optionArr[] = JHTML::_('select.option', $format_step, $format_step." ".JText::_('OS_MINUTES'));
		}
		$lists['step_in_minutes'] = JHTML::_('select.genericlist',$optionArr,'step_in_minutes','class="input-large"','value','text',$row->step_in_minutes);
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		
		$accesslevel = array();
		$accesslevel[] = JHTML::_('select.option','0',JText::_('OS_PUBLIC'));
		$accesslevel[] = JHTML::_('select.option','1',JText::_('OS_REGISTER'));
		$accesslevel[] = JHTML::_('select.option','2',JText::_('OS_SPECIAL'));
		$lists['access'] = JHTML::_('select.genericlist',$accesslevel,'access','class="input-medium"','value','text',$row->access);
		
		$acyLists = null;
		if(file_exists(JPATH_ADMINISTRATOR . '/components/com_acymailing/acymailing.php') && JComponentHelper::isEnabled('com_acymailing', true)){
			if(include_once(rtrim(JPATH_ADMINISTRATOR,DS).DS.'components'.DS.'com_acymailing'.DS.'helpers'.DS.'helper.php')){
				$listClass = acymailing_get('class.list');
				$acyLists = $listClass->getLists();	
				$lists['acyLists'] = $acyLists;
			 }
		}
		
		$db->setQuery("Select * from #__app_sch_service_custom_prices where sid = '$row->id' order by cstart");
		$customs = $db->loadObjectList();
		
		$optionArr = array();
		$optionArr[] = JHtml::_('select.option',0,JText::_('OS_FIXED_AMOUNT_DISCOUNTED'));
		$optionArr[] = JHtml::_('select.option',1,JText::_('OS_PERCENTAGE_DISCOUNT'));
		$lists['early_bird_type'] = JHtml::_('select.genericlist',$optionArr,'early_bird_type','class="input-large"','value','text',$row->early_bird_type);
		
		$optionArr = array();
		$optionArr[] = JHtml::_('select.option',0,JText::_('OS_FIXED_AMOUNT_DISCOUNTED'));
		$optionArr[] = JHtml::_('select.option',1,JText::_('OS_PERCENTAGE_DISCOUNT'));
		$lists['discount_type'] = JHtml::_('select.genericlist',$optionArr,'discount_type','class="input-large"','value','text',$row->discount_type);
		
		HTML_OSappscheduleService::service_modify($option,$row,$lists,$customs,$translatable);
	}
	
	/**
	 * save service
	 *
	 * @param unknown_type $option
	 */
	function service_save($option,$save){
		global $mainframe,$languages;
		$db = JFactory::getDbo();
		
		$post = JRequest::get('post',JREQUEST_ALLOWHTML);
		$row = &JTable::getInstance('Service','OsAppTable');
		$row->bind($post);
		
		$repeat_day = Jrequest::getVar('repeat_day',0);
		$repeat_week = Jrequest::getVar('repeat_week',0);
		$repeat_month = Jrequest::getVar('repeat_month',0);
		$row->repeat_day = $repeat_day;
		$row->repeat_week = $repeat_week;
		$row->repeat_month = $repeat_month;
		
		$remove_image = JRequest::getVar('remove_image',0);
		if(is_uploaded_file($_FILES['image']['tmp_name'])){
			$photo_name = time()."_".str_replace(" ","_",$_FILES['image']['name']);
			move_uploaded_file($_FILES['image']['tmp_name'],JPATH_ROOT.DS."images".DS."osservicesbooking".DS."services".DS.$photo_name);
			$row->service_photo = $photo_name;
		}elseif($remove_image == 1){
			$row->service_photo = "";
		}
		// if new item, order last in appropriate group
		if (!$row->id) {
			$row->ordering = $row->getNextOrder();
		}
		$row->check();
		$row->service_total = $row->service_length + $row->service_before + $row->service_after;
		$msg = JText::_('OS_ITEM_HAS_BEEN_SAVED'); 
	 	if (!$row->store()){
		 	$msg = JText::_('OS_ERROR_SAVING'); 	 			 	
		}
		$mainframe->enqueueMessage($msg,'message');
		$row->reorder();
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		if($translatable){
			foreach ($languages as $language){	
				$sef = $language->sef;
				$service_language = JRequest::getVar('service_name_'.$sef,'');
				if($service_language == ""){
					$address_language = $row->service_name;
				}
				if($address_language != ""){
					$service = &JTable::getInstance('Service','OsAppTable');
					$service->id = $row->id;
					$service->{'service_name_'.$sef} = $address_language;
                    $service->access = $row->access;
					$service->store();
				}
				
				$service_description_language = $_POST['service_description_'.$sef];
				if($service_description_language == ""){
					$service_description_language = $row->service_description;
				}
				if($service_description_language != ""){
					$service = &JTable::getInstance('Service','OsAppTable');
					$service->id = $row->id;
                    $service->access = $row->access;
					$service->{'service_description_'.$sef} = $service_description_language;
					$service->store();
				}
			}
		}
		
		//update adjustment price
		$db->setQuery("Delete from #__app_sch_service_price_adjustment where sid = '$row->id'");
		$db->query();
		for($i=1;$i<=7;$i++){
			$same = JRequest::getInt('same'.$i,0);
			$price = JRequest::getInt('price'.$i,0);
			if($same == 1){
				$db->setQuery("Insert into #__app_sch_service_price_adjustment (id,sid,date_in_week,same_as_original,price) values (NULL,'$row->id','$i','1','')");
				$db->query();
			}else{
				$db->setQuery("Insert into #__app_sch_service_price_adjustment (id,sid,date_in_week,same_as_original,price) values (NULL,'$row->id','$i','0','$price')");
				$db->query();
			}
		}
		
		if($save){
			OSappscheduleService::service_list($option);
		}else{
			OSappscheduleService::service_modify($option,$row->id);
		}
	}
	
	/**
	 * Manage availability
	 *
	 * @param unknown_type $option
	 */
	function manageAvailability($option){
		global $mainframe,$configClass;
		$id = JRequest::getInt('id',0);
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_services where id = '$id'");
		$service = $db->loadObject();
		$db->setQuery("Select * from #__app_sch_service_availability where sid = '$id' order by avail_date desc");
		$dates = $db->loadObjectList();
		HTML_OSappscheduleService::manageAvailability($option,$service,$dates);
	}
	
	/**
	 * Add unavailable time
	 *
	 * @param unknown_type $option
	 */
	function addUnavailableTime($option){
		global $mainframe,$configClass;
		$id = JRequest::getInt('id',0);
		$db = JFactory::getDbo();
		$avail_date = JRequest::getVar('avail_date','');
		$start_time = JRequest::getVar('start_time','');
		$end_time   = JRequest::getVar('end_time','');
		$db->setQuery("INSERT INTO #__app_sch_service_availability (id,sid,avail_date,start_time,end_time) VALUES (NULL,'$id','$avail_date','$start_time','$end_time')");
		$db->query();
		$mainframe->redirect("index.php?option=com_osservicesbooking&task=service_manageavailability&id=".$id,JText::_('OS_UNAVAILABILITY_TIME_HAS_BEEN_ADDED'));
	}
	
	/**
	 * Remove unvailable time
	 *
	 * @param unknown_type $option
	 */
	function removeUnavailableTime($option){
		global $mainframe,$configClass;
		$id = JRequest::getInt('id',0);
		$sid = JRequest::getInt('sid',0);
		$db = JFactory::getDbo();
		$db->setQuery("Delete from #__app_sch_service_availability where id = '$id'");
		$db->query();
		$mainframe->redirect("index.php?option=com_osservicesbooking&task=service_manageavailability&id=".$sid,JText::_('OS_UNAVAILABILITY_TIME_HAS_BEEN_REMOVED'));
	}
	
	function manageTimeSlots(){
		global $mainframe,$configClass;
		$document = JFactory::getDocument();
		$document->addScript(JURI::root()."components/com_ossservicesbooking/js/ajax.js");
		$id = JRequest::getInt('sid',0);
		$db = JFactory::getDbo();
		$limit = JRequest::getInt('limit',20);
		$limitstart = Jrequest::getInt('limitstart',0);
		$db->setQuery("Select * from #__app_sch_services where id = '$id'");
		$service = $db->loadObject();
		$db->setQuery("Select count(a.id) from #__app_sch_custom_time_slots as a where a.sid = '$id'");
		$total = $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total,$limitstart,$limit);
		$query = "Select a.* from #__app_sch_custom_time_slots as a where a.sid = '$id' order by a.start_hour";
		$db->setQuery($query,$pageNav->limitstart,$pageNav->limit);
		$slots = $db->loadObjectList();
		HTML_OSappscheduleService::manageTimeSlots($service,$slots,$pageNav);
	}
	
	function editTimeSlot($id){
		global $mainframe,$configClass;
		$sid = JRequest::getInt('sid');
		$db = JFactory::getDbo();
		if($id > 0){
			$db->setQuery("Select * from #__app_sch_custom_time_slots where id = '$id'");
			$slot = $db->loadObject();
		}
		$hourArr =  array();
		$hourArr[] = JHTML::_('select.option','','');
		for($i=0;$i<24;$i++){
			if($i<10){
				$value = "0".$i;
			}else{
				$value = $i;
			}
			$hourArr[] = JHTML::_('select.option',$i,$value);
		}
		$lists['hours'] = $hourArr;
		$minArr = array();
		$minArr[] = JHTML::_('select.option','','');
		for($i=0;$i<60;$i=$i+5){
			if($i<10){
				$value = "0".$i;
			}else{
				$value = $i;
			}
			$minArr[] = JHTML::_('select.option',$i,$value);
		}
		$lists['mins'] = $minArr;
		HTML_OSappscheduleService::editTimeSlot($slot,$lists,$sid);
	}
	
	function saveTimeSlot($save){
		global $mainframe;
		$db = JFactory::getDbo();
		$id = JRequest::getInt('id',0);
		$sid = JRequest::getInt('sid',0);
		$start_hour = JRequest::getInt('start_hour',0);
		$start_min  = JRequest::getInt('start_min',0);
		$end_hour	= JRequest::getInt('end_hour',0);
		$end_min	= JRequest::getInt('end_min',0);
		$nslots 	= JRequest::getInt('nslots',0);
		
		if($id == 0){//add new
			$db->setQuery("Insert into #__app_sch_custom_time_slots (id,sid,start_hour,start_min,end_hour,end_min,nslots) values (NULL,'$sid','$start_hour','$start_min','$end_hour','$end_min','$nslots')");
			$db->query();
			$id = $db->insertid();
		}else{
			$db->setQuery("Update #__app_sch_custom_time_slots set start_hour = '$start_hour',start_min = '$start_min',end_hour = '$end_hour',end_min = '$end_min',nslots = '$nslots' where id = '$id'");
			$db->query();
		}
		//update date relation
		$db->setQuery("Delete from #__app_sch_custom_time_slots_relation where time_slot_id = '$id'");
		$db->query();
		$date_in_week = JRequest::getVar('date_in_week',NULL,array());
		if(count($date_in_week) > 0){
			for($i=0;$i<count($date_in_week);$i++){
				$date = $date_in_week[$i];
				$db->setQuery("Insert into #__app_sch_custom_time_slots_relation (id,time_slot_id,date_in_week) values (NULL,'$id','$date')");
				$db->query();
			}
		}
		$msg = JText::_('OS_ITEM_HAS_BEEN_SAVED');
		switch ($save){
			case "0":
				$mainframe->redirect("index.php?option=com_osservicesbooking&task=service_timeslotedit&cid[]=$id&sid=".$sid,$msg);
			break;
			case "1":
				$mainframe->redirect("index.php?option=com_osservicesbooking&task=service_managetimeslots&sid=".$sid,$msg);
			break;
			case "2":
				$mainframe->redirect("index.php?option=com_osservicesbooking&task=service_timeslotadd&sid=".$sid,$msg);
			break;
		}
	}
	
	function removeTimeSlots($cid){
		global $mainframe;
		$db = JFactory::getDbo();
		$sid = JRequest::getInt('sid',0);
		if($cid){
			$cids = implode(",",$cid);
			$db->setQuery("Delete from #__app_sch_custom_time_slots where id in ($cids)");
			$db->query();
			$db->setQuery("Delete from #__app_sch_custom_time_slots_relation where time_slot_id in ($cids)");
			$db->query();
		}
		$msg = JText::_('OS_ITEMS_HAVE_BEEN_REMOVED');
		$mainframe->redirect("index.php?option=com_osservicesbooking&task=service_managetimeslots&sid=".$sid,$msg);
	}
	
	static function addcustomprice(){
		$db = JFactory::getDbo();
		$sid = JRequest::getInt('sid',0);
		$cstart = JRequest::getVar('cstart','');
		$cend = JRequest::getVar('cend','');
		$camount = JRequest::getVar('camount');
		$db->setQuery("Insert into #__app_sch_service_custom_prices (id,sid,cstart,cend,amount) values (NULL,'$sid','$cstart','$cend','$camount')");
		$db->query();
		self::getCustomPrice($sid);
		exit();
	}
	
	static function removecustomprice(){
		$db = JFactory::getDbo();
		$id = JRequest::getInt('id',0);
		$sid = JRequest::getInt('sid',0);
		$db->setQuery("Delete from #__app_sch_service_custom_prices where id = '$id'");
		$db->query();
		self::getCustomPrice($sid);
		exit();
	}
	
	public static function getCustomPrice($sid){
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_service_custom_prices where sid = '$sid' order by cstart");
		$customs = $db->loadObjectList();
		?>
		<table width="80%" style="border:1px solid #CCC;">
			<tr>
				<td width="40%" class="headerajaxtd">
					<?php echo JText::_('OS_DATE_PERIOD')?>
				</td>
				<td width="20%" class="headerajaxtd">
					<?php echo JText::_('OS_PRICE')?> <?php echo $configClass['currency_format'];?>
				</td>
				<td width="20%" class="headerajaxtd">
					<?php echo JText::_('OS_REMOVE')?>
				</td>
			</tr>
			<?php
			for($i=0;$i<count($customs);$i++){
				$rest = $customs[$i];
				?>
				<tr>
					<td width="30%" align="left" style="text-align:center;">
						<?php
						$timestemp = strtotime($rest->cstart);
						$timestemp1 = strtotime($rest->cend);
						echo date("D, jS M Y",  $timestemp);
						echo "&nbsp;-&nbsp;";
						echo date("D, jS M Y",  $timestemp1);
						?>
					</td>
					<td width="30%" align="left" style="text-align:center;">
						<?php
						echo $rest->amount;
						?>
					</td>
					<td width="30%" align="center">
						<a href="javascript:removeCustomPrice(<?php echo $rest->id?>,<?php echo $sid?>,'<?php echo JUri::root();?>')">
							<img src="<?php echo JURI::base()?>templates/hathor/images/menu/icon-16-delete.png">
						</a>
					</td>
				</tr>
				<?php
			}
			?>
		</table>
		<BR /><BR />
		<?php 
	}
	
	/**
	 * Duplicate Service Information
	 *
	 * @param unknown_type $id
	 */
	public static function duplicateServices($id){
		global $languages,$mainframe;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_services where id = '$id'");
		$service = $db->loadObject();
		$row = &JTable::getInstance('Service','OsAppTable');
		$row->id = 0;
		$row->category_id = $service->category_id;
		$row->service_name = JText::_('OS_COPIED')." ".$service->service_name;
		$row->service_price = $service->service_price;
		$row->service_length = $service->service_length;
		$row->service_description = $service->service_description;
		$row->service_photo = $service->service_photo;
		$row->service_time_type = $service->service_time_type;
		$row->early_bird_amount = $service->early_bird_amount;
		$row->early_bird_type = $service->early_bird_type;
		$row->early_bird_days = $service->early_bird_days;
		$row->discount_timeslots = $service->discount_timeslots;
		$row->discount_type = $service->discount_type;
		$row->discount_amount = $service->discount_amount;
		$row->step_in_minutes = $service->step_in_minutes;
		$row->repeat_day = $service->repeat_day;
		$row->repeat_week = $service->repeat_week;
		$row->repeat_month = $service->repeat_month;
		$row->published = 0;
		$row->access = $service->access;
		$row->acymailing_list_id = $service->acymailing_list_id;
		$db->setQuery("Select ordering from #__app_sch_services order by ordering desc limit 1");
		$ordering = $db->loadResult();
		$ordering = $ordering + 1;
		$row->ordering = $ordering;		
		
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		if ($translatable){
			$i = 0;
			foreach ($languages as $language) {						
				$sef = $language->sef;
				$row->{'service_name_'.$sef} = $service->{'service_name_'.$sef};
				$row->{'service_description_'.$sef} = $service->{'service_description_'.$sef};
			}
		}
		$row->store();
		$service_id = $db->insertid();
		
		#__app_sch_service_availability
		$db->setQuery("Select * from #__app_sch_service_availability where sid = '$id'");
		$availabilities = $db->loadObjectList();
		
		if(count($availabilities) > 0){
			foreach ($availabilities as $avail){
				$db->setQuery("Insert into #__app_sch_service_availability (id,sid,avail_date,start_time,end_time) values (NULL,'$service_id','$avail->avail_date','$avail->start_time','$avail->end_time')");
				$db->query();
			}
		}
		
		#__app_sch_service_custom_prices
		$db->setQuery("Select * from #__app_sch_service_custom_prices where sid = '$id'");
		$custom_prices = $db->loadObjectList();
		
		if(count($custom_prices) > 0){
			foreach ($custom_prices as $custom_price){
				$db->setQuery("Insert into #__app_sch_service_custom_prices (id,sid,cstart,cend,amount) values (NULL,'$service_id','$custom_price->cstart','$custom_price->cend','$custom_price->amount')");
				$db->query();
			}
		}
		
		#__app_sch_service_fields
		$db->setQuery("Select * from #__app_sch_service_fields where service_id = '$id'");
		$fields = $db->loadObjectList();
		
		if(count($fields) > 0){
			foreach ($fields as $field){
				$db->setQuery("Insert into #__app_sch_service_fields (id,service_id,field_id) values (NULL,'$service_id','$field->field_id')");
				$db->query();
			}
		}
		
		#__app_sch_service_price_adjustment
		$db->setQuery("Select * from #__app_sch_service_price_adjustment where sid = '$id'");
		$prices = $db->loadObjectList();
		
		if(count($prices) > 0){
			foreach ($prices as $price){
				$db->setQuery("Insert into #__app_sch_service_price_adjustment (id,sid,date_in_week,same_as_original,price) values (NULL,'$service_id','$price->date_in_week','$price->same_as_original','$price->price')");
				$db->query();
			}
		}
		
		#__app_sch_service_time_custom_slots
		$db->setQuery("Select * from #__app_sch_service_time_custom_slots where sid = '$id'");
		$custom_slots = $db->loadObjectList();
		
		if(count($custom_slots) > 0){
			foreach ($custom_slots as $custom_slot){
				$db->setQuery("Insert into #__app_sch_service_time_custom_slots (id,custom_id,sid,service_slots) values (NULL,'$custom_slot->custom_id','$service_id','$custom_slot->service_slots')");
				$db->query();
			}
		}
		
		#__app_sch_service_time_custom_slots
		$db->setQuery("Select * from #__app_sch_service_time_custom_slots where sid = '$id'");
		$custom_slots = $db->loadObjectList();
		
		if(count($custom_slots) > 0){
			foreach ($custom_slots as $custom_slot){
				$db->setQuery("Insert into #__app_sch_service_time_custom_slots (id,custom_id,sid,service_slots) values (NULL,'$custom_slot->custom_id','$service_id','$custom_slot->service_slots')");
				$db->query();
			}
		}
		
		#__app_sch_custom_time_slots
		$db->setQuery("Select * from #__app_sch_custom_time_slots where sid = '$id'");
		$custom_slots = $db->loadObjectList();
		
		if(count($custom_slots) > 0){
			foreach ($custom_slots as $custom_slot){
				$db->setQuery("Insert into #__app_sch_custom_time_slots (id,sid,start_hour,start_min,end_hour,end_min,nslots) values (NULL,'$service_id','$custom_slot->start_hour','$custom_slot->start_min','$custom_slot->end_hour','$custom_slot->end_min','$custom_slot->nslots')");
				$db->query();
			}
		}
		
		$mainframe->redirect("index.php?option=com_osservicesbooking&task=service_list",JText::_('OS_SERVICE_HAS_BEEN_DUPLICATED'));
	}
}
?>