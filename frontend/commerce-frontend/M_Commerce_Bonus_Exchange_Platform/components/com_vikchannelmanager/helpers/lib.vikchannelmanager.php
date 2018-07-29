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

defined('_JEXEC') OR die('Restricted Area');

class VikChannelManager {
	
	public static function loadConfiguration () {
		$dbo = JFactory::getDBO();
		$q = "SELECT * FROM `#__vikchannelmanager_config`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$rows = $dbo->loadAssocList();
		$conf = array();
		foreach( $rows as $r ) {
			$conf[$r['param']] = $r['setting'];
		}
		return $conf;
	}
	
	public static function getAdminMail($skip_session=false) {
		return self::getFieldFromConfig('emailadmin', 'vcmGetAdminMail', $skip_session);
	}
	
	public static function getDateFormat($skip_session=false) {
		return self::getFieldFromConfig('dateformat', 'vcmGetDateFormat', $skip_session);
	}
	
	public static function getClearDateFormat($skip_session=false) {
		return str_replace('%', '', self::getFieldFromConfig('dateformat', 'vcmGetDateFormat', $skip_session));
	}
	
	public static function getCurrencySymb($skip_session=false) {
		return self::getFieldFromConfig('currencysymb', 'vcmGetCurrencySymb', $skip_session);
	}
	
	public static function getCurrencyName($skip_session=false) {
		return self::getFieldFromConfig('currencyname', 'vcmGetCurrencyName', $skip_session);
	}
	
	public static function getDefaultPaymentID($skip_session=false) {
		return intval(self::getFieldFromConfig('defaultpayment', 'vcmGetDefaultPayment', $skip_session));
	}
	
	public static function getApiKey($skip_session=false) {
		return self::getFieldFromConfig('apikey', 'vcmGetApiKey', $skip_session);
	}

	public static function getProLevel($skip_session=true) {
		return intval(self::getFieldFromConfig('pro_level', 'vcmGetProLevel', $skip_session));
	}
	
	public static function getAccountStatus($skip_session=false) {
		return intval(self::getFieldFromConfig('account_status', 'vcmGetAccountStatus', $skip_session));
	}
	
	public static function isNewVersionAvailable($skip_session=false) {
		return intval(self::getFieldFromConfig('to_update', 'vcmGetToUpdate', $skip_session));
	}

	public static function isProgramBlocked($skip_session=false) {
		return intval(self::getFieldFromConfig('block_program', 'vcmGetBlockProgram', $skip_session));
	}
	
	public static function getTripConnectPartnerID($skip_session=false) {
		return self::getFieldFromConfig('tac_partner_id', 'vcmGetTripConnectPartnerID', $skip_session);
	}

	public static function getTrivagoPartnerID($skip_session=false) {
		return self::getFieldFromConfig('tri_partner_id', 'vcmGetTrivagoPartnerID', $skip_session);
	}
	
	public static function getTripConnectAccountID($skip_session=false) {
		return self::readHex(self::getFieldFromConfig('tac_account_id', 'vcmGetTripConnectAccountID', $skip_session));
	}
	
	public static function getTripConnectApiKey($skip_session=false) {
		return self::readHex(self::getFieldFromConfig('tac_api_key', 'vcmGetTripConnectApiKey', $skip_session));
	}
	
	public static function loadCypherFramework() {
		require_once(JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.'cypher.php');
	}
	
	public static function createTimestamp($date, $hour, $min, $skip_session=false) {
		$formats = explode('/',self::getClearDateFormat($skip_session));
		$d_exp = explode('/',$date);
		
		if( count( $d_exp ) != 3 ) {
			return -1;
		}
		
		$_attr = array();
		for( $i = 0, $n = count( $formats ); $i < $n; $i++ ) {
			$_attr[$formats[$i]] = $d_exp[$i];
		}
		
		return mktime(intval( $hour ), intval( $min ), 0, intval( $_attr['m'] ), intval( $_attr['d'] ), intval( $_attr['Y'] ) );
		
	}
	
