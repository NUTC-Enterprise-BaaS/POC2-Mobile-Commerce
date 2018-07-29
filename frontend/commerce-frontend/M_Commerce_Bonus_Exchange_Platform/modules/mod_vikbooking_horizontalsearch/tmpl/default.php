<?php  
/**------------------------------------------------------------------------
 * mod_vikbooking_horizontalsearch - VikBooking
 * ------------------------------------------------------------------------
 * author    Alessio Gaggii - e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') or die('Restricted Area');

$session = JFactory::getSession();
$dbo = JFactory::getDBO();

$randid = is_object($module) && property_exists($module, 'id') ? $module->id : rand(1, 999);

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root().'modules/mod_vikbooking_horizontalsearch/mod_vikbooking_horizontalsearch.css');
if(intval($params->get('loadjqueryvb')) == 1) {
	JHtml::_('jquery.framework', true, true);
	JHtml::_('script', JURI::root().'components/com_vikbooking/resources/jquery-1.11.3.min.js', false, true, false, false);
}
if ($params->get('calendar') != "jqueryui") {
	$calendartype = "joomla";
	JHTML::_('behavior.calendar');
}else {
	$calendartype = "jqueryui";
	//load jQuery UI
	$document->addStyleSheet(JURI::root().'components/com_vikbooking/resources/jquery-ui.min.css');
	JHtml::_('script', JURI::root().'components/com_vikbooking/resources/jquery-ui.min.js', false, true, false, false);
}
//vikbooking 1.2
$restrictions = modVikbooking_horizontalsearchHelper::loadRestrictions();
//
$vbdateformat = modVikbooking_horizontalsearchHelper::getDateFormat();
if ($vbdateformat == "%d/%m/%Y") {
	$df = 'd/m/Y';
} elseif ($vbdateformat == "%m/%d/%Y") {
	$df = 'm/d/Y';
} else {
	$df = 'Y/m/d';
}
?>

<div class="<?php echo $params->get('moduleclass_sfx'); ?>">
	<div class="vbmodhorsearchmaindiv">
	<?php
	echo (strlen($vrtext) > 0 ? $vrtext : "");
	?>
	<form action="<?php echo JRoute::_('index.php?option=com_vikbooking'.(strlen($force_menu_itemid = $params->get('itemid')) ? '&Itemid='.$force_menu_itemid : '')); ?>" method="get">
		<input type="hidden" name="option" value="com_vikbooking" />
		<input type="hidden" name="task" value="search" />
	<?php
	if(intval($params->get('room_id')) > 0) {
		?>
		<input type="hidden" name="roomdetail" value="<?php echo $params->get('room_id'); ?>" />
		<?php
	}
	$timeopst = modVikbooking_horizontalsearchHelper::getTimeOpenStore();
	if (is_array($timeopst)) {
		$opent = modVikbooking_horizontalsearchHelper::getHoursMinutes($timeopst[0]);
		$closet = modVikbooking_horizontalsearchHelper::getHoursMinutes($timeopst[1]);
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
	
	if($calendartype == "jqueryui") {
		if ($vbdateformat == "%d/%m/%Y") {
			$juidf = 'dd/mm/yy';
		}elseif ($vbdateformat == "%m/%d/%Y") {
			$juidf = 'mm/dd/yy';
		}else {
			$juidf = 'yy/mm/dd';
		}
		//lang for jQuery UI Calendar
		$ldecl = '
jQuery.noConflict();
jQuery(function($){'."\n".'
	$.datepicker.regional["vikbookingmod"] = {'."\n".'
		closeText: "'.JText::_('VBJQCALDONE').'",'."\n".'
		prevText: "'.JText::_('VBJQCALPREV').'",'."\n".'
		nextText: "'.JText::_('VBJQCALNEXT').'",'."\n".'
		currentText: "'.JText::_('VBJQCALTODAY').'",'."\n".'
		monthNames: ["'.JText::_('VBMONTHONE').'","'.JText::_('VBMONTHTWO').'","'.JText::_('VBMONTHTHREE').'","'.JText::_('VBMONTHFOUR').'","'.JText::_('VBMONTHFIVE').'","'.JText::_('VBMONTHSIX').'","'.JText::_('VBMONTHSEVEN').'","'.JText::_('VBMONTHEIGHT').'","'.JText::_('VBMONTHNINE').'","'.JText::_('VBMONTHTEN').'","'.JText::_('VBMONTHELEVEN').'","'.JText::_('VBMONTHTWELVE').'"],'."\n".'
		monthNamesShort: ["'.mb_substr(JText::_('VBMONTHONE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VBMONTHTWO'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VBMONTHTHREE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VBMONTHFOUR'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VBMONTHFIVE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VBMONTHSIX'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VBMONTHSEVEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VBMONTHEIGHT'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VBMONTHNINE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VBMONTHTEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VBMONTHELEVEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VBMONTHTWELVE'), 0, 3, 'UTF-8').'"],'."\n".'
		dayNames: ["'.JText::_('VBJQCALSUN').'", "'.JText::_('VBJQCALMON').'", "'.JText::_('VBJQCALTUE').'", "'.JText::_('VBJQCALWED').'", "'.JText::_('VBJQCALTHU').'", "'.JText::_('VBJQCALFRI').'", "'.JText::_('VBJQCALSAT').'"],'."\n".'
		dayNamesShort: ["'.mb_substr(JText::_('VBJQCALSUN'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::_('VBJQCALMON'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::_('VBJQCALTUE'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::_('VBJQCALWED'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::_('VBJQCALTHU'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::_('VBJQCALFRI'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::_('VBJQCALSAT'), 0, 3, 'UTF-8').'"],'."\n".'
		dayNamesMin: ["'.mb_substr(JText::_('VBJQCALSUN'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::_('VBJQCALMON'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::_('VBJQCALTUE'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::_('VBJQCALWED'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::_('VBJQCALTHU'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::_('VBJQCALFRI'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::_('VBJQCALSAT'), 0, 2, 'UTF-8').'"],'."\n".'
		weekHeader: "'.JText::_('VBJQCALWKHEADER').'",'."\n".'
		dateFormat: "'.$juidf.'",'."\n".'
		firstDay: 1,'."\n".'
		isRTL: false,'."\n".'
		showMonthAfterYear: false,'."\n".'
		yearSuffix: ""'."\n".'
	};'."\n".'
	$.datepicker.setDefaults($.datepicker.regional["vikbookingmod"]);'."\n".'
});
function vbGetDateObject'.$randid.'(dstring) {
	var dparts = dstring.split("-");
	return new Date(dparts[0], (parseInt(dparts[1]) - 1), parseInt(dparts[2]), 0, 0, 0, 0);
}
function vbFullObject'.$randid.'(obj) {
	var jk;
	for(jk in obj) {
		return obj.hasOwnProperty(jk);
	}
}';
		$document->addScriptDeclaration($ldecl);
		//
		//VikBooking 1.4
		$totrestrictions = count($restrictions);
		if ($totrestrictions > 0) {
			$wdaysrestrictions = array();
			$wdaystworestrictions = array();
			$wdaysrestrictionsrange = array();
			$wdaysrestrictionsmonths = array();
			$monthscomborestr = array();
			$minlosrestrictions = array();
			$minlosrestrictionsrange = array();
			$maxlosrestrictions = array();
			$maxlosrestrictionsrange = array();
			$notmultiplyminlosrestrictions = array();
			foreach($restrictions as $rmonth => $restr) {
				if($rmonth != 'range') {
					if (strlen($restr['wday']) > 0) {
						$wdaysrestrictions[] = "'".($rmonth - 1)."': '".$restr['wday']."'";
						$wdaysrestrictionsmonths[] = $rmonth;
						if (strlen($restr['wdaytwo']) > 0) {
							$wdaystworestrictions[] = "'".($rmonth - 1)."': '".$restr['wdaytwo']."'";
							$monthscomborestr[($rmonth - 1)] = modVikbooking_horizontalsearchHelper::parseJsDrangeWdayCombo($restr);
						}
					}
					if ($restr['multiplyminlos'] == 0) {
						$notmultiplyminlosrestrictions[] = $rmonth;
					}
					$minlosrestrictions[] = "'".($rmonth - 1)."': '".$restr['minlos']."'";
					if (!empty($restr['maxlos']) && $restr['maxlos'] > 0 && $restr['maxlos'] > $restr['minlos']) {
						$maxlosrestrictions[] = "'".($rmonth - 1)."': '".$restr['maxlos']."'";
					}
				}else {
					foreach ($restr as $kr => $drestr) {
						if (strlen($drestr['wday']) > 0) {
							$wdaysrestrictionsrange[$kr][0] = date('Y-m-d', $drestr['dfrom']);
							$wdaysrestrictionsrange[$kr][1] = date('Y-m-d', $drestr['dto']);
							$wdaysrestrictionsrange[$kr][2] = $drestr['wday'];
							$wdaysrestrictionsrange[$kr][3] = $drestr['multiplyminlos'];
							$wdaysrestrictionsrange[$kr][4] = strlen($drestr['wdaytwo']) > 0 ? $drestr['wdaytwo'] : -1;
							$wdaysrestrictionsrange[$kr][5] = modVikbooking_horizontalsearchHelper::parseJsDrangeWdayCombo($drestr);
						}
						$minlosrestrictionsrange[$kr][0] = date('Y-m-d', $drestr['dfrom']);
						$minlosrestrictionsrange[$kr][1] = date('Y-m-d', $drestr['dto']);
						$minlosrestrictionsrange[$kr][2] = $drestr['minlos'];
						if (!empty($drestr['maxlos']) && $drestr['maxlos'] > 0 && $drestr['maxlos'] > $drestr['minlos']) {
							$maxlosrestrictionsrange[$kr] = $drestr['maxlos'];
						}
					}
					unset($restrictions['range']);
				}
			}
		
			$resdecl = "
var vbrestrmonthswdays = [".implode(", ", $wdaysrestrictionsmonths)."];
var vbrestrmonths = [".implode(", ", array_keys($restrictions))."];
var vbrestrmonthscombojn = jQuery.parseJSON('".json_encode($monthscomborestr)."');
var vbrestrminlos = {".implode(", ", $minlosrestrictions)."};
var vbrestrminlosrangejn = jQuery.parseJSON('".json_encode($minlosrestrictionsrange)."');
var vbrestrmultiplyminlos = [".implode(", ", $notmultiplyminlosrestrictions)."];
var vbrestrmaxlos = {".implode(", ", $maxlosrestrictions)."};
var vbrestrmaxlosrangejn = jQuery.parseJSON('".json_encode($maxlosrestrictionsrange)."');
var vbrestrwdaysrangejn = jQuery.parseJSON('".json_encode($wdaysrestrictionsrange)."');
var vbcombowdays = {};
function vbRefreshCheckout".$randid."(darrive) {
	if(vbFullObject".$randid."(vbcombowdays)) {
		var vbtosort = new Array();
		for(var vbi in vbcombowdays) {
			if(vbcombowdays.hasOwnProperty(vbi)) {
				var vbusedate = darrive;
				vbtosort[vbi] = vbusedate.setDate(vbusedate.getDate() + (vbcombowdays[vbi] - 1 - vbusedate.getDay() + 7) % 7 + 1);
			}
		}
		vbtosort.sort(function(da, db) {
			return da > db ? 1 : -1;
		});
		for(var vbnext in vbtosort) {
			if(vbtosort.hasOwnProperty(vbnext)) {
				var vbfirstnextd = new Date(vbtosort[vbnext]);
				jQuery('#checkoutdatemod".$randid."').datepicker( 'option', 'minDate', vbfirstnextd );
				jQuery('#checkoutdatemod".$randid."').datepicker( 'setDate', vbfirstnextd );
				break;
			}
		}
	}
}
function vbSetMinCheckoutDatemod".$randid." () {
	var minlos = ".modVikbooking_horizontalsearchHelper::getDefaultNightsCalendar().";
	var maxlosrange = 0;
	var nowcheckin = jQuery('#checkindatemod".$randid."').datepicker('getDate');
	var nowd = nowcheckin.getDay();
	var nowcheckindate = new Date(nowcheckin.getTime());
	vbcombowdays = {};
	if(vbFullObject".$randid."(vbrestrminlosrangejn)) {
		for (var rk in vbrestrminlosrangejn) {
			if(vbrestrminlosrangejn.hasOwnProperty(rk)) {
				var minldrangeinit = vbGetDateObject".$randid."(vbrestrminlosrangejn[rk][0]);
				if(nowcheckindate >= minldrangeinit) {
					var minldrangeend = vbGetDateObject".$randid."(vbrestrminlosrangejn[rk][1]);
					if(nowcheckindate <= minldrangeend) {
						minlos = parseInt(vbrestrminlosrangejn[rk][2]);
						if(vbFullObject".$randid."(vbrestrmaxlosrangejn)) {
							if(rk in vbrestrmaxlosrangejn) {
								maxlosrange = parseInt(vbrestrmaxlosrangejn[rk]);
							}
						}
						if(rk in vbrestrwdaysrangejn && nowd in vbrestrwdaysrangejn[rk][5]) {
							vbcombowdays = vbrestrwdaysrangejn[rk][5][nowd];
						}
					}
				}
			}
		}
	}
	var nowm = nowcheckin.getMonth();
	if(vbFullObject".$randid."(vbrestrmonthscombojn) && vbrestrmonthscombojn.hasOwnProperty(nowm)) {
		if(nowd in vbrestrmonthscombojn[nowm]) {
			vbcombowdays = vbrestrmonthscombojn[nowm][nowd];
		}
	}
	if(jQuery.inArray((nowm + 1), vbrestrmonths) != -1) {
		minlos = parseInt(vbrestrminlos[nowm]);
	}
	nowcheckindate.setDate(nowcheckindate.getDate() + minlos);
	jQuery('#checkoutdatemod".$randid."').datepicker( 'option', 'minDate', nowcheckindate );
	if(maxlosrange > 0) {
		var diffmaxminlos = maxlosrange - minlos;
		var maxcheckoutdate = new Date(nowcheckindate.getTime());
		maxcheckoutdate.setDate(maxcheckoutdate.getDate() + diffmaxminlos);
		jQuery('#checkoutdatemod".$randid."').datepicker( 'option', 'maxDate', maxcheckoutdate );
	}
	if(nowm in vbrestrmaxlos) {
		var diffmaxminlos = parseInt(vbrestrmaxlos[nowm]) - minlos;
		var maxcheckoutdate = new Date(nowcheckindate.getTime());
		maxcheckoutdate.setDate(maxcheckoutdate.getDate() + diffmaxminlos);
		jQuery('#checkoutdatemod".$randid."').datepicker( 'option', 'maxDate', maxcheckoutdate );
	}
	if(!vbFullObject".$randid."(vbcombowdays)) {
		jQuery('#checkoutdatemod".$randid."').datepicker( 'setDate', nowcheckindate );
	}else {
		vbRefreshCheckout".$randid."(nowcheckin);
	}
}";
		
			if(count($wdaysrestrictions) > 0 || count($wdaysrestrictionsrange) > 0) {
				$resdecl .= "
var vbrestrwdays = {".implode(", ", $wdaysrestrictions)."};
var vbrestrwdaystwo = {".implode(", ", $wdaystworestrictions)."};
function vbIsDayDisabledmod".$randid."(date) {
	if(!vbIsDayOpenmod".$randid."(date)) {
		return [false];
	}
	var m = date.getMonth(), wd = date.getDay();
	if(vbFullObject".$randid."(vbrestrwdaysrangejn)) {
		for (var rk in vbrestrwdaysrangejn) {
			if(vbrestrwdaysrangejn.hasOwnProperty(rk)) {
				var wdrangeinit = vbGetDateObject".$randid."(vbrestrwdaysrangejn[rk][0]);
				if(date >= wdrangeinit) {
					var wdrangeend = vbGetDateObject".$randid."(vbrestrwdaysrangejn[rk][1]);
					if(date <= wdrangeend) {
						if(wd != vbrestrwdaysrangejn[rk][2]) {
							if(vbrestrwdaysrangejn[rk][4] == -1 || wd != vbrestrwdaysrangejn[rk][4]) {
								return [false];
							}
						}
					}
				}
			}
		}
	}
	if(vbFullObject".$randid."(vbrestrwdays)) {
		if(jQuery.inArray((m+1), vbrestrmonthswdays) == -1) {
			return [true];
		}
		if(wd == vbrestrwdays[m]) {
			return [true];
		}
		if(vbFullObject".$randid."(vbrestrwdaystwo)) {
			if(wd == vbrestrwdaystwo[m]) {
				return [true];
			}
		}
		return [false];
	}
	return [true];
}
function vbIsDayDisabledCheckoutmod".$randid."(date) {
	if(!vbIsDayOpenmod".$randid."(date)) {
		return [false];
	}
	var m = date.getMonth(), wd = date.getDay();
	if(vbFullObject".$randid."(vbcombowdays)) {
		if(jQuery.inArray(wd, vbcombowdays) != -1) {
			return [true];
		}else {
			return [false];
		}
	}
	if(vbFullObject".$randid."(vbrestrwdaysrangejn)) {
		for (var rk in vbrestrwdaysrangejn) {
			if(vbrestrwdaysrangejn.hasOwnProperty(rk)) {
				var wdrangeinit = vbGetDateObject".$randid."(vbrestrwdaysrangejn[rk][0]);
				if(date >= wdrangeinit) {
					var wdrangeend = vbGetDateObject".$randid."(vbrestrwdaysrangejn[rk][1]);
					if(date <= wdrangeend) {
						if(wd != vbrestrwdaysrangejn[rk][2] && vbrestrwdaysrangejn[rk][3] == 1) {
							return [false];
						}
					}
				}
			}
		}
	}
	if(vbFullObject".$randid."(vbrestrwdays)) {
		if(jQuery.inArray((m+1), vbrestrmonthswdays) == -1 || jQuery.inArray((m+1), vbrestrmultiplyminlos) != -1) {
			return [true];
		}
		if(wd == vbrestrwdays[m]) {
			return [true];
		}
		return [false];
	}
	return [true];
}";
			}
			$document->addScriptDeclaration($resdecl);
		}
		//
		$closing_dates = modVikbooking_horizontalsearchHelper::parseJsClosingDates();
		$sdecl = "
var vbclosingdates = jQuery.parseJSON('".json_encode($closing_dates)."');
function vbCheckClosingDatesmod".$randid."(date) {
	if(!vbIsDayOpenmod".$randid."(date)) {
		return [false];
	}
	return [true];
}
function vbIsDayOpenmod".$randid."(date) {
	if(vbFullObject".$randid."(vbclosingdates)) {
		for (var cd in vbclosingdates) {
			if(vbclosingdates.hasOwnProperty(cd)) {
				var cdfrom = vbGetDateObject".$randid."(vbclosingdates[cd][0]);
				var cdto = vbGetDateObject".$randid."(vbclosingdates[cd][1]);
				if(date >= cdfrom && date <= cdto) {
					return false;
				}
			}
		}
	}
	return true;
}
function vbSetGlobalMinCheckoutDatemod".$randid."() {
	var nowcheckin = jQuery('#checkindatemod".$randid."').datepicker('getDate');
	var nowcheckindate = new Date(nowcheckin.getTime());
	nowcheckindate.setDate(nowcheckindate.getDate() + ".modVikbooking_horizontalsearchHelper::getDefaultNightsCalendar().");
	jQuery('#checkoutdatemod".$randid."').datepicker( 'option', 'minDate', nowcheckindate );
	jQuery('#checkoutdatemod".$randid."').datepicker( 'setDate', nowcheckindate );
}
jQuery(function(){
	jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ '' ] );
	jQuery('#checkindatemod".$randid."').datepicker({
		showOn: 'focus',
		numberOfMonths: 2,".(count($wdaysrestrictions) > 0 || count($wdaysrestrictionsrange) > 0 ? "\nbeforeShowDay: vbIsDayDisabledmod".$randid.",\n" : "\nbeforeShowDay: vbCheckClosingDatesmod".$randid.",\n")."
		onSelect: function( selectedDate ) {
			".($totrestrictions > 0 ? "vbSetMinCheckoutDatemod".$randid."();" : "vbSetGlobalMinCheckoutDatemod".$randid."();")."
					vbCalcNightsMod".$randid."();
		}
	});
	jQuery('#checkindatemod".$randid."').datepicker( 'option', 'dateFormat', '".$juidf."');
	jQuery('#checkindatemod".$randid."').datepicker( 'option', 'minDate', '".modVikbooking_horizontalsearchHelper::getMinDaysAdvance()."d');
	jQuery('#checkindatemod".$randid."').datepicker( 'option', 'maxDate', '".modVikbooking_horizontalsearchHelper::getMaxDateFuture()."');
	jQuery('#checkoutdatemod".$randid."').datepicker({
		showOn: 'focus',
		numberOfMonths: 2,".(count($wdaysrestrictions) > 0 || count($wdaysrestrictionsrange) > 0 ? "\nbeforeShowDay: vbIsDayDisabledCheckoutmod".$randid.",\n" : "\nbeforeShowDay: vbCheckClosingDatesmod".$randid.",\n")."
		onSelect: function( selectedDate ) {
			vbCalcNightsMod".$randid."();
		}
	});
	jQuery('#checkoutdatemod".$randid."').datepicker( 'option', 'dateFormat', '".$juidf."');
	jQuery('#checkoutdatemod".$randid."').datepicker( 'option', 'minDate', '".modVikbooking_horizontalsearchHelper::getMinDaysAdvance()."d');
	jQuery('#checkoutdatemod".$randid."').datepicker( 'option', 'maxDate', '".modVikbooking_horizontalsearchHelper::getMaxDateFuture()."');
	jQuery('#checkindatemod".$randid."').datepicker( 'option', jQuery.datepicker.regional[ 'vikbookingmod' ] );
	jQuery('#checkoutdatemod".$randid."').datepicker( 'option', jQuery.datepicker.regional[ 'vikbookingmod' ] );
	jQuery('.vb-cal-img').click(function(){
		var jdp = jQuery(this).prev('input.hasDatepicker');
		if(jdp.length) {
			jdp.focus();
		}
	});
});";
		$document->addScriptDeclaration($sdecl);
		?>
		<div class="vbmodhorsearchcheckindiv"><label for="checkindatemod<?php echo $randid; ?>"><?php echo JText::_('VBMCHECKIN'); ?>:</label><div class="input-group"><input type="text" name="checkindate" id="checkindatemod<?php echo $randid; ?>" size="10"/><span class="vb-cal-img"></span><input type="hidden" name="checkinh" value="<?php echo $hcheckin; ?>"/><input type="hidden" name="checkinm" value="<?php echo $mcheckin; ?>"/></div></div>
		<div class="vbmodhorsearchcheckoutdiv"><label for="checkoutdatemod<?php echo $randid; ?>"><?php echo JText::_('VBMCHECKOUT'); ?>:</label><div class="input-group"><input type="text" name="checkoutdate" id="checkoutdatemod<?php echo $randid; ?>" size="10"/><span class="vb-cal-img"></span><input type="hidden" name="checkouth" value="<?php echo $hcheckout; ?>"/><input type="hidden" name="checkoutm" value="<?php echo $mcheckout; ?>"/></div></div>
		<?php
	}else {
		//default Joomla Calendar
		JHTML::_('behavior.calendar');
		?>
		<div class="vbmodhorsearchcheckindiv"><label for="checkindatemod<?php echo $randid; ?>"><?php echo JText::_('VBMCHECKIN'); ?>:</label><?php echo JHTML::_('calendar', '', 'checkindate', 'checkindatemod'.$randid, $vbdateformat, array ('class' => '','size' => '10','maxlength' => '19')); ?><input type="hidden" name="checkinh" value="<?php echo $hcheckin; ?>"/><input type="hidden" name="checkinm" value="<?php echo $mcheckin; ?>"/></div>
		<div class="vbmodhorsearchcheckoutdiv"><label for="checkoutdatemod<?php echo $randid; ?>"><?php echo JText::_('VBMCHECKOUT'); ?>:</label><?php JHTML::_('calendar', '', 'checkoutdate', 'checkoutdatemod'.$randid, $vbdateformat, array ('class' => '','size' => '10','maxlength' => '19')) ?><input type="hidden" name="checkouth" value="<?php echo $hcheckout; ?>"/><input type="hidden" name="checkoutm" value="<?php echo $mcheckout; ?>"/></div>
		<?php
	}
	//
	//rooms, adults, children
	$showchildren = modVikbooking_horizontalsearchHelper::showChildrenFront();
	//max number of rooms
	$maxsearchnumrooms = modVikbooking_horizontalsearchHelper::getSearchNumRooms();
	if (intval($maxsearchnumrooms) > 1) {
		$roomsel = "<span class=\"vbhsrnselsp\"><select name=\"roomsnum\" onchange=\"vbSetRoomsAdultsMod".$randid."(this.value);\">\n";
		for($r = 1; $r <= intval($maxsearchnumrooms); $r++) {
			$roomsel .= "<option value=\"".$r."\">".$r."</option>\n";
		}
		$roomsel .= "</select></span>\n";
	}else {
		$roomsel = "<input type=\"hidden\" name=\"roomsnum\" value=\"1\">\n";
	}
	//
	//max number of adults per room
	$globnumadults = modVikbooking_horizontalsearchHelper::getSearchNumAdults();
	$adultsparts = explode('-', $globnumadults);
	$adultsel = "<select name=\"adults[]\">";
	$def_adults = intval($params->get('defadults')) > 1 ? intval($params->get('defadults')) : 0;
	for($a = $adultsparts[0]; $a <= $adultsparts[1]; $a++) {
		$adultsel .= "<option value=\"".$a."\"".(($def_adults > 1 && $a == $def_adults) || (intval($adultsparts[0]) < 1 && $a == 1) ? " selected=\"selected\"" : "").">".$a."</option>";
	}
	$adultsel .= "</select>";
	//
	//max number of children per room
	$globnumchildren = modVikbooking_horizontalsearchHelper::getSearchNumChildren();
	$childrenparts = explode('-', $globnumchildren);
	$childrensel = "<select name=\"children[]\">";
	for($c = $childrenparts[0]; $c <= $childrenparts[1]; $c++) {
		$childrensel .= "<option value=\"".$c."\">".$c."</option>";
	}
	$childrensel .= "</select>";
	//
	?>
	<div class="vbmodhorsearchrac">
	
		<div class="vbmodhorsearchroomsel"><?php if(intval($maxsearchnumrooms) > 1): ?><label for="vbmodformroomsn"><?php echo JText::_('VBMFORMROOMSN'); ?>:</label><?php endif; ?><?php echo $roomsel; ?></div>
		
		<div class="vbmodhorsearchroomdentr">
			
			<div class="vbmodhorsearchroomdentrfirst">
				<?php if(intval($maxsearchnumrooms) > 1): ?><span class="horsrnum"><?php echo JText::_('VBMFORMNUMROOM'); ?> 1</span><?php endif; ?>
				<div class="horsanumdiv"><span class="horsanumlb"><?php echo JText::_('VBMFORMADULTS'); ?></span><span class="horsanumsel"><?php echo $adultsel; ?></span></div>
				<?php if($showchildren): ?><div class="horscnumdiv"><span class="horscnumlb"><?php echo JText::_('VBFORMCHILDREN'); ?></span><span class="horscnumsel"><?php echo $childrensel; ?></span></div><?php endif; ?>
			</div>
			
			<div class="vbmoreroomscontmod" id="vbmoreroomscontmod<?php echo $randid; ?>"></div>
			
		</div>
		
	</div>
	
	<div class="vbmodhorsearchtotnights" id="vbjstotnightsmod<?php echo $randid; ?>"></div>
	
	<?php
	//
	if (intval($params->get('showcat')) == 1) {
		$q = "SELECT * FROM `#__vikbooking_categories` ORDER BY `#__vikbooking_categories`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$categories = $dbo->loadAssocList();
			?>
		<div class="vbmodhorsearchcategoriesblock">
			<span class="vbmodhscategories"><?php echo JText::_('VBMROOMCAT'); ?>:</span>
			<span class="vbhsrcselsp">
			<select name="categories">
				<option value="all"><?php echo JText::_('VBMALLCAT'); ?></option>
			<?php
			foreach ($categories as $cat) {
				?>
				<option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
				<?php
			}
			?>
			</select>
			</span>
		</div>
			<?php
		}
	}
	?>

		<div class="vbmodhorsearchbookdiv">
			<input type="submit" name="search" value="<?php echo (strlen($params->get('srchbtntext')) > 0 ? $params->get('srchbtntext') : JText::_('SEARCHD')); ?>" class="vbsearchinputmodhors"/>
		</div>
	
	</form>
	</div>
</div>

	<script type="text/javascript">
	/* <![CDATA[ */
	function vbAddElementMod<?php echo $randid; ?>() {
		var ni = document.getElementById('vbmoreroomscontmod<?php echo $randid; ?>');
		var numi = document.getElementById('vbroomhelpermod<?php echo $randid; ?>');
		var num = (document.getElementById('vbroomhelpermod<?php echo $randid; ?>').value -1)+ 2;
		numi.value = num;
		var newdiv = document.createElement('div');
		var divIdName = 'vb'+num+'racont';
		newdiv.setAttribute('id',divIdName);
		newdiv.innerHTML = '<div class=\'vbmodhorsearchroomdentr\'><span class=\'horsrnum\'><?php echo JText::_('VBMFORMNUMROOM'); ?> '+ num +'</span><div class=\'horsanumdiv\'><span class=\'horsanumsel\'><?php echo addslashes(str_replace('"', "'", $adultsel)); ?></span><?php echo ($showchildren ? "<div class=\'horscnumdiv\'><span class=\'horscnumsel\'>".addslashes(str_replace('"', "'", $childrensel))."</span></div" : ""); ?></div>';
		ni.appendChild(newdiv);
	}
	function vbSetRoomsAdultsMod<?php echo $randid; ?>(totrooms) {
		var actrooms = parseInt(document.getElementById('vbroomhelpermod<?php echo $randid; ?>').value);
		var torooms = parseInt(totrooms);
		var difrooms;
		if(torooms > actrooms) {
			difrooms = torooms - actrooms;
			for(var ir=1; ir<=difrooms; ir++) {
				vbAddElementMod<?php echo $randid; ?>();
			}
		}
		if(torooms < actrooms) {
			for(var ir=actrooms; ir>torooms; ir--) {
				if(ir > 1) {
					var rmra = document.getElementById('vb' + ir + 'racont');
					rmra.parentNode.removeChild(rmra);
				}
			}
			document.getElementById('vbroomhelpermod<?php echo $randid; ?>').value = torooms;
		}
	}
	function vbCalcNightsMod<?php echo $randid; ?>() {
		var vbcheckin = document.getElementById('checkindatemod<?php echo $randid; ?>').value;
		var vbcheckout = document.getElementById('checkoutdatemod<?php echo $randid; ?>').value;
		if(vbcheckin.length > 0 && vbcheckout.length > 0) {
			var vbcheckinp = vbcheckin.split("/");
			var vbcheckoutp = vbcheckout.split("/");
		<?php
		if ($vbdateformat == "%d/%m/%Y") {
			?>
			var vbinmonth = parseInt(vbcheckinp[1]);
			vbinmonth = vbinmonth - 1;
			var vbinday = parseInt(vbcheckinp[0], 10);
			var vbcheckind = new Date(vbcheckinp[2], vbinmonth, vbinday);
			var vboutmonth = parseInt(vbcheckoutp[1]);
			vboutmonth = vboutmonth - 1;
			var vboutday = parseInt(vbcheckoutp[0], 10);
			var vbcheckoutd = new Date(vbcheckoutp[2], vboutmonth, vboutday);
			<?php
		}elseif ($vbdateformat == "%m/%d/%Y") {
			?>
			var vbinmonth = parseInt(vbcheckinp[0]);
			vbinmonth = vbinmonth - 1;
			var vbinday = parseInt(vbcheckinp[1], 10);
			var vbcheckind = new Date(vbcheckinp[2], vbinmonth, vbinday);
			var vboutmonth = parseInt(vbcheckoutp[0]);
			vboutmonth = vboutmonth - 1;
			var vboutday = parseInt(vbcheckoutp[1], 10);
			var vbcheckoutd = new Date(vbcheckoutp[2], vboutmonth, vboutday);
			<?php
		}else {
			?>
			var vbinmonth = parseInt(vbcheckinp[1]);
			vbinmonth = vbinmonth - 1;
			var vbinday = parseInt(vbcheckinp[2], 10);
			var vbcheckind = new Date(vbcheckinp[0], vbinmonth, vbinday);
			var vboutmonth = parseInt(vbcheckoutp[1]);
			vboutmonth = vboutmonth - 1;
			var vboutday = parseInt(vbcheckoutp[2], 10);
			var vbcheckoutd = new Date(vbcheckoutp[0], vboutmonth, vboutday);
			<?php
		}
		?>
			var vbdivider = 1000 * 60 * 60 * 24;
			var vbints = vbcheckind.getTime();
			var vboutts = vbcheckoutd.getTime();
			if(vboutts > vbints) {
				//var vbnights = Math.ceil((vboutts - vbints) / (vbdivider));
				var utc1 = Date.UTC(vbcheckind.getFullYear(), vbcheckind.getMonth(), vbcheckind.getDate());
				var utc2 = Date.UTC(vbcheckoutd.getFullYear(), vbcheckoutd.getMonth(), vbcheckoutd.getDate());
				var vbnights = Math.ceil((utc2 - utc1) / vbdivider);
				if(vbnights > 0) {
					document.getElementById('vbjstotnightsmod<?php echo $randid; ?>').innerHTML = '<?php echo addslashes(JText::_('VBMJSTOTNIGHTS')); ?>: '+vbnights;
				}else {
					document.getElementById('vbjstotnightsmod<?php echo $randid; ?>').innerHTML = '';
				}
			}else {
				document.getElementById('vbjstotnightsmod<?php echo $randid; ?>').innerHTML = '';
			}
		}else {
			document.getElementById('vbjstotnightsmod<?php echo $randid; ?>').innerHTML = '';
		}
	}
	/* ]]> */
	</script>
	<input type="hidden" id="vbroomhelpermod<?php echo $randid; ?>" value="1"/>
