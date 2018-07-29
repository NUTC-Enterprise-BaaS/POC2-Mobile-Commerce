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

$config = $this->config;
$vbrooms = $this->vbrooms;
$comparison = $this->comparison;
$channel = $this->channel;
$currencysymb = $this->currencysymb;
$rars = $this->rars;
$rar_updates = $this->rar_updates;
$currency = VikChannelManager::getCurrencyName(true);

$cookie = JFactory::getApplication()->input->cookie;
$cookie_ariprmodel = $cookie->get('vcmAriPrModel'.$channel['uniquekey'], '', 'string');

$inventory_loaded = array_key_exists('NoInventory', $rars) ? false : true;

$vik = new VikApplication(VersionListener::getID());

$ota_rooms = array();
foreach ($vbrooms as $rxref) {
	$ota_rooms[$rxref['idroomota']][] = $rxref;
}
?>
<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
<?php echo $vik->openTableHead(); ?>
	<tr>
		<th width="20">
			<?php echo $vik->getAdminToggle(count($rows)); ?>
		</th>
		<th class="title" width="100"><?php echo JText::_('VCMRARDATE'); ?></th>
		<th class="title" width="100"><?php echo JText::_('VCMOTAROOMTYPE'); ?></th>
		<th class="title center" width="40" align="center"><?php echo JText::_('VCMRAROPEN'); ?></th>
		<th class="title center" width="75" align="center"><?php echo JText::_('VCMRARINVENTORY'); ?></th>
		<th class="title left" width="250"><?php echo JText::_('VCMRARRATEPLAN'); ?></th>
		<th class="title left" width="200"><?php echo JText::_('VCMRARRESTRICTIONS'); ?></th>
	</tr>
