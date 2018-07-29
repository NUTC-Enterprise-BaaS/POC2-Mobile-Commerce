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

JHtml::_('behavior.modal');

abstract class VCM {
	
	public static function printMenu() {
		//self::load_css_js();
		
		if( VikChannelManager::isProgramBlocked(true) ) {
			VCM::printBlockVersionView();
			return;
		}
		
		$new_version = VikChannelManager::isNewVersionAvailable(true);
		
		$highlight = JRequest::getString('task', 'dashboard');
		
		$module = VikChannelManager::getActiveModule(true);
		$mod_rows = array();
		$id = 0;
		if( !empty($module['id']) ) {
			$id = $module['id'];
		}
		
		$dbo = JFactory::getDBO();
		$q = "SELECT `id`,`name`,`uniquekey`,`av_enabled` FROM `#__vikchannelmanager_channel` WHERE `id`<>".$id.";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() > 0 ) {
			$mod_rows = $dbo->loadAssocList();
		}
		
		if( empty($module['id']) && count($mod_rows) > 0 ) {
			$module = $mod_rows[0];
			$arr = array();
			for( $i = 1; $i < count($mod_rows); $i++ ) {
				$arr[$i-1] = $mod_rows[$i];
			}
			$mod_rows = $arr;
		}
		
		$li_mod_class = '';
		if( !empty($module['uniquekey']) ) {
			switch( $module['uniquekey'] ) {
				case VikChannelManagerConfig::EXPEDIA: $li_mod_class = "vcmliexpedia"; break;
				case VikChannelManagerConfig::TRIP_CONNECT: $li_mod_class = "vcmlitripconnect"; break;
				case VikChannelManagerConfig::TRIVAGO: $li_mod_class = "vcmlitrivago"; break;
				case VikChannelManagerConfig::BOOKING: $li_mod_class = "vcmlibookingcom"; break;
				case VikChannelManagerConfig::AIRBNB: $li_mod_class = "vcmliairbnb"; break;
				case VikChannelManagerConfig::FLIPKEY: $li_mod_class = "vcmliflipkey"; break;
				case VikChannelManagerConfig::HOLIDAYLETTINGS: $li_mod_class = "vcmliholidaylettings"; break;
				case VikChannelManagerConfig::AGODA: $li_mod_class = "vcmliagoda"; break;
				case VikChannelManagerConfig::WIMDU: $li_mod_class = "vcmliwimdu"; break;
				case VikChannelManagerConfig::HOMEAWAY: $li_mod_class = "vcmlihomeaway"; break;
				case VikChannelManagerConfig::VRBO: $li_mod_class = "vcmlivrbo"; break;
				case VikChannelManagerConfig::YCS50: $li_mod_class = "vcmliycs50"; break;
			}
		}
		
		?>
		<script type="text/javascript">
		jQuery.noConflict();
		jQuery(document).ready(function(){
			jQuery("ul.vcmuladminmenu li.parent").hover(function() {
				jQuery(this).find("ul:first").stop(true, true).delay(50).slideDown(400);
			}, function(){
				jQuery(this).find("ul:first").stop(true, true).slideUp(600);
			});
			
			jQuery("li").hover(function(){
				jQuery(this).addClass('vcmulhover');
			}, function() {
				jQuery(this).removeClass('vcmulhover');
			});
		});
		</script>
	<div class="vcm-menunav-wrap">
		<div class="vcm-menunav-left">
			<img align="middle" alt="logo vikchannelmanager" src="<?php echo JURI::root(); ?>administrator/components/com_vikchannelmanager/assets/css/images/vikchannelmanager.jpg" />
		</div>
		<div class="vcm-menunav-right">
			<ul class="vcmuladminmenu">
				<li class="vcmmenulifirst <?php echo ($highlight == 'dashboard' ? 'highlight' : '').($new_version ? ' vcmnewupavailnotice' : ''); ?>"><a href="index.php?option=com_vikchannelmanager"><span id="dashboard-menu"></span><i class="vboicn-earth"></i><?php echo JText::_('VCMMENUDASHBOARD'); ?></a></li>
				<li<?php echo ($highlight == 'config' ? ' class="highlight"' : ''); ?>><a href="index.php?option=com_vikchannelmanager&amp;task=config"><i class="vboicn-cogs"></i><?php echo JText::_('VCMMENUSETTINGS'); ?></a></li>
				<li style="margin-right: 30px;"><a href="index.php?option=com_vikbooking"><i class="vboicn-home"></i><?php echo JText::_('VCMMENUIBE'); ?></a></li>
				
