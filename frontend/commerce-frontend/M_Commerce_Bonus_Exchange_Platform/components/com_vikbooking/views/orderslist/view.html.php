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

class VikbookingViewOrderslist extends JViewLegacy {
	function display($tpl = null) {
		vikbooking::prepareViewContent();
		$islogged = vikbooking::userIsLogged();
		$cpin = vikbooking::getCPinIstance();
		$pconfirmnumber = JRequest::getString('confirmnumber', '', 'request');
		$dbo = JFactory::getDBO();
		if (!empty($pconfirmnumber)) {
			$q = "SELECT `id`,`ts`,`sid` FROM `#__vikbooking_orders` WHERE `confirmnumber`=".$dbo->quote($pconfirmnumber).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$odata = $dbo->loadAssocList();
				$mainframe = JFactory::getApplication();
				$mainframe->redirect(JRoute::_('index.php?option=com_vikbooking&task=vieworder&sid='.$odata[0]['sid'].'&ts='.$odata[0]['ts'], false));
				exit;
			}else {
				if($cpin->pinExists($pconfirmnumber)) {
					$cpin->setNewPin($pconfirmnumber);
				}else {
					JError::raiseWarning('', JText::_('VBINVALIDCONFIRMNUMBER'));
				}
			}
		}
		$customer_details = $cpin->loadCustomerDetails();
		$userorders = '';
		$navig = '';
		if ($islogged || count($customer_details) > 0) {
			$currentUser = JFactory::getUser();
			$lim=10;
			$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
			$q = "SELECT SQL_CALC_FOUND_ROWS `o`.*,`co`.`idcustomer` FROM `#__vikbooking_orders` AS `o` LEFT JOIN `#__vikbooking_customers_orders` `co` ON `co`.`idorder`=`o`.`id` WHERE ".($islogged ? "`o`.`ujid`='".$currentUser->id."'".(count($customer_details) > 0 ? " OR " : "") : "").(count($customer_details) > 0 ? "`co`.`idcustomer`=".(int)$customer_details['id'] : "")." ORDER BY `o`.`checkin` DESC";
			$dbo->setQuery($q, $lim0, $lim);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$userorders = $dbo->loadAssocList();
				$dbo->setQuery('SELECT FOUND_ROWS();');
				jimport('joomla.html.pagination');
				$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
				$navig = $pageNav->getPagesLinks();
			}
		}
		$this->assignRef('userorders', $userorders);
		$this->assignRef('customer_details', $customer_details);
		$this->assignRef('navig', $navig);
		//theme
		$theme = vikbooking::getTheme();
		if($theme != 'default') {
			$thdir = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'themes'.DS.$theme.DS.'orderslist';
			if(is_dir($thdir)) {
				$this->_setPath('template', $thdir.DS);
			}
		}
		//
		parent::display($tpl);
	}
}


?>