	public static function getActiveModule($skip_session=false) {
		$id = intval(self::getFieldFromConfig('moduleactive', 'vcmGetModuleActive', $skip_session));
		if( $id != 0 ) {
			$dbo = JFactory::getDBO();
			$q = "SELECT * FROM `#__vikchannelmanager_channel` WHERE `id`=".$id." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if( $dbo->getNumRows() > 0 ) {
				return $dbo->loadAssoc();
			}
		} else {
			$dbo = JFactory::getDBO();
			$q = "SELECT * FROM `#__vikchannelmanager_channel` LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if( $dbo->getNumRows() > 0 ) {
				$row = $dbo->loadAssoc();
				
				$q = "UPDATE `#__vikchannelmanager_config` SET `setting`=".$dbo->quote($row['id'])." WHERE `param`='moduleactive' LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				
				return $row;
			}
		}
		
		return array();
	}
	
	public static function getChannel($unique_key) {
		$dbo = JFactory::getDBO();
		$q = "SELECT * FROM `#__vikchannelmanager_channel` WHERE `uniquekey`=".$dbo->quote($unique_key)." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			return $dbo->loadAssoc();
		}
		
		return array();
	}
	
	public static function getChannelCredentials($unique_key) {
		$row = self::getChannel($unique_key);
		if( count($row) > 0 ) {
			return json_decode($row['params'], true);
		}
		return array();
	}
	
	protected static function getFieldFromConfig($param, $session_key, $skipsession=false) {
		if( $skipsession ) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikchannelmanager_config` WHERE `param`=".$dbo->quote($param)." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return $s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get($session_key, '');
			if(!empty($sval)) {
				return $sval;
			} else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikchannelmanager_config` WHERE `param`=".$dbo->quote($param)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set($session_key, $s[0]['setting']);
				return $s[0]['setting'];
			}
		}
	}
	
	public static function isAvailabilityRequest() {
		$dbo = JFactory::getDBO();
	
		$q = "SELECT `id` FROM `#__vikchannelmanager_channel` WHERE `av_enabled`=1 LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		return ($dbo->getNumRows() > 0);
	}
	
	public static function retrieveNotifications($launch = false) {
		$fastcheck = JRequest::getInt('fastcheck');
		if(self::isAvailabilityRequest()) {
			?>
			<script type="text/javascript">
			jQuery(document).ready(function(){
				function vcmRetrieveNotifications() {
					var jqxhr = jQuery.ajax({
						type: "POST",
						url: "index.php",
						data: { option: "com_vikchannelmanager", task: "check_notifications", tmpl: "component" }
					}).done(function(res) { 
						if(parseInt(res) > 0) {
							jQuery("#dashboard-menu").text(res).fadeIn();
							if(!(jQuery("#vcm-audio-notification").length > 0)) {
								jQuery("#dashboard-menu").after("<audio id=\"vcm-audio-notification\" preload=\"auto\"><source type=\"audio/mp3\" src=\"<?php echo JURI::root(); ?>administrator/components/com_vikchannelmanager/assets/css/audio/new_notification.mp3\"></source></audio>");
								document.getElementById('vcm-audio-notification').play();
							}
						}else {
							jQuery("#dashboard-menu").hide();
						}
					}).fail(function() {
						jQuery("#dashboard-menu").hide(); 
					});
				}
				setInterval(function() {vcmRetrieveNotifications()}, <?php echo $fastcheck > 0 ? '10000' : '30000'; ?>);
			<?php
			if($fastcheck > 0) {
				?>
				setTimeout(function() {vcmRetrieveNotifications()}, 4000);
				<?php
			}
			if($launch) {
				?>
				vcmRetrieveNotifications();
				<?php
			}
			?>
			});
			</script>
			<?php
		}
	}
	
	public static function readNotifications($notifications) {
		if(count($notifications) > 0) {
			$ids = array();
			foreach ($notifications as $n) {
				$ids[] = $n['id'];
			}
			$dbo = JFactory::getDBO();
			$q = "UPDATE `#__vikchannelmanager_notifications` SET `read`=1 WHERE `id` IN (".implode(', ', $ids).");";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
		return;
	}
	
	public static function generateNKey($idordervb=0) {
		$nkey = rand(1000, 9999);
		$dbo = JFactory::getDBO();
		$q = "INSERT INTO `#__vikchannelmanager_keys` (`idordervb`,`key`) VALUES(".$idordervb.", '".$nkey."');";
		$dbo->setQuery($q);
		$dbo->Query($q);
		return $nkey;
	}

	public static function generateSerialCode($len=12, $_TOKENS='') {
		if( empty($_TOKENS) ) {
			$_TOKENS = array( 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', '0123456789' );
		}
		$_key = '';
		for( $i = 0; $i < $len; $i++ ) {
			$_row = rand( 0, count( $_TOKENS )-1 );
			$_col = rand( 0, strlen( $_TOKENS[$_row] )-1 );
			$_key .= '' . $_TOKENS[$_row][$_col];
		}
		return $_key;
	}
	
	public static function updateNKey($nkey, $id_notification) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id` FROM `#__vikchannelmanager_keys` WHERE `idordervb`=0 AND `key`=".(int)$nkey." ORDER BY `id` DESC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$key_id = $dbo->loadResult();
		$q = "UPDATE `#__vikchannelmanager_keys` SET `id_notification`=".(int)$id_notification." WHERE `id`=".(int)$key_id.";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		return true;
	}
	
	public static function authorizeAction($ch_key) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id` FROM `#__vikchannelmanager_channel` WHERE `uniquekey`=".$dbo->quote($ch_key)." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		return ($dbo->getNumRows() > 0);
	}
	
	public static function checkIntegrityHotelDetails() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id` FROM `#__vikchannelmanager_hotel_details` WHERE `required`=1 AND `value`='' LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		return ($dbo->getNumRows() == 0);
	}
	
	public static function composeSelectAmenities($name, $amenities=array(), $values=array(), $class="") {
		$select_amenities = '<select name="'.$name.'" multiple="multiple" size="8" class="'.$class.'">';
		foreach( $amenities as $amenity ) {
			$sel = false;
			for( $i = 0; $i < count($values) && !$sel; $i++ ) {
				$sel = ($values[$i] == $amenity);
			}
			$select_amenities .= '<option value="'.$amenity.'" '.(($sel) ? 'selected="selected"' : '').'>'.JText::_($amenity).'</option>';
		}
		$select_amenities .= '</select>';
		
		return $select_amenities;
	}
	
	public static function composeSelectRoomCodes($name, $options, $value="", $class="") {
		$select_codes = '<select name="'.$name.'" class="'.$class.'">';
		foreach( $options as $code ) {
			$select_codes .= '<option value="'.$code.'" '.(($value == $code) ? 'selected="selected"' : '').'>'.JText::_("ROOM_".$code).'</option>';
		}
		$select_codes .= '</select>';
		
		return $select_codes;
	}
	
	public static function getRoomRatesCost($id_room) {
		$dbo = JFactory::getDBO();
		
		$room_cost = "";
		
		$q = "SELECT `params` FROM `#__vikbooking_rooms` WHERE `id`=".$id_room." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$row = $dbo->loadAssoc();
			if( !empty($row['params']) ) {
				$row = json_decode($row['params'], true);
				$room_cost = (float)$row['custprice'];
			}
		}
		
		if( empty($room_cost) ) {
			$q = "SELECT `days`, `cost` FROM `#__vikbooking_dispcost` WHERE `idroom`=".$id_room." ORDER BY `days` ASC LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if( $dbo->getNumRows() > 0 ) {
				$row = $dbo->loadAssoc();
				$room_cost = $row['cost']/$row['days'];
			} else {
				$room_cost = 0.0;
			}
		}
		
		return $room_cost;
	}
	
	public static function getDateTimestamp($date, $h, $m, $skip_session = false) {
		$df = self::getDateFormat($skip_session);
		$x = explode("/", $date);
		if ($df == "%d/%m/%Y") {
			$dts = strtotime($x[1] . "/" . $x[0] . "/" . $x[2]);
		} elseif ($df == "%m/%d/%Y") {
			$dts = strtotime($x[0] . "/" . $x[1] . "/" . $x[2]);
		} else {
			$dts = strtotime($x[1] . "/" . $x[2] . "/" . $x[0]);
		}
		$h = empty($h) ? 0 : $h;
		$m = empty($m) ? 0 : $m;
		$hts = 3600 * $h;
		$mts = 60 * $m;
		return ($dts + $hts + $mts);
	}
	
	public static function loadOrdersRoomsDataVb ($idorder) {
		$dbo = JFactory::getDBO();
		$q = "SELECT * FROM `#__vikbooking_ordersrooms` WHERE `idorder`=".$idorder.";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
		return $s;
	}
	
	public static function getPaymentVb($idp) {
		if (!empty ($idp)) {
			$dbo = JFactory::getDBO();
			$q = "SELECT * FROM `#__vikbooking_gpayments` WHERE `id`=".$dbo->quote($idp)." AND `published`=1;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() == 1) {
				$payment = $dbo->loadAssocList();
				return $payment[0];
			} else {
				return false;
			}
		}
		return false;
	}
	
	public static function getHoursMoreRb($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='hoursmorebookingback';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return $s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('getHoursMoreRb', '');
			if(strlen($sval) > 0) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='hoursmorebookingback';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('getHoursMoreRb', $s[0]['setting']);
				return $s[0]['setting'];
			}
		}
	}
	
	public static function getPriceName($idp) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id`,`name` FROM `#__vikbooking_prices` WHERE `id`='" . $idp . "';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
			$n = $dbo->loadAssocList();
			return $n[0]['name'];
		}
		return "";
	}

	public static function getPriceAttr($idp) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id`,`attr` FROM `#__vikbooking_prices` WHERE `id`='" . $idp . "';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
			$n = $dbo->loadAssocList();
			return $n[0]['attr'];
		}
		return "";
	}
	
	public static function sayCostPlusIva($cost, $idprice) {
		$dbo = JFactory::getDBO();
		$session = JFactory::getSession();
		$sval = $session->get('vbivaInclusa', '');
		if(strlen($sval) > 0) {
			$ivainclusa = $sval;
		}else {
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='ivainclusa';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$iva = $dbo->loadAssocList();
			$session->set('vbivaInclusa', $iva[0]['setting']);
			$ivainclusa = $iva[0]['setting'];
		}
		if (intval($ivainclusa) == 0) {
			$q = "SELECT `idiva` FROM `#__vikbooking_prices` WHERE `id`='" . $idprice . "';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() == 1) {
				$pidiva = $dbo->loadAssocList();
				$q = "SELECT `aliq` FROM `#__vikbooking_iva` WHERE `id`='" . $pidiva[0]['idiva'] . "';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($dbo->getNumRows() == 1) {
					$paliq = $dbo->loadAssocList();
					$subt = 100 + $paliq[0]['aliq'];
					$op = ($cost * $subt / 100);
					//$op=money_format('%.2n', $op);
					//$op=number_format($op, 2);
					return $op;
				}
			}
		}
		return $cost;
	}
	
	public static function sayOptionalsPlusIva($cost, $idiva) {
		$dbo = JFactory :: getDBO();
		$session = JFactory::getSession();
		$sval = $session->get('vbivaInclusa', '');
		if(strlen($sval) > 0) {
			$ivainclusa = $sval;
		}else {
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='ivainclusa';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$iva = $dbo->loadAssocList();
			$session->set('vbivaInclusa', $iva[0]['setting']);
			$ivainclusa = $iva[0]['setting'];
		}
		if (intval($ivainclusa) == 0) {
			$q = "SELECT `aliq` FROM `#__vikbooking_iva` WHERE `id`='" . $idiva . "';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() == 1) {
				$piva = $dbo->loadAssocList();
				$subt = 100 + $piva[0]['aliq'];
				$op = ($cost * $subt / 100);
				return $op;
			}
		}
		return $cost;
	}
	
	public static function loadOrdersVbNotifications ($idorder) {
		$dbo = JFactory::getDBO();
		$q = "SELECT * FROM `#__vikchannelmanager_notifications` WHERE `idordervb`='" . $idorder . "';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
		return $s;
	}
	
	public static function readHex($str) {
		$var = "";
		for($i = 0; $i < strlen($str); $i += 2)
			$var .= chr(hexdec(substr($str, $i, 2)));
		return $var;
	}
	
	/**
	 * Check Expiring Date
	 */
	 
	public static function validateChannelResponse($rs) {
		$dbo = JFactory::getDBO();
		
		if( substr($rs, 0, 9) == 'e4j.error' ) {
			if( strpos($rs, 'AuthenticationError' ) !== false ) {
				$q = "UPDATE `#__vikchannelmanager_config` SET `setting`=0 WHERE `param`='account_status' LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
			}
		} else {
			$q = "UPDATE `#__vikchannelmanager_config` SET `setting`=1 WHERE `param`='account_status' LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
	}
	
	public static function getErrorFromMap($string, $revert_to_plain = false) {
		if( strlen($string) == 0 ) {
			return JText::_('UNKNOWN_ERROR');
		}
		$plain_mess = $string;
		$params = array();
		if( strpos($string, ':') !== false ) {
			$string = explode(':', $string);
			$nodes = explode('.', $string[0]);
			
			unset($string[0]);
			
			$params = explode(';;', implode(':', $string));
		}else {
			$nodes = explode('.', $string);
		}
		
		$pointer = VikChannelManagerConfig::$ERRORS_MAP;
		foreach( $nodes as $node ) {
			
			if( empty($pointer[$node]) ) {
				return !$revert_to_plain ? JText::sprintf('UNKNOWN_ERROR_MAP', $node) : $plain_mess;
			}
			
			$pointer = $pointer[$node];

		}
		
		$lang_text = JText::_($pointer['_default']);
		if( count($params) == 0 ) {
			return $lang_text;
		}
		return vsprintf($lang_text, $params);
	}
	
	/**
	 * VikBooking calls
	 */
	 
	public static function invokeChannelImpression() {
		
		//Mod February 2015
		/*
		if( !self::getAccountStatus() ) {
			return false;
		}
		*/

		$uri = JURI::getInstance();
		
		// GET CHANNEL FROM REQUEST, SESSION or COOKIE
		$channel = self::getChannelFromRequest();
		$session = JFactory::getSession();
		$cookie = JFactory::getApplication()->input->cookie;
		if(empty($channel)) {
			$channel = $session->get('vcmChannelData', '');
			if(empty($channel)) {
				$cookie_channel = $cookie->get('vcmChannelData', '', 'string');
				$channel = !empty($cookie_channel) ? json_decode($cookie_channel, true) : '';
				//check cookie integrity
				if(!empty($channel)) {
					$channel = self::getChannelFromRequest($channel);
				}
				//
			}
		}
		//
		if( !empty($channel) ) {
			$session->set('vcmChannelData', $channel);
			$cookie->set( 'vcmChannelData', json_encode($channel), (time() + (86400 * 30)), '/' );
			// PIXEL IMPRESSION
			switch ($channel['uniquekey']) {
				case VikChannelManagerConfig::TRIP_CONNECT:
					self::generateTripConnectPixel($uri->getScheme().'://www.tripadvisor.com/js3/conversion/pixel.js');
					break;
				case VikChannelManagerConfig::TRIVAGO:
					self::generateTrivagoPixel();
					break;
				default:
					break;
			}
		}
		
	}
	
	public static function invokeChannelConversionImpression($order=array()) {
		
		//Mod February 2015
		/*
		if( !self::getAccountStatus() ) {
			return false;
		}
		*/
		
		$uri = JURI::getInstance();

		$session = JFactory::getSession();
		$channel = $session->get('vcmChannelData', '');
		$cookie = JFactory::getApplication()->input->cookie;
		if(empty($channel)) {
			$cookie_channel = $cookie->get('vcmChannelData', '', 'string');
			$channel = !empty($cookie_channel) ? json_decode($cookie_channel, true) : '';
			//check cookie integrity
			if(!empty($channel)) {
				$channel = self::getChannelFromRequest($channel);
			}
			//
		}
		if(!empty($channel) ) {
			// Conversion PIXEL IMPRESSION
			switch ($channel['uniquekey']) {
				case VikChannelManagerConfig::TRIP_CONNECT:
					self::generateTripConnectPixel($uri->getScheme().'://www.tripadvisor.com/js3/conversion/pixel.js', $order, 2);
					break;
				case VikChannelManagerConfig::TRIVAGO:
					self::generateTrivagoPixel($order, 2);
					break;
				default:
					break;
			}
			$session->set('vcmChannelData', '');
			$cookie->set( 'vcmChannelData', json_encode($channel), (time() - (86400 * 30)), '/' );
		}
		
	}
	
	private static function generateTrivagoPixel($order=array(), $type=1) {
		
		if(count(self::getChannelCredentials( VikChannelManagerConfig::TRIVAGO )) == 0 ) {
			return false;
		}

		$document = JFactory::getDocument();
		if( $type == 1 ) {
			//nothing needs to be done in the landing page (Search Results)
		} else {
			$partner_id = self::getTrivagoPartnerID();
			$curr_name = self::getCurrencyName();
			//$tot_revenue = $order['total']; //net amount only
			$tot_revenue = $order['total'] + $order['taxes'];
			$sdecl = '
var mhsCV = {
	\'revenue\': '.number_format($tot_revenue, 2, '.', '').',
	\'roomnights\': '.$order['days'].',
	\'rooms\': '.$order['roomsnum'].',
	\'reservationnumber\': "'.$order['id'].'",
	\'currency\': "'.$curr_name.'",
	\'partnerReference\': "'.$partner_id.'"
};';
			$document->addScriptDeclaration($sdecl);
			$document->addScript('https://b3919f1f4bd09ea8624e-10404c33765d0268e8e32a61a8acd647.ssl.cf3.rackcdn.com/LAB.js');
			$sdecl = 'var mhsHost = (("https:" == document.location.protocol) ? "https://m." : "http://m.");
$LAB.script(mhsHost + \'myhotelshop.de/conversion.js\');';
			$document->addScriptDeclaration($sdecl);
		}
	}

	private static function generateTripConnectPixel($path, $order=array(), $type=1) {
		$account_id = self::getTripConnectAccountID();
		
		if( empty($account_id) || count(self::getChannelCredentials( VikChannelManagerConfig::TRIP_CONNECT )) == 0 ) {
			return false;
		}
		
		$vik = new VikApplication(VersionListener::getID());
		$vik->addScript($path);
		
		$document = JFactory::getDocument();
		if( $type == 1 ) {
			$document->addScriptDeclaration('window.onload = function(e){TAPixel.impressionWithReferer("'.$account_id.'");}');
		} else {
			$partner_id = self::getTripConnectPartnerID();
			$curr_name = self::getCurrencyName();
			$document->addScriptDeclaration('window.onload = function(e){TAPixel.conversionWithReferer(
				"'.$account_id.'", "'.$partner_id.'", '.intval($order['total']*100).', "'.$curr_name.'", '.intval($order['taxes']*100).
				', '.intval($order['fees']*100).', "'.date('Y-m-d', $order['checkin']).'", "'.date('Y-m-d', $order['checkout']).'", '.intval($order['tot_adults']).', "'.$order['confirmnumber'].'"
			);}');
		}
	}
	
	private static function getChannelFromRequest($cookie_ch = '') {
		
		$vig = new Vigenere( array('0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9) );
		
		$ta_ch_enc = JRequest::getString('ta_ch');
		
		if( !empty($ta_ch_enc) ) {
			$dec = $vig->decrypt($ta_ch_enc, ''.self::getTripConnectPartnerID());
			$dec = substr($dec, strlen(VikChannelManagerConfig::VCM_CONNECTION_SERIAL));
			if( $dec == VikChannelManagerConfig::TRIP_CONNECT ) {
				$channel = self::getChannel(VikChannelManagerConfig::TRIP_CONNECT);
				$channel['disclaimer'] = 'TRIP_CONNECT_DISCLAIMER';
				$channel['url_ch'] = $ta_ch_enc;
				return $channel;
			}
			return '';
		}
		
		$tri_ch_enc = JRequest::getString('tri_ch');
		
		if( !empty($tri_ch_enc) ) {
			$dec = $vig->decrypt($tri_ch_enc, ''.self::getTrivagoPartnerID());
			$dec = substr($dec, strlen(VikChannelManagerConfig::VCM_CONNECTION_SERIAL));
			if( $dec == VikChannelManagerConfig::TRIVAGO ) {
				$channel = self::getChannel(VikChannelManagerConfig::TRIVAGO);
				$channel['url_ch'] = $tri_ch_enc;
				return $channel;
			}
			return '';	
		}

		if( !empty($cookie_ch) ) {
			if( !empty($cookie_ch['uniquekey']) ) {
				$partner_id = '';
				$validate_ch = -1;
				if($cookie_ch['uniquekey'] == VikChannelManagerConfig::TRIP_CONNECT) {
					$partner_id = self::getTripConnectPartnerID();
					$validate_ch = VikChannelManagerConfig::TRIP_CONNECT;
				}elseif($cookie_ch['uniquekey'] == VikChannelManagerConfig::TRIVAGO) {
					$partner_id = self::getTrivagoPartnerID();
					$validate_ch = VikChannelManagerConfig::TRIVAGO;
				}
				if(!empty($partner_id)) {
					$dec = $vig->decrypt($cookie_ch['url_ch'], ''.$partner_id);
					$dec = substr($dec, strlen(VikChannelManagerConfig::VCM_CONNECTION_SERIAL));
					if( $dec == $validate_ch ) {
						return $cookie_ch;
					}
				}
			}
			return '';	
		}

		return ''; 

	}
	
	public static function storeCallStats($channel, $call, $elapsed_time) {
		
		$last_call = array();
		
		$dbo = JFactory::getDBO();
		
		$q = "SELECT *  FROM `#__vikchannelmanager_call_stats` WHERE `channel`=".$dbo->quote($channel)." AND `call`=".$dbo->quote($call)." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$last_call = $dbo->loadAssoc();
			
			$min = min(array($elapsed_time, $last_call['min_exec_time']));
			$max = max(array($elapsed_time, $last_call['max_exec_time']));
			
			$q = "UPDATE `#__vikchannelmanager_call_stats` SET `last_exec_time`=".$elapsed_time.",`min_exec_time`=".$min.",`max_exec_time`=".$max.",`last_visit`=NOW() WHERE `id`=".$last_call['id']." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			
		} else {
			$q = "INSERT INTO `#__vikchannelmanager_call_stats`(`channel`,`call`,`min_exec_time`,`max_exec_time`,`last_exec_time`) VALUES (".
				$dbo->quote($channel).",".$dbo->quote($call).",".$elapsed_time.",".$elapsed_time.",".$elapsed_time.
			");";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
		
	}

	public static function getTripConnectPaymentLog($payment) {
		
		$cc = $payment['card_number'];
		$cc_num_len = strlen($cc);
		$cc_hidden = '';
		
		if( $cc_num_len == 14 ) {
			// Diners Club
			$cc_hidden .= substr($cc, 0, 4)." **** **** **";
		}elseif( $cc_num_len == 15 ) {
			// American Express
			$cc_hidden .= "**** ****** ".substr($cc, 10, 5);
		}else {
			// Master Card, Visa, Discover, JCB
			$cc_hidden .= "**** **** **** ".substr($cc, 12, 4);
		}
		
		$text = 'Credit Card: '.$payment['card_type']."\n\nCardholder Name: ".$payment['cardholder_name']."\n\n".$cc_hidden." (sent via mail)\n\nCVV: ".$payment['cvv']."\n\nValid Thru: ".$payment['expiration_month']."/".$payment['expiration_year']."\n\n";
		foreach( $payment['billing_address'] as $k => $v ) {
			$text .= ucwords(str_replace('_', ' ', $k)).": ".$v."\n";
		}
		
		return $text;
		
	}

	// MAIL CONTENTS
	/**
	 * args - array()
	 * 'latest_version' - string
	 * 'required' - boolean
	 */
	public static function getNewVersionMailContent($args) {
		$html = '<p>A new version of VikChannelManager was released.<br/>Please execute the update from the following link:<br/><br/>
		<a href="'.JURI::root().'administrator/index.php?option=com_vikchannelmanager">'.JURI::root().'administrator/index.php?option=com_vikchannelmanager</a></p>'."\n";
		if(!empty($args['message'])) {
			$html .= '<p>'.$args['message'].'</p>'."\n";
		}
		// TODO
		return $html;
	}
	
	// MAIL CONTENTS
	/**
	 * args - array()
	 * 'latest_version' - string
	 * 'required' - boolean
	 */
	public static function getTripConnectCCMailContent($args) {
		$cc = $args['payment_method']['card_number'];
		$cc_num_len = strlen($cc);
		$cc_hidden = '';
		
		if( $cc_num_len == 14 ) {
			// Diners Club
			$app = "****".substr($cc, 4, 10);
			for( $i = 1; $i <= $cc_num_len; $i++ ) {
				$cc_hidden .= $app[$i-1].($i%4 == 0 ? ' ':'');
			}
		} else if( $cc_num_len == 15 ) {
			// American Express
			$app = substr($cc, 0, 10)."*****";
			for( $i = 1; $i <= $cc_num_len; $i++ ) {
				$cc_hidden .= $app[$i-1].($i==4 || $i==10 ? ' ':'');
			}
		} else {
			// Master Card, Visa, Discover, JCB
			$app = substr($cc, 0, 12)."****";
			for( $i = 1; $i <= $cc_num_len; $i++ ) {
				$cc_hidden .= $app[$i-1].($i%4 == 0 ? ' ':'');
			}
		}
		
		$cust_info = '';
		foreach( $args['customer_info'] as $k => $v ) {
			$cust_info .= ucwords(str_replace('_', ' ', $k)).': '.$v."\n";
		}
		
		$admin_url = JURI::root().'administrator/index.php?option=com_vikbooking&task=editorder&cid[]='.$args['response']['id'].'#paymentlog';
		$front_url = $args['response']['orderlink'];
		
		return JText::sprintf('VCMTACNEWORDERMAILCONTENT', 
			"#".$args['response']['id'],
			JText::_('VCMTACNEWORD'.strtoupper($args['response']['status']).'STATUS'),
			$args['start_date'],
			$args['end_date'],
			$cust_info,
			$cc_hidden,
			$admin_url."\n\n".$front_url
		);
	}

	public static function parseNotificationHotelId($notif_cont, $cha_id, $ret_first = false) {
		$first_hid = '';
		preg_match_all('/\{hotelid ([a-zA-Z0-9]+)\}/U', $notif_cont, $matches);
		if (is_array($matches[1]) && @count($matches[1]) > 0) {
			$hids = array();
			$hname_map = array();
			foreach($matches[1] as $hid ){
				$hids[] = $hid;
			}
			$dbo = JFactory::getDBO();
			$q = "SELECT `prop_name`,`prop_params`  FROM `#__vikchannelmanager_roomsxref` WHERE `idchannel`=".(int)$cha_id.";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if( $dbo->getNumRows() > 0 ) {
				$all_rooms = $dbo->loadAssocList();
				foreach ($all_rooms as $room) {
					if(!empty($room['prop_params']) && !empty($room['prop_name'])) {
						$prop_info = json_decode($room['prop_params'], true);
						if(is_array($prop_info) && array_key_exists('hotelid', $prop_info)) {
							$hname_map[$prop_info['hotelid']] = $room['prop_name'];
						}
					}
				}
			}
			foreach ($hids as $k => $hid) {
				if(array_key_exists($hid, $hname_map)) {
					$notif_cont = str_replace('{hotelid '.$hid.'}', $hname_map[$hid], $notif_cont);
					$first_hid = (!($k > 0) ? $hname_map[$hid] : $first_hid);
				}else {
					$notif_cont = str_replace('{hotelid '.$hid.'}', 'Account ID '.$hid, $notif_cont);
					$first_hid = (!($k > 0) ? 'Account ID '.$hid : $first_hid);
				}
			}
		}
		return $ret_first ? $first_hid : $notif_cont;
	}
	
}

class Vigenere {
	
	private $char_map;
	
	public function __construct( $char_map ) {
		$this->char_map = $char_map;
	}
	
	public function encrypt($word, $key) {
		$key = $this->prepare_key($key, strlen($word));
		
		$enc = '';
		
		for( $i = 0; $i < strlen($word); $i++ ) {
			$a = $this->char_map[$word[$i]];
			$b = $this->char_map[$key[$i]];
			$c = $a+$b;
			$enc .= (($c >= count($this->char_map)) ? ($c-count($this->char_map)) : $c);
		}
		
		return $enc;
	}
	
	public function decrypt($enc, $key) {
		$key = $this->prepare_key($key, strlen($enc));
		
		$word = '';
		
		for( $i = 0; $i < strlen($enc); $i++ ) {
			$a = $this->char_map[$enc[$i]];
			$b = $this->char_map[$key[$i]];
			$c = $a-$b;
			$word .= (($c < 0) ? ($c+count($this->char_map)) : $c);
		}
		
		return $word;
	}
	
	private function prepare_key($key, $len) {
		if( empty($key) ) {
			$key = 'abc';
		}
		
		$i = 0;
		$n = strlen($key);
		while(strlen($key) != $len) {
			$key .= $key[$i];
			$i = ($i+1)%$n;
		}
		return $key;
	}
	
}

class E4jConnectRequest {
	
	private $endpoint;
	private $httpheader;
	private $connect_timeout;
	private $timeout;
	private $retries;
	private $curl_retry_errornos;
	private $postFields;
	private $curlopt_add;
	public $slaveEnabled;

	private $ch;
	private $result;
	private $result_info;
	private $error_no;
	private $error_msg;

	/**
	* Class construct
	* @param endpoint
	*/
	public function __construct ($endpoint) {
		$this->endpoint = $endpoint;
		$this->httpheader = array('Content-Type: text/xml');
		$this->connect_timeout = 10;
		$this->timeout = 20;
		$this->retries = 5;
		$this->curl_retry_errornos = array(2, 6, 7, 28, 35);
		$this->postFields = '';
		$this->curlopt_add = array();
		$this->slaveEnabled = false;

		$this->ch = null;
		$this->result = 'e4j.error';
		$this->result_info = array();
		$this->error_no = 0;
		$this->error_msg = '';
	}

	public function setHttpHeader ($hheader) {
		if(is_array($hheader)) {
			$this->httpheader = $hheader;
		}
	}

	public function setConnectTimeout ($sec) {
		if(intval($sec) > 0) {
			$this->connect_timeout = (int)$sec;
		}
	}

	public function setTimeout ($sec) {
		if(intval($sec) > 0) {
			$this->timeout = (int)$sec;
		}
	}

	public function setRetries ($n) {
		if(intval($n) > 0) {
			$this->retries = (int)$n;
		}
	}

	public function setPostFields ($pf) {
		$this->postFields = $pf;
	}

	public function setCurlOpt ($copt) {
		if(is_array($copt)) {
			$this->curlopt_add = $copt;
		}
	}

	private function setErrorMsg ($err) {
		$this->error_msg .= $err."\n";
	}

	private function setErrorNo ($err) {
		$this->error_no = $err;
	}

	private function setResultInfo ($key, $param) {
		if(strlen($key) && strlen($param)) {
			$this->result_info[$key] = $param;
		}
	}

	public function getErrorMsg () {
		return rtrim($this->error_msg, "\n");
	}

	public function getErrorNo () {
		return $this->error_no;
	}

	public function getCurlHeader () {
		return $this->ch;
	}

	public function getResultInfo ($key = '') {
		if(strlen($key)) {
			return $this->result_info[$key];
		}
		return $this->result_info;
	}

	public function exec ($recursion = false) {
		$bet_ssl = false;
		$try = 0;
		$curl_errno = 0;
		$curl_errmsg = '';
		$res = 'e4j.error';
		do {
			$this->ch = curl_init($this->endpoint);
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 2);
			if(!$bet_ssl && defined('CURLOPT_SSLVERSION')) {
				//TLS 1.2
				curl_setopt($this->ch, CURLOPT_SSLVERSION, 6);
			}
			curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout);
			curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout);
			curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($this->ch, CURLOPT_POST, 1);
			curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->httpheader);
			curl_setopt($this->ch, CURLOPT_HEADER, 0);
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->postFields);
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
			if(is_array($this->curlopt_add) && @count($this->curlopt_add) > 0) {
				foreach ($this->curlopt_add as $curlopt => $curlval) {
					curl_setopt($this->ch, constant($curlopt), $curlval);
				}
			}
			$res = curl_exec($this->ch);
			if($curl_errno = curl_errno($this->ch)) {
				$curl_errmsg = 'e4j.error.Curl.Request'."\n".curl_error($this->ch);
				if($curl_errno == 35) {
					$bet_ssl = true;
				}
			}else {
				$this->setResultInfo('http_code', curl_getinfo($this->ch, CURLINFO_HTTP_CODE));
				$curl_errno = 0;
				$curl_errmsg = '';
			}
			curl_close($this->ch);
			$try++;
		} while ($try < $this->retries && $curl_errno > 0 && in_array($curl_errno, $this->curl_retry_errornos));

		//Slave Cron Server
		if( ($curl_errno || $res === false) && !$recursion && $this->slaveEnabled && strpos($this->endpoint, 'slave.e4jconnect.com') === false ) {
			$this->endpoint = str_replace('e4jconnect.com', 'slave.e4jconnect.com', $this->endpoint);
			return $this->exec(true);
		}
		//
		
		if ($res === false) {
			$res = 'e4j.error';
		}

		$this->setErrorNo($curl_errno);
		$this->setErrorMsg($curl_errmsg);
		$this->result = $res;

		return $this->result;
	}

}

