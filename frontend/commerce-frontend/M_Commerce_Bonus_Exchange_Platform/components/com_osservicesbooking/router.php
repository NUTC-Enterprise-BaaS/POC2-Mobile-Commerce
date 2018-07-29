<?php
/**
 * @version		1.0.0
 * @package		Joomla
 * @subpackage	OS Services Booking
 * @author  	Dang Thuc Dam
 * @copyright	Copyright (C) 2013 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();
error_reporting(0);
/**
 * 
 * Build the route for the com_osproperty component
 * @param	array	An array of URL arguments
 * @return	array	The URL arguments to use to assemble the subsequent URL.
 * @since	1.5
 */
function osservicesbookingBuildRoute(&$query)
{
	$db = JFactory::getDbo();
	$segments = array();
	require_once JPATH_ROOT . '/administrator/components/com_osservicesbooking/helpers/helper.php';
	$db = JFactory::getDbo();
	$queryArr = $query;
	if (isset($queryArr['option']))
		unset($queryArr['option']);
	if (isset($queryArr['Itemid']))
		unset($queryArr['Itemid']);
	//Store the query string to use in the parseRouter method
	$queryString = http_build_query($queryArr);
	
	$app		= JFactory::getApplication();
	$menu		= $app->getMenu();
	
	//We need a menu item.  Either the one specified in the query, or the current active one if none specified
	if (empty($query['Itemid']))
		$menuItem = $menu->getActive();
	else
		$menuItem = $menu->getItem($query['Itemid']);
	
	if (empty($menuItem->query['view']))
	{
		$menuItem->query['view'] = '';
	}
		
	$view = isset($query['view']) ? $query['view'] : '';
	$id = 	isset($query['id']) ? (int) $query['id'] : 0;
	$task = isset($query['task']) ? $query['task'] : '';
	
	if($task == ""){
		switch ($view)
		{
			case "category":
				$task = "category_listing";
			break;
			case "employee":
				$task = "default_employeeworks";
			break;
			case "customer":
				$task = "default_customer";
			break;
			default:
				$task = "default_layout";
			break;
		}
	}
	switch ($task){
		case "default_layout":
			if(isset($query['category_id']) and ($query['category_id'] > 0)){
				$db->setQuery("Select * from #__app_sch_categories where id = '".$query['category_id']."'");
				$category = $db->loadObject();
				$segments[] = JText::_('OS_CATEGORY').": ".OSBHelper::getLanguageFieldValue($category,'category_name');
			}
			if(isset($query['vid']) and ($query['vid'] > 0)){
				$db->setQuery("Select * from #__app_sch_venues where id = '".$query['vid']."'");
				$venue = $db->loadObject();
				$segments[] = JText::_('OS_VENUE').": ".OSBHelper::getLanguageFieldValue($venue,'address');
			}
			if(isset($query['employee_id']) and ($query['employee_id'] > 0)){
				$db->setQuery("Select id, employee_name from #__app_sch_employee where id = '".$query['employee_id']."'");
				$employee = $db->loadObject();
				$segments[] = JText::_('OS_EMPLOYEE').": ".$employee->employee_name;
			}
            if(isset($query['sid']) and ($query['sid'] > 0)){
                $db->setQuery("Select id, service_name from #__app_sch_services where id = '".$query['sid']."'");
                $service = $db->loadObject();
                $segments[] = JText::_('OS_SERVICE').": ".$service->service_name;
            }
		break;
		case "default_paymentfailure":
			$segments[] = JText::_('OS_FAILURE_PAYMENT');
			$segments[] = $query['id'];
		break;
		case "default_orderDetailsForm":
			$segments[] = JText::_('OS_ORDER_DETAILS');
			$segments[] = $query['id'];
			unset($query['id']);
			if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
				unset($query['Itemid']);
			}
		break;
		case "form_step1":
			$segments[] = JText::_('OS_CHECKOUT');
			if(isset($query['category_id']) and ($query['category_id'] > 0)){
				$db->setQuery("Select * from #__app_sch_categories where id = '".$query['category_id']."'");
				$category = $db->loadObject();
				$segments[] = JText::_('OS_CATEGORY').": ".OSBHelper::getLanguageFieldValue($category,'category_name');
				unset($query['category_id']);
			}
			if(isset($query['vid']) and ($query['vid'] > 0)){
				$db->setQuery("Select * from #__app_sch_venues where id = '".$query['vid']."'");
				$venue = $db->loadObject();
				$segments[] = JText::_('OS_VENUE').": ".OSBHelper::getLanguageFieldValue($venue,'venue');
				unset($query['vid']);
			}
			if(isset($query['sid']) and ($query['sid'] > 0)){
				$db->setQuery("Select * from #__app_sch_services where id = '".$query['sid']."'");
				$service = $db->loadObject();
				$segments[] = JText::_('OS_SERVICE').": ".OSBHelper::getLanguageFieldValue($service,'service_name');
				unset($query['sid']);
			}
			if(isset($query['employee_id']) and ($query['employee_id'] > 0)){
				$db->setQuery("Select id, employee_name from #__app_sch_employee where id = '".$query['employee_id']."'");
				$employee = $db->loadObject();
				$segments[] = JText::_('OS_EMPLOYEE').": ".$employee->employee_name;
				unset($query['employee_id']);
			}
			if(isset($query['date_from']) and ($query['date_from'] != "")){
				$segments[] = $query['date_from'];
				unset($query['date_from']);
			}
			if(isset($query['date_to']) and ($query['date_to'] != "")){
				$segments[] = $query['date_to'];
				unset($query['date_to']);
			}
			if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
				unset($query['Itemid']);
			}
			unset($query['date_from']);
			unset($query['date_to']);
		break;
		case "form_step2":
			$segments[] = JText::_('OS_CONFIRM');
			if(isset($query['category_id']) and ($query['category_id'] > 0)){
				$db->setQuery("Select * from #__app_sch_categories where id = '".$query['category_id']."'");
				$category = $db->loadObject();
				$segments[] = JText::_('OS_CATEGORY').": ".OSBHelper::getLanguageFieldValue($category,'category_name');
				unset($query['category_id']);
			}
			if(isset($query['vid']) and ($query['vid'] > 0)){
				$db->setQuery("Select * from #__app_sch_venues where id = '".$query['vid']."'");
				$venue = $db->loadObject();
				$segments[] = JText::_('OS_VENUE').": ".OSBHelper::getLanguageFieldValue($venue,'venue');
				unset($query['vid']);
			}
			if(isset($query['sid']) and ($query['sid'] > 0)){
				$db->setQuery("Select * from #__app_sch_services where id = '".$query['sid']."'");
				$service = $db->loadObject();
				$segments[] = JText::_('OS_SERVICE').": ".OSBHelper::getLanguageFieldValue($service,'service_name');
				unset($query['sid']);
			}
			if(isset($query['employee_id']) and ($query['employee_id'] > 0)){
				$db->setQuery("Select id, employee_name from #__app_sch_employee where id = '".$query['employee_id']."'");
				$employee = $db->loadObject();
				$segments[] = JText::_('OS_EMPLOYEE').": ".$employee->employee_name;
				unset($query['employee_id']);
			}
			if(isset($query['date_from']) and ($query['date_from'] > 0)){
				$segments[] = $query['date_from'];
				unset($query['date_from']);
			}
			if(isset($query['date_to']) and ($query['date_to'] > 0)){
				$segments[] = $query['date_to'];
				unset($query['date_to']);
			}
			if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
				unset($query['Itemid']);
			}
		break;
		case "default_customer":
			if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
				$segments[] = JText::_('OS_MY_ORDERS_HISTORY');
				unset($query['Itemid']);
			}
		break;
		case "default_employeeworks":
			if (!isset($query['Itemid']) or ($query['Itemid'] == 0) or ($query['Itemid'] == 99999) or ($query['Itemid'] == 9999)){
				$segments[] = JText::_('OS_MY_WORKKING_LIST');
				unset($query['Itemid']);
			}
		break;
	}
	
	if (isset($query['start']) || isset($query['limitstart']))
	{
		$limit = $app->getUserState('limit');
		$limitStart = isset($query['limitstart']) ? (int)$query['limitstart'] : (int)$query['start'];
		$page = ceil(($limitStart + 1) / $limit);
		$segments[] = JText::_('OS_PAGE').'-'.$page;
	}

	if (isset($query['task']))
		unset($query['task']);
	
	if (isset($query['view']))
		unset($query['view']);
	
	if (isset($query['id']))
		unset($query['id']);
	
	if (isset($query['category_id']))
		unset($query['category_id']);
		
	if (isset($query['employee_id']))
		unset($query['employee_id']);
		
	if (isset($query['sid']))
		unset($query['sid']);
		
	if (isset($query['vid']))
		unset($query['vid']);
	
	if (isset($query['layout']))
		unset($query['layout']);
	
	if (count($segments))
	{
		$segments = array_map('JApplication::stringURLSafe', $segments);
		$key = md5(implode('/', $segments));
		$q = $db->getQuery(true);
		$q->select('COUNT(*)')
			->from('#__app_sch_urls')
			->where('md5_key="'.$key.'"');
		$db->setQuery($q);
		$total = $db->loadResult();
		if (!$total)
		{
			$q->clear();
			$q->insert('#__app_sch_urls')
				->columns('md5_key, `query`')
				->values("'$key', '$queryString'");
			$db->setQuery($q);
			$db->query();
		}
	}
		
	return $segments;
}

/**
 * 
 * Parse the segments of a URL.
 * @param	array	The segments of the URL to parse.
 * @return	array	The URL attributes to be used by the application.
 * @since	1.5
 */
function osservicesbookingParseRoute($segments)
{		
	$vars = array();
	if (count($segments))
	{
		$db = JFactory::getDbo();
		$key = md5(str_replace(':', '-', implode('/', $segments)));
		$query = $db->getQuery(true);
		$query->select('`query`')
			->from('#__app_sch_urls')
			->where('md5_key="'.$key.'"');
		$db->setQuery($query);
		$queryString = $db->loadResult();
		if ($queryString)
			parse_str($queryString, $vars);
	}
	
	$app		= JFactory::getApplication();
	$menu		= $app->getMenu();
	if ($item = $menu->getActive())
	{
		foreach ($item->query as $key=>$value)
		{
			if ($key != 'option' && $key != 'Itemid' && !isset($vars[$key]))
				$vars[$key] = $value;
		}
	}
	return $vars;
}