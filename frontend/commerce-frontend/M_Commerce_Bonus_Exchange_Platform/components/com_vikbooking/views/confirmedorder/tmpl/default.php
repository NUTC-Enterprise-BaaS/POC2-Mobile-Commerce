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

$ord = $this->ord;
$orderrooms = $this->orderrooms;
$tars = $this->tars;
$payment = $this->payment;
$vbo_tn = $this->vbo_tn;

$currencysymb = vikbooking::getCurrencySymb();
$nowdf = vikbooking::getDateFormat();
if ($nowdf == "%d/%m/%Y") {
	$df = 'd/m/Y';
} elseif ($nowdf == "%m/%d/%Y") {
	$df = 'm/d/Y';
} else {
	$df = 'Y/m/d';
}
$dbo = JFactory::getDBO();
$ptmpl = JRequest::getString('tmpl', '', 'request');
$pitemid = JRequest::getInt('Itemid', '', 'request');

$isdue = 0;
$pricenames = array();
$optbought = array();
$roomsnames = array();
$is_package = !empty($ord['pkg']) ? true : false;
foreach($orderrooms as $kor => $or) {
	$num = $kor + 1;
	$roomsnames[] = $or['name'];
	if($is_package === true || (!empty($or['cust_cost']) && $or['cust_cost'] > 0.00)) {
		//package cost or cust_cost should always be inclusive of taxes
		$calctar = $or['cust_cost'];
		$isdue += $calctar;
		$pricenames[$num] = (!empty($or['pkg_name']) ? $or['pkg_name'] : JText::_('VBOROOMCUSTRATEPLAN'));
	}elseif (array_key_exists($num, $tars) && is_array($tars[$num])) {
		$calctar = vikbooking::sayCostPlusIva($tars[$num]['cost'], $tars[$num]['idprice']);
		$tars[$num]['calctar'] = $calctar;
		$isdue += $calctar;
		$pricenames[$num] = vikbooking::getPriceName($tars[$num]['idprice'], $vbo_tn);
	}
	if (!empty ($or['optionals'])) {
		$stepo = explode(";", $or['optionals']);
		foreach ($stepo as $one) {
			if (!empty ($one)) {
				$stept = explode(":", $one);
				$q = "SELECT * FROM `#__vikbooking_optionals` WHERE `id`='" . $stept[0] . "';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($dbo->getNumRows() == 1) {
					$actopt = $dbo->loadAssocList();
					$vbo_tn->translateContents($actopt, '#__vikbooking_optionals');
					$chvar = '';
					if (!empty($actopt[0]['ageintervals']) && $or['children'] > 0 && strstr($stept[1], '-') != false) {
						$optagecosts = vikbooking::getOptionIntervalsCosts($actopt[0]['ageintervals']);
						$optagenames = vikbooking::getOptionIntervalsAges($actopt[0]['ageintervals']);
						$agestept = explode('-', $stept[1]);
						$stept[1] = $agestept[0];
						$chvar = $agestept[1];
						$actopt[0]['chageintv'] = $chvar;
						$actopt[0]['name'] .= ' ('.$optagenames[($chvar - 1)].')';
						$realcost = (intval($actopt[0]['perday']) == 1 ? (floatval($optagecosts[($chvar - 1)]) * $ord['days'] * $stept[1]) : (floatval($optagecosts[($chvar - 1)]) * $stept[1]));
					}else {
						$realcost = (intval($actopt[0]['perday']) == 1 ? ($actopt[0]['cost'] * $ord['days'] * $stept[1]) : ($actopt[0]['cost'] * $stept[1]));
					}
					if (!empty ($actopt[0]['maxprice']) && $actopt[0]['maxprice'] > 0 && $realcost > $actopt[0]['maxprice']) {
						$realcost = $actopt[0]['maxprice'];
						if(intval($actopt[0]['hmany']) == 1 && intval($stept[1]) > 1) {
							$realcost = $actopt[0]['maxprice'] * $stept[1];
						}
					}
					if ($actopt[0]['perperson'] == 1) {
						$realcost = $realcost * $or['adults'];
					}
					$tmpopr = vikbooking::sayOptionalsPlusIva($realcost, $actopt[0]['idiva']);
					$isdue += $tmpopr;
					$optbought[$num] = $optbought[$num].($stept[1] > 1 ? $stept[1] . " " : "") . $actopt[0]['name'] . ": <span class=\"vbo_currency\">" . $currencysymb . "</span> <span class=\"vbo_price\">" . vikbooking::numberFormat($tmpopr) . "</span><br/>";
				}
			}
		}
	}
}