class Crono {
	
	private $_time = 0.0;
	
	public function start() {
		$this->_time = microtime(true);
		return $this->_time;
	}
	
	public function stop() {
		return (float)(microtime(true)-$this->_time);
	}
	
}

if( !class_exists('VersionListener') ) {
	
	class VersionListener {
		
		private static $_ID = -1; 
		
		public function __construct($forced_id='') {
			$version = new JVersion();
			$v = $version->getShortVersion();
			
			if( !empty( $forced_id ) ) {
				$v = $forced_id;
			}
			
			if( version_compare($v, '2.5') >= 0 && version_compare($v, '3.0') < 0 ) {
				self::$_ID = 0;
			} else if ( version_compare($v, '3.0') >= 0 ) {
				self::$_ID = 1;
			} else {
				die('VERSION NOT SUPPORTED!');
			}
		}
		
		public static function getID() {
			return self::$_ID;
		}
		
	}
}

if( !class_exists('VikApplication') ) {
	
	class VikApplication {
		
		private $id = -1; 
		
		public function __construct($id) {
			$this->id = $id;
		}
		
		public function getAdminTableClass() {
			if( $this->id == 0 ) {
				// 2.5
				return "adminlist";
			} else {
				// 3.x
				return "table table-striped";
			}
		}
		
		public function openTableHead() {
			if( $this->id == 0 ) {
				// 2.5
				return "";
			} else {
				// 3.x
				return "<thead>";
			}
		}
		
		public function closeTableHead() {
			if( $this->id == 0 ) {
				// 2.5
				return "";
			} else {
				// 3.x
				return "</thead>";
			}
		}
		
		public function getAdminThClass($h_align='center') {
			if( $this->id == 0 ) {
				// 2.5
				return 'title';
			} else {
				// 3.x
				return 'title ' . $h_align;
			}
		}
		
		public function getAdminToggle($count) {
			if( $this->id == 0 ) {
				// 2.5
				return '<input type="checkbox" name="toggle" value="" onclick="checkAll('.$count.');" />';
			} else {
				// 3.x
				return '<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle" />';
			}
		}
		
		public function checkboxOnClick($js_arg = 'this.checked') {
			if( $this->id == 0 ) {
				// 2.5
				return 'isChecked('.$js_arg.');';
			} else {
				// 3.x
				return 'Joomla.isChecked('.$js_arg.');';
			}
		}
		
		public function sendMail($from_address, $from_name, $to, $reply_address, $subject, $hmess, $is_html=true, $encoding='base64') {
			if( $this->id == 0 ) {
				// 2.5
				JUtility::sendMail($from_address, $fromname, $to, $subject, $hmess, $is_html, null, null, null, $reply_address, $from_name);
			} else {
				// 3.x
				$mailer = JFactory::getMailer();
				$sender = array($from_address, $from_name);
				$mailer->setSender($sender);
				$mailer->addRecipient($to);
				$mailer->addReplyTo($reply_address);
				$mailer->setSubject($subject);
				$mailer->setBody($hmess);
				$mailer->isHTML($is_html);
				$mailer->Encoding = $encoding;
				$mailer->Send();
			}
		}
		
		public function addScript($path='', $arg1=false, $arg2=true, $arg3=false, $arg4=false) {
			if( empty($path) ) return; 
			
			if( $this->id == 0 ) {
				$doc = JFactory::getDocument();
				$doc->addScript($path);
			} else {
				JHtml::_( 'script', $path, $arg1, $arg2, $arg3, $arg4 );
			}
		}
		
		public function loadFramework($fw='', $arg1=true, $arg2=true) {
			if( empty($fw) ) return;
			
			if( $this->id == 0 ) {
				
			} else {
				JHtml::_( $fw, $arg1, $arg2 );
			}
		}
		
		public function emailToPunycode($email='') {
			if( $this->id == 0 ) {
				// 2.5
				return $email;
			} else {
				// 3.x
				return JStringPunycode::emailToPunycode($email);
			}
		}
		
		/*
		public function _name(_params,...) {
			if( $this->id == 0 ) {
				// 2.5
				
			} else {
				// 3.x
			
			}
		}
		*/
		
	}

}

?>
