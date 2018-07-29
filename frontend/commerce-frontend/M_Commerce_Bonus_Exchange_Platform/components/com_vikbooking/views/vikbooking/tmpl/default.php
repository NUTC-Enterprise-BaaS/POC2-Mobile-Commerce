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

if (vikbooking::allowBooking()) {
	$session = JFactory::getSession();
	$dbo = JFactory::getDBO();
	$vbo_tn = vikbooking::getTranslator();
	//vikbooking 1.1
	$calendartype = vikbooking::calendarType();
	$document = JFactory::getDocument();
	//load jQuery lib e jQuery UI
	if(vikbooking::loadJquery()) {
		JHtml::_('jquery.framework', true, true);
		JHtml::_('script', JURI::root().'components/com_vikbooking/resources/jquery-1.11.3.min.js', false, true, false, false);
	}
	if($calendartype == "jqueryui") {
		$document->addStyleSheet(JURI::root().'components/com_vikbooking/resources/jquery-ui.min.css');
		//load jQuery UI
		JHtml::_('script', JURI::root().'components/com_vikbooking/resources/jquery-ui.min.js', false, true, false, false);
	}
	//
	//vikbooking 1.2
	$restrictions = vikbooking::loadRestrictions();
	//
	$pcheckin = JRequest::getInt('checkin', '', 'request');
	$pcheckout = JRequest::getInt('checkout', '', 'request');
	$pitemid = JRequest::getInt('Itemid', '', 'request');
	$pval = "";
	$rval = "";
	$vbdateformat = vikbooking::getDateFormat();
	if ($vbdateformat == "%d/%m/%Y") {
		$df = 'd/m/Y';
	} elseif ($vbdateformat == "%m/%d/%Y") {
		$df = 'm/d/Y';
	} else {
		$df = 'Y/m/d';
	}
	if (!empty ($pcheckin)) {
		$dp = date($df, $pcheckin);
		if (vikbooking::dateIsValid($dp)) {
			$pval = $dp;
		}
	}
	if (!empty ($pcheckout)) {
		$dr = date($df, $pcheckout);
		if (vikbooking::dateIsValid($dr)) {
			$rval = $dr;
		}
	}
	$selform = "<div class=\"vbdivsearch vbo-search-mainview\"><form action=\"".JRoute::_('index.php?option=com_vikbooking')."\" method=\"get\"><div class=\"vb-search-inner\">\n";
	$selform .= "<input type=\"hidden\" name=\"option\" value=\"com_vikbooking\"/>\n";
	$selform .= "<input type=\"hidden\" name=\"task\" value=\"search\"/>\n";
	
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
	
	//vikbooking 1.1
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
	$.datepicker.regional["vikbooking"] = {'."\n".'
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
		firstDay: '.vikbooking::getFirstWeekDay().','."\n".'
		isRTL: false,'."\n".'
		showMonthAfterYear: false,'."\n".'
		yearSuffix: ""'."\n".'
	};'."\n".'
	$.datepicker.setDefaults($.datepicker.regional["vikbooking"]);'."\n".'
});
function vbGetDateObject(dstring) {
	var dparts = dstring.split("-");
	return new Date(dparts[0], (parseInt(dparts[1]) - 1), parseInt(dparts[2]), 0, 0, 0, 0);
}
function vbFullObject(obj) {
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
							$monthscomborestr[($rmonth - 1)] = vikbooking::parseJsDrangeWdayCombo($restr);
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
							$wdaysrestrictionsrange[$kr][5] = vikbooking::parseJsDrangeWdayCombo($drestr);
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
function vbRefreshCheckout(darrive) {
	if(vbFullObject(vbcombowdays)) {
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
				jQuery('#checkoutdate').datepicker( 'option', 'minDate', vbfirstnextd );
				jQuery('#checkoutdate').datepicker( 'setDate', vbfirstnextd );
				break;
			}
		}
	}
}
function vbSetMinCheckoutDate () {
	var minlos = ".vikbooking::getDefaultNightsCalendar().";
	var maxlosrange = 0;
	var nowcheckin = jQuery('#checkindate').datepicker('getDate');
	var nowd = nowcheckin.getDay();
	var nowcheckindate = new Date(nowcheckin.getTime());
	vbcombowdays = {};
	if(vbFullObject(vbrestrminlosrangejn)) {
		for (var rk in vbrestrminlosrangejn) {
			if(vbrestrminlosrangejn.hasOwnProperty(rk)) {
				var minldrangeinit = vbGetDateObject(vbrestrminlosrangejn[rk][0]);
				if(nowcheckindate >= minldrangeinit) {
					var minldrangeend = vbGetDateObject(vbrestrminlosrangejn[rk][1]);
					if(nowcheckindate <= minldrangeend) {
						minlos = parseInt(vbrestrminlosrangejn[rk][2]);
						if(vbFullObject(vbrestrmaxlosrangejn)) {
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
	if(vbFullObject(vbrestrmonthscombojn) && vbrestrmonthscombojn.hasOwnProperty(nowm)) {
		if(nowd in vbrestrmonthscombojn[nowm]) {
			vbcombowdays = vbrestrmonthscombojn[nowm][nowd];
		}
	}
	if(jQuery.inArray((nowm + 1), vbrestrmonths) != -1) {
		minlos = parseInt(vbrestrminlos[nowm]);
	}
	nowcheckindate.setDate(nowcheckindate.getDate() + minlos);
	jQuery('#checkoutdate').datepicker( 'option', 'minDate', nowcheckindate );
	if(maxlosrange > 0) {
		var diffmaxminlos = maxlosrange - minlos;
		var maxcheckoutdate = new Date(nowcheckindate.getTime());
		maxcheckoutdate.setDate(maxcheckoutdate.getDate() + diffmaxminlos);
		jQuery('#checkoutdate').datepicker( 'option', 'maxDate', maxcheckoutdate );
	}
	if(nowm in vbrestrmaxlos) {
		var diffmaxminlos = parseInt(vbrestrmaxlos[nowm]) - minlos;
		var maxcheckoutdate = new Date(nowcheckindate.getTime());
		maxcheckoutdate.setDate(maxcheckoutdate.getDate() + diffmaxminlos);
		jQuery('#checkoutdate').datepicker( 'option', 'maxDate', maxcheckoutdate );
	}
	if(!vbFullObject(vbcombowdays)) {
		jQuery('#checkoutdate').datepicker( 'setDate', nowcheckindate );
	}else {
		vbRefreshCheckout(nowcheckin);
	}
}";
			
			if(count($wdaysrestrictions) > 0 || count($wdaysrestrictionsrange) > 0) {
				$resdecl .= "
var vbrestrwdays = {".implode(", ", $wdaysrestrictions)."};
var vbrestrwdaystwo = {".implode(", ", $wdaystworestrictions)."};
function vbIsDayDisabled(date) {
	if(!vbIsDayOpen(date)) {
		return [false];
	}
	var m = date.getMonth(), wd = date.getDay();
	if(vbFullObject(vbrestrwdaysrangejn)) {
		for (var rk in vbrestrwdaysrangejn) {
			if(vbrestrwdaysrangejn.hasOwnProperty(rk)) {
				var wdrangeinit = vbGetDateObject(vbrestrwdaysrangejn[rk][0]);
				if(date >= wdrangeinit) {
					var wdrangeend = vbGetDateObject(vbrestrwdaysrangejn[rk][1]);
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
	if(vbFullObject(vbrestrwdays)) {
		if(jQuery.inArray((m+1), vbrestrmonthswdays) == -1) {
			return [true];
		}
		if(wd == vbrestrwdays[m]) {
			return [true];
		}
		if(vbFullObject(vbrestrwdaystwo)) {
			if(wd == vbrestrwdaystwo[m]) {
				return [true];
			}
		}
		return [false];
	}
	return [true];
}
function vbIsDayDisabledCheckout(date) {
	if(!vbIsDayOpen(date)) {
		return [false];
	}
	var m = date.getMonth(), wd = date.getDay();
	if(vbFullObject(vbcombowdays)) {
		if(jQuery.inArray(wd, vbcombowdays) != -1) {
			return [true];
		}else {
			return [false];
		}
	}
	if(vbFullObject(vbrestrwdaysrangejn)) {
		for (var rk in vbrestrwdaysrangejn) {
			if(vbrestrwdaysrangejn.hasOwnProperty(rk)) {
				var wdrangeinit = vbGetDateObject(vbrestrwdaysrangejn[rk][0]);
				if(date >= wdrangeinit) {
					var wdrangeend = vbGetDateObject(vbrestrwdaysrangejn[rk][1]);
					if(date <= wdrangeend) {
						if(wd != vbrestrwdaysrangejn[rk][2] && vbrestrwdaysrangejn[rk][3] == 1) {
							return [false];
						}
					}
				}
			}
		}
	}
	if(vbFullObject(vbrestrwdays)) {
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
		$closing_dates = vikbooking::parseJsClosingDates();
		$sdecl = "
var vbclosingdates = jQuery.parseJSON('".json_encode($closing_dates)."');
function vbCheckClosingDates(date) {
	if(!vbIsDayOpen(date)) {
		return [false];
	}
	return [true];
}
function vbIsDayOpen(date) {
	if(vbFullObject(vbclosingdates)) {
		for (var cd in vbclosingdates) {
			if(vbclosingdates.hasOwnProperty(cd)) {
				var cdfrom = vbGetDateObject(vbclosingdates[cd][0]);
				var cdto = vbGetDateObject(vbclosingdates[cd][1]);
				if(date >= cdfrom && date <= cdto) {
					return false;
				}
			}
		}
	}
	return true;
}
function vbSetGlobalMinCheckoutDate() {
	var nowcheckin = jQuery('#checkindate').datepicker('getDate');
	var nowcheckindate = new Date(nowcheckin.getTime());
	nowcheckindate.setDate(nowcheckindate.getDate() + ".vikbooking::getDefaultNightsCalendar().");
	jQuery('#checkoutdate').datepicker( 'option', 'minDate', nowcheckindate );
	jQuery('#checkoutdate').datepicker( 'setDate', nowcheckindate );
}
jQuery(function(){
	jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ '' ] );
	jQuery('#checkindate').datepicker({
		showOn: 'focus',
		numberOfMonths: 2,".(count($wdaysrestrictions) > 0 || count($wdaysrestrictionsrange) > 0 ? "\nbeforeShowDay: vbIsDayDisabled,\n" : "\nbeforeShowDay: vbCheckClosingDates,\n")."
		onSelect: function( selectedDate ) {
			".($totrestrictions > 0 ? "vbSetMinCheckoutDate();" : "vbSetGlobalMinCheckoutDate();")."
			vbCalcNights();
		}
	});
	jQuery('#checkindate').datepicker( 'option', 'dateFormat', '".$juidf."');
	jQuery('#checkindate').datepicker( 'option', 'minDate', '".vikbooking::getMinDaysAdvance()."d');
	jQuery('#checkindate').datepicker( 'option', 'maxDate', '".vikbooking::getMaxDateFuture()."');
	jQuery('#checkoutdate').datepicker({
		showOn: 'focus',
		numberOfMonths: 2,".(count($wdaysrestrictions) > 0 || count($wdaysrestrictionsrange) > 0 ? "\nbeforeShowDay: vbIsDayDisabledCheckout,\n" : "\nbeforeShowDay: vbCheckClosingDates,\n")."
		onSelect: function( selectedDate ) {
			vbCalcNights();
		}
	});
	jQuery('#checkoutdate').datepicker( 'option', 'dateFormat', '".$juidf."');
	jQuery('#checkoutdate').datepicker( 'option', 'minDate', '".vikbooking::getMinDaysAdvance()."d');
	jQuery('#checkoutdate').datepicker( 'option', 'maxDate', '".vikbooking::getMaxDateFuture()."');
	jQuery('#checkindate').datepicker( 'option', jQuery.datepicker.regional[ 'vikbooking' ] );
	jQuery('#checkoutdate').datepicker( 'option', jQuery.datepicker.regional[ 'vikbooking' ] );
	jQuery('.vb-cal-img').click(function(){
		var jdp = jQuery(this).prev('input.hasDatepicker');
		if(jdp.length) {
			jdp.focus();
		}
	});
});";
		$document->addScriptDeclaration($sdecl);
		$selform .= "<div class=\"vbo-search-inpblock vbo-search-inpblock-checkin\"><label for=\"checkindate\">" . JText::_('VBPICKUPROOM') . "</label><div class=\"input-group\"><input type=\"text\" name=\"checkindate\" id=\"checkindate\" size=\"10\" autocomplete=\"off\"/><span class=\"vb-cal-img\"></span></div><input type=\"hidden\" name=\"checkinh\" value=\"".$hcheckin."\"/><input type=\"hidden\" name=\"checkinm\" value=\"".$mcheckin."\"/></div>\n";
		$selform .= "<div class=\"vbo-search-inpblock vbo-search-inpblock-checkout\"><label for=\"checkoutdate\">" . JText::_('VBRETURNROOM') . "</label><div class=\"input-group\"><input type=\"text\" name=\"checkoutdate\" id=\"checkoutdate\" size=\"10\" autocomplete=\"off\"/><span class=\"vb-cal-img\"></span></div><input type=\"hidden\" name=\"checkouth\" value=\"".$hcheckout."\"/><input type=\"hidden\" name=\"checkoutm\" value=\"".$mcheckout."\"/></div>\n";
	}else {
		//default Joomla Calendar
		JHTML::_('behavior.calendar');
		$selform .= "<div class=\"vbo-search-inpblock vbo-search-inpblock-checkin\"><label for=\"checkindate\">" . JText::_('VBPICKUPROOM') . "</label><div class=\"input-group\">" . JHTML::_('calendar', '', 'checkindate', 'checkindate', $vbdateformat, array ('class' => '','size' => '10','maxlength' => '19'));
		$selform .= "<input type=\"hidden\" name=\"checkinh\" value=\"".$hcheckin."\"/><input type=\"hidden\" name=\"checkinm\" value=\"".$mcheckin."\"/></div></div>\n";
		$selform .= "<div class=\"vbo-search-inpblock vbo-search-inpblock-checkout\"><label for=\"checkoutdate\">" . JText::_('VBRETURNROOM') . "</label><div class=\"input-group\">" . JHTML::_('calendar', '', 'checkoutdate', 'checkoutdate', $vbdateformat, array ('class' => '','size' => '10','maxlength' => '19')); 
		$selform .= "<input type=\"hidden\" name=\"checkouth\" value=\"".$hcheckout."\"/><input type=\"hidden\" name=\"checkoutm\" value=\"".$mcheckout."\"/></div></div>\n";
	}
	//
	//rooms, adults, children
	$showchildren = vikbooking::showChildrenFront();
	//max number of rooms
	$maxsearchnumrooms = vikbooking::getSearchNumRooms();
	if (intval($maxsearchnumrooms) > 1) {
		$roomsel = "<span>".JText::_('VBFORMROOMSN')."</span><select name=\"roomsnum\" onchange=\"vbSetRoomsAdults(this.value);\">\n";
		for($r = 1; $r <= $maxsearchnumrooms; $r++) {
			$roomsel .= "<option value=\"".$r."\">".$r."</option>\n";
		}
		$roomsel .= "</select>\n";
	}else {
		$roomsel = "<input type=\"hidden\" name=\"roomsnum\" value=\"1\">\n";
	}
	//
	//max number of adults per room
	$globnumadults = vikbooking::getSearchNumAdults();
	$adultsparts = explode('-', $globnumadults);
	$adultsel = "<select name=\"adults[]\">";
	for($a = $adultsparts[0]; $a <= $adultsparts[1]; $a++) {
		$adultsel .= "<option value=\"".$a."\"".(intval($adultsparts[0]) < 1 && $a == 1 ? " selected=\"selected\"" : "").">".$a."</option>";
	}
	$adultsel .= "</select>";
	//
	//max number of children per room
	$globnumchildren = vikbooking::getSearchNumChildren();
	$childrenparts = explode('-', $globnumchildren);
	$childrensel = "<select name=\"children[]\">";
	for($c = $childrenparts[0]; $c <= $childrenparts[1]; $c++) {
		$childrensel .= "<option value=\"".$c."\">".$c."</option>";
	}
	$childrensel .= "</select>";
	//
	$inpsubclass = vikbooking::getSubmitClass();
	$selform .= "<div class=\"vbo-search-num-racblock\">\n";
	$selform .= "	<div class=\"vbo-search-num-rooms\">".$roomsel."</div>\n";
	$selform .= "	<div class=\"vbo-search-num-aduchild-block\" id=\"vbo-search-num-aduchild-block\">\n";
	$selform .= "		<div class=\"vbo-search-num-aduchild-entry\">".(intval($maxsearchnumrooms) > 1 ? "<span class=\"vbo-search-roomnum\">".JText::_('VBFORMNUMROOM')." 1</span>" : "")."\n";
	$selform .= "			<div class=\"vbo-search-num-adults-entry\"><span class=\"vbo-search-num-adults-entry-label\">".JText::_('VBFORMADULTS')."</span><span class=\"vbo-search-num-adults-entry-inp\">".$adultsel."</span></div>\n";
	if($showchildren) {
		$selform .= "		<div class=\"vbo-search-num-children-entry\"><span class=\"vbo-search-num-children-entry-label\">".JText::_('VBFORMCHILDREN')."</span><span class=\"vbo-search-num-children-entry-inp\">".$childrensel."</span></div>\n";
	}
	$selform .= "		</div>\n";
	$selform .= "	</div>\n";
	//the tag <div id=\"vbjstotnights\"></div> will be used by javascript to calculate the nights
	$selform .= "	<div id=\"vbjstotnights\"></div>\n";
	$selform .= "</div>\n";
	if (vikbooking::showCategoriesFront()) {
		$q = "SELECT * FROM `#__vikbooking_categories` ORDER BY `#__vikbooking_categories`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$categories = $dbo->loadAssocList();
			$vbo_tn->translateContents($categories, '#__vikbooking_categories');
			$selform .= "<div class=\"vbo-search-categories\"><span class=\"vbformcategories\">" . JText::_('VBROOMCAT') . "</span><select name=\"categories\">";
			$selform .= "<option value=\"all\">" . JText::_('VBALLCAT') . "</option>\n";
			foreach ($categories as $cat) {
				$selform .= "<option value=\"" . $cat['id'] . "\">" . $cat['name'] . "</option>\n";
			}
			$selform .= "</select></div>\n";
		}
	}
	$selform .= "<div class=\"vbo-search-submit\"><input type=\"submit\" name=\"search\" value=\"" . vikbooking::getSubmitName() . "\" class=\"btn".(strlen($inpsubclass) > 0 ? " ".$inpsubclass : "")."\"/></div>\n";
	$selform .= "</div>\n";
	$selform .= (!empty ($pitemid) ? "<input type=\"hidden\" name=\"Itemid\" value=\"" . $pitemid . "\"/>" : "") . "</form></div>";

	?>
	<script type="text/javascript">
	/* <![CDATA[ */
	function vbAddElement() {
		var ni = document.getElementById('vbo-search-num-aduchild-block');
		var numi = document.getElementById('vbroomhelper');
		var num = (document.getElementById('vbroomhelper').value -1)+ 2;
		numi.value = num;
		var newdiv = document.createElement('div');
		var divIdName = 'vb'+num+'racont';
		newdiv.setAttribute('id',divIdName);
		newdiv.innerHTML = '<div class=\'vbo-search-num-aduchild-entry\'><span class=\'vbo-search-roomnum\'><?php echo addslashes(JText::_('VBFORMNUMROOM')); ?> '+ num +'</span><div class=\'vbo-search-num-adults-entry\'><span class=\'vbo-search-num-adults-entry-label\'><?php echo addslashes(JText::_('VBFORMADULTS')); ?></span><span class=\'vbo-search-num-adults-entry-inp\'><?php echo addslashes(str_replace('"', "'", $adultsel)); ?></span></div><?php if($showchildren): ?><div class=\'vbo-search-num-children-entry\'><span class=\'vbo-search-num-children-entry-label\'><?php echo addslashes(JText::_('VBFORMCHILDREN')); ?></span><span class=\'vbo-search-num-adults-entry-inp\'><?php echo addslashes(str_replace('"', "'", $childrensel)); ?></span></div><?php endif; ?></div>';
		ni.appendChild(newdiv);
	}
	function vbSetRoomsAdults(totrooms) {
		var actrooms = parseInt(document.getElementById('vbroomhelper').value);
		var torooms = parseInt(totrooms);
		var difrooms;
		if(torooms > actrooms) {
			difrooms = torooms - actrooms;
			for(var ir=1; ir<=difrooms; ir++) {
				vbAddElement();
			}
		}
		if(torooms < actrooms) {
			for(var ir=actrooms; ir>torooms; ir--) {
				if(ir > 1) {
					var rmra = document.getElementById('vb' + ir + 'racont');
					rmra.parentNode.removeChild(rmra);
				}
			}
			document.getElementById('vbroomhelper').value = torooms;
		}
	}
	function vbCalcNights() {
		var vbcheckin = document.getElementById('checkindate').value;
		var vbcheckout = document.getElementById('checkoutdate').value;
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
					document.getElementById('vbjstotnights').innerHTML = '<?php echo addslashes(JText::_('VBJSTOTNIGHTS')); ?>: '+vbnights;
				}else {
					document.getElementById('vbjstotnights').innerHTML = '';
				}
			}else {
				document.getElementById('vbjstotnights').innerHTML = '';
			}
		}else {
			document.getElementById('vbjstotnights').innerHTML = '';
		}
	}
	/* ]]> */
	</script>
	<input type="hidden" id="vbroomhelper" value="1"/>
	<?php
	
	echo vikbooking::getFullFrontTitle();
	echo vikbooking::getIntroMain();
	
	echo $selform;
	
	echo vikbooking::getClosingMain();
	//echo javascript to fill the date values
	if (!empty ($pval) && !empty ($rval)) {
		if($calendartype == "jqueryui") {
			?>
			<script language="JavaScript" type="text/javascript">
			jQuery.noConflict();
			jQuery(function(){
				jQuery('#checkindate').val('<?php echo $pval; ?>');
				jQuery('#checkoutdate').val('<?php echo $rval; ?>');
			});
			</script>
			<?php
		}else {
			?>
			<script language="JavaScript" type="text/javascript">
			document.getElementById('checkindate').value='<?php echo $pval; ?>';
			document.getElementById('checkoutdate').value='<?php echo $rval; ?>';
			</script>
			<?php
		}
	}
	//
} else {
	echo vikbooking::getDisabledBookingMsg();
}
?>