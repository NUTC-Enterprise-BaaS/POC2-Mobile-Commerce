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

JHTML::_('behavior.tooltip');

$config = $this->config;
$module = $this->module;

$vb_params = array(
	"currencysymb" => '&euro;',
	"currencyname" => 'EUR',
	"emailadmin" => '',
	"dateformat" => '%Y/%m/%d',
);

$iso4217 = array(
	'AED: United Arab Emirates Dirham',
	'AFN: Afghan Afghani',
	'ALL: Albanian Lek',
	'AMD: Armenian Dram',
	'ANG: Netherlands Antillean Gulden',
	'AOA: Angolan Kwanza',
	'ARS: Argentine Peso',
	'AUD: Australian Dollar',
	'AWG: Aruban Florin',
	'AZN: Azerbaijani Manat',
	'BAM: Bosnia & Herzegovina Convertible Mark',
	'BBD: Barbadian Dollar',
	'BDT: Bangladeshi Taka',
	'BGN: Bulgarian Lev',
	'BIF: Burundian Franc',
	'BMD: Bermudian Dollar',
	'BND: Brunei Dollar',
	'BOB: Bolivian Boliviano',
	'BRL: Brazilian Real',
	'BSD: Bahamian Dollar',
	'BWP: Botswana Pula',
	'BZD: Belize Dollar',
	'CAD: Canadian Dollar',
	'CDF: Congolese Franc',
	'CHF: Swiss Franc',
	'CLP: Chilean Peso',
	'CNY: Chinese Renminbi Yuan',
	'COP: Colombian Peso',
	'CRC: Costa Rican Colón',
	'CVE: Cape Verdean Escudo',
	'CZK: Czech Koruna',
	'DJF: Djiboutian Franc',
	'DKK: Danish Krone',
	'DOP: Dominican Peso',
	'DZD: Algerian Dinar',
	'EEK: Estonian Kroon',
	'EGP: Egyptian Pound',
	'ETB: Ethiopian Birr',
	'EUR: Euro',
	'FJD: Fijian Dollar',
	'FKP: Falkland Islands Pound',
	'GBP: British Pound',
	'GEL: Georgian Lari',
	'GIP: Gibraltar Pound',
	'GMD: Gambian Dalasi',
	'GNF: Guinean Franc',
	'GTQ: Guatemalan Quetzal',
	'GYD: Guyanese Dollar',
	'HKD: Hong Kong Dollar',
	'HNL: Honduran Lempira',
	'HRK: Croatian Kuna',
	'HTG: Haitian Gourde',
	'HUF: Hungarian Forint',
	'IDR: Indonesian Rupiah',
	'ILS: Israeli New Sheqel',
	'INR: Indian Rupee',
	'ISK: Icelandic Króna',
	'JMD: Jamaican Dollar',
	'JPY: Japanese Yen',
	'KES: Kenyan Shilling',
	'KGS: Kyrgyzstani Som',
	'KHR: Cambodian Riel',
	'KMF: Comorian Franc',
	'KRW: South Korean Won',
	'KYD: Cayman Islands Dollar',
	'KZT: Kazakhstani Tenge',
	'LAK: Lao Kip',
	'LBP: Lebanese Pound',
	'LKR: Sri Lankan Rupee',
	'LRD: Liberian Dollar',
	'LSL: Lesotho Loti',
	'LTL: Lithuanian Litas',
	'LVL: Latvian Lats',
	'MAD: Moroccan Dirham',
	'MDL: Moldovan Leu',
	'MGA: Malagasy Ariary',
	'MKD: Macedonian Denar',
	'MNT: Mongolian Tögrög',
	'MOP: Macanese Pataca',
	'MRO: Mauritanian Ouguiya',
	'MUR: Mauritian Rupee',
	'MVR: Maldivian Rufiyaa',
	'MWK: Malawian Kwacha',
	'MXN: Mexican Peso',
	'MYR: Malaysian Ringgit',
	'MZN: Mozambican Metical',
	'NAD: Namibian Dollar',
	'NGN: Nigerian Naira',
	'NIO: Nicaraguan Córdoba',
	'NOK: Norwegian Krone',
	'NPR: Nepalese Rupee',
	'NZD: New Zealand Dollar',
	'PAB: Panamanian Balboa',
	'PEN: Peruvian Nuevo Sol',
	'PGK: Papua New Guinean Kina',
	'PHP: Philippine Peso',
	'PKR: Pakistani Rupee',
	'PLN: Polish Złoty',
	'PYG: Paraguayan Guaraní',
	'QAR: Qatari Riyal',
	'RON: Romanian Leu',
	'RSD: Serbian Dinar',
	'RUB: Russian Ruble',
	'RWF: Rwandan Franc',
	'SAR: Saudi Riyal',
	'SBD: Solomon Islands Dollar',
	'SCR: Seychellois Rupee',
	'SEK: Swedish Krona',
	'SGD: Singapore Dollar',
	'SHP: Saint Helenian Pound',
	'SLL: Sierra Leonean Leone',
	'SOS: Somali Shilling',
	'SRD: Surinamese Dollar',
	'STD: São Tomé and Príncipe Dobra',
	'SVC: Salvadoran Colón',
	'SZL: Swazi Lilangeni',
	'THB: Thai Baht',
	'TJS: Tajikistani Somoni',
	'TOP: Tongan Paʻanga',
	'TRY: Turkish Lira',
	'TTD: Trinidad and Tobago Dollar',
	'TWD: New Taiwan Dollar',
	'TZS: Tanzanian Shilling',
	'UAH: Ukrainian Hryvnia',
	'UGX: Ugandan Shilling',
	'USD: United States Dollar',
	'UYU: Uruguayan Peso',
	'UZS: Uzbekistani Som',
	'VEF: Venezuelan Bolívar',
	'VND: Vietnamese Đồng',
	'VUV: Vanuatu Vatu',
	'WST: Samoan Tala',
	'XAF: Central African Cfa Franc',
	'XCD: East Caribbean Dollar',
	'XOF: West African Cfa Franc',
	'XPF: Cfp Franc',
	'YER: Yemeni Rial',
	'ZAR: South African Rand',
	'ZMW: Zambian Kwacha'
);

