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

class vikBookingPayment {
	
	private $order_info;
	
	public function __construct ($order) {
		$this->order_info=$order;
	}
	
	public function showPayment () {
		$depositmess="";
		if($this->order_info['leave_deposit']) {
			$depositmess="<p class=\"vbo-leave-deposit\"><span>".JText::_('VBLEAVEDEPOSIT')."</span>".$this->order_info['currency_symb']." ".number_format($this->order_info['total_to_pay'], 2)."</p><br/>";
		}
		//output
		echo $depositmess;
		echo $this->order_info['payment_info']['note'];
		
		return true;
	}
	
	public function validatePayment () {
		$array_result=array();
		$array_result['verified']=1;
		
		return $array_result;
	}
	
}


?>