<?php echo $vik->closeTableHead(); ?>
<?php
$k = 0;
$i = 0;
$max_rooms_found = 0;
$max_rateplans_found = 0;
$parent_rates_found = 0;
foreach ($rars['AvailRate'] as $day => $rooms) {
	$was_updated = $inventory_loaded && count($rar_updates) && array_key_exists($day, $rar_updates) ? true : false;
	$day_rooms = count($rooms);
	$max_rooms_found = $day_rooms > $max_rooms_found ? $day_rooms : $max_rooms_found;
	$ota_rate_plan = !empty($ota_rooms[$rooms[0]['id']][key($ota_rooms[$rooms[0]['id']])]['otapricing']) ? json_decode($ota_rooms[$rooms[0]['id']][key($ota_rooms[$rooms[0]['id']])]['otapricing'], true) : array();
	$inv_details = '';
	foreach ($rooms[0]['Inventory'] as $inv_type => $inv_val) {
		$inv_details .= '- '.$inv_type.': '.$inv_val.'&lt;br/&gt;';
	}
	?>
	<tr class="row<?php echo $k; ?>">
		<td rowspan="<?php echo $day_rooms; ?>"><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $day; ?>" class="vcm-rar-ckb" onClick="<?php echo $vik->checkboxOnClick(); ?>"><span id="date<?php echo $day; ?>"></span></td>
		<td rowspan="<?php echo $day_rooms; ?>"><div class="vcmrardate-box"><span class="vcmrardate"><?php echo $day; ?></span></div></td>
		<td><div class="vcmrar-room-box"><span class="vcmshowtip vcmistip" title="ID <?php echo $rooms[0]['id']; ?>"><?php echo $ota_rooms[$rooms[0]['id']][0]['otaroomname']; ?></span></div></td>
		<td class="center" align="center"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikchannelmanager/assets/css/images/<?php echo $rooms[0]['closed'] == 'true' ? 'disabled' : 'enabled'; ?>.png" class="imgtoggle" onclick="toggleRoomStatus(<?php echo $rooms[0]['closed'] == 'true' ? '0' : '1'; ?>, '<?php echo $day; ?>', '<?php echo $rooms[0]['id'] ?>');"/><div class="vcmrar-newroomstatus" id="divroomstatus<?php echo $day.$rooms[0]['id']; ?>"><input type="hidden" name="<?php echo 'roomstatus_'.$day.'_'.$rooms[0]['id']; ?>" value="" id="roomstatus<?php echo $day.$rooms[0]['id']; ?>"/></div></td>
		<td class="center" align="center">
			<span class="vcmrarinventorysp"><span class="vcmshowtip" title="<?php echo $inv_details; ?>"><?php echo JText::_('VCMTOTINVAVAILABLE'); ?></span> <input type="number" min="0" name="<?php echo 'inv_'.$day.'_'.$rooms[0]['id']; ?>" value="<?php echo $rooms[0]['Inventory']['rooms_to_sell']; ?>" size="3"/></span>
	<?php
	if (@count($comparison[$day][$rooms[0]['id']]) > 0) {
		?>
			<div class="vcm-comparison vcm-compare-units">
				<span class="vcm-compare-ibelab"><?php echo JText::_('VCMCOMPONIBE'); ?></span>
				<span class="vcm-compare-ibecircle vcm-compare-ibecircleavail"><?php echo $comparison[$day][$rooms[0]['id']]['unitsavail']; ?></span>
				<span class="vcm-compare-ibeoflab"><?php echo JText::_('VCMCOMPONIBEOF'); ?></span>
				<span class="vcm-compare-ibecircle"><?php echo $comparison[$day][$rooms[0]['id']]['units']; ?></span>
			</div>
		<?php
	}
	if($was_updated && !empty($rar_updates[$day][$rooms[0]['id']]['last_update'])) {
		?>
			<div class="vcmrarinvlastupdate">
				<span class="vcmrarinvlastupdate-txt"><?php echo JText::_('VCMRARINVLASTUPDATE'); ?></span>
				<span class="vcmrarinvlastupdate-date"><?php echo $rar_updates[$day][$rooms[0]['id']]['last_update']; ?></span>
			</div>
		<?php
	}
	?>
		</td>
		<?php
		$rate_plans = '';
		$restrictions = array();
		$tot_rate_plans = count($rooms[0]['RatePlan']);
		$max_rateplans_found = $tot_rate_plans > $max_rateplans_found ? $tot_rate_plans : $max_rateplans_found;
		if($tot_rate_plans > 0) {
			foreach($rooms[0]['RatePlan'] as $rateplan) {
				$rate_plans .= '<div class="vcmrar-rateplan">'."\n";
				$rate_plan_tip = '';
				if(array_key_exists($rateplan['id'], $ota_rate_plan['RatePlan'])) {
					foreach ($ota_rate_plan['RatePlan'][$rateplan['id']] as $rpkey => $rpval) {
						$rate_plan_tip .= ucwords($rpkey).': '.$rpval."&lt;br/&gt;";
					}
					if(array_key_exists('booked', $rateplan)) {
						$rate_plan_tip .= "&lt;br/&gt;".'Booked: '.$rateplan['booked']."&lt;br/&gt;";
					}
					if(array_key_exists('cancelled', $rateplan)) {
						$rate_plan_tip .= 'Cancelled: '.$rateplan['cancelled']."&lt;br/&gt;";
					}
					if(array_key_exists('min_contracted_rooms', $rateplan)) {
						$rate_plan_tip .= 'Min Contracted Rooms: '.$rateplan['min_contracted_rooms']."&lt;br/&gt;";
					}
					if(array_key_exists('min_contracted_rooms_until', $rateplan)) {
						$rate_plan_tip .= 'Min Contracted Rooms Until: '.$rateplan['min_contracted_rooms_until']."&lt;br/&gt;";
					}
				}
				$rate_plans .= '<span class="'.(!empty($rate_plan_tip) ? 'vcmshowtip ' : '').'vcmrateplansp '.($rateplan['closed'] == 'true' ? 'vcmrateplanoff' : 'vcmrateplanon').'" id="rateplanstatus'.$day.$rooms[0]['id'].$rateplan['id'].'" title="'.$rate_plan_tip.'" onclick="toggleRatePlanStatus('.($rateplan['closed'] == 'true' ? '0' : '1').', \''.$day.'\', \''.$rateplan['id'].'\', \''.$rooms[0]['id'].'\');">'.(array_key_exists($rateplan['id'], $ota_rate_plan['RatePlan']) ? $ota_rate_plan['RatePlan'][$rateplan['id']]['name'] : 'Unknown Rate Plan').'</span><span class="vcmrar-spacer"></span><input type="hidden" name="rateplanstatus'.$day.$rooms[0]['id'].$rateplan['id'].'" value="" id="inprateplanstatus'.$day.$rooms[0]['id'].$rateplan['id'].'"/>'."\n";
				if (array_key_exists('Restrictions', $rateplan) && count($rateplan['Restrictions']) > 0) {
					$restrictions[$rateplan['id']] = $rateplan['Restrictions'];
				}
				if (array_key_exists('Rate', $rateplan) && count($rateplan['Rate']) > 0) {
					$rate_type = '';
					$rate_plans .= '<div class="vcmrar-rplan-leftblock">'."\n";
					//Full Room Price
					if($was_updated && !empty($rar_updates[$day][$rooms[0]['id']]['data']['RatePlan'][$rateplan['id']]['Rate']['price'])) {
						//previously updated record
						$rateplan['Rate']['price'] = $rar_updates[$day][$rooms[0]['id']]['data']['RatePlan'][$rateplan['id']]['Rate']['price'];
					}
					$rate_plans .= '<span class="vcmrarratesp vcm-default-pricing"><span class="vcmrarratelabel">'.JText::_('VCMBOOKINGCOMRARRATEPRICEFULL').'</span> <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'_price" value="'.$rateplan['Rate']['price'].'" placeholder="0.00"/></span>'."\n";
					//Single Occupancy
					if(array_key_exists('price1', $rateplan['Rate']) && (array_key_exists('RatePlan', $ota_rate_plan) && array_key_exists($rateplan['id'], $ota_rate_plan['RatePlan']) && array_key_exists('max_persons', $ota_rate_plan['RatePlan'][$rateplan['id']]) && $ota_rate_plan['RatePlan'][$rateplan['id']]['max_persons'] > 1)) {
						if($was_updated && !empty($rar_updates[$day][$rooms[0]['id']]['data']['RatePlan'][$rateplan['id']]['Rate']['price1'])) {
							//previously updated record
							$rateplan['Rate']['price1'] = $rar_updates[$day][$rooms[0]['id']]['data']['RatePlan'][$rateplan['id']]['Rate']['price1'];
						}
						$rate_plans .= '<span class="vcmrarratesp vcm-default-pricing"><span class="vcmrarratelabel">'.JText::_('VCMBOOKINGCOMRARRATEPRICESINGLE').'</span> <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'_price1" value="'.$rateplan['Rate']['price1'].'" placeholder="0.00"/></span>'."\n";
					}
					//Rates based on LOS only for parent rate plans
					if(array_key_exists($rateplan['id'], $ota_rate_plan['RatePlan']) && (!array_key_exists('is_child_rate', $ota_rate_plan['RatePlan'][$rateplan['id']]) || (int)$ota_rate_plan['RatePlan'][$rateplan['id']]['is_child_rate'] != 1)) {
						$parent_rates_found++;
						$start_los = 1;
						$max_los = 30;
						$rocc = 2;
						$last_nights = 0;
						if(array_key_exists('max_persons', $ota_rate_plan['RatePlan'][$rateplan['id']])) {
							$rocc = intval($ota_rate_plan['RatePlan'][$rateplan['id']]['max_persons']);
						}
						$rate_plans .= '<div class="vcmrar-rplan-los-block">'."\n";
						$rate_plans .= '<div class="vcmrar-rplan-losoccupancy-cont">'."\n";
						for($z = 1; $z <= $rocc; $z++) {
							$prev_los = '';
							if($was_updated && @is_array($rar_updates[$day][$rooms[0]['id']]['data']['RatePlan'][$rateplan['id']]) && array_key_exists('RatesLOS', $rar_updates[$day][$rooms[0]['id']]['data']['RatePlan'][$rateplan['id']])) {
								//previously updated record
								if(array_key_exists($z, $rar_updates[$day][$rooms[0]['id']]['data']['RatePlan'][$rateplan['id']]['RatesLOS'])) {
									if(count($rar_updates[$day][$rooms[0]['id']]['data']['RatePlan'][$rateplan['id']]['RatesLOS'][$z]) > 0) {
										foreach ($rar_updates[$day][$rooms[0]['id']]['data']['RatePlan'][$rateplan['id']]['RatesLOS'][$z] as $prev_n => $prev_p) {
											$prev_los .= '<span class="vcmrarratesp vcm-los-new" data-los="'.$z.'_'.$prev_n.'">'.JText::sprintf('VCMRARRATEPERDAYLOS', $prev_n).' <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'_'.$z.'_'.($prev_n - 1).'" value="'.$prev_p.'" placeholder="0.00"></span>'."\n";
											$last_nights = $prev_n > $last_nights ? $prev_n : $last_nights;
										}
									}
								}
							}
							$rate_plans .= '<div class="vcmrar-rplan-losoccupancy-column" data-rateoccupancy="'.$z.'"'.(!empty($prev_los) ? ' style="display: block;"' : '').'><span>'.JText::sprintf(($z > 1 ? 'VCMRARADDLOSGUESTS' : 'VCMRARADDLOSGUEST'), $z).'</span><div>'.$prev_los.'</div></div>'."\n";
						}
						$rate_plans .= '</div>'."\n";
						$rate_plans .= '<span class="vcmrar-addlos" data-iberoomid="'.(!empty($ota_rooms[$rooms[0]['id']][key($ota_rooms[$rooms[0]['id']])]['idroomvb']) ? $ota_rooms[$rooms[0]['id']][key($ota_rooms[$rooms[0]['id']])]['idroomvb'] : '0').'" data-rateoccupancy="'.$rocc.'" data-rateflag="'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'" data-rateminlos="'.$start_los.'" data-ratemaxlos="'.$max_los.'" data-ratecurrency="'.$currency.'">'.JText::_('VCMRARADDLOS').'</span>';
						$rate_plans .= '<input type="hidden" name="addrateplans_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'" id="addrateplans_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'" value="'.$last_nights.'"/>';
						$rate_plans .= '<input type="hidden" name="addrateplansocc_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'" id="addrateplansocc_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'" value="'.$rocc.'"/>';
						$rate_plans .= '</div>'."\n";
					}
					//
					$rate_plans .= '</div>'."\n";
					//Comparison with IBE
					if (@count($comparison[$day][$rooms[0]['id']]) > 0) {
						//build tabs for each type of price
						$tp_tabs = array();
						foreach ($comparison[$day][$rooms[0]['id']] as $nights => $prices) {
							if (is_numeric($nights)) {
								foreach ($prices as $fare) {
									$tp_tabs[$fare['idprice']] = $fare;
								}
							}
						}
						//
						if (count($tp_tabs) > 0) {
							$rate_plans .= '<div class="vcmrar-rplan-rightblock vcm-comparison">'."\n";
							$rate_plans .= '<div class="vcm-compare-rates">'."\n";
							$tk = 0;
							foreach ($tp_tabs as $tpid => $fare) {
								$tk++;
								$tp_tab_class = $tk == 1 ? ' vcm-compare-ratetab-active' : '';
								$rate_plans .= '<div class="vcm-compare-ratetab'.$tp_tab_class.'" id="'.$day.'-'.$rooms[0]['id'].'-'.$tpid.'-'.$rateplan['id'].'"><a href="javascript: void(0);">'.(strlen($fare['name']) > 9 ? substr($fare['name'], 0, 9).'.' : $fare['name']).'</a></div>'."\n";
							}
							$tk = 0;
							foreach ($tp_tabs as $tpid => $fare) {
								$tk++;
								$tp_col_class = $tk == 1 ? ' vcm-compare-pricecols-active' : '';
								$n = 0;
								$rate_plans .= '<div class="vcm-compare-pricecols'.$tp_col_class.' '.$day.'-'.$rooms[0]['id'].'-'.$tpid.'-'.$rateplan['id'].'">'."\n";
								foreach ($rateplan['Rate'] as $kr => $rate) {
									$n = 1;
									$rate_type = $kr;
									$occ = 0;
									if($rate_type == 'price') {
										if(array_key_exists('RoomInfo', $ota_rate_plan) && array_key_exists('max_persons', $ota_rate_plan['RoomInfo']) && $ota_rate_plan['RoomInfo']['max_persons'] > 1) {
											$occ = intval($ota_rate_plan['RoomInfo']['max_persons']);
										}
									}elseif($rate_type == 'price1') {
										$occ = 1;
									}
									if(!in_array($rate_type, array('price', 'price1'))) {
										continue;
									}
									foreach ($comparison[$day][$rooms[0]['id']] as $nights => $prices) {
										if (is_numeric($nights)) {
											foreach ($prices as $price) {
												if((int)$nights == (int)$n && $tpid == $price['idprice']) {
													if($occ > 0) {
														$diffusageprice = vikbooking::loadAdultsDiff($price['idroom'], $occ);
														if (is_array($diffusageprice)) {
															if ($diffusageprice['chdisc'] == 1) {
																//Charge
																if ($diffusageprice['valpcent'] == 1) {
																	//fixed value
																	$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $price['days'] : $diffusageprice['value'];
																	$price['cost'] += $aduseval;
																}else {
																	//percentage value
																	$aduseval = $diffusageprice['pernight'] == 1 ? round(($price['cost'] * $diffusageprice['value'] / 100) * $price['days'], 2) : round(($price['cost'] * $diffusageprice['value'] / 100), 2);
																	$price['cost'] += $aduseval;
																}
															}else {
																//Discount
																if ($diffusageprice['valpcent'] == 1) {
																	//fixed value
																	$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $price['days'] : $diffusageprice['value'];
																	$price['cost'] -= $aduseval;
																}else {
																	//percentage value
																	$aduseval = $diffusageprice['pernight'] == 1 ? round(($price['cost'] * $diffusageprice['value'] / 100) * $price['days'], 2) : round(($price['cost'] * $diffusageprice['value'] / 100), 2);
																	$price['cost'] -= $aduseval;
																}
															}
														}
													}
													$rate_plans .= '<div class="vcm-compare-rates-roomcost"><span class="vcm-compare-rates-copycost" id="'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'_'.$kr.'" title="'.JText::_('VCMRARCOPYPRICE').'"></span><span class="vcm-compare-pricefornights">'.ucwords($rate_type).' - '.JText::sprintf('VCMRARCOMPNUMNIGHTS', $price['days']).'</span><span class="vcm-compare-pricecurrency">'.$currencysymb.'</span><span class="vcm-compare-pricebox">'.number_format($price['cost'], 2, '.', '').'</span></div>'."\n";
												}
											}
										}
									}
								}
								$rate_plans .= '</div>'."\n";
							}
							$rate_plans .= '</div>'."\n";
							$rate_plans .= '</div>'."\n";
						}
					}
					//End Comparison with IBE
				}
				$rate_plans .= '</div>'."\n";
			}
		}
		?>
		<td class="left"><?php echo $rate_plans; ?></td>
		<td class="left">
		<?php
		if(count($restrictions) > 0) {
			?>
			<div class="vcmrar-restr-leftblock">
			<?php
			foreach ($restrictions as $rpid => $restriction) {
				if($was_updated) {
					//previously updated records
					if(strlen($rar_updates[$day][$rooms[0]['id']]['data']['RatePlan'][$rpid]['Restrictions']['minimumstay'])) {
						$restriction['minimumstay'] = $rar_updates[$day][$rooms[0]['id']]['data']['RatePlan'][$rpid]['Restrictions']['minimumstay'];
					}
					if(strlen($rar_updates[$day][$rooms[0]['id']]['data']['RatePlan'][$rpid]['Restrictions']['maximumstay'])) {
						$restriction['maximumstay'] = $rar_updates[$day][$rooms[0]['id']]['data']['RatePlan'][$rpid]['Restrictions']['maximumstay'];
					}
					if(!empty($rar_updates[$day][$rooms[0]['id']]['data']['RatePlan'][$rpid]['Restrictions']['closedonarrival'])) {
						$restriction['closedonarrival'] = $rar_updates[$day][$rooms[0]['id']]['data']['RatePlan'][$rpid]['Restrictions']['closedonarrival'];
					}
					if(!empty($rar_updates[$day][$rooms[0]['id']]['data']['RatePlan'][$rpid]['Restrictions']['closedondeparture'])) {
						$restriction['closedondeparture'] = $rar_updates[$day][$rooms[0]['id']]['data']['RatePlan'][$rpid]['Restrictions']['closedondeparture'];
					}
				}
				?>
				<div class="vcmrarrestr-block restrflag-<?php echo $rooms[0]['id'].'_'.$day.'_'.$rpid; ?>">
					<span class="vcmrarrestr-minlos"><span class="vcmshowtip" title="<?php echo JText::sprintf('VCMRARRATEPLANID', $rpid); ?>"><?php echo JText::_('VCMRARRESTRMINLOS'); ?></span> <input type="number" min="0" name="<?php echo 'restrmin_'.$rooms[0]['id'].'_'.$day.'_'.$rpid; ?>" size="3" value="<?php echo $restriction['minimumstay']; ?>"/></span>
					<span class="vcmrarrestr-maxlos"><span class="vcmshowtip" title="<?php echo JText::sprintf('VCMRARRATEPLANID', $rpid); ?>"><?php echo JText::_('VCMRARRESTRMAXLOS'); ?></span> <input type="number" min="0" name="<?php echo 'restrmax_'.$rooms[0]['id'].'_'.$day.'_'.$rpid; ?>" size="3" value="<?php echo $restriction['maximumstay']; ?>"/></span>
					<div class="vcmrarrestr-arrivdep">
						<span class="vcmrarrestr-tag <?php echo $restriction['closedonarrival'] == 'true' ? 'vcmtagenabled' : 'vcmtagdisabled'; ?>" onclick="toggleRestrArrivalStatus(<?php echo $restriction['closedonarrival'] == 'true' ? '1' : '0'; ?>, '<?php echo $day; ?>', '<?php echo $rpid; ?>', '<?php echo $rooms[0]['id']; ?>');" id="restrplanarrival<?php echo $day.$rooms[0]['id'].$rpid; ?>"><?php echo JText::_('VCMRARRESTRCLOSEDARRIVAL'); ?></span><input type="hidden" name="restrplanarrival<?php echo $day.$rooms[0]['id'].$rpid; ?>" value="" id="inprestrplanarrival<?php echo $day.$rooms[0]['id'].$rpid; ?>"/>
						<span class="vcmrarrestr-tag <?php echo $restriction['closedondeparture'] == 'true' ? 'vcmtagenabled' : 'vcmtagdisabled'; ?>" onclick="toggleRestrDepartureStatus(<?php echo $restriction['closedondeparture'] == 'true' ? '1' : '0'; ?>, '<?php echo $day; ?>', '<?php echo $rpid; ?>', '<?php echo $rooms[0]['id']; ?>');" id="restrplandeparture<?php echo $day.$rooms[0]['id'].$rpid; ?>"><?php echo JText::_('VCMRARRESTRCLOSEDDEPARTURE'); ?></span><input type="hidden" name="restrplandeparture<?php echo $day.$rooms[0]['id'].$rpid; ?>" value="" id="inprestrplandeparture<?php echo $day.$rooms[0]['id'].$rpid; ?>"/>
					</div>
				</div>
				<?php
			}
			?>
			</div>
			<?php
			if (@count($comparison[$day][$rooms[0]['id']]) > 0) {
				?>
			<div class="vcm-comparison vcm-compare-restrictions">
				<span class="vcm-compare-ibelab vcm-compare-ibecenter"><?php echo JText::_('VCMCOMPONIBE'); ?></span>
				<span class="vcm-compare-ibelab"><?php echo JText::_('VCMRARRESTRMINLOS'); ?></span>
				<span class="vcm-compare-ibecircle"><?php echo $comparison[$day][$rooms[0]['id']]['minlos']; ?></span>
				<span class="vcm-compare-ibeoflab"></span>
				<span class="vcm-compare-ibelab"><?php echo JText::_('VCMRARRESTRMAXLOS'); ?></span>
				<span class="vcm-compare-ibecircle"><?php echo $comparison[$day][$rooms[0]['id']]['maxlos']; ?></span>
			</div>
				<?php
			}
		}
		?>
		</td>
	</tr>
	<?php
	$k = 1 - $k;
	$i++;
	if($day_rooms > 1) {
		for ($j = 1; $j < $day_rooms; $j++) {
			$ota_rate_plan = !empty($ota_rooms[$rooms[$j]['id']][key($ota_rooms[$rooms[$j]['id']])]['otapricing']) ? json_decode($ota_rooms[$rooms[$j]['id']][key($ota_rooms[$rooms[$j]['id']])]['otapricing'], true) : array();
			$inv_details = '';
			foreach ($rooms[$j]['Inventory'] as $inv_type => $inv_val) {
				$inv_details .= '- '.$inv_type.': '.$inv_val.'&lt;br/&gt;';
			}
	?>
	<tr class="row<?php echo $k; ?>">
		<td><div class="vcmrar-room-box"><span class="vcmshowtip vcmistip" title="ID <?php echo $rooms[$j]['id']; ?>"><?php echo $ota_rooms[$rooms[$j]['id']][0]['otaroomname']; ?></span></div></td>
		<td class="center" align="center"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikchannelmanager/assets/css/images/<?php echo $rooms[$j]['closed'] == 'true' ? 'disabled' : 'enabled'; ?>.png" class="imgtoggle" onclick="toggleRoomStatus(<?php echo $rooms[$j]['closed'] == 'true' ? '0' : '1'; ?>, '<?php echo $day; ?>', '<?php echo $rooms[$j]['id'] ?>');"/><div class="vcmrar-newroomstatus" id="divroomstatus<?php echo $day.$rooms[$j]['id']; ?>"><input type="hidden" name="<?php echo 'roomstatus_'.$day.'_'.$rooms[$j]['id']; ?>" value="" id="roomstatus<?php echo $day.$rooms[$j]['id']; ?>"/></div></td>
		<td class="center" align="center">
			<span class="vcmrarinventorysp"><span class="vcmshowtip" title="<?php echo $inv_details; ?>"><?php echo JText::_('VCMTOTINVAVAILABLE'); ?></span> <input type="number" min="0" name="<?php echo 'inv_'.$day.'_'.$rooms[$j]['id']; ?>" value="<?php echo $rooms[$j]['Inventory']['rooms_to_sell']; ?>" size="3"/></span>
			<?php
			if (@count($comparison[$day][$rooms[$j]['id']]) > 0) {
			?>
			<div class="vcm-comparison vcm-compare-units">
				<span class="vcm-compare-ibelab"><?php echo JText::_('VCMCOMPONIBE'); ?></span>
				<span class="vcm-compare-ibecircle vcm-compare-ibecircleavail"><?php echo $comparison[$day][$rooms[$j]['id']]['unitsavail']; ?></span>
				<span class="vcm-compare-ibeoflab"><?php echo JText::_('VCMCOMPONIBEOF'); ?></span>
				<span class="vcm-compare-ibecircle"><?php echo $comparison[$day][$rooms[$j]['id']]['units']; ?></span>
			</div>
			<?php
			}
			if($was_updated && !empty($rar_updates[$day][$rooms[$j]['id']]['last_update'])) {
				?>
			<div class="vcmrarinvlastupdate">
				<span class="vcmrarinvlastupdate-txt"><?php echo JText::_('VCMRARINVLASTUPDATE'); ?></span>
				<span class="vcmrarinvlastupdate-date"><?php echo $rar_updates[$day][$rooms[$j]['id']]['last_update']; ?></span>
			</div>
				<?php
			}
			?>
		</td>
		<?php
		$rate_plans = '';
		$restrictions = array();
		$tot_rate_plans = count($rooms[$j]['RatePlan']);
		if($tot_rate_plans > 0) {
			foreach($rooms[$j]['RatePlan'] as $rateplan) {
				$rate_plans .= '<div class="vcmrar-rateplan">'."\n";
				$rate_plan_tip = '';
				if(array_key_exists($rateplan['id'], $ota_rate_plan['RatePlan'])) {
					foreach ($ota_rate_plan['RatePlan'][$rateplan['id']] as $rpkey => $rpval) {
						$rate_plan_tip .= ucwords($rpkey).': '.$rpval."&lt;br/&gt;";
					}
					if(array_key_exists('booked', $rateplan)) {
						$rate_plan_tip .= "&lt;br/&gt;".'Booked: '.$rateplan['booked']."&lt;br/&gt;";
					}
					if(array_key_exists('cancelled', $rateplan)) {
						$rate_plan_tip .= 'Cancelled: '.$rateplan['cancelled']."&lt;br/&gt;";
					}
					if(array_key_exists('min_contracted_rooms', $rateplan)) {
						$rate_plan_tip .= 'Min Contracted Rooms: '.$rateplan['min_contracted_rooms']."&lt;br/&gt;";
					}
					if(array_key_exists('min_contracted_rooms_until', $rateplan)) {
						$rate_plan_tip .= 'Min Contracted Rooms Until: '.$rateplan['min_contracted_rooms_until']."&lt;br/&gt;";
					}
				}
				$rate_plans .= '<span class="'.(!empty($rate_plan_tip) ? 'vcmshowtip ' : '').'vcmrateplansp '.($rateplan['closed'] == 'true' ? 'vcmrateplanoff' : 'vcmrateplanon').'" id="rateplanstatus'.$day.$rooms[$j]['id'].$rateplan['id'].'" title="'.$rate_plan_tip.'" onclick="toggleRatePlanStatus('.($rateplan['closed'] == 'true' ? '0' : '1').', \''.$day.'\', \''.$rateplan['id'].'\', \''.$rooms[$j]['id'].'\');">'.(array_key_exists($rateplan['id'], $ota_rate_plan['RatePlan']) ? $ota_rate_plan['RatePlan'][$rateplan['id']]['name'] : 'Unknown Rate Plan').'</span><span class="vcmrar-spacer"></span><input type="hidden" name="rateplanstatus'.$day.$rooms[$j]['id'].$rateplan['id'].'" value="" id="inprateplanstatus'.$day.$rooms[$j]['id'].$rateplan['id'].'"/>'."\n";
				if (array_key_exists('Restrictions', $rateplan) && count($rateplan['Restrictions']) > 0) {
					$restrictions[$rateplan['id']] = $rateplan['Restrictions'];
				}
				if (array_key_exists('Rate', $rateplan) && count($rateplan['Rate']) > 0) {
					$rate_type = '';
					$rate_plans .= '<div class="vcmrar-rplan-leftblock">'."\n";
					//Full Room Price
					if($was_updated && !empty($rar_updates[$day][$rooms[$j]['id']]['data']['RatePlan'][$rateplan['id']]['Rate']['price'])) {
						//previously updated record
						$rateplan['Rate']['price'] = $rar_updates[$day][$rooms[$j]['id']]['data']['RatePlan'][$rateplan['id']]['Rate']['price'];
					}
					$rate_plans .= '<span class="vcmrarratesp vcm-default-pricing"><span class="vcmrarratelabel">'.JText::_('VCMBOOKINGCOMRARRATEPRICEFULL').'</span> <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'_price" value="'.$rateplan['Rate']['price'].'" placeholder="0.00"/></span>'."\n";
					//Single Occupancy
					if(array_key_exists('price1', $rateplan['Rate']) && (array_key_exists('RatePlan', $ota_rate_plan) && array_key_exists($rateplan['id'], $ota_rate_plan['RatePlan']) && array_key_exists('max_persons', $ota_rate_plan['RatePlan'][$rateplan['id']]) && $ota_rate_plan['RatePlan'][$rateplan['id']]['max_persons'] > 1)) {
						if($was_updated && !empty($rar_updates[$day][$rooms[$j]['id']]['data']['RatePlan'][$rateplan['id']]['Rate']['price1'])) {
							//previously updated record
							$rateplan['Rate']['price1'] = $rar_updates[$day][$rooms[$j]['id']]['data']['RatePlan'][$rateplan['id']]['Rate']['price1'];
						}
						$rate_plans .= '<span class="vcmrarratesp vcm-default-pricing"><span class="vcmrarratelabel">'.JText::_('VCMBOOKINGCOMRARRATEPRICESINGLE').'</span> <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'_price1" value="'.$rateplan['Rate']['price1'].'" placeholder="0.00"/></span>'."\n";
					}
					//Rates based on LOS only for parent rate plans
					if(array_key_exists($rateplan['id'], $ota_rate_plan['RatePlan']) && (!array_key_exists('is_child_rate', $ota_rate_plan['RatePlan'][$rateplan['id']]) || (int)$ota_rate_plan['RatePlan'][$rateplan['id']]['is_child_rate'] != 1)) {
						$parent_rates_found++;
						$start_los = 1;
						$max_los = 30;
						$rocc = 2;
						$last_nights = 0;
						if(array_key_exists('max_persons', $ota_rate_plan['RatePlan'][$rateplan['id']])) {
							$rocc = intval($ota_rate_plan['RatePlan'][$rateplan['id']]['max_persons']);
						}
						$rate_plans .= '<div class="vcmrar-rplan-los-block">'."\n";
						$rate_plans .= '<div class="vcmrar-rplan-losoccupancy-cont">'."\n";
						for($z = 1; $z <= $rocc; $z++) {
							$prev_los = '';
							if($was_updated && @is_array($rar_updates[$day][$rooms[$j]['id']]['data']['RatePlan'][$rateplan['id']]) && array_key_exists('RatesLOS', $rar_updates[$day][$rooms[$j]['id']]['data']['RatePlan'][$rateplan['id']])) {
								//previously updated record
								if(array_key_exists($z, $rar_updates[$day][$rooms[$j]['id']]['data']['RatePlan'][$rateplan['id']]['RatesLOS'])) {
									if(count($rar_updates[$day][$rooms[$j]['id']]['data']['RatePlan'][$rateplan['id']]['RatesLOS'][$z]) > 0) {
										foreach ($rar_updates[$day][$rooms[$j]['id']]['data']['RatePlan'][$rateplan['id']]['RatesLOS'][$z] as $prev_n => $prev_p) {
											$prev_los .= '<span class="vcmrarratesp vcm-los-new" data-los="'.$z.'_'.$prev_n.'">'.JText::sprintf('VCMRARRATEPERDAYLOS', $prev_n).' <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'_'.$z.'_'.($prev_n - 1).'" value="'.$prev_p.'" placeholder="0.00"></span>'."\n";
											$last_nights = $prev_n > $last_nights ? $prev_n : $last_nights;
										}
									}
								}
							}
							$rate_plans .= '<div class="vcmrar-rplan-losoccupancy-column" data-rateoccupancy="'.$z.'"'.(!empty($prev_los) ? ' style="display: block;"' : '').'><span>'.JText::sprintf(($z > 1 ? 'VCMRARADDLOSGUESTS' : 'VCMRARADDLOSGUEST'), $z).'</span><div>'.$prev_los.'</div></div>'."\n";
						}
						$rate_plans .= '</div>'."\n";
						$rate_plans .= '<span class="vcmrar-addlos" data-iberoomid="'.(!empty($ota_rooms[$rooms[$j]['id']][key($ota_rooms[$rooms[$j]['id']])]['idroomvb']) ? $ota_rooms[$rooms[$j]['id']][key($ota_rooms[$rooms[$j]['id']])]['idroomvb'] : '0').'" data-rateoccupancy="'.$rocc.'" data-rateflag="'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'" data-rateminlos="'.$start_los.'" data-ratemaxlos="'.$max_los.'" data-ratecurrency="'.$currency.'">'.JText::_('VCMRARADDLOS').'</span>';
						$rate_plans .= '<input type="hidden" name="addrateplans_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'" id="addrateplans_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'" value="'.$last_nights.'"/>';
						$rate_plans .= '<input type="hidden" name="addrateplansocc_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'" id="addrateplansocc_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'" value="'.$rocc.'"/>';
						$rate_plans .= '</div>'."\n";
					}
					//
					$rate_plans .= '</div>'."\n";
					//Comparison with IBE
					if (@count($comparison[$day][$rooms[$j]['id']]) > 0) {
						//build tabs for each type of price
						$tp_tabs = array();
						foreach ($comparison[$day][$rooms[$j]['id']] as $nights => $prices) {
							if (is_numeric($nights)) {
								foreach ($prices as $fare) {
									$tp_tabs[$fare['idprice']] = $fare;
								}
							}
						}
						//
						if (count($tp_tabs) > 0) {
							$rate_plans .= '<div class="vcmrar-rplan-rightblock vcm-comparison">'."\n";
							$rate_plans .= '<div class="vcm-compare-rates">'."\n";
							$tk = 0;
							foreach ($tp_tabs as $tpid => $fare) {
								$tk++;
								$tp_tab_class = $tk == 1 ? ' vcm-compare-ratetab-active' : '';
								$rate_plans .= '<div class="vcm-compare-ratetab'.$tp_tab_class.'" id="'.$day.'-'.$rooms[$j]['id'].'-'.$tpid.'-'.$rateplan['id'].'"><a href="javascript: void(0);">'.(strlen($fare['name']) > 9 ? substr($fare['name'], 0, 9).'.' : $fare['name']).'</a></div>'."\n";
							}
							$tk = 0;
							foreach ($tp_tabs as $tpid => $fare) {
								$tk++;
								$tp_col_class = $tk == 1 ? ' vcm-compare-pricecols-active' : '';
								$n = 0;
								$rate_plans .= '<div class="vcm-compare-pricecols'.$tp_col_class.' '.$day.'-'.$rooms[$j]['id'].'-'.$tpid.'-'.$rateplan['id'].'">'."\n";
								foreach ($rateplan['Rate'] as $kr => $rate) {
									$n = 1;
									$rate_type = $kr;
									$occ = 0;
									if($rate_type == 'price') {
										if(array_key_exists('RoomInfo', $ota_rate_plan) && array_key_exists('max_persons', $ota_rate_plan['RoomInfo']) && $ota_rate_plan['RoomInfo']['max_persons'] > 1) {
											$occ = intval($ota_rate_plan['RoomInfo']['max_persons']);
										}
									}elseif($rate_type == 'price1') {
										$occ = 1;
									}
									if(!in_array($rate_type, array('price', 'price1'))) {
										continue;
									}
									foreach ($comparison[$day][$rooms[$j]['id']] as $nights => $prices) {
										if (is_numeric($nights)) {
											foreach ($prices as $price) {
												if((int)$nights == (int)$n && $tpid == $price['idprice']) {
													if($occ > 0) {
														$diffusageprice = vikbooking::loadAdultsDiff($price['idroom'], $occ);
														if (is_array($diffusageprice)) {
															if ($diffusageprice['chdisc'] == 1) {
																//Charge
																if ($diffusageprice['valpcent'] == 1) {
																	//fixed value
																	$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $price['days'] : $diffusageprice['value'];
																	$price['cost'] += $aduseval;
																}else {
																	//percentage value
																	$aduseval = $diffusageprice['pernight'] == 1 ? round(($price['cost'] * $diffusageprice['value'] / 100) * $price['days'], 2) : round(($price['cost'] * $diffusageprice['value'] / 100), 2);
																	$price['cost'] += $aduseval;
																}
															}else {
																//Discount
																if ($diffusageprice['valpcent'] == 1) {
																	//fixed value
																	$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $price['days'] : $diffusageprice['value'];
																	$price['cost'] -= $aduseval;
																}else {
																	//percentage value
																	$aduseval = $diffusageprice['pernight'] == 1 ? round(($price['cost'] * $diffusageprice['value'] / 100) * $price['days'], 2) : round(($price['cost'] * $diffusageprice['value'] / 100), 2);
																	$price['cost'] -= $aduseval;
																}
															}
														}
													}
													$rate_plans .= '<div class="vcm-compare-rates-roomcost"><span class="vcm-compare-rates-copycost" id="'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'_'.$kr.'" title="'.JText::_('VCMRARCOPYPRICE').'"></span><span class="vcm-compare-pricefornights">'.ucwords($rate_type).' - '.JText::sprintf('VCMRARCOMPNUMNIGHTS', $price['days']).'</span><span class="vcm-compare-pricecurrency">'.$currencysymb.'</span><span class="vcm-compare-pricebox">'.number_format($price['cost'], 2, '.', '').'</span></div>'."\n";
												}
											}
										}
									}
								}
								$rate_plans .= '</div>'."\n";
							}
							$rate_plans .= '</div>'."\n";
							$rate_plans .= '</div>'."\n";
						}
					}
					//End Comparison with IBE
				}
				$rate_plans .= '</div>'."\n";
			}
		}
		?>
		<td class="left"><?php echo $rate_plans; ?></td>
		<td class="left">
		<?php
		if(count($restrictions) > 0) {
			?>
			<div class="vcmrar-restr-leftblock">
			<?php
			foreach ($restrictions as $rpid => $restriction) {
				if($was_updated) {
					//previously updated records
					if(strlen($rar_updates[$day][$rooms[$j]['id']]['data']['RatePlan'][$rpid]['Restrictions']['minimumstay'])) {
						$restriction['minimumstay'] = $rar_updates[$day][$rooms[$j]['id']]['data']['RatePlan'][$rpid]['Restrictions']['minimumstay'];
					}
					if(strlen($rar_updates[$day][$rooms[$j]['id']]['data']['RatePlan'][$rpid]['Restrictions']['maximumstay'])) {
						$restriction['maximumstay'] = $rar_updates[$day][$rooms[$j]['id']]['data']['RatePlan'][$rpid]['Restrictions']['maximumstay'];
					}
					if(!empty($rar_updates[$day][$rooms[$j]['id']]['data']['RatePlan'][$rpid]['Restrictions']['closedonarrival'])) {
						$restriction['closedonarrival'] = $rar_updates[$day][$rooms[$j]['id']]['data']['RatePlan'][$rpid]['Restrictions']['closedonarrival'];
					}
					if(!empty($rar_updates[$day][$rooms[$j]['id']]['data']['RatePlan'][$rpid]['Restrictions']['closedondeparture'])) {
						$restriction['closedondeparture'] = $rar_updates[$day][$rooms[$j]['id']]['data']['RatePlan'][$rpid]['Restrictions']['closedondeparture'];
					}
				}
				?>
				<div class="vcmrarrestr-block restrflag-<?php echo $rooms[$j]['id'].'_'.$day.'_'.$rpid; ?>">
					<span class="vcmrarrestr-minlos"><span class="vcmshowtip" title="<?php echo JText::sprintf('VCMRARRATEPLANID', $rpid); ?>"><?php echo JText::_('VCMRARRESTRMINLOS'); ?></span> <input type="number" min="0" name="<?php echo 'restrmin_'.$rooms[$j]['id'].'_'.$day.'_'.$rpid; ?>" size="3" value="<?php echo $restriction['minimumstay']; ?>"/></span>
					<span class="vcmrarrestr-maxlos"><span class="vcmshowtip" title="<?php echo JText::sprintf('VCMRARRATEPLANID', $rpid); ?>"><?php echo JText::_('VCMRARRESTRMAXLOS'); ?></span> <input type="number" min="0" name="<?php echo 'restrmax_'.$rooms[$j]['id'].'_'.$day.'_'.$rpid; ?>" size="3" value="<?php echo $restriction['maximumstay']; ?>"/></span>
					<div class="vcmrarrestr-arrivdep">
						<span class="vcmrarrestr-tag <?php echo $restriction['closedonarrival'] == 'true' ? 'vcmtagenabled' : 'vcmtagdisabled'; ?>" onclick="toggleRestrArrivalStatus(<?php echo $restriction['closedonarrival'] == 'true' ? '1' : '0'; ?>, '<?php echo $day; ?>', '<?php echo $rpid; ?>', '<?php echo $rooms[$j]['id']; ?>');" id="restrplanarrival<?php echo $day.$rooms[$j]['id'].$rpid; ?>"><?php echo JText::_('VCMRARRESTRCLOSEDARRIVAL'); ?></span><input type="hidden" name="restrplanarrival<?php echo $day.$rooms[$j]['id'].$rpid; ?>" value="" id="inprestrplanarrival<?php echo $day.$rooms[$j]['id'].$rpid; ?>"/>
						<span class="vcmrarrestr-tag <?php echo $restriction['closedondeparture'] == 'true' ? 'vcmtagenabled' : 'vcmtagdisabled'; ?>" onclick="toggleRestrDepartureStatus(<?php echo $restriction['closedondeparture'] == 'true' ? '1' : '0'; ?>, '<?php echo $day; ?>', '<?php echo $rpid; ?>', '<?php echo $rooms[$j]['id']; ?>');" id="restrplandeparture<?php echo $day.$rooms[$j]['id'].$rpid; ?>"><?php echo JText::_('VCMRARRESTRCLOSEDDEPARTURE'); ?></span><input type="hidden" name="restrplandeparture<?php echo $day.$rooms[$j]['id'].$rpid; ?>" value="" id="inprestrplandeparture<?php echo $day.$rooms[$j]['id'].$rpid; ?>"/>
					</div>
				</div>
				<?php
			}
			?>
			</div>
			<?php
			if (@count($comparison[$day][$rooms[$j]['id']]) > 0) {
				?>
			<div class="vcm-comparison vcm-compare-restrictions">
				<span class="vcm-compare-ibelab vcm-compare-ibecenter"><?php echo JText::_('VCMCOMPONIBE'); ?></span>
				<span class="vcm-compare-ibelab"><?php echo JText::_('VCMRARRESTRMINLOS'); ?></span>
				<span class="vcm-compare-ibecircle"><?php echo $comparison[$day][$rooms[$j]['id']]['minlos']; ?></span>
				<span class="vcm-compare-ibeoflab"></span>
				<span class="vcm-compare-ibelab"><?php echo JText::_('VCMRARRESTRMAXLOS'); ?></span>
				<span class="vcm-compare-ibecircle"><?php echo $comparison[$day][$rooms[$j]['id']]['maxlos']; ?></span>
			</div>
				<?php
			}
		}
		?>
		</td>
	</tr>
	<?php
		}
	}
}
?>
</table>

