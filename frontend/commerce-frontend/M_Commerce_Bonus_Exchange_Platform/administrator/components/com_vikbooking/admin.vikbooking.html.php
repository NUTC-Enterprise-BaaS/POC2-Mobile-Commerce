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

defined('_JEXEC') or die('Restricted access');

class HTML_vikbooking {
	public static function printHeader($highlight="") {
		$tmpl = JRequest::getVar('tmpl');
		if ($tmpl == 'component') return;
		if ($highlight == '18' || $highlight=='11') {
			JHTML::_('behavior.modal');
			?>
			<a id="vcheck" href="javascript: void(0);" onblur="this.href='javascript: void(0);';" class="modal" rel="{handler: 'iframe'}" target="_blank" style="font-size:11px; background:#22485d; color:#fff; text-decoration:none; display:inline-block; float:right; padding:3px 8px; border:1px solid #003300; border-radius:5px;" onclick="this.href = '<?php echo strrev(strrev(urlencode(E4J_SOFTWARE_VERSION)).'=rev&'.strrev(urlencode(CREATIVIKAPP)).'=ppa&'.strrev(urlencode(getenv("SERVER_NAME"))).'=ns&'.strrev(urlencode(getenv("HTTP_HOST"))).'=nh?/kcehckiv/moc.almoojrofsnoisnetxe//:ptth'); ?>';"><?php echo strrev('setadpU kcehC'); ?></a>
			<?php
		}
		$channel_manager_btn = '';
		if(file_exists(JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.'lib.vikchannelmanager.php')) {
			$channel_manager_btn = '<li><span><a href="index.php?option=com_vikchannelmanager"><i class="vboicn-cloud"></i>'.JText::_('VBMENUCHANNELMANAGER').'</a></span></li>';
		}
		$vbo_auth_global = JFactory::getUser()->authorise('core.vbo.global', 'com_vikbooking');
		$vbo_auth_rateplans = JFactory::getUser()->authorise('core.vbo.rateplans', 'com_vikbooking');
		$vbo_auth_rooms = JFactory::getUser()->authorise('core.vbo.rooms', 'com_vikbooking');
		$vbo_auth_pricing = JFactory::getUser()->authorise('core.vbo.pricing', 'com_vikbooking');
		$vbo_auth_bookings = JFactory::getUser()->authorise('core.vbo.bookings', 'com_vikbooking');
		$vbo_auth_availability = JFactory::getUser()->authorise('core.vbo.availability', 'com_vikbooking');
		$vbo_auth_management = JFactory::getUser()->authorise('core.vbo.management', 'com_vikbooking');
		?>
		<div class="vbo-menu-container">
			<div class="vbo-menu-left"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikbooking/vikbooking.jpg" alt="VikBooking Logo" /></div>
			<div class="vbo-menu-right">
				<ul class="vbo-menu-ul">
					<?php
					if($vbo_auth_global || $vbo_auth_management) {
					?>
					<li class="vbo-menu-parent-li">
						<span><i class="vboicn-cogs"></i><a href="javascript: void(0);"><?php echo JText::_('VBMENUFOUR'); ?></a></span>
						<ul class="vbo-submenu-ul">
							<?php if($vbo_auth_global) : ?><li><span class="<?php echo ($highlight=="14" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=payments"><?php echo JText::_('VBMENUTENEIGHT'); ?></a></span></li><?php endif; ?>
							<?php if($vbo_auth_global) : ?><li><span class="<?php echo ($highlight=="16" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=viewcustomf"><?php echo JText::_('VBMENUTENTEN'); ?></a></span></li><?php endif; ?>
							<?php if($vbo_auth_management) : ?><li><span class="<?php echo ($highlight=="21" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=translations"><?php echo JText::_('VBMENUTRANSLATIONS'); ?></a></span></li><?php endif; ?>
							<?php if($vbo_auth_global) : ?><li><span class="<?php echo ($highlight=="11" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=config"><?php echo JText::_('VBMENUTWELVE'); ?></a></span></li><?php endif; ?>
						</ul>
					</li>
					<?php
					}
					if($vbo_auth_rateplans) {
					?>
					<li class="vbo-menu-parent-li">
						<span><i class="vboicn-briefcase"></i><a href="javascript: void(0);"><?php echo JText::_('VBMENURATEPLANS'); ?></a></span>
						<ul class="vbo-submenu-ul">
							<li><span class="<?php echo ($highlight=="2" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=viewiva"><?php echo JText::_('VBMENUNINE'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="1" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=viewprices"><?php echo JText::_('VBMENUFIVE'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="17" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=viewcoupons"><?php echo JText::_('VBMENUCOUPONS'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="packages" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=packages"><?php echo JText::_('VBMENUPACKAGES'); ?></a></span></li>
						</ul>
					</li>
					<?php
					}
					if($vbo_auth_rooms || $vbo_auth_pricing) {
					?>
					<li class="vbo-menu-parent-li">
						<span><i class="vboicn-office"></i><a href="javascript: void(0);"><?php echo JText::_('VBMENUTWO'); ?></a></span>
						<ul class="vbo-submenu-ul">
							<?php if($vbo_auth_rooms) : ?><li><span class="<?php echo ($highlight=="4" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=viewcategories"><?php echo JText::_('VBMENUSIX'); ?></a></span></li><?php endif; ?>
							<?php if($vbo_auth_rooms) : ?><li><span class="<?php echo ($highlight=="5" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=viewcarat"><?php echo JText::_('VBMENUTENFOUR'); ?></a></span></li><?php endif; ?>
							<?php if($vbo_auth_pricing) : ?><li><span class="<?php echo ($highlight=="6" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=viewoptionals"><?php echo JText::_('VBMENUTENFIVE'); ?></a></span></li><?php endif; ?>
							<?php if($vbo_auth_rooms) : ?><li><span class="<?php echo ($highlight=="7" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=rooms"><?php echo JText::_('VBMENUTEN'); ?></a></span></li><?php endif; ?>
						</ul>
					</li>
					<?php
					}
					if($vbo_auth_pricing) {
					?>
					<li class="vbo-menu-parent-li">
						<span><i class="vboicn-calculator"></i><a href="javascript: void(0);"><?php echo JText::_('VBMENUFARES'); ?></a></span>
						<ul class="vbo-submenu-ul">
							<li><span class="<?php echo ($highlight=="fares" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=viewtariffe"><?php echo JText::_('VBMENUPRICESTABLE'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="13" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=seasons"><?php echo JText::_('VBMENUTENSEVEN'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="restrictions" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=restrictions"><?php echo JText::_('VBMENURESTRICTIONS'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="20" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=ratesoverview"><?php echo JText::_('VBMENURATESOVERVIEW'); ?></a></span></li>
						</ul>
					</li>
					<?php
					}
					?>
					<li class="vbo-menu-parent-li">
						<span><i class="vboicn-credit-card"></i><a href="javascript: void(0);"><?php echo JText::_('VBMENUTHREE'); ?></a></span>
						<ul class="vbo-submenu-ul">
							<li><span class="<?php echo ($highlight=="18" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking"><?php echo JText::_('VBMENUDASHBOARD'); ?></a></span></li>
							<?php if($vbo_auth_availability || $vbo_auth_bookings) : ?><li><span class="<?php echo ($highlight=="19" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=calendar"><?php echo JText::_('VBMENUQUICKRES'); ?></a></span></li><?php endif; ?>
							<?php if($vbo_auth_availability) : ?><li><span class="<?php echo ($highlight=="15" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=overview"><?php echo JText::_('VBMENUTENNINE'); ?></a></span></li><?php endif; ?>
							<?php if($vbo_auth_bookings) : ?><li><span class="<?php echo ($highlight=="8" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=vieworders"><?php echo JText::_('VBMENUSEVEN'); ?></a></span></li><?php endif; ?>
							<?php echo $vbo_auth_availability || $vbo_auth_bookings ? $channel_manager_btn : ''; ?>
						</ul>
					</li>
					<?php
					if($vbo_auth_management) {
					?>
					<li class="vbo-menu-parent-li">
						<span><i class="vboicn-stats-bars"></i><a href="javascript: void(0);"><?php echo JText::_('VBMENUMANAGEMENT'); ?></a></span>
						<ul class="vbo-submenu-ul">
							<li><span class="<?php echo ($highlight=="22" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=customers"><?php echo JText::_('VBMENUCUSTOMERS'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="invoices" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=invoices"><?php echo JText::_('VBMENUINVOICES'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="stats" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=stats"><?php echo JText::_('VBMENUSTATS'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="crons" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=crons"><?php echo JText::_('VBMENUCRONS'); ?></a></span></li>
						</ul>
					</li>
					<?php
					}
					?>
				</ul>
			</div>
		</div>
		<div style="clear: both;"></div>
		<script type="text/javascript">
		jQuery.noConflict();
		var vbo_menu_on = false;
		jQuery(document).ready(function(){
			jQuery('.vbo-menu-parent-li').click(function() {
				if(jQuery(this).find('ul.vbo-submenu-ul').is(':visible')) {
					vbo_menu_on = false;
					return;
				}
				jQuery('ul.vbo-submenu-ul').hide();
				jQuery(this).find('ul.vbo-submenu-ul').show();
				vbo_menu_on = true;
			});
			jQuery('.vbo-menu-parent-li').hover(
				function() {
					if(vbo_menu_on === true) {
						jQuery(this).find('ul.vbo-submenu-ul').show();
					}
				},function() {
					if(vbo_menu_on === true) {
						jQuery(this).find('ul.vbo-submenu-ul').hide();
					}
				}
			);
			var targetY = jQuery('.vbo-menu-right').offset().top + jQuery('.vbo-menu-right').outerHeight() + 150;
			jQuery(document).click(function(event) { 
				if(!jQuery(event.target).closest('.vbo-menu-right').length && parseInt(event.which) == 1 && event.pageY < targetY) {
					jQuery('ul.vbo-submenu-ul').hide();
					vbo_menu_on = false;
				}
			});
			if(jQuery('.vmenulinkactive').length) {
				jQuery('.vmenulinkactive').parent('li').parent('ul').show();
				jQuery('.vmenulinkactive').parent('li').parent('ul').parent('li').addClass('vbo-menu-parent-li-active');
			}
		});
		</script>
		<?php	
	}
	
	public static function printFooter() {
		$tmpl = JRequest::getVar('tmpl');
		if ($tmpl == 'component') return;
		echo '<br clear="all" />' . '<div id="hmfooter">' . JText::sprintf('VBFOOTER', E4J_SOFTWARE_VERSION) . ' <a href="http://www.extensionsforjoomla.com/">e4j - Extensionsforjoomla.com</a></div>';
	}
	
	public static function pViewDashboard($arrayfirst, $nextreservations, $checkin_today, $checkout_today, $rooms_locked, $option) {
		$document = JFactory::getDocument();
		$nowdf = vikbooking::getDateFormat(true);
		if ($nowdf=="%d/%m/%Y") {
			$df='d/m/Y';
		}elseif ($nowdf=="%m/%d/%Y") {
			$df='m/d/Y';
		}else {
			$df='Y/m/d';
		}
		$up_running = true;
		if($arrayfirst['totprices'] < 1 || $arrayfirst['totrooms'] < 1 || $arrayfirst['tot_rooms_units'] < 1 || $arrayfirst['totdailyfares'] < 1) {
			$up_running = false;
			?>
		<div class="vbo-dashboard-firstsetup">
			<h3 class="vbdashdivlefthead"><?php echo JText::_('VBDASHUPCRES'); ?></h3>
			<?php
			if($arrayfirst['totprices'] < 1) {
				?>
			<p class="vbdashparagred"><?php echo JText::_('VBDASHNOPRICES'); ?>: 0</p>
				<?php
			}
			if($arrayfirst['totrooms'] < 1) {
				?>
			<p class="vbdashparagred"><?php echo JText::_('VBDASHNOROOMS'); ?>: 0</p>
				<?php
			}
			if($arrayfirst['totdailyfares'] < 1) {
				?>
			<p class="vbdashparagred"><?php echo JText::_('VBDASHNODAILYFARES'); ?>: 0</p>
				<?php
			}
			?>
		</div>
			<?php
		}
		if($up_running === true) {
			//First setup complete. Show reports, check-ins and check-outs today, next bookings
			JHTML::_('behavior.keepalive');
			$document->addScript(JURI::root().'administrator/components/com_vikbooking/resources/donutChart.js');
			$wdaysmap = array('0' => JText::_('VBSUNDAY'), '1' => JText::_('VBMONDAY'), '2' => JText::_('VBTUESDAY'), '3' => JText::_('VBWEDNESDAY'), '4' => JText::_('VBTHURSDAY'), '5' => JText::_('VBFRIDAY'), '6' => JText::_('VBSATURDAY'));
			//Todays Check-in
			?>
		<div class="vbo-dashboard-today-bookings">
			<div class="vbo-dashboard-today-checkin-wrapper">
				<h4><?php echo JText::_('VBDASHTODAYCHECKIN'); ?></h4>
				<div class="vbo-dashboard-today-checkin table-responsive">
					<table class="table">
						<tr class="vbo-dashboard-today-checkin-firstrow">
							<td align="center"><?php echo JText::_('VBDASHUPRESONE'); ?></td>
							<td align="center"><?php echo JText::_('VBCUSTOMERNOMINATIVE'); ?></td>
							<td align="center"><?php echo JText::_('VBDASHUPRESSIX'); ?></td>
							<td align="center"><?php echo JText::_('VBDASHUPRESTWO'); ?></td>
							<td align="center"><?php echo JText::_('VBDASHUPRESFOUR'); ?></td>
							<td align="center"><?php echo JText::_('VBDASHUPRESFIVE'); ?></td>
						</tr>
					<?php
					foreach ($checkin_today as $ink => $intoday) {
						$totpeople_str = $intoday['tot_adults']." ".($intoday['tot_adults'] > 1 ? JText::_('VBMAILADULTS') : JText::_('VBMAILADULT')).($intoday['tot_children'] > 0 ? ", ".$intoday['tot_children']." ".($intoday['tot_children'] > 1 ? JText::_('VBMAILCHILDREN') : JText::_('VBMAILCHILD')) : "");
						if ($intoday['status'] == 'confirmed') {
							$ord_status = '<span style="font-weight: bold; color: green;">'.strtoupper(JText::_('VBCONFIRMED')).'</span>';
						}elseif ($intoday['status'] == 'standby') {
							$ord_status = '<span style="font-weight: bold; color: #cc9a04;">'.strtoupper(JText::_('VBSTANDBY')).'</span>';
						}else {
							$ord_status = '<span style="font-weight: bold; color: red;">'.strtoupper(JText::_('VBCANCELLED')).'</span>';
						}
						$nominative = strlen($intoday['nominative']) > 1 ? $intoday['nominative'] : vikbooking::getFirstCustDataField($intoday['custdata']);
						$country_flag = '';
						if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'countries'.DS.$intoday['country'].'.png')) {
							$country_flag = '<img src="'.JURI::root().'administrator/components/com_vikbooking/resources/countries/'.$intoday['country'].'.png'.'" title="'.$intoday['country'].'" class="vbo-country-flag vbo-country-flag-left"/>';
						}
						?>
						<tr class="vbo-dashboard-today-checkin-rows">
							<td align="center"><a href="index.php?option=com_vikbooking&amp;task=editorder&amp;cid[]=<?php echo $intoday['id']; ?>"><?php echo $intoday['id']; ?></a></td>
							<td align="center"><?php echo $country_flag.$nominative; ?></td>
							<td align="center"><?php echo $totpeople_str; ?></td>
							<td align="center"><?php echo $intoday['roomsnum']; ?></td>
							<td align="center"><?php echo date($df.' H:i', $intoday['checkout']); ?></td>
							<td align="center"><?php echo $ord_status; ?></td>
						</tr>
						<?php
					}
					?>
					</table>
				</div>
			</div>
			<?php
			//Todays Check-out
			?>
			<div class="vbo-dashboard-today-checkout-wrapper">
				<h4><?php echo JText::_('VBDASHTODAYCHECKOUT'); ?></h4>
				<div class="vbo-dashboard-today-checkout table-responsive">
					<table class="table">
						<tr class="vbo-dashboard-today-checkout-firstrow">
							<td align="center"><?php echo JText::_('VBDASHUPRESONE'); ?></td>
							<td align="center"><?php echo JText::_('VBCUSTOMERNOMINATIVE'); ?></td>
							<td align="center"><?php echo JText::_('VBDASHUPRESSIX'); ?></td>
							<td align="center"><?php echo JText::_('VBDASHUPRESTWO'); ?></td>
							<td align="center"><?php echo JText::_('VBDASHUPRESTHREE'); ?></td>
							<td align="center"><?php echo JText::_('VBDASHUPRESFIVE'); ?></td>
						</tr>
					<?php
					foreach ($checkout_today as $outk => $outtoday) {
						$totpeople_str = $outtoday['tot_adults']." ".($outtoday['tot_adults'] > 1 ? JText::_('VBMAILADULTS') : JText::_('VBMAILADULT')).($outtoday['tot_children'] > 0 ? ", ".$outtoday['tot_children']." ".($outtoday['tot_children'] > 1 ? JText::_('VBMAILCHILDREN') : JText::_('VBMAILCHILD')) : "");
						if ($outtoday['status'] == 'confirmed') {
							$ord_status = '<span style="font-weight: bold; color: green;">'.strtoupper(JText::_('VBCONFIRMED')).'</span>';
						}elseif ($outtoday['status'] == 'standby') {
							$ord_status = '<span style="font-weight: bold; color: #cc9a04;">'.strtoupper(JText::_('VBSTANDBY')).'</span>';
						}else {
							$ord_status = '<span style="font-weight: bold; color: red;">'.strtoupper(JText::_('VBCANCELLED')).'</span>';
						}
						$nominative = strlen($outtoday['nominative']) > 1 ? $outtoday['nominative'] : vikbooking::getFirstCustDataField($outtoday['custdata']);
						$country_flag = '';
						if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'countries'.DS.$outtoday['country'].'.png')) {
							$country_flag = '<img src="'.JURI::root().'administrator/components/com_vikbooking/resources/countries/'.$outtoday['country'].'.png'.'" title="'.$outtoday['country'].'" class="vbo-country-flag vbo-country-flag-left"/>';
						}
						?>
						<tr class="vbo-dashboard-today-checkout-rows">
							<td align="center"><a href="index.php?option=com_vikbooking&amp;task=editorder&amp;cid[]=<?php echo $outtoday['id']; ?>"><?php echo $outtoday['id']; ?></a></td>
							<td align="center"><?php echo $country_flag.$nominative; ?></td>
							<td align="center"><?php echo $totpeople_str; ?></td>
							<td align="center"><?php echo $outtoday['roomsnum']; ?></td>
							<td align="center"><?php echo date($df.' H:i', $outtoday['checkin']); ?></td>
							<td align="center"><?php echo $ord_status; ?></td>
						</tr>
						<?php
					}
					?>
					</table>
				</div>
			</div>
		</div>
			<?php
			$busy = vikbooking::loadBusyRecords(array_keys($arrayfirst['all_rooms_ids']), $arrayfirst['today_end_ts']);
			//Used for Today's Rooms Occupancy
			$today_tot_occupancy = 0;
			//
			//Chart for Rooms Sold Today and all week
			?>
		<div class="vbo-dashboard-charts">
			<h4><?php echo JText::_('VBDASHWEEKGLOBAVAIL'); ?></h4>
			<div class="vbo-dashboard-charts-wrapper">
			<?php
			$is_dst = date('I', $arrayfirst['today_end_ts']);
			for($i = 0; $i < 7; $i++) {
				$today_ts = $arrayfirst['today_end_ts'] + ($i * 86400);
				$is_now_dst = date('I', $today_ts);
				if($is_dst != $is_now_dst) {
					if ((bool)$is_dst === true) {
						$today_ts += 3600;
						$season_fromdayts += 3600;
					}else {
						$today_ts -= 3600;
					}
					$is_dst = $is_now_dst;
				}
				$today_info = getdate($today_ts);
				$tot_booked_today = 0;
				if(count($busy) > 0) {
					foreach ($busy as $idroom => $rbusy) {
						foreach ($rbusy as $b) {
							$tmpone=getdate($b['checkin']);
							$rit=($tmpone['mon'] < 10 ? "0".$tmpone['mon'] : $tmpone['mon'])."/".($tmpone['mday'] < 10 ? "0".$tmpone['mday'] : $tmpone['mday'])."/".$tmpone['year'];
							$ritts=strtotime($rit);
							$tmptwo=getdate($b['checkout']);
							$con=($tmptwo['mon'] < 10 ? "0".$tmptwo['mon'] : $tmptwo['mon'])."/".($tmptwo['mday'] < 10 ? "0".$tmptwo['mday'] : $tmptwo['mday'])."/".$tmptwo['year'];
							$conts=strtotime($con);
							if ($today_ts >= $ritts && $today_ts < $conts) {
								$tot_booked_today++;
							}
						}
					}
				}
				$percentage_booked = round((100 * $tot_booked_today / $arrayfirst['tot_rooms_units']), 2);
				$outer_color = '#2a762c'; //green
				if($percentage_booked > 33 && $percentage_booked <= 66) {
					$outer_color = '#ffa64d'; //orange
				}elseif($percentage_booked > 66 && $percentage_booked < 100) {
					$outer_color = '#ff4d4d'; //red
				}elseif($percentage_booked >= 100) {
					$outer_color = '#550000'; //black-red
				}
				//Used for Today's Rooms Occupancy
				$today_tot_occupancy = $i == 0 ? $tot_booked_today : $today_tot_occupancy;
				//
				?>
				<div class="vbo-dashboard-chart-container" id="vbo-dashboard-chart-container-<?php echo ($i + 1); ?>">
					<span class="vbo-dashboard-chart-date"><?php echo $i == 0 ? JText::_('VBTODAY').', ' : ''; ?><?php echo $wdaysmap[(string)$today_info['wday']]; ?> <?php echo $today_info['mday']; ?></span>
				</div>
				<script type="text/JavaScript">
				var todaychart = new donutChart("vbo-dashboard-chart-container-<?php echo ($i + 1); ?>");
				todaychart.draw({
					start: 0,
					end: <?php echo $tot_booked_today; ?>,
					maxValue: <?php echo $arrayfirst['tot_rooms_units']; ?>,
					size: 160,
					unitText: " / <?php echo $arrayfirst['tot_rooms_units']; ?>",
					animationSpeed: 3,
					textColor: "#22485d",
					titlePosition: "outer-top", //outer-bottom, outer-top, inner-bottom, inner-top
					titleText: "",
					titleColor: '#333333',
					outerCircleColor: '<?php echo $outer_color; ?>',
					innerCircleColor: '#ffffff',
					innerCircleStroke: '#333333'
				});
				</script>
				<?php
			}
			?>
			</div>
			<div class="vbo-dashboard-refresh-container">
				<div class="vbo-dashboard-refresh-head"><span class="vbo-dashboard-refresh-label"><?php echo JText::_('VBDASHNEXTREFRESH'); ?></span> <span class="vbo-dashboard-refresh-minutes">05</span>:<span class="vbo-dashboard-refresh-seconds">00</span></div>
				<span class="vbo-dashboard-refresh-stop"> </span>
				<span class="vbo-dashboard-refresh-play" style="display: none;"> </span>
			</div>
			<script type="text/JavaScript">
			var vbo_dash_counter = 300;
			var vbo_t;
			var vbo_m = 5;
			var vbo_s = 0;
			var vbo_t_on = false;
			function vboRefreshTimer() {
				vbo_dash_counter--;
				if(vbo_dash_counter <= 0) {
					vbo_t_on = false;
					clearTimeout(vbo_t);
					location.reload();
					return true;
				}
				vbo_m = Math.floor(vbo_dash_counter / 60);
				vbo_s = Math.floor((vbo_dash_counter - (vbo_m * 60)));
				jQuery(".vbo-dashboard-refresh-minutes").text("0"+vbo_m);
				jQuery(".vbo-dashboard-refresh-seconds").text((parseInt(vbo_s) < 10 ? "0"+vbo_s : vbo_s));
				vbo_t = setTimeout(vboRefreshTimer, 1000);
			}
			function vboStartTimer() {
				vbo_t = setTimeout(vboRefreshTimer, 1000);
				vbo_t_on = true;
			}
			jQuery(document).ready(function() {
				vboStartTimer();
				jQuery(".vbo-dashboard-refresh-stop").click(function(){
					if(vbo_t_on) {
						vbo_t_on = false;
						clearTimeout(vbo_t);
						jQuery(".vbo-dashboard-refresh-play").fadeIn();
					}else {
						jQuery(this).parent().fadeOut();
					}
				});
				jQuery(".vbo-dashboard-refresh-play").click(function(){
					if(!vbo_t_on) {
						vboStartTimer();
						jQuery(this).fadeOut();
					}
				});
			});
			</script>
		</div>
			<?php
			//Today's Rooms Occupancy
			if($today_tot_occupancy > 0) {
				$today_rbookmap = array();
				$today_bidbookmap = array();
				foreach ($busy as $idroom => $rbusy) {
					foreach ($rbusy as $b) {
						$tmpone=getdate($b['checkin']);
						$rit=($tmpone['mon'] < 10 ? "0".$tmpone['mon'] : $tmpone['mon'])."/".($tmpone['mday'] < 10 ? "0".$tmpone['mday'] : $tmpone['mday'])."/".$tmpone['year'];
						$ritts=strtotime($rit);
						$tmptwo=getdate($b['checkout']);
						$con=($tmptwo['mon'] < 10 ? "0".$tmptwo['mon'] : $tmptwo['mon'])."/".($tmptwo['mday'] < 10 ? "0".$tmptwo['mday'] : $tmptwo['mday'])."/".$tmptwo['year'];
						$conts=strtotime($con);
						if ($arrayfirst['today_end_ts'] >= $ritts && $arrayfirst['today_end_ts'] < $conts) {
							if(array_key_exists($b['idroom'], $today_rbookmap)) {
								$today_rbookmap[$b['idroom']]++;
								$today_bidbookmap[$b['idroom']][] = $b['id'];
							}else {
								$today_rbookmap[$b['idroom']] = 1;
								$today_bidbookmap[$b['idroom']] = array($b['id']);
							}
						}
					}
				}
				?>
		<div class="vbo-dashboard-today-occ-block">
			<div class="vbo-dashboard-today-occ">
				<h4><?php echo JText::_('VBDASHTODROCC'); ?></h4>
				<div class="vbo-dashboard-today-occ-listcont">
				<?php
				foreach ($today_rbookmap as $idr => $rbked) {
					$room_bookings_det = vikbooking::getRoomBookingsFromBusyIds($idr, $today_bidbookmap[$idr]);
					?>
					<div class="vbo-dashboard-today-roomocc">
						<div class="vbo-dashboard-today-roomocc-det">
							<h5><?php echo $arrayfirst['all_rooms_ids'][$idr]; ?> <span><?php echo $rbked; ?></span> / <span><?php echo $arrayfirst['all_rooms_units'][$idr]; ?></span></h5>
							<div class="vbo-dashboard-today-roomocc-customers table-responsive">
								<table class="table">
									<tr class="vbo-dashboard-today-roomocc-firstrow">
										<td><?php echo JText::_('VBCUSTOMERNOMINATIVE'); ?></td>
										<td align="center">&nbsp;</td>
										<td align="center"><?php echo JText::_('VBDASHUPRESFOUR'); ?></td>
									</tr>
								<?php
								foreach ($room_bookings_det as $rbind => $room_booking) {
									$nominative = strlen($room_booking['nominative']) > 1 ? $room_booking['nominative'] : vikbooking::getFirstCustDataField($room_booking['custdata']);
									$country_flag = '';
									if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'countries'.DS.$room_booking['country'].'.png')) {
										$country_flag = '<img src="'.JURI::root().'administrator/components/com_vikbooking/resources/countries/'.$room_booking['country'].'.png'.'" title="'.$room_booking['country'].'" class="vbo-country-flag vbo-country-flag-left"/>';
									}
									//Room specific unit
									$room_first_feature = '&nbsp;';
									if(!empty($room_booking['roomindex']) && array_key_exists($idr, $arrayfirst['all_rooms_features']) && count($arrayfirst['all_rooms_features'][$idr]) > 0) {
										foreach ($arrayfirst['all_rooms_features'][$idr] as $rind => $rfeatures) {
											if($rind != $room_booking['roomindex']) {
												continue;
											}
											foreach ($rfeatures as $fname => $fval) {
												if(strlen($fval)) {
													$room_first_feature = '#'.$rind.' - '.JText::_($fname).': '.$fval;
													break 2;
												}
											}
										}
									}
									//
									?>
									<tr class="vbo-dashboard-today-roomocc-rows">
										<td><?php echo $country_flag.'<a href="index.php?option=com_vikbooking&task=editorder&cid[]='.$room_booking['idorder'].'" target="_blank">'.$nominative.'</a>'; ?></td>
										<td align="center"><?php echo $room_first_feature; ?></td>
										<td align="center"><?php echo date($df.' H:i', $room_booking['checkout']); ?></td>
									</tr>
									<?php
								}
								?>
								</table>
							</div>
						</div>
					</div>
					<?php
				}
				?>
				</div>
			</div>
		</div>
				<?php
			}
			//
			//Next Bookings
			?>
		<div class="vbo-dashboard-next-bookings-block">
			<div class="vbo-dashboard-next-bookings table-responsive">
				<h4><?php echo JText::_('VBDASHUPCRES'); ?></h4>
				<table class="table">
					<tr class="vbo-dashboard-today-checkout-firstrow">
						<td align="center"><?php echo JText::_('VBDASHUPRESONE'); ?></td>
						<td align="center"><?php echo JText::_('VBCUSTOMERNOMINATIVE'); ?></td>
						<td align="center"><?php echo JText::_('VBDASHUPRESSIX'); ?></td>
						<td align="center"><?php echo JText::_('VBDASHUPRESTWO'); ?></td>
						<td align="center"><?php echo JText::_('VBDASHUPRESTHREE'); ?></td>
						<td align="center"><?php echo JText::_('VBDASHUPRESFOUR'); ?></td>
						<td align="center"><?php echo JText::_('VBDASHUPRESFIVE'); ?></td>
					</tr>
				<?php
				foreach ($nextreservations as $nbk => $next) {
					$totpeople_str = $next['tot_adults']." ".($next['tot_adults'] > 1 ? JText::_('VBMAILADULTS') : JText::_('VBMAILADULT')).($next['tot_children'] > 0 ? ", ".$next['tot_children']." ".($next['tot_children'] > 1 ? JText::_('VBMAILCHILDREN') : JText::_('VBMAILCHILD')) : "");
					if ($next['status'] == 'confirmed') {
						$ord_status = '<span style="font-weight: bold; color: green;">'.strtoupper(JText::_('VBCONFIRMED')).'</span>';
					}elseif ($next['status'] == 'standby') {
						$ord_status = '<span style="font-weight: bold; color: #cc9a04;">'.strtoupper(JText::_('VBSTANDBY')).'</span>';
					}else {
						$ord_status = '<span style="font-weight: bold; color: red;">'.strtoupper(JText::_('VBCANCELLED')).'</span>';
					}
					$nominative = strlen($next['nominative']) > 1 ? $next['nominative'] : vikbooking::getFirstCustDataField($next['custdata']);
					$country_flag = '';
					if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'countries'.DS.$next['country'].'.png')) {
						$country_flag = '<img src="'.JURI::root().'administrator/components/com_vikbooking/resources/countries/'.$next['country'].'.png'.'" title="'.$next['country'].'" class="vbo-country-flag vbo-country-flag-left"/>';
					}
					?>
					<tr class="vbo-dashboard-today-checkout-rows">
						<td align="center"><a href="index.php?option=com_vikbooking&amp;task=editorder&amp;cid[]=<?php echo $next['id']; ?>"><?php echo $next['id']; ?></a></td>
						<td align="center"><?php echo $country_flag.$nominative; ?></td>
						<td align="center"><?php echo $totpeople_str; ?></td>
						<td align="center"><?php echo $next['roomsnum']; ?></td>
						<td align="center"><?php echo date($df.' H:i', $next['checkin']); ?></td>
						<td align="center"><?php echo date($df.' H:i', $next['checkout']); ?></td>
						<td align="center"><?php echo $ord_status; ?></td>
					</tr>
					<?php
				}
				?>
				</table>
			</div>
		</div>
			<?php
			//Rooms Locked
			if(count($rooms_locked)) {
				?>
		<div class="vbo-dashboard-rooms-locked-block">
			<div class="vbo-dashboard-rooms-locked table-responsive">
				<h4 id="vbo-dashboard-rooms-locked-toggle"><?php echo JText::_('VBDASHROOMSLOCKED'); ?><span>(<?php echo count($rooms_locked); ?>)</span></h4>
				<table class="table" style="display: none;">
					<tr class="vbo-dashboard-rooms-locked-firstrow">
						<td align="center"><?php echo JText::_('VBDASHROOMNAME'); ?></td>
						<td align="center"><?php echo JText::_('VBCUSTOMERNOMINATIVE'); ?></td>
						<td align="center"><?php echo JText::_('VBDASHLOCKUNTIL'); ?></td>
						<td align="center"><?php echo JText::_('VBDASHBOOKINGID'); ?></td>
						<td align="center">&nbsp;</td>
					</tr>
				<?php
				foreach ($rooms_locked as $lock) {
					?>
					<tr class="vbo-dashboard-rooms-locked-rows">
						<td align="center"><?php echo $lock['name']; ?></td>
						<td align="center"><?php echo $lock['nominative']; ?></td>
						<td align="center"><?php echo date($df.' H:i', $lock['until']); ?></td>
						<td align="center"><a href="index.php?option=com_vikbooking&amp;task=editorder&amp;cid[]=<?php echo $lock['idorder']; ?>" target="_blank"><?php echo $lock['idorder']; ?></a></td>
						<td align="center"><button type="button" class="btn btn-danger" onclick="if(confirm('<?php echo addslashes(JText::_('VBDELCONFIRM')); ?>')) location.href='index.php?option=com_vikbooking&amp;task=unlockrecords&amp;cid[]=<?php echo $lock['id']; ?>';"><?php echo JText::_('VBDASHUNLOCK'); ?></button></td>
					</tr>
					<?php
				}
				?>
				</table>
			</div>
		</div>
		<script type="text/JavaScript">
		jQuery(document).ready(function() {
			jQuery("#vbo-dashboard-rooms-locked-toggle").click(function(){
				jQuery(this).next("table").fadeToggle();
			});
		});
		</script>
				<?php
			}
		}
	}
	
	public static function printHeaderRoom($room, $name, $prezzi, $idroom, $allc) {
		if (file_exists('../components/com_vikbooking/resources/uploads/'.$room) && getimagesize('../components/com_vikbooking/resources/uploads/'.$room)) {
			$img='<img align="middle" class="maxninety" alt="Room Image" src="' . JURI::root() . 'components/com_vikbooking/resources/uploads/'.$room.'" />';
		}else {
			$img='<img align="middle" alt="vikbooking logo" src="' . JURI::root() . 'administrator/components/com_vikbooking/vikbooking.jpg' . '" />';
		}
		//$fprice="<p class=\"vbadminfaresctitle\">".$name." - ".JText::_('VBINSERTFEE')."</p>\n";
		$fprice="<div class=\"dailypricesactive\">".JText::_('VBDAILYFARES')."</div>\n";
		//
		if (empty($prezzi)) {
			$fprice.="<br/><span class=\"err\"><b>".JText::_('VBMSGONE')." <a href=\"index.php?option=com_vikbooking&task=newprice\">".JText::_('VBHERE')."</a></b></span>";
		}else {
			$colsp="2";
			$fprice.="<form name=\"newd\" method=\"post\" action=\"index.php?option=com_vikbooking\" onsubmit=\"javascript: if(!document.newd.ddaysfrom.value.match(/\S/)){alert('".JText::_('VBMSGTWO')."'); return false;}else{return true;}\">\n<br clear=\"all\"/><div class=\"vbo-insertrates-cont\"><span class=\"vbo-ratestable-lbl\">".JText::_('VBDAYS').": </span><br/><table><tr><td>".JText::_('VBDAYSFROM')." <input type=\"number\" name=\"ddaysfrom\" id=\"ddaysfrom\" value=\"\" min=\"1\" placeholder=\"1\" /></td><td>&nbsp;&nbsp;&nbsp; ".JText::_('VBDAYSTO')." <input type=\"number\" name=\"ddaysto\" id=\"ddaysto\" value=\"\" min=\"1\" max=\"999\" placeholder=\"30\" /></td></tr></table>\n";
			$fprice.="<br/><span class=\"vbo-ratestable-lbl\">".JText::_('VBDAILYPRICES').": </span><br/><table>\n";
			$currencysymb=vikbooking::getCurrencySymb(true);
			foreach($prezzi as $pr){
				$fprice.="<tr><td>".$pr['name'].": </td><td>".$currencysymb." <input type=\"text\" name=\"dprice".$pr['id']."\" value=\"\" size=\"10\"/></td>";
				if (!empty($pr['attr'])) {
					$colsp="4";
					$fprice.="<td>".$pr['attr']."</td><td><input type=\"text\" name=\"dattr".$pr['id']."\" value=\"\" size=\"10\"/></td>";
				}
				$fprice.="</tr>\n";
			}
			$fprice.="<tr><td colspan=\"".$colsp."\" align=\"right\"><input type=\"submit\" class=\"vbsubmitfares\" name=\"newdispcost\" value=\"".JText::_('VBINSERT')."\"/></td></tr></table></div><input type=\"hidden\" name=\"cid[]\" value=\"".$idroom."\"/><input type=\"hidden\" name=\"task\" value=\"viewtariffe\"/></form>";
		}
		$chroomsel = "<select name=\"cid[]\" onchange=\"javascript: document.vbchroom.submit();\">\n";
		foreach($allc as $cc) {
			$chroomsel .= "<option value=\"".$cc['id']."\"".($cc['id'] == $idroom ? " selected=\"selected\"" : "").">".$cc['name']."</option>\n";
		}
		$chroomsel .= "</select>\n";
		$chroomf = "<form name=\"vbchroom\" method=\"post\" action=\"index.php?option=com_vikbooking\"><input type=\"hidden\" name=\"task\" value=\"viewtariffe\"/>".JText::_('VBSELVEHICLE').": ".$chroomsel."</form>";
		echo "<table><tr><td colspan=\"2\" valign=\"top\" align=\"left\"><div class=\"vbadminfaresctitle\">".$name." - ".JText::_('VBINSERTFEE')." <span style=\"float: right; text-transform: none;\">".$chroomf."</span></div></td></tr><tr><td valign=\"top\" align=\"left\">".$img."</td><td valign=\"top\" align=\"left\">".$fprice."</td></tr></table><br/>\n";
		?>
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#ddaysfrom').change(function() {
				var fnights = parseInt(jQuery(this).val());
				if(!isNaN(fnights)) {
					jQuery('#ddaysto').attr('min', fnights);
					var tnights = jQuery('#ddaysto').val();
					if(!(tnights.length > 0)) {
						jQuery('#ddaysto').val(fnights);
					}else {
						if(parseInt(tnights) < fnights) {
							jQuery('#ddaysto').val(fnights);
						}
					}
				}
			});
		});
		</script>
		<?php
	}
	
	public static function printHeaderBusy ($arrheader) {
		echo "<table><tr><td><div class=\"vbadminfaresctitle\">".JText::_('VBMODRES')."</div></td></tr></table><br/>\n";
	}
	
	public static function printHeaderCalendar($room, $msg, $allc, $payments) {
		$dbo = JFactory::getDBO();
		$document = JFactory::getDocument();
		$document->addStyleSheet('components/com_vikbooking/resources/jquery.highlighttextarea.min.css');
		JHtml::_('script', JURI::root().'administrator/components/com_vikbooking/resources/jquery.highlighttextarea.min.js', false, true, false, false);
		JHTML::_('behavior.calendar');
		$fquick="";
		if ($msg=="1") {
			$fquick.="<br/><p class=\"successmade\" style=\"margin-top: -15px;\">".JText::_('VBBOOKMADE')."</p>";
		}elseif ($msg=="0") {
			$fquick.="<br/><p class=\"err\" style=\"margin-top: -15px;\">".JText::_('VBBOOKNOTMADE')."</p>";
		}
		$fquick.="<form name=\"newb\" method=\"post\" action=\"index.php?option=com_vikbooking\" onsubmit=\"javascript: if(!document.newb.checkindate.value.match(/\S/)){alert('".JText::_('VBMSGTHREE')."'); return false;} if(!document.newb.checkoutdate.value.match(/\S/)){alert('".JText::_('VBMSGFOUR')."'); return false;} return true;\">";
		
		$timeopst = vikbooking::getTimeOpenStore();
		if (is_array($timeopst)) {
			$opent = vikbooking::getHoursMinutes($timeopst[0]);
			$closet = vikbooking::getHoursMinutes($timeopst[1]);
			$hcheckin = $opent[0];
			$mcheckin = $opent[1];
			$hcheckout = $closet[0];
			$mcheckout = $closet[1];
		} else {
			$hcheckin = 0;
			$mcheckin = 0;
			$hcheckout = 0;
			$mcheckout = 0;
		}
		$globnumadults = vikbooking::getSearchNumAdults(true);
		$adultsparts = explode('-', $globnumadults);
		$seladults = "<select name=\"adults\">\n";
		for($i=$adultsparts[0]; $i <= $adultsparts[1]; $i++){
			$seladults .= "<option value=\"".$i."\"".(intval($adultsparts[0]) < 1 && $i == 1 ? " selected=\"selected\"" : "").">".$i."</option>\n";
		}
		$seladults .= "</select>\n";
		$globnumchildren = vikbooking::getSearchNumChildren(true);
		$childrenparts = explode('-', $globnumchildren);
		$selchildren = "<select name=\"children\">\n";
		for($i=$childrenparts[0]; $i <= $childrenparts[1]; $i++){
			$selchildren .= "<option value=\"".$i."\">".$i."</option>\n";
		}
		$selchildren .= "</select>\n";
		$selpayments = '<select name="payment"><option value="">'.JText::_('VBPAYMUNDEFINED').'</option>';
		if (is_array($payments) && @count($payments) > 0) {
			foreach ($payments as $pay) {
				$selpayments .= '<option value="'.$pay['id'].'">'.$pay['name'].'</option>';
			}
		}
		$selpayments .= '</select>';
		//Custom Fields
		$cfields_cont = '';
		$q = "SELECT * FROM `#__vikbooking_custfields` ORDER BY `#__vikbooking_custfields`.`ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$all_cfields = $dbo->loadAssocList();
			$q = "SELECT * FROM `#__vikbooking_countries` ORDER BY `#__vikbooking_countries`.`country_name` ASC;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$all_countries = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : array();
			foreach ($all_cfields as $cfield) {
				if($cfield['type'] == 'text') {
					$cfields_cont .= '<div class="vbo-calendar-cfield-entry"><label for="cfield'.$cfield['id'].'" data-fieldid="'.$cfield['id'].'">'.JText::_($cfield['name']).'</label><span><input type="text" id="cfield'.$cfield['id'].'" data-isemail="'.($cfield['isemail'] == 1 ? '1' : '0').'" data-isnominative="'.($cfield['isnominative'] == 1 ? '1' : '0').'" data-isphone="'.($cfield['isphone'] == 1 ? '1' : '0').'" value="" size="35"/></span></div>'."\n";
				}elseif($cfield['type'] == 'textarea') {
					$cfields_cont .= '<div class="vbo-calendar-cfield-entry"><label for="cfield'.$cfield['id'].'" data-fieldid="'.$cfield['id'].'">'.JText::_($cfield['name']).'</label><span><textarea id="cfield'.$cfield['id'].'" rows="4" cols="35"></textarea></span></div>'."\n";
				}elseif($cfield['type'] == 'country') {
					$cfields_cont .= '<div class="vbo-calendar-cfield-entry"><label for="cfield'.$cfield['id'].'" data-fieldid="'.$cfield['id'].'">'.JText::_($cfield['name']).'</label><span><select id="cfield'.$cfield['id'].'"><option value=""> </option>'."\n";
					foreach ($all_countries as $country) {
						$cfields_cont .= '<option value="'.$country['country_name'].'" data-ccode="'.$country['country_3_code'].'">'.$country['country_name'].'</option>';
					}
					$cfields_cont .= '</select></span></div>'."\n";
				}
			}
		}
		//
		
		$fquick.="<fieldset class=\"adminform\"><table cellspacing=\"1\" class=\"admintable table\"><tbody><tr><td width=\"200\" class=\"vbo-config-param-cell\"><strong>".JText::_('VBDATEPICKUP').":</strong> </td><td>".JHTML::_('calendar', '', 'checkindate', 'checkindate', vikbooking::getDateFormat(true), array('class'=>'', 'size'=>'10',  'maxlength'=>'19'))." <span style=\"display: inline-block; margin-left: 10px;\">".JText::_('VBAT')." ".($hcheckin < 10 ? '0'.$hcheckin : $hcheckin).":".($mcheckin < 10 ? '0'.$mcheckin : $mcheckin)."</span><input type=\"hidden\" name=\"checkinh\" value=\"".$hcheckin."\"/><input type=\"hidden\" name=\"checkinm\" value=\"".$mcheckin."\"/></td></tr>\n";
		$fquick.="<tr><td class=\"vbo-config-param-cell\"><strong>".JText::_('VBDATERELEASE').":</strong> </td><td>".JHTML::_('calendar', '', 'checkoutdate', 'checkoutdate', vikbooking::getDateFormat(true), array('class'=>'', 'size'=>'10',  'maxlength'=>'19'))." <span style=\"display: inline-block; margin-left: 10px;\">".JText::_('VBAT')." ".($hcheckout < 10 ? '0'.$hcheckout : $hcheckout).":".($mcheckout < 10 ? '0'.$mcheckout : $mcheckout)."</span><input type=\"hidden\" name=\"checkouth\" value=\"".$hcheckout."\"/><input type=\"hidden\" name=\"checkoutm\" value=\"".$mcheckout."\"/></td></tr>";
		$fquick.="<tr><td class=\"vbo-config-param-cell\"><span class=\"vbcloseroomsp\"><label for=\"setclosed\"><strong>".JText::_('VBCLOSEROOM').":</strong></label></span> </td><td><input type=\"checkbox\" name=\"setclosed\" id=\"setclosed\" value=\"1\" onclick=\"javascript: vbCloseRoom();\"/></td></tr>\n";
		$fquick.="<tr><td class=\"vbo-config-param-cell\"><strong>".JText::_('VBQUICKRESGUESTS').":</strong> </td><td><span id=\"vbspanpeople\">".JText::_('VBQUICKADULTS').": ".$seladults." &nbsp;&nbsp; ".JText::_('VBQUICKCHILDREN').": ".$selchildren."</span></td></tr>\n";
		$fquick.="<tr><td class=\"vbo-config-param-cell\"><strong>".JText::_('VBCALBOOKINGSTATUS').":</strong> </td><td><span id=\"vbspanbstat\"><select name=\"newstatus\"><option value=\"confirmed\">".JText::_('VBCONFIRMED')."</option><option value=\"standby\">".JText::_('VBSTANDBY')."</option></select></span></td></tr>\n";
		$fquick.="<tr><td class=\"vbo-config-param-cell\"><strong>".JText::_('VBCALBOOKINGPAYMENT').":</strong> </td><td><span id=\"vbspanbpay\">".$selpayments."</span></td></tr>\n";
		$fquick.="<tr><td class=\"vbo-config-param-cell\">&nbsp;</td><td><span id=\"vbfillcustfields\"><i class=\"vboicn-user-check\"></i>".JText::_('VBFILLCUSTFIELDS')."</span></td></tr>\n";
		$fquick.="<tr><td class=\"vbo-config-param-cell\"><strong>".JText::_('VBCUSTEMAIL').":</strong> </td><td><span id=\"vbspancmail\"><input type=\"text\" name=\"custmail\" id=\"custmailfield\" value=\"\" size=\"25\"/></span></td></tr>\n";
		$fquick.="<tr><td class=\"vbo-config-param-cell\"><strong>".JText::_('VBCUSTINFO').":</strong> </td><td><textarea name=\"custdata\" id=\"vbcustdatatxtarea\" rows=\"5\" cols=\"70\" style=\"min-width: 300px;\"></textarea></td></tr>\n";
		$fquick.="<tr><td class=\"vbo-config-param-cell\">&nbsp;</td><td><button type=\"submit\" id=\"quickbsubmit\" class=\"btn btn-primary\"><i class=\"icon-save\"></i> ".JText::_('VBMAKERESERV')."</button></td></tr>\n";
		$fquick.="</tbody></table></fieldset>";
		$fquick.="<input type=\"hidden\" name=\"customer_id\" value=\"\" id=\"customer_id_inpfield\"/><input type=\"hidden\" name=\"countrycode\" value=\"\" id=\"ccode_inpfield\"/><input type=\"hidden\" name=\"t_first_name\" value=\"\" id=\"t_first_name_inpfield\"/><input type=\"hidden\" name=\"t_last_name\" value=\"\" id=\"t_last_name_inpfield\"/><input type=\"hidden\" name=\"phone\" value=\"\" id=\"phonefield\"/><input type=\"hidden\" name=\"task\" value=\"calendar\"/><input type=\"hidden\" name=\"cid[]\" value=\"".$room['id']."\"/></form>\n";
		//search customer
		$search_funct = '<div class="vbo-calendar-cfields-search"><span id="vbo-searchcust-loading"><i class="vboicn-hour-glass"></i></span><input type="text" id="vbo-searchcust" value="" placeholder="'.JText::_('VBOSEARCHCUSTBY').'" size="35" /><div id="vbo-searchcust-res"></div></div>';
		//
		//custom fields
		$fquick.='<div class="vbo-calendar-cfields-filler-overlay"><div class="vbo-calendar-cfields-filler"><h4>'.JText::_('VBCUSTINFO').'</h4>'.$search_funct.'<div class="vbo-calendar-cfields-inner">'.$cfields_cont.'</div><div class="vbo-calendar-cfields-bottom"><button type="button" class="btn" onclick="hideCustomFields();">'.JText::_('VBANNULLA').'</button> <button type="button" class="btn btn-success" onclick="applyCustomFieldsContent();"><i class="icon-edit"></i> '.JText::_('VBAPPLY').'</button></div></div></div>';
		//
		$fquick.='
		<script type="text/javascript">
		var cfields_overlay = false;
		var customers_search_vals = "";
		function vbCloseRoom() {
			if(document.getElementById("setclosed").checked == true) {
				document.getElementById("vbspanpeople").style.display = "none";
				document.getElementById("vbspanbstat").style.display = "none";
				document.getElementById("vbspancmail").style.display = "none";
				document.getElementById("vbcustdatatxtarea").value = "'.addslashes(JText::_('VBDBTEXTROOMCLOSED')).'";
				jQuery("#quickbsubmit").html("'.addslashes(JText::_('VBSUBMCLOSEROOM')).'");
			}else {
				document.getElementById("vbspanpeople").style.display = "inline-block";
				document.getElementById("vbspanbstat").style.display = "block";
				document.getElementById("vbspancmail").style.display = "block";
				document.getElementById("vbcustdatatxtarea").value = "";
				jQuery("#quickbsubmit").html("'.addslashes(JText::_('VBMAKERESERV')).'");
			}
		}
		function showCustomFields() {
			cfields_overlay = true;
			jQuery(".vbo-calendar-cfields-filler-overlay, .vbo-calendar-cfields-filler").fadeIn();
		}
		function hideCustomFields() {
			cfields_overlay = false;
			jQuery(".vbo-calendar-cfields-filler-overlay").fadeOut();
		}
		function applyCustomFieldsContent() {
			var cfields_cont = "";
			var cfields_labels = new Array;
			var nominatives = new Array;
			var tot_rows = 1;
			jQuery(".vbo-calendar-cfields-inner .vbo-calendar-cfield-entry").each(function(){
				var cfield_name = jQuery(this).find("label").text();
				var cfield_input = jQuery(this).find("span").find("input");
				var cfield_textarea = jQuery(this).find("span").find("textarea");
				var cfield_select = jQuery(this).find("span").find("select");
				var cfield_cont = "";
				if(cfield_input.length) {
					cfield_cont = cfield_input.val();
					if(cfield_input.attr("data-isemail") == "1" && cfield_cont.length) {
						jQuery("#custmailfield").val(cfield_cont);
					}
					if(cfield_input.attr("data-isphone") == "1") {
						jQuery("#phonefield").val(cfield_cont);
					}
					if(cfield_input.attr("data-isnominative") == "1") {
						nominatives.push(cfield_cont);
					}
				}else if(cfield_textarea.length) {
					cfield_cont = cfield_textarea.val();
				}else if(cfield_select.length) {
					cfield_cont = cfield_select.val();
					if(cfield_cont.length) {
						var country_code = jQuery("option:selected", cfield_select).attr("data-ccode");
						if(country_code.length) {
							jQuery("#ccode_inpfield").val(country_code);
						}
					}
				}
				if(cfield_cont.length) {
					cfields_cont += cfield_name+": "+cfield_cont+"\r\n";
					tot_rows++;
					cfields_labels.push(cfield_name+":");
				}
			});
			if(cfields_cont.length) {
				cfields_cont = cfields_cont.replace(/\r\n+$/, "");
			}
			if(nominatives.length > 1) {
				jQuery("#t_first_name_inpfield").val(nominatives[0]);
				jQuery("#t_last_name_inpfield").val(nominatives[1]);
			}
			jQuery("#vbcustdatatxtarea").val(cfields_cont);
			jQuery("#vbcustdatatxtarea").attr("rows", tot_rows);
			//Highlight Custom Fields Labels
			jQuery("#vbcustdatatxtarea").highlightTextarea({
				words: cfields_labels,
				color: "#ddd",
				id: "vbo-highlight-cfields"
			});
			//end highlight
			hideCustomFields();
		}
		jQuery(document).ready(function(){
			jQuery("#vbfillcustfields").click(function(){
				showCustomFields();
			});
			jQuery(document).mouseup(function(e) {
				if(!cfields_overlay) {
					return false;
				}
				var vbdialogcf_cont = jQuery(".vbo-calendar-cfields-filler");
				if(!vbdialogcf_cont.is(e.target) && vbdialogcf_cont.has(e.target).length === 0) {
					hideCustomFields();
				}
			});
			//Search customer - Start
			var vbocustsdelay = (function(){
				var timer = 0;
				return function(callback, ms){
					clearTimeout (timer);
					timer = setTimeout(callback, ms);
				};
			})();
			function vboCustomerSearch(words) {
				jQuery("#vbo-searchcust-res").hide().html("");
				jQuery("#vbo-searchcust-loading").show();
				var jqxhr = jQuery.ajax({
					type: "POST",
					url: "index.php",
					data: { option: "com_vikbooking", task: "searchcustomer", kw: words, tmpl: "component" }
				}).done(function(cont) {
					if(cont.length) {
						var obj_res = jQuery.parseJSON(cont);
						customers_search_vals = obj_res[0];
						jQuery("#vbo-searchcust-res").html(obj_res[1]);
					}else {
						customers_search_vals = "";
						jQuery("#vbo-searchcust-res").html("----");
					}
					jQuery("#vbo-searchcust-res").show();
					jQuery("#vbo-searchcust-loading").hide();
				}).fail(function() {
					jQuery("#vbo-searchcust-loading").hide();
					alert("Error Searching.");
				});
			}
			jQuery("#vbo-searchcust").keyup(function(event) {
				vbocustsdelay(function() {
					var keywords = jQuery("#vbo-searchcust").val();
					var chars = keywords.length;
					if(chars > 1) {
						if ((event.which > 96 && event.which < 123) || (event.which > 64 && event.which < 91) || event.which == 13) {
							vboCustomerSearch(keywords);
						}
					}else {
						if(jQuery("#vbo-searchcust-res").is(":visible")) {
							jQuery("#vbo-searchcust-res").hide();
						}
					}
				}, 600);
			});
			//Search customer - End

		});
		jQuery("body").on("click", ".vbo-custsearchres-entry", function() {
			var custid = jQuery(this).attr("data-custid");
			var custemail = jQuery(this).attr("data-email");
			var custphone = jQuery(this).attr("data-phone");
			var custcountry = jQuery(this).attr("data-country");
			var custfirstname = jQuery(this).attr("data-firstname");
			var custlastname = jQuery(this).attr("data-lastname");
			jQuery("#customer_id_inpfield").val(custid);
			if(customers_search_vals.hasOwnProperty(custid)) {
				jQuery.each(customers_search_vals[custid], function(cfid, cfval) {
					var fill_field = jQuery("#cfield"+cfid);
					if(fill_field.length) {
						fill_field.val(cfval);
					}
				});
			}else {
				jQuery("input[data-isnominative=\"1\"]").each(function(k, v) {
					if(k == 0) {
						jQuery(this).val(custfirstname);
						return true;
					}
					if(k == 1) {
						jQuery(this).val(custlastname);
						return true;
					}
					return false;
				});
				jQuery("input[data-isemail=\"1\"]").val(custemail);
				jQuery("input[data-isphone=\"1\"]").val(custphone);
				//Populate main calendar form
				jQuery("#custmailfield").val(custemail);
				jQuery("#t_first_name_inpfield").val(custfirstname);
				jQuery("#t_last_name_inpfield").val(custlastname);
				//
			}
			applyCustomFieldsContent();
			if(custcountry.length) {
				jQuery("#ccode_inpfield").val(custcountry);
			}
			if(custphone.length) {
				jQuery("#phonefield").val(custphone);
			}
		});
		</script>';
		//vikbooking 1.1
		$chroomsel = "<select name=\"cid[]\" onchange=\"javascript: document.vbchroom.submit();\">\n";
		foreach($allc as $cc) {
			$chroomsel .= "<option value=\"".$cc['id']."\"".($cc['id'] == $room['id'] ? " selected=\"selected\"" : "").">".$cc['name']."</option>\n";
		}
		$chroomsel .= "</select>\n";
		$chroomf = "<form name=\"vbchroom\" method=\"post\" action=\"index.php?option=com_vikbooking\"><input type=\"hidden\" name=\"task\" value=\"calendar\"/>".JText::_('VBSELVEHICLE').": ".$chroomsel."</form>";
		//
		echo "<div class=\"vbo-quickres-wrapper\"><table style=\"width: 95%;\"><tr><td valign=\"top\" align=\"left\"><div class=\"vbadminfaresctitle\">".$room['name'].", ".JText::_('VBQUICKBOOK')." <span style=\"float: right; text-transform: none;\">".$chroomf."</span></div></td></tr><tr><td valign=\"top\" align=\"left\">".$fquick."</td></tr></table></div>\n";
	}
	
	public static function pShowOverview ($rows, $arrbusy, $wmonthsel, $tsstart, $option, $lim0, $navbut) {
		$nowts = getdate($tsstart);
		$days_labels = array(
				JText::_('VBSUN'),
				JText::_('VBMON'),
				JText::_('VBTUE'),
				JText::_('VBWED'),
				JText::_('VBTHU'),
				JText::_('VBFRI'),
				JText::_('VBSAT')
		);
		$session = JFactory::getSession();
		$show_type = $session->get('vbUnitsShowType', '');
		?>
		<script type="text/Javascript">
		function vboUnitsLeftOrBooked() {
			var set_to = jQuery('#uleftorbooked').val();
			if(jQuery('.vbo-overview-redday').length) {
				jQuery('.vbo-overview-redday').each(function(){
					jQuery(this).text(jQuery(this).attr('data-'+set_to));
				});
			}
		}
		</script>
		<form action="index.php?option=com_vikbooking&amp;task=overview" method="post" name="vboverview">
			<div style="width: 100%; display: inline-block;" class="btn-toolbar" id="filter-bar">
				<div class="btn-group pull-left">
					<?php echo $wmonthsel; ?>
				</div>
				<div class="btn-group pull-right">
					<select name="units_show_type" id="uleftorbooked" onchange="vboUnitsLeftOrBooked();"><option value="units-booked"><?php echo JText::_('VBOVERVIEWUBOOKEDFILT'); ?></option><option value="units-left"<?php echo $show_type == 'units-left' ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBOVERVIEWULEFTFILT'); ?></option></select>
				</div>
				<div class="btn-group pull-right" style="margin-right: 15px;">
					<span class="vbo-overview-legend-init"><?php echo JText::_('VBOVERVIEWLEGEND'); ?></span>
					<div class="vbo-overview-legend-red">
						<span class="vbo-overview-legend-box">&nbsp;</span>
						<span class="vbo-overview-legend-title"><?php echo JText::_('VBOVERVIEWLEGRED'); ?></span>
					</div>
					<div class="vbo-overview-legend-yellow">
						<span class="vbo-overview-legend-box">&nbsp;</span>
						<span class="vbo-overview-legend-title"><?php echo JText::_('VBOVERVIEWLEGYELLOW'); ?></span>
					</div>
					<div class="vbo-overview-legend-green">
						<span class="vbo-overview-legend-box">&nbsp;</span>
						<span class="vbo-overview-legend-title"><?php echo JText::_('VBOVERVIEWLEGGREEN'); ?></span>
					</div>
				</div>
			</div>
		</form>
		<br/>
		<table class="vboverviewtable">
		<tr class="vboverviewtablerowone">
		<td class="bluedays vbo-overview-month"><?php echo vikbooking::sayMonth($nowts['mon'])." ".$nowts['year']; ?></td>
		<?php
		$mon=$nowts['mon'];
		while ($nowts['mon']==$mon) {
			echo '<td class="bluedays">'.$nowts['mday'].'<br/>'.$days_labels[$nowts['wday']].'</td>';
			$next=$nowts['mday'] + 1;
			$dayts=mktime(0, 0, 0, ($nowts['mon'] < 10 ? "0".$nowts['mon'] : $nowts['mon']), ($next < 10 ? "0".$next : $next), $nowts['year']);
			$nowts=getdate($dayts);
		}
		?>
		</tr>
		<?php
		foreach($rows as $room) {
			$nowts=getdate($tsstart);
			$mon=$nowts['mon'];
			echo '<tr class="vboverviewtablerow">';
			echo '<td class="roomname"><span class="vbo-overview-roomunits">'.$room['units'].'</span><span class="vbo-overview-roomname">'.$room['name'].'</span></td>';
			while ($nowts['mon']==$mon) {
				$dclass="notbusy";
				$is_checkin = false;
				$dalt="";
				$bid="";
				$totfound=0;
				if(@is_array($arrbusy[$room['id']])) {
					foreach($arrbusy[$room['id']] as $b){
						$tmpone=getdate($b['checkin']);
						$rit=($tmpone['mon'] < 10 ? "0".$tmpone['mon'] : $tmpone['mon'])."/".($tmpone['mday'] < 10 ? "0".$tmpone['mday'] : $tmpone['mday'])."/".$tmpone['year'];
						$ritts=strtotime($rit);
						$tmptwo=getdate($b['checkout']);
						$con=($tmptwo['mon'] < 10 ? "0".$tmptwo['mon'] : $tmptwo['mon'])."/".($tmptwo['mday'] < 10 ? "0".$tmptwo['mday'] : $tmptwo['mday'])."/".$tmptwo['year'];
						$conts=strtotime($con);
						//if ($nowts[0]>=$ritts && $nowts[0]<=$conts) {
						if ($nowts[0]>=$ritts && $nowts[0]<$conts) {
							$dclass="busy";
							$bid=$b['idorder'];
							if ($nowts[0]==$ritts) {
								$dalt=JText::_('VBPICKUPAT')." ".date('H:i', $b['checkin']);
								$is_checkin = true;
							}elseif ($nowts[0]==$conts) {
								$dalt=JText::_('VBRELEASEAT')." ".date('H:i', $b['checkout']);
							}
							$totfound++;
						}
					}
				}
				$useday = ($nowts['mday'] < 10 ? "0".$nowts['mday'] : $nowts['mday']);
				$dclass .= ($totfound < $room['units'] && $totfound > 0 ? ' vbo-partially' : '');
				if($totfound == 1) {
					$dclass .= $is_checkin === true ? ' vbo-checkinday' : '';
					$dlnk="<a href=\"index.php?option=com_vikbooking&task=editbusy&cid[]=".$bid."&goto=overview\" class=\"vbo-overview-redday\" data-units-booked=\"".$totfound."\" data-units-left=\"".($room['units'] - $totfound)."\">".$totfound."</a>";
					$cal="<td align=\"center\" class=\"".$dclass."\"".(!empty($dalt) ? " title=\"".$dalt."\"" : "").">".$dlnk."</td>\n";
				}elseif($totfound > 1) {
					$dlnk="<a href=\"index.php?option=com_vikbooking&task=choosebusy&idroom=".$room['id']."&ts=".$nowts[0]."&goto=overview\" class=\"vbo-overview-redday\" data-units-booked=\"".$totfound."\" data-units-left=\"".($room['units'] - $totfound)."\">".$totfound."</a>";
					$cal="<td align=\"center\" class=\"".$dclass."\">".$dlnk."</td>\n";
				}else {
					$dlnk=$useday;
					$cal="<td align=\"center\" class=\"".$dclass."\">&nbsp;</td>\n";
				}
				echo $cal;
				$next=$nowts['mday'] + 1;
				$dayts=mktime(0, 0, 0, ($nowts['mon'] < 10 ? "0".$nowts['mon'] : $nowts['mon']), ($next < 10 ? "0".$next : $next), $nowts['year']);
				$nowts=getdate($dayts);
			}
			echo '</tr>';
		}
		?>
		</table>
		<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<?php
		if(defined('JVERSION') && version_compare(JVERSION, '1.6.0') < 0) {
			//Joomla 1.5
			
		}
		?>
		<input type="hidden" name="task" value="overview" />
		<input type="hidden" name="month" value="<?php echo $tsstart; ?>" />
		<?php echo '<br/>'.$navbut; ?>
		</form>
		<script type="text/Javascript">
		vboUnitsLeftOrBooked();
		</script>
		<?php
	}
	
	public static function pViewCalendar ($room, $busy, $vmode, $option) {
		?>
		<div class="vbo-avcalendars-wrapper">
			<div class="vbo-avcalendars-roomphoto">
			<?php
			if (file_exists(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS.$room['img'])) {
				$img = '<img alt="Room Image" src="' . JURI::root() . 'components/com_vikbooking/resources/uploads/'.$room['img'].'" />';
			}else {
				$img = '<img alt="Vik Booking Logo" src="' . JURI::root() . 'administrator/components/com_vikbooking/vikbooking.jpg' . '" />';
			}
			echo $img;
			?>
			</div>
		<?php
		if(empty($busy)){
			echo "<p class=\"warn\">".JText::_('VBNOFUTURERES')."</p>";
		}else {
			$check=true;
			?>
			<p>
				<a class="vbmodelink" href="index.php?option=com_vikbooking&amp;task=calendar&amp;cid[]=<?php echo $room['id']; ?>&amp;vmode=3"><?php echo JText::_('VBTHREEMONTHS'); ?></a>
				<a class="vbmodelink" href="index.php?option=com_vikbooking&amp;task=calendar&amp;cid[]=<?php echo $room['id']; ?>&amp;vmode=6"><?php echo JText::_('VBSIXMONTHS'); ?></a>
				<a class="vbmodelink" href="index.php?option=com_vikbooking&amp;task=calendar&amp;cid[]=<?php echo $room['id']; ?>&amp;vmode=12"><?php echo JText::_('VBTWELVEMONTHS'); ?></a>
				<a class="vbmodelink" href="index.php?option=com_vikbooking&amp;task=calendar&amp;cid[]=<?php echo $room['id']; ?>&amp;vmode=24"><?php echo JText::_('VBTWOYEARS'); ?></a>
			</p>
			<?php
		}
		?>
		<table align="center"><tr>
		<?php
		$arr=getdate();
		$mon=$arr['mon'];
		$realmon=($mon < 10 ? "0".$mon : $mon);
		$year=$arr['year'];
		$day=$realmon."/01/".$year;
		$dayts=strtotime($day);
		$newarr=getdate($dayts);
		
		$firstwday = (int)vikbooking::getFirstWeekDay(true);
		$days_labels = array(
				JText::_('VBSUN'),
				JText::_('VBMON'),
				JText::_('VBTUE'),
				JText::_('VBWED'),
				JText::_('VBTHU'),
				JText::_('VBFRI'),
				JText::_('VBSAT')
		);
		$days_indexes = array();
		for( $i = 0; $i < 7; $i++ ) {
			$days_indexes[$i] = (6-($firstwday-$i)+1)%7;
		}
		
		for($jj=1; $jj<=$vmode; $jj++) {
			$d_count = 0;
			echo "<td valign=\"top\">";
			$cal="";
			?>
			<table class="vbadmincaltable">
			<tr class="vbadmincaltrmon"><td colspan="7" align="center"><?php echo vikbooking::sayMonth($newarr['mon'])." ".$newarr['year']; ?></td></tr>
			<tr class="vbadmincaltrmdays">
			<?php
			for($i = 0; $i < 7; $i++) {
				$d_ind = ($i + $firstwday) < 7 ? ($i + $firstwday) : ($i + $firstwday - 7);
				echo '<td>'.$days_labels[$d_ind].'</td>';
			}
			?>
			</tr>
			<tr>
			<?php
			for($i=0, $n = $days_indexes[$newarr['wday']]; $i < $n; $i++, $d_count++) {
				$cal.="<td align=\"center\">&nbsp;</td>";
			}
			while ($newarr['mon']==$mon) {
				if($d_count > 6) {
					$d_count = 0;
					$cal.="</tr>\n<tr>";
				}
				$dclass="free";
				$dalt="";
				$bid="";
				if ($check) {
					$totfound=0;
					foreach($busy as $b){
						$tmpone=getdate($b['checkin']);
						$rit=($tmpone['mon'] < 10 ? "0".$tmpone['mon'] : $tmpone['mon'])."/".($tmpone['mday'] < 10 ? "0".$tmpone['mday'] : $tmpone['mday'])."/".$tmpone['year'];
						$ritts=strtotime($rit);
						$tmptwo=getdate($b['checkout']);
						$con=($tmptwo['mon'] < 10 ? "0".$tmptwo['mon'] : $tmptwo['mon'])."/".($tmptwo['mday'] < 10 ? "0".$tmptwo['mday'] : $tmptwo['mday'])."/".$tmptwo['year'];
						$conts=strtotime($con);
						//if ($newarr[0]>=$ritts && $newarr[0]<=$conts) {
						if ($newarr[0]>=$ritts && $newarr[0]<$conts) {
							$dclass="busy";
							$bid=$b['idorder'];
							if ($newarr[0]==$ritts) {
								$dalt=JText::_('VBPICKUPAT')." ".date('H:i', $b['checkin']);
							}elseif ($newarr[0]==$conts) {
								$dalt=JText::_('VBRELEASEAT')." ".date('H:i', $b['checkout']);
							}
							$totfound++;
							//break;
						}
					}
				}
				$useday=($newarr['mday'] < 10 ? "0".$newarr['mday'] : $newarr['mday']);
				if($totfound == 1) {
					$dlnk="<a href=\"index.php?option=com_vikbooking&task=editbusy&cid[]=".$bid."\">".$useday."</a>";
					$cal.="<td align=\"center\" class=\"".$dclass."\"".(!empty($dalt) ? " title=\"".$dalt."\"" : "").">".$dlnk."</td>\n";
				}elseif($totfound > 1) {
					$dlnk="<a href=\"index.php?option=com_vikbooking&task=choosebusy&idroom=".$room['id']."&ts=".$newarr[0]."\">".$useday."</a>";
					$cal.="<td align=\"center\" class=\"".$dclass."\">".$dlnk."</td>\n";
				}else {
					$dlnk=$useday;
					$cal.="<td align=\"center\" class=\"".$dclass."\">".$dlnk."</td>\n";
				}
				$next=$newarr['mday'] + 1;
				$dayts=mktime(0, 0, 0, ($newarr['mon'] < 10 ? "0".$newarr['mon'] : $newarr['mon']), ($next < 10 ? "0".$next : $next), $newarr['year']);
				$newarr=getdate($dayts);
				$d_count++;
			}
			
			for($i=$d_count; $i <= 6; $i++){
				$cal.="<td align=\"center\">&nbsp;</td>";
			}
			
			echo $cal;
			?>
			</tr>
			</table>
			<?php
			echo "</td>";
			if ($mon==12) {
				$mon=1;
				$year+=1;
				$dayts=mktime(0, 0, 0, ($mon < 10 ? "0".$mon : $mon), 01, $year);
			}else {
				$mon+=1;
				$dayts=mktime(0, 0, 0, ($mon < 10 ? "0".$mon : $mon), 01, $year);
			}
			$newarr=getdate($dayts);
			
			if (($jj % 4)==0 && $vmode > 4) {
				echo "</tr>\n<tr>";
			}
		}
		
		?>
		</tr></table>
		</div>
		<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
		</form>
		<br clear="all" />
		<?php
	}
	
	public static function pViewRoom ($rows, $option, $lim0="0", $navbut="", $orderby="name", $ordersort="ASC") {
		if(empty($rows)){
			?>
			<p class="warn"><?php echo JText::_('VBNOROOMSFOUND'); ?></p>
			<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
			<?php
		}else{
		
		?>
   <script type="text/javascript">
function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'removeroom') {
				if (confirm('<?php echo JText::_('VBJSDELROOM'); ?>?')){
					submitform( pressbutton );
					return;
				}else{
					return false;
				}
			}

			// do field validation
			try {
				document.adminForm.onsubmit();
			}
			catch(e){}
			submitform( pressbutton );
		}
</script>
   <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

	<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped">
		<thead>
		<tr>
			<th width="20">
				<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
			</th>
			<th class="title left" width="150"><a href="index.php?option=com_vikbooking&amp;task=rooms&amp;vborderby=name&amp;vbordersort=<?php echo ($orderby == "name" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "name" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "name" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBPVIEWROOMONE' ); ?></a></th>
			<th class="title center" align="center" width="75"><a href="index.php?option=com_vikbooking&amp;task=rooms&amp;vborderby=toadult&amp;vbordersort=<?php echo ($orderby == "toadult" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "toadult" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "toadult" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBPVIEWROOMADULTS' ); ?></a></th>
			<th class="title center" align="center" width="75"><a href="index.php?option=com_vikbooking&amp;task=rooms&amp;vborderby=tochild&amp;vbordersort=<?php echo ($orderby == "tochild" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "tochild" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "tochild" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBPVIEWROOMCHILDREN' ); ?></a></th>
			<th class="title center" align="center" width="75"><a href="index.php?option=com_vikbooking&amp;task=rooms&amp;vborderby=totpeople&amp;vbordersort=<?php echo ($orderby == "totpeople" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "totpeople" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "totpeople" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBPVIEWROOMTOTPEOPLE' ); ?></a></th>
			<th class="title left" width="75"><?php echo JText::_( 'VBPVIEWROOMTWO' ); ?></th>
			<th class="title center" align="center" width="75"><?php echo JText::_( 'VBPVIEWROOMTHREE' ); ?></th>
			<th class="title center" align="center" width="150"><?php echo JText::_( 'VBPVIEWROOMFOUR' ); ?></th>
			<th class="title center" align="center" width="100"><a href="index.php?option=com_vikbooking&amp;task=rooms&amp;vborderby=units&amp;vbordersort=<?php echo ($orderby == "units" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "units" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "units" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBPVIEWROOMSEVEN' ); ?></a></th>
			<th class="title center" align="center" width="100"><?php echo JText::_( 'VBPVIEWROOMSIX' ); ?></th>
		</tr>
		</thead>
		<?php
		$dbo = JFactory::getDBO();
		$kk = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$q="SELECT COUNT(*) AS `totdisp` FROM `#__vikbooking_dispcost` WHERE `idroom`='".$row['id']."' ORDER BY `#__vikbooking_dispcost`.`days`;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$lines = $dbo->loadAssocList();
			$tot=$lines[0]['totdisp'];
			if (strlen(trim(str_replace(";", "", $row['idcat']))) > 0) {
				$categories = "";
				$cat=explode(";", $row['idcat']);
				$q="SELECT `name` FROM `#__vikbooking_categories` WHERE ";
				foreach($cat as $k=>$cc){
					if (!empty($cc)) {
						$q.="`id`=".$dbo->quote($cc)." ";
						if ($cc!=end($cat) && !empty($cat[($k + 1)])) {
							$q.="OR ";
						}
					}
				}
				$q.=";";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$lines = $dbo->loadAssocList();
				if (is_array($lines)) {
					$categories = array();
					foreach($lines as $ll){
						$categories[]=$ll['name'];
					}
					$categories = implode(", ", $categories);
				}else {
					$categories="";
				}
			}else {
				$categories="";
			}
			
			if (!empty($row['idcarat'])) {
				$tmpcarat=explode(";", $row['idcarat']);
				$caratteristiche=vikbooking::totElements($tmpcarat);
			}else {
				$caratteristiche="";
			}
			
			if (!empty($row['idopt'])) {
				$tmpopt=explode(";", $row['idopt']);
				$optionals=vikbooking::totElements($tmpopt);
			}else {
				$optionals="";
			}
			if ($row['fromadult'] == $row['toadult']) {
				$stradult = $row['fromadult'];
			}else {
				$stradult = $row['fromadult'].' - '.$row['toadult'];
			}
			if ($row['fromchild'] == $row['tochild']) {
				$strchild = $row['fromchild'];
			}else {
				$strchild = $row['fromchild'].' - '.$row['tochild'];
			}
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td><a href="index.php?option=com_vikbooking&amp;task=editroom&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td class="center"><?php echo $stradult; ?></td>
				<td class="center"><?php echo $strchild; ?></td>
				<td class="center"><?php echo $row['mintotpeople'].' - '.$row['totpeople']; ?></td>
				<td><?php echo $categories; ?></td>
				<td class="center"><?php echo $caratteristiche; ?></td>
				<td class="center"><?php echo $optionals; ?></td>
				<td class="center"><?php echo $row['units']; ?></td>
				<td class="center"><a href="index.php?option=com_vikbooking&amp;task=modavail&amp;cid[]=<?php echo $row['id']; ?>"><?php echo (intval($row['avail'])=="1" ? "<img src=\"".JURI::root()."administrator/components/com_vikbooking/resources/ok.png"."\" border=\"0\" title=\"".JText::_('VBMAKENOTAVAIL')."\"/>" : "<img src=\"".JURI::root()."administrator/components/com_vikbooking/resources/no.png"."\" border=\"0\" title=\"".JText::_('VBMAKENOTAVAIL')."\"/>"); ?></a></td>
			 </tr>
			  <?php
			$kk = 1 - $kk;
			unset($categories);
			
		}
?>
		
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<?php
		if(defined('JVERSION') && version_compare(JVERSION, '1.6.0') < 0) {
			//Joomla 1.5
			
		}
		?>
		<input type="hidden" name="task" value="rooms" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php echo $navbut; ?>
	</form>
	<?php
		}
	}

	public static function pViewOrders ($rows, $option, $lim0="0", $navbut="", $orderby="ts", $ordersort="DESC", $allrooms = array()) {
		$dbo = JFactory::getDBO();
		$cid = JRequest::getVar('cid', array());
		$pcust_id = JRequest::getInt('cust_id', '', 'request');
		if(empty($rows)){
			?>
			<p class="warn"><?php echo JText::_('VBNOORDERSFOUND'); ?></p>
			<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
			<?php
		}else{
			JHTML::_('behavior.modal');
			//1.6 filter by channel
			$all_channels = array();
			$q = "SELECT `channel` FROM `#__vikbooking_orders` WHERE `channel` IS NOT NULL GROUP BY `channel`;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if($dbo->getNumRows() > 0) {
				$ord_channels = $dbo->loadAssocList();
				foreach ($ord_channels as $o_channel) {
					$channel_parts = explode('_', $o_channel['channel']);
					$channel_name = count($channel_parts) > 1 ? trim($channel_parts[1]) : trim($channel_parts[0]);
					if(in_array($channel_name, $all_channels)) {
						continue;
					}
					$all_channels[] = $channel_name;
				}
			}
		?>
	<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
	
	<div id="filter-bar" class="btn-toolbar" style="width: 100%; display: inline-block;">
		<div class="btn-group pull-left">
			<a href="index.php?option=com_vikbooking&amp;task=csvexportprepare&amp;tmpl=component" rel="{handler: 'iframe', size: {x: 750, y: 600}}" target="_blank" class="vbcsvexport modal"><?php echo JText::_('VBCSVEXPORT'); ?></a>
			<a href="index.php?option=com_vikbooking&amp;task=icsexportprepare&amp;tmpl=component" rel="{handler: 'iframe', size: {x: 750, y: 600}}" target="_blank" class="vbicsexport modal"><?php echo JText::_('VBICSEXPORT'); ?></a>
		</div>
		<div class="btn-group pull-right">
			<button type="submit" class="btn"><?php echo JText::_('VBPVIEWORDERSSEARCHSUBM'); ?></button>
		</div>
		<div class="btn-group pull-right">
			<input type="text" name="confirmnumber" size="20" placeholder="<?php echo (strlen($_POST['confirmnumber']) > 0 ? $_POST['confirmnumber'] : JText::_('VBCONFIRMNUMB')); ?>" value="<?php echo (strlen($_POST['confirmnumber']) > 0 ? $_POST['confirmnumber'] : JText::_('VBCONFIRMNUMB')); ?>" onfocus="javascript: if (this.defaultValue==this.value) this.value='';" onblur="javascript: if (this.value == '') this.value=this.defaultValue;"/>
		</div>
		<div class="btn-group pull-right">
		<?php
		$pidroom = JRequest::getInt('idroom', '', 'request');
		if (count($allrooms) > 0) {
			$rsel = '<select name="idroom"><option value="">'.JText::_('VBROOMFILTER').'</option>';
			foreach ($allrooms as $room) {
				$rsel .= '<option value="'.$room['id'].'"'.(!empty($pidroom) && $pidroom == $room['id'] ? ' selected="selected"' : '').'>'.$room['name'].'</option>';
			}
			$rsel .= '</select>';
		}
		echo $rsel;
		?>
		</div>
		<div class="btn-group pull-right">
			<select name="channel">
				<option value=""><?php echo JText::_('VBCHANNELFILTER'); ?></option>
		<?php
		$pchannel = JRequest::getString('channel', '', 'request');
		if(count($all_channels) > 0) {
			?>
				<option value="-1"<?php echo $pchannel == '-1' ? ' selected="selected"' : ''; ?>>- <?php echo JText::_('VBORDFROMSITE'); ?></option>
			<?php
			foreach ($all_channels as $o_channel) {
				?>
				<option value="<?php echo $o_channel; ?>"<?php echo $pchannel == $o_channel ? ' selected="selected"' : ''; ?>>- <?php echo ucwords($o_channel); ?></option>
				<?php
			}
		}
		?>
			</select>
		</div>
		<?php
		$cust_id_filter = false;
		if(array_key_exists('customer_fullname', $rows[0])) {
			//customer ID filter
			$cust_id_filter = true;
			?>
		<div class="btn-group pull-right">
			<a href="index.php?option=com_vikbooking&amp;task=vieworders" class="btn btn-danger"><i class="icon-remove"></i><?php echo $rows[0]['customer_fullname']; ?></a>
		</div>
			<?php
		}
		?>
	</div>
	
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped">
		<thead>
		<tr>
			<th width="20">
				<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
			</th>
			<th class="title center" width="20" align="center">ID</th>
			<th class="title left" width="110"><a href="index.php?option=com_vikbooking&amp;task=vieworders<?php echo $cust_id_filter ? '&amp;cust_id='.$pcust_id : ''; ?>&amp;vborderby=ts&amp;vbordersort=<?php echo ($orderby == "ts" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "ts" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "ts" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBPVIEWORDERSONE' ); ?></a></th>
			<th class="title left" width="200"><?php echo JText::_( 'VBPVIEWORDERSTWO' ); ?></th>
			<th class="title center" width="50" align="center"><?php echo JText::_( 'VBPVIEWORDERSTHREE' ); ?></th>
			<th class="title left" width="150"><?php echo JText::_( 'VBPVIEWORDERSPEOPLE' ); ?></th>
			<th class="title left" width="110"><a href="index.php?option=com_vikbooking&amp;task=vieworders<?php echo $cust_id_filter ? '&amp;cust_id='.$pcust_id : ''; ?>&amp;vborderby=checkin&amp;vbordersort=<?php echo ($orderby == "checkin" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "checkin" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "checkin" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBPVIEWORDERSFOUR' ); ?></a></th>
			<th class="title left" width="110"><a href="index.php?option=com_vikbooking&amp;task=vieworders<?php echo $cust_id_filter ? '&amp;cust_id='.$pcust_id : ''; ?>&amp;vborderby=checkout&amp;vbordersort=<?php echo ($orderby == "checkout" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "checkout" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "checkout" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBPVIEWORDERSFIVE' ); ?></a></th>
			<th class="title center" width="70" align="center"><a href="index.php?option=com_vikbooking&amp;task=vieworders<?php echo $cust_id_filter ? '&amp;cust_id='.$pcust_id : ''; ?>&amp;vborderby=days&amp;vbordersort=<?php echo ($orderby == "days" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "days" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "days" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBPVIEWORDERSSIX' ); ?></a></th>
			<th class="title center" width="110" align="center"><a href="index.php?option=com_vikbooking&amp;task=vieworders<?php echo $cust_id_filter ? '&amp;cust_id='.$pcust_id : ''; ?>&amp;vborderby=total&amp;vbordersort=<?php echo ($orderby == "total" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "total" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "total" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBPVIEWORDERSSEVEN' ); ?></a></th>
			<th class="title center" width="50" align="center">&nbsp;</th>
			<th class="title center" width="100" align="center"><?php echo JText::_( 'VBPVIEWORDERSEIGHT' ); ?></th>
			<th class="title center" width="100" align="center"><?php echo JText::_( 'VBPVIEWORDERCHANNEL' ); ?></th>
		</tr>
		</thead>
		<?php
		$nowdf = vikbooking::getDateFormat(true);
		if ($nowdf=="%d/%m/%Y") {
			$df='d/m/Y';
		}elseif ($nowdf=="%m/%d/%Y") {
			$df='m/d/Y';
		}else {
			$df='Y/m/d';
		}
		$currencysymb=vikbooking::getCurrencySymb(true);
		$kk = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$rooms=vikbooking::loadOrdersRoomsData($row['id']);
			$peoplestr = "";
			$room_names = array();
			if (is_array($rooms)) {
				$totadults = 0;
				$totchildren = 0;
				foreach($rooms as $rr) {
					$totadults += $rr['adults'];
					$totchildren += $rr['children'];
					$room_names[] = $rr['room_name'];
				}
				$peoplestr .= $totadults." ".($totadults > 1 ? JText::_('VBMAILADULTS') : JText::_('VBMAILADULT')).($totchildren > 0 ? ", ".$totchildren." ".($totchildren > 1 ? JText::_('VBMAILCHILDREN') : JText::_('VBMAILCHILD')) : "");
			}
			$isdue=$row['total'];
			$otachannel = '';
			$otacurrency = '';
			if (!empty($row['channel'])) {
				$channelparts = explode('_', $row['channel']);
				$otachannel = array_key_exists(1, $channelparts) && strlen($channelparts[1]) > 0 ? $channelparts[1] : ucwords($channelparts[0]);
				$otachannelclass = $otachannel;
				if (strstr($otachannelclass, '.') !== false) {
					$otaccparts = explode('.', $otachannelclass);
					$otachannelclass = $otaccparts[0];
				}
				$otacurrency = strlen($row['chcurrency']) > 0 ? $row['chcurrency'] : '';
			}
			//Customer Details
			$custdata = !empty($row['custdata']) ? substr($row['custdata'], 0, 45)." ..." : "";
			$q = "SELECT `c`.*,`co`.`idorder` FROM `#__vikbooking_customers` AS `c` LEFT JOIN `#__vikbooking_customers_orders` `co` ON `c`.`id`=`co`.`idcustomer` WHERE `co`.`idorder`=".$row['id'].";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if($dbo->getNumRows() > 0) {
				$cust_country = $dbo->loadAssocList();
				$cust_country = $cust_country[0];
				if(!empty($cust_country['first_name'])) {
					$custdata = $cust_country['first_name'].' '.$cust_country['last_name'];
					if(!empty($cust_country['country'])) {
						if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'countries'.DS.$cust_country['country'].'.png')) {
							$custdata .= '<img src="'.JURI::root().'administrator/components/com_vikbooking/resources/countries/'.$cust_country['country'].'.png'.'" title="'.$cust_country['country'].'" class="vbo-country-flag vbo-country-flag-left"/>';
						}
					}
				}
			}
			$custdata = JText::_('VBDBTEXTROOMCLOSED') == $row['custdata'] ? '<span class="vbordersroomclosed">'.JText::_('VBDBTEXTROOMCLOSED').'</span>' : $custdata;
			//
			if ($row['status']=="confirmed") {
				//$saystaus = "<span style=\"color: #4ca25a; font-weight: bold;\">".JText::_('VBCONFIRMED')."</span>";
				$saystaus = '<span class="label label-success">'.JText::_('VBCONFIRMED').'</span>';
			}elseif ($row['status']=="standby") {
				//$saystaus = "<span style=\"color: #e0a504; font-weight: bold;\">".JText::_('VBSTANDBY')."</span>";
				$saystaus = '<span class="label label-warning">'.JText::_('VBSTANDBY').'</span>';
			}else {
				//$saystaus = "<span class=\"vbordcancelled\">".JText::_('VBCANCELLED')."</span>";
				$saystaus = '<span class="label label-error" style="background-color: #d9534f;">'.JText::_('VBCANCELLED').'</span>';
			}
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td class="center"><?php echo $row['id']; ?></td>
				<td><span class="label label-info vbo-label-viewdetails"><a href="index.php?option=com_vikbooking&amp;task=editorder&amp;cid[]=<?php echo $row['id']; ?>" style="color: #fff;"><?php echo date($df.' H:i', $row['ts']); ?></a></span></td>
				<td><?php echo $custdata; ?></td>
				<td class="center"><span<?php echo (count($room_names) > 0 ? ' class="hasTooltip vbo-tip-small" title="'.implode(', ', $room_names).'"' : ''); ?>><?php echo $row['roomsnum']; ?></span></td>
				<td><?php echo $peoplestr; ?></td>
				<td><?php echo date($df.' H:i', $row['checkin']); ?></td>
				<td><?php echo date($df.' H:i', $row['checkout']); ?></td>
				<td class="center"><?php echo $row['days']; ?></td>
				<td class="center"><?php echo (strlen($otacurrency) > 0 ? $otacurrency : $currencysymb)." ".vikbooking::numberFormat($isdue).(!empty($row['totpaid']) ? " &nbsp;(".$currencysymb." ".vikbooking::numberFormat($row['totpaid']).")" : ""); ?></td>
				<td class="center">
					<?php echo (!empty($row['adminnotes']) ? '<span class="hasTooltip vbo-admin-tipsicon" title="'.htmlentities(nl2br($row['adminnotes'])).'"><img src="'.JURI::root().'administrator/components/com_vikbooking/resources/admin_notes.png" /></span>&nbsp;&nbsp;' : ''); ?>
					<?php echo (file_exists(JPATH_SITE . DS ."components". DS ."com_vikbooking". DS . "helpers" . DS . "invoices" . DS . "generated" . DS . $row['id'].'_'.$row['sid'] .".pdf") ? '<a class="hasTooltip" href="'.JURI::root().'components/com_vikbooking/helpers/invoices/generated/'.$row['id'].'_'.$row['sid'].'.pdf" target="_blank" title="'.JText::_('VBOINVDOWNLOAD').'"><img src="'.JURI::root().'administrator/components/com_vikbooking/resources/invoice-small.png" border="0" /></a>' : ''); ?>
				</td>
				<td class="center"><?php echo $saystaus; ?></td>
				<td class="center"><?php echo (!empty($row['channel']) ? "<span class=\"vbotasp ".strtolower($otachannelclass)."\">".$otachannel."</span>" : "<span class=\"vbotasp\">".JText::_('VBORDFROMSITE')."</span>"); ?></td>
			</tr>
			<?php
			$kk = 1 - $kk;
			
		}
		?>
		
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<?php
		if(!empty($pidroom)) {
			echo '<input type="hidden" name="idroom" value="'.$pidroom.'" />';
		}
		if(!empty($pcust_id)) {
			echo '<input type="hidden" name="cust_id" value="'.$pcust_id.'" />';
		}
		?>
		<input type="hidden" name="task" value="vieworders" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php echo $navbut; ?>
	</form>
	<script type="text/javascript">
	if(jQuery.isFunction(jQuery.fn.tooltip)) {
		jQuery(".hasTooltip").tooltip();
	}
	</script>
	<?php
		}
		//Invoices
		if(count($cid) > 0 && !empty($cid[0])) {
			$nextinvnum = vikbooking::getNextInvoiceNumber();
			$invsuff = vikbooking::getInvoiceNumberSuffix();
			$companyinfo = vikbooking::getInvoiceCompanyInfo();
			?>
			<div class="vbo-info-overlay-block">
				<div class="vbo-info-overlay-content vbo-info-overlay-content-invoices">
					<h4><?php echo JText::_('VBOGENINVOICES') ?> (<?php echo count($cid); ?>)</h4>
					<form action="index.php?option=com_vikbooking" method="post">
						<div class="vbo-calendar-cfield-entry">
							<label for="invoice_num"><?php echo JText::_('VBINVSTARTNUM') ?></label>
							<span><input type="number" min="1" size="4" style="width: 65px !important;" value="<?php echo $nextinvnum; ?>" id="invoice_num" name="invoice_num" /></span>
						</div>
						<div class="vbo-calendar-cfield-entry">
							<label for="invoice_suff"><?php echo JText::_('VBINVNUMSUFFIX') ?></label>
							<span><input type="text" size="7" value="<?php echo $invsuff; ?>" id="invoice_suff" name="invoice_suff" /></span>
						</div>
						<div class="vbo-calendar-cfield-entry">
							<label for="invoice_date"><?php echo JText::_('VBINVUSEDATE') ?></label>
							<span><select id="invoice_date" name="invoice_date"><option value="<?php echo date($df, time()); ?>"><?php echo date($df, time()); ?></option><option value="0"><?php echo JText::_('VBINVUSEDATEBOOKING') ?></option></select></span>
						</div>
						<div class="vbo-calendar-cfield-entry">
							<label for="company_info"><?php echo JText::_('VBINVCOMPANYINFO') ?></label>
							<span><textarea name="company_info" id="company_info" style="width: 98%; min-width: 98%;max-width: 98%; height: 70px;"><?php echo $companyinfo; ?></textarea></span>
						</div>
						<div class="vbo-calendar-cfield-entry">
							<label for="invoice_send"><i class="vboicn-envelop"></i><?php echo JText::_('VBINVSENDVIAMAIL') ?></label>
							<span><select id="invoice_send" name="invoice_send"><option value=""><?php echo JText::_('VBNO'); ?></option><option value="1"><?php echo JText::_('VBYES') ?></option></select></span>
						</div>
						<br clear="all" />
						<div class="vbo-calendar-cfields-bottom">
							<button type="submit" class="btn"><i class="vboicn-file-text2"></i><?php echo JText::_('VBOGENINVOICES') ?></button>
						</div>
					<?php
					foreach ($cid as $invid) {
						echo '<input type="hidden" name="cid[]" value="'.$invid.'" />';
					}
					?>
						<input type="hidden" name="option" value="<?php echo $option; ?>" />
						<input type="hidden" name="task" value="geninvoices" />
					</form>
				</div>
			</div>
			<script type="text/javascript">
			var vbo_overlay_on = false;
			jQuery(document).ready(function() {
				jQuery(".vbo-info-overlay-block").fadeIn(400, function() {
					if(jQuery(".vbo-info-overlay-block").is(":visible")) {
						vbo_overlay_on = true;
					}else {
						vbo_overlay_on = false;
					}
				});
				jQuery(document).mouseup(function(e) {
					if(!vbo_overlay_on) {
						return false;
					}
					var vbo_overlay_cont = jQuery(".vbo-info-overlay-content");
					if(!vbo_overlay_cont.is(e.target) && vbo_overlay_cont.has(e.target).length === 0) {
						jQuery(".vbo-info-overlay-block").fadeOut();
						vbo_overlay_on = false;
					}
				});
				jQuery(document).keyup(function(e) {
					if (e.keyCode == 27 && vbo_overlay_on) {
						jQuery(".vbo-info-overlay-block").fadeOut();
						vbo_overlay_on = false;
					}
				});
			});
			</script>
			<?php
		}
	}
	
	public static function pCsvExportPrepare($option) {
		JHTML::_('behavior.calendar');
		JHTML::_('behavior.tooltip');
		$nowdf = vikbooking::getDateFormat(true);
		if ($nowdf=="%d/%m/%Y") {
			$df='d/m/Y';
		}elseif ($nowdf=="%m/%d/%Y") {
			$df='m/d/Y';
		}else {
			$df='Y/m/d';
		}
		?>
		<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<h3><?php echo JText::_('VBCSVEXPORT'); ?></h3>
			<table class="adminform">
			<tr><td width="200">&bull; <b><?php echo JText::_('VBCSVEXPFILTDATES'); ?>:</b> </td><td><?php echo JHTML::_('calendar', '', 'checkindate', 'checkindate', $nowdf, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?> &nbsp;-&nbsp; <?php echo JHTML::_('calendar', '', 'checkoutdate', 'checkoutdate', $nowdf, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?></td></tr>
			<tr><td>&bull; <b><?php echo JText::_('VBCSVEXPFILTBSTATUS'); ?>:</b> </td><td><select name="status"><option value="">----------</option><option value="confirmed"><?php echo JText::_('VBCSVSTATUSCONFIRMED'); ?></option><option value="standby"><?php echo JText::_('VBCSVSTATUSSTANDBY'); ?></option><option value="cancelled"><?php echo JText::_('VBCSVSTATUSCANCELLED'); ?></option></select></td></tr>
			<tr><td>&nbsp;</td><td style="text-align: left;"><input type="submit" class="btn" name="csvsubmit" value="<?php echo JText::_('VBCSVGENERATE'); ?>"/></td></tr>
			</table>
			<input type="hidden" name="task" value="csvexportlaunch" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
		</form>
		<?php
	}
	
	public static function pIcsExportPrepare($option) {
		JHTML::_('behavior.calendar');
		JHTML::_('behavior.tooltip');
		$nowdf = vikbooking::getDateFormat(true);
		if ($nowdf=="%d/%m/%Y") {
			$df='d/m/Y';
		}elseif ($nowdf=="%m/%d/%Y") {
			$df='m/d/Y';
		}else {
			$df='Y/m/d';
		}
		?>
		<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<h3><?php echo JText::_('VBICSEXPORT'); ?></h3>
			<table class="adminform">
			<tr><td width="200">&bull; <b><?php echo JText::_('VBCSVEXPFILTDATES'); ?>:</b> </td><td><?php echo JHTML::_('calendar', '', 'checkindate', 'checkindate', $nowdf, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?> &nbsp;-&nbsp; <?php echo JHTML::_('calendar', '', 'checkoutdate', 'checkoutdate', $nowdf, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?></td></tr>
			<tr><td>&bull; <b><?php echo JText::_('VBCSVEXPFILTBSTATUS'); ?>:</b> </td><td><select name="status"><option value="">----------</option><option value="confirmed"><?php echo JText::_('VBCSVSTATUSCONFIRMED'); ?></option><option value="standby"><?php echo JText::_('VBCSVSTATUSSTANDBY'); ?></option><option value="cancelled"><?php echo JText::_('VBCSVSTATUSCANCELLED'); ?></option></select></td></tr>
			<tr><td>&nbsp;</td><td style="text-align: left;"><input type="submit" class="btn" name="icssubmit" value="<?php echo JText::_('VBICSGENERATE'); ?>"/></td></tr>
			</table>
			<input type="hidden" name="task" value="icsexportlaunch" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
		</form>
		<?php
	}
		
	public static function pViewIva ($rows, $option, $lim0="0", $navbut="") {
		if(empty($rows)){
			?>
			<p class="warn"><?php echo JText::_('VBNOIVAFOUND'); ?></p>
			<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
			<?php
		}else{
		
		?>
	<script type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'removeiva') {
			if (confirm('<?php echo JText::_('VBJSDELIVA'); ?> ?')){
				submitform( pressbutton );
				return;
			}else{
				return false;
			}
		}
		// do field validation
		try {
			document.adminForm.onsubmit();
		}
		catch(e){}
		submitform( pressbutton );
	}
	</script>
	<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

	<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped">
		<thead>
		<tr>
			<th width="20">
				<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
			</th>
			<th class="title left" width="150"><?php echo JText::_( 'VBPVIEWIVAONE' ); ?></th>
			<th class="title left" width="150"><?php echo JText::_( 'VBPVIEWIVATWO' ); ?></th>
			<th class="title center" width="150"><?php echo JText::_( 'VBOTAXBKDWNCOUNT' ); ?></th>
		</tr>
		</thead>
		<?php

		$k = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$breakdown_str = '-----';
			if(!empty($row['breakdown'])) {
				$breakdown = json_decode($row['breakdown'], true);
				if(is_array($breakdown) && count($breakdown) > 0) {
					$breakdown_aliq = array();
					foreach ($breakdown as $key => $subtax) {
						$breakdown_aliq[] = $subtax['aliq'].'%';
					}
					$breakdown_str = implode(', ', $breakdown_aliq);
				}
			}
			?>
			<tr class="row<?php echo $k; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td><a href="index.php?option=com_vikbooking&amp;task=editiva&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td><?php echo $row['aliq']; ?></td>
				<td style="text-align: center;"><?php echo $breakdown_str; ?></td>
			</tr>
			<?php
			$k = 1 - $k;
			
		}
		?>
		
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<?php
		if(defined('JVERSION') && version_compare(JVERSION, '1.6.0') < 0) {
			//Joomla 1.5
			
		}
		?>
		<input type="hidden" name="task" value="viewiva" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php echo $navbut; ?>
	</form>
	<?php
		}
	}
	
	public static function pViewCoupons ($rows, $option, $lim0="0", $navbut="") {
		if(empty($rows)){
			?>
			<p class="warn"><?php echo JText::_('VBNOCOUPONSFOUND'); ?></p>
			<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
			<?php
		}else{
			
		?>
   <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

	<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped">
		<thead>
		<tr>
			<th width="20">
				<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
			</th>
			<th class="title left" width="200"><?php echo JText::_( 'VBPVIEWCOUPONSONE' ); ?></th>
			<th class="title left" width="200" align="center"><?php echo JText::_( 'VBPVIEWCOUPONSTWO' ); ?></th>
			<th class="title left" width="100" align="center"><?php echo JText::_( 'VBPVIEWCOUPONSTHREE' ); ?></th>
			<th class="title left" width="100" align="center"><?php echo JText::_( 'VBPVIEWCOUPONSFOUR' ); ?></th>
			<th class="title left" width="100" align="center"><?php echo JText::_( 'VBPVIEWCOUPONSFIVE' ); ?></th>
		</tr>
		</thead>
		<?php
		$currencysymb=vikbooking::getCurrencySymb(true);
		$nowdf = vikbooking::getDateFormat(true);
		if ($nowdf=="%d/%m/%Y") {
			$df='d/m/Y';
		}elseif ($nowdf=="%m/%d/%Y") {
			$df='m/d/Y';
		}else {
			$df='Y/m/d';
		}
		$k = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$strtype = $row['type'] == 1 ? JText::_('VBCOUPONTYPEPERMANENT') : JText::_('VBCOUPONTYPEGIFT');
			$strtype .= ", ".$row['value']." ".($row['percentot'] == 1 ? "%" : $currencysymb);
			$strdate = JText::_('VBCOUPONALWAYSVALID');
			if(strlen($row['datevalid']) > 0) {
				$dparts = explode("-", $row['datevalid']);
				$strdate = date($df, $dparts[0])." - ".date($df, $dparts[1]);
			}
			$totvehicles = 0;
			if(intval($row['allvehicles']) == 0) {
				$allve = explode(";", $row['idrooms']);
				foreach($allve as $fv) {
					if(!empty($fv)) {
						$totvehicles++;
					} 
				}
			}
			?>
			<tr class="row<?php echo $k; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td><a href="index.php?option=com_vikbooking&amp;task=editcoupon&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['code']; ?></a></td>
				<td align="center"><?php echo $strtype; ?></td>
				<td align="center"><?php echo $strdate; ?></td>
				<td align="center"><?php echo intval($row['allvehicles']) == 1 ? JText::_('VBCOUPONALLVEHICLES') : $totvehicles; ?></td>
				<td align="center"><?php echo $row['mintotord']; ?></td>
			</tr>	
			<?php
			$k = 1 - $k;
			
		}
		?>
		
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<?php
		if(defined('JVERSION') && version_compare(JVERSION, '1.6.0') < 0) {
			//Joomla 1.5
			
		}
		?>
		<input type="hidden" name="task" value="viewcoupons" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php echo $navbut; ?>
	</form>
	<?php
		}
	}
	
	public static function pViewCustomf ($rows, $option, $lim0="0", $navbut="") {
		if(empty($rows)){
			?>
			<p class="warn"><?php echo JText::_('VBNOFIELDSFOUND'); ?></p>
			<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
			<?php
		}else{
		
		?>
   <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

	<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped">
		<thead>
		<tr>
			<th width="20">
				<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
			</th>
			<th class="title left" width="200"><?php echo JText::_( 'VBPVIEWCUSTOMFONE' ); ?></th>
			<th class="title left" width="200"><?php echo JText::_( 'VBPVIEWCUSTOMFTWO' ); ?></th>
			<th class="title center" width="100" align="center"><?php echo JText::_( 'VBPVIEWCUSTOMFTHREE' ); ?></th>
			<th class="title center" width="100" align="center"><?php echo JText::_( 'VBPVIEWCUSTOMFFOUR' ); ?></th>
			<th class="title center" width="100" align="center"><?php echo JText::_( 'VBPVIEWCUSTOMFFIVE' ); ?></th>
		</tr>
		</thead>
		<?php

		$k = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			?>
			<tr class="row<?php echo $k; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td><a href="index.php?option=com_vikbooking&amp;task=editcustomf&amp;cid[]=<?php echo $row['id']; ?>"><?php echo JText::_($row['name']); ?></a></td>
				<td><?php echo ucwords($row['type']).($row['isnominative'] == 1 ? ' <span class="badge">'.JText::_('VBISNOMINATIVE').'</span>' : '').($row['isphone'] == 1 ? ' <span class="badge">'.JText::_('VBISPHONENUMBER').'</span>' : ''); ?></td>
				<td class="center"><?php echo intval($row['required']) == 1 ? "<img src=\"".JURI::root()."administrator/components/com_vikbooking/resources/ok.png\"/>" : "<img src=\"".JURI::root()."administrator/components/com_vikbooking/resources/no.png\"/>"; ?></td>
				<td class="center"><a href="index.php?option=com_vikbooking&amp;task=sortfield&amp;cid[]=<?php echo $row['id']; ?>&amp;mode=up"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikbooking/resources/up.png" border="0"/></a> <a href="index.php?option=com_vikbooking&amp;task=sortfield&amp;cid[]=<?php echo $row['id']; ?>&amp;mode=down"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikbooking/resources/down.png" border="0"/></a></td>
				<td class="center"><?php echo intval($row['isemail']) == 1 ? "<img src=\"".JURI::root()."administrator/components/com_vikbooking/resources/ok.png\"/>" : "<img src=\"".JURI::root()."administrator/components/com_vikbooking/resources/no.png\"/>"; ?></td>
			</tr>	
			<?php
			$k = 1 - $k;
			
		}
		?>
		
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<?php
		if(defined('JVERSION') && version_compare(JVERSION, '1.6.0') < 0) {
			//Joomla 1.5
			
		}
		?>
		<input type="hidden" name="task" value="viewcustomf" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php echo $navbut; ?>
	</form>
	<?php
		}
	}
	
	public static function pViewCategories ($rows, $option, $lim0="0", $navbut="") {
		if(empty($rows)){
			?>
			<p class="warn"><?php echo JText::_('VBNOCATEGORIESFOUND'); ?></p>
			<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
			<?php
		}else{
		
		?>
   <script type="text/javascript">
function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'removecat') {
				if (confirm('<?php echo JText::_('VBJSDELCATEGORIES'); ?> ?')){
					submitform( pressbutton );
					return;
				}else{
					return false;
				}
			}

			// do field validation
			try {
				document.adminForm.onsubmit();
			}
			catch(e){}
			submitform( pressbutton );
		}
</script>
   <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

	<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped">
		<thead>
		<tr>
			<th width="20">
				<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
			</th>
			<th class="title left" width="150"><?php echo JText::_( 'VBPVIEWCATEGORIESONE' ); ?></th>
			<th class="title left" width="150"><?php echo JText::_( 'VBPVIEWCATEGORIESDESCR' ); ?></th>
		</tr>
		</thead>
		<?php

		$k = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			?>
			<tr class="row<?php echo $k; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td><a href="index.php?option=com_vikbooking&amp;task=editcat&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td><?php echo strip_tags($row['descr']); ?></td>
			</tr>
			<?php
			$k = 1 - $k;
			
		}
		?>
		
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<?php
		if(defined('JVERSION') && version_compare(JVERSION, '1.6.0') < 0) {
			//Joomla 1.5
			
		}
		?>
		<input type="hidden" name="task" value="viewcategories" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php echo $navbut; ?>
	</form>
	<?php
		}
	}
	
	public static function pViewCarat ($rows, $option, $lim0="0", $navbut="") {
		if(empty($rows)){
			?>
			<p class="warn"><?php echo JText::_('VBNOCARATFOUND'); ?></p>
			<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
			<?php
		}else{
		
		?>
   <script type="text/javascript">
function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'removecarat') {
				if (confirm('<?php echo JText::_('VBJSDELCARAT'); ?> ?')){
					submitform( pressbutton );
					return;
				}else{
					return false;
				}
			}

			// do field validation
			try {
				document.adminForm.onsubmit();
			}
			catch(e){}
			submitform( pressbutton );
		}
</script>
   <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

	<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped">
		<thead>
		<tr>
			<th width="20">
				<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
			</th>
			<th class="title left" width="150"><?php echo JText::_( 'VBPVIEWCARATONE' ); ?></th>
			<th class="title left" width="150"><?php echo JText::_( 'VBPVIEWCARATTWO' ); ?></th>
			<th class="title left" width="250"><?php echo JText::_( 'VBPVIEWCARATTHREE' ); ?></th>
		</tr>
		</thead>
		<?php

		$k = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			?>
			<tr class="row<?php echo $k; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td><a href="index.php?option=com_vikbooking&amp;task=editcarat&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td>
				<?php 
					echo (file_exists('../components/com_vikbooking/resources/uploads/'.$row['icon']) ? "<span>".$row['icon']." &nbsp;&nbsp;<img align=\"middle\" src=\"".JURI::root()."components/com_vikbooking/resources/uploads/".$row['icon']."\"/></span>" : $row['icon']); 
				?>
				</td>
				<td><?php echo $row['textimg']; ?></td>
			</tr>	
			  <?php
			$k = 1 - $k;
			
		}
		?>
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<?php
		if(defined('JVERSION') && version_compare(JVERSION, '1.6.0') < 0) {
			//Joomla 1.5
			
		}
		?>
		<input type="hidden" name="task" value="viewcarat" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php echo $navbut; ?>
	</form>
	<?php
		}
	}
	
	public static function pViewOptionals ($rows, $option, $lim0="0", $navbut="") {
		JHTML::_('behavior.modal');
		if(empty($rows)){
			?>
			<p class="warn"><?php echo JText::_('VBNOOPTIONALSFOUND'); ?></p>
			<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
			<?php
		}else{
		
		?>
   <script type="text/javascript">
function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'removeoptionals') {
				if (confirm('<?php echo JText::_('VBJSDELOPTIONALS'); ?> ?')){
					submitform( pressbutton );
					return;
				}else{
					return false;
				}
			}

			// do field validation
			try {
				document.adminForm.onsubmit();
			}
			catch(e){}
			submitform( pressbutton );
		}
</script>
   <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

	<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped">
		<thead>
		<tr>
			<th width="20">
				<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
			</th>
			<th class="title left" width="150"><?php echo JText::_( 'VBPVIEWOPTIONALSONE' ); ?></th>
			<th class="title left" width="150"><?php echo JText::_( 'VBPVIEWOPTIONALSTWO' ); ?></th>
			<th class="title center" align="center" width="75"><?php echo JText::_( 'VBPVIEWOPTIONALSTHREE' ); ?></th>
			<th class="title center" align="center" width="75"><?php echo JText::_( 'VBPVIEWOPTIONALSFOUR' ); ?></th>
			<th class="title center" align="center" width="75"><?php echo JText::_( 'VBPVIEWOPTIONALSEIGHT' ); ?></th>
			<th class="title center" align="center" width="75"><?php echo JText::_( 'VBPVIEWOPTIONALSFIVE' ); ?></th>
			<th class="title center" align="center" width="75"><?php echo JText::_( 'VBPVIEWOPTIONALSPERPERS' ); ?></th>
			<th class="title center" align="center" width="150"><?php echo JText::_( 'VBPVIEWOPTIONALSSIX' ); ?></th>
			<th class="title left" width="150"><?php echo JText::_( 'VBPVIEWOPTIONALSSEVEN' ); ?></th>
			<th class="title center" align="center" width="50"><?php echo JText::_( 'VBPVIEWOPTIONALSORDERING' ); ?></th>
		</tr>
		</thead>
		<?php

		$k = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			?>
			<tr class="row<?php echo $k; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td><a href="index.php?option=com_vikbooking&amp;task=editoptional&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td><?php echo (strlen($row['descr'])>150 ? substr($row['descr'], 0, 150) : $row['descr']); ?></td>
				<td class="center"><?php echo $row['cost']; ?></td>
				<td class="center"><?php echo vikbooking::getAliq($row['idiva']); ?>%</td>
				<td class="center"><?php echo $row['maxprice']; ?></td>
				<td class="center"><?php echo (intval($row['perday'])==1 ? "Y" : "N"); ?></td>
				<td class="center"><?php echo (intval($row['perperson'])==1 ? "Y" : "N"); ?></td>
				<td class="center"><?php echo (intval($row['hmany'])==1 ? "&gt; 1" : "1"); ?></td>
				<td><?php echo (file_exists('../components/com_vikbooking/resources/uploads/'.$row['img']) ? '<a href="'.JURI::root().'components/com_vikbooking/resources/uploads/'.$row['img'].'" class="modal" target="_blank">'.$row['img'].'</a>' : ''); ?></td>
				<td class="center"><a href="index.php?option=com_vikbooking&amp;task=sortoption&amp;cid[]=<?php echo $row['id']; ?>&amp;mode=up"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikbooking/resources/up.png" border="0"/></a> <a href="index.php?option=com_vikbooking&amp;task=sortoption&amp;cid[]=<?php echo $row['id']; ?>&amp;mode=down"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikbooking/resources/down.png" border="0"/></a></td>
			</tr>
			  <?php
			$k = 1 - $k;
			
		}
		?>
		
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<?php
		if(defined('JVERSION') && version_compare(JVERSION, '1.6.0') < 0) {
			//Joomla 1.5
			
		}
		?>
		<input type="hidden" name="task" value="viewoptionals" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php echo $navbut; ?>
	</form>
	<?php
		}
	}
	
	public static function pViewPrices ($rows, $option, $lim0="0", $navbut="") {
		if(empty($rows)){
			?>
			<p class="warn"><?php echo JText::_('VBNOPRICESFOUND'); ?></p>
			<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
			<?php
		}else{
		
		?>
   <script type="text/javascript">
function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'removeprice') {
				if (confirm('<?php echo JText::_('VBJSDELPRICES'); ?>')){
					submitform( pressbutton );
					return;
				}else{
					return false;
				}
			}

			// do field validation
			try {
				document.adminForm.onsubmit();
			}
			catch(e){}
			submitform( pressbutton );
		}
</script>
   <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

	<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped">
		<thead>
		<tr>
			<th width="20">
				<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
			</th>
			<th class="title left" width="150"><?php echo JText::_( 'VBPVIEWPRICESONE' ); ?></th>
			<th class="title left" width="150"><?php echo JText::_( 'VBPVIEWPRICESTWO' ); ?></th>
			<th class="title left" width="75"><?php echo JText::_( 'VBPVIEWPRICESTHREE' ); ?></th>
			<th class="title center" width="75"><?php echo JText::_( 'VBNEWPRICEBREAKFAST' ); ?></th>
			<th class="title center" width="75"><?php echo JText::_( 'VBNEWPRICEFREECANC' ); ?></th>
		</tr>
		</thead>
		<?php

		$k = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			?>
			<tr class="row<?php echo $k; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td><a href="index.php?option=com_vikbooking&amp;task=editprice&cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td><?php echo $row['attr']; ?></td>
				<td><?php echo vikbooking::getAliq($row['idiva']); ?>%</td>
				<td class="center"><?php echo (intval($row['breakfast_included'])==1 ? "<img src=\"".JURI::root()."administrator/components/com_vikbooking/resources/ok.png"."\" border=\"0\"/>" : "<img src=\"".JURI::root()."administrator/components/com_vikbooking/resources/no.png"."\" border=\"0\"/>"); ?></td>
				<td class="center"><?php echo (intval($row['free_cancellation'])==1 ? "<img src=\"".JURI::root()."administrator/components/com_vikbooking/resources/ok.png"."\" border=\"0\" title=\"".JText::sprintf('VBNEWPRICEFREECANCDLINETIP', $row['canc_deadline'])."\"/>" : "<img src=\"".JURI::root()."administrator/components/com_vikbooking/resources/no.png"."\" border=\"0\"/>"); ?></td>
			</tr>
			  <?php
			$k = 1 - $k;
			
		}
		?>
		
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<?php
		if(defined('JVERSION') && version_compare(JVERSION, '1.6.0') < 0) {
			//Joomla 1.5
			
		}
		?>
		<input type="hidden" name="task" value="viewprices" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php echo $navbut; ?>
	</form>
	<?php
		}
	}
	
	public static function pEditBusy ($ordersrooms, $ord, $all_rooms, $option) {
		$dbo = JFactory::getDBO();
		$pgoto = JRequest::getString('goto', '', 'request');
		$currencysymb=vikbooking::getCurrencySymb(true);
		JHTML::_('behavior.calendar');
		$nowdf = vikbooking::getDateFormat(true);
		if ($nowdf=="%d/%m/%Y") {
			$rit=date('d/m/Y', $ord[0]['checkin']);
			$con=date('d/m/Y', $ord[0]['checkout']);
			$df='d/m/Y';
		}elseif ($nowdf=="%m/%d/%Y") {
			$rit=date('m/d/Y', $ord[0]['checkin']);
			$con=date('m/d/Y', $ord[0]['checkout']);
			$df='m/d/Y';
		}else {
			$rit=date('Y/m/d', $ord[0]['checkin']);
			$con=date('Y/m/d', $ord[0]['checkout']);
			$df='Y/m/d';
		}
		$arit=getdate($ord[0]['checkin']);
		$acon=getdate($ord[0]['checkout']);
		for($i=0; $i < 24; $i++){
			$ritho.="<option value=\"".$i."\"".($arit['hours']==$i ? " selected=\"selected\"" : "").">".($i < 10 ? "0".$i : $i)."</option>\n";
			$conho.="<option value=\"".$i."\"".($acon['hours']==$i ? " selected=\"selected\"" : "").">".($i < 10 ? "0".$i : $i)."</option>\n";
		}
		for($i=0; $i < 60; $i++){
			$ritmi.="<option value=\"".$i."\"".($arit['minutes']==$i ? " selected=\"selected\"" : "").">".($i < 10 ? "0".$i : $i)."</option>\n";
			$conmi.="<option value=\"".$i."\"".($acon['minutes']==$i ? " selected=\"selected\"" : "").">".($i < 10 ? "0".$i : $i)."</option>\n";
		}
		if(is_array($ord)) {
			$pcheckin = $ord[0]['checkin'];
			$pcheckout = $ord[0]['checkout'];
			$secdiff = $pcheckout - $pcheckin;
			$daysdiff = $secdiff / 86400;
			if (is_int($daysdiff)) {
				if ($daysdiff < 1) {
					$daysdiff = 1;
				}
			}else {
				if ($daysdiff < 1) {
					$daysdiff = 1;
				}else {
					$sum = floor($daysdiff) * 86400;
					$newdiff = $secdiff - $sum;
					$maxhmore = vikbooking::getHoursMoreRb() * 3600;
					if ($maxhmore >= $newdiff) {
						$daysdiff = floor($daysdiff);
					} else {
						$daysdiff = ceil($daysdiff);
					}
				}
			}
		}
		//Package or custom rate
		$is_package = !empty($ord[0]['pkg']) ? true : false;
		$is_cust_cost = false;
		foreach($ordersrooms as $kor => $or) {
			if($is_package !== true && !empty($or['cust_cost']) && $or['cust_cost'] > 0.00) {
				$is_cust_cost = true;
				break;
			}
		}
		$wiva = "";
		$q="SELECT * FROM `#__vikbooking_iva`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$ivas=$dbo->loadAssocList();
			$wiva="<select name=\"aliq%s\"><option value=\"\">".JText::_('VBNEWOPTFOUR')."</option>\n";
			foreach($ivas as $iv){
				$wiva.="<option value=\"".$iv['id']."\" data-aliqid=\"".$iv['id']."\">".(empty($iv['name']) ? $iv['aliq']."%" : $iv['name']."-".$iv['aliq']."%")."</option>\n";
			}
			$wiva.="</select>\n";
		}
		//
		//VikBooking 1.5 room switching
		$switching = false;
		$switcher = '';
		if (is_array($ord) && count($all_rooms) > 1 && (!empty($ordersrooms[0]['idtar']) || $is_package || $is_cust_cost)) {
			$switching = true;
			$occ_rooms = array();
			foreach($all_rooms as $r) {
				$rkey = $r['fromadult'] < $r['toadult'] ? $r['fromadult'].' - '.$r['toadult'] : $r['toadult'];
				$occ_rooms[$rkey][] = $r;
			}
			$switcher = '<select name="%s" id="vbswr%d" onchange="vbIsSwitchable(this.value, %d, %d);"><option value=""> -------- </option>'."\n";
			foreach($occ_rooms as $occ => $rr) {
				$switcher .= '<optgroup label="'.JText::sprintf('VBSWROOMOCC', $occ).'">'."\n";
				foreach($rr as $r) {
					$switcher .= '<option value="'.$r['id'].'">'.$r['name'].'</option>'."\n";
				}
				$switcher .= '</optgroup>'."\n";
			}
			$switcher .= '</select>'."\n";
		}
		//
		?>
		<script type="text/javascript">
		Joomla.submitbutton = function(task) {
			if( task == 'removebusy' ) {
				if (confirm('<?php echo addslashes(JText::_('VBDELCONFIRM')); ?>')) {
					Joomla.submitform(task, document.adminForm);
				}else {
					return false;
				}
			}else {
				Joomla.submitform(task, document.adminForm);
			}
		}
		function vbIsSwitchable(toid, fromid, orid) {
			if(parseInt(toid) == parseInt(fromid)) {
				document.getElementById('vbswr'+orid).value = '';
				return false;
			}
			return true;
		}
		</script>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
		<?php if(!is_array($ord)){ ?>
		<p><?php echo JText::_('VBPEDITBUSYONE'); ?></p>	
		<?php }else{ ?>
		<p><strong><?php echo JText::_('VBPEDITBUSYTWO'); ?></strong>: <?php echo date($df.' H:i', $ord[0]['ts']); ?> - <strong><?php echo JText::_('VBPEDITBUSYTHREE'); ?> <?php echo ($ord[0]['days']==1 ? "1 ".JText::_('VBDAY') : $ord[0]['days']." ".JText::_('VBDAYS')); ?></strong></p>
		<?php 	echo (!empty($ord[0]['custdata']) ? "<textarea name=\"custdata\" rows=\"7\" cols=\"30\" style=\"min-width: 50%;\">".$ord[0]['custdata']."</textarea>" : "<textarea name=\"custdata\" rows=\"7\" cols=\"30\" style=\"min-width: 50%;\"></textarea>"); 
			} ?>
		<table class="adminform">
		<tr><td width="200"><b><?php echo JText::_('VBPEDITBUSYFOUR'); ?>:</b> </td><td><?php echo JHTML::_('calendar', '', 'checkindate', 'checkindate', vikbooking::getDateFormat(true), array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?> <?php echo JText::_('VBPEDITBUSYFIVE'); ?> <select name="checkinh"><?php echo $ritho; ?></select> <select name="checkinm"><?php echo $ritmi; ?></select></td></tr>
		<tr><td><b><?php echo JText::_('VBPEDITBUSYSIX'); ?>:</b> </td><td><?php echo JHTML::_('calendar', '', 'checkoutdate', 'checkoutdate', vikbooking::getDateFormat(true), array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?> <?php echo JText::_('VBPEDITBUSYFIVE'); ?> <select name="checkouth"><?php echo $conho; ?></select> <select name="checkoutm"><?php echo $conmi; ?></select></td></tr>
		<?php
		if (is_array($ord) && (!empty($ordersrooms[0]['idtar']) || $is_package || $is_cust_cost)) {
			//order from front end or correctly saved
			$proceedtars = true;
			$rooms = array();
			$tars = array();
			$arrpeople = array();
			foreach($ordersrooms as $kor => $or) {
				$num = $kor + 1;
				$rooms[$num] = $or;
				$arrpeople[$num]['adults'] = $or['adults'];
				$arrpeople[$num]['children'] = $or['children'];
				if($is_package) {
					continue;
				}
				$q="SELECT * FROM `#__vikbooking_dispcost` WHERE `days`='".$ord[0]['days']."' AND `idroom`='".$or['idroom']."' ORDER BY `#__vikbooking_dispcost`.`cost` ASC;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($dbo->getNumRows() > 0) {
					$tar = $dbo->loadAssocList();
					$tar = vikbooking::applySeasonsRoom($tar, $ord[0]['checkin'], $ord[0]['checkout']);
					//different usage
					if ($or['fromadult'] <= $or['adults'] && $or['toadult'] >= $or['adults']) {
						$diffusageprice = vikbooking::loadAdultsDiff($or['idroom'], $or['adults']);
						//Occupancy Override
						$occ_ovr = vikbooking::occupancyOverrideExists($tar, $or['adults']);
						$diffusageprice = $occ_ovr !== false ? $occ_ovr : $diffusageprice;
						//
						if (is_array($diffusageprice)) {
							//set a charge or discount to the price(s) for the different usage of the room
							foreach($tar as $kpr => $vpr) {
								$tar[$kpr]['diffusage'] = $or['adults'];
								if ($diffusageprice['chdisc'] == 1) {
									//charge
									if ($diffusageprice['valpcent'] == 1) {
										//fixed value
										$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
										$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $tar[$kpr]['days'] : $diffusageprice['value'];
										$tar[$kpr]['diffusagecost'] = "+".$aduseval;
										$tar[$kpr]['cost'] = $vpr['cost'] + $aduseval;
									}else {
										//percentage value
										$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
										$aduseval = $diffusageprice['pernight'] == 1 ? round(($vpr['cost'] * $diffusageprice['value'] / 100) * $tar[$kpr]['days'] + $vpr['cost'], 2) : round(($vpr['cost'] * (100 + $diffusageprice['value']) / 100), 2);
										$tar[$kpr]['diffusagecost'] = "+".$diffusageprice['value']."%";
										$tar[$kpr]['cost'] = $aduseval;
									}
								}else {
									//discount
									if ($diffusageprice['valpcent'] == 1) {
										//fixed value
										$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
										$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $tar[$kpr]['days'] : $diffusageprice['value'];
										$tar[$kpr]['diffusagecost'] = "-".$aduseval;
										$tar[$kpr]['cost'] = $vpr['cost'] - $aduseval;
									}else {
										//percentage value
										$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
										$aduseval = $diffusageprice['pernight'] == 1 ? round($vpr['cost'] - ((($vpr['cost'] / $tar[$kpr]['days']) * $diffusageprice['value'] / 100) * $tar[$kpr]['days']), 2) : round(($vpr['cost'] * (100 - $diffusageprice['value']) / 100), 2);
										$tar[$kpr]['diffusagecost'] = "-".$diffusageprice['value']."%";
										$tar[$kpr]['cost'] = $aduseval;
									}
								}
							}
						}
					}
					//
					$tars[$num] = $tar;
				}else {
					$proceedtars = false;
					break;
				}
			}
			if ($proceedtars) {
				?>
				<input type="hidden" name="areprices" value="yes"/>
				<?php
				foreach($ordersrooms as $kor => $or) {
					$num = $kor + 1;
					$switch_code = '';
					if($switching) {
						$switch_code = '<div class="vbswitchrblock"><label for="vbswr'.$or['id'].'">'.JText::_('VBSWITCHRWITH').'</label> '.sprintf($switcher, 'switch_'.$or['id'], $or['id'], $or['idroom'], $or['id']) .'</div>';
					}
					?>
					<tr><td colspan="2"><h4 class="vbheadfourblue"><?php echo $or['name']; ?>, <?php echo JText::_('VBMAILADULTS').': '.$or['adults']; ?><?php echo ($or['children'] > 0 ? " - ".JText::_('VBMAILCHILDREN').': '.$or['children'] : ""); ?><span class="vbo-ebusy-toggle-aduchild" id="toggle-aduchild-<?php echo $num; ?>" onclick="JavaScript: var vboel = document.getElementById('aduchild-cont-<?php echo $num; ?>'); if(vboel.style.display == 'none'){vboel.style.display = 'block';this.className += ' vbo-ebusy-toggle-aduchild-on';}else{vboel.style.display = 'none';this.className = 'vbo-ebusy-toggle-aduchild';}"><i class="vboicn-man-woman"></i></span></h4></td></tr>
					<?php
					$from_a = $or['fromadult'];
					$from_a = $from_a > $or['adults'] ? $or['adults'] : $from_a;
					$to_a = $or['toadult'];
					$to_a = $to_a < $or['adults'] ? $or['adults'] : $to_a;
					$from_c = $or['fromchild'];
					$from_c = $from_c > $or['children'] ? $or['children'] : $from_c;
					$to_c = $or['tochild'];
					$to_c = $to_c < $or['children'] ? $or['children'] : $to_c;
					$adults_opts = '';
					$children_opts = '';
					for ($z = $from_a; $z <= $to_a; $z++) {
						$adults_opts .= '<option value="'.$z.'"'.($z == $or['adults'] ? ' selected="selected"' : '').'>'.$z.'</option>';
					}
					for ($z = $from_c; $z <= $to_c; $z++) {
						$children_opts .= '<option value="'.$z.'"'.($z == $or['children'] ? ' selected="selected"' : '').'>'.$z.'</option>';
					}
					$toggle_aduchild = '<div class="aduchild-cont" id="aduchild-cont-'.$num.'" style="display: none;"><select name="adults'.$num.'"><option value="">'.JText::_('VBMAILADULTS').'</option>'.$adults_opts.'</select> <select name="children'.$num.'"><option value="">'.JText::_('VBMAILCHILDREN').'</option>'.$children_opts.'</select></div>';
					if(!empty($switch_code)) {
						?>
						<tr><td><?php echo $toggle_aduchild; ?></td><td rowspan="2"><?php echo $switch_code; ?></td></tr>
						<?php
					}else {
						?>
						<tr><td colspan="2"><?php echo $toggle_aduchild; ?></td></tr>
						<?php
					}
					?>
					<tr><td colspan="<?php echo !empty($switch_code) ? '1' : '2'; ?>"><span class="vbo-ebusy-lbl"><?php echo JText::_('VBPEDITBUSYTRAVELERINFO'); ?></span></td></tr>
					<tr><td colspan="2"><label for="t_first_name<?php echo $num; ?>"><?php echo JText::_('VBTRAVELERNAME'); ?></label> <input type="text" name="t_first_name<?php echo $num; ?>" id="t_first_name<?php echo $num; ?>" value="<?php echo $or['t_first_name']; ?>" size="12"/></td></tr>
					<tr><td colspan="2"><label for="t_last_name<?php echo $num; ?>"><?php echo JText::_('VBTRAVELERLNAME'); ?></label> <input type="text" name="t_last_name<?php echo $num; ?>" id="t_last_name<?php echo $num; ?>" value="<?php echo $or['t_last_name']; ?>" size="12"/></td></tr>
					<tr><td colspan="2"><span class="vbo-ebusy-lbl"><?php echo JText::_('VBPEDITBUSYSEVEN'); ?></span></td></tr>
					<tr><td colspan="2"><table class="vbo-ebusy-tars-table">
					<?php
					$is_cust_cost = !empty($or['cust_cost']) && $or['cust_cost'] > 0.00 ? true : false;
					if($is_package || $is_cust_cost) {
						if($is_package) {
							$pkg_name = (!empty($or['pkg_name']) ? $or['pkg_name'] : JText::_('VBOROOMCUSTRATEPLAN'));
							?>
							<tr><td><label for="pid<?php echo $num.$or['id']; ?>"><?php echo $pkg_name; ?></label></td><td><?php echo $currencysymb." ".vikbooking::numberFormat($or['cust_cost']); ?></td><td><input type="radio" name="pkgid<?php echo $num; ?>" id="pid<?php echo $num.$or['id']; ?>" value="<?php echo $or['pkg_id']; ?>" checked="checked" /></td></tr>
							<?php
						}else {
							//custom rate
							?>
							<tr><td><label for="pid<?php echo $num.$or['id']; ?>" class="hasTooltip" title="<?php echo JText::_('VBOROOMCUSTRATETAXHELP'); ?>"><?php echo JText::_('VBOROOMCUSTRATEPLAN'); ?></label></td><td><?php echo $currencysymb; ?> <input type="text" name="cust_cost<?php echo $num; ?>" value="<?php echo $or['cust_cost']; ?>" size="4" /><div id="tax<?php echo $num; ?>" style="display: block;"><?php echo (!empty($wiva) ? str_replace('%s', $num, str_replace('data-aliqid="'.(int)$or['cust_idiva'].'"', 'selected="selected"', $wiva)) : ''); ?></div></td><td><input type="radio" name="priceid<?php echo $num; ?>" id="pid<?php echo $num.$or['id']; ?>" value="" checked="checked" /></td></tr>
							<?php
							//print the standard rates anyway
							foreach($tars[$num] as $k => $t) {
							?>
							<tr><td><label for="pid<?php echo $num.$t['idprice']; ?>"><?php echo vikbooking::getPriceName($t['idprice']).(strlen($t['attrdata']) ? "<br/>".vikbooking::getPriceAttr($t['idprice']).": ".$t['attrdata'] : ""); ?></label></td><td><?php echo $currencysymb." ".vikbooking::numberFormat(vikbooking::sayCostPlusIva($t['cost'], $t['idprice'])); ?></td><td><input type="radio" name="priceid<?php echo $num; ?>" id="pid<?php echo $num.$t['idprice']; ?>" value="<?php echo $t['idprice']; ?>" /></td></tr>
							<?php
							}
						}
					}else {
						foreach($tars[$num] as $k => $t) {
							?>
							<tr><td><label for="pid<?php echo $num.$t['idprice']; ?>"><?php echo vikbooking::getPriceName($t['idprice']).(strlen($t['attrdata']) ? "<br/>".vikbooking::getPriceAttr($t['idprice']).": ".$t['attrdata'] : ""); ?></label></td><td><?php echo $currencysymb." ".vikbooking::numberFormat(vikbooking::sayCostPlusIva($t['cost'], $t['idprice'])); ?></td><td><input type="radio" name="priceid<?php echo $num; ?>" id="pid<?php echo $num.$t['idprice']; ?>" value="<?php echo $t['idprice']; ?>"<?php echo ($t['id']==$or['idtar'] ? " checked=\"checked\"" : ""); ?>/></td></tr>
							<?php
						}
						//print the set custom rate anyway
						?>
							<tr><td><label for="cust_cost<?php echo $num; ?>" class="vbo-custrate-lbl-add hasTooltip" title="<?php echo JText::_('VBOROOMCUSTRATETAXHELP'); ?>"><?php echo JText::_('VBOROOMCUSTRATEPLANADD'); ?></label></td><td><?php echo $currencysymb; ?> <input type="text" name="cust_cost<?php echo $num; ?>" id="cust_cost<?php echo $num; ?>" value="" placeholder="<?php echo vikbooking::numberFormat(0); ?>" size="4" onblur="if(this.value.length) {document.getElementById('priceid<?php echo $num; ?>').checked = true; document.getElementById('tax<?php echo $num; ?>').style.display = 'block';}" /><div id="tax<?php echo $num; ?>" style="display: none;"><?php echo (!empty($wiva) ? str_replace('%s', $num, $wiva) : ''); ?></div></td><td><input type="radio" name="priceid<?php echo $num; ?>" id="priceid<?php echo $num; ?>" value="" onclick="document.getElementById('tax<?php echo $num; ?>').style.display = 'block';" /></td></tr>
						<?php
					}
					?>
					</table></td></tr>
					<?php
					if(!empty($or['idopt'])) {
						$optionals=vikbooking::getRoomOptionals($or['idopt']);
						$arropt = array();
						if (is_array($optionals)) {
							list($optionals, $ageintervals) = vikbooking::loadOptionAgeIntervals($optionals);
							if (is_array($ageintervals)) {
								if (is_array($optionals)) {
									$ageintervals = array(0 => $ageintervals);
									$optionals = array_merge($ageintervals, $optionals);
								}else {
									$optionals = array(0 => $ageintervals);
								}
							}
							if (!empty($or['optionals'])) {
								$haveopt=explode(";", $or['optionals']);
								foreach($haveopt as $ho){
									if (!empty($ho)) {
										$havetwo=explode(":", $ho);
										if (strstr($havetwo[1], '-') != false) {
											$arropt[$havetwo[0]][]=$havetwo[1];
										}else {
											$arropt[$havetwo[0]]=$havetwo[1];
										}
									}
								}
							}else {
								$arropt[]="";
							}
							?>
							<tr><td colspan="2"><span class="vbo-ebusy-lbl"><?php echo JText::_('VBPEDITBUSYEIGHT'); ?></span></td></tr>
							<tr><td colspan="2"><table class="vbo-ebusy-opt-table">
							<?php
							foreach($optionals as $k=>$o) {
								if (intval($o['hmany'])==1) {
									if (array_key_exists($o['id'], $arropt)) {
										$oval=$arropt[$o['id']];
									}else {
										$oval="";
									}
								}else {
									if (array_key_exists($o['id'], $arropt) && !is_array($arropt[$o['id']])) {
										$oval=" checked=\"checked\"";
									}else {
										$oval="";
									}
								}
								if (!empty($o['ageintervals'])) {
									if($or['children'] > 0) {
										for($ch = 1; $ch <= $or['children']; $ch++) {
											$optagecosts = vikbooking::getOptionIntervalsCosts($o['ageintervals']);
											$optagenames = vikbooking::getOptionIntervalsAges($o['ageintervals']);
											$chageselect = '<select name="optid'.$num.$o['id'].'[]">'."\n".'<option value="">  </option>'."\n";
											$intervals = explode(';;', $o['ageintervals']);
											foreach($intervals as $kintv => $intv) {
												if (empty($intv)) continue;
												$intvparts = explode('_', $intv);
												$intvparts[2] = intval($o['perday']) == 1 ? ($intvparts[2] * $ord[0]['days']) : $intvparts[2];
												$pricestr = floatval($intvparts[2]) >= 0 ? '+ '.vikbooking::numberFormat(vikbooking::sayOptionalsPlusIva($intvparts[2], $o['idiva'])) : '- '.vikbooking::numberFormat($intvparts[2]);
												$selstatus = '';
												if(is_array($arropt[$o['id']])) {
													$ageparts = explode('-', $arropt[$o['id']][($ch - 1)]);
													if ($kintv == ($ageparts[1] - 1)) {
														$selstatus = ' selected="selected"';
													}
												}
												$chageselect .= '<option value="'.($kintv + 1).'"'.$selstatus.'>'.$intvparts[0].' - '.$intvparts[1].' ('.$pricestr.' '.$currencysymb.')'.'</option>'."\n";
											}
											$chageselect .= '</select>'."\n";
											?>
											<tr><td><?php echo JText::_('VBMAILCHILD').' #'.$ch; ?></td><td colspan="3"><?php echo $chageselect; ?></td></tr>
											<?php
										}
									}
								}else {
									if(intval($o['perday'])==1) {
										$thisoptcost=$o['cost'] * $ord[0]['days'];
									}else {
										$thisoptcost=$o['cost'];
									}
									if($o['maxprice'] > 0 && $thisoptcost > $o['maxprice']) {
										$thisoptcost=$o['maxprice'];
									}
									if(intval($o['perperson'])==1) {
										$thisoptcost=$thisoptcost * $arrpeople[$num]['adults'];
									}
									?>	
									<tr>
										<td><?php echo $o['name']; ?></td>
										<td align="center"><?php echo $currencysymb; ?> <?php echo vikbooking::numberFormat(vikbooking::sayOptionalsPlusIva($thisoptcost, $o['idiva'])); ?></td>
										<td align="center"><?php echo (intval($o['hmany'])==1 ? "<input type=\"number\" name=\"optid".$num.$o['id']."\" value=\"".$oval."\" min=\"0\" size=\"4\" style=\"width: 50px !important;\"/>" : "<input type=\"checkbox\" name=\"optid".$num.$o['id']."\" value=\"1\"".$oval."/>"); ?></td>
										<td><?php echo (!empty($o['img']) ? "<img src=\"".JURI::root()."components/com_vikbooking/resources/uploads/".$o['img']."\" class=\"maxfifty\"/>" : ""); ?></td>
									</tr>
									<?php
								}
							}
							?>
							</table></td></tr>
							<?php
						}
					}
				}
				
				?>
				<tr><td>&nbsp;</td></tr>
				<tr><td colspan="2"><table class="vbo-ebusy-tars-table">
					<tr><td><strong><?php echo JText::_('VBPEDITBUSYTOTPAID'); ?></strong></td><td><?php echo $currencysymb; ?> <input type="text" name="totpaid" value="<?php echo $ord[0]['totpaid']; ?>" size="6"/></td></tr>
				</table></td></tr>
				<?php
			}else {
				?>
				<tr><td colspan="2"><p class="err"><?php echo JText::_('VBPEDITBUSYERRNOFARES'); ?></p></td></tr>
				<?php
			}
		}elseif (is_array($ord) && empty($ordersrooms[0]['idtar'])) {
			//order is a quick reservation from administrator
			$proceedtars = true;
			$rooms = array();
			$tars = array();
			$arrpeople = array();
			foreach($ordersrooms as $kor => $or) {
				$num = $kor + 1;
				$rooms[$num] = $or;
				$arrpeople[$num]['adults'] = $or['adults'];
				$arrpeople[$num]['children'] = $or['children'];
				$q="SELECT * FROM `#__vikbooking_dispcost` WHERE `days`='".$ord[0]['days']."' AND `idroom`='".$or['idroom']."' ORDER BY `#__vikbooking_dispcost`.`cost` ASC;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($dbo->getNumRows() > 0) {
					$tar = $dbo->loadAssocList();
					$tar = vikbooking::applySeasonsRoom($tar, $ord[0]['checkin'], $ord[0]['checkout']);
					//different usage
					if ($or['fromadult'] <= $or['adults'] && $or['toadult'] >= $or['adults']) {
						$diffusageprice = vikbooking::loadAdultsDiff($or['idroom'], $or['adults']);
						//Occupancy Override
						$occ_ovr = vikbooking::occupancyOverrideExists($tar, $or['adults']);
						$diffusageprice = $occ_ovr !== false ? $occ_ovr : $diffusageprice;
						//
						if (is_array($diffusageprice)) {
							//set a charge or discount to the price(s) for the different usage of the room
							foreach($tar as $kpr => $vpr) {
								$tar[$kpr]['diffusage'] = $or['adults'];
								if ($diffusageprice['chdisc'] == 1) {
									//charge
									if ($diffusageprice['valpcent'] == 1) {
										//fixed value
										$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
										$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $tar[$kpr]['days'] : $diffusageprice['value'];
										$tar[$kpr]['diffusagecost'] = "+".$aduseval;
										$tar[$kpr]['cost'] = $vpr['cost'] + $aduseval;
									}else {
										//percentage value
										$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
										$aduseval = $diffusageprice['pernight'] == 1 ? round(($vpr['cost'] * $diffusageprice['value'] / 100) * $tar[$kpr]['days'] + $vpr['cost'], 2) : round(($vpr['cost'] * (100 + $diffusageprice['value']) / 100), 2);
										$tar[$kpr]['diffusagecost'] = "+".$diffusageprice['value']."%";
										$tar[$kpr]['cost'] = $aduseval;
									}
								}else {
									//discount
									if ($diffusageprice['valpcent'] == 1) {
										//fixed value
										$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
										$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $tar[$kpr]['days'] : $diffusageprice['value'];
										$tar[$kpr]['diffusagecost'] = "-".$aduseval;
										$tar[$kpr]['cost'] = $vpr['cost'] - $aduseval;
									}else {
										//percentage value
										$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
										$aduseval = $diffusageprice['pernight'] == 1 ? round($vpr['cost'] - ((($vpr['cost'] / $tar[$kpr]['days']) * $diffusageprice['value'] / 100) * $tar[$kpr]['days']), 2) : round(($vpr['cost'] * (100 - $diffusageprice['value']) / 100), 2);
										$tar[$kpr]['diffusagecost'] = "-".$diffusageprice['value']."%";
										$tar[$kpr]['cost'] = $aduseval;
									}
								}
							}
						}
					}
					//
					$tars[$num] = $tar;
				}else {
					$proceedtars = false;
					break;
				}
			}
			if ($proceedtars) {
				?>
				<input type="hidden" name="areprices" value="quick"/>
				<?php
				foreach($ordersrooms as $kor => $or) {
					$num = $kor + 1;
					?>
					<tr><td colspan="2"><h4 class="vbheadfourblue"><?php echo $or['name']; ?>, <?php echo JText::_('VBMAILADULTS').': '.$or['adults']; ?><?php echo ($or['children'] > 0 ? " - ".JText::_('VBMAILCHILDREN').': '.$or['children'] : ""); ?></h4></td></tr>
					<tr><td colspan="2"><span class="vbo-ebusy-lbl"><?php echo JText::_('VBPEDITBUSYSEVEN'); ?></span></td></tr>
					<tr><td colspan="2"><table class="vbo-ebusy-tars-table">
					<?php
					foreach($tars[$num] as $k=>$t) {
					?>
					<tr><td><label for="pid<?php echo $num.$t['idprice']; ?>"><?php echo vikbooking::getPriceName($t['idprice']).(strlen($t['attrdata']) ? "<br/>".vikbooking::getPriceAttr($t['idprice']).": ".$t['attrdata'] : ""); ?></label></td><td><?php echo $currencysymb." ".vikbooking::numberFormat(vikbooking::sayCostPlusIva($t['cost'], $t['idprice'])); ?></td><td><input type="radio" name="priceid<?php echo $num; ?>" id="pid<?php echo $num.$t['idprice']; ?>" value="<?php echo $t['idprice']; ?>"/></td></tr>
					<?php
					}
					?>
					<tr><td><label for="cust_cost<?php echo $num; ?>" class="vbo-custrate-lbl-add hasTooltip" title="<?php echo JText::_('VBOROOMCUSTRATETAXHELP'); ?>"><?php echo JText::_('VBOROOMCUSTRATEPLANADD'); ?></label></td><td><?php echo $currencysymb; ?> <input type="text" name="cust_cost<?php echo $num; ?>" id="cust_cost<?php echo $num; ?>" value="" placeholder="<?php echo vikbooking::numberFormat((!empty($ord[0]['idorderota']) && !empty($ord[0]['total']) ? $ord[0]['total'] : 0)); ?>" size="4" onblur="if(this.value.length) {document.getElementById('priceid<?php echo $num; ?>').checked = true; document.getElementById('tax<?php echo $num; ?>').style.display = 'block';}" /><div id="tax<?php echo $num; ?>" style="display: none;"><?php echo (!empty($wiva) ? str_replace('%s', $num, $wiva) : ''); ?></div></td><td><input type="radio" name="priceid<?php echo $num; ?>" id="priceid<?php echo $num; ?>" value="" onclick="document.getElementById('tax<?php echo $num; ?>').style.display = 'block';" /></td></tr>
					</table></td></tr>
					<?php
					if(!empty($or['idopt'])) {
						$optionals=vikbooking::getRoomOptionals($or['idopt']);
						if (is_array($optionals)) {
							list($optionals, $ageintervals) = vikbooking::loadOptionAgeIntervals($optionals);
							if (is_array($ageintervals)) {
								if (is_array($optionals)) {
									$ageintervals = array(0 => $ageintervals);
									$optionals = array_merge($ageintervals, $optionals);
								}else {
									$optionals = array(0 => $ageintervals);
								}
							}
							?>
							<tr><td colspan="2"><span class="vbo-ebusy-lbl"><?php echo JText::_('VBPEDITBUSYEIGHT'); ?></span></td></tr>
							<tr><td colspan="2"><table class="vbo-ebusy-opt-table">
							<?php
							foreach($optionals as $k=>$o) {
								if (!empty($o['ageintervals'])) {
									if($or['children'] > 0) {
										$optagecosts = vikbooking::getOptionIntervalsCosts($o['ageintervals']);
										$optagenames = vikbooking::getOptionIntervalsAges($o['ageintervals']);
										$chageselect = '<select name="optid'.$num.$o['id'].'[]">'."\n".'<option value="">  </option>'."\n";
										$intervals = explode(';;', $o['ageintervals']);
										foreach($intervals as $kintv => $intv) {
											if (empty($intv)) continue;
											$intvparts = explode('_', $intv);
											$intvparts[2] = intval($o['perday']) == 1 ? ($intvparts[2] * $ord[0]['days']) : $intvparts[2];
											$pricestr = floatval($intvparts[2]) >= 0 ? '+ '.vikbooking::numberFormat(vikbooking::sayOptionalsPlusIva($intvparts[2], $o['idiva'])) : '- '.vikbooking::numberFormat($intvparts[2]);
											$chageselect .= '<option value="'.($kintv + 1).'">'.$intvparts[0].' - '.$intvparts[1].' ('.$pricestr.' '.$currencysymb.')'.'</option>'."\n";
										}
										$chageselect .= '</select>'."\n";
										for($ch = 1; $ch <= $or['children']; $ch++) {
											?>
											<tr><td><?php echo JText::_('VBMAILCHILD').' #'.$ch; ?></td><td colspan="2"><?php echo $chageselect; ?></td></tr>
											<?php
										}
									}
								}else {
									?>
									<tr>
										<td><?php echo $o['name']; ?></td>
										<td align="center"><?php echo (intval($o['hmany'])==1 ? "<input type=\"number\" name=\"optid".$num.$o['id']."\" value=\"\" min=\"0\" size=\"4\" style=\"width: 50px !important;\"/>" : "<input type=\"checkbox\" name=\"optid".$num.$o['id']."\" value=\"1\" />"); ?></td>
										<td><?php echo (!empty($o['img']) ? "<img src=\"".JURI::root()."components/com_vikbooking/resources/uploads/".$o['img']."\" class=\"maxfifty\"/>" : ""); ?></td>
									</tr>
									<?php
								}
							}
							?>
							</table></td></tr>
							<?php
						}
					}
					//Amount paid available also for the incomplete bookings
					?>
					<tr><td>&nbsp;</td></tr>
					<tr><td colspan="2"><table class="vbo-ebusy-tars-table">
						<tr><td><strong><?php echo JText::_('VBPEDITBUSYTOTPAID'); ?></strong></td><td><?php echo $currencysymb; ?> <input type="text" name="totpaid" value="<?php echo $ord[0]['totpaid']; ?>" size="6"/></td></tr>
					</table></td></tr>
					<?php
				}
			}else {
				?>
				<tr><td colspan="2"><p class="err"><?php echo JText::_('VBPEDITBUSYERRNOFARES'); ?></p></td></tr>
				<?php
			}
			//
		}
		?>
		</table>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="idorder" value="<?php echo $ord[0]['id']; ?>">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		<?php
		$pvcm = JRequest::getInt('vcm', '', 'request');
		echo $pvcm == 1 ? '<input type="hidden" name="vcm" value="1">' : '';
		echo $pgoto == 'overview' ? '<input type="hidden" name="goto" value="overview">' : '';
		?>
		</form>
		<script type="text/javascript">
		document.getElementById('checkindate').value='<?php echo $rit; ?>';
		document.getElementById('checkoutdate').value='<?php echo $con; ?>';
		if(jQuery.isFunction(jQuery.fn.tooltip)) {
			jQuery(".hasTooltip").tooltip();
		}
		</script>
		<?php
	}
	
	public static function pNewCoupon ($wselrooms, $option) {
		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.calendar');
		$currencysymb=vikbooking::getCurrencySymb(true);
		$df=vikbooking::getDateFormat(true);
		?>
		<script type="text/javascript">
		function setVehiclesList() {
			if(document.adminForm.allvehicles.checked == true) {
				document.getElementById('vbvlist').style.display='none';
			}else {
				document.getElementById('vbvlist').style.display='block';
			}
			return true;
		}
		</script>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
		<table class="adminform">
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCOUPONONE'); ?>:</b> </td><td><input type="text" name="code" value="" size="30"/></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCOUPONTWO'); ?>:</b> </td><td><select name="type"><option value="1"><?php echo JText::_('VBCOUPONTYPEPERMANENT'); ?></option><option value="2"><?php echo JText::_('VBCOUPONTYPEGIFT'); ?></option></select></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCOUPONTHREE'); ?>:</b> </td><td><select name="percentot"><option value="1">%</option><option value="2"><?php echo $currencysymb; ?></option></select></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCOUPONFOUR'); ?>:</b> </td><td><input type="text" name="value" value="" size="4"/></td></tr>
		<tr><td width="200" valign="top">&bull; <b><?php echo JText::_('VBNEWCOUPONFIVE'); ?>:</b> </td><td><input type="checkbox" name="allvehicles" value="1" checked="checked" onclick="javascript: setVehiclesList();"/> <?php echo JText::_('VBNEWCOUPONEIGHT'); ?><span id="vbvlist" style="display: none;"><br/><?php echo $wselrooms; ?></span></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCOUPONSIX'); ?>:</b> <?php echo JHTML::tooltip(JText::_('VBNEWCOUPONNINE'), JText::_('VBNEWCOUPONSIX'), 'tooltip.png', ''); ?></td><td><?php echo JHTML::_('calendar', '', 'from', 'from', $df, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?> - <?php echo JHTML::_('calendar', '', 'to', 'to', $df, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCOUPONSEVEN'); ?>:</b> </td><td><input type="text" name="mintotord" value="0.00" size="4"/></td></tr>
		</table>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<?php
	}
	
	public static function pEditCoupon ($coupon, $wselrooms, $option) {
		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.calendar');
		$currencysymb=vikbooking::getCurrencySymb(true);
		$df=vikbooking::getDateFormat(true);
		$fromdate = "";
		$todate = "";
		if(strlen($coupon['datevalid']) > 0) {
			$dateparts = explode("-", $coupon['datevalid']);
			if ($df=="%d/%m/%Y") {
				$udf='d/m/Y';
			}elseif ($df=="%m/%d/%Y") {
				$udf='m/d/Y';
			}else {
				$udf='Y/m/d';
			}
			$fromdate = date($udf, $dateparts[0]);
			$todate = date($udf, $dateparts[1]);
		}
		?>
		<script type="text/javascript">
		function setVehiclesList() {
			if(document.adminForm.allvehicles.checked == true) {
				document.getElementById('vbvlist').style.display='none';
			}else {
				document.getElementById('vbvlist').style.display='block';
			}
			return true;
		}
		</script>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
		<table class="adminform">
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCOUPONONE'); ?>:</b> </td><td><input type="text" name="code" value="<?php echo $coupon['code']; ?>" size="30"/></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCOUPONTWO'); ?>:</b> </td><td><select name="type"><option value="1"<?php echo ($coupon['type'] == 1 ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBCOUPONTYPEPERMANENT'); ?></option><option value="2"<?php echo ($coupon['type'] == 2 ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBCOUPONTYPEGIFT'); ?></option></select></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCOUPONTHREE'); ?>:</b> </td><td><select name="percentot"><option value="1"<?php echo ($coupon['percentot'] == 1 ? " selected=\"selected\"" : ""); ?>>%</option><option value="2"<?php echo ($coupon['percentot'] == 2 ? " selected=\"selected\"" : ""); ?>><?php echo $currencysymb; ?></option></select></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCOUPONFOUR'); ?>:</b> </td><td><input type="text" name="value" value="<?php echo $coupon['value']; ?>" size="4"/></td></tr>
		<tr><td width="200" valign="top">&bull; <b><?php echo JText::_('VBNEWCOUPONFIVE'); ?>:</b> </td><td><input type="checkbox" name="allvehicles" value="1"<?php echo ($coupon['allvehicles'] == 1 ? " checked=\"checked\"" : ""); ?> onclick="javascript: setVehiclesList();"/> <?php echo JText::_('VBNEWCOUPONEIGHT'); ?><span id="vbvlist" style="display: <?php echo ($coupon['allvehicles'] == 1 ? "none" : "block"); ?>;"><br/><?php echo $wselrooms; ?></span></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCOUPONSIX'); ?>:</b> <?php echo JHTML::tooltip(JText::_('VBNEWCOUPONNINE'), JText::_('VBNEWCOUPONSIX'), 'tooltip.png', ''); ?></td><td><?php echo JHTML::_('calendar', '', 'from', 'from', $df, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?> - <?php echo JHTML::_('calendar', '', 'to', 'to', $df, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCOUPONSEVEN'); ?>:</b> </td><td><input type="text" name="mintotord" value="<?php echo $coupon['mintotord']; ?>" size="4"/></td></tr>
		</table>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		<input type="hidden" name="where" value="<?php echo $coupon['id']; ?>">
		</form>
		<?php
		if(strlen($fromdate) > 0 && strlen($todate) > 0) {
		?>
		<script type="text/javascript">
		document.getElementById('from').value='<?php echo $fromdate; ?>';
		document.getElementById('to').value='<?php echo $todate; ?>';
		</script>
		<?php
		}
		?>
		<?php
	}
	
	public static function pEditCustomf ($field, $option) {
		$choose="";
		if($field['type']=="select") {
			$x=explode(";;__;;", $field['choose']);
			if(@count($x) > 0) {
				foreach($x as $y) {
					if(!empty($y)) {
						$choose.='<input type="text" name="choose[]" value="'.$y.'" size="40"/><br/>'."\n";
					}
				}
			}
		}
		?>
		<script type="text/javascript">
		function setCustomfChoose (val) {
			if(val == "text") {
				document.getElementById('customfchoose').style.display = 'none';
				document.getElementById('vbnominative').style.display = '';
				document.getElementById('vbphone').style.display = '';
			}
			if(val == "textarea") {
				document.getElementById('customfchoose').style.display = 'none';
				document.getElementById('vbnominative').style.display = 'none';
				document.getElementById('vbphone').style.display = 'none';
			}
			if(val == "checkbox") {
				document.getElementById('customfchoose').style.display = 'none';
				document.getElementById('vbnominative').style.display = 'none';
				document.getElementById('vbphone').style.display = 'none';
			}
			if(val == "date") {
				document.getElementById('customfchoose').style.display = 'none';
				document.getElementById('vbnominative').style.display = 'none';
				document.getElementById('vbphone').style.display = 'none';
			}
			if(val == "select") {
				document.getElementById('customfchoose').style.display = 'block';
				document.getElementById('vbnominative').style.display = 'none';
				document.getElementById('vbphone').style.display = 'none';
			}
			if(val == "country") {
				document.getElementById('customfchoose').style.display = 'none';
				document.getElementById('vbnominative').style.display = 'none';
				document.getElementById('vbphone').style.display = 'none';
			}
			if(val == "separator") {
				document.getElementById('customfchoose').style.display = 'none';
				document.getElementById('vbnominative').style.display = 'none';
				document.getElementById('vbphone').style.display = 'none';
			}
			return true;
		}
		function addElement() {
			var ni = document.getElementById('customfchooseadd');
			var numi = document.getElementById('theValue');
			var num = (document.getElementById('theValue').value -1)+ 2;
			numi.value = num;
			var newdiv = document.createElement('div');
			var divIdName = 'my'+num+'Div';
			newdiv.setAttribute('id',divIdName);
			newdiv.innerHTML = '<input type=\'text\' name=\'choose[]\' value=\'\' size=\'40\'/><br/>';
			ni.appendChild(newdiv);
		}
		</script>
		<input type="hidden" value="0" id="theValue" />
		
		<form name="adminForm" id="adminForm" action="index.php" method="post">
		<table class="adminform">
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCUSTOMFONE'); ?>:</b> </td><td><input type="text" name="name" value="<?php echo $field['name']; ?>" size="40"/></td></tr>
		<tr><td width="200" valign="top">&bull; <b><?php echo JText::_('VBNEWCUSTOMFTWO'); ?>:</b> </td><td valign="top">
		<select id="stype" name="type" onchange="setCustomfChoose(this.value);"><option value="text"<?php echo ($field['type']=="text" ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBNEWCUSTOMFTHREE'); ?></option><option value="textarea"<?php echo ($field['type']=="textarea" ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBNEWCUSTOMFTEN'); ?></option><option value="select"<?php echo ($field['type']=="select" ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBNEWCUSTOMFFOUR'); ?></option><option value="checkbox"<?php echo ($field['type']=="checkbox" ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBNEWCUSTOMFFIVE'); ?></option><option value="date"<?php echo ($field['type']=="date" ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBNEWCUSTOMFDATETYPE'); ?></option><option value="country"<?php echo ($field['type']=="country" ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBNEWCUSTOMFCOUNTRY'); ?></option><option value="separator"<?php echo ($field['type']=="separator" ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBNEWCUSTOMFSEPARATOR'); ?></option></select>
		<div id="customfchoose" style="display: <?php echo ($field['type']=="select" ? "block" : "none"); ?>;">
			<?php
			if($field['type']!="select") {
			?>
			<br/><input type="text" name="choose[]" value="" size="40"/>
			<?php
			}else {
				echo '<br/>'.$choose;
			}
			?>
			<div id="customfchooseadd" style="display: block;"></div>
			<span><b><a href="javascript: void(0);" onclick="javascript: addElement();"><?php echo JText::_('VBNEWCUSTOMFNINE'); ?></a></b></span>
		</div>
		</td></tr>
		<tr><td width="200" style="background: none repeat scroll 0 0 #e9e9e9;">&nbsp; <b><?php echo JText::_('VBNEWCUSTOMFSIX'); ?>:</b> </td><td>&nbsp;<input type="checkbox" name="required" value="1"<?php echo (intval($field['required']) == 1 ? " checked=\"checked\"" : ""); ?>/></td></tr>
		<tr><td width="200" style="background: none repeat scroll 0 0 #e9e9e9; border-top: 1px solid #ddd;">&nbsp; <b><?php echo JText::_('VBNEWCUSTOMFSEVEN'); ?>:</b> </td><td>&nbsp;<input type="checkbox" name="isemail" value="1"<?php echo (intval($field['isemail']) == 1 ? " checked=\"checked\"" : ""); ?>/></td></tr>
		<tr id="vbnominative"<?php echo ($field['type']!="text" ? " style=\"display: none;\"" : ""); ?>><td width="200" style="background: none repeat scroll 0 0 #e9e9e9; border-top: 1px solid #ddd;">&nbsp; <b><?php echo JText::_('VBISNOMINATIVE'); ?>:</b> </td><td>&nbsp;<input type="checkbox" name="isnominative" value="1"<?php echo (intval($field['isnominative']) == 1 ? " checked=\"checked\"" : ""); ?>/></td></tr>
		<tr id="vbphone"<?php echo ($field['type']!="text" ? " style=\"display: none;\"" : ""); ?>><td width="200" style="background: none repeat scroll 0 0 #e9e9e9; border-top: 1px solid #ddd;">&nbsp; <b><?php echo JText::_('VBISPHONENUMBER'); ?>:</b> </td><td>&nbsp;<input type="checkbox" name="isphone" value="1"<?php echo (intval($field['isphone']) == 1 ? " checked=\"checked\"" : ""); ?>/></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCUSTOMFEIGHT'); ?>:</b> </td><td><input type="text" name="poplink" value="<?php echo $field['poplink']; ?>" size="40"/> <small>Ex. <i>index.php?option=com_content&view=article&id=#JoomlaArticleID#&tmpl=component</i></small></td></tr>
		</table>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		<input type="hidden" name="where" value="<?php echo $field['id']; ?>">
		</form>
		<?php
	}
	
	public static function pNewCustomf ($option) {
		?>
		<script type="text/javascript">
		function setCustomfChoose (val) {
			if(val == "text") {
				document.getElementById('customfchoose').style.display = 'none';
				document.getElementById('vbnominative').style.display = '';
				document.getElementById('vbphone').style.display = '';
			}
			if(val == "textarea") {
				document.getElementById('customfchoose').style.display = 'none';
				document.getElementById('vbnominative').style.display = 'none';
				document.getElementById('vbphone').style.display = 'none';
			}
			if(val == "checkbox") {
				document.getElementById('customfchoose').style.display = 'none';
				document.getElementById('vbnominative').style.display = 'none';
				document.getElementById('vbphone').style.display = 'none';
			}
			if(val == "date") {
				document.getElementById('customfchoose').style.display = 'none';
				document.getElementById('vbnominative').style.display = 'none';
				document.getElementById('vbphone').style.display = 'none';
			}
			if(val == "select") {
				document.getElementById('customfchoose').style.display = 'block';
				document.getElementById('vbnominative').style.display = 'none';
				document.getElementById('vbphone').style.display = 'none';
			}
			if(val == "country") {
				document.getElementById('customfchoose').style.display = 'none';
				document.getElementById('vbnominative').style.display = 'none';
				document.getElementById('vbphone').style.display = 'none';
			}
			if(val == "separator") {
				document.getElementById('customfchoose').style.display = 'none';
				document.getElementById('vbnominative').style.display = 'none';
				document.getElementById('vbphone').style.display = 'none';
			}
			return true;
		}
		function addElement() {
			var ni = document.getElementById('customfchooseadd');
			var numi = document.getElementById('theValue');
			var num = (document.getElementById('theValue').value -1)+ 2;
			numi.value = num;
			var newdiv = document.createElement('div');
			var divIdName = 'my'+num+'Div';
			newdiv.setAttribute('id',divIdName);
			newdiv.innerHTML = '<input type=\'text\' name=\'choose[]\' value=\'\' size=\'40\'/><br/>';
			ni.appendChild(newdiv);
		}
		</script>
		<input type="hidden" value="0" id="theValue" />
		
		<form name="adminForm" id="adminForm" action="index.php" method="post">
		<table class="adminform">
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCUSTOMFONE'); ?>:</b> </td><td><input type="text" name="name" value="" size="40"/></td></tr>
		<tr><td width="200" valign="top">&bull; <b><?php echo JText::_('VBNEWCUSTOMFTWO'); ?>:</b> </td><td valign="top">
		<select id="stype" name="type" onchange="setCustomfChoose(this.value);"><option value="text"><?php echo JText::_('VBNEWCUSTOMFTHREE'); ?></option><option value="textarea"><?php echo JText::_('VBNEWCUSTOMFTEN'); ?></option><option value="select"><?php echo JText::_('VBNEWCUSTOMFFOUR'); ?></option><option value="checkbox"><?php echo JText::_('VBNEWCUSTOMFFIVE'); ?></option><option value="date"><?php echo JText::_('VBNEWCUSTOMFDATETYPE'); ?></option><option value="country"><?php echo JText::_('VBNEWCUSTOMFCOUNTRY'); ?></option><option value="separator"><?php echo JText::_('VBNEWCUSTOMFSEPARATOR'); ?></option></select>
		<div id="customfchoose" style="display: none;"><br/><input type="text" name="choose[]" value="" size="40"/>
			<div id="customfchooseadd" style="display: block;"></div>
			<span><b><a href="javascript: void(0);" onclick="javascript: addElement();"><?php echo JText::_('VBNEWCUSTOMFNINE'); ?></a></b></span>
		</div>
		</td></tr>
		<tr><td width="200" style="background: none repeat scroll 0 0 #e9e9e9;">&nbsp; <b><?php echo JText::_('VBNEWCUSTOMFSIX'); ?>:</b> </td><td>&nbsp;<input type="checkbox" name="required" value="1"/></td></tr>
		<tr><td width="200" style="background: none repeat scroll 0 0 #e9e9e9; border-top: 1px solid #ddd;">&nbsp; <b><?php echo JText::_('VBNEWCUSTOMFSEVEN'); ?>:</b> </td><td>&nbsp;<input type="checkbox" name="isemail" value="1"/></td></tr>
		<tr id="vbnominative"><td width="200" style="background: none repeat scroll 0 0 #e9e9e9; border-top: 1px solid #ddd;">&nbsp; <b><?php echo JText::_('VBISNOMINATIVE'); ?>:</b> </td><td>&nbsp;<input type="checkbox" name="isnominative" value="1"/></td></tr>
		<tr id="vbphone"><td width="200" style="background: none repeat scroll 0 0 #e9e9e9; border-top: 1px solid #ddd;">&nbsp; <b><?php echo JText::_('VBISPHONENUMBER'); ?>:</b> </td><td>&nbsp;<input type="checkbox" name="isphone" value="1"/></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCUSTOMFEIGHT'); ?>:</b> </td><td><input type="text" name="poplink" value="" size="40"/> <small>Ex. <i>index.php?option=com_content&view=article&id=#JoomlaArticleID#&tmpl=component</i></small></td></tr>
		</table>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<?php
	}
	
	public static function pEditOrder ($row, $rooms, $busy, $payments, $option) {
		JHTML::_('behavior.modal');
		$currencyname=vikbooking::getCurrencyName();
		$dbo = JFactory::getDBO();
		$vbo_app = new VikApplication();
		$nowdf = vikbooking::getDateFormat(true);
		if ($nowdf=="%d/%m/%Y") {
			$df='d/m/Y';
		}elseif ($nowdf=="%m/%d/%Y") {
			$df='m/d/Y';
		}else {
			$df='Y/m/d';
		}
		$payment=vikbooking::getPayment($row['idpayment']);
		if(is_array($rooms)) {
			$tars = array();
			$arrpeople = array();
			$is_package = !empty($row['pkg']) ? true : false;
			$is_cust_cost = false;
			foreach($rooms as $ind => $or) {
				$num = $ind + 1;
				$arrpeople[$num]['adults'] = $or['adults'];
				$arrpeople[$num]['children'] = $or['children'];
				$arrpeople[$num]['children_age'] = $or['childrenage'];
				$arrpeople[$num]['t_first_name'] = $or['t_first_name'];
				$arrpeople[$num]['t_last_name'] = $or['t_last_name'];
				if($is_package === true || (!empty($or['cust_cost']) && $or['cust_cost'] > 0.00)) {
					//package or custom cost set from the back-end
					$is_cust_cost = true;
					continue;
				}
				$q="SELECT * FROM `#__vikbooking_dispcost` WHERE `id`='".$or['idtar']."';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$tar=$dbo->loadAssocList();
				$tar = vikbooking::applySeasonsRoom($tar, $row['checkin'], $row['checkout']);
				//different usage
				if ($or['fromadult'] <= $or['adults'] && $or['toadult'] >= $or['adults']) {
					$diffusageprice = vikbooking::loadAdultsDiff($or['idroom'], $or['adults']);
					//Occupancy Override
					$occ_ovr = vikbooking::occupancyOverrideExists($tar, $or['adults']);
					$diffusageprice = $occ_ovr !== false ? $occ_ovr : $diffusageprice;
					//
					if (is_array($diffusageprice)) {
						//set a charge or discount to the price(s) for the different usage of the room
						foreach($tar as $kpr => $vpr) {
							$tar[$kpr]['diffusage'] = $or['adults'];
							if ($diffusageprice['chdisc'] == 1) {
								//charge
								if ($diffusageprice['valpcent'] == 1) {
									//fixed value
									$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
									$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $tar[$kpr]['days'] : $diffusageprice['value'];
									$tar[$kpr]['diffusagecost'] = "+".$aduseval;
									$tar[$kpr]['cost'] = $vpr['cost'] + $aduseval;
								}else {
									//percentage value
									$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
									$aduseval = $diffusageprice['pernight'] == 1 ? round(($vpr['cost'] * $diffusageprice['value'] / 100) * $tar[$kpr]['days'] + $vpr['cost'], 2) : round(($vpr['cost'] * (100 + $diffusageprice['value']) / 100), 2);
									$tar[$kpr]['diffusagecost'] = "+".$diffusageprice['value']."%";
									$tar[$kpr]['cost'] = $aduseval;
								}
							}else {
								//discount
								if ($diffusageprice['valpcent'] == 1) {
									//fixed value
									$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
									$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $tar[$kpr]['days'] : $diffusageprice['value'];
									$tar[$kpr]['diffusagecost'] = "-".$aduseval;
									$tar[$kpr]['cost'] = $vpr['cost'] - $aduseval;
								}else {
									//percentage value
									$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
									$aduseval = $diffusageprice['pernight'] == 1 ? round($vpr['cost'] - ((($vpr['cost'] / $tar[$kpr]['days']) * $diffusageprice['value'] / 100) * $tar[$kpr]['days']), 2) : round(($vpr['cost'] * (100 - $diffusageprice['value']) / 100), 2);
									$tar[$kpr]['diffusagecost'] = "-".$diffusageprice['value']."%";
									$tar[$kpr]['cost'] = $aduseval;
								}
							}
						}
					}
				}
				//
				$tars[$num] = $tar;
			}
			$pcheckin = $row['checkin'];
			$pcheckout = $row['checkout'];
			$secdiff = $pcheckout - $pcheckin;
			$daysdiff = $secdiff / 86400;
			if (is_int($daysdiff)) {
				if ($daysdiff < 1) {
					$daysdiff = 1;
				}
			}else {
				if ($daysdiff < 1) {
					$daysdiff = 1;
				}else {
					$sum = floor($daysdiff) * 86400;
					$newdiff = $secdiff - $sum;
					$maxhmore = vikbooking::getHoursMoreRb() * 3600;
					if ($maxhmore >= $newdiff) {
						$daysdiff = floor($daysdiff);
					} else {
						$daysdiff = ceil($daysdiff);
					}
				}
			}
		}
		$otachannel = '';
		$otachannel_name = '';
		$otachannel_bid = '';
		$otacurrency = '';
		if (!empty($row['channel'])) {
			$channelparts = explode('_', $row['channel']);
			$otachannel = array_key_exists(1, $channelparts) && strlen($channelparts[1]) > 0 ? $channelparts[1] : ucwords($channelparts[0]);
			$otachannel_name = $otachannel;
			$otachannel_bid = $otachannel.(!empty($row['idorderota']) ? ' - Booking ID: '.$row['idorderota'] : '');
			if (strstr($otachannel, '.') !== false) {
				$otaccparts = explode('.', $otachannel);
				$otachannel = $otaccparts[0];
			}
			$otacurrency = strlen($row['chcurrency']) > 0 ? $row['chcurrency'] : '';
		}
		if ($row['status']=="confirmed") {
			$saystaus = "<p class=\"successmade\">".JText::_('VBCONFIRMED')."</p>";
		}elseif ($row['status']=="standby") {
			$saystaus = "<p class=\"warn\">".JText::_('VBSTANDBY')."</p>";
		}else {
			$saystaus = "<p class=\"err\">".JText::_('VBCANCELLED')."</p>";
		}
		?>
		<script type="text/javascript">
		function vbToggleLog() {
			var logdiv = document.getElementById('vbpaymentlogdiv').style.display;
			if(logdiv == 'block') {
				document.getElementById('vbpaymentlogdiv').style.display = 'none';
			}else {
				document.getElementById('vbadminnotesdiv').style.display = 'none';
				document.getElementById('vbpaymentlogdiv').style.display = 'block';
			}
		}
		function changePayment() {
			var newpayment = document.getElementById('newpayment').value;
			if(newpayment != '') {
				var paymentname = document.getElementById('newpayment').options[document.getElementById('newpayment').selectedIndex].text;
				if(confirm('<?php echo addslashes(JText::_('VBCHANGEPAYCONFIRM')); ?>' + paymentname + '?')) {
					document.adminForm.submit();
				}else {
					document.getElementById('newpayment').selectedIndex = 0;
				}
			}
		}
		function vbToggleNotes() {
			var notesdiv = document.getElementById('vbadminnotesdiv').style.display;
			if(notesdiv == 'block') {
				document.getElementById('vbadminnotesdiv').style.display = 'none';
			}else {
				if(document.getElementById('vbpaymentlogdiv')) {
					document.getElementById('vbpaymentlogdiv').style.display = 'none';
				}
				document.getElementById('vbadminnotesdiv').style.display = 'block';
			}
		}
		function toggleDiscount() {
			var discsp = document.getElementById('vbdiscenter').style.display;
			if(discsp == 'block') {
				document.getElementById('vbdiscenter').style.display = 'none';
			}else {
				document.getElementById('vbdiscenter').style.display = 'block';
			}
		}
		</script>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
		<table class="adminform" style="min-width: 70%;">
		<tr><td width="100%">
		<?php echo (!empty($row['channel']) ? "<span class=\"vbotaspblock ".strtolower($otachannel)."\">".$otachannel_bid."</span>" : ""); ?>
		<p class="vborderof">
			<span class="label label-info"><?php echo JText::_('VBEDITORDERONE'); ?>: <span class="badge"><?php echo date($df.' H:i', $row['ts']); ?></span></span>
			<?php echo (is_array($busy) || $row['status']=="standby" ? " &nbsp; <button onclick=\"document.location.href='index.php?option=com_vikbooking&task=editbusy&cid[]=".$row['id']."';\" class=\"btn\" type=\"button\"><i class=\"icon-pencil\"></i> ".JText::_('VBMODRES')."</button>" : "").((array_key_exists(1, $tars) && count($tars[1]) > 0) || ($is_package || $is_cust_cost) ? ' &nbsp; <button onclick="window.open(\''.JURI::root().'index.php?option=com_vikbooking&task=vieworder&sid='.$row['sid'].'&ts='.$row['ts'].'\', \'_blank\');" type="button" class="btn"><i class="icon-eye"></i> '.JText::_('VBVIEWORDFRONT').'</button>' : ''); ?>
			<?php echo $row['status']=="standby" ? " &nbsp;&nbsp; <button class=\"btn btn-success\" type=\"button\" onclick=\"if(confirm('".addslashes(JText::_('VBSETORDCONFIRMED'))." ?')) {document.location.href='index.php?option=com_vikbooking&task=setordconfirmed&cid[]=".$row['id']."';}\"><i class=\"icon-apply\"></i> ".JText::_('VBSETORDCONFIRMED')."</button>" : ""; ?>
			<?php echo $row['status']=="confirmed" && ((array_key_exists(1, $tars) && count($tars[1]) > 0) || ($is_package || $is_cust_cost)) ? " &nbsp;&nbsp; <button class=\"btn btn-success\" type=\"button\" onclick=\"document.location.href='index.php?option=com_vikbooking&task=resendordemail&cid[]=".$row['id']."';\"><i class=\"icon-mail\"></i> ".JText::_('VBRESENDORDEMAIL')."</button>" : ""; ?>
			<?php echo $row['status']=="cancelled" && !empty($row['custmail']) ? " &nbsp;&nbsp; <button class=\"btn btn-primary\" type=\"button\" onclick=\"document.location.href='index.php?option=com_vikbooking&task=sendcancordemail&cid[]=".$row['id']."';\"><i class=\"icon-mail\"></i> ".JText::_('VBSENDCANCORDEMAIL')."</button>" : ""; ?>
		</p>
		<?php echo $saystaus; ?>
		<span class="label label-info">ID: <span class="badge"><?php echo $row['id']; ?></span></span>
		<?php
		if(strlen($row['confirmnumber']) > 0) {
			?>
			<span class="label label-success"><?php echo JText::_('VBCONFIRMNUMB'); ?>: <span class="badge"><?php echo $row['confirmnumber']; ?></span></span>
			<?php
		}
		if(file_exists(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'invoices'.DS.'generated'.DS.$row['id'].'_'.$row['sid'].'.pdf')) {
			?>
			<span class="label label-success"><span class="badge"><a href="<?php echo JURI::root(); ?>components/com_vikbooking/helpers/invoices/generated/<?php echo $row['id'].'_'.$row['sid']; ?>.pdf" target="_blank"><i class="vboicn-file-text2"></i><?php echo JText::_('VBOINVDOWNLOAD'); ?></a></span></span>
			<?php
		}
		?>
		</td></tr>
		<tr><td width="100%">
		<table class="vborderdatatab"><tr><td valign="top">
		<?php
		if(!empty($row['custdata'])) {
		?>
		<p class="vborderpar"><?php echo JText::_('VBEDITORDERTWO'); ?>:</p>
		<?php
			if(!empty($row['ujid'])) {
				echo 'User ID: '.$row['ujid'].'<br/>';
			}
		?>
		<?php echo nl2br($row['custdata']); ?>
		<?php
		}
		?>
		<br/>
		<div class="vborderinfoblock">
			<span class="vbordersp"><?php echo JText::_('VBEDITORDERROOMSNUM'); ?>:</span> <?php echo $row['roomsnum']; ?><br/>
			<span class="vbordersp"><?php echo JText::_('VBEDITORDERFOUR'); ?>:</span> <?php echo $row['days']; ?><br/>
			<span class="vbordersp"><?php echo JText::_('VBEDITORDERFIVE'); ?>:</span> <?php echo date($df.' H:i', $row['checkin']); ?><br/>
			<span class="vbordersp"><?php echo JText::_('VBEDITORDERSIX'); ?>:</span> <?php echo date($df.' H:i', $row['checkout']); ?><br/>
		</div>
		<p class="vborderpar"><?php echo JText::_('VBCUSTEMAIL'); ?>:<br/><input type="text" name="custmail" value="<?php echo $row['custmail']; ?>" size="30"/></p>
		<p class="vborderpar"><?php echo JText::_('VBCUSTOMERPHONE'); ?>:<br/><input type="text" name="custphone" id="custphone" value="<?php echo $row['phone']; ?>" size="30"/><?php if(!empty($row['phone'])) : ?> <button type="button" class="btn" onclick="vboToggleSendSMS();" style="vertical-align: top;"><i class="vboicn-bubble"></i><?php echo JText::_('VBSENDSMSACTION'); ?></button><?php endif; ?></p>
		</td>
		<?php 
		$isdue = 0;
		$all_id_prices = array();
		if(is_array($rooms)) {
		?>
		<td valign="top" style="padding-left: 30px;">
		<?php
		$used_indexes_map = array();
		foreach($rooms as $ind => $or) {
			$num = $ind + 1;
			?>
			<div class="vbordroominfo">
			<span class="vbordersp"><?php echo JText::_('VBEDITORDERTHREE').' '.$num; ?>:</span> <?php echo $or['name']; ?><br/>
			<?php
			//Room Specific Unit
			if($row['status']=="confirmed" && !empty($or['params'])) {
				$room_params = json_decode($or['params'], true);
				$arr_features = array();
				$unavailable_indexes = vikbooking::getRoomUnitNumsUnavailable($row, $or['idroom']);
				if(is_array($room_params) && array_key_exists('features', $room_params) && @count($room_params['features']) > 0) {
					foreach ($room_params['features'] as $rind => $rfeatures) {
						if(in_array($rind, $unavailable_indexes) || in_array($rind, $used_indexes_map[$or['idroom']])) {
							continue;
						}
						foreach ($rfeatures as $fname => $fval) {
							if(strlen($fval)) {
								$arr_features[$rind] = '#'.$rind.' - '.JText::_($fname).': '.$fval;
								break;
							}
						}
					}
				}
				if(count($arr_features) > 0) {
					//$or['id'] equals to the ID of each matching record in _ordersrooms
					echo $vbo_app->getDropDown($arr_features, $or['roomindex'], JText::_('VBOFEATASSIGNUNITEMPTY'), JText::_('VBOFEATASSIGNUNIT'), 'roomindex['.$or['id'].']', $or['id']).'<br/>';
					if(!empty($or['idroom']) && !empty($or['roomindex'])) {
						if(!array_key_exists($or['idroom'], $used_indexes_map)) {
							$used_indexes_map[$or['idroom']] = array();
						}
						$used_indexes_map[$or['idroom']][] = $or['roomindex'];
					}
				}
			}
			//
			?>
			<span class="vbordersp"><?php echo JText::_('VBEDITORDERADULTS'); ?>:</span> <?php echo $arrpeople[$num]['adults']; ?><br/>
			<?php
			if ($arrpeople[$num]['children'] > 0) {
				$age_str = '';
				if(!empty($arrpeople[$num]['children_age'])) {
					$json_child = json_decode($arrpeople[$num]['children_age'], true);
					if(@is_array($json_child['age']) && @count($json_child['age']) > 0) {
						$age_str = ' '.JText::sprintf('VBORDERCHILDAGES', implode(', ', $json_child['age']));
					}
				}
			?>
			<span class="vbordersp"><?php echo JText::_('VBEDITORDERCHILDREN'); ?>:</span> <?php echo $arrpeople[$num]['children'].$age_str; ?><br/>
			<?php
			}
			if (!empty($arrpeople[$num]['t_first_name'])) {
			?>
			<span class="vbordersp vbordersphighlight"><?php echo $arrpeople[$num]['t_first_name'].' '.$arrpeople[$num]['t_last_name']; ?></span><br/>
			<?php
			}
			if($is_package === true || (!empty($or['cust_cost']) && $or['cust_cost'] > 0.00)) {
				//package cost or cust_cost should always be inclusive of taxes
				$isdue += $or['cust_cost'];
			}else {
				$isdue += vikbooking::sayCostPlusIva($tars[$num][0]['cost'], $tars[$num][0]['idprice']);
			}
			if($is_package === true || (!empty($or['cust_cost']) && $or['cust_cost'] > 0.00)) {
				?>
			<p class="vborderpar"><?php echo JText::_('VBEDITORDERSEVEN'); ?>:</p>
			&nbsp; <?php echo (!empty($or['pkg_name']) ? $or['pkg_name'] : JText::_('VBOROOMCUSTRATEPLAN')); ?>: <?php echo $currencyname; ?> <?php echo vikbooking::numberFormat($or['cust_cost']); ?><br/>
				<?php
			}elseif(array_key_exists($num, $tars) && !empty($tars[$num][0]['idprice'])) {
				$all_id_prices[] = $tars[$num][0]['idprice'];
				?>
			<p class="vborderpar"><?php echo JText::_('VBEDITORDERSEVEN'); ?>:</p>
			&nbsp; <?php echo vikbooking::getPriceName($tars[$num][0]['idprice']); ?>: <?php echo $currencyname; ?> <?php echo vikbooking::numberFormat(vikbooking::sayCostPlusIva($tars[$num][0]['cost'], $tars[$num][0]['idprice'])); ?><br/>
				<?php
				echo (!empty($tars[$num][0]['attrdata']) ? "&nbsp; ".vikbooking::getPriceAttr($tars[$num][0]['idprice']).": ".$tars[$num][0]['attrdata']."<br/>" : ""); 
			}
			if (!empty($or['optionals'])) {
				?>
				<p class="vborderpar"><?php echo JText::_('VBEDITORDEREIGHT'); ?>:</p>
				<?php 
				$stepo=explode(";", $or['optionals']);
				foreach($stepo as $oo){
					if (!empty($oo)) {
						$stept=explode(":", $oo);
						$q="SELECT * FROM `#__vikbooking_optionals` WHERE `id`='".$stept[0]."';";
						$dbo->setQuery($q);
						$dbo->Query($q);
						if ($dbo->getNumRows() == 1) {
							$actopt = $dbo->loadAssocList();
							$chvar = '';
							if (!empty($actopt[0]['ageintervals']) && $or['children'] > 0 && strstr($stept[1], '-') != false) {
								$optagecosts = vikbooking::getOptionIntervalsCosts($actopt[0]['ageintervals']);
								$optagenames = vikbooking::getOptionIntervalsAges($actopt[0]['ageintervals']);
								$agestept = explode('-', $stept[1]);
								$stept[1] = $agestept[0];
								$chvar = $agestept[1];
								$actopt[0]['chageintv'] = $chvar;
								$actopt[0]['name'] .= ' ('.$optagenames[($chvar - 1)].')';
								$realcost = (intval($actopt[0]['perday']) == 1 ? (floatval($optagecosts[($chvar - 1)]) * $row['days'] * $stept[1]) : (floatval($optagecosts[($chvar - 1)]) * $stept[1]));
							}else {
								$realcost = (intval($actopt[0]['perday']) == 1 ? ($actopt[0]['cost'] * $row['days'] * $stept[1]) : ($actopt[0]['cost'] * $stept[1]));
							}
							if($actopt[0]['maxprice'] > 0 && $realcost > $actopt[0]['maxprice']) {
								$realcost=$actopt[0]['maxprice'];
								if(intval($actopt[0]['hmany']) == 1 && intval($stept[1]) > 1) {
									$realcost = $actopt[0]['maxprice'] * $stept[1];
								}
							}
							$realcost = $actopt[0]['perperson'] == 1 ? ($realcost * $arrpeople[$num]['adults']) : $realcost;
							$tmpopr=vikbooking::sayOptionalsPlusIva($realcost, $actopt[0]['idiva']);
							$isdue += $tmpopr;
							echo "&nbsp; ".($stept[1] > 1 ? $stept[1]." " : "").$actopt[0]['name'].": ".$currencyname." ".vikbooking::numberFormat($tmpopr)."<br/>\n";
						}
					}
				}
			}
			?>
			</div>
			<?php
		}
		//vikbooking 1.1 coupon
		$usedcoupon = false;
		$origisdue = $isdue;
		if(strlen($row['coupon']) > 0) {
			$usedcoupon = true;
			$expcoupon = explode(";", $row['coupon']);
			$isdue = $isdue - $expcoupon[1];
			?>
			<br/><span class="vbordersp"><?php echo JText::_('VBCOUPON').' '.$expcoupon[2]; ?>:</span> - <?php echo $currencyname; ?> <?php echo vikbooking::numberFormat($expcoupon[1]); ?><br/>
			<?php
		}
		//
		//Taxes Breakdown (only if tot_taxes is greater than 0)
		$tax_breakdown = array();
		$base_aliq = 0;
		if(count($all_id_prices) > 0 && $row['tot_taxes'] > 0) {
			//only last type of price assuming that the tax breakdown is equivalent in case of different rates
			$q = "SELECT `p`.`id`,`p`.`name`,`p`.`idiva`,`t`.`aliq`,`t`.`breakdown` FROM `#__vikbooking_prices` AS `p` LEFT JOIN `#__vikbooking_iva` `t` ON `p`.`idiva`=`t`.`id` WHERE `p`.`id`=".intval(array_pop($all_id_prices))." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if($dbo->getNumRows() > 0) {
				$breakdown_info = $dbo->loadAssoc();
				if(!empty($breakdown_info['breakdown']) && !empty($breakdown_info['aliq'])) {
					$tax_breakdown = json_decode($breakdown_info['breakdown'], true);
					$tax_breakdown = is_array($tax_breakdown) && count($tax_breakdown) > 0 ? $tax_breakdown : array();
					$base_aliq = $breakdown_info['aliq'];
				}
			}
		}
		//
		?>
		<div class="vborderpartot">
		<?php echo JText::_('VBEDITORDERNINE'); ?>: <?php echo (strlen($otacurrency) > 0 ? '('.$otacurrency.') '.$currencyname : $currencyname); ?> <?php echo vikbooking::numberFormat($row['total']); ?>
		<span class="vbapplydiscsp" onclick="toggleDiscount();"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikbooking/resources/down.png" title="<?php echo JText::_('VBAPPLYDISCOUNT'); ?>"/></span>
		<div class="vbdiscenter" id="vbdiscenter" style="display: none;">
			<div class="vbdiscenter-entry">
				<span class="vbdiscenter-label"><?php echo JText::_('VBTOTALVAT'); ?>:</span><span class="vbdiscenter-value"><?php echo $currencyname; ?> <input type="text" name="tot_taxes" value="<?php echo $row['tot_taxes']; ?>" size="4" placeholder="0.00"/></span>
			</div>
		<?php
		if(count($tax_breakdown)) {
			foreach ($tax_breakdown as $tbkk => $tbkv) {
				$tax_break_cost = $row['tot_taxes'] * floatval($tbkv['aliq']) / $base_aliq;
				?>
			<div class="vbdiscenter-entry vbdiscenter-entry-breakdown">
				<span class="vbdiscenter-label"><?php echo $tbkv['name']; ?>:</span><span class="vbdiscenter-value"><?php echo $currencyname; ?> <?php echo vikbooking::numberFormat($tax_break_cost); ?></span>
			</div>
				<?php
			}
		}
		?>
			<div class="vbdiscenter-entry">
				<span class="vbdiscenter-label"><?php echo JText::_('VBTOTALCITYTAX'); ?>:</span><span class="vbdiscenter-value"><?php echo $currencyname; ?> <input type="text" name="tot_city_taxes" value="<?php echo $row['tot_city_taxes']; ?>" size="4" placeholder="0.00"/></span>
			</div>
			<div class="vbdiscenter-entry">
				<span class="vbdiscenter-label"><?php echo JText::_('VBTOTALFEES'); ?>:</span><span class="vbdiscenter-value"><?php echo $currencyname; ?> <input type="text" name="tot_fees" value="<?php echo $row['tot_fees']; ?>" size="4" placeholder="0.00"/></span>
			</div>
			<div class="vbdiscenter-entry">
				<span class="vbdiscenter-label hasTooltip"<?php echo !empty($otachannel_name) ? ' title="'.$otachannel_name.'"' : ''; ?>><?php echo JText::_('VBTOTALCOMMISSIONS'); ?>:</span><span class="vbdiscenter-value"><?php echo $currencyname; ?> <input type="text" name="cmms" value="<?php echo $row['cmms']; ?>" size="4" placeholder="0.00"/></span>
			</div>
			<div class="vbdiscenter-entry">
				<span class="vbdiscenter-label"><?php echo JText::_('VBAPPLYDISCOUNT'); ?>:</span><span class="vbdiscenter-value"><?php echo $currencyname; ?> <input type="text" name="admindisc" value="" size="4" placeholder="0.00"/></span>
			</div>
			<div class="vbdiscenter-entrycentered">
				<input type="submit" name="submdisc" value="<?php echo JText::_('VBAPPLYDISCOUNTSAVE'); ?>"/>
			</div>
		</div>
		</div>
		<?php
		if (!empty($row['totpaid']) && $row['totpaid'] > 0) {
			$diff_to_pay = $row['total'] - $row['totpaid'];
			?>
		<div class="vborderpartot">
			<?php echo JText::_('VBAMOUNTPAID'); ?>: <?php echo $currencyname; ?> <?php echo vikbooking::numberFormat($row['totpaid']); ?>
			<?php
			if($diff_to_pay > 1) {
				?>
				<br/>
				<?php echo JText::_('VBTOTALREMAINING'); ?>: <?php echo $currencyname; ?> <?php echo vikbooking::numberFormat($diff_to_pay); ?>
				<?php
				//enable second payment
				if($row['status'] == 'confirmed' && !($row['paymcount'] > 0) && vikbooking::multiplePayments() && is_array($payment) && !empty($payment['id'])) {
					?>
					<br clear="all" />
					<div style="text-align: right;">
						<a href="index.php?option=com_vikbooking&amp;task=editorder&amp;makepay=1&amp;cid[]=<?php echo $row['id']; ?>" class="vbo-makepayable-link"><i class="vboicn-credit-card"></i><?php echo JText::_('VBMAKEORDERPAYABLE'); ?></a>
					</div>
					<?php
				}
				//
			}
			?>
		</div>
			<?php
		}
		?>
		<?php
		$chpayment = '';
		if(is_array($payments)) {
			$chpayment = '<div style="display: block;"><select name="newpayment" id="newpayment" onchange="changePayment();"><option value="">'.JText::_('VBCHANGEPAYLABEL').'</option>';
			foreach($payments as $pay) {
				$chpayment .= '<option value="'.$pay['id'].'">'.(is_array($payment) && $payment['id'] == $pay['id'] ? ' ::' : '').$pay['name'].'</option>';
			}
			$chpayment .= '</select></div>';
		}
		?>
		<span class="vbordersp"><?php echo JText::_('VBPAYMENTMETHOD'); ?>:</span> 
		<?php
		if(@is_array($payment)) {
			echo $payment['name'];
		}
		echo $chpayment;
		?>
			
		</td>
		<?php
		} 
		?>
		</tr></table>
		</td></tr>
		<tr><td>
			<button type="button" class="btn btn-primary" onclick="javascript: vbToggleNotes();"><i class="icon-comment"></i> <?php echo JText::_('VBADMINNOTESTOGGLE'); ?></button>
		<?php
		if(!empty($row['paymentlog'])) {
			?>
			&nbsp;&nbsp;&nbsp;
			<a name="paymentlog" href="javascript: void(0);"></a>
			<button type="button" class="btn btn-primary" onclick="javascript: vbToggleLog();"><i class="icon-key"></i> <?php echo JText::_('VBPAYMENTLOGTOGGLE'); ?></button>
			<div id="vbpaymentlogdiv" style="display: none;">
			<?php
			//PCI Data Retrieval
			if(!empty($row['idorderota']) && !empty($row['channel'])) {
				$channel_source = $row['channel'];
				if(strpos($row['channel'], '_') !== false) {
					$channelparts = explode('_', $row['channel']);
					$channel_source = $channelparts[0];
				}
				//Maximum one hour after the checkout date and time
				if((time() + 3600) < $row['checkout']) {
					$plain_log = htmlspecialchars($row['paymentlog']);
					if(stripos($plain_log, 'card number') !== false && strpos($plain_log, '****') !== false) {
						//log contains credit card details
						?>
				<div class="vcm-notif-pcidrq-container">
					<a class="vcm-pcid-launch modal" href="index.php?option=com_vikchannelmanager&amp;task=execpcid&amp;channel_source=<?php echo $channel_source; ?>&amp;otaid=<?php echo $row['idorderota']; ?>&amp;tmpl=component"><?php echo JText::_('GETFULLCARDDETAILS'); ?></a>
				</div>
						<?php
					}
				}
			}
			//
			?>
				<pre style="min-height: 100%;"><?php echo htmlspecialchars($row['paymentlog']); ?></pre>
			</div>
			<script type="text/javascript">
			if(window.location.hash == '#paymentlog') {
				document.getElementById('vbpaymentlogdiv').style.display = 'block';
			}
			</script>
			<?php
		}
		?>
		<div id="vbadminnotesdiv" style="display: none;"><textarea name="adminnotes" class="vbadminnotestarea"><?php echo strip_tags($row['adminnotes']); ?></textarea><br clear="all"/><input type="submit" name="updadmnotes" value="<?php echo JText::_('VBADMINNOTESUPD'); ?>"/></div>
		</td></tr>
		</table>
		<input type="hidden" name="task" value="editorder">
		<input type="hidden" name="whereup" value="<?php echo $row['id']; ?>">
		<input type="hidden" name="cid[]" value="<?php echo $row['id']; ?>">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		<?php
		$tmpl = JRequest::getVar('tmpl');
		if ($tmpl == 'component') {
			echo '<input type="hidden" name="tmpl" value="component">';
		}
		?>
		</form>
		<div class="vbo-info-overlay-block">
			<div class="vbo-info-overlay-content vbo-info-overlay-content-sendsms">
				<h4><?php echo JText::_('VBSENDSMSACTION') ?>: <span id="smstophone-lbl"><?php echo $row['phone']; ?></span></h4>
				<form action="index.php?option=com_vikbooking" method="post">
					<div class="vbo-calendar-cfield-entry">
						<label for="smscont"><?php echo JText::_('VBSENDSMSCUSTCONT') ?></label>
						<span><textarea name="smscont" id="smscont" style="width: 99%; min-width: 99%;max-width: 99%; height: 35%;"></textarea></span>
					</div>
					<div class="vbo-calendar-cfields-bottom">
						<button type="submit" class="btn"><i class="vboicn-bubbles"></i><?php echo JText::_('VBSENDSMSACTION') ?></button>
					</div>
					<input type="hidden" name="phone" id="smstophone" value="<?php echo $row['phone']; ?>" />
					<input type="hidden" name="goto" value="<?php echo urlencode('index.php?option=com_vikbooking&task=editorder&cid[]='.$row['id']); ?>" />
					<input type="hidden" name="task" value="sendcustomsms" />
				</form>
			</div>
		</div>
		<script type="text/javascript">
		var vbo_overlay_on = false;
		if(jQuery.isFunction(jQuery.fn.tooltip)) {
			jQuery(".hasTooltip").tooltip();
		}
		function vboToggleSendSMS() {
			var cur_phone = jQuery("#smstophone").val();
			var phone_set = jQuery("#custphone").val();
			if(phone_set.length && phone_set != cur_phone) {
				jQuery("#smstophone").val(phone_set);
				jQuery("#smstophone-lbl").text(phone_set);
			}
			jQuery(".vbo-info-overlay-block").fadeToggle(400, function() {
				if(jQuery(".vbo-info-overlay-block").is(":visible")) {
					vbo_overlay_on = true;
				}else {
					vbo_overlay_on = false;
				}
			});
		}
		jQuery(document).ready(function(){
			jQuery(document).mouseup(function(e) {
				if(!vbo_overlay_on) {
					return false;
				}
				var vbo_overlay_cont = jQuery(".vbo-info-overlay-content");
				if(!vbo_overlay_cont.is(e.target) && vbo_overlay_cont.has(e.target).length === 0) {
					jQuery(".vbo-info-overlay-block").fadeOut();
					vbo_overlay_on = false;
				}
			});
			jQuery(document).keyup(function(e) {
				if (e.keyCode == 27 && vbo_overlay_on) {
					jQuery(".vbo-info-overlay-block").fadeOut();
					vbo_overlay_on = false;
				}
			});
		});
		</script>
		<?php
	}
	
	public static function pNewIva ($option) {
		?>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
		<table class="adminform">
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWIVAONE'); ?>:</b> </td><td><input type="text" name="aliqname" value="" size="30"/></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWIVATWO'); ?>:</b> </td><td><input type="text" name="aliqperc" value="" size="10"/> %</td></tr>
		<tr><td style="vertical-align: top;">&nbsp; <a href="javascript: void(0);" class="vbo-link-add"><?php echo JText::_('VBOADDTAXBKDWN'); ?></a></td><td><div id="breakdown-cont"></div></td></tr>
		</table>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<div style="display: none;" id="add-breakdown">
			<div class="add-tax-breakdown-cont">
				<div class="add-tax-breakdown-remove"> </div>
				<br clear="all"/>
				<div class="add-tax-breakdown-name">
					<span><?php echo JText::_('VBOTAXNAMEBKDWN'); ?></span>
					<input type="text" name="breakdown_name[]" value="" size="30" placeholder="<?php echo JText::_('VBOTAXNAMEBKDWNEX'); ?>"/>
				</div>
				<div class="add-tax-breakdown-rate">
					<span><?php echo JText::_('VBOTAXRATEBKDWN'); ?></span>
					<input type="text" name="breakdown_rate[]" value="" size="6" placeholder="0.00"/>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery(".vbo-link-add").click(function(){
				jQuery("#breakdown-cont").append(jQuery("#add-breakdown").html());
			});
			jQuery("body").on("click", ".add-tax-breakdown-remove", function() {
				jQuery(this).parent().remove();
			});
		});
		</script>
		<?php
	}
	
	public static function pEditIva ($row, $option) {
		$breakdown = array();
		if(!empty($row['breakdown'])) {
			$get_breakdown = json_decode($row['breakdown'], true);
			if(is_array($get_breakdown) && count($get_breakdown) > 0) {
				$breakdown = $get_breakdown;
			}
		}
		$breakdown_str = '';
		if(count($breakdown) > 0) {
			foreach ($breakdown as $bkey => $subtax) {
				$breakdown_str .= '<div class="add-tax-breakdown-cont">'."\n";
				$breakdown_str .= '<div class="add-tax-breakdown-remove"> </div>'."\n";
				$breakdown_str .= '<br clear="all"/>'."\n";
				$breakdown_str .= '<div class="add-tax-breakdown-name">'."\n";
				$breakdown_str .= '<span>'.JText::_('VBOTAXNAMEBKDWN').'</span>'."\n";
				$breakdown_str .= '<input type="text" name="breakdown_name[]" value="'.$subtax['name'].'" size="30" placeholder="'.JText::_('VBOTAXNAMEBKDWNEX').'"/>'."\n";
				$breakdown_str .= '</div>'."\n";
				$breakdown_str .= '<div class="add-tax-breakdown-rate">'."\n";
				$breakdown_str .= '<span>'.JText::_('VBOTAXRATEBKDWN').'</span>'."\n";
				$breakdown_str .= '<input type="text" name="breakdown_rate[]" value="'.$subtax['aliq'].'" size="6" placeholder="0.00"/>'."\n";
				$breakdown_str .= '</div>'."\n";
				$breakdown_str .= '</div>'."\n";
			}
		}
		?>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
		<table class="adminform">
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWIVAONE'); ?>:</b> </td><td><input type="text" name="aliqname" value="<?php echo $row['name']; ?>" size="30"/></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWIVATWO'); ?>:</b> </td><td><input type="text" name="aliqperc" value="<?php echo $row['aliq']; ?>" size="10"/> %</td></tr>
		<tr><td style="vertical-align: top;">&nbsp; <a href="javascript: void(0);" class="vbo-link-add"><?php echo JText::_('VBOADDTAXBKDWN'); ?></a></td><td><div id="breakdown-cont"><?php echo $breakdown_str; ?></div></td></tr>
		</table>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="whereup" value="<?php echo $row['id']; ?>">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<div style="display: none;" id="add-breakdown">
			<div class="add-tax-breakdown-cont">
				<div class="add-tax-breakdown-remove"> </div>
				<br clear="all"/>
				<div class="add-tax-breakdown-name">
					<span><?php echo JText::_('VBOTAXNAMEBKDWN'); ?></span>
					<input type="text" name="breakdown_name[]" value="" size="30" placeholder="<?php echo JText::_('VBOTAXNAMEBKDWNEX'); ?>"/>
				</div>
				<div class="add-tax-breakdown-rate">
					<span><?php echo JText::_('VBOTAXRATEBKDWN'); ?></span>
					<input type="text" name="breakdown_rate[]" value="" size="6" placeholder="0.00"/>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery(".vbo-link-add").click(function(){
				jQuery("#breakdown-cont").append(jQuery("#add-breakdown").html());
			});
			jQuery("body").on("click", ".add-tax-breakdown-remove", function() {
				jQuery(this).parent().remove();
			});
		});
		</script>
		<?php
	}
	
	public static function pNewPrice ($option) {
		$dbo = JFactory::getDBO();
		$q="SELECT * FROM `#__vikbooking_iva`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$ivas=$dbo->loadAssocList();
			$wiva="<select name=\"praliq\">\n";
			foreach($ivas as $iv){
				$wiva.="<option value=\"".$iv['id']."\">".(empty($iv['name']) ? $iv['aliq']."%" : $iv['name']."-".$iv['aliq']."%")."</option>\n";
			}
			$wiva.="</select>\n";
		}else {
			$wiva="<a href=\"index.php?option=com_vikbooking&task=viewiva\">".JText::_('NESSUNAIVA')."</a>";
		}
		?>
		<script type="text/javascript">
		function toggleFreeCancellation() {
			if(document.getElementById('free_cancellation').checked == true) {
				document.getElementById('canc_deadline').style.display='block';
			}else {
				document.getElementById('canc_deadline').style.display='none';
			}
			return true;
		}
		</script>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
		<table class="adminform">
		<tr><td width="250">&bull; <b><?php echo JText::_('VBNEWPRICEONE'); ?>*:</b> </td><td><input type="text" name="price" value="" size="40"/></td></tr>
		<tr><td width="250">&bull; <b><?php echo JText::_('VBNEWPRICETWO'); ?>:</b> </td><td><input type="text" name="attr" value="" size="40"/></td></tr>
		<tr><td width="250">&bull; <b><?php echo JText::_('VBNEWPRICETHREE'); ?>:</b> </td><td><?php echo $wiva; ?></td></tr>
		<tr><td width="250">&bull; <b><?php echo JText::_('VBNEWPRICEBREAKFAST'); ?>:</b> </td><td><input type="checkbox" name="breakfast_included" value="1" /></td></tr>
		<tr><td width="250">&bull; <b><?php echo JText::_('VBNEWPRICEFREECANC'); ?>:</b> </td><td><input type="checkbox" id="free_cancellation" name="free_cancellation" value="1" onclick="toggleFreeCancellation();"/></td></tr>
		<tr id="canc_deadline" style="display: none;"><td width="250">&bull; <b><?php echo JText::_('VBNEWPRICEFREECANCDLINE'); ?>:</b> </td><td><input type="text" name="canc_deadline" value="7" size="5"/></td></tr>
		</table>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<?php
	}
	
	public static function pEditPrice ($row, $option) {
		$dbo = JFactory::getDBO();
		$q="SELECT * FROM `#__vikbooking_iva`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$ivas=$dbo->loadAssocList();
			$wiva="<select name=\"praliq\">\n";
			foreach($ivas as $iv){
				$wiva.="<option value=\"".$iv['id']."\"".($iv['id']==$row['idiva'] ? " selected=\"selected\"" : "").">".(empty($iv['name']) ? $iv['aliq']."%" : $iv['name']."-".$iv['aliq']."%")."</option>\n";
			}
			$wiva.="</select>\n";
		}else {
			$wiva="<a href=\"index.php?option=com_vikbooking&task=viewiva\">".JText::_('NESSUNAIVA')."</a>";
		}
		?>
		<script type="text/javascript">
		function toggleFreeCancellation() {
			if(document.getElementById('free_cancellation').checked == true) {
				document.getElementById('canc_deadline').style.display='block';
			}else {
				document.getElementById('canc_deadline').style.display='none';
			}
			return true;
		}
		</script>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
		<table class="adminform">
		<tr><td width="250">&bull; <b><?php echo JText::_('VBNEWPRICEONE'); ?>*:</b> </td><td><input type="text" name="price" value="<?php echo $row['name']; ?>" size="40"/></td></tr>
		<tr><td width="250">&bull; <b><?php echo JText::_('VBNEWPRICETWO'); ?>:</b> </td><td><input type="text" name="attr" value="<?php echo $row['attr']; ?>" size="40"/></td></tr>
		<tr><td width="250">&bull; <b><?php echo JText::_('VBNEWPRICETHREE'); ?>:</b> </td><td><?php echo $wiva; ?></td></tr>
		<tr><td width="250">&bull; <b><?php echo JText::_('VBNEWPRICEBREAKFAST'); ?>:</b> </td><td><input type="checkbox" name="breakfast_included" value="1" <?php echo $row['breakfast_included'] == 1 ? 'checked="checked"' : ''; ?>/></td></tr>
		<tr><td width="250">&bull; <b><?php echo JText::_('VBNEWPRICEFREECANC'); ?>:</b> </td><td><input type="checkbox" id="free_cancellation" name="free_cancellation" value="1" onclick="toggleFreeCancellation();" <?php echo $row['free_cancellation'] == 1 ? 'checked="checked"' : ''; ?>/></td></tr>
		<tr id="canc_deadline" style="display: <?php echo $row['free_cancellation'] == 1 ? 'block' : 'none'; ?>;"><td width="250">&bull; <b><?php echo JText::_('VBNEWPRICEFREECANCDLINE'); ?>:</b> </td><td><input type="text" name="canc_deadline" value="<?php echo $row['canc_deadline']; ?>" size="5"/></td></tr>
		</table>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="whereup" value="<?php echo $row['id']; ?>">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<?php
	}
	
	public static function pNewCat ($option) {
		$editor = JFactory::getEditor();
		?>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
		<table class="adminform">
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCATONE'); ?>:</b> </td><td><input type="text" name="catname" value="" size="40"/></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCATDESCR'); ?>:</b> </td><td><?php echo $editor->display( "descr", "", 400, 200, 70, 20 ); ?></td></tr>
		</table>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<?php
	}
	
	public static function pEditCat ($row, $option) {
		$editor = JFactory::getEditor();
		?>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
		<table class="adminform">
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCATONE'); ?>:</b> </td><td><input type="text" name="catname" value="<?php echo $row['name']; ?>" size="40"/></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCATDESCR'); ?>:</b> </td><td><?php echo $editor->display( "descr", $row['descr'], 400, 200, 70, 20 ); ?></td></tr>
		</table>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="whereup" value="<?php echo $row['id']; ?>">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<?php
	}
	
	public static function pNewCarat ($option) {
		?>
		<script type="text/javascript">
		function showResizeSel() {
			if(document.adminForm.autoresize.checked == true) {
				document.getElementById('resizesel').style.display='block';
			}else {
				document.getElementById('resizesel').style.display='none';
			}
			return true;
		}
		</script>
		<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data">
		<table class="adminform">
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCARATONE'); ?>:</b> </td><td><input type="text" name="caratname" value="" size="40"/></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCARATTWO'); ?>:</b> </td><td><input type="file" name="caraticon" size="35"/><br/><label for="autoresize"><?php echo JText::_('VBNEWOPTNINE'); ?></label> <input type="checkbox" id="autoresize" name="autoresize" value="1" onclick="showResizeSel();"/> <span id="resizesel" style="display: none;">&nbsp;<?php echo JText::_('VBNEWOPTTEN'); ?>: <input type="text" name="resizeto" value="50" size="3"/> px</span></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCARATTHREE'); ?>:</b> </td><td><input type="text" name="carattextimg" value="" size="40"/></td></tr>
		</table>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<?php
	}
	
	public static function pEditCarat ($row, $option) {
		?>
		<script type="text/javascript">
		function showResizeSel() {
			if(document.adminForm.autoresize.checked == true) {
				document.getElementById('resizesel').style.display='block';
			}else {
				document.getElementById('resizesel').style.display='none';
			}
			return true;
		}
		</script>
		<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data">
		<table class="adminform">
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCARATONE'); ?>:</b> </td><td><input type="text" name="caratname" value="<?php echo $row['name']; ?>" size="40"/></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCARATTWO'); ?>:</b> </td><td><?php echo (!empty($row['icon']) && file_exists('../components/com_vikbooking/resources/uploads/'.$row['icon']) ? "<img src=\"../components/com_vikbooking/resources/uploads/".$row['icon']."\"/>&nbsp; " : ""); ?><input type="file" name="caraticon" size="35"/><br/><label for="autoresize"><?php echo JText::_('VBNEWOPTNINE'); ?></label> <input type="checkbox" id="autoresize" name="autoresize" value="1" onclick="showResizeSel();"/> <span id="resizesel" style="display: none;">&nbsp;<?php echo JText::_('VBNEWOPTTEN'); ?>: <input type="text" name="resizeto" value="50" size="3"/> px</span></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWCARATTHREE'); ?>:</b> </td><td><input type="text" name="carattextimg" value="<?php echo $row['textimg']; ?>" size="40"/></td></tr>
		</table>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="whereup" value="<?php echo $row['id']; ?>">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<?php
	}
	
	public static function pNewOptionals ($option) {
		$editor = JFactory::getEditor();
		$dbo = JFactory::getDBO();
		$q="SELECT * FROM `#__vikbooking_iva`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$ivas=$dbo->loadAssocList();
			$wiva="<select name=\"optaliq\"><option value=\"\"> </option>\n";
			foreach($ivas as $iv){
				$wiva.="<option value=\"".$iv['id']."\">".(empty($iv['name']) ? $iv['aliq']."%" : $iv['name']."-".$iv['aliq']."%")."</option>\n";
			}
			$wiva.="</select>\n";
		}else {
			$wiva="<a href=\"index.php?option=com_vikbooking&task=viewiva\">".JText::_('VBNOIVAFOUND')."</a>";
		}
		$currencysymb=vikbooking::getCurrencySymb(true);
		?>
		<script type="text/javascript">
		function showResizeSel() {
			if(document.adminForm.autoresize.checked == true) {
				document.getElementById('resizesel').style.display='block';
			}else {
				document.getElementById('resizesel').style.display='none';
			}
			return true;
		}
		function showForceSel() {
			if(document.adminForm.forcesel.checked == true) {
				document.getElementById('forcevalspan').style.display='block';
			}else {
				document.getElementById('forcevalspan').style.display='none';
			}
			return true;
		}
		function showMaxQuant() {
			if(document.adminForm.opthmany.checked == true) {
				document.getElementById('maxquantblock').style.display='block';
			}else {
				document.getElementById('maxquantblock').style.display='none';
			}
			return true;
		}
		function showAgeIntervals() {
			if(document.adminForm.ifchildren.checked == true) {
				document.getElementById('ifchildrenextra').style.display='block';
				if(document.getElementById('myDiv').getElementsByTagName('div').length > 0) {
					document.getElementById('optperpersontr').style.display='none';
					document.getElementById('opthmanytr').style.display='none';
					document.getElementById('forceseltr').style.display='none';
				}
			}else {
				document.getElementById('ifchildrenextra').style.display='none';
				if(document.getElementById('optperpersontr').style.display == 'none') {
					document.getElementById('optperpersontr').style.display='';
					document.getElementById('opthmanytr').style.display='';
					document.getElementById('forceseltr').style.display='';
				}
			}
			return true;
		}
		function addAgeInterval() {
			var ni = document.getElementById('myDiv');
			var numi = document.getElementById('moreagaintervals');
			var num = (document.getElementById('moreagaintervals').value -1)+ 2;
			numi.value = num;
			var newdiv = document.createElement('div');
			var divIdName = 'my'+num+'Div';
			newdiv.setAttribute('id',divIdName);
			newdiv.innerHTML = '<p><?php echo addslashes(JText::_('VBNEWAGEINTERVALFROM')); ?>: <input type=\'text\' name=\'agefrom[]\' size=\'2\'/> <?php echo addslashes(JText::_('VBNEWAGEINTERVALTO')); ?>: <input type=\'text\' name=\'ageto[]\' size=\'2\'/> <?php echo addslashes(JText::_('VBNEWAGEINTERVALCOST')); ?>: <input type=\'text\' name=\'agecost[]\' size=\'4\'/> <?php echo addslashes($currencysymb); ?> <img src=\'<?php echo JURI::root(); ?>administrator/components/com_vikbooking/resources/remove.png\' onclick=\'removeAgeInterval("my'+num+'Div");\' style=\'cursor: pointer; vertical-align: middle;\'/></p>';
			ni.appendChild(newdiv);
			if(document.getElementById('optperpersontr').style.display != 'none') {
				document.getElementById('optperpersontr').style.display='none';
				document.getElementById('opthmanytr').style.display='none';
				document.getElementById('forceseltr').style.display='none';
			}
		}
		function removeAgeInterval(el) {
			return (elem=document.getElementById(el)).parentNode.removeChild(elem);
		}
		</script>
		<input type="hidden" value="0" id="moreagaintervals" />
  
		<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data">
		<table class="adminform">
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTONE'); ?>:</b> </td><td><input type="text" name="optname" value="" size="40"/></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTTWO'); ?>:</b> </td><td><?php echo $editor->display( "optdescr", "", 400, 200, 70, 20 ); ?></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTTHREE'); ?>:</b> </td><td><?php echo $currencysymb; ?> <input type="text" name="optcost" value="" size="10"/></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTFOUR'); ?>:</b> </td><td><?php echo $wiva; ?></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTFIVE'); ?>:</b> </td><td><input type="checkbox" name="optperday" value="each"/></td></tr>
		<tr id="optperpersontr"><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTPERPERSON'); ?>:</b> </td><td><input type="checkbox" name="optperperson" value="each"/></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTEIGHT'); ?>:</b> </td><td><?php echo $currencysymb; ?> <input type="text" name="maxprice" value="" size="4"/></td></tr>
		<tr><td width="200" style="vertical-align: top;">&bull; <b><?php echo JText::_('VBNEWOPTIFCHILDREN'); ?>:</b> </td><td><input type="checkbox" name="ifchildren" value="1" onclick="showAgeIntervals();"/><div id="ifchildrenextra" style="display: none;"><p style="display: block; font-weight: bold;"><?php echo JText::_('VBNEWOPTIFAGEINTERVAL'); ?></p><div id="myDiv" style="display: block;"></div><a href="javascript: void(0);" onclick="addAgeInterval();"><?php echo JText::_('VBADDAGEINTERVAL'); ?></a></div></td></tr>
		<tr id="opthmanytr"><td width="200" style="vertical-align: top;">&bull; <b><?php echo JText::_('VBNEWOPTSIX'); ?>:</b> </td><td><input type="checkbox" name="opthmany" value="yes" onclick="showMaxQuant();"/> <span id="maxquantblock" style="display: none;"><?php echo JText::_('VBNEWOPTMAXQUANTSEL'); ?> <input type="text" name="maxquant" value="0" size="2"/></span></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTSEVEN'); ?>:</b> </td><td><input type="file" name="optimg" size="35"/><br/><label for="autoresize"><?php echo JText::_('VBNEWOPTNINE'); ?></label> <input type="checkbox" id="autoresize" name="autoresize" value="1" onclick="showResizeSel();"/> <span id="resizesel" style="display: none;">&nbsp;<?php echo JText::_('VBNEWOPTTEN'); ?>: <input type="text" name="resizeto" value="50" size="3"/> px</span></td></tr>
		<tr id="forceseltr"><td width="200" valign="top">&bull; <b><?php echo JText::_('VBNEWOPTFORCESEL'); ?>:</b> </td><td><input type="checkbox" name="forcesel" value="1" onclick="showForceSel();"/> <span id="forcevalspan" style="display: none;"><?php echo JText::_('VBNEWOPTFORCEVALT'); ?> <input type="text" name="forceval" value="1" size="2"/><br/><?php echo JText::_('VBNEWOPTFORCEVALTPDAY'); ?> <input type="checkbox" name="forcevalperday" value="1"/><br/><?php echo JText::_('VBNEWOPTFORCEVALPERCHILD'); ?> <input type="checkbox" name="forcevalperchild" value="1"/><br/><br/><?php echo JText::_('VBNEWOPTFORCESUMMARY'); ?> <input type="checkbox" name="forcesummary" value="1"/></span></td></tr>
		<tr><td colspan="2"><div class="vbexplaination"><?php echo JText::_('VBOPTHELPCITYTAXFEE'); ?></div></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTISCITYTAX'); ?>:</b> </td><td><input type="checkbox" name="is_citytax" value="1"/></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTISFEE'); ?>:</b> </td><td><input type="checkbox" name="is_fee" value="1"/></td></tr>
		</table>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<?php
	}
	
	public static function pEditOptional ($row, $tot_rooms, $tot_rooms_options, $option) {
		JHTML::_('behavior.modal');
		$editor = JFactory::getEditor();
		$dbo = JFactory::getDBO();
		$q="SELECT * FROM `#__vikbooking_iva`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$ivas=$dbo->loadAssocList();
			$wiva="<select name=\"optaliq\"><option value=\"\"> </option>\n";
			foreach($ivas as $iv){
				$wiva.="<option value=\"".$iv['id']."\"".($row['idiva']==$iv['id'] ? " selected=\"selected\"" : "").">".(empty($iv['name']) ? $iv['aliq']."%" : $iv['name']."-".$iv['aliq']."%")."</option>\n";
			}
			$wiva.="</select>\n";
		}else {
			$wiva="<a href=\"index.php?option=com_vikbooking&task=viewiva\">".JText::_('VBNOIVAFOUND')."</a>";
		}
		$currencysymb=vikbooking::getCurrencySymb(true);
		//vikbooking 1.1
		if(strlen($row['forceval']) > 0) {
			$forceparts = explode("-", $row['forceval']);
			$forcedq = $forceparts[0];
			$forcedqperday = intval($forceparts[1]) == 1 ? true : false;
			$forcedqperchild = intval($forceparts[2]) == 1 ? true : false;
			$forcesummary = intval($forceparts[3]) == 1 ? true : false;
		}else {
			$forcedq = "1";
			$forcedqperday = false;
			$forcedqperchild = false;
			$forcesummary = false;
		}
		//
		$useageintervals = false;
		$oldageintervals = '';
		if (!empty($row['ageintervals'])) {
			$useageintervals = true;
			$ageparts = explode(';;', $row['ageintervals']);
			foreach($ageparts as $kage => $age) {
				if (empty($age)) {
					continue;
				}
				$interval = explode('_', $age);
				$oldageintervals .= '<p id="old'.$kage.'intv">'.JText::_('VBNEWAGEINTERVALFROM').': <input type="text" name="agefrom[]" size="2" value="'.$interval[0].'"/> '.JText::_('VBNEWAGEINTERVALTO').': <input type="text" name="ageto[]" size="2" value="'.$interval[1].'"/> '.JText::_('VBNEWAGEINTERVALCOST').': <input type="text" name="agecost[]" size="4" value="'.$interval[2].'"/> '.$currencysymb.' <img src="'.JURI::root().'administrator/components/com_vikbooking/resources/remove.png" onclick="removeAgeInterval(\'old'.$kage.'intv\');" style="cursor: pointer; vertical-align: middle;"/></p>'."\n";
			}
		}
		?>
		<script type="text/javascript">
		function showResizeSel() {
			if(document.adminForm.autoresize.checked == true) {
				document.getElementById('resizesel').style.display='block';
			}else {
				document.getElementById('resizesel').style.display='none';
			}
			return true;
		}
		function showForceSel() {
			if(document.adminForm.forcesel.checked == true) {
				document.getElementById('forcevalspan').style.display='block';
			}else {
				document.getElementById('forcevalspan').style.display='none';
			}
			return true;
		}
		function showMaxQuant() {
			if(document.adminForm.opthmany.checked == true) {
				document.getElementById('maxquantblock').style.display='block';
			}else {
				document.getElementById('maxquantblock').style.display='none';
			}
			return true;
		}
		function showAgeIntervals() {
			if(document.adminForm.ifchildren.checked == true) {
				document.getElementById('ifchildrenextra').style.display='block';
				if(document.getElementById('myDiv').getElementsByTagName('div').length > 0 || document.getElementById('myDiv').getElementsByTagName('p').length > 0) {
					document.getElementById('optperpersontr').style.display='none';
					document.getElementById('opthmanytr').style.display='none';
					document.getElementById('forceseltr').style.display='none';
				}
			}else {
				document.getElementById('ifchildrenextra').style.display='none';
				if(document.getElementById('optperpersontr').style.display == 'none') {
					document.getElementById('optperpersontr').style.display='';
					document.getElementById('opthmanytr').style.display='';
					document.getElementById('forceseltr').style.display='';
				}
			}
			return true;
		}
		function addAgeInterval() {
			var ni = document.getElementById('myDiv');
			var numi = document.getElementById('moreagaintervals');
			var num = (document.getElementById('moreagaintervals').value -1)+ 2;
			numi.value = num;
			var newdiv = document.createElement('div');
			var divIdName = 'my'+num+'Div';
			newdiv.setAttribute('id',divIdName);
			newdiv.innerHTML = '<p><?php echo addslashes(JText::_('VBNEWAGEINTERVALFROM')); ?>: <input type=\'text\' name=\'agefrom[]\' size=\'2\'/> <?php echo addslashes(JText::_('VBNEWAGEINTERVALTO')); ?>: <input type=\'text\' name=\'ageto[]\' size=\'2\'/> <?php echo addslashes(JText::_('VBNEWAGEINTERVALCOST')); ?>: <input type=\'text\' name=\'agecost[]\' size=\'4\'/> <?php echo addslashes($currencysymb); ?> <img src=\'<?php echo JURI::root(); ?>administrator/components/com_vikbooking/resources/remove.png\' onclick=\'removeAgeInterval("my'+num+'Div");\' style=\'cursor: pointer; vertical-align: middle;\'/></p>';
			ni.appendChild(newdiv);
			if(document.getElementById('optperpersontr').style.display != 'none') {
				document.getElementById('optperpersontr').style.display='none';
				document.getElementById('opthmanytr').style.display='none';
				document.getElementById('forceseltr').style.display='none';
			}
		}
		function removeAgeInterval(el) {
			return (elem=document.getElementById(el)).parentNode.removeChild(elem);
		}
		</script>
		<input type="hidden" value="0" id="moreagaintervals" />
		
		<div class="vbo-outer-info-message" id="vbo-outer-info-message-opt" style="display: block;" onclick="removeAgeInterval('vbo-outer-info-message-opt');">
			<div class="vbo-info-message-cont">
				<i class="vboicn-info"></i><span><?php echo JText::sprintf('VBOOPTASSTOXROOMS', $tot_rooms_options, $tot_rooms); ?></span>
			</div>
		</div>

		<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data">
		<table class="adminform">
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTONE'); ?>:</b> </td><td><input type="text" name="optname" value="<?php echo $row['name']; ?>" size="40"/></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTTWO'); ?>:</b> </td><td><?php echo $editor->display( "optdescr", $row['descr'], 400, 200, 70, 20 ); ?></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTTHREE'); ?>:</b> </td><td><?php echo $currencysymb; ?> <input type="text" name="optcost" value="<?php echo $row['cost']; ?>" size="10"/></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTFOUR'); ?>:</b> </td><td><?php echo $wiva; ?></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTFIVE'); ?>:</b> </td><td><input type="checkbox" name="optperday" value="each"<?php echo (intval($row['perday'])==1 ? " checked=\"checked\"" : ""); ?>/></td></tr>
		<tr id="optperpersontr"><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTPERPERSON'); ?>:</b> </td><td><input type="checkbox" name="optperperson" value="each"<?php echo (intval($row['perperson'])==1 ? " checked=\"checked\"" : ""); ?>/></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTEIGHT'); ?>:</b> </td><td><?php echo $currencysymb; ?> <input type="text" name="maxprice" value="<?php echo $row['maxprice']; ?>" size="4"/></td></tr>
		<tr><td width="200" style="vertical-align: top;">&bull; <b><?php echo JText::_('VBNEWOPTIFCHILDREN'); ?>:</b> </td><td><input type="checkbox" name="ifchildren" value="1" onclick="showAgeIntervals();"<?php echo (intval($row['ifchildren'])==1 ? " checked=\"checked\"" : ""); ?>/><div id="ifchildrenextra" style="display: <?php echo ($useageintervals === true && strlen($oldageintervals) > 0 ? "block" : "none"); ?>;"><p style="display: block; font-weight: bold;"><?php echo JText::_('VBNEWOPTIFAGEINTERVAL'); ?></p><div id="myDiv" style="display: block;"><?php echo $oldageintervals; ?></div><a href="javascript: void(0);" onclick="addAgeInterval();"><?php echo JText::_('VBADDAGEINTERVAL'); ?></a></div></td></tr>
		<tr id="opthmanytr"><td width="200" style="vertical-align: top;">&bull; <b><?php echo JText::_('VBNEWOPTSIX'); ?>:</b> </td><td><input type="checkbox" name="opthmany" value="yes" onclick="showMaxQuant();"<?php echo (intval($row['hmany'])==1 ? " checked=\"checked\"" : ""); ?>/> <span id="maxquantblock" style="display: <?php echo (intval($row['hmany'])==1 ? "block" : "none"); ?>;"><?php echo JText::_('VBNEWOPTMAXQUANTSEL'); ?> <input type="text" name="maxquant" value="<?php echo $row['maxquant']; ?>" size="2"/></span></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTSEVEN'); ?>:</b> </td><td><?php echo (!empty($row['img']) && file_exists('../components/com_vikbooking/resources/uploads/'.$row['img']) ? '<a href="'.JURI::root().'components/com_vikbooking/resources/uploads/'.$row['img'].'" class="modal" target="_blank">'.$row['img'].'</a> &nbsp;' : ""); ?><input type="file" name="optimg" size="35"/><br/><label for="autoresize"><?php echo JText::_('VBNEWOPTNINE'); ?></label> <input type="checkbox" id="autoresize" name="autoresize" value="1" onclick="showResizeSel();"/> <span id="resizesel" style="display: none;">&nbsp;<?php echo JText::_('VBNEWOPTTEN'); ?>: <input type="text" name="resizeto" value="50" size="3"/> px</span></td></tr>
		<tr id="forceseltr"><td width="200" valign="top">&bull; <b><?php echo JText::_('VBNEWOPTFORCESEL'); ?>:</b> </td><td><input type="checkbox" name="forcesel" value="1" onclick="showForceSel();"<?php echo (intval($row['forcesel'])==1 ? " checked=\"checked\"" : ""); ?>/> <span id="forcevalspan" style="display: <?php echo (intval($row['forcesel'])==1 ? "block" : "none"); ?>;"><?php echo JText::_('VBNEWOPTFORCEVALT'); ?> <input type="text" name="forceval" value="<?php echo $forcedq; ?>" size="2"/><br/><?php echo JText::_('VBNEWOPTFORCEVALTPDAY'); ?> <input type="checkbox" name="forcevalperday" value="1"<?php echo ($forcedqperday == true ? " checked=\"checked\"" : ""); ?>/><br/><?php echo JText::_('VBNEWOPTFORCEVALPERCHILD'); ?> <input type="checkbox" name="forcevalperchild" value="1"<?php echo ($forcedqperchild == true ? " checked=\"checked\"" : ""); ?>/><br/><br/><?php echo JText::_('VBNEWOPTFORCESUMMARY'); ?> <input type="checkbox" name="forcesummary" value="1"<?php echo ($forcesummary == true ? " checked=\"checked\"" : ""); ?>/></span></td></tr>
		<tr><td colspan="2"><div class="vbexplaination"><?php echo JText::_('VBOPTHELPCITYTAXFEE'); ?></div></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTISCITYTAX'); ?>:</b> </td><td><input type="checkbox" name="is_citytax" value="1"<?php echo ($row['is_citytax'] == 1 ? " checked=\"checked\"" : ""); ?>/></td></tr>
		<tr><td width="200">&bull; <b><?php echo JText::_('VBNEWOPTISFEE'); ?>:</b> </td><td><input type="checkbox" name="is_fee" value="1"<?php echo ($row['is_fee'] == 1 ? " checked=\"checked\"" : ""); ?>/></td></tr>
		</table>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="whereup" value="<?php echo $row['id']; ?>">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		
		<script type="text/javascript">
		if(document.adminForm.ifchildren.checked == true && document.getElementById('myDiv').getElementsByTagName('p').length > 0) {
			document.getElementById('optperpersontr').style.display='none';
			document.getElementById('opthmanytr').style.display='none';
			document.getElementById('forceseltr').style.display='none';
		}
		</script>
		<?php
	}
	
	public static function pNewRoom ($cats, $carats, $optionals, $option) {
		JHTML::_('behavior.tooltip');
		$vbo_app = new VikApplication();
		$currencysymb=vikbooking::getCurrencySymb(true);
		if (is_array($cats)) {
			$wcats="<tr><td width=\"200\" class=\"vbo-config-param-cell\"> <b>".JText::_('VBNEWROOMONE')."</b></td><td>";
			$wcats.="<select name=\"ccat[]\" multiple=\"multiple\" size=\"".(count($cats) + 1)."\">";
			foreach($cats as $cat){
				$wcats.="<option value=\"".$cat['id']."\">".$cat['name']."</option>\n";
			}
			$wcats.="</select></td></tr>\n";
		}else {
			$wcats="";
		}
		if (is_array($carats)) {
			$wcarats="<tr><td width=\"200\" class=\"vbo-config-param-cell\"> <b>".JText::_('VBNEWROOMTHREE')."</b> </td><td>";
			$wcarats.="<div class=\"vbo-roomentries-cont\">";
			$nn=0;
			foreach($carats as $kcarat => $carat) {
				$wcarats.="<div class=\"vbo-roomentry-cont\"><input type=\"checkbox\" name=\"ccarat[]\" value=\"".$carat['id']."\" id=\"carat".$kcarat."\"/> <label for=\"carat".$kcarat."\">".$carat['name']."</label></div>\n";
				$nn++;
				if (($nn % 3) == 0) {
					$wcarats.="</div>\n<div class=\"vbo-roomentries-cont\">";
				}
			}
			$wcarats.="</div>\n";
			$wcarats.="</td></tr>\n";
		}else {
			$wcarats="";
		}
		if (is_array($optionals)) {
			$woptionals="<tr><td width=\"200\" class=\"vbo-config-param-cell\"> <b>".JText::_('VBNEWROOMFOUR')."</b> </td><td>";
			$woptionals.="<div class=\"vbo-roomentries-cont\">";
			$nn=0;
			foreach($optionals as $kopt => $optional){
				$woptionals.="<div class=\"vbo-roomentry-cont\"><input type=\"checkbox\" name=\"coptional[]\" value=\"".$optional['id']."\" id=\"opt".$kopt."\"/> <label for=\"opt".$kopt."\">".$optional['name']." ".(empty($optional['ageintervals']) ? $currencysymb."".$optional['cost'] : "")."</label></div>\n";
				$nn++;
				if (($nn % 3) == 0) {
					$woptionals.="</div>\n<div class=\"vbo-roomentries-cont\">";
				}
			}
			$woptionals.="</div>\n";
			$woptionals.="</td></tr>\n";
		}else {
			$woptionals="";
		}
		$editor = JFactory::getEditor();
		?>
		<script type="text/javascript">
		//Code to debug the size of the form to be submitted in case it will exceed the PHP post_max_size
		/*
		Joomla.submitbutton = function(task) {
			console.log(jQuery("#adminForm").not("[type='file']").serialize().length);
			Joomla.submitform(task, document.adminForm);
		}
		*/
		function showResizeSel() {
			if(document.adminForm.autoresize.checked == true) {
				document.getElementById('resizesel').style.display='block';
			}else {
				document.getElementById('resizesel').style.display='none';
			}
			return true;
		}
		function showResizeSelMore() {
			if(document.adminForm.autoresizemore.checked == true) {
				document.getElementById('resizeselmore').style.display='block';
			}else {
				document.getElementById('resizeselmore').style.display='none';
			}
			return true;
		}
		function addMoreImages() {
			var ni = document.getElementById('myDiv');
			var numi = document.getElementById('moreimagescounter');
			var num = (document.getElementById('moreimagescounter').value -1)+ 2;
			numi.value = num;
			var newdiv = document.createElement('div');
			var divIdName = 'my'+num+'Div';
			newdiv.setAttribute('id',divIdName);
			newdiv.innerHTML = '<input type=\'file\' name=\'cimgmore[]\' size=\'35\'/> <span><?php echo addslashes(JText::_('VBIMGCAPTION')); ?></span> <input type=\'text\' name=\'cimgcaption[]\' size=\'30\' value=\'\'/><br/>';
			ni.appendChild(newdiv);
		}
		function vbPlusMinus(what, how) {
			var inp = document.getElementById(what);
			var actval = inp.value;
			var newval = 0;
			if(how == 'plus') {
				newval = parseInt(actval) + 1;
			}else {
				if(parseInt(actval) >= 1) {
					newval = parseInt(actval) - 1;
				}
			}
			inp.value = newval;
			if(what == 'toadult' || what == 'tochild') {
				vbMaxTotPeople();
			}
			if(what == 'fromadult' || what == 'fromchild') {
				vbMinTotPeople();
			}
			return true;
		}
		function vbMaxTotPeople() {
			var toadu = document.getElementById('toadult').value;
			var tochi = document.getElementById('tochild').value;
			document.getElementById('totpeople').value = parseInt(toadu) + parseInt(tochi);
			return true;
		}
		function vbMinTotPeople() {
			var fadu = document.getElementById('fromadult').value;
			var fchi = document.getElementById('fromchild').value;
			document.getElementById('mintotpeople').value = parseInt(fadu) + parseInt(fchi);
			return true;
		}
		function togglePriceCalendarParam() {
			if(parseInt(document.getElementById('pricecal').value) == 1) {
				document.getElementById('defcalcostp').style.display = 'table-row';
				jQuery('.param-pricecal').addClass("vbroomparampactive");
			}else {
				document.getElementById('defcalcostp').style.display = 'none';
				jQuery('.param-pricecal').removeClass("vbroomparampactive");
			}
		}
		function toggleSeasonalCalendarParam() {
			if(parseInt(document.getElementById('seasoncal').value) > 0) {
				jQuery('.param-seasoncal').addClass("vbroomparampactive").show();
			}else {
				jQuery('.param-seasoncal').removeClass("vbroomparampactive");
				jQuery('.param-seasoncal').each(function(k, v) {
					if(k > 0) {
						jQuery(this).hide();
					}
				});
			}
		}
		/* Start - Room Disctinctive Features */
		var cur_units = 1;
		jQuery(document).ready(function() {
			jQuery('#vbo-distfeatures-toggle').click(function() {
				jQuery(this).toggleClass('btn-primary');
				jQuery('.vbo-distfeatures-cont').fadeToggle();
			});
			jQuery('#room_units').change(function() {
				var to_units = parseInt(jQuery(this).val());
				if(to_units > 1) {
					jQuery('.param-multiunits').show();
					jQuery('.vbo-distfeature-row').css('display', 'table-row');
				}else {
					jQuery('.param-multiunits').hide();
					jQuery('.vbo-distfeature-row').css('display', 'none');
				}
				if(to_units > cur_units) {
					var diff_units = (to_units - cur_units);
					for (var i = 1; i <= diff_units; i++) {
						var unit_html = "<div class=\"vbo-runit-features-cont\" id=\"runit-features-"+(i + cur_units)+"\">"+
										"	<span class=\"vbo-runit-num\"><?php echo addslashes(JText::_('VBODISTFEATURERUNIT')); ?>"+(i + cur_units)+"</span>"+
										"	<div class=\"vbo-runit-features\">"+
										"		<div class=\"vbo-runit-feature\">"+
										"			<input type=\"text\" name=\"feature-name"+(i + cur_units)+"[]\" value=\"\" size=\"20\" placeholder=\"<?php echo JText::_('VBODISTFEATURETXT'); ?>\"/>"+
										"			<input type=\"hidden\" name=\"feature-lang"+(i + cur_units)+"[]\" value=\"\"/>"+
										"			<input type=\"text\" name=\"feature-value"+(i + cur_units)+"[]\" value=\"\" size=\"20\" placeholder=\"<?php echo JText::_('VBODISTFEATUREVAL'); ?>\"/>"+
										"			<span class=\"vbo-feature-remove\">&nbsp;</span>"+
										"		</div>"+
										"		<span class=\"vbo-feature-add btn\"><i class=\"icon-new\"></i><?php echo addslashes(JText::_('VBODISTFEATUREADD')); ?></span>"+
										"	</div>"+
										"</div>";
						jQuery('.vbo-distfeatures-cont').append(unit_html);
					}
					cur_units = to_units;
				}else if(to_units < cur_units) {
					for (var i = cur_units; i > to_units; i--) {
						jQuery('#runit-features-'+i).remove();
					}
					cur_units = to_units;
				}
			});
		});
		jQuery(document.body).on('click', '.vbo-feature-add', function() {
			var cfeature_id = jQuery(this).parent('div').parent('div').attr('id').split('runit-features-');
			if(cfeature_id[1].length) {
				jQuery(this).before("<div class=\"vbo-runit-feature\">"+
									"	<input type=\"text\" name=\"feature-name"+cfeature_id[1]+"[]\" value=\"\" size=\"20\" placeholder=\"<?php echo JText::_('VBODISTFEATURETXT'); ?>\"/>"+
									"	<input type=\"hidden\" name=\"feature-lang"+cfeature_id[1]+"[]\" value=\"\"/>"+
									"	<input type=\"text\" name=\"feature-value"+cfeature_id[1]+"[]\" value=\"\" size=\"20\" placeholder=\"<?php echo JText::_('VBODISTFEATUREVAL'); ?>\"/>"+
									"	<span class=\"vbo-feature-remove\">&nbsp;</span>"+
									"</div>"
									);
			}
		});
		jQuery(document.body).on('click', '.vbo-feature-remove', function() {
			jQuery(this).parent('div').remove();
		});
		/* End - Room Disctinctive Features */
		if(jQuery.isFunction(jQuery.fn.tooltip)) {
			jQuery(".hasTooltip").tooltip();
		}
		</script>
		<input type="hidden" value="0" id="moreimagescounter" />
		
		<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data">
			
			<fieldset class="adminform">
				<legend class="adminlegend"><?php echo JText::_('VBOROOMLEGUNITOCC'); ?></legend>
				<table cellspacing="1" class="admintable table">
					<tbody>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWROOMFIVE'); ?></b> </td>
							<td><input type="text" name="cname" value="" size="40"/></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWROOMEIGHT'); ?></b> </td>
							<td><?php echo $vbo_app->printYesNoButtons('cavail', JText::_('VBYES'), JText::_('VBNO'), 'yes', 'yes', 0); ?></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWROOMNINE'); ?></b> </td>
							<td><input type="number" min="1" name="units" id="room_units" value="1" size="3" onfocus="this.select();" /></td>
						</tr>
						<?php
						$room_features = array(1 => vikbooking::getDefaultDistinctiveFeatures());
						?>
						<tr class="vbo-distfeature-row" style="display: none;">
							<td width="200" class="vbo-config-param-cell" style="vertical-align: top !important;"> <b><?php echo JText::_('VBOROOMUNITSDISTFEAT'); ?></b> </td>
							<td>
								<div class="vbo-distfeatures-toggle-cont">
									<span id="vbo-distfeatures-toggle" class="btn btn-primary"><i class="icon-eye"></i><?php echo JText::_('VBOROOMUNITSDISTFEATTOGGLE'); ?></span>
								</div>
								<div class="vbo-distfeatures-cont">
								<?php
								for ($i=1; $i <= 1; $i++) {
									?>
									<div class="vbo-runit-features-cont" id="runit-features-<?php echo $i; ?>">
										<span class="vbo-runit-num"><?php echo JText::_('VBODISTFEATURERUNIT'); ?><?php echo $i; ?></span>
										<div class="vbo-runit-features">
									<?php
									if(array_key_exists($i, $room_features)) {
										foreach ($room_features[$i] as $fkey => $fval) {
											?>
											<div class="vbo-runit-feature">
												<input type="text" name="feature-name<?php echo $i; ?>[]" value="<?php echo JText::_($fkey); ?>" size="20"/>
												<input type="hidden" name="feature-lang<?php echo $i; ?>[]" value="<?php echo $fkey; ?>"/>
												<input type="text" name="feature-value<?php echo $i; ?>[]" value="<?php echo $fval; ?>" size="20"/>
												<span class="vbo-feature-remove">&nbsp;</span>
											</div>
											<?php
										}
									}
									?>
											<span class="vbo-feature-add btn"><i class="icon-new"></i><?php echo JText::_('VBODISTFEATUREADD'); ?></span>
										</div>
									</div>
									<?php
								}
								?>
								</div>
							</td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWROOMADULTS'); ?></b> </td>
							<td><div class="vbplusminuscont"><?php echo JText::_('VBNEWROOMMIN'); ?> <input type="text" id="fromadult" name="fromadult" value="1" size="3" onchange="vbMinTotPeople();" style="width: 40px;"/></div><div onclick="vbPlusMinus('fromadult', 'plus');" class="vbplusminus"></div><div onclick="vbPlusMinus('fromadult', 'minus');" class="vbminus vbplusminus"></div><br clear="all"/><div class="vbplusminuscont"><?php echo JText::_('VBNEWROOMMAX'); ?> <input type="text" id="toadult" name="toadult" value="1" size="3" onchange="vbMaxTotPeople();" style="width: 40px;"/></div><div onclick="vbPlusMinus('toadult', 'plus');" class="vbplusminus"></div><div onclick="vbPlusMinus('toadult', 'minus');" class="vbminus vbplusminus"></div><br clear="all"/></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWROOMADULTSDIFF'); ?></b><br/><?php echo JHTML::tooltip(JText::_('VBNEWROOMADULTSDIFFHELP'), JText::_('VBNEWROOMADULTSDIFF'), 'tooltip.png', ''); ?> </td>
							<td><div style="display: block; width: 50%;"><i><?php echo JText::_('VBNEWROOMADULTSDIFFBEFSAVE'); ?></i></div></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWROOMCHILDREN'); ?></b> </td>
							<td><div class="vbplusminuscont"><?php echo JText::_('VBNEWROOMMIN'); ?> <input type="text" id="fromchild" name="fromchild" value="0" size="3" onchange="vbMinTotPeople();" style="width: 40px;"/></div><div onclick="vbPlusMinus('fromchild', 'plus');" class="vbplusminus"></div><div onclick="vbPlusMinus('fromchild', 'minus');" class="vbminus vbplusminus"></div><br clear="all"/><div class="vbplusminuscont"><?php echo JText::_('VBNEWROOMMAX'); ?> <input type="text" id="tochild" name="tochild" value="0" size="3" onchange="vbMaxTotPeople();" style="width: 40px;"/></div><div onclick="vbPlusMinus('tochild', 'plus');" class="vbplusminus"></div><div onclick="vbPlusMinus('tochild', 'minus');" class="vbminus vbplusminus"></div><br clear="all"/></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBMAXTOTPEOPLE'); ?></b> </td>
							<td><input type="number" name="totpeople" id="totpeople" value="1" min="1" style="width: 40px;"/> <i><?php echo JText::_('VBMAXTOTPEOPLEDESC'); ?></i></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBMINTOTPEOPLE'); ?></b> </td>
							<td><input type="number" name="mintotpeople" id="mintotpeople" value="1" min="1" style="width: 40px;"/> <i><?php echo JText::_('VBMINTOTPEOPLEDESC'); ?></i></td>
						</tr>
					</tbody>
				</table>
			</fieldset>

			<fieldset class="adminform">
				<legend class="adminlegend"><?php echo JText::_('VBOROOMLEGPHOTODESC'); ?></legend>
				<table cellspacing="1" class="admintable table">
					<tbody>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWROOMSIX'); ?></b> </td>
							<td><input type="file" name="cimg" size="35"/><br/><label style="display: inline;" for="autoresize"><?php echo JText::_('VBNEWOPTNINE'); ?></label> <input type="checkbox" id="autoresize" name="autoresize" value="1" onclick="showResizeSel();"/> <span id="resizesel" style="display: none;">&nbsp;<?php echo JText::_('VBNEWOPTTEN'); ?>: <input type="text" name="resizeto" value="250" size="3"/> px</span></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBMOREIMAGES'); ?></b><br/><a class="btn" href="javascript: void(0);" onclick="addMoreImages();"><i class="icon-new"></i><?php echo JText::_('VBADDIMAGES'); ?></a><p class="vbo-small-p-info"><?php echo JText::_('VBOBULKUPLOADAFTERSAVE'); ?></p></td>
							<td><input type="file" name="cimgmore[]" size="35"/> <span><?php echo JText::_('VBIMGCAPTION'); ?></span> <input type="text" name="cimgcaption[]" size="30" value=""/><div id="myDiv" style="display: block;"></div><label style="display: inline;" for="autoresizemore"><?php echo JText::_('VBRESIZEIMAGES'); ?></label> <input type="checkbox" id="autoresizemore" name="autoresizemore" value="1" onclick="showResizeSelMore();"/> <span id="resizeselmore" style="display: none;">&nbsp;<?php echo JText::_('VBNEWOPTTEN'); ?>: <input type="text" name="resizetomore" value="600" size="3"/> px</span></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWROOMSMALLDESC'); ?></b> </td>
							<td><textarea name="smalldesc" rows="6" cols="50"></textarea></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell" style="vertical-align: top !important;"> <b><?php echo JText::_('VBNEWROOMSEVEN'); ?></b> </td>
							<td><?php echo $editor->display( "cdescr", "", 400, 200, 70, 20 ); ?></td>
						</tr>
					</tbody>
				</table>
			</fieldset>

			<fieldset class="adminform">
				<legend class="adminlegend"><?php echo JText::_('VBOROOMLEGCARATCATOPT'); ?></legend>
				<table cellspacing="1" class="admintable table">
					<tbody>
						<?php echo $wcats; ?>
						<?php echo $wcarats; ?>
						<?php echo $woptionals; ?>
					</tbody>
				</table>
			</fieldset>

			<fieldset class="adminform">
				<legend class="adminlegend"><?php echo JText::_('VBNEWROOMPARAMS'); ?></legend>
				<table cellspacing="1" class="admintable table">
					<tbody>
						<tr class="vbroomparamp">
							<td width="200" class="vbo-config-param-cell"> <label for="lastavail"><?php echo JText::_('VBPARAMLASTAVAIL'); ?></label> </td>
							<td><input type="text" name="lastavail" id="lastavail" value="" size="2"/><span><?php echo JText::_('VBPARAMLASTAVAILHELP'); ?></span></td>
						</tr>
						<tr class="vbroomparamp">
							<td width="200" class="vbo-config-param-cell"> <label for="custprice"><?php echo JText::_('VBPARAMCUSTPRICE'); ?></label> </td>
							<td><input type="text" name="custprice" id="custprice" value="" size="5"/><span><?php echo JText::_('VBPARAMCUSTPRICEHELP'); ?></span></td>
						</tr>
						<tr class="vbroomparamp">
							<td width="200" class="vbo-config-param-cell"> <label for="custpricetxt"><?php echo JText::_('VBPARAMCUSTPRICETEXT'); ?></label> </td>
							<td><input type="text" name="custpricetxt" id="custpricetxt" value="" size="9"/><span><?php echo JText::_('VBPARAMCUSTPRICETEXTHELP'); ?></span></td>
						</tr>
						<tr class="vbroomparamp">
							<td width="200" class="vbo-config-param-cell"> <label for="reqinfo"><?php echo JText::_('VBORPARAMREQINFO'); ?></label> </td>
							<td><?php echo $vbo_app->printYesNoButtons('reqinfo', JText::_('VBYES'), JText::_('VBNO'), 0, 1, 0); ?></td>
						</tr>
						<tr class="vbroomparamp">
							<td width="200" class="vbo-config-param-cell"> <label for="maxminpeople"><?php echo JText::_('VBPARAMSHOWPEOPLE'); ?></label> </td>
							<td><select name="maxminpeople" id="maxminpeople"><option value="0"><?php echo JText::_('VBPARAMSHOWPEOPLENO'); ?></option><option value="1"><?php echo JText::_('VBPARAMSHOWPEOPLEADU'); ?></option><option value="2"><?php echo JText::_('VBPARAMSHOWPEOPLECHI'); ?></option><option value="3"><?php echo JText::_('VBPARAMSHOWPEOPLEADUTOT'); ?></option><option value="4"><?php echo JText::_('VBPARAMSHOWPEOPLECHITOT'); ?></option><option value="5"><?php echo JText::_('VBPARAMSHOWPEOPLEALLTOT'); ?></option></select></td>
						</tr>
						<tr class="vbroomparamp param-pricecal">
							<td width="200" class="vbo-config-param-cell"> <label for="pricecal"><?php echo JText::_('VBPARAMPRICECALENDAR'); ?></label> </td>
							<td><select name="pricecal" id="pricecal" onchange="togglePriceCalendarParam();"><option value="0"><?php echo JText::_('VBPARAMPRICECALENDARDISABLED'); ?></option><option value="1"><?php echo JText::_('VBPARAMPRICECALENDARENABLED'); ?></option></select><span><?php echo JText::_('VBPARAMPRICECALENDARHELP'); ?></span></td>
						</tr>
						<tr class="vbroomparamp param-pricecal" id="defcalcostp" style="display: none;">
							<td width="200" class="vbo-config-param-cell"> <label for="defcalcost"><?php echo JText::_('VBPARAMDEFCALCOST'); ?></label> </td>
							<td><input type="text" name="defcalcost" id="defcalcost" size="4" value="" placeholder="50.00"/><span><?php echo JText::_('VBPARAMDEFCALCOSTHELP'); ?></span></td>
						</tr>
						<tr class="vbroomparamp param-seasoncal">
							<td width="200" class="vbo-config-param-cell"> <label for="seasoncal"><?php echo JText::_('VBPARAMSEASONCALENDAR'); ?></label> </td>
							<td><select name="seasoncal" id="seasoncal" onchange="toggleSeasonalCalendarParam();"><option value="0"><?php echo JText::_('VBPARAMSEASONCALENDARDISABLED'); ?></option><optgroup label="<?php echo JText::_('VBPARAMSEASONCALENDARENABLED'); ?>"><option value="1"><?php echo JText::_('VBPARAMSEASONCALENDARENABLEDALL'); ?></option><option value="2"><?php echo JText::_('VBPARAMSEASONCALENDARENABLEDCHARGEDISC'); ?></option><option value="3"><?php echo JText::_('VBPARAMSEASONCALENDARENABLEDCHARGE'); ?></option></optgroup></select></td>
						</tr>
						<tr class="vbroomparamp param-seasoncal" style="display: none;">
							<td width="200" class="vbo-config-param-cell"> <label for="seasoncal_nights"><?php echo JText::_('VBPARAMSEASONCALNIGHTS'); ?></label> </td>
							<td><input type="text" name="seasoncal_nights" id="seasoncal_nights" size="10" value="" placeholder="1, 3, 7, 14"/><span><?php echo JText::_('VBPARAMSEASONCALNIGHTSHELP'); ?></span></td>
						</tr>
						<tr class="vbroomparamp param-seasoncal" style="display: none;">
							<td width="200" class="vbo-config-param-cell"> <label for="seasoncal_prices"><?php echo JText::_('VBPARAMSEASONCALENDARPRICES'); ?></label> </td>
							<td><select name="seasoncal_prices" id="seasoncal_prices"><option value="0"><?php echo JText::_('VBPARAMSEASONCALENDARPRICESANY'); ?></option><option value="1"><?php echo JText::_('VBPARAMSEASONCALENDARPRICESLOW'); ?></option></select></td>
						</tr>
						<tr class="vbroomparamp param-seasoncal" style="display: none;">
							<td width="200" class="vbo-config-param-cell"> <label for="seasoncal_restr"><?php echo JText::_('VBPARAMSEASONCALENDARLOS'); ?></label> </td>
							<td><select name="seasoncal_restr" id="seasoncal_restr"><option value="0"><?php echo JText::_('VBPARAMSEASONCALENDARLOSHIDE'); ?></option><option value="1"><?php echo JText::_('VBPARAMSEASONCALENDARLOSSHOW'); ?></option></select></td>
						</tr>
						<tr class="vbroomparamp param-multiunits" style="display: none;">
							<td width="200" class="vbo-config-param-cell"> <label for="multi_units"><?php echo JText::_('VBPARAMROOMMULTIUNITS'); ?></label> </td>
							<td><select name="multi_units" id="multi_units"><option value="0"><?php echo JText::_('VBPARAMROOMMULTIUNITSDISABLE'); ?></option><option value="1"><?php echo JText::_('VBPARAMROOMMULTIUNITSENABLE'); ?></option></select><span><?php echo JText::_('VBPARAMROOMMULTIUNITSHELP'); ?></span></td>
						</tr>
						<tr class="vbroomparamp param-sef vbroomparampactive">
							<td width="200" class="vbo-config-param-cell"> <label for="sefalias"><?php echo JText::_('VBROOMSEFALIAS'); ?></label> </td>
							<td><input type="text" id="sefalias" name="sefalias" value="" placeholder="double-room-superior"/></td>
						</tr>
						<tr class="vbroomparamp param-sef vbroomparampactive">
							<td width="200" class="vbo-config-param-cell"> <label for="custptitle"><?php echo JText::_('VBPARAMPAGETITLE'); ?></label> </td>
							<td><input type="text" id="custptitle" name="custptitle" value=""/> <span><select name="custptitlew"><option value="before"><?php echo JText::_('VBPARAMPAGETITLEBEFORECUR'); ?></option><option value="after"><?php echo JText::_('VBPARAMPAGETITLEAFTERCUR'); ?></option><option value="replace"><?php echo JText::_('VBPARAMPAGETITLEREPLACECUR'); ?></option></select></span></td>
						</tr>
						<tr class="vbroomparamp param-sef vbroomparampactive">
							<td width="200" class="vbo-config-param-cell"> <label for="metakeywords"><?php echo JText::_('VBPARAMKEYWORDSMETATAG'); ?></label> </td>
							<td><textarea name="metakeywords" id="metakeywords" rows="3" cols="40"></textarea></td>
						</tr>
						<tr class="vbroomparamp param-sef vbroomparampactive">
							<td width="200" class="vbo-config-param-cell"> <label for="metadescription"><?php echo JText::_('VBPARAMDESCRIPTIONMETATAG'); ?></label> </td>
							<td><textarea name="metadescription" id="metadescription" rows="4" cols="40"></textarea></td>
						</tr>
					</tbody>
				</table>
			</fieldset>
		
			<input type="hidden" name="task" value="">
			<input type="hidden" name="option" value="<?php echo $option; ?>">

		</form>
		<?php
	}
	
	public static function pEditRoom ($row, $cats, $carats, $optionals, $adultsdiff, $option) {
		JHTML::_('behavior.modal');
		JHTML::_('behavior.tooltip');
		$vbo_app = new VikApplication();
		$currencysymb=vikbooking::getCurrencySymb(true);
		$arrcats=array();
		$arrcarats=array();
		$arropts=array();
		$oldcats=explode(";", $row['idcat']);
		foreach($oldcats as $oc){
			if (!empty($oc)) {
				$arrcats[$oc]=$oc;
			}
		}
		$oldcarats=explode(";", $row['idcarat']);
		foreach($oldcarats as $ocr){
			if (!empty($ocr)) {
				$arrcarats[$ocr]=$ocr;
			}
		}
		$oldopts=explode(";", $row['idopt']);
		foreach($oldopts as $oopt){
			if (!empty($oopt)) {
				$arropts[$oopt]=$oopt;
			}
		}
		if (is_array($cats)) {
			$wcats="<tr><td width=\"200\" class=\"vbo-config-param-cell\"> <b>".JText::_('VBNEWROOMONE')."</b> </td><td>";
			$wcats.="<select name=\"ccat[]\" multiple=\"multiple\" size=\"".(count($cats) + 1)."\">";
			foreach($cats as $cat){
				$wcats.="<option value=\"".$cat['id']."\"".(array_key_exists($cat['id'], $arrcats) ? " selected=\"selected\"" : "").">".$cat['name']."</option>\n";
			}
			$wcats.="</select></td></tr>\n";
		}else {
			$wcats="";
		}
		if (is_array($carats)) {
			$wcarats="<tr><td width=\"200\" class=\"vbo-config-param-cell\"> <b>".JText::_('VBNEWROOMTHREE').":</b> </td><td>";
			$wcarats.="<div class=\"vbo-roomentries-cont\">";
			$nn=0;
			foreach($carats as $kcarat => $carat){
				$wcarats.="<div class=\"vbo-roomentry-cont\"><input type=\"checkbox\" name=\"ccarat[]\" id=\"carat".$kcarat."\" value=\"".$carat['id']."\"".(array_key_exists($carat['id'], $arrcarats) ? " checked=\"checked\"" : "")."/> <label for=\"carat".$kcarat."\">".$carat['name']."</label></div>\n";
				$nn++;
				if (($nn % 3) == 0) {
					$wcarats.="</div>\n<div class=\"vbo-roomentries-cont\">";
				}
			}
			$wcarats.="</div>\n";
			$wcarats.="</td></tr>\n";
		}else {
			$wcarats="";
		}
		if (is_array($optionals)) {
			$woptionals="<tr><td width=\"200\" class=\"vbo-config-param-cell\"> <b>".JText::_('VBNEWROOMFOUR').":</b> </td><td>";
			$woptionals.="<div class=\"vbo-roomentries-cont\">";
			$nn=0;
			foreach($optionals as $kopt => $optional){
				$woptionals.="<div class=\"vbo-roomentry-cont\"><input type=\"checkbox\" name=\"coptional[]\" id=\"opt".$kopt."\" value=\"".$optional['id']."\"".(array_key_exists($optional['id'], $arropts) ? " checked=\"checked\"" : "")."/> <label for=\"opt".$kopt."\">".$optional['name']." ".(empty($optional['ageintervals']) ? $currencysymb."".$optional['cost'] : "")."</label></div>\n";
				$nn++;
				if (($nn % 3) == 0) {
					$woptionals.="</div>\n<div class=\"vbo-roomentries-cont\">";
				}
			}
			$woptionals.="</div>\n";
			$woptionals.="</td></tr>\n";
		}else {
			$woptionals="";
		}
		//more images
		$morei=explode(';;', $row['moreimgs']);
		$actmoreimgs="";
		if(@count($morei) > 0) {
			$notemptymoreim=false;
			$imgcaptions = json_decode($row['imgcaptions'], true);
			$usecaptions = empty($imgcaptions) || is_null($imgcaptions) || !is_array($imgcaptions) || !(count($imgcaptions) > 0) ? false : true;
			foreach($morei as $ki => $mi) {
				if(!empty($mi)) {
					$notemptymoreim=true;
					$actmoreimgs.='<div class="vbo-editroom-currentphoto">';
					$actmoreimgs.='<a href="'.JURI::root().'components/com_vikbooking/resources/uploads/big_'.$mi.'" target="_blank" class="modal"><img src="'.JURI::root().'components/com_vikbooking/resources/uploads/thumb_'.$mi.'" class="maxfifty"/></a>';
					$actmoreimgs.='<a class="vbo-toggle-imgcaption" href="javascript: void(0);" onclick="vbOpenImgDetails(\''.$ki.'\')"><img src="./components/com_vikbooking/resources/settings.png" style="border: 0; width: 25px;"/></a>';
					$actmoreimgs.='<div id="vbimgdetbox'.$ki.'" class="vbimagedetbox" style="display: none;"><div class="captionlabel"><span>'.JText::_('VBIMGCAPTION').'</span><input type="text" name="caption'.$ki.'" value="'.($usecaptions === true ? $imgcaptions[$ki] : "").'" size="40"/></div><input class="captionsubmit" type="button" name="updcatpion" value="'.JText::_('VBIMGUPDATE').'" onclick="javascript: updateCaptions();"/><div class="captionremoveimg"><a class="vbimgrm btn btn-danger" href="index.php?option=com_vikbooking&task=removemoreimgs&roomid='.$row['id'].'&imgind='.$ki.'" title="'.JText::_('VBREMOVEIMG').'"><i class="icon-remove"></i>'.JText::_('VBREMOVEIMG').'</a></div></div>';
					$actmoreimgs.='</div>';
				}
			}
			if($notemptymoreim) {
				$actmoreimgs.='<br clear="all"/>';
			}
		}
		//end more images
		//num adults charges/discounts only if the max numb of adults allowed is > than 1 and the minimum is less than the maximum 
		$writeadultsdiff = false;
		if ($row['toadult'] > 1 && $row['fromadult'] < $row['toadult']) {
			$writeadultsdiff = true;
			$stradultsdiff = "";
			$startadind = $row['fromadult'] > 0 ? $row['fromadult'] : 1;
			$parseadultsdiff = array();
			if(@is_array($adultsdiff)) {
				foreach($adultsdiff as $adiff) {
					$parseadultsdiff[$adiff['adults']]=$adiff;
				}
			}
			for($adi = $startadind; $adi <= $row['toadult']; $adi++) {
				$stradultsdiff .= "<p>";
				$stradultsdiff .= JText::sprintf('VBADULTSDIFFNUM', $adi)." <select name=\"adultsdiffchdisc[]\"><option value=\"1\"".(array_key_exists($adi, $parseadultsdiff) && $parseadultsdiff[$adi]['chdisc'] == 1 ? " selected=\"selected\"" : "").">".JText::_('VBADULTSDIFFCHDISCONE')."</option><option value=\"2\"".(array_key_exists($adi, $parseadultsdiff) && $parseadultsdiff[$adi]['chdisc'] == 2 ? " selected=\"selected\"" : "").">".JText::_('VBADULTSDIFFCHDISCTWO')."</option></select>\n";
				$stradultsdiff .= "<input type=\"text\" name=\"adultsdiffval[]\" value=\"".(array_key_exists($adi, $parseadultsdiff) ? $parseadultsdiff[$adi]['value'] : "")."\" size=\"3\" style=\"width: 40px;\"/><input type=\"hidden\" name=\"adultsdiffnum[]\" value=\"".$adi."\"/>\n";
				$stradultsdiff .= "<select name=\"adultsdiffvalpcent[]\"><option value=\"1\"".(array_key_exists($adi, $parseadultsdiff) && $parseadultsdiff[$adi]['valpcent'] == 1 ? " selected=\"selected\"" : "").">".$currencysymb."</option><option value=\"2\"".(array_key_exists($adi, $parseadultsdiff) && $parseadultsdiff[$adi]['valpcent'] == 2 ? " selected=\"selected\"" : "").">%</option></select>\n";
				$stradultsdiff .= "<select name=\"adultsdiffpernight[]\"><option value=\"0\"".(array_key_exists($adi, $parseadultsdiff) && $parseadultsdiff[$adi]['pernight'] == 0 ? " selected=\"selected\"" : "").">".JText::_('VBADULTSDIFFONTOTAL')."</option><option value=\"1\"".(array_key_exists($adi, $parseadultsdiff) && $parseadultsdiff[$adi]['pernight'] == 1 ? " selected=\"selected\"" : "").">".JText::_('VBADULTSDIFFONPERNIGHT')."</option></select>\n";
				$stradultsdiff .= "</p>\n";
			}
		}
		//
		$editor = JFactory::getEditor();
		?>
		<script type="text/javascript">
		//Code to debug the size of the form to be submitted in case it will exceed the PHP post_max_size
		/*
		Joomla.submitbutton = function(task) {
			console.log(jQuery("#adminForm").not("[type='file']").serialize().length);
			Joomla.submitform(task, document.adminForm);
		}
		*/
		function showResizeSel() {
			if(document.adminForm.autoresize.checked == true) {
				document.getElementById('resizesel').style.display='block';
			}else {
				document.getElementById('resizesel').style.display='none';
			}
			return true;
		}
		function showResizeSelMore() {
			if(document.adminForm.autoresizemore.checked == true) {
				document.getElementById('resizeselmore').style.display='block';
			}else {
				document.getElementById('resizeselmore').style.display='none';
			}
			return true;
		}
		function addMoreImages() {
			var ni = document.getElementById('myDiv');
			var numi = document.getElementById('moreimagescounter');
			var num = (document.getElementById('moreimagescounter').value -1)+ 2;
			numi.value = num;
			var newdiv = document.createElement('div');
			var divIdName = 'my'+num+'Div';
			newdiv.setAttribute('id',divIdName);
			newdiv.innerHTML = '<input type=\'file\' name=\'cimgmore[]\' size=\'35\'/> <span><?php echo addslashes(JText::_('VBIMGCAPTION')); ?></span> <input type=\'text\' name=\'cimgcaption[]\' size=\'30\' value=\'\'/><br/>';
			ni.appendChild(newdiv);
		}
		function vbPlusMinus(what, how) {
			var inp = document.getElementById(what);
			var actval = inp.value;
			var newval = 0;
			if(how == 'plus') {
				newval = parseInt(actval) + 1;
			}else {
				if(parseInt(actval) >= 1) {
					newval = parseInt(actval) - 1;
				}
			}
			inp.value = newval;
			<?php
			if($writeadultsdiff == true) {
				?>
				var origfrom = <?php echo $row['fromadult']; ?>;
				var origto = <?php echo $row['toadult']; ?>;
				if(what == 'fromadult') {
					if(newval == origfrom) {
						document.getElementById('vbadultsdiffsavemess').style.display = 'none';
						document.getElementById('vbadultsdiffbox').style.display = 'block';
					}else {
						document.getElementById('vbadultsdiffbox').style.display = 'none';
						document.getElementById('vbadultsdiffsavemess').style.display = 'block';
					}
				}
				if(what == 'toadult') {
					if(newval == origto) {
						document.getElementById('vbadultsdiffsavemess').style.display = 'none';
						document.getElementById('vbadultsdiffbox').style.display = 'block';
					}else {
						document.getElementById('vbadultsdiffbox').style.display = 'none';
						document.getElementById('vbadultsdiffsavemess').style.display = 'block';
					}
				}
				<?php
			}
			?>
			if(what == 'toadult' || what == 'tochild') {
				vbMaxTotPeople();
			}
			if(what == 'fromadult' || what == 'fromchild') {
				vbMinTotPeople();
			}
			return true;
		}
		function vbMaxTotPeople() {
			var toadu = document.getElementById('toadult').value;
			var tochi = document.getElementById('tochild').value;
			document.getElementById('totpeople').value = parseInt(toadu) + parseInt(tochi);
			return true;
		}
		function vbMinTotPeople() {
			var fadu = document.getElementById('fromadult').value;
			var fchi = document.getElementById('fromchild').value;
			document.getElementById('mintotpeople').value = parseInt(fadu) + parseInt(fchi);
			return true;
		}
		function togglePriceCalendarParam() {
			if(parseInt(document.getElementById('pricecal').value) == 1) {
				document.getElementById('defcalcostp').style.display = 'table-row';
				jQuery('.param-pricecal').addClass("vbroomparampactive");
			}else {
				document.getElementById('defcalcostp').style.display = 'none';
				jQuery('.param-pricecal').removeClass("vbroomparampactive");
			}
		}
		function toggleSeasonalCalendarParam() {
			if(parseInt(document.getElementById('seasoncal').value) > 0) {
				jQuery('.param-seasoncal').addClass("vbroomparampactive").show();
			}else {
				jQuery('.param-seasoncal').removeClass("vbroomparampactive");
				jQuery('.param-seasoncal').each(function(k, v) {
					if(k > 0) {
						jQuery(this).hide();
					}
				});
			}
		}
		var vbo_details_on = false;
		function vbOpenImgDetails(key) {
			if(vbo_details_on === true) {
				jQuery('.vbimagedetbox').not('#vbimgdetbox'+key).hide();
			}
			if(document.getElementById('vbimgdetbox'+key).style.display == 'none') {
				document.getElementById('vbimgdetbox'+key).style.display = 'block';
				vbo_details_on = true;
			}else {
				document.getElementById('vbimgdetbox'+key).style.display = 'none';
				vbo_details_on = false;
			}
		}
		function updateCaptions() {
			var ni = document.adminForm;
			var newdiv = document.createElement('div');
			newdiv.innerHTML = '<input type=\'hidden\' name=\'updatecaption\' value=\'1\'/>';
			ni.appendChild(newdiv);
			document.adminForm.task.value='updateroom';
			document.adminForm.submit();
		}
		/* Start - Room Disctinctive Features */
		var cur_units = <?php echo $row['units']; ?>;
		jQuery(document).ready(function() {
			jQuery('#vbo-distfeatures-toggle').click(function() {
				jQuery(this).toggleClass('btn-primary');
				jQuery('.vbo-distfeatures-cont').fadeToggle();
			});
			jQuery('#room_units').change(function() {
				var to_units = parseInt(jQuery(this).val());
				if(to_units > 1) {
					jQuery('.param-multiunits').show();
					jQuery('.vbo-distfeature-row').css('display', 'table-row');
				}else {
					jQuery('.param-multiunits').hide();
					jQuery('.vbo-distfeature-row').css('display', 'none');
				}
				if(to_units > cur_units) {
					var diff_units = (to_units - cur_units);
					for (var i = 1; i <= diff_units; i++) {
						var unit_html = "<div class=\"vbo-runit-features-cont\" id=\"runit-features-"+(i + cur_units)+"\">"+
										"	<span class=\"vbo-runit-num\"><?php echo addslashes(JText::_('VBODISTFEATURERUNIT')); ?>"+(i + cur_units)+"</span>"+
										"	<div class=\"vbo-runit-features\">"+
										"		<div class=\"vbo-runit-feature\">"+
										"			<input type=\"text\" name=\"feature-name"+(i + cur_units)+"[]\" value=\"\" size=\"20\" placeholder=\"<?php echo JText::_('VBODISTFEATURETXT'); ?>\"/>"+
										"			<input type=\"hidden\" name=\"feature-lang"+(i + cur_units)+"[]\" value=\"\"/>"+
										"			<input type=\"text\" name=\"feature-value"+(i + cur_units)+"[]\" value=\"\" size=\"20\" placeholder=\"<?php echo JText::_('VBODISTFEATUREVAL'); ?>\"/>"+
										"			<span class=\"vbo-feature-remove\">&nbsp;</span>"+
										"		</div>"+
										"		<span class=\"vbo-feature-add btn\"><i class=\"icon-new\"></i><?php echo addslashes(JText::_('VBODISTFEATUREADD')); ?></span>"+
										"	</div>"+
										"</div>";
						jQuery('.vbo-distfeatures-cont').append(unit_html);
					}
					cur_units = to_units;
				}else if(to_units < cur_units) {
					for (var i = cur_units; i > to_units; i--) {
						jQuery('#runit-features-'+i).remove();
					}
					cur_units = to_units;
				}
			});
		});
		jQuery(document.body).on('click', '.vbo-feature-add', function() {
			var cfeature_id = jQuery(this).parent('div').parent('div').attr('id').split('runit-features-');
			if(cfeature_id[1].length) {
				jQuery(this).before("<div class=\"vbo-runit-feature\">"+
									"	<input type=\"text\" name=\"feature-name"+cfeature_id[1]+"[]\" value=\"\" size=\"20\" placeholder=\"<?php echo JText::_('VBODISTFEATURETXT'); ?>\"/>"+
									"	<input type=\"hidden\" name=\"feature-lang"+cfeature_id[1]+"[]\" value=\"\"/>"+
									"	<input type=\"text\" name=\"feature-value"+cfeature_id[1]+"[]\" value=\"\" size=\"20\" placeholder=\"<?php echo JText::_('VBODISTFEATUREVAL'); ?>\"/>"+
									"	<span class=\"vbo-feature-remove\">&nbsp;</span>"+
									"</div>"
									);
			}
		});
		jQuery(document.body).on('click', '.vbo-feature-remove', function() {
			jQuery(this).parent('div').remove();
		});
		/* End - Room Disctinctive Features */
		</script>
		<input type="hidden" value="0" id="moreimagescounter" />
		
		<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data">
			
			<fieldset class="adminform">
				<legend class="adminlegend"><?php echo JText::_('VBOROOMLEGUNITOCC'); ?></legend>
				<table cellspacing="1" class="admintable table">
					<tbody>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWROOMFIVE'); ?></b> </td>
							<td><input type="text" name="cname" value="<?php echo $row['name']; ?>" size="40"/></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWROOMEIGHT'); ?></b> </td>
							<td><?php echo $vbo_app->printYesNoButtons('cavail', JText::_('VBYES'), JText::_('VBNO'), (intval($row['avail'])==1 ? 'yes' : 0), 'yes', 0); ?></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWROOMNINE'); ?></b> </td>
							<td><input type="number" min="1" name="units" id="room_units" value="<?php echo $row['units']; ?>" size="3" onfocus="this.select();" /></td>
						</tr>
						<?php
						$room_features = vikbooking::getRoomParam('features', $row['params']);
						if(!is_array($room_features)) {
							$room_features = array();
						}
						if(!(count($room_features) > 0)) {
							$default_features = vikbooking::getDefaultDistinctiveFeatures();
							for ($i=1; $i <= $row['units']; $i++) {
								$room_features[$i] = $default_features;
							}
						}
						?>
						<tr class="vbo-distfeature-row" style="display: <?php echo $row['units'] > 1 ? 'table-row' : 'none'; ?>;">
							<td width="200" class="vbo-config-param-cell" style="vertical-align: top !important;"> <b><?php echo JText::_('VBOROOMUNITSDISTFEAT'); ?></b> </td>
							<td>
								<div class="vbo-distfeatures-toggle-cont">
									<span id="vbo-distfeatures-toggle" class="btn btn-primary"><i class="icon-eye"></i><?php echo JText::_('VBOROOMUNITSDISTFEATTOGGLE'); ?></span>
								</div>
								<div class="vbo-distfeatures-cont">
								<?php
								for ($i=1; $i <= $row['units']; $i++) {
									?>
									<div class="vbo-runit-features-cont" id="runit-features-<?php echo $i; ?>">
										<span class="vbo-runit-num"><?php echo JText::_('VBODISTFEATURERUNIT'); ?><?php echo $i; ?></span>
										<div class="vbo-runit-features">
									<?php
									if(array_key_exists($i, $room_features)) {
										foreach ($room_features[$i] as $fkey => $fval) {
											?>
											<div class="vbo-runit-feature">
												<input type="text" name="feature-name<?php echo $i; ?>[]" value="<?php echo JText::_($fkey); ?>" size="20"/>
												<input type="hidden" name="feature-lang<?php echo $i; ?>[]" value="<?php echo $fkey; ?>"/>
												<input type="text" name="feature-value<?php echo $i; ?>[]" value="<?php echo $fval; ?>" size="20"/>
												<span class="vbo-feature-remove">&nbsp;</span>
											</div>
											<?php
										}
									}
									?>
											<span class="vbo-feature-add btn"><i class="icon-new"></i><?php echo JText::_('VBODISTFEATUREADD'); ?></span>
										</div>
									</div>
									<?php
								}
								?>
								</div>
							</td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWROOMADULTS'); ?></b> </td>
							<td><div class="vbplusminuscont"><?php echo JText::_('VBNEWROOMMIN'); ?> <input type="text" id="fromadult" name="fromadult" value="<?php echo $row['fromadult']; ?>" size="3" onchange="vbMinTotPeople();" style="width: 40px;"/></div><div onclick="vbPlusMinus('fromadult', 'plus');" class="vbplusminus"></div><div onclick="vbPlusMinus('fromadult', 'minus');" class="vbminus vbplusminus"></div><br clear="all"/><div class="vbplusminuscont"><?php echo JText::_('VBNEWROOMMAX'); ?> <input type="text" id="toadult" name="toadult" value="<?php echo $row['toadult']; ?>" size="3" onchange="vbMaxTotPeople();" style="width: 40px;"/></div><div onclick="vbPlusMinus('toadult', 'plus');" class="vbplusminus"></div><div onclick="vbPlusMinus('toadult', 'minus');" class="vbminus vbplusminus"></div><br clear="all"/></td>
						</tr>
					<?php
					if($writeadultsdiff == true) {
						?>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWROOMADULTSDIFF'); ?></b><br/><?php echo JHTML::tooltip(JText::_('VBNEWROOMADULTSDIFFHELP'), JText::_('VBNEWROOMADULTSDIFF'), 'tooltip.png', ''); ?> </td>
							<td><div id="vbadultsdiffsavemess" style="display: none; width: 50%;"><i><?php echo JText::_('VBNEWROOMNOTCHANGENUMMESS'); ?></i></div><div id="vbadultsdiffbox" style="display: block;"><?php echo $stradultsdiff; ?></div></td>
						</tr>
						<?php
					}else {
						?>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWROOMADULTSDIFF'); ?></b><br/><?php echo JHTML::tooltip(JText::_('VBNEWROOMADULTSDIFFHELP'), JText::_('VBNEWROOMADULTSDIFF'), 'tooltip.png', ''); ?> </td>
							<td><div style="display: block; width: 50%;"><i><?php echo JText::_('VBNEWROOMADULTSDIFFBEFSAVE'); ?></i></div></td>
						</tr>
						<?php
					}
					?>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWROOMCHILDREN'); ?></b> </td>
							<td><div class="vbplusminuscont"><?php echo JText::_('VBNEWROOMMIN'); ?> <input type="text" id="fromchild" name="fromchild" value="<?php echo $row['fromchild']; ?>" size="3" onchange="vbMinTotPeople();" style="width: 40px;"/></div><div onclick="vbPlusMinus('fromchild', 'plus');" class="vbplusminus"></div><div onclick="vbPlusMinus('fromchild', 'minus');" class="vbminus vbplusminus"></div><br clear="all"/><div class="vbplusminuscont"><?php echo JText::_('VBNEWROOMMAX'); ?> <input type="text" id="tochild" name="tochild" value="<?php echo $row['tochild']; ?>" size="3" onchange="vbMaxTotPeople();" style="width: 40px;"/></div><div onclick="vbPlusMinus('tochild', 'plus');" class="vbplusminus"></div><div onclick="vbPlusMinus('tochild', 'minus');" class="vbminus vbplusminus"></div><br clear="all"/></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBMAXTOTPEOPLE'); ?></b> </td>
							<td><input type="text" name="totpeople" id="totpeople" value="<?php echo $row['totpeople']; ?>" size="3" style="width: 40px;"/> <i><?php echo JText::_('VBMAXTOTPEOPLEDESC'); ?></i></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBMINTOTPEOPLE'); ?></b> </td>
							<td><input type="text" name="mintotpeople" id="mintotpeople" value="<?php echo $row['mintotpeople']; ?>" size="3" style="width: 40px;"/> <i><?php echo JText::_('VBMINTOTPEOPLEDESC'); ?></i></td>
						</tr>
					</tbody>
				</table>
			</fieldset>

			<fieldset class="adminform">
				<legend class="adminlegend"><?php echo JText::_('VBOROOMLEGPHOTODESC'); ?></legend>
				<table cellspacing="1" class="admintable table">
					<tbody>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWROOMSIX'); ?></b> </td>
							<td><?php echo (!empty($row['img']) && file_exists('../components/com_vikbooking/resources/uploads/'.$row['img']) ? '<a href="'.JURI::root().'components/com_vikbooking/resources/uploads/'.$row['img'].'" class="modal" target="_blank">'.$row['img'].'</a> &nbsp;' : ""); ?><input type="file" name="cimg" size="35"/><br/><label style="display: inline;" for="autoresize"><?php echo JText::_('VBNEWOPTNINE'); ?></label> <input type="checkbox" id="autoresize" name="autoresize" value="1" onclick="showResizeSel();"/> <span id="resizesel" style="display: none;">&nbsp;<?php echo JText::_('VBNEWOPTTEN'); ?>: <input type="text" name="resizeto" value="250" size="3"/> px</span></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBMOREIMAGES'); ?></b><br/><a href="javascript: void(0);" onclick="addMoreImages();" class="btn"><i class="icon-new"></i><?php echo JText::_('VBADDIMAGES'); ?></a><div class="vbo-bulkupload-cont"><div class="vbo-bulkupload-inner"><a href="javascript: void(0);" onclick="showBulkUpload();" class="btn"><i class="icon-image"></i><?php echo JText::_('VBOBULKUPLOAD'); ?></a></div></div></td>
							<td><div class="vbo-rmphotos-cont"><a class="btn btn-danger" href="index.php?option=com_vikbooking&amp;task=removemoreimgs&amp;roomid=<?php echo $row['id']; ?>&amp;imgind=-1" onclick="return confirm('<?php echo addslashes(JText::_('VBORMALLPHOTOS')); ?>?');"><i class="icon-cancel"></i><?php echo JText::_('VBORMALLPHOTOS'); ?></a></div><div class="vbo-editroom-currentphotos"><?php echo $actmoreimgs; ?></div><div class="vbo-first-imgup"><input type="file" name="cimgmore[]" size="35"/> <span><?php echo JText::_('VBIMGCAPTION'); ?></span> <input type="text" name="cimgcaption[]" size="30" value=""/></div><div id="myDiv" style="display: block;"></div><label style="display: inline;" for="autoresizemore"><?php echo JText::_('VBRESIZEIMAGES'); ?></label> <input type="checkbox" id="autoresizemore" name="autoresizemore" value="1" onclick="showResizeSelMore();"/> <span id="resizeselmore" style="display: none;">&nbsp;<?php echo JText::_('VBNEWOPTTEN'); ?>: <input type="text" name="resizetomore" value="600" size="3"/> px</span></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWROOMSMALLDESC'); ?></b> </td>
							<td><textarea name="smalldesc" rows="6" cols="50"><?php echo $row['smalldesc']; ?></textarea></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell" style="vertical-align: top !important;"> <b><?php echo JText::_('VBNEWROOMSEVEN'); ?></b> </td>
							<td><?php echo $editor->display( "cdescr", $row['info'], 400, 200, 70, 20 ); ?></td>
						</tr>
					</tbody>
				</table>
			</fieldset>

			<fieldset class="adminform">
				<legend class="adminlegend"><?php echo JText::_('VBOROOMLEGCARATCATOPT'); ?></legend>
				<table cellspacing="1" class="admintable table">
					<tbody>
						<?php echo $wcats; ?>
						<?php echo $wcarats; ?>
						<?php echo $woptionals; ?>
					</tbody>
				</table>
			</fieldset>

			<fieldset class="adminform">
				<legend class="adminlegend"><?php echo JText::_('VBNEWROOMPARAMS'); ?></legend>
				<table cellspacing="1" class="admintable table">
					<tbody>
						<tr class="vbroomparamp">
							<td width="200" class="vbo-config-param-cell"> <label for="lastavail"><?php echo JText::_('VBPARAMLASTAVAIL'); ?></label> </td>
							<td><input type="text" name="lastavail" id="lastavail" value="<?php echo vikbooking::getRoomParam('lastavail', $row['params']); ?>" size="2"/><span><?php echo JText::_('VBPARAMLASTAVAILHELP'); ?></span></td>
						</tr>
						<tr class="vbroomparamp">
							<td width="200" class="vbo-config-param-cell"> <label for="custprice"><?php echo JText::_('VBPARAMCUSTPRICE'); ?></label> </td>
							<td><input type="text" name="custprice" id="custprice" value="<?php echo vikbooking::getRoomParam('custprice', $row['params']); ?>" size="5"/><span><?php echo JText::_('VBPARAMCUSTPRICEHELP'); ?></span></td>
						</tr>
						<tr class="vbroomparamp">
							<td width="200" class="vbo-config-param-cell"> <label for="custpricetxt"><?php echo JText::_('VBPARAMCUSTPRICETEXT'); ?></label> </td>
							<td><input type="text" name="custpricetxt" id="custpricetxt" value="<?php echo vikbooking::getRoomParam('custpricetxt', $row['params']); ?>" size="9"/><span><?php echo JText::_('VBPARAMCUSTPRICETEXTHELP'); ?></span></td>
						</tr>
						<tr class="vbroomparamp">
							<td width="200" class="vbo-config-param-cell"> <label for="reqinfo"><?php echo JText::_('VBORPARAMREQINFO'); ?></label> </td>
							<td><?php echo $vbo_app->printYesNoButtons('reqinfo', JText::_('VBYES'), JText::_('VBNO'), (intval(vikbooking::getRoomParam('reqinfo', $row['params'])) == 1 ? 1 : 0), 1, 0); ?></td>
						</tr>
						<?php
						$paramshowpeople = vikbooking::getRoomParam('maxminpeople', $row['params']);
						?>
						<tr class="vbroomparamp">
							<td width="200" class="vbo-config-param-cell"> <label for="maxminpeople"><?php echo JText::_('VBPARAMSHOWPEOPLE'); ?></label> </td>
							<td><select name="maxminpeople" id="maxminpeople"><option value="0"><?php echo JText::_('VBPARAMSHOWPEOPLENO'); ?></option><option value="1"<?php echo ($paramshowpeople == "1" ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBPARAMSHOWPEOPLEADU'); ?></option><option value="2"<?php echo ($paramshowpeople == "2" ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBPARAMSHOWPEOPLECHI'); ?></option><option value="3"<?php echo ($paramshowpeople == "3" ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBPARAMSHOWPEOPLEADUTOT'); ?></option><option value="4"<?php echo ($paramshowpeople == "4" ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBPARAMSHOWPEOPLECHITOT'); ?></option><option value="5"<?php echo ($paramshowpeople == "5" ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBPARAMSHOWPEOPLEALLTOT'); ?></option></select></td>
						</tr>
						<tr class="vbroomparamp param-pricecal">
							<td width="200" class="vbo-config-param-cell"> <label for="pricecal"><?php echo JText::_('VBPARAMPRICECALENDAR'); ?></label> </td>
							<td><select name="pricecal" id="pricecal" onchange="togglePriceCalendarParam();"><option value="0"><?php echo JText::_('VBPARAMPRICECALENDARDISABLED'); ?></option><option value="1"<?php echo (intval(vikbooking::getRoomParam('pricecal', $row['params'])) == 1 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBPARAMPRICECALENDARENABLED'); ?></option></select><span><?php echo JText::_('VBPARAMPRICECALENDARHELP'); ?></span></td>
						</tr>
						<tr class="vbroomparamp param-pricecal" id="defcalcostp" style="display: <?php echo (intval(vikbooking::getRoomParam('pricecal', $row['params'])) == 1 ? 'table-row' : 'none'); ?>;">
							<td width="200" class="vbo-config-param-cell"> <label for="defcalcost"><?php echo JText::_('VBPARAMDEFCALCOST'); ?></label> </td>
							<td><input type="text" name="defcalcost" id="defcalcost" size="4" value="<?php echo vikbooking::getRoomParam('defcalcost', $row['params']); ?>" placeholder="50.00"/><span><?php echo JText::_('VBPARAMDEFCALCOSTHELP'); ?></span></td>
						</tr>
						<?php
						$season_cal = vikbooking::getRoomParam('seasoncal', $row['params']);
						$season_cal_prices = vikbooking::getRoomParam('seasoncal_prices', $row['params']);
						$season_cal_restr = vikbooking::getRoomParam('seasoncal_restr', $row['params']);
						?>
						<tr class="vbroomparamp param-seasoncal">
							<td width="200" class="vbo-config-param-cell"> <label for="seasoncal"><?php echo JText::_('VBPARAMSEASONCALENDAR'); ?></label> </td>
							<td><select name="seasoncal" id="seasoncal" onchange="toggleSeasonalCalendarParam();"><option value="0"><?php echo JText::_('VBPARAMSEASONCALENDARDISABLED'); ?></option><optgroup label="<?php echo JText::_('VBPARAMSEASONCALENDARENABLED'); ?>"><option value="1"<?php echo intval($season_cal) == 1 ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBPARAMSEASONCALENDARENABLEDALL'); ?></option><option value="2"<?php echo intval($season_cal) == 2 ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBPARAMSEASONCALENDARENABLEDCHARGEDISC'); ?></option><option value="3"<?php echo intval($season_cal) == 3 ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBPARAMSEASONCALENDARENABLEDCHARGE'); ?></option></optgroup></select></td>
						</tr>
						<tr class="vbroomparamp param-seasoncal" style="display: <?php echo (intval($season_cal) > 0 ? 'table-row' : 'none'); ?>;">
							<td width="200" class="vbo-config-param-cell"> <label for="seasoncal_nights"><?php echo JText::_('VBPARAMSEASONCALNIGHTS'); ?></label> </td>
							<td><input type="text" name="seasoncal_nights" id="seasoncal_nights" size="10" value="<?php echo vikbooking::getRoomParam('seasoncal_nights', $row['params']); ?>" placeholder="1, 3, 7, 14"/><span><?php echo JText::_('VBPARAMSEASONCALNIGHTSHELP'); ?></span></td>
						</tr>
						<tr class="vbroomparamp param-seasoncal" style="display: <?php echo (intval($season_cal) > 0 ? 'table-row' : 'none'); ?>;">
							<td width="200" class="vbo-config-param-cell"> <label for="seasoncal_prices"><?php echo JText::_('VBPARAMSEASONCALENDARPRICES'); ?></label> </td>
							<td><select name="seasoncal_prices" id="seasoncal_prices"><option value="0"><?php echo JText::_('VBPARAMSEASONCALENDARPRICESANY'); ?></option><option value="1"<?php echo intval($season_cal_prices) == 1 ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBPARAMSEASONCALENDARPRICESLOW'); ?></option></select></td>
						</tr>
						<tr class="vbroomparamp param-seasoncal" style="display: <?php echo (intval($season_cal) > 0 ? 'table-row' : 'none'); ?>;">
							<td width="200" class="vbo-config-param-cell"> <label for="seasoncal_restr"><?php echo JText::_('VBPARAMSEASONCALENDARLOS'); ?></label> </td>
							<td><select name="seasoncal_restr" id="seasoncal_restr"><option value="0"><?php echo JText::_('VBPARAMSEASONCALENDARLOSHIDE'); ?></option><option value="1"<?php echo intval($season_cal_restr) == 1 ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBPARAMSEASONCALENDARLOSSHOW'); ?></option></select></td>
						</tr>
						<?php
						$multi_units = vikbooking::getRoomParam('multi_units', $row['params']);
						?>
						<tr class="vbroomparamp param-multiunits" style="display: <?php echo ($row['units'] > 0 ? 'table-row' : 'none'); ?>;">
							<td width="200" class="vbo-config-param-cell"> <label for="multi_units"><?php echo JText::_('VBPARAMROOMMULTIUNITS'); ?></label> </td>
							<td><select name="multi_units" id="multi_units"><option value="0"><?php echo JText::_('VBPARAMROOMMULTIUNITSDISABLE'); ?></option><option value="1"<?php echo intval($multi_units) == 1 ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBPARAMROOMMULTIUNITSENABLE'); ?></option></select><span><?php echo JText::_('VBPARAMROOMMULTIUNITSHELP'); ?></span></td>
						</tr>
						<?php
						$custptitle = vikbooking::getRoomParam('custptitle', $row['params']);
						$custptitlew = vikbooking::getRoomParam('custptitlew', $row['params']);
						$metakeywords = vikbooking::getRoomParam('metakeywords', $row['params']);
						$metadescription = vikbooking::getRoomParam('metadescription', $row['params']);
						?>
						<tr class="vbroomparamp param-sef vbroomparampactive">
							<td width="200" class="vbo-config-param-cell"> <label for="sefalias"><?php echo JText::_('VBROOMSEFALIAS'); ?></label> </td>
							<td><input type="text" id="sefalias" name="sefalias" value="<?php echo $row['alias']; ?>" placeholder="double-room-superior"/></td>
						</tr>
						<tr class="vbroomparamp param-sef vbroomparampactive">
							<td width="200" class="vbo-config-param-cell"> <label for="custptitle"><?php echo JText::_('VBPARAMPAGETITLE'); ?></label> </td>
							<td><input type="text" id="custptitle" name="custptitle" value="<?php echo $custptitle; ?>"/> <span><select name="custptitlew"><option value="before"<?php echo $custptitlew == 'before' ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBPARAMPAGETITLEBEFORECUR'); ?></option><option value="after"<?php echo $custptitlew == 'after' ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBPARAMPAGETITLEAFTERCUR'); ?></option><option value="replace"<?php echo $custptitlew == 'replace' ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBPARAMPAGETITLEREPLACECUR'); ?></option></select></span></td>
						</tr>
						<tr class="vbroomparamp param-sef vbroomparampactive">
							<td width="200" class="vbo-config-param-cell"> <label for="metakeywords"><?php echo JText::_('VBPARAMKEYWORDSMETATAG'); ?></label> </td>
							<td><textarea name="metakeywords" id="metakeywords" rows="3" cols="40"><?php echo $metakeywords; ?></textarea></td>
						</tr>
						<tr class="vbroomparamp param-sef vbroomparampactive">
							<td width="200" class="vbo-config-param-cell"> <label for="metadescription"><?php echo JText::_('VBPARAMDESCRIPTIONMETATAG'); ?></label> </td>
							<td><textarea name="metadescription" id="metadescription" rows="4" cols="40"><?php echo $metadescription; ?></textarea></td>
						</tr>
					</tbody>
				</table>
			</fieldset>

			<input type="hidden" name="task" value="">
			<input type="hidden" name="whereup" value="<?php echo $row['id']; ?>">
			<input type="hidden" name="actmoreimgs" id="actmoreimgs" value="<?php echo $row['moreimgs']; ?>">
			<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>

		<div class="vbo-info-overlay-block">
			<div class="vbo-info-overlay-content">
				<!-- The fileinput-button span is used to style the file input field as button -->
				<span class="btn btn-success fileinput-button">
					<i class="icon-new"></i>
					<span><?php echo JText::_('VBOSELORDRAGFILES'); ?></span>
					<!-- The file input field used as target for the file upload widget -->
					<input id="fileupload" type="file" name="bulkphotos[]" multiple>
				</span>
				<br>
				<br>
				<!-- The global progress bar -->
				<div id="progress" class="progress">
					<div class="progress-bar"></div>
				</div>
				<!-- The container for the uploaded files -->
				<div id="files" class="files"></div>
				<br clear="all"/>
				<div class="vbo-upload-done">
					<button type="button" class="btn" onclick="vboCloseModal();"><i class="icon-save"></i><?php echo JText::_('VBOUPLOADFILEDONE'); ?></button>
				</div>
			</div>
		</div>
		<script src="<?php echo JURI::root(); ?>administrator/components/com_vikbooking/resources/js_upload/jquery.ui.widget.js"></script>
		<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
		<script src="<?php echo JURI::root(); ?>administrator/components/com_vikbooking/resources/js_upload/load-image.all.min.js"></script>
		<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
		<script src="<?php echo JURI::root(); ?>administrator/components/com_vikbooking/resources/js_upload/jquery.iframe-transport.js"></script>
		<!-- The basic File Upload plugin -->
		<script src="<?php echo JURI::root(); ?>administrator/components/com_vikbooking/resources/js_upload/jquery.fileupload.js"></script>
		<!-- The File Upload processing plugin -->
		<script src="<?php echo JURI::root(); ?>administrator/components/com_vikbooking/resources/js_upload/jquery.fileupload-process.js"></script>
		<!-- The File Upload image preview & resize plugin -->
		<script src="<?php echo JURI::root(); ?>administrator/components/com_vikbooking/resources/js_upload/jquery.fileupload-image.js"></script>
		<!-- The File Upload validation plugin -->
		<script src="<?php echo JURI::root(); ?>administrator/components/com_vikbooking/resources/js_upload/jquery.fileupload-validate.js"></script>

		<script type="text/javascript">
		togglePriceCalendarParam();toggleSeasonalCalendarParam();
		var vbo_overlay_on = false;
		function showBulkUpload() {
			jQuery(".vbo-info-overlay-block").fadeIn();
			vbo_overlay_on = true;
		}
		function vboCloseModal() {
			jQuery(".vbo-info-overlay-block").fadeOut(400, function() {
				jQuery(this).attr("class", "vbo-info-overlay-block");
			});
			vbo_overlay_on = false;
		}
		jQuery(document).ready(function(){
			jQuery(document).mouseup(function(e) {
				if(!vbo_overlay_on) {
					return false;
				}
				var vbo_overlay_cont = jQuery(".vbo-info-overlay-content");
				if(!vbo_overlay_cont.is(e.target) && vbo_overlay_cont.has(e.target).length === 0) {
					vboCloseModal();
				}
			});
			jQuery(document).keyup(function(e) {
				if (e.keyCode == 27) {
					if(vbo_overlay_on) {
						vboCloseModal();
					}
					if(vbo_details_on) {
						vbo_details_on = false;
						jQuery('.vbimagedetbox').hide();
					}
				}
			});
		});
		jQuery(function () {
			'use strict';
			var url = 'index.php?option=com_vikbooking&task=multiphotosupload&roomid=<?php echo $row['id']; ?>',
				uploadButton = jQuery('<button/>')
					.addClass('btn btn-primary')
					.prop('disabled', true)
					.text('Processing...')
					.on('click', function () {
						var $this = jQuery(this),
							data = $this.data();
						$this
							.off('click')
							.text('Abort')
							.on('click', function () {
								$this.remove();
								data.abort();
							});
						data.submit().always(function () {
							$this.remove();
						});
					});
			jQuery('#fileupload').fileupload({
				url: url,
				dataType: 'json',
				autoUpload: true,
				acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
				maxFileSize: 999000,
				disableImageResize: true,
				previewMaxWidth: 100,
				previewMaxHeight: 100,
				previewCrop: true
			}).on('fileuploadadd', function (e, data) {
				data.context = jQuery('<div/>').addClass('vbo-upload-photo').appendTo('#files');
				jQuery.each(data.files, function (index, file) {
					var node = jQuery('<p/>')
							.append(jQuery('<span/>').text(file.name));
					if (!index) {
						node
							.append('<br>')
							.append(uploadButton.clone(true).data(data));
					}
					node.appendTo(data.context);
				});
			}).on('fileuploadprocessalways', function (e, data) {
				var index = data.index,
					file = data.files[index],
					node = jQuery(data.context.children()[index]);
				if (file.preview) {
					node
						.prepend('<br>')
						.prepend(file.preview);
				}
				if (file.error) {
					node
						.append('<br>')
						.append(jQuery('<span class="text-danger"/>').text(file.error));
				}
				if (index + 1 === data.files.length) {
					data.context.find('button')
						.text('Upload')
						.prop('disabled', !!data.files.error);
				}
			}).on('fileuploadprogressall', function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				jQuery('#progress .progress-bar').css(
					'width',
					progress + '%'
				);
				if(progress > 99) {
					jQuery('#progress .progress-bar').addClass("progress-bar-success");
				}else {
					if(jQuery('#progress .progress-bar').hasClass("progress-bar-success")){
						jQuery('#progress .progress-bar').removeClass("progress-bar-success");
					} 
				}
			}).on('fileuploaddone', function (e, data) {
				jQuery.each(data.result.files, function (index, file) {
					if (file.url) {
						var link = jQuery('<a>')
							.attr('target', '_blank')
							.attr('class', 'modal')
							.prop('href', file.url);
						jQuery(data.context.children()[index])
							.wrap(link);
						if(typeof SqueezeBox != "undefined") {
							SqueezeBox.assign('.modal', {});
						}
						data.context.find('button')
							.hide();
						jQuery('.vbo-upload-done')
							.fadeIn();
					} else if (file.error) {
						var error = jQuery('<span class="text-danger"/>').text(file.error);
						jQuery(data.context.children()[index])
							.append('<br>')
							.append(error);
					}else {
						jQuery(data.context.children()[index])
							.append('<br>')
							.append('Generic Error.');
					}
				});
				if(data.result.hasOwnProperty('actmoreimgs')) {
					jQuery('#actmoreimgs').val(data.result.actmoreimgs);
				}
				if(data.result.hasOwnProperty('currentthumbs')) {
					jQuery('.vbo-editroom-currentphotos').html(data.result.currentthumbs);
				}
			}).on('fileuploadfail', function (e, data) {
				jQuery.each(data.files, function (index) {
					var error = jQuery('<span class="text-danger"/>').text('File upload failed.');
					jQuery(data.context.children()[index])
						.append('<br>')
						.append(error);
				});
			}).prop('disabled', !jQuery.support.fileInput)
				.parent().addClass(jQuery.support.fileInput ? undefined : 'disabled');
		});
		if(jQuery.isFunction(jQuery.fn.tooltip)) {
			jQuery(".hasTooltip").tooltip();
		}
		</script>
		<?php
	}
	
	public static function pViewTariffe ($roomrows, $rows, $option) {
		
		if(empty($rows)){
			?>
			<p class="warn"><?php echo JText::_('VBNOTARFOUND'); ?></p>
			<form name="adminForm" id="adminForm" action="index.php" method="post">
			<input type="hidden" name="task" value="">
			<input type="hidden" name="option" value="<?php echo $option; ?>">
			</form>
			<?php
		}else{
			$mainframe = JFactory::getApplication();
			$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', 15, 'int');
			$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
			$allpr = array();
			$tottar = array();
			foreach($rows as $r){
				if (!array_key_exists($r['idprice'], $allpr)) {
					$allpr[$r['idprice']]=vikbooking::getPriceAttr($r['idprice']);
				}
				$tottar[$r['days']][]=$r;
			}
			$prord = array();
			$prvar = '';
			foreach($allpr as $kap=>$ap){
				$prord[]=$kap;
				$prvar.="<th class=\"title center\" width=\"150\">".vikbooking::getPriceName($kap).(!empty($ap) ? " - ".$ap : "")."</th>\n";
			}
			$totrows = count($tottar);
			$tottar = array_slice($tottar, $lim0, $lim, true);
			?>
	<script type="text/javascript">
	function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'removetariffe') {
				if (confirm('<?php echo JText::_('VBJSDELTAR'); ?> ?')){
					submitform( pressbutton );
					return;
				}else{
					return false;
				}
			}

			// do field validation
			try {
				document.adminForm.onsubmit();
			}
			catch(e){}
			submitform( pressbutton );
	}
	function vbRateSetTask(event) {
		event.preventDefault();
		document.getElementById('vbtarmod').value = '1';
		document.getElementById('vbtask').value = 'rooms';
		document.adminForm.submit();
	}
	</script>
	<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

	<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped">
		<thead>
		<tr>
			<th class="title left" width="100" style="text-align: left;"><?php echo JText::_( 'VBPVIEWTARONE' ); ?></th>
			<?php echo $prvar; ?>
			<th width="20" class="title right" style="text-align: right;">
				<input type="submit" name="modtar" value="<?php echo JText::_( 'VBPVIEWTARTWO' ); ?>" onclick="vbRateSetTask(event);"/> &nbsp; <input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
			</th>
		</tr>
		</thead>
		<?php
		$k = 0;
		$i = 0;
		foreach($tottar as $kt=>$vt){
			
			?>
			<tr class="row<?php echo $k; ?>">
				<td class="left"><?php echo $kt; ?></td>
			<?php
			foreach($prord as $ord){
				$thereis=false;
				foreach($vt as $kkkt=>$vvv){
					if ($vvv['idprice']==$ord) {
						$multiid.=$vvv['id'].";";
						echo "<td class=\"center\"><input type=\"text\" name=\"cost".$vvv['id']."\" value=\"".$vvv['cost']."\" size=\"5\"/>".(!empty($vvv['attrdata'])? " - <input type=\"text\" name=\"attr".$vvv['id']."\" value=\"".$vvv['attrdata']."\" size=\"10\"/>" : "")."</td>\n";
						$thereis=true;
						break;
					}
				}
				
				if (!$thereis) {
					echo "<td></td>\n";
				}
				unset($thereis);
				
			}
			
			?>
			<td class="right" style="text-align: right;"><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $multiid; ?>" onclick="Joomla.isChecked(this.checked);"></td>
			</tr>
			<?php
			unset($multiid);
			$k = 1 - $k;
			$i++;
		}
		
		?>
		
		</table>
		<input type="hidden" name="roomid" value="<?php echo $roomrows['id']; ?>" />
		<input type="hidden" name="cid[]" value="<?php echo $roomrows['id']; ?>" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" id="vbtask" value="viewtariffe" />
		<input type="hidden" name="tarmod" id="vbtarmod" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $totrows, $lim0, $lim );
		$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		echo $navbut;
		?>
	</form>
	<?php
		}
		
	}
	
	public static function pViewConfigOne () {
		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.calendar');
		$vbo_app = new VikApplication();
		$timeopst=vikbooking::getTimeOpenStore(true);
		if (is_array($timeopst)) {
			$openat=vikbooking::getHoursMinutes($timeopst[0]);
			$closeat=vikbooking::getHoursMinutes($timeopst[1]);
		}else {
			$openat = "";
			$closeat = "";
		}
		$wcheckintime = "<select name=\"timeopenstorefh\">\n";
		for($i=0; $i <= 23; $i++){
			if ($i < 10) {
				$in="0".$i;
			}else {
				$in=$i;
			}
			$stat=($openat[0]==$i ? " selected=\"selected\"" : "");
			$wcheckintime.="<option value=\"".$i."\"".$stat.">".$in."</option>\n";
		}
		$wcheckintime.="</select> <select name=\"timeopenstorefm\">\n";
		for($i=0; $i <= 59; $i++){
			if ($i < 10) {
				$in="0".$i;
			}else {
				$in=$i;
			}
			$stat=($openat[1]==$i ? " selected=\"selected\"" : "");
			$wcheckintime.="<option value=\"".$i."\"".$stat.">".$in."</option>\n";
		}
		$wcheckintime.="</select>\n";
		$wcheckouttime = "<select name=\"timeopenstoreth\">\n";
		for($i=0; $i <= 23; $i++){
			if ($i < 10) {
				$in="0".$i;
			}else {
				$in=$i;
			}
			$stat=($closeat[0]==$i ? " selected=\"selected\"" : "");
			$wcheckouttime.="<option value=\"".$i."\"".$stat.">".$in."</option>\n";
		}
		$wcheckouttime.="</select> <select name=\"timeopenstoretm\">\n";
		for($i=0; $i <= 59; $i++){
			if ($i < 10) {
				$in="0".$i;
			}else {
				$in=$i;
			}
			$stat=($closeat[1]==$i ? " selected=\"selected\"" : "");
			$wcheckouttime.="<option value=\"".$i."\"".$stat.">".$in."</option>\n";
		}
		$wcheckouttime.="</select>\n";
		
		$calendartype = vikbooking::calendarType(true);
		
		$globnumadults = vikbooking::getSearchNumAdults(true);
		$adultsparts = explode('-', $globnumadults);
		$globnumchildren = vikbooking::getSearchNumChildren(true);
		$childrenparts = explode('-', $globnumchildren);
		
		$maxdatefuture = vikbooking::getMaxDateFuture(true);
		$maxdate_val = intval(substr($maxdatefuture, 1, (strlen($maxdatefuture) - 1)));
		$maxdate_interval = substr($maxdatefuture, -1, 1);
		
		$smartseach_type = vikbooking::getSmartSearchType(true);
		
		$vbosef = file_exists(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'router.php');

		$nowdf = vikbooking::getDateFormat(true);
		if ($nowdf=="%d/%m/%Y") {
			$usedf='d/m/Y';
		}elseif ($nowdf=="%m/%d/%Y") {
			$usedf='m/d/Y';
		}else {
			$usedf='Y/m/d';
		}
		?>
		<script type="text/javascript">
		function vbFlushSession() {
			if(confirm('<?php echo addslashes(JText::_('VBCONFIGFLUSHSESSIONCONF')); ?>')) {
				location.href='<?php echo JURI::root(); ?>administrator/index.php?option=com_vikbooking&task=renewsession';
			}else {
				return false;
			}
		}
		function vboRemoveElement(el) {
			return (elem=document.getElementById(el)).parentNode.removeChild(elem);
		}
		function vboAddClosingDate() {
			var cdfrom = document.getElementById('cdfrom').value;
			var cdto = document.getElementById('cdto').value;
			if(cdfrom.length && cdto.length) {
				var cdcounter = document.getElementsByClassName('vbo-closed-date-entry').length + 1;
				var cdstring = "<div class=\"vbo-closed-date-entry\" id=\"vbo-closed-date-entry"+cdcounter+"\"><span>"+cdfrom+"</span> - <span>"+cdto+"</span> <span class=\"vbo-closed-date-rm\" onclick=\"vboRemoveElement('vbo-closed-date-entry"+cdcounter+"');\"><i class=\"vboicn-cross\"></i> </span><input type=\"hidden\" name=\"cdsfrom[]\" value=\""+cdfrom+"\" /><input type=\"hidden\" name=\"cdsto[]\" value=\""+cdto+"\" /></div>";
				document.getElementById('vbo-config-closed-dates').innerHTML += cdstring;
				document.getElementById('cdfrom').value = '';
				document.getElementById('cdto').value = '';
			}
		}
		</script>
		<a href="javascript: void(0);" class="vbflushsession" onclick="vbFlushSession();"><?php echo JText::_('VBCONFIGFLUSHSESSION'); ?></a>

		<fieldset class="adminform">
			<legend class="adminlegend"><?php echo JText::_('VBOCPARAMBOOKING'); ?></legend>
			<table cellspacing="1" class="admintable table">
				<tbody>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGONEFIVE'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('allowbooking', JText::_('VBYES'), JText::_('VBNO'), (int)vikbooking::allowBooking(), 1, 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGONESIX'); ?></b> </td>
						<td><textarea name="disabledbookingmsg" rows="5" cols="50"><?php echo vikbooking::getDisabledBookingMsg(); ?></textarea></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGONETENSIX'); ?></b> </td>
						<td><input type="text" name="adminemail" value="<?php echo vikbooking::getAdminMail(); ?>" size="35"/></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBSENDEREMAIL'); ?></b> </td>
						<td><input type="text" name="senderemail" value="<?php echo vikbooking::getSenderMail(); ?>" size="35"/></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGONESEVEN'); ?></b> </td>
						<td><?php echo $wcheckintime; ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGONETHREE'); ?></b> </td>
						<td><?php echo $wcheckouttime; ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGONEELEVEN'); ?></b> </td>
						<td>
							<select name="dateformat">
								<option value="%d/%m/%Y"<?php echo ($nowdf=="%d/%m/%Y" ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBCONFIGONETWELVE'); ?></option>
								<option value="%m/%d/%Y"<?php echo ($nowdf=="%m/%d/%Y" ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBCONFIGONEMDY'); ?></option>
								<option value="%Y/%m/%d"<?php echo ($nowdf=="%Y/%m/%d" ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBCONFIGONETENTHREE'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTODAYBOOKINGS'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('todaybookings', JText::_('VBYES'), JText::_('VBNO'), (int)vikbooking::todayBookings(), 1, 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGONECOUPONS'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('enablecoupons', JText::_('VBYES'), JText::_('VBNO'), (int)vikbooking::couponsEnabled(), 1, 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGENABLECUSTOMERPIN'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('enablepin', JText::_('VBYES'), JText::_('VBNO'), (int)vikbooking::customersPinEnabled(), 1, 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGONETENFIVE'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('tokenform', JText::_('VBYES'), JText::_('VBNO'), (vikbooking::tokenForm() ? 'yes' : 0), 'yes', 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGREQUIRELOGIN'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('requirelogin', JText::_('VBYES'), JText::_('VBNO'), (int)vikbooking::requireLogin(), 1, 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGAUTODISTFEATURE'); ?></b><br/><?php echo JHTML::tooltip(JText::_('VBCONFIGAUTODISTFEATUREHELP'), JText::_('VBCONFIGAUTODISTFEATURE'), 'tooltip.png', ''); ?></td>
						<td><?php echo $vbo_app->printYesNoButtons('autoroomunit', JText::_('VBYES'), JText::_('VBNO'), (vikbooking::autoRoomUnit() ? 1 : 0), 1, 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGONETENSEVEN'); ?></b> </td>
						<td><input type="text" name="minuteslock" value="<?php echo vikbooking::getMinutesLock(); ?>" size="3"/></td>
					</tr>
				</tbody>
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend class="adminlegend"><?php echo JText::_('VBCONFIGSEARCHPARAMS'); ?></legend>
			<table cellspacing="1" class="admintable table">
				<tbody>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGMINDAYSADVANCE'); ?></b> </td>
						<td><input type="text" name="mindaysadvance" value="<?php echo vikbooking::getMinDaysAdvance(true); ?>" size="2"/></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGSEARCHDEFNIGHTS'); ?></b> </td>
						<td><input type="text" name="autodefcalnights" value="<?php echo vikbooking::getDefaultNightsCalendar(true); ?>" size="2"/></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGSEARCHPNUMROOM'); ?></b> </td>
						<td><input type="text" name="numrooms" value="<?php echo vikbooking::getSearchNumRooms(true); ?>" size="2"/></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGSEARCHPNUMADULTS'); ?></b> </td>
						<td><?php echo JText::_('VBCONFIGSEARCHPFROM'); ?> <input type="text" name="numadultsfrom" value="<?php echo $adultsparts[0]; ?>" size="2"/> &nbsp;&nbsp; <?php echo JText::_('VBCONFIGSEARCHPTO'); ?> <input type="text" name="numadultsto" value="<?php echo $adultsparts[1]; ?>" size="2"/></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGSEARCHPNUMCHILDREN'); ?></b> </td>
						<td><?php echo JText::_('VBCONFIGSEARCHPFROM'); ?> <input type="text" name="numchildrenfrom" value="<?php echo $childrenparts[0]; ?>" size="2"/> &nbsp;&nbsp; <?php echo JText::_('VBCONFIGSEARCHPTO'); ?> <input type="text" name="numchildrento" value="<?php echo $childrenparts[1]; ?>" size="2"/></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGSEARCHPMAXDATEFUT'); ?></b> </td>
						<td><input type="text" name="maxdate" value="<?php echo $maxdate_val; ?>" size="2"/> <select name="maxdateinterval"><option value="d"<?php echo $maxdate_interval == 'd' ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBCONFIGSEARCHPMAXDATEDAYS'); ?></option><option value="w"<?php echo $maxdate_interval == 'w' ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBCONFIGSEARCHPMAXDATEWEEKS'); ?></option><option value="m"<?php echo $maxdate_interval == 'm' ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBCONFIGSEARCHPMAXDATEMONTHS'); ?></option><option value="y"<?php echo $maxdate_interval == 'y' ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBCONFIGSEARCHPMAXDATEYEARS'); ?></option></select></td>
					</tr>

					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGCLOSINGDATES'); ?></b> </td>
						<td>
							<div style="width: 100%; display: inline-block;" class="btn-toolbar" id="filter-bar">
								<div class="btn-group pull-left">
									<?php echo JHTML::_('calendar', '', 'cdfrom', 'cdfrom', $nowdf, array('class'=>'', 'size'=>'10',  'maxlength'=>'19', 'placeholder' => JText::_('VBCONFIGCLOSINGDATEFROM'))); ?>
								</div>
								<div class="btn-group pull-left">
									<?php echo JHTML::_('calendar', '', 'cdto', 'cdto', $nowdf, array('class'=>'', 'size'=>'10',  'maxlength'=>'19', 'placeholder' => JText::_('VBCONFIGCLOSINGDATETO'))); ?>
								</div>
								<div class="btn-group pull-left">
									<button type="button" class="btn" onclick="vboAddClosingDate();"><i class="icon-new"></i><?php echo JText::_('VBCONFIGCLOSINGDATEADD'); ?></button>
								</div>
							</div>
							<div id="vbo-config-closed-dates" style="display: block;">
						<?php
						$cur_closed_dates = vikbooking::getClosingDates();
						if(is_array($cur_closed_dates) && count($cur_closed_dates)) {
							foreach ($cur_closed_dates as $kcd => $vcd) {
								echo "<div class=\"vbo-closed-date-entry\" id=\"vbo-closed-date-entry".$kcd."\"><span>".date($usedf, $vcd['from'])."</span> - <span>".date($usedf, $vcd['to'])."</span> <span class=\"vbo-closed-date-rm\" onclick=\"vboRemoveElement('vbo-closed-date-entry".$kcd."');\"><i class=\"vboicn-cross\"></i> </span><input type=\"hidden\" name=\"cdsfrom[]\" value=\"".date($usedf, $vcd['from'])."\" /><input type=\"hidden\" name=\"cdsto[]\" value=\"".date($usedf, $vcd['to'])."\" /></div>"."\n";
							}
						}
						?>
							</div>
						</td>
					</tr>

					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGSEARCHPSMARTSEARCH'); ?></b> </td>
						<td><select name="smartsearch"><option value="dynamic"<?php echo $smartseach_type == 'dynamic' ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBCONFIGSEARCHPSMARTSEARCHDYN'); ?></option><option value="automatic"<?php echo $smartseach_type == 'automatic' ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBCONFIGSEARCHPSMARTSEARCHAUTO'); ?></option></select></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGONETENFOUR'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('showcategories', JText::_('VBYES'), JText::_('VBNO'), (vikbooking::showCategoriesFront(true) ? 'yes' : 0), 'yes', 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGSHOWCHILDREN'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('showchildren', JText::_('VBYES'), JText::_('VBNO'), (vikbooking::showChildrenFront(true) ? 'yes' : 0), 'yes', 0); ?></td>
					</tr>
				</tbody>
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend class="adminlegend"><?php echo JText::_('VBOCPARAMSYSTEM'); ?></legend>
			<table cellspacing="1" class="admintable table">
				<tbody>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGCRONKEY'); ?></b> </td>
						<td><input type="text" name="cronkey" value="<?php echo vikbooking::getCronKey(); ?>" size="6" /></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGMULTILANG'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('multilang', JText::_('VBYES'), JText::_('VBNO'), (int)vikbooking::allowMultiLanguage(true), 1, 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGROUTER'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('vbosef', JText::_('VBYES'), JText::_('VBNO'), (int)$vbosef, 1, 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBLOADBOOTSTRAP'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('loadbootstrap', JText::_('VBYES'), JText::_('VBNO'), (int)vikbooking::loadBootstrap(true), 1, 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGONEJQUERY'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('loadjquery', JText::_('VBYES'), JText::_('VBNO'), (vikbooking::loadJquery(true) ? 'yes' : 0), 'yes', 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGONECALENDAR'); ?></b> </td>
						<td><select name="calendar"><option value="jqueryui"<?php echo ($calendartype == "jqueryui" ? " selected=\"selected\"" : ""); ?>>jQuery UI</option><option value="joomla"<?php echo ($calendartype == "joomla" ? " selected=\"selected\"" : ""); ?>>Joomla</option></select></td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		<?php
	}
	
	public static function pViewConfigTwo () {
		$vbo_app = new VikApplication();
		$formatvals = vikbooking::getNumberFormatData(true);
		$formatparts = explode(':', $formatvals);
		?>
		<fieldset class="adminform">
			<legend class="adminlegend"><?php echo JText::_('VBOCPARAMCURRENCY'); ?></legend>
			<table cellspacing="1" class="admintable table">
				<tbody>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTHREECURNAME'); ?></b> </td>
						<td><input type="text" name="currencyname" value="<?php echo vikbooking::getCurrencyName(); ?>" size="10"/></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTHREECURSYMB'); ?></b> </td>
						<td><input type="text" name="currencysymb" value="<?php echo vikbooking::getCurrencySymb(true); ?>" size="10"/></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTHREECURCODEPP'); ?></b> </td>
						<td><input type="text" name="currencycodepp" value="<?php echo vikbooking::getCurrencyCodePp(); ?>" size="10"/></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGNUMDECIMALS'); ?></b> </td>
						<td><input type="text" name="numdecimals" value="<?php echo $formatparts[0]; ?>" size="2"/></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGNUMDECSEPARATOR'); ?></b> </td>
						<td><input type="text" name="decseparator" value="<?php echo $formatparts[1]; ?>" size="2"/></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGNUMTHOSEPARATOR'); ?></b> </td>
						<td><input type="text" name="thoseparator" value="<?php echo $formatparts[2]; ?>" size="2"/></td>
					</tr>
				</tbody>
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend class="adminlegend"><?php echo JText::_('VBOCPARAMTAXPAY'); ?></legend>
			<table cellspacing="1" class="admintable table">
				<tbody>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTWOFIVE'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('ivainclusa', JText::_('VBYES'), JText::_('VBNO'), (vikbooking::ivaInclusa(true) ? 'yes' : 0), 'yes', 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTAXSUMMARY'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('taxsummary', JText::_('VBYES'), JText::_('VBNO'), (vikbooking::showTaxOnSummaryOnly(true) ? 'yes' : 0), 'yes', 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGMULTIPAY'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('multipay', JText::_('VBYES'), JText::_('VBNO'), (vikbooking::multiplePayments() ? 'yes' : 0), 'yes', 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTWOTHREE'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('paytotal', JText::_('VBYES'), JText::_('VBNO'), (vikbooking::payTotal() ? 'yes' : 0), 'yes', 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTWOFOUR'); ?></b> </td>
						<td><input type="text" name="payaccpercent" value="<?php echo vikbooking::getAccPerCent(); ?>" size="5"/> <select name="typedeposit"><option value="pcent">%</option><option value="fixed"<?php echo (vikbooking::getTypeDeposit(true) == "fixed" ? ' selected="selected"' : ''); ?>><?php echo vikbooking::getCurrencySymb(); ?></option></select></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTWOSIX'); ?></b> </td>
						<td><input type="text" name="paymentname" value="<?php echo vikbooking::getPaymentName(); ?>" size="25"/></td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		<?php
		
	}
	
	public static function pViewConfigThree () {
		$vbo_app = new VikApplication();
		$editor = JFactory::getEditor();
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root().'components/com_vikbooking/resources/jquery.fancybox.css');
		JHtml::_('script', JURI::root().'components/com_vikbooking/resources/jquery.fancybox.js', false, true, false, false);
		$themesel = '<select name="theme">';
		$themesel .= '<option value="default">default</option>';
		$themes = glob(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'themes'.DS.'*');
		$acttheme = vikbooking::getTheme();
		if(count($themes) > 0) {
			$strip = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'themes'.DS;
			foreach($themes as $th) {
				if(is_dir($th)) {
					$tname = str_replace($strip, '', $th);
					if($tname != 'default') {
						$themesel .= '<option value="'.$tname.'"'.($tname == $acttheme ? ' selected="selected"' : '').'>'.$tname.'</option>';
					}
				}
			}
		}
		$themesel .= '</select>';
		$firstwday = vikbooking::getFirstWeekDay(true);
		?>
		<fieldset class="adminform">
			<legend class="adminlegend"><?php echo JText::_('VBOCPARAMLAYOUT'); ?></legend>
			<table cellspacing="1" class="admintable table">
				<tbody>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGFIRSTWDAY'); ?></b> </td>
						<td><select name="firstwday"><option value="0"<?php echo $firstwday == '0' ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBSUNDAY'); ?></option><option value="1"<?php echo $firstwday == '1' ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBMONDAY'); ?></option><option value="2"<?php echo $firstwday == '2' ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBTUESDAY'); ?></option><option value="3"<?php echo $firstwday == '3' ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBWEDNESDAY'); ?></option><option value="4"<?php echo $firstwday == '4' ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBTHURSDAY'); ?></option><option value="5"<?php echo $firstwday == '5' ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBFRIDAY'); ?></option><option value="6"<?php echo $firstwday == '6' ? ' selected="selected"' : ''; ?>><?php echo JText::_('VBSATURDAY'); ?></option></select></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTHREETEN'); ?></b> </td>
						<td><input type="text" name="numcalendars" value="<?php echo vikbooking::numCalendars(); ?>" size="10"/></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTHREENINE'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('showpartlyreserved', JText::_('VBYES'), JText::_('VBNO'), (vikbooking::showPartlyReserved() ? 'yes' : 0), 'yes', 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTHREECHECKINOUTSTAT'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('showcheckinoutonly', JText::_('VBYES'), JText::_('VBNO'), (vikbooking::showStatusCheckinoutOnly() ? 1 : 0), 1, 0); ?></td>
					</tr>

					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBOCONFIGEMAILTEMPLATE'); ?></b> </td>
						<td><button type="button" class="btn vbo-edit-tmpl" data-tmpl-path="<?php echo urlencode(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'email_tmpl.php'); ?>"><i class="icon-edit"></i> <?php echo JText::_('VBOCONFIGEDITTMPLFILE'); ?></button></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBOCONFIGINVOICETEMPLATE'); ?></b> </td>
						<td><button type="button" class="btn vbo-edit-tmpl" data-tmpl-path="<?php echo urlencode(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'invoices'.DS.'invoice_tmpl.php'); ?>"><i class="icon-edit"></i> <?php echo JText::_('VBOCONFIGEDITTMPLFILE'); ?></button></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBOCONFIGCUSTCSSTPL'); ?></b> </td>
						<td><button type="button" class="btn vbo-edit-tmpl" data-tmpl-path="<?php echo urlencode(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'vikbooking_custom.css'); ?>"><i class="icon-edit"></i> <?php echo JText::_('VBOCONFIGEDITTMPLFILE'); ?></button></td>
					</tr>

					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTHREEONE'); ?></b> </td>
						<td><input type="text" name="fronttitle" value="<?php echo vikbooking::getFrontTitle(); ?>" size="30"/></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTHREETWO'); ?></b> </td>
						<td><input type="text" name="fronttitletag" value="<?php echo vikbooking::getFrontTitleTag(); ?>" size="10"/></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTHREETHREE'); ?></b> </td>
						<td><input type="text" name="fronttitletagclass" value="<?php echo vikbooking::getFrontTitleTagClass(); ?>" size="10"/></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTHREEFOUR'); ?></b> </td>
						<td><input type="text" name="searchbtnval" value="<?php echo vikbooking::getSubmitName(true); ?>" size="10"/></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTHREEFIVE'); ?></b> </td>
						<td><input type="text" name="searchbtnclass" value="<?php echo vikbooking::getSubmitClass(true); ?>" size="10"/></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTHREESIX'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('showfooter', JText::_('VBYES'), JText::_('VBNO'), (vikbooking::showFooter() ? 'yes' : 0), 'yes', 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGTHEME'); ?></b> </td>
						<td><?php echo $themesel; ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell" style="vertical-align: top !important;"> <b><?php echo JText::_('VBCONFIGTHREESEVEN'); ?></b> </td>
						<td><?php echo $editor->display( "intromain", vikbooking::getIntroMain(), 500, 350, 70, 20 ); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell" style="vertical-align: top !important;"> <b><?php echo JText::_('VBCONFIGTHREEEIGHT'); ?></b> </td>
						<td><textarea name="closingmain" rows="5" cols="60" style="min-width: 400px;"><?php echo vikbooking::getClosingMain(); ?></textarea></td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery(".vbo-edit-tmpl").click(function(){
				var vbo_tmpl_path = jQuery(this).attr("data-tmpl-path");
				jQuery.fancybox({
					"helpers": {
						"overlay": {
							"locked": false
						}
					},
					"href": "index.php?option=com_vikbooking&task=edittmplfile&path="+vbo_tmpl_path+"&tmpl=component",
					"width": "75%",
					"height": "75%",
					"autoScale": false,
					"transitionIn": "none",
					"transitionOut": "none",
					//"padding": 0,
					"type": "iframe"
				});
			});
		});
		</script>
		<?php
		
	}
	
	public static function pViewConfigFour () {
		$vbo_app = new VikApplication();
		$editor = JFactory::getEditor();
		JHTML::_('behavior.modal');
		$sitelogo = vikbooking::getSiteLogo();
		?>
		<fieldset class="adminform">
			<legend class="adminlegend"><?php echo JText::_('VBOCPARAMCOMPANY'); ?></legend>
			<table cellspacing="1" class="admintable table">
				<tbody>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBUSEJUTILITY'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('sendjutility', JText::_('VBYES'), JText::_('VBNO'), (vikbooking::sendJutility() ? 'yes' : 0), 'yes', 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGFOURLOGO'); ?></b> </td>
						<td><input type="file" name="sitelogo" size="35"/> <?php echo (strlen($sitelogo) > 0 ? '<a href="'.JURI::root().'administrator/components/com_vikbooking/resources/'.$sitelogo.'" class="modal" target="_blank">'.$sitelogo.'</a>' : ''); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell" style="vertical-align: top !important;"> <b><?php echo JText::_('VBCONFIGFOURORDMAILFOOTER'); ?></b> </td>
						<td><?php echo $editor->display( "footerordmail", vikbooking::getFooterOrdMail(), 500, 350, 70, 20 ); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell" style="vertical-align: top !important;"> <b><?php echo JText::_('VBCONFIGFOURFOUR'); ?></b> </td>
						<td><textarea name="disclaimer" rows="5" cols="60"><?php echo vikbooking::getDisclaimer(); ?></textarea></td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		<?php

	}

	public static function pViewConfigFive () {
		$vbo_app = new VikApplication();
		$current_smsapi = vikbooking::getSMSAPIClass();
		$allf=glob('./components/com_vikbooking/smsapi/*.php');
		$psel="<select name=\"smsapi\" id=\"smsapifile\" onchange=\"vikLoadSMSParameters(this.value);\">\n<option value=\"\"></option>\n";
		if(@count($allf) > 0) {
			$classfiles=array();
			foreach($allf as $af) {
				$classfiles[]=str_replace('./components/com_vikbooking/smsapi/', '', $af);
			}
			sort($classfiles);
			
			foreach($classfiles as $cf) {
				$psel.="<option value=\"".$cf."\"".($cf == $current_smsapi ? ' selected="selected"' : '').">".$cf."</option>\n";
			}
		}
		$psel.="</select>";
		$sendsmsto = vikbooking::getSendSMSTo();
		?>
		<fieldset class="adminform">
			<legend class="adminlegend"><?php echo JText::_('VBOCPARAMSMS'); ?></legend>
			<table cellspacing="1" class="admintable table">
				<tbody>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGSMSCLASS'); ?></b> </td>
						<td><?php echo $psel; ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGSMSAUTOSEND'); ?></b> </td>
						<td><?php echo $vbo_app->printYesNoButtons('smsautosend', JText::_('VBYES'), JText::_('VBNO'), (vikbooking::autoSendSMSEnabled() ? 1 : 0), 1, 0); ?></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGSMSSENDTO'); ?></b> </td>
						<td>
							<span class="vbo-spblock-inline"><input type="checkbox" name="smssendto[]" value="admin" id="smssendtoadmin"<?php echo in_array('admin', $sendsmsto) ? ' checked="checked"' : ''; ?> /> <label for="smssendtoadmin"><?php echo JText::_('VBCONFIGSMSSENDTOADMIN'); ?></label></span>
							<span class="vbo-spblock-inline"><input type="checkbox" name="smssendto[]" value="customer" id="smssendtocustomer"<?php echo in_array('customer', $sendsmsto) ? ' checked="checked"' : ''; ?> /> <label for="smssendtocustomer"><?php echo JText::_('VBCONFIGSMSSENDTOCUSTOMER'); ?></label></span>
						</td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCONFIGSMSSADMINPHONE'); ?></b> </td>
						<td><input type="text" name="smsadminphone" size="20" value="<?php echo vikbooking::getSMSAdminPhone(); ?>" /></td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell" style="vertical-align: top !important;"> <b><?php echo JText::_('VBCONFIGSMSPARAMETERS'); ?></b> </td>
						<td><div id="vbo-sms-params"><?php echo !empty($current_smsapi) ? vikbooking::displaySMSParameters($current_smsapi, vikbooking::getSMSParams(false)) : ''; ?></div></td>
					</tr>
			<?php
			if(!empty($current_smsapi)) {
				require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'smsapi'.DS.$current_smsapi);
				if(method_exists('VikSmsApi', 'estimate')) {
					?>
					<tr>
						<td width="200" class="vbo-config-param-cell" style="vertical-align: top !important;"> <b><?php echo JText::_('VBCONFIGSMSREMAINBAL'); ?></b> </td>
						<td><button type="button" class="btn" onclick="vboEstimateCredit();"><i class="vboicn-coin-euro"></i><?php echo JText::_('VBCONFIGSMSESTCREDIT'); ?></button><div id="vbo-sms-balance"></div></td>
					</tr>
					<?php
				}
			}
			?>
					<tr>
						<td width="200" class="vbo-config-param-cell" style="vertical-align: top !important;"> <b><?php echo JText::_('VBCONFIGSMSADMTPL'); ?></b> </td>
						<td>
							<div class="btn-toolbar vbo-smstpl-toolbar">
								<div class="btn-group pull-left vbo-smstpl-bgroup">
									<button onclick="setSmsTplTag('smsadmintpl', '{customer_name}');" class="btn" type="button">{customer_name}</button>
									<button onclick="setSmsTplTag('smsadmintpl', '{booking_id}');" class="btn" type="button">{booking_id}</button>
									<button onclick="setSmsTplTag('smsadmintpl', '{checkin_date}');" class="btn" type="button">{checkin_date}</button>
									<button onclick="setSmsTplTag('smsadmintpl', '{checkout_date}');" class="btn" type="button">{checkout_date}</button>
									<button onclick="setSmsTplTag('smsadmintpl', '{num_nights}');" class="btn" type="button">{num_nights}</button>
									<button onclick="setSmsTplTag('smsadmintpl', '{rooms_booked}');" class="btn" type="button">{rooms_booked}</button>
									<button onclick="setSmsTplTag('smsadmintpl', '{customer_country}');" class="btn" type="button">{customer_country}</button>
									<button onclick="setSmsTplTag('smsadmintpl', '{customer_email}');" class="btn" type="button">{customer_email}</button>
									<button onclick="setSmsTplTag('smsadmintpl', '{customer_phone}');" class="btn" type="button">{customer_phone}</button>
									<button onclick="setSmsTplTag('smsadmintpl', '{tot_adults}');" class="btn" type="button">{tot_adults}</button>
									<button onclick="setSmsTplTag('smsadmintpl', '{tot_children}');" class="btn" type="button">{tot_children}</button>
									<button onclick="setSmsTplTag('smsadmintpl', '{tot_guests}');" class="btn" type="button">{tot_guests}</button>
									<button onclick="setSmsTplTag('smsadmintpl', '{total}');" class="btn" type="button">{total}</button>
									<button onclick="setSmsTplTag('smsadmintpl', '{total_paid}');" class="btn" type="button">{total_paid}</button>
									<button onclick="setSmsTplTag('smsadmintpl', '{remaining_balance}');" class="btn" type="button">{remaining_balance}</button>
								</div>
							</div>
							<div class="control vbo-smstpl-control">
								<textarea name="smsadmintpl" id="smsadmintpl" style="width: 90%; min-width: 90%; max-width: 100%; height: 100px;"><?php echo vikbooking::getSMSAdminTemplate(); ?></textarea>
							</div>
						</td>
					</tr>
					<tr>
						<td width="200" class="vbo-config-param-cell" style="vertical-align: top !important;"> <b><?php echo JText::_('VBCONFIGSMSCUSTOTPL'); ?></b> </td>
						<td>
							<div class="btn-toolbar vbo-smstpl-toolbar">
								<div class="btn-group pull-left vbo-smstpl-bgroup">
									<button onclick="setSmsTplTag('smscustomertpl', '{customer_name}');" class="btn" type="button">{customer_name}</button>
									<button onclick="setSmsTplTag('smscustomertpl', '{customer_pin}');" class="btn" type="button">{customer_pin}</button>
									<button onclick="setSmsTplTag('smscustomertpl', '{booking_id}');" class="btn" type="button">{booking_id}</button>
									<button onclick="setSmsTplTag('smscustomertpl', '{checkin_date}');" class="btn" type="button">{checkin_date}</button>
									<button onclick="setSmsTplTag('smscustomertpl', '{checkout_date}');" class="btn" type="button">{checkout_date}</button>
									<button onclick="setSmsTplTag('smscustomertpl', '{num_nights}');" class="btn" type="button">{num_nights}</button>
									<button onclick="setSmsTplTag('smscustomertpl', '{rooms_booked}');" class="btn" type="button">{rooms_booked}</button>
									<button onclick="setSmsTplTag('smscustomertpl', '{tot_adults}');" class="btn" type="button">{tot_adults}</button>
									<button onclick="setSmsTplTag('smscustomertpl', '{tot_children}');" class="btn" type="button">{tot_children}</button>
									<button onclick="setSmsTplTag('smscustomertpl', '{tot_guests}');" class="btn" type="button">{tot_guests}</button>
									<button onclick="setSmsTplTag('smscustomertpl', '{total}');" class="btn" type="button">{total}</button>
									<button onclick="setSmsTplTag('smscustomertpl', '{total_paid}');" class="btn" type="button">{total_paid}</button>
									<button onclick="setSmsTplTag('smscustomertpl', '{remaining_balance}');" class="btn" type="button">{remaining_balance}</button>
								</div>
							</div>
							<div class="control vbo-smstpl-control">
								<textarea name="smscustomertpl" id="smscustomertpl" style="width: 90%; min-width: 90%; max-width: 100%; height: 100px;"><?php echo vikbooking::getSMSCustomerTemplate(); ?></textarea>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		<script type="text/javascript">
		jQuery.noConflict();
		if(jQuery.isFunction(jQuery.fn.tooltip)) {
			jQuery(".hasTooltip").tooltip();
		}
		function setSmsTplTag(taid, tpltag) {
			var tplobj = document.getElementById(taid);
			if(tplobj != null) {
				var start = tplobj.selectionStart;
				var end = tplobj.selectionEnd;
				tplobj.value = tplobj.value.substring(0, start) + tpltag + tplobj.value.substring(end);
				tplobj.selectionStart = tplobj.selectionEnd = start + tpltag.length;
				tplobj.focus();
			}
		}
		function vikLoadSMSParameters(pfile) {
			if(pfile.length > 0) {
				jQuery("#vbo-sms-params").html('<?php echo addslashes(JTEXT::_('VIKLOADING')); ?>');
				jQuery.ajax({
					type: "POST",
					url: "index.php?option=com_vikbooking&task=loadsmsparams&tmpl=component",
					data: { phpfile: pfile }
				}).done(function(res) {
					jQuery("#vbo-sms-params").html(res);
				});
			}else {
				jQuery("#vbo-sms-params").html('--------');
			}
		}
		function vboEstimateCredit() {
			jQuery("#vbo-sms-balance").html('<?php echo addslashes(JTEXT::_('VIKLOADING')); ?>');
			jQuery.ajax({
				type: "POST",
				url: "index.php?option=com_vikbooking&task=loadsmsbalance&tmpl=component",
				data: { vbo: '1' }
			}).done(function(res) {
				jQuery("#vbo-sms-balance").html(res);
			});
		}
		</script>
		<?php

	}
	
	public static function pChooseBusy ($reservs, $totres, $pts, $option, $lim0="0", $navbut="") {
		$dbo = JFactory::getDBO();
		if (file_exists('../components/com_vikbooking/resources/uploads/'.$reservs[0]['img']) && getimagesize('../components/com_vikbooking/resources/uploads/'.$reservs[0]['img'])) {
			$img='<img align="middle" class="maxninety" style="border-radius: 5px;" alt="Room Image" src="' . JURI::root() . 'components/com_vikbooking/resources/uploads/'.$reservs[0]['img'].'" />';
		}else {
			$img='<img align="middle" alt="vikbooking logo" src="' . JURI::root() . 'administrator/components/com_vikbooking/vikbooking.jpg' . '" />';
		}
		$unitsdisp=$reservs[0]['units'] - $totres;
		$unitsdisp=($unitsdisp < 0 ? "0" : $unitsdisp);
		$pvcm = JRequest::getInt('vcm', '', 'request');
		?>
		<table class="vbo-choosebusy-table">
			<tr class="vbo-choosebusy-tr1">
				<td><div class="vbadminfaresctitle-chbusy"><?php echo JText::_('VBMAINCHOOSEBUSY'); ?> <?php echo $reservs[0]['name']; ?></div></td>
			</tr>
			<tr class="vbo-choosebusy-tr2">
				<td><?php echo $img; ?></td>
			</tr>
			<tr class="vbo-choosebusy-tr3">
				<td>
					<div class="vbadminfaresctitle-chbusy">
						<span class="label label-success"><?php echo JText::_('VBPCHOOSEBUSYCAVAIL'); ?>:</span>
						<span class="badge badge-warning"><?php echo $unitsdisp; ?> / <?php echo $reservs[0]['units']; ?></span>
					</div>
				</td>
			</tr>
		</table>
		
		<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped">
				<thead>
					<tr>
						<th class="title left" width="50">ID</th>
						<th class="title left" width="150"><?php echo JText::_( 'VBPVIEWORDERSFOUR' ); ?></th>
						<th class="title left" width="250"><?php echo JText::_( 'VBPVIEWORDERSTWO' ); ?></th>
						<th class="title left" width="150"><?php echo JText::_( 'VBPVIEWORDERSFIVE' ); ?></th>
						<th class="title center" width="150"><?php echo JText::_( 'VBOFEATUNITASSIGNED' ); ?></th>
						<th class="title left" width="150"><?php echo JText::_( 'VBPCHOOSEBUSYORDATE' ); ?></th>
					</tr>
				</thead>
			<?php
			$nowdf = vikbooking::getDateFormat(true);
			if ($nowdf=="%d/%m/%Y") {
				$df='d/m/Y';
			}elseif ($nowdf=="%m/%d/%Y") {
				$df='m/d/Y';
			}else {
				$df='Y/m/d';
			}
			$k = 0;
			$i = 0;
			$room_params = json_decode($reservs[0]['params'], true);
			$or_map = array();
			for ($i = 0, $n = count($reservs); $i < $n; $i++) {
				$row = $reservs[$i];
				//Room specific unit
				$room_first_feature = '----';
				$q = "SELECT `id`,`roomindex` FROM `#__vikbooking_ordersrooms` WHERE `idorder`=".(int)$row['idorder']." AND `idroom`=".(int)$row['idroom'].";";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if($dbo->getNumRows() > 0) {
					$roomindexes = $dbo->loadAssocList();
					$usekey = 0;
					if(array_key_exists($row['idorder'], $or_map)) {
						$usekey = count($or_map[$row['idorder']]);
						$or_map[$row['idorder']][] = $row['id'];
					}else {
						$or_map[$row['idorder']] = array($row['id']);
					}
					if(array_key_exists($usekey, $roomindexes) && is_array($room_params) && array_key_exists('features', $room_params) && count($room_params['features']) > 0) {
						foreach ($room_params['features'] as $rind => $rfeatures) {
							if($rind != $roomindexes[$usekey]['roomindex']) {
								continue;
							}
							foreach ($rfeatures as $fname => $fval) {
								if(strlen($fval)) {
									$room_first_feature = '#'.$rind.' - '.JText::_($fname).': '.$fval;
									break 2;
								}
							}
						}
					}
				}
				//
				//Customer Details
				$custdata = !empty($row['custdata']) ? substr($row['custdata'], 0, 45)." ..." : "";
				$q = "SELECT `c`.*,`co`.`idorder` FROM `#__vikbooking_customers` AS `c` LEFT JOIN `#__vikbooking_customers_orders` `co` ON `c`.`id`=`co`.`idcustomer` WHERE `co`.`idorder`=".$row['idorder'].";";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if($dbo->getNumRows() > 0) {
					$cust_country = $dbo->loadAssocList();
					$cust_country = $cust_country[0];
					if(!empty($cust_country['first_name'])) {
						$custdata = $cust_country['first_name'].' '.$cust_country['last_name'];
						if(!empty($cust_country['country'])) {
							if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'countries'.DS.$cust_country['country'].'.png')) {
								$custdata .= '<img src="'.JURI::root().'administrator/components/com_vikbooking/resources/countries/'.$cust_country['country'].'.png'.'" title="'.$cust_country['country'].'" class="vbo-country-flag vbo-country-flag-left"/>';
							}
						}
					}
				}
				$custdata = JText::_('VBDBTEXTROOMCLOSED') == $row['custdata'] ? '<span class="vbordersroomclosed">'.JText::_('VBDBTEXTROOMCLOSED').'</span>' : $custdata;
				?>
				<tr class="row<?php echo $k; ?>">
					<td><?php echo $row['idorder']; ?></td>
					<td><a href="index.php?option=com_vikbooking&amp;task=editbusy<?php echo $pvcm == 1 ? '&amp;vcm=1' : ''; ?>&amp;cid[]=<?php echo $row['idorder']; ?>"><?php echo date($df.' H:i', $row['checkin']); ?></a></td>
					<td><?php echo $custdata; ?></td>
					<td><?php echo date($df.' H:i', $row['checkout']); ?></td>
					<td style="text-align: center;"><?php echo $room_first_feature; ?></td>
					<td><?php echo date($df.' H:i', $row['ts']); ?></td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
		
			</table>
			<input type="hidden" name="idroom" value="<?php echo $reservs[0]['idroom']; ?>" />
			<input type="hidden" name="ts" value="<?php echo $pts; ?>" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="choosebusy" />
			<input type="hidden" name="boxchecked" value="0" />
			<?php echo JHTML::_( 'form.token' ); ?>
			<?php echo $navbut; ?>
		</form>
		<?php
	}
	
	public static function pShowSeasons ($rows, $roomsel, $option, $lim0="0", $navbut="") {
		$pidroom = JRequest::getInt('idroom', '', 'request');
		?>
		<div class="vbo-ratesoverview-roomsel-block">
			<form action="index.php?option=com_vikbooking" method="post" name="seasonsform">
				<div class="vbo-ratesoverview-roomsel-entry">
					<label for="idroom"><?php echo JText::_('VBRATESOVWROOM'); ?></label>
					<?php echo $roomsel; ?>
				</div>
				<input type="hidden" name="task" value="seasons" />
				<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
		</div>
		<br clear="all" />
		<?php
		if(empty($rows)){
			?>
			<p class="warn"><?php echo JText::_('VBNOSEASONS'); ?></p>
			<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
			<?php
		}else{
		
		?>
   <script type="text/javascript">
function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'removeseasons') {
				if (confirm('<?php echo JText::_('VBJSDELSEASONS'); ?> ?')){
					submitform( pressbutton );
					return;
				}else{
					return false;
				}
			}

			// do field validation
			try {
				document.adminForm.onsubmit();
			}
			catch(e){}
			submitform( pressbutton );
		}
</script>
   <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

	<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped">
		<thead>
		<tr>
			<th width="20">
				<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
			</th>
			<th class="title left" width="100"><?php echo JText::_( 'VBPSHOWSEASONSPNAME' ); ?></th>
			<th class="title center" width="100" align="center"><?php echo JText::_( 'VBPSHOWSEASONSONE' ); ?></th>
			<th class="title center" width="100" align="center"><?php echo JText::_( 'VBPSHOWSEASONSTWO' ); ?></th>
			<th class="title center" width="150" align="center"><?php echo JText::_( 'VBPSHOWSEASONSWDAYS' ); ?></th>
			<th class="title center" width="100" align="center"><?php echo JText::_( 'VBPSHOWSEASONSTHREE' ); ?></th>
			<th class="title center" width="100" align="center"><?php echo JText::_( 'VBOSEASONAFFECTEDROOMS' ); ?></th>
			<th class="title center" width="100" align="center"><?php echo JText::_( 'VBOISPROMOTION' ); ?></th>
			<th class="title center" width="100" align="center"><?php echo JText::_( 'VBPSHOWSEASONSFOUR' ); ?></th>
		</tr>
		</thead>
		<?php
		$currencysymb=vikbooking::getCurrencySymb(true);
		$nowdf = vikbooking::getDateFormat(true);
		if ($nowdf=="%d/%m/%Y") {
			$df='d/m/Y';
		}elseif ($nowdf=="%m/%d/%Y") {
			$df='m/d/Y';
		}else {
			$df='Y/m/d';
		}
		$k = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$sfrom = "";
			$sto = "";
			if($row['from'] > 0 || $row['to'] > 0) {
				$nowyear=!empty($row['year']) ? $row['year'] : date('Y');
				list($sfrom, $sto) = vikbooking::getSeasonRangeTs($row['from'], $row['to'], $nowyear);
				if(!empty($sfrom) && !empty($sto)) {
					$sfrom = date($df, $sfrom);
					$sto = date($df, $sto);
				}
			}
			$actwdays = explode(';', $row['wdays']);
			$wdaysmatch = array('0' => JText::_('VBSUNDAY'), '1' => JText::_('VBMONDAY'), '2' => JText::_('VBTUESDAY'), '3' => JText::_('VBWEDNESDAY'), '4' => JText::_('VBTHURSDAY'), '5' => JText::_('VBFRIDAY'), '6' => JText::_('VBSATURDAY'));
			$wdaystr = "";
			if(@count($actwdays) > 0) {
				foreach($actwdays as $awd) {
					if(strlen($awd) > 0) {
						$wdaystr .= substr($wdaysmatch[$awd], 0, 3).' ';
					}
				}
			}
			$aff_rooms = 0;
			$srooms = explode(',', $row['idrooms']);
			foreach ($srooms as $sroom) {
				if(!empty($sroom) && intval(str_replace('-', '', $sroom)) > 0) {
					$aff_rooms++;
				}
			}
			?>
			<tr class="row<?php echo $k; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td><a href="index.php?option=com_vikbooking&amp;task=editseason&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['spname']; ?></a></td>
				<td class="center"><?php echo $sfrom; ?></td>
				<td class="center"><?php echo $sto; ?></td>
				<td class="center"><?php echo $wdaystr; ?></td>
				<td class="center"><?php echo (intval($row['type']) == 1 ? JText::_('VBPSHOWSEASONSFIVE') : JText::_('VBPSHOWSEASONSSIX')); ?></td>
				<td class="center"><?php echo $aff_rooms; ?></td>
				<td class="center"><?php echo ($row['promo'] == 1 ? '<img src="'.JURI::root().'administrator/components/com_vikbooking/resources/ok.png"/>' : '----'); ?></td>
				<td class="center"><?php echo (intval($row['val_pcent']) == 1 ? $currencysymb.' ' : ''); ?><?php echo $row['diffcost']; ?><?php echo (intval($row['val_pcent']) == 1 ? '' : ' %'); ?></td>
			</tr>	
			<?php
			$k = 1 - $k;
			
		}
		?>
		
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<?php
		if(defined('JVERSION') && version_compare(JVERSION, '1.6.0') < 0) {
			//Joomla 1.5
			
		}
		?>
		<input type="hidden" name="task" value="seasons" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php echo $navbut; ?>
	</form>
	<?php
		}
	}
	
	public static function pNewSeason ($wsel, $wpricesel, $adults_diff, $option) {
		$vbo_app = new VikApplication();
		JHTML::_('behavior.tooltip');
		$editor = JFactory::getEditor();
		if(strlen($wsel) > 0) {
			JHTML::_('behavior.calendar');
			$df=vikbooking::getDateFormat(true);
			$currencysymb=vikbooking::getCurrencySymb(true);
			?>
			<script type="text/javascript">
			function addMoreOverrides() {
				var sel = document.getElementById('val_pcent');
				var curpcent = sel.options[sel.selectedIndex].text;
				var ni = document.getElementById('myDiv');
				var numi = document.getElementById('morevalueoverrides');
				var num = (document.getElementById('morevalueoverrides').value -1)+ 2;
				numi.value = num;
				var newdiv = document.createElement('div');
				var divIdName = 'my'+num+'Div';
				newdiv.setAttribute('id',divIdName);
				newdiv.innerHTML = '<p><?php echo addslashes(JText::_('VBNEWSEASONNIGHTSOVR')); ?> <input type=\'text\' name=\'nightsoverrides[]\' value=\'\' size=\'4\'/> <select name=\'andmoreoverride[]\'><option value=\'0\'>-------</option><option value=\'1\'><?php echo addslashes(JText::_('VBNEWSEASONVALUESOVREMORE')); ?></option></select> - <?php echo addslashes(JText::_('VBNEWSEASONVALUESOVR')); ?> <input type=\'text\' name=\'valuesoverrides[]\' value=\'\' size=\'5\'/> '+curpcent+'</p>';
				ni.appendChild(newdiv);
			}
			jQuery.noConflict();
			var rooms_sel_ids = [];
			var rooms_names_map = [];
			var rooms_adults_pricing = <?php echo json_encode($adults_diff); ?>;
			jQuery(document).ready(function() {
				var rseltag = document.getElementById("idrooms");
				for(var i=0; i < rseltag.length; i++) {
					rooms_names_map[rseltag.options[i].value] = rseltag.options[i].text;
				}
				jQuery(".vbo-select-all").click(function(){
					jQuery(this).next("select").find("option").prop('selected', true);
					jQuery(this).next("select").trigger("change");
				});
				jQuery("#idrooms").change(function(){
					if(jQuery(this).val() !== null) {
						rooms_sel_ids = jQuery(this).val();
					}else {
						rooms_sel_ids = [];
					}
					updateOccupancyPricing();
				});
				jQuery(document.body).on('click', ".occupancy-room-name", function() {
					jQuery(this).next(".occupancy-room-data").fadeToggle();
				});
			});
			function isFullObject(obj) {
				var jk;
				for(jk in obj) {
					return obj.hasOwnProperty(jk);
				}
			}
			function updateOccupancyPricing() {
				var occupancy_cont = jQuery("#vbo-occupancy-container");
				var usage_lbl = '<?php echo addslashes(JText::_('VBADULTSDIFFNUM')); ?>';
				if(rooms_sel_ids.length > 0) {
					jQuery("#vbo-occupancy-pricing-fieldset").fadeIn();
					jQuery(rooms_sel_ids).each(function(k, v){
						if(!rooms_adults_pricing.hasOwnProperty(v)) {
							return true;
						}
						if(jQuery("#occupancy-r"+v).length) {
							return true;
						}
						if(isFullObject(rooms_adults_pricing[v])) {
							//Occupancy supported
							var occ_data = "<div id=\"occupancy-r"+v+"\" class=\"occupancy-room\"  data-roomid=\""+v+"\">"+
								"<div class=\"occupancy-room-name\">"+rooms_names_map[v]+"</div>"+
								"<div class=\"occupancy-room-data\">";
							for(var occ in rooms_adults_pricing[v]) {
								if(rooms_adults_pricing[v].hasOwnProperty(occ)) {
									occ_data += "<div class=\"occupancy-adults-data\">"+
										"<span class=\"occupancy-adults-lbl\">"+usage_lbl.replace("%s", occ)+"</span>"+
										"<div class=\"occupancy-adults-ovr\">"+
											"<select name=\"adultsdiffchdisc["+v+"]["+occ+"]\"><option value=\"1\""+(rooms_adults_pricing[v][occ].hasOwnProperty('chdisc') && rooms_adults_pricing[v][occ]['chdisc'] == 1 ? " selected=\"selected\"" : "")+"><?php echo addslashes(JText::_('VBADULTSDIFFCHDISCONE')); ?></option><option value=\"2\""+(rooms_adults_pricing[v][occ].hasOwnProperty('chdisc') && rooms_adults_pricing[v][occ]['chdisc'] == 2 ? " selected=\"selected\"" : "")+"><?php echo addslashes(JText::_('VBADULTSDIFFCHDISCTWO')); ?></option></select>"+
											"<input type=\"text\" name=\"adultsdiffval["+v+"]["+occ+"]\" value=\"\" placeholder=\""+(rooms_adults_pricing[v][occ].hasOwnProperty('value') ? rooms_adults_pricing[v][occ]['value'] : "0.00")+"\" size=\"3\" style=\"width: 40px;\"/>"+
											"<select name=\"adultsdiffvalpcent["+v+"]["+occ+"]\"><option value=\"1\""+(rooms_adults_pricing[v][occ].hasOwnProperty('valpcent') && rooms_adults_pricing[v][occ]['valpcent'] == 1 ? " selected=\"selected\"" : "")+"><?php echo $currencysymb; ?></option><option value=\"2\""+(rooms_adults_pricing[v][occ].hasOwnProperty('valpcent') && rooms_adults_pricing[v][occ]['valpcent'] == 2 ? " selected=\"selected\"" : "")+">%</option></select>"+
											"<select name=\"adultsdiffpernight["+v+"]["+occ+"]\"><option value=\"0\""+(rooms_adults_pricing[v][occ].hasOwnProperty('pernight') && rooms_adults_pricing[v][occ]['pernight'] <= 0 ? " selected=\"selected\"" : "")+"><?php echo addslashes(JText::_('VBADULTSDIFFONTOTAL')); ?></option><option value=\"1\""+(rooms_adults_pricing[v][occ].hasOwnProperty('pernight') && rooms_adults_pricing[v][occ]['pernight'] >= 1 ? " selected=\"selected\"" : "")+"><?php echo addslashes(JText::_('VBADULTSDIFFONPERNIGHT')); ?></option></select>"+
										"</div>"+
										"</div>";
								}
							}
							occ_data += "</div>"+
								"</div>";
							occupancy_cont.append(occ_data);
						}else {
							//Occupancy not supported (same fromadult and toadult)
							occupancy_cont.append("<div id=\"occupancy-r"+v+"\" class=\"occupancy-room\" data-roomid=\""+v+"\">"+
								"<div class=\"occupancy-room-name\">"+rooms_names_map[v]+"</div>"+
								"<div class=\"occupancy-room-data\"><p><?php echo addslashes(JText::_('VBOROOMOCCUPANCYPRNOTSUPP')); ?></p></div>"+
								"</div>");
						}
					});
				}else {
					jQuery("#vbo-occupancy-pricing-fieldset").fadeOut();
				}
				//hide the un-selected rooms
				jQuery(".occupancy-room").each(function() {
					var rid = jQuery(this).attr("data-roomid");
					if(jQuery.inArray(rid, rooms_sel_ids) == -1) {
						jQuery(this).remove();
					}
				});
				//
			}
			function togglePromotion() {
				var promo_on = document.getElementById('promo').checked;
				if(promo_on === true) {
					jQuery('.promotr').fadeIn();
					var cur_startd = jQuery('#from').val();
					jQuery('#promovalidity span').text('');
					if(cur_startd.length) {
						jQuery('#promovalidity span').text(' ('+cur_startd+')');
					}
				}else {
					jQuery('.promotr').fadeOut();
				}
			}
			</script>
			<input type="hidden" value="0" id="morevalueoverrides" />
			
			<form name="adminForm" id="adminForm" action="index.php" method="post">
				
				<fieldset class="adminform fieldset-left">
					<legend class="adminlegend"><?php echo JText::_('VBSEASON'); ?> &nbsp;&nbsp;<?php echo JHTML::tooltip(JText::_('VBSPRICESHELP'), JText::_('VBSPRICESHELPTITLE'), 'tooltip.png', ''); ?></legend>
					<table cellspacing="1" class="admintable table">
						<tbody>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWSEASONONE'); ?></b> </td>
								<td><?php echo JHTML::_('calendar', '', 'from', 'from', $df, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?></td>
							</tr>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWSEASONTWO'); ?></b> </td>
								<td><?php echo JHTML::_('calendar', '', 'to', 'to', $df, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?></td>
							</tr>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBSPONLYPICKINCL'); ?></b> </td>
								<td><?php echo $vbo_app->printYesNoButtons('checkinincl', JText::_('VBYES'), JText::_('VBNO'), 0, 1, 0); ?></td>
							</tr>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBSPYEARTIED'); ?></b> </td>
								<td><?php echo $vbo_app->printYesNoButtons('yeartied', JText::_('VBYES'), JText::_('VBNO'), 0, 1, 0); ?></td>
							</tr>
						</tbody>
					</table>
				</fieldset>

				<fieldset class="adminform fieldset-left">
					<legend class="adminlegend"><?php echo JText::_('VBWEEKDAYS'); ?></legend>
					<table cellspacing="1" class="admintable table">
						<tbody>
							<tr>
								<td width="200" class="vbo-config-param-cell" style="vertical-align: top;"> <b><?php echo JText::_('VBSEASONDAYS'); ?></b> </td>
								<td><select multiple="multiple" size="7" name="wdays[]"><option value="0"><?php echo JText::_('VBSUNDAY'); ?></option><option value="1"><?php echo JText::_('VBMONDAY'); ?></option><option value="2"><?php echo JText::_('VBTUESDAY'); ?></option><option value="3"><?php echo JText::_('VBWEDNESDAY'); ?></option><option value="4"><?php echo JText::_('VBTHURSDAY'); ?></option><option value="5"><?php echo JText::_('VBFRIDAY'); ?></option><option value="6"><?php echo JText::_('VBSATURDAY'); ?></option></select></td>
							</tr>
						</tbody>
					</table>
				</fieldset>

				<br clear="all" />

				<fieldset class="adminform fieldset-half">
					<legend class="adminlegend"><?php echo JText::_('VBSPMAINSETTINGS'); ?></legend>
					<table cellspacing="1" class="admintable table">
						<tbody>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBSPNAME'); ?></b> </td>
								<td><input type="text" name="spname" value="" size="30"/></td>
							</tr>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWSEASONTHREE'); ?></b> </td>
								<td><select name="type"><option value="1"><?php echo JText::_('VBNEWSEASONSIX'); ?></option><option value="2"><?php echo JText::_('VBNEWSEASONSEVEN'); ?></option></select></td>
							</tr>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWSEASONFOUR'); ?></b> </td>
								<td><input type="text" name="diffcost" value="" size="5"/>  <select name="val_pcent" id="val_pcent"><option value="2">%</option><option value="1"><?php echo $currencysymb; ?></option></select> &nbsp;<?php echo JHTML::tooltip(JText::_('VBSPECIALPRICEVALHELP'), JText::_('VBNEWSEASONFOUR'), 'tooltip.png', ''); ?></td>
							</tr>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWSEASONVALUEOVERRIDE'); ?></b> <?php echo JHTML::tooltip(JText::_('VBNEWSEASONVALUEOVERRIDEHELP'), JText::_('VBNEWSEASONVALUEOVERRIDE'), 'tooltip.png', ''); ?></td>
								<td><div id="myDiv" style="display: block;"></div><a href="javascript: void(0);" onclick="addMoreOverrides();"><?php echo JText::_('VBNEWSEASONADDOVERRIDE'); ?></a></td>
							</tr>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWSEASONROUNDCOST'); ?></b> </td>
								<td><select name="roundmode"><option value=""><?php echo JText::_('VBNEWSEASONROUNDCOSTNO'); ?></option><option value="PHP_ROUND_HALF_UP"><?php echo JText::_('VBNEWSEASONROUNDCOSTUP'); ?></option><option value="PHP_ROUND_HALF_DOWN"><?php echo JText::_('VBNEWSEASONROUNDCOSTDOWN'); ?></option></select></td>
							</tr>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWSEASONFIVE'); ?></b> </td>
								<td><span class="vbo-select-all"><?php echo JText::_('VBOSELECTALL'); ?></span><?php echo $wsel; ?></td>
							</tr>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBOSPTYPESPRICE'); ?></b> </td>
								<td><span class="vbo-select-all"><?php echo JText::_('VBOSELECTALL'); ?></span><?php echo $wpricesel; ?></td>
							</tr>
						</tbody>
					</table>
				</fieldset>

				<fieldset class="adminform fieldset-half" id="vbo-occupancy-pricing-fieldset" style="display: none;">
					<legend class="adminlegend"><?php echo JText::_('VBSEASONOCCUPANCYPR'); ?></legend>
					<div id="vbo-occupancy-container"></div>
				</fieldset>

				<br clear="all" />

				<fieldset class="adminform">
					<legend class="adminlegend"><?php echo JText::_('VBSPPROMOTIONLABEL'); ?></legend>
					<table cellspacing="1" class="admintable table">
						<tbody>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBOISPROMOTION'); ?></b> </td>
								<td><input type="checkbox" id="promo" name="promo" value="1" onclick="togglePromotion();" /></td>
							</tr>
							<tr class="promotr">
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBOPROMOVALIDITY'); ?></b> </td>
								<td><input type="number" name="promodaysadv" value="0" size="5"/><span id="promovalidity"><?php echo JText::_('VBOPROMOVALIDITYDAYSADV'); ?><span></span></span></td>
							</tr>
							<tr class="promotr">
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBPROMOFORCEMINLOS'); ?></b> </td>
								<td><input type="number" name="promominlos" value="0" size="5"/></td>
							</tr>
							<tr class="promotr">
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBOPROMOTEXT'); ?></b> </td>
								<td><?php echo $editor->display( "promotxt", "", 400, 200, 70, 20 ); ?></td>
							</tr>
						</tbody>
					</table>
				</fieldset>

				<input type="hidden" name="task" value="">
				<input type="hidden" name="option" value="<?php echo $option; ?>">
			</form>
			<?php
		}else {
			?>
			<p class="warn"><a href="index.php?option=com_vikbooking&amp;task=newroom"><?php echo JText::_('VBNOROOMSFOUNDSEASONS'); ?></a></p>
			<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
			<?php
		}
	}
	
	public static function pEditSeason ($sdata, $wsel, $wpricesel, $adults_diff, $option) {
		if(strlen($wsel) > 0) {
			$vbo_app = new VikApplication();
			JHTML::_('behavior.calendar');
			$editor = JFactory::getEditor();
			$caldf=vikbooking::getDateFormat(true);
			$currencysymb=vikbooking::getCurrencySymb(true);
			if ($caldf=="%d/%m/%Y") {
				$df='d/m/Y';
			}elseif ($caldf=="%m/%d/%Y") {
				$df='m/d/Y';
			}else {
				$df='Y/m/d';
			}
			if($sdata['from'] > 0 || $sdata['to'] > 0) {
				$nowyear=!empty($sdata['year']) ? $sdata['year'] : date('Y');
				$frombase=mktime(0, 0, 0, 1, 1, $nowyear);
				$fromdate=date($df, ($frombase + $sdata['from']));
				if($sdata['to'] < $sdata['from']) {
					$nowyear=$nowyear + 1;
					$frombase=mktime(0, 0, 0, 1, 1, $nowyear);
				}
				$todate=date($df, ($frombase + $sdata['to']));
				//leap years
				$checkly=!empty($sdata['year']) ? $sdata['year'] : date('Y');
				if($checkly % 4 == 0 && ($checkly % 100 != 0 || $checkly % 400 == 0)) {
					$frombase=mktime(0, 0, 0, 1, 1, $checkly);
					$infoseason = getdate($frombase + $sdata['from']);
					$leapts = mktime(0, 0, 0, 2, 29, $infoseason['year']);
					if($infoseason[0] >= $leapts) {
						$fromdate=date($df, ($frombase + $sdata['from'] + 86400));
						$frombase=mktime(0, 0, 0, 1, 1, $nowyear);
						$todate=date($df, ($frombase + $sdata['to'] + 86400));
					}
				}
				//
			}else {
				$fromdate = '';
				$todate = '';
			}
			$actweekdays = explode(";", $sdata['wdays']);
			
			$actvalueoverrides = '';
			if (strlen($sdata['losoverride']) > 0) {
				$losoverrides = explode('_', $sdata['losoverride']);
				foreach($losoverrides as $loso) {
					if (!empty($loso)) {
						$losoparts = explode(':', $loso);
						$losoparts[2] = strstr($losoparts[0], '-i') != false ? 1 : 0;
						$losoparts[0] = str_replace('-i', '', $losoparts[0]);
						$actvalueoverrides .= '<p>'.JText::_('VBNEWSEASONNIGHTSOVR').' <input type="text" name="nightsoverrides[]" value="'.$losoparts[0].'" size="4"/> <select name="andmoreoverride[]"><option value="0">-------</option><option value="1"'.($losoparts[2] == 1 ? ' selected="selected"' : '').'>'.JText::_('VBNEWSEASONVALUESOVREMORE').'</option></select> - '.JText::_('VBNEWSEASONVALUESOVR').' <input type="text" name="valuesoverrides[]" value="'.$losoparts[1].'" size="5"/> '.(intval($sdata['val_pcent']) == 2 ? '%' : $currencysymb).'</p>';
					}
				}
			}
			
			?>
			<script type="text/javascript">
			function addMoreOverrides() {
				var sel = document.getElementById('val_pcent');
				var curpcent = sel.options[sel.selectedIndex].text;
				var ni = document.getElementById('myDiv');
				var numi = document.getElementById('morevalueoverrides');
				var num = (document.getElementById('morevalueoverrides').value -1)+ 2;
				numi.value = num;
				var newdiv = document.createElement('div');
				var divIdName = 'my'+num+'Div';
				newdiv.setAttribute('id',divIdName);
				newdiv.innerHTML = '<p><?php echo addslashes(JText::_('VBNEWSEASONNIGHTSOVR')); ?> <input type=\'text\' name=\'nightsoverrides[]\' value=\'\' size=\'4\'/> <select name=\'andmoreoverride[]\'><option value=\'0\'>-------</option><option value=\'1\'><?php echo addslashes(JText::_('VBNEWSEASONVALUESOVREMORE')); ?></option></select> - <?php echo addslashes(JText::_('VBNEWSEASONVALUESOVR')); ?> <input type=\'text\' name=\'valuesoverrides[]\' value=\'\' size=\'5\'/> '+curpcent+'</p>';
				ni.appendChild(newdiv);
			}
			jQuery.noConflict();
			var rooms_sel_ids = [];
			var rooms_names_map = [];
			var rooms_adults_pricing = <?php echo json_encode($adults_diff); ?>;
			jQuery(document).ready(function() {
				var rseltag = document.getElementById("idrooms");
				for(var i=0; i < rseltag.length; i++) {
					rooms_names_map[rseltag.options[i].value] = rseltag.options[i].text;
				}
				jQuery(".vbo-select-all").click(function(){
					jQuery(this).next("select").find("option").prop('selected', true);
				});
				jQuery("#idrooms").change(function(){
					if(jQuery(this).val() !== null) {
						rooms_sel_ids = jQuery(this).val();
					}else {
						rooms_sel_ids = [];
					}
					updateOccupancyPricing();
				});
				jQuery(document.body).on('click', ".occupancy-room-name", function() {
					jQuery(this).next(".occupancy-room-data").fadeToggle();
				});
				//edit mode must trigger the change event when the document is ready
				jQuery("#idrooms").trigger("change");
			});
			function isFullObject(obj) {
				var jk;
				for(jk in obj) {
					return obj.hasOwnProperty(jk);
				}
			}
			function updateOccupancyPricing() {
				var occupancy_cont = jQuery("#vbo-occupancy-container");
				var usage_lbl = '<?php echo addslashes(JText::_('VBADULTSDIFFNUM')); ?>';
				if(rooms_sel_ids.length > 0) {
					jQuery("#vbo-occupancy-pricing-fieldset").fadeIn();
					jQuery(rooms_sel_ids).each(function(k, v){
						if(!rooms_adults_pricing.hasOwnProperty(v)) {
							return true;
						}
						if(jQuery("#occupancy-r"+v).length) {
							return true;
						}
						if(isFullObject(rooms_adults_pricing[v])) {
							//Occupancy supported
							var is_ovr = false;
							var occ_data = "<div id=\"occupancy-r"+v+"\" class=\"occupancy-room\"  data-roomid=\""+v+"\">"+
								"<div class=\"occupancy-room-name\">"+rooms_names_map[v]+"</div>"+
								"<div class=\"occupancy-room-data\">";
							for(var occ in rooms_adults_pricing[v]) {
								if(rooms_adults_pricing[v].hasOwnProperty(occ)) {
									occ_data += "<div class=\"occupancy-adults-data\">"+
										"<span class=\"occupancy-adults-lbl\">"+usage_lbl.replace("%s", occ)+"</span>"+
										"<div class=\"occupancy-adults-ovr\">"+
											"<select name=\"adultsdiffchdisc["+v+"]["+occ+"]\"><option value=\"1\""+(rooms_adults_pricing[v][occ].hasOwnProperty('chdisc') && rooms_adults_pricing[v][occ]['chdisc'] == 1 ? " selected=\"selected\"" : "")+"><?php echo addslashes(JText::_('VBADULTSDIFFCHDISCONE')); ?></option><option value=\"2\""+(rooms_adults_pricing[v][occ].hasOwnProperty('chdisc') && rooms_adults_pricing[v][occ]['chdisc'] == 2 ? " selected=\"selected\"" : "")+"><?php echo addslashes(JText::_('VBADULTSDIFFCHDISCTWO')); ?></option></select>"+
											"<input type=\"text\" name=\"adultsdiffval["+v+"]["+occ+"]\" value=\""+(rooms_adults_pricing[v][occ].hasOwnProperty('override') && rooms_adults_pricing[v][occ].hasOwnProperty('value') ? rooms_adults_pricing[v][occ]['value'] : "")+"\" placeholder=\""+(rooms_adults_pricing[v][occ].hasOwnProperty('value') ? rooms_adults_pricing[v][occ]['value'] : "0.00")+"\" size=\"3\" style=\"width: 40px;\"/>"+
											"<select name=\"adultsdiffvalpcent["+v+"]["+occ+"]\"><option value=\"1\""+(rooms_adults_pricing[v][occ].hasOwnProperty('valpcent') && rooms_adults_pricing[v][occ]['valpcent'] == 1 ? " selected=\"selected\"" : "")+"><?php echo $currencysymb; ?></option><option value=\"2\""+(rooms_adults_pricing[v][occ].hasOwnProperty('valpcent') && rooms_adults_pricing[v][occ]['valpcent'] == 2 ? " selected=\"selected\"" : "")+">%</option></select>"+
											"<select name=\"adultsdiffpernight["+v+"]["+occ+"]\"><option value=\"0\""+(rooms_adults_pricing[v][occ].hasOwnProperty('pernight') && rooms_adults_pricing[v][occ]['pernight'] <= 0 ? " selected=\"selected\"" : "")+"><?php echo addslashes(JText::_('VBADULTSDIFFONTOTAL')); ?></option><option value=\"1\""+(rooms_adults_pricing[v][occ].hasOwnProperty('pernight') && rooms_adults_pricing[v][occ]['pernight'] >= 1 ? " selected=\"selected\"" : "")+"><?php echo addslashes(JText::_('VBADULTSDIFFONPERNIGHT')); ?></option></select>"+
										"</div>"+
										"</div>";
									is_ovr = rooms_adults_pricing[v][occ].hasOwnProperty('override') ? true : is_ovr;
								}
							}
							occ_data += "</div>"+
								"</div>";
							occupancy_cont.append(occ_data);
							if(is_ovr === true) {
								jQuery("#occupancy-r"+v).find(".occupancy-room-name").trigger("click");
							}
						}else {
							//Occupancy not supported (same fromadult and toadult)
							occupancy_cont.append("<div id=\"occupancy-r"+v+"\" class=\"occupancy-room\" data-roomid=\""+v+"\">"+
								"<div class=\"occupancy-room-name\">"+rooms_names_map[v]+"</div>"+
								"<div class=\"occupancy-room-data\"><p><?php echo addslashes(JText::_('VBOROOMOCCUPANCYPRNOTSUPP')); ?></p></div>"+
								"</div>");
						}
					});
				}else {
					jQuery("#vbo-occupancy-pricing-fieldset").fadeOut();
				}
				//hide the un-selected rooms
				jQuery(".occupancy-room").each(function() {
					var rid = jQuery(this).attr("data-roomid");
					if(jQuery.inArray(rid, rooms_sel_ids) == -1) {
						jQuery(this).remove();
					}
				});
				//
			}
			function togglePromotion() {
				var promo_on = document.getElementById('promo').checked;
				if(promo_on === true) {
					jQuery('.promotr').fadeIn();
					var cur_startd = jQuery('#from').val();
					jQuery('#promovalidity span').text('');
					if(cur_startd.length) {
						jQuery('#promovalidity span').text(' ('+cur_startd+')');
					}
				}else {
					jQuery('.promotr').fadeOut();
				}
			}
			</script>
			<input type="hidden" value="0" id="morevalueoverrides" />
			
			<form name="adminForm" id="adminForm" action="index.php" method="post">

				<fieldset class="adminform fieldset-left">
					<legend class="adminlegend"><?php echo JText::_('VBSEASON'); ?> &nbsp;&nbsp;<?php echo JHTML::tooltip(JText::_('VBSPRICESHELP'), JText::_('VBSPRICESHELPTITLE'), 'tooltip.png', ''); ?></legend>
					<table cellspacing="1" class="admintable table">
						<tbody>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWSEASONONE'); ?></b> </td>
								<td><?php echo JHTML::_('calendar', '', 'from', 'from', $caldf, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?></td>
							</tr>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWSEASONTWO'); ?></b> </td>
								<td><?php echo JHTML::_('calendar', '', 'to', 'to', $caldf, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?></td>
							</tr>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBSPONLYPICKINCL'); ?></b> </td>
								<td><?php echo $vbo_app->printYesNoButtons('checkinincl', JText::_('VBYES'), JText::_('VBNO'), (int)$sdata['checkinincl'], 1, 0); ?></td>
							</tr>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBSPYEARTIED'); ?></b> </td>
								<td><?php echo $vbo_app->printYesNoButtons('yeartied', JText::_('VBYES'), JText::_('VBNO'), (!empty($sdata['year']) ? 1 : 0), 1, 0); ?></td>
							</tr>
						</tbody>
					</table>
				</fieldset>

				<fieldset class="adminform fieldset-left">
					<legend class="adminlegend"><?php echo JText::_('VBWEEKDAYS'); ?></legend>
					<table cellspacing="1" class="admintable table">
						<tbody>
							<tr>
								<td width="200" class="vbo-config-param-cell" style="vertical-align: top;"> <b><?php echo JText::_('VBSEASONDAYS'); ?></b> </td>
								<td><select multiple="multiple" size="7" name="wdays[]"><option value="0"<?php echo (in_array("0", $actweekdays) ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBSUNDAY'); ?></option><option value="1"<?php echo (in_array("1", $actweekdays) ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBMONDAY'); ?></option><option value="2"<?php echo (in_array("2", $actweekdays) ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBTUESDAY'); ?></option><option value="3"<?php echo (in_array("3", $actweekdays) ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBWEDNESDAY'); ?></option><option value="4"<?php echo (in_array("4", $actweekdays) ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBTHURSDAY'); ?></option><option value="5"<?php echo (in_array("5", $actweekdays) ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBFRIDAY'); ?></option><option value="6"<?php echo (in_array("6", $actweekdays) ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBSATURDAY'); ?></option></select></td>
							</tr>
						</tbody>
					</table>
				</fieldset>

				<br clear="all" />

				<fieldset class="adminform fieldset-half">
					<legend class="adminlegend"><?php echo JText::_('VBSPMAINSETTINGS'); ?></legend>
					<table cellspacing="1" class="admintable table">
						<tbody>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBSPNAME'); ?></b> </td>
								<td><input type="text" name="spname" value="<?php echo $sdata['spname']; ?>" size="30"/></td>
							</tr>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWSEASONTHREE'); ?></b> </td>
								<td><select name="type"><option value="1"<?php echo (intval($sdata['type']) == 1 ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBNEWSEASONSIX'); ?></option><option value="2"<?php echo (intval($sdata['type']) == 2 ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBNEWSEASONSEVEN'); ?></option></select></td>
							</tr>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWSEASONFOUR'); ?></b> </td>
								<td><input type="text" name="diffcost" value="<?php echo $sdata['diffcost']; ?>" size="5"/> <select name="val_pcent" id="val_pcent"><option value="2"<?php echo (intval($sdata['val_pcent']) == 2 ? " selected=\"selected\"" : ""); ?>>%</option><option value="1"<?php echo (intval($sdata['val_pcent']) == 1 ? " selected=\"selected\"" : ""); ?>><?php echo $currencysymb; ?></option></select> &nbsp;<?php echo JHTML::tooltip(JText::_('VBSPECIALPRICEVALHELP'), JText::_('VBNEWSEASONFOUR'), 'tooltip.png', ''); ?></td>
							</tr>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWSEASONVALUEOVERRIDE'); ?></b> <?php echo JHTML::tooltip(JText::_('VBNEWSEASONVALUEOVERRIDEHELP'), JText::_('VBNEWSEASONVALUEOVERRIDE'), 'tooltip.png', ''); ?></td>
								<td><div id="myDiv" style="display: block;"><?php echo $actvalueoverrides; ?></div><a href="javascript: void(0);" onclick="addMoreOverrides();"><?php echo JText::_('VBNEWSEASONADDOVERRIDE'); ?></a></td>
							</tr>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWSEASONROUNDCOST'); ?></b> </td>
								<td><select name="roundmode"><option value=""><?php echo JText::_('VBNEWSEASONROUNDCOSTNO'); ?></option><option value="PHP_ROUND_HALF_UP"<?php echo ($sdata['roundmode'] == 'PHP_ROUND_HALF_UP' ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBNEWSEASONROUNDCOSTUP'); ?></option><option value="PHP_ROUND_HALF_DOWN"<?php echo ($sdata['roundmode'] == 'PHP_ROUND_HALF_DOWN' ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBNEWSEASONROUNDCOSTDOWN'); ?></option></select></td>
							</tr>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWSEASONFIVE'); ?></b> </td>
								<td><span class="vbo-select-all"><?php echo JText::_('VBOSELECTALL'); ?></span><?php echo $wsel; ?></td>
							</tr>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBOSPTYPESPRICE'); ?></b> </td>
								<td><span class="vbo-select-all"><?php echo JText::_('VBOSELECTALL'); ?></span><?php echo $wpricesel; ?></td>
							</tr>
						</tbody>
					</table>
				</fieldset>

				<fieldset class="adminform fieldset-half" id="vbo-occupancy-pricing-fieldset" style="display: none;">
					<legend class="adminlegend"><?php echo JText::_('VBSEASONOCCUPANCYPR'); ?></legend>
					<div id="vbo-occupancy-container"></div>
				</fieldset>

				<br clear="all" />

				<fieldset class="adminform">
					<legend class="adminlegend"><?php echo JText::_('VBSPPROMOTIONLABEL'); ?></legend>
					<table cellspacing="1" class="admintable table">
						<tbody>
							<tr>
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBOISPROMOTION'); ?></b> </td>
								<td><input type="checkbox" id="promo" name="promo" value="1" onclick="togglePromotion();" <?php echo $sdata['promo'] == 1 ? "checked=\"checked\"" : ""; ?>/></td>
							</tr>
							<tr class="promotr">
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBOPROMOVALIDITY'); ?></b> </td>
								<td><input type="number" name="promodaysadv" value="<?php echo empty($sdata['promodaysadv']) ? '0' : $sdata['promodaysadv']; ?>" size="5"/><span id="promovalidity"><?php echo JText::_('VBOPROMOVALIDITYDAYSADV'); ?><span></span></span></td>
							</tr>
							<tr class="promotr">
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBPROMOFORCEMINLOS'); ?></b> </td>
								<td><input type="number" name="promominlos" value="<?php echo empty($sdata['promominlos']) ? '0' : $sdata['promominlos']; ?>" size="5"/></td>
							</tr>
							<tr class="promotr">
								<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBOPROMOTEXT'); ?></b> </td>
								<td><?php echo $editor->display( "promotxt", $sdata['promotxt'], 400, 200, 70, 20 ); ?></td>
							</tr>
						</tbody>
					</table>
				</fieldset>

				<input type="hidden" name="task" value="">
				<input type="hidden" name="option" value="<?php echo $option; ?>">
				<input type="hidden" name="where" value="<?php echo $sdata['id']; ?>">
			</form>
			<script type="text/javascript">
			document.getElementById('from').value='<?php echo $fromdate; ?>';
			document.getElementById('to').value='<?php echo $todate; ?>';
			togglePromotion();
			</script>
			<?php
		}else {
			?>
			<p class="warn"><a href="index.php?option=com_vikbooking&amp;task=newroom"><?php echo JText::_('VBNOROOMSFOUNDSEASONS'); ?></a></p>
			<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
			<?php
		}
	}
	
	public static function pShowPayments ($rows, $option, $lim0="0", $navbut="") {
		$currencysymb=vikbooking::getCurrencySymb(true);
		if(empty($rows)){
			?>
			<p class="warn"><?php echo JText::_('VBNOPAYMENTS'); ?></p>
			<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
			<?php
		}else{
		
		?>
	<script type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'removepayments') {
			if (confirm('<?php echo JText::_('VBJSDELPAYMENTS'); ?> ?')){
				submitform( pressbutton );
				return;
			}else{
				return false;
			}
		}

		// do field validation
		try {
			document.adminForm.onsubmit();
		}
		catch(e){}
		submitform( pressbutton );
	}
	</script>
   <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

	<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped">
		<thead>
		<tr>
			<th width="20">
				<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
			</th>
			<th class="title left" width="150"><?php echo JText::_( 'VBPSHOWPAYMENTSONE' ); ?></th>
			<th class="title left" width="150"><?php echo JText::_( 'VBPSHOWPAYMENTSTWO' ); ?></th>
			<th class="title center" width="150" align="center"><?php echo JText::_( 'VBPSHOWPAYMENTSTHREE' ); ?></th>
			<th class="title center" width="100" align="center"><?php echo JText::_( 'VBPSHOWPAYMENTSCHARGEORDISC' ); ?></th>
			<th class="title center" width="50" align="center"><?php echo JText::_( 'VBPSHOWPAYMENTSFIVE' ); ?></th>
			<th class="title center" width="50" align="center"><?php echo JText::_( 'VBORDERING' ); ?></th>
		</tr>
		</thead>
		<?php
		
		$k = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$saycharge = "";
			if(strlen($row['charge']) > 0 && $row['charge'] > 0.00) {
				$saycharge .= $row['ch_disc'] == 1 ? "+ " : "- ";
				$saycharge .= $row['charge']." ";
				$saycharge .= $row['val_pcent'] == 1 ? $currencysymb : "%";
			}
			?>
			<tr class="row<?php echo $k; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td><a href="index.php?option=com_vikbooking&amp;task=editpayment&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td><?php echo $row['file']; ?></td>
				<td class="center"><?php echo strip_tags($row['note']); ?></td>
				<td class="center"><?php echo $saycharge; ?></td>
				<td class="center"><a href="index.php?option=com_vikbooking&amp;task=modavailpayment&amp;cid[]=<?php echo $row['id']; ?>"><?php echo intval($row['published']) == 1 ? "<img src=\"".JURI::root()."administrator/components/com_vikbooking/resources/ok.png\" style=\"border: 0;\"/>" : "<img src=\"".JURI::root()."administrator/components/com_vikbooking/resources/no.png\" style=\"border: 0;\"/>"; ?></a></td>
				<td class="center"><a href="index.php?option=com_vikbooking&amp;task=sortpayment&amp;cid[]=<?php echo $row['id']; ?>&amp;mode=up"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikbooking/resources/up.png" border="0" title="(<?php echo $row['ordering']; ?>)"/></a> <a href="index.php?option=com_vikbooking&amp;task=sortpayment&amp;cid[]=<?php echo $row['id']; ?>&amp;mode=down"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikbooking/resources/down.png" border="0" title="(<?php echo $row['ordering']; ?>)"/></a></td>
			</tr>  
			  <?php
			$k = 1 - $k;
			
		}
?>
		
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<?php
		if(defined('JVERSION') && version_compare(JVERSION, '1.6.0') < 0) {
			//Joomla 1.5
			
		}
		?>
		<input type="hidden" name="task" value="payments" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php echo $navbut; ?>
	</form>
	<?php
		}
	}
	
	public static function pNewPayment ($option) {
		JHTML::_('behavior.tooltip');
		$editor = JFactory::getEditor();
		$allf=glob('./components/com_vikbooking/payments/*.php');
		$psel="";
		if(@count($allf) > 0) {
			$classfiles=array();
			foreach($allf as $af) {
				$classfiles[]=str_replace('./components/com_vikbooking/payments/', '', $af);
			}
			sort($classfiles);
			$psel="<select name=\"payment\" onchange=\"vikLoadPaymentParameters(this.value);\">\n<option value=\"\"></option>\n";
			foreach($classfiles as $cf) {
				$psel.="<option value=\"".$cf."\">".$cf."</option>\n";
			}
			$psel.="</select>";
		}
		$currencysymb=vikbooking::getCurrencySymb(true);
		?>
		<script type="text/javascript">
		function vikLoadPaymentParameters(pfile) {
			jQuery.noConflict();
			if(pfile.length > 0) {
				jQuery("#vikparameters").html('<?php echo addslashes(JTEXT::_('VIKLOADING')); ?>');
				jQuery.ajax({
					type: "POST",
					url: "index.php?option=com_vikbooking&task=loadpaymentparams&tmpl=component",
					data: { phpfile: pfile }
				}).done(function(res) {
					jQuery("#vikparameters").html(res);
				});
			}else {
				jQuery("#vikparameters").html('--------');
			}
		}
		</script>
		
		<form name="adminForm" id="adminForm" action="index.php" method="post">
		<table class="adminform">
		<tr><td width="170">&bull; <b><?php echo JText::_('VBNEWPAYMENTONE'); ?>:</b> </td><td><input type="text" name="name" value="" size="30"/></td></tr>
		<tr><td width="170">&bull; <b><?php echo JText::_('VBNEWPAYMENTTWO'); ?>:</b> </td><td><?php echo $psel; ?></td></tr>
		<tr><td width="170" style="vertical-align: top;">&bull; <b><?php echo JText::_('VBPAYMENTPARAMETERS'); ?>:</b> </td><td id="vikparameters"></td></tr>
		<tr><td width="170">&bull; <b><?php echo JText::_('VBNEWPAYMENTTHREE'); ?>:</b> </td><td><select name="published"><option value="1"><?php echo JText::_('VBNEWPAYMENTSIX'); ?></option><option value="0"><?php echo JText::_('VBNEWPAYMENTSEVEN'); ?></option></select></td></tr>
		<tr><td width="170">&bull; <b><?php echo JText::_('VBNEWPAYMENTCHARGEORDISC'); ?>:</b> </td><td><select name="ch_disc"><option value="1"><?php echo JText::_('VBNEWPAYMENTCHARGEPLUS'); ?></option><option value="2"><?php echo JText::_('VBNEWPAYMENTDISCMINUS'); ?></option></select> <input type="text" name="charge" value="" size="5"/> <select name="val_pcent"><option value="1"><?php echo $currencysymb; ?></option><option value="2">%</option></select></td></tr>
		<tr><td width="170">&bull; <b><?php echo JText::_('VBNEWPAYMENTEIGHT'); ?>:</b> </td><td><select name="setconfirmed"><option value="1"><?php echo JText::_('VBNEWPAYMENTSIX'); ?></option><option value="0" selected="selected"><?php echo JText::_('VBNEWPAYMENTSEVEN'); ?></option></select> &nbsp; <?php echo JHTML::tooltip(JText::_('VBPAYMENTSHELPCONFIRM'), JText::_('VBPAYMENTSHELPCONFIRMTXT'), 'tooltip.png', ''); ?></td></tr>
		<tr><td width="170">&bull; <b><?php echo JText::_('VBNEWPAYMENTNINE'); ?>:</b> </td><td><select name="shownotealw"><option value="1"><?php echo JText::_('VBNEWPAYMENTSIX'); ?></option><option value="0" selected="selected"><?php echo JText::_('VBNEWPAYMENTSEVEN'); ?></option></select></td></tr>
		<tr><td width="170" valign="top">&bull; <b><?php echo JText::_('VBNEWPAYMENTFIVE'); ?>:</b> </td><td><?php echo $editor->display( "note", "", 400, 200, 70, 20 ); ?></td></tr>
		</table>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<?php
	}
	
	public static function pEditPayment ($payment, $option) {
		JHTML::_('behavior.tooltip');
		$editor = JFactory::getEditor();
		$allf=glob('./components/com_vikbooking/payments/*.php');
		$psel="";
		if(@count($allf) > 0) {
			$classfiles=array();
			foreach($allf as $af) {
				$classfiles[]=str_replace('./components/com_vikbooking/payments/', '', $af);
			}
			sort($classfiles);
			$psel="<select name=\"payment\" onchange=\"vikLoadPaymentParameters(this.value);\">\n<option value=\"\"></option>\n";
			foreach($classfiles as $cf) {
				$psel.="<option value=\"".$cf."\"".($cf==$payment['file'] ? " selected=\"selected\"" : "").">".$cf."</option>\n";
			}
			$psel.="</select>";
		}
		$currencysymb=vikbooking::getCurrencySymb(true);
		$payparams = vikbooking::displayPaymentParameters($payment['file'], $payment['params']);
		?>
		<script type="text/javascript">
		function vikLoadPaymentParameters(pfile) {
			jQuery.noConflict();
			if(pfile.length > 0) {
				jQuery("#vikparameters").html('<?php echo addslashes(JTEXT::_('VIKLOADING')); ?>');
				jQuery.ajax({
					type: "POST",
					url: "index.php?option=com_vikbooking&task=loadpaymentparams&tmpl=component",
					data: { phpfile: pfile }
				}).done(function(res) {
					jQuery("#vikparameters").html(res);
				});
			}else {
				jQuery("#vikparameters").html('--------');
			}
		}
		</script>
		
		<form name="adminForm" id="adminForm" action="index.php" method="post">
		<table class="adminform">
		<tr><td width="170">&bull; <b><?php echo JText::_('VBNEWPAYMENTONE'); ?>:</b> </td><td><input type="text" name="name" value="<?php echo $payment['name']; ?>" size="30"/></td></tr>
		<tr><td width="170">&bull; <b><?php echo JText::_('VBNEWPAYMENTTWO'); ?>:</b> </td><td><?php echo $psel; ?></td></tr>
		<tr><td width="170" style="vertical-align: top;">&bull; <b><?php echo JText::_('VBPAYMENTPARAMETERS'); ?>:</b> </td><td id="vikparameters"><?php echo $payparams; ?></td></tr>
		<tr><td width="170">&bull; <b><?php echo JText::_('VBNEWPAYMENTTHREE'); ?>:</b> </td><td><select name="published"><option value="1"<?php echo intval($payment['published']) == 1 ? " selected=\"selected\"" : ""; ?>><?php echo JText::_('VBNEWPAYMENTSIX'); ?></option><option value="0"<?php echo intval($payment['published']) != 1 ? " selected=\"selected\"" : ""; ?>><?php echo JText::_('VBNEWPAYMENTSEVEN'); ?></option></select></td></tr>
		<tr><td width="170">&bull; <b><?php echo JText::_('VBNEWPAYMENTCHARGEORDISC'); ?>:</b> </td><td><select name="ch_disc"><option value="1"<?php echo ($payment['ch_disc'] == 1 ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBNEWPAYMENTCHARGEPLUS'); ?></option><option value="2"<?php echo ($payment['ch_disc'] == 2 ? " selected=\"selected\"" : ""); ?>><?php echo JText::_('VBNEWPAYMENTDISCMINUS'); ?></option></select> <input type="text" name="charge" value="<?php echo $payment['charge']; ?>" size="5"/> <select name="val_pcent"><option value="1"<?php echo ($payment['val_pcent'] == 1 ? " selected=\"selected\"" : ""); ?>><?php echo $currencysymb; ?></option><option value="2"<?php echo ($payment['val_pcent'] == 2 ? " selected=\"selected\"" : ""); ?>>%</option></select></td></tr>
		<tr><td width="170">&bull; <b><?php echo JText::_('VBNEWPAYMENTEIGHT'); ?>:</b> </td><td><select name="setconfirmed"><option value="1"<?php echo intval($payment['setconfirmed']) == 1 ? " selected=\"selected\"" : ""; ?>><?php echo JText::_('VBNEWPAYMENTSIX'); ?></option><option value="0"<?php echo intval($payment['setconfirmed']) != 1 ? " selected=\"selected\"" : ""; ?>><?php echo JText::_('VBNEWPAYMENTSEVEN'); ?></option></select> &nbsp; <?php echo JHTML::tooltip(JText::_('VBPAYMENTSHELPCONFIRM'), JText::_('VBPAYMENTSHELPCONFIRMTXT'), 'tooltip.png', ''); ?></td></tr>
		<tr><td width="170">&bull; <b><?php echo JText::_('VBNEWPAYMENTNINE'); ?>:</b> </td><td><select name="shownotealw"><option value="1"<?php echo intval($payment['shownotealw']) == 1 ? " selected=\"selected\"" : ""; ?>><?php echo JText::_('VBNEWPAYMENTSIX'); ?></option><option value="0"<?php echo intval($payment['shownotealw']) != 1 ? " selected=\"selected\"" : ""; ?>><?php echo JText::_('VBNEWPAYMENTSEVEN'); ?></option></select></td></tr>
		<tr><td width="170" valign="top">&bull; <b><?php echo JText::_('VBNEWPAYMENTFIVE'); ?>:</b> </td><td><?php echo $editor->display( "note", $payment['note'], 400, 200, 70, 20 ); ?></td></tr>
		</table>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		<input type="hidden" name="where" value="<?php echo $payment['id']; ?>">
		</form>
		<?php
	}
	
	public static function pViewRestrictions ($rows, $option, $lim0="0", $navbut="") {
		if(empty($rows)){
			?>
			<p class="warn"><?php echo JText::_('VBNORESTRICTIONSFOUND'); ?></p>
			<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
			<?php
		}else{
			$df = vikbooking::getDateFormat(true);
			if ($df=="%d/%m/%Y") {
				$cdf='d/m/Y';
			}elseif ($df=="%m/%d/%Y") {
				$cdf='m/d/Y';
			}else {
				$cdf='Y/m/d';
			}
		?>
   <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

	<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped">
		<thead>
		<tr>
			<th width="20">
				<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
			</th>
			<th class="title left" width="200"><?php echo JText::_( 'VBPVIEWRESTRICTIONSONE' ); ?></th>
			<th class="title center" width="200" align="center"><?php echo JText::_( 'VBPVIEWRESTRICTIONSTWO' ); ?></th>
			<th class="title center" width="200" align="center"><?php echo JText::_( 'VBRESTRICTIONSDRANGE' ); ?></th>
			<th class="title center" width="100" align="center"><?php echo JText::_( 'VBPVIEWRESTRICTIONSTHREE' ); ?></th>
			<th class="title center" width="100" align="center"><?php echo JText::_( 'VBPVIEWRESTRICTIONSFOUR' ); ?></th>
			<th class="title center" width="100" align="center"><?php echo JText::_( 'VBPVIEWRESTRICTIONSFIVE' ); ?></th>
			<th class="title center" width="100" align="center"><?php echo JText::_( 'VBRESTRLISTROOMS' ); ?></th>
		</tr>
		</thead>
		<?php
		$arrmonths = array(1 => JText::_('VBMONTHONE'),
							2 => JText::_('VBMONTHTWO'),
							3 => JText::_('VBMONTHTHREE'),
							4 => JText::_('VBMONTHFOUR'),
							5 => JText::_('VBMONTHFIVE'),
							6 => JText::_('VBMONTHSIX'),
							7 => JText::_('VBMONTHSEVEN'),
							8 => JText::_('VBMONTHEIGHT'),
							9 => JText::_('VBMONTHNINE'),
							10 => JText::_('VBMONTHTEN'),
							11 => JText::_('VBMONTHELEVEN'),
							12 => JText::_('VBMONTHTWELVE')
							);
		$arrwdays = array(1 => JText::_('VBMONDAY'),
							2 => JText::_('VBTUESDAY'),
							3 => JText::_('VBWEDNESDAY'),
							4 => JText::_('VBTHURSDAY'),
							5 => JText::_('VBFRIDAY'),
							6 => JText::_('VBSATURDAY'),
							0 => JText::_('VBSUNDAY')
							);
		$k = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$drange = '-';
			if (!empty($row['dfrom'])) {
				$drange = date($cdf, $row['dfrom']).' - '.date($cdf, $row['dto']);
			}
			$sayrooms = JText::_('VBRESTRALLROOMS');
			if ($row['allrooms'] == 0) {
				$idr = explode(';', $row['idrooms']);
				$sayrooms = (count($idr) - 1);
			}
			?>
			<tr class="row<?php echo $k; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td><a href="index.php?option=com_vikbooking&amp;task=editrestriction&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td class="center"><?php echo !empty($row['month']) ? $arrmonths[$row['month']] : '-'; ?></td>
				<td class="center"><?php echo $drange; ?></td>
				<td class="center"><?php echo (strlen($row['wday']) > 0 ? $arrwdays[$row['wday']] : '').(strlen($row['wday']) > 0 && strlen($row['wdaytwo']) > 0 ? '/'.$arrwdays[$row['wdaytwo']] : ''); ?></td>
				<td class="center"><?php echo $row['minlos']; ?></td>
				<td class="center"><?php echo !empty($row['maxlos']) ? $row['maxlos'] : '-'; ?></td>
				<td class="center"><?php echo $sayrooms; ?></td>
			</tr>	
			<?php
			$k = 1 - $k;
			
		}
		?>
		
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<?php
		if(defined('JVERSION') && version_compare(JVERSION, '1.6.0') < 0) {
			//Joomla 1.5
			
		}
		?>
		<input type="hidden" name="task" value="restrictions" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php echo $navbut; ?>
	</form>
	<?php
		}
	}
	
	public static function pNewRestriction ($rooms, $option) {
		JHTML::_('behavior.calendar');
		$vbo_app = new VikApplication();
		$df = vikbooking::getDateFormat(true);
		$roomsel = '';
		if (is_array($rooms) && count($rooms) > 0) {
			$roomsel = '<select name="idrooms[]" multiple="multiple">'."\n";
			foreach ($rooms as $r) {
				$roomsel .= '<option value="'.$r['id'].'">'.$r['name'].'</option>'."\n";
			}
			$roomsel .= '</select>';
		}
		?>
		<script type="text/javascript">
		function vbSecondArrWDay() {
			var wdayone = document.adminForm.wday.value;
			if(wdayone != "") {
				document.getElementById("vbwdaytwodivid").style.display = "inline-block";
			}else {
				document.getElementById("vbwdaytwodivid").style.display = "none";
			}
			vbComboArrWDay();
		}
		function vbComboArrWDay() {
			var wdayone = document.adminForm.wday;
			var wdaytwo = document.adminForm.wdaytwo;
			if(wdayone.value != "" && wdaytwo.value != "" && wdayone.value != wdaytwo.value) {
				var comboa = wdayone.options[wdayone.selectedIndex].text;
				var combob = wdaytwo.options[wdaytwo.selectedIndex].text;
				document.getElementById("vbrcomboa1").innerHTML = comboa;
				document.getElementById("vbrcomboa2").innerHTML = combob;
				document.getElementById("vbrcomboa").value = wdayone.value+"-"+wdaytwo.value;
				document.getElementById("vbrcombob1").innerHTML = combob;
				document.getElementById("vbrcombob2").innerHTML = comboa;
				document.getElementById("vbrcombob").value = wdaytwo.value+"-"+wdayone.value;
				document.getElementById("vbrcomboc1").innerHTML = comboa;
				document.getElementById("vbrcomboc2").innerHTML = comboa;
				document.getElementById("vbrcomboc").value = wdayone.value+"-"+wdayone.value;
				document.getElementById("vbrcombod1").innerHTML = combob;
				document.getElementById("vbrcombod2").innerHTML = combob;
				document.getElementById("vbrcombod").value = wdaytwo.value+"-"+wdaytwo.value;
				document.getElementById("vbwdaycombodivid").style.display = "block";
			}else {
				document.getElementById("vbwdaycombodivid").style.display = "none";
			}
		}
		function vbToggleRooms() {
			if(document.adminForm.allrooms.checked == true) {
				document.getElementById("vbrestrroomsdiv").style.display = "none";
			}else {
				document.getElementById("vbrestrroomsdiv").style.display = "block";
			}
		}
		</script>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
			<fieldset class="adminform">
				<table cellspacing="1" class="admintable table">
					<tbody>
						<tr>
							<td width="200" class="vbo-config-param-cell"><div style="float: left;"><?php echo JHTML::tooltip(JText::_('VBRESTRICTIONSSHELP'), JText::_('VBRESTRICTIONSHELPTITLE'), 'tooltip.png', ''); ?></div> <b><?php echo JText::_('VBNEWRESTRICTIONNAME'); ?>*</b></td>
							<td><input type="text" name="name" value="" size="40"/></td>
						</tr>
						<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWRESTRICTIONONE'); ?>*</b></td>
							<td><select name="month" id="month"><option value="0">----</option><option value="1"><?php echo JText::_('VBMONTHONE'); ?></option><option value="2"><?php echo JText::_('VBMONTHTWO'); ?></option><option value="3"><?php echo JText::_('VBMONTHTHREE'); ?></option><option value="4"><?php echo JText::_('VBMONTHFOUR'); ?></option><option value="5"><?php echo JText::_('VBMONTHFIVE'); ?></option><option value="6"><?php echo JText::_('VBMONTHSIX'); ?></option><option value="7"><?php echo JText::_('VBMONTHSEVEN'); ?></option><option value="8"><?php echo JText::_('VBMONTHEIGHT'); ?></option><option value="9"><?php echo JText::_('VBMONTHNINE'); ?></option><option value="10"><?php echo JText::_('VBMONTHTEN'); ?></option><option value="11"><?php echo JText::_('VBMONTHELEVEN'); ?></option><option value="12"><?php echo JText::_('VBMONTHTWELVE'); ?></option></select></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWRESTRICTIONOR'); ?>*</b></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWRESTRICTIONDATERANGE'); ?>*</b></td>
							<td><div style="display: block; margin-bottom: 3px;"><?php echo '<span class="vbrestrdrangesp">'.JText::_('VBNEWRESTRICTIONDFROMRANGE').'</span>'.JHTML::_('calendar', '', 'dfrom', 'dfrom', $df, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?></div><div style="display: block; margin-bottom: 3px;"><?php echo '<span class="vbrestrdrangesp">'.JText::_('VBNEWRESTRICTIONDTORANGE').'</span>'.JHTML::_('calendar', '', 'dto', 'dto', $df, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?></div></td>
						</tr>
						<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWRESTRICTIONWDAY'); ?></b></td>
							<td>
								<select name="wday" onchange="vbSecondArrWDay();"><option value=""></option><option value="0"><?php echo JText::_('VBSUNDAY'); ?></option><option value="1"><?php echo JText::_('VBMONDAY'); ?></option><option value="2"><?php echo JText::_('VBTUESDAY'); ?></option><option value="3"><?php echo JText::_('VBWEDNESDAY'); ?></option><option value="4"><?php echo JText::_('VBTHURSDAY'); ?></option><option value="5"><?php echo JText::_('VBFRIDAY'); ?></option><option value="6"><?php echo JText::_('VBSATURDAY'); ?></option></select>
								<div class="vbwdaytwodiv" id="vbwdaytwodivid" style="display: none;"><span><?php echo JText::_('VBNEWRESTRICTIONOR'); ?></span> 
								<select name="wdaytwo" onchange="vbComboArrWDay();"><option value=""></option><option value="0"><?php echo JText::_('VBSUNDAY'); ?></option><option value="1"><?php echo JText::_('VBMONDAY'); ?></option><option value="2"><?php echo JText::_('VBTUESDAY'); ?></option><option value="3"><?php echo JText::_('VBWEDNESDAY'); ?></option><option value="4"><?php echo JText::_('VBTHURSDAY'); ?></option><option value="5"><?php echo JText::_('VBFRIDAY'); ?></option><option value="6"><?php echo JText::_('VBSATURDAY'); ?></option></select></div>
								<div class="vbwdaycombodiv" id="vbwdaycombodivid" style="display: none;"><span class="vbwdaycombosp"><?php echo JText::_('VBNEWRESTRICTIONALLCOMBO'); ?></span><span class="vbwdaycombohelp"><?php echo JText::_('VBNEWRESTRICTIONALLCOMBOHELP'); ?></span>
								<p class="vbwdaycombop"><label for="vbrcomboa" style="display: inline-block; vertical-align: top;"><span id="vbrcomboa1"></span> - <span id="vbrcomboa2"></span></label> <input type="checkbox" name="comboa" id="vbrcomboa" value="" style="display: inline-block; vertical-align: top;"/></p>
								<p class="vbwdaycombop"><label for="vbrcombob" style="display: inline-block; vertical-align: top;"><span id="vbrcombob1"></span> - <span id="vbrcombob2"></span></label> <input type="checkbox" name="combob" id="vbrcombob" value="" style="display: inline-block; vertical-align: top;"/></p>
								<p class="vbwdaycombop"><label for="vbrcomboc" style="display: inline-block; vertical-align: top;"><span id="vbrcomboc1"></span> - <span id="vbrcomboc2"></span></label> <input type="checkbox" name="comboc" id="vbrcomboc" value="" style="display: inline-block; vertical-align: top;"/></p>
								<p class="vbwdaycombop"><label for="vbrcombod" style="display: inline-block; vertical-align: top;"><span id="vbrcombod1"></span> - <span id="vbrcombod2"></span></label> <input type="checkbox" name="combod" id="vbrcombod" value="" style="display: inline-block; vertical-align: top;"/></p>
								</div>
							</td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWRESTRICTIONMINLOS'); ?>*</b></td>
							<td><input type="number" name="minlos" value="1" min="1" size="3" style="width: 60px !important;" /></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"><div style="float: left;"><?php echo JHTML::tooltip(JText::_('VBNEWRESTRICTIONMULTIPLYMINLOSHELP'), JText::_('VBNEWRESTRICTIONMULTIPLYMINLOS'), 'tooltip.png', ''); ?></div> <b><?php echo JText::_('VBNEWRESTRICTIONMULTIPLYMINLOS'); ?></b></td>
							<td><input type="checkbox" name="multiplyminlos" value="1"/></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWRESTRICTIONMAXLOS'); ?></b></td>
							<td><input type="number" name="maxlos" value="0" min="0" size="3" style="width: 60px !important;" /></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWRESTRICTIONALLROOMS'); ?></b></td>
							<td><input type="checkbox" name="allrooms" value="1" checked="checked" onclick="vbToggleRooms();"/><div id="vbrestrroomsdiv" style="display: none;"><span class="vbrestrroomssp"><?php echo JText::_('VBNEWRESTRICTIONROOMSAFF'); ?></span><?php echo $roomsel; ?></div></td>
						</tr>
					</tbody>
				</table>
			</fieldset>
			<input type="hidden" name="task" value="">
			<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<?php
	}
	
	public static function pEditRestriction ($data, $rooms, $option) {
		JHTML::_('behavior.calendar');
		$vbo_app = new VikApplication();
		$df = vikbooking::getDateFormat(true);
		if ($df=="%d/%m/%Y") {
			$cdf='d/m/Y';
		}elseif ($df=="%m/%d/%Y") {
			$cdf='m/d/Y';
		}else {
			$cdf='Y/m/d';
		}
		$roomsel = '';
		if (is_array($rooms) && count($rooms) > 0) {
			$nowrooms = !empty($data['idrooms']) && $data['allrooms'] == 0 ? explode(';', $data['idrooms']) : array();
			$roomsel = '<select name="idrooms[]" multiple="multiple">'."\n";
			foreach ($rooms as $r) {
				$roomsel .= '<option value="'.$r['id'].'"'.(in_array('-'.$r['id'].'-', $nowrooms) ? ' selected="selected"' : '').'>'.$r['name'].'</option>'."\n";
			}
			$roomsel .= '</select>';
		}
		$dfromval = !empty($data['dfrom']) ? date($cdf, $data['dfrom']) : '';
		$dtoval = !empty($data['dto']) ? date($cdf, $data['dto']) : '';
		$vbra1 = '';
		$vbra2 = '';
		$vbrb1 = '';
		$vbrb2 = '';
		$vbrc1 = '';
		$vbrc2 = '';
		$vbrd1 = '';
		$vbrd2 = '';
		if(strlen($data['wdaycombo']) > 0) {
			$vbcomboparts = explode(':', $data['wdaycombo']);
			foreach($vbcomboparts as $kc => $cb) {
				if (!empty($cb)) {
					$nowcombo = explode('-', $cb);
					if ($kc == 0) {
						$vbra1 = $nowcombo[0];
						$vbra2 = $nowcombo[1];
					}elseif ($kc == 1) {
						$vbrb1 = $nowcombo[0];
						$vbrb2 = $nowcombo[1];
					}elseif ($kc == 2) {
						$vbrc1 = $nowcombo[0];
						$vbrc2 = $nowcombo[1];
					}elseif ($kc == 3) {
						$vbrd1 = $nowcombo[0];
						$vbrd2 = $nowcombo[1];
					}
				}
			}
		}
		$arrwdays = array(1 => JText::_('VBMONDAY'),
				2 => JText::_('VBTUESDAY'),
				3 => JText::_('VBWEDNESDAY'),
				4 => JText::_('VBTHURSDAY'),
				5 => JText::_('VBFRIDAY'),
				6 => JText::_('VBSATURDAY'),
				0 => JText::_('VBSUNDAY')
		);
		?>
		<script type="text/javascript">
		function vbSecondArrWDay() {
			var wdayone = document.adminForm.wday.value;
			if(wdayone != "") {
				document.getElementById("vbwdaytwodivid").style.display = "inline-block";
			}else {
				document.getElementById("vbwdaytwodivid").style.display = "none";
			}
			vbComboArrWDay();
		}
		function vbComboArrWDay() {
			var wdayone = document.adminForm.wday;
			var wdaytwo = document.adminForm.wdaytwo;
			if(wdayone.value != "" && wdaytwo.value != "" && wdayone.value != wdaytwo.value) {
				var comboa = wdayone.options[wdayone.selectedIndex].text;
				var combob = wdaytwo.options[wdaytwo.selectedIndex].text;
				document.getElementById("vbrcomboa1").innerHTML = comboa;
				document.getElementById("vbrcomboa2").innerHTML = combob;
				document.getElementById("vbrcomboa").value = wdayone.value+"-"+wdaytwo.value;
				document.getElementById("vbrcombob1").innerHTML = combob;
				document.getElementById("vbrcombob2").innerHTML = comboa;
				document.getElementById("vbrcombob").value = wdaytwo.value+"-"+wdayone.value;
				document.getElementById("vbrcomboc1").innerHTML = comboa;
				document.getElementById("vbrcomboc2").innerHTML = comboa;
				document.getElementById("vbrcomboc").value = wdayone.value+"-"+wdayone.value;
				document.getElementById("vbrcombod1").innerHTML = combob;
				document.getElementById("vbrcombod2").innerHTML = combob;
				document.getElementById("vbrcombod").value = wdaytwo.value+"-"+wdaytwo.value;
				document.getElementById("vbwdaycombodivid").style.display = "block";
			}else {
				document.getElementById("vbwdaycombodivid").style.display = "none";
			}
		}
		function vbToggleRooms() {
			if(document.adminForm.allrooms.checked == true) {
				document.getElementById("vbrestrroomsdiv").style.display = "none";
			}else {
				document.getElementById("vbrestrroomsdiv").style.display = "block";
			}
		}
		</script>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
			<fieldset class="adminform">
				<table cellspacing="1" class="admintable table">
					<tbody>
						<tr>
							<td width="200" class="vbo-config-param-cell"><div style="float: left;"><?php echo JHTML::tooltip(JText::_('VBRESTRICTIONSSHELP'), JText::_('VBRESTRICTIONSHELPTITLE'), 'tooltip.png', ''); ?></div> <b><?php echo JText::_('VBNEWRESTRICTIONNAME'); ?>*</b></td>
							<td><input type="text" name="name" value="<?php echo $data['name']; ?>" size="40"/></td>
						</tr>
						<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWRESTRICTIONONE'); ?>*</b></td>
							<td><select name="month"><option value="0">----</option><option value="1"<?php echo ($data['month'] == 1 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBMONTHONE'); ?></option><option value="2"<?php echo ($data['month'] == 2 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBMONTHTWO'); ?></option><option value="3"<?php echo ($data['month'] == 3 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBMONTHTHREE'); ?></option><option value="4"<?php echo ($data['month'] == 4 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBMONTHFOUR'); ?></option><option value="5"<?php echo ($data['month'] == 5 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBMONTHFIVE'); ?></option><option value="6"<?php echo ($data['month'] == 6 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBMONTHSIX'); ?></option><option value="7"<?php echo ($data['month'] == 7 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBMONTHSEVEN'); ?></option><option value="8"<?php echo ($data['month'] == 8 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBMONTHEIGHT'); ?></option><option value="9"<?php echo ($data['month'] == 9 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBMONTHNINE'); ?></option><option value="10"<?php echo ($data['month'] == 10 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBMONTHTEN'); ?></option><option value="11"<?php echo ($data['month'] == 11 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBMONTHELEVEN'); ?></option><option value="12"<?php echo ($data['month'] == 12 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBMONTHTWELVE'); ?></option></select></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWRESTRICTIONOR'); ?>*</b></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWRESTRICTIONDATERANGE'); ?>*</b></td>
							<td><div style="display: block; margin-bottom: 3px;"><?php echo '<span class="vbrestrdrangesp">'.JText::_('VBNEWRESTRICTIONDFROMRANGE').'</span>'.JHTML::_('calendar', '', 'dfrom', 'dfrom', $df, array('class'=>'', 'size'=>'10', 'value'=>$dfromval, 'maxlength'=>'19')); ?></div><div style="display: block; margin-bottom: 3px;"><?php echo '<span class="vbrestrdrangesp">'.JText::_('VBNEWRESTRICTIONDTORANGE').'</span>'.JHTML::_('calendar', '', 'dto', 'dto', $df, array('class'=>'', 'size'=>'10', 'value'=>$dtoval, 'maxlength'=>'19')); ?></div></td>
						</tr>
						<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWRESTRICTIONWDAY'); ?></b></td>
							<td>
								<select name="wday" onchange="vbSecondArrWDay();"><option value=""></option><option value="0"<?php echo (strlen($data['wday']) > 0 && $data['wday'] == 0 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBSUNDAY'); ?></option><option value="1"<?php echo ($data['wday'] == 1 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBMONDAY'); ?></option><option value="2"<?php echo ($data['wday'] == 2 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBTUESDAY'); ?></option><option value="3"<?php echo ($data['wday'] == 3 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBWEDNESDAY'); ?></option><option value="4"<?php echo ($data['wday'] == 4 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBTHURSDAY'); ?></option><option value="5"<?php echo ($data['wday'] == 5 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBFRIDAY'); ?></option><option value="6"<?php echo ($data['wday'] == 6 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBSATURDAY'); ?></option></select>
								<div class="vbwdaytwodiv" id="vbwdaytwodivid" style="display: <?php echo (strlen($data['wday']) > 0 ? 'inline-block' : 'none'); ?>;"><span><?php echo JText::_('VBNEWRESTRICTIONOR'); ?></span> 
								<select name="wdaytwo" onchange="vbComboArrWDay();"><option value=""></option><option value="0"<?php echo (strlen($data['wdaytwo']) > 0 && $data['wdaytwo'] == 0 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBSUNDAY'); ?></option><option value="1"<?php echo ($data['wdaytwo'] == 1 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBMONDAY'); ?></option><option value="2"<?php echo ($data['wdaytwo'] == 2 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBTUESDAY'); ?></option><option value="3"<?php echo ($data['wdaytwo'] == 3 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBWEDNESDAY'); ?></option><option value="4"<?php echo ($data['wdaytwo'] == 4 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBTHURSDAY'); ?></option><option value="5"<?php echo ($data['wdaytwo'] == 5 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBFRIDAY'); ?></option><option value="6"<?php echo ($data['wdaytwo'] == 6 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBSATURDAY'); ?></option></select></div>
								<div class="vbwdaycombodiv" id="vbwdaycombodivid" style="display: <?php echo (!empty($data['wdaycombo']) && strlen($data['wdaycombo']) > 3 ? 'block' : 'none'); ?>;"><span class="vbwdaycombosp"><?php echo JText::_('VBNEWRESTRICTIONALLCOMBO'); ?></span><span class="vbwdaycombohelp"><?php echo JText::_('VBNEWRESTRICTIONALLCOMBOHELP'); ?></span>
								<p class="vbwdaycombop"><label for="vbrcomboa" style="display: inline-block; vertical-align: top;"><span id="vbrcomboa1"><?php echo strlen($vbra1) ? $arrwdays[intval($vbra1)] : ''; ?></span> - <span id="vbrcomboa2"><?php echo strlen($vbra2) ? $arrwdays[intval($vbra2)] : ''; ?></span></label> <input type="checkbox" name="comboa" id="vbrcomboa" value="<?php echo strlen($vbra1) ? $vbra1.'-'.$vbra2 : ''; ?>"<?php echo (strlen($vbra1) && $vbcomboparts[0] == $vbra1.'-'.$vbra2 ? ' checked="checked"' : ''); ?> style="display: inline-block; vertical-align: top;"/></p>
								<p class="vbwdaycombop"><label for="vbrcombob" style="display: inline-block; vertical-align: top;"><span id="vbrcombob1"><?php echo strlen($vbrb1) ? $arrwdays[intval($vbrb1)] : ''; ?></span> - <span id="vbrcombob2"><?php echo strlen($vbrb2) ? $arrwdays[intval($vbrb2)] : ''; ?></span></label> <input type="checkbox" name="combob" id="vbrcombob" value="<?php echo strlen($vbrb1) ? $vbrb1.'-'.$vbrb2 : ''; ?>"<?php echo (strlen($vbrb1) && $vbcomboparts[1] == $vbrb1.'-'.$vbrb2 ? ' checked="checked"' : ''); ?> style="display: inline-block; vertical-align: top;"/></p>
								<p class="vbwdaycombop"><label for="vbrcomboc" style="display: inline-block; vertical-align: top;"><span id="vbrcomboc1"><?php echo strlen($vbrc1) ? $arrwdays[intval($vbrc1)] : ''; ?></span> - <span id="vbrcomboc2"><?php echo strlen($vbrc2) ? $arrwdays[intval($vbrc2)] : ''; ?></span></label> <input type="checkbox" name="comboc" id="vbrcomboc" value="<?php echo strlen($vbrc1) ? $vbrc1.'-'.$vbrc2 : ''; ?>"<?php echo (strlen($vbrc1) && $vbcomboparts[2] == $vbrc1.'-'.$vbrc2 ? ' checked="checked"' : ''); ?> style="display: inline-block; vertical-align: top;"/></p>
								<p class="vbwdaycombop"><label for="vbrcombod" style="display: inline-block; vertical-align: top;"><span id="vbrcombod1"><?php echo strlen($vbrd1) ? $arrwdays[intval($vbrd1)] : ''; ?></span> - <span id="vbrcombod2"><?php echo strlen($vbrd2) ? $arrwdays[intval($vbrd2)] : ''; ?></span></label> <input type="checkbox" name="combod" id="vbrcombod" value="<?php echo strlen($vbrd1) ? $vbrd1.'-'.$vbrd2 : ''; ?>"<?php echo (strlen($vbrd1) && $vbcomboparts[3] == $vbrd1.'-'.$vbrd2 ? ' checked="checked"' : ''); ?> style="display: inline-block; vertical-align: top;"/></p>
								</div>
							</td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWRESTRICTIONMINLOS'); ?>*</b></td>
							<td><input type="number" name="minlos" value="<?php echo $data['minlos']; ?>" min="1" size="3" style="width: 60px !important;" /></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"><div style="float: left;"><?php echo JHTML::tooltip(JText::_('VBNEWRESTRICTIONMULTIPLYMINLOSHELP'), JText::_('VBNEWRESTRICTIONMULTIPLYMINLOS'), 'tooltip.png', ''); ?></div> <b><?php echo JText::_('VBNEWRESTRICTIONMULTIPLYMINLOS'); ?></b></td>
							<td><input type="checkbox" name="multiplyminlos" value="1"<?php echo ($data['multiplyminlos'] == 1 ? ' checked="checked"' : ''); ?>/></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWRESTRICTIONMAXLOS'); ?></b></td>
							<td><input type="number" name="maxlos" value="<?php echo $data['maxlos']; ?>" min="0" size="3" style="width: 60px !important;" /></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWRESTRICTIONALLROOMS'); ?></b></td>
							<td><input type="checkbox" name="allrooms" value="1" onclick="vbToggleRooms();"<?php echo ($data['allrooms'] == 1 ? ' checked="checked"' : ''); ?>/><div id="vbrestrroomsdiv" style="display: <?php echo ($data['allrooms'] == 1 ? 'none' : 'block'); ?>;"><span class="vbrestrroomssp"><?php echo JText::_('VBNEWRESTRICTIONROOMSAFF'); ?></span><?php echo $roomsel; ?></div></td>
						</tr>
					</tbody>
				</table>
			</fieldset>
			<input type="hidden" name="where" value="<?php echo $data['id']; ?>">
			<input type="hidden" name="task" value="">
			<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<script type="text/javascript">
		document.getElementById('dfrom').value='<?php echo $dfromval; ?>';
		document.getElementById('dto').value='<?php echo $dtoval; ?>';
		<?php
		if(strlen($data['wday']) > 0 && strlen($data['wdaytwo']) > 0) {
			?>
		vbComboArrWDay();
			<?php
		}
		?>
		</script>
		<?php
	}

	public static function pRatesOverview($all_rooms, $roomrows, $seasoncal_nights, $seasons_cal, $option) {
		JHTML::_('behavior.calendar');
		$currencysymb = vikbooking::getCurrencySymb();
		$vbo_df = vikbooking::getDateFormat();
		$df = $vbo_df == "%d/%m/%Y" ? 'd/m/Y' : ($vbo_df == "%m/%d/%Y" ? 'm/d/Y' : 'Y/m/d');
		$price_types_show = true;
		$los_show = true;
		?>
		<div class="vbo-ratesoverview-roomsel-block">
			<form method="get" action="index.php?option=com_vikbooking" name="vboratesovwform">
			<input type="hidden" name="option" value="com_vikbooking" />
				<input type="hidden" name="task" value="ratesoverview" />
				<div class="vbo-ratesoverview-roomsel-entry">
					<label for="roomsel"><?php echo JText::_('VBRATESOVWROOM'); ?></label>
					<select name="cid[]" onchange="document.vboratesovwform.submit();">
			<?php
			foreach ($all_rooms as $room) {
				?>
					<option value="<?php echo $room['id']; ?>"<?php echo $room['id'] == $roomrows['id'] ? ' selected="selected"' : ''; ?>><?php echo $room['name']; ?></option>
				<?php
			}
			?>
					</select>
				</div>
				<div class="vbo-ratesoverview-roomsel-entry">
					<label><?php echo JText::_('VBRATESOVWNUMNIGHTSACT'); ?></label>
			<?php
			foreach ($seasoncal_nights as $numnights) {
				?>
					<span class="vbo-ratesoverview-numnight" id="numnights<?php echo $numnights; ?>"><?php echo $numnights; ?></span>
					<input type="hidden" name="nights_cal[]" id="inpnumnights<?php echo $numnights; ?>" value="<?php echo $numnights; ?>" />
				<?php
			}
			?>
					<input type="number" id="vbo-addnumnight" value="<?php echo ($numnights + 1); ?>" min="1"/>
					<span id="vbo-addnumnight-act"></span>
					<button type="button" class="btn vbo-apply-los-btn" onclick="document.vboratesovwform.submit();"><?php echo JText::_('VBRATESOVWAPPLYLOS'); ?></button>
				</div>
				<div class="vbo-ratesoverview-roomsel-entry">
					<label for="roomsel"><?php echo JText::_('VBRATESOVWRATESCALCULATOR'); ?></label>
					<span class="vbo-ratesoverview-entryinline"><?php echo JHTML::_('calendar', '', 'checkindate', 'checkindate', '%Y-%m-%d', array('class'=>'', 'size'=>'10',  'maxlength'=>'19', 'placeholder'=>JText::_('VBPICKUPAT'))); ?></span>
					<span class="vbo-ratesoverview-entryinline"><span><?php echo JText::_('VBRATESOVWRATESCALCNUMNIGHTS'); ?></span> <input type="number" id="vbo-numnights" value="1" min="1"/></span>
					<span class="vbo-ratesoverview-entryinline"><span><?php echo JText::_('VBRATESOVWRATESCALCNUMADULTS'); ?></span> <input type="number" id="vbo-numadults" value="<?php echo $roomrows['fromadult']; ?>" min="<?php echo $roomrows['fromadult']; ?>" max="<?php echo $roomrows['toadult']; ?>"/></span>
					<span class="vbo-ratesoverview-entryinline"><span><?php echo JText::_('VBRATESOVWRATESCALCNUMCHILDREN'); ?></span> <input type="number" id="vbo-numchildren" value="<?php echo $roomrows['fromchild']; ?>" min="<?php echo $roomrows['fromchild']; ?>" max="<?php echo $roomrows['tochild']; ?>"/></span>
					<span class="vbo-ratesoverview-entryinline"><button type="button" class="btn" id="vbo-ratesoverview-calculate"><?php echo JText::_('VBRATESOVWRATESCALCULATORCALC'); ?></button></span>
				</div>
			</form>
		</div>
		<br clear="all" />
		<div class="vbo-ratesoverview-calculation-response"></div>
		<div class="vbo-ratesoverview-roomdetails">
			<h3><?php echo $roomrows['name']; ?></h3>
		</div>
		<?php
		if(count($seasons_cal) > 0) {
			//Special Prices Timeline
			if(count($seasons_cal['seasons'])) {
				?>
		<div class="vbo-timeline-container">
			<ul id="vbo-timeline">
				<?php
				foreach ($seasons_cal['seasons'] as $ks => $timeseason) {
					$s_val_diff = '';
					if($timeseason['val_pcent'] == 2) {
						//percentage
						$s_val_diff = (($timeseason['diffcost'] - abs($timeseason['diffcost'])) > 0.00 ? vikbooking::numberFormat($timeseason['diffcost']) : intval($timeseason['diffcost']))." %";
					}else {
						//absolute
						$s_val_diff = $currencysymb.''.vikbooking::numberFormat($timeseason['diffcost']);
					}
					$s_explanation = array();
					if(empty($timeseason['year'])) {
						$s_explanation[] = JText::_('VBSEASONANYYEARS');
					}
					if(!empty($timeseason['losoverride'])) {
						$s_explanation[] = JText::_('VBSEASONBASEDLOS');
					}
					?>
				<li data-fromts="<?php echo $timeseason['from_ts']; ?>" data-tots="<?php echo $timeseason['to_ts']; ?>">
					<input type="radio" name="timeline" class="vbo-timeline-radio" id="vbo-timeline-dot<?php echo $ks; ?>" <?php echo $ks === 0 ? 'checked="checked"' : ''; ?>/>
					<div class="vbo-timeline-relative">
						<label class="vbo-timeline-label" for="vbo-timeline-dot<?php echo $ks; ?>"><?php echo $timeseason['spname']; ?></label>
						<span class="vbo-timeline-date"><?php echo vikbooking::formatSeasonDates($timeseason['from_ts'], $timeseason['to_ts']); ?></span>
						<span class="vbo-timeline-circle" onclick="Javascript: jQuery('#vbo-timeline-dot<?php echo $ks; ?>').trigger('click');"></span>
					</div>
					<div class="vbo-timeline-content">
						<p>
							<span class="vbo-seasons-calendar-slabel vbo-seasons-calendar-season-<?php echo $timeseason['type'] == 2 ? 'discount' : 'charge'; ?>"><?php echo $timeseason['type'] == 2 ? '-' : '+'; ?> <?php echo $s_val_diff; ?> <?php echo JText::_('VBSEASONPERNIGHT'); ?></span>
							<br/>
							<?php
							if(count($s_explanation) > 0) {
								echo implode(' - ', $s_explanation);
							}
							?>
						</p>
					</div>
				</li>
					<?php
				}
				?>
			</ul>
		</div>
		<script>
		jQuery(document).ready(function(){
			jQuery('.vbo-timeline-container').css('min-height', (jQuery('.vbo-timeline-container').outerHeight() + 20));
		});
		</script>
				<?php
			}
			//
			//Begin Seasons Calendar
			?>
		<table class="vbo-seasons-calendar-table">
			<tr class="vbo-seasons-calendar-nightsrow">
				<td>&nbsp;</td>
			<?php
			foreach ($seasons_cal['offseason'] as $numnights => $ntars) {
				?>
				<td><span><?php echo JText::sprintf(($numnights > 1 ? 'VBOSEASONCALNUMNIGHTS' : 'VBOSEASONCALNUMNIGHT'), $numnights); ?></span></td>
				<?php
			}
			?>
			</tr>
			<tr class="vbo-seasons-calendar-offseasonrow">
				<td>
					<span class="vbo-seasons-calendar-offseasonname"><?php echo JText::_('VBOSEASONSCALOFFSEASONPRICES'); ?></span>
				</td>
			<?php
			foreach ($seasons_cal['offseason'] as $numnights => $tars) {
				?>
				<td>
					<div class="vbo-seasons-calendar-offseasoncosts">
						<?php
						foreach ($tars as $tar) {
							?>
						<div class="vbo-seasons-calendar-offseasoncost">
							<?php
							if($price_types_show) {
							?>
							<span class="vbo-seasons-calendar-pricename"><?php echo $tar['name']; ?></span>
							<?php
							}
							?>
							<span class="vbo-seasons-calendar-pricecost">
								<span class="vbo_currency"><?php echo $currencysymb; ?></span><span class="vbo_price"><?php echo vikbooking::numberFormat($tar['cost']); ?></span>
							</span>
						</div>
							<?php
							if(!$price_types_show) {
								break;
							}
						}
						?>
					</div>
				</td>
				<?php
			}
			?>
			</tr>
			<?php
			foreach ($seasons_cal['seasons'] as $s_id => $s) {
				$restr_diff_nights = array();
				if($los_show && array_key_exists($s_id, $seasons_cal['restrictions'])) {
					$restr_diff_nights = vikbooking::compareSeasonRestrictionsNights($seasons_cal['restrictions'][$s_id]);
				}
				$s_val_diff = '';
				if($s['val_pcent'] == 2) {
					//percentage
					$s_val_diff = (($s['diffcost'] - abs($s['diffcost'])) > 0.00 ? vikbooking::numberFormat($s['diffcost']) : intval($s['diffcost']))." %";
				}else {
					//absolute
					$s_val_diff = $currencysymb.''.vikbooking::numberFormat($s['diffcost']);
				}
				?>
			<tr class="vbo-seasons-calendar-seasonrow">
				<td>
					<div class="vbo-seasons-calendar-seasondates">
						<span class="vbo-seasons-calendar-seasonfrom"><?php echo date($df, $s['from_ts']); ?></span>
						<span class="vbo-seasons-calendar-seasondates-separe">-</span>
						<span class="vbo-seasons-calendar-seasonto"><?php echo date($df, $s['to_ts']); ?></span>
					</div>
					<div class="vbo-seasons-calendar-seasonchargedisc">
						<span class="vbo-seasons-calendar-slabel vbo-seasons-calendar-season-<?php echo $s['type'] == 2 ? 'discount' : 'charge'; ?>"><span class="vbo-seasons-calendar-operator"><?php echo $s['type'] == 2 ? '-' : '+'; ?></span><?php echo $s_val_diff; ?></span>
					</div>
					<span class="vbo-seasons-calendar-seasonname"><a href="index.php?option=com_vikbooking&amp;task=editseason&amp;cid[]=<?php echo $s['id']; ?>" target="_blank"><?php echo $s['spname']; ?></a></span>
				<?php
				if($los_show && array_key_exists($s_id, $seasons_cal['restrictions']) && count($restr_diff_nights) == 0) {
					//Season Restrictions
					$season_restrictions = array();
					foreach ($seasons_cal['restrictions'][$s_id] as $restr) {
						$season_restrictions = $restr;
						break;
					}
					?>
					<div class="vbo-seasons-calendar-restrictions">
					<?php
					if($season_restrictions['minlos'] > 1) {
						?>
						<span class="vbo-seasons-calendar-restriction-minlos"><?php echo JText::_('VBORESTRMINLOS'); ?><span class="vbo-seasons-calendar-restriction-minlos-badge"><?php echo $season_restrictions['minlos']; ?></span></span>
						<?php
					}
					if(array_key_exists('maxlos', $season_restrictions) && $season_restrictions['maxlos'] > 1) {
						?>
						<span class="vbo-seasons-calendar-restriction-maxlos"><?php echo JText::_('VBORESTRMAXLOS'); ?><span class="vbo-seasons-calendar-restriction-maxlos-badge"><?php echo $season_restrictions['maxlos']; ?></span></span>
						<?php
					}
					if(array_key_exists('wdays', $season_restrictions) && count($season_restrictions['wdays']) > 0) {
						?>
						<div class="vbo-seasons-calendar-restriction-wdays">
							<label><?php echo JText::_((count($season_restrictions['wdays']) > 1 ? 'VBORESTRARRIVWDAYS' : 'VBORESTRARRIVWDAY')); ?></label>
						<?php
						foreach ($season_restrictions['wdays'] as $wday) {
							?>
							<span class="vbo-seasons-calendar-restriction-wday"><?php echo vikbooking::sayWeekDay($wday); ?></span>
							<?php
						}
						?>
						</div>
						<?php
					}
					?>
					</div>
					<?php
				}
				?>
				</td>
				<?php
				if(array_key_exists($s_id, $seasons_cal['season_prices']) && count($seasons_cal['season_prices'][$s_id]) > 0) {
					foreach ($seasons_cal['season_prices'][$s_id] as $numnights => $tars) {
						$show_day_cost = true;
						if($los_show && array_key_exists($s_id, $seasons_cal['restrictions']) && array_key_exists($numnights, $seasons_cal['restrictions'][$s_id])) {
							if($seasons_cal['restrictions'][$s_id][$numnights]['allowed'] === false) {
								$show_day_cost = false;
							}
						}
						?>
				<td>
					<?php
					if($show_day_cost) {
					?>
					<div class="vbo-seasons-calendar-seasoncosts">
						<?php
						foreach ($tars as $tar) {
							?>
						<div class="vbo-seasons-calendar-seasoncost">
							<?php
							if($price_types_show) {
							?>
							<span class="vbo-seasons-calendar-pricename"><?php echo $tar['name']; ?></span>
							<?php
							}
							?>
							<span class="vbo-seasons-calendar-pricecost">
								<span class="vbo_currency"><?php echo $currencysymb; ?></span><span class="vbo_price"><?php echo vikbooking::numberFormat($tar['cost']); ?></span>
							</span>
						</div>
							<?php
							if(!$price_types_show) {
								break;
							}
						}
						?>
					</div>
					<?php
					}else {
						?>
						<div class="vbo-seasons-calendar-seasoncosts-disabled"></div>
						<?php
					}
					?>
				</td>
						<?php
					}
				}
				?>
			</tr>
				<?php
			}
			?>
		</table>
			<?php
			//End Seasons Calendar
		}else {
			?>
		<p class="vbo-warning"><?php echo JText::_('VBOWARNNORATESROOM'); ?></p>
			<?php
		}
		?>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
			<input type="hidden" name="task" value="">
			<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<script>
		jQuery(document).ready(function() {
			jQuery("body").on("click", ".vbo-ratesoverview-numnight", function() {
				var inpnight = jQuery(this).attr('id');
				if(jQuery('.vbo-ratesoverview-numnight').length > 1) {
					jQuery('#inp'+inpnight).remove();
					jQuery(this).remove();
				}
			});
			jQuery("body").on("dblclick", ".vbo-calcrates-rateblock", function() {
				jQuery(this).remove();
			});
			jQuery('#vbo-addnumnight-act').click(function() {
				var setnights = jQuery('#vbo-addnumnight').val();
				if(parseInt(setnights) > 0) {
					var los_exists = false;
					jQuery('.vbo-ratesoverview-numnight').each(function() {
						if(parseInt(jQuery(this).text()) == parseInt(setnights)) {
							los_exists = true;
						}
					});
					if(!los_exists) {
						jQuery('.vbo-ratesoverview-numnight').last().after("<span class=\"vbo-ratesoverview-numnight\" id=\"numnights"+setnights+"\">"+setnights+"</span><input type=\"hidden\" name=\"nights_cal[]\" id=\"inpnumnights"+setnights+"\" value=\""+setnights+"\" />");
					}else {
						jQuery('#vbo-addnumnight').val((parseInt(setnights) + 1));
					}
				}
			});
			jQuery('#vbo-ratesoverview-calculate').click(function() {
				jQuery(this).text('<?php echo addslashes(JText::_('VBRATESOVWRATESCALCULATORCALCING')); ?>').prop('disabled', true);
				jQuery('.vbo-ratesoverview-calculation-response').html('');
				var checkindate = jQuery("#checkindate").val();
				if(!(checkindate.length > 0)) {
					checkindate = '<?php echo date('Y-m-d') ?>';
					jQuery("#checkindate").val(checkindate);
				}
				var nights = jQuery("#vbo-numnights").val();
				var adults = jQuery("#vbo-numadults").val();
				var children = jQuery("#vbo-numchildren").val();
				var jqxhr = jQuery.ajax({
					type: "POST",
					url: "index.php",
					data: { option: "com_vikbooking", task: "calc_rates", tmpl: "component", id_room: "<?php echo $roomrows['id']; ?>", checkin: checkindate, num_nights: nights, num_adults: adults, num_children: children }
				}).done(function(res) {
					if(res.indexOf('e4j.error') >= 0 ) {
						jQuery(".vbo-ratesoverview-calculation-response").html("<p class='vbo-warning'>" + res.replace("e4j.error.", "") + "</p>").fadeIn();
					}else {
						jQuery(".vbo-ratesoverview-calculation-response").html(res).fadeIn();
					}
					jQuery('#vbo-ratesoverview-calculate').text('<?php echo addslashes(JText::_('VBRATESOVWRATESCALCULATORCALC')); ?>').prop('disabled', false);
				}).fail(function() { 
					jQuery(".vbo-ratesoverview-calculation-response").fadeOut();
					jQuery('#vbo-ratesoverview-calculate').text('<?php echo addslashes(JText::_('VBRATESOVWRATESCALCULATORCALC')); ?>').prop('disabled', false);
					alert("Error Performing Ajax Request"); 
				});
			});
		});
		</script>
		<?php
	}

	public static function pViewTranslations($vbo_tn, $option) {
		$editor = JFactory::getEditor();
		$langs = $vbo_tn->getLanguagesList();
		$xml_tables = $vbo_tn->getTranslationTables();
		$active_table = '';
		$active_table_key = '';
		if(!(count($langs) > 1)) {
			//Error: only one language is published. Translations are useless
			?>
			<p class="err"><?php echo JText::_('VBTRANSLATIONERRONELANG'); ?></p>
			<form name="adminForm" id="adminForm" action="index.php" method="post">
				<input type="hidden" name="task" value="">
				<input type="hidden" name="option" value="<?php echo $option; ?>">
			</form>
			<?php
		}elseif(!(count($xml_tables) > 0) || strlen($vbo_tn->getError())) {
			//Error: XML file not readable or errors occurred
			?>
			<p class="err"><?php echo $vbo_tn->getError(); ?></p>
			<form name="adminForm" id="adminForm" action="index.php" method="post">
				<input type="hidden" name="task" value="">
				<input type="hidden" name="option" value="<?php echo $option; ?>">
			</form>
			<?php
		}else {
			$cur_langtab = JRequest::getString('vbo_lang', '', 'request');
			$table = JRequest::getString('vbo_table', '', 'request');
			if(!empty($table)) {
				$table = $vbo_tn->replacePrefix($table);
			}
		?>
		<script type="text/Javascript">
		var vbo_tn_changes = false;
		jQuery(document).ready(function(){
			jQuery('#adminForm input[type=text], #adminForm textarea').change(function() {
				vbo_tn_changes = true;
			});
		});
		function vboCheckChanges() {
			if(!vbo_tn_changes) {
				return true;
			}
			return confirm("<?php echo addslashes(JText::_('VBTANSLATIONSCHANGESCONF')); ?>");
		}
		</script>
		<form action="index.php?option=com_vikbooking&amp;task=translations" method="post" onsubmit="return vboCheckChanges();">
			<div style="width: 100%; display: inline-block;" class="btn-toolbar" id="filter-bar">
				<div class="btn-group pull-right">
					<button class="btn" type="submit"><?php echo JText::_('VBOGETTRANSLATIONS'); ?></button>
				</div>
				<div class="btn-group pull-right">
					<select name="vbo_table">
						<option value="">-----------</option>
					<?php
					foreach ($xml_tables as $key => $value) {
						$active_table = $vbo_tn->replacePrefix($key) == $table ? $value : $active_table;
						$active_table_key = $vbo_tn->replacePrefix($key) == $table ? $key : $active_table_key;
						?>
						<option value="<?php echo $key; ?>"<?php echo $vbo_tn->replacePrefix($key) == $table ? ' selected="selected"' : ''; ?>><?php echo $value; ?></option>
						<?php
					}
					?>
					</select>
				</div>
			</div>
			<input type="hidden" name="vbo_lang" class="vbo_lang" value="<?php echo $vbo_tn->default_lang; ?>">
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="translations" />
		</form>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
			<div class="vbo-translation-langtabs">
		<?php
		foreach ($langs as $ltag => $lang) {
			$is_def = ($ltag == $vbo_tn->default_lang);
			$lcountry = substr($ltag, 0, 2);
			$flag = file_exists(JPATH_SITE.DS.'media'.DS.'mod_languages'.DS.'images'.DS.$lcountry.'.gif') ? '<img src="'.JURI::root().'media/mod_languages/images/'.$lcountry.'.gif"/>' : '';
				?>
				<div class="vbo-translation-tab<?php echo $is_def ? ' vbo-translation-tab-default' : ''; ?>" data-vbolang="<?php echo $ltag; ?>">
				<?php
				if(!empty($flag)) {
					?>
					<span class="vbo-translation-flag"><?php echo $flag; ?></span>
					<?php
				}
				?>
					<span class="vbo-translation-langname"><?php echo $lang['name']; ?></span>
				</div>
			<?php
		}
		?>
				<div class="vbo-translation-tab vbo-translation-tab-ini" data-vbolang="">
					<span class="vbo-translation-iniflag">.INI</span>
					<span class="vbo-translation-langname"><?php echo JText::_('VBTRANSLATIONINISTATUS'); ?></span>
				</div>
			</div>
			<div class="vbo-translation-tabscontents">
		<?php
		$table_cols = !empty($active_table_key) ? $vbo_tn->getTableColumns($active_table_key) : array();
		$table_def_dbvals = !empty($active_table_key) ? $vbo_tn->getTableDefaultDbValues($active_table_key, array_keys($table_cols)) : array();
		if(!empty($active_table_key)) {
			echo '<input type="hidden" name="vbo_table" value="'.$active_table_key.'"/>'."\n";
		}
		foreach ($langs as $ltag => $lang) {
			$is_def = ($ltag == $vbo_tn->default_lang);
			?>
				<div class="vbo-translation-langcontent" style="display: <?php echo $is_def ? 'block' : 'none'; ?>;" id="vbo_langcontent_<?php echo $ltag; ?>">
			<?php
			if(empty($active_table_key)) {
				?>
					<p class="warn"><?php echo JText::_('VBTRANSLATIONSELTABLEMESS'); ?></p>
				<?php
			}elseif(strlen($vbo_tn->getError()) > 0) {
				?>
					<p class="err"><?php echo $vbo_tn->getError(); ?></p>
				<?php
			}else {
				?>
					<fieldset class="adminform">
						<legend class="adminlegend"><?php echo $active_table; ?> - <?php echo $lang['name'].($is_def ? ' - '.JText::_('VBTRANSLATIONDEFLANG') : ''); ?></legend>
						<table cellspacing="1" class="admintable table">
							<tbody>
				<?php
				if($is_def) {
					//Values of Default Language to be translated
					foreach ($table_def_dbvals as $reference_id => $values) {
						?>
								<tr data-reference="<?php echo $ltag.'-'.$reference_id; ?>">
									<td class="vbo-translate-reference-cell" colspan="2"><?php echo $vbo_tn->getRecordReferenceName($table_cols, $values); ?></td>
								</tr>
						<?php
						foreach ($values as $field => $def_value) {
							$title = $table_cols[$field]['jlang'];
							$type = $table_cols[$field]['type'];
							if($type == 'html') {
								$def_value = strip_tags($def_value);
							}
							?>
								<tr data-reference="<?php echo $ltag.'-'.$reference_id; ?>">
									<td width="200" class="vbo-translate-column-cell"> <b><?php echo $title; ?></b> </td>
									<td><?php echo $type != 'json' ? $def_value : ''; ?></td>
								</tr>
							<?php
							if($type == 'json') {
								$tn_keys = $table_cols[$field]['keys'];
								$keys = !empty($tn_keys) ? explode(',', $tn_keys) : array();
								$json_def_values = json_decode($def_value, true);
								if(count($json_def_values) > 0) {
									foreach ($json_def_values as $jkey => $jval) {
										if((!in_array($jkey, $keys) && count($keys) > 0) || empty($jval)) {
											continue;
										}
										?>
								<tr data-reference="<?php echo $ltag.'-'.$reference_id; ?>">
									<td width="200" class="vbo-translate-column-cell"><?php echo !is_numeric($jkey) ? ucwords($jkey) : '&nbsp;'; ?></td>
									<td><?php echo $jval; ?></td>
								</tr>
										<?php
									}
								}
							}
							?>
							<?php
						}
					}
				}else {
					//Translation Fields for this language
					$lang_record_tn = $vbo_tn->getTranslatedTable($active_table_key, $ltag);
					foreach ($table_def_dbvals as $reference_id => $values) {
						?>
								<tr data-reference="<?php echo $ltag.'-'.$reference_id; ?>">
									<td class="vbo-translate-reference-cell" colspan="2"><?php echo $vbo_tn->getRecordReferenceName($table_cols, $values); ?></td>
								</tr>
						<?php
						foreach ($values as $field => $def_value) {
							$title = $table_cols[$field]['jlang'];
							$type = $table_cols[$field]['type'];
							if($type == 'skip') {
								continue;
							}
							$tn_value = '';
							$tn_class = ' vbo-missing-translation';
							if(array_key_exists($reference_id, $lang_record_tn) && array_key_exists($field, $lang_record_tn[$reference_id]['content']) && strlen($lang_record_tn[$reference_id]['content'][$field])) {
								if(in_array($type, array('text', 'textarea', 'html'))) {
									$tn_class = ' vbo-field-translated';
								}else {
									$tn_class = '';
								}
							}
							?>
								<tr data-reference="<?php echo $ltag.'-'.$reference_id; ?>">
									<td width="200" class="vbo-translate-column-cell<?php echo $tn_class; ?>"<?php echo in_array($type, array('textarea', 'html')) ? ' style="vertical-align: top !important;"' : ''; ?>> <b><?php echo $title; ?></b> </td>
									<td>
							<?php
							if($type == 'text') {
								if(array_key_exists($reference_id, $lang_record_tn) && array_key_exists($field, $lang_record_tn[$reference_id]['content'])) {
									$tn_value = $lang_record_tn[$reference_id]['content'][$field];
								}
								?>
										<input type="text" name="tn[<?php echo $ltag; ?>][<?php echo $reference_id; ?>][<?php echo $field; ?>]" value="<?php echo $tn_value; ?>" size="40" placeholder="<?php echo $def_value; ?>"/>
								<?php
							}elseif($type == 'textarea') {
								if(array_key_exists($reference_id, $lang_record_tn) && array_key_exists($field, $lang_record_tn[$reference_id]['content'])) {
									$tn_value = $lang_record_tn[$reference_id]['content'][$field];
								}
								?>
										<textarea name="tn[<?php echo $ltag; ?>][<?php echo $reference_id; ?>][<?php echo $field; ?>]" rows="5" cols="40"><?php echo $tn_value; ?></textarea>
								<?php
							}elseif($type == 'html') {
								if(array_key_exists($reference_id, $lang_record_tn) && array_key_exists($field, $lang_record_tn[$reference_id]['content'])) {
									$tn_value = $lang_record_tn[$reference_id]['content'][$field];
								}
								echo $editor->display( "tn[".$ltag."][".$reference_id."][".$field."]", $tn_value, 500, 350, 70, 20, true, "tn_".$ltag."_".$reference_id."_".$field );
							}
							?>
									</td>
								</tr>
							<?php
							if($type == 'json') {
								$tn_keys = $table_cols[$field]['keys'];
								$keys = !empty($tn_keys) ? explode(',', $tn_keys) : array();
								$json_def_values = json_decode($def_value, true);
								if(count($json_def_values) > 0) {
									$tn_json_value = array();
									if(array_key_exists($reference_id, $lang_record_tn) && array_key_exists($field, $lang_record_tn[$reference_id]['content'])) {
										$tn_json_value = json_decode($lang_record_tn[$reference_id]['content'][$field], true);
									}
									foreach ($json_def_values as $jkey => $jval) {
										if((!in_array($jkey, $keys) && count($keys) > 0) || empty($jval)) {
											continue;
										}
										?>
								<tr data-reference="<?php echo $ltag.'-'.$reference_id; ?>">
									<td width="200" class="vbo-translate-column-cell"><?php echo !is_numeric($jkey) ? ucwords($jkey) : '&nbsp;'; ?></td>
									<td>
									<?php
									if(strlen($jval) > 40) {
									?>
										<textarea rows="5" cols="170" style="min-width: 60%;" name="tn[<?php echo $ltag; ?>][<?php echo $reference_id; ?>][<?php echo $field; ?>][<?php echo $jkey; ?>]"><?php echo $tn_json_value[$jkey]; ?></textarea>
									<?php
									}else {
									?>
										<input type="text" name="tn[<?php echo $ltag; ?>][<?php echo $reference_id; ?>][<?php echo $field; ?>][<?php echo $jkey; ?>]" value="<?php echo $tn_json_value[$jkey]; ?>" size="40"/>
									<?php
									}
									?>
									</td>
								</tr>
										<?php
									}
								}
							}
						}
					}
				}
				?>
							</tbody>
						</table>
					</fieldset>
				<?php
				//echo '<pre>'.print_r($table_def_dbvals, true).'</pre><br/>';
				//echo '<pre>'.print_r($table_cols, true).'</pre><br/>';
			}
			?>
				</div>
			<?php
		}
		//ini files status
		$all_inis = $vbo_tn->getIniFiles();
		?>
				<div class="vbo-translation-langcontent" style="display: none;" id="vbo_langcontent_ini">
					<fieldset class="adminform">
						<legend class="adminlegend">.INI <?php echo JText::_('VBTRANSLATIONINISTATUS'); ?></legend>
						<table cellspacing="1" class="admintable table">
							<tbody>
							<?php
							foreach ($all_inis as $initype => $inidet) {
								$inipath = $inidet['path'];
								?>
								<tr>
									<td class="vbo-translate-reference-cell" colspan="2"><?php echo JText::_('VBINIEXPL'.strtoupper($initype)); ?></td>
								</tr>
								<?php
								foreach ($langs as $ltag => $lang) {
									$t_file_exists = file_exists(str_replace('en-GB', $ltag, $inipath));
									$t_parsed_ini = $t_file_exists ? parse_ini_file(str_replace('en-GB', $ltag, $inipath)) : false;
									?>
								<tr>
									<td width="200" class="vbo-translate-column-cell <?php echo $t_file_exists ? 'vbo-field-translated' : 'vbo-missing-translation'; ?>"> <b><?php echo ($ltag == 'en-GB' ? 'Native ' : '').$lang['name']; ?></b> </td>
									<td>
										<span class="vbo-inifile-totrows <?php echo $t_file_exists ? 'vbo-inifile-exists' : 'vbo-inifile-notfound'; ?>"><?php echo $t_file_exists && $t_parsed_ini !== false ? JText::_('VBOINIDEFINITIONS').': '.count($t_parsed_ini) : JText::_('VBOINIMISSINGFILE'); ?></span>
										<span class="vbo-inifile-path <?php echo $t_file_exists ? 'vbo-inifile-exists' : 'vbo-inifile-notfound'; ?>"><?php echo JText::_('VBOINIPATH').': '.str_replace('en-GB', $ltag, $inipath); ?></span>
									</td>
								</tr>
									<?php
								}
							}
							?>
							</tbody>
						</table>
					</fieldset>
				</div>
			<?php
			//end ini files status
			?>
			</div>
			<input type="hidden" name="vbo_lang" class="vbo_lang" value="<?php echo $vbo_tn->default_lang; ?>">
			<input type="hidden" name="task" value="">
			<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<script type="text/Javascript">
		jQuery(document).ready(function(){
			jQuery('.vbo-translation-tab').click(function() {
				var langtag = jQuery(this).attr('data-vbolang');
				if(jQuery('#vbo_langcontent_'+langtag).length) {
					jQuery('.vbo_lang').val(langtag);
					jQuery('.vbo-translation-tab').removeClass('vbo-translation-tab-default');
					jQuery(this).addClass('vbo-translation-tab-default');
					jQuery('.vbo-translation-langcontent').hide();
					jQuery('#vbo_langcontent_'+langtag).fadeIn();
				}else {
					jQuery('.vbo-translation-tab').removeClass('vbo-translation-tab-default');
					jQuery(this).addClass('vbo-translation-tab-default');
					jQuery('.vbo-translation-langcontent').hide();
					jQuery('#vbo_langcontent_ini').fadeIn();
				}
			});
		<?php
		if(!empty($cur_langtab)) {
			?>
			jQuery('.vbo-translation-tab').each(function() {
				var langtag = jQuery(this).attr('data-vbolang');
				if(langtag != '<?php echo $cur_langtab; ?>') {
					return true;
				}
				if(jQuery('#vbo_langcontent_'+langtag).length) {
					jQuery('.vbo_lang').val(langtag);
					jQuery('.vbo-translation-tab').removeClass('vbo-translation-tab-default');
					jQuery(this).addClass('vbo-translation-tab-default');
					jQuery('.vbo-translation-langcontent').hide();
					jQuery('#vbo_langcontent_'+langtag).fadeIn();
				}
			});
			<?php
		}
		?>
		});
		</script>
		<?php
		}
	}

	public static function pViewCustomers ($rows, $option, $lim0="0", $navbut="", $orderby="name", $ordersort="ASC") {
		$pfiltercustomer = JRequest::getString('filtercustomer', '', 'request');
		?>
		<form action="index.php?option=com_vikbooking&amp;task=customers" method="post" name="customersform">
			<div style="width: 100%; display: inline-block;" class="btn-toolbar" id="filter-bar">
				<div class="btn-group pull-right hidden-phone">
					<button type="button" class="btn" onclick="document.customersform.submit();"><i class="icon-search"></i></button>
					<button type="button" class="btn" onclick="document.getElementById('filtercustomer').value='';document.customersform.submit();"><i class="icon-remove"></i></button>
				</div>
				<div class="btn-group pull-right">
					<input type="text" name="filtercustomer" id="filtercustomer" value="<?php echo $pfiltercustomer; ?>" size="40" placeholder="<?php echo JText::_( 'VBCUSTOMERFIRSTNAME' ).', '.JText::_( 'VBCUSTOMERLASTNAME' ).', '.JText::_( 'VBCUSTOMEREMAIL' ).', '.JText::_( 'VBCUSTOMERPIN' ); ?>"/>
				</div>
			</div>
			<input type="hidden" name="task" value="customers" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
		</form>
		<?php
		if(empty($rows)){
			?>
			<p class="warn"><?php echo JText::_('VBNOCUSTOMERS'); ?></p>
			<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
			<?php
		}else{
		
		?>
	<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped">
		<thead>
		<tr>
			<th width="20">
				<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
			</th>
			<th class="title left" width="75"><a href="index.php?option=com_vikbooking&amp;task=customers&amp;vborderby=first_name&amp;vbordersort=<?php echo ($orderby == "first_name" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "first_name" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "first_name" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBCUSTOMERFIRSTNAME' ); ?></a></th>
			<th class="title left" width="75"><a href="index.php?option=com_vikbooking&amp;task=customers&amp;vborderby=last_name&amp;vbordersort=<?php echo ($orderby == "last_name" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "last_name" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "last_name" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBCUSTOMERLASTNAME' ); ?></a></th>
			<th class="title left" width="75"><a href="index.php?option=com_vikbooking&amp;task=customers&amp;vborderby=email&amp;vbordersort=<?php echo ($orderby == "email" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "email" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "email" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBCUSTOMEREMAIL' ); ?></a></th>
			<th class="title left" width="75"><a href="index.php?option=com_vikbooking&amp;task=customers&amp;vborderby=phone&amp;vbordersort=<?php echo ($orderby == "phone" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "phone" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "phone" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBCUSTOMERPHONE' ); ?></a></th>
			<th class="title center" width="75"><a href="index.php?option=com_vikbooking&amp;task=customers&amp;vborderby=country&amp;vbordersort=<?php echo ($orderby == "country" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "country" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "country" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBCUSTOMERCOUNTRY' ); ?></a></th>
			<th class="title center" width="75"><a href="index.php?option=com_vikbooking&amp;task=customers&amp;vborderby=pin&amp;vbordersort=<?php echo ($orderby == "pin" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "pin" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "pin" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBCUSTOMERPIN' ); ?></a></th>
			<th class="title center" width="75"><a href="index.php?option=com_vikbooking&amp;task=customers&amp;vborderby=tot_bookings&amp;vbordersort=<?php echo ($orderby == "tot_bookings" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "tot_bookings" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "tot_bookings" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBCUSTOMERTOTBOOKINGS' ); ?></a></th>
			<th class="title center" width="75">&nbsp;</th>
			<th class="title center" width="75">&nbsp;</th>
		</tr>
		</thead>
		<?php
		$kk = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$country_flag = '';
			if(!empty($row['country']) && !empty($row['country_full_name'])) {
				if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'countries'.DS.$row['country'].'.png')) {
					$country_flag = '<img src="'.JURI::root().'administrator/components/com_vikbooking/resources/countries/'.$row['country'].'.png'.'" title="'.$row['country_full_name'].'" class="vbo-country-flag"/>';
				}
			}
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td><a href="index.php?option=com_vikbooking&amp;task=editcustomer&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['first_name']; ?></a></td>
				<td><?php echo $row['last_name']; ?></td>
				<td><?php echo $row['email']; ?></td>
				<td><?php echo $row['phone']; ?></td>
				<td class="center"><?php echo empty($country_flag) ? $row['country'] : $country_flag; ?></td>
				<td class="center"><?php echo $row['pin']; ?></td>
				<td class="center"><?php echo $row['tot_bookings']; ?></td>
				<td class="center"><?php echo ($row['tot_bookings'] > 0 ? '<a href="index.php?option=com_vikbooking&task=vieworders&cust_id='.$row['id'].'" class="btn hasTooltip" title="'.JText::_('VBMENUSEVEN').'"><i class="icon-eye"></i></a>' : ''); ?></td>
				<td class="center"><?php echo (!empty($row['phone']) ? '<button type="button" class="btn hasTooltip" onclick="vboToggleSendSMS(\''.$row['phone'].'\', \''.addslashes($row['first_name']).'\');" title="'.JText::_('VBSENDSMSACTION').'"><i class="vboicn-bubbles"></i></button>' : ''); ?></td>
			 </tr>
			  <?php
			$kk = 1 - $kk;
		}
		?>
		
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="customers" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php echo $navbut; ?>
	</form>
	<div class="vbo-info-overlay-block">
		<div class="vbo-info-overlay-content vbo-info-overlay-content-sendsms">
			<h4><?php echo JText::_('VBSENDSMSACTION') ?>: <span id="smstophone-lbl"></span></h4>
			<form action="index.php?option=com_vikbooking" method="post">
				<div class="vbo-calendar-cfield-entry">
					<label for="smscont"><?php echo JText::_('VBSENDSMSCUSTCONT') ?></label>
					<span><textarea name="smscont" id="smscont" style="width: 99%; min-width: 99%;max-width: 99%; height: 35%;"></textarea></span>
				</div>
				<div class="vbo-calendar-cfields-bottom">
					<button type="submit" class="btn"><i class="vboicn-bubbles"></i><?php echo JText::_('VBSENDSMSACTION') ?></button>
				</div>
				<input type="hidden" name="phone" id="smstophone" value="" />
				<input type="hidden" name="goto" value="<?php echo urlencode('index.php?option=com_vikbooking&task=customers&limitstart='.$lim0); ?>" />
				<input type="hidden" name="task" value="sendcustomsms" />
			</form>
		</div>
	</div>
	<script type="text/javascript">
	var vbo_overlay_on = false;
	if(jQuery.isFunction(jQuery.fn.tooltip)) {
		jQuery(".hasTooltip").tooltip();
	}
	function vboToggleSendSMS(phone, firstname) {
		jQuery("#smstophone").val(phone);
		jQuery("#smstophone-lbl").text(firstname+" "+phone);
		jQuery(".vbo-info-overlay-block").fadeToggle(400, function() {
			if(jQuery(".vbo-info-overlay-block").is(":visible")) {
				vbo_overlay_on = true;
			}else {
				vbo_overlay_on = false;
			}
		});
	}
	jQuery(document).ready(function(){
		jQuery(document).mouseup(function(e) {
			if(!vbo_overlay_on) {
				return false;
			}
			var vbo_overlay_cont = jQuery(".vbo-info-overlay-content");
			if(!vbo_overlay_cont.is(e.target) && vbo_overlay_cont.has(e.target).length === 0) {
				jQuery(".vbo-info-overlay-block").fadeOut();
				vbo_overlay_on = false;
			}
		});
		jQuery(document).keyup(function(e) {
			if (e.keyCode == 27 && vbo_overlay_on) {
				jQuery(".vbo-info-overlay-block").fadeOut();
				vbo_overlay_on = false;
			}
		});
	});
	</script>
	<?php
		}
	}

	public static function pNewCustomer ($wselcountries, $option) {
		//JHtmlList::users(string $name, string $active, integer $nouser, string $javascript = null, string $order = 'name')
		if(!class_exists('JHtmlList')) {
			jimport( 'joomla.html.html.list' );
		}
		?>
		<script type="text/Javascript">
		function getRandomPin(min, max) {
			return Math.floor(Math.random() * (max - min)) + min;
		}
		function generatePin() {
			var pin = getRandomPin(10999, 99999);
			document.getElementById('pin').value = pin;
		}
		</script>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
			<table class="adminform">
				<tr><td width="200">&bull; <b><?php echo JText::_('VBCUSTOMERFIRSTNAME'); ?>:</b> </td><td><input type="text" name="first_name" value="" size="30"/></td></tr>
				<tr><td width="200">&bull; <b><?php echo JText::_('VBCUSTOMERLASTNAME'); ?>:</b> </td><td><input type="text" name="last_name" value="" size="30"/></td></tr>
				<tr><td width="200">&bull; <b><?php echo JText::_('VBCUSTOMEREMAIL'); ?>:</b> </td><td><input type="text" name="email" value="" size="30"/></td></tr>
				<tr><td width="200">&bull; <b><?php echo JText::_('VBCUSTOMERPHONE'); ?>:</b> </td><td><input type="text" name="phone" value="" size="30"/></td></tr>
				<tr><td width="200">&bull; <b><?php echo JText::_('VBCUSTOMERCOUNTRY'); ?>:</b> </td><td><?php echo $wselcountries; ?></td></tr>
				<tr><td width="200">&bull; <b><?php echo JText::_('VBCUSTOMERPIN'); ?>:</b> </td><td><input type="text" name="pin" id="pin" value="" size="6" placeholder="54321"/> &nbsp;&nbsp; <button type="button" class="btn" onclick="generatePin();"><?php echo JText::_('VBCUSTOMERGENERATEPIN'); ?></button></td></tr>
				<tr><td width="200">&bull; <b>Joomla User:</b> </td><td><?php echo JHtmlList::users('ujid', '', 1); ?></td></tr>
			</table>
			<input type="hidden" name="task" value="">
			<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<?php
	}

	public static function pEditCustomer ($customer, $wselcountries, $option) {
		//JHtmlList::users(string $name, string $active, integer $nouser, string $javascript = null, string $order = 'name')
		if(!class_exists('JHtmlList')) {
			jimport( 'joomla.html.html.list' );
		}
		?>
		<script type="text/Javascript">
		function getRandomPin(min, max) {
			return Math.floor(Math.random() * (max - min)) + min;
		}
		function generatePin() {
			var pin = getRandomPin(10999, 99999);
			document.getElementById('pin').value = pin;
		}
		</script>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
			<table class="adminform">
				<tr><td width="200">&bull; <b><?php echo JText::_('VBCUSTOMERFIRSTNAME'); ?>:</b> </td><td><input type="text" name="first_name" value="<?php echo $customer['first_name']; ?>" size="30"/></td></tr>
				<tr><td width="200">&bull; <b><?php echo JText::_('VBCUSTOMERLASTNAME'); ?>:</b> </td><td><input type="text" name="last_name" value="<?php echo $customer['last_name']; ?>" size="30"/></td></tr>
				<tr><td width="200">&bull; <b><?php echo JText::_('VBCUSTOMEREMAIL'); ?>:</b> </td><td><input type="text" name="email" value="<?php echo $customer['email']; ?>" size="30"/></td></tr>
				<tr><td width="200">&bull; <b><?php echo JText::_('VBCUSTOMERPHONE'); ?>:</b> </td><td><input type="text" name="phone" value="<?php echo $customer['phone']; ?>" size="30"/></td></tr>
				<tr><td width="200">&bull; <b><?php echo JText::_('VBCUSTOMERCOUNTRY'); ?>:</b> </td><td><?php echo $wselcountries; ?></td></tr>
				<tr><td width="200">&bull; <b><?php echo JText::_('VBCUSTOMERPIN'); ?>:</b> </td><td><input type="text" name="pin" id="pin" value="<?php echo $customer['pin']; ?>" size="6"/> &nbsp;&nbsp; <button type="button" class="btn" onclick="generatePin();"><?php echo JText::_('VBCUSTOMERGENERATEPIN'); ?></button></td></tr>
				<tr><td width="200">&bull; <b>Joomla User:</b> </td><td><?php echo JHtmlList::users('ujid', $customer['ujid'], 1); ?></td></tr>
			</table>
			<input type="hidden" name="where" value="<?php echo $customer['id']; ?>">
			<input type="hidden" name="task" value="">
			<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<?php
	}

	public static function pEditTmplFile($fpath, $option) {
		$editor = JFactory::getEditor('codemirror');
		$fcode = '';
		$fp = fopen($fpath, "rb");
		if (false === $fp || empty($fpath)) {
			?>
			<p class="err"><?php echo JText::_('VBOTMPLFILENOTREAD'); ?></p>
			<?php
		}else {
			while (!feof($fp)) {
				$fcode .= fread($fp, 8192);
			}
			fclose($fp);
		?>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
			<fieldset class="adminform">
				<legend class="adminlegend"><?php echo JText::_('VBOEDITTMPLFILE'); ?></legend>
				<p class="vbo-path-tmpl-file"><?php echo $fpath; ?></p>
				<?php echo $editor->display("cont", $fcode, 400, 300, 90, 40); ?>
				<br clear="all" />
				<p style="text-align: center;"><button type="submit" class="btn btn-success"><?php echo JText::_('VBOSAVETMPLFILE'); ?></button></p>
			</fieldset>
			<input type="hidden" name="path" value="<?php echo $fpath; ?>">
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="savetmplfile" />
		</form>
		<?php
		}
	}

	public static function pViewPackages ($rows, $option, $lim0="0", $navbut="", $orderby="id", $ordersort="DESC") {
		$nowdf = vikbooking::getDateFormat(true);
		$currencysymb = vikbooking::getCurrencySymb(true);
		if ($nowdf=="%d/%m/%Y") {
			$df='d/m/Y';
		}elseif ($nowdf=="%m/%d/%Y") {
			$df='m/d/Y';
		}else {
			$df='Y/m/d';
		}
		if(empty($rows)){
			?>
			<p class="warn"><?php echo JText::_('VBNOPACKAGES'); ?></p>
			<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
			<?php
		}else{
		
		?>
	<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped">
		<thead>
		<tr>
			<th width="20">
				<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
			</th>
			<th class="title left" width="50"><a href="index.php?option=com_vikbooking&amp;task=packages&amp;vborderby=id&amp;vbordersort=<?php echo ($orderby == "id" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "id" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "id" ? "vbsortdesc" : "")); ?>">ID</a></th>
			<th class="title left" width="150"><a href="index.php?option=com_vikbooking&amp;task=packages&amp;vborderby=name&amp;vbordersort=<?php echo ($orderby == "name" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "name" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "name" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBPACKAGESNAME' ); ?></a></th>
			<th class="title center" width="75"><a href="index.php?option=com_vikbooking&amp;task=packages&amp;vborderby=dfrom&amp;vbordersort=<?php echo ($orderby == "dfrom" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "dfrom" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "dfrom" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBPACKAGESDROM' ); ?></a></th>
			<th class="title center" width="75"><a href="index.php?option=com_vikbooking&amp;task=packages&amp;vborderby=dto&amp;vbordersort=<?php echo ($orderby == "dto" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "dto" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "dto" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBPACKAGESDTO' ); ?></a></th>
			<th class="title center" width="75"><a href="index.php?option=com_vikbooking&amp;task=packages&amp;vborderby=cost&amp;vbordersort=<?php echo ($orderby == "cost" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "cost" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "cost" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBPACKAGESCOST' ); ?></a></th>
			<th class="title center" width="75"><?php echo JText::_( 'VBPACKAGESROOMSCOUNT' ); ?></th>
		</tr>
		</thead>
		<?php
		$kk = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td><a href="index.php?option=com_vikbooking&amp;task=editpackage&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['id']; ?></a></td>
				<td><a href="index.php?option=com_vikbooking&amp;task=editpackage&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td class="center"><?php echo date($df, $row['dfrom']); ?></td>
				<td class="center"><?php echo date($df, $row['dto']); ?></td>
				<td class="center"><?php echo $currencysymb.' '.vikbooking::numberFormat($row['cost']); ?></td>
				<td class="center"><?php echo $row['tot_rooms']; ?></td>
			</tr>
			<?php
			$kk = 1 - $kk;
		}
		?>
		
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="packages" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php echo $navbut; ?>
	</form>
	<?php
		}
	}

	public static function pNewPackage ($rooms, $option) {
		$vbo_app = new VikApplication();
		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.calendar');
		$editor = JFactory::getEditor();
		$df=vikbooking::getDateFormat(true);
		$currencysymb=vikbooking::getCurrencySymb(true);
		$dbo = JFactory::getDBO();
		$q="SELECT * FROM `#__vikbooking_iva`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$ivas=$dbo->loadAssocList();
			$wiva="<select name=\"aliq\"><option value=\"\"> </option>\n";
			foreach($ivas as $iv){
				$wiva.="<option value=\"".$iv['id']."\">".(empty($iv['name']) ? $iv['aliq']."%" : $iv['name']."-".$iv['aliq']."%")."</option>\n";
			}
			$wiva.="</select>\n";
		}else {
			$wiva="<a href=\"index.php?option=com_vikbooking&task=viewiva\">".JText::_('VBNOIVAFOUND')."</a>";
		}
		?>
		<script type="text/javascript">
		function showResizeSel() {
			if(document.adminForm.autoresize.checked == true) {
				document.getElementById('resizesel').style.display='block';
			}else {
				document.getElementById('resizesel').style.display='none';
			}
			return true;
		}
		function vboExcludeWDays() {
			var excludewdays = document.getElementById('excludewdays');
			var vboexclusion = document.getElementById('vboexclusion');
			var weekday = '0';
			var setnew = false;
			var curdate = '';
			var curwday = '0';
			for(i = 0; i < excludewdays.length; i++) {
				weekday = parseInt(excludewdays.options[i].value);
				setnew = excludewdays.options[i].selected == false ? false : true;
				for(j = 0; j < vboexclusion.length; j++) {
					curdate = vboexclusion.options[j].value;
					var dateparts = curdate.split("-");
					var dobj = new Date(dateparts[2], (parseInt(dateparts[0]) - 1), dateparts[1]);
					curwday = parseInt(dobj.getDay());
					if(weekday == curwday) {
						vboexclusion.options[j].selected = setnew;
					}
				}
			}
		}
		jQuery.noConflict();
		jQuery(document).ready(function() {
			jQuery(".vbo-select-all").click(function(){
				jQuery(this).next("select").find("option").prop('selected', true);
			});
			jQuery('#vbo-pkg-calcexcld').click(function(){
				var fdate = jQuery('#from').val();
				var tdate = jQuery('#to').val();
				if(fdate.length && tdate.length) {
					jQuery('#vbo-pkg-excldates-td').html('');
					var jqxhr = jQuery.ajax({
						type: "POST",
						url: "index.php",
						data: { option: "com_vikbooking", task: "dayselectioncount", dinit: fdate, dend: tdate, tmpl: "component" }
					}).done(function(cont) {
						if(cont.length) {
							jQuery("#vbo-pkg-excldates-td").html(cont);
						}else {
							jQuery('#vbo-pkg-excldates-td').html('----');
						}
					}).fail(function() {
						alert("Error Calculating the dates for exclusion");
					});
				}else {
					jQuery('#vbo-pkg-excldates-td').html('----');
				}
			});
		});
		</script>
		<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data">
			<fieldset class="adminform">
				<table cellspacing="1" class="admintable table">
					<tbody>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGNAME'); ?></b> </td>
							<td><input type="text" name="name" value="" size="50"/></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGALIAS'); ?></b> </td>
							<td><input type="text" name="alias" value="" placeholder="<?php echo JFilterOutput::stringURLSafe(JText::_('VBNEWPKGNAME')); ?>" size="50"/></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGIMG'); ?></b> </td>
							<td><input type="file" name="img" size="35"/><br/><label style="display: inline;" for="autoresize"><?php echo JText::_('VBNEWOPTNINE'); ?></label> <input type="checkbox" id="autoresize" name="autoresize" value="1" onclick="showResizeSel();"/> <span id="resizesel" style="display: none;">&nbsp;<?php echo JText::_('VBNEWOPTTEN'); ?>: <input type="text" name="resizeto" value="500" size="3"/> px</span></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGDFROM'); ?></b> </td>
							<td><?php echo JHTML::_('calendar', '', 'from', 'from', $df, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGDTO'); ?></b> </td>
							<td><?php echo JHTML::_('calendar', '', 'to', 'to', $df, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGEXCLDATES'); ?></b> </td>
							<td><button type="button" class="btn" id="vbo-pkg-calcexcld"><i class="icon-refresh"></i></button><div id="vbo-pkg-excldates-td"></div></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGROOMS'); ?></b> </td>
							<td>
								<span class="vbo-select-all"><?php echo JText::_('VBOSELECTALL'); ?></span>
								<select name="rooms[]" multiple="multiple" size="<?php echo (count($rooms) > 6 ? '6' : count($rooms)); ?>">
								<?php
								foreach ($rooms as $rk => $rv) {
									?>
									<option value="<?php echo $rv['id']; ?>"><?php echo $rv['name']; ?></option>
									<?php
								}
								?>									
								</select>
							</td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGMINLOS'); ?></b> </td>
							<td><input type="number" name="minlos" id="minlos" value="1" min="1" size="5"/></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGMAXLOS'); ?></b> </td>
							<td><input type="number" name="maxlos" id="maxlos" value="0" min="0" size="5"/></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGCOST'); ?></b> </td>
							<td><input type="text" name="cost" id="cost" value="" size="5"/> <?php echo $currencysymb; ?></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWOPTFOUR'); ?></b> </td>
							<td><?php echo $wiva; ?></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGCOSTTYPE'); ?></b> </td>
							<td><select name="pernight_total"><option value="1"><?php echo JText::_('VBNEWPKGCOSTTYPEPNIGHT'); ?></option><option value="2"><?php echo JText::_('VBNEWPKGCOSTTYPETOTAL'); ?></option></select></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGCOSTTYPEPPERSON'); ?></b> </td>
							<td><?php echo $vbo_app->printYesNoButtons('perperson', JText::_('VBYES'), JText::_('VBNO'), 0, 1, 0); ?></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGSHOWOPT'); ?></b> </td>
							<td><select name="showoptions"><option value="1"><?php echo JText::_('VBNEWPKGSHOWOPTALL'); ?></option><option value="2"><?php echo JText::_('VBNEWPKGSHOWOPTOBL'); ?></option><option value="3"><?php echo JText::_('VBNEWPKGHIDEOPT'); ?></option></select></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGSHORTDESCR'); ?></b> </td>
							<td><textarea name="shortdescr" rows="4" cols="60"></textarea></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGDESCR'); ?></b> </td>
							<td><?php echo $editor->display( "descr", "", 400, 200, 70, 20 ); ?></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGCONDS'); ?></b> </td>
							<td><?php echo $editor->display( "conditions", "", 400, 200, 70, 20 ); ?></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGBENEFITS'); ?></b> </td>
							<td><textarea name="benefits" placeholder="<?php echo JText::_('VBNEWPKGBENEFITSHELP'); ?>" rows="3" cols="60"></textarea></td>
						</tr>
					</tbody>
				</table>
			</fieldset>
			<input type="hidden" name="task" value="">
			<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<?php
	}

	public static function pEditPackage ($package, $rooms, $option) {
		$vbo_app = new VikApplication();
		JHTML::_('behavior.modal');
		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.calendar');
		$editor = JFactory::getEditor();
		$df = vikbooking::getDateFormat(true);
		$excldf = 'Y-m-d';
		if ($df=="%d/%m/%Y") {
			$usedf='d/m/Y';
			$excldf = 'd-m-Y';
		}elseif ($df=="%m/%d/%Y") {
			$usedf='m/d/Y';
		}else {
			$usedf='Y/m/d';
		}
		$currencysymb = vikbooking::getCurrencySymb(true);
		$dbo = JFactory::getDBO();
		$q="SELECT * FROM `#__vikbooking_iva`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$ivas=$dbo->loadAssocList();
			$wiva="<select name=\"aliq\"><option value=\"\"> </option>\n";
			foreach($ivas as $iv){
				$wiva.="<option value=\"".$iv['id']."\"".($package['idiva']==$iv['id'] ? " selected=\"selected\"" : "").">".(empty($iv['name']) ? $iv['aliq']."%" : $iv['name']."-".$iv['aliq']."%")."</option>\n";
			}
			$wiva.="</select>\n";
		}else {
			$wiva="<a href=\"index.php?option=com_vikbooking&task=viewiva\">".JText::_('VBNOIVAFOUND')."</a>";
		}
		$actexcludedays = "";
		$diff = $package['dto'] - $package['dfrom'];
		$oldexcluded = !empty($package['excldates']) ? explode(";", $package['excldates']) : array();
		if($diff >= 172800) {
			$daysdiff = floor($diff / 86400);
			$infoinit = getdate($package['dfrom']);
			$actexcludedays .= '<select name="excludeday[]" multiple="multiple" size="'.($daysdiff > 8 ? 8 : $daysdiff).'">';
			for($i = 0; $i <= $daysdiff; $i++) {
				$ts = $i > 0 ? mktime(0, 0, 0, $infoinit['mon'], ((int)$infoinit['mday'] + $i), $infoinit['year']) : $package['dfrom'];
				$infots = getdate($ts);
				$optval = $infots['mon'].'-'.$infots['mday'].'-'.$infots['year'];
				$actexcludedays .= '<option value="'.$optval.'"'.(in_array($optval, $oldexcluded) ? ' selected="selected"' : '').'>'.date($excldf, $ts).'</option>';
			}
			$actexcludedays .= '</select>';
		}
		?>
		<script type="text/javascript">
		function showResizeSel() {
			if(document.adminForm.autoresize.checked == true) {
				document.getElementById('resizesel').style.display='block';
			}else {
				document.getElementById('resizesel').style.display='none';
			}
			return true;
		}
		function vboExcludeWDays() {
			var excludewdays = document.getElementById('excludewdays');
			var vboexclusion = document.getElementById('vboexclusion');
			var weekday = '0';
			var setnew = false;
			var curdate = '';
			var curwday = '0';
			for(i = 0; i < excludewdays.length; i++) {
				weekday = parseInt(excludewdays.options[i].value);
				setnew = excludewdays.options[i].selected == false ? false : true;
				for(j = 0; j < vboexclusion.length; j++) {
					curdate = vboexclusion.options[j].value;
					var dateparts = curdate.split("-");
					var dobj = new Date(dateparts[2], (parseInt(dateparts[0]) - 1), dateparts[1]);
					curwday = parseInt(dobj.getDay());
					if(weekday == curwday) {
						vboexclusion.options[j].selected = setnew;
					}
				}
			}
		}
		jQuery.noConflict();
		jQuery(document).ready(function() {
			jQuery(".vbo-select-all").click(function(){
				jQuery(this).next("select").find("option").prop('selected', true);
			});
			jQuery('#vbo-pkg-calcexcld').click(function(){
				var fdate = jQuery('#from').val();
				var tdate = jQuery('#to').val();
				if(fdate.length && tdate.length) {
					jQuery('#vbo-pkg-excldates-td').html('');
					var jqxhr = jQuery.ajax({
						type: "POST",
						url: "index.php",
						data: { option: "com_vikbooking", task: "dayselectioncount", dinit: fdate, dend: tdate, tmpl: "component" }
					}).done(function(cont) {
						if(cont.length) {
							jQuery("#vbo-pkg-excldates-td").html(cont);
						}else {
							jQuery('#vbo-pkg-excldates-td').html('----');
						}
					}).fail(function() {
						alert("Error Calculating the dates for exclusion");
					});
				}else {
					jQuery('#vbo-pkg-excldates-td').html('----');
				}
			});
			jQuery("#from").val("<?php echo date($usedf, $package['dfrom']); ?>");
			jQuery("#to").val("<?php echo date($usedf, $package['dto']); ?>");
		});
		</script>
		<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data">
			<fieldset class="adminform">
				<table cellspacing="1" class="admintable table">
					<tbody>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGNAME'); ?></b> </td>
							<td><input type="text" name="name" value="<?php echo $package['name']; ?>" size="50"/></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGALIAS'); ?></b> </td>
							<td><input type="text" name="alias" value="<?php echo $package['alias']; ?>" placeholder="<?php echo JFilterOutput::stringURLSafe(JText::_('VBNEWPKGNAME')); ?>" size="50"/></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGIMG'); ?></b> </td>
							<td><?php echo (!empty($package['img']) && file_exists(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS.'big_'.$package['img']) ? '<a href="'.JURI::root().'components/com_vikbooking/resources/uploads/big_'.$package['img'].'" class="modal" target="_blank">'.$package['img'].'</a> &nbsp;' : ""); ?><input type="file" name="img" size="35"/><br/><label style="display: inline;" for="autoresize"><?php echo JText::_('VBNEWOPTNINE'); ?></label> <input type="checkbox" id="autoresize" name="autoresize" value="1" onclick="showResizeSel();"/> <span id="resizesel" style="display: none;">&nbsp;<?php echo JText::_('VBNEWOPTTEN'); ?>: <input type="text" name="resizeto" value="500" size="3"/> px</span></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGDFROM'); ?></b> </td>
							<td><?php echo JHTML::_('calendar', '', 'from', 'from', $df, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGDTO'); ?></b> </td>
							<td><?php echo JHTML::_('calendar', '', 'to', 'to', $df, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGEXCLDATES'); ?></b> </td>
							<td><button type="button" class="btn" id="vbo-pkg-calcexcld"><i class="icon-refresh"></i></button><div id="vbo-pkg-excldates-td"><?php echo $actexcludedays; ?></div></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGROOMS'); ?></b> </td>
							<td>
								<span class="vbo-select-all"><?php echo JText::_('VBOSELECTALL'); ?></span>
								<select name="rooms[]" multiple="multiple" size="<?php echo (count($rooms) > 6 ? '6' : count($rooms)); ?>">
								<?php
								foreach ($rooms as $rk => $rv) {
									?>
									<option value="<?php echo $rv['id']; ?>"<?php echo (array_key_exists('selected', $rv) ? ' selected="selected"' : ''); ?>><?php echo $rv['name']; ?></option>
									<?php
								}
								?>									
								</select>
							</td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGMINLOS'); ?></b> </td>
							<td><input type="number" name="minlos" id="minlos" value="<?php echo $package['minlos']; ?>" min="1" size="5"/></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGMAXLOS'); ?></b> </td>
							<td><input type="number" name="maxlos" id="maxlos" value="<?php echo $package['maxlos']; ?>" min="0" size="5"/></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGCOST'); ?></b> </td>
							<td><input type="text" name="cost" id="cost" value="<?php echo $package['cost']; ?>" size="5"/> <?php echo $currencysymb; ?></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWOPTFOUR'); ?></b> </td>
							<td><?php echo $wiva; ?></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGCOSTTYPE'); ?></b> </td>
							<td><select name="pernight_total"><option value="1"<?php echo ($package['pernight_total'] == 1 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBNEWPKGCOSTTYPEPNIGHT'); ?></option><option value="2"<?php echo ($package['pernight_total'] == 2 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBNEWPKGCOSTTYPETOTAL'); ?></option></select></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGCOSTTYPEPPERSON'); ?></b> </td>
							<td><?php echo $vbo_app->printYesNoButtons('perperson', JText::_('VBYES'), JText::_('VBNO'), (int)$package['perperson'], 1, 0); ?></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGSHOWOPT'); ?></b> </td>
							<td><select name="showoptions"><option value="1"<?php echo ($package['showoptions'] == 1 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBNEWPKGSHOWOPTALL'); ?></option><option value="2"<?php echo ($package['showoptions'] == 2 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBNEWPKGSHOWOPTOBL'); ?></option><option value="3"<?php echo ($package['showoptions'] == 3 ? ' selected="selected"' : ''); ?>><?php echo JText::_('VBNEWPKGHIDEOPT'); ?></option></select></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGSHORTDESCR'); ?></b> </td>
							<td><textarea name="shortdescr" rows="4" cols="60"><?php echo $package['shortdescr']; ?></textarea></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGDESCR'); ?></b> </td>
							<td><?php echo $editor->display( "descr", $package['descr'], 400, 200, 70, 20 ); ?></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGCONDS'); ?></b> </td>
							<td><?php echo $editor->display( "conditions", $package['conditions'], 400, 200, 70, 20 ); ?></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBNEWPKGBENEFITS'); ?></b> </td>
							<td><textarea name="benefits" placeholder="<?php echo JText::_('VBNEWPKGBENEFITSHELP'); ?>" rows="3" cols="60"><?php echo $package['benefits']; ?></textarea></td>
						</tr>
					</tbody>
				</table>
			</fieldset>
			<input type="hidden" name="task" value="">
			<input type="hidden" name="option" value="<?php echo $option; ?>">
			<input type="hidden" name="whereup" value="<?php echo $package['id']; ?>">
		</form>
		<?php
	}

	public static function pViewStats($bookings, $fromts, $tots, $arr_months, $arr_channels, $arr_countries, $arr_totals, $option) {
		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.calendar');
		$df = vikbooking::getDateFormat(true);
		if ($df=="%d/%m/%Y") {
			$usedf='d/m/Y';
		}elseif ($df=="%m/%d/%Y") {
			$usedf='m/d/Y';
		}else {
			$usedf='Y/m/d';
		}
		$currencysymb = vikbooking::getCurrencySymb(true);
		$days_diff = (int)ceil(($tots - $fromts) / 86400);
		?>
		<form action="index.php?option=com_vikbooking&amp;task=stats" method="post" style="margin: 0;">
			<div id="filter-bar" class="btn-toolbar" style="width: 100%; display: inline-block;">
				<div class="btn-group pull-right">
					&nbsp;<button type="submit" class="btn"><?php echo JText::_('VBPVIEWORDERSSEARCHSUBM'); ?></button>
				</div>
				<div class="btn-group pull-right">
					<?php echo JHTML::_('calendar', '', 'dto', 'dto', $df, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?>
				</div>
				<div class="btn-group pull-right">
					<?php echo JHTML::_('calendar', '', 'dfrom', 'dfrom', $df, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?>
				</div>
			</div>
		</form>
		<script type="text/javascript">
		jQuery(document).ready(function() {
			document.getElementById('dfrom').value = '<?php echo date($usedf, $fromts); ?>';
			document.getElementById('dto').value = '<?php echo date($usedf, $tots); ?>';
		});
		</script>
		<?php
		$months_map = array(
			'1' => JText::_('VBSHORTMONTHONE'),
			'2' => JText::_('VBSHORTMONTHTWO'),
			'3' => JText::_('VBSHORTMONTHTHREE'),
			'4' => JText::_('VBSHORTMONTHFOUR'),
			'5' => JText::_('VBSHORTMONTHFIVE'),
			'6' => JText::_('VBSHORTMONTHSIX'),
			'7' => JText::_('VBSHORTMONTHSEVEN'),
			'8' => JText::_('VBSHORTMONTHEIGHT'),
			'9' => JText::_('VBSHORTMONTHNINE'),
			'10' => JText::_('VBSHORTMONTHTEN'),
			'11' => JText::_('VBSHORTMONTHELEVEN'),
			'12' => JText::_('VBSHORTMONTHTWELVE')
		);
		if(!(count($bookings) > 0) || !(count($arr_months) > 0)) {
			?>
		<p class="warn"><?php echo JText::_('VBNOBOOKINGSTATS'); ?></p>
			<?php
		}else {
			$datasets = array();
			$donut_datasets = array();
			$months_labels = array_keys($arr_months);
			foreach ($months_labels as $mlbk => $mlbv) {
				$mlb_parts = explode('-', $mlbv);
				$months_labels[$mlbk] = $months_map[$mlb_parts[0]].' '.$mlb_parts[1];
			}
			$tot_months = count($months_labels);
			$tot_channels = count($arr_channels);
			$rgb_rand = array();
			for ($z = 0; $z < $tot_channels; $z++) { 
				$rgb_rand[$z] = mt_rand(0, 255).','.mt_rand(0, 255).','.mt_rand(0, 255);
			}
			$known_ch_rgb = array(
				JText::_('VBOIBECHANNEL') => '34,72,93',
				'booking.com' => '1,170,233',
				'agoda' => '128,3,205',
				'expedia' => '252,189,6',
				'airbnb' => '255,70,124',
				'hotels.com' => '243,86,64',
				'venere' => '214,109,26',
				'tripconnect' => '75,154,87',
				'homeaway' => '59,113,194'
			);
			$ch_dataset = array();
			$ch_donut_dataset = array();
			//commissions (Donuts Chart)
			$ch_donut_dataset[JText::_('VBSTATSOTACOMMISSIONS')] = array(
				'label' => JText::_('VBSTATSOTACOMMISSIONS'),
				'color' => "rgba(255,0,0,1)",
				'highlight' => "rgba(255,0,0,0.9)",
				'value' => 0
			);
			//
			$ch_map = array();
			foreach ($arr_channels as $chname) {
				$ch_color = $rgb_rand[rand(0, ($tot_channels - 1))];
				if(array_key_exists(strtolower($chname), $known_ch_rgb)) {
					$ch_color = $known_ch_rgb[strtolower($chname)];
				}else {
					foreach ($known_ch_rgb as $kch => $krgb) {
						if(stripos($chname, $kch) !== false) {
							$ch_color = $krgb;
							break;
						}
					}
				}
				$ch_dataset[$chname] = array(
					'label' => $chname,
					'fillColor' => "rgba(".$ch_color.",0.2)",
					'strokeColor' => "rgba(".$ch_color.",1)",
					'pointColor' => "rgba(".$ch_color.",1)",
					'pointStrokeColor' => "#fff",
					'pointHighlightFill' => "#fff",
					'pointHighlightStroke' => "rgba(".$ch_color.",1)",
					'tot_bookings' => 0,
					'data' => array()
				);
				$ch_donut_dataset[$chname] = array(
					'label' => $chname,
					'color' => "rgba(".$ch_color.",1)",
					'highlight' => "rgba(".$ch_color.",0.9)",
					'value' => 0
				);
				$ch_map[$chname] = $chname;
			}
			foreach ($arr_months as $monyear => $chbookings) {
				$tot_monchannels = count($chbookings);
				$monchannels = array();
				foreach ($chbookings as $chname => $ords) {
					$monchannels[] = $chname;
					$totchb = 0;
					$totchcomm = 0;
					foreach ($ords as $ord) {
						$totchb += (float)$ord['total'];
						$totchcomm += (float)$ord['cmms'];
					}
					$ch_dataset[$chname]['tot_bookings'] += count($ords);
					$ch_dataset[$chname]['data'][] = $totchb;
					$ch_donut_dataset[$chname]['value'] += $totchb;
					$ch_donut_dataset[JText::_('VBSTATSOTACOMMISSIONS')]['value'] += $totchcomm;
				}
				if($tot_monchannels < $tot_channels) {
					$ch_missing = array_diff($ch_map, $monchannels);
					foreach ($ch_missing as $chnk => $chnv) {
						if(array_key_exists($chnv, $ch_dataset)) {
							$ch_dataset[$chnv]['data'][] = 0;
						}
					}
				}
			}
			foreach ($ch_dataset as $chname => $chgraph) {
				$chgraph['label'] = $chgraph['label'].' ('.$chgraph['tot_bookings'].')';
				unset($chgraph['tot_bookings']);
				$datasets[] = $chgraph;
			}
			foreach ($ch_donut_dataset as $chname => $chgraph) {
				$donut_datasets[] = $chgraph;
			}
			?>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
			<fieldset class="adminform">
				<legend class="adminlegend"><?php echo JText::sprintf('VBOSTATSFOR', count($bookings), $days_diff, $tot_channels); ?></legend>
				<div class="vbo-graphstats-left">
					<canvas id="vbo-graphstats-left-canv"></canvas>
					<div id="vbo-graphstats-left-legend"></div>
				</div>
				<div class="vbo-graphstats-right">
					<canvas id="vbo-graphstats-right-canv"></canvas>
					<div id="vbo-graphstats-right-legend"></div>
				</div>
				<div class="vbo-graphstats-secondright">
					<h4><?php echo JText::_('VBOSTATSTOPCOUNTRIES'); ?></h4>
					<div class="vbo-graphstats-countries">
					<?php
					$clisted = 0;
					foreach ($arr_countries as $ccode => $cdata) {
						if($clisted > 4) {
							break;
						}
						?>
						<div class="vbo-graphstats-country-wrap">
							<span class="vbo-graphstats-country-img"><?php echo $cdata['img']; ?></span>
							<span class="vbo-graphstats-country-name"><?php echo $cdata['country_name']; ?></span>
							<span class="vbo-graphstats-country-totb badge"><?php echo $cdata['tot_bookings']; ?></span>
						</div>
						<?php
						$clisted++;
					}
					?>
					</div>
				</div>
				<div class="vbo-graphstats-thirdright">
					<p class="vbo-graphstats-income"><span><?php echo JText::_('VBOSTATSTOTINCOME'); ?></span> <?php echo $currencysymb.' '.vikbooking::numberFormat($arr_totals['total_income']); ?></p>
					<p class="vbo-graphstats-income-netcmms"><span><?php echo JText::_('VBOSTATSTOTINCOMELESSCMMS'); ?></span> <?php echo $currencysymb.' '.vikbooking::numberFormat($arr_totals['total_income_netcmms']); ?></p>
					<p class="vbo-graphstats-income-nettax"><span class="hasTooltip" title="<?php echo JText::_('VBOSTATSTOTINCOMELESSTAXHELP'); ?>"><?php echo JText::_('VBOSTATSTOTINCOMELESSTAX'); ?></span> <?php echo $currencysymb.' '.vikbooking::numberFormat($arr_totals['total_income_nettax']); ?></p>
				</div>
			</fieldset>
			<input type="hidden" name="task" value="">
			<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<script type="text/javascript">
		Chart.defaults.global.responsive = true;

		var data = {
			labels: <?php echo json_encode($months_labels); ?>,
			datasets: <?php echo json_encode($datasets); ?>
		};

		var donut_data = <?php echo json_encode($donut_datasets); ?>;

		var options = {
			///Boolean - Whether grid lines are shown across the chart
			scaleShowGridLines : true,
			//String - Colour of the grid lines
			scaleGridLineColor : "rgba(0,0,0,.05)",
			//Number - Width of the grid lines
			scaleGridLineWidth : 1,
			//Boolean - Whether to show horizontal lines (except X axis)
			scaleShowHorizontalLines: true,
			//Boolean - Whether to show vertical lines (except Y axis)
			scaleShowVerticalLines: true,
			//Boolean - Whether the line is curved between points
			bezierCurve : true,
			//Number - Tension of the bezier curve between points
			bezierCurveTension : 0.4,
			//Boolean - Whether to show a dot for each point
			pointDot : true,
			//Number - Radius of each point dot in pixels
			pointDotRadius : 4,
			//Number - Pixel width of point dot stroke
			pointDotStrokeWidth : 1,
			//Number - amount extra to add to the radius to cater for hit detection outside the drawn point
			pointHitDetectionRadius : 20,
			//Boolean - Whether to show a stroke for datasets
			datasetStroke : true,
			//Number - Pixel width of dataset stroke
			datasetStrokeWidth : 2,
			//Boolean - Whether to fill the dataset with a colour
			datasetFill : true,
			//String - A legend template
			legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span class=\"entry\" style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
			tooltipTemplate: "<%if (label){%><%=label%>: <%}%><?php echo $currencysymb; ?> <%=value%>",
			multiTooltipTemplate: "<%if (datasetLabel){%><%=datasetLabel.substring( 0, datasetLabel.indexOf('(')-1 )%>: <%}%><?php echo $currencysymb; ?> <%=value%>",
			scaleLabel: "<?php echo $currencysymb; ?> <%=value%>"
		};

		var donut_options = {
			//Boolean - Whether we should show a stroke on each segment
			segmentShowStroke : true,
			//String - The colour of each segment stroke
			segmentStrokeColor : "#fff",
			//Number - The width of each segment stroke
			segmentStrokeWidth : 2,
			//Number - The percentage of the chart that we cut out of the middle
			//percentageInnerCutout : 30, // This is 0 for Pie charts, 50 for Donut charts
			//Number - Amount of animation steps
			animationSteps : 100,
			//String - Animation easing effect
			animationEasing : "easeOutQuart",
			//Boolean - Whether we animate the rotation of the Doughnut
			animateRotate : true,
			//Boolean - Whether we animate scaling the Doughnut from the centre
			animateScale : false,
			legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span class=\"entry\" style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><span class=\"vbo-graphstats-legend-sub\">(<?php echo $currencysymb; ?> <%=segments[i].value%>)</span><%}%></li><%}%></ul>",
			tooltipTemplate: "<%if (label){%><%=label%>: <%}%><?php echo $currencysymb; ?> <%=value%>"
		};

		var ctx = document.getElementById("vbo-graphstats-left-canv").getContext("2d");
		var vboLineChart = new Chart(ctx).Line(data, options);
		var legend = vboLineChart.generateLegend();
		jQuery('#vbo-graphstats-left-legend').html(legend);

		var donut_ctx = document.getElementById("vbo-graphstats-right-canv").getContext("2d");
		var vboDonutChart = new Chart(donut_ctx).Pie(donut_data, donut_options);
		var legend = vboDonutChart.generateLegend();
		jQuery('#vbo-graphstats-right-legend').html(legend);
		</script>
			<?php
		}
	}

	public static function pViewCrons ($rows, $option, $lim0="0", $navbut="", $orderby="id", $ordersort="DESC") {
		$nowdf = vikbooking::getDateFormat(true);
		if ($nowdf=="%d/%m/%Y") {
			$df='d/m/Y';
		}elseif ($nowdf=="%m/%d/%Y") {
			$df='m/d/Y';
		}else {
			$df='Y/m/d';
		}
		if(empty($rows)){
			?>
			<p class="warn"><?php echo JText::_('VBNOCRONS'); ?></p>
			<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
			<?php
		}else{
			$document = JFactory::getDocument();
			$document->addStyleSheet(JURI::root().'components/com_vikbooking/resources/jquery.fancybox.css');
			JHtml::_('script', JURI::root().'components/com_vikbooking/resources/jquery.fancybox.js', false, true, false, false);
		?>
	<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped">
		<thead>
		<tr>
			<th width="20">
				<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
			</th>
			<th class="title left" width="50"><a href="index.php?option=com_vikbooking&amp;task=crons&amp;vborderby=id&amp;vbordersort=<?php echo ($orderby == "id" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "id" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "id" ? "vbsortdesc" : "")); ?>">ID</a></th>
			<th class="title left" width="200"><a href="index.php?option=com_vikbooking&amp;task=crons&amp;vborderby=cron_name&amp;vbordersort=<?php echo ($orderby == "cron_name" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "cron_name" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "cron_name" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBCRONNAME' ); ?></a></th>
			<th class="title center" width="100"><?php echo JText::_( 'VBCRONCLASS' ); ?></th>
			<th class="title center" width="75"><a href="index.php?option=com_vikbooking&amp;task=crons&amp;vborderby=last_exec&amp;vbordersort=<?php echo ($orderby == "last_exec" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "last_exec" && $ordersort == "ASC" ? "vbsortasc" : ($orderby == "last_exec" ? "vbsortdesc" : "")); ?>"><?php echo JText::_( 'VBCRONLASTEXEC' ); ?></a></th>
			<th class="title center" width="50"><?php echo JText::_( 'VBCRONPUBLISHED' ); ?></th>
			<th class="title center" width="150"><?php echo JText::_( 'VBCRONACTIONS' ); ?></th>
			<th class="title center" width="100">&nbsp;</th>
		</tr>
		</thead>
		<?php
		$kk = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td><a href="index.php?option=com_vikbooking&amp;task=editcron&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['id']; ?></a></td>
				<td><a href="index.php?option=com_vikbooking&amp;task=editcron&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['cron_name']; ?></a></td>
				<td class="center"><?php echo $row['class_file']; ?></td>
				<td class="center"><?php echo !empty($row['last_exec']) ? date($df.' H:i:s', $row['last_exec']) : '----'; ?></td>
				<td class="center"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikbooking/resources/<?php echo intval($row['published']) > 0 ? 'ok.png' : 'no.png'; ?>" /></td>
				<td class="center"><button type="button" class="btn vbo-getcmd" data-cronid="<?php echo $row['id']; ?>" data-cronname="<?php echo addslashes($row['cron_name']); ?>" data-cronclass="<?php echo $row['class_file']; ?>"><i class="vboicn-terminal"></i><?php echo JText::_('VBCRONGETCMD'); ?></button> &nbsp;&nbsp; <button type="button" class="btn vbo-exec" data-cronid="<?php echo $row['id']; ?>"><i class="vboicn-power-cord"></i><?php echo JText::_('VBCRONACTION'); ?></button></td>
				<td class="center"><button type="button" class="btn vbo-logs" data-cronid="<?php echo $row['id']; ?>"><i class="vboicn-file-text"></i><?php echo JText::_('VBCRONLOGS'); ?></button></td>
			</tr>
			<?php
			$kk = 1 - $kk;
		}
		?>
		
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="crons" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php echo $navbut; ?>
	</form>
	<div class="vbo-info-overlay-block">
		<div class="vbo-info-overlay-content vbo-info-overlay-content-getcmd">
			<h3><i class="vboicn-terminal"></i><?php echo JText::_('VBCRONGETCMD') ?>: <span id="crongetcmd-lbl"></span></h3>
			<blockquote class="vbo-crongetcmd-help"><?php echo JText::_('VBCRONGETCMDHELP') ?></blockquote>
			<h4><?php echo JText::_('VBCRONGETCMDINSTSTEPS') ?></h4>
			<ol>
				<li><?php echo JText::_('VBCRONGETCMDINSTSTEPONE') ?></li>
				<li><?php echo JText::_('VBCRONGETCMDINSTSTEPTWO') ?></li>
				<li><?php echo JText::_('VBCRONGETCMDINSTSTEPTHREE') ?></li>
				<li><?php echo JText::_('VBCRONGETCMDINSTSTEPFOUR') ?></li>
			</ol>
			<p><?php echo JText::_('VBCRONGETCMDINSTPATH'); ?></p>
			<p><span class="label label-info">/usr/bin/php <?php echo JPATH_SITE.DS; ?><span class="crongetcmd-php"></span>.php</span></p>
			<p><i class="vboicn-warning"></i><?php echo JText::_('VBCRONGETCMDINSTURL'); ?></p>
			<p><span class="label"><?php echo JURI::root(); ?><span class="crongetcmd-php"></span>.php</span></p>
			<br/>
			<form action="index.php?option=com_vikbooking" method="post">
				<button type="submit" class="btn"><i class="vboicn-download"></i><?php echo JText::_('VBCRONGETCMDGETFILE') ?></button>
				<input type="hidden" name="cron_id" id="cronid-inp" value="" />
				<input type="hidden" name="cron_name" id="cronname-inp" value="" />
				<input type="hidden" name="task" value="downloadcron" />
			</form>
		</div>
	</div>
	<script type="text/javascript">
	var vbo_overlay_on = false;
	jQuery(document).ready(function() {
		jQuery(".vbo-getcmd").click(function() {
			var cronid = jQuery(this).attr("data-cronid");
			var cronname = jQuery(this).attr("data-cronname");
			jQuery("#crongetcmd-lbl").text(cronname);
			var cronclass = jQuery(this).attr("data-cronclass");
			jQuery("#cronid-inp").val(cronid);
			var cronnamephp = cronname.replace(/\s/g, "").toLowerCase();
			jQuery("#cronname-inp").val(cronnamephp);
			jQuery(".crongetcmd-php").text(cronnamephp);
			jQuery(".vbo-info-overlay-block").fadeToggle(400, function() {
				if(jQuery(".vbo-info-overlay-block").is(":visible")) {
					vbo_overlay_on = true;
				}else {
					vbo_overlay_on = false;
				}
			});
		});
		jQuery(".vbo-logs").click(function() {
			var cron_id = jQuery(this).attr("data-cronid");
			jQuery.fancybox({
				"helpers": {
					"overlay": {
						"locked": false
					}
				},
				"href": "index.php?option=com_vikbooking&task=cronlogs&cron_id="+cron_id+"&tmpl=component",
				"width": "75%",
				"height": "75%",
				"autoScale": false,
				"transitionIn": "none",
				"transitionOut": "none",
				//"padding": 0,
				"type": "iframe"
			});
		});
		jQuery(".vbo-exec").click(function() {
			var cron_id = jQuery(this).attr("data-cronid");
			jQuery.fancybox({
				"helpers": {
					"overlay": {
						"locked": false
					}
				},
				"href": "index.php?option=com_vikbooking&task=cron_exec&cronkey=<?php echo vikbooking::getCronKey(); ?>&cron_id="+cron_id+"&tmpl=component",
				"width": "75%",
				"height": "75%",
				"autoScale": false,
				"transitionIn": "none",
				"transitionOut": "none",
				//"padding": 0,
				"type": "iframe"
			});
		});
		jQuery(document).mouseup(function(e) {
			if(!vbo_overlay_on) {
				return false;
			}
			var vbo_overlay_cont = jQuery(".vbo-info-overlay-content");
			if(!vbo_overlay_cont.is(e.target) && vbo_overlay_cont.has(e.target).length === 0) {
				jQuery(".vbo-info-overlay-block").fadeOut();
				vbo_overlay_on = false;
			}
		});
		jQuery(document).keyup(function(e) {
			if (e.keyCode == 27 && vbo_overlay_on) {
				jQuery(".vbo-info-overlay-block").fadeOut();
				vbo_overlay_on = false;
			}
		});
	});
	</script>
	<?php
		}
	}

	public static function pNewCron ($allf, $option) {
		$vbo_app = new VikApplication();
		$editor = JFactory::getEditor();
		$psel="<select name=\"class_file\" id=\"cronfile\" onchange=\"vikLoadCronParameters(this.value);\">\n<option value=\"\"></option>\n";
		$classfiles=array();
		foreach($allf as $af) {
			$classfiles[]=str_replace(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'cronjobs'.DS, '', $af);
		}
		sort($classfiles);
		
		foreach($classfiles as $cf) {
			$psel.="<option value=\"".$cf."\">".$cf."</option>\n";
		}
		$psel.="</select>";
		?>
		<script type="text/javascript">
		jQuery.noConflict();
		function vikLoadCronParameters(pfile) {
			if(pfile.length > 0) {
				jQuery("#vbo-cron-params").html('<?php echo addslashes(JTEXT::_('VIKLOADING')); ?>');
				jQuery.ajax({
					type: "POST",
					url: "index.php?option=com_vikbooking&task=loadcronparams&tmpl=component",
					data: { phpfile: pfile }
				}).done(function(res) {
					jQuery("#vbo-cron-params").html(res);
				});
			}else {
				jQuery("#vbo-cron-params").html('--------');
			}
		}
		</script>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
			<fieldset class="adminform">
				<table cellspacing="1" class="admintable table">
					<tbody>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCRONNAME'); ?></b> </td>
							<td><input type="text" name="cron_name" value="" size="50"/></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCRONCLASS'); ?></b> </td>
							<td><?php echo $psel; ?></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell" style="vertical-align: top !important;"> <b><?php echo JText::_('VBCRONPARAMS'); ?></b> </td>
							<td><div id="vbo-cron-params"></div></td>
						</tr>

						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCRONPUBLISHED'); ?></b> </td>
							<td><?php echo $vbo_app->printYesNoButtons('published', JText::_('VBYES'), JText::_('VBNO'), 1, 1, 0); ?></td>
						</tr>
					</tbody>
				</table>
			</fieldset>
			<input type="hidden" name="task" value="">
			<input type="hidden" name="option" value="<?php echo $option; ?>">
		</form>
		<?php
	}

	public static function pEditCron ($row, $allf, $option) {
		$vbo_app = new VikApplication();
		$editor = JFactory::getEditor();
		$psel="<select name=\"class_file\" id=\"cronfile\" onchange=\"vikLoadCronParameters(this.value);\">\n<option value=\"\"></option>\n";
		$classfiles=array();
		foreach($allf as $af) {
			$classfiles[]=str_replace(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'cronjobs'.DS, '', $af);
		}
		sort($classfiles);
		
		foreach($classfiles as $cf) {
			$psel.="<option value=\"".$cf."\"".($cf == $row['class_file'] ? ' selected="selected"' : '').">".$cf."</option>\n";
		}
		$psel.="</select>";
		?>
		<script type="text/javascript">
		jQuery.noConflict();
		function vikLoadCronParameters(pfile) {
			if(pfile.length > 0) {
				jQuery("#vbo-cron-params").html('<?php echo addslashes(JTEXT::_('VIKLOADING')); ?>');
				jQuery.ajax({
					type: "POST",
					url: "index.php?option=com_vikbooking&task=loadcronparams&tmpl=component",
					data: { phpfile: pfile }
				}).done(function(res) {
					jQuery("#vbo-cron-params").html(res);
				});
			}else {
				jQuery("#vbo-cron-params").html('--------');
			}
		}
		</script>
		<form name="adminForm" id="adminForm" action="index.php" method="post">
			<fieldset class="adminform">
				<table cellspacing="1" class="admintable table">
					<tbody>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCRONNAME'); ?></b> </td>
							<td><input type="text" name="cron_name" value="<?php echo $row['cron_name']; ?>" size="50"/></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCRONCLASS'); ?></b> </td>
							<td><?php echo $psel; ?></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell" style="vertical-align: top !important;"> <b><?php echo JText::_('VBCRONPARAMS'); ?></b> </td>
							<td><div id="vbo-cron-params"><?php echo vikbooking::displayCronParameters($row['class_file'], $row['params']); ?></div></td>
						</tr>
						<tr>
							<td width="200" class="vbo-config-param-cell"> <b><?php echo JText::_('VBCRONPUBLISHED'); ?></b> </td>
							<td><?php echo $vbo_app->printYesNoButtons('published', JText::_('VBYES'), JText::_('VBNO'), (int)$row['published'], 1, 0); ?></td>
						</tr>
					</tbody>
				</table>
			</fieldset>
			<input type="hidden" name="task" value="">
			<input type="hidden" name="option" value="<?php echo $option; ?>">
			<input type="hidden" name="where" value="<?php echo $row['id']; ?>">
		</form>
		<?php
	}

	public static function pExecuteCron($cron_data, $run_res, $cron_output, $cron_obj, $option) {
		?>
		<div class="vbo-shell-wrap">
			<p class="vbo-shell-top-bar"><?php echo $cron_data['cron_name']; ?> - <span><?php echo JText::_('VBCRONEXECRESULT'); ?>:</span> <?php var_dump($run_res); ?></p>
			<div class="vbo-shell-body" style="min-height: 400px;">
				<?php echo $cron_output; ?>
			<?php
			if(strlen($cron_obj->log)) {
			?>
				<p>---------- LOG ----------</p>
				<div class="vbo-cronexec-log">
					<pre><?php echo $cron_obj->log; ?></pre>
				</div>
			<?php
			}
			?>
			</div>
		</div>
		<script type="text/javascript">
		jQuery(document).ready(function() {
			setTimeout(function() {
				checkShellHeight();
			}, 400);
		});
		function checkShellHeight() {
			var page_height = jQuery(window).height();
			var shell_height = jQuery(".vbo-shell-wrap").height();
			if(shell_height < page_height) {
				var diff_height = page_height - shell_height - 10;
				jQuery(".vbo-shell-body").css('height', "+="+diff_height+'px');
			}
		}
		</script>
		<?php
	}

	public static function pViewInvoices($rows, $archive, $option, $lim0, $navbut) {
		$pmonyear = JRequest::getString('monyear', '', 'request');
		$pfilterinvoices = JRequest::getString('filterinvoices', '', 'request');
		$nowdf = vikbooking::getDateFormat(true);
		if ($nowdf=="%d/%m/%Y") {
			$df='d/m/Y';
		}elseif ($nowdf=="%m/%d/%Y") {
			$df='m/d/Y';
		}else {
			$df='Y/m/d';
		}
		$arrmonths = array(
			1 => JText::_('VBMONTHONE'),
			2 => JText::_('VBMONTHTWO'),
			3 => JText::_('VBMONTHTHREE'),
			4 => JText::_('VBMONTHFOUR'),
			5 => JText::_('VBMONTHFIVE'),
			6 => JText::_('VBMONTHSIX'),
			7 => JText::_('VBMONTHSEVEN'),
			8 => JText::_('VBMONTHEIGHT'),
			9 => JText::_('VBMONTHNINE'),
			10 => JText::_('VBMONTHTEN'),
			11 => JText::_('VBMONTHELEVEN'),
			12 => JText::_('VBMONTHTWELVE')
		);
		?>
		<div class="vbo-timeline-container">
			<ul id="vbo-timeline">
				<?php
				foreach ($archive as $monyear => $totinvoices) {
					$monyear_parts = explode('_', $monyear);
					?>
				<li data-month="<?php echo $monyear; ?>">
					<input type="radio" name="timeline" class="vbo-timeline-radio" id="vbo-timeline-dot<?php echo $monyear; ?>" <?php echo $monyear == $pmonyear ? 'checked="checked"' : ''; ?>/>
					<div class="vbo-timeline-relative">
						<label class="vbo-timeline-label" for="vbo-timeline-dot<?php echo $monyear; ?>"><?php echo $arrmonths[(int)$monyear_parts[1]].' '.$monyear_parts[0]; ?></label>
						<span class="vbo-timeline-date"><i class="vboicn-file-text2"></i><?php echo $totinvoices; ?></span>
						<span class="vbo-timeline-circle" onclick="Javascript: jQuery('#vbo-timeline-dot<?php echo $monyear; ?>').trigger('click');"></span>
					</div>
					<div class="vbo-timeline-content">
						<p><span class="label label-info"><?php echo JText::_('VBTOTINVOICES').': <span class="badge">'.$totinvoices.'</span>'; ?></span> <button type="button" class="btn<?php echo ($monyear == $pmonyear ? ' btn-danger' : ''); ?>" onclick="document.location.href='index.php?option=com_vikbooking&amp;task=invoices&amp;monyear=<?php echo ($monyear == $pmonyear ? '0' : $monyear); ?>';"><i class="vboicn-<?php echo ($monyear == $pmonyear ? 'cross' : 'checkmark'); ?>"></i><?php echo JText::_(($monyear == $pmonyear ? 'VBOINVREMOVEFILTER' : 'VBOINVAPPLYFILTER')); ?></button></p>
					</div>
				</li>
					<?php
				}
				?>
			</ul>
		</div>
		<script>
		jQuery(document).ready(function(){
			jQuery('.vbo-timeline-container').css('min-height', (jQuery('.vbo-timeline-container').outerHeight() + 5));
			jQuery('.vbo-invoices-inv-back-commands button').click(function(e) {
				e.stopPropagation();
				e.preventDefault();
			});
			jQuery('.vbo-invoices-inv-container').click(function() {
				var rowkey = jQuery(this).attr("data-rowkey");
				if(jQuery(this).hasClass('vbo-invoice-active')) {
					jQuery("#cb"+rowkey).prop("checked", false);
					document.adminForm.boxchecked.value--;
					jQuery(this).removeClass('vbo-invoice-active');
				}else {
					jQuery("#cb"+rowkey).prop("checked", true);
					jQuery(this).addClass('vbo-invoice-active');
					document.adminForm.boxchecked.value++;
				}
			});
			jQuery('#vbo-selall').click(function() {
				jQuery('.vbo-invoices-inv-container').each(function() {
					var rowkey = jQuery(this).attr("data-rowkey");
					if(!jQuery(this).hasClass('vbo-invoice-active')) {
						jQuery(this).addClass('vbo-invoice-active');
					}
					if(jQuery("#cb"+rowkey).prop("checked") === false) {
						jQuery("#cb"+rowkey).prop("checked", true);
						document.adminForm.boxchecked.value++;
					}
				});
			});
			jQuery('#vbo-deselall').click(function() {
				jQuery('.vbo-invoices-inv-container').each(function() {
					var rowkey = jQuery(this).attr("data-rowkey");
					if(jQuery(this).hasClass('vbo-invoice-active')) {
						jQuery(this).removeClass('vbo-invoice-active');
					}
					if(jQuery("#cb"+rowkey).prop("checked") === true) {
						jQuery("#cb"+rowkey).prop("checked", false);
						document.adminForm.boxchecked.value--;
					}
				});
			});
		});
		</script>
		<form action="index.php?option=com_vikbooking&amp;task=invoices" method="post" name="invoicesform">
			<div id="filter-bar" class="btn-toolbar" style="width: 100%; display: inline-block;">
				<div class="btn-group pull-left">
					<input type="text" name="filterinvoices" id="filterinvoices" value="<?php echo $pfilterinvoices; ?>" size="43" placeholder="<?php echo JText::_( 'VBOINVNUM' ).', '.JText::_( 'VBCUSTOMERFIRSTNAME' ).', '.JText::_( 'VBCUSTOMERLASTNAME' ).', '.JText::_( 'VBCUSTOMEREMAIL' ); ?>"/>
				</div>
				<div class="btn-group pull-left">
					<button type="button" class="btn" onclick="document.invoicesform.submit();"><i class="icon-search"></i></button>
					<button type="button" class="btn" onclick="document.getElementById('filterinvoices').value='';document.invoicesform.submit();"><i class="icon-remove"></i></button>
				</div>
			<?php
			if(!empty($rows)) {
			?>
				<div class="btn-group pull-right">
					<button type="button" class="btn" id="vbo-deselall"><?php echo JText::_('VBINVDESELECTALL'); ?></button>
				</div>
				<div class="btn-group pull-right">
					<button type="button" class="btn" id="vbo-selall"><?php echo JText::_('VBINVSELECTALL'); ?></button>
				</div>
			<?php
			}
			?>
			</div>
			<input type="hidden" name="task" value="invoices" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
		</form>
		<?php
		if(empty($rows)) {
			?>
		<p class="warn"><?php echo JText::_('VBNOINVOICESFOUND'); ?></p>
		<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
		</form>
			<?php
		}else {
			?>
		<form action="index.php?option=com_vikbooking" method="post" name="adminForm" id="adminForm">
			<div class="vbo-invoices-wrapper">
			<?php
			foreach ($rows as $k => $row) {
				if ($row['status']=="confirmed") {
					$saystaus = '<span class="label label-success">'.JText::_('VBCONFIRMED').'</span>';
				}elseif ($row['status']=="standby") {
					$saystaus = '<span class="label label-warning">'.JText::_('VBSTANDBY').'</span>';
				}else {
					$saystaus = '<span class="label label-error" style="background-color: #d9534f;">'.JText::_('VBCANCELLED').'</span>';
				}
				?>
				<div class="vbo-invoices-inv-container" data-rowkey="<?php echo $k; ?>">
					<div class="vbo-invoices-inv-inner">
						<div class="vbo-invoices-inv-front vbo-invoices-inv-face">
							<div class="vbo-invoices-inv-frontleft"></div>
							<div class="vbo-invoices-inv-frontright">
								<div class="vbo-invoices-inv-entry">
									<span class="vbo-invoices-inv-entry-lbl"><?php echo JText::_('VBOINVNUM'); ?></span>
									<span class="vbo-invoices-inv-entry-val"><?php echo $row['number']; ?></span>
								</div>
								<div class="vbo-invoices-inv-entry">
									<span class="vbo-invoices-inv-entry-lbl"><?php echo JText::_('VBOINVDATE'); ?></span>
									<span class="vbo-invoices-inv-entry-val"><?php echo date($df, $row['for_date']); ?></span>
								</div>
								<div class="vbo-invoices-inv-entry">
									<span class="vbo-invoices-inv-entry-lbl"><?php echo JText::_('VBOINVCREATIONDATE'); ?></span>
									<span class="vbo-invoices-inv-entry-val"><?php echo date($df, $row['created_on']); ?></span>
								</div>
								<div class="vbo-invoices-inv-entry">
									<span class="vbo-invoices-inv-entry-lbl"><?php echo JText::_('VBOINVBOOKINGID'); ?></span>
									<span class="vbo-invoices-inv-entry-val"><?php echo $row['idorder']; ?></span>
								</div>
							<?php
							if(strlen($row['customer_name']) > 2) {
								?>
								<div class="vbo-invoices-inv-entry vbo-invoices-inv-entry-custname">
									<span class="vbo-invoices-inv-entry-val"><strong><?php echo $row['customer_name']; ?></strong></span>
								</div>
								<?php
							}
							$country_flag = '';
							if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'countries'.DS.$row['country'].'.png')) {
								$country_flag = '<img src="'.JURI::root().'administrator/components/com_vikbooking/resources/countries/'.$row['country'].'.png'.'" title="'.$row['country'].'" class="vbo-country-flag vbo-country-flag-left"/>';
							}
							if(!empty($country_flag) || !empty($row['country_name'])) {
								?>
								<div class="vbo-invoices-inv-entry">
								<?php
								if(!empty($country_flag)) {
									?>
									<span class="vbo-invoices-inv-entry-lbl"><?php echo $country_flag; ?></span>
									<?php
								}
								if(!empty($row['country_name'])) {
									?>
									<span class="vbo-invoices-inv-entry-val"><?php echo $row['country_name']; ?></span>
									<?php
								}
								?>
								</div>
								<?php
							}
							?>
							</div>
						</div>
						<div class="vbo-invoices-inv-back vbo-invoices-inv-face">
							<div class="vbo-invoices-inv-ckbox">
								<input type="checkbox" id="cb<?php echo $k;?>" name="cid[]" value="<?php echo $row['id']; ?>">
							</div>
							<div class="vbo-invoices-inv-back-commands">
								<button type="button" class="btn" onclick="document.location.href='index.php?option=com_vikbooking&amp;task=resendinvoices&amp;cid[]=<?php echo $row['id']; ?>';"><i class="vboicn-envelop"></i><?php echo JText::_(($row['emailed'] > 0 && !empty($row['emailed_to']) ? 'VBOINVREEMAIL' : 'VBOINVEMAILNOW')); ?></button>
								<button type="button" class="btn" onclick="window.open('<?php echo JURI::root(); ?>components/com_vikbooking/helpers/invoices/generated/<?php echo $row['file_name']; ?>', '_blank');"><i class="vboicn-eye"></i><?php echo JText::_('VBOINVOPEN'); ?></button>
								<button type="button" class="btn" onclick="document.location.href='index.php?option=com_vikbooking&amp;task=downloadinvoices&amp;cid[]=<?php echo $row['id']; ?>';"><i class="vboicn-download"></i><?php echo JText::_('VBOINVDOWNLOAD'); ?></button>
								<button type="button" class="btn" onclick="window.open('index.php?option=com_vikbooking&amp;task=editorder&amp;cid[]=<?php echo $row['idorder']; ?>', '_blank');"><i class="vboicn-eye"></i><?php echo JText::_('VBOINVBOOKDETAILS'); ?></button>
							</div>
							<div class="vbo-invoices-inv-back-entries">
								<div class="vbo-invoices-inv-entry">
									<span class="vbo-invoices-inv-entry-lbl"><?php echo JText::_('VBOINVNUM'); ?></span>
									<span class="vbo-invoices-inv-entry-val"><?php echo $row['number']; ?></span>
								</div>
								<div class="vbo-invoices-inv-entry">
									<span class="vbo-invoices-inv-entry-lbl"><?php echo JText::_('VBOINVBOOKINGID'); ?></span>
									<span class="vbo-invoices-inv-entry-val"><?php echo $row['idorder'].' '.$saystaus; ?></span>
								</div>
								<div class="vbo-invoices-inv-entry">
									<span class="vbo-invoices-inv-entry-lbl"><i class="vboicn-envelop"></i><?php echo JText::_('VBOINVEMAILED'); ?></span>
									<span class="vbo-invoices-inv-entry-val"><?php echo $row['emailed'] > 0 && !empty($row['emailed_to']) ? '<small>'.$row['emailed_to'].'</small>' : '----'; ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			?>
			</div>
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="invoices" />
			<input type="hidden" name="boxchecked" value="0" />
			<?php echo JHTML::_( 'form.token' ); ?>
			<?php echo $navbut; ?>
			<?php
			if(!empty($pfilterinvoices)) {
				?>
				<input type="hidden" name="filterinvoices" value="<?php echo $pfilterinvoices; ?>" />
				<?php
			}
			?>
		</form>
			<?php
		}
	}
	
}
?>