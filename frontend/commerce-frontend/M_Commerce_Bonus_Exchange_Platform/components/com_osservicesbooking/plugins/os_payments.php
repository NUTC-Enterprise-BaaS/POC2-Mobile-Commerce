<?php
/**
 * @version		1.1.1
 * @package		Joomla
 * @subpackage	OS Services Booking
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2011 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined( '_JEXEC' ) or die ;

class os_payments {	
	/**
	 * Get list of payment methods
	 *
	 * @return array
	 */
	function getPaymentMethods($loadOffline = true, $onlyRecurring = false) {
		static $methods ;
        $user = JFactory::getUser();
		if (!$methods) {
            $methods = array();
			define('JPAYMENT_METHODS_PATH', JPATH_ROOT.DS.'components'.DS.'com_osservicesbooking'.DS.'/plugins'.DS) ;
			$db = & JFactory::getDBO() ;
			if ($loadOffline) {
				$sql = 'SELECT * FROM #__app_sch_plugins WHERE published=1 ' ;
			} else {
				$sql = 'SELECT * FROM #__app_sch_plugins WHERE published=1 AND name != "os_offline" ' ;
			}			
			if ($onlyRecurring) {
				$sql .= " AND support_recurring_subscription = 1 ";
			}			
			$sql .= " ORDER BY ordering " ;
			$db->setQuery($sql) ;
			$rows = $db->loadObjectList();					
			foreach ($rows as $row) {
                //using Prepaid version
                if(($row->name == "os_prepaid") and ($user->id > 0)){
                    if (file_exists(JPAYMENT_METHODS_PATH.$row->name.'.php')) {
                        $db->setQuery("Select count(id) from #__app_sch_user_balance where user_id = '$user->id'");
                        $count = $db->loadResult();
                        if($count > 0){
                            $db->setQuery("Select * from #__app_sch_user_balance where user_id = '$user->id'");
                            $balance = $db->loadObject();
                            $user_balance = "(".JText::_('OS_AVAILABLE_BALANCE').": ".OSBHelper::showMoney($balance->amount,1).")";
                            require_once JPAYMENT_METHODS_PATH.$row->name.'.php';
                            $method = new $row->name(new JRegistry($row->params));
                            $method->title = $row->title." ".$user_balance;
                            $methods[] = $method;
                        }
                    }
                }else {
                    if (file_exists(JPAYMENT_METHODS_PATH . $row->name . '.php')) {
                        require_once JPAYMENT_METHODS_PATH . $row->name . '.php';
                        $method = new $row->name(new JRegistry($row->params));
                        $method->title = $row->title;
                        $methods[] = $method;
                    }
                }
			}
		}
		return $methods ;
	}
	/**
	 * Write the javascript objects to show the page
	 *
	 * @return string
	 */		
	function writeJavascriptObjects() {
		$methods =  os_payments::getPaymentMethods();
		$jsString = " methods = new PaymentMethods();\n" ;			
		if (count($methods)) {
			foreach ($methods as $method) {
				$jsString .= " method = new PaymentMethod('".$method->getName()."',".$method->getCreditCard().",".$method->getCardType().",".$method->getCardCvv().",".$method->getCardHolderName().");\n" ;
				$jsString .= " methods.Add(method);\n";								
			}
		}
		echo $jsString ;
	}
	/**
	 * Load information about the payment method
	 *
	 * @param string $name Name of the payment method
	 */
	function loadPaymentMethod($name)
    {
        $db = &JFactory::getDBO();
        $user = JFactory::getUser();
        if ($name == "os_prepaid") {
            $db->setQuery("Select count(id) from #__app_sch_user_balance where user_id = '$user->id'");
            $count = $db->loadResult();
            if ($count > 0) {
                $db->setQuery("Select * from #__app_sch_user_balance where user_id = '$user->id'");
                $balance = $db->loadObject();
                $user_balance = "(" . JText::_('OS_AVAILABLE_BALANCE') . ": " . OSBHelper::showMoney($balance->amount, 1) . ")";
            }
            $sql = 'SELECT * FROM #__app_sch_plugins WHERE name="' . $name . '"';
            $db->setQuery($sql);
            $method = $db->loadObject();
            $method->title .= " ".$user_balance;
        } else {
            $sql = 'SELECT * FROM #__app_sch_plugins WHERE name="' . $name . '"';
            $db->setQuery($sql);
            $method = $db->loadObject();
        }
		return $method;
	}
	/**
	 * Get default payment gateway
	 *
	 * @return string
	 */
    function getDefautPaymentMethod() {
        $db = & JFactory::getDBO() ;
        $sql = 'SELECT name FROM #__app_sch_plugins WHERE published=1 ORDER BY ordering LIMIT 1';
        $db->setQuery($sql) ;
        return $db->loadResult();
    }
	/**
	 * Get the payment method object based on it's name
	 *
	 * @param string $name
	 * @return object
	 */		
	function getPaymentMethod($name) {
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
		$methods = os_payments::getPaymentMethods() ;
		foreach ($methods as $method) {
			if ($method->getName() == $name) {
                if($method->getName() == "os_prepaid"){
                    $db->setQuery("Select count(id) from #__app_sch_user_balance where user_id = '$user->id'");
                    $count = $db->loadResult();
                    if($count > 0){
                        $db->setQuery("Select * from #__app_sch_user_balance where user_id = '$user->id'");
                        $balance = $db->loadObject();
                        $user_balance = "(".JText::_('OS_AVAILABLE_BALANCE').": ".OSBHelper::showMoney($balance->amount,1).")";
                        $method->title .= " ".$user_balance;
                    }
                }
				return $method ;		
			}
		}
		return null ;
	}
}
?>