				<li class="parent<?php echo (self::isHotelHighlighted($highlight) ? ' highlight' : ''); ?> <?php echo $li_mod_class; ?>">
					<a href="index.php?option=com_vikchannelmanager&amp;task=<?php echo self::getHotelDefaultTask($module['uniquekey']); ?>"><i class="vboicn-office"></i><?php echo JText::_('VCMMENUHOTEL'); ?></a>
					<ul>
						<li class=""><a href="index.php?option=com_vikchannelmanager&amp;task=hoteldetails"><?php echo JText::_('VCMMENUTACDETAILS'); ?></a></li>
						<?php if( in_array($module['uniquekey'], array(VikChannelManagerConfig::EXPEDIA, VikChannelManagerConfig::AGODA, VikChannelManagerConfig::BOOKING, VikChannelManagerConfig::YCS50)) ) { // expedia, agoda, booking.com ?>
							<li class=""><a href="index.php?option=com_vikchannelmanager&amp;task=rooms"><?php echo JText::_('VCMMENUEXPROOMSREL'); ?></a></li>
							<li class=""><a href="index.php?option=com_vikchannelmanager&amp;task=roomsrar"><?php echo JText::_('VCMMENUEXPROOMSAVRATRESTR'); ?></a></li>
							<li class=""><a href="index.php?option=com_vikchannelmanager&amp;task=roomsynch"><?php echo JText::_('VCMMENUEXPSYNCH'); ?></a></li>
						<?php } else if( $module['uniquekey'] == VikChannelManagerConfig::TRIP_CONNECT ) { // trip advisor ?>
							<li class=""><a href="index.php?option=com_vikchannelmanager&amp;task=inventory"><?php echo JText::_('VCMMENUTACROOMSINV'); ?></a></li>
						<?php } else if( $module['uniquekey'] == VikChannelManagerConfig::TRIVAGO ) { // trivago ?>
							<li class=""><a href="index.php?option=com_vikchannelmanager&amp;task=trinventory"><?php echo JText::_('VCMMENUTACROOMSINV'); ?></a></li>
						<?php } else if( in_array($module['uniquekey'], array(VikChannelManagerConfig::AIRBNB, VikChannelManagerConfig::FLIPKEY, VikChannelManagerConfig::HOLIDAYLETTINGS, VikChannelManagerConfig::WIMDU, VikChannelManagerConfig::HOMEAWAY, VikChannelManagerConfig::VRBO)) ) { // airbnb flipkey holidaylettings wimdu homeaway vrbo ?> 
							<li class="<?php echo $module['name']; ?>"><a href="index.php?option=com_vikchannelmanager&amp;task=listings"><?php echo JText::_('VCMMENULISTINGS'); ?></a></li>
						<?php } ?>
					</ul>
				</li>
				
				<?php if( $module['av_enabled'] == 1 || $module['uniquekey'] == VikChannelManagerConfig::TRIP_CONNECT) { ?>
				<li class="parent<?php echo (self::isOrderHighlighted($highlight) ? ' highlight' : ''); ?> <?php echo $li_mod_class; ?>">
					<a href="index.php?option=com_vikchannelmanager&amp;task=<?php echo self::getOrderDefaultTask($module['uniquekey']); ?>"><i class="vboicn-calendar"></i><?php echo JText::_('VCMMENUORDERS'); ?></a>
					<ul>
						<?php if( in_array($module['uniquekey'], array(VikChannelManagerConfig::EXPEDIA, VikChannelManagerConfig::AGODA, VikChannelManagerConfig::BOOKING, VikChannelManagerConfig::YCS50)) ) { // expedia, agoda, booking.com ?>
							<li class=""><a href="index.php?option=com_vikbooking&amp;task=vieworders"><?php echo JText::_('VCMMENUEXPFROMVB'); ?></a></li>
						<?php } else if( $module['uniquekey'] == VikChannelManagerConfig::TRIP_CONNECT ) { // trip advisor ?> 
							<li class=""><a href="index.php?option=com_vikchannelmanager&amp;task=tacstatus"><?php echo JText::_('VCMMENUTACSTATUS'); ?></a></li>
							<li class=""><a href="index.php?option=com_vikchannelmanager&amp;task=revexpress"><?php echo JText::_('VCMMENUREVEXP'); ?></a></li>
						<?php } ?>
						<?php if( $module['av_enabled'] == 1 ) { ?>
							<li class=""><a href="index.php?option=com_vikchannelmanager&amp;task=oversight"><?php echo JText::_('VCMMENUOVERVIEW'); ?></a></li>
						<?php } ?>
					</ul>
				</li>
				<?php } ?>
				
