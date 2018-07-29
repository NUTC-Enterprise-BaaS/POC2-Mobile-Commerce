<?php
/*------------------------------------------------------------------------
# calendar.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2012 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class HTML_OsAppscheduleCalendar{
	function customerCalendar(){
        global $configClass;
		JHTML::_('behavior.tooltip');
		?>
		<table width="100%">
			<tr>
				<td width="50%">
					<div style="font-size:15px;font-weight:bold;">
						<?php echo JText::_('OS_MY_WORKKING_LIST');?>
					</div>
				</td>
				<td	width="50%" align="right">
					<input type="button" class="btn btn-success" value="<?php echo JText::_('OS_MY_ORDERS_HISTORY')?>" title="<?php echo JText::_('OS_GO_TO_MY_ORDERS_HISTORY')?>" onclick="javascript:customerorder('<?php echo JURI::root()?>','<?php  echo Jrequest::getVar('Itemid',0)?>')"/>
					<input type="button" class="btn btn-warning" value="<?php echo JText::_('OS_BACK')?>" title="<?php echo JText::_('OS_GO_BACK')?>" onclick="javascript:history.go(-1);"/>
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="2">
					<?php
					$year = JRequest::getVar('year',date("Y",time()));
					$month =  intval(JRequest::getVar('month',date("m",time())));
					OSBHelper::initCustomerCalendar($year,$month);
					?>
				</td>
			</tr>
		</table>
		<?php
		if($configClass['footer_content'] != ""){
			?>
			<div class="osbfootercontent">
				<?php echo $configClass['footer_content'];?>
			</div>
			<?php
		}
		?>
		<?php
	}
	/**
	 * List all the work of employee in calendar
	 *
	 * @param unknown_type $employee
	 */
	function employeeCalendar($employee){
		global $mainframe,$configClass;
		JHTML::_('behavior.tooltip');
		?>
		<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osservicesbooking&task=default_employeeworks&Itemid='.JRequest::getVar('Itemid'))?>" name="ftForm">
		<table width="100%">
			<tr>
				<td width="50%">
					<div style="font-size:15px;font-weight:bold;">
						<?php echo JText::_('OS_MY_WORKKING_LIST');?>
					</div>
				</td>
				<td	width="50%" align="right">
					<?php
					if($configClass['employee_change_availability'] == 1){
						?>
						<input type="button" class="btn btn-info" value="<?php echo JText::_('OS_AVAILABILITY_STATUS')?>" title="<?php echo JText::_('OS_AVAILABILITY_STATUS')?>" onclick="javascript:workingavailabilitystatus('<?php echo JURI::root()?>','<?php  echo Jrequest::getVar('Itemid',0)?>')"/>
						<?php
					}
					?>
					<!--<input type="button" class="btn btn-success" value="<?php echo JText::_('OS_MY_WORKING_CALENDAR')?>" title="<?php echo JText::_('OS_GO_TO_MY_WORKING_CALENDAR')?>" onclick="javascript:workingcalendar('<?php echo JURI::root()?>','<?php  echo Jrequest::getVar('Itemid',0)?>')"/>-->
					<input type="button" class="btn btn-warning" value="<?php echo JText::_('OS_BACK')?>" title="<?php echo JText::_('OS_GO_BACK')?>" onclick="javascript:history.go(-1);"/>
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="2">
					<?php
					$year = JRequest::getVar('year',date("Y",time()));
					$month =  intval(JRequest::getVar('month',date("m",time())));
					OSBHelper::initEmployeeCalendar($employee->id,$year,$month);
					?>
				</td>
			</tr>
		</table>
		<?php
		if($configClass['footer_content'] != ""){
			?>
			<div class="osbfootercontent">
				<?php echo $configClass['footer_content'];?>
			</div>
			<?php
		}
		?>
		</form>
		<?php
	}
	
	
	function workinglistinOneDay($day,$rows){
		global $mainframe,$configClass;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);	
		?>
		<link rel="stylesheet" href="<?php echo JURI::root()?>components/com_osservicesbooking/style/bootstrap/css/bootstrap.css" type="text/css" />
		<strong><?php echo JText::_(OS_DAY)?></strong> &nbsp;<?php echo date($configClass['date_format'],strtotime($day));?>
		<BR /><BR />
		<table class="table table-striped">
			<thead>
				<tr>
                    <th class="success">
                        <?php echo JText::_('OS_CUSTOMER')?>
                    </th>
					<th class="success">
						<?php echo JText::_('OS_SERVICE')?>
					</th>
					<th class="success">
						<?php echo JText::_('OS_FROM')?>
					</th>
					<th class="success">
						<?php echo JText::_('OS_TO')?>
					</th>
					<th class="success">
						<?php echo JText::_('OS_ADDITIONAL_INFORMATION')?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$k = 0;
				for($i=0;$i<count($rows);$i++){
					$row = $rows[$i];
					$data = OSBHelper::generateData($row);
					?>
					<tr class="rows<?php echo $k?>">
                        <td>
                            <?php echo $row->order_name;?>
                        </td>
						<td>
							<?php echo $data[0]->service_name;?>
						</td>
						<td>
							<?php echo date($configClass['time_format'],$data[5]);?>
						</td>
						<td>
							<?php echo date($configClass['time_format'],$data[6]);?>
						</td>
						<td>
							<?php
							if($data[7] > 0){
								echo JText::_('OS_NUMBER_SLOT').": ".$data[7];
								echo "<BR />";
							}
							?>
							<?php echo $data[4];?>
						</td>
					</tr>
					<?php
					$k = 1-$k;
				}
				?>
			</tbody>
		</table>
		<?php
	}
	
	function availabilityCalendar($employee){
        global $configClass;
		?>
		
		<style>
		.header_calendar{
			font-weight:bold;
			text-align:center;
			padding:5px;
			font-size:14px;
		}
		.td_calendar_date{
			font-size:13px;
			text-align:center;
			vertical-align:middle;
			border:1px dotted #CCC !important;
			padding:5px;
			font-weight:bold;
		}
		</style>
		<form method="POST" action="<?php echo JURI::root()?>index.php?option=com_oscalendar" name="adminForm" id="adminForm">
		<table class="admintable" width="100%">
			<tr>
				<td width="30%">
					<div style="font-size:15px;font-weight:bold;">
						<?php echo JText::_('OS_MY_AVAILABILITY_STATUS');?>
					</div>
				</td>
				<td	width="70%" style="text-align:right;" class="hidden-phone">
					<?php
					if($configClass['employee_change_availability'] == 1){
						?>
						<input type="button" class="btn btn-info" value="<?php echo JText::_('OS_AVAILABILITY_STATUS')?>" title="<?php echo JText::_('OS_AVAILABILITY_STATUS')?>" onclick="javascript:workingavailabilitystatus('<?php echo JURI::root()?>','<?php  echo Jrequest::getVar('Itemid',0)?>')"/>
						<?php
					}
					?>
					<input type="button" class="btn btn-success" value="<?php echo JText::_('OS_MY_WORKING_CALENDAR')?>" title="<?php echo JText::_('OS_GO_TO_MY_WORKING_CALENDAR')?>" onclick="javascript:workingcalendar('<?php echo JURI::root()?>','<?php  echo Jrequest::getVar('Itemid',0)?>')"/>
					<?php
					if(($configClass['integrate_gcalendar'] == 1) and (JFolder::exists(JPATH_ROOT.DS."Zend")) and ($employee->gcalendarid != "")){
						?>
						<input type="button" class="btn btn-info" value="<?php echo JText::_('OS_MY_GCALENDAR')?>" title="<?php echo JText::_('OS_MY_GCALENDAR')?>" onclick="javascript:gcalendar('<?php echo JURI::root()?>','<?php  echo Jrequest::getVar('Itemid',0)?>')"/>
						<?php
					}
					?>
					<input type="button" class="btn btn-warning" value="<?php echo JText::_('OS_BACK')?>" title="<?php echo JText::_('OS_GO_BACK')?>" onclick="javascript:history.go(-1);"/>
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="2">
					<?php
					$year = JRequest::getVar('year',date("Y",time()));
					$month =  intval(JRequest::getVar('month',date("m",time())));
					OSBHelper::initCalendarInBackend($employee->id,$year,$month);
					?>
				</td>
			</tr>
		</table>
		<input type="hidden" name="task"    	id="task" 	value=""/>
		<input type="hidden" name="option"  	id="option" value="com_osservicesbooking"/>
		<input type="hidden" name="boxchecked"				value="0" />
		<input type="hidden" name="year"    	id="year" 	value="<?php echo $year;?>" />
		<input type="hidden" name="month"   	id="month" 	value="<?php echo $month;?>" />
		<input type="hidden" name="live_site"   id="live_site" value="<?php echo JURI::root()?>" />
		</form>
		<?php
	}
}
?>