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

if (!function_exists('showSelectVb')) {
	function showSelectVb($err) {
		if (vikbooking::allowBooking()) {
			$session = JFactory::getSession();
			$dbo = JFactory::getDBO();
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
			$oldroomsnum = $session->get('vbroomsnum', '');
			$oldarrpeople = $session->get('vbarrpeople', '');
			//
			$pcheckin = JRequest::getInt('checkin', '', 'request');
			$pcheckout = JRequest::getInt('checkout', '', 'request');
			$sesscheckin = $session->get('vbcheckin', '');
			$sesscheckout = $session->get('vbcheckout', '');
			$pitemid = JRequest::getInt('Itemid', '', 'request');
			$pval = "";
			$rval = "";
			$vbdateformat = vikbooking::getDateFormat();
			if ($vbdateformat == "%d/%m/%Y") {
				$df = 'd/m/Y';
			}elseif ($vbdateformat == "%m/%d/%Y") {
				$df = 'm/d/Y';
			} else {
				$df = 'Y/m/d';
			}
			if (!empty($pcheckin) || !empty($sesscheckin)) {
				$pcheckin = !empty($pcheckin) ? $pcheckin : $sesscheckin;
				$dp = date($df, $pcheckin);
				if (vikbooking::dateIsValid($dp)) {
					$pval = $dp;
				}
			}
			if (!empty($pcheckout) || !empty($sesscheckout)) {
				$pcheckout = !empty($pcheckout) ? $pcheckout : $sesscheckout;
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
					$roomsel .= "<option value=\"".$r."\"".(!empty($oldroomsnum) && $oldroomsnum == $r ? " selected=\"selected\"" : "").">".$r."</option>\n";
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
				$adultsel .= "<option value=\"".$a."\"".((is_array($oldarrpeople) && $oldarrpeople[1]['adults'] == $a) || (intval($adultsparts[0]) < 1 && $a == 1) ? " selected=\"selected\"" : "").">".$a."</option>";
			}
			$adultsel .= "</select>";
			//
			//max number of children per room
			$globnumchildren = vikbooking::getSearchNumChildren();
			$childrenparts = explode('-', $globnumchildren);
			$childrensel = "<select name=\"children[]\">";
			for($c = $childrenparts[0]; $c <= $childrenparts[1]; $c++) {
				$childrensel .= "<option value=\"".$c."\"".(is_array($oldarrpeople) && $oldarrpeople[1]['children'] == $c ? " selected=\"selected\"" : "").">".$c."</option>";
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
			if (strlen($err)) {
				echo "<p class=\"err\">" . $err . "</p>";
			}
			echo $selform;
			echo vikbooking::getClosingMain();
			//echo javascript to fill the date values
			if (!empty ($pval) && !empty ($rval)) {
				if($calendartype == "jqueryui") {
					?>
					<script type="text/javascript">
					jQuery.noConflict();
					jQuery(function(){
						jQuery('#checkindate').val('<?php echo $pval; ?>');
						jQuery('#checkoutdate').val('<?php echo $rval; ?>');
					});
					</script>
					<?php
				}else {
					?>
					<script type="text/javascript">
					document.getElementById('checkindate').value='<?php echo $pval; ?>';
					document.getElementById('checkoutdate').value='<?php echo $rval; ?>';
					</script>
					<?php
				}
			}
			//
			if (!empty($oldroomsnum) && $oldroomsnum > 1 && count($oldarrpeople) > 0) {
				$oldroomscountadults = array();
				$oldroomscountchildren = array();
				for($i = 2; $i <= $oldroomsnum; $i++) {
					$globnumadults = vikbooking::getSearchNumAdults();
					$adultsparts = explode('-', $globnumadults);
					$adultsel = "<select name=\"adults[]\">";
					for($a = $adultsparts[0]; $a <= $adultsparts[1]; $a++) {
						$adultsel .= "<option value=\"".$a."\"".($oldarrpeople[$i]['adults'] == $a ? " selected=\"selected\"" : "").">".$a."</option>";
					}
					$adultsel .= "</select>";
					$oldroomscountadults[$i] = $adultsel;
					$globnumchildren = vikbooking::getSearchNumChildren();
					$childrenparts = explode('-', $globnumchildren);
					$childrensel = "<select name=\"children[]\">";
					for($c = $childrenparts[0]; $c <= $childrenparts[1]; $c++) {
						$childrensel .= "<option value=\"".$c."\"".($oldarrpeople[$i]['children'] == $c ? " selected=\"selected\"" : "").">".$c."</option>";
					}
					$childrensel .= "</select>";
					$oldroomscountchildren[$i] = $childrensel;
				}
				?>
			<script type="text/javascript">
			/* <![CDATA[ */
			function vbAddElementSession() {
				var oldradultsvals = new Array();
				var oldrchildrenvals = new Array();
				<?php
				for($i = 2; $i <= $oldroomsnum; $i++) {
					?>
					oldradultsvals[<?php echo $i; ?>] = "<?php echo addslashes(str_replace('"', "'", $oldroomscountadults[$i])); ?>";
					oldrchildrenvals[<?php echo $i; ?>] = "<?php echo addslashes(str_replace('"', "'", $oldroomscountchildren[$i])); ?>";
					<?php
				}
				?>
				var ni = document.getElementById('vbo-search-num-aduchild-block');
				var numi = document.getElementById('vbroomhelper');
				var num = (document.getElementById('vbroomhelper').value -1)+ 2;
				numi.value = num;
				var newdiv = document.createElement('div');
				var divIdName = 'vb'+num+'racont';
				newdiv.setAttribute('id',divIdName);
				newdiv.innerHTML = '<div class=\'vbo-search-num-aduchild-entry\'><span class=\'vbo-search-roomnum\'><?php echo addslashes(JText::_('VBFORMNUMROOM')); ?> '+ num +'</span><div class=\'vbo-search-num-adults-entry\'><span class=\'vbo-search-num-adults-entry-label\'><?php echo addslashes(JText::_('VBFORMADULTS')); ?></span><span class=\'vbo-search-num-adults-entry-inp\'>'+ oldradultsvals[num] +'</span></div><?php if($showchildren): ?><div class=\'vbo-search-num-children-entry\'><span class=\'vbo-search-num-children-entry-label\'><?php echo addslashes(JText::_('VBFORMCHILDREN')); ?></span><span class=\'vbo-search-num-adults-entry-inp\'>'+ oldrchildrenvals[num] +'</span></div><?php endif; ?></div>';
				ni.appendChild(newdiv);
			}
			function vbSetRoomsAdultsSession(totrooms) {
				var actrooms = parseInt(document.getElementById('vbroomhelper').value);
				var torooms = parseInt(totrooms);
				var difrooms;
				if(torooms > actrooms) {
					difrooms = torooms - actrooms;
					for(var ir=1; ir<=difrooms; ir++) {
						vbAddElementSession();
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
			vbSetRoomsAdultsSession('<?php echo $oldroomsnum; ?>');
			/* ]]> */
			</script>
			<?php
			}
		} else {
			echo vikbooking::getDisabledBookingMsg();
		}
	}
}

class vikbooking {
	
	public static function addJoomlaUser($name, $username, $email, $password) {
		//new method
		jimport('joomla.application.component.helper');
		$params = JComponentHelper::getParams('com_users');
		$user = new JUser;
		$data = array();
		//Get the default new user group, Registered if not specified.
		$system = $params->get('new_usertype', 2);
		$data['groups'] = array();
		$data['groups'][] = $system;
		$data['name']=$name;
		$data['username']=$username;
		$data['email'] = JStringPunycode::emailToPunycode($email);
		$data['password']=$password;
		$data['password2']=$password;
		$data['sendEmail'] = 0; //should the user receive system mails?
		//$data['block'] = 0;
		if (!$user->bind($data)) {
			JError::raiseWarning('', JText::_($user->getError()));
			return false;
		}
		if (!$user->save()) {
			JError::raiseWarning('', JText::_($user->getError()));
			return false;
		}
		return $user->id;
	}
	
	public static function userIsLogged () {
		$user = JFactory::getUser();
		if ($user->guest) {
			return false;
		}else {
			return true;
		}
	}

	public static function prepareViewContent() {
		$menu = JFactory::getApplication()->getMenu()->getActive();
		if( isset($menu->params) ) {
			$document = JFactory::getDocument();
			if( intval($menu->params->get('show_page_heading')) == 1 && strlen($menu->params->get('page_heading')) ) {
				echo '<div class="page-header'.(strlen($clazz = $menu->params->get('pageclass_sfx')) ? ' '.$clazz : '' ).'"><h1>'.$menu->params->get('page_heading').'</h1></div>';
			}
			if( strlen($menu->params->get('menu-meta_description')) ) {
				$document->setDescription($menu->params->get('menu-meta_description'));
			}
			if( strlen($menu->params->get('menu-meta_keywords')) ) {
				$document->setMetadata('keywords', $menu->params->get('menu-meta_keywords'));
			}
			if( strlen($menu->params->get('robots')) ) {
				$document->setMetadata('robots', $menu->params->get('robots'));
			}
		}
	}

	public static function getDefaultDistinctiveFeatures() {
		$features = array();
		$features['VBODEFAULTDISTFEATUREONE'] = '';
		//Below is the default feature for 'Room Code'. One default feature is sufficient
		//$features['VBODEFAULTDISTFEATURETWO'] = '';
		return $features;
	}

	public static function getRoomUnitNumsUnavailable($order, $idroom) {
		$dbo = JFactory::getDBO();
		$unavailable_indexes = array();
		$first = $order['checkin'];
		$second = $order['checkout'];
		$secdiff = $second - $first;
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
				$maxhmore = self::getHoursMoreRb() * 3600;
				if ($maxhmore >= $newdiff) {
					$daysdiff = floor($daysdiff);
				} else {
					$daysdiff = ceil($daysdiff);
				}
			}
		}
		$groupdays = self::getGroupDays($first, $second, $daysdiff);
		$q = "SELECT `b`.`id`,`b`.`checkin`,`b`.`checkout`,`b`.`realback`,`ob`.`idorder`,`ob`.`idbusy`,`or`.`id` AS `or_id`,`or`.`idroom`,`or`.`roomindex`,`o`.`status` ".
			"FROM `#__vikbooking_busy` AS `b` ".
			"LEFT JOIN `#__vikbooking_ordersbusy` `ob` ON `ob`.`idbusy`=`b`.`id` ".
			"LEFT JOIN `#__vikbooking_ordersrooms` `or` ON `or`.`idorder`=`ob`.`idorder` AND `or`.`idorder`!=".(int)$order['id']." ".
			"LEFT JOIN `#__vikbooking_orders` `o` ON `o`.`id`=`or`.`idorder` AND `o`.`id`=`ob`.`idorder` AND `o`.`id`!=".(int)$order['id']." ".
			"WHERE `or`.`idroom`=".(int)$idroom." AND `b`.`checkout` > ".time()." AND `o`.`status`='confirmed' AND `ob`.`idorder`!=".(int)$order['id']." AND `ob`.`idorder` > 0;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$busy = $dbo->loadAssocList();
			foreach ($groupdays as $gday) {
				foreach ($busy as $bu) {
					if(empty($bu['roomindex']) || empty($bu['idorder'])) {
						continue;
					}
					if ($gday >= $bu['checkin'] && $gday <= $bu['realback']) {
						$unavailable_indexes[$bu['or_id']] = $bu['roomindex'];
					}elseif(count($groupdays) == 2 && $gday == $groupdays[0]) {
						if($groupdays[0] < $bu['checkin'] && $groupdays[0] < $bu['realback'] && $groupdays[1] > $bu['checkin'] && $groupdays[1] > $bu['realback']) {
							$unavailable_indexes[$bu['or_id']] = $bu['roomindex'];
						}
					}
				}
			}
		}

		return $unavailable_indexes;
	}

	public static function getRoomUnitNumsAvailable($order, $idroom) {
		$dbo = JFactory::getDBO();
		$unavailable_indexes = array();
		$available_indexes = array();
		$first = $order['checkin'];
		$second = $order['checkout'];
		$secdiff = $second - $first;
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
				$maxhmore = self::getHoursMoreRb() * 3600;
				if ($maxhmore >= $newdiff) {
					$daysdiff = floor($daysdiff);
				} else {
					$daysdiff = ceil($daysdiff);
				}
			}
		}
		$groupdays = self::getGroupDays($first, $second, $daysdiff);
		$q = "SELECT `b`.`id`,`b`.`checkin`,`b`.`checkout`,`b`.`realback`,`ob`.`idorder`,`ob`.`idbusy`,`or`.`id` AS `or_id`,`or`.`idroom`,`or`.`roomindex`,`o`.`status` ".
			"FROM `#__vikbooking_busy` AS `b` ".
			"LEFT JOIN `#__vikbooking_ordersbusy` `ob` ON `ob`.`idbusy`=`b`.`id` ".
			"LEFT JOIN `#__vikbooking_ordersrooms` `or` ON `or`.`idorder`=`ob`.`idorder` AND `or`.`idorder`!=".(int)$order['id']." ".
			"LEFT JOIN `#__vikbooking_orders` `o` ON `o`.`id`=`or`.`idorder` AND `o`.`id`=`ob`.`idorder` AND `o`.`id`!=".(int)$order['id']." ".
			"WHERE `or`.`idroom`=".(int)$idroom." AND `b`.`checkout` > ".time()." AND `o`.`status`='confirmed' AND `ob`.`idorder`!=".(int)$order['id']." AND `ob`.`idorder` > 0;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$busy = $dbo->loadAssocList();
			foreach ($groupdays as $gday) {
				foreach ($busy as $bu) {
					if(empty($bu['roomindex']) || empty($bu['idorder'])) {
						continue;
					}
					if ($gday >= $bu['checkin'] && $gday <= $bu['realback']) {
						$unavailable_indexes[$bu['or_id']] = $bu['roomindex'];
					}elseif(count($groupdays) == 2 && $gday == $groupdays[0]) {
						if($groupdays[0] < $bu['checkin'] && $groupdays[0] < $bu['realback'] && $groupdays[1] > $bu['checkin'] && $groupdays[1] > $bu['realback']) {
							$unavailable_indexes[$bu['or_id']] = $bu['roomindex'];
						}
					}
				}
			}
		}
		$q = "SELECT `params` FROM `#__vikbooking_rooms` WHERE `id`=".(int)$idroom.";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$room_params = $dbo->loadResult();
			$room_params_arr = json_decode($room_params, true);
			if(array_key_exists('features', $room_params_arr) && is_array($room_params_arr['features']) && count($room_params_arr['features'])) {
				foreach ($room_params_arr['features'] as $rind => $rfeatures) {
					if(in_array($rind, $unavailable_indexes)) {
						continue;
					}
					$available_indexes[] = $rind;
				}
			}
		}

		return $available_indexes;
	}
	
	public static function loadRestrictions ($filters = true, $rooms = array()) {
		$restrictions = array();
		$dbo = JFactory::getDBO();
		if(!$filters) {
			$q="SELECT * FROM `#__vikbooking_restrictions`;";
		}else {
			if (count($rooms) == 0) {
				$q="SELECT * FROM `#__vikbooking_restrictions` WHERE `allrooms`=1;";
			}else {
				$clause = array();
				foreach($rooms as $idr) {
					if (empty($idr)) continue;
					$clause[] = "`idrooms` LIKE '%-".intval($idr)."-%'";
				}
				if (count($clause) > 0) {
					$q="SELECT * FROM `#__vikbooking_restrictions` WHERE `allrooms`=1 OR (`allrooms`=0 AND (".implode(" OR ", $clause)."));";
				}else {
					$q="SELECT * FROM `#__vikbooking_restrictions` WHERE `allrooms`=1;";
				}
			}
		}
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$allrestrictions = $dbo->loadAssocList();
			foreach($allrestrictions as $k=>$res) {
				if(!empty($res['month'])) {
					$restrictions[$res['month']] = $res;
				}else {
					$restrictions['range'][$k] = $res;
				}
			}
		}
		return $restrictions;
	}
	
	public static function globalRestrictions ($restrictions) {
		$ret = array();
		if (count($restrictions) > 0) {
			foreach($restrictions as $kr => $rr) {
				if ($kr == 'range') {
					foreach ($rr as $kd => $dr) {
						if ($dr['allrooms'] == 1) {
							$ret['range'][$kd] = $restrictions[$kr][$kd];
						}
					}
				}else {
					if ($rr['allrooms'] == 1) {
						$ret[$kr] = $restrictions[$kr];
					}
				}
			}
		}
		return $ret;
	}

	public static function parseSeasonRestrictions ($first, $second, $daysdiff, $restrictions) {
		$season_restrictions = array();
		$restrcheckin = getdate($first);
		$restrcheckout = getdate($second);
		if (array_key_exists($restrcheckin['mon'], $restrictions)) {
			//restriction found for this month, checking:
			$season_restrictions['id'] = $restrictions[$restrcheckin['mon']]['id'];
			$season_restrictions['name'] = $restrictions[$restrcheckin['mon']]['name'];
			$season_restrictions['allowed'] = true; //set to false when these nights are not allowed
			if (strlen($restrictions[$restrcheckin['mon']]['wday']) > 0) {
				//Week Day Arrival Restriction
				$rvalidwdays = array($restrictions[$restrcheckin['mon']]['wday']);
				if (strlen($restrictions[$restrcheckin['mon']]['wdaytwo']) > 0) {
					$rvalidwdays[] = $restrictions[$restrcheckin['mon']]['wdaytwo'];
				}
				$season_restrictions['wdays'] = $rvalidwdays;
			}
			if (!empty($restrictions[$restrcheckin['mon']]['maxlos']) && $restrictions[$restrcheckin['mon']]['maxlos'] > 0 && $restrictions[$restrcheckin['mon']]['maxlos'] > $restrictions[$restrcheckin['mon']]['minlos']) {
				$season_restrictions['maxlos'] = $restrictions[$restrcheckin['mon']]['maxlos'];
				if ($daysdiff > $restrictions[$restrcheckin['mon']]['maxlos']) {
					$season_restrictions['allowed'] = false;
				}
			}
			if ($daysdiff < $restrictions[$restrcheckin['mon']]['minlos']) {
				$season_restrictions['allowed'] = false;
			}
			$season_restrictions['minlos'] = $restrictions[$restrcheckin['mon']]['minlos'];
		}elseif (array_key_exists('range', $restrictions)) {
			foreach($restrictions['range'] as $restr) {
				if ($restr['dfrom'] <= $first && $restr['dto'] >= $first) {
					//restriction found for this date range, checking:
					$season_restrictions['id'] = $restr['id'];
					$season_restrictions['name'] = $restr['name'];
					$season_restrictions['allowed'] = true; //set to false when these nights are not allowed
					if (strlen($restr['wday']) > 0) {
						//Week Day Arrival Restriction
						$rvalidwdays = array($restr['wday']);
						if (strlen($restr['wdaytwo']) > 0) {
							$rvalidwdays[] = $restr['wdaytwo'];
						}
						$season_restrictions['wdays'] = $rvalidwdays;
					}
					if (!empty($restr['maxlos']) && $restr['maxlos'] > 0 && $restr['maxlos'] > $restr['minlos']) {
						$season_restrictions['maxlos'] = $restr['maxlos'];
						if ($daysdiff > $restr['maxlos']) {
							$season_restrictions['allowed'] = false;
						}
					}
					if ($daysdiff < $restr['minlos']) {
						$season_restrictions['allowed'] = false;
					}
					$season_restrictions['minlos'] = $restr['minlos'];
				}
			}
		}

		return $season_restrictions;
	}

	public static function compareSeasonRestrictionsNights ($restrictions) {
		$base_compare = array();
		$base_nights = 0;
		foreach ($restrictions as $nights => $restr) {
			$base_compare = $restr;
			$base_nights = $nights;
			break;
		}
		foreach ($restrictions as $nights => $restr) {
			if($nights == $base_nights) {
				continue;
			}
			$diff = array_diff($base_compare, $restr);
			if(count($diff) > 0 && array_key_exists('id', $diff)) {
				//return differences only if the Restriction ID is different: ignore allowed, wdays, minlos, maxlos.
				//only one Restriction per time should be applied to certain Season Dates but check just in case.
				return $diff;
			}
		}

		return array();
	}
	
	public static function roomRestrictions ($roomid, $restrictions) {
		$ret = array();
		if (!empty($roomid) && count($restrictions) > 0) {
			foreach($restrictions as $kr => $rr) {
				if ($kr == 'range') {
					foreach ($rr as $kd => $dr) {
						if ($dr['allrooms'] == 0 && !empty($dr['idrooms'])) {
							$allrooms = explode(';', $dr['idrooms']);
							if (in_array('-'.$roomid.'-', $allrooms)) {
								$ret['range'][$kd] = $restrictions[$kr][$kd];
							}
						}
					}
				}else {
					if ($rr['allrooms'] == 0 && !empty($rr['idrooms'])) {
						$allrooms = explode(';', $rr['idrooms']);
						if (in_array('-'.$roomid.'-', $allrooms)) {
							$ret[$kr] = $restrictions[$kr];
						}
					}
				}
			}
		}
		return $ret;
	}
	
	public static function validateRoomRestriction ($roomrestr, $restrcheckin, $restrcheckout, $daysdiff) {
		$restrictionerrmsg = '';
		if (array_key_exists($restrcheckin['mon'], $roomrestr)) {
			//restriction found for this month, checking:
			if (strlen($roomrestr[$restrcheckin['mon']]['wday']) > 0) {
				$rvalidwdays = array($roomrestr[$restrcheckin['mon']]['wday']);
				if (strlen($roomrestr[$restrcheckin['mon']]['wdaytwo']) > 0) {
					$rvalidwdays[] = $roomrestr[$restrcheckin['mon']]['wdaytwo'];
				}
				if (!in_array($restrcheckin['wday'], $rvalidwdays)) {
					$restrictionerrmsg = JText::sprintf('VBRESTRTIPWDAYARRIVAL', self::sayMonth($restrcheckin['mon']), self::sayWeekDay($roomrestr[$restrcheckin['mon']]['wday']).(strlen($roomrestr[$restrcheckin['mon']]['wdaytwo']) > 0 ? '/'.self::sayWeekDay($roomrestr[$restrcheckin['mon']]['wdaytwo']) : ''));
				}elseif ($roomrestr[$restrcheckin['mon']]['multiplyminlos'] == 1) {
					if (($daysdiff % $roomrestr[$restrcheckin['mon']]['minlos']) != 0) {
						$restrictionerrmsg = JText::sprintf('VBRESTRTIPMULTIPLYMINLOS', self::sayMonth($restrcheckin['mon']), $roomrestr[$restrcheckin['mon']]['minlos']);
					}
				}
				$comborestr = self::parseJsDrangeWdayCombo($roomrestr[$restrcheckin['mon']]);
				if (count($comborestr) > 0) {
					if (array_key_exists($restrcheckin['wday'], $comborestr)) {
						if (!in_array($restrcheckout['wday'], $comborestr[$restrcheckin['wday']])) {
							$restrictionerrmsg = JText::sprintf('VBRESTRTIPWDAYCOMBO', self::sayMonth($restrcheckin['mon']), self::sayWeekDay($comborestr[$restrcheckin['wday']][0]).(count($comborestr[$restrcheckin['wday']]) == 2 ? '/'.self::sayWeekDay($comborestr[$restrcheckin['wday']][1]) : ''), self::sayWeekDay($restrcheckin['wday']));
						}
					}
				}
			}
			if (!empty($roomrestr[$restrcheckin['mon']]['maxlos']) && $roomrestr[$restrcheckin['mon']]['maxlos'] > 0 && $roomrestr[$restrcheckin['mon']]['maxlos'] > $roomrestr[$restrcheckin['mon']]['minlos']) {
				if ($daysdiff > $roomrestr[$restrcheckin['mon']]['maxlos']) {
					$restrictionerrmsg = JText::sprintf('VBRESTRTIPMAXLOSEXCEEDED', self::sayMonth($restrcheckin['mon']), $roomrestr[$restrcheckin['mon']]['maxlos']);
				}
			}
			if ($daysdiff < $roomrestr[$restrcheckin['mon']]['minlos']) {
				$restrictionerrmsg = JText::sprintf('VBRESTRTIPMINLOSEXCEEDED', self::sayMonth($restrcheckin['mon']), $roomrestr[$restrcheckin['mon']]['minlos']);
			}
		}elseif (array_key_exists('range', $roomrestr)) {
			$restrictionsvalid = true;
			foreach($roomrestr['range'] as $restr) {
				if ($restr['dfrom'] <= $restrcheckin[0] && ($restr['dto'] + 82799) >= $restrcheckin[0]) {
					//restriction found for this date range, checking:
					if (strlen($restr['wday']) > 0) {
						$rvalidwdays = array($restr['wday']);
						if (strlen($restr['wdaytwo']) > 0) {
							$rvalidwdays[] = $restr['wdaytwo'];
						}
						if (!in_array($restrcheckin['wday'], $rvalidwdays)) {
							$restrictionsvalid = false;
							$restrictionerrmsg = JText::sprintf('VBRESTRTIPWDAYARRIVALRANGE', self::sayWeekDay($restr['wday']).(strlen($restr['wdaytwo']) > 0 ? '/'.self::sayWeekDay($restr['wdaytwo']) : ''));
						}elseif ($restr['multiplyminlos'] == 1) {
							if (($daysdiff % $restr['minlos']) != 0) {
								$restrictionsvalid = false;
								$restrictionerrmsg = JText::sprintf('VBRESTRTIPMULTIPLYMINLOSRANGE', $restr['minlos']);
							}
						}
						$comborestr = self::parseJsDrangeWdayCombo($restr);
						if (count($comborestr) > 0) {
							if (array_key_exists($restrcheckin['wday'], $comborestr)) {
								if (!in_array($restrcheckout['wday'], $comborestr[$restrcheckin['wday']])) {
									$restrictionsvalid = false;
									$restrictionerrmsg = JText::sprintf('VBRESTRTIPWDAYCOMBORANGE', self::sayWeekDay($comborestr[$restrcheckin['wday']][0]).(count($comborestr[$restrcheckin['wday']]) == 2 ? '/'.self::sayWeekDay($comborestr[$restrcheckin['wday']][1]) : ''), self::sayWeekDay($restrcheckin['wday']));
								}
							}
						}
					}
					if (!empty($restr['maxlos']) && $restr['maxlos'] > 0 && $restr['maxlos'] > $restr['minlos']) {
						if ($daysdiff > $restr['maxlos']) {
							$restrictionsvalid = false;
							$restrictionerrmsg = JText::sprintf('VBRESTRTIPMAXLOSEXCEEDEDRANGE', $restr['maxlos']);
						}
					}
					if ($daysdiff < $restr['minlos']) {
						$restrictionsvalid = false;
						$restrictionerrmsg = JText::sprintf('VBRESTRTIPMINLOSEXCEEDEDRANGE', $restr['minlos']);
					}
					if ($restrictionsvalid == false) {
						break;
					}
				}
			}
		}
		return $restrictionerrmsg;
	}
	
	public static function parseJsDrangeWdayCombo ($drestr) {
		$combo = array();
		if (strlen($drestr['wday']) > 0 && strlen($drestr['wdaytwo']) > 0 && !empty($drestr['wdaycombo'])) {
			$cparts = explode(':', $drestr['wdaycombo']);
			foreach($cparts as $kc => $cw) {
				if (!empty($cw)) {
					$nowcombo = explode('-', $cw);
					$combo[intval($nowcombo[0])][] = intval($nowcombo[1]);
				}
			}
		}
		return $combo;
	}

	public static function validateRoomPackage($pkg_id, $rooms, $numnights, $checkints, $checkoutts) {
		$dbo = JFactory::getDBO();
		$pkg = array();
		$q = "SELECT * FROM `#__vikbooking_packages` WHERE `id`='".intval($pkg_id)."';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
			$pkg = $dbo->loadAssoc();
			$vbo_tn = self::getTranslator();
			$vbo_tn->translateContents($pkg, '#__vikbooking_packages');
		}else {
			return JText::_('VBOPKGERRNOTFOUND');
		}
		$rooms_req = array();
		foreach ($rooms as $num => $room) {
			if(!empty($room['id']) && !in_array($room['id'], $rooms_req)) {
				$rooms_req[] = $room['id'];
			}
		}
		$q = "SELECT `id` FROM `#__vikbooking_packages_rooms` WHERE `idpackage`=".$pkg['id']." AND `idroom` IN (".implode(', ', $rooms_req).");";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() != count($rooms_req)) {
			//error, not all the rooms requested are available for this package
			return JText::_('VBOPKGERRNOTROOM');
		}
		if($numnights < $pkg['minlos'] || ($pkg['maxlos'] > 0 && $numnights > $pkg['maxlos'])) {
			return JText::_('VBOPKGERRNUMNIGHTS');
		}
		if($checkints < $pkg['dfrom'] || $checkints > $pkg['dto']) {
			return JText::_('VBOPKGERRCHECKIND');
		}
		if($checkoutts < $pkg['dfrom'] || $checkoutts > $pkg['dto']) {
			return JText::_('VBOPKGERRCHECKOUTD');
		}
		if(!empty($pkg['excldates'])) {
			//this would check if any stay date is excluded
			//$bookdates_ts = self::getGroupDays($checkints, $checkoutts, $numnights);
			//check just the arrival and departure dates
			$bookdates_ts = array($checkints, $checkoutts);
			$bookdates = array();
			foreach ($bookdates_ts as $bookdate_ts) {
				$info_d = getdate($bookdate_ts);
				$bookdates[] = $info_d['mon'].'-'.$info_d['mday'].'-'.$info_d['year'];
			}
			$edates = explode(';', $pkg['excldates']);
			foreach ($edates as $edate) {
				if(!empty($edate) && in_array($edate, $bookdates)) {
					return JText::sprintf('VBOPKGERREXCLUDEDATE', $edate);
				}
			}
		}
		return $pkg;
	}

	public static function getPackage($pkg_id) {
		$dbo = JFactory::getDBO();
		$pkg = array();
		$q = "SELECT * FROM `#__vikbooking_packages` WHERE `id`='".intval($pkg_id)."';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
			$pkg = $dbo->loadAssoc();
		}
		return $pkg;
	}
	
	public static function getRoomParam ($paramname, $paramstr) {
		if (empty($paramstr)) return '';
		$paramarr = json_decode($paramstr, true);
		if (array_key_exists($paramname, $paramarr)) {
			return $paramarr[$paramname];
		}
		return '';
	}

	public static function filterNightsSeasonsCal ($arr_nights) {
		$nights = array();
		foreach ($arr_nights as $night) {
			if(intval(trim($night)) > 0) {
				$nights[] = intval(trim($night));
			}
		}
		sort($nights);
		return array_unique($nights);
	}

	public static function getSeasonRangeTs ($from, $to, $year) {
		$sfrom = 0;
		$sto = 0;
		$tsbase = mktime(0, 0, 0, 1, 1, $year);
		$curyear = $year;
		$tsbasetwo = $tsbase;
		$curyeartwo = $year;
		if($from > $to) {
			//between two years
			$curyeartwo += 1;
			$tsbasetwo = mktime(0, 0, 0, 1, 1, $curyeartwo);
		}
		$sfrom = ($tsbase + $from);
		$sto = ($tsbasetwo + $to);
		if($curyear % 4 == 0 && ($curyear % 100 != 0 || $curyear % 400 == 0)) {
			//leap years
			$infoseason = getdate($sfrom);
			$leapts = mktime(0, 0, 0, 2, 29, $infoseason['year']);
			if($infoseason[0] >= $leapts) {
				$sfrom += 86400;
				if($curyear == $curyeartwo) {
					$sto += 86400;
				}
			}
		}elseif($curyeartwo % 4 == 0 && ($curyeartwo % 100 != 0 || $curyeartwo % 400 == 0)) {
			//leap years
			$infoseason = getdate($sto);
			$leapts = mktime(0, 0, 0, 2, 29, $infoseason['year']);
			if($infoseason[0] >= $leapts) {
				$sto += 86400;
			}
		}
		return array($sfrom, $sto);
	}

	public static function sortSeasonsRangeTs ($all_seasons) {
		$sorted = array();
		$map = array();
		foreach ($all_seasons as $key => $season) {
			$map[$key] = $season['from_ts'];
		}
		asort($map);
		foreach ($map as $key => $s) {
			$sorted[] = $all_seasons[$key];
		}
		return $sorted;
	}

	public static function formatSeasonDates ($from_ts, $to_ts) {
		$one = getdate($from_ts);
		$two = getdate($to_ts);
		$months_map = array(
			1 => JText::_('VBSHORTMONTHONE'),
			2 => JText::_('VBSHORTMONTHTWO'),
			3 => JText::_('VBSHORTMONTHTHREE'),
			4 => JText::_('VBSHORTMONTHFOUR'),
			5 => JText::_('VBSHORTMONTHFIVE'),
			6 => JText::_('VBSHORTMONTHSIX'),
			7 => JText::_('VBSHORTMONTHSEVEN'),
			8 => JText::_('VBSHORTMONTHEIGHT'),
			9 => JText::_('VBSHORTMONTHNINE'),
			10 => JText::_('VBSHORTMONTHTEN'),
			11 => JText::_('VBSHORTMONTHELEVEN'),
			12 => JText::_('VBSHORTMONTHTWELVE')
		);
		$mday_map = array(
			1 => JText::_('VBMDAYFRIST'),
			2 => JText::_('VBMDAYSECOND'),
			3 => JText::_('VBMDAYTHIRD'),
			'generic' => JText::_('VBMDAYNUMGEN')
		);
		if($one['year'] == $two['year']) {
			return $one['year'].' '.$months_map[(int)$one['mon']].' '.$one['mday'].'<sup>'.(array_key_exists((int)substr($one['mday'], -1), $mday_map) && ($one['mday'] < 10 || $one['mday'] > 20) ? $mday_map[(int)substr($one['mday'], -1)] : $mday_map['generic']).'</sup> - '.$months_map[(int)$two['mon']].' '.$two['mday'].'<sup>'.(array_key_exists((int)substr($two['mday'], -1), $mday_map) && ($two['mday'] < 10 || $two['mday'] > 20) ? $mday_map[(int)substr($two['mday'], -1)] : $mday_map['generic']).'</sup>';
		}
		return $months_map[(int)$one['mon']].' '.$one['mday'].'<sup>'.(array_key_exists((int)substr($one['mday'], -1), $mday_map) && ($one['mday'] < 10 || $one['mday'] > 20) ? $mday_map[(int)substr($one['mday'], -1)] : $mday_map['generic']).'</sup> '.$one['year'].' - '.$months_map[(int)$two['mon']].' '.$two['mday'].'<sup>'.(array_key_exists((int)substr($two['mday'], -1), $mday_map) && ($two['mday'] < 10 || $two['mday'] > 20) ? $mday_map[(int)substr($two['mday'], -1)] : $mday_map['generic']).'</sup> '.$two['year'];
	}

	public static function getFirstCustDataField($custdata) {
		$first_field = '';
		if(strpos($custdata, JText::_('VBDBTEXTROOMCLOSED')) !== false) {
			//Room is closed with this booking
			return '----';
		}
		$parts = explode("\n", $custdata);
		foreach ($parts as $part) {
			if(!empty($part)) {
				$field = explode(':', trim($part));
				if(!empty($field[1])) {
					return trim($field[1]);
				}
			}
		}
		return $first_field;
	}
	
	public static function getTheme () {
		$dbo = JFactory::getDBO();
		$q="SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='theme';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s=$dbo->loadAssocList();
		return $s[0]['setting'];
	}
	
	public static function getFooterOrdMail($vbo_tn = null) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id`,`setting` FROM `#__vikbooking_texts` WHERE `param`='footerordmail';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$ft = $dbo->loadAssocList();
		if(!is_object($vbo_tn)) {
			$vbo_tn = self::getTranslator();
		}
		$vbo_tn->translateContents($ft, '#__vikbooking_texts');
		return $ft[0]['setting'];
	}
	
	public static function requireLogin() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='requirelogin';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return intval($s[0]['setting']) == 1 ? true : false;
	}

	public static function autoRoomUnit() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='autoroomunit';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return intval($s[0]['setting']) == 1 ? true : false;
	}

	public static function todayBookings() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='todaybookings';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return intval($s[0]['setting']) == 1 ? true : false;
	}
	
	public static function couponsEnabled() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='enablecoupons';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return intval($s[0]['setting']) == 1 ? true : false;
	}

	public static function customersPinEnabled() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='enablepin';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return intval($s[0]['setting']) == 1 ? true : false;
	}
	
	public static function detectUserAgent($returnua = false) {
		$session = JFactory::getSession();
		$sval = $session->get('vbuseragent', '');
		if(!empty($sval)) {
			self::userAgentStyleSheet($sval);
			return $returnua ? $sval : true;
		}else {
			if (!class_exists('MobileDetector')) {
				require_once(JPATH_SITE . DS ."components". DS ."com_vikbooking". DS . "helpers" . DS ."mobile_detector.php");
			}
			$detector = new MobileDetector;
			$visitoris = $detector->isMobile() ? ($detector->isTablet() ? 'tablet' : 'smartphone') : 'computer';
			$session->set('vbuseragent', $visitoris);
			self::userAgentStyleSheet($visitoris);
			return $returnua ? $visitoris : true;
		}
	}
	
	public static function userAgentStyleSheet($ua) {
		$document = JFactory::getDocument();
		if ($ua == 'smartphone') {
			$document->addStyleSheet(JURI::root().'components/com_vikbooking/vikbooking_smartphones.css');
		}elseif ($ua == 'tablet') {
			$document->addStyleSheet(JURI::root().'components/com_vikbooking/vikbooking_tablets.css');
		}
		return true;
	}
	
	public static function loadJquery($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='loadjquery';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return intval($s[0]['setting']) == 1 ? true : false;
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbloadJquery', '');
			if(!empty($sval)) {
				return intval($sval) == 1 ? true : false;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='loadjquery';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbloadJquery', $s[0]['setting']);
				return intval($s[0]['setting']) == 1 ? true : false;
			}
		}
	}

	public static function loadBootstrap($skipsession = false) {
		$dbo = JFactory::getDBO();
		if($skipsession) {
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='bootstrap';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return intval($s[0]['setting']) == 1 ? true : false;
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbBootstrap', '');
			if(!empty($sval)) {
				return intval($sval) == 1 ? true : false;
			}else {
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='bootstrap';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbBootstrap', $s[0]['setting']);
				return intval($s[0]['setting']) == 1 ? true : false;
			}
		}
	}

	public static function allowMultiLanguage($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='multilang';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return intval($s[0]['setting']) == 1 ? true : false;
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbMultiLang', '');
			if(!empty($sval)) {
				return intval($sval) == 1 ? true : false;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='multilang';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbMultiLang', $s[0]['setting']);
				return intval($s[0]['setting']) == 1 ? true : false;
			}
		}
	}

	public static function getTranslator() {
		if(!class_exists('VikBookingTranslator')) {
			require_once(JPATH_SITE . DS ."components". DS ."com_vikbooking". DS . "helpers" . DS ."translator.php");
		}
		return new VikBookingTranslator();
	}

	public static function getCPinIstance() {
		if(!class_exists('VikBookingCustomersPin')) {
			require_once(JPATH_SITE . DS ."components". DS ."com_vikbooking". DS . "helpers" . DS ."cpin.php");
		}
		return new VikBookingCustomersPin();
	}
	
	public static function getMinDaysAdvance($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='mindaysadvance';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return (int)$s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbminDaysAdvance', '');
			if(!empty($sval)) {
				return (int)$sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='mindaysadvance';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbminDaysAdvance', $s[0]['setting']);
				return (int)$s[0]['setting'];
			}
		}
	}
	
	public static function getDefaultNightsCalendar($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='autodefcalnights';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return (int)$s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbdefaultNightsCalendar', '');
			if(!empty($sval)) {
				return (int)$sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='autodefcalnights';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbdefaultNightsCalendar', $s[0]['setting']);
				return (int)$s[0]['setting'];
			}
		}
	}
	
	public static function getSearchNumRooms($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='numrooms';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return (int)$s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbsearchNumRooms', '');
			if(!empty($sval)) {
				return (int)$sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='numrooms';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbsearchNumRooms', $s[0]['setting']);
				return (int)$s[0]['setting'];
			}
		}
	}
	
	public static function getSearchNumAdults($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='numadults';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return $s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbsearchNumAdults', '');
			if(!empty($sval)) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='numadults';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbsearchNumAdults', $s[0]['setting']);
				return $s[0]['setting'];
			}
		}
	}
	
	public static function getSearchNumChildren($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='numchildren';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return $s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbsearchNumChildren', '');
			if(!empty($sval)) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='numchildren';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbsearchNumChildren', $s[0]['setting']);
				return $s[0]['setting'];
			}
		}
	}
	
	public static function getSmartSearchType($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='smartsearch';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return $s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbsmartSearchType', '');
			if(!empty($sval)) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='smartsearch';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbsmartSearchType', $s[0]['setting']);
				return $s[0]['setting'];
			}
		}
	}
	
	public static function getMaxDateFuture($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='maxdate';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return $s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbmaxDateFuture', '');
			if(!empty($sval)) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='maxdate';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbmaxDateFuture', $s[0]['setting']);
				return $s[0]['setting'];
			}
		}
	}
	
	public static function calendarType($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='calendar';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return $s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbcalendarType', '');
			if(!empty($sval)) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='calendar';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbcalendarType', $s[0]['setting']);
				return $s[0]['setting'];
			}
		}
	}
	
	public static function getSiteLogo() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='sitelogo';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return $s[0]['setting'];
	}
	
	public static function numCalendars() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='numcalendars';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return $s[0]['setting'];
	}
	
	public static function getFirstWeekDay($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='firstwday';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return $s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbfirstWeekDay', '');
			if(strlen($sval)) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='firstwday';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbfirstWeekDay', $s[0]['setting']);
				return $s[0]['setting'];
			}
		}
	}
	
	public static function showPartlyReserved() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='showpartlyreserved';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return intval($s[0]['setting']) == 1 ? true : false;
	}

	public static function showStatusCheckinoutOnly() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='showcheckinoutonly';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return intval($s[0]['setting']) == 1 ? true : false;
	}

	public static function getDisclaimer($vbo_tn = null) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id`,`setting` FROM `#__vikbooking_texts` WHERE `param`='disclaimer';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$ft = $dbo->loadAssocList();
		if(!is_object($vbo_tn)) {
			$vbo_tn = self::getTranslator();
		}
		$vbo_tn->translateContents($ft, '#__vikbooking_texts');
		return $ft[0]['setting'];
	}

	public static function showFooter() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='showfooter';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
			$s = $dbo->loadAssocList();
			return (intval($s[0]['setting']) == 1 ? true : false);
		} else {
			return false;
		}
	}

	public static function getPriceName($idp, $vbo_tn = null) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id`,`name` FROM `#__vikbooking_prices` WHERE `id`=" . (int)$idp . "";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
			$n = $dbo->loadAssocList();
			if(is_object($vbo_tn)) {
				$vbo_tn->translateContents($n, '#__vikbooking_prices');
			}
			return $n[0]['name'];
		}
		return "";
	}

	public static function getPriceAttr($idp, $vbo_tn = null) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id`,`attr` FROM `#__vikbooking_prices` WHERE `id`=" . (int)$idp . "";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
			$n = $dbo->loadAssocList();
			if(is_object($vbo_tn)) {
				$vbo_tn->translateContents($n, '#__vikbooking_prices');
			}
			return $n[0]['attr'];
		}
		return "";
	}
	
	public static function getPriceInfo($idp, $vbo_tn = null) {
		$dbo = JFactory::getDBO();
		$q = "SELECT * FROM `#__vikbooking_prices` WHERE `id`=" . (int)$idp . ";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
			$n = $dbo->loadAssocList();
			if(is_object($vbo_tn)) {
				$vbo_tn->translateContents($n, '#__vikbooking_prices');
			}
			return $n[0];
		}
		return "";
	}
	
	public static function getAliq($idal) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `aliq` FROM `#__vikbooking_iva` WHERE `id`='" . $idal . "';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$n = $dbo->loadAssocList();
		return $n[0]['aliq'];
	}

	public static function getTimeOpenStore($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='timeopenstore';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$n = $dbo->loadAssocList();
			if (empty ($n[0]['setting']) && $n[0]['setting'] != "0") {
				return false;
			} else {
				$x = explode("-", $n[0]['setting']);
				if (!empty ($x[1]) && $x[1] != "0") {
					return $x;
				}
			}
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbgetTimeOpenStore', '');
			if(!empty($sval)) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='timeopenstore';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$n = $dbo->loadAssocList();
				if (empty ($n[0]['setting']) && $n[0]['setting'] != "0") {
					return false;
				} else {
					$x = explode("-", $n[0]['setting']);
					if (!empty ($x[1]) && $x[1] != "0") {
						$session->set('vbgetTimeOpenStore', $x);
						return $x;
					}
				}
			}
		}
		return false;
	}

	public static function getHoursMinutes($secs) {
		if ($secs >= 3600) {
			$op = $secs / 3600;
			$hours = floor($op);
			$less = $hours * 3600;
			$newsec = $secs - $less;
			$optwo = $newsec / 60;
			$minutes = floor($optwo);
		} else {
			$hours = "0";
			$optwo = $secs / 60;
			$minutes = floor($optwo);
		}
		$x[] = $hours;
		$x[] = $minutes;
		return $x;
	}

	public static function getClosingDates() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='closingdates';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
			$s = $dbo->loadAssocList();
			if(!empty($s[0]['setting'])) {
				$allcd = json_decode($s[0]['setting'], true);
				$base_ts = mktime(0, 0, 0, date("n"), date("j"), date("Y"));
				foreach ($allcd as $k => $v) {
					if($v['to'] < $base_ts) {
						unset($allcd[$k]);
					}
				}
				$allcd = array_values($allcd);
				return $allcd;
			}
		}
		return array();
	}

	public static function parseJsClosingDates() {
		$cd = self::getClosingDates();
		if(count($cd) > 0) {
			$cdjs = array();
			foreach ($cd as $k => $v) {
				$cdjs[] = array(date('Y-m-d', $v['from']), date('Y-m-d', $v['to']));
			}
			return $cdjs;
		}
		return array();
	}

	public static function validateClosingDates($checkints, $checkoutts, $df) {
		$df = empty($df) ? 'Y-m-d' : $df;
		$cd = self::getClosingDates();
		if(count($cd) > 0) {
			foreach ($cd as $k => $v) {
				if( ( $checkints >= $v['from'] && $checkints <= ($v['to'] + (22*60*60)) ) || ( $checkoutts >= $v['from'] && $checkoutts <= ($v['to'] + (22*60*60)) ) ) {
					return date($df, $v['from']) . ' - ' . date($df, $v['to']);
				}
			}
		}
		return '';
	}

	public static function showCategoriesFront($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='showcategories';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() == 1) {
				$s = $dbo->loadAssocList();
				return (intval($s[0]['setting']) == 1 ? true : false);
			} else {
				return false;
			}
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbshowCategoriesFront', '');
			if(strlen($sval) > 0) {
				return (intval($sval) == 1 ? true : false);
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='showcategories';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($dbo->getNumRows() == 1) {
					$s = $dbo->loadAssocList();
					$session->set('vbshowCategoriesFront', $s[0]['setting']);
					return (intval($s[0]['setting']) == 1 ? true : false);
				} else {
					return false;
				}
			}
		}
	}
	
	public static function showChildrenFront($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='showchildren';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$s = $dbo->loadAssocList();
				return (intval($s[0]['setting']) == 1 ? true : false);
			} else {
				return false;
			}
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbshowChildrenFront', '');
			if(strlen($sval) > 0) {
				return (intval($sval) == 1 ? true : false);
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='showchildren';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($dbo->getNumRows() > 0) {
					$s = $dbo->loadAssocList();
					$session->set('vbshowChildrenFront', $s[0]['setting']);
					return (intval($s[0]['setting']) == 1 ? true : false);
				} else {
					return false;
				}
			}
		}
	}

	public static function allowBooking() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='allowbooking';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$s = $dbo->loadAssocList();
			return (intval($s[0]['setting']) == 1 ? true : false);
		} else {
			return false;
		}
	}

	public static function getDisabledBookingMsg($vbo_tn = null) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id`,`setting` FROM `#__vikbooking_texts` WHERE `param`='disabledbookingmsg';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		if(!is_object($vbo_tn)) {
			$vbo_tn = self::getTranslator();
		}
		$vbo_tn->translateContents($s, '#__vikbooking_texts');
		return $s[0]['setting'];
	}

	public static function getDateFormat($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='dateformat';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return $s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbgetDateFormat', '');
			if(!empty($sval)) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='dateformat';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbgetDateFormat', $s[0]['setting']);
				return $s[0]['setting'];
			}
		}
	}

	public static function getHoursMoreRb($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='hoursmorebookingback';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return $s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('getHoursMoreRb', '');
			if(strlen($sval) > 0) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='hoursmorebookingback';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('getHoursMoreRb', $s[0]['setting']);
				return $s[0]['setting'];
			}
		}
	}

	public static function getHoursRoomAvail() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='hoursmoreroomavail';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return $s[0]['setting'];
	}

	public static function getFrontTitle($vbo_tn = null) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id`,`setting` FROM `#__vikbooking_texts` WHERE `param`='fronttitle';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$ft = $dbo->loadAssocList();
		if(!is_object($vbo_tn)) {
			$vbo_tn = self::getTranslator();
		}
		$vbo_tn->translateContents($ft, '#__vikbooking_texts');
		return $ft[0]['setting'];
	}

	public static function getFrontTitleTag() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='fronttitletag';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$ft = $dbo->loadAssocList();
		return $ft[0]['setting'];
	}

	public static function getFrontTitleTagClass() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='fronttitletagclass';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$ft = $dbo->loadAssocList();
		return $ft[0]['setting'];
	}

	public static function getCurrencyName() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='currencyname';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$ft = $dbo->loadAssocList();
		return $ft[0]['setting'];
	}

	public static function getCurrencySymb($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='currencysymb';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$ft = $dbo->loadAssocList();
			return $ft[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbgetCurrencySymb', '');
			if(!empty($sval)) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='currencysymb';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$ft = $dbo->loadAssocList();
				$session->set('vbgetCurrencySymb', $ft[0]['setting']);
				return $ft[0]['setting'];
			}
		}
	}
	
	public static function getNumberFormatData($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='numberformat';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$ft = $dbo->loadAssocList();
			return $ft[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('getNumberFormatData', '');
			if(!empty($sval)) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='numberformat';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$ft = $dbo->loadAssocList();
				$session->set('getNumberFormatData', $ft[0]['setting']);
				return $ft[0]['setting'];
			}
		}
	}
	
	public static function numberFormat($num, $skipsession = false) {
		$formatvals = self::getNumberFormatData($skipsession);
		$formatparts = explode(':', $formatvals);
		return number_format($num, (int)$formatparts[0], $formatparts[1], $formatparts[2]);
	}

	public static function getCurrencyCodePp() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='currencycodepp';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$ft = $dbo->loadAssocList();
		return $ft[0]['setting'];
	}

	public static function getSubmitName($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `id`,`setting` FROM `#__vikbooking_texts` WHERE `param`='searchbtnval';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$ft = $dbo->loadAssocList();
			if (!empty ($ft[0]['setting'])) {
				return $ft[0]['setting'];
			} else {
				return $skipsession ? '' : JText::_('VBSEARCHBUTTON');
			}
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbgetSubmitName', '');
			if(!empty($sval)) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `id`,`setting` FROM `#__vikbooking_texts` WHERE `param`='searchbtnval';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$ft = $dbo->loadAssocList();
				$vbo_tn = self::getTranslator();
				if (!empty ($ft[0]['setting'])) {
					$vbo_tn->translateContents($ft, '#__vikbooking_texts');
					$session->set('vbgetSubmitName', $ft[0]['setting']);
					return $ft[0]['setting'];
				} else {
					return $skipsession ? '' : JText::_('VBSEARCHBUTTON');
				}
			}
		}
	}

	public static function getSubmitClass($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='searchbtnclass';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$ft = $dbo->loadAssocList();
			return $ft[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbgetSubmitClass', '');
			if(!empty($sval)) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='searchbtnclass';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$ft = $dbo->loadAssocList();
				$session->set('vbgetSubmitClass', $ft[0]['setting']);
				return $ft[0]['setting'];
			}
		}
	}

	public static function getIntroMain($vbo_tn = null) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id`,`setting` FROM `#__vikbooking_texts` WHERE `param`='intromain';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$ft = $dbo->loadAssocList();
		if(!is_object($vbo_tn)) {
			$vbo_tn = self::getTranslator();
		}
		$vbo_tn->translateContents($ft, '#__vikbooking_texts');
		return $ft[0]['setting'];
	}

	public static function getClosingMain($vbo_tn = null) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id`,`setting` FROM `#__vikbooking_texts` WHERE `param`='closingmain';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$ft = $dbo->loadAssocList();
		if(!is_object($vbo_tn)) {
			$vbo_tn = self::getTranslator();
		}
		$vbo_tn->translateContents($ft, '#__vikbooking_texts');
		return $ft[0]['setting'];
	}

	public static function getFullFrontTitle($vbo_tn = null) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id`,`setting` FROM `#__vikbooking_texts` WHERE `param`='fronttitle';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$ft = $dbo->loadAssocList();
		if(!is_object($vbo_tn)) {
			$vbo_tn = self::getTranslator();
		}
		$vbo_tn->translateContents($ft, '#__vikbooking_texts');
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='fronttitletag';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$fttag = $dbo->loadAssocList();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='fronttitletagclass';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$fttagclass = $dbo->loadAssocList();
		if (empty ($ft[0]['setting'])) {
			return "";
		} else {
			if (empty ($fttag[0]['setting'])) {
				return $ft[0]['setting'] . "<br/>\n";
			} else {
				$tag = str_replace("<", "", $fttag[0]['setting']);
				$tag = str_replace(">", "", $tag);
				$tag = str_replace("/", "", $tag);
				$tag = trim($tag);
				return "<" . $tag . "" . (!empty ($fttagclass) ? " class=\"" . $fttagclass[0]['setting'] . "\"" : "") . ">" . $ft[0]['setting'] . "</" . $tag . ">";
			}
		}
	}

	public static function dateIsValid($date) {
		$df = self::getDateFormat();
		if (strlen($date) != 10) {
			return false;
		}
		$x = explode("/", $date);
		if ($df == "%d/%m/%Y") {
			if (strlen($x[0]) != 2 || $x[0] > 31 || strlen($x[1]) != 2 || $x[1] > 12 || strlen($x[2]) != 4) {
				return false;
			}
		} elseif ($df == "%m/%d/%Y") {
			if (strlen($x[1]) != 2 || $x[1] > 31 || strlen($x[0]) != 2 || $x[0] > 12 || strlen($x[2]) != 4) {
				return false;
			}
		} else {
			if (strlen($x[2]) != 2 || $x[2] > 31 || strlen($x[1]) != 2 || $x[1] > 12 || strlen($x[0]) != 4) {
				return false;
			}
		}
		return true;
	}

	public static function sayDateFormat() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='dateformat';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		if ($s[0]['setting'] == "%d/%m/%Y") {
			return JText::_('VBCONFIGONETWELVE');
		} elseif ($s[0]['setting'] == "%m/%d/%Y") {
			return JText::_('VBCONFIGONEMDY');
		} else {
			return JText::_('VBCONFIGONETENTHREE');
		}
	}

	public static function getDateTimestamp($date, $h, $m) {
		$df = self::getDateFormat();
		$x = explode("/", $date);
		if ($df == "%d/%m/%Y") {
			$dts = strtotime($x[1] . "/" . $x[0] . "/" . $x[2]);
		} elseif ($df == "%m/%d/%Y") {
			$dts = strtotime($x[0] . "/" . $x[1] . "/" . $x[2]);
		} else {
			$dts = strtotime($x[1] . "/" . $x[2] . "/" . $x[0]);
		}
		$h = empty($h) ? 0 : $h;
		$m = empty($m) ? 0 : $m;
		$hts = 3600 * $h;
		$mts = 60 * $m;
		return ($dts + $hts + $mts);
	}

	public static function ivaInclusa($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='ivainclusa';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return (intval($s[0]['setting']) == 1 ? true : false);
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbivaInclusa', '');
			if(strlen($sval) > 0) {
				return (intval($sval) == 1 ? true : false);
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='ivainclusa';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbivaInclusa', $s[0]['setting']);
				return (intval($s[0]['setting']) == 1 ? true : false);
			}
		}
	}
	
	public static function showTaxOnSummaryOnly($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='taxsummary';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return (intval($s[0]['setting']) == 1 ? true : false);
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('vbshowTaxOnSummaryOnly', '');
			if(strlen($sval) > 0) {
				return (intval($sval) == 1 ? true : false);
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='taxsummary';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('vbshowTaxOnSummaryOnly', $s[0]['setting']);
				return (intval($s[0]['setting']) == 1 ? true : false);
			}
		}
	}

	public static function tokenForm() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='tokenform';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return (intval($s[0]['setting']) == 1 ? true : false);
	}

	public static function getPaypalAcc() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='ccpaypal';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return $s[0]['setting'];
	}

	public static function getAccPerCent() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='payaccpercent';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return $s[0]['setting'];
	}
	
	public static function getTypeDeposit($skipsession = false) {
		if($skipsession) {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='typedeposit';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$s = $dbo->loadAssocList();
			return $s[0]['setting'];
		}else {
			$session = JFactory::getSession();
			$sval = $session->get('getTypeDeposit', '');
			if(strlen($sval) > 0) {
				return $sval;
			}else {
				$dbo = JFactory::getDBO();
				$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='typedeposit';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$s = $dbo->loadAssocList();
				$session->set('getTypeDeposit', $s[0]['setting']);
				return $s[0]['setting'];
			}
		}
	}
	
	public static function multiplePayments() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='multipay';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return (intval($s[0]['setting']) == 1 ? true : false);
	}

	public static function getAdminMail() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='adminemail';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return $s[0]['setting'];
	}

	public static function getSenderMail () {
		$dbo = JFactory::getDBO();
		$q="SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='senderemail';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s=$dbo->loadAssocList();
		return empty($s[0]['setting']) ? self::getAdminMail() : $s[0]['setting'];
	}

	public static function getPaymentName($vbo_tn = null) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id`,`setting` FROM `#__vikbooking_texts` WHERE `param`='paymentname';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		if(!is_object($vbo_tn)) {
			$vbo_tn = self::getTranslator();
		}
		$vbo_tn->translateContents($s, '#__vikbooking_texts');
		return $s[0]['setting'];
	}

	public static function getMinutesLock($conv = false) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='minuteslock';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		if ($conv) {
			$op = $s[0]['setting'] * 60;
			return (time() + $op);
		} else {
			return $s[0]['setting'];
		}
	}

	public static function roomNotLocked($idroom, $units, $first, $second) {
		$dbo = JFactory::getDBO();
		$actnow = time();
		$booked = array ();
		$q = "DELETE FROM `#__vikbooking_tmplock` WHERE `until`<" . $dbo->quote($actnow) . ";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		//vikbooking 1.1
		$secdiff = $second - $first;
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
				$maxhmore = self::getHoursMoreRb() * 3600;
				if ($maxhmore >= $newdiff) {
					$daysdiff = floor($daysdiff);
				}else {
					$daysdiff = ceil($daysdiff);
				}
			}
		}
		$groupdays = self::getGroupDays($first, $second, $daysdiff);
		$check = "SELECT `id`,`checkin`,`realback` FROM `#__vikbooking_tmplock` WHERE `idroom`=" . $dbo->quote($idroom) . " AND `until`>=" . $dbo->quote($actnow) . ";";
		$dbo->setQuery($check);
		$dbo->Query($check);
		if ($dbo->getNumRows() > 0) {
			$busy = $dbo->loadAssocList();
			foreach ($groupdays as $gday) {
				$bfound = 0;
				foreach ($busy as $bu) {
					if ($gday >= $bu['checkin'] && $gday <= $bu['realback']) {
						$bfound++;
					}
				}
				if ($bfound >= $units) {
					return false;
				}
			}
		}
		//
		return true;
	}
	
	public static function getGroupDays($first, $second, $daysdiff) {
		$ret = array();
		$ret[] = $first;
		if($daysdiff > 1) {
			$start = getdate($first);
			$end = getdate($second);
			$endcheck = mktime(0, 0, 0, $end['mon'], $end['mday'], $end['year']);
			for($i = 1; $i < $daysdiff; $i++) {
				$checkday = $start['mday'] + $i;
				$dayts = mktime(0, 0, 0, $start['mon'], $checkday, $start['year']);
				if($dayts != $endcheck) {
					$ret[] = $dayts;
				}
			}
		}
		$ret[] = $second;
		return $ret;
	}
	
	public static function loadBusyRecords ($roomids, $ts = 0) {
		$actnow = empty($ts) ? time() : $ts;
		$busy = array();
		$dbo = JFactory::getDBO();
		$check = "SELECT `id`,`idroom`,`checkin`,`checkout` FROM `#__vikbooking_busy` WHERE `idroom` IN (".implode(', ', $roomids).") AND `checkout` > ".$actnow.";";
		$dbo->setQuery($check);
		$dbo->Query($check);
		if ($dbo->getNumRows() > 0) {
			$allbusy = $dbo->loadAssocList();
			foreach ($allbusy as $kb => $br) {
				$busy[$br['idroom']][$kb] = $br;
			}
		}
		return $busy;
	}

	public static function loadLockedRecords ($roomids, $ts = 0) {
		$actnow = empty($ts) ? time() : $ts;
		$locked = array();
		$dbo = JFactory::getDBO();
		$q = "DELETE FROM `#__vikbooking_tmplock` WHERE `until`<" . $actnow . ";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$check = "SELECT `id`,`idroom`,`checkin`,`realback` FROM `#__vikbooking_tmplock` WHERE `idroom` IN (".implode(', ', $roomids).") AND `until` > ".$actnow.";";
		$dbo->setQuery($check);
		$dbo->Query($check);
		if ($dbo->getNumRows() > 0) {
			$all_locked = $dbo->loadAssocList();
			foreach ($all_locked as $kb => $br) {
				$locked[$br['idroom']][$kb] = $br;
			}
		}
		return $locked;
	}

	public static function getRoomBookingsFromBusyIds($idroom, $arr_bids) {
		$bookings = array();
		if(empty($idroom) || !is_array($arr_bids) || !(count($arr_bids) > 0)) {
			return $bookings;
		}
		$dbo = JFactory::getDBO();
		$q = "SELECT `ob`.`idorder`,`ob`.`idbusy` FROM `#__vikbooking_ordersbusy` AS `ob` WHERE `ob`.`idbusy` IN (".implode(',', $arr_bids).") GROUP BY `ob`.`idorder`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$all_booking_ids = $dbo->loadAssocList();
			$oids = array();
			foreach ($all_booking_ids as $bid) {
				$oids[] = $bid['idorder'];
			}
			$q = "SELECT `or`.`idorder`,CONCAT_WS(' ',`or`.`t_first_name`,`or`.`t_last_name`) AS `nominative`,`or`.`roomindex`,`o`.`status`,`o`.`days`,`o`.`checkout`,`o`.`custdata`,`o`.`country` ".
				"FROM `#__vikbooking_ordersrooms` AS `or` ".
				"LEFT JOIN `#__vikbooking_orders` `o` ON `o`.`id`=`or`.`idorder` ".
				"WHERE `or`.`idorder` IN (".implode(',', $oids).") AND `or`.`idroom`=".(int)$idroom." AND `o`.`status`='confirmed' ".
				"ORDER BY `o`.`checkout` ASC;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$bookings = $dbo->loadAssocList();
			}
		}
		return $bookings;
	}
	
	public static function roomBookable($idroom, $units, $first, $second) {
		$dbo = JFactory::getDBO();
		//vikbooking 1.1
		$secdiff = $second - $first;
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
				$maxhmore = self::getHoursMoreRb() * 3600;
				if ($maxhmore >= $newdiff) {
					$daysdiff = floor($daysdiff);
				}else {
					$daysdiff = ceil($daysdiff);
				}
			}
		}
		$groupdays = self::getGroupDays($first, $second, $daysdiff);
		$check = "SELECT `id`,`checkin`,`realback` FROM `#__vikbooking_busy` WHERE `idroom`=" . $dbo->quote($idroom) . ";";
		$dbo->setQuery($check);
		$dbo->Query($check);
		if ($dbo->getNumRows() > 0) {
			$busy = $dbo->loadAssocList();
			foreach ($groupdays as $gday) {
				$bfound = 0;
				foreach ($busy as $bu) {
					if ($gday >= $bu['checkin'] && $gday <= $bu['realback']) {
						$bfound++;
					}
				}
				if ($bfound >= $units) {
					return false;
				}
			}
		}
		//
		return true;
	}

	public static function payTotal() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='paytotal';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return (intval($s[0]['setting']) == 1 ? true : false);
	}
	
	public static function getCouponInfo($code) {
		$dbo = JFactory::getDBO();
		$q = "SELECT * FROM `#__vikbooking_coupons` WHERE `code`=".$dbo->quote($code).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() == 1) {
			$c = $dbo->loadAssocList();
			return $c[0];
		}else {
			return "";
		}
	}
	
	public static function getRoomInfo($idroom) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id`,`name`,`img`,`idcat`,`idcarat`,`info`,`smalldesc` FROM `#__vikbooking_rooms` WHERE `id`='" . $idroom . "';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return $s[0];
	}
	
	public static function loadOrdersRoomsData ($idorder) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `or`.*,`r`.`name` AS `room_name` FROM `#__vikbooking_ordersrooms` AS `or` LEFT JOIN `#__vikbooking_rooms` `r` ON `r`.`id`=`or`.`idroom` WHERE `or`.`idorder`='" . $idorder . "';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
		return $s;
	}
	
	public static function sayCategory($ids, $vbo_tn = null) {
		$dbo = JFactory::getDBO();
		$split = explode(";", $ids);
		$say = "";
		foreach ($split as $k => $s) {
			if (strlen($s)) {
				$q = "SELECT `id`,`name` FROM `#__vikbooking_categories` WHERE `id`='" . $s . "';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if($dbo->getNumRows() < 1) {
					continue;
				}
				$nam = $dbo->loadAssocList();
				if(is_object($vbo_tn)) {
					$vbo_tn->translateContents($nam, '#__vikbooking_categories');
				}
				$say .= $nam[0]['name'];
				$say .= (strlen($split[($k +1)]) && end($split) != $s ? ", " : "");
			}
		}
		return $say;
	}

	public static function getRoomCaratOriz($idc, $vbo_tn = null) {
		$dbo = JFactory::getDBO();
		$split = explode(";", $idc);
		$carat = "";
		$dbo = JFactory::getDBO();
		$arr = array ();
		$where = array();
		foreach ($split as $s) {
			if (!empty($s)) {
				$where[]=$s;
			}
		}
		if (count($where) > 0) {
			$q = "SELECT * FROM `#__vikbooking_characteristics` WHERE `id` IN (".implode(",", $where).");";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$arr = $dbo->loadAssocList();
				if(is_object($vbo_tn)) {
					$vbo_tn->translateContents($arr, '#__vikbooking_characteristics');
				}
			}
		}
		if (count($arr) > 0) {
			$carat .= "<ul class=\"vbulcarats\">\n";
			foreach ($arr as $a) {
				if (!empty ($a['textimg'])) {
					$carat .= (!empty ($a['icon']) ? "<li><span class=\"vbo-expl\" data-vbo-expl=\"".$a['textimg']."\"><img src=\"".JURI::root()."components/com_vikbooking/resources/uploads/".$a['icon']."\" alt=\"" . $a['name'] . "\" /></span></li>\n" : "<li>".$a['textimg']."</li>\n");
				}else {
					$carat .= (!empty ($a['icon']) ? "<li><img src=\"".JURI::root()."components/com_vikbooking/resources/uploads/" . $a['icon'] . "\" alt=\"" . $a['name'] . "\" title=\"" . $a['name'] . "\"/></li>\n" : "<li>".$a['name']."</li>\n");
				}
			}
			$carat .= "</ul>\n";
		}
		return $carat;
	}

	public static function getRoomOptionals($idopts, $vbo_tn = null) {
		$split = explode(";", $idopts);
		$dbo = JFactory::getDBO();
		$arr = array ();
		$fetch = array();
		foreach ($split as $s) {
			if (!empty ($s)) {
				$fetch[] = $s;
			}
		}
		if(count($fetch) > 0) {
			$q = "SELECT * FROM `#__vikbooking_optionals` WHERE `id` IN (".implode(", ", $fetch).") ORDER BY `#__vikbooking_optionals`.`ordering` ASC;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$arr = $dbo->loadAssocList();
				if(is_object($vbo_tn)) {
					$vbo_tn->translateContents($arr, '#__vikbooking_optionals');
				}
				return $arr;
			}
		}
		return "";
	}
	
	public static function getMandatoryTaxesFees($id_rooms, $num_adults, $num_nights) {
		$dbo = JFactory::getDBO();
		$taxes = 0;
		$fees = 0;
		$options_data = array();
		$id_options = array();
		$q = "SELECT `id`,`idopt` FROM `#__vikbooking_rooms` WHERE `id` IN (".implode(", ", $id_rooms).");";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$assocs = $dbo->loadAssocList();
			foreach ($assocs as $opts) {
				if (!empty($opts['idopt'])) {
					$r_ido = explode(';', rtrim($opts['idopt']));
					foreach ($r_ido as $ido) {
						if (!empty($ido) && !in_array($ido, $id_options)) {
							$id_options[] = $ido;
						}
					}
				}
			}
		}
		if (count($id_options) > 0) {
			$q = "SELECT * FROM `#__vikbooking_optionals` WHERE `id` IN (".implode(", ", $id_options).") AND `forcesel`=1 AND `ifchildren`=0 AND (`is_citytax`=1 OR `is_fee`=1);";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$alltaxesfees = $dbo->loadAssocList();
				foreach ($alltaxesfees as $tf) {
					$realcost = (intval($tf['perday']) == 1 ? ($tf['cost'] * $num_nights) : $tf['cost']);
					if (!empty($tf['maxprice']) && $tf['maxprice'] > 0 && $realcost > $tf['maxprice']) {
						$realcost = $tf['maxprice'];
					}
					$realcost = $tf['perperson'] == 1 ? ($realcost * $num_adults) : $realcost;
					$realcost = self::sayOptionalsPlusIva($realcost, $tf['idiva']);
					if ($tf['is_citytax'] == 1) {
						$taxes += $realcost;
					}elseif ($tf['is_fee'] == 1) {
						$fees += $realcost;
					}
					$optsett = explode('-', $tf['forceval']);
					$options_data[] = $tf['id'].':'.$optsett[0];
				}
			}
		}
		return array('city_taxes' => $taxes, 'fees' => $fees, 'options' => $options_data);
	}
	
	public static function loadOptionAgeIntervals($optionals) {
		$ageintervals = '';
		foreach ($optionals as $kopt => $opt) {
			if (!empty($opt['ageintervals'])) {
				$intervals = explode(';;', $opt['ageintervals']);
				foreach($intervals as $intv) {
					if (empty($intv)) continue;
					$parts = explode('_', $intv);
					if (count($parts) == 3) {
						$ageintervals = $optionals[$kopt];
						break 2;
					}
				}
			}
		}
		if (is_array($ageintervals)) {
			foreach ($optionals as $kopt => $opt) {
				if (!empty($opt['ageintervals']) || $opt['id'] == $ageintervals['id']) {
					unset($optionals[$kopt]);
				}
			}
			if (count($optionals) <= 0) {
				$optionals = '';
			}
		}
		return array($optionals, $ageintervals);
	}
	
	public static function getOptionIntervalsCosts($intvstr) {
		$optcosts = array();
		$intervals = explode(';;', $intvstr);
		foreach($intervals as $kintv => $intv) {
			if (empty($intv)) continue;
			$parts = explode('_', $intv);
			if (count($parts) == 3) {
				$optcosts[$kintv] = (float)$parts[2];
			}
		}
		return $optcosts;
	}
	
	public static function getOptionIntervalsAges($intvstr) {
		$optages = array();
		$intervals = explode(';;', $intvstr);
		foreach($intervals as $kintv => $intv) {
			if (empty($intv)) continue;
			$parts = explode('_', $intv);
			if (count($parts) == 3) {
				$optages[$kintv] = $parts[0].' - '.$parts[1];
			}
		}
		return $optages;
	}

	public static function dayValidTs($days, $first, $second) {
		$secdiff = $second - $first;
		$daysdiff = $secdiff / 86400;
		if (is_int($daysdiff)) {
			if ($daysdiff < 1) {
				$daysdiff = 1;
			}
		} else {
			if ($daysdiff < 1) {
				$daysdiff = 1;
			} else {
				$sum = floor($daysdiff) * 86400;
				$newdiff = $secdiff - $sum;
				$maxhmore = self::getHoursMoreRb() * 3600;
				if ($maxhmore >= $newdiff) {
					$daysdiff = floor($daysdiff);
				} else {
					$daysdiff = ceil($daysdiff);
				}
			}
		}
		return ($daysdiff == $days ? true : false);
	}

	public static function sayCostPlusIva($cost, $idprice) {
		$dbo = JFactory::getDBO();
		$session = JFactory::getSession();
		$sval = $session->get('vbivaInclusa', '');
		if(strlen($sval) > 0) {
			$ivainclusa = $sval;
		}else {
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='ivainclusa';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$iva = $dbo->loadAssocList();
			$session->set('vbivaInclusa', $iva[0]['setting']);
			$ivainclusa = $iva[0]['setting'];
		}
		if (intval($ivainclusa) == 0) {
			$q = "SELECT `p`.`idiva`,`i`.`aliq` FROM `#__vikbooking_prices` AS `p` LEFT JOIN `#__vikbooking_iva` `i` ON `i`.`id`=`p`.`idiva` WHERE `p`.`id`='" . (int)$idprice . "';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$paliq = $dbo->loadAssocList();
				if(!empty($paliq[0]['aliq'])) {
					$subt = 100 + $paliq[0]['aliq'];
					$op = ($cost * $subt / 100);
					return $op;
				}
			}
		}
		return $cost;
	}

	public static function sayCostMinusIva($cost, $idprice) {
		$dbo = JFactory::getDBO();
		$session = JFactory::getSession();
		$sval = $session->get('vbivaInclusa', '');
		if(strlen($sval) > 0) {
			$ivainclusa = $sval;
		}else {
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='ivainclusa';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$iva = $dbo->loadAssocList();
			$session->set('vbivaInclusa', $iva[0]['setting']);
			$ivainclusa = $iva[0]['setting'];
		}
		if (intval($ivainclusa) == 1) {
			$q = "SELECT `p`.`idiva`,`i`.`aliq` FROM `#__vikbooking_prices` AS `p` LEFT JOIN `#__vikbooking_iva` `i` ON `i`.`id`=`p`.`idiva` WHERE `p`.`id`='" . (int)$idprice . "';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$paliq = $dbo->loadAssocList();
				if(!empty($paliq[0]['aliq'])) {
					$subt = 100 + $paliq[0]['aliq'];
					$op = ($cost * 100 / $subt);
					return $op;
				}
			}
		}
		return $cost;
	}

	public static function sayOptionalsPlusIva($cost, $idiva) {
		$dbo = JFactory::getDBO();
		$session = JFactory::getSession();
		$sval = $session->get('vbivaInclusa', '');
		if(strlen($sval) > 0) {
			$ivainclusa = $sval;
		}else {
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='ivainclusa';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$iva = $dbo->loadAssocList();
			$session->set('vbivaInclusa', $iva[0]['setting']);
			$ivainclusa = $iva[0]['setting'];
		}
		if (intval($ivainclusa) == 0) {
			$q = "SELECT `aliq` FROM `#__vikbooking_iva` WHERE `id`='" . (int)$idiva . "';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() == 1) {
				$piva = $dbo->loadAssocList();
				$subt = 100 + $piva[0]['aliq'];
				$op = ($cost * $subt / 100);
				return $op;
			}
		}
		return $cost;
	}

	public static function sayOptionalsMinusIva($cost, $idiva) {
		$dbo = JFactory::getDBO();
		$session = JFactory::getSession();
		$sval = $session->get('vbivaInclusa', '');
		if(strlen($sval) > 0) {
			$ivainclusa = $sval;
		}else {
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='ivainclusa';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$iva = $dbo->loadAssocList();
			$session->set('vbivaInclusa', $iva[0]['setting']);
			$ivainclusa = $iva[0]['setting'];
		}
		if (intval($ivainclusa) == 1) {
			$q = "SELECT `aliq` FROM `#__vikbooking_iva` WHERE `id`='" . (int)$idiva . "';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() == 1) {
				$piva = $dbo->loadAssocList();
				$subt = 100 + $piva[0]['aliq'];
				$op = ($cost * 100 / $subt);
				return $op;
			}
		}
		return $cost;
	}

	public static function sayPackagePlusIva($cost, $idiva) {
		$dbo = JFactory::getDBO();
		$session = JFactory::getSession();
		$sval = $session->get('vbivaInclusa', '');
		if(strlen($sval) > 0) {
			$ivainclusa = $sval;
		}else {
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='ivainclusa';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$iva = $dbo->loadAssocList();
			$session->set('vbivaInclusa', $iva[0]['setting']);
			$ivainclusa = $iva[0]['setting'];
		}
		if (intval($ivainclusa) == 0) {
			$q = "SELECT `aliq` FROM `#__vikbooking_iva` WHERE `id`='" . (int)$idiva . "';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() == 1) {
				$piva = $dbo->loadAssocList();
				$subt = 100 + $piva[0]['aliq'];
				$op = ($cost * $subt / 100);
				return $op;
			}
		}
		return $cost;
	}

	public static function sayPackageMinusIva($cost, $idiva) {
		$dbo = JFactory::getDBO();
		$session = JFactory::getSession();
		$sval = $session->get('vbivaInclusa', '');
		if(strlen($sval) > 0) {
			$ivainclusa = $sval;
		}else {
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='ivainclusa';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$iva = $dbo->loadAssocList();
			$session->set('vbivaInclusa', $iva[0]['setting']);
			$ivainclusa = $iva[0]['setting'];
		}
		if (intval($ivainclusa) == 1) {
			$q = "SELECT `aliq` FROM `#__vikbooking_iva` WHERE `id`='" . (int)$idiva . "';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() == 1) {
				$piva = $dbo->loadAssocList();
				$subt = 100 + $piva[0]['aliq'];
				$op = ($cost * 100 / $subt);
				return $op;
			}
		}
		return $cost;
	}
	
	public static function getSecretLink() {
		$sid = mt_rand();
		$dbo = JFactory::getDBO();
		$q = "SELECT `sid` FROM `#__vikbooking_orders`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if (@ $dbo->getNumRows() > 0) {
			$all = $dbo->loadAssocList();
			foreach ($all as $s) {
				$arr[] = $s['sid'];
			}
			if (in_array($sid, $arr)) {
				while (in_array($sid, $arr)) {
					$sid++;
				}
			}
		}
		return $sid;
	}
	
	public static function generateConfirmNumber($oid, $update = true) {
		$confirmnumb = date('ym');
		$confirmnumb .= (string)rand(100, 999);
		$confirmnumb .= (string)rand(10, 99);
		$confirmnumb .= (string)$oid;
		if($update) {
			$dbo = JFactory::getDBO();
			$q="UPDATE `#__vikbooking_orders` SET `confirmnumber`='".$confirmnumb."' WHERE `id`='".$oid."';";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
		return $confirmnumb;
	}
	
	public static function buildCustData($arr, $sep) {
		$cdata = "";
		foreach ($arr as $k => $e) {
			if (strlen($e)) {
				$cdata .= (strlen($k) > 0 ? $k . ": " : "") . $e . $sep;
			}
		}
		return $cdata;
	}

	public static function sendAdminMail($to, $subject, $ftitle, $ts, $custdata, $rooms, $first, $second, $pricestr, $optstr, $tot, $status, $payname = "", $couponstr = "", $arrpeople = "", $confirmnumber = "") {
		$emailparts = explode(';_;', $to);
		$to = $emailparts[0];
		$replyto = $emailparts[1];
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='currencyname';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$currencyname = $dbo->loadResult();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='dateformat';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$formdate = $dbo->loadResult();
		if ($formdate == "%d/%m/%Y") {
			$df = 'd/m/Y';
		} elseif ($formdate == "%m/%d/%Y") {
			$df = 'm/d/Y';
		} else {
			$df = 'Y/m/d';
		}
		$roomsnum = count($rooms);
		$msg = $ftitle . "\n\n";
		$msg .= JText::_('VBLIBONE') . " " . date($df . ' H:i', $ts) . "\n";
		$msg .= JText::_('VBLIBTWO') . ":\n" . $custdata . "\n";
		$msg .= JText::_('VBLIBTHREE') . ": " . $roomsnum . "\n";
		$msg .= JText::_('VBLIBFOUR') . " " . date($df . ' H:i', $first) . "\n";
		$msg .= JText::_('VBLIBFIVE') . " " . date($df . ' H:i', $second) . "\n\n";
		foreach($rooms as $num => $r) {
			$msg .= ($roomsnum > 1 ? JText::_('VBMAILROOMNUM')."".$num.": " : "").$r['name'];
			//Rooms Distinctive Features
			$distinctive_features = array();
			$rparams = json_decode($r['params'], true);
			if(array_key_exists('features', $rparams) && count($rparams['features']) > 0 && array_key_exists('roomindex', $r) && !empty($r['roomindex']) && array_key_exists($r['roomindex'], $rparams['features'])) {
				$distinctive_features = $rparams['features'][$r['roomindex']];
			}
			if(count($distinctive_features)) {
				foreach ($distinctive_features as $dfk => $dfv) {
					if(strlen($dfv)) {
						//get the first non-empty distinctive feature of the room
						$msg .= " - ".JText::_($dfk).': '.$dfv;
						break;
					}
				}
			}
			//
			$msg .= "\n";
			$msg .= JText::_('VBMAILADULTS').": ".intval($arrpeople[$num]['adults']) . "\n";
			if($arrpeople[$num]['children'] > 0) {
				$msg .= JText::_('VBMAILCHILDREN').": ".$arrpeople[$num]['children'] . "\n";
			}
			$msg .= $pricestr[$num] . "\n";
			$allopts = "";
			if (count($optstr[$num]) > 0) {
				foreach($optstr[$num] as $oo) {
					$allopts .= $oo;
				}
			}
			$msg .= $allopts . "\n";
		}
		//vikbooking 1.1 coupon
		if(strlen($couponstr) > 0) {
			$expcoupon = explode(";", $couponstr);
			$msg .= JText::_('VBCOUPON')." ".$expcoupon[2].": -" . $expcoupon[1] . " " . $currencyname . "\n\n";
		}
		//
		$msg .= JText::_('VBLIBSIX') . ": " . $tot . " " . $currencyname . "\n\n";
		if (!empty ($payname)) {
			$msg .= JText::_('VBLIBPAYNAME') . ": " . $payname . "\n\n";
		}
		$msg .= JText::_('VBLIBSEVEN') . ": " . $status;
		
		//Confirmation Number
		if (strlen($confirmnumber) > 0) {
			$msg .= "\n\n".JText::_('VBCONFIRMNUMB') . ": " . $confirmnumber;
		}
		//
		
		//$subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
		$mailer = JFactory::getMailer();
		$adsendermail = self::getSenderMail();
		$sender = array($adsendermail, $adsendermail);
		$mailer->setSender($sender);
		$mailer->addRecipient($to);
		$mailer->addReplyTo($adsendermail);
		$mailer->setSubject($subject);
		$mailer->setBody($msg);
		$mailer->isHTML(false);
		$mailer->Encoding = 'base64';
		$mailer->Send();
		
		return true;
	}
	
	public static function loadEmailTemplate () {
		define('_VIKBOOKINGEXEC', '1');
		ob_start();
		include JPATH_SITE . DS ."components". DS ."com_vikbooking". DS . "helpers" . DS ."email_tmpl.php";
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	
	public static function parseEmailTemplate ($tmpl, $orderid, $currencyname, $status, $tlogo, $tcname, $todate, $tcustdata, $rooms, $tcheckindate, $tdropdate, $tprices, $topts, $ttot, $tlink, $tfootm, $couponstr, $arrpeople, $confirmnumber) {
		$dbo = JFactory::getDBO();
		$vbo_tn = self::getTranslator();
		$parsed = $tmpl;
		$parsed = str_replace("{logo}", $tlogo, $parsed);
		$parsed = str_replace("{company_name}", $tcname, $parsed);
		$parsed = str_replace("{order_id}", $orderid, $parsed);
		$statusclass = $status == JText::_('VBCOMPLETED') ? "confirmed" : "standby";
		$statusclass = $status == JText::_('VBCANCELLED') ? "cancelled" : $statusclass;
		$parsed = str_replace("{order_status_class}", $statusclass, $parsed);
		$parsed = str_replace("{order_status}", $status, $parsed);
		$parsed = str_replace("{order_date}", $todate, $parsed);
		//PIN Code
		if($status == JText::_('VBCOMPLETED') && self::customersPinEnabled()) {
			$cpin = self::getCPinIstance();
			$customer_pin = $cpin->getPinCodeByOrderId($orderid);
			if(!empty($customer_pin)) {
				$tcustdata .= '<h3>'.JText::_('VBYOURPIN').': '.$customer_pin.'</h3>';
			}
		}
		//
		$parsed = str_replace("{customer_info}", $tcustdata, $parsed);
		//Confirmation Number
		if (strlen($confirmnumber) > 0) {
			$parsed = str_replace("{confirmnumb}", $confirmnumber, $parsed);
		}else {
			$parsed = preg_replace('#('.preg_quote('{confirmnumb_delimiter}').')(.*)('.preg_quote('{/confirmnumb_delimiter}').')#si', '$1'.' '.'$3', $parsed);
		}
		$parsed = str_replace("{confirmnumb_delimiter}", "", $parsed);
		$parsed = str_replace("{/confirmnumb_delimiter}", "", $parsed);
		//
		$roomsnum = count($rooms);
		$parsed = str_replace("{rooms_count}", $roomsnum, $parsed);
		$roomstr = "";
		//Rooms Distinctive Features
		preg_match_all('/\{roomfeature ([a-zA-Z0-9]+)\}/U', $parsed, $matches);
		//
		foreach($rooms as $num => $r) {
			$roomstr .= "<strong>".$r['name']."</strong> ".$arrpeople[$num]['adults']." ".($arrpeople[$num]['adults'] > 1 ? JText::_('VBMAILADULTS') : JText::_('VBMAILADULT')).($arrpeople[$num]['children'] > 0 ? ", ".$arrpeople[$num]['children']." ".($arrpeople[$num]['children'] > 1 ? JText::_('VBMAILCHILDREN') : JText::_('VBMAILCHILD')) : "")."<br/>";
			//Rooms Distinctive Features
			if (is_array($matches[1]) && @count($matches[1]) > 0) {
				$distinctive_features = array();
				$rparams = json_decode($r['params'], true);
				if(array_key_exists('features', $rparams) && count($rparams['features']) > 0 && array_key_exists('roomindex', $r) && !empty($r['roomindex']) && array_key_exists($r['roomindex'], $rparams['features'])) {
					$distinctive_features = $rparams['features'][$r['roomindex']];
				}
				$docheck = (count($distinctive_features) > 0);
				foreach($matches[1] as $reqf) {
					$feature_found = false;
					if($docheck) {
						foreach ($distinctive_features as $dfk => $dfv) {
							if(stripos($dfk, $reqf) !== false) {
								$feature_found = $dfk;
								if(strlen(trim($dfk)) == strlen(trim($reqf))) {
									break;
								}
							}
						}
					}
					if($feature_found !== false && strlen($distinctive_features[$feature_found]) > 0) {
						$roomstr .= JText::_($feature_found).': '.$distinctive_features[$feature_found].'<br/>';
					}
					$parsed = str_replace("{roomfeature ".$reqf."}", "", $parsed);
				}
			}
			//
		}
		$parsed = str_replace("{rooms_info}", $roomstr, $parsed);
		$parsed = str_replace("{checkin_date}", $tcheckindate, $parsed);
		$parsed = str_replace("{checkout_date}", $tdropdate, $parsed);
		//order details
		$orderdetails = "";
		foreach($rooms as $num => $r) {
			$expdet = explode("\n", $tprices[$num]);
			$faredets = explode(":", $expdet[0]);
			$orderdetails .= '<div class="roombooked"><strong>'.$r['name'].'</strong><br/>'.$faredets[0];
			if(!empty($expdet[1])) {
				$attrfaredets = explode(":", $expdet[1]);
				if(strlen($attrfaredets[1]) > 0) {
					$orderdetails .= ' - '.$attrfaredets[0].':'.$attrfaredets[1];
				}
			}
			$fareprice = trim(str_replace($currencyname, "", $faredets[1]));
			$orderdetails .= '<div style="float: right;"><span>'.$currencyname.' '.self::numberFormat($fareprice).'</span></div></div>';
			//options
			if(count($topts[$num]) > 0) {
				foreach($topts[$num] as $oo) {
					$expopts = explode("\n", $oo);
					foreach($expopts as $optinfo) {
						if(!empty($optinfo)) {
							$splitopt = explode(":", $optinfo);
							$optprice = trim(str_replace($currencyname, "", $splitopt[1]));
							$orderdetails .= '<div class="roomoption"><span>'.$splitopt[0].'</span><div style="float: right;"><span>'.$currencyname.' '.self::numberFormat($optprice).'</span></div></div>';
						}
					}
				}
			}
			//
			if ($roomsnum > 1 && $num < $roomsnum) {
				$orderdetails .= '<br/>';
			}
		}
		//
		//coupon
		if(strlen($couponstr) > 0) {
			$expcoupon = explode(";", $couponstr);
			$orderdetails .= '<br/><div class="discount"><span>'.JText::_('VBCOUPON').' '.$expcoupon[2].'</span><div style="float: right;"><span>- '.$currencyname.' '.self::numberFormat($expcoupon[1]).'</span></div></div>';
		}
		//
		//discount payment method
		$q = "SELECT `idpayment` FROM `#__vikbooking_orders` WHERE `id`=".(int)$orderid.";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() == 1 && $status != JText::_('VBCANCELLED')) {
			$idpayment = $dbo->loadResult();
			if (!empty ($idpayment)) {
				$exppay = explode('=', $idpayment);
				$payment = vikbooking::getPayment($exppay[0], $vbo_tn);
				if(is_array($payment)) {
					if($payment['charge'] > 0.00 && $payment['ch_disc'] != 1) {
						//Discount (not charge)
						if($payment['val_pcent'] == 1) {
							//fixed value
							$ttot -= $payment['charge'];
							$orderdetails .= '<br/><div class="discount"><span>'.$payment['name'].'</span><div style="float: right;"><span>- '.$currencyname.' '.self::numberFormat($payment['charge']).'</span></div></div>';
						}else {
							//percent value
							$percent_disc = $ttot * $payment['charge'] / 100;
							$ttot -= $percent_disc;
							$orderdetails .= '<br/><div class="discount"><span>'.$payment['name'].'</span><div style="float: right;"><span>- '.$currencyname.' '.self::numberFormat($percent_disc).'</span></div></div>';
						}
					}
				}
			}
		}
		//
		$parsed = str_replace("{order_details}", $orderdetails, $parsed);
		//
		$parsed = str_replace("{order_total}", $currencyname.' '.self::numberFormat($ttot), $parsed);
		$parsed = str_replace("{order_link}", '<a href="'.$tlink.'">'.$tlink.'</a>', $parsed);
		$parsed = str_replace("{footer_emailtext}", $tfootm, $parsed);
		//deposit
		$deposit_str = '';
		if($status != JText::_('VBCOMPLETED') && $status != JText::_('VBCANCELLED') && !self::payTotal()) {
			$percentdeposit = self::getAccPerCent();
			if ($percentdeposit > 0) {
				if(self::getTypeDeposit() == "fixed") {
					$deposit_amount = $percentdeposit;
				}else {
					$deposit_amount = $ttot * $percentdeposit / 100;
				}
				if($deposit_amount > 0) {
					$deposit_str = '<div class="deposit"><span>'.JText::_('VBLEAVEDEPOSIT').'</span><div style="float: right;"><strong>'.$currencyname.' '.self::numberFormat($deposit_amount).'</strong></div></div>';
				}
			}
		}
		$parsed = str_replace("{order_deposit}", $deposit_str, $parsed);
		//
		//Amount Paid - Remaining Balance
		$totpaid_str = '';
		$q = "SELECT `totpaid` FROM `#__vikbooking_orders` WHERE `id`=".(int)$orderid.";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() == 1 && $status != JText::_('VBCANCELLED')) {
			$tot_paid = $dbo->loadResult();
			$diff_topay = (float)$ttot - (float)$tot_paid;
			if((float)$tot_paid > 0) {
				$totpaid_str .= '<div class="amountpaid"><span>'.JText::_('VBAMOUNTPAID').'</span><div style="float: right;"><strong>'.$currencyname.' '.self::numberFormat($tot_paid).'</strong></div></div>';
				//only in case the remaining balance is greater than 1 to avoid commissions issues
				if($diff_topay > 1) {
					$totpaid_str .= '<div class="amountpaid"><span>'.JText::_('VBTOTALREMAINING').'</span><div style="float: right;"><strong>'.$currencyname.' '.self::numberFormat($diff_topay).'</strong></div></div>';
				}
			}
		}
		$parsed = str_replace("{order_total_paid}", $totpaid_str, $parsed);
		//
		
		return $parsed;
	}
	
	public static function sendCustMail($to, $subject, $ftitle, $ts, $custdata, $rooms, $first, $second, $pricestr, $optstr, $tot, $link, $status, $orderid = "", $strcouponeff = "", $arrpeople = "", $confirmnumber = "") {
		$subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
		$dbo = JFactory::getDBO();
		$vbo_tn = self::getTranslator();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='currencyname';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$currencyname = $dbo->loadResult();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='adminemail';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$adminemail = $dbo->loadResult();
		$q = "SELECT `id`,`setting` FROM `#__vikbooking_texts` WHERE `param`='footerordmail';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$ft = $dbo->loadAssocList();
		$vbo_tn->translateContents($ft, '#__vikbooking_texts');
		$q = "SELECT `id`,`setting` FROM `#__vikbooking_config` WHERE `param`='sendjutility';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$sendmethod = $dbo->loadAssocList();
		$useju = intval($sendmethod[0]['setting']) == 1 ? true : false;
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='sitelogo';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$sitelogo = $dbo->loadResult();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='dateformat';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$formdate = $dbo->loadResult();
		if ($formdate == "%d/%m/%Y") {
			$df = 'd/m/Y';
		} elseif ($formdate == "%m/%d/%Y") {
			$df = 'm/d/Y';
		} else {
			$df = 'Y/m/d';
		}
		$footerordmail = $ft[0]['setting'];
		$textfooterordmail = strip_tags($footerordmail);
		$roomsnum = count($rooms);
		//text part
		$msg = $ftitle . "\n\n";
		$msg .= JText::_('VBLIBEIGHT') . " " . date($df . ' H:i', $ts) . "\n";
		$msg .= JText::_('VBLIBNINE') . ":\n" . $custdata . "\n";
		$msg .= JText::_('VBLIBTEN') . ": " . $roomsnum . "\n";
		$msg .= JText::_('VBLIBELEVEN') . " " . date($df . ' H:i', $first) . "\n";
		$msg .= JText::_('VBLIBTWELVE') . " " . date($df . ' H:i', $second) . "\n";
		foreach($rooms as $num => $r) {
			$msg .= ($roomsnum > 1 ? JText::_('VBMAILROOMNUM')." ".$num.": " : "").$r['name']."\n";
			$msg .= JText::_('VBMAILADULTS').": ".intval($arrpeople[$num]['adults']) . "\n";
			if($arrpeople[$num]['children'] > 0) {
				$msg .= JText::_('VBMAILCHILDREN').": ".$arrpeople[$num]['children'] . "\n";
			}
			$msg .= $pricestr[$num] . "\n";
			$allopts = "";
			if (count($optstr[$num]) > 0) {
				foreach($optstr[$num] as $oo) {
					$allopts .= $oo;
				}
			}
			$msg .= $allopts . "\n";
		}
		$msg .= JText::_('VBLIBSIX') . " " . $tot . " " . $currencyname . "\n";
		$msg .= JText::_('VBLIBSEVEN') . ": " . $status . "\n\n";
		//Confirmation Number
		if (strlen($confirmnumber) > 0) {
			$msg .= JText::_('VBCONFIRMNUMB') . ": " . $confirmnumber . "\n\n";
		}
		//
		$msg .= JText::_('VBLIBTENTHREE') . ": \n" . $link;
		$msg .= (strlen(trim($textfooterordmail)) > 0 ? "\n" . $textfooterordmail : "");
		//
		//html part
		$from_name = $adminemail;
		$from_address = $adminemail;
		$reply_name = $from_name;
		$reply_address = $from_address;
		$reply_address = $from_address;
		$error_delivery_name = $from_name;
		$error_delivery_address = $from_address;
		$to_name = $to;
		$to_address = $to;
		//vikbooking 1.1
		$tmpl = self::loadEmailTemplate();
		//
		if (!$useju) {
			require_once ("./components/com_vikbooking/class/email_message.php");
			$email_message = new email_message_class;
			$email_message->SetEncodedEmailHeader("To", $to_address, $to_name);
			$email_message->SetEncodedEmailHeader("From", $from_address, $from_name);
			$email_message->SetEncodedEmailHeader("Reply-To", $reply_address, $reply_name);
			$email_message->SetHeader("Sender", $from_address);
			//			if(defined("PHP_OS")
			//			&& strcmp(substr(PHP_OS,0,3),"WIN"))
			//				$email_message->SetHeader("Return-Path",$error_delivery_address);

			$email_message->SetEncodedHeader("Subject", $subject);
			$attachlogo = false;
			if (!empty ($sitelogo) && @ file_exists('./administrator/components/com_vikbooking/resources/' . $sitelogo)) {
				$image = array (
				"FileName" => JURI::root() . "administrator/components/com_vikbooking/resources/" . $sitelogo, "Content-Type" => "automatic/name", "Disposition" => "inline");
				$email_message->CreateFilePart($image, $image_part);
				$image_content_id = $email_message->GetPartContentID($image_part);
				$attachlogo = true;
			}
			$tlogo = ($attachlogo ? "<img src=\"cid:" . $image_content_id . "\" alt=\"".$ftitle." Logo\"/>\n" : "");
		} else {
			$attachlogo = false;
			if (!empty ($sitelogo) && @ file_exists('./administrator/components/com_vikbooking/resources/' . $sitelogo)) {
				$attachlogo = true;
			}
			$tlogo = ($attachlogo ? "<img src=\"" . JURI::root() . "administrator/components/com_vikbooking/resources/" . $sitelogo . "\" alt=\"".$ftitle." Logo\"/>\n" : "");
		}
		//vikbooking 1.1
		$tcname = $ftitle."\n";
		$todate = date($df . ' H:i', $ts)."\n";
		$tcustdata = nl2br($custdata)."\n";
		$tiname = $rooms;
		$tcheckindate = date($df . ' H:i', $first)."\n";
		$tdropdate = date($df . ' H:i', $second)."\n";
		$tprices = $pricestr;
		$topts = $optstr;
		$ttot = $tot."\n";
		$tlink = $link;
		$tfootm = $footerordmail;
		$hmess = self::parseEmailTemplate($tmpl, $orderid, $currencyname, $status, $tlogo, $tcname, $todate, $tcustdata, $tiname, $tcheckindate, $tdropdate, $tprices, $topts, $ttot, $tlink, $tfootm, $strcouponeff, $arrpeople, $confirmnumber);
		//
		
		if (!$useju) {
			$email_message->CreateQuotedPrintableHTMLPart($hmess, "", $html_part);
			$email_message->CreateQuotedPrintableTextPart($email_message->WrapText($msg), "", $text_part);
			$alternative_parts = array (
				$text_part,
				$html_part
			);
			$email_message->CreateAlternativeMultipart($alternative_parts, $alternative_part);
			$related_parts = array (
				$alternative_part,
				$image_part
			);
			$email_message->AddRelatedMultipart($related_parts);
			$error = $email_message->Send();
			if (strcmp($error, "")) {
				//$msg = utf8_decode($msg);
				@ mail($to, $subject, $msg, "MIME-Version: 1.0" . "\r\n" . "Content-type: text/plain; charset=UTF-8");
			}
		} else {
			$hmess = '<html>'."\n".'<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>'."\n".'<body>'.$hmess.'</body>'."\n".'</html>';
			$mailer = JFactory::getMailer();
			$adsendermail = self::getSenderMail();
			$sender = array($adsendermail, $adsendermail);
			$mailer->setSender($sender);
			$mailer->addRecipient($to);
			$mailer->addReplyTo($adsendermail);
			$mailer->setSubject($subject);
			$mailer->setBody($hmess);
			$mailer->isHTML(true);
			$mailer->Encoding = 'base64';
			$mailer->Send();
		}
		//
		
		return true;
	}

	public static function sendCustMailFromBack($to, $subject, $ftitle, $ts, $custdata, $rooms, $first, $second, $pricestr, $optstr, $tot, $link, $status, $orderid = "", $strcouponeff = "", $arrpeople = "", $confirmnumber = "") {
		//this public static function is called from the administrator site
		$subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
		$dbo = JFactory::getDBO();
		$vbo_tn = self::getTranslator();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='currencyname';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$currencyname = $dbo->loadResult();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='adminemail';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$adminemail = $dbo->loadResult();
		$q = "SELECT `id`,`setting` FROM `#__vikbooking_texts` WHERE `param`='footerordmail';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$ft = $dbo->loadAssocList();
		$vbo_tn->translateContents($ft, '#__vikbooking_texts');
		$q = "SELECT `id`,`setting` FROM `#__vikbooking_config` WHERE `param`='sendjutility';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$sendmethod = $dbo->loadAssocList();
		$useju = intval($sendmethod[0]['setting']) == 1 ? true : false;
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='sitelogo';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$sitelogo = $dbo->loadResult();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='dateformat';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$formdate = $dbo->loadResult();
		if ($formdate == "%d/%m/%Y") {
			$df = 'd/m/Y';
		} elseif ($formdate == "%m/%d/%Y") {
			$df = 'm/d/Y';
		} else {
			$df = 'Y/m/d';
		}
		$footerordmail = $ft[0]['setting'];
		$textfooterordmail = strip_tags($footerordmail);
		$roomsnum = count($rooms);
		//text part
		$msg = $ftitle . "\n\n";
		$msg .= JText::_('VBLIBEIGHT') . " " . date($df . ' H:i', $ts) . "\n";
		$msg .= JText::_('VBLIBNINE') . ":\n" . $custdata . "\n";
		$msg .= JText::_('VBLIBTEN') . ": " . $roomsnum . "\n";
		$msg .= JText::_('VBLIBELEVEN') . " " . date($df . ' H:i', $first) . "\n";
		$msg .= JText::_('VBLIBTWELVE') . " " . date($df . ' H:i', $second) . "\n";
		foreach($rooms as $num => $r) {
			$msg .= ($roomsnum > 1 ? JText::_('VBMAILROOMNUM')." ".$num.": " : "").$r['name']."\n";
			$msg .= JText::_('VBMAILADULTS').": ".intval($arrpeople[$num]['adults']) . "\n";
			if($arrpeople[$num]['children'] > 0) {
				$msg .= JText::_('VBMAILCHILDREN').": ".$arrpeople[$num]['children'] . "\n";
			}
			$msg .= $pricestr[$num] . "\n";
			$allopts = "";
			if (count($optstr[$num]) > 0) {
				foreach($optstr[$num] as $oo) {
					$allopts .= $oo;
				}
			}
			$msg .= $allopts . "\n";
		}
		$msg .= JText::_('VBLIBSIX') . " " . $tot . " " . $currencyname . "\n";
		$msg .= JText::_('VBLIBSEVEN') . ": " . $status . "\n\n";
		//Confirmation Number
		if (strlen($confirmnumber) > 0) {
			$msg .= JText::_('VBCONFIRMNUMB') . ": " . $confirmnumber . "\n\n";
		}
		//
		$msg .= JText::_('VBLIBTENTHREE') . ": \n" . $link;
		$msg .= (strlen(trim($textfooterordmail)) > 0 ? "\n" . $textfooterordmail : "");
		//
		//html part
		$from_name = $adminemail;
		$from_address = $adminemail;
		$reply_name = $from_name;
		$reply_address = $from_address;
		$reply_address = $from_address;
		$error_delivery_name = $from_name;
		$error_delivery_address = $from_address;
		$to_name = $to;
		$to_address = $to;
		//vikbooking 1.1
		$tmpl = self::loadEmailTemplate();
		//
		if (!$useju) {
			require_once ("../components/com_vikbooking/class/email_message.php");
			$email_message = new email_message_class;
			$email_message->SetEncodedEmailHeader("To", $to_address, $to_name);
			$email_message->SetEncodedEmailHeader("From", $from_address, $from_name);
			$email_message->SetEncodedEmailHeader("Reply-To", $reply_address, $reply_name);
			$email_message->SetHeader("Sender", $from_address);
			//			if(defined("PHP_OS")
			//			&& strcmp(substr(PHP_OS,0,3),"WIN"))
			//				$email_message->SetHeader("Return-Path",$error_delivery_address);

			$email_message->SetEncodedHeader("Subject", $subject);
			$attachlogo = false;
			if (!empty ($sitelogo) && @ file_exists('./components/com_vikbooking/resources/' . $sitelogo)) {
				$image = array (
				"FileName" => JURI::root() . "administrator/components/com_vikbooking/resources/" . $sitelogo, "Content-Type" => "automatic/name", "Disposition" => "inline");
				$email_message->CreateFilePart($image, $image_part);
				$image_content_id = $email_message->GetPartContentID($image_part);
				$attachlogo = true;
			}
			$tlogo = ($attachlogo ? "<img src=\"cid:" . $image_content_id . "\" alt=\"".$ftitle." Logo\"/>\n" : "");
		} else {
			$attachlogo = false;
			if (!empty ($sitelogo) && @ file_exists('./components/com_vikbooking/resources/' . $sitelogo)) {
				$attachlogo = true;
			}
			$tlogo = ($attachlogo ? "<img src=\"" . JURI::root() . "administrator/components/com_vikbooking/resources/" . $sitelogo . "\" alt=\"".$ftitle." Logo\"/>\n" : "");
		}
		//vikbooking 1.1
		$tcname = $ftitle."\n";
		$todate = date($df . ' H:i', $ts)."\n";
		$tcustdata = nl2br($custdata)."\n";
		$tiname = $rooms;
		$tcheckindate = date($df . ' H:i', $first)."\n";
		$tdropdate = date($df . ' H:i', $second)."\n";
		$tprices = $pricestr;
		$topts = $optstr;
		$ttot = $tot."\n";
		$tlink = $link;
		$tfootm = $footerordmail;
		$hmess = self::parseEmailTemplate($tmpl, $orderid, $currencyname, $status, $tlogo, $tcname, $todate, $tcustdata, $tiname, $tcheckindate, $tdropdate, $tprices, $topts, $ttot, $tlink, $tfootm, $strcouponeff, $arrpeople, $confirmnumber);
		//
		
		if (!$useju) {
			$email_message->CreateQuotedPrintableHTMLPart($hmess, "", $html_part);
			$email_message->CreateQuotedPrintableTextPart($email_message->WrapText($msg), "", $text_part);
			$alternative_parts = array (
				$text_part,
				$html_part
			);
			$email_message->CreateAlternativeMultipart($alternative_parts, $alternative_part);
			$related_parts = array (
				$alternative_part,
				$image_part
			);
			$email_message->AddRelatedMultipart($related_parts);
			$error = $email_message->Send();
			if (strcmp($error, "")) {
				//$msg = utf8_decode($msg);
				@ mail($to, $subject, $msg, "MIME-Version: 1.0" . "\r\n" . "Content-type: text/plain; charset=UTF-8");
			}
		} else {
			$hmess = '<html>'."\n".'<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>'."\n".'<body>'.$hmess.'</body>'."\n".'</html>';
			$mailer = JFactory::getMailer();
			$adsendermail = self::getSenderMail();
			$sender = array($adsendermail, $adsendermail);
			$mailer->setSender($sender);
			$mailer->addRecipient($to);
			$mailer->addReplyTo($adsendermail);
			$mailer->setSubject($subject);
			$mailer->setBody($hmess);
			$mailer->isHTML(true);
			$mailer->Encoding = 'base64';
			$mailer->Send();
		}
		//
		
		return true;
	}
	
	public static function sendCustMailByOrderId($oid) {
		//VikChannelManager should be the one calling this function
		$dbo = JFactory::getDBO();
		$q="SELECT * FROM `#__vikbooking_orders` WHERE `id`=".intval($oid).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() == 1) {
			$order=$dbo->loadAssocList();
			//check if the language in use is the same as the one used during the checkout
			$lang = JFactory::getLanguage();
			$usetag = $lang->getTag();
			if (!empty($order[0]['lang'])) {
				if($usetag != $order[0]['lang']) {
					$usetag = $order[0]['lang'];
				}
			}
			$lang->load('com_vikbooking', JPATH_SITE, $usetag, true);
			//
			$q="SELECT `or`.*,`r`.`name`,`r`.`units`,`r`.`fromadult`,`r`.`toadult` FROM `#__vikbooking_ordersrooms` AS `or`,`#__vikbooking_rooms` AS `r` WHERE `or`.`idorder`='".$order[0]['id']."' AND `or`.`idroom`=`r`.`id` ORDER BY `or`.`id` ASC;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$ordersrooms=$dbo->loadAssocList();
			$currencyname = self::getCurrencyName();
			$realback=self::getHoursRoomAvail() * 3600;
			$realback+=$order[0]['checkout'];
			$rooms = array();
			$tars = array();
			$arrpeople = array();
			//send mail
			$ftitle=self::getFrontTitle ();
			$nowts=time();
			$viklink=JURI::root()."index.php?option=com_vikbooking&task=vieworder&sid=".$order[0]['sid']."&ts=".$order[0]['ts'];
			foreach($ordersrooms as $kor => $or) {
				$num = $kor + 1;
				$rooms[$num] = $or;
				$arrpeople[$num]['adults'] = $or['adults'];
				$arrpeople[$num]['children'] = $or['children'];
				$q="SELECT * FROM `#__vikbooking_dispcost` WHERE `id`='".$or['idtar']."';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if($dbo->getNumRows() > 0) {
					$tar = $dbo->loadAssocList();
					$tar = self::applySeasonsRoom($tar, $order[0]['checkin'], $order[0]['checkout']);
					//different usage
					if ($or['fromadult'] <= $or['adults'] && $or['toadult'] >= $or['adults']) {
						$diffusageprice = self::loadAdultsDiff($or['idroom'], $or['adults']);
						//Occupancy Override
						$occ_ovr = self::occupancyOverrideExists($tar, $or['adults']);
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
					$tars[$num] = $tar[0];
				}else {
					return false;
				}
			}
			$pcheckin = $order[0]['checkin'];
			$pcheckout = $order[0]['checkout'];
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
					$maxhmore = self::getHoursMoreRb() * 3600;
					if ($maxhmore >= $newdiff) {
						$daysdiff = floor($daysdiff);
					} else {
						$daysdiff = ceil($daysdiff);
					}
				}
			}
			foreach($ordersrooms as $kor => $or) {
				$num = $kor + 1;
				if (is_array($tars[$num])) {
					$calctar = self::sayCostPlusIva($tars[$num]['cost'], $tars[$num]['idprice']);
					$tars[$num]['calctar'] = $calctar;
					$isdue += $calctar;
					$pricestr[$num] = self::getPriceName($tars[$num]['idprice']) . ": " . $calctar . " " . $currencyname . (!empty ($tars[$num]['attrdata']) ? "\n" . self::getPriceAttr($tars[$num]['idprice']) . ": " . $tars[$num]['attrdata'] : "");
				}
				if (!empty ($or['optionals'])) {
					$stepo = explode(";", $or['optionals']);
					foreach ($stepo as $oo) {
						if (!empty ($oo)) {
							$stept = explode(":", $oo);
							$q = "SELECT * FROM `#__vikbooking_optionals` WHERE `id`=" . $dbo->quote($stept[0]) . ";";
							$dbo->setQuery($q);
							$dbo->Query($q);
							if ($dbo->getNumRows() == 1) {
								$actopt = $dbo->loadAssocList();
								$chvar = '';
								if (!empty($actopt[0]['ageintervals']) && $or['children'] > 0 && strstr($stept[1], '-') != false) {
									$optagecosts = self::getOptionIntervalsCosts($actopt[0]['ageintervals']);
									$optagenames = self::getOptionIntervalsAges($actopt[0]['ageintervals']);
									$agestept = explode('-', $stept[1]);
									$stept[1] = $agestept[0];
									$chvar = $agestept[1];
									$actopt[0]['chageintv'] = $chvar;
									$actopt[0]['name'] .= ' ('.$optagenames[($chvar - 1)].')';
									$actopt[0]['quan'] = $stept[1];
									$realcost = (intval($actopt[0]['perday']) == 1 ? (floatval($optagecosts[($chvar - 1)]) * $order[0]['days'] * $stept[1]) : (floatval($optagecosts[($chvar - 1)]) * $stept[1]));
								}else {
									$actopt[0]['quan'] = $stept[1];
									$realcost = (intval($actopt[0]['perday']) == 1 ? ($actopt[0]['cost'] * $order[0]['days'] * $stept[1]) : ($actopt[0]['cost'] * $stept[1]));
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
								$tmpopr = self::sayOptionalsPlusIva($realcost, $actopt[0]['idiva']);
								$isdue += $tmpopr;
								$optstr[$num][] = ($stept[1] > 1 ? $stept[1] . " " : "") . $actopt[0]['name'] . ": " . $tmpopr . " " . $currencyname . "\n";
							}
						}
					}
				}
			}
			//vikbooking 1.1 coupon
			$usedcoupon = false;
			$origisdue = $isdue;
			if(strlen($order[0]['coupon']) > 0) {
				$usedcoupon = true;
				$expcoupon = explode(";", $order[0]['coupon']);
				$isdue = $isdue - $expcoupon[1];
			}
			//
			//ConfirmationNumber
			$confirmnumber = $order[0]['confirmnumber'];
			//end ConfirmationNumber
			
			if($order[0]['status'] != 'confirmed' && $order[0]['status'] != 'standby') {
				return false;
			}
			
			$langstatus = $order[0]['status'] == 'confirmed' ? JText::_('VBCOMPLETED') : JText::_('VBINATTESA');
			
			self::sendCustMail($order[0]['custmail'], strip_tags($ftitle)." ".JText::_('VBORDNOL'), $ftitle, $nowts, $order[0]['custdata'], $rooms, $order[0]['checkin'], $order[0]['checkout'], $pricestr, $optstr, $isdue, $viklink, $langstatus, $order[0]['id'], $order[0]['coupon'], $arrpeople, $confirmnumber);
			
			return true;
		}
		return false;
	}
	
	public static function paypalForm($imp, $tax, $sid, $ts, $roomname, $currencysymb = "") {
		$dbo = JFactory::getDBO();
		$depositmess = "";
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='paytotal';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		if (intval($s[0]['setting']) == 0) {
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='payaccpercent';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$per = $dbo->loadAssocList();
			if ($per[0]['setting'] > 0) {
				$imp = $imp * $per[0]['setting'] / 100;
				$tax = $tax * $per[0]['setting'] / 100;
				$depositmess = "<p><strong>" . JText::_('VBLEAVEDEPOSIT') . " " . (number_format($imp + $tax, 2)) . " " . $currencysymb . "</strong></p><br/>";
			}
		}
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='ccpaypal';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$acc = $dbo->loadAssocList();
		$q = "SELECT `id`,`setting` FROM `#__vikbooking_texts` WHERE `param`='paymentname';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$payname = $dbo->loadAssocList();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='currencycodepp';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$paypalcurcode = trim($dbo->loadResult());
		$itname = (empty ($payname[0]['setting']) ? $roomname : $payname[0]['setting']);
		$returl = JURI::root() . "index.php?option=com_vikbooking&task=vieworder&sid=" . $sid . "&ts=" . $ts;
		$notifyurl = JURI::root() . "index.php?option=com_vikbooking&task=notifypayment&sid=" . $sid . "&ts=" . $ts;
		$form = "<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">\n";
		$form .= "<input type=\"hidden\" name=\"business\" value=\"" . $acc[0]['setting'] . "\"/>\n";
		$form .= "<input type=\"hidden\" name=\"cmd\" value=\"_xclick\"/>\n";
		$form .= "<input type=\"hidden\" name=\"amount\" value=\"" . number_format($imp, 2) . "\"/>\n";
		$form .= "<input type=\"hidden\" name=\"item_name\" value=\"" . $itname . "\"/>\n";
		$form .= "<input type=\"hidden\" name=\"item_number\" value=\"" . $roomname . "\"/>\n";
		$form .= "<input type=\"hidden\" name=\"quantity\" value=\"1\"/>\n";
		$form .= "<input type=\"hidden\" name=\"tax\" value=\"" . number_format($tax, 2) . "\"/>\n";
		$form .= "<input type=\"hidden\" name=\"shipping\" value=\"0.00\"/>\n";
		$form .= "<input type=\"hidden\" name=\"currency_code\" value=\"" . $paypalcurcode . "\"/>\n";
		$form .= "<input type=\"hidden\" name=\"no_shipping\" value=\"1\"/>\n";
		$form .= "<input type=\"hidden\" name=\"rm\" value=\"2\"/>\n";
		$form .= "<input type=\"hidden\" name=\"notify_url\" value=\"" . $notifyurl . "\"/>\n";
		$form .= "<input type=\"hidden\" name=\"return\" value=\"" . $returl . "\"/>\n";
		$form .= "<input type=\"image\" src=\"https://www.paypal.com/en_US/i/btn/btn_paynow_SM.gif\" name=\"submit\" alt=\"PayPal - The safer, easier way to pay online!\">\n";
		$form .= "</form>\n";
		return $depositmess . $form;
	}

	public static function sendJutility() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='sendjutility';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s = $dbo->loadAssocList();
		return (intval($s[0]['setting']) == 1 ? true : false);
	}

	public static function getCategoryName($idcat) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id`,`name` FROM `#__vikbooking_categories` WHERE `id`=" . $dbo->quote($idcat) . ";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$p = @ $dbo->loadAssocList();
		return $p[0]['name'];
	}
	
	public static function loadAdultsDiff($idroom, $adults) {
		$dbo = JFactory::getDBO();
		$q = "SELECT * FROM `#__vikbooking_adultsdiff` WHERE `idroom`='" . $idroom . "' AND `adults`='".$adults."';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$diff = $dbo->loadAssocList();
			return $diff[0];
		}else {
			return "";
		}
	}

	public static function loadRoomAdultsDiff($idroom) {
		$dbo = JFactory::getDBO();
		$q = "SELECT * FROM `#__vikbooking_adultsdiff` WHERE `idroom`=" . (int)$idroom . " ORDER BY `adults` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$diff = $dbo->loadAssocList();
			$roomdiff = array();
			foreach ($diff as $v) {
				$roomdiff[$v['adults']] = $v;
			}
			return $roomdiff;
		}
		return array();
	}

	public static function occupancyOverrideExists($tar, $adults) {
		foreach ($tar as $k => $v) {
			if(is_array($v) && array_key_exists('occupancy_ovr', $v)) {
				if(array_key_exists($adults, $v['occupancy_ovr'])) {
					return $v['occupancy_ovr'][$adults];
				}
			}
		}
		return false;
	}
	
	public static function getChildrenCharges($idroom, $children, $ages, $num_nights) {
		$charges = array();
		if (!($children > 0) || !(count($ages) > 0)) {
			return $charges;
		}
		$dbo = JFactory::getDBO();
		$id_options = array();
		$q = "SELECT `id`,`idopt` FROM `#__vikbooking_rooms` WHERE `id`=".(int)$idroom.";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$assocs = $dbo->loadAssocList();
			foreach ($assocs as $opts) {
				if (!empty($opts['idopt'])) {
					$r_ido = explode(';', rtrim($opts['idopt']));
					foreach ($r_ido as $ido) {
						if (!empty($ido) && !in_array($ido, $id_options)) {
							$id_options[] = $ido;
						}
					}
				}
			}
		}
		if (count($id_options) > 0) {
			$q = "SELECT * FROM `#__vikbooking_optionals` WHERE `id` IN (".implode(", ", $id_options).") AND `ifchildren`=1 AND (LENGTH(`ageintervals`) > 0 OR `ageintervals` IS NOT NULL) LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$ageintervals = $dbo->loadAssocList();
				$split_ages = explode(';;', $ageintervals[0]['ageintervals']);
				$age_range = array();
				foreach ($split_ages as $kg => $spage) {
					if (empty($spage)) {
						continue;
					}
					$parts = explode('_', $spage);
					if (strlen($parts[0]) > 0 && intval($parts[1]) > 0 && floatval($parts[2]) > 0) {
						$ind = count($age_range);
						$age_range[$ind]['from'] = intval($parts[0]);
						$age_range[$ind]['to'] = intval($parts[1]);
						//taxes are calculated later in VCM
						//$age_range[$ind]['cost'] = self::sayOptionalsPlusIva((floatval($parts[2]) * $num_nights), $ageintervals[0]['idiva']);
						$age_range[$ind]['cost'] = floatval($parts[2]) * $num_nights;
						$age_range[$ind]['option_str'] = $ageintervals[0]['id'].':1-'.($kg + 1);
					}
				}
				if (count($age_range) > 0) {
					$tot_charge = 0;
					$affected = array();
					$option_str = '';
					foreach ($ages as $age) {
						if (strlen($age) == 0) {
							continue;
						}
						foreach ($age_range as $range) {
							if (intval($age) >= $range['from'] && intval($age) <= $range['to']) {
								$tot_charge += $range['cost'];
								$affected[] = $age;
								$option_str .= $range['option_str'].';';
								break;
							}
						}
					}
					if ($tot_charge > 0) {
						$charges['total'] = $tot_charge;
						$charges['affected'] = $affected;
						$charges['options'] = $option_str;
					}
				}
			}
		}
		
		return $charges;
	}
	
	public static function sortRoomPrices($arr) {
		$newarr = array ();
		foreach ($arr as $k => $v) {
			$newarr[$k] = $v['cost'];
		}
		asort($newarr);
		$sorted = array ();
		foreach ($newarr as $k => $v) {
			$sorted[$k] = $arr[$k];
		}
		return $sorted;
	}
	
	public static function sortResults($arr) {
		$newarr = array ();
		foreach ($arr as $k => $v) {
			$newarr[$k] = $v[0]['cost'];
		}
		asort($newarr);
		$sorted = array ();
		foreach ($newarr as $k => $v) {
			$sorted[$k] = $arr[$k];
		}
		return $sorted;
	}
	
	public static function sortMultipleResults($arr) {
		foreach ($arr as $k => $v) {
			$newarr = array ();
			foreach ($v as $subk => $subv) {
				$newarr[$subk] = $subv[0]['cost'];
			}
			asort($newarr);
			$sorted = array ();
			foreach ($newarr as $nk => $v) {
				$sorted[$nk] = $arr[$k][$nk];
			}
			$arr[$k] = $sorted;
		}
		return $arr;
	}

	public static function applySeasonalPrices($arr, $from, $to) {
		$dbo = JFactory::getDBO();
		$vbo_tn = self::getTranslator();
		$roomschange = array();
		$one = getdate($from);
		//leap years
		if(($one['year'] % 4) == 0 && ($one['year'] % 100 != 0 || $one['year'] % 400 == 0)) {
			$isleap = true;
		}else {
			$isleap = false;
		}
		//
		$baseone = mktime(0, 0, 0, 1, 1, $one['year']);
		$tomidnightone = intval($one['hours']) * 3600;
		$tomidnightone += intval($one['minutes']) * 60;
		$sfrom = $from - $baseone - $tomidnightone;
		$fromdayts = mktime(0, 0, 0, $one['mon'], $one['mday'], $one['year']);
		$two = getdate($to);
		$basetwo = mktime(0, 0, 0, 1, 1, $two['year']);
		$tomidnighttwo = intval($two['hours']) * 3600;
		$tomidnighttwo += intval($two['minutes']) * 60;
		$sto = $to - $basetwo - $tomidnighttwo;
		//leap years, last day of the month of the season
		if($isleap) {
			$leapts = mktime(0, 0, 0, 2, 29, $two['year']);
			if($two[0] >= $leapts) {
				$sfrom -= 86400;
				$sto -= 86400;
			}
		}
		//
		$q = "SELECT * FROM `#__vikbooking_seasons` WHERE (" .
		 ($sto > $sfrom ? "(`from`<=" . $sfrom . " AND `to`>=" . $sto . ") " : "") .
		 ($sto > $sfrom ? "OR (`from`<=" . $sfrom . " AND `to`>=" . $sfrom . ") " : "(`from`<=" . $sfrom . " AND `to`<=" . $sfrom . " AND `from`>`to`) ") .
		 ($sto > $sfrom ? "OR (`from`<=" . $sto . " AND `to`>=" . $sto . ") " : "OR (`from`>=" . $sto . " AND `to`>=" . $sto . " AND `from`>`to`) ") .
		 ($sto > $sfrom ? "OR (`from`>=" . $sfrom . " AND `from`<=" . $sto . " AND `to`>=" . $sfrom . " AND `to`<=" . $sto . ")" : "OR (`from`>=" . $sfrom . " AND `from`>" . $sto . " AND `to`<" . $sfrom . " AND `to`<=" . $sto . " AND `from`>`to`)") .
		 ($sto > $sfrom ? " OR (`from`<=" . $sfrom . " AND `from`<=" . $sto . " AND `to`<" . $sfrom . " AND `to`<" . $sto . " AND `from`>`to`) OR (`from`>" . $sfrom . " AND `from`>" . $sto . " AND `to`>=" . $sfrom . " AND `to`>=" . $sto . " AND `from`>`to`)" : " OR (`from` <=" . $sfrom . " AND `to` >=" . $sfrom . " AND `from` >" . $sto . " AND `to` >" . $sto . " AND `from` < `to`)") .
		 ($sto > $sfrom ? " OR (`from` >=" . $sfrom . " AND `from` <" . $sto . " AND `to` <" . $sfrom . " AND `to` <" . $sto . " AND `from` > `to`)" : " OR (`from` <" . $sfrom . " AND `to` >=" . $sto . " AND `from` <=" . $sto . " AND `to` <" . $sfrom . " AND `from` < `to`)"). //VBO 1.6 Else part is for Season Jan 6 to Feb 12 - Booking Dec 31 to Jan 8
		 ($sto > $sfrom ? " OR (`from` >" . $sfrom . " AND `from` >" . $sto . " AND `to` >=" . $sfrom . " AND `to` <" . $sto . " AND `from` > `to`)" : "").
		");";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$totseasons = $dbo->getNumRows();
		if ($totseasons > 0) {
			$seasons = $dbo->loadAssocList();
			$vbo_tn->translateContents($seasons, '#__vikbooking_seasons');
			$applyseasons = false;
			$mem = array();
			foreach ($arr as $k => $a) {
				$mem[$k]['daysused'] = 0;
				$mem[$k]['sum'] = array();
			}
			foreach ($seasons as $s) {
				//Special Price tied to the year
				if (!empty($s['year']) && $s['year'] > 0) {
					if ($one['year'] != $s['year']) {
						continue;
					}
				}
				//
				$allrooms = explode(",", $s['idrooms']);
				$allprices = !empty($s['idprices']) ? explode(",", $s['idprices']) : array();
				$inits = $baseone + $s['from'];
				if ($s['from'] < $s['to']) {
					$ends = $basetwo + $s['to'];
					//VikBooking 1.6 check if the inits must be set to the year after
					//ex. Season Jan 6 to Feb 12 - Booking Dec 31 to Jan 8 to charge Jan 6,7
					if($sfrom > $s['from'] && $sto >= $s['from'] && $sfrom > $s['to'] && $sto <= $s['to'] && $s['from'] < $s['to'] && $sfrom > $sto) {
						$tmpbase = mktime(0, 0, 0, 1, 1, ($one['year'] + 1));
						$inits = $tmpbase + $s['from'];
					}
				} else {
					//between 2 years
					if ($baseone < $basetwo) {
						//ex. 29/12/2012 - 14/01/2013
						$ends = $basetwo + $s['to'];
					} else {
						if (($sfrom >= $s['from'] && $sto >= $s['from']) OR ($sfrom < $s['from'] && $sto >= $s['from'] && $sfrom > $s['to'] && $sto > $s['to'])) {
							//ex. 25/12 - 30/12 with init season on 20/12 OR 27/12 for counting 28,29,30/12
							$tmpbase = mktime(0, 0, 0, 1, 1, ($one['year'] + 1));
							$ends = $tmpbase + $s['to'];
						} else {
							//ex. 03/01 - 09/01
							$ends = $basetwo + $s['to'];
							$tmpbase = mktime(0, 0, 0, 1, 1, ($one['year'] - 1));
							$inits = $tmpbase + $s['from'];
						}
					}
				}
				//leap years
				if($isleap == true) {
					$infoseason = getdate($inits);
					$leapts = mktime(0, 0, 0, 2, 29, $infoseason['year']);
					//VikBooking 1.6 added below && $infoseason['year'] == $one['year']
					//for those seasons like 2015 Dec 14 to 2016 Jan 5 and booking dates like 2016 Jan 1 to Jan 6 where 2015 is not leap
					if($infoseason[0] >= $leapts && $infoseason['year'] == $one['year']) {
						$inits += 86400;
						$ends += 86400;
					}
				}
				//
				//Promotions
				$promotion = array();
				if($s['promo'] == 1) {
					$daysadv = (($inits - time()) / 86400);
					$daysadv = $daysadv > 0 ? (int)ceil($daysadv) : 0;
					if(!empty($s['promodaysadv']) && $s['promodaysadv'] > $daysadv) {
						continue;
					}else {
						$promotion['todaydaysadv'] = $daysadv;
						$promotion['promodaysadv'] = $s['promodaysadv'];
						$promotion['promotxt'] = $s['promotxt'];
					}
				}
				//
				//Occupancy Override
				$occupancy_ovr = !empty($s['occupancy_ovr']) ? json_decode($s['occupancy_ovr'], true) : array();
				//
				//week days
				$filterwdays = !empty($s['wdays']) ? true : false;
				$wdays = $filterwdays == true ? explode(';', $s['wdays']) : '';
				if (is_array($wdays) && count($wdays) > 0) {
					foreach($wdays as $kw=>$wd) {
						if (strlen($wd) == 0) {
							unset($wdays[$kw]);
						}
					}
				}
				//
				//checkin must be after the begin of the season
				if($s['checkinincl'] == 1) {
					$checkininclok = false;
					if($s['from'] < $s['to']) {
						if($sfrom >= $s['from'] && $sfrom <= $s['to']) {
							$checkininclok = true;
						}
					}else {
						if(($sfrom >= $s['from'] && $sfrom > $s['to']) || ($sfrom < $s['from'] && $sfrom <= $s['to'])) {
							$checkininclok = true;
						}
					}
				}else {
					$checkininclok = true;
				}
				//
				if($checkininclok == true) {
					foreach ($arr as $k => $a) {
						//Applied only to some types of price
						if(count($allprices) > 0 && !empty($allprices[0])) {
							if (!in_array("-" . $a[0]['idprice'] . "-", $allprices)) {
								continue;
							}
						}
						//
						if (in_array("-" . $a[0]['idroom'] . "-", $allrooms)) {
							$affdays = 0;
							$season_fromdayts = $fromdayts;
							$is_dst = date('I', $season_fromdayts);
							for ($i = 0; $i < $a[0]['days']; $i++) {
								$todayts = $season_fromdayts + ($i * 86400);
								$is_now_dst = date('I', $todayts);
								if ($is_dst != $is_now_dst) {
									//Daylight Saving Time has changed, check how
									if ((bool)$is_dst === true) {
										$todayts += 3600;
										$season_fromdayts += 3600;
									}else {
										$todayts -= 3600;
										$season_fromdayts -= 3600;
									}
									$is_dst = $is_now_dst;
								}
								if ($todayts >= $inits && $todayts <= $ends) {
									//week days
									if($filterwdays == true) {
										$checkwday = getdate($todayts);
										if(in_array($checkwday['wday'], $wdays)) {
											$affdays++;
										}
									}else {
										$affdays++;
									}
									//
								}
							}
							if ($affdays > 0) {
								$applyseasons = true;
								$dailyprice = $a[0]['cost'] / $a[0]['days'];
								//VikBooking 1.2 for abs or pcent and values overrides
								if (intval($s['val_pcent']) == 2) {
									//percentage value
									$pctval = $s['diffcost'];
									if (strlen($s['losoverride']) > 0) {
										//values overrides
										$arrvaloverrides = array();
										$valovrparts = explode('_', $s['losoverride']);
										foreach($valovrparts as $valovr) {
											if (!empty($valovr)) {
												$ovrinfo = explode(':', $valovr);
												if(strstr($ovrinfo[0], '-i') != false) {
													$ovrinfo[0] = str_replace('-i', '', $ovrinfo[0]);
													if((int)$ovrinfo[0] < $a[0]['days']) {
														$arrvaloverrides[$a[0]['days']] = $ovrinfo[1];
													}
												}
												$arrvaloverrides[$ovrinfo[0]] = $ovrinfo[1];
											}
										}
										if (array_key_exists($a[0]['days'], $arrvaloverrides)) {
											$pctval = $arrvaloverrides[$a[0]['days']];
										}
									}
									if (intval($s['type']) == 1) {
										//charge
										$cpercent = 100 + $pctval;
									} else {
										//discount
										$cpercent = 100 - $pctval;
									}
									$newprice = ($dailyprice * $cpercent / 100) * $affdays;
								}else {
									//absolute value
									$absval = $s['diffcost'];
									if (strlen($s['losoverride']) > 0) {
										//values overrides
										$arrvaloverrides = array();
										$valovrparts = explode('_', $s['losoverride']);
										foreach($valovrparts as $valovr) {
											if (!empty($valovr)) {
												$ovrinfo = explode(':', $valovr);
												if(strstr($ovrinfo[0], '-i') != false) {
													$ovrinfo[0] = str_replace('-i', '', $ovrinfo[0]);
													if((int)$ovrinfo[0] < $a[0]['days']) {
														$arrvaloverrides[$a[0]['days']] = $ovrinfo[1];
													}
												}
												$arrvaloverrides[$ovrinfo[0]] = $ovrinfo[1];
											}
										}
										if (array_key_exists($a[0]['days'], $arrvaloverrides)) {
											$absval = $arrvaloverrides[$a[0]['days']];
										}
									}
									if (intval($s['type']) == 1) {
										//charge
										$newprice = ($dailyprice + $absval) * $affdays;
									} else {
										//discount
										$newprice = ($dailyprice - $absval) * $affdays;
									}
								}
								//end VikBooking 1.2 for abs or pcent and values overrides
								//VikBooking 1.4
								if (!empty($s['roundmode'])) {
									$newprice = round($newprice, 0, constant($s['roundmode']));
								}else {
									//VikBooking 1.5
									$newprice = round($newprice, 2);
								}
								//
								//Promotions (only if no value overrides set the amount to 0)
								if(count($promotion) > 0 && ($absval > 0 || $pctval > 0)) {
									$mem[$k]['promotion'] = $promotion;
								}
								//
								//Occupancy Override
								if(array_key_exists($a[0]['idroom'], $occupancy_ovr) && count($occupancy_ovr[$a[0]['idroom']]) > 0) {
									$mem[$k]['occupancy_ovr'] = $occupancy_ovr[$a[0]['idroom']];
								}
								//
								$mem[$k]['sum'][] = $newprice;
								$mem[$k]['daysused'] += $affdays;
								$roomschange[] = $a[0]['idroom'];
							}
						}
					}
				}
			}
			if ($applyseasons) {
				foreach ($mem as $k => $v) {
					if ($v['daysused'] > 0 && @ count($v['sum']) > 0) {
						$newprice = 0;
						$dailyprice = $arr[$k][0]['cost'] / $arr[$k][0]['days'];
						$restdays = $arr[$k][0]['days'] - $v['daysused'];
						$addrest = $restdays * $dailyprice;
						$newprice += $addrest;
						foreach ($v['sum'] as $add) {
							$newprice += $add;
						}
						//Promotions
						if(array_key_exists('promotion', $v)) {
							$arr[$k][0]['promotion'] = $v['promotion'];
						}
						//
						//Occupancy Override
						if(array_key_exists('occupancy_ovr', $v)) {
							$arr[$k][0]['occupancy_ovr'] = $v['occupancy_ovr'];
						}
						//
						$arr[$k][0]['cost'] = $newprice;
						$arr[$k][0]['affdays'] = $v['daysused'];
					}
				}
			}
		}
		//week days with no season
		$roomschange = array_unique($roomschange);
		$q="SELECT * FROM `#__vikbooking_seasons` WHERE ((`from` = 0 AND `to` = 0) OR (`from` IS NULL AND `to` IS NULL));";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$specials = $dbo->loadAssocList();
			$vbo_tn->translateContents($specials, '#__vikbooking_seasons');
			$applyseasons = false;
			unset($mem);
			$mem = array();
			foreach ($arr as $k => $a) {
				$mem[$k]['daysused'] = 0;
				$mem[$k]['sum'] = array ();
			}
			foreach($specials as $s) {
				//Special Price tied to the year
				if (!empty($s['year']) && $s['year'] > 0) {
					if ($one['year'] != $s['year']) {
						continue;
					}
				}
				//
				$allrooms = explode(",", $s['idrooms']);
				$allprices = !empty($s['idprices']) ? explode(",", $s['idprices']) : array();
				//week days
				$filterwdays = !empty($s['wdays']) ? true : false;
				$wdays = $filterwdays == true ? explode(';', $s['wdays']) : '';
				if (is_array($wdays) && count($wdays) > 0) {
					foreach($wdays as $kw=>$wd) {
						if (strlen($wd) == 0) {
							unset($wdays[$kw]);
						}
					}
				}
				//
				foreach ($arr as $k => $a) {
					//only rooms with no price modifications from seasons
					//Applied only to some types of price
					if(count($allprices) > 0 && !empty($allprices[0])) {
						if (!in_array("-" . $a[0]['idprice'] . "-", $allprices)) {
							continue;
						}
					}
					//
					if (in_array("-" . $a[0]['idroom'] . "-", $allrooms) && !in_array($a[0]['idroom'], $roomschange)) {
						$affdays = 0;
						$season_fromdayts = $fromdayts;
						$is_dst = date('I', $season_fromdayts);
						for ($i = 0; $i < $a[0]['days']; $i++) {
							$todayts = $season_fromdayts + ($i * 86400);
							$is_now_dst = date('I', $todayts);
							if ($is_dst != $is_now_dst) {
								//Daylight Saving Time has changed, check how
								if ((bool)$is_dst === true) {
									$todayts += 3600;
									$season_fromdayts += 3600;
								}else {
									$todayts -= 3600;
									$season_fromdayts -= 3600;
								}
								$is_dst = $is_now_dst;
							}
							//week days
							if($filterwdays == true) {
								$checkwday = getdate($todayts);
								if(in_array($checkwday['wday'], $wdays)) {
									$affdays++;
								}
							}
							//
						}
						if ($affdays > 0) {
							$applyseasons = true;
							$dailyprice = $a[0]['cost'] / $a[0]['days'];
							//VikBooking 1.2 for abs or pcent and values overrides
							if (intval($s['val_pcent']) == 2) {
								//percentage value
								$pctval = $s['diffcost'];
								if (strlen($s['losoverride']) > 0) {
									//values overrides
									$arrvaloverrides = array();
									$valovrparts = explode('_', $s['losoverride']);
									foreach($valovrparts as $valovr) {
										if (!empty($valovr)) {
											$ovrinfo = explode(':', $valovr);
											if(strstr($ovrinfo[0], '-i') != false) {
												$ovrinfo[0] = str_replace('-i', '', $ovrinfo[0]);
												if((int)$ovrinfo[0] < $a[0]['days']) {
													$arrvaloverrides[$a[0]['days']] = $ovrinfo[1];
												}
											}
											$arrvaloverrides[$ovrinfo[0]] = $ovrinfo[1];
										}
									}
									if (array_key_exists($a[0]['days'], $arrvaloverrides)) {
										$pctval = $arrvaloverrides[$a[0]['days']];
									}
								}
								if (intval($s['type']) == 1) {
									//charge
									$cpercent = 100 + $pctval;
								} else {
									//discount
									$cpercent = 100 - $pctval;
								}
								$newprice = ($dailyprice * $cpercent / 100) * $affdays;
							}else {
								//absolute value
								$absval = $s['diffcost'];
								if (strlen($s['losoverride']) > 0) {
									//values overrides
									$arrvaloverrides = array();
									$valovrparts = explode('_', $s['losoverride']);
									foreach($valovrparts as $valovr) {
										if (!empty($valovr)) {
											$ovrinfo = explode(':', $valovr);
											if(strstr($ovrinfo[0], '-i') != false) {
												$ovrinfo[0] = str_replace('-i', '', $ovrinfo[0]);
												if((int)$ovrinfo[0] < $a[0]['days']) {
													$arrvaloverrides[$a[0]['days']] = $ovrinfo[1];
												}
											}
											$arrvaloverrides[$ovrinfo[0]] = $ovrinfo[1];
										}
									}
									if (array_key_exists($a[0]['days'], $arrvaloverrides)) {
										$absval = $arrvaloverrides[$a[0]['days']];
									}
								}
								if (intval($s['type']) == 1) {
									//charge
									$newprice = ($dailyprice + $absval) * $affdays;
								}else {
									//discount
									$newprice = ($dailyprice - $absval) * $affdays;
								}
							}
							//end VikBooking 1.2 for abs or pcent and values overrides
							//VikBooking 1.4
							if (!empty($s['roundmode'])) {
								$newprice = round($newprice, 0, constant($s['roundmode']));
							}else {
								//VikBooking 1.5
								$newprice = round($newprice, 2);
							}
							//
							$mem[$k]['sum'][] = $newprice;
							$mem[$k]['daysused'] += $affdays;
						}
					}
				}
			}
			if ($applyseasons) {
				foreach ($mem as $k => $v) {
					if ($v['daysused'] > 0 && @ count($v['sum']) > 0) {
						$newprice = 0;
						$dailyprice = $arr[$k][0]['cost'] / $arr[$k][0]['days'];
						$restdays = $arr[$k][0]['days'] - $v['daysused'];
						$addrest = $restdays * $dailyprice;
						$newprice += $addrest;
						foreach ($v['sum'] as $add) {
							$newprice += $add;
						}
						$arr[$k][0]['cost'] = $newprice;
						$arr[$k][0]['affdays'] = $v['daysused'];
					}
				}
			}
		}
		//end week days with no season
		return $arr;
	}

	public static function applySeasonsRoom($arr, $from, $to, $parsed_season = array()) {
		$dbo = JFactory::getDBO();
		$vbo_tn = self::getTranslator();
		$roomschange = array();
		$one = getdate($from);
		//leap years
		if($one['year'] % 4 == 0 && ($one['year'] % 100 != 0 || $one['year'] % 400 == 0)) {
			$isleap = true;
		}else {
			$isleap = false;
		}
		//
		$baseone = mktime(0, 0, 0, 1, 1, $one['year']);
		$tomidnightone = intval($one['hours']) * 3600;
		$tomidnightone += intval($one['minutes']) * 60;
		$sfrom = $from - $baseone - $tomidnightone;
		$fromdayts = mktime(0, 0, 0, $one['mon'], $one['mday'], $one['year']);
		$two = getdate($to);
		$basetwo = mktime(0, 0, 0, 1, 1, $two['year']);
		$tomidnighttwo = intval($two['hours']) * 3600;
		$tomidnighttwo += intval($two['minutes']) * 60;
		$sto = $to - $basetwo - $tomidnighttwo;
		//leap years, last day of the month of the season
		if($isleap) {
			$leapts = mktime(0, 0, 0, 2, 29, $two['year']);
			if($two[0] >= $leapts) {
				$sfrom -= 86400;
				$sto -= 86400;
			}
		}
		//
		$totseasons = 0;
		if(count($parsed_season) == 0) {
			$q = "SELECT * FROM `#__vikbooking_seasons` WHERE (" .
		 	($sto > $sfrom ? "(`from`<=" . $sfrom . " AND `to`>=" . $sto . ") " : "") .
		 	($sto > $sfrom ? "OR (`from`<=" . $sfrom . " AND `to`>=" . $sfrom . ") " : "(`from`<=" . $sfrom . " AND `to`<=" . $sfrom . " AND `from`>`to`) ") .
		 	($sto > $sfrom ? "OR (`from`<=" . $sto . " AND `to`>=" . $sto . ") " : "OR (`from`>=" . $sto . " AND `to`>=" . $sto . " AND `from`>`to`) ") .
		 	($sto > $sfrom ? "OR (`from`>=" . $sfrom . " AND `from`<=" . $sto . " AND `to`>=" . $sfrom . " AND `to`<=" . $sto . ")" : "OR (`from`>=" . $sfrom . " AND `from`>" . $sto . " AND `to`<" . $sfrom . " AND `to`<=" . $sto . " AND `from`>`to`)") .
		 	($sto > $sfrom ? " OR (`from`<=" . $sfrom . " AND `from`<=" . $sto . " AND `to`<" . $sfrom . " AND `to`<" . $sto . " AND `from`>`to`) OR (`from`>" . $sfrom . " AND `from`>" . $sto . " AND `to`>=" . $sfrom . " AND `to`>=" . $sto . " AND `from`>`to`)" : " OR (`from` <=" . $sfrom . " AND `to` >=" . $sfrom . " AND `from` >" . $sto . " AND `to` >" . $sto . " AND `from` < `to`)") .
		 	($sto > $sfrom ? " OR (`from` >=" . $sfrom . " AND `from` <" . $sto . " AND `to` <" . $sfrom . " AND `to` <" . $sto . " AND `from` > `to`)" : " OR (`from` <" . $sfrom . " AND `to` >=" . $sto . " AND `from` <=" . $sto . " AND `to` <" . $sfrom . " AND `from` < `to`)"). //VBO 1.6 Else part is for Season Jan 6 to Feb 12 - Booking Dec 31 to Jan 8
		 	($sto > $sfrom ? " OR (`from` >" . $sfrom . " AND `from` >" . $sto . " AND `to` >=" . $sfrom . " AND `to` <" . $sto . " AND `from` > `to`)" : "").
			");";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$totseasons = $dbo->getNumRows();
		}
		if ($totseasons > 0 || count($parsed_season) > 0) {
			$seasons = $totseasons > 0 ? $dbo->loadAssocList() : array($parsed_season);
			$vbo_tn->translateContents($seasons, '#__vikbooking_seasons');
			$applyseasons = false;
			$mem = array ();
			foreach ($arr as $k => $a) {
				$mem[$k]['daysused'] = 0;
				$mem[$k]['sum'] = array ();
			}
			$affdayslistless = array();
			foreach ($seasons as $s) {
				//Special Price tied to the year
				if (!empty($s['year']) && $s['year'] > 0) {
					if ($one['year'] != $s['year']) {
						continue;
					}
				}
				//
				$allrooms = explode(",", $s['idrooms']);
				$allprices = !empty($s['idprices']) ? explode(",", $s['idprices']) : array();
				$inits = $baseone + $s['from'];
				if ($s['from'] < $s['to']) {
					$ends = $basetwo + $s['to'];
					//VikBooking 1.6 check if the inits must be set to the year after
					//ex. Season Jan 6 to Feb 12 - Booking Dec 31 to Jan 8 to charge Jan 6,7
					if($sfrom > $s['from'] && $sto >= $s['from'] && $sfrom > $s['to'] && $sto <= $s['to'] && $s['from'] < $s['to'] && $sfrom > $sto) {
						$tmpbase = mktime(0, 0, 0, 1, 1, ($one['year'] + 1));
						$inits = $tmpbase + $s['from'];
					}
				} else {
					//between 2 years
					if ($baseone < $basetwo) {
						//ex. 29/12/2012 - 14/01/2013
						$ends = $basetwo + $s['to'];
					} else {
						if (($sfrom >= $s['from'] && $sto >= $s['from']) OR ($sfrom < $s['from'] && $sto >= $s['from'] && $sfrom > $s['to'] && $sto > $s['to'])) {
							//ex. 25/12 - 30/12 with init season on 20/12 OR 27/12 for counting 28,29,30/12
							$tmpbase = mktime(0, 0, 0, 1, 1, ($one['year'] + 1));
							$ends = $tmpbase + $s['to'];
						} else {
							//ex. 03/01 - 09/01
							$ends = $basetwo + $s['to'];
							$tmpbase = mktime(0, 0, 0, 1, 1, ($one['year'] - 1));
							$inits = $tmpbase + $s['from'];
						}
					}
				}
				//leap years
				if($isleap == true) {
					$infoseason = getdate($inits);
					$leapts = mktime(0, 0, 0, 2, 29, $infoseason['year']);
					//VikBooking 1.6 added below && $infoseason['year'] == $one['year']
					//for those seasons like 2015 Dec 14 to 2016 Jan 5 and booking dates like 2016 Jan 1 to Jan 6 where 2015 is not leap
					if($infoseason[0] >= $leapts && $infoseason['year'] == $one['year']) {
						$inits += 86400;
						$ends += 86400;
					}
				}
				//
				//Promotions
				$promotion = array();
				if($s['promo'] == 1) {
					$daysadv = (($inits - time()) / 86400);
					$daysadv = $daysadv > 0 ? (int)ceil($daysadv) : 0;
					if(!empty($s['promodaysadv']) && $s['promodaysadv'] > $daysadv) {
						continue;
					}else {
						$promotion['todaydaysadv'] = $daysadv;
						$promotion['promodaysadv'] = $s['promodaysadv'];
						$promotion['promotxt'] = $s['promotxt'];
					}
				}
				//
				//Occupancy Override
				$occupancy_ovr = !empty($s['occupancy_ovr']) ? json_decode($s['occupancy_ovr'], true) : array();
				//
				//week days
				$filterwdays = !empty($s['wdays']) ? true : false;
				$wdays = $filterwdays == true ? explode(';', $s['wdays']) : '';
				if (is_array($wdays) && count($wdays) > 0) {
					foreach($wdays as $kw=>$wd) {
						if (strlen($wd) == 0) {
							unset($wdays[$kw]);
						}
					}
				}
				//
				//checkin must be after the begin of the season
				if($s['checkinincl'] == 1) {
					$checkininclok = false;
					if($s['from'] < $s['to']) {
						if($sfrom >= $s['from'] && $sfrom <= $s['to']) {
							$checkininclok = true;
						}
					}else {
						if(($sfrom >= $s['from'] && $sfrom > $s['to']) || ($sfrom < $s['from'] && $sfrom <= $s['to'])) {
							$checkininclok = true;
						}
					}
				}else {
					$checkininclok = true;
				}
				//
				if($checkininclok == true) {
					foreach ($arr as $k => $a) {
						//Applied only to some types of price
						if(count($allprices) > 0 && !empty($allprices[0])) {
							//VikBooking 1.6: Price Calendar sets the idprice to -1
							if (!in_array("-" . $a['idprice'] . "-", $allprices) && $a['idprice'] > 0) {
								continue;
							}
						}
						//
						if (in_array("-" . $a['idroom'] . "-", $allrooms)) {
							$affdays = 0;
							$season_fromdayts = $fromdayts;
							$is_dst = date('I', $season_fromdayts);
							for ($i = 0; $i < $a['days']; $i++) {
								$todayts = $season_fromdayts + ($i * 86400);
								$is_now_dst = date('I', $todayts);
								if ($is_dst != $is_now_dst) {
									//Daylight Saving Time has changed, check how
									if ((bool)$is_dst === true) {
										$todayts += 3600;
										$season_fromdayts += 3600;
									}else {
										$todayts -= 3600;
										$season_fromdayts -= 3600;
									}
									$is_dst = $is_now_dst;
								}
								if ($todayts >= $inits && $todayts <= $ends) {
									$checkwday = getdate($todayts);
									//week days
									if($filterwdays == true) {
										if(in_array($checkwday['wday'], $wdays)) {
											$arr[$k]['affdayslist'][$checkwday['wday'].'-'.$checkwday['mday'].'-'.$checkwday['mon']] = $arr[$k]['affdayslist'][$checkwday['wday'].'-'.$checkwday['mday'].'-'.$checkwday['mon']] > 0 ? $arr[$k]['affdayslist'][$checkwday['wday'].'-'.$checkwday['mday'].'-'.$checkwday['mon']] : 0;
											$arr[$k]['origdailycost'] = $a['cost'] / $a['days'];
											$affdayslistless[$s['id']][] = $checkwday['wday'].'-'.$checkwday['mday'].'-'.$checkwday['mon'];
											$affdays++;
										}
									}else {
										$arr[$k]['affdayslist'][$checkwday['wday'].'-'.$checkwday['mday'].'-'.$checkwday['mon']] = $arr[$k]['affdayslist'][$checkwday['wday'].'-'.$checkwday['mday'].'-'.$checkwday['mon']] > 0 ? $arr[$k]['affdayslist'][$checkwday['wday'].'-'.$checkwday['mday'].'-'.$checkwday['mon']] : 0;
										$arr[$k]['origdailycost'] = $a['cost'] / $a['days'];
										$affdayslistless[$s['id']][] = $checkwday['wday'].'-'.$checkwday['mday'].'-'.$checkwday['mon'];
										$affdays++;
									}
									//
								}
							}
							if ($affdays > 0) {
								$applyseasons = true;
								$dailyprice = $a['cost'] / $a['days'];
								//VikBooking 1.2 for abs or pcent and values overrides
								if (intval($s['val_pcent']) == 2) {
									//percentage value
									$pctval = $s['diffcost'];
									if (strlen($s['losoverride']) > 0) {
										//values overrides
										$arrvaloverrides = array();
										$valovrparts = explode('_', $s['losoverride']);
										foreach($valovrparts as $valovr) {
											if (!empty($valovr)) {
												$ovrinfo = explode(':', $valovr);
												if(strstr($ovrinfo[0], '-i') != false) {
													$ovrinfo[0] = str_replace('-i', '', $ovrinfo[0]);
													if((int)$ovrinfo[0] < $a['days']) {
														$arrvaloverrides[$a['days']] = $ovrinfo[1];
													}
												}
												$arrvaloverrides[$ovrinfo[0]] = $ovrinfo[1];
											}
										}
										if (array_key_exists($a['days'], $arrvaloverrides)) {
											$pctval = $arrvaloverrides[$a['days']];
										}
									}
									if (intval($s['type']) == 1) {
										//charge
										$cpercent = 100 + $pctval;
									} else {
										//discount
										$cpercent = 100 - $pctval;
									}
									$dailysum = ($dailyprice * $cpercent / 100);
									$newprice = $dailysum * $affdays;
								}else {
									//absolute value
									$absval = $s['diffcost'];
									if (strlen($s['losoverride']) > 0) {
										//values overrides
										$arrvaloverrides = array();
										$valovrparts = explode('_', $s['losoverride']);
										foreach($valovrparts as $valovr) {
											if (!empty($valovr)) {
												$ovrinfo = explode(':', $valovr);
												if(strstr($ovrinfo[0], '-i') != false) {
													$ovrinfo[0] = str_replace('-i', '', $ovrinfo[0]);
													if((int)$ovrinfo[0] < $a['days']) {
														$arrvaloverrides[$a['days']] = $ovrinfo[1];
													}
												}
												$arrvaloverrides[$ovrinfo[0]] = $ovrinfo[1];
											}
										}
										if (array_key_exists($a['days'], $arrvaloverrides)) {
											$absval = $arrvaloverrides[$a['days']];
										}
									}
									if (intval($s['type']) == 1) {
										//charge
										$dailysum = ($dailyprice + $absval);
										$newprice = $dailysum * $affdays;
									}else {
										//discount
										$dailysum = ($dailyprice - $absval);
										$newprice = $dailysum * $affdays;
									}
								}
								//end VikBooking 1.2 for abs or pcent and values overrides
								//VikBooking 1.4
								if (!empty($s['roundmode'])) {
									$newprice = round($newprice, 0, constant($s['roundmode']));
								}else {
									//VikBooking 1.5
									$newprice = round($newprice, 2);
								}
								//
								//Promotions (only if no value overrides set the amount to 0)
								if(count($promotion) > 0 && ($absval > 0 || $pctval > 0)) {
									$mem[$k]['promotion'] = $promotion;
								}
								//
								//Occupancy Override
								if(array_key_exists($a['idroom'], $occupancy_ovr) && count($occupancy_ovr[$a['idroom']]) > 0) {
									$mem[$k]['occupancy_ovr'] = $occupancy_ovr[$a['idroom']];
								}
								//
								foreach($arr[$k]['affdayslist'] as $affk => $affv) {
									if (in_array($affk, $affdayslistless[$s['id']])) {
										$arr[$k]['affdayslist'][$affk] = !empty($arr[$k]['affdayslist'][$affk]) && $arr[$k]['affdayslist'][$affk] > 0 ? ($arr[$k]['affdayslist'][$affk] - $arr[$k]['origdailycost'] + $dailysum) : ($affv + $dailysum);
									}
								}
								$mem[$k]['sum'][] = $newprice;
								$mem[$k]['daysused'] += $affdays;
								$roomschange[] = $a['idroom'];
							}
						}
					}
				}
			}
			if ($applyseasons) {
				foreach ($mem as $k => $v) {
					if ($v['daysused'] > 0 && @ count($v['sum']) > 0) {
						$newprice = 0;
						$dailyprice = $arr[$k]['cost'] / $arr[$k]['days'];
						$restdays = $arr[$k]['days'] - $v['daysused'];
						$addrest = $restdays * $dailyprice;
						$newprice += $addrest;
						foreach ($v['sum'] as $add) {
							$newprice += $add;
						}
						//Promotions
						if(array_key_exists('promotion', $v)) {
							$arr[$k]['promotion'] = $v['promotion'];
						}
						//
						//Occupancy Override
						if(array_key_exists('occupancy_ovr', $v)) {
							$arr[$k]['occupancy_ovr'] = $v['occupancy_ovr'];
						}
						//
						$arr[$k]['cost'] = $newprice;
						$arr[$k]['affdays'] = $v['daysused'];
					}
				}
			}
		}
		//week days with no season
		$roomschange = array_unique($roomschange);
		$q="SELECT * FROM `#__vikbooking_seasons` WHERE ((`from` = 0 AND `to` = 0) OR (`from` IS NULL AND `to` IS NULL));";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$specials = $dbo->loadAssocList();
			$vbo_tn->translateContents($specials, '#__vikbooking_seasons');
			$applyseasons = false;
			unset($mem);
			$mem = array();
			foreach ($arr as $k => $a) {
				$mem[$k]['daysused'] = 0;
				$mem[$k]['sum'] = array ();
			}
			foreach($specials as $s) {
				//Special Price tied to the year
				if (!empty($s['year']) && $s['year'] > 0) {
					if ($one['year'] != $s['year']) {
						continue;
					}
				}
				//
				$allrooms = explode(",", $s['idrooms']);
				$allprices = !empty($s['idprices']) ? explode(",", $s['idprices']) : array();
				//week days
				$filterwdays = !empty($s['wdays']) ? true : false;
				$wdays = $filterwdays == true ? explode(';', $s['wdays']) : '';
				if (is_array($wdays) && count($wdays) > 0) {
					foreach($wdays as $kw=>$wd) {
						if (strlen($wd) == 0) {
							unset($wdays[$kw]);
						}
					}
				}
				//
				foreach ($arr as $k => $a) {
					//only rooms with no price modifications from seasons
					//Applied only to some types of price
					if(count($allprices) > 0 && !empty($allprices[0])) {
						//VikBooking 1.6: Price Calendar sets the idprice to -1
						if (!in_array("-" . $a['idprice'] . "-", $allprices) && $a['idprice'] > 0) {
							continue;
						}
					}
					//
					if (in_array("-" . $a['idroom'] . "-", $allrooms) && !in_array($a['idroom'], $roomschange)) {
						$affdays = 0;
						$season_fromdayts = $fromdayts;
						$is_dst = date('I', $season_fromdayts);
						for ($i = 0; $i < $a['days']; $i++) {
							$todayts = $season_fromdayts + ($i * 86400);
							$is_now_dst = date('I', $todayts);
							if ($is_dst != $is_now_dst) {
								//Daylight Saving Time has changed, check how
								if ((bool)$is_dst === true) {
									$todayts += 3600;
									$season_fromdayts += 3600;
								}else {
									$todayts -= 3600;
									$season_fromdayts -= 3600;
								}
								$is_dst = $is_now_dst;
							}
							//week days
							if($filterwdays == true) {
								$checkwday = getdate($todayts);
								if(in_array($checkwday['wday'], $wdays)) {
									$arr[$k]['affdayslist'][$checkwday['wday'].'-'.$checkwday['mday'].'-'.$checkwday['mon']] = 0;
									$arr[$k]['origdailycost'] = $a['cost'] / $a['days'];
									$affdays++;
								}
							}
							//
						}
						if ($affdays > 0) {
							$applyseasons = true;
							$dailyprice = $a['cost'] / $a['days'];
							//VikBooking 1.2 for abs or pcent and values overrides
							if (intval($s['val_pcent']) == 2) {
								//percentage value
								$pctval = $s['diffcost'];
								if (strlen($s['losoverride']) > 0) {
									//values overrides
									$arrvaloverrides = array();
									$valovrparts = explode('_', $s['losoverride']);
									foreach($valovrparts as $valovr) {
										if (!empty($valovr)) {
											$ovrinfo = explode(':', $valovr);
											if(strstr($ovrinfo[0], '-i') != false) {
												$ovrinfo[0] = str_replace('-i', '', $ovrinfo[0]);
												if((int)$ovrinfo[0] < $a['days']) {
													$arrvaloverrides[$a['days']] = $ovrinfo[1];
												}
											}
											$arrvaloverrides[$ovrinfo[0]] = $ovrinfo[1];
										}
									}
									if (array_key_exists($a['days'], $arrvaloverrides)) {
										$pctval = $arrvaloverrides[$a['days']];
									}
								}
								if (intval($s['type']) == 1) {
									//charge
									$cpercent = 100 + $pctval;
								} else {
									//discount
									$cpercent = 100 - $pctval;
								}
								$dailysum = ($dailyprice * $cpercent / 100);
								$newprice = $dailysum * $affdays;
							}else {
								//absolute value
								$absval = $s['diffcost'];
								if (strlen($s['losoverride']) > 0) {
									//values overrides
									$arrvaloverrides = array();
									$valovrparts = explode('_', $s['losoverride']);
									foreach($valovrparts as $valovr) {
										if (!empty($valovr)) {
											$ovrinfo = explode(':', $valovr);
											if(strstr($ovrinfo[0], '-i') != false) {
												$ovrinfo[0] = str_replace('-i', '', $ovrinfo[0]);
												if((int)$ovrinfo[0] < $a['days']) {
													$arrvaloverrides[$a['days']] = $ovrinfo[1];
												}
											}
											$arrvaloverrides[$ovrinfo[0]] = $ovrinfo[1];
										}
									}
									if (array_key_exists($a['days'], $arrvaloverrides)) {
										$absval = $arrvaloverrides[$a['days']];
									}
								}
								if (intval($s['type']) == 1) {
									//charge
									$dailysum = ($dailyprice + $absval);
									$newprice = $dailysum * $affdays;
								}else {
									//discount
									$dailysum = ($dailyprice - $absval);
									$newprice = $dailysum * $affdays;
								}
							}
							//end VikBooking 1.2 for abs or pcent and values overrides
							//VikBooking 1.4
							if (!empty($s['roundmode'])) {
								$newprice = round($newprice, 0, constant($s['roundmode']));
							}else {
								//VikBooking 1.5
								$newprice = round($newprice, 2);
							}
							//
							foreach($arr[$k]['affdayslist'] as $affk => $affv) {
								$arr[$k]['affdayslist'][$affk] = $affv + $dailysum;
							}
							$mem[$k]['sum'][] = $newprice;
							$mem[$k]['daysused'] += $affdays;
						}
					}
				}
			}
			if ($applyseasons) {
				foreach ($mem as $k => $v) {
					if ($v['daysused'] > 0 && @ count($v['sum']) > 0) {
						$newprice = 0;
						$dailyprice = $arr[$k]['cost'] / $arr[$k]['days'];
						$restdays = $arr[$k]['days'] - $v['daysused'];
						$addrest = $restdays * $dailyprice;
						$newprice += $addrest;
						foreach ($v['sum'] as $add) {
							$newprice += $add;
						}
						$arr[$k]['cost'] = $newprice;
						$arr[$k]['affdays'] = $v['daysused'];
					}
				}
			}
		}
		//end week days with no season
		return $arr;
	}

	public static function areTherePayments() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id` FROM `#__vikbooking_gpayments` WHERE `published`='1';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		return $dbo->getNumRows() > 0 ? true : false;
	}

	public static function getPayment($idp, $vbo_tn = null) {
		if (!empty ($idp)) {
			if (strstr($idp, '=') !== false) {
				$parts = explode('=', $idp);
				$idp = $parts[0];
			}
			$dbo = JFactory::getDBO();
			$q = "SELECT * FROM `#__vikbooking_gpayments` WHERE `id`=" . $dbo->quote($idp) . ";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() == 1) {
				$payment = $dbo->loadAssocList();
				if(is_object($vbo_tn)) {
					$vbo_tn->translateContents($payment, '#__vikbooking_gpayments');
				}
				return $payment[0];
			} else {
				return false;
			}
		}
		return false;
	}

	public static function getCronKey() {
		$dbo = JFactory::getDBO();
		$ckey = '';
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='cronkey';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$cval = $dbo->loadAssocList();
			$ckey = $cval[0]['setting'];
		}
		return $ckey;
	}

	public static function getNextInvoiceNumber () {
		$dbo = JFactory::getDBO();
		$q="SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='invoiceinum';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s=$dbo->loadAssocList();
		return (intval($s[0]['setting']) + 1);
	}
	
	public static function getInvoiceNumberSuffix () {
		$dbo = JFactory::getDBO();
		$q="SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='invoicesuffix';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s=$dbo->loadAssocList();
		return $s[0]['setting'];
	}
	
	public static function getInvoiceCompanyInfo () {
		$dbo = JFactory::getDBO();
		$q="SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='invcompanyinfo';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$s=$dbo->loadAssocList();
		return $s[0]['setting'];
	}

	public static function getSMSAPIClass() {
		$dbo = JFactory::getDBO();
		$cfile = '';
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='smsapi';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$cval = $dbo->loadAssocList();
			$cfile = $cval[0]['setting'];
		}
		return $cfile;
	}

	public static function autoSendSMSEnabled() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='smsautosend';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$cval = $dbo->loadAssocList();
			return intval($cval[0]['setting']) > 0 ? true : false;
		}
		return false;
	}

	public static function getSendSMSTo() {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='smssendto';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$cval = $dbo->loadAssocList();
			if(!empty($cval[0]['setting'])) {
				$sto = json_decode($cval[0]['setting'], true);
				if(is_array($sto)) {
					return $sto;
				}
			}
		}
		return array();
	}

	public static function getSMSAdminPhone() {
		$dbo = JFactory::getDBO();
		$pnum = '';
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='smsadminphone';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$cval = $dbo->loadAssocList();
			$pnum = $cval[0]['setting'];
		}
		return $pnum;
	}

	public static function getSMSParams($as_array = true) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`='smsparams';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$cval = $dbo->loadAssocList();
			if(!empty($cval[0]['setting'])) {
				if(!$as_array) {
					return $cval[0]['setting'];
				}
				$sparams = json_decode($cval[0]['setting'], true);
				if(is_array($sparams)) {
					return $sparams;
				}
			}
		}
		return array();
	}

	public static function getSMSAdminTemplate($vbo_tn = null) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id`,`setting` FROM `#__vikbooking_texts` WHERE `param`='smsadmintpl';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$ft = $dbo->loadAssocList();
		if(is_object($vbo_tn)) {
			$vbo_tn->translateContents($ft, '#__vikbooking_texts');
		}
		return $ft[0]['setting'];
	}

	public static function getSMSCustomerTemplate($vbo_tn = null) {
		$dbo = JFactory::getDBO();
		$q = "SELECT `id`,`setting` FROM `#__vikbooking_texts` WHERE `param`='smscustomertpl';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$ft = $dbo->loadAssocList();
		if(is_object($vbo_tn)) {
			$vbo_tn->translateContents($ft, '#__vikbooking_texts');
		}
		return $ft[0]['setting'];
	}

	public static function checkPhonePrefixCountry($phone, $country_threecode) {
		$dbo = JFactory::getDBO();
		$phone = str_replace(" ", '', trim($phone));
		$cprefix = '';
		if(!empty($country_threecode)) {
			$q = "SELECT `phone_prefix` FROM `#__vikbooking_countries` WHERE `country_3_code`=".$dbo->quote($country_threecode).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if($dbo->getNumRows() > 0) {
				$cprefix = $dbo->loadResult();
				$cprefix = str_replace(" ", '', trim($cprefix));
			}
		}
		if(!empty($cprefix)) {
			if(substr($phone, 0, 1) != '+') {
				if(substr($phone, 0, 2) == '00') {
					$phone = '+'.substr($phone, 2);
				}else {
					$phone = $cprefix.$phone;
				}
			}
		}
		return $phone;
	}

	public static function parseAdminSMSTemplate($booking, $booking_rooms, $vbo_tn = null) {
		$tpl = self::getSMSAdminTemplate($vbo_tn);
		$vbo_df = self::getDateFormat();
		$df = $vbo_df == "%d/%m/%Y" ? 'd/m/Y' : ($vbo_df == "%m/%d/%Y" ? 'm/d/Y' : 'Y-m-d');
		$tpl = str_replace('{customer_name}', $booking['customer_name'], $tpl);
		$tpl = str_replace('{booking_id}', $booking['id'], $tpl);
		$tpl = str_replace('{checkin_date}', date($df, $booking['checkin']), $tpl);
		$tpl = str_replace('{checkout_date}', date($df, $booking['checkout']), $tpl);
		$tpl = str_replace('{num_nights}', $booking['days'], $tpl);
		$rooms_booked = array();
		$tot_adults = 0;
		$tot_children = 0;
		$tot_guests = 0;
		foreach ($booking_rooms as $broom) {
			if(array_key_exists($broom['room_name'], $rooms_booked)) {
				$rooms_booked[$broom['room_name']] += 1;
			}else {
				$rooms_booked[$broom['room_name']] = 1;
			}
			$tot_adults += (int)$broom['adults'];
			$tot_children += (int)$broom['children'];
			$tot_guests += ((int)$broom['adults'] + (int)$broom['children']);
		}
		$tpl = str_replace('{tot_adults}', $tot_adults, $tpl);
		$tpl = str_replace('{tot_children}', $tot_children, $tpl);
		$tpl = str_replace('{tot_guests}', $tot_guests, $tpl);
		$rooms_booked_quant = array();
		foreach ($rooms_booked as $rname => $quant) {
			$rooms_booked_quant[] = ($quant > 1 ? $quant.' ' : '').$rname;
		}
		$tpl = str_replace('{rooms_booked}', implode(', ', $rooms_booked_quant), $tpl);
		$tpl = str_replace('{customer_country}', $booking['country_name'], $tpl);
		$tpl = str_replace('{customer_email}', $booking['custmail'], $tpl);
		$tpl = str_replace('{customer_phone}', $booking['phone'], $tpl);
		$tpl = str_replace('{total}', self::numberFormat($booking['total']), $tpl);
		$tpl = str_replace('{total_paid}', self::numberFormat($booking['totpaid']), $tpl);
		$remaining_bal = $booking['total'] - $booking['totpaid'];
		$tpl = str_replace('{remaining_balance}', self::numberFormat($remaining_bal), $tpl);

		return $tpl;
	}

	public static function parseCustomerSMSTemplate($booking, $booking_rooms, $vbo_tn = null, $force_text = null) {
		$tpl = !empty($force_text) ? $force_text : self::getSMSCustomerTemplate($vbo_tn);
		$vbo_df = self::getDateFormat();
		$df = $vbo_df == "%d/%m/%Y" ? 'd/m/Y' : ($vbo_df == "%m/%d/%Y" ? 'm/d/Y' : 'Y-m-d');
		$tpl = str_replace('{customer_name}', $booking['customer_name'], $tpl);
		$tpl = str_replace('{booking_id}', $booking['id'], $tpl);
		$tpl = str_replace('{checkin_date}', date($df, $booking['checkin']), $tpl);
		$tpl = str_replace('{checkout_date}', date($df, $booking['checkout']), $tpl);
		$tpl = str_replace('{num_nights}', $booking['days'], $tpl);
		$rooms_booked = array();
		$tot_adults = 0;
		$tot_children = 0;
		$tot_guests = 0;
		foreach ($booking_rooms as $broom) {
			if(array_key_exists($broom['room_name'], $rooms_booked)) {
				$rooms_booked[$broom['room_name']] += 1;
			}else {
				$rooms_booked[$broom['room_name']] = 1;
			}
			$tot_adults += (int)$broom['adults'];
			$tot_children += (int)$broom['children'];
			$tot_guests += ((int)$broom['adults'] + (int)$broom['children']);
		}
		$tpl = str_replace('{tot_adults}', $tot_adults, $tpl);
		$tpl = str_replace('{tot_children}', $tot_children, $tpl);
		$tpl = str_replace('{tot_guests}', $tot_guests, $tpl);
		$rooms_booked_quant = array();
		foreach ($rooms_booked as $rname => $quant) {
			$rooms_booked_quant[] = ($quant > 1 ? $quant.' ' : '').$rname;
		}
		$tpl = str_replace('{rooms_booked}', implode(', ', $rooms_booked_quant), $tpl);
		$tpl = str_replace('{total}', self::numberFormat($booking['total']), $tpl);
		$tpl = str_replace('{total_paid}', self::numberFormat($booking['totpaid']), $tpl);
		$remaining_bal = $booking['total'] - $booking['totpaid'];
		$tpl = str_replace('{remaining_balance}', self::numberFormat($remaining_bal), $tpl);
		$tpl = str_replace('{customer_pin}', $booking['customer_pin'], $tpl);
		$book_link = JURI::root().'index.php?option=com_vikbooking&task=vieworder&sid='.$booking['sid'].'&ts='.$booking['ts'];
		$tpl = str_replace('{booking_link}', $book_link, $tpl);

		return $tpl;
	}

	public static function sendBookingSMS($oid, $skip_send_to = array(), $force_send_to = array(), $force_text = null) {
		$dbo = JFactory::getDBO();
		if(!class_exists('VikApplication')) {
			require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'jv_helper.php');
		}
		$vbo_app = new VikApplication;
		if(empty($oid)) {
			return false;
		}
		$sms_api = self::getSMSAPIClass();
		if(empty($sms_api)) {
			return false;
		}
		if(!file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'smsapi'.DS.$sms_api)) {
			return false;
		}
		$sms_api_params = self::getSMSParams();
		if(!is_array($sms_api_params) || !(count($sms_api_params) > 0)) {
			return false;
		}
		if(!self::autoSendSMSEnabled() && !(count($force_send_to) > 0)) {
			return false;
		}
		$send_sms_to = self::getSendSMSTo();
		if(!(count($send_sms_to) > 0) && !(count($force_send_to) > 0)) {
			return false;
		}
		$booking = array();
		$q = "SELECT `o`.*,`co`.`idcustomer`,CONCAT_WS(' ',`c`.`first_name`,`c`.`last_name`) AS `customer_name`,`c`.`pin` AS `customer_pin`,`nat`.`country_name` FROM `#__vikbooking_orders` AS `o` LEFT JOIN `#__vikbooking_customers_orders` `co` ON `co`.`idorder`=`o`.`id` AND `co`.`idorder`=".(int)$oid." LEFT JOIN `#__vikbooking_customers` `c` ON `c`.`id`=`co`.`idcustomer` LEFT JOIN `#__vikbooking_countries` `nat` ON `nat`.`country_3_code`=`o`.`country` WHERE `o`.`id`=".(int)$oid.";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$booking = $dbo->loadAssocList();
			$booking = $booking[0];
		}
		if(!(count($booking) > 0)) {
			return false;
		}
		$booking_rooms = array();
		$q = "SELECT `or`.*,`r`.`name` AS `room_name` FROM `#__vikbooking_ordersrooms` AS `or` LEFT JOIN `#__vikbooking_rooms` `r` ON `r`.`id`=`or`.`idroom` WHERE `or`.`idorder`=".(int)$booking['id'].";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$booking_rooms = $dbo->loadAssocList();
		}
		$admin_phone = self::getSMSAdminPhone();
		$admin_sendermail = self::getSenderMail();
		$admin_email = self::getAdminMail();
		$f_result = false;
		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'smsapi'.DS.$sms_api);
		if((in_array('admin', $send_sms_to) && !empty($admin_phone) && !in_array('admin', $skip_send_to)) || in_array('admin', $force_send_to)) {
			//SMS for the administrator
			$sms_text = self::parseAdminSMSTemplate($booking, $booking_rooms);
			if(!empty($sms_text)) {
				$sms_obj = new VikSmsApi($booking, $sms_api_params);
				$response_obj = $sms_obj->sendMessage($admin_phone, $sms_text);
				if( !$sms_obj->validateResponse($response_obj) ) {
					//notify the administrator via email with the error of the SMS sending
					$vbo_app->sendMail($admin_sendermail, $admin_sendermail, $admin_email, $admin_sendermail, JText::_('VBOSENDSMSERRMAILSUBJ'), JText::_('VBOSENDADMINSMSERRMAILTXT')."<br />".$sms_obj->getLog(), true);
				}else {
					$f_result = true;
				}
			}
		}
		if((in_array('customer', $send_sms_to) && !empty($booking['phone']) && !in_array('customer', $skip_send_to)) || in_array('customer', $force_send_to)) {
			//SMS for the Customer
			$vbo_tn = self::getTranslator();
			$vbo_tn->translateContents($booking_rooms, '#__vikbooking_rooms', array('id' => 'idroom', 'name' => 'room_name'));
			$sms_text = self::parseCustomerSMSTemplate($booking, $booking_rooms, $vbo_tn, $force_text);
			if(!empty($sms_text)) {
				$sms_obj = new VikSmsApi($booking, $sms_api_params);
				$response_obj = $sms_obj->sendMessage($booking['phone'], $sms_text);
				if( !$sms_obj->validateResponse($response_obj) ) {
					//notify the administrator via email with the error of the SMS sending
					$vbo_app->sendMail($admin_sendermail, $admin_sendermail, $admin_email, $admin_sendermail, JText::_('VBOSENDSMSERRMAILSUBJ'), JText::_('VBOSENDCUSTOMERSMSERRMAILTXT')."<br />".$sms_obj->getLog(), true);
				}else {
					$f_result = true;
				}
			}
		}
		return $f_result;
	}

	public static function loadInvoiceTmpl () {
		define('_VIKBOOKINGEXEC', '1');
		ob_start();
		include JPATH_SITE . DS ."components". DS ."com_vikbooking". DS . "helpers" . DS . "invoices" . DS ."invoice_tmpl.php";
		$content = ob_get_contents();
		ob_end_clean();
		$default_params = array(
			'show_header' => 0,
			'header_data' => array(),
			'show_footer' => 0,
			'pdf_page_orientation' => 'PDF_PAGE_ORIENTATION',
			'pdf_unit' => 'PDF_UNIT',
			'pdf_page_format' => 'PDF_PAGE_FORMAT',
			'pdf_margin_left' => 'PDF_MARGIN_LEFT',
			'pdf_margin_top' => 'PDF_MARGIN_TOP',
			'pdf_margin_right' => 'PDF_MARGIN_RIGHT',
			'pdf_margin_header' => 'PDF_MARGIN_HEADER',
			'pdf_margin_footer' => 'PDF_MARGIN_FOOTER',
			'pdf_margin_bottom' => 'PDF_MARGIN_BOTTOM',
			'pdf_image_scale_ratio' => 'PDF_IMAGE_SCALE_RATIO',
			'header_font_size' => '10',
			'body_font_size' => '10',
			'footer_font_size' => '8'
		);
		if (defined('_VIKBOOKING_INVOICE_PARAMS') && isset($invoice_params) && @count($invoice_params) > 0) {
			$default_params = array_merge($default_params, $invoice_params);
		}
		return array($content, $default_params);
	}

	public static function parseInvoiceTemplate($invoicetpl, $booking, $booking_rooms, $invoice_num, $invoice_suff, $invoice_date, $company_info, $vbo_tn = null, $is_front = false) {
		$parsed = $invoicetpl;
		$dbo = JFactory::getDBO();
		$nowdf = self::getDateFormat();
		if ($nowdf=="%d/%m/%Y") {
			$df='d/m/Y';
		}elseif ($nowdf=="%m/%d/%Y") {
			$df='m/d/Y';
		}else {
			$df='Y/m/d';
		}
		$companylogo = self::getSiteLogo();
		$uselogo = '';
		if (!empty($companylogo)) {
			$uselogo = '<img src="'.($is_front ? './administrator/' : './').'components/com_vikbooking/resources/'.$companylogo.'"/>';
		}
		$parsed = str_replace("{company_logo}", $uselogo, $parsed);
		$parsed = str_replace("{company_info}", $company_info, $parsed);
		$parsed = str_replace("{invoice_number}", $invoice_num, $parsed);
		$parsed = str_replace("{invoice_suffix}", $invoice_suff, $parsed);
		$parsed = str_replace("{invoice_date}", $invoice_date, $parsed);
		$parsed = str_replace("{customer_info}", nl2br(rtrim($booking['custdata'], "\n")), $parsed);
		//invoice price description - Start
		$rooms = array();
		$tars = array();
		$arrpeople = array();
		$is_package = !empty($booking['pkg']) ? true : false;
		$tot_adults = 0;
		$tot_children = 0;
		$tot_guests = 0;
		foreach($booking_rooms as $kor => $or) {
			$num = $kor + 1;
			$rooms[$num] = $or;
			$arrpeople[$num]['adults'] = $or['adults'];
			$arrpeople[$num]['children'] = $or['children'];
			$tot_adults += $or['adults'];
			$tot_children += $or['children'];
			$tot_guests += ($or['adults'] + $or['children']);
			if($is_package === true || (!empty($or['cust_cost']) && $or['cust_cost'] > 0.00)) {
				//package or custom cost set from the back-end
				continue;
			}
			$q="SELECT * FROM `#__vikbooking_dispcost` WHERE `id`='".$or['idtar']."';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if($dbo->getNumRows() > 0) {
				$tar = $dbo->loadAssocList();
				$tar = self::applySeasonsRoom($tar, $booking['checkin'], $booking['checkout']);
				//different usage
				if ($or['fromadult'] <= $or['adults'] && $or['toadult'] >= $or['adults']) {
					$diffusageprice = self::loadAdultsDiff($or['idroom'], $or['adults']);
					//Occupancy Override
					$occ_ovr = self::occupancyOverrideExists($tar, $or['adults']);
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
				$tars[$num] = $tar[0];
			}
		}
		$parsed = str_replace("{checkin_date}", date($df, $booking['checkin']), $parsed);
		$parsed = str_replace("{checkout_date}", date($df, $booking['checkout']), $parsed);
		$parsed = str_replace("{num_nights}", $booking['days'], $parsed);
		$parsed = str_replace("{tot_guests}", $tot_guests, $parsed);
		$parsed = str_replace("{tot_adults}", $tot_adults, $parsed);
		$parsed = str_replace("{tot_children}", $tot_children, $parsed);
		$isdue = 0;
		$tot_taxes = 0;
		$tot_city_taxes = 0;
		$tot_fees = 0;
		$pricestr = array();
		$optstr = array();
		foreach($booking_rooms as $kor => $or) {
			$num = $kor + 1;
			$pricestr[$num] = array();
			if($is_package === true || (!empty($or['cust_cost']) && $or['cust_cost'] > 0.00)) {
				//package cost or cust_cost should always be inclusive of taxes
				$calctar = $or['cust_cost'];
				$pricestr[$num]['name'] = (!empty($or['pkg_name']) ? $or['pkg_name'] : JText::_('VBOROOMCUSTRATEPLAN'));
				$pricestr[$num]['tot'] = $calctar;
				$pricestr[$num]['tax'] = 0;
				$isdue += $calctar;
				if($calctar == $or['cust_cost']) {
					$cost_minus_tax = self::sayPackageMinusIva($or['cust_cost'], $or['cust_idiva']);
					$tot_taxes += ($or['cust_cost'] - $cost_minus_tax);
					$pricestr[$num]['tax'] = ($or['cust_cost'] - $cost_minus_tax);
				}else {
					$tot_taxes += ($calctar - $or['cust_cost']);
				}
			}elseif (array_key_exists($num, $tars) && is_array($tars[$num])) {
				$calctar = self::sayCostPlusIva($tars[$num]['cost'], $tars[$num]['idprice']);
				$pricestr[$num]['name'] = self::getPriceName($tars[$num]['idprice'], $vbo_tn) . (!empty ($tars[$num]['attrdata']) ? "\n" . self::getPriceAttr($tars[$num]['idprice'], $vbo_tn) . ": " . $tars[$num]['attrdata'] : "");
				$pricestr[$num]['tot'] = $calctar;
				$tars[$num]['calctar'] = $calctar;
				$isdue += $calctar;
				if($calctar == $tars[$num]['cost']) {
					$cost_minus_tax = self::sayCostMinusIva($tars[$num]['cost'], $tars[$num]['idprice']);
					$tot_taxes += ($tars[$num]['cost'] - $cost_minus_tax);
					$pricestr[$num]['tax'] = ($tars[$num]['cost'] - $cost_minus_tax);
				}else {
					$tot_taxes += ($calctar - $tars[$num]['cost']);
					$pricestr[$num]['tax'] = ($calctar - $tars[$num]['cost']);
				}
			}
			if (!empty ($or['optionals'])) {
				$stepo = explode(";", $or['optionals']);
				$optstr[$num] = array();
				$opt_ind = 0;
				foreach ($stepo as $oo) {
					if (!empty ($oo)) {
						$stept = explode(":", $oo);
						$q = "SELECT * FROM `#__vikbooking_optionals` WHERE `id`=" . $dbo->quote($stept[0]) . ";";
						$dbo->setQuery($q);
						$dbo->Query($q);
						if ($dbo->getNumRows() == 1) {
							$actopt = $dbo->loadAssocList();
							if(is_object($vbo_tn)) {
								$vbo_tn->translateContents($actopt, '#__vikbooking_optionals');
							}
							$optstr[$num][$opt_ind] = array();
							$chvar = '';
							if (!empty($actopt[0]['ageintervals']) && $or['children'] > 0 && strstr($stept[1], '-') != false) {
								$optagecosts = self::getOptionIntervalsCosts($actopt[0]['ageintervals']);
								$optagenames = self::getOptionIntervalsAges($actopt[0]['ageintervals']);
								$agestept = explode('-', $stept[1]);
								$stept[1] = $agestept[0];
								$chvar = $agestept[1];
								$actopt[0]['chageintv'] = $chvar;
								$actopt[0]['name'] .= ' ('.$optagenames[($chvar - 1)].')';
								$actopt[0]['quan'] = $stept[1];
								$realcost = (intval($actopt[0]['perday']) == 1 ? (floatval($optagecosts[($chvar - 1)]) * $booking['days'] * $stept[1]) : (floatval($optagecosts[($chvar - 1)]) * $stept[1]));
							}else {
								$actopt[0]['quan'] = $stept[1];
								$realcost = (intval($actopt[0]['perday']) == 1 ? ($actopt[0]['cost'] * $booking['days'] * $stept[1]) : ($actopt[0]['cost'] * $stept[1]));
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
							$tmpopr = self::sayOptionalsPlusIva($realcost, $actopt[0]['idiva']);
							$optstr[$num][$opt_ind]['name'] = ($stept[1] > 1 ? $stept[1] . " " : "") . $actopt[0]['name'];
							$optstr[$num][$opt_ind]['tot'] = $tmpopr;
							$optstr[$num][$opt_ind]['tax'] = 0;
							if ($actopt[0]['is_citytax'] == 1) {
								$tot_city_taxes += $tmpopr;
							}elseif ($actopt[0]['is_fee'] == 1) {
								$tot_fees += $tmpopr;
							}else {
								if($tmpopr == $realcost) {
									$opt_minus_tax = self::sayOptionalsMinusIva($realcost, $actopt[0]['idiva']);
									$tot_taxes += ($realcost - $opt_minus_tax);
									$optstr[$num][$opt_ind]['tax'] = ($realcost - $opt_minus_tax);
								}else {
									$tot_taxes += ($tmpopr - $realcost);
									$optstr[$num][$opt_ind]['tax'] = ($tmpopr - $realcost);
								}
							}
							$opt_ind++;
							$isdue += $tmpopr;
						}
					}
				}
			}
		}
		$usedcoupon = false;
		if(strlen($booking['coupon']) > 0) {
			$orig_isdue = $isdue;
			$expcoupon = explode(";", $booking['coupon']);
			$usedcoupon = $expcoupon;
			$isdue = $isdue - (float)$expcoupon[1];
			if($isdue != $orig_isdue) {
				//lower taxes proportionally
				$tot_taxes = $isdue * $tot_taxes / $orig_isdue;
			}
		}
		$rows_written = 0;
		$inv_rows = '';
		foreach ($pricestr as $num => $price_descr) {
			$inv_rows .= '<tr>'."\n";
			$inv_rows .= '<td>'.$rooms[$num]['room_name'].'<br/>'.nl2br(rtrim($price_descr['name'], "\n")).'</td>'."\n";
			$inv_rows .= '<td>'.$booking['currencyname'].' '.self::numberformat(($price_descr['tot'] - $price_descr['tax'])).'</td>'."\n";
			$inv_rows .= '<td>'.$booking['currencyname'].' '.self::numberformat($price_descr['tax']).'</td>'."\n";
			$inv_rows .= '<td>'.$booking['currencyname'].' '.self::numberformat($price_descr['tot']).'</td>'."\n";
			$inv_rows .= '</tr>'."\n";
			$rows_written++;
			if(array_key_exists($num, $optstr) && count($optstr[$num]) > 0) {
				foreach ($optstr[$num] as $optk => $optv) {
					$inv_rows .= '<tr>'."\n";
					$inv_rows .= '<td>'.$optv['name'].'</td>'."\n";
					$inv_rows .= '<td>'.$booking['currencyname'].' '.self::numberformat(($optv['tot'] - $optv['tax'])).'</td>'."\n";
					$inv_rows .= '<td>'.$booking['currencyname'].' '.self::numberformat($optv['tax']).'</td>'."\n";
					$inv_rows .= '<td>'.$booking['currencyname'].' '.self::numberformat($optv['tot']).'</td>'."\n";
					$inv_rows .= '</tr>'."\n";
					$rows_written++;
				}
			}
		}
		//if discount print row
		if($usedcoupon !== false) {
			$inv_rows .= '<tr>'."\n";
			$inv_rows .= '<td></td><td></td><td></td><td></td>'."\n";
			$inv_rows .= '</tr>'."\n";
			$inv_rows .= '<tr>'."\n";
			$inv_rows .= '<td>'.$usedcoupon[2].'</td>'."\n";
			$inv_rows .= '<td></td>'."\n";
			$inv_rows .= '<td></td>'."\n";
			$inv_rows .= '<td>- '.$booking['currencyname'].' '.self::numberformat($usedcoupon[1]).'</td>'."\n";
			$inv_rows .= '</tr>'."\n";
			$rows_written += 2;
		}
		//
		$min_records = 10;
		if($rows_written < $min_records) {
			for ($i=1; $i <= ($min_records - $rows_written); $i++) { 
				$inv_rows .= '<tr>'."\n";
				$inv_rows .= '<td></td>'."\n";
				$inv_rows .= '<td></td>'."\n";
				$inv_rows .= '<td></td>'."\n";
				$inv_rows .= '</tr>'."\n";
			}
		}
		//invoice price description - End
		$parsed = str_replace("{invoice_products_descriptions}", $inv_rows, $parsed);
		$parsed = str_replace("{invoice_totalnet}", $booking['currencyname'].' '.self::numberformat(($isdue - $tot_taxes)), $parsed);
		$parsed = str_replace("{invoice_totaltax}", $booking['currencyname'].' '.self::numberformat($tot_taxes), $parsed);
		$parsed = str_replace("{invoice_grandtotal}", $booking['currencyname'].' '.self::numberformat($isdue), $parsed);

		return $parsed;
	}

	public static function generateBookingInvoice($booking, $invoice_num = 0, $invoice_suff = '', $invoice_date = '', $company_info = '', $translate = false, $is_front = false) {
		$invoice_num = empty($invoice_num) ? self::getNextInvoiceNumber() : $invoice_num;
		$invoice_suff = empty($invoice_suff) ? self::getInvoiceNumberSuffix() : $invoice_suff;
		$company_info = empty($company_info) ? self::getInvoiceCompanyInfo() : $company_info;
		if(!(count($booking) > 0)) {
			return false;
		}
		if(!($booking['total'] > 0)) {
			return false;
		}
		$dbo = JFactory::getDBO();
		$vbo_tn = self::getTranslator();
		$currencyname = self::getCurrencyName();
		$booking['currencyname'] = $currencyname;
		$nowdf = self::getDateFormat(true);
		if ($nowdf=="%d/%m/%Y") {
			$df='d/m/Y';
		}elseif ($nowdf=="%m/%d/%Y") {
			$df='m/d/Y';
		}else {
			$df='Y/m/d';
		}
		if(empty($invoice_date)) {
			$invoice_date = date($df, $booking['ts']);
			$used_date = $booking['ts'];
		}else {
			$invoice_date = date($df, time());
			$used_date = time();
		}
		$booking_rooms = array();
		$q = "SELECT `or`.*,`r`.`name` AS `room_name` FROM `#__vikbooking_ordersrooms` AS `or` LEFT JOIN `#__vikbooking_rooms` `r` ON `r`.`id`=`or`.`idroom` WHERE `or`.`idorder`=".(int)$booking['id'].";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$booking_rooms = $dbo->loadAssocList();
		}
		if(!(count($booking_rooms) > 0)) {
			return false;
		}
		//Translations for the invoices are disabled by default as well as the language definitions for the customer language
		if($translate === true) {
			if(!empty($booking['lang'])) {
				$lang = JFactory::getLanguage();
				if($lang->getTag() != $booking['lang']) {
					$lang->load('com_vikbooking', JPATH_SITE, $booking['lang'], true);
					$lang->load('com_vikbooking', JPATH_ADMINISTRATOR, $booking['lang'], true);
				}
				$vbo_tn->translateContents($booking_rooms, '#__vikbooking_rooms', array('id' => 'idroom', 'name' => 'room_name'), $booking['lang']);
			}
		}
		//
		if(!class_exists('TCPDF')) {
			require_once(JPATH_SITE . DS ."components". DS ."com_vikbooking". DS . "helpers" . DS . "tcpdf" . DS . 'tcpdf.php');
		}
		$usepdffont = file_exists(JPATH_SITE . DS ."components". DS ."com_vikbooking". DS . "helpers" . DS . "tcpdf" . DS . "fonts" . DS . "dejavusans.php") ? 'dejavusans' : 'helvetica';
		list($invoicetpl, $pdfparams) = self::loadInvoiceTmpl();
		$invoice_body = self::parseInvoiceTemplate($invoicetpl, $booking, $booking_rooms, $invoice_num, $invoice_suff, $invoice_date, $company_info, ($translate === true ? $vbo_tn : null), $is_front);
		$pdffname = $booking['id'] . '_' . $booking['sid'] . '.pdf';
		$pathpdf = JPATH_SITE . DS ."components". DS ."com_vikbooking". DS . "helpers" . DS . "invoices" . DS . "generated" . DS . $pdffname;
		if(file_exists($pathpdf)) @unlink($pathpdf);
		$pdf_page_format = is_array($pdfparams['pdf_page_format']) ? $pdfparams['pdf_page_format'] : constant($pdfparams['pdf_page_format']);
		$pdf = new TCPDF(constant($pdfparams['pdf_page_orientation']), constant($pdfparams['pdf_unit']), $pdf_page_format, true, 'UTF-8', false);
		$pdf->SetTitle(JText::_('VBOINVNUM').' '.$invoice_num);
		//Header for each page of the pdf
		if ($pdfparams['show_header'] == 1 && count($pdfparams['header_data']) > 0) {
			$pdf->SetHeaderData($pdfparams['header_data'][0], $pdfparams['header_data'][1], $pdfparams['header_data'][2], $pdfparams['header_data'][3], $pdfparams['header_data'][4], $pdfparams['header_data'][5]);
		}
		//Change some currencies to their unicode (decimal) value
		$unichr_map = array('EUR' => 8364, 'USD' => 36, 'AUD' => 36, 'CAD' => 36, 'GBP' => 163);
		if(array_key_exists($booking['currencyname'], $unichr_map)) {
			$invoice_body = str_replace($booking['currencyname'], $pdf->unichr($unichr_map[$booking['currencyname']]), $invoice_body);
		}
		//header and footer fonts
		$pdf->setHeaderFont(array($usepdffont, '', $pdfparams['header_font_size']));
		$pdf->setFooterFont(array($usepdffont, '', $pdfparams['footer_font_size']));
		//margins
		$pdf->SetMargins(constant($pdfparams['pdf_margin_left']), constant($pdfparams['pdf_margin_top']), constant($pdfparams['pdf_margin_right']));
		$pdf->SetHeaderMargin(constant($pdfparams['pdf_margin_header']));
		$pdf->SetFooterMargin(constant($pdfparams['pdf_margin_footer']));
		//
		$pdf->SetAutoPageBreak(true, constant($pdfparams['pdf_margin_bottom']));
		$pdf->setImageScale(constant($pdfparams['pdf_image_scale_ratio']));
		$pdf->SetFont($usepdffont, '', (int)$pdfparams['body_font_size']);
		if ($pdfparams['show_header'] == 0 || !(count($pdfparams['header_data']) > 0)) {
			$pdf->SetPrintHeader(false);
		}
		if ($pdfparams['show_footer'] == 0) {
			$pdf->SetPrintFooter(false);
		}
		$pdf->AddPage();
		$pdf->writeHTML($invoice_body, true, false, true, false, '');
		$pdf->lastPage();
		$pdf->Output($pathpdf, 'F');
		if(!file_exists($pathpdf)) {
			return false;
		}
		//insert or update record for this invoice
		$invoice_id = 0;
		$q = "SELECT `id` FROM `#__vikbooking_invoices` WHERE `idorder`=".(int)$booking['id'].";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$invoice_data = $dbo->loadAssocList();
			$invoice_id = $invoice_data[0]['id'];
		}
		if($invoice_id > 0) {
			//update
			$q = "UPDATE `#__vikbooking_invoices` SET `number`=".$dbo->quote($invoice_num.$invoice_suff).", `file_name`=".$dbo->quote($pdffname).", `created_on`=".time().", `for_date`=".(int)$used_date." WHERE `id`=".(int)$invoice_id.";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			return $invoice_id;
		}else {
			//insert
			$q = "INSERT INTO `#__vikbooking_invoices` (`number`,`file_name`,`idorder`,`idcustomer`,`created_on`,`for_date`) VALUES(".$dbo->quote($invoice_num.$invoice_suff).", ".$dbo->quote($pdffname).", ".(int)$booking['id'].", ".(int)$booking['idcustomer'].", ".time().", ".(int)$used_date.");";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$lid = $dbo->insertid();
			return $lid > 0 ? $lid : false;
		}
	}

	public static function sendBookingInvoice($invoice_id, $booking, $text = '', $subject = '') {
		if(!(count($booking) > 0) || empty($invoice_id) || empty($booking['custmail'])) {
			return false;
		}
		$dbo = JFactory::getDBO();
		$invoice_data = array();
		$q = "SELECT * FROM `#__vikbooking_invoices` WHERE `id`=".(int)$invoice_id.";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() == 1) {
			$invoice_data = $dbo->loadAssoc();
		}
		if(!(count($invoice_data) > 0)) {
			return false;
		}
		$mail_text = empty($text) ? JText::_('VBOEMAILINVOICEATTACHTXT') : $text;
		$mail_subject = empty($subject) ? JText::_('VBOEMAILINVOICEATTACHSUBJ') : $subject;
		$invoice_file_path = JPATH_SITE . DS ."components". DS ."com_vikbooking". DS . "helpers" . DS . "invoices" . DS . "generated" . DS . $invoice_data['file_name'];
		if(!file_exists($invoice_file_path)) {
			return false;
		}
		if(!class_exists('VikApplication')) {
			require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'jv_helper.php');
		}
		$vbo_app = new VikApplication;
		$admin_sendermail = self::getSenderMail();
		$vbo_app->sendMail($admin_sendermail, $admin_sendermail, $booking['custmail'], $admin_sendermail, $mail_subject, $mail_text, (strpos($mail_text, '<') !== false && strpos($mail_text, '/>') !== false ? true : false), 'base64', $invoice_file_path);
		//update record
		$q = "UPDATE `#__vikbooking_invoices` SET `emailed`=1, `emailed_to`=".$dbo->quote($booking['custmail'])." WHERE `id`=".(int)$invoice_id.";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		//
		return true;
	}
	
	public static function sayWeekDay($wd) {
		switch ($wd) {
			case '6' :
				$ret = JText::_('VBWEEKDAYSIX');
				break;
			case '5' :
				$ret = JText::_('VBWEEKDAYFIVE');
				break;
			case '4' :
				$ret = JText::_('VBWEEKDAYFOUR');
				break;
			case '3' :
				$ret = JText::_('VBWEEKDAYTHREE');
				break;
			case '2' :
				$ret = JText::_('VBWEEKDAYTWO');
				break;
			case '1' :
				$ret = JText::_('VBWEEKDAYONE');
				break;
			default :
				$ret = JText::_('VBWEEKDAYZERO');
				break;
		}
		return $ret;
	}
	
	public static function sayMonth($idm) {
		switch ($idm) {
			case '12' :
				$ret = JText::_('VBMONTHTWELVE');
				break;
			case '11' :
				$ret = JText::_('VBMONTHELEVEN');
				break;
			case '10' :
				$ret = JText::_('VBMONTHTEN');
				break;
			case '9' :
				$ret = JText::_('VBMONTHNINE');
				break;
			case '8' :
				$ret = JText::_('VBMONTHEIGHT');
				break;
			case '7' :
				$ret = JText::_('VBMONTHSEVEN');
				break;
			case '6' :
				$ret = JText::_('VBMONTHSIX');
				break;
			case '5' :
				$ret = JText::_('VBMONTHFIVE');
				break;
			case '4' :
				$ret = JText::_('VBMONTHFOUR');
				break;
			case '3' :
				$ret = JText::_('VBMONTHTHREE');
				break;
			case '2' :
				$ret = JText::_('VBMONTHTWO');
				break;
			default :
				$ret = JText::_('VBMONTHONE');
				break;
		}
		return $ret;
	}
	
	public static function sayDayMonth($d) {
		switch ($d) {
			case '31' :
				$ret = JText::_('VBDAYMONTHTHIRTYONE');
				break;
			case '30' :
				$ret = JText::_('VBDAYMONTHTHIRTY');
				break;
			case '29' :
				$ret = JText::_('VBDAYMONTHTWENTYNINE');
				break;
			case '28' :
				$ret = JText::_('VBDAYMONTHTWENTYEIGHT');
				break;
			case '27' :
				$ret = JText::_('VBDAYMONTHTWENTYSEVEN');
				break;
			case '26' :
				$ret = JText::_('VBDAYMONTHTWENTYSIX');
				break;
			case '25' :
				$ret = JText::_('VBDAYMONTHTWENTYFIVE');
				break;
			case '24' :
				$ret = JText::_('VBDAYMONTHTWENTYFOUR');
				break;
			case '23' :
				$ret = JText::_('VBDAYMONTHTWENTYTHREE');
				break;
			case '22' :
				$ret = JText::_('VBDAYMONTHTWENTYTWO');
				break;
			case '21' :
				$ret = JText::_('VBDAYMONTHTWENTYONE');
				break;
			case '20' :
				$ret = JText::_('VBDAYMONTHTWENTY');
				break;
			case '19' :
				$ret = JText::_('VBDAYMONTHNINETEEN');
				break;
			case '18' :
				$ret = JText::_('VBDAYMONTHEIGHTEEN');
				break;
			case '17' :
				$ret = JText::_('VBDAYMONTHSEVENTEEN');
				break;
			case '16' :
				$ret = JText::_('VBDAYMONTHSIXTEEN');
				break;
			case '15' :
				$ret = JText::_('VBDAYMONTHFIFTEEN');
				break;
			case '14' :
				$ret = JText::_('VBDAYMONTHFOURTEEN');
				break;
			case '13' :
				$ret = JText::_('VBDAYMONTHTHIRTEEN');
				break;
			case '12' :
				$ret = JText::_('VBDAYMONTHTWELVE');
				break;
			case '11' :
				$ret = JText::_('VBDAYMONTHELEVEN');
				break;
			case '10' :
				$ret = JText::_('VBDAYMONTHTEN');
				break;
			case '9' :
				$ret = JText::_('VBDAYMONTHNINE');
				break;
			case '8' :
				$ret = JText::_('VBDAYMONTHEIGHT');
				break;
			case '7' :
				$ret = JText::_('VBDAYMONTHSEVEN');
				break;
			case '6' :
				$ret = JText::_('VBDAYMONTHSIX');
				break;
			case '5' :
				$ret = JText::_('VBDAYMONTHFIVE');
				break;
			case '4' :
				$ret = JText::_('VBDAYMONTHFOUR');
				break;
			case '3' :
				$ret = JText::_('VBDAYMONTHTHREE');
				break;
			case '2' :
				$ret = JText::_('VBDAYMONTHTWO');
				break;
			default :
				$ret = JText::_('VBDAYMONTHONE');
				break;
		}
		return $ret;
	}

	public static function totElements($arr) {
		$n = 0;
		if (is_array($arr)) {
			foreach ($arr as $a) {
				if (!empty ($a)) {
					$n++;
				}
			}
			return $n;
		}
		return false;
	}
	
	public static function displayPaymentParameters ($pfile, $pparams = '') {
		$html = '---------';
		$arrparams = !empty($pparams) ? json_decode($pparams, true) : array();
		if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'payments'.DS.$pfile) && !empty($pfile)) {
			require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'payments'.DS.$pfile);
			if (method_exists('vikBookingPayment', 'getAdminParameters')) {
				$pconfig = vikBookingPayment::getAdminParameters();
				if (count($pconfig) > 0) {
					$html = '';
					foreach($pconfig as $value => $cont) {
						if (empty($value)) {
							continue;
						}
						$labelparts = explode('//', $cont['label']);
						$label = $labelparts[0];
						$labelhelp = $labelparts[1];
						$html .= '<div class="vikpaymentparam">';
						if (strlen($label) > 0) {
							$html .= '<span class="vikpaymentparamlabel">'.$label.'</span>';
						}
						switch ($cont['type']) {
							case 'custom':
								$html .= $cont['html'];
								break;
							case 'select':
								$html .= '<span class="vikpaymentparaminput">' .
										'<select name="vikpaymentparams['.$value.']">';
								foreach($cont['options'] as $poption) {
									$html .= '<option value="'.$poption.'"'.(array_key_exists($value, $arrparams) && $poption == $arrparams[$value] ? ' selected="selected"' : '').'>'.$poption.'</option>';
								}
								$html .= '</select></span>';
								break;
							default:
								$html .= '<span class="vikpaymentparaminput">' .
										'<input type="text" name="vikpaymentparams['.$value.']" value="'.(array_key_exists($value, $arrparams) ? $arrparams[$value] : '').'" size="20"/>' .
										'</span>';
								break;
						}
						if (strlen($labelhelp) > 0) {
							$html .= '<span class="vikpaymentparamlabelhelp">'.$labelhelp.'</span>';
						}
						$html .= '</div>';
					}
				}
			}
		}
		return $html;
	}

	public static function displaySMSParameters ($pfile, $pparams = '') {
		$html = '---------';
		$arrparams = !empty($pparams) ? json_decode($pparams, true) : array();
		if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'smsapi'.DS.$pfile) && !empty($pfile)) {
			require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'smsapi'.DS.$pfile);
			if (method_exists('VikSmsApi', 'getAdminParameters')) {
				$pconfig = VikSmsApi::getAdminParameters();
				if (count($pconfig) > 0) {
					$html = '';
					foreach($pconfig as $value => $cont) {
						if (empty($value)) {
							continue;
						}
						$labelparts = explode('//', $cont['label']);
						$label = $labelparts[0];
						$labelhelp = $labelparts[1];
						$html .= '<div class="vikpaymentparam">';
						if (strlen($label) > 0) {
							$html .= '<span class="vikpaymentparamlabel">'.$label.'</span>';
						}
						switch ($cont['type']) {
							case 'custom':
								$html .= $cont['html'];
								break;
							case 'select':
								$html .= '<span class="vikpaymentparaminput">' .
										'<select name="viksmsparams['.$value.']">';
								foreach($cont['options'] as $poption) {
									$html .= '<option value="'.$poption.'"'.(array_key_exists($value, $arrparams) && $poption == $arrparams[$value] ? ' selected="selected"' : '').'>'.$poption.'</option>';
								}
								$html .= '</select></span>';
								break;
							default:
								$html .= '<span class="vikpaymentparaminput">' .
										'<input type="text" name="viksmsparams['.$value.']" value="'.(array_key_exists($value, $arrparams) ? $arrparams[$value] : '').'" size="40"/>' .
										'</span>';
								break;
						}
						if (strlen($labelhelp) > 0) {
							$html .= '<span class="vikpaymentparamlabelhelp">'.$labelhelp.'</span>';
						}
						$html .= '</div>';
					}
				}
			}
		}
		return $html;
	}

	public static function displayCronParameters ($pfile, $pparams = '') {
		$html = '---------';
		$arrparams = !empty($pparams) ? json_decode($pparams, true) : array();
		if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'cronjobs'.DS.$pfile) && !empty($pfile)) {
			require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'cronjobs'.DS.$pfile);
			if (method_exists('VikCronJob', 'getAdminParameters')) {
				$pconfig = VikCronJob::getAdminParameters();
				if (count($pconfig) > 0) {
					$html = '';
					foreach($pconfig as $value => $cont) {
						if (empty($value)) {
							continue;
						}
						$inp_attr = '';
						if(array_key_exists('attributes', $cont)) {
							foreach ($cont['attributes'] as $inpk => $inpv) {
								$inp_attr .= $inpk.'="'.$inpv.'" ';
							}
						}
						$labelparts = explode('//', $cont['label']);
						$label = $labelparts[0];
						$labelhelp = $labelparts[1];
						$html .= '<div class="vikpaymentparam">';
						if (strlen($label) > 0) {
							$html .= '<span class="vikpaymentparamlabel vikpaymentparamlbl-'.strtolower($cont['type']).'">'.$label.'</span>';
						}
						switch ($cont['type']) {
							case 'custom':
								$html .= $cont['html'];
								break;
							case 'select':
								$html .= '<span class="vikpaymentparaminput">' .
										'<select name="vikcronparams['.$value.']"'.(array_key_exists('attributes', $cont) ? ' '.$inp_attr : '').'>';
								foreach($cont['options'] as $kopt => $poption) {
									$html .= '<option value="'.$poption.'"'.(array_key_exists($value, $arrparams) && $poption == $arrparams[$value] ? ' selected="selected"' : '').'>'.(is_numeric($kopt) ? $poption : $kopt).'</option>';
								}
								$html .= '</select></span>';
								break;
							case 'number':
								$html .= '<span class="vikpaymentparaminput">' .
										'<input type="number" name="vikcronparams['.$value.']" value="'.(array_key_exists($value, $arrparams) ? $arrparams[$value] : (array_key_exists('default', $cont) ? $cont['default'] : '')).'" '.(array_key_exists('attributes', $cont) ? $inp_attr : '').'/>' .
										'</span>';
								break;
							case 'textarea':
								$html .= '<span class="vikpaymentparaminput vikpaymentparaminput-tarea">' .
										'<textarea name="vikcronparams['.$value.']" '.(array_key_exists('attributes', $cont) ? $inp_attr : 'rows="4" cols="60"').'>'.(array_key_exists($value, $arrparams) ? htmlentities($arrparams[$value]) : (array_key_exists('default', $cont) ? htmlentities($cont['default']) : '')).'</textarea>' .
										'</span>';
								break;
							default:
								$html .= '<span class="vikpaymentparaminput">' .
										'<input type="text" name="vikcronparams['.$value.']" value="'.(array_key_exists($value, $arrparams) ? $arrparams[$value] : (array_key_exists('default', $cont) ? $cont['default'] : '')).'" '.(array_key_exists('attributes', $cont) ? $inp_attr : 'size="40"').'/>' .
										'</span>';
								break;
						}
						if (strlen($labelhelp) > 0) {
							$html .= '<span class="vikpaymentparamlabelhelp">'.$labelhelp.'</span>';
						}
						$html .= '</div>';
					}
				}
			}
		}
		return $html;
	}
	
	public static function invokeChannelManager($skiporder = true, $order = array()) {
		$task = JRequest::getString('task', '', 'request');
		$tmpl = JRequest::getString('tmpl', '', 'request');
		$noimpression = array('vieworder');
		if ($tmpl != 'component' && (!$skiporder || !in_array($task, $noimpression)) && file_exists(JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.'lib.vikchannelmanager.php')) {
			//VCM Channel Impression
			if (!class_exists('VikChannelManagerConfig')) {
				require_once(JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.'vcm_config.php');
			}
			if (!class_exists('VikChannelManager')) {
				require_once(JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.'lib.vikchannelmanager.php');
			}
			VikChannelManager::invokeChannelImpression();
		}elseif ($tmpl != 'component' && count($order) > 0 && file_exists(JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.'lib.vikchannelmanager.php')) {
			//VCM Channel Conversion-Impression
			if (!class_exists('VikChannelManagerConfig')) {
				require_once(JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.'vcm_config.php');
			}
			if (!class_exists('VikChannelManager')) {
				require_once(JPATH_SITE.DS.'components'.DS.'com_vikchannelmanager'.DS.'helpers'.DS.'lib.vikchannelmanager.php');
			}
			VikChannelManager::invokeChannelConversionImpression($order);
		}
	}

}

