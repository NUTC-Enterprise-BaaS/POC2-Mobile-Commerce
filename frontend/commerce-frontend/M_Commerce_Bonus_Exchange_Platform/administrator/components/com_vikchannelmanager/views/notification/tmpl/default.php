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

$notification = $this->notification;
$row = $this->row;
$rooms = $this->rooms;
$busy = $this->busy;

$currencyname = VikChannelManager::getCurrencySymb();

$dbo = JFactory::getDBO();

$date_format = VikChannelManager::getDateFormat(true);

if( $date_format == "%d/%m/%Y" ) {
	$df = 'd/m/Y';
} else if( $date_format == "%d/%m/%Y" ) {
	$df = 'Y/m/d';
} else {
	$df = 'm/d/Y';
}

$txt_parts = explode("\n", $notification['cont']);
$render_mess = VikChannelManager::getErrorFromMap(trim($txt_parts[0]), true);
unset($txt_parts[0]);
$notification['cont'] = $render_mess.(count($txt_parts) > 0 ? "\n".implode("\n", $txt_parts) : '');
switch( intval($notification['type']) ) {
	case 1:
		$ntype = 'Success';
		break;
	case 2:
		$ntype = 'Success - Warning';
		break;
	default:
		$ntype = 'Error';
		break;
}

$otachannel = '';
$otacurrency = '';
if(!empty($row['idorderota'])) {
	$channelparts = explode('_', $row['channel']);
	$otachannel = strlen($channelparts[1]) > 0 ? $channelparts[1] : $channelparts[0];
	$otachannel .= ' - Booking ID: '.$row['idorderota'];
	$otacurrency = strlen($row['chcurrency']) > 0 ? $row['chcurrency'] : '';
}
?>

<?php echo (!empty($row['idorderota']) ? "<span class=\"vcmotaspblock\">".$otachannel."</span>" : ""); ?>
<p class="vcm-notif-globcont"><strong><?php echo JText::_('VCMNOTIFICATIONTYPE'); ?>:</strong> <?php echo $ntype; ?></p>
<pre class="vcmnotifymessblock" id="vcm-notif-globcont" <?php if (count($notification['children']) > 0): ?>style="display: none;"<?php endif; ?>><?php echo htmlentities(urldecode($notification['cont'])); ?></pre>

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
		$plain_log = urldecode($notification['cont']);
		if(stripos($plain_log, 'card number') !== false && strpos($plain_log, '****') !== false) {
			//log contains credit card details
			?>
<div class="vcm-notif-pcidrq-container">
	<a class="vcm-pcid-launch" href="index.php?option=com_vikchannelmanager&amp;task=execpcid&amp;channel_source=<?php echo $channel_source; ?>&amp;otaid=<?php echo $row['idorderota']; ?>&amp;tmpl=component"><?php echo JText::_('VCMPCIDLAUNCH'); ?></a>
</div>
			<?php
		}
	}
}
//
?>

<p><strong><?php echo JText::_('VCMDASHNOTSFROM'); ?>:</strong> <?php echo $notification['from']; ?> - <strong><?php echo JText::_('VCMDASHNOTSDATE'); ?>:</strong> <?php echo date($df.' H:i', $notification['ts']); ?></p>
<?php
if (count($notification['children']) > 0) {
	foreach ($notification['children'] as $child) {
		$txt_parts = explode("\n", $child['cont']);
		$render_mess = VikChannelManager::getErrorFromMap(trim($txt_parts[0]), true);
		unset($txt_parts[0]);
		$child['cont'] = $render_mess.(count($txt_parts) > 0 ? "\n".implode("\n", $txt_parts) : '');
		switch( intval($child['type']) ) {
			case 1:
				$ntype = 'Success';
				break;
			case 2:
				$ntype = 'Success - Warning';
				break;
			default:
				$ntype = 'Error';
				break;
		}
		?>
		<div class="vcm-childnotification-block">
			<div class="vcm-childnotification-head">
				<span><strong><?php echo JText::_('VCMNOTIFICATIONTYPE'); ?>:</strong> <?php echo $ntype; ?></span>
		<?php
		if (!empty($child['channel'])) {
			$channel_info = VikChannelManager::getChannel($child['channel']);
			if(count($channel_info) > 0) {
				?>
				<span class="vcm-sp-right"><span class="vbotasp <?php echo $channel_info['name']; ?>"><?php echo ucwords($channel_info['name']); ?></span></span>
				<?php
			}
		}
		?>
			</div>
		<?php
		if (!empty($child['cont'])) {
			//parse {hotelid n} for Multiple Accounts
			if(strpos($child['cont'], '{hotelid') !== false) {
				$child['cont'] = VikChannelManager::parseNotificationHotelId($child['cont'], $child['channel']);
			}
			?>
			<pre class="vcmnotifymessblock"><?php echo htmlentities(urldecode($child['cont'])); ?></pre>
			<?php
		}
		?>
		</div>
		<?php
	}
	?>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery(".vcm-notif-globcont").css("cursor", "pointer");
	jQuery(".vcm-notif-globcont").click(function(){
		jQuery("#vcm-notif-globcont").toggle();
		jQuery(this).toggleClass("vcm-notif-glob-opened");
	});
});
</script>
	<?php
}
?>
<br clear="all"/>

