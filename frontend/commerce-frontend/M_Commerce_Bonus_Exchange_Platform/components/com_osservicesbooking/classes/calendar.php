<?php
/*------------------------------------------------------------------------
# calendar.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2012 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class OsAppscheduleCalendar{
	/**
	 * Osproperty default
	 *
	 * @param unknown_type $option
	 */
	function display($option,$task){
		global $mainframe;
		$document = JFactory::getDocument();
		switch ($task){
			case "calendar_employee":
				OsAppscheduleCalendar::employee($option);
			break;
			case "calendar_customer":
				OsAppscheduleCalendar::customer($option);
			break;
			case "calendar_dateinfo":
				OsAppscheduleCalendar::dateinfo($option);
			break;
			case "calendar_gcalendar":
				OsAppscheduleCalendar::gCalendar($option);
			break;
			case "calendar_availability":
				OsAppscheduleCalendar::availability($option);
			break;
		}
	}
	
	function customer($option){
		global $mainframe,$configClass;
		$user = JFactory::getUser();
		$db   = JFactory::getDbo();
		if(intval($user->id) == 0){
			$mainframe->redirect(JURI::root()."index.php",JText::_('OS_YOU_DONT_HAVE_PERMISSION_TO_GO_TO_THIS_AREA'));
		}
		HTML_OsAppscheduleCalendar::customerCalendar();
	}
	
	/**
	 * Working calendar of Employee
	 *
	 * @param unknown_type $option
	 */
	function employee($option){
		global $mainframe,$configClass;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		$user = JFactory::getUser();
		$db   = JFactory::getDbo();
		if(intval($user->id) == 0){
			$mainframe->redirect(JURI::root()."index.php",JText::_('OS_YOU_DONT_HAVE_PERMISSION_TO_GO_TO_THIS_AREA'));
		}
		$db->setQuery("SELECT COUNT(id) FROM #__app_sch_employee WHERE user_id = '$user->id' AND published = '1'");
		$count  = $db->loadResult();
		if($count == 0){
			$mainframe->redirect(JURI::root()."index.php",JText::_('OS_YOU_DONT_HAVE_PERMISSION_TO_GO_TO_THIS_AREA'));
		}else{
			$db->setQuery("SELECT id FROM #__app_sch_employee WHERE user_id = '$user->id' AND published = '1'");
			$eid = $db->loadResult();
		}
		$employee = Jtable::getInstance('Employee','OsAppTable');
		$employee->load((int)$eid);
		HTML_OsAppscheduleCalendar::employeeCalendar($employee);
	}
	
	
	function dateinfo($option){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$day = Jrequest::getVar('date','');
		$user = JFactory::getUser();
		$db->setQuery("Select id from #__app_sch_employee where user_id = '$user->id'");
		$eid = $db->loadResult();
		if($eid > 0){
			$db->setQuery("SELECT a.*,c.service_name,b.order_name FROM #__app_sch_order_items AS a INNER JOIN #__app_sch_orders AS b ON b.id = a.order_id INNER JOIN #__app_sch_services AS c ON c.id = a.sid WHERE a.eid = '$eid' AND b.order_status IN ('P','S') AND a.booking_date = '$day'");
			$rows = $db->loadObjectList();
			HTML_OsAppscheduleCalendar::workinglistinOneDay($day,$rows);
		}
		exit();
	}
	
	/**
	 * Google Calendar
	 *
	 * @param unknown_type $option
	 */
	function gCalendar($option){
		global $mainframe,$configClass;
		if(!HelperOSappscheduleCommon::checkEmployee()){
			$mainframe->redirect(JURI::root()."index.php",JText::_('OS_YOU_DONT_HAVE_PERMISSION_TO_GO_TO_THIS_AREA'));
		}
		$eid = HelperOSappscheduleCommon::getEmployeeId();
		$db  = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_employee where id = '$eid'");
		$employee = $db->loadObject();
		$config = new JConfig();
		$offset = $config->offset;
		if($employee->gcalendarid != ""){
		?>
		<table width="100%">
			<tr>
				<td width="50%" align="left">
					<div style="font-size:15px;font-weight:bold;">
						<?php echo JText::_('OS_MY_GCALENDAR');?>
					</div>
				</td>
				<td	width="50%" style="text-align:right;">
					<input type="button" class="btn btn-warning" value="<?php echo JText::_('OS_BACK')?>" title="<?php echo JText::_('OS_GO_BACK')?>" onclick="javascript:history.go(-1);"/>
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="2">
					<iframe src="https://www.google.com/calendar/embed?src=<?php echo $employee->gcalendarid?>&ctz=<?php echo $offset;?>" style="border: 0" width="<?php echo $configClass['gcalendar_width']?>" height="<?php echo $configClass['gcalendar_height']?>" frameborder="0" scrolling="no"></iframe>
				</td>
			</tr>
		</table>
		<?php
		}
	}
	
	function availability($option){
		global $mainframe,$configClass;
		if(!HelperOSappscheduleCommon::checkEmployee()){
			$mainframe->redirect(JURI::root()."index.php",JText::_('OS_YOU_DONT_HAVE_PERMISSION_TO_GO_TO_THIS_AREA'));
		}
		$eid = HelperOSappscheduleCommon::getEmployeeId();
		$db  = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_employee where id = '$eid'");
		$employee = $db->loadObject();
		HTML_OsAppscheduleCalendar::availabilityCalendar($employee);
		
	}
}
?>