class vikResizer {

	public static function proportionalImage($fileimg, $dest, $towidth, $toheight) {
		if (!file_exists($fileimg)) {
			return false;
		}
		if (empty ($towidth) && empty ($toheight)) {
			copy($fileimg, $dest);
			return true;
		}

		list ($owid, $ohei, $type) = getimagesize($fileimg);

		if ($owid > $towidth || $ohei > $toheight) {
			$xscale = $owid / $towidth;
			$yscale = $ohei / $toheight;
			if ($yscale > $xscale) {
				$new_width = round($owid * (1 / $yscale));
				$new_height = round($ohei * (1 / $yscale));
			} else {
				$new_width = round($owid * (1 / $xscale));
				$new_height = round($ohei * (1 / $xscale));
			}

			$imageresized = imagecreatetruecolor($new_width, $new_height);

			switch ($type) {
				case '1' :
					$imagetmp = imagecreatefromgif($fileimg);
					break;
				case '2' :
					$imagetmp = imagecreatefromjpeg($fileimg);
					break;
				default :
					$imagetmp = imagecreatefrompng($fileimg);
					break;
			}

			imagecopyresampled($imageresized, $imagetmp, 0, 0, 0, 0, $new_width, $new_height, $owid, $ohei);

			switch ($type) {
				case '1' :
					imagegif($imageresized, $dest);
					break;
				case '2' :
					imagejpeg($imageresized, $dest);
					break;
				default :
					imagepng($imageresized, $dest);
					break;
			}

			imagedestroy($imageresized);
			return true;
		} else {
			copy($fileimg, $dest);
		}
		return true;
	}