$usedcoupon = false;
$origisdue = $isdue;
if(strlen($ord['coupon']) > 0) {
	$usedcoupon = true;
	$expcoupon = explode(";", $ord['coupon']);
	$isdue = $isdue - $expcoupon[1];
}

//print button
if($ptmpl != 'component') {
	?>
<div class="vbo-booking-print">
	<a class="vbo-booking-print-link" href="<?php echo JRoute::_('index.php?option=com_vikbooking&task=vieworder&sid='.$ord['sid'].'&ts='.$ord['ts'].'&tmpl=component'.(!empty($pitemid) ? '&Itemid='.$pitemid : '')); ?>" target="_blank" title="<?php echo JText::_('VBOPRINT'); ?>"><img src="<?php echo JURI::root(); ?>components/com_vikbooking/resources/images/printer.png" alt="<?php echo JText::_('VBOPRINT'); ?>" /></a>
</div>
	<?php
}
//

echo vikbooking::getFullFrontTitle();

?>
<p class="successmade"><?php echo JText::_('VBORDEREDON'); ?> <?php echo date($df.' H:i', $ord['ts']); ?> - <?php echo JText::_('VBCONFIRMNUMB'); ?>: <?php echo $ord['confirmnumber']; ?></p> 
	
<div class="vbo-booking-details-wrapper">
	<div class="vbo-order-arrivdep-info">
		<div class="vbcheckinroom"><span><?php echo JText::_('VBDAL'); ?></span><?php echo date($df.' H:i', $ord['checkin']); ?></div>
		<div class="vbcheckoutroom"><span><?php echo JText::_('VBAL'); ?></span><?php echo date($df.' H:i', $ord['checkout']); ?></div>
	</div>
	<div class="vbvordudata">
		<span class="vbvordudatatitle"><?php echo JText::_('VBPERSDETS'); ?>:</span> <?php echo nl2br($ord['custdata']); ?>
	</div>
</div>

