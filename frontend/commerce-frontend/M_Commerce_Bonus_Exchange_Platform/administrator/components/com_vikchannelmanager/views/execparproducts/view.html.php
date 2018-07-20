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

class VikChannelManagerViewexecparproducts extends JViewLegacy {
	
	function display($tpl = null) {
		
		if(!function_exists('curl_init')) {
			echo 'e4j.error.'.VikChannelManager::getErrorFromMap('e4j.error.Curl');
			exit;
		}
		
		$config = VikChannelManager::loadConfiguration();
		$validate = array('apikey');
		foreach($validate as $v) {
			if( empty($config[$v]) ) {
				echo 'e4j.error.'.VikChannelManager::getErrorFromMap('e4j.error.Settings');
				exit;
			}
		}
		
		$dbo = JFactory::getDBO();
		$q = "SELECT `vbr`.`id`,`vbr`.`name`,`vbr`.`img`,`vbr`.`smalldesc` FROM `#__vikbooking_rooms` AS `vbr` ORDER BY `vbr`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$vbrooms = $dbo->loadAssocList();
		} else {
			echo 'e4j.error.There are no rooms in VikBooking, fetching the rooms from the OTA would be useless.';
			exit;
		}
		
		$channel = VikChannelManager::getActiveModule(true);
		$channel['params'] = json_decode($channel['params'], true);
		
		$e4jc_url = "https://e4jconnect.com/channelmanager/?r=par&c=".$channel['name'];
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!-- VikChannelManager PAR Request e4jConnect.com - '.ucwords($channel['name']).' Module Extensionsforjoomla.com -->
<ProductsAvailabilityRatesRQ xmlns="http://www.e4jconnect.com/channels/parrq">
	<Notify client="'.JURI::root().'"/>
	<Api key="'.$config['apikey'].'"/>
	<ProductsAvailabilityRates>
		<Fetch element="products" hotelid="'.$channel['params']['hotelid'].'"/>
		<Dates from="'.date('Y-m-d').'" to="'.date('Y-m-d').'"/>
	</ProductsAvailabilityRates>
</ProductsAvailabilityRatesRQ>';
		
		$e4jC = new E4jConnectRequest($e4jc_url);
		$e4jC->setPostFields($xml);
		$rs = $e4jC->exec();
		if($e4jC->getErrorNo()) {
			echo 'e4j.error.'.@curl_error($e4jC->getCurlHeader());
			exit;
		}
		if(substr($rs, 0, 9) == 'e4j.error' || substr($rs, 0, 11) == 'e4j.warning') {
			echo 'e4j.error.'.VikChannelManager::getErrorFromMap($rs);
			exit;
		}
		
		$channelrooms = unserialize($rs);
		if(count($channelrooms['Rooms']) == 0) {
			echo 'e4j.error.No Rooms Returned. Check your Settings.';
			exit;
		}

		$this->assignRef('config', $config);
		$this->assignRef('vbrooms', $vbrooms);
		$this->assignRef('channelrooms', $channelrooms);
		
		// Display the template (default.php)
		parent::display($tpl);
		
	}

}
?>