	public static function bandedImage($fileimg, $dest, $towidth, $toheight, $rgb) {
		if (!file_exists($fileimg)) {
			return false;
		}
		if (empty ($towidth) && empty ($toheight)) {
			copy($fileimg, $dest);
			return true;
		}

		$exp = explode(",", $rgb);
		if (count($exp) == 3) {
			$r = trim($exp[0]);
			$g = trim($exp[1]);
			$b = trim($exp[2]);
		} else {
			$r = 0;
			$g = 0;
			$b = 0;
		}

		list ($owid, $ohei, $type) = getimagesize($fileimg);

		if ($owid > $towidth || $ohei > $toheight) {
			$xscale = $owid / $towidth;
			$yscale = $ohei / $toheight;
			if ($yscale > $xscale) {
				$new_width = round($owid * (1 / $yscale));
				$new_height = round($ohei * (1 / $yscale));
				$ydest = 0;
				$diff = $towidth - $new_width;
				$xdest = ($diff > 0 ? round($diff / 2) : 0);
			} else {
				$new_width = round($owid * (1 / $xscale));
				$new_height = round($ohei * (1 / $xscale));
				$xdest = 0;
				$diff = $toheight - $new_height;
				$ydest = ($diff > 0 ? round($diff / 2) : 0);
			}

			$imageresized = imagecreatetruecolor($towidth, $toheight);

			$bgColor = imagecolorallocate($imageresized, (int) $r, (int) $g, (int) $b);
			imagefill($imageresized, 0, 0, $bgColor);

			switch ($type) {
				case '1' :
					$imagetmp = imagecreatefromgif($fileimg);
					break;
				case '2' :
					$imagetmp = imagecreatefromjpeg($fileimg);
					break;
				default :
					$imagetmp = imagecreatefrompng($fileimg);
					break;
			}

			imagecopyresampled($imageresized, $imagetmp, $xdest, $ydest, 0, 0, $new_width, $new_height, $owid, $ohei);

			switch ($type) {
				case '1' :
					imagegif($imageresized, $dest);
					break;
				case '2' :
					imagejpeg($imageresized, $dest);
					break;
				default :
					imagepng($imageresized, $dest);
					break;
			}

			imagedestroy($imageresized);

			return true;
		} else {
			copy($fileimg, $dest);
		}
		return true;
	}

