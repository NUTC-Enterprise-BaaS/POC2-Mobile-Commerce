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

$package = $this->package;
$vbo_tn=$this->vbo_tn;

$currencysymb = vikbooking::getCurrencySymb();
$calendartype = vikbooking::calendarType();
$vbdateformat = vikbooking::getDateFormat();
$juidf = '';
if ($vbdateformat == "%d/%m/%Y") {
	$df = 'd/m/Y';
	$juidf = 'dd/mm/yy';
} elseif ($vbdateformat == "%m/%d/%Y") {
	$df = 'm/d/Y';
	$juidf = 'mm/dd/yy';
} else {
	$df = 'Y/m/d';
	$juidf = 'yy/mm/dd';
}
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

$pitemid = JRequest::getInt('Itemid', '', 'request');
$start_info = getdate($package['dfrom']);
$end_info = getdate($package['dto']);
$exclude_dates = array();
if(!empty($package['excldates'])) {
	$excl_parts = explode(';', $package['excldates']);
	foreach ($excl_parts as $excl) {
		if(!empty($excl)) {
			$exclude_dates[] = "'".$excl."'";
		}
	}
}

$document = JFactory::getDocument();
//load jQuery
if(vikbooking::loadJquery()) {
	JHtml::_('jquery.framework', true, true);
	JHtml::_('script', JURI::root().'components/com_vikbooking/resources/jquery-1.11.3.min.js', false, true, false, false);
}
$document->addStyleSheet(JURI::root().'components/com_vikbooking/resources/jquery-ui.min.css');
$document->addStyleSheet(JURI::root().'components/com_vikbooking/resources/jquery.fancybox.css');
JHtml::_('script', JURI::root().'components/com_vikbooking/resources/jquery-ui.min.js', false, true, false, false);
JHtml::_('script', JURI::root().'components/com_vikbooking/resources/jquery.fancybox.js', false, true, false, false);


if($calendartype == "jqueryui") {
	//lang for jQuery UI Calendar
	$ldecl = '
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
});';
	$document->addScriptDeclaration($ldecl);
	//
	$vbo_js = "
var pkg_not_dates = [".(count($exclude_dates) > 0 ? implode(', ', $exclude_dates) : '')."];
function vbIsDayDisabled(date) {
	var m = date.getMonth(), d = date.getDate(), y = date.getFullYear();
	if(jQuery.inArray((m+1) + '-' + d + '-' + y, pkg_not_dates) != -1) {
		return [false];
	}
	return [true];
}";
	$document->addScriptDeclaration($vbo_js);
}

$costfor = array();
if($package['perperson'] == 1) {
	$costfor[] = JText::_('VBOPKGCOSTPERPERSON');
}
if($package['pernight_total'] == 1) {
	$costfor[] = JText::_('VBOPKGCOSTPERNIGHT');
}

$thumbs_rel = array();

?>
<h3 class="vbo-pkgdet-title"><?php echo $package['name']; ?></h3>
<div class="vbo-pkgdet-container">
	<div class="vbo-pkgdet-topwrap">
