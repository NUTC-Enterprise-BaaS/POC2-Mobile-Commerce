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
$currencysymb = $this->currencysymb;
$rars = $this->rars;
$currency = '';

$inventory_loaded = array_key_exists('NoInventory', $rars) ? false : true;
if($inventory_loaded === false) {
	$currency = VikChannelManager::getCurrencyName(true);
}

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
foreach ($rars['AvailRate'] as $day => $rooms) {
	$day_rooms = count($rooms);
	$max_rooms_found = $day_rooms > $max_rooms_found ? $day_rooms : $max_rooms_found;
	$ota_rate_plan = !empty($ota_rooms[$rooms[0]['id']][key($ota_rooms[$rooms[0]['id']])]['otapricing']) ? json_decode($ota_rooms[$rooms[0]['id']][key($ota_rooms[$rooms[0]['id']])]['otapricing'], true) : array();
	//Parent and Derived RatePlans for both ExpediaCollect and HotelCollect Distribution Models
	$parent_rate_plans = array();
	$derived_rate_plans = array();
	$derived_rate_parents = array();
	if(array_key_exists('RatePlan', $ota_rate_plan)) {
		foreach ($ota_rate_plan['RatePlan'] as $rpkey => $rpval) {
			if(stripos($rpval['distributionModel'], 'expediacollect') !== false && (stripos($rpval['rateAcquisitionType'], 'derived') !== false || stripos($rpval['rateAcquisitionType'], 'netrate') !== false)) {
				if(count($parent_rate_plans) > 0) {
					foreach ($parent_rate_plans as $parent_rate_plan) {
						if(strpos((string)$parent_rate_plan, (string)$rpkey) !== false) {
							$derived_rate_plans[$parent_rate_plan][] = (string)$rpkey;
							$derived_rate_parents[] = (string)$rpkey;
							break;
						}
					}
				}
			}else {
				$parent_rate_plans[] = (string)$rpkey;
			}
		}
	}
	//
	?>
	<tr class="row<?php echo $k; ?>">
		<td rowspan="<?php echo $day_rooms; ?>"><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $day; ?>" class="vcm-rar-ckb" onClick="<?php echo $vik->checkboxOnClick(); ?>"><span id="date<?php echo $day; ?>"></span></td>
		<td rowspan="<?php echo $day_rooms; ?>"><div class="vcmrardate-box"><span class="vcmrardate"><?php echo $day; ?></span></div></td>
		<td><div class="vcmrar-room-box"><span class="vcmshowtip vcmistip" title="ID <?php echo $rooms[0]['id']; ?>"><?php echo $ota_rooms[$rooms[0]['id']][0]['otaroomname']; ?></span></div></td>
		<td class="center" align="center"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikchannelmanager/assets/css/images/<?php echo $rooms[0]['closed'] == 'true' ? 'disabled' : 'enabled'; ?>.png" class="imgtoggle" onclick="toggleRoomStatus(<?php echo $rooms[0]['closed'] == 'true' ? '0' : '1'; ?>, '<?php echo $day; ?>', '<?php echo $rooms[0]['id'] ?>');"/><div class="vcmrar-newroomstatus" id="divroomstatus<?php echo $day.$rooms[0]['id']; ?>"><input type="hidden" name="<?php echo 'roomstatus_'.$day.'_'.$rooms[0]['id']; ?>" value="" id="roomstatus<?php echo $day.$rooms[0]['id']; ?>"/></div></td>
		<td class="center" align="center">
			<span class="vcmrarinventorysp"><span class="vcmshowtip" title="- <?php echo JText::sprintf('VCMRARINVSOLD', $rooms[0]['Inventory']['totalInventorySold']); ?>&lt;br/&gt;- <?php echo JText::sprintf('VCMRARINVBASEALLOC', $rooms[0]['Inventory']['baseAllocation']); ?>&lt;br/&gt;- <?php echo JText::sprintf('VCMRARINVFLEXALLOC', $rooms[0]['Inventory']['flexibleAllocation']); ?>"><?php echo JText::_('VCMTOTINVAVAILABLE'); ?></span> <input type="number" min="0" name="<?php echo 'inv_'.$day.'_'.$rooms[0]['id']; ?>" value="<?php echo $rooms[0]['Inventory']['totalInventoryAvailable']; ?>" size="3"/></span>
			<div class="vcmrarinvddown">
				<select name="<?php echo 'invtype_'.$day.'_'.$rooms[0]['id']; ?>">
				<option value="totalInventoryAvailable"><?php echo JText::_('VCMRARINVLABTOTINVAV'); ?></option>
				<option value="flexibleAllocation"<?php echo array_key_exists('baseAllocation', $rooms[0]['Inventory']) ? ' selected="selected"' : ''; ?>><?php echo JText::_('VCMRARINVLABFLEXALLOC'); ?></option>
				</select>
			</div>
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
	?>
		</td>
		<?php
		$rate_plans = '';
		$restrictions = array();
		$tot_rate_plans = count($rooms[0]['RatePlan']);
		$max_rateplans_found = $tot_rate_plans > $max_rateplans_found ? $tot_rate_plans : $max_rateplans_found;
		if($tot_rate_plans > 0) {
			foreach($rooms[0]['RatePlan'] as $krrp => $rateplan) {
				$parent_derived_class = '';
				if(in_array($rateplan['id'], $parent_rate_plans) && $tot_rate_plans > 1) {
					if(array_key_exists($rateplan['id'], $derived_rate_plans)) {
						$parent_derived_class = ' vcmrar-rateplan-parent';
					}
				}elseif(in_array($rateplan['id'], $derived_rate_parents) && $tot_rate_plans > 1) {
					$parent_derived_class = ' vcmrar-rateplan-derived';
				}
				$rate_plans .= '<div class="vcmrar-rateplan'.$parent_derived_class.'" data-rateflag="'.$rooms[0]['id'].'_'.$day.'_'.$rateplan['id'].'">'."\n";
				$rate_plan_tip = '';
				if(array_key_exists($rateplan['id'], $ota_rate_plan['RatePlan'])) {
					foreach ($ota_rate_plan['RatePlan'][$rateplan['id']] as $rpkey => $rpval) {
						$rate_plan_tip .= ucwords($rpkey).': '.$rpval."&lt;br/&gt;";
					}
					//No inventories loaded for these dates and the pricingModel is different than the default PerDayPricing
					if($inventory_loaded === false && !empty($ota_rate_plan['RatePlan'][$rateplan['id']]['pricingModel']) && trim($ota_rate_plan['RatePlan'][$rateplan['id']]['pricingModel']) != 'PerDayPricing') {
						if (array_key_exists('Rate', $rateplan) && count($rateplan['Rate']) > 0) {
							//Change the default pricingModel returned and update the session value
							$session = JFactory::getSession();
							$pricingmodel = trim($ota_rate_plan['RatePlan'][$rateplan['id']]['pricingModel']);
							if($pricingmodel == 'PerDayPricingByLengthOfStay') {
								//PerDay and PerDay LOS have the same array key so always replace the session value
								$new_rate = array('currency' => $currency);
								$new_rate['PerDay'] = array(
									array('currency' => $currency, 'lengthOfStay' => 1, 'rate' => 1.00),
									array('currency' => $currency, 'lengthOfStay' => 2, 'rate' => 2.00)
								);
								$rateplan['Rate'] = $new_rate;
								$rars['AvailRate'][$day][0]['RatePlan'][$krrp]['Rate'] = $new_rate;
								$sess_rar = $session->get('vcmExecRarRs', '');
								$sess_rar['rars'] = $rars;
								$session->set('vcmExecRarRs', $sess_rar);
							}elseif($pricingmodel == 'OccupancyBasedPricing') {
								if (!array_key_exists('PerOccupancy', $rateplan['Rate'])) {
									//Simulator returns PerOccupancy pricing with hotel id 411
									$new_rate = array('currency' => $currency);
									$new_rate['PerOccupancy'] = array(
										array('rate' => 1.00, 'occupancy' => 1),
										array('rate' => 2.00, 'occupancy' => 2),
										array('rate' => '', 'occupancy' => 3),
										array('rate' => '', 'occupancy' => 4)
									);
									$rateplan['Rate'] = $new_rate;
									$rars['AvailRate'][$day][0]['RatePlan'][$krrp]['Rate'] = $new_rate;
									$sess_rar = $session->get('vcmExecRarRs', '');
									$sess_rar['rars'] = $rars;
									$session->set('vcmExecRarRs', $sess_rar);
								}
							}
							//PerPersonPricing has no sample-rates on the Simulator so for the moment the supported Pricing Models are: PerDay, PerDay+LOS, PerOccupancy
						}
					}
					//end No inventories loaded for these dates and the pricingModel is different than the default PerDayPricing
				}
				//Distribution Model and Rate Acquisition Type
				$rateplan_distrmodel = $rateplan['id'];
				if(@is_array($ota_rate_plan['RatePlan'][$rateplan['id']]) && (array_key_exists('distributionModel', $ota_rate_plan['RatePlan'][$rateplan['id']]) && array_key_exists('rateAcquisitionType', $ota_rate_plan['RatePlan'][$rateplan['id']]))) {
					$rateplan_distrmodel = $ota_rate_plan['RatePlan'][$rateplan['id']]['distributionModel'].' - '.$ota_rate_plan['RatePlan'][$rateplan['id']]['rateAcquisitionType'];
				}
				//
				$rate_plans .= '<span class="'.(!empty($rate_plan_tip) ? 'vcmshowtip ' : '').'vcmrateplansp '.($rateplan['closed'] == 'true' ? 'vcmrateplanoff' : 'vcmrateplanon').'" id="rateplanstatus'.$day.$rateplan['id'].'" title="'.$rate_plan_tip.'" onclick="toggleRatePlanStatus('.($rateplan['closed'] == 'true' ? '0' : '1').', \''.$day.'\', \''.$rateplan['id'].'\');">'.JText::sprintf('VCMRARRATEPLANTITLE', (array_key_exists($rateplan['id'], $ota_rate_plan['RatePlan']) ? $ota_rate_plan['RatePlan'][$rateplan['id']]['name'].' ' : ''), $rateplan_distrmodel).'</span><span class="vcmrar-spacer"></span><input type="hidden" name="rateplanstatus'.$day.$rateplan['id'].'" value="" id="inprateplanstatus'.$day.$rateplan['id'].'"/>'."\n";
				if (array_key_exists('Restrictions', $rateplan) && count($rateplan['Restrictions']) > 0) {
					$restrictions[$rateplan['id']] = $rateplan['Restrictions'];
					if(array_key_exists('@attributes', $rateplan['Restrictions']) && count($rateplan['Restrictions']) == 1) {
						$restrictions[$rateplan['id']] = $rateplan['Restrictions']['@attributes'];
					}
				}
				if (array_key_exists('Rate', $rateplan) && count($rateplan['Rate']) > 0) {
					$rate_type = '';
					$rate_plans .= '<div class="vcmrar-rplan-leftblock">'."\n";
					$currency = $inventory_loaded === true ? $rateplan['Rate']['currency'] : $currency;
					if (array_key_exists('PerDay', $rateplan['Rate'])) {
						$rate_type = 'PerDay';
						$rate_plans .= '<input type="hidden" name="rateplantype_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'" value="PerDay"/>'."\n";
						$is_los = false;
						if(count($rateplan['Rate']['PerDay']) > 1) {
							foreach ($rateplan['Rate']['PerDay'] as $kr => $rate) {
								$is_los = $is_los || (array_key_exists('lengthOfStay', $rate) && (int)$rate['lengthOfStay'] > 0) ? ((int)$rate['lengthOfStay'] + 1) : false;
								$rate_plans .= '<span class="vcmrarratesp" data-los="'.($is_los - 1).'">'.(array_key_exists('lengthOfStay', $rate) ? JText::sprintf('VCMRARRATEPERDAYLOS', $rate['lengthOfStay']) : JText::_('VCMRARRATEPERDAY')).' <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'_'.$kr.'" value="'.$rate['rate'].'" placeholder="0.00"/></span>'."\n";
							}
						}else {
							if(array_key_exists(0, $rateplan['Rate']['PerDay']) && count($rateplan['Rate']['PerDay']) == 1) {
								$rateplan['Rate']['PerDay'] = $rateplan['Rate']['PerDay'][0];
							}
							$is_los = $is_los || (array_key_exists('lengthOfStay', $rateplan['Rate']['PerDay']) && (int)$rateplan['Rate']['PerDay']['lengthOfStay'] > 0) ? ((int)$rateplan['Rate']['PerDay']['lengthOfStay'] + 1) : false;
							$rate_plans .= '<span class="vcmrarratesp" data-los="'.($is_los - 1).'">'.(array_key_exists('lengthOfStay', $rateplan['Rate']['PerDay']) ? JText::sprintf('VCMRARRATEPERDAYLOS', $rateplan['Rate']['PerDay']['lengthOfStay']) : JText::_('VCMRARRATEPERDAY')).' <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'_0" value="'.$rateplan['Rate']['PerDay']['rate'].'" placeholder="0.00"/></span>'."\n";
						}
						if($is_los !== false) {
							$max_los = array_key_exists($rateplan['id'], $restrictions) && array_key_exists('maxLOS', $restrictions[$rateplan['id']]) && !empty($restrictions[$rateplan['id']]['maxLOS']) ? (int)$restrictions[$rateplan['id']]['maxLOS'] : 45;
							if($is_los < $max_los) {
								$rate_plans .= '<span class="vcmrar-addlos" data-rateflag="'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'" data-rateminlos="'.$is_los.'" data-ratemaxlos="'.$max_los.'" data-ratecurrency="'.$currency.'">'.JText::_('VCMRARADDLOS').'</span>';
								$rate_plans .= '<input type="hidden" name="addrateplans_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'" id="addrateplans_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'" value=""/>';
							}
						}
					}elseif (array_key_exists('PerOccupancy', $rateplan['Rate'])) {
						$rate_type = 'PerOccupancy';
						$rate_plans .= '<input type="hidden" name="rateplantype_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'" value="PerOccupancy"/>'."\n";
						if(count($rateplan['Rate']['PerOccupancy']) > 1) {
							foreach ($rateplan['Rate']['PerOccupancy'] as $kr => $rate) {
								$rate_plans .= '<span class="vcmrarratesp">'.JText::sprintf('VCMRARRATEPEROCCUPANCY', $rate['occupancy']).' <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'_'.$kr.'" value="'.$rate['rate'].'"/></span>'."\n";
							}
						}else {
							if(array_key_exists(0, $rateplan['Rate']['PerOccupancy']) && count($rateplan['Rate']['PerOccupancy']) == 1) {
								$rateplan['Rate']['PerOccupancy'] = $rateplan['Rate']['PerOccupancy'][0];
							}
							$rate_plans .= '<span class="vcmrarratesp">'.JText::sprintf('VCMRARRATEPEROCCUPANCY', $rateplan['Rate']['PerOccupancy']['occupancy']).' <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'_0" value="'.$rateplan['Rate']['PerOccupancy']['rate'].'"/></span>'."\n";
						}
					}elseif (array_key_exists('PerPerson', $rateplan['Rate'])) {
						$rate_type = 'PerPerson';
						$rate_plans .= '<input type="hidden" name="rateplantype_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'" value="PerPerson"/>'."\n";
						if(count($rateplan['Rate']['PerPerson']) > 1) {
							foreach ($rateplan['Rate']['PerPerson'] as $kr => $rate) {
								$rate_plans .= '<span class="vcmrarratesp">'.JText::_('VCMRARRATEPERPERSON').' <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'_'.$kr.'" value="'.$rate['rate'].'"/></span>'."\n";
							}
						}else {
							if(array_key_exists(0, $rateplan['Rate']['PerPerson']) && count($rateplan['Rate']['PerPerson']) == 1) {
								$rateplan['Rate']['PerPerson'] = $rateplan['Rate']['PerPerson'][0];
							}
							$rate_plans .= '<span class="vcmrarratesp">'.JText::_('VCMRARRATEPERPERSON').' <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'_0" value="'.$rateplan['Rate']['PerPerson']['rate'].'"/></span>'."\n";
						}
					}else {
						//Unknown Type of Rate not Available: use placeholder
						$rate_plans .= '<span class="vcmrarratesp">'.JText::_('VCMRARRATEUNKNOWN').' <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'" value="" placeholder="0.00"/><input type="hidden" name="rateplantype_'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'" value="PerDay"/></span>'."\n";
					}
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
						if(in_array($rate_type, array('PerDay', 'PerOccupancy', 'PerPerson'))) {
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
									foreach ($rateplan['Rate'][$rate_type] as $kr => $rate) {
										$n = is_array($rate) && array_key_exists('lengthOfStay', $rate) ? $rate['lengthOfStay'] : ($n + 1);
										//skip when $kr is 'currency' or 'rateChangeIndicator'
										if($kr == 'currency' || $kr == 'rateChangeIndicator') {
											if(!is_array($rate) || !array_key_exists('lengthOfStay', $rate)) {
												$n--;
											}
											continue;
										}
										$n = $rate_type == 'PerOccupancy' ? 1 : $n;
										$occ = is_array($rate) && array_key_exists('occupancy', $rate) ? intval($rate['occupancy']) : 0;
										foreach ($comparison[$day][$rooms[0]['id']] as $nights => $prices) {
											if (is_numeric($nights)) {
												foreach ($prices as $price) {
													if((int)$nights == (int)$n && $tpid == $price['idprice']) {
														$kr = is_numeric($kr) ? $kr : '0';
														if($rate_type == 'PerOccupancy' && !empty($occ)) {
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
														$rate_plans .= '<div class="vcm-compare-rates-roomcost"><span class="vcm-compare-rates-copycost" id="'.$day.'_'.$rooms[0]['id'].'_'.$rateplan['id'].'_'.$kr.'" title="'.JText::_('VCMRARCOPYPRICE').'"></span><span class="vcm-compare-pricefornights">'.JText::sprintf('VCMRARCOMPNUMNIGHTS', $price['days']).'</span><span class="vcm-compare-pricecurrency">'.$currencysymb.'</span><span class="vcm-compare-pricebox">'.number_format($price['cost'], 2, '.', '').'</span></div>'."\n";
													}
												}
											}
										}
									}
									if($n > 1 || ($rate_type == 'PerOccupancy' && count($rateplan['Rate'][$rate_type]) > 1)) {
										$rate_plans .= '<div class="vcm-compare-copyall">'.JText::_('VCMRARCOPYALLPRICES').'</div>'."\n";
									}
									$rate_plans .= '</div>'."\n";
								}
								$rate_plans .= '</div>'."\n";
								$rate_plans .= '</div>'."\n";
							}
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
				?>
				<div class="vcmrarrestr-block restrflag-<?php echo $rooms[0]['id'].'_'.$day.'_'.$rpid; ?>">
					<span class="vcmrarrestr-minlos"><span class="vcmshowtip" title="<?php echo JText::sprintf('VCMRARRATEPLANID', $rpid); ?>"><?php echo JText::_('VCMRARRESTRMINLOS'); ?></span> <input type="number" min="0" name="<?php echo 'restrmin_'.$rooms[0]['id'].'_'.$day.'_'.$rpid; ?>" size="3" value="<?php echo $restriction['minLOS']; ?>"/></span>
					<span class="vcmrarrestr-maxlos"><span class="vcmshowtip" title="<?php echo JText::sprintf('VCMRARRATEPLANID', $rpid); ?>"><?php echo JText::_('VCMRARRESTRMAXLOS'); ?></span> <input type="number" min="0" name="<?php echo 'restrmax_'.$rooms[0]['id'].'_'.$day.'_'.$rpid; ?>" size="3" value="<?php echo $restriction['maxLOS']; ?>"/></span>
					<div class="vcmrarrestr-arrivdep">
						<span class="vcmrarrestr-tag <?php echo $restriction['closedToArrival'] == 'true' ? 'vcmtagenabled' : 'vcmtagdisabled'; ?>" onclick="toggleRestrArrivalStatus(<?php echo $restriction['closedToArrival'] == 'true' ? '1' : '0'; ?>, '<?php echo $day; ?>', '<?php echo $rpid; ?>');" id="restrplanarrival<?php echo $day.$rpid; ?>"><?php echo JText::_('VCMRARRESTRCLOSEDARRIVAL'); ?></span><input type="hidden" name="restrplanarrival<?php echo $day.$rpid; ?>" value="" id="inprestrplanarrival<?php echo $day.$rpid; ?>"/>
						<span class="vcmrarrestr-tag <?php echo $restriction['closedToDeparture'] == 'true' ? 'vcmtagenabled' : 'vcmtagdisabled'; ?>" onclick="toggleRestrDepartureStatus(<?php echo $restriction['closedToDeparture'] == 'true' ? '1' : '0'; ?>, '<?php echo $day; ?>', '<?php echo $rpid; ?>');" id="restrplandeparture<?php echo $day.$rpid; ?>"><?php echo JText::_('VCMRARRESTRCLOSEDDEPARTURE'); ?></span><input type="hidden" name="restrplandeparture<?php echo $day.$rpid; ?>" value="" id="inprestrplandeparture<?php echo $day.$rpid; ?>"/>
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
			//Parent and Derived RatePlans for both ExpediaCollect and HotelCollect Distribution Models
			$parent_rate_plans = array();
			$derived_rate_plans = array();
			$derived_rate_parents = array();
			if(array_key_exists('RatePlan', $ota_rate_plan)) {
				foreach ($ota_rate_plan['RatePlan'] as $rpkey => $rpval) {
					if(stripos($rpval['distributionModel'], 'expediacollect') !== false && (stripos($rpval['rateAcquisitionType'], 'derived') !== false || stripos($rpval['rateAcquisitionType'], 'netrate') !== false)) {
						if(count($parent_rate_plans) > 0) {
							foreach ($parent_rate_plans as $parent_rate_plan) {
								if(strpos((string)$parent_rate_plan, (string)$rpkey) !== false) {
									$derived_rate_plans[$parent_rate_plan][] = (string)$rpkey;
									$derived_rate_parents[] = (string)$rpkey;
									break;
								}
							}
						}
					}else {
						$parent_rate_plans[] = (string)$rpkey;
					}
				}
			}
			//
	?>
	<tr class="row<?php echo $k; ?>">
		<td><div class="vcmrar-room-box"><span class="vcmshowtip vcmistip" title="ID <?php echo $rooms[$j]['id']; ?>"><?php echo $ota_rooms[$rooms[$j]['id']][0]['otaroomname']; ?></span></div></td>
		<td class="center" align="center"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikchannelmanager/assets/css/images/<?php echo $rooms[$j]['closed'] == 'true' ? 'disabled' : 'enabled'; ?>.png" class="imgtoggle" onclick="toggleRoomStatus(<?php echo $rooms[$j]['closed'] == 'true' ? '0' : '1'; ?>, '<?php echo $day; ?>', '<?php echo $rooms[$j]['id'] ?>');"/><div class="vcmrar-newroomstatus" id="divroomstatus<?php echo $day.$rooms[$j]['id']; ?>"><input type="hidden" name="<?php echo 'roomstatus_'.$day.'_'.$rooms[$j]['id']; ?>" value="" id="roomstatus<?php echo $day.$rooms[$j]['id']; ?>"/></div></td>
		<td class="center" align="center">
			<span class="vcmrarinventorysp"><span class="vcmshowtip" title="- <?php echo JText::sprintf('VCMRARINVSOLD', $rooms[$j]['Inventory']['totalInventorySold']); ?>&lt;br/&gt;- <?php echo JText::sprintf('VCMRARINVBASEALLOC', $rooms[$j]['Inventory']['baseAllocation']); ?>&lt;br/&gt;- <?php echo JText::sprintf('VCMRARINVFLEXALLOC', $rooms[$j]['Inventory']['flexibleAllocation']); ?>"><?php echo JText::_('VCMTOTINVAVAILABLE'); ?></span> <input type="number" min="0" name="<?php echo 'inv_'.$day.'_'.$rooms[$j]['id']; ?>" value="<?php echo $rooms[$j]['Inventory']['totalInventoryAvailable']; ?>" size="3"/></span>
			<div class="vcmrarinvddown">
				<select name="<?php echo 'invtype_'.$day.'_'.$rooms[$j]['id']; ?>">
				<option value="totalInventoryAvailable"><?php echo JText::_('VCMRARINVLABTOTINVAV'); ?></option>
				<option value="flexibleAllocation"<?php echo array_key_exists('baseAllocation', $rooms[$j]['Inventory']) ? ' selected="selected"' : ''; ?>><?php echo JText::_('VCMRARINVLABFLEXALLOC'); ?></option>
				</select>
			</div>
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
			?>
		</td>
		<?php
		$rate_plans = '';
		$restrictions = array();
		$tot_rate_plans = count($rooms[$j]['RatePlan']);
		if($tot_rate_plans > 0) {
			foreach($rooms[$j]['RatePlan'] as $rateplan) {
				$parent_derived_class = '';
				if(in_array($rateplan['id'], $parent_rate_plans) && $tot_rate_plans > 1) {
					if(array_key_exists($rateplan['id'], $derived_rate_plans)) {
						$parent_derived_class = ' vcmrar-rateplan-parent';
					}
				}elseif(in_array($rateplan['id'], $derived_rate_parents) && $tot_rate_plans > 1) {
					$parent_derived_class = ' vcmrar-rateplan-derived';
				}
				$rate_plans .= '<div class="vcmrar-rateplan'.$parent_derived_class.'" data-rateflag="'.$rooms[$j]['id'].'_'.$day.'_'.$rateplan['id'].'">'."\n";
				$rate_plan_tip = '';
				if(array_key_exists($rateplan['id'], $ota_rate_plan['RatePlan'])) {
					foreach ($ota_rate_plan['RatePlan'][$rateplan['id']] as $rpkey => $rpval) {
						$rate_plan_tip .= ucwords($rpkey).': '.$rpval."&lt;br/&gt;";
					}
				}
				//Distribution Model and Rate Acquisition Type
				$rateplan_distrmodel = $rateplan['id'];
				if(@is_array($ota_rate_plan['RatePlan'][$rateplan['id']]) && (array_key_exists('distributionModel', $ota_rate_plan['RatePlan'][$rateplan['id']]) && array_key_exists('rateAcquisitionType', $ota_rate_plan['RatePlan'][$rateplan['id']]))) {
					$rateplan_distrmodel = $ota_rate_plan['RatePlan'][$rateplan['id']]['distributionModel'].' - '.$ota_rate_plan['RatePlan'][$rateplan['id']]['rateAcquisitionType'];
				}
				//
				$rate_plans .= '<span class="'.(!empty($rate_plan_tip) ? 'vcmshowtip ' : '').'vcmrateplansp '.($rateplan['closed'] == 'true' ? 'vcmrateplanoff' : 'vcmrateplanon').'" id="rateplanstatus'.$day.$rateplan['id'].'" title="'.$rate_plan_tip.'" onclick="toggleRatePlanStatus('.($rateplan['closed'] == 'true' ? '0' : '1').', \''.$day.'\', \''.$rateplan['id'].'\');">'.JText::sprintf('VCMRARRATEPLANTITLE', (array_key_exists($rateplan['id'], $ota_rate_plan['RatePlan']) ? $ota_rate_plan['RatePlan'][$rateplan['id']]['name'].' ' : ''), $rateplan_distrmodel).'</span><span class="vcmrar-spacer"></span><input type="hidden" name="rateplanstatus'.$day.$rateplan['id'].'" value="" id="inprateplanstatus'.$day.$rateplan['id'].'"/>'."\n";
				if (array_key_exists('Restrictions', $rateplan) && count($rateplan['Restrictions']) > 0) {
					$restrictions[$rateplan['id']] = $rateplan['Restrictions'];
					if(array_key_exists('@attributes', $rateplan['Restrictions']) && count($rateplan['Restrictions']) == 1) {
						$restrictions[$rateplan['id']] = $rateplan['Restrictions']['@attributes'];
					}
				}
				if (array_key_exists('Rate', $rateplan) && count($rateplan['Rate']) > 0) {
					$rate_type = '';
					$rate_plans .= '<div class="vcmrar-rplan-leftblock">'."\n";
					$currency = $inventory_loaded === true ? $rateplan['Rate']['currency'] : $currency;
					if (array_key_exists('PerDay', $rateplan['Rate'])) {
						$rate_type = 'PerDay';
						$rate_plans .= '<input type="hidden" name="rateplantype_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'" value="PerDay"/>'."\n";
						$is_los = false;
						if(count($rateplan['Rate']['PerDay']) > 1) {
							foreach ($rateplan['Rate']['PerDay'] as $kr => $rate) {
								$is_los = $is_los || (array_key_exists('lengthOfStay', $rate) && (int)$rate['lengthOfStay'] > 0) ? ((int)$rate['lengthOfStay'] + 1) : false;
								$rate_plans .= '<span class="vcmrarratesp" data-los="'.($is_los - 1).'">'.(array_key_exists('lengthOfStay', $rate) ? JText::sprintf('VCMRARRATEPERDAYLOS', $rate['lengthOfStay']) : JText::_('VCMRARRATEPERDAY')).' <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'_'.$kr.'" value="'.$rate['rate'].'" placeholder="0.00"/><input type="hidden" name="rateplantype_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'_'.$kr.'" value="PerDay"/></span>'."\n";
							}
						}else {
							if(array_key_exists(0, $rateplan['Rate']['PerDay']) && count($rateplan['Rate']['PerDay']) == 1) {
								$rateplan['Rate']['PerDay'] = $rateplan['Rate']['PerDay'][0];
							}
							$is_los = $is_los || (array_key_exists('lengthOfStay', $rateplan['Rate']['PerDay']) && (int)$rateplan['Rate']['PerDay']['lengthOfStay'] > 0) ? ((int)$rateplan['Rate']['PerDay']['lengthOfStay'] + 1) : false;
							$rate_plans .= '<span class="vcmrarratesp" data-los="'.($is_los - 1).'">'.(array_key_exists('lengthOfStay', $rateplan['Rate']['PerDay']) ? JText::sprintf('VCMRARRATEPERDAYLOS', $rateplan['Rate']['PerDay']['lengthOfStay']) : JText::_('VCMRARRATEPERDAY')).' <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'_0" value="'.$rateplan['Rate']['PerDay']['rate'].'" placeholder="0.00"/><input type="hidden" name="rateplantype_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'" value="PerDay"/></span>'."\n";
						}
						if($is_los !== false) {
							$max_los = array_key_exists($rateplan['id'], $restrictions) && array_key_exists('maxLOS', $restrictions[$rateplan['id']]) && !empty($restrictions[$rateplan['id']]['maxLOS']) ? (int)$restrictions[$rateplan['id']]['maxLOS'] : 45;
							if($is_los < $max_los) {
								$rate_plans .= '<span class="vcmrar-addlos" data-rateflag="'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'" data-rateminlos="'.$is_los.'" data-ratemaxlos="'.$max_los.'" data-ratecurrency="'.$currency.'">'.JText::_('VCMRARADDLOS').'</span>';
								$rate_plans .= '<input type="hidden" name="addrateplans_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'" id="addrateplans_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'" value=""/>';
							}
						}
					}elseif (array_key_exists('PerOccupancy', $rateplan['Rate'])) {
						$rate_type = 'PerOccupancy';
						$rate_plans .= '<input type="hidden" name="rateplantype_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'" value="PerOccupancy"/>'."\n";
						if(count($rateplan['Rate']['PerOccupancy']) > 1) {
							foreach ($rateplan['Rate']['PerOccupancy'] as $kr => $rate) {
								$rate_plans .= '<span class="vcmrarratesp">'.JText::sprintf('VCMRARRATEPEROCCUPANCY', $rate['occupancy']).' <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'_'.$kr.'" value="'.$rate['rate'].'"/><input type="hidden" name="rateplantype_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'_'.$kr.'" value="PerOccupancy"/></span>'."\n";
							}
						}else {
							if(array_key_exists(0, $rateplan['Rate']['PerOccupancy']) && count($rateplan['Rate']['PerOccupancy']) == 1) {
								$rateplan['Rate']['PerOccupancy'] = $rateplan['Rate']['PerOccupancy'][0];
							}
							$rate_plans .= '<span class="vcmrarratesp">'.JText::sprintf('VCMRARRATEPEROCCUPANCY', $rateplan['Rate']['PerOccupancy']['occupancy']).' <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'_0" value="'.$rateplan['Rate']['PerOccupancy']['rate'].'"/><input type="hidden" name="rateplantype_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'" value="PerOccupancy"/></span>'."\n";
						}
					}elseif (array_key_exists('PerPerson', $rateplan['Rate'])) {
						$rate_type = 'PerPerson';
						$rate_plans .= '<input type="hidden" name="rateplantype_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'" value="PerPerson"/>'."\n";
						if(count($rateplan['Rate']['PerPerson']) > 1) {
							foreach ($rateplan['Rate']['PerPerson'] as $kr => $rate) {
								$rate_plans .= '<span class="vcmrarratesp">'.JText::_('VCMRARRATEPERPERSON').' <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'_'.$kr.'" value="'.$rate['rate'].'"/><input type="hidden" name="rateplantype_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'_'.$kr.'" value="PerPerson"/></span>'."\n";
							}
						}else {
							if(array_key_exists(0, $rateplan['Rate']['PerPerson']) && count($rateplan['Rate']['PerPerson']) == 1) {
								$rateplan['Rate']['PerPerson'] = $rateplan['Rate']['PerPerson'][0];
							}
							$rate_plans .= '<span class="vcmrarratesp">'.JText::_('VCMRARRATEPERPERSON').' <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'_0" value="'.$rateplan['Rate']['PerPerson']['rate'].'"/><input type="hidden" name="rateplantype_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'" value="PerPerson"/></span>'."\n";
						}
					}else {
						//Unknown Type of Rate not Available: use placeholder
						$rate_plans .= '<span class="vcmrarratesp">'.JText::_('VCMRARRATEUNKNOWN').' <span class="vcmrarcurrency">'.$currency.'</span> <input type="text" size="5" name="rateplan_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'" value="" placeholder="0.00"/><input type="hidden" name="rateplantype_'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'" value="PerDay"/></span>'."\n";
					}
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
						if(in_array($rate_type, array('PerDay', 'PerOccupancy', 'PerPerson'))) {
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
									foreach ($rateplan['Rate'][$rate_type] as $kr => $rate) {
										$n = is_array($rate) && array_key_exists('lengthOfStay', $rate) ? $rate['lengthOfStay'] : ($n + 1);
										//skip when $kr is 'currency' or 'rateChangeIndicator'
										if($kr == 'currency' || $kr == 'rateChangeIndicator') {
											if(!is_array($rate) || !array_key_exists('lengthOfStay', $rate)) {
												$n--;
											}
											continue;
										}
										$n = $rate_type == 'PerOccupancy' ? 1 : $n;
										$occ = is_array($rate) && array_key_exists('occupancy', $rate) ? intval($rate['occupancy']) : 0;
										foreach ($comparison[$day][$rooms[$j]['id']] as $nights => $prices) {
											if (is_numeric($nights)) {
												foreach ($prices as $price) {
													if((int)$nights == (int)$n && $tpid == $price['idprice']) {
														$kr = is_numeric($kr) ? $kr : '0';
														if($rate_type == 'PerOccupancy' && !empty($occ)) {
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
														$rate_plans .= '<div class="vcm-compare-rates-roomcost"><span class="vcm-compare-rates-copycost" id="'.$day.'_'.$rooms[$j]['id'].'_'.$rateplan['id'].'_'.$kr.'" title="'.JText::_('VCMRARCOPYPRICE').'"></span><span class="vcm-compare-pricefornights">'.JText::sprintf('VCMRARCOMPNUMNIGHTS', $price['days']).'</span><span class="vcm-compare-pricecurrency">'.$currencysymb.'</span><span class="vcm-compare-pricebox">'.number_format($price['cost'], 2, '.', '').'</span></div>'."\n";
													}
												}
											}
										}
									}
									if($n > 1 || ($rate_type == 'PerOccupancy' && count($rateplan['Rate'][$rate_type]) > 1)) {
										$rate_plans .= '<div class="vcm-compare-copyall">'.JText::_('VCMRARCOPYALLPRICES').'</div>'."\n";
									}
									$rate_plans .= '</div>'."\n";
								}
								$rate_plans .= '</div>'."\n";
								$rate_plans .= '</div>'."\n";
							}
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
				?>
				<div class="vcmrarrestr-block restrflag-<?php echo $rooms[$j]['id'].'_'.$day.'_'.$rpid; ?>">
					<span class="vcmrarrestr-minlos"><span class="vcmshowtip" title="<?php echo JText::sprintf('VCMRARRATEPLANID', $rpid); ?>"><?php echo JText::_('VCMRARRESTRMINLOS'); ?></span> <input type="number" min="0" name="<?php echo 'restrmin_'.$rooms[$j]['id'].'_'.$day.'_'.$rpid; ?>" size="3" value="<?php echo $restriction['minLOS']; ?>"/></span>
					<span class="vcmrarrestr-maxlos"><span class="vcmshowtip" title="<?php echo JText::sprintf('VCMRARRATEPLANID', $rpid); ?>"><?php echo JText::_('VCMRARRESTRMAXLOS'); ?></span> <input type="number" min="0" name="<?php echo 'restrmax_'.$rooms[$j]['id'].'_'.$day.'_'.$rpid; ?>" size="3" value="<?php echo $restriction['maxLOS']; ?>"/></span>
					<div class="vcmrarrestr-arrivdep">
						<span class="vcmrarrestr-tag <?php echo $restriction['closedToArrival'] == 'true' ? 'vcmtagenabled' : 'vcmtagdisabled'; ?>" onclick="toggleRestrArrivalStatus(<?php echo $restriction['closedToArrival'] == 'true' ? '1' : '0'; ?>, '<?php echo $day; ?>', '<?php echo $rpid; ?>');" id="restrplanarrival<?php echo $day.$rpid; ?>"><?php echo JText::_('VCMRARRESTRCLOSEDARRIVAL'); ?></span><input type="hidden" name="restrplanarrival<?php echo $day.$rpid; ?>" value="" id="inprestrplanarrival<?php echo $day.$rpid; ?>"/>
						<span class="vcmrarrestr-tag <?php echo $restriction['closedToDeparture'] == 'true' ? 'vcmtagenabled' : 'vcmtagdisabled'; ?>" onclick="toggleRestrDepartureStatus(<?php echo $restriction['closedToDeparture'] == 'true' ? '1' : '0'; ?>, '<?php echo $day; ?>', '<?php echo $rpid; ?>');" id="restrplandeparture<?php echo $day.$rpid; ?>"><?php echo JText::_('VCMRARRESTRCLOSEDDEPARTURE'); ?></span><input type="hidden" name="restrplandeparture<?php echo $day.$rpid; ?>" value="" id="inprestrplandeparture<?php echo $day.$rpid; ?>"/>
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

<script type="text/javascript">
jQuery(".vcmshowtip").tooltip({show:{delay: 500}});
vcm_rar_days = <?php echo json_encode(array_keys($rars['AvailRate'])); ?>;
var rplansheight = new Array();
jQuery(".vcmrar-rateplan").each(function(k){
	rplansheight.push(jQuery(this).height());
});
if(rplansheight.length > 0) {
	jQuery(".vcmrarrestr-block").each(function(k){
		jQuery(this).height(rplansheight[k]);
	});
}
jQuery(".vcm-copy-ratesinv").fadeIn();
<?php
if(count($comparison) > 0) {
	?>
jQuery(".vcm-ibe-compare").fadeIn();
	<?php
}
?>
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
function toggleRestrArrivalStatus(status, date, rpid) {
	var setclass = status == 1 ? "vcmrarrestr-tag-todisabled" : "vcmrarrestr-tag-toenabled";
	var cur_status = jQuery("#inprestrplanarrival"+date+rpid).val();
	var opposite_status = status == 1 ? 0 : 1;
	var setstatus = cur_status.length == 0 ? opposite_status : '';
	if(cur_status.length == 0) {
		jQuery("#inprestrplanarrival"+date+rpid).val(setstatus);
		jQuery("#restrplanarrival"+date+rpid).removeClass("vcmrarrestr-tag-toenabled").removeClass("vcmrarrestr-tag-todisabled").addClass(setclass);
	}else {
		jQuery("#inprestrplanarrival"+date+rpid).val("");
		jQuery("#restrplanarrival"+date+rpid).removeClass("vcmrarrestr-tag-toenabled").removeClass("vcmrarrestr-tag-todisabled");
	}
	jQuery("#inprestrplanarrival"+date+rpid).trigger("change");
}
function toggleRestrDepartureStatus(status, date, rpid) {
	var setclass = status == 1 ? "vcmrarrestr-tag-todisabled" : "vcmrarrestr-tag-toenabled";
	var cur_status = jQuery("#inprestrplandeparture"+date+rpid).val();
	var opposite_status = status == 1 ? 0 : 1;
	var setstatus = cur_status.length == 0 ? opposite_status : '';
	if(cur_status.length == 0) {
		jQuery("#inprestrplandeparture"+date+rpid).val(setstatus);
		jQuery("#restrplandeparture"+date+rpid).removeClass("vcmrarrestr-tag-toenabled").removeClass("vcmrarrestr-tag-todisabled").addClass(setclass);
	}else {
		jQuery("#inprestrplandeparture"+date+rpid).val("");
		jQuery("#restrplandeparture"+date+rpid).removeClass("vcmrarrestr-tag-toenabled").removeClass("vcmrarrestr-tag-todisabled");
	}
	jQuery("#inprestrplandeparture"+date+rpid).trigger("change");
}
function toggleRatePlanStatus(status, date, rpid) {
	var setclass = status == 1 ? "vcmrateplansp-todisabled" : "vcmrateplansp-toenabled";
	var cur_status = jQuery("#inprateplanstatus"+date+rpid).val();
	var opposite_status = status == 1 ? 0 : 1;
	var setstatus = cur_status.length == 0 ? opposite_status : '';
	if(cur_status.length == 0) {
		jQuery("#inprateplanstatus"+date+rpid).val(setstatus);
		jQuery("#rateplanstatus"+date+rpid).removeClass("vcmrateplansp-toenabled").removeClass("vcmrateplansp-todisabled").addClass(setclass);
	}else {
		jQuery("#inprateplanstatus"+date+rpid).val("");
		jQuery("#rateplanstatus"+date+rpid).removeClass("vcmrateplansp-toenabled").removeClass("vcmrateplansp-todisabled");
	}
	jQuery("#inprateplanstatus"+date+rpid).trigger("change");
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
if(tot_rooms_found > 1 || tot_rateplans_found > 1) {
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

/* Prevent to send both parent and derived Rate Plans */
jQuery(".vcmrar-rateplan input").change(function() {
	var rate_type = jQuery(this).closest("div.vcmrar-rateplan").hasClass("vcmrar-rateplan-parent") ? 'vcmrar-rateplan-parent' : false;
	rate_type = jQuery(this).closest("div.vcmrar-rateplan").hasClass("vcmrar-rateplan-derived") ? 'vcmrar-rateplan-derived' : rate_type;
	if(rate_type === false) {
		return true;
	}
	if(rate_type == 'vcmrar-rateplan-parent') {
		if(jQuery(this).closest("div.vcmrar-rateplan").next(".vcmrar-rateplan-derived").find("input:text").length > 0) {
			jQuery(this).closest("div.vcmrar-rateplan").next(".vcmrar-rateplan-derived").find("input:text").each(function() {
				var rate_current_cost = jQuery(this).val();
				if(rate_current_cost.length > 0) {
					jQuery(this).attr("placeholder", rate_current_cost);
					jQuery(this).val("");
				}
			});
			var rate_flag = jQuery(this).closest("div.vcmrar-rateplan").next(".vcmrar-rateplan-derived").attr("data-rateflag");
			if(rate_flag.length > 0) {
				var restr_val = jQuery("input[name='restrmin_"+rate_flag+"']").val();
				if(restr_val.length > 0) {
					jQuery("input[name='restrmin_"+rate_flag+"']").attr("placeholder", restr_val);
					jQuery("input[name='restrmin_"+rate_flag+"']").val("");
				}
				restr_val = jQuery("input[name='restrmax_"+rate_flag+"']").val();
				if(restr_val.length > 0) {
					jQuery("input[name='restrmax_"+rate_flag+"']").attr("placeholder", restr_val);
					jQuery("input[name='restrmax_"+rate_flag+"']").val("");
				}
			}
		}
	}else {
		if(jQuery(this).closest("div.vcmrar-rateplan").prev(".vcmrar-rateplan-parent").find("input:text").length > 0) {
			jQuery(this).closest("div.vcmrar-rateplan").prev(".vcmrar-rateplan-parent").find("input:text").each(function() {
				var rate_current_cost = jQuery(this).val();
				if(rate_current_cost.length > 0) {
					jQuery(this).attr("placeholder", rate_current_cost);
					jQuery(this).val("");
				}
			});
			var rate_flag = jQuery(this).closest("div.vcmrar-rateplan").prev(".vcmrar-rateplan-parent").attr("data-rateflag");
			if(rate_flag.length > 0) {
				var restr_val = jQuery("input[name='restrmin_"+rate_flag+"']").val();
				if(restr_val.length > 0) {
					jQuery("input[name='restrmin_"+rate_flag+"']").attr("placeholder", restr_val);
					jQuery("input[name='restrmin_"+rate_flag+"']").val("");
				}
				restr_val = jQuery("input[name='restrmax_"+rate_flag+"']").val();
				if(restr_val.length > 0) {
					jQuery("input[name='restrmax_"+rate_flag+"']").attr("placeholder", restr_val);
					jQuery("input[name='restrmax_"+rate_flag+"']").val("");
				}
			}
		}
	}
});
/* Parent and Derived Rate Plans Control */
var tot_parent_rates = jQuery(".vcmrar-rateplan-parent").length;
var tot_derived_rates = jQuery(".vcmrar-rateplan-derived").length;
if(tot_parent_rates > 0 && tot_derived_rates > 0) {
	jQuery(".vcmrar-rateplan-parent").addClass("vcmrar-rateplan-parent-noderived");
	jQuery(".vcmrar-rateplan-derived").addClass("vcmrar-rateplan-derived-hidden").hide();
	jQuery(".vcmrar-rateplan-derived").each(function() {
		var restrflag = jQuery(this).attr("data-rateflag");
		if(restrflag.length > 0) {
			jQuery(".restrflag-"+restrflag).hide();
		}
	});
	var ch_controls = "<select id=\"vcm-channel-controls-rplantype\"><option value=\"parents\"><?php echo addslashes(JText::_('VCMRARPARENTSRATEPLANS')); ?></option><option value=\"any\"><?php echo addslashes(JText::_('VCMRARANYRATEPLANS')); ?></option><select>";
	jQuery("#vcm-channel-controls").html(ch_controls).fadeIn();
	jQuery("body").on("change", "#vcm-channel-controls-rplantype", function() {
		var show_rplan_type = jQuery(this).val();
		if(show_rplan_type == 'parents') {
			jQuery(".vcmrar-rateplan-parent").removeClass("vcmrar-rateplan-parent-noderived").addClass("vcmrar-rateplan-parent-noderived");
			jQuery(".vcmrar-rateplan-derived").removeClass("vcmrar-rateplan-derived-hidden").addClass("vcmrar-rateplan-derived-hidden").hide();
			jQuery(".vcmrar-rateplan-derived").each(function() {
				var restrflag = jQuery(this).attr("data-rateflag");
				if(restrflag.length > 0) {
					jQuery(".restrflag-"+restrflag).hide();
				}
			});
		}else {
			jQuery(".vcmrar-rateplan-parent").removeClass("vcmrar-rateplan-parent-noderived");
			jQuery(".vcmrar-rateplan-derived").removeClass("vcmrar-rateplan-derived-hidden").show();
			jQuery(".vcmrar-rateplan-derived").each(function() {
				var restrflag = jQuery(this).attr("data-rateflag");
				if(restrflag.length > 0) {
					jQuery(".restrflag-"+restrflag).show();
				}
			});
		}
	});
}else {
	jQuery("#vcm-channel-controls").html("");
}
/* Add Costs per night based on LOS */
jQuery("body").on("click", ".vcmrar-addlos", function() {
	var losrateflag = jQuery(this).attr("data-rateflag");
	var losrateflag_parts = losrateflag.split("_");
	var base_los = parseInt(jQuery(this).attr("data-rateminlos"));
	var base_maxlos = parseInt(jQuery(this).attr("data-ratemaxlos"));
	var base_currency = jQuery(this).attr("data-ratecurrency");
	var los_label = '<?php echo addslashes(JText::_('VCMRARRATEPERDAYLOS')); ?>';
	var addlos_cont_html = "<h3>"+losrateflag_parts[0]+" <?php echo addslashes(JText::_('VCMRARADDLOS')); ?></h3>";
	addlos_cont_html += "<div class=\"vcm-addlos-left\"><p><?php echo addslashes(JText::_('VCMRARADDLOSNUMNIGHTS')); ?></p><div><span class=\"vcm-addlos-from\"><?php echo addslashes(JText::_('VCMRARADDLOSFROMNIGHTS')); ?></span><input type=\"number\" value=\""+base_los+"\" min=\""+base_los+"\" max=\""+base_maxlos+"\" id=\"vcm-addlos-from-inp\"/><span class=\"vcm-addlos-to\"><?php echo addslashes(JText::_('VCMRARADDLOSTONIGHTS')); ?></span><input type=\"number\" value=\""+base_los+"\" min=\""+base_los+"\" max=\""+base_maxlos+"\" id=\"vcm-addlos-to-inp\"/></div></div>";
	addlos_cont_html += "<div class=\"vcm-addlos-right\"><p><?php echo addslashes(JText::_('VCMRARADDLOSCOSTPNIGHT')); ?></p><div><span>"+base_currency+"</span><input type=\"text\" value=\"\" placeholder=\"0.00\" size=\"7\" id=\"vcm-addlos-price-inp\"/></div></div>";
	addlos_cont_html += "<br clear=\"all\"/><div class=\"vcm-addlos-bottom\"><button class=\"btn btn-success\" id=\"vcm-addlos-apply\" type=\"button\" data-rateflag=\""+losrateflag+"\" data-ratecurrency=\""+base_currency+"\"><?php echo addslashes(JText::_('VCMRARADDLOSAPPLY')); ?></button></div>";
	var losadded_cont = "";
	var all_rplan_los = jQuery(this).parent().find(".vcm-los-new");
	if(all_rplan_los.length > 0) {
		all_rplan_los.each(function(){
			var now_addlos = jQuery(this).attr("data-los");
			losadded_cont += "<div class=\"vcm-addlos-applied-los\" data-rateflag=\""+losrateflag+"\" data-los=\""+now_addlos+"\"><span class=\"vcm-addlos-applied-cont\">"+los_label.replace("%d", now_addlos)+"</span><span class=\"vcm-addlos-unset\"> </span></div>";
		});
	}
	addlos_cont_html += "<div class=\"vcm-addlos-applied\">"+losadded_cont+"</div>";
	jQuery(".vcm-info-overlay-content").html(addlos_cont_html);
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
	var now_los = jQuery(this).parent().attr("data-los");
	jQuery(".vcmrar-addlos[data-rateflag='"+losrateflag+"']").parent().find(".vcmrarratesp[data-los='"+now_los+"']").remove();
	jQuery(this).parent(".vcm-addlos-applied-los").remove();
	//update rate-base-min-los
	var set_rmin_los = parseInt(now_los);
	var all_rplan_los = jQuery(".vcmrar-addlos[data-rateflag='"+losrateflag+"']").parent().find(".vcmrarratesp");
	if(all_rplan_los.length > 0) {
		all_rplan_los.each(function(){
			var now_addlos = jQuery(this).attr("data-los");
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
/* Apply Costs per night based on LOS */
jQuery("body").on("click", "#vcm-addlos-apply", function() {
	var losrateflag = jQuery(this).attr("data-rateflag");
	var from_nights = parseInt(jQuery("#vcm-addlos-from-inp").val());
	var to_nights = parseInt(jQuery("#vcm-addlos-to-inp").val());
	var price_pnight = parseFloat(jQuery("#vcm-addlos-price-inp").val());
	var base_currency = jQuery(this).attr("data-ratecurrency");
	var los_label = '<?php echo addslashes(JText::_('VCMRARRATEPERDAYLOS')); ?>';
	if(!isNaN(price_pnight)) {
		if(from_nights > 0 && to_nights > 0 && to_nights >= from_nights && losrateflag.length) {
			var nights_diff = to_nights - from_nights;
			for(var i = 0; i <= nights_diff; i++) {
				jQuery(".vcmrar-addlos[data-rateflag='"+losrateflag+"']").before("<span class=\"vcmrarratesp vcm-los-new\" data-los=\""+(from_nights + i)+"\">"+los_label.replace("%d", (from_nights + i))+" <span class=\"vcmrarcurrency\">"+base_currency+"</span> <input type=\"text\" placeholder=\"0.00\" value=\""+price_pnight+"\" name=\"rateplan_"+losrateflag+"_"+(from_nights + i - 1)+"\" size=\"5\"></span>");
			}
			jQuery(".vcmrar-addlos[data-rateflag='"+losrateflag+"']").attr("data-rateminlos", (to_nights + 1));
			jQuery("#addrateplans_"+losrateflag).val(to_nights);
			vcmCloseModal();
		}else {
			alert('Error: invalid data.');
		}
	}else {
		alert('Float Numbers must be formatted with a dot. Example: 12.34');
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
}