	public static function croppedImage($fileimg, $dest, $towidth, $toheight) {
		if (!file_exists($fileimg)) {
			return false;
		}
		if (empty ($towidth) && empty ($toheight)) {
			copy($fileimg, $dest);
			return true;
		}

		list ($owid, $ohei, $type) = getimagesize($fileimg);

		if ($owid <= $ohei) {
			$new_width = $towidth;
			$new_height = ($towidth / $owid) * $ohei;
		} else {
			$new_height = $toheight;
			$new_width = ($new_height / $ohei) * $owid;
		}

		switch ($type) {
			case '1' :
				$img_src = imagecreatefromgif($fileimg);
				$img_dest = imagecreate($new_width, $new_height);
				break;
			case '2' :
				$img_src = imagecreatefromjpeg($fileimg);
				$img_dest = imagecreatetruecolor($new_width, $new_height);
				break;
			default :
				$img_src = imagecreatefrompng($fileimg);
				$img_dest = imagecreatetruecolor($new_width, $new_height);
				break;
		}

		imagecopyresampled($img_dest, $img_src, 0, 0, 0, 0, $new_width, $new_height, $owid, $ohei);

		switch ($type) {
			case '1' :
				$cropped = imagecreate($towidth, $toheight);
				break;
			case '2' :
				$cropped = imagecreatetruecolor($towidth, $toheight);
				break;
			default :
				$cropped = imagecreatetruecolor($towidth, $toheight);
				break;
		}

		imagecopy($cropped, $img_dest, 0, 0, 0, 0, $owid, $ohei);

		switch ($type) {
			case '1' :
				imagegif($cropped, $dest);
				break;
			case '2' :
				imagejpeg($cropped, $dest);
				break;
			default :
				imagepng($cropped, $dest);
				break;
		}

		imagedestroy($img_dest);
		imagedestroy($cropped);

		return true;
	}

}