if( file_exists(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'lib.vikbooking.php') ) {
	if(!class_exists('vikbooking')) {
		require_once (JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'lib.vikbooking.php');
	}
	$vb_params['currencysymb'] = vikbooking::getCurrencySymb(true);
	$vb_params['currencyname'] = vikbooking::getCurrencyName(true);
	$vb_params['emailadmin'] = vikbooking::getAdminMail(true);
	$vb_params['dateformat'] = vikbooking::getDateFormat(true);
}

foreach( $vb_params as $k => $v ) {
	if( empty($config[$k]) ) {
		$config[$k] = $v;
	}
}

$old_pub_val = -1;
$select_payments = '<select name="defaultpayment">';
$select_payments .= '<option value="">'.JText::_('VCMCONFDEFPAYMENTOPTNONE').'</option>';
foreach( $this->vb_payments as $k => $v ) {
	if( $old_pub_val != $v['published'] ) {
		if( $k != 0 ) {
			$select_payments .= '</optgroup>';
		}
		$select_payments .= '<optgroup label="'.JText::_('VCMPAYMENTSTATUS'.$v['published']).'">';
		$old_pub_val = $v['published'];
	}
	$select_payments .= '<option value="'.$v['id'].'" '.($config['defaultpayment'] == $v['id'] ? 'selected="selected"' : '').'>'.$v['name'].'</option>';
}
$select_payments .= '</optgroup>';
$select_payments .= '</select>';