				<?php if( !empty($module['id']) ) { ?>
					<li class="parent <?php echo $li_mod_class; ?>">
						<a href="index.php?option=com_vikchannelmanager&amp;task=setmodule&amp;id=<?php echo $module['id']; ?>"><i class="vboicn-cloud"></i><?php echo $module['name']; ?></a>
						<ul>
							<?php foreach( $mod_rows as $r ) { ?>
								<li><a href="index.php?option=com_vikchannelmanager&amp;task=setmodule&amp;id=<?php echo $r['id']; ?>"><?php echo $r['name']; ?></a></li>
							<?php } ?>
						</ul>
					</li>
				<?php } else { ?>
					<li class="vcmlidisabled"><a href="index.php?option=com_vikchannelmanager"><?php echo JText::_('VCMNOCHANNELSACTIVE'); ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
		<?php
		$session = JFactory::getSession();
		$sess_notifs = $session->get('vcmNotifications', 0, 'vcm');
		$launch_notifs = !empty($sess_notifs) && $sess_notifs > 0 ? true : false;
		VikChannelManager::retrieveNotifications($launch_notifs);
		
	}
	
	public static function printFooter() {
		echo '<br clear="all" />' . '<div id="hmfooter">VikChannelManager v.'.VIKCHANNELMANAGER_SOFTWARE_VERSION.' - <a href="http://www.extensionsforjoomla.com/" target="_blank">e4j - Extensionsforjoomla.com</a></div>';
	}
	
	private static function isHotelHighlighted($task) {
		$av_task = array('rooms', 'roomsynch', 'hoteldetails', 'inventory', 'trinventory');
		return (@in_array($task, $av_task));
	}
	
	private static function isOrderHighlighted($task) {
		$av_task = array('ordersvb', 'customa', 'tacstatus', 'revexpress');
		return (@in_array($task, $av_task));
	}
	
	private static function getHotelDefaultTask($channel) {
		switch( $channel ) {
			case VikChannelManagerConfig::EXPEDIA: return 'rooms'; break;
			case VikChannelManagerConfig::TRIP_CONNECT: return 'hoteldetails'; break;
			case VikChannelManagerConfig::TRIVAGO: return 'hoteldetails'; break;
			case VikChannelManagerConfig::BOOKING: return 'rooms'; break;
			case VikChannelManagerConfig::AIRBNB: return 'listings'; break;
			case VikChannelManagerConfig::FLIPKEY: return 'listings'; break;
			case VikChannelManagerConfig::HOLIDAYLETTINGS: return 'listings'; break;
			case VikChannelManagerConfig::AGODA: return 'rooms'; break;
			case VikChannelManagerConfig::WIMDU: return 'listings'; break;
			case VikChannelManagerConfig::HOMEAWAY: return 'listings'; break;
			case VikChannelManagerConfig::VRBO: return 'listings'; break;
			case VikChannelManagerConfig::YCS50: return 'rooms'; break;
		}
		
		return "";
	}
	
	private static function getOrderDefaultTask($channel) {
		switch( $channel ) {
			case VikChannelManagerConfig::EXPEDIA: return 'oversight'; break;
			case VikChannelManagerConfig::TRIP_CONNECT: return 'tacstatus'; break;
			case VikChannelManagerConfig::TRIVAGO: return ''; break;
			case VikChannelManagerConfig::BOOKING: return 'oversight'; break;
			case VikChannelManagerConfig::AIRBNB: return ''; break;
			case VikChannelManagerConfig::FLIPKEY: return ''; break;
			case VikChannelManagerConfig::HOLIDAYLETTINGS: return ''; break;
			case VikChannelManagerConfig::AGODA: return 'oversight'; break;
			case VikChannelManagerConfig::WIMDU: return ''; break;
			case VikChannelManagerConfig::HOMEAWAY: return ''; break;
			case VikChannelManagerConfig::VRBO: return ''; break;
			case VikChannelManagerConfig::YCS50: return 'oversight'; break;
		}
		
		return "";
	}
	