function encryptCookie($str) {
	for ($i = 0; $i < 5; $i++) {
		$str = strrev(base64_encode($str));
	}
	return $str;
}

function decryptCookie($str) {
	for ($i = 0; $i < 5; $i++) {
		$str = base64_decode(strrev($str));
	}
	return $str;
}

function read($str) {
	for ($i = 0; $i < strlen($str); $i += 2)
		$var .= chr(hexdec(substr($str, $i, 2)));
	return $var;
}

function checkComp($lf, $h, $n) {
	$a = $lf[0];
	$b = $lf[1];
	for ($i = 0; $i < 5; $i++) {
		$a = base64_decode(strrev($a));
		$b = base64_decode(strrev($b));
	}
	if ($a == $h || $b == $h || $a == $n || $b == $n) {
		return true;
	} else {
		$a = str_replace('www.', "", $a);
		$b = str_replace('www.', "", $b);
		if ((!empty ($a) && (preg_match("/" . $a . "/i", $h) || preg_match("/" . $a . "/i", $n))) || (!empty ($b) && (preg_match("/" . $b . "/i", $h) || preg_match("/" . $b . "/i", $n)))) {
			return true;
		}
	}
	return false;
}

define('CREATIVIKAPP', 'com_vikbooking');
defined('E4J_SOFTWARE_VERSION') or define('E4J_SOFTWARE_VERSION', '1.7');