<div class="vbo-booking-rooms-wrapper">
<?php
foreach($orderrooms as $kor => $or) {
	$num = $kor + 1;
	?>
	<div class="vbvordroominfo<?php echo count($orderrooms) > 1 ? ' vbvordroominfo-multi' : ''; ?>">
		<div class="vbordroomdet">
			<span class="vbvordroominfotitle"><?php echo $or['name']; ?></span>
			<div class="vbordroomdetpeople">
				<span class="vbo-booking-numadults"><?php echo $or['adults']; ?> <?php echo ($or['adults'] == 1 ? JText::_('VBSEARCHRESADULT') : JText::_('VBSEARCHRESADULTS')); ?></span>
			<?php
			if ($or['children'] > 0) {
				?>
				<span class="vbo-booking-numchildren"><?php echo $or['children']." ".($or['children'] == 1 ? JText::_('VBSEARCHRESCHILD') : JText::_('VBSEARCHRESCHILDREN')); ?></span>
				<?php
			}
			?>
			</div>
		</div>
		<?php
		if (strlen($or['img']) > 0) {
			?>
			<img src="<?php echo JURI::root(); ?>components/com_vikbooking/resources/uploads/<?php echo $or['img']; ?>"/>
			<?php
		}
		?>
		
		<div class="vbvordcosts">
		<?php
		if($is_package === true || (!empty($or['cust_cost']) && $or['cust_cost'] > 0.00)) {
			?>
			<p><span class="vbvordcoststitlemain"><?php echo $pricenames[$num]; ?>: <span class="vbo_currency"><?php echo $currencysymb; ?></span> <span class="vbo_price"><?php echo vikbooking::numberFormat($or['cust_cost']); ?></span></span></p>
			<?php
		}elseif (array_key_exists($num, $tars) && is_array($tars[$num])) {
			?>
			<p><span class="vbvordcoststitlemain"><?php echo $pricenames[$num]; ?>: <span class="vbo_currency"><?php echo $currencysymb; ?></span> <span class="vbo_price"><?php echo vikbooking::numberFormat($tars[$num]['calctar']); ?></span></span></p>
			<?php
		}
		if(strlen($optbought[$num]) > 0) {
			?>
			<p><span class="vbvordcoststitle"><?php echo JText::_('VBOPTS'); ?>:</span><div class="vbvordcostsoptionals"><?php echo $optbought[$num]; ?></div></p>
			<?php
		}
		?>
		</div>
		
	</div>
	<?php
}
?>
</div>
	
	<div class="vbvordcosts">
		<?php if($usedcoupon == true) { ?>
		<p class="vbvordcostsdiscount"><span class="vbvordcoststitle"><?php echo JText::_('VBCOUPON').' '.$expcoupon[2]; ?>:</span> - <?php echo $currencysymb; ?> <?php echo vikbooking::numberFormat($expcoupon[1]); ?></p>
		<?php } ?>
		<p class="vbvordcoststot"><span class="vbvordcoststitle"><?php echo JText::_('VBTOTAL'); ?>:</span> <span class="vbo_currency"><?php echo $currencysymb; ?></span> <span class="vbo_price"><?php echo vikbooking::numberFormat($isdue); ?></span></p>
	</div>

	<?php
	if (@ is_array($payment) && intval($payment['shownotealw']) == 1) {
		if(strlen($payment['note']) > 0) {
			?>
			<div class="vbvordpaynote"><?php echo $payment['note']; ?></div>
			<?php
		}
	}
	
	if (vikbooking::multiplePayments() && $ord['total'] > 0 && $ord['totpaid'] > 0.00 && $ord['totpaid'] < $ord['total'] && $ord['paymcount'] > 0) {
		//write again the payment form because the order was not fully paid
		require_once(JPATH_ADMINISTRATOR . DS ."components". DS ."com_vikbooking". DS . "payments" . DS . $payment['file']);
		$return_url = JURI::root() . "index.php?option=com_vikbooking&task=vieworder&sid=" . $ord['sid'] . "&ts=" . $ord['ts'];
		$error_url = JURI::root() . "index.php?option=com_vikbooking&task=vieworder&sid=" . $ord['sid'] . "&ts=" . $ord['ts'];
		$notify_url = JURI::root() . "index.php?option=com_vikbooking&task=notifypayment&sid=" . $ord['sid'] . "&ts=" . $ord['ts']."&tmpl=component";
		$transaction_name = vikbooking::getPaymentName();
		$remainingamount = $ord['total'] - $ord['totpaid'];
		$leave_deposit = 0;
		$percentdeposit = "";
		$array_order = array ();
		$array_order['details'] = $ord;
		$array_order['customer_email'] = $ord['custmail'];
		$array_order['account_name'] = vikbooking::getPaypalAcc();
		$array_order['transaction_currency'] = vikbooking::getCurrencyCodePp();
		$array_order['rooms_name'] = implode(", ", $roomsnames);
		$array_order['transaction_name'] = !empty ($transaction_name) ? $transaction_name : implode(", ", $roomsnames);
		$array_order['order_total'] = $remainingamount;
		$array_order['currency_symb'] = $currencysymb;
		$array_order['net_price'] = $remainingamount;
		$array_order['tax'] = 0;
		$array_order['return_url'] = $return_url;
		$array_order['error_url'] = $error_url;
		$array_order['notify_url'] = $notify_url;
		$array_order['total_to_pay'] = $remainingamount;
		$array_order['total_net_price'] = $remainingamount;
		$array_order['total_tax'] = 0;
		$array_order['leave_deposit'] = $leave_deposit;
		$array_order['percentdeposit'] = $percentdeposit;
		$array_order['payment_info'] = $payment;
		
		?>
		<div class="vbvordcosts vbo-remaining-balance-block">
			<p class="vbvordcoststot"><span class="vbvordcoststitle"><?php echo JText::_('VBTOTALREMAINING'); ?>:</span> <span class="vbo_currency"><?php echo $currencysymb; ?></span> <span class="vbo_price"><?php echo vikbooking::numberFormat($remainingamount); ?></span></p>
		</div>
		<div class="vbvordpaybutton">
		<?php
		$obj = new vikBookingPayment($array_order, json_decode($payment['params'], true));
		$obj->showPayment();
		?>
		</div>
		<?php
	}else {
		if($ptmpl != 'component') {
			if ($ord['total'] > 0 && $ord['totpaid'] > 0.00 && $ord['totpaid'] < $ord['total']) {
				$remainingamount = $ord['total'] - $ord['totpaid'];
			?>
		<div class="vbvordcosts vbo-amount-paid-block">
			<p class="vbvordcoststot"><span class="vbvordcoststitle"><?php echo JText::_('VBAMOUNTPAID'); ?>:</span> <span class="vbo_currency"><?php echo $currencysymb; ?></span> <span class="vbo_price"><?php echo vikbooking::numberFormat($ord['totpaid']); ?></span></p>
		</div>
		<div class="vbvordcosts vbo-remaining-balance-block">
			<p class="vbvordcoststot"><span class="vbvordcoststitle"><?php echo JText::_('VBTOTALREMAINING'); ?>:</span> <span class="vbo_currency"><?php echo $currencysymb; ?></span> <span class="vbo_price"><?php echo vikbooking::numberFormat($remainingamount); ?></span></p>
		</div>
			<?php
			}
		?>
		<script type="text/javascript">
		function vbOpenCancOrdForm() {
			document.getElementById('vbopencancform').style.display = 'none';
			document.getElementById('vbordcancformbox').style.display = 'block';
		}
		function vbValidateCancForm() {
			var vrvar = document.vbcanc;
			if(!document.getElementById('vbcancemail').value.match(/\S/)) {
				document.getElementById('vbformcancemail').style.color='#ff0000';
				return false;
			}else {
				document.getElementById('vbformcancemail').style.color='';
			}
			if(!document.getElementById('vbcancreason').value.match(/\S/)) {
				document.getElementById('vbformcancreason').style.color='#ff0000';
				return false;
			}else {
				document.getElementById('vbformcancreason').style.color='';
			}
			return true;
		}
		</script>
		<div class="vbordcancbox">
			<h3><?php echo JText::_('VBREQUESTCANCMOD'); ?></h3>
			<a href="javascript: void(0);" id="vbopencancform" onclick="javascript: vbOpenCancOrdForm();"><?php echo JText::_('VBREQUESTCANCMODOPENTEXT'); ?></a>
			<div class="vbordcancformbox" id="vbordcancformbox">
				<form action="<?php echo JRoute::_('index.php?option=com_vikbooking'); ?>" name="vbcanc" method="post" onsubmit="javascript: return vbValidateCancForm();">
					<div class="vbordcancform-inner">
						<div class="vbordcancform-entry">
							<div class="vbordcancform-entry-label">
								<span id="vbformcancemail"><?php echo JText::_('VBREQUESTCANCMODEMAIL'); ?></span>
							</div>
							<div class="vbordcancform-entry-inp">
								<input type="text" class="vbinput" name="email" id="vbcancemail" value="<?php echo $ord['custmail']; ?>"/>
							</div>
						</div>
						<div class="vbordcancform-entry">
							<div class="vbordcancform-entry-label">
								<span id="vbformcancreason"><?php echo JText::_('VBREQUESTCANCMODREASON'); ?></span>
							</div>
							<div class="vbordcancform-entry-inp">
								<textarea name="reason" id="vbcancreason" rows="7" cols="30" class="vbtextarea"></textarea>
							</div>
						</div>
						<div class="vbordcancform-entry-submit">
							<input type="submit" name="sendrequest" value="<?php echo JText::_('VBREQUESTCANCMODSUBMIT'); ?>" class="btn"/>
						</div>
					</div>
					<input type="hidden" name="sid" value="<?php echo $ord['sid']; ?>"/>
					<input type="hidden" name="idorder" value="<?php echo $ord['id']; ?>"/>
					<input type="hidden" name="option" value="com_vikbooking"/>
					<input type="hidden" name="task" value="cancelrequest"/>
				</form>
			</div>
		</div>
		<?php
		}else {
			?>
		<script type="text/javascript">
		window.print();
		</script>
			<?php
		}
	}
	?>