if(count($this->more_accounts)) {
?>
<div class="vcm-info-overlay-block">
	<div class="vcm-info-overlay-content">
		<h3><?php echo ucwords($module['name']).' - '.JText::_('VCMMANAGEACCOUNTS'); ?></h3>
		<table class="vcm-moreaccounts-table">
			<tr class="vcm-moreaccounts-firstrow">
				<td><?php echo JText::_('VCMMANAGEACCOUNTNAME'); ?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		<?php
		foreach ($this->more_accounts as $acck => $accv) {
			$acc_name = $accv['prop_name'];
			$acc_info = json_decode($accv['prop_params'], true);
			$acc_id = array_key_exists('hotelid', $acc_info) ? $acc_info['hotelid'] : '';
			?>
			<tr class="vcm-moreaccounts-rows">
				<td><span<?php echo (!empty($acc_id) ? ' title="ID '.$acc_id.'"' : ''); ?> class="vcm-moreaccounts-listname"><?php echo (empty($acc_name) ? $acc_id : $acc_name); ?></span><span class="vcm-moreaccounts-numrooms"><?php echo JText::sprintf('VCMMANAGEACCOUNTNUMRS', $accv['tot_rooms']); ?></span></td>
				<td>
				<?php
				if($accv['active'] != 1) {
					?>
					<button type="button" class="btn btn-primary" onclick="vcmCloseModal();setAccountParams('<?php echo $acck; ?>');"><?php echo JText::_('VCMSELECTACCOUNT'); ?></button>
					<?php
				}else {
					?>
					<button type="button" class="btn btn-success" onclick="vcmCloseModal();"><?php echo JText::_('VCMACTIVEACCOUNT'); ?></button>
					<?php
				}
				?>
				</td>
				<td>
				<?php
				if($accv['active'] != 1) {
					?>
					<button type="button" class="btn btn-danger" onclick="vcmCloseModal();removeAccount('<?php echo $acck; ?>', '<?php echo $acc_id; ?>');"><?php echo JText::_('VCMREMOVEACCOUNT'); ?></button>
					<?php
				}else {
					?>&nbsp;<?php
				}
				?>
				</td>
			</tr>
			<?php
		}
		?>
		</table>
	</div>
</div>
<?php
}
?>

