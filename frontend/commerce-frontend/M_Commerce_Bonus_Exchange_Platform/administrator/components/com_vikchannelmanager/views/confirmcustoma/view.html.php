<?php
/**------------------------------------------------------------------------
 * com_vikchannelmanager - VikChannelManager
 * ------------------------------------------------------------------------
 * author    e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class VikChannelManagerViewconfirmcustoma extends JViewLegacy {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();
		
		VCM::load_css_js();
		
		$dbo = JFactory::getDBO();
        
		$cust_a_req = JRequest::getVar('cust_av', array());
        
        foreach( $cust_a_req as $i => $c ) {
            list($ts, $day, $month, $year, $idroom, $units, $vbounits) = explode('-', $c);
            $ts = mktime(0, 0, 0, $month, $day, $year);
            $cust_a_req[$i] = "$idroom-$ts-$day-$month-$year-$units-$vbounits";
        }
        
        if(!(count($cust_a_req) > 0)) {
            JError::raiseWarning('', JText::_('VCMNOCUSTOMAMODS'));
            $mainframe = JFactory::getApplication();
            $mainframe->redirect('index.php?option=com_vikchannelmanager&task=oversight');
            exit;
        }

        sort($cust_a_req);
        
        $cust_a = array();
        //$last_index_used = -1;
        $last_details = array();
        foreach( $cust_a_req as $i => $c ) {
            list($idroom, $ts, $day, $month, $year, $units, $vbounits) = explode('-', $c);
            
            if( empty($cust_a[$idroom]) ) {
                $cust_a[$idroom]['details'] = array();
                $cust_a[$idroom]['channels'] = array();
            }
            
            $details = array(
                'day' => $day,
                'month' => $month,
                'year' => $year,
                'fromts' => $ts,
                'endts' => 0, 
                'units' => $units,
                'vbounits' => $vbounits
            );
            
            $last_index_used = count($cust_a[$idroom]['details'])-1;
            
            if( 
                $last_index_used != -1 && 
                $cust_a[$idroom]['details'][$last_index_used]['units'] == $details['units'] &&
                $this->getNextDayTimestamp($last_details) == $details['fromts'] ) {
                
                $cust_a[$idroom]['details'][$last_index_used]['endts'] = $details['fromts'];
            } else {
                array_push( $cust_a[$idroom]['details'], $details );
            }
            
            $last_details = $details;
            
        }

        $rooms_xref = array();
        
        $q = "SELECT `r`.*, `c`.`name` AS `chname`, `c`.`uniquekey`, `b`.`name` AS `roomname` 
        FROM `#__vikchannelmanager_roomsxref` AS `r`, `#__vikchannelmanager_channel` AS `c`, `#__vikbooking_rooms` AS `b` 
        WHERE `b`.`id`=`r`.`idroomvb` AND `r`.`idchannel`=`c`.`uniquekey` AND `c`.`av_enabled`=1 GROUP BY `r`.`idroomvb`, `r`.`idchannel`;";
        $dbo->setQuery($q);
        $dbo->Query($q);
        if( $dbo->getNumRows() > 0 ) {
            $rooms_xref = $dbo->loadAssocList();
        }
        
        foreach( $cust_a as $idroom => $v ) {
            for( $j = 0; $j < count($rooms_xref); $j++ ) {
                if( $rooms_xref[$j]['idroomvb'] == $idroom ) {
                    $channel = array( 'name' => $rooms_xref[$j]['chname'], 'uniquekey' => $rooms_xref[$j]['uniquekey']);
                    array_push( $cust_a[$idroom]['channels'], $channel);
                    
                    $cust_a[$idroom]['rname'] = $rooms_xref[$j]['roomname'];
                }
            }
            
            if( empty($cust_a[$idroom]['rname']) ) {
                $q = "SELECT `name` FROM `#__vikbooking_rooms` WHERE `id`=$idroom LIMIT 1;";
                $dbo->setQuery($q);
                $dbo->Query($q);
                if( $dbo->getNumRows() > 0 ) {
                    $cust_a[$idroom]['rname'] = $dbo->loadResult();
                } else {
                    $cust_a[$idroom]['rname'] = JText::_('VCMOSUNDEFINEDROOM');
                }
                $cust_a[$idroom]['rdesc'] = JText::_('VCMOSUNDEFINEDDESC');
            }
            
        }

        $this->assignRef('cust_a', $cust_a);
		
		// Display the template (default.php)
		parent::display($tpl);
		
	}

    protected function getNextDayTimestamp($details) {
        return mktime(0, 0, 0, $details['month'], $details['day']+1, $details['year']);
    } 

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() {
		//Add menu title and some buttons to the page
		JToolBarHelper :: title(JText::_('VCMMAINTCONFIRMCUSTOMA'), 'vikchannelmanager');
        
        JToolbarHelper::save('sendCustomAvailabilityRequest', JText::_('VCMAPPLYCUSTAV'));
        JToolBarHelper::spacer();
		JToolBarHelper::cancel( 'canceloversight', JText::_('CANCEL'));
		
	}
}
?>