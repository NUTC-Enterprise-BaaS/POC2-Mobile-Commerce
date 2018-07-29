<?php
/*------------------------------------------------------------------------
# manage.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class OSappscheduleManage{
	/**
	 * Osproperty default
	 *
	 * @param unknown_type $option
	 */
	function display($option,$task){
		$user = JFactory::getUser();
		switch ($task){
			case "manage_orders":
                OSappscheduleManage::manageAllOrders();
			break;
			case "manage_removeorders":
				OSappscheduleManage::removeOrders();
			break;
		}
	}

    /**
     * List All orders
     *
     */
    static function manageAllOrders(){
        global $mainframe,$configClass;
        $document = JFactory::getDocument();
        $menus = JSite::getMenu();
        $menu = $menus->getActive();
        $list_type = 0;
        if (is_object($menu)) {
            $params = new JRegistry() ;
            $params->loadString($menu->params);
            if($params->get('page_title') != ""){
                $document->setTitle($params->get('page_title'));
            }else{
                $document->setTitle($configClass['business_name'].' | '.JText::_('OS_LIST_ALL_ORDERS'));
            }
            $list_type = $params->get('list_type',0);
        }else{
            $document->setTitle($configClass['business_name'].' | '.JText::_('OS_LIST_ALL_ORDERS'));
        }
        $db = JFactory::getDbo();

		$limit = JRequest::getInt('limit',10);
		$limitstart = JRequest::getInt('limitstart',0);

		// filter state
		$filter_status 				= JRequest::getVar('filter_status','');
		$condition 				   .= ($filter_status != '')? " AND a.order_status = '$filter_status'":"";

		$lists['filter_status'] 	= OSBHelper::buildOrderStaticDropdownList($filter_status,"onChange='javascript:document.ftForm.submit();'",JText::_('OS_SELECT_ORDER_STATUS'),'filter_status');
		
		$lists['order_status']		= array('P'=>'<span style="color:orange;">'.JText::_('OS_PENDING').'</span>', 'S'=>'<span style="color:green;">'.JText::_('OS_COMPLETE').'</span>', 'C'=>'<span style="color:red;">'.JText::_('OS_CANCEL').'</span>');
		$service_filter				= JRequest::getInt('service_filter',0);
		$employee_filter			= JRequest::getInt('employee_filter',0);

		if ($service_filter || $employee_filter){
			$add_query 				= " INNER JOIN #__app_sch_order_items AS b ON a.id = b.order_id ";
			$condition 			   .= $service_filter? " AND b.sid = '$service_filter' ":'';	
			$condition 			   .= $employee_filter? " AND b.eid = '$employee_filter' ":'';
		}

		// filter service
		$options 					= array();
		if ($employee_filter){	
			$query 					= " SELECT a.id AS value, a.service_name AS text"
									 ." FROM #__app_sch_services AS a"						 
									 ." INNER JOIN #__app_sch_employee_service AS b ON (a.id = b.service_id AND b.employee_id ='$employee_filter')"			 
									 ." ORDER BY a.service_name, a.ordering";
		
			
		}else{
			$query 					= " SELECT `id` AS value, `service_name` AS text"
									 ." FROM #__app_sch_services"
									 ." ORDER BY service_name, ordering";
			
		}
		$db->setQuery($query);
		//echo $db->getQuery();
		$options = $db->loadObjectlist();
		array_unshift($options,JHtml::_('select.option',0,JText::_('OS_SELECT_SERVICES')));
		$lists['filter_service']	= JHtml::_('select.genericlist',$options,'service_filter','class="input-medium" onchange="javascript:document.ftForm.submit();" ','value','text',$service_filter);
		// filter employee
		$options 					= array();	
		if ($service_filter){
			$query 					= " SELECT a.id AS value, a.employee_name AS text"
									." FROM #__app_sch_employee AS a"
								    ." INNER JOIN #__app_sch_employee_service AS b ON (a.id = b.employee_id AND b.service_id = '$service_filter')"
								    ." ORDER BY a.employee_name, b.ordering"
								    ;
		}else{
			$query 					= " SELECT `id` AS value, `employee_name` AS text"
									 ." FROM #__app_sch_employee "
									 ." ORDER BY employee_name "
									 ;
		}		
		$db->setQuery($query);
		$options = $db->loadObjectlist();
		array_unshift($options,JHtml::_('select.option',0,JText::_('OS_SELECT_EMPLOYEE')));
		$lists['filter_employee']	= JHtml::_('select.genericlist',$options,'employee_filter','class="input-medium" onchange="javascript:document.ftForm.submit();" ','value','text',$employee_filter);

		// get data	
		$count 						= " SELECT count(a.id) FROM #__app_sch_orders AS a" 
		."\n $add_query "
		."\n WHERE 1=1";
		$count 					   .= $condition;
		$count					   .= "";
		$db->setQuery($count);
		$total 						= $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav 					= new OSBJPagination($total,$limitstart,$limit);
		$list  						= " SELECT a.* FROM #__app_sch_orders AS a"
		.$add_query
		."\n WHERE 1=1 ";
		$list 					   .= $condition;
		$order_by 					= " ORDER BY order_date desc";
		$list 					   .= " group by a.id ".$order_by;
		$db->setQuery($list,$pageNav->limitstart,$pageNav->limit);
		$rows 						= $db->loadObjectList();
		
        HTML_OSappscheduleManage::listOrders($rows,$lists,$pageNav);
    }

	static function removeOrders(){
		global $mainframe,$configClass;
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();
		$cid = JRequest::getVar('cid');
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
		
		$mainframe->redirect(JRoute::_('index.php?option=com_osservicesbooking&view=manageallorders&Itemid='.JRequest::getInt('Itemid',0)),JText::_("OS_ITEMS_HAS_BEEN_DELETED"));
	}
}
?>