<input type="hidden" name="currency" value="<?php echo $currency; ?>"/>
<input type="hidden" name="e4j_debug" value="<?php echo isset($_REQUEST['e4j_debug']) && intval($_REQUEST['e4j_debug']) == 1 ? '1' : ''; ?>"/>

<script type="text/javascript">
jQuery(".vcmshowtip").tooltip({show:{delay: 500}});
vcm_rar_days = <?php echo json_encode(array_keys($rars['AvailRate'])); ?>;
var tot_parent_rates = <?php echo $parent_rates_found; ?>;
var tip_pricing_model = false;
jQuery(".vcm-copy-ratesinv").fadeIn();
<?php
if(count($comparison) > 0) {
	?>
jQuery(".vcm-ibe-compare").fadeIn();
	<?php
}
?>
if(tot_parent_rates > 0) {
	var ch_controls = "<select id=\"vcm-channel-controls-pmodeltype\" onchange=\"vcmChoosePricingModel(this.value);\"><option value=\"any\"><?php echo addslashes(JText::_('VCMRARBCOMANYPMODEL')); ?></option><option value=\"person\" title=\"<?php echo addslashes(JText::_('VCMRARBCOMPERSONPMODELTIP')); ?>\"><?php echo addslashes(JText::_('VCMRARBCOMPERSONPMODEL')); ?></option><option value=\"los\" title=\"<?php echo addslashes(JText::_('VCMRARBCOMLOSPMODELTIP')); ?>\"><?php echo addslashes(JText::_('VCMRARBCOMLOSPMODEL')); ?></option><select>";
	jQuery("#vcm-channel-controls").html(ch_controls).fadeIn();
<?php
if(!empty($cookie_ariprmodel)) {
	?>
	jQuery("#vcm-channel-controls-pmodeltype").val("<?php echo $cookie_ariprmodel; ?>").trigger("change");
	<?php
}
?>
}
var rplansheight = new Array();
jQuery(".vcmrar-rateplan").each(function(k){
	rplansheight.push(jQuery(this).height());
});
if(rplansheight.length > 0) {
	jQuery(".vcmrarrestr-block").each(function(k){
		jQuery(this).height(rplansheight[k]);
	});
}
function vcmChoosePricingModel(pmodel) {
	var nd = new Date();
	nd.setTime(nd.getTime() + (365*24*60*60*1000));
	if(pmodel == "any") {
		jQuery(".vcm-default-pricing, .vcmrar-rplan-los-block").show();
	}
	if(pmodel == "person") {
		jQuery(".vcmrar-rplan-los-block").hide();
		jQuery(".vcm-default-pricing").show();
	}
	if(pmodel == "los") {
		jQuery(".vcm-default-pricing").hide();
		jQuery(".vcmrar-rplan-los-block").show();
	}
	document.cookie = "vcmAriPrModel<?php echo $channel['uniquekey']; ?>=" + pmodel + "; expires=" + nd.toUTCString() + "; path=/";
<?php
if(empty($cookie_ariprmodel)) {
	?>
	if(!tip_pricing_model) {
		vcmAlertModal('warning warning-longer', '<?php echo addslashes(JText::_('VCMWARNINGTEXT')); ?>', '<p><?php echo addslashes(JText::_('VCMARIPRMODELEXPL')); ?></p>');
	}
	<?php
}
?>
	tip_pricing_model = true;
}
function toggleRoomStatus(status, date, roomid) {
	var setclass = status == 1 ? "vcmrar-newroomstatus-todisabled" : "vcmrar-newroomstatus-toenabled";
	var settitle = status == 1 ? "<?php echo addslashes(JText::_('VCMRARSETTOCLOSED')); ?>" : "<?php echo addslashes(JText::_('VCMRARSETTOOPEN')); ?>";
	var cur_status = jQuery("#roomstatus"+date+roomid).val();
	var opposite_status = status == 1 ? 0 : 1;
	var setstatus = cur_status.length == 0 ? opposite_status : '';
	if(cur_status.length == 0) {
		jQuery("#roomstatus"+date+roomid).val(setstatus);
		jQuery("#divroomstatus"+date+roomid).attr("title", settitle);
		jQuery("#divroomstatus"+date+roomid).removeClass("vcmrar-newroomstatus-toenabled").removeClass("vcmrar-newroomstatus-todisabled").addClass(setclass);
	}else {
		jQuery("#roomstatus"+date+roomid).val("");
		jQuery("#divroomstatus"+date+roomid).attr("title", "");
		jQuery("#divroomstatus"+date+roomid).removeClass("vcmrar-newroomstatus-toenabled").removeClass("vcmrar-newroomstatus-todisabled");
	}
	jQuery("#roomstatus"+date+roomid).trigger("change");
}
/* Booking.com has the same Rate Plan ID for multiple rooms so it is necessary to add a fourth argument roomid */
function toggleRestrArrivalStatus(status, date, rpid, roomid) {
	var setclass = status == 1 ? "vcmrarrestr-tag-todisabled" : "vcmrarrestr-tag-toenabled";
	var cur_status = jQuery("#inprestrplanarrival"+date+roomid+rpid).val();
	var opposite_status = status == 1 ? 0 : 1;
	var setstatus = cur_status.length == 0 ? opposite_status : '';
	if(cur_status.length == 0) {
		jQuery("#inprestrplanarrival"+date+roomid+rpid).val(setstatus);
		jQuery("#restrplanarrival"+date+roomid+rpid).removeClass("vcmrarrestr-tag-toenabled").removeClass("vcmrarrestr-tag-todisabled").addClass(setclass);
	}else {
		jQuery("#inprestrplanarrival"+date+roomid+rpid).val("");
		jQuery("#restrplanarrival"+date+roomid+rpid).removeClass("vcmrarrestr-tag-toenabled").removeClass("vcmrarrestr-tag-todisabled");
	}
	jQuery("#inprestrplanarrival"+date+roomid+rpid).trigger("change");
}
/* Booking.com has the same Rate Plan ID for multiple rooms so it is necessary to add a fourth argument roomid */
function toggleRestrDepartureStatus(status, date, rpid, roomid) {
	var setclass = status == 1 ? "vcmrarrestr-tag-todisabled" : "vcmrarrestr-tag-toenabled";
	var cur_status = jQuery("#inprestrplandeparture"+date+roomid+rpid).val();
	var opposite_status = status == 1 ? 0 : 1;
	var setstatus = cur_status.length == 0 ? opposite_status : '';
	if(cur_status.length == 0) {
		jQuery("#inprestrplandeparture"+date+roomid+rpid).val(setstatus);
		jQuery("#restrplandeparture"+date+roomid+rpid).removeClass("vcmrarrestr-tag-toenabled").removeClass("vcmrarrestr-tag-todisabled").addClass(setclass);
	}else {
		jQuery("#inprestrplandeparture"+date+roomid+rpid).val("");
		jQuery("#restrplandeparture"+date+roomid+rpid).removeClass("vcmrarrestr-tag-toenabled").removeClass("vcmrarrestr-tag-todisabled");
	}
	jQuery("#inprestrplandeparture"+date+roomid+rpid).trigger("change");
}
/* Booking.com has the same Rate Plan ID for multiple rooms so it is necessary to add a fourth argument roomid */
function toggleRatePlanStatus(status, date, rpid, roomid) {
	var setclass = status == 1 ? "vcmrateplansp-todisabled" : "vcmrateplansp-toenabled";
	var cur_status = jQuery("#inprateplanstatus"+date+roomid+rpid).val();
	var opposite_status = status == 1 ? 0 : 1;
	var setstatus = cur_status.length == 0 ? opposite_status : '';
	if(cur_status.length == 0) {
		jQuery("#inprateplanstatus"+date+roomid+rpid).val(setstatus);
		jQuery("#rateplanstatus"+date+roomid+rpid).removeClass("vcmrateplansp-toenabled").removeClass("vcmrateplansp-todisabled").addClass(setclass);
	}else {
		jQuery("#inprateplanstatus"+date+roomid+rpid).val("");
		jQuery("#rateplanstatus"+date+roomid+rpid).removeClass("vcmrateplansp-toenabled").removeClass("vcmrateplansp-todisabled");
	}
	jQuery("#inprateplanstatus"+date+roomid+rpid).trigger("change");
}

