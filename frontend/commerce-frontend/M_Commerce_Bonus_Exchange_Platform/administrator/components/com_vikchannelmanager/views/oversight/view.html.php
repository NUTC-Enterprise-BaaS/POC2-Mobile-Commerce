<?php
/**------------------------------------------------------------------------
 * com_vikbooking - VikBooking
 * ------------------------------------------------------------------------
 * author    Alessio Gaggii - e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') OR die('Restricted Area');

jimport('joomla.application.component.view');

class VikChannelManagerViewOversight extends JViewLegacy {
    function display($tpl = null) {
        require_once(JPATH_SITE . DS ."components". DS ."com_vikbooking". DS . "helpers" . DS ."lib.vikbooking.php");
        
        $lang = JFactory::getLanguage();
        $lang->load('com_vikbooking', JPATH_ADMINISTRATOR, $lang->getTag(), true);
        
        $this->addToolBar();
        
        VCM::load_css_js();
        VCM::loadDatePicker();
        
        $max_days = 60;
        $max_days_to_display = 14;
        $this->assignRef('maxDays', $max_days);
        $this->assignRef('maxDaysToDisplay', $max_days_to_display);
        
        $dbo = JFactory::getDBO();
        $pmonth = JRequest::getString('month', '', 'request');
        $datepicker = JRequest::getString('datepicker', '', 'request');
        $oldest_checkin = 0;
        $furthest_checkout = 0;
        $q = "SELECT `checkin` FROM `#__vikbooking_busy` ORDER BY `checkin` ASC LIMIT 1;";
        $dbo->setQuery($q);
        $dbo->Query($q);
        if( $dbo->getNumRows() > 0 ) {
            $oldest_arr = $dbo->loadAssocList();
            $oldest_checkin = $oldest_arr[0]['checkin'];
        }
        
        $q = "SELECT `checkout` FROM `#__vikbooking_busy` ORDER BY `checkout` DESC LIMIT 1;";
        $dbo->setQuery($q);
        $dbo->Query($q);
        if( $dbo->getNumRows() > 0 ) {
            $furthest_arr = $dbo->loadAssocList();
            $furthest_checkout = $furthest_arr[0]['checkout'];
        }

        $session = JFactory::getSession();
        
        if( !empty($datepicker) ) {
            $tsstart = VikChannelManager::createTimestamp($datepicker, 0, 0);
            $oggid = getdate();
            if( $tsstart == mktime(0, 0, 0, $oggid['mon'], 1, $oggid['year']) ){
                $tsstart = mktime(0, 0, 0, $oggid['mon'], $oggid['mday'], $oggid['year']);
            }
        } else {
            if( !empty($pmonth) ) {
                $tsstart = $pmonth;
            } else {
                $tsstart = $session->get('vcm-datepicker', '', 'oversight');
            }
            if( empty($tsstart) ) {
                $oggid = getdate();
                $tsstart = mktime(0, 0, 0, $oggid['mon'], $oggid['mday'], $oggid['year']);
            }
        }
        
        $session->set('vcm-datepicker', $tsstart, 'oversight');
        
        $oggid = getdate($tsstart);
        $nextmon = $oggid['mon']+round($max_days/30);
        $year = $oggid['year'];
        if( $nextmon > 12 ) {
            $nextmon -= 12;
            $year++;
        }
        /*
        if( $oggid['mon'] == 12 ) {
            $nextmon = 1;
            $year = $oggid['year']+1;
        } else {
            $nextmon = $oggid['mon']+1;
            $year = $oggid['year'];
        }
        */
        
        $tsend = mktime(0, 0, 0, $oggid['mon'], $oggid['mday']+$max_days, $oggid['year']);
        $today = getdate();
        $firstmonth = mktime(0, 0, 0, $today['mon'], 1, $today['year']);
        $wmonthsel = "<select name=\"month\" onchange=\"document.vboverview.submit();\">\n";
        if( !empty($oldest_checkin) ) {
            $oldest_date = getdate($oldest_checkin);
            $oldest_month = mktime(0, 0, 0, $oldest_date['mon'], 1, $oldest_date['year']);
            if( $oldest_month < $firstmonth ) {
                while( $oldest_month < $firstmonth ) {
                    //$wmonthsel .= "<option value=\"".$oldest_month."\"".($oldest_month==$tsstart ? " selected=\"selected\"" : "").">".vikbooking::sayMonth($oldest_date['mon'])." ".$oldest_date['year']."</option>\n";
                    $wmonthsel .= "<option value=\"".$oldest_month."\"".($oldest_date['mon']==$oggid['mon'] && $oldest_date['year']==$oggid['year'] ? " selected=\"selected\"" : "").">".vikbooking::sayMonth($oldest_date['mon'])." ".$oldest_date['year']."</option>\n";
                    if( $oldest_date['mon'] == 12 ) {
                        $nextmon = 1;
                        $year = $oldest_date['year']+1;
                    } else {
                        $nextmon = $oldest_date['mon']+1;
                        $year = $oldest_date['year'];
                    }
                    $oldest_month = mktime(0, 0, 0, $nextmon, 1, $year);
                    $oldest_date = getdate($oldest_month);
                }
            }
        }
        //$wmonthsel .= "<option value=\"".$firstmonth."\"".($firstmonth==$tsstart ? " selected=\"selected\"" : "").">".vikbooking::sayMonth($today['mon'])." ".$today['year']."</option>\n";
        $wmonthsel .= "<option value=\"".$firstmonth."\"".($today['mon']==$oggid['mon'] && $today['year']==$oggid['year'] ? " selected=\"selected\"" : "").">".vikbooking::sayMonth($today['mon'])." ".$today['year']."</option>\n";
        $futuremonths = 12;
        if( !empty($furthest_checkout) ) {
            $furthest_date = getdate($furthest_checkout);
            $furthest_month = mktime(0, 0, 0, $furthest_date['mon'], 1, $furthest_date['year']);
            if( $furthest_month > $firstmonth ) {
                $monthsdiff = floor(($furthest_month - $firstmonth) / (86400 * 30));
                $futuremonths = $monthsdiff > $futuremonths ? $monthsdiff : $futuremonths;
            }
        }
        
        for( $i = 1; $i <= $futuremonths; $i++ ) {
            $newts = getdate($firstmonth);
            if( $newts['mon'] == 12 ) {
                $nextmon = 1;
                $year = $newts['year']+1;
            } else {
                $nextmon = $newts['mon'] + 1;
                $year = $newts['year'];
            }
            $firstmonth = mktime(0, 0, 0, $nextmon, 1, $year);
            $newts = getdate($firstmonth);
            //$wmonthsel .= "<option value=\"".$firstmonth."\"".($firstmonth==$tsstart ? " selected=\"selected\"" : "").">".vikbooking::sayMonth($newts['mon'])." ".$newts['year']."</option>\n";
            $wmonthsel .= "<option value=\"".$firstmonth."\"".($newts['mon']==$oggid['mon'] && $newts['year']==$oggid['year'] ? " selected=\"selected\"" : "").">".vikbooking::sayMonth($newts['mon'])." ".$newts['year']."</option>\n";
        }
        $wmonthsel .= "</select>\n";
        $mainframe = JFactory::getApplication();
        $lim = $mainframe->getUserStateFromRequest("com_vikchannelmanager.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
        $lim0 = JRequest::getVar('limitstart', 0, '', 'int');
        $q = "SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_rooms` ORDER BY `#__vikbooking_rooms`.`name` ASC";
        $dbo->setQuery($q, $lim0, $lim);
        $dbo->Query($q);
        if( $dbo->getNumRows() > 0 ) {
            $rows = $dbo->loadAssocList();
            $dbo->setQuery('SELECT FOUND_ROWS();');
            jimport('joomla.html.pagination');
            $pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
            $navbut = "<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
            $arrbusy = array();
            $actnow = time();
            foreach( $rows as $r ) {
                $q = "SELECT `b`.*,`ob`.`idorder` FROM `#__vikbooking_busy` AS `b`,`#__vikbooking_ordersbusy` AS `ob` WHERE `b`.`idroom`='".$r['id']."' AND `b`.`id`=`ob`.`idbusy` AND (`b`.`checkin`>=".$tsstart." OR `b`.`checkout`>=".$tsstart.") AND (`b`.`checkin`<=".$tsend." OR `b`.`checkout`<=".$tsstart.");";
                $dbo->setQuery($q);
                $dbo->Query($q);
                $cbusy = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
                $arrbusy[$r['id']] = $cbusy;
            }
        } else {
            $rows = array();
        }

        //Availability Comparison Request allowed and start date
        $acmp_rq_enabled = 0;
        $acmp_rq_start = date('Y-m-d', $tsstart);
        if($tsstart >= mktime(0, 0, 0, date('n'), date('j'), date('Y'))) {
            $q = "SELECT `id` FROM `#__vikchannelmanager_channel` WHERE `av_enabled`=1;";
            $dbo->setQuery($q);
            $dbo->Query($q);
            if( $dbo->getNumRows() > 0 ) {
                $acmp_rq_enabled = 1;
            }
        }
        $acmp_last_request = '';
        $sess_acmp = $session->get('vcmExecAcmpRs', '');
        if (!empty($sess_acmp) && @is_array($sess_acmp)) {
            $acmp_last_request = $sess_acmp['fromdate'];
        }
        //

        $this->assignRef('rows', $rows);
        $this->assignRef('arrbusy', $arrbusy);
        $this->assignRef('wmonthsel', $wmonthsel);
        $this->assignRef('tsstart', $tsstart);
        $this->assignRef('acmp_rq_enabled', $acmp_rq_enabled);
        $this->assignRef('acmp_rq_start', $acmp_rq_start);
        $this->assignRef('acmp_last_request', $acmp_last_request);
        $this->assignRef('lim0', $lim0);
        $this->assignRef('navbut', $navbut);
        
        parent::display($tpl);
    }
    
    /**
     * Setting the toolbar
     */
    protected function addToolBar() {
        //Add menu title and some buttons to the page
        JToolBarHelper::title(JText::_('VCMMAINTOVERVIEW'), 'vikchannelmanager');
        
        JToolBarHelper::save('confirmcustoma', JText::_('VCMSAVECUSTA'));
        JToolBarHelper::spacer();
        JToolBarHelper::cancel( 'cancel', JText::_('BACK'));
        
    }

}

?>