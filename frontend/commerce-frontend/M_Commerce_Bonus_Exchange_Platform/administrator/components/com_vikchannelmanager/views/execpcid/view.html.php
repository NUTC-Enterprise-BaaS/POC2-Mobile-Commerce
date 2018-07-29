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

class VikChannelManagerViewexecpcid extends JViewLegacy {
	
	function display($tpl = null) {

		$dbo = JFactory::getDBO();
		
		if(!function_exists('curl_init')) {
			echo VikChannelManager::getErrorFromMap('e4j.error.Curl');
			exit;
		}
		
		$config = VikChannelManager::loadConfiguration();
		$validate = array('apikey');
		foreach($validate as $v) {
			if( empty($config[$v]) ) {
				echo VikChannelManager::getErrorFromMap('e4j.error.Settings');
				exit;
			}
		}

		$channel_source = JRequest::getString('channel_source');
		$ota_id = JRequest::getString('otaid');
		
		$e4jc_url = "https://e4jconnect.com/channelmanager/?r=pcid&c=generic";
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!-- VikChannelManager PCID Request e4jConnect.com - Module Extensionsforjoomla.com -->
<PCIDataRQ xmlns="http://www.e4jconnect.com/schemas/pcidrq">
	<Notify client="'.JURI::root().'"/>
	<Api key="'.$config['apikey'].'"/>
	<Channel source="'.$channel_source.'"/>
	<Booking otaid="'.$ota_id.'"/>
</PCIDataRQ>';
		
		$e4jC = new E4jConnectRequest($e4jc_url);
		$e4jC->setPostFields($xml);
		$e4jC->slaveEnabled = true;
		$rs = $e4jC->exec();
		if($e4jC->getErrorNo()) {
			echo @curl_error($e4jC->getCurlHeader());
			exit;
		}
		if(substr($rs, 0, 9) == 'e4j.error' || substr($rs, 0, 11) == 'e4j.warning') {
			echo '<p style="margin: 10px 0px; padding: 12px; text-align: center; color: #D8000C; background: #FFBABA;">'.VikChannelManager::getErrorFromMap($rs).'</p>';
			exit;
		}
		
		if( !class_exists('Encryption') ) {
			require_once(JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.'cypher.php');
		}
		$cipher = new Encryption(md5($config['apikey']."e4j".$ota_id));
		// @array credit card response
		// [card_number] @string : 4242 4242 4242 ****
		// [cvv] @int : 123
		$credit_card_response = json_decode($cipher->decode($rs), true);

		$order = array();
		$q = "SELECT `id`, `paymentlog` FROM `#__vikbooking_orders` WHERE `idorderota`=".$dbo->quote($ota_id)." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$order = $dbo->loadAssoc();
		}

		$this->assignRef('config', $config);
		$this->assignRef('creditCardResponse', $credit_card_response);
		$this->assignRef('order', $order);
		
		// Display the template (default.php)
		parent::display($tpl);
		
	}

}
?>