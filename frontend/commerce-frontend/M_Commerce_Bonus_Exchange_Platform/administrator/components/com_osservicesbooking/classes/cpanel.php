<?php
/*------------------------------------------------------------------------
# cpanel.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;

class OSappscheduleCpanel{
	/**
	 * Default function
	 *
	 * @param unknown_type $option
	 */
	function display($option,$task){
		switch ($task){
			case "cpanel_optimizedatabase":
				OSappscheduleCpanel::optimizeDatabase();
			break;
			case "cpanel_list":
			default:
				OSappscheduleCpanel::cpanel_list($option);
			break;
		}
	}
	
	/**
	 * Zend lib checking
	 *
	 */
	function zendChecking(){
		global $mainframe,$configClass;
		jimport('joomla.filesystem.folder');
		$error = "";
		if($configClass['integrate_gcalendar'] == 1){
			if(!JFolder::exists(JPATH_COMPONENT_SITE.DS."google-api-php-client-master")){
				$error = "Please install Google API library. The destination directory of Google API library lib is: [".JPATH_COMPONENT_SITE."]. You can download it from <a href='https://github.com/google/google-api-php-client' target='_blank'>here</a>";
			}
		}
		
		if($error != ""){
			?>
			<div class="row-fluid">
				<div class="span12 label label-important" style="padding-top:5px;">
					<?php echo $error?>
				</div>
			</div>
			<?php
		}
	}
	
	/**
	 * Database optimization
	 *
	 */
	function optimizeDatabase(){
		global $mainframe;
		$db = JFactory::getDbo();
		$dbtable = array('#__app_sch_temp_orders','#__app_sch_temp_order_field_options','#__app_sch_temp_order_items','#__app_sch_temp_temp_order_field_options','#__app_sch_temp_temp_order_items');
		for($i=0;$i<count($dbtable);$i++){
			$table = $dbtable[$i];
			$db->setQuery("Delete from `".$table."`");
			$db->query();
		}
		$msg = JText::_('OS_DATABASE_OPTIMIZATION_SUCESSFULLY');
		$mainframe->redirect("index.php?option=com_osservicesbooking&task=cpanel_list",$msg);
	}
	
	/**
	 * Control panel
	 *
	 * @param unknown_type $option
	 */
	function cpanel_list($option){
		global $mainframe,$configClass;
		$db 			= JFactory::getDbo();
		$config 		= new JConfig();
		$offset 		= $config->offset;
		$current_date 	= JFactory::getDate('now',$offset);
		$cdate_int		= strtotime($current_date);
		
		//today
		$return			= OSBHelper::checkDate('today');
		$start_time		= date("Y-m-d H:i:s",$return[0]);
		$end_time		= date("Y-m-d H:i:s",$return[1]);
		$db->setQuery("SELECT SUM(order_total) FROM #__app_sch_orders WHERE order_status in ('S') AND order_date > '$start_time' AND order_date < '$end_time'");
		$lists['today'] = ($db->loadResult() > 0 ? $db->loadResult():0);
		//yesterday
		$return			= OSBHelper::checkDate('yesterday');
		$start_time		= date("Y-m-d H:i:s",$return[0]);
		$end_time		= date("Y-m-d H:i:s",$return[1]);
		$db->setQuery("SELECT SUM(order_total) FROM #__app_sch_orders WHERE order_status in ('S') AND order_date > '$start_time' AND order_date < '$end_time'");
		$lists['yesterday'] = ($db->loadResult() > 0 ? $db->loadResult():0);
		//this month
		$return			= OSBHelper::checkDate('current_month');
		$start_time		= date("Y-m-d H:i:s",$return[0]);
		$end_time		= date("Y-m-d H:i:s",$return[1]);
		$db->setQuery("SELECT SUM(order_total) FROM #__app_sch_orders WHERE order_status in ('S') AND order_date > '$start_time' AND order_date < '$end_time'");
		
		$lists['current_month'] = ($db->loadResult() > 0 ? $db->loadResult():0);
		//last month
		$return			= OSBHelper::checkDate('last_month');
		$start_time		= date("Y-m-d H:i:s",$return[0]);
		$end_time		= date("Y-m-d H:i:s",$return[1]);
		$db->setQuery("SELECT SUM(order_total) FROM #__app_sch_orders WHERE order_status in ('S') AND order_date > '$start_time' AND order_date < '$end_time'");
		
		$lists['last_month'] = ($db->loadResult() > 0 ? $db->loadResult():0);
		//current year
		$return			= OSBHelper::checkDate('current_year');
		$start_time		= date("Y-m-d H:i:s",$return[0]);
		$end_time		= date("Y-m-d H:i:s",$return[1]);
		$db->setQuery("SELECT SUM(order_total) FROM #__app_sch_orders WHERE order_status in ('S') AND order_date > '$start_time' AND order_date < '$end_time'");
		$lists['current_year'] = ($db->loadResult() > 0 ? $db->loadResult():0);
		//last year
		$return			= OSBHelper::checkDate('last_year');
		$start_time		= date("Y-m-d H:i:s",$return[0]);
		$end_time		= date("Y-m-d H:i:s",$return[1]);
		$db->setQuery("SELECT SUM(order_total) FROM #__app_sch_orders WHERE order_status in ('S') AND order_date > '$start_time' AND order_date < '$end_time'");
		$lists['last_year'] = ($db->loadResult() > 0 ? $db->loadResult():0);
		
		
		$db->setQuery("Select id as value, service_name as text from #__app_sch_services where published = '1' order by service_name");
		$services = $db->loadObjectList();
		$serviceArr = array();
		$serviceArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_SERVICE'));
		$serviceArr   = array_merge($serviceArr,$services);
		$lists['services'] = JHTML::_('select.genericlist',$serviceArr,'sid','class="input-large"','value','text');
		
		$db->setQuery("Select id as value, employee_name as text from #__app_sch_employee where published = '1' order by employee_name");
		$employees = $db->loadObjectList();
		$employeeArr = array();
		$employeeArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_EMPLOYEES'));
		$employeeArr = array_merge($employeeArr,$employees);
		$lists['employee'] = JHTML::_('select.genericlist',$employeeArr,'eid','class="input-large"','value','text');
		
		$options = array();
		$options[]					= JHtml::_('select.option','',JText::_('OS_FILTER_STATUS'));
		$options[]					= JHtml::_('select.option','P',JText::_('OS_PENDING'));
		$options[]					= JHtml::_('select.option','S',JText::_('OS_COMPLETE'));
		$options[]					= JHtml::_('select.option','C',JText::_('OS_CANCEL'));
		//$lists['order_status']		= JHtml::_('select.genericlist',$options,'order_status','class="input-small"','value','text');
		$lists['order_status']		= OSBHelper::buildOrderStaticDropdownList('','',JText::_('OS_FILTER_STATUS'),'order_status');
		HTML_OSappscheduleCpanel::showControlpanel($lists);
	}
	
	/**
	 * Creates the buttons view.
	 * @param string $link targeturl
	 * @param string $image path to image
	 * @param string $text image description
	 * @param boolean $modal 1 for loading in modal
	 */
	function quickiconButton($link, $image, $text, $modal = 0)
	{
		//initialise variables
		$lang 		= &JFactory::getLanguage();

		if($link == ""){
			$div_id = "id = 'oschecking_div'";
		}
  		?>

		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;" <?php echo $div_id; ?>>
			<div class="icon">
				<?php
				if ($modal == 1) {
					JHTML::_('behavior.modal');
				?>
					<a href="<?php echo $link.'&amp;tmpl=component'; ?>" style="cursor:pointer" class="modal" rel="{handler: 'iframe', size: {x: 650, y: 400}}">
				<?php
				} else {
				?>
					<a href="<?php echo $link; ?>">
				<?php
				}
					echo JHTML::_('image', 'administrator/components/com_osservicesbooking/asset/images/' . $image, $text);
				?>
					<span><?php echo $text; ?></span>
				</a>
			</div>
		</div>
		<?php
	}

    /**
     * Get month Report
     * @param $current_month_offset
     * @param $before
     * @param $after
     */
    public static function getMonthlyReport($current_month_offset, $before, $after){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__app_sch_orders')
            ->where('order_status = "S"')
            ->where('order_date <= "'.$before.'"')
            ->where('order_date >= "'.$after.'"')
            ->order('order_date DESC');
        $db->setQuery($query);
        $data = $db->loadObjectList();
        return $data;
    }
}
?>