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

$rooms = $this->rooms;
$tsstart = $this->tsstart;
$wmonthsel = $this->wmonthsel;
$busy = $this->busy;
$vbo_tn = $this->vbo_tn;

$currencysymb = vikbooking::getCurrencySymb();
$showpartlyres=vikbooking::showPartlyReserved();
$vbdateformat = vikbooking::getDateFormat();
if ($vbdateformat == "%d/%m/%Y") {
	$df = 'd/m/Y';
} elseif ($vbdateformat == "%m/%d/%Y") {
	$df = 'm/d/Y';
} else {
	$df = 'Y/m/d';
}

$document = JFactory::getDocument();
//load jQuery
if(vikbooking::loadJquery()) {
	JHtml::_('jquery.framework', true, true);
	JHtml::_('script', JURI::root().'components/com_vikbooking/resources/jquery-1.11.3.min.js', false, true, false, false);
}

$pmonth = JRequest::getInt('month', '', 'request');
$pshowtype = JRequest::getInt('showtype', 2, 'request');
//1 = do not show the units - 2 = show the units remaning - 3 = show the number of units booked.
$pshowtype = $pshowtype >= 1 && $pshowtype <= 3 ? $pshowtype : 1; 

$begin_info=getdate($tsstart);

?>

<h3><?php echo JText::_('VBOAVAILABILITYCALENDAR'); ?></h3>

<div class="vbo-availability-controls">
	<form action="<?php echo JRoute::_('index.php?option=com_vikbooking&view=availability'); ?>" method="post" name="vbmonths">
		<?php echo $wmonthsel; ?>
	<?php
	foreach ($rooms as $room) {
		?>
		<input type="hidden" name="room_ids[]" value="<?php echo $room['id']; ?>" />
		<?php
	}
	?>
		<input type="hidden" name="showtype" value="<?php echo $pshowtype; ?>" />
	</form>
	<div class="vblegendediv">
		<span class="vblegenda"><div class="vblegfree">&nbsp;</div> <?php echo JText::_('VBLEGFREE'); ?></span>
	<?php
	if($showpartlyres) {
		?>
		<span class="vblegenda"><div class="vblegwarning">&nbsp;</div> <?php echo JText::_('VBLEGWARNING'); ?></span>
		<?php
	}
	?>
		<span class="vblegenda"><div class="vblegbusy">&nbsp;</div> <?php echo JText::_('VBLEGBUSY'); ?></span>
	</div>
</div>
	