<?php
if( is_array($rooms) ) {
	$currencyname = VikChannelManager::getCurrencySymb();
	$payment = VikChannelManager::getPaymentVb($row['idpayment']);
	
	$tars = array();
	$arrpeople = array();
	foreach($rooms as $ind => $or) {
		$num = $ind + 1;
		if(!empty($or['idtar'])) {
			$q = "SELECT * FROM `#__vikbooking_dispcost` WHERE `id`=".$or['idtar'].";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$tar = $dbo->loadAssocList();
		}else {
			$tar = array();
		}
		$tars[$num] = $tar;
		$arrpeople[$num]['adults'] = $or['adults'];
		$arrpeople[$num]['children'] = $or['children'];
	}
	$secdiff = $row['checkout'] - $row['checkin'];
	$daysdiff = $secdiff / 86400;
	if( is_int($daysdiff) ) {
		if ($daysdiff < 1) {
			$daysdiff = 1;
		}
	} else {
		if( $daysdiff < 1 ) {
			$daysdiff = 1;
		} else {
			$sum = floor($daysdiff) * 86400;
			$newdiff = $secdiff - $sum;
			$maxhmore = VikChannelManager::getHoursMoreRb() * 3600;
			if($maxhmore >= $newdiff) {
				$daysdiff = floor($daysdiff);
			} else {
				$daysdiff = ceil($daysdiff);
			}
		}
	}
	?>
	
	<div class="vcm-notif-left-block">
		<p class="vborderof"><strong><?php echo JText::_('VCMVBEDITORDERONE'); ?>:</strong> <?php echo date($df.' H:i', $row['ts']); ?></p>
		<?php
		$status_txt = JText::_('VBCONFIRMED');
		$status_class = 'successmade';
		if ($row['status'] == "standby") {
			$status_txt = JText::_('VBSTANDBY');
			$status_class = 'standby';
		}elseif ($row['status'] == "cancelled") {
			$status_txt = JText::_('VBCANCELLED');
			$status_class = 'cancelled';
		}
		?>
		<p class="<?php echo $status_class; ?>"><?php echo $status_txt; ?></p>
		<span class="vcm-notif-idblock"><a href="index.php?option=com_vikbooking&task=editorder&cid[]=<?php echo $row['id']; ?>" target="_blank">ID: <?php echo $row['id']; ?></a></span>
		<?php
		if(strlen($row['confirmnumber']) > 0) {
		?>
		<p><strong><?php echo JText::_('VCMVBCONFIRMNUMB'); ?>:</strong> <?php echo $row['confirmnumber']; ?></p>
		<?php
		}
		if(!empty($row['custdata'])) {
		?>
		<p>
			<strong><?php echo JText::_('VCMVBEDITORDERTWO'); ?>:</strong><br/>
			<?php echo nl2br($row['custdata']); ?>
		</p>
		<?php
		}
		?>
	</div>
	
	<div class="vcm-notif-right-block">
		
		<p><strong><?php echo JText::_('VCMVBEDITORDERFOUR'); ?>:</strong> <?php echo $row['days']; ?> - <strong><?php echo JText::_('VCMVBEDITORDERROOMSNUM'); ?>:</strong> <?php echo $row['roomsnum']; ?></p>
		<p><strong><?php echo JText::_('VCMVBEDITORDERFIVE'); ?>:</strong> <?php echo date($df.' H:i', $row['checkin']); ?></p>
		<p><strong><?php echo JText::_('VCMVBEDITORDERSIX'); ?>:</strong> <?php echo date($df.' H:i', $row['checkout']); ?></p>
		
		<div class="vcm-notif-rooms-det">
		<?php
		foreach($rooms as $ind => $or) {
			$num = $ind + 1;
			?>
			<div class="vcm-notif-room-cont">
				<span class="vcm-notif-title"><span><?php echo JText::sprintf('VCMVBNOTROOMNUM', $num); ?>:</span> <?php echo $or['name']; ?></span>
				<span class="vcm-notif-title"><span><?php echo JText::_('VCMVBEDITORDERADULTS'); ?>:</span> <?php echo $arrpeople[$num]['adults']; ?></span>
			<?php
			if ($arrpeople[$num]['children'] > 0) {
				?>
				<span class="vcm-notif-title"><span><?php echo JText::_('VCMVBEDITORDERCHILDREN'); ?>:</span> <?php echo $arrpeople[$num]['children']; ?></span>
				<?php
			}
			if(count($tars[$num]) > 0) {
			?>
				<span class="vcm-notif-title"><span><?php echo JText::_('VCMVBEDITORDERSEVEN'); ?>:</span> <?php echo VikChannelManager::getPriceName($tars[$num][0]['idprice']); ?></span>
			<?php
			}
			?>
			</div>
			<?php
		}
		?>
		</div>
		
		<p class="vcm-notif-largep"><strong><?php echo JText::_('VCMVBEDITORDERNINE'); ?>:</strong> <?php echo (strlen($otacurrency) > 0 ? '('.$otacurrency.') '.$currencyname : $currencyname); ?> <?php echo $row['total']; ?></p>
		
	</div>
	
<?php
}
?>