class CreativikDotIt {
	function CreativikDotIt() {
		$this->headers = array (
				"Referer" => "",
				"User-Agent" => "CreativikDotIt/1.0",
				"Connection" => "close"
		);
		$this->version = "1.1";
		$this->ctout = 15;
		$this->f_redha = false;
	}

	function exeqer($url) {
		$rcodes = array (
				301,
				302,
				303,
				307
		);
		$rmeth = array (
				'GET',
				'HEAD'
		);
		$rres = false;
		$this->fd_redhad = false;
		$ppred = array ();
		do {
			$rres = $this->sendout($url);
			$url = false;
			if ($this->f_redha && in_array($this->edocser, $rcodes)) {
				if (($this->edocser == 303) || in_array($this->method, $rmeth)) {
					$url = $this->resphh['Location'];
				}
			}
			if ($url && strlen($url)) {
				if (isset ($ppred[$url])) {
					$this->rore = "tceriderpool";
					$rres = false;
					break;
				}
				if (is_numeric($this->f_redha) && (count($ppred) > $this->f_redha)) {
					$this->rore = "tceriderynamoot";
					$rres = false;
					break;
				}
				$ppred[$url] = true;
			}
		} while ($url && strlen($url));
		$rep_qer_daeh = array (
				'Host',
				'Content-Length'
		);
		foreach ($rep_qer_daeh as $k => $v)
			unset ($this->headers[$v]);
		if (count($ppred) > 1)
			$this->fd_redhad = array_keys($ppred);
		return $rres;
	}