<?php
if(!empty($package['img'])) {
	?>
		<div class="vbo-pkgdet-img">
			<img src="<?php echo JURI::root(); ?>components/com_vikbooking/resources/uploads/big_<?php echo $package['img']; ?>" alt="<?php echo $package['name']; ?>" />
		</div>
	<?php
}
//BEGIN: Joomla Content Plugins Rendering
JPluginHelper::importPlugin('content');
$myItem = JTable::getInstance('content');
$objparams = array();
$dispatcher = JDispatcher::getInstance();
$myItem->text = $package['descr'];
$dispatcher->trigger('onContentPrepare', array('com_vikbooking.packagedetails', &$myItem, &$objparams, 0));
$package['descr'] = $myItem->text;
$myItem->text = $package['conditions'];
$dispatcher->trigger('onContentPrepare', array('com_vikbooking.packagedetails', &$myItem, &$objparams, 0));
$package['conditions'] = $myItem->text;
//END: Joomla Content Plugins Rendering
?>
		<div class="vbo-pkgdet-descrprice-block">
			<div class="vbo-pkgdet-descr">
				<?php echo $package['descr'] ?>
			</div>
			<div class="vbo-pkgdet-cost">
				<span class="vbo-pkglist-pkg-price"><span class="vbo_currency"><?php echo $currencysymb; ?></span> <span class="vbo_price"><?php echo vikbooking::numberFormat($package['cost']); ?></span></span>
				<span class="vbo-pkglist-pkg-priceper"><?php echo implode(', ', $costfor); ?></span>
			</div>
		</div>
		<div class="vbo-pkgdet-condsdates-block">
			<div class="vbo-pkgdet-conds">
				<?php echo $package['conditions'] ?>
			</div>
			<div class="vbo-pkgdet-dates">
				<span class="vbo-pkgdet-dates-lbl"><?php echo JText::_('VBOPKGVALIDATES'); ?></span>
				<span class="vbo-pkgdet-dates-ft"><?php echo date($df, $package['dfrom']).($package['dfrom'] != $package['dto'] ? ' - '.date($df, $package['dto']) : ''); ?></span>
			</div>
		</div>
<?php
if(!empty($package['benefits'])) {
	?>
		<div class="vbo-pkgdet-benefits">
			<?php echo $package['benefits']; ?>
		</div>
	<?php
}
?>
	</div>

	<div class="vbo-pkgdet-roomswrap">
		<h3 class="vbo-pkgdet-roomsttl"><?php echo JText::_('VBOPKGBOOKNOWROOMS'); ?></h3>
