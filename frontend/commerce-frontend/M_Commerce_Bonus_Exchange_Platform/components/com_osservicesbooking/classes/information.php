<?php
/*------------------------------------------------------------------------
# information.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2012 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class OSappscheduleInformation{
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
			
		}
	}
	
	/**
	 * Show error header 
	 *
	 * @param unknown_type $sid
	 * @param unknown_type $eid
	 * @param unknown_type $date
	 */
	function showError($sid,$eid,$errorArr,$vid){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_services where id = '$sid'");
		$service = $db->loadObject();
		$db->setQuery("Select * from #__app_sch_employee where id = '$eid'");
		$employee = $db->loadObject();
		
		$inforArr = array();
		for($i=0;$i<count($errorArr);$i++){
			
			$row = $errorArr[$i];
			$date = strtotime($row->booking_date); //return int
			$dateArr = explode("-",$row->booking_date);
			$date = date($configClass['date_format'],$date); //return date informat
			
			$temp_start_hour 	=  intval(date("H",$row->start_time));
			$temp_start_min 	=  intval(date("i",$row->start_time));
			$temp_end_hour 		=  intval(date("H",$row->end_time));
			$temp_end_min 		=  intval(date("i",$row->end_time));
			$optionArr   		=  array();
			$optionArr[] 		=  JHTML::_('select.option',0,JText::_('OS_SELECT_DIFFERENT_SLOTS'));
			for($j=1;$j<=$row->number_slots_available;$j++){
				$optionArr[] 		=  JHTML::_('select.option',$j,$j);	
			}
			$lists['optionArr'] =  JHTML::_('select.genericlist',$optionArr,'nslots_'.$sid.'_'.$eid.'_'.intval($dateArr[2]).'_'.intval($dateArr[1]).'_'.intval($dateArr[0]),'onChange="javascript:updateTempDate('.$sid.','.$eid.','.$row->start_time.','.$row->end_time.','.intval($dateArr[2]).','.intval($dateArr[1]).','.intval($dateArr[0]).')" style="width:180px;" class="inputbox"','value','text');
			
			$inforArr[$i]->id						= $row->id;
			$inforArr[$i]->date 					= $date;
			$inforArr[$i]->temp_start_hour 			= $temp_start_hour;
			$inforArr[$i]->temp_start_min  			= $temp_start_min ;
			$inforArr[$i]->temp_end_hour 			= $temp_end_hour;
			$inforArr[$i]->temp_end_min 			= $temp_end_min;
			$inforArr[$i]->list						= $lists['optionArr'];
			$inforArr[$i]->nslots  		 			= $row->nslots;
			$inforArr[$i]->number_slots_available 	= $row->number_slots_available;
			$inforArr[$i]->start_time				= $row->start_time;
			$inforArr[$i]->end_time					= $row->end_time;
			$inforArr[$i]->return 					= $row->return;
		}
		HTML_OSappscheduleInformation::showErrorHtml($service,$employee,$inforArr,$vid,$dateArr);
	}
}
?>