	function dliubh() {
		$daeh = "";
		foreach ($this->headers as $name => $value) {
			$value = trim($value);
			if (empty ($value))
				continue;
			$daeh .= "{$name}: $value\r\n";
		}
		$daeh .= "\r\n";
		return $daeh;
	}

	function sendout($url) {
		$time_request_start = time();
		$urldata = parse_url($url);
		if (!$urldata["port"])
			$urldata["port"] = ($urldata["scheme"] == "https") ? 443 : 80;
		if (!$urldata["path"])
			$urldata["path"] = '/';
		if ($this->version > "1.0")
			$this->headers["Host"] = $urldata["host"];
		unset ($this->headers['Authorization']);
		if (!empty ($urldata["query"]))
			$urldata["path"] .= "?" . $urldata["query"];
		$request = $this->method . " " . $urldata["path"] . " HTTP/" . $this->version . "\r\n";
		$request .= $this->dliubh();
		$this->tise = "";
		$hostname = $urldata['host'];
		$time_connect_start = time();
		$fp = @ fsockopen($hostname, $urldata["port"], $errno, $errstr, $this->ctout);
		$connect_time = time() - $time_connect_start;
		if ($fp) {
			stream_set_timeout($fp, 3);
			fputs($fp, $request);
			$meta = stream_get_meta_data($fp);
			if ($meta['timed_out']) {
				$this->rore = "sdnoceseerhtfotuoemitetirwtekcosdedeecxe";
				return false;
			}
			$cerdaeh = false;
			$data_length = false;
			$chunked = false;
			while (!feof($fp)) {
				if ($data_length > 0) {
					$line = fread($fp, $data_length);
					$data_length -= strlen($line);
				} else {
					$line = fgets($fp, 10240);
					if ($chunked) {
						$line = trim($line);
						if (!strlen($line))
							continue;
						list ($data_length,) = explode(';', $line);
						$data_length = (int) hexdec(trim($data_length));
						if ($data_length == 0) {
							break;
						}
						continue;
					}
				}
				$this->tise .= $line;
				if ((!$cerdaeh) && (trim($line) == "")) {
					$cerdaeh = true;
					if (preg_match('/\nContent-Length: ([0-9]+)/i', $this->tise, $matches)) {
						$data_length = (int) $matches[1];
					}
					if (preg_match("/\nTransfer-Encoding: chunked/i", $this->tise, $matches)) {
						$chunked = true;
					}
				}
				$meta = stream_get_meta_data($fp);
				if ($meta['timed_out']) {
					$this->rore = "sceseerhttuoemitdaertekcos";
					return false;
				}
				if (time() - $time_request_start > 5) {
					$this->rore = "maxtransfertimefivesecs";
					return false;
					break;
				}
			}
			fclose($fp);
		} else {
			$this->rore = $urldata['scheme'] . " otdeliafnoitcennoc " . $hostname . " trop " . $urldata['port'];
			return false;
		}
		do {
			$neldaeh = strpos($this->tise, "\r\n\r\n");
			$serp_daeh = explode("\r\n", substr($this->tise, 0, $neldaeh));
			$pthats = trim(array_shift($serp_daeh));
			foreach ($serp_daeh as $line) {
				list ($k, $v) = explode(":", $line, 2);
				$this->resphh[trim($k)] = trim($v);
			}
			$this->tise = substr($this->tise, $neldaeh +4);
			if (!preg_match("/^HTTP\/([0-9\.]+) ([0-9]+) (.*?)$/", $pthats, $matches)) {
				$matches = array (
						"",
						$this->version,
						0,
						"HTTP request error"
				);
			}
			list (, $pserver, $this->edocser, $this->txet) = $matches;
		} while (($this->edocser == 100) && ($neldaeh));
		$ok = ($this->edocser == 200);
		return $ok;
	}

