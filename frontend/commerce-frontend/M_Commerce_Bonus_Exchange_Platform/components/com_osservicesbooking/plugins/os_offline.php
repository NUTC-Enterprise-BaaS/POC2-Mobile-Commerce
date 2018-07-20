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

class os_offline extends os_payment {
	/**
	 * Order Status
	 *
	 * @var unknown_type
	 */
	var $order_status = null;
	/**
	 * Constructor functions, init some parameter
	 *
	 * @param object $params
	 */
	function os_offline($params) {
		parent::setName('os_offline');		
		parent::os_payment();				
		parent::setCreditCard(false);		
    	parent::setCardType(false);
    	parent::setCardCvv(false);
    	parent::setCardHolderName(false);	
    	$this->order_status = $params->get('order_status');
	}	
	/**
	 * Process payment 
	 *
	 */
	function processPayment($row, $data) {
		$mainframe = & JFactory::getApplication() ;
		$Itemid = JRequest::getint('Itemid');
		if($this->order_status == 0){
				
		}else{
			OsAppscheduleDefault::paymentComplete($row->id);
		}
		$url = JRoute::_(JURI::root()."index.php?option=com_osservicesbooking&task=default_paymentreturn&id=$row->id&Itemid=".JRequest::getVar('Itemid'), false, false);		
		$mainframe->redirect($url);				    
	}		
}