<?php
$check = is_array($busy) && count($busy) > 0 ? true : false;
$days_labels = array(
	JText::_('VBSUN'),
	JText::_('VBMON'),
	JText::_('VBTUE'),
	JText::_('VBWED'),
	JText::_('VBTHU'),
	JText::_('VBFRI'),
	JText::_('VBSAT')
);
?>
<div class="vbo-availability-wrapper">
<?php
foreach ($rooms as $rk => $room) {
	$nowts = $begin_info;
	$carats = vikbooking::getRoomCaratOriz($room['idcarat'], $vbo_tn);
	?>
	<div class="vbo-availability-room-container">
		<div class="vbo-availability-room-details">
			<div class="vbo-availability-room-details-first">
				<div class="vbo-availability-room-details-left">
				<?php
				if(!empty($room['img'])) {
					?>
					<img src="<?php echo JURI::root(); ?>components/com_vikbooking/resources/uploads/<?php echo $room['img']; ?>" alt="<?php echo $room['name']; ?>"/>
					<?php
				}
				?>
				</div>
				<div class="vbo-availability-room-details-right">
					<h4><?php echo $room['name']; ?></h4>
					<div class="vbo-availability-room-details-descr">
						<?php echo $room['smalldesc']; ?>
					</div>
				<?php
				if(!empty($carats)) {
					?>
					<div class="room_carats">
						<?php echo $carats; ?>
					</div>
					<?php
				}
				?>
				</div>
			</div>
			<div class="vbo-availability-room-details-last vbselectr">
				<div class="vbo-availability-room-details-last-inner">
					<a class="btn" id="vbo-av-btn-<?php echo $room['id']; ?>" href="<?php echo JRoute::_('index.php?option=com_vikbooking&view=roomdetails&roomid='.$room['id'].'&checkin=-1'); ?>"><?php echo JText::_('VBAVAILBOOKNOW'); ?></a>
				</div>
				<div class="vbo-availability-room-details-last-checkin" id="vbo-av-checkin-<?php echo $room['id']; ?>"><span></span></div>
			</div>
		</div>
		<div class="vbo-availability-room-monthcal table-responsive">
			<table class="table" id="vbo-av-table-<?php echo $room['id']; ?>" data-room-table="<?php echo $room['id']; ?>">
				<tr class="vbo-availability-room-monthdays">
					<td class="vbo-availability-month-name" rowspan="2"><?php echo vikbooking::sayMonth($nowts['mon'])." ".$nowts['year']; ?></td>
				<?php
				$mon = $nowts['mon'];
				while ($nowts['mon'] == $mon) {
					?>
					<td class="vbo-availability-month-day">
						<span class="vbo-availability-daynumber"><?php echo $nowts['mday']; ?></span>
						<span class="vbo-availability-weekday"><?php echo $days_labels[$nowts['wday']]; ?></span>
					</td>
					<?php
					$next = $nowts['mday'] + 1;
					$dayts = mktime(0, 0, 0, ($nowts['mon'] < 10 ? "0".$nowts['mon'] : $nowts['mon']), ($next < 10 ? "0".$next : $next), $nowts['year']);
					$nowts = getdate($dayts);
				}
				?>
				</tr>
				<tr class="vbo-availability-room-avdays">
				<?php
				$nowts=getdate($tsstart);
				$mon=$nowts['mon'];
				while ($nowts['mon'] == $mon) {
					$dclass = "vbo-free-cell";
					$is_checkin = false;
					$is_checkout = false;
					$dlnk = "";
					$bid = "";
					$totfound = 0;
					if(array_key_exists($room['id'], $busy) && count($busy[$room['id']]) > 0) {
						foreach($busy[$room['id']] as $b){
							$tmpone = getdate($b['checkin']);
							$rit = ($tmpone['mon'] < 10 ? "0".$tmpone['mon'] : $tmpone['mon'])."/".($tmpone['mday'] < 10 ? "0".$tmpone['mday'] : $tmpone['mday'])."/".$tmpone['year'];
							$ritts = strtotime($rit);
							$tmptwo = getdate($b['checkout']);
							$con = ($tmptwo['mon'] < 10 ? "0".$tmptwo['mon'] : $tmptwo['mon'])."/".($tmptwo['mday'] < 10 ? "0".$tmptwo['mday'] : $tmptwo['mday'])."/".$tmptwo['year'];
							$conts = strtotime($con);
							if ($nowts[0] >= $ritts && $nowts[0] < $conts) {
								$dclass = "vbo-occupied-cell";
								$bid = $b['idorder'];
								if ($nowts[0] == $ritts) {
									$is_checkin = true;
								}elseif ($nowts[0] == $conts) {
									$is_checkout = true;
								}
								$totfound++;
							}
						}
					}
					$useday = ($nowts['mday'] < 10 ? "0".$nowts['mday'] : $nowts['mday']);
					$dclass .= ($totfound < $room['units'] && $totfound > 0 ? ' vbo-partially-cell' : '');
					//Partially Reserved Days can be disabled from the Configuration
					$dclass = !$showpartlyres && $totfound < $room['units'] && $totfound > 0 ? 'vbo-free-cell' : $dclass;
					$show_day_units = $totfound;
					if($pshowtype == 1) {
						$show_day_units = '';
					}elseif($pshowtype == 2 && $totfound >= 1) {
						$show_day_units = ($room['units'] - $totfound);
						$show_day_units = $show_day_units < 0 ? 0 : $show_day_units;
					}elseif($pshowtype == 3 && $totfound >= 1) {
						$show_day_units = $totfound;
					}
					if(!$showpartlyres && $totfound < $room['units'] && $totfound > 0) {
						$show_day_units = '';
					}
					if($totfound == 1) {
						$dclass .= $is_checkin === true ? ' vbo-checkinday-cell' : '';
						$dclass .= $is_checkout === true ? ' vbo-checkoutday-cell' : '';
						$dlnk="<span class=\"vbo-availability-day-container\" data-units-booked=\"".$totfound."\" data-units-left=\"".($room['units'] - $totfound)."\">".$show_day_units."</span>";
					}elseif($totfound > 1) {
						$dlnk="<span class=\"vbo-availability-day-container\" data-units-booked=\"".$totfound."\" data-units-left=\"".($room['units'] - $totfound)."\">".$show_day_units."</span>";
					}
					?>
					<td class="<?php echo $dclass; ?>" data-cell-date="<?php echo date($df, $nowts[0]); ?>" data-cell-ts="<?php echo $nowts[0]; ?>"><?php echo $dlnk; ?></td>
					<?php
					$next=$nowts['mday'] + 1;
					$dayts=mktime(0, 0, 0, ($nowts['mon'] < 10 ? "0".$nowts['mon'] : $nowts['mon']), ($next < 10 ? "0".$next : $next), $nowts['year']);
					$nowts=getdate($dayts);
				}
				?>
				</tr>
			</table>
		</div>
	</div>
	<?php
}
?>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery(".vbo-free-cell, .vbo-partially-cell").click(function() {
		var idroom = jQuery(this).closest("table").attr("data-room-table");
		var celldate = jQuery(this).attr("data-cell-date");
		var cellts = jQuery(this).attr("data-cell-ts");
		if(idroom.length && celldate.length && cellts.length) {
			jQuery("#vbo-av-checkin-"+idroom).hide().find("span").text("");
			if(jQuery("#vbo-av-btn-"+idroom).length) {
				var btnlink = jQuery("#vbo-av-btn-"+idroom).attr("href");
				if(jQuery(this).hasClass("vbo-cell-selected-arrival")) {
					jQuery("#vbo-av-table-"+idroom).find("tr").find("td").removeClass("vbo-cell-selected-arrival");
					jQuery("#vbo-av-checkin-"+idroom).fadeOut().find("span").text(celldate);
					btnlink = btnlink.replace(/(checkin=)[^\&]+/, '$1' + "-1");
				}else {
					jQuery("#vbo-av-table-"+idroom).find("tr").find("td").removeClass("vbo-cell-selected-arrival");
					jQuery(this).addClass("vbo-cell-selected-arrival");
					jQuery("#vbo-av-checkin-"+idroom).fadeIn().find("span").text(celldate);
					btnlink = btnlink.replace(/(checkin=)[^\&]+/, '$1' + cellts);
				}
				jQuery("#vbo-av-btn-"+idroom).attr("href", btnlink);
			}
		}
	});
});
</script>