<?php
if(array_key_exists('rooms', $package) && count($package['rooms'])) {
	?>
		<div class="vbo-pkgdet-roomslist">
	<?php
	foreach ($package['rooms'] as $rk => $room) {
		?>
			<div class="vbo-pkgdet-room-container">
				<div class="vbo-pkgdet-room-outer">
			<?php
			if(!empty($room['img'])) {
				?>
					<div class="vbo-pkgdet-room-img">
						<img src="<?php echo JURI::root(); ?>components/com_vikbooking/resources/uploads/<?php echo $room['img']; ?>" alt="<?php echo $room['name']; ?>" />
					</div>
				<?php
			}
			?>
					<div class="vbo-pkgdet-room-det">
						<h4 class="vbo-pkgdet-roomname"><?php echo $room['name']; ?></h4>
						<div class="vbo-pkgdet-room-shortdescr"><?php echo $room['smalldesc']; ?></div>
					</div>
					<div class="vbo-pkgdet-room-booknow">
						<span class="vbo-pkgdet-room-booknow-btn" data-room="<?php echo $room['idroom']; ?>" onclick="vboToggleRoomBooking('<?php echo $room['idroom']; ?>');"><?php echo JText::_('VBOPKGROOMCHECKAVAIL'); ?></span>
					</div>
				</div>
				<div class="vbo-pkgdet-room-inner" id="vbo-pkgdet-room<?php echo $room['idroom']; ?>-inner" style="display: none;">
			<?php
			if(!empty($room['moreimgs'])) {
				$moreimages = explode(';;', $room['moreimgs']);
				$imgcaptions = json_decode($room['imgcaptions'], true);
				$usecaptions = empty($imgcaptions) || is_null($imgcaptions) || !is_array($imgcaptions) || !(count($imgcaptions) > 0) ? false : true;
				$thumbs_ind = 0;
				$extra_photos = array();
				foreach($moreimages as $iind => $mimg) {
					if (!empty($mimg)) {
						$img_alt = $usecaptions === true && !empty($imgcaptions[$iind]) ? $imgcaptions[$iind] : '';
						$extra_photos[$thumbs_ind] = array('big' => JURI::root().'components/com_vikbooking/resources/uploads/big_'.$mimg, 'thumb' => JURI::root().'components/com_vikbooking/resources/uploads/thumb_'.$mimg, 'alt' => $img_alt);
						$thumbs_ind++;
					}
				}
				if(count($extra_photos)) {
					$thumbs_rel[] = $room['idroom'];
					?>
					<div class="vbo-pkgdet-room-thumbs-cont">
					<?php
					foreach ($extra_photos as $extra_photo) {
						?>
						<div class="vbo-pkgdet-room-thumb">
							<a href="<?php echo $extra_photo['big']; ?>" title="<?php echo $extra_photo['alt']; ?>" rel="room<?php echo $room['idroom']; ?>" target="_blank"><img src="<?php echo $extra_photo['thumb']; ?>" alt="<?php echo $extra_photo['alt']; ?>" /></a>
						</div>
						<?php
					}
					?>
					</div>
					<?php
				}
			}
			?>
					<div class="vbo-seldates-cont vbo-pkgdet-room-form">
						<h4><?php echo JText::_('VBSELECTPDDATES'); ?></h4>
					<?php
					$paramshowpeople = intval(vikbooking::getRoomParam('maxminpeople', $room['params']));
					if ($paramshowpeople > 0) {
						$maxadustr = ($room['fromadult'] != $room['toadult'] ? $room['fromadult'].' - '.$room['toadult'] : $room['toadult']);
						$maxchistr = ($room['fromchild'] != $room['tochild'] ? $room['fromchild'].' - '.$room['tochild'] : $room['tochild']);
						$maxtotstr = ($room['mintotpeople'] != $room['totpeople'] ? $room['mintotpeople'].' - '.$room['totpeople'] : $room['totpeople']);
						?>
						<div class="vbmaxminpeopleroom">
						<?php
						if ($paramshowpeople == 1) {
							?>
							<div class="vbmaxadultsdet"><span class="vbmaximgdet"></span><span class="vbmaxlabeldet"><?php echo JText::_('VBFORMADULTS'); ?></span><span class="vbmaxnumberdet"><?php echo $maxadustr; ?></span></div>
							<?php
						}elseif ($paramshowpeople == 2) {
							?>
							<div class="vbmaxchildrendet"><span class="vbmaximgdet"></span><span class="vbmaxlabeldet"><?php echo JText::_('VBFORMCHILDREN'); ?></span><span class="vbmaxnumberdet"><?php echo $maxchistr; ?></span></div>
							<?php
						}elseif ($paramshowpeople == 3) {
							?>
							<div class="vbmaxadultsdet"><span class="vbmaximgdet"></span><span class="vbmaxlabeldet"><?php echo JText::_('VBFORMADULTS'); ?></span><span class="vbmaxnumberdet"><?php echo $maxadustr; ?></span></div>
							<div class="vbmaxtotdet"><span class="vbmaximgdet"></span><span class="vbmaxlabeldet"><?php echo JText::_('VBMAXTOTPEOPLE'); ?></span><span class="vbmaxnumberdet"><?php echo $maxtotstr; ?></span></div>
							<?php
						}elseif ($paramshowpeople == 4) {
							?>
							<div class="vbmaxchildrendet"><span class="vbmaximgdet"></span><span class="vbmaxlabeldet"><?php echo JText::_('VBFORMCHILDREN'); ?></span><span class="vbmaxnumberdet"><?php echo $maxchistr; ?></span></div>
							<div class="vbmaxtotdet"><span class="vbmaximgdet"></span><span class="vbmaxlabeldet"><?php echo JText::_('VBMAXTOTPEOPLE'); ?></span><span class="vbmaxnumberdet"><?php echo $maxtotstr; ?></span></div>
							<?php
						}elseif ($paramshowpeople == 5) {
							?>
							<div class="vbmaxadultsdet"><span class="vbmaximgdet"></span><span class="vbmaxlabeldet"><?php echo JText::_('VBFORMADULTS'); ?></span><span class="vbmaxnumberdet"><?php echo $maxadustr; ?></span></div>
							<div class="vbmaxchildrendet"><span class="vbmaximgdet"></span><span class="vbmaxlabeldet"><?php echo JText::_('VBFORMCHILDREN'); ?></span><span class="vbmaxnumberdet"><?php echo $maxchistr; ?></span></div>
							<div class="vbmaxtotdet"><span class="vbmaximgdet"></span><span class="vbmaxlabeldet"><?php echo JText::_('VBMAXTOTPEOPLE'); ?></span><span class="vbmaxnumberdet"><?php echo $maxtotstr; ?></span></div>
							<?php
						}
						?>
						</div>
						<?php
					}
					/* Begin room booking form */
					$selform = "<div class=\"vbdivsearch\"><form action=\"".JRoute::_('index.php?option=com_vikbooking')."\" method=\"post\"><div class=\"vb-search-inner\">\n";
					$selform .= "<input type=\"hidden\" name=\"option\" value=\"com_vikbooking\"/>\n";
					$selform .= "<input type=\"hidden\" name=\"task\" value=\"search\"/>\n";
					$selform .= "<input type=\"hidden\" name=\"roomdetail\" value=\"".$room['idroom']."\"/>\n";
					$selform .= "<input type=\"hidden\" name=\"pkg_id\" value=\"".$package['id']."\"/>\n";
					
					if($calendartype == "jqueryui") {
						$orig_start_info = $start_info;
						if($package['dfrom'] < time()) {
							$start_info = getdate(time());
						}
						$sdecl = "
jQuery.noConflict();
jQuery(function(){
	jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ '' ] );
	jQuery('#checkindate".$room['idroom']."').datepicker({
		showOn: 'focus',
		numberOfMonths: 2,
		beforeShowDay: vbIsDayDisabled,
		onSelect: function( selectedDate ) {
			vbSetGlobalMinCheckoutDate('".$room['idroom']."');
			vbCalcNights('".$room['idroom']."');
		}
	});
	jQuery('#checkindate".$room['idroom']."').datepicker( 'option', 'dateFormat', '".$juidf."');
	jQuery('#checkindate".$room['idroom']."').datepicker( 'option', 'minDate', new Date(".$start_info['year'].", ".((int)$start_info['mon'] - 1).", ".$start_info['mday'].") );
	jQuery('#checkindate".$room['idroom']."').datepicker( 'option', 'maxDate', new Date(".$end_info['year'].", ".((int)$end_info['mon'] - 1).", ".$end_info['mday'].") );
	jQuery('#checkoutdate".$room['idroom']."').datepicker({
		showOn: 'focus',
		numberOfMonths: 2,
		beforeShowDay: vbIsDayDisabled,
		onSelect: function( selectedDate ) {
			vbCalcNights('".$room['idroom']."');
		}
	});
	jQuery('#checkoutdate".$room['idroom']."').datepicker( 'option', 'dateFormat', '".$juidf."');
	jQuery('#checkoutdate".$room['idroom']."').datepicker( 'option', 'minDate', new Date(".$start_info['year'].", ".((int)$start_info['mon'] - 1).", ".$start_info['mday'].") );
	jQuery('#checkoutdate".$room['idroom']."').datepicker( 'option', 'maxDate', new Date(".$end_info['year'].", ".((int)$end_info['mon'] - 1).", ".$end_info['mday'].") );
	jQuery('#checkindate".$room['idroom']."').datepicker( 'option', jQuery.datepicker.regional[ 'vikbooking' ] );
	jQuery('#checkoutdate".$room['idroom']."').datepicker( 'option', jQuery.datepicker.regional[ 'vikbooking' ] );
});";
						$document->addScriptDeclaration($sdecl);
						$start_info = $orig_start_info;
						$selform .= "<div class=\"vbo-search-inpblock vbo-search-inpblock-checkin\"><label for=\"checkindate".$room['idroom']."\">" . JText::_('VBPICKUPROOM') . "</label><div class=\"input-group\"><input type=\"text\" name=\"checkindate\" id=\"checkindate".$room['idroom']."\" size=\"10\" autocomplete=\"off\"/><span class=\"vb-cal-img\"></span></div><input type=\"hidden\" name=\"checkinh\" value=\"".$hcheckin."\"/><input type=\"hidden\" name=\"checkinm\" value=\"".$mcheckin."\"/></div>\n";
						$selform .= "<div class=\"vbo-search-inpblock vbo-search-inpblock-checkout\"><label for=\"checkoutdate".$room['idroom']."\">" . JText::_('VBRETURNROOM') . "</label><div class=\"input-group\"><input type=\"text\" name=\"checkoutdate\" id=\"checkoutdate".$room['idroom']."\" size=\"10\" autocomplete=\"off\"/><span class=\"vb-cal-img\"></span></div><input type=\"hidden\" name=\"checkouth\" value=\"".$hcheckout."\"/><input type=\"hidden\" name=\"checkoutm\" value=\"".$mcheckout."\"/></div>\n";
					}else {
						//default Joomla Calendar
						JHTML::_('behavior.calendar');
						$selform .= "<div class=\"vbo-search-inpblock vbo-search-inpblock-checkin\"><label for=\"checkindate".$room['idroom']."\">" . JText::_('VBPICKUPROOM') . "</label><div class=\"input-group\">" . JHTML::_('calendar', '', 'checkindate', 'checkindate'.$room['idroom'], $vbdateformat, array ('class' => '','size' => '10','maxlength' => '19'));
						$selform .= "<input type=\"hidden\" name=\"checkinh\" value=\"".$hcheckin."\"/><input type=\"hidden\" name=\"checkinm\" value=\"".$mcheckin."\"/></div></div>\n";
						$selform .= "<div class=\"vbo-search-inpblock vbo-search-inpblock-checkout\"><label for=\"checkoutdate".$room['idroom']."\">" . JText::_('VBRETURNROOM') . "</label><div class=\"input-group\">" . JHTML::_('calendar', '', 'checkoutdate', 'checkoutdate'.$room['idroom'], $vbdateformat, array ('class' => '','size' => '10','maxlength' => '19')); 
						$selform .= "<input type=\"hidden\" name=\"checkouth\" value=\"".$hcheckout."\"/><input type=\"hidden\" name=\"checkoutm\" value=\"".$mcheckout."\"/></div></div>\n";
					}
					//rooms, adults, children
					$showchildren = vikbooking::showChildrenFront();
					//max number of rooms
					$multi_units = (int)vikbooking::getRoomParam('multi_units', $room['params']);
					if($multi_units === 1 && $room['units'] > 1) {
						$maxsearchnumrooms = (int)vikbooking::getSearchNumRooms();
						$maxsearchnumrooms = $room['units'] > $maxsearchnumrooms ? $maxsearchnumrooms : $room['units'];
						$roomsel = "<span>".JText::_('VBFORMROOMSN')."</span><select name=\"roomsnum\" onchange=\"vbSetRoomsAdults(this.value, '".$room['idroom']."');\">\n";
						for($r = 1; $r <= $maxsearchnumrooms; $r++) {
							$roomsel .= "<option value=\"".$r."\">".$r."</option>\n";
						}
						$roomsel .= "</select>\n";
					}else {
						$roomsel = "<input type=\"hidden\" name=\"roomsnum\" value=\"1\">\n";
					}
					//
					//max number of adults per room
					$adultsel = "<select name=\"adults[]\">";
					for($a = 1; $a <= $room['toadult']; $a++) {
						$adultsel .= "<option value=\"".$a."\"".((!empty($ch_num_adults) && $ch_num_adults == $a) || (empty($ch_num_adults) && $a == $room['toadult']) ? " selected=\"selected\"" : "").">".$a."</option>";
					}
					$adultsel .= "</select>";
					//
					//max number of children per room
					$childrensel = "<select name=\"children[]\">";
					for($c = 0; $c <= $room['tochild']; $c++) {
						$childrensel .= "<option value=\"".$c."\"".(!empty($ch_num_children) && $ch_num_children == $c ? " selected=\"selected\"" : "").">".$c."</option>";
					}
					$childrensel .= "</select>";
					//

					$selform .= "<div class=\"vbo-search-num-racblock\">\n";
					$selform .= "	<div class=\"vbo-search-num-rooms\">".$roomsel."</div>\n";
					$selform .= "	<div class=\"vbo-search-num-aduchild-block\" id=\"vbo-search-num-aduchild-block".$room['idroom']."\">\n";
					$selform .= "		<div class=\"vbo-search-num-aduchild-entry\"><span class=\"vbo-search-roomnum\">".JText::_('VBFORMNUMROOM')." 1</span>\n";
					$selform .= "			<div class=\"vbo-search-num-adults-entry\"><span class=\"vbo-search-num-adults-entry-label\">".JText::_('VBFORMADULTS')."</span><span class=\"vbo-search-num-adults-entry-inp\">".$adultsel."</span></div>\n";
					if($showchildren) {
						$selform .= "		<div class=\"vbo-search-num-children-entry\"><span class=\"vbo-search-num-children-entry-label\">".JText::_('VBFORMCHILDREN')."</span><span class=\"vbo-search-num-children-entry-inp\">".$childrensel."</span></div>\n";
					}
					$selform .= "		</div>\n";
					$selform .= "	</div>\n";
					//the tag <div id=\"vbjstotnights".$room['idroom']."\"></div> will be used by javascript to calculate the nights
					$selform .= "	<div id=\"vbjstotnights".$room['idroom']."\"></div>\n";
					$selform .= "</div>\n";
					$selform .= "<div class=\"vbo-search-submit\"><input type=\"submit\" name=\"search\" value=\"" . JText::_('VBBOOKTHISROOM') . "\" class=\"btn vbdetbooksubmit\"/></div>\n";
					$selform .= "</div>\n";
					$selform .= (!empty ($pitemid) ? "<input type=\"hidden\" name=\"Itemid\" value=\"" . $pitemid . "\"/>" : "") . "</form></div>";
					?>
						<input type="hidden" id="vbroomdethelper<?php echo $room['idroom']; ?>" value="1"/>
						<div class="vbo-room-details-booking-wrapper">
							<?php echo $selform; ?>
						</div>
					<?php
					/* End room booking form */
					?>
					</div>
				</div>
			</div>
		<?php
	}
	?>		
		</div>
	<?php
}
?>
	</div>

