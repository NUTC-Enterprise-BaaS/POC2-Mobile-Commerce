<?php
/*------------------------------------------------------------------------
# orders.php - Ossolution Services Booking
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
class OSappscheduleOrders{
	/**
	 * Default function
	 *
	 * @param unknown_type $option
	 */
	function display($option,$task){
		global $mainframe;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);	
		$option = JRequest::getVar('option','com_osservicesbooking');
		$mainframe = JFactory::getApplication();
		$cid = JRequest::getVar( 'cid', array(0));
		JArrayHelper::toInteger($cid, array(0));		
		require_once(JPATH_ROOT.DS."components".DS."com_osservicesbooking".DS."classes".DS."default.php");
		require_once(JPATH_ROOT.DS."components".DS."com_osservicesbooking".DS."classes".DS."default.html.php");
		switch ($task){
			default:
			case "orders_list":
				OSappscheduleOrders::orders_list($option);
			break;
			case "orders_save":
				OSappscheduleOrders::orders_status($option,1);
			break;
			case "orders_apply":
				OSappscheduleOrders::orders_status($option,0);
			break;	
			case "orders_remove":
				OSappscheduleOrders::orders_remove($option,$cid);
			break;
			case "orders_detail":
				OSappscheduleOrders::orders_detail($option,$cid[0]);
			break;
			case "orders_export":
				OSappscheduleOrders::exportCsv($option,$cid);
			break;
			case "orders_dowloadInvoice" :
				OSappscheduleOrders::download_invoice ( $cid[0] );
			break;
			case "orders_addservice":
				OSappscheduleOrders::addServices($option);
			break;
			case "orders_saveservice":
				OsAppscheduleOrders::saveService($option);
			break;
			case "orders_removeservice":
				OsAppscheduleOrders::removeService($option);
			break;
			case "orders_sendnotify":
				OsAppscheduleOrders::sendnotifyEmails($cid);
			break;
			case "orders_addnew":
				OSappscheduleOrders::orders_detail($option,0);
			break;
			case "orders_gotoorderdetails":
				$mainframe->redirect("index.php?option=com_osservicesbooking&task=orders_detail&cid[]=".JRequest::getInt('order_id',0));
			break;
			case "orders_exportreport":
				OSappscheduleOrders::exportReport();
			break;
			case "orders_copyfolder":
				OSappscheduleOrders::copyFolder();
			break;
			case "orders_updateNewOrderStatus":
				OSappscheduleOrders::updateNewOrderStatus();
			break;
		}
	}
	
	function copyFolder(){
		jimport('joomla.filesystem.folder');
		if(JFolder::exists(JPATH_ROOT.DS."Zend")){
			if(!JFolder::exists(JPATH_ROOT.DS."administrator".DS."Zend")){	
				JFolder::copy(JPATH_ROOT.DS."Zend",JPATH_ROOT.DS."administrator".DS."Zend");
			}
		}
	}
	
	/**
	 * Adding new services
	 *
	 */
	function addServices(){
		global $mainframe,$configClass;
		$db 		= JFactory::getDbo();
		$order_id 	= JRequest::getInt('order_id',0);
		$sid		= JRequest::getInt('sid',0);
		$vid		= JRequest::getInt('vid',0);
		$eid 		= JRequest::getInt('eid',0);
		$booking_date = JRequest::getVar('booking_date','');
		
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('a.id as value,a.service_name as text');
		$query->from($db->quoteName('#__app_sch_services').' AS a');
		$query->where("a.published = '1'");
		if($vid > 0){
			$query->where("a.id in (Select sid from #__app_sch_venue_services where vid = '$vid')");
		}
		$query->order($db->escape('a.service_name'));
		$db->setQuery($query);
		//echo $db->getQuery();
		$services = $db->loadObjectList();
		$optionArr[] = JHTML::_('select.option','','');
		$optionArr = array_merge($optionArr,$services);
		$lists['services'] = JHTML::_('select.genericlist',$optionArr,'sid','class="input-large" onChange="javascript:document.adminForm.submit();"','value','text',$sid);
		
		$query = "Select a.id as value, concat(a.address,',',a.city,',',a.state) as text from #__app_sch_venues as a inner join #__app_sch_venue_services as b on b.vid = a.id where a.published = '1'";
		if($sid > 0){
			$query .= " and b.sid = '$sid'";
		}
		$query .= " group by a.id order by a.address";
		$db->setQuery($query);
		//echo $db->getQuery();
		$venues = $db->loadObjectList();
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option','','');
		$optionArr = array_merge($optionArr,$venues);
		$lists['venues'] = JHTML::_('select.genericlist',$optionArr,'vid','class="input-large" onChange="javascript:document.adminForm.submit();"','value','text',$vid);
		
		$query = $db->getQuery(true);
		$query->select('id as value, employee_name as text');
		$query->from('#__app_sch_employee');
		$query->where("published = '1'");
		if($sid > 0){
			$query->where("id in (Select employee_id from #__app_sch_employee_service where service_id = '$sid')");
		}
		if($vid > 0){
			$query->where("id in (Select employee_id from #__app_sch_employee_service where vid = '$vid')");
		}
		$query->order('employee_name');
		$db->setQuery($query);
		$employees = $db->loadObjectList();
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option','','');
		$optionArr = array_merge($optionArr,$employees);
		$lists['employees'] = JHTML::_('select.genericlist',$optionArr,'eid','class="input-large" onChange="javascript:document.adminForm.submit();"','value','text',$eid);
		
		if(($sid > 0) and ($eid > 0)){
			//show date
			$show_date = 1;
		}else{
			$show_date = 0;
		}
		
		if(($sid > 0) and ($eid > 0) and ($booking_date != "")){
			if(OSBHelper::checkAvailableDate($sid,$eid,$booking_date)){
				
			}
		}
		HTML_OSappscheduleOrders::addServicesForm($order_id,$lists,$show_date,$sid,$vid,$eid,$booking_date);
	}
	
	/**
	 * Download Invoice
	 * Step 1: Making PPF file 
	 * Step 2: Download the PDF file
	 *
	 * @param unknown_type $id
	 */
	function download_invoice($id) {
		global $configClass;
		require_once JPATH_ROOT . "/components/com_osservicesbooking/tcpdf/tcpdf.php";
		require_once JPATH_ROOT . "/components/com_osservicesbooking/tcpdf/config/lang/eng.php";
		$return = OSBHelper::generateOrderPdf($id);
		while ( @ob_end_clean () );
		ServiceDowloadinvoice::processDownload ( $return[0],$return[1]);
	}
	
	/**
	 * Export csv
	 *
	 */
	function exportCsv($option,$cid){
		global $mainframe,$configClass;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);	
		
		$db = JFactory::getDbo();
		$cids						= implode(",",$cid);
		$csv_separator 				= $configClass['csv_separator'];
		$filter_order 				= JRequest::getVar('filter_order','a.id');
		$filter_order_Dir 			= JRequest::getVar('filter_order_Dir','desc');
		$lists['order'] 			= $filter_order;
		$lists['order_Dir'] 		= $filter_order_Dir;
		$order_by 					= " ORDER BY $filter_order $filter_order_Dir";
		
		$keyword 			 		= $mainframe->getUserStateFromRequest($option.'.orders.keyword','keyword','','string');
		$lists['keyword']  			= $keyword;
		if($keyword != ""){
			$condition 			   .= " AND (";
			$condition 			   .= " a.order_name LIKE '%$keyword%'";
			$condition 			   .= " OR a.order_email LIKE '%$keyword%'";
			$condition 			   .= " OR a.order_phone LIKE '%$keyword%'";
			$condition 			   .= " OR a.order_country LIKE '%$keyword%'";
			$condition 			   .= " OR a.order_city LIKE '%$keyword%'";
			$condition 			   .= " OR a.order_state LIKE '%$keyword%'";
			$condition 			   .= " OR a.order_zip LIKE '%$keyword%'";
			$condition 			   .= " OR a.order_address LIKE '%$keyword%'";
			$condition 			   .= " OR a.order_upfront LIKE '%$keyword%'";
			$condition 			   .= " OR a.order_date LIKE '%$keyword%'";
			$condition 			   .= " )";
		}
			
		// filter state
		$filter_status 				= $mainframe->getUserStateFromRequest($option.'.orders.filter_status','filter_status','','string');
		// filter date
		$filter_date_from			= $mainframe->getUserStateFromRequest($option.'.orders.filter_date_from','filter_date_from',null,'string');
		if ($filter_date_from != '' ){
			$condition 				.= " AND a.order_date >= '$filter_date_from 00:00:00'";
		}
		$filter_date_to				= $mainframe->getUserStateFromRequest($option.'.orders.filter_date_to','filter_date_to',null,'string');
		if ($filter_date_to != '' ){
			$condition 				.= " AND a.order_date <= '$filter_date_to 23:59:59'";
		}
		// filter extra
		$add_query 						= '';
		$filter_service 				= $mainframe->getUserStateFromRequest($option.'.orders.filter_service','filter_service',0,'int');
		$filter_employee 				= $mainframe->getUserStateFromRequest($option.'.orders.filter_employee','filter_employee',0,'int');
		if ($filter_service || $filter_employee){
			$add_query 					= " INNER JOIN #__app_sch_order_items AS b ON a.id = b.order_id ";
			$condition 				   .= $filter_service? " AND b.sid = '$filter_service' ":'';	
			$condition 				   .= $filter_employee? " AND b.eid = '$filter_employee' ":'';
		}
		// filter service
		$options 					= array();
		if ($filter_employee){	
			$query 					= " SELECT a.id AS value, a.service_name AS text"
									 ." FROM #__app_sch_services AS a"						 
									." INNER JOIN #__app_sch_employee_service AS b ON (a.id = b.service_id AND b.employee_id = '$filter_employee')"					//." WHERE  a.published = '1' "						 
									." ORDER BY a.service_name, a.ordering";			
		}else{
				
			$query 					= " SELECT `id` AS value, `service_name` AS text"
									." FROM #__app_sch_services"
										// ." WHERE `published` = '1' "
									." ORDER BY service_name, ordering";
		}
		$db->setQuery($query);
		// filter employee
		if ($filter_service){
			$query 				= " SELECT a.id AS value, a.employee_name AS text"
								." FROM #__app_sch_employee AS a"
							    ." INNER JOIN #__app_sch_employee_service AS b ON (a.id = b.employee_id AND b.service_id = '$filter_service')"
															// ." WHERE a.published = '1' "
							    ." ORDER BY a.employee_name, b.ordering"
							    ;
		}else{
			$query 				= " SELECT `id` AS value, `employee_name` AS text"
								 ." FROM #__app_sch_employee "
								 // ." WHERE `published` = 1 "
								 ." ORDER BY employee_name "
								 ;
		}
			
		$list  						= " SELECT * FROM #__app_sch_orders AS a"
		.$add_query
		."\n WHERE 1=1 and a.id in ($cids)";
		$list 					   .= $condition;
		$list 					   .= $order_by;
		$db->setQuery($list);
		//echo $db->getQuery();
		$rows 						= $db->loadObjectList();
		
		$header = '"ID"'.$csv_separator.'"'.JText::_('OS_NAME').'"'.$csv_separator.'"'.JText::_('OS_EMAIL').'"'.$csv_separator.'"'.JText::_('OS_PHONE').'"'.$csv_separator.'"'.JText::_('OS_COUNTRY').'"'.$csv_separator.'"'.JText::_('OS_STATE').'"'.$csv_separator.'"'.JText::_('OS_CITY').'"'.$csv_separator.'"'.JText::_('OS_ADDRESS').'"'.$csv_separator.'"'.JText::_('OS_ZIP').'"'.$csv_separator.'"'.JText::_('OS_DATE').'"'.$csv_separator.'"'.JText::_('OS_SERVICES').'"'.$csv_separator.'"'.JText::_('OS_ADDITIONAL_INFORMATION').'"';
		if($configClass['disable_payments'] == 1){
			$header .= $csv_separator.'"'.JText::_('OS_PAYMENT').'"'.$csv_separator.'"'.JText::_('OS_TOTAL').'"';
		}
		$header .= $csv_separator.'"'.JText::_('OS_STATUS').'"';
		
		$csv_content .= "\n";
		if(count($rows) > 0){
			for($i=0;$i<count($rows);$i++){
				$row = $rows[$i];
				$id = $row->id;
				if(strlen($id) < 5){
					for($j=strlen($id);$j<=5;$j++){
						$id = "0".$id;
					}
				}
				$csv_content .= '"'.$id.'"'.$csv_separator.'"'.$row->order_name.'"'.$csv_separator.'"'.$row->order_email.'"'.$csv_separator.'"'.$row->order_phone.'"'.$csv_separator.'"'.$row->order_country.'"'.$csv_separator.'"'.$row->order_state.'"'.$csv_separator.'"'.$row->order_city.'"'.$csv_separator.'"'.$row->order_address.'"'.$csv_separator.'"'.$row->order_zip.'"'.$csv_separator.'"'.$row->order_date.'"';
				
				$db->setQuery("Select a.*,b.service_name,b.service_time_type,c.employee_name from #__app_sch_order_items as a inner join #__app_sch_services as b on b.id = a.sid inner join #__app_sch_employee as c on c.id = a.eid where a.order_id = '$row->id' order by b.service_name");
				$items = $db->loadObjectList();
				if(count($items) > 0){
					$item_content = "";
					for($j=0;$j<count($items);$j++){
						$item = $items[$j];
						$pos  = $j+1;
						$item_content .= $pos.". ".JText::_('OS_SERVICE_NAME').": ".$item->service_name."  ".JText::_('OS_EMPLOYEE_NAME').": ".$item->employee_name." ".JText::_('OS_ON')." ".$item->booking_date;
						$item_content .= " ".JText::_('OS_FROM').": ".date($configClass['time_format'],$item->start_time);
						$item_content .= " ".JText::_('OS_TO').": ".date($configClass['time_format'],$item->end_time);
						//Additional information
						$db->setQuery("Select a.* from #__app_sch_venues as a inner join #__app_sch_employee_service as b on b.vid = a.id where b.employee_id = '$item->eid' and b.service_id = '$item->sid'");
						$venue = $db->loadObject();
						if($venue->address != ""){
							$item_content .= "| ".JText::_('OS_VENUE').": ".$venue->address."|";
						}
						if($item->service_time_type == 1){
							$item_content .= "| ".JText::_('OS_NUMBER_SLOT').": ".$item->nslots."|";
						}
						$db->setQuery("Select * from #__app_sch_fields where field_area = '0' and published = '1' order by ordering");
						$fields = $db->loadObjectList();
						if(count($fields) > 0){
							for($i1=0;$i1<count($fields);$i1++){
								$field = $fields[$i1];
								//echo $field->id;
								$db->setQuery("Select count(id) from #__app_sch_order_field_options where order_item_id = '$item->id' and field_id = '$field->id'");
								$count = $db->loadResult();
								if($count > 0){
									if($field->field_type == 1){
										$db->setQuery("Select option_id from #__app_sch_order_field_options where order_item_id = '$item->id' and field_id = '$field->id'");
										//echo $db->getQuery();
										$option_id = $db->loadResult();
										$db->setQuery("Select * from #__app_sch_field_options where id = '$option_id'");
										$optionvalue = $db->loadObject();
										?>
										<?php $item_content .= " ".OSBHelper::getLanguageFieldValueOrder($field,'field_label',$row->order_lang).": ";?>
										<?php
										$field_data = OSBHelper::getLanguageFieldValueOrder($optionvalue,'field_option',$row->order_lang);
										if($optionvalue->additional_price > 0){
											$field_data.= " - ".$optionvalue->additional_price." ".$configClass['currency_format'];
										}
										$item_content .= $field_data ."|";
									}elseif($field->field_type == 2){
										$db->setQuery("Select option_id from #__app_sch_order_field_options where order_item_id = '$item->id' and field_id = '$field->id'");
										$option_ids = $db->loadObjectList();
										$fieldArr = array();
										//$item_content .= " ".OSBHelper::getLanguageFieldValueOrder($field,'field_label',$row->order_lang).": ";
										for($j1=0;$j1<count($option_ids);$j1++){
											$oid = $option_ids[$j1];
											$db->setQuery("Select * from #__app_sch_field_options where id = '$oid->option_id'");
											//echo $db->getQuery();
											$optionvalue = $db->loadObject();
											$field_data = OSBHelper::getLanguageFieldValueOrder($optionvalue,'field_option',$row->order_lang);
											if($optionvalue->additional_price > 0){
												$field_data.= " - ".$optionvalue->additional_price." ".$configClass['currency_format'];
											}
											$fieldArr[] = $field_data;
										}
										?>
										<?php $item_content .= OSBHelper::getLanguageFieldValueOrder($field,'field_label',$row->order_lang);?>:
										<?php
										$item_content .= " ".implode(", ",$fieldArr)."|";
									}
								}
							}
						}
						
						$item_content .= " | ";
					}
				}
				
				$csv_content .= $csv_separator.'"'.$item_content.'"';
				
				/*
				$db->setQuery("Select distinct field_id from #__app_sch_order_options where order_id = '$row->id'");
				$fieldids = $db->loadObjectList();
				if(count($fieldids) > 0){
					$field_content = "";
					for($j=0;$j<count($fieldids);$j++){
						$fid = $fieldids[$j]->field_id;
						$db->setQuery("Select field_label from #__app_sch_fields where id = '$fid'");
						$field_label = $db->loadResult();
						$field_content .= $field_label;
						$db->setQuery("Select a.field_option from #__app_sch_field_options as a inner join #__app_sch_order_options as b on b.option_id = a.id where b.field_id = '$fid' and b.order_id = '$row->id'");
						$field_options 	= $db->loadResultArray();
						$field_content .= ": ".implode(" + ",$field_options);
						$field_content .= " | ";
					}
				}
				*/
				$field_content = "";
				$db->setQuery("Select * from #__app_sch_fields where field_area = '1' and published = '1'");
				$fields = $db->loadObjectList();
				//print_r($fields);
				if(count($fields) > 0){
					$field_content_array = array();
					for($i=0;$i<count($fields);$i++){
						$field = $fields[$i];
						$field_value = OsAppscheduleDefault::orderFieldData($field,$item->order_id);
						//echo $item->order_id;
						//echo $field_value;
						//echo "<BR />";
						if($field_value != ""){
							//echo $field_value;
							$field_content_array[] = OSBHelper::getLanguageFieldValueOrder($field,'field_label',$row->order_lang).": ".$field_value;
						}
					}
					//print_r($field_content_array);
				}
				//echo implode(" | ",$field_content_array);
				//die();	
				$csv_content .= $csv_separator.'"'.implode(" | ",$field_content_array).'"';
				if($configClass['disable_payments'] == 1){
					$order_payment = $row->order_payment;
					if($order_payment != ""){
						$csv_content .= $csv_separator.'"'.JText::_(os_payments::loadPaymentMethod($order_payment)->title).'"';
						$csv_content .= $csv_separator.'"'.$row->order_total." ".$configClass['currency_format'].'"';
					}
				}
				/*
				if($row->order_status == "P"){
					$csv_content .= $csv_separator.'"'.JText::_('OS_PENDING').'"';
				}elseif($row->order_status == "S"){
					$csv_content .= $csv_separator.'"'.JText::_('OS_COMPLETE').'"';
				}elseif($row->order_status == "C"){
					$csv_content .= $csv_separator.'"'.JText::_('OS_CANCEL').'"';
				}
				*/
				$csv_content .= $csv_separator.'"'.OSBHelper::orderStatus(0,$row->order_status).'"';
				$csv_content .= "\n";
			}
		}
		
		$header = $header.$csv_content;
		//create the csv file
		$filename = time().".csv";
		$csv_absoluted_link = JPATH_ROOT.DS."tmp".DS.$filename;
		//create the content of csv
		$csvf = fopen($csv_absoluted_link,'w');
		@fwrite($csvf,$header);
		@fclose($csvf);
		OSappscheduleOrders::downloadfile2($csv_absoluted_link,$filename);
	}
	
	function downloadfile2($file_path,$filename){
    	while (@ob_end_clean());
    	$len = @ filesize($file_path);
		$cont_dis ='attachment';

		// required for IE, otherwise Content-disposition is ignored
		if(ini_get('zlib.output_compression'))  {
			ini_set('zlib.output_compression', 'Off');
		}
	
	    header("Pragma: public");
	    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    header("Expires: 0");
	
	    header("Content-Transfer-Encoding: binary");
		header('Content-Disposition:' . $cont_dis .';'
			. ' filename="'.$filename.'";'
			. ' size=' . $len .';'
			); //RFC2183
	    header("Content-Length: "  . $len);
	    if( ! ini_get('safe_mode') ) { // set_time_limit doesn't work in safe mode
		    @set_time_limit(0);
	    }
	    OSappscheduleOrders::readfile_chunked($file_path);
		exit();
    }
    
    
    function readfile_chunked($filename,$retbytes=true){
		$chunksize = 1*(1024*1024); // how many bytes per chunk
		$buffer = '';
		$cnt =0;
		$handle = fopen($filename, 'rb');
		if ($handle === false) {
   			return false;
		}
		while (!feof($handle)) {
	   		$buffer = fread($handle, $chunksize);
	   		echo $buffer;
			@ob_flush();
			flush();
	   		if ($retbytes) {
	       		$cnt += strlen($buffer);
	   		}
		}
   		$status = fclose($handle);
	    if ($retbytes && $status) {
   			return $cnt; // return num. bytes delivered like readfile() does.
		}
		return $status;
	}
	
	/**
	 * agent list
	 *
	 * @param unknown_type $option
	 */
	function orders_list($option){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();
		$lists = array();
		$condition = '';
		
		
		// filte sort
		$filter_order 				= JRequest::getVar('filter_order','a.id');
		$filter_order_Dir 			= JRequest::getVar('filter_order_Dir','desc');
		$lists['order'] 			= $filter_order;
		$lists['order_Dir'] 		= $filter_order_Dir;
		$order_by 					= " ORDER BY $filter_order $filter_order_Dir";
		
		// Get the pagination request variables
		$limit						= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', 20, 'int' );
		$limitstart					= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
		
		// search 
		$keyword 			 		= $mainframe->getUserStateFromRequest($option.'.orders.keyword','keyword','','string');
		$lists['keyword']  			= $keyword;
		if($keyword != ""){
			$condition 			   .= " AND (";
			$condition 			   .= " a.order_name LIKE '%$keyword%'";
			$condition 			   .= " OR a.order_email LIKE '%$keyword%'";
			$condition 			   .= " OR a.order_phone LIKE '%$keyword%'";
			$condition 			   .= " OR a.order_country LIKE '%$keyword%'";
			$condition 			   .= " OR a.order_city LIKE '%$keyword%'";
			$condition 			   .= " OR a.order_state LIKE '%$keyword%'";
			$condition 			   .= " OR a.order_zip LIKE '%$keyword%'";
			$condition 			   .= " OR a.order_address LIKE '%$keyword%'";
			$condition 			   .= " OR a.order_upfront LIKE '%$keyword%'";
			$condition 			   .= " OR a.order_date LIKE '%$keyword%'";
			$condition 			   .= " )";
		}
			
		// filter state
		$filter_status 				= $mainframe->getUserStateFromRequest($option.'.orders.filter_status','filter_status','','string');
		$condition 				   .= ($filter_status != '')? " AND a.order_status = '$filter_status'":"";
		
		$lists['filter_status'] 	= OSBHelper::buildOrderStaticDropdownList($filter_status,"onChange='javascript:document.adminForm.submit();'",JText::_('OS_SELECT_ORDER_STATUS'),'filter_status');
		
		$lists['order_status']		= array('P'=>'<font color="orange">'.JText::_('OS_PENDING').'<font>', 'S'=>'<font color="green">'.JText::_('OS_COMPLETE').'</font>', 'C'=>'<font color="red">'.JText::_('OS_CANCEL').'</font>');
		
		// filter date
		$filter_date_from			= $mainframe->getUserStateFromRequest($option.'.orders.filter_date_from','filter_date_from',null,'string');
		
		$lists['filter_date_from']	= $filter_date_from;	
		if ($filter_date_from != '' ){
			$condition 				.= " AND b.booking_date >= '$filter_date_from'";
		}
		$filter_date_to				= $mainframe->getUserStateFromRequest($option.'.orders.filter_date_to','filter_date_to',null,'string');
		$lists['filter_date_to']	= $filter_date_to;	
		if ($filter_date_to != '' ){
			$condition 				.= " AND b.booking_date <= '$filter_date_to'";
		}
		// filter extra
		$add_query 						= '';
		$filter_service 				= $mainframe->getUserStateFromRequest($option.'.orders.filter_service','filter_service',0,'int');
		$filter_employee 				= $mainframe->getUserStateFromRequest($option.'.orders.filter_employee','filter_employee',0,'int');
		if ($filter_service || $filter_employee || $filter_date_from || $filter_date_to){
			$add_query 					= " INNER JOIN #__app_sch_order_items AS b ON a.id = b.order_id ";
			$condition 				   .= $filter_service? " AND b.sid = '$filter_service' ":'';	
			$condition 				   .= $filter_employee? " AND b.eid = '$filter_employee' ":'';
		}
		// filter service
		$options 					= array();
		if ($filter_employee){	
			$query 					= " SELECT a.id AS value, a.service_name AS text"
									 ." FROM #__app_sch_services AS a"						 
									." INNER JOIN #__app_sch_employee_service AS b ON (a.id = b.service_id AND b.employee_id = '$filter_employee')"					//." WHERE  a.published = '1' "						 
									." ORDER BY a.service_name, a.ordering";
		
			
		}else{
				
			$query 					= " SELECT `id` AS value, `service_name` AS text"
									." FROM #__app_sch_services"
										// ." WHERE `published` = '1' "
									." ORDER BY service_name, ordering";
			
		}
			
		$db->setQuery($query);
		//echo $db->getQuery();
		$options = $db->loadObjectlist();
		array_unshift($options,JHtml::_('select.option',0,JText::_('OS_FILTER_SERVICE')));
		$lists['filter_service']	= JHtml::_('select.genericlist',$options,'filter_service','class="input-medium" onchange="this.form.submit();" ','value','text',$filter_service);
		// filter employee
			
		$options 					= array();
			
		if ($filter_service){
			$query 					= " SELECT a.id AS value, a.employee_name AS text"
									." FROM #__app_sch_employee AS a"
								    ." INNER JOIN #__app_sch_employee_service AS b ON (a.id = b.employee_id AND b.service_id = '$filter_service')"
																// ." WHERE a.published = '1' "
								    ." ORDER BY a.employee_name, b.ordering"
								    ;
		}else{
			$query 					= " SELECT `id` AS value, `employee_name` AS text"
									 ." FROM #__app_sch_employee "
									 // ." WHERE `published` = 1 "
									 ." ORDER BY employee_name "
									 ;
		}
			
		$db->setQuery($query);
		$options = $db->loadObjectlist();
		array_unshift($options,JHtml::_('select.option',0,JText::_('OS_FILTER_EMPLOYEE')));
		$lists['filter_employee']	= JHtml::_('select.genericlist',$options,'filter_employee','class="input-medium" onchange="this.form.submit();" ','value','text',$filter_employee);

		// get data	
		$count 						= " SELECT count(distinct a.id) FROM #__app_sch_orders AS a" 
		."\n $add_query "
		."\n WHERE 1=1";
		$count 					   .= $condition;
		//$count					   .= " group by a.id";
		$db->setQuery($count);
		$total 						= $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav 					= new JPagination($total,$limitstart,$limit);
		$list  						= " SELECT a.* FROM #__app_sch_orders AS a"
		.$add_query
		."\n WHERE 1=1 ";
		$list 					   .= $condition;
		$list 					   .= " group by a.id ".$order_by;
		$db->setQuery($list,$pageNav->limitstart,$pageNav->limit);
		//echo $db->getQuery();
		$rows 						= $db->loadObjectList();
		HTML_OSappscheduleOrders::orders_list($option,$rows,$pageNav,$lists);
	}
	
	
	
	/**
	 * * remove agent
	 * * @param unknown_type $option
	 * * @param unknown_type $cid
	 *
	 **/	
	function orders_remove($option,$cid){
		global $mainframe,$configClass;
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			if($configClass['integrate_gcalendar'] == 1){
				foreach ($cid as $id){
					OSBHelper::removeEventOnGCalendar($id);
				}
			}
			$cids = implode(",",$cid);
			$db->setQuery("DELETE FROM #__app_sch_orders WHERE id IN ($cids)");
			$db->query();
			
			$db->setQuery("DELETE FROM #__app_sch_order_items WHERE order_id IN ($cids)");
			$db->query();
		}
		
		$mainframe->enqueueMessage(JText::_("OS_ITEMS_HAS_BEEN_DELETED"),'message');
		OSappscheduleOrders::orders_list($option);
	}
	
	
	/**
	 * Service modify
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function orders_detail($option,$id){
		global $mainframe;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);	
		$mainframe 	= JFactory::getApplication();
		$db 		= JFactory::getDbo();
		$row 		= &JTable::getInstance('Order','OsAppTable');
		
		$row->load((int)$id);
		
		$options = array();
		$options[]			= JHtml::_('select.option','P',JText::_('OS_PENDING'));				
		$options[]			= JHtml::_('select.option','S',JText::_('OS_COMPLETE'));
		$options[]			= JHtml::_('select.option','C',JText::_('OS_CANCEL'));
		//$row->order_status_select_list = JHTML::_('select.genericlist',$options, 'order_status','class="inputbox" ','value','text',$row->order_status);
		$row->order_status_select_list = OSBHelper::buildOrderStaticDropdownList($row->order_status,'','','order_status');
		// list detail
		// limit page
		$limit					= $mainframe->getUserStateFromRequest( 'order.global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart				= $mainframe->getUserStateFromRequest( $option.'.order.limitstart', 'limitstart', 0, 'int' );
		// get database	
		$count = " SELECT count(a.id) FROM #__app_sch_order_items AS a "
				."\n INNER JOIN #__app_sch_employee AS b ON a.eid = b.id"
				."\n INNER JOIN #__app_sch_services AS c ON a.sid = c.id"
				."\n WHERE a.order_id = '$row->id'";
		$db->setQuery($count);
		$total = $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total,$limitstart,$limit);
		
		$list = " SELECT a.*, c.service_name, c.service_time_type, b.employee_name FROM #__app_sch_order_items AS a "
				."\n INNER JOIN #__app_sch_employee AS b ON a.eid = b.id"
				."\n INNER JOIN #__app_sch_services AS c ON a.sid = c.id"
				."\n WHERE a.order_id = '$row->id'"
				;
		
		$db->setQuery($list,$pageNav->limitstart,$pageNav->limit);
		$rows = $db->loadObjectList();
		$db->setQuery("Select a.*,b.fvalue from #__app_sch_fields as a inner join #__app_sch_field_data as b on b.fid = a.id  where b.order_id = '$id'");
		$fields = $db->loadObjectList();
		
		$countryArr[] = JHTML::_('select.option','','');
		$db->setQuery("Select country_name as value, country_name as text from #__app_sch_countries order by country_name");
		$countries = $db->loadObjectList();
		$countryArr = array_merge($countryArr,$countries);
		$lists['country'] = JHTML::_('select.genericlist',$countryArr,'order_country','class="input-large"','value','text',$row->order_country);

		HTML_OSappscheduleOrders::orders_detail($option,$row,$rows,$pageNav,$fields,$lists);
	}
	
	
	/**
	 * publish or unpublish agent
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 * @param unknown_type $state
	 */
	function orders_status($option,$save){
		global $mainframe;
		$db = JFactory::getDbo();
		require_once(JPATH_ROOT.DS."components".DS."com_osservicesbooking".DS."helpers".DS."common.php");
		$mainframe 	= JFactory::getApplication();
		$id 		= JRequest::getInt('id',0);
		
		$row = &JTable::getInstance('Order','OsAppTable');
		$row->load((int) $id);
		$post = JRequest::get('post');
		$row->bind($post);
		$row->order_notes = $_POST['notes'];
		$row->order_status = JRequest::getVar('order_status','P');
		$row->store();			
		
		if($id == 0){
			$id = $db->insertID();
		}
		//save extra fields
		$db->setQuery("Delete from #__app_sch_order_options where order_id = '$id'");
		$db->query();
		$db->setQuery("Delete from #__app_sch_field_data where order_id = '$id'");
		$db->query();
		$db->setQuery("Select * from #__app_sch_fields where published = '1' and field_area = '1'");
		$fields = $db->loadObjectList();
		if(count($fields) > 0){
			for($i=0;$i<count($fields);$i++){
				$field = $fields[$i];
				$field_id = $field->id;
				$field_type = $field->field_type;
				$field_name = "field_".$field_id;
				if($field_type == 0){
					$field_value = JRequest::getVar($field_name,'');
					if($field_value != ""){
						$db->setQuery("INSERT INTO #__app_sch_field_data (id,order_id, 	fid, fvalue) VALUES (NULL,'$id','$field_id','$field_value')");
						$db->query();
					}
				}else{
					$field_value = JRequest::getVar($field_name,'');
					
					if($field_value != ""){
						$field_value_array = explode(",",$field_value);
						//print_r($field_value_array);
						if(count($field_value_array) > 0){
							for($j=0;$j<count($field_value_array);$j++){
								$value = $field_value_array[$j];
								$db->setQuery("INSERT INTO #__app_sch_order_options (id,order_id,field_id,option_id) VALUES (NULL,'$id','$field_id','$value')");
								//echo $db->getQuery();
								$db->query();
							}
						}
					}
				}
			}
		}
		//die();
		$old_status = JRequest::getVar('old_status','P');
		
		if((JRequest::getVar('order_status','P') == "S") and ($old_status != "S")){
			//send notification email to user
			HelperOSappscheduleCommon::sendEmail("confirm",$id);
			HelperOSappscheduleCommon::sendEmployeeEmail('employee_notification',$id,0);
			HelperOSappscheduleCommon::sendSMS('confirm',$id);
			OSBHelper::updateGoogleCalendar($id);
		}
		
		if((JRequest::getVar('order_status','P') == "C") and ($old_status != "C")){
			OSBHelper::removeEventOnGCalendar($id);
		}
		
		if((JRequest::getVar('order_status','P') != "C") and (JRequest::getVar('order_status','P') != "S")){
			HelperOSappscheduleCommon::sendEmail("order_status_changed_to_customer",$id);
			HelperOSappscheduleCommon::sendEmployeeEmail('order_status_changed_to_employee',$id,0);
			HelperOSappscheduleCommon::sendSMS('order_status_changed_to_customer',$id);
		}
		
		if ($save || !$id){
			$msg = JText::_("OS_ITEMS_STATUS_HAS_BEEN_CHANGED");			
			$mainframe->redirect("index.php?option=com_osservicesbooking&task=orders_list",$msg);
		}else{
			$msg = JText::_("OS_ITEMS_STATUS_HAS_BEEN_CHANGED");
			$mainframe->redirect("index.php?option=com_osservicesbooking&task=orders_detail&cid[]=".$id,$msg);
		}		
	}
	
	public static function updateNewOrderStatus(){
		global $configClass;
		$configClass = OSBHelper::loadConfig();
		require_once(JPATH_ROOT.DS."components".DS."com_osservicesbooking".DS."helpers".DS."common.php");
		$db = Jfactory::getDbo();
		$order_id = JRequest::getInt('order_id',0);
		$db->setQuery("Select * from #__app_sch_orders where id = '$order_id'");
		$row = $db->loadObject();
		$old_status = $row->order_status;
		if(JRequest::getVar('new_status','P') != $old_status){
			if((JRequest::getVar('new_status','P') == "S") and ($old_status != "S")){
				HelperOSappscheduleCommon::sendEmail("confirm",$order_id);
				HelperOSappscheduleCommon::sendEmployeeEmail('employee_notification',$order_id,0);
				HelperOSappscheduleCommon::sendSMS('confirm',$order_id);
				OSBHelper::updateGoogleCalendar($order_id);
			}
			
			if((JRequest::getVar('new_status','P') == "C") and ($old_status != "C")){
				OSBHelper::removeEventOnGCalendar($order_id);
			}
			
			$db->setQuery("Update #__app_sch_orders set order_status = '".JRequest::getVar('new_status','P')."' where id = '$order_id'");
			$db->query();
			
			//Send alert email
			if($old_status != "S"){
				HelperOSappscheduleCommon::sendEmail("order_status_changed_to_customer",$order_id);
				HelperOSappscheduleCommon::sendEmployeeEmail('order_status_changed_to_employee',$order_id,0);
				HelperOSappscheduleCommon::sendSMS('order_status_changed_to_customer',$order_id);
			}
		}
		
		$optionArr = array();
		$statusArr = array(JText::_('OS_PENDING'),JText::_('OS_COMPLETED'),JText::_('OS_CANCELED'),JText::_('OS_ATTENDED'),JText::_('OS_TIMEOUT'),JText::_('OS_DECLINED'),JText::_('OS_REFUNDED'));
		$statusVarriableCode = array('P','S','C','A','T','D','R');
		for($j=0;$j<count($statusArr);$j++){
			$optionArr[] = JHtml::_('select.option',$statusVarriableCode[$j],$statusArr[$j]);				
		}
		echo "<span style='color:gray;'>".JText::_('OS_CURRENT_STATUS').": <strong>".OSBHelper::orderStatus(0,JRequest::getVar('new_status','P'))."</strong></span>";
		echo "<BR />";
		echo "<span style='color:gray;font-size:11px;'>".JText::_('OS_CHANGE_STATUS')."</span>";
		echo JHtml::_('select.genericlist',$optionArr,'orderstatus'.$row->id,'class="input-small"','value','text',JRequest::getVar('new_status','P'));
		?>
		<a href="javascript:updateOrderStatusAjax(<?php echo $row->id;?>,'<?php echo JUri::root();?>')">
			<i class="icon-edit"></i>
		</a>
		<?php
		exit();
	}
	
	public static function getUserInput($user_id)
	{
		// Initialize variables.
		$html = array();
		//$groups = $this->getGroups();
		//$excluded = $this->getExcluded();
		$link = 'index.php?option=com_users&amp;view=users&amp;layout=modal&amp;tmpl=component&amp;field=user_id';
			//. (isset($groups) ? ('&amp;groups=' . base64_encode(json_encode($groups))) : '')
			//. (isset($excluded) ? ('&amp;excluded=' . base64_encode(json_encode($excluded))) : '');

		// Initialize some field attributes.
		$attr = ' class="inputbox"';
		//$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

		// Initialize JavaScript field attributes.
		//$onchange = (string) $this->element['onchange'];

		// Load the modal behavior script.
		JHtml::_('behavior.modal');
		JHtml::_('behavior.modal', 'a.modal_user_id');
		
		$db = JFactory::getDbo();
		$db->setQuery("SELECT id, email FROM #__users");
		$users = $db->loadObjectlist();

		// Build the script.
		$script = array();
		$script[] = ' var emailuser = [];';
		foreach($users AS $user){
			$script[] = ' emailuser['.$user->id.'] = \''.$user->email.'\';';
		}
		
		// Build the script.
		$script[] = '	function jSelectUser_user_id(id, title) {';
		$script[] = '		var old_id = document.getElementById("user_id").value;';
		$script[] = '		if (old_id != id) {';
		$script[] = '			document.getElementById("user_id").value = id;';
		$script[] = '			document.getElementById("user_id_name").value = title;';
		$script[] = '			document.getElementById("order_name").value = title;';
		$script[] = '			document.getElementById("order_email").value = emailuser[id];';
		$script[] = '			' . $onchange;
		$script[] = '		}';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Load the current username if available.
		$table = JTable::getInstance('user');
		
		if ($user_id)
		{
			$table->load($user_id);
		}
		else
		{
			$table->username = JText::_('OS_SELECT_AGENT');
		}

		// Create a dummy text field with the user name.
		$html[] = '<span class="input-append">';
		$html[] = '<input type="text" class="input-medium" id="user_id_name" value="'.htmlspecialchars($table->name, ENT_COMPAT, 'UTF-8') .'" disabled="disabled" size="35" /><a class="modal btn" title="'.JText::_('JLIB_FORM_CHANGE_USER').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-file"></i> '.JText::_('JLIB_FORM_CHANGE_USER').'</a>';
		$html[] = '</span>';

		// Create the real field, hidden, that stored the user id.
		$html[] = '<input type="hidden" id="user_id" name="user_id" value="'.$user_id.'" />';

		return implode("\n", $html);
	}
	
	
	/**
	 * Save services
	 *
	 * @param unknown_type $option
	 */
	function saveService($option){
		global $mainframe,$configClass;
		jimport('joomla.filesystem.file');
		$db 		= JFactory::getDbo();
		$order_id 	= JRequest::getInt('order_id',0);
		$sid 		= JRequest::getInt('sid',0);
		$eid		= JRequest::getInt('eid',0);
		$vid		= JRequest::getInt('vid',0);
		$start_time = JRequest::getVar('start_time',0);
		$end_time	= JRequest::getVar('end_time',0);
		$nslots 	= JRequest::getInt('nslots',0);
		$field_ids  = JRequest::getVar('field_ids'.$sid,'');
		$booking_date = JRequest::getVar('booking_date','');
		
		
		//OSB 2.3.3. add
		$db->setQuery("Select * from #__app_sch_employee where id = '$eid'");
		$employee = $db->loadObject();
		$client_id = $employee->client_id;
		$app_name = $employee->app_name;
		$app_email_address = $employee->app_email_address;
		$p12_key_filename = $employee->p12_key_filename;
		$gcalendarid = $employee->gcalendarid;
		
		$db->setQuery("Select service_name from #__app_sch_services where id = '$sid'");
		$service_name = $db->loadResult();
		
		$selected_timeslots = JRequest::getVar('selected_timeslots');
		if(count($selected_timeslots) > 0){
			for($t = 0;$t<count($selected_timeslots);$t++){
				$timeslot = $selected_timeslots[$t];
				$timeslotArr = explode("-",$timeslot);
				$nslots = JRequest::getInt("nslots".$timeslot,1);
				$start_time = $timeslotArr[0];
				$end_time = $timeslotArr[1];
				$row = &JTable::getInstance('OrderItem','OsAppTable');
				$row->bind($_POST);
				$row->id = 0;
				$row->start_time = $start_time;
				$row->end_time = $end_time;
				$row->nslots = $nslots;
				$row->store();
				$order_item_id = $db->insertid();
				
				if(($configClass['integrate_gcalendar'] == 1) and ($client_id != "") and ($app_name != "")and ($app_email_address != "") and ($gcalendarid != "") and ($p12_key_filename != "") and (JFile::exists(JPATH_COMPONENT_SITE."/".$p12_key_filename)) ){
					OSBHelper::addEventonGCalendar(trim($client_id),trim($app_name),trim($app_email_address),trim($p12_key_filename),trim($gcalendarid),$service_name,$start_time,$end_time,$booking_date,$order_item_id,$order_id);
				}
				
				if($field_ids != ""){
					$fieldArr = explode(",",$field_ids);
					if(count($fieldArr) > 0){
						for($i=0;$i<count($fieldArr);$i++){
							$field = trim($fieldArr[$i]);
							$field_name = "field_".$sid."_".$eid."_".$field."_selected";
							$field_value = JRequest::getVar($field_name,'');
							if($field_value != ""){
								$field_value_array = explode(",",$field_value);
								if(count($field_value_array) > 0){
									for($j=0;$j<count($field_value_array);$j++){
										$db->setQuery("INSERT INTO #__app_sch_order_field_options (id, order_item_id,field_id, option_id) VALUES (NULL,'$order_item_id','$field','".$field_value_array[$j]."')");
										$db->query();
									}
								}
							}
						}
					}
				}
			}
		}
		
		
		//save complete
		$msg = JText::_('OS_NEW_SERVICE_HAS_BEEN_ADDED_TO_ORDER');
		$mainframe->redirect("index.php?option=com_osservicesbooking&task=orders_detail&cid[]=".$order_id,$msg);
	}
	
	/**
	 * Remove service
	 *
	 * @param unknown_type $option
	 */
	function removeService($option){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$order_id = JRequest::getInt('order_id',0);
		$id = JRequest::getInt('id',0);
		jimport('joomla.filesystem.file');
		$db->setQuery("Select eid from #__app_sch_order_items where id = '$id'");
		$eid = $db->loadResult();
		$db->setQuery("Select * from #__app_sch_employee where id = '$eid'");
		$employee = $db->loadObject();
		$client_id = $employee->client_id;
		$app_name = $employee->app_name;
		$app_email_address = $employee->app_email_address;
		$p12_key_filename = $employee->p12_key_filename;
		$gcalendarid = $employee->gcalendarid;
		if(($configClass['integrate_gcalendar'] == 1) and ($client_id != "") and ($app_name != "")and ($app_email_address != "") and ($gcalendarid != "") and ($p12_key_filename != "") and (JFile::exists(JPATH_COMPONENT_SITE."/".$p12_key_filename)) ){
			OSBHelper::removeOneEventOnGCalendar($id);
		}
		$db->setQuery("Delete from #__app_sch_order_items where id = '$id'");
		$db->query();
		$db->setQuery("Delete from #__app_sch_order_field_options where order_item_id = '$id'");
		$db->query();
		//remove complete
		$msg = JText::_('OS_SERVICE_HAS_BEEN_REMOVED');
		$mainframe->redirect("index.php?option=com_osservicesbooking&task=orders_detail&cid[]=".$order_id,$msg);
	}
	
	/**
	 * Send the notification emails to customers
	 *
	 * @param unknown_type $cid
	 */
	function sendnotifyEmails($cid){
		global $mainframe,$configClass;
		require_once(JPATH_ROOT.DS."components".DS."com_osservicesbooking".DS."helpers".DS."common.php");
		if(count($cid) > 0){
			for($i=0;$i<count($cid);$i++){
				$order_id = $cid[$i];
				HelperOSappscheduleCommon::sendEmail('confirm',$order_id);
			}
		}
		$mainframe->redirect("index.php?option=com_osservicesbooking&task=orders_list");
	}
	
	/**
	 * Export Report
	 *
	 */
	function exportReport(){
		global $mainframe;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);	
		$db = JFactory::getDbo();
		$date_from = JRequest::getVar('date_from','');
		$date_to   = JRequest::getVar('date_to','');
		$sid	   = JRequest::getInt('sid',0);
		$eid	   = JRequest::getInt('eid',0);
		$order_status = JRequest::getVar('order_status','');
		
		$query = "Select a.*,b.*,a.id as order_item_id,c.service_name,c.service_time_type,d.employee_name from #__app_sch_order_items as a inner join #__app_sch_orders as b on b.id = a.order_id inner join #__app_sch_services as c on c.id = a.sid  inner join #__app_sch_employee as d on d.id = a.eid where 1=1";
		if($sid > 0){
			$query .= " and a.sid = '$sid'";
		}
		if($eid > 0){
			$query .= " and a.eid = '$eid'";
		}
		if($date_from != ""){
			$query .= " and a.start_time >= '".strtotime($date_from)."'";
		}
		if($date_to != ""){
			$query .= " and a.end_time <= '".strtotime($date_to)."'";
		}
		if($order_status != ""){
			$query .= " and b.order_status = '$order_status'";
		}
		$query .= " order by a.start_time";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		$lists['date_from'] = $date_from;
		$lists['date_to'] = $date_to;
		$lists['sid'] = $sid;
		$lists['eid'] = $eid;
		$lists['order_status'] = $order_status;
		
		HTML_OSappscheduleOrders::exportReport($rows,$lists);
	}
}
?>