	public static function load_css_js() {
		$document = JFactory::getDocument();
		$vik = new VikApplication(VersionListener::getID());
		
		$vik->loadFramework('jquery.framework');
		
		$vik->addScript( JURI::root() . 'administrator/components/com_vikchannelmanager/assets/js/jquery-1.9.1.js');	
		$vik->addScript( JURI::root() . 'administrator/components/com_vikchannelmanager/assets/js/jquery-ui-1.10.2.custom.min.js');
		$document->addStyleSheet( JURI::root() . 'administrator/components/com_vikchannelmanager/assets/css/jquery-ui-1.10.2.custom.css' );
		
		$document->addStyleSheet( JURI::root() . 'administrator/components/com_vikchannelmanager/assets/css/vikchannelmanager.css' );
		$document->addStyleSheet( JURI::root() . 'administrator/components/com_vikchannelmanager/assets/css/vcm-channels.css' );
		if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'fonts'.DS.'vboicomoon.css')) {
			$document->addStyleSheet( JURI::root() . 'administrator/components/com_vikbooking/resources/fonts/vboicomoon.css' );
		}
	}
	
	public static function printBlockVersionView() { ?>
		<div class="vcmprogramblockeddiv">
			<div class="vcmwizardlogodiv">
				<img align="middle" alt="logo vikchannelmanager" src="<?php echo JURI::root(); ?>administrator/components/com_vikchannelmanager/assets/css/images/vikchannelmanager.jpg" />
			</div>
			
			<div class="vcmprogramblockedmsg">
				<span class="vcmprogramblockedlabel">
					<?php echo JText::_('VCMPROGRAMBLOCKEDMESSAGE'); ?>
				</span>
				<span class="vcmprogramblockedbutton">
					<a href="index.php?option=com_vikchannelmanager&task=update_program" class="vcmupdatenowlink"><?php echo JText::_('VCMUPDATENOWBTN'); ?></a>
				</span>
			</div>
		</div>
	<?php }

	public static function parseChannelSettings($channel) {
		
		$dbo = JFactory::getDBO();
		
		$q = "SELECT `settings` FROM `#__vikchannelmanager_channel` WHERE `uniquekey`=".$dbo->quote($channel['idchannel'])." LIMIT 1;";
		$dbo->setQUery($q);
		$dbo->Query($q);
		if( $dbo->getNumRows() == 0 ) {
			return $channel['settings'];
		}
		
		$db_settings = json_decode($dbo->loadResult(), true);
		if( empty($db_settings) ) {
			return $channel['settings'];
		}
		
		foreach( $channel['settings'] as $k => $arr ) {
			if( !array_key_exists($k, $db_settings) ) {
				$db_settings[$k] = $arr;
			}
		}
		
		foreach( $db_settings as $k => $arr ) {
			if( !array_key_exists($k, $channel['settings']) ) {
				unset($db_settings[$k]);
			}
		}
		
		return $db_settings;
		
	}
	
	public static function loadDatePicker() {
		$document = JFactory::getDocument();

		$ldecl = '
		jQuery(function(){'."\n".'
			jQuery.datepicker.regional["vikchannelmanager"] = {'."\n".'
				closeText: "'.JText::_('VCMJQCALDONE').'",'."\n".'
				prevText: "'.JText::_('VCMJQCALPREV').'",'."\n".'
				nextText: "'.JText::_('VCMJQCALNEXT').'",'."\n".'
				currentText: "'.JText::_('VCMJQCALTODAY').'",'."\n".'
				monthNames: ["'.JText::_('VCMMONTHONE').'","'.JText::_('VCMMONTHTWO').'","'.JText::_('VCMMONTHTHREE').'","'.JText::_('VCMMONTHFOUR').'","'.JText::_('VCMMONTHFIVE').'","'.JText::_('VCMMONTHSIX').'","'.JText::_('VCMMONTHSEVEN').'","'.JText::_('VCMMONTHEIGHT').'","'.JText::_('VCMMONTHNINE').'","'.JText::_('VCMMONTHTEN').'","'.JText::_('VCMMONTHELEVEN').'","'.JText::_('VCMMONTHTWELVE').'"],'."\n".'
				monthNamesShort: ["'.mb_substr(JText::_('VCMMONTHONE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VCMMONTHTWO'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VCMMONTHTHREE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VCMMONTHFOUR'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VCMMONTHFIVE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VCMMONTHSIX'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VCMMONTHSEVEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VCMMONTHEIGHT'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VCMMONTHNINE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VCMMONTHTEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VCMMONTHELEVEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VCMMONTHTWELVE'), 0, 3, 'UTF-8').'"],'."\n".'
				dayNames: ["'.JText::_('VCMJQCALSUN').'", "'.JText::_('VCMJQCALMON').'", "'.JText::_('VCMJQCALTUE').'", "'.JText::_('VCMJQCALWED').'", "'.JText::_('VCMJQCALTHU').'", "'.JText::_('VCMJQCALFRI').'", "'.JText::_('VCMJQCALSAT').'"],'."\n".'
				dayNamesShort: ["'.mb_substr(JText::_('VCMJQCALSUN'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::_('VCMJQCALMON'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::_('VCMJQCALTUE'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::_('VCMJQCALWED'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::_('VCMJQCALTHU'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::_('VCMJQCALFRI'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::_('VCMJQCALSAT'), 0, 3, 'UTF-8').'"],'."\n".'
				dayNamesMin: ["'.mb_substr(JText::_('VCMJQCALSUN'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::_('VCMJQCALMON'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::_('VCMJQCALTUE'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::_('VCMJQCALWED'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::_('VCMJQCALTHU'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::_('VCMJQCALFRI'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::_('VCMJQCALSAT'), 0, 2, 'UTF-8').'"],'."\n".'
				weekHeader: "'.JText::_('VCMJQCALWKHEADER').'",'."\n".'
				firstDay: '.JText::_('VCMJQFIRSTDAY').','."\n".'
				isRTL: false,'."\n".'
				showMonthAfterYear: false,'."\n".'
				yearSuffix: ""'."\n".'
			};'."\n".'
			jQuery.datepicker.setDefaults(jQuery.datepicker.regional["vikchannelmanager"]);'."\n".'
		});';
		$document->addScriptDeclaration($ldecl);
	}
	
	/**
	 *	Get the actions
	 */
	public static function getActions($Id = 0) {

		jimport('joomla.access.access');

		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($Id)){
			$assetName = 'com_vikchannelmanager';
		} else {
			$assetName = 'com_vikchannelmanager.message.'.(int) $Id;
		};

		$actions = JAccess::getActions('com_vikchannelmanager', 'component');

		foreach ($actions as $action){
			$result->set($action->name, $user->authorise($action->name, $assetName));
		};

		return $result;
	}
}