var fix_height = 0;
var fix_margin = 5;
var min_pos_check = 150;
function setFixHeight(limit) {
	jQuery('*').filter(function() {
	    return jQuery(this).css("position") === 'fixed' && !jQuery(this).hasClass("vcm-info-overlay-block") && !jQuery(this).hasClass("vcm-info-overlay-content");
	}).each(function(){
		if(jQuery(this).offset().top < limit) {
			fix_height += jQuery(this).height();
		}
	});
}
var tot_rooms_found = <?php echo $max_rooms_found; ?>;
var tot_rateplans_found = <?php echo $max_rateplans_found; ?>;
if(tot_rooms_found > 2) {
	jQuery(window).scroll(function() {
		var scrollpos = jQuery(window).scrollTop();
		jQuery(".vcmrardate-box").each(function(kel) {
			var d_top = jQuery(this).offset().top + jQuery(this).outerHeight(true);
			var par_d_top = jQuery(this).parent("td").offset().top;
			var par_limit = (par_d_top + jQuery(this).parent("td").height());
			if(scrollpos > min_pos_check && fix_height == 0) {
				setFixHeight(par_d_top);
			}
			if((scrollpos + fix_height + fix_margin) > par_d_top && (scrollpos + fix_height + fix_margin) < par_limit) {
				jQuery(this).css({top: (scrollpos + fix_height + fix_margin), position: 'absolute'}).addClass("vcmrardate-scroll");
				return false;
			}
			if(scrollpos == 0 || (scrollpos - fix_height - fix_margin) < par_d_top) {
				jQuery(this).css({top: 'auto', position: 'inherit'}).removeClass("vcmrardate-scroll");
				return false;
			}
		});
		jQuery(".vcmrar-room-box").each(function(kel) {
			var r_top = jQuery(this).offset().top + jQuery(this).outerHeight(true);
			var par_r_top = jQuery(this).parent("td").offset().top;
			var par_limit = (par_r_top + jQuery(this).parent("td").height());
			if(scrollpos > min_pos_check && fix_height == 0) {
				setFixHeight(par_r_top);
			}
			if((scrollpos + fix_height + fix_margin) > par_r_top && (scrollpos + fix_height + fix_margin) < par_limit) {
				jQuery(this).css({top: (scrollpos + fix_height + fix_margin), position: 'absolute'}).addClass("vcmrar-room-scroll");
				return false;
			}
			if(scrollpos == 0 || (scrollpos - fix_height - fix_margin) < par_r_top) {
				jQuery(this).css({top: 'auto', position: 'inherit'}).removeClass("vcmrar-room-scroll");
				return false;
			}
		});
	});
}
jQuery("body").on("click", "div.vcmrardate-scroll", function() {
	var gotopos = jQuery(this).find("span").text();
	jQuery('html,body').animate({ scrollTop: (jQuery("#date"+gotopos).offset().top - fix_height - fix_margin) }, { duration: 'slow' });
});