<form name="adminForm" action="index.php" method="post" id="adminForm">
	<fieldset class="adminform">
		<table cellspacing="1" class="admintable table">
			<tbody>
			<?php if( $this->showSync ) { ?>
				<tr>
					<td width="200" class="vcm-config-param-cell"> <b><?php echo JText::_('VCMAUTOSYNC'); ?></b> </td>
					<td>
						<div class="controls">
							<fieldset class="radio btn-group btn-group-yesno">
								<input type="radio" id="vcmsyncon" value="1" name="vikbookingsynch" class="btn-group"<?php echo ($config['vikbookingsynch']=="1" ? " checked=\"checked\"" : ""); ?>>
								<label style="display: inline-block; margin: 0;" for="vcmsyncon"><?php echo JText::_('VCMAUTOSYNCON'); ?></label>
								<input type="radio" id="vcmsyncoff" value="0" name="vikbookingsynch" class="btn-group"<?php echo ($config['vikbookingsynch']=="0" ? " checked=\"checked\"" : ""); ?>>
								<label style="display: inline-block; margin: 0;" for="vcmsyncoff"><?php echo JText::_('VCMAUTOSYNCOFF'); ?></label>
							</fieldset>
						</div>
					</td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
			<?php } else { ?>
				<input type="hidden" name="vikbookingsynch" value="<?php echo intval($config['vikbookingsynch']); ?>" />
			<?php } ?>
				
				<tr>
					<td width="200" class="vcm-config-param-cell"> <b><?php echo JText::_('VCMCONFEMAIL'); ?></b> </td>
					<td><input type="text" name="emailadmin" value="<?php echo $config['emailadmin']; ?>" size="40"/></td>
				</tr>
				
				<tr>
					<td width="200" class="vcm-config-param-cell"> <b><?php echo JText::_('VCMCONFDATEFORMAT'); ?></b> </td>
					<td>
						<select name="dateformat">
							<option value="%Y/%m/%d"<?php echo ($config['dateformat']=="%Y/%m/%d" ? " selected=\"selected\"" : ""); ?>>Y/m/d</option>
							<option value="%d/%m/%Y"<?php echo ($config['dateformat']=="%d/%m/%Y" ? " selected=\"selected\"" : ""); ?>>d/m/Y</option>
							<option value="%m/%d/%Y"<?php echo ($config['dateformat']=="%m/%d/%Y" ? " selected=\"selected\"" : ""); ?>>m/d/Y</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<td width="200" class="vcm-config-param-cell"> <b><?php echo JText::_('VCMCONFCURSYMB'); ?></b> </td>
					<td><input type="text" name="currencysymb" value="<?php echo $config['currencysymb']; ?>" size="10"/></td>
				</tr>
			
				<tr>
					<td width="200" class="vcm-config-param-cell"> <b><?php echo JText::_('VCMCONFCURNAME'); ?></b> </td>
					<td>
						<select name="currencyname">
						<?php
						foreach ($iso4217 as $currency) {
							echo '<option value="'.substr($currency, 0, 3).'"'.($config['currencyname'] == substr($currency, 0, 3) ? ' selected="selected"' : '').'>'.$currency.'</option>'."\n";
						}
						?>
						</select>
					</td>
				</tr>
				
				<tr>
					<td width="200" class="vcm-config-param-cell"> <b><?php echo JText::_('VCMCONFDEFPAYMENTOPT'); ?></b> </td>
					<td><?php echo $select_payments; ?></td>
				</tr>

				<tr>
					<td width="200" class="vcm-config-param-cell">&nbsp;</td>
					<td><a href="index.php?option=com_vikchannelmanager&amp;task=diagnostic" class="vcm-diagnostic-setting"><?php echo JText::_('VCMCONFDIAGNOSTICBTN'); ?></a></td>
				</tr>
			
				<tr><td colspan="2" style="border-top: 0;">&nbsp;</td></tr>
				
				<tr>
					<td colspan="2" width="400">
						<div class="vcmorderapikeydiv">
							<div class="vcmorderapikeylogo">
								<span>
								</span>
							</div>
							<div class="vcmorderapikeyinner">
								<span class="vcmorderapikeylabspan"><?php echo JText::_('VCMAPIKEY'); ?>:</span>
								<input class="vcmorderapikeyvalinput" name="apikey" value="<?php echo $config['apikey']; ?>" size="24"/>
							</div>
						</div>
					</td>
				</tr>
				
				<tr><td colspan="2">&nbsp;</td></tr>
			</tbody>
		</table>
	</fieldset>
	
	<div class="vcmconfigbottom">
	
	<?php if( !empty($module['id']) ) { ?>
		<div class="vcmparamshead<?php echo preg_replace("/[^a-zA-Z0-9]+/", '', $module['name']); ?>">
			<h3><?php echo ucwords($module['name']); ?></h3>
		</div>
		<?php
		if(count($this->more_accounts)) {
			?>
			<div class="vcm-moreaccounts-cont">
				<div class="vcm-moreaccounts-inner">
					<div class="vcm-moreaccounts-sel">
						<label for="vcm-changeaccount"><?php echo JText::_('VCMCHANGEACCOUNT'); ?></label>
						<select id="vcm-changeaccount" onchange="setAccountParams(this.value);">
						<?php
						foreach ($this->more_accounts as $acck => $accv) {
							$acc_name = $accv['prop_name'];
							if(empty($acc_name)) {
								$acc_info = json_decode($accv['prop_params'], true);
								$acc_name = array_key_exists('hotelid', $acc_info) ? $acc_info['hotelid'] : '----';
							}
							?>
							<option value="<?php echo $acck; ?>"<?php echo $accv['active'] == 1 ? ' selected="selected"' : ''; ?>><?php echo $acc_name; ?></option>
							<?php
						}
						?>
						</select>
					</div>
					<div class="vcm-moreaccounts-manage">
					<button type="button" class="btn" onclick="vcmOpenModal();"><i class="icon-edit"></i><?php echo JText::_('VCMMANAGEACCOUNTS'); ?></button>
					</div>
				</div>
			</div>
			<?php
		}
		?>
		<fieldset class="adminform">
			<table cellspacing="1" class="admintable table">
				<tbody>
			<?php foreach($module['params'] as $k => $v ) {
				if( strpos($k, 'pwd') !== false || strpos($k, 'pass') !== false ) { ?>
					<tr>
						<td width="200" class="vcm-config-param-cell"> <b><?php echo ucwords($k); ?> <sup>*</sup></b> </td>
						<td>
							<?php for( $i = 0, $ph = ''; $i < strlen($v); $i++ ) {
								$ph .= '*';
							} ?>
							<input type="text" name="<?php echo $k; ?>" value="" size="13" placeholder="<?php echo $ph; ?>" data-param="<?php echo $k; ?>"/>
							<input type="hidden" name="old_<?php echo $k; ?>" value="<?php echo $v; ?>"/>
						</td>
						
					</tr>
				<?php } else { ?> 
					<tr>
						<td width="200" class="vcm-config-param-cell"> <b><?php echo ucwords($k); ?> <sup>*</sup></b> </td>
						<td><input type="text" name="<?php echo $k; ?>" value="<?php echo $v; ?>" size="13" data-param="<?php echo $k; ?>"/></td>
					</tr>
				<?php } ?>
			<?php } ?>
				</tbody>
			</table>
		</fieldset>
		
	<?php if(!empty($module['settings']) ) { ?>
		<div class="vcmsettingshead<?php echo preg_replace("/[^a-zA-Z0-9]+/", '', $module['name']); ?>">
			<h3><?php echo JText::_('VCMCONFIGCHASETTINGSTITLE'); ?></h3>
		</div>
		<fieldset class="adminform">
			<table cellspacing="1" class="admintable table">
				<tbody>
				<?php foreach($module['settings'] as $k => $v ) { 
					$required = '';
					$req_action = '';
					if( $v['required'] ) {
						$required = ' <sup>*</sup>';
						$req_action = 'onBlur="checkField(\'vcm'.$k.'\');"';
					}
					?>
					<tr>
						<td width="200" class="vcm-config-param-cell" style="<?php echo ($v['type'] == 'largetext' || $v['type'] == 'multiple' ? 'vertical-align: top !important;' : ''); ?>"> <b><?php echo JText::_($v['label']).$required; ?></b> </td>
						<td><?php
						
							if( $v['type'] == 'text' ) {
								?><input type="text" name="<?php echo $k; ?>" id="vcm<?php echo $k; ?>" value="<?php echo ((!empty($v['value'])) ? $v['value'] : $v['default']); ?>" <?php echo $req_action; ?> size="30"/><?php
							} else if( $v['type'] == 'largetext' ) {
								?><textarea cols="50" rows="6" name="<?php echo $k; ?>" id="vcm<?php echo $k; ?>" <?php echo $req_action; ?>><?php echo ((!empty($v['value'])) ? $v['value'] : $v['default']); ?></textarea><?php
							} else if( $v['type'] == 'select' ) {
								?><select name="<?php echo $k; ?>" id="vcm<?php echo $k; ?>" <?php echo $req_action; ?>>
									<?php $value = (!empty($v['value']) ? $v['value'] : $v['default']);
									foreach( $v['options'] as $o ) { ?>
										<option value="<?php echo $o; ?>" <?php echo (($value == $o) ? 'selected="selected"' : ''); ?>><?php echo JText::_($o); ?></option>
									<?php } ?>
								</select><?php
							} else if( $v['type'] == 'multiple' ) {
								?><select name="<?php echo $k; ?>[]" id="vcm<?php echo $k; ?>" multiple size="<?php echo min(count($v['options'])+1, 8); ?>" <?php echo $req_action; ?>>
									<?php $values = (count($v['value']) > 0 ? $v['value'] : $v['default']);
									foreach( $v['options'] as $o ) { ?>
										<option value="<?php echo $o; ?>" <?php echo ((@in_array($o, $values) == $o) ? 'selected="selected"' : ''); ?>><?php echo JText::_($o); ?></option>
									<?php } ?>
								</select><?php
							}
						
						$help_text = JText::_($v['label'].'_HELP');
						if( !empty($help_text) && $help_text != $v['label'].'_HELP' ) {
							?><span class="vcmchsetthelptext" style="<?php echo ($v['type'] == 'largetext' || $v['type'] == 'multiple' ? 'vertical-align: top;' : ''); ?>"><?php echo $help_text; ?></span><?php	
						}
						?>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</fieldset>
		<?php } ?>
	<?php } ?>
	
	</div>
	
	<input type="hidden" name="task" value="saveconfig">
	<input type="hidden" name="option" value="com_vikchannelmanager">
</form>

<script>
	function checkField(id) {
		if( jQuery('#'+id).val().length == 0 ) {
			jQuery('#'+id).addClass('vcmrequired');
		} else {
			jQuery('#'+id).removeClass('vcmrequired');
		}
	}
	var vcm_overlay_on = false;
	function vcmCloseModal() {
		jQuery(".vcm-info-overlay-block").fadeOut(400, function() {
			jQuery(this).attr("class", "vcm-info-overlay-block");
		});
		vcm_overlay_on = false;
	}
	function vcmOpenModal() {
		jQuery(".vcm-info-overlay-block").fadeIn();
		vcm_overlay_on = true;
	}
	jQuery(document).ready(function(){
		jQuery(document).mouseup(function(e) {
			if(!vcm_overlay_on) {
				return false;
			}
			var vcm_overlay_cont = jQuery(".vcm-info-overlay-content");
			if(!vcm_overlay_cont.is(e.target) && vcm_overlay_cont.has(e.target).length === 0) {
				vcmCloseModal();
			}
		});
		jQuery(document).keyup(function(e) {
			if (e.keyCode == 27 && vcm_overlay_on) {
				vcmCloseModal();
			}
		});
	});
<?php
if(count($this->more_accounts)) {
	$js_acc_arr = array();
	foreach ($this->more_accounts as $acck => $accv) {
		$js_acc_arr[$acck] = json_decode($accv['prop_params']);
	}
	?>
	var vcm_accounts_params = <?php echo json_encode($js_acc_arr); ?>;
	function setAccountParams(ind) {
		if(!window.jQuery) {
			alert('JavaScript error: jQuery is undefined');
			return false;
		}
		var params_set = 0;
		if(vcm_accounts_params.hasOwnProperty(ind)) {
			for (var param in vcm_accounts_params[ind]) {
				if(vcm_accounts_params[ind].hasOwnProperty(param)) {
					var felem = jQuery("input[data-param='"+param+"']");
					if(felem.length) {
						felem.val(vcm_accounts_params[ind][param]).addClass('vcm-accountparam-changed');
						params_set++;
					}
				}
			}
		}
		if(params_set > 0) {
			document.getElementById("adminForm").submit();
		}else {
			alert('No Params found');
			return false;
		}
	}
	function removeAccount(ind, hotelid) {
		if(!window.jQuery) {
			alert('JavaScript error: jQuery is undefined');
			return false;
		}
		if(confirm('<?php echo addslashes(JText::_('VCMREMOVEACCOUNTCONF')); ?>')) {
			window.location.href = 'index.php?option=com_vikchannelmanager&task=rmchaccount&ind='+ind+'&hid='+hotelid;
		}else {
			return false;
		}

	}
	<?php
}
?>
</script>