class OrderingManager {
	
	private static $_OPTION_;
	private static $_COLUMN_KEY_;
	private static $_TYPE_KEY_;
	
	public function __construct( $option, $column_key, $type_key ) {
		self::$_OPTION_ = $option;
		self::$_COLUMN_KEY_ = $column_key;
		self::$_TYPE_KEY_ = $type_key;
	}
	
	public static function getLinkColumnOrder($task='', $text='', $col='', $type='', $def_type='', $params=array(), $active_class='') {
		if( empty($type) ) {
			$type = $def_type;
			$active_class = '';
		} 
		
		$url = '<a class="'.$active_class.'" href="index.php?option='.self::$_OPTION_.'&task='.$task.'&'.self::$_COLUMN_KEY_.'='.$col.'&'.self::$_TYPE_KEY_.'='.$type;
		if( count( $params ) > 0 ) {
			foreach($params as $key => $val) {
				$url .= '&'.$key.'='.$val;
			}
		}
		
		return $url.'">'.$text.'</a>';
	}
	
	/*
	 * type = 1 ASC 
	 * type = 2 DESC
	 */
	public static function getColumnToOrder($task='', $def_col='', $def_type='', $skip_session=false) {
		$col = JRequest::getString(self::$_COLUMN_KEY_);
		$type = JRequest::getString(self::$_TYPE_KEY_);
		
		$session = JFactory::getSession();
		
		if( empty( $col ) ) {
			$col =  $def_col;
			
			if( !$skip_session ) {
				$app_c = $session->get(self::$_COLUMN_KEY_.'_'.$task, '');
				$app_t = $session->get(self::$_TYPE_KEY_.'_'.$task, '');
				
				if( !empty( $app_c ) ) {
					$col = $app_c;
				}
				
				if( !empty( $app_t ) ) {
					$type = $app_t;
				}
			}
		}
		
		if( empty( $type ) ) {
			$type = $def_type;
		}
		
		$session->set(self::$_COLUMN_KEY_.'_'.$task, $col);
		$session->set(self::$_TYPE_KEY_.'_'.$task, $type);
		
		return array( 'column' => $col, 'type' => $type );
	}
	
	public static function getSwitchColumnType( $task, $col, $curr_type, $types ) {
		$session = JFactory::getSession();
		$old_c = $session->get(self::$_COLUMN_KEY_.'_'.$task, '');
		
		if( $old_c == $col ) {
			$found = -1;
			for( $i = 0; $i < count($types) && $found == -1; $i++ ) {
				if( $types[$i] == $curr_type ) {
					$found = $i;
				}
			}
			
			if( $found != -1 ) {
				$found++;
				if( $found >= count($types) ) {
					$found = 0;
				}
				
				return $types[$found];
			}
		} 
		
		return $types[count($types)-1];
	}
	
}

?>