/* Add Costs per night based on LOS */
jQuery("body").on("click", ".vcmrar-addlos", function() {
	var losrateflag = jQuery(this).attr("data-rateflag");
	var losrateflag_parts = losrateflag.split("_");
	var base_los = parseInt(jQuery(this).attr("data-rateminlos"));
	var base_maxlos = parseInt(jQuery(this).attr("data-ratemaxlos"));
	var base_currency = jQuery(this).attr("data-ratecurrency");
	var losrateoccupancy = parseInt(jQuery(this).attr("data-rateoccupancy"));
	losrateoccupancy = losrateoccupancy < 1 || isNaN(losrateoccupancy) ? 1 : losrateoccupancy;
	var losrateocc_opts = '';
	for(var z = 1; z <= losrateoccupancy; z++) {
		losrateocc_opts += "<option value=\""+z+"\" selected=\"selected\">"+z+" <?php echo addslashes(JText::_('VCMRARADDLOSOCCUPANCY')); ?></option>";
	}
	var los_label = '<?php echo addslashes(JText::_('VCMRARRATEPERDAYLOS')); ?>';
	var guests_label = '<?php echo addslashes(JText::_('VCMRARRATEPERDAYLOSGUESTS')); ?>';
	var addlos_cont_html = "<h3>"+losrateflag_parts[0]+" <?php echo addslashes(JText::_('VCMRARADDLOS')); ?></h3>";
	addlos_cont_html += "<div class=\"vcm-addlos-left\"><p><?php echo addslashes(JText::_('VCMRARADDLOSNUMNIGHTS')); ?></p><div><span class=\"vcm-addlos-from\"><?php echo addslashes(JText::_('VCMRARADDLOSFROMNIGHTS')); ?></span><input type=\"number\" value=\""+base_los+"\" min=\"1\" max=\""+base_maxlos+"\" id=\"vcm-addlos-from-inp\"/><span class=\"vcm-addlos-to\"><?php echo addslashes(JText::_('VCMRARADDLOSTONIGHTS')); ?></span><input type=\"number\" value=\""+base_los+"\" min=\"1\" max=\""+base_maxlos+"\" id=\"vcm-addlos-to-inp\"/></div></div>";
	addlos_cont_html += "<div class=\"vcm-addlos-right\"><p><?php echo addslashes(JText::_('VCMRARADDLOSCOSTPNIGHT')); ?></p><div><span>"+base_currency+"</span><input type=\"text\" value=\"\" placeholder=\"0.00\" size=\"7\" id=\"vcm-addlos-price-inp\"/></div></div>";
	addlos_cont_html += "<br clear=\"all\"/><div class=\"vcm-addlos-bottom\"><div class=\"vcm-addlos-occupancy\"><select id=\"vcm-los-occupancy-sel\" size=\""+(losrateoccupancy > 4 ? 4 : losrateoccupancy)+"\" multiple=\"multiple\">"+losrateocc_opts+"</select></div>";
	addlos_cont_html += "<button class=\"btn btn-success\" id=\"vcm-addlos-apply\" type=\"button\" data-rateflag=\""+losrateflag+"\" data-ratecurrency=\""+base_currency+"\"><?php echo addslashes(JText::_('VCMRARADDLOSAPPLY')); ?></button>";
	addlos_cont_html += "<div class=\"vcm-addlos-copyratesibe\"><?php echo addslashes(JText::_('VCMOR')); ?><br/><button class=\"btn btn-primary vcm-addlos-copyratesibe-launch\" data-rateflag=\""+losrateflag+"\" data-ratecurrency=\""+base_currency+"\" data-iberoomid=\""+jQuery(this).attr("data-iberoomid")+"\" data-dateflag=\""+losrateflag_parts[0]+"\"><?php echo addslashes(JText::_('VCMRARLOADRATESIBE')); ?></button></div>";
	addlos_cont_html += "<p class=\"vcm-centerp vcm-italicp\"><?php echo addslashes(JText::_('VCMRARADDLOSEXPL')); ?></p></div>";
	var losadded_cont = "";
	var all_rplan_los = jQuery(this).parent().find(".vcm-los-new");
	if(all_rplan_los.length > 0) {
		var last_guests = '';
		all_rplan_los.each(function(){
			var now_addlos = jQuery(this).attr("data-los");
			var los_det_parts = jQuery(this).find("input[type=\"text\"]").attr("name").split("_");
			if(last_guests.length && last_guests != los_det_parts[(los_det_parts.length - 2)]) {
				losadded_cont += "<br/>";
			}
			losadded_cont += "<div class=\"vcm-addlos-applied-los\" data-rateoccupancy=\""+los_det_parts[(los_det_parts.length - 2)]+"\" data-rateflag=\""+losrateflag+"\" data-los=\""+now_addlos+"\"><span class=\"vcm-addlos-applied-cont\">"+guests_label.replace("%d", los_det_parts[(los_det_parts.length - 2)])+" - "+los_label.replace("%d", now_addlos.split("_")[1])+"</span><span class=\"vcm-addlos-unset\"> </span></div>";
			last_guests = los_det_parts[(los_det_parts.length - 2)];
		});
		losadded_cont += "<br/><button type=\"button\" class=\"btn btn-danger vcm-addlos-unset-all\"><?php echo addslashes(JText::_('VCMRARREMOVEALLLOS')); ?></button>";
	}
	addlos_cont_html += "<div class=\"vcm-addlos-applied\">"+losadded_cont+"</div>";
	jQuery(".vcm-info-overlay-content").html(addlos_cont_html);
	if(!(all_rplan_los.length > 0)) {
		jQuery(".vcm-addlos-copyratesibe").show();
	}
	jQuery(".vcm-info-overlay-block").fadeIn();
	vcm_overlay_on = true;
});
/* Add Costs per night based on LOS: from-to nights */
jQuery("body").on("change", "#vcm-addlos-from-inp", function() {
	var from_nights = parseInt(jQuery(this).val());
	var to_nights = parseInt(jQuery("#vcm-addlos-to-inp").val());
	if(to_nights < from_nights) {
		jQuery("#vcm-addlos-to-inp").val(from_nights);
	}
	jQuery("#vcm-addlos-to-inp").attr("min", from_nights);
});
/* Unset previously added costs per night based on LOS */
jQuery("body").on("click", ".vcm-addlos-unset", function() {
	var losrateflag = jQuery(this).parent().attr("data-rateflag");
	var losrateoccupancy = jQuery(this).parent().attr("data-rateoccupancy");
	var now_los = jQuery(this).parent().attr("data-los");
	jQuery(".vcmrar-addlos[data-rateflag='"+losrateflag+"']").parent().find(".vcmrarratesp[data-los='"+now_los+"']").remove();
	jQuery(this).parent(".vcm-addlos-applied-los").remove();
	//show load rates from IBE if no more rates
	if(!(jQuery(".vcm-addlos-applied-los").length > 0)) {
		jQuery(".vcm-addlos-copyratesibe").show();
	}
	//Hide column if no more rates based on los
	var los_column_cont = jQuery(".vcmrar-addlos[data-rateflag='"+losrateflag+"']").prev(".vcmrar-rplan-losoccupancy-cont").find(".vcmrar-rplan-losoccupancy-column[data-rateoccupancy='"+now_los.split("_")[0]+"']").find("div");
	if(los_column_cont.length) {
		if(!los_column_cont.find(".vcm-los-new").length) {
			los_column_cont.parent().hide();
		}
	}
	//
	//update rate-base-min-los
	var set_rmin_los = parseInt(now_los.split("_")[1]);
	var all_rplan_los = jQuery(".vcmrar-addlos[data-rateflag='"+losrateflag+"']").parent().find(".vcmrarratesp");
	if(all_rplan_los.length > 0) {
		all_rplan_los.each(function(){
			var now_addlos = jQuery(this).attr("data-los").split("_")[1];
			if(now_addlos.length > 0) {
				now_addlos = parseInt(now_addlos);
				if(set_rmin_los < now_addlos) {
					set_rmin_los = (now_addlos + 1);
				}
			}
		});
	}
	jQuery("#vcm-addlos-from-inp").val(set_rmin_los);
	jQuery("#vcm-addlos-from-inp").attr("min", set_rmin_los);
	jQuery("#vcm-addlos-to-inp").val(set_rmin_los);
	jQuery("#vcm-addlos-to-inp").attr("min", set_rmin_los);
	jQuery(".vcmrar-addlos[data-rateflag='"+losrateflag+"']").attr("data-rateminlos", set_rmin_los);
});
/* Unset All the previously added costs per night based on LOS */
jQuery("body").on("click", ".vcm-addlos-unset-all", function() {
	var unset_btns = jQuery(this).parent().find(".vcm-addlos-unset");
	if(unset_btns.length) {
		jQuery(unset_btns).each(function(){
			jQuery(this).trigger("click");
		});
	}
	jQuery(this).remove();
	jQuery("#vcm-addlos-from-inp").attr("min", "1").val("1");
});
/* Apply Costs per night based on LOS */
jQuery("body").on("click", "#vcm-addlos-apply", function() {
	var losrateflag = jQuery(this).attr("data-rateflag");
	var from_nights = parseInt(jQuery("#vcm-addlos-from-inp").val());
	var to_nights = parseInt(jQuery("#vcm-addlos-to-inp").val());
	var price_pnight = parseFloat(jQuery("#vcm-addlos-price-inp").val());
	var base_currency = jQuery(this).attr("data-ratecurrency");
	var los_label = '<?php echo addslashes(JText::_('VCMRARRATEPERDAYLOS')); ?>';
	var los_occupancy = jQuery("#vcm-los-occupancy-sel").val();
	if(los_occupancy !== null && los_occupancy !== undefined) {
		if(!isNaN(price_pnight) && los_occupancy.length > 0) {
			if(from_nights > 0 && to_nights > 0 && to_nights >= from_nights && losrateflag.length) {
				var nights_diff = to_nights - from_nights;
				var last_occ = '';
				var last_i = '';
				jQuery.each(los_occupancy, function(k, v) {
					var los_column_cont = jQuery(".vcmrar-addlos[data-rateflag='"+losrateflag+"']").prev(".vcmrar-rplan-losoccupancy-cont").find(".vcmrar-rplan-losoccupancy-column[data-rateoccupancy='"+v+"']").find("div");
					if(los_column_cont.length) {
						for(var i = 0; i <= nights_diff; i++) {
							var tot_price_pnight = parseFloat(price_pnight * (from_nights + i));
							if(!los_column_cont.find(".vcm-los-new[data-los='"+v+"_"+(from_nights + i)+"']").length) {
								los_column_cont.append("<span class=\"vcmrarratesp vcm-los-new\" data-los=\""+v+"_"+(from_nights + i)+"\">"+los_label.replace("%d", (from_nights + i))+" <span class=\"vcmrarcurrency\">"+base_currency+"</span> <input type=\"text\" placeholder=\"0.00\" value=\""+tot_price_pnight+"\" name=\"rateplan_"+losrateflag+"_"+v+"_"+(from_nights + i - 1)+"\" size=\"5\"></span>");
								last_i = (from_nights + i - 1);
							}
						}
						los_column_cont.parent().show();
					}
					last_occ = v;
				});
				if(los_occupancy.length >= last_occ) {
					jQuery(".vcmrar-addlos[data-rateflag='"+losrateflag+"']").attr("data-rateminlos", (to_nights + 1));
				}
				var cur_top_numnights = jQuery("#addrateplans_"+losrateflag).val();
				var cur_top_occupancy = jQuery("#addrateplansocc_"+losrateflag).val();
				if(cur_top_numnights.length) {
					if(parseInt(cur_top_numnights) > to_nights) {
						jQuery("#addrateplans_"+losrateflag).val(cur_top_numnights);
					}else {
						jQuery("#addrateplans_"+losrateflag).val(to_nights);
					}
				}else {
					jQuery("#addrateplans_"+losrateflag).val(to_nights);
				}
				if(cur_top_occupancy.length) {
					if(parseInt(cur_top_occupancy) > last_occ) {
						jQuery("#addrateplansocc_"+losrateflag).val(cur_top_occupancy);
					}else {
						jQuery("#addrateplansocc_"+losrateflag).val(last_occ);
					}
				}else {
					jQuery("#addrateplansocc_"+losrateflag).val(last_occ);
				}
				if(last_i.length || last_i >= 0) {
					jQuery("input[name='rateplan_"+losrateflag+"_"+last_occ+"_"+last_i+"']").trigger("change");
				}
				vcmCloseModal();
			}else {
				alert('Error: invalid data.');
			}
		}else {
			alert('Float Numbers must be formatted with a dot. Example: 12.34');
		}
	}else {
		alert('Guests Occupancy cannot be empty');
	}
});
/* Load Rates based on LOS from IBE */
var last_ibe_los = '';
jQuery("body").on("click", "button.vcm-addlos-copyratesibe-launch", function() {
	var los_occupancy = jQuery("#vcm-los-occupancy-sel").val();
	var losrateflag = jQuery(this).attr("data-rateflag");
	var base_currency = jQuery(this).attr("data-ratecurrency");
	if(los_occupancy !== null && los_occupancy !== undefined) {
		if(los_occupancy.length > 0) {
			var parent_button = jQuery(this);
			var jqxhr = jQuery.ajax({
				type: "POST",
				url: "index.php",
				data: { option: "com_vikchannelmanager", task: "loadlosibe", room_id: jQuery(this).attr("data-iberoomid"), date: jQuery(this).attr("data-dateflag"), occupancy: los_occupancy, tmpl: "component" }
			}).done(function(res) {
				if(res.substr(0, 9) == 'e4j.error') {
					alert(res.replace("e4j.error.", ""));
				}else {
					last_ibe_los = res;
					var obj_res = jQuery.parseJSON(res);
					var rplans_buttons = "";
					jQuery.each(obj_res.rate_plans, function(k, v) {
						rplans_buttons += "<button class=\"btn btn-success vcm-addlos-rateplanibe-apply\" data-rateplanid=\""+k+"\" data-rateflag=\""+losrateflag+"\" data-ratecurrency=\""+base_currency+"\">"+v+"</button>&nbsp;&nbsp;";
					});
					rplans_buttons = rplans_buttons.replace(/&nbsp;&nbsp;+$/, '');
					parent_button.replaceWith(rplans_buttons);
				}
			}).fail(function() {
				alert("Error Performing Ajax Request"); 
			});
		}else {
			alert('Guests Occupancy is empty');
		}
	}else {
		alert('Guests Occupancy cannot be empty');
	}
});
/* Apply the Loaded Rates based on LOS from IBE */
jQuery("body").on("click", "button.vcm-addlos-rateplanibe-apply", function() {
	var rplan_id = jQuery(this).attr("data-rateplanid");
	var losrateflag = jQuery(this).attr("data-rateflag");
	var base_currency = jQuery(this).attr("data-ratecurrency");
	if(rplan_id.length > 0 && last_ibe_los.length > 0) {
		var obj_res = jQuery.parseJSON(last_ibe_los);
		if(obj_res.los.hasOwnProperty(rplan_id)) {
			var los_label = '<?php echo addslashes(JText::_('VCMRARRATEPERDAYLOS')); ?>';
			var last_occ = 1;
			var last_i = '';
			var to_nights = 1;
			jQuery.each(obj_res.los[rplan_id], function(occupancy, nights_costs) {
				var los_column_cont = jQuery(".vcmrar-addlos[data-rateflag='"+losrateflag+"']").prev(".vcmrar-rplan-losoccupancy-cont").find(".vcmrar-rplan-losoccupancy-column[data-rateoccupancy='"+occupancy+"']").find("div");
				jQuery.each(nights_costs, function(nights, cost){
					if(los_column_cont.find(".vcm-los-new[data-los='"+occupancy+"_"+nights+"']").length) {
						los_column_cont.find(".vcm-los-new[data-los='"+occupancy+"_"+nights+"']").remove();
					}
					los_column_cont.append("<span class=\"vcmrarratesp vcm-los-new\" data-los=\""+occupancy+"_"+nights+"\">"+los_label.replace("%d", nights)+" <span class=\"vcmrarcurrency\">"+base_currency+"</span> <input type=\"text\" placeholder=\"0.00\" value=\""+cost+"\" name=\"rateplan_"+losrateflag+"_"+occupancy+"_"+(nights - 1)+"\" size=\"5\"></span>");
					last_i = (nights - 1);
					to_nights = parseInt(nights) > to_nights ? parseInt(nights) : to_nights;
				});
				los_column_cont.parent().show();
				last_occ = parseInt(occupancy) > last_occ ? parseInt(occupancy) : last_occ;
			});
			var cur_top_numnights = jQuery("#addrateplans_"+losrateflag).val();
			var cur_top_occupancy = jQuery("#addrateplansocc_"+losrateflag).val();
			if(cur_top_numnights.length) {
				if(parseInt(cur_top_numnights) > to_nights) {
					jQuery("#addrateplans_"+losrateflag).val(cur_top_numnights);
				}else {
					jQuery("#addrateplans_"+losrateflag).val(to_nights);
				}
			}else {
				jQuery("#addrateplans_"+losrateflag).val(to_nights);
			}
			if(cur_top_occupancy.length) {
				if(parseInt(cur_top_occupancy) > last_occ) {
					jQuery("#addrateplansocc_"+losrateflag).val(cur_top_occupancy);
				}else {
					jQuery("#addrateplansocc_"+losrateflag).val(last_occ);
				}
			}else {
				jQuery("#addrateplansocc_"+losrateflag).val(last_occ);
			}
			if(last_i.length || last_i >= 0) {
				jQuery("input[name='rateplan_"+losrateflag+"_"+last_occ+"_"+last_i+"']").trigger("change");
			}
			vcmCloseModal();
		}else {
			alert("Rate Plan not found"); 
		}
	}else {
		alert('Missing Data. Please reload the page');
	}
});
<?php
if($inventory_loaded === false) {
	?>
vcmAlertModal('warning', '<?php echo addslashes(JText::_('VCMWARNINGTEXT')); ?>', '<p><?php echo addslashes(JText::_('VCMPARNOINVLOADEDRESP')); ?></p><p><?php echo addslashes(JText::_('VCMPARNOINVPUSHSUGGEST')); ?></p>');
	<?php
}
?>
</script>
<?php
//Debug:
//echo '<br clear="all"/><br/><pre>'.print_r($rars, true).'</pre>';
if(isset($_REQUEST['e4j_debug']) && intval($_REQUEST['e4j_debug']) == 1) {
	echo '<br clear="all"/><br/><pre>'.print_r($rars, true).'</pre>';
	echo '<br clear="all"/><br/><pre>'.print_r($rar_updates, true).'</pre>';
}