</div>

<script type="text/javascript">
function vboToggleRoomBooking(idr) {
	if(typeof jQuery != 'undefined') {
		var elem = document.getElementById("vbo-pkgdet-room"+idr+"-inner");
		if(elem.style.display == 'none') {
			elem.style.display = 'block';
		}else {
			elem.style.display = 'none';
		}
	}else {
		jQuery("#vbo-pkgdet-room"+idr+"-inner").slideToggle();
	}
}
function vbSetGlobalMinCheckoutDate(rid) {
	var nowcheckin = jQuery('#checkindate'+rid).datepicker('getDate');
	var nowcheckindate = new Date(nowcheckin.getTime());
	nowcheckindate.setDate(nowcheckindate.getDate() + <?php echo (int)$package['minlos']; ?>);
	jQuery('#checkoutdate'+rid).datepicker( 'option', 'minDate', nowcheckindate );
<?php
if($package['maxlos'] > 0) {
	?>
	var scndcheckin = jQuery('#checkindate'+rid).datepicker('getDate');
	var scndcheckindate = new Date(scndcheckin.getTime());
	scndcheckindate.setDate(scndcheckindate.getDate() + <?php echo (int)$package['maxlos']; ?>);
	jQuery('#checkoutdate'+rid).datepicker( 'option', 'maxDate', scndcheckindate );
	<?php
}
?>
}
function vbCalcNights(rid) {
	var vbcheckin = document.getElementById('checkindate'+rid).value;
	var vbcheckout = document.getElementById('checkoutdate'+rid).value;
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
				document.getElementById('vbjstotnights'+rid).innerHTML = '<?php echo addslashes(JText::_('VBJSTOTNIGHTS')); ?>: '+vbnights;
			}else {
				document.getElementById('vbjstotnights'+rid).innerHTML = '';
			}
		}else {
			document.getElementById('vbjstotnights'+rid).innerHTML = '';
		}
	}else {
		document.getElementById('vbjstotnights'+rid).innerHTML = '';
	}
}
function vbAddElement(rid) {
	var ni = document.getElementById('vbo-search-num-aduchild-block'+rid);
	var numi = document.getElementById('vbroomdethelper'+rid);
	var num = (document.getElementById('vbroomdethelper'+rid).value -1)+ 2;
	numi.value = num;
	var newdiv = document.createElement('div');
	var divIdName = 'vb'+num+'detracont';
	newdiv.setAttribute('id',divIdName);
	newdiv.innerHTML = '<div class=\'vbo-search-num-aduchild-entry\'><span class=\'vbo-search-roomnum\'><?php echo addslashes(JText::_('VBFORMNUMROOM')); ?> '+ num +'</span><div class=\'vbo-search-num-adults-entry\'><span class=\'vbo-search-num-adults-entry-label\'><?php echo addslashes(JText::_('VBFORMADULTS')); ?></span><span class=\'vbo-search-num-adults-entry-inp\'><?php echo addslashes(str_replace('"', "'", $adultsel)); ?></span></div><?php if($showchildren): ?><div class=\'vbo-search-num-children-entry\'><span class=\'vbo-search-num-children-entry-label\'><?php echo addslashes(JText::_('VBFORMCHILDREN')); ?></span><span class=\'vbo-search-num-adults-entry-inp\'><?php echo addslashes(str_replace('"', "'", $childrensel)); ?></span></div><?php endif; ?></div>';
	ni.appendChild(newdiv);
}
function vbSetRoomsAdults(totrooms, rid) {
	var actrooms = parseInt(document.getElementById('vbroomdethelper'+rid).value);
	var torooms = parseInt(totrooms);
	var difrooms;
	if(torooms > actrooms) {
		difrooms = torooms - actrooms;
		for(var ir=1; ir<=difrooms; ir++) {
			vbAddElement(rid);
		}
	}
	if(torooms < actrooms) {
		for(var ir=actrooms; ir>torooms; ir--) {
			if(ir > 1) {
				var rmra = document.getElementById('vb' + ir + 'detracont');
				rmra.parentNode.removeChild(rmra);
			}
		}
		document.getElementById('vbroomdethelper'+rid).value = torooms;
	}
}
function vbFullObject(obj) {
	var jk;
	for(jk in obj) {
		return obj.hasOwnProperty(jk);
	}
}
jQuery(document).ready(function() {
	jQuery('.vb-cal-img').click(function(){
		var jdp = jQuery(this).prev('input.hasDatepicker');
		if(jdp.length) {
			jdp.focus();
		}
	});
<?php
if(count($thumbs_rel)) {
	foreach ($thumbs_rel as $rel) {
		?>
	jQuery("a[rel=room<?php echo $rel; ?>]").fancybox({
		'helpers': {
			'overlay': {
				'locked': false
			}
		},
		'padding': 0,
		'transitionIn': 'none',
		'transitionOut': 'none',
		'titlePosition': 'outside'
	});
		<?php
	}
}
?>
});
</script>