	function ksa($url) {
		$this->method = "GET";
		return $this->exeqer($url);
	}

}

function validEmail($email) {
	$isValid = true;
	$atIndex = strrpos($email, "@");
	if (is_bool($atIndex) && !$atIndex) {
		$isValid = false;
	} else {
		$domain = substr($email, $atIndex +1);
		$local = substr($email, 0, $atIndex);
		$localLen = strlen($local);
		$domainLen = strlen($domain);
		if ($localLen < 1 || $localLen > 64) {
			// local part length exceeded
			$isValid = false;
		} else
			if ($domainLen < 1 || $domainLen > 255) {
				// domain part length exceeded
				$isValid = false;
			} else
				if ($local[0] == '.' || $local[$localLen -1] == '.') {
					// local part starts or ends with '.'
					$isValid = false;
				} else
					if (preg_match('/\\.\\./', $local)) {
						// local part has two consecutive dots
						$isValid = false;
					} else
						if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
							// character not valid in domain part
							$isValid = false;
						} else
							if (preg_match('/\\.\\./', $domain)) {
								// domain part has two consecutive dots
								$isValid = false;
							} else
								if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $local))) {
									// character not valid in local part unless 
									// local part is quoted
									if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local))) {
										$isValid = false;
									}
								}
		if ($isValid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A"))) {
			// domain not found in DNS
			$isValid = false;
		}
	}
	return $isValid;
}

function checkCodiceFiscale($cf) {
	if ($cf == '')
		return false;
	if (strlen($cf) != 16)
		return false;
	$cf = strtoupper(str_replace(" ", "", $cf));
	if (!ereg("^[A-Z0-9]+$", $cf)) {
		return false;
	}
	$s = 0;
	for ($i = 1; $i <= 13; $i += 2) {
		$c = $cf[$i];
		if ('0' <= $c && $c <= '9')
			$s += ord($c) - ord('0');
		else
			$s += ord($c) - ord('A');
	}
	for ($i = 0; $i <= 14; $i += 2) {
		$c = $cf[$i];
		switch ($c) {
			case '0' :
				$s += 1;
				break;
			case '1' :
				$s += 0;
				break;
			case '2' :
				$s += 5;
				break;
			case '3' :
				$s += 7;
				break;
			case '4' :
				$s += 9;
				break;
			case '5' :
				$s += 13;
				break;
			case '6' :
				$s += 15;
				break;
			case '7' :
				$s += 17;
				break;
			case '8' :
				$s += 19;
				break;
			case '9' :
				$s += 21;
				break;
			case 'A' :
				$s += 1;
				break;
			case 'B' :
				$s += 0;
				break;
			case 'C' :
				$s += 5;
				break;
			case 'D' :
				$s += 7;
				break;
			case 'E' :
				$s += 9;
				break;
			case 'F' :
				$s += 13;
				break;
			case 'G' :
				$s += 15;
				break;
			case 'H' :
				$s += 17;
				break;
			case 'I' :
				$s += 19;
				break;
			case 'J' :
				$s += 21;
				break;
			case 'K' :
				$s += 2;
				break;
			case 'L' :
				$s += 4;
				break;
			case 'M' :
				$s += 18;
				break;
			case 'N' :
				$s += 20;
				break;
			case 'O' :
				$s += 11;
				break;
			case 'P' :
				$s += 3;
				break;
			case 'Q' :
				$s += 6;
				break;
			case 'R' :
				$s += 8;
				break;
			case 'S' :
				$s += 12;
				break;
			case 'T' :
				$s += 14;
				break;
			case 'U' :
				$s += 16;
				break;
			case 'V' :
				$s += 10;
				break;
			case 'W' :
				$s += 22;
				break;
			case 'X' :
				$s += 25;
				break;
			case 'Y' :
				$s += 24;
				break;
			case 'Z' :
				$s += 23;
				break;
		}
	}
	if (chr($s % 26 + ord('A')) != $cf[15])
		return false;
	return true;
}

function secureString($string) {
	$search = array (
		'/<\?((?!\?>).)*\?>/s'
	);
	return preg_replace($search, '', $string);
}

function cleanString4Db($str) {
	$var = $str;
	if (get_magic_quotes_gpc()) {
		$var = stripslashes($str);
	}
	$var = str_replace("'", "`", $var);
	return secureString($var);
}

function caniWrite($path) {
	if ($path {
		strlen($path) - 1 }
	== '/') // ricorsivo return a temporary file path
	return caniWrite($path . uniqid(mt_rand()) . '.tmp');
else
	if (is_dir($path))
		return caniWrite($path . '/' . uniqid(mt_rand()) . '.tmp');
// check tmp file for read/write capabilities
$rm = file_exists($path);
$f = @ fopen($path, 'a');
if ($f === false)
	return false;
fclose($f);
if (!$rm)
	unlink($path);
return true;
}

function realInt($num) {
	for ($i = 0; $i < strlen($num); $i++) {
		if (!ctype_digit($num {
			$i })) {
			return false;
		}
	}
	return true;
}

function realDecimal($num) {
	for ($i = 0; $i < strlen($num); $i++) {
		if (!ctype_digit($num {
			$i }) && $num {
			$i }
		!= "." && $num {
			$i }
		!= ",") {
			return false;
		}
	}
	return true;
}
?>
