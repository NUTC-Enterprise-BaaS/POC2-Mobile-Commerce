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

JHtml::_('behavior.keepalive');
JHTML::_('behavior.calendar');

$config = $this->config;
$rows = $this->rows;
$channel = $this->channel;

$def_from_date = date('Y-m-d');
$def_to_date = date('Y-m-d');
$def_roomtypeid = '';
$def_rateplanid = '';
$session = JFactory::getSession();
$sess_rar = $session->get('vcmExecRarRs', '');
$start_rq = false;
$fill_dates = false;
if (!empty($sess_rar) && @is_array($sess_rar)) {
	$def_from_date = $sess_rar['fromdate'];
	$def_to_date = $sess_rar['todate'];
	$def_roomtypeid = $sess_rar['roomtypeid'];
	$def_rateplanid = $sess_rar['rateplanid'];
	$fill_dates = true;
	if(@is_array($sess_rar['rars']) && @count($sess_rar['rars']) > 0) {
		$start_rq = true;
	}
}

$vik = new VikApplication(VersionListener::getID());

if(count($rows) == 0) {
	?>
	<p class="vcmfatal"><?php echo JText::_('VCMNOROOMSASSOCFOUND'); ?></p>
	<br clear="all"/>
	<span class="vcmsynchspan">
		<a class="vcmsyncha" href="index.php?option=com_vikchannelmanager&amp;task=roomsynch"><?php echo JText::_('VCMGOSYNCHROOMS'); ?></a>
	</span>
	<form action="index.php?option=com_vikchannelmanager" method="post" name="adminForm" id="adminForm">
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="option" value="com_vikchannelmanager" />
	</form>
	<?php
}else {
	$schema = array();
	foreach($rows as $row) {
		$schema[$row['idroomota'].'_'.$row['otaroomname']][] = $row;
	}
	?>
	<div class="vcm-loading-overlay">
		<div class="vcm-loading-dot vcm-loading-dot1"></div>
		<div class="vcm-loading-dot vcm-loading-dot2"></div>
		<div class="vcm-loading-dot vcm-loading-dot3"></div>
		<div class="vcm-loading-dot vcm-loading-dot4"></div>
		<div class="vcm-loading-dot vcm-loading-dot5"></div>
	</div>
	<div class="vcm-info-overlay-block">
		<div class="vcm-info-overlay-content"></div>
	</div>
	<h3 class="vcmlargeheading"><?php echo JText::_('VCMROOMSRELATIONS'); ?></h3>
	<div class="vcmrelationshema">
		<table class="vcmtableschema">
			<tr class="vcmtrbigheader">
				<td colspan="2"><div class="vcmrheadtype headtype_<?php echo preg_replace("/[^a-zA-Z0-9]+/", '', $channel['name']); ?>"><?php echo JText::_('VCMROOMSRELATIONSOTA'); ?></div></td>
				<td rowspan="2" class="vcmtdlinked"></td>
				<td colspan="2"><div class="vcmrheadtype headtype_vikbooking"><?php echo JText::_('VCMROOMSRELATIONSVB'); ?></div></td>
			</tr>
			<tr class="vcmtrmediumheader">
				<td class="vcmrsmallheadtype vcmfirsttd"><?php echo JText::_('VCMROOMSRELATIONSID'); ?></td>
				<td class="vcmrsmallheadtype"><?php echo JText::_('VCMROOMSRELATIONSNAME'); ?></td>
				<td class="vcmrsmallheadtype"><?php echo JText::_('VCMROOMSRELATIONSNAME'); ?></td>
				<td class="vcmrsmallheadtype vcmlasttd"><?php echo JText::_('VCMROOMSRELATIONSID'); ?></td>
			</tr>
		<?php
		foreach( $schema as $otak => $relval ) {
			$room_info = !empty($relval[0]['otapricing']) ? json_decode($relval[0]['otapricing'], true) : array();
			$room_info_str = '';
			if(count($room_info) > 0 && array_key_exists('RoomInfo', $room_info)) {
				$room_info_str .= '<p class="vcm-roominfo-paragraph">Details:</p>';
				$room_info_str .= '<div class="vcm-roominfo-block">';
				foreach ($room_info['RoomInfo'] as $rinfo_k => $rinfo_v) {
					$room_info_str .= '<div class="vcm-roominfo-entry"><span>'.$rinfo_k.':</span>'.$rinfo_v.'</div>';
				}
				$room_info_str .= '</div>';
			}
			if(count($room_info) > 0 && array_key_exists('RatePlan', $room_info)) {
				$room_info_str .= '<p class="vcm-roominfo-paragraph">Rate Plans:</p>';
				foreach ($room_info['RatePlan'] as $rplan_k => $rplan_v) {
					$room_info_str .= '<div class="vcm-roominfo-block">';
					foreach ($rplan_v as $rinfo_k => $rinfo_v) {
						$room_info_str .= '<div class="vcm-roominfo-entry"><span>'.$rinfo_k.':</span>'.$rinfo_v.'</div>';
					}
					$room_info_str .= '</div>';
				}
			}
			$otaparts = explode('_', $otak); ?>
			<tr class="vcmschemarow">
				<td><?php echo $otaparts[0]; ?></td>
				<td>
					<span<?php echo !empty($room_info_str) ? ' class="vcmshowinfo" data-infotag="rinfo'.$otaparts[0].'" title="'.JText::_('VCMSHOWINFOCLICK').'"' : ''; ?>><?php echo $otaparts[1]; ?></span>
				<?php
				if(!empty($room_info_str)) {
					?>
					<span class="vcmnotshow" id="rinfo<?php echo $otaparts[0]; ?>"><?php echo $room_info_str; ?></span>
					<?php
				}
				?>
				</td>
			<?php 
			foreach( $relval as $relk => $rel ) {
				if( $relk > 0 ) {
				?>
				<tr class="vcmschemarow">
					<td></td>
					<td></td>
				<?php
					if( count($relval) != ($relk + 1) ) {
						$relimg = 'rel_middle.png';
					} else {
						$relimg = 'rel_last.png';
					}
				} else {
					if( count($relval) > 1 ) {
						$relimg = 'rel_first_multi.png';
					} else {
						$relimg = 'rel_first_single.png';
					}
				} ?>
				<td><img src="<?php echo JURI::root(); ?>administrator/components/com_vikchannelmanager/assets/css/images/<?php echo $relimg; ?>"/></td>
				<td><?php echo $rel['name']; ?></td>
				<td><?php echo $rel['idroomvb']; ?></td>
				</tr>
			<?php 
			}
		}
		?>
		</table>
	</div>
	
	<script type="text/javascript">
	jQuery(".vcmshowtip").tooltip();
	/* Show loading when sending RAR_RQ to prevent double submit */
	Joomla.submitbutton = function(task) {
		if( task == 'sendrar' ) {
			vcmShowLoading();
		}
		Joomla.submitform(task, document.adminForm);
	}
	/* Loading Overlay */
	function vcmShowLoading() {
		jQuery(".vcm-loading-overlay").show();
	}
	var def_date = '<?php echo $def_from_date; ?>';
	var def_todate = '<?php echo $def_to_date; ?>';
	var def_roomtypeid = '<?php echo $def_roomtypeid; ?>';
	var def_rateplanid = '<?php echo $def_rateplanid; ?>';
	var vcm_overlay_on = false;
	var vcm_rar_days = new Array();
	function startRarRq() {
		vcm_rar_days = new Array();
		var fromdate = jQuery("#fromdate").val();
		var todate = jQuery("#todate").val();
		var room_type_id = jQuery("#ch_roomtypeid").val();
		var rate_plan_id = jQuery("#ch_rateplanid").val();
		if(fromdate.length == 0) {
			jQuery("#fromdate").val(def_date);
			fromdate = def_date;
		}
		if(todate.length == 0) {
			jQuery("#todate").val(fromdate);
			todate = fromdate;
		}
		var df_obj = new Date(fromdate);
		var td_obj = new Date('<?php echo date('Y-m-d'); ?>');
		if(df_obj < td_obj) {
			jQuery("#fromdate").val('<?php echo date('Y-m-d'); ?>');
			fromdate = '<?php echo date('Y-m-d'); ?>';
			df_obj = td_obj;
		}
		var dt_obj = new Date(todate);
		if(dt_obj < df_obj) {
			jQuery("#todate").val(fromdate);
			todate = fromdate;
		}
		jQuery(".vcm-ibe-compare").fadeOut();
		jQuery(".vcm-copy-ratesinv").fadeOut();
		jQuery("#vcm-channel-controls").fadeOut();
		jQuery(".vcmsynchspan").removeClass("vcmsynchspansuccess");
		jQuery(".vcmsynchspan").removeClass("vcmsynchspanerror").addClass("vcmsynchspanloading");
		jQuery("#vcmroomsynchresponsebox").html("");
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php",
			data: { option: "com_vikchannelmanager", task: "exec_rar_rq", from: fromdate, to: todate, roomtypeid: room_type_id, rateplanid: rate_plan_id<?php echo isset($_REQUEST['e4j_debug']) && (int)$_REQUEST['e4j_debug'] == 1 ? ', e4j_debug: 1' : ''; ?>, tmpl: "component" }
		}).done(function(res) { 
			jQuery(".vcmsynchspan").removeClass("vcmsynchspanloading");
			if(res.substr(0, 9) == 'e4j.error') {
				jQuery(".vcmsynchspan").addClass("vcmsynchspanerror");
				jQuery("#vcmroomsynchresponsebox").html("<pre class='vcmpreerror'>" + res.replace("e4j.error.", "") + "</pre>");
			}else {
				jQuery(".vcmsynchspan").addClass("vcmsynchspansuccess");
				jQuery("#vcmroomsynchresponsebox").html(res);
			}
		}).fail(function() { 
			jQuery(".vcmsynchspan").removeClass("vcmsynchspanloading").addClass("vcmsynchspanerror");
			alert("Error Performing Ajax Request"); 
		});
	}
	function vcmCloseModal() {
		jQuery(".vcm-info-overlay-block").fadeOut(400, function() {
			jQuery(this).attr("class", "vcm-info-overlay-block");
		});
		vcm_overlay_on = false;
	}
	function vcmAlertModal(modal_class, modal_title, modal_content) {
		var vcm_modal_cont = "<h3>"+modal_title+"</h3>";
		vcm_modal_cont += modal_content;
		vcm_modal_cont += "<div class=\"vcm-alert-modal-done\"><button type=\"button\" class=\"btn btn-success\" onclick=\"javascript: vcmCloseModal();\"><?php echo JText::_('VCMALERTMODALOK'); ?></button></div>";
		jQuery(".vcm-info-overlay-content").html(vcm_modal_cont);
		jQuery(".vcm-info-overlay-block").addClass("vcm-modal-alert "+modal_class).fadeIn();
		vcm_overlay_on = true;
	}
	var hold_alt = false;
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
		jQuery(document).keydown(function(e) {
			if(e.altKey === true) {
				hold_alt = true;
			}
		});
		jQuery(document).keyup(function(e) {
			if (e.keyCode == 27 && vcm_overlay_on) {
				vcmCloseModal();
			}
			hold_alt = false;
		});
		jQuery(".vcmlargeheading").click(function(){
			if(!hold_alt) {
				jQuery(".vcmrelationshema").fadeToggle();
			}else {
				jQuery(this).remove();
				jQuery(".vcmrelationshema").remove();
			}
		});
		jQuery(".vcmshowinfo").click(function() {
			var rinfo_cont_id = jQuery(this).attr("data-infotag");
			var rinfo_cont_html = "<h3>"+jQuery(this).text()+"</h3>";
			rinfo_cont_html += jQuery("#"+rinfo_cont_id).html();
			jQuery(".vcm-info-overlay-content").html(rinfo_cont_html);
			jQuery(".vcm-info-overlay-block").fadeIn();
			vcm_overlay_on = true;
		});
		jQuery("#vcmstartsynch").click(function() {
			startRarRq();
		});
	<?php
	if($fill_dates) {
		?>
		jQuery("#fromdate").val(def_date);
		jQuery("#todate").val(def_todate);
		jQuery("#ch_roomtypeid").val(def_roomtypeid);
		jQuery("#ch_roomtypeid").trigger("change");
		jQuery("#ch_rateplanid").val(def_rateplanid);
		<?php
	}
	if($start_rq) {
		?>
		startRarRq();
		<?php
	}
		?>
	});
	</script>
	
	<br clear="all"/>
	<span class="vcminlinedate"><?php echo JText::_('VCMFROMDATE'); ?> <?php echo JHTML::_('calendar', '', 'fromdate', 'fromdate', '%Y-%m-%d', array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?></span>
	<span class="vcminlinedate"><?php echo JText::_('VCMTODATE'); ?> <?php echo JHTML::_('calendar', '', 'todate', 'todate', '%Y-%m-%d', array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?></span>
<?php
if($channel['uniquekey'] == VikChannelManagerConfig::AGODA || $channel['uniquekey'] == VikChannelManagerConfig::YCS50) {
	$channel_pricing = array();
	$channel_rooms = array();
	$rooms_rate_plans = array();
	foreach ($rows as $room) {
		$channel_pricing[$room['idroomota']] = json_decode($room['otapricing'], true);
		$channel_rooms[$room['idroomota']] = $room['otaroomname'];
	}
	$room_types_sel = '<select name="ch_roomtypeid" id="ch_roomtypeid" onchange="javascript: vcmSetRoomRatePlans(this.value);">'."\n";
	$rate_plans_sel = '<select name="ch_rateplanid" id="ch_rateplanid">'."\n";
	$rate_plans_caught = false;
	foreach ($channel_pricing as $idroom => $rateplans) {
		$room_types_sel .= '<option value="'.$idroom.'">'.$channel_rooms[$idroom].'</option>'."\n";
		foreach ($rateplans['RatePlan'] as $rateplan) {
			$rateplan_info = array(
				'id' => $rateplan['id'],
				'name' => $rateplan['name'],
				'rate_type' => (array_key_exists('rate_type', $rateplan) ? ' ('.$rateplan['rate_type'].')' : ''),
				'tax_included' => (array_key_exists('tax_included', $rateplan) ? ((int)$rateplan['tax_included'] == 1 ? 'Yes' : 'No' ) : '')
			);
			$rooms_rate_plans[$idroom][$rateplan['id']] = $rateplan_info;
			if(!$rate_plans_caught) {
				$rate_plans_sel .= '<option value="'.$rateplan['id'].'">'.$rateplan['name'].' '.(array_key_exists('rate_type', $rateplan) ? '('.$rateplan['rate_type'].')' : '').'</option>'."\n";
			}
		}
		$rate_plans_caught = true;
	}
	$room_types_sel .= '</select>'."\n";
	$rate_plans_sel .= '</select>'."\n";
	?>
	<script type="text/javascript">
	var rooms_rate_plans = <?php echo json_encode($rooms_rate_plans); ?>;
	function vcmCleanRoomRatePlans() {
		jQuery("#ch_rateplanid").html("").val("");
	}
	function vcmSetRoomRatePlans(rtype_id) {
		vcmCleanRoomRatePlans();
		if(rooms_rate_plans.hasOwnProperty(rtype_id)) {
			var allrateplans = "";
			for(var i in rooms_rate_plans[rtype_id]) {
				if(rooms_rate_plans[rtype_id].hasOwnProperty(i)) {
					allrateplans += "<option value=\""+rooms_rate_plans[rtype_id][i].id+"\" title=\"Tax Included: "+rooms_rate_plans[rtype_id][i].tax_included+"\">"+rooms_rate_plans[rtype_id][i].name+rooms_rate_plans[rtype_id][i].rate_type+"</option>";
				}
			}
			jQuery("#ch_rateplanid").append(allrateplans);
		}
	}
	</script>
	<span class="vcminlinedate"><?php echo $room_types_sel; ?></span>
	<span class="vcminlinedate"><?php echo $rate_plans_sel; ?></span>
	<?php
}elseif($channel['uniquekey'] == VikChannelManagerConfig::EXPEDIA) {
	$channel_pricing = array();
	$channel_rooms = array();
	$rooms_rate_plans = array();
	foreach ($rows as $room) {
		$channel_pricing[$room['idroomota']] = json_decode($room['otapricing'], true);
		$channel_rooms[$room['idroomota']] = $room['otaroomname'];
	}
	$room_types_sel = '<select name="ch_roomtypeid" id="ch_roomtypeid" onchange="javascript: vcmSetRoomRatePlans(this.value);">'."\n";
	$room_types_sel .= '<option value="">'.JText::_('VCMALLROOMTYPES').'</option>'."\n";
	$rate_plans_sel = '<select name="ch_rateplanid" id="ch_rateplanid">'."\n";
	$rate_plans_sel .= '<option value="">'.JText::_('VCMALLRATEPLANS').'</option>'."\n";
	foreach ($channel_pricing as $idroom => $rateplans) {
		$room_types_sel .= '<option value="'.$idroom.'">- '.$channel_rooms[$idroom].'</option>'."\n";
		foreach ($rateplans['RatePlan'] as $rateplan) {
			$rateplan_info = array(
				'id' => $rateplan['id'],
				'name' => $rateplan['name'],
				'distributionModel' => $rateplan['distributionModel'],
				'rateAcquisitionType' => $rateplan['rateAcquisitionType'],
				'pricingModel' => $rateplan['pricingModel']
			);
			$rooms_rate_plans[$idroom][$rateplan['id']] = $rateplan_info;
		}
	}
	$room_types_sel .= '</select>'."\n";
	$rate_plans_sel .= '</select>'."\n";
	?>
	<script type="text/javascript">
	var rooms_rate_plans = <?php echo json_encode($rooms_rate_plans); ?>;
	function vcmCleanRoomRatePlans() {
		jQuery("#ch_rateplanid").html("<option value=\"\"><?php echo addslashes(JText::_('VCMALLRATEPLANS')); ?></option>").val("");
	}
	function vcmSetRoomRatePlans(rtype_id) {
		vcmCleanRoomRatePlans();
		if(rooms_rate_plans.hasOwnProperty(rtype_id)) {
			var allrateplans = "";
			for(var i in rooms_rate_plans[rtype_id]) {
				if(rooms_rate_plans[rtype_id].hasOwnProperty(i)) {
					allrateplans += "<option value=\""+rooms_rate_plans[rtype_id][i].id+"\" title=\""+rooms_rate_plans[rtype_id][i].pricingModel+"\">- "+rooms_rate_plans[rtype_id][i].name+" ("+rooms_rate_plans[rtype_id][i].distributionModel+")</option>";
				}
			}
			jQuery("#ch_rateplanid").append(allrateplans);
		}
	}
	</script>
	<span class="vcminlinedate"><?php echo $room_types_sel; ?></span>
	<span class="vcminlinedate"><?php echo $rate_plans_sel; ?></span>
	<?php
}elseif($channel['uniquekey'] == VikChannelManagerConfig::BOOKING) {
	$channel_pricing = array();
	$channel_rooms = array();
	$rooms_rate_plans = array();
	foreach ($rows as $room) {
		$channel_pricing[$room['idroomota']] = json_decode($room['otapricing'], true);
		$channel_rooms[$room['idroomota']] = $room['otaroomname'];
	}
	$room_types_sel = '<select name="ch_roomtypeid" id="ch_roomtypeid" onchange="javascript: vcmSetRoomRatePlans(this.value);">'."\n";
	$room_types_sel .= '<option value="">'.JText::_('VCMALLROOMTYPES').'</option>'."\n";
	$rate_plans_sel = '<select name="ch_rateplanid" id="ch_rateplanid">'."\n";
	$rate_plans_sel .= '<option value="">'.JText::_('VCMALLRATEPLANS').'</option>'."\n";
	foreach ($channel_pricing as $idroom => $rateplans) {
		$room_types_sel .= '<option value="'.$idroom.'">- '.$channel_rooms[$idroom].'</option>'."\n";
		foreach ($rateplans['RatePlan'] as $rateplan) {
			$rateplan_info = array(
				'id' => $rateplan['id'],
				'name' => $rateplan['name'],
				'is_child_rate' => (array_key_exists('is_child_rate', $rateplan) && intval($rateplan['is_child_rate']) == 1 ? ' (Derived Rate)' : ''),
				'max_persons' => $rateplan['max_persons'],
				'policy' => $rateplan['policy']
			);
			$rooms_rate_plans[$idroom][$rateplan['id']] = $rateplan_info;
		}
	}
	$room_types_sel .= '</select>'."\n";
	$rate_plans_sel .= '</select>'."\n";
	?>
	<script type="text/javascript">
	var rooms_rate_plans = <?php echo json_encode($rooms_rate_plans); ?>;
	function vcmCleanRoomRatePlans() {
		jQuery("#ch_rateplanid").html("<option value=\"\"><?php echo addslashes(JText::_('VCMALLRATEPLANS')); ?></option>").val("");
	}
	function vcmSetRoomRatePlans(rtype_id) {
		vcmCleanRoomRatePlans();
		if(rooms_rate_plans.hasOwnProperty(rtype_id)) {
			var allrateplans = "";
			for(var i in rooms_rate_plans[rtype_id]) {
				if(rooms_rate_plans[rtype_id].hasOwnProperty(i)) {
					allrateplans += "<option value=\""+rooms_rate_plans[rtype_id][i].id+"\" title=\"Policy: "+rooms_rate_plans[rtype_id][i].policy+", Max Persons: "+rooms_rate_plans[rtype_id][i].max_persons+rooms_rate_plans[rtype_id][i].is_child_rate+"\">- "+rooms_rate_plans[rtype_id][i].name+rooms_rate_plans[rtype_id][i].is_child_rate+"</option>";
				}
			}
			jQuery("#ch_rateplanid").append(allrateplans);
		}
	}
	</script>
	<span class="vcminlinedate"><?php echo $room_types_sel; ?></span>
	<span class="vcminlinedate"><?php echo $rate_plans_sel; ?></span>
	<?php
}else {
	echo '<input type="hidden" name="ch_roomtypeid" id="ch_roomtypeid" value=""/><input type="hidden" name="ch_rateplanid" id="ch_rateplanid" value=""/>';
}
?>
	<span class="vcmsynchspan">
		<a class="vcmsyncha" href="javascript: void(0);" id="vcmstartsynch"><?php echo JText::_('VCMSTARTROOMSRAR'); ?></a>
	</span>

	<div class="vcm-channel-toolbar">
		<span class="vcm-separe">&nbsp;</span>
		<span class="vcm-copy-ratesinv"><?php echo JText::_('VCMCOPYRATESINV'); ?></span>
		<span class="vcm-separe">&nbsp;</span>
		<span class="vcm-ibe-compare"><?php echo JText::_('VCMIBECOMPARE'); ?></span>
		<span class="vcm-separe">&nbsp;</span>
		<span id="vcm-channel-controls"></span>
	</div>

	<br clear="all"/>
	
	<form action="index.php?option=com_vikchannelmanager" method="post" name="adminForm" id="adminForm">
		
		<div id="vcmcopyrateshelper" style="display: none;">
			<h3><?php echo JText::_('VCMCOPYRATESINV'); ?></h3>
			<div class="vcm-copyrar-left">
				<div class="vcm-copyrar-new">
					<label for="vcm-copyrar-fromday"><?php echo (in_array($channel['uniquekey'], array(VikChannelManagerConfig::BOOKING, VikChannelManagerConfig::AGODA, VikChannelManagerConfig::YCS50)) ? JText::_('VCMCOPYRATESINVFROMOR') : JText::_('VCMCOPYRATESINVFROM')); ?></label>
				</div>
				<!--
				<div class="vcm-copyrar-where">
					<span style="display: block;"><?php echo JText::_('VCMCOPYRATESINVWHERE'); ?></span>
					<div class="vcm-copyrar-where-radios">
						<span><label for="copywhereota"><?php echo JText::_('VCMCOPYRATESINVWHEREOTA'); ?></label> <input type="radio" name="copywhereradio" id="copywhereota" class="copywhereota" value="ota" checked="checked"/></span>
						<span><label for="copywhereibe"><?php echo JText::_('VCMCOPYRATESINVWHEREIBE'); ?></label> <input type="radio" name="copywhereradio" id="copywhereibe" value="ibe"/></span>
					</div>
				</div>
				-->
			</div>
			<div class="vcm-copyrar-right">
				<div class="vcm-copyrar-new">
					<label for="vcm-copyrar-todate"><?php echo JText::_('VCMCOPYRATESINVTO'); ?></label>
					<input type="number" id="vcm-copyrar-todate" class="vcm-skip-inp-listener" value="0" min="0" max="365"/>
					<span class="vcm-copyrar-tocalc-cont">
						<span class="vcm-copyrar-tocalc"></span>
					</span>
				</div>
			</div>
			<div class="vcm-copyrar-bottom">
				<button type="button" class="btn vcm-copyrar-apply"><i class="icon-apply"></i><?php echo JText::_('VCMCOPYRATESINVAPPLY'); ?></button>
			</div>
			<div class="vcm-copyrar-applied"></div>
			<div class="vcm-copyrar-done">
				<button type="button" class="btn btn-success" onclick="javascript: vcmCloseModal();"><?php echo JText::_('VCMCOPYRATESINVDONE'); ?></button>
			</div>
		</div>

		<div id="vcmroomsynchresponsebox"></div>
		
		<input type="hidden" name="option" value="com_vikchannelmanager" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
	<script type="text/javascript">
	/* Calculate the Date of start_date + n Days */
	function vcmCalDateTo(start_date, plus_days) {
		var sd = new Date(start_date);
		if(plus_days > 0) {
			sd.setDate(sd.getDate() + plus_days);
			var calc_month = (sd.getMonth() + 1);
			calc_month = calc_month < 10 ? "0"+calc_month : calc_month;
			var calc_day = (sd.getDate());
			calc_day = calc_day < 10 ? "0"+calc_day : calc_day;
			var calc_todate = sd.getFullYear()+"-"+calc_month+"-"+calc_day;
			return calc_todate;
		}
		return false;
	}
	/* Counts how many rates will be copied */
	function vcmCountCopyRates() {
		var vcm_tot_copyrates = jQuery("#vcmcopyrateshelper .vcm-copyrar-applied").find("div").length;
		if(vcm_tot_copyrates > 0) {
			jQuery(".vcm-copy-ratesinv").addClass("vcm-copy-ratesinv-full").attr("data-content", ""+vcm_tot_copyrates);
		}else {
			jQuery(".vcm-copy-ratesinv").removeClass("vcm-copy-ratesinv-full").attr("data-content", "");
		}
	}
	/* Toggle comparison blocks with IBE */
	jQuery("body").on("click", ".vcm-ibe-compare", function() {
		jQuery('.vcm-comparison').toggle();
		jQuery(this).toggleClass("vcm-ibe-compare-active");
	});
	/* Open and compose modal box for copying the rates and inventory */
	jQuery("body").on("click", ".vcm-copy-ratesinv", function() {
		if(vcm_rar_days.length > 0) {
			var vcm_rar_copy_daysel = "<select id=\"vcm-copyrar-fromday\">";
			for(var iday in vcm_rar_days) {
				if(vcm_rar_days.hasOwnProperty(iday)) {
					vcm_rar_copy_daysel += "<option value=\""+vcm_rar_days[iday]+"\">"+vcm_rar_days[iday]+"</option>";
				}
			}
			vcm_rar_copy_daysel += "</select>";
		<?php
		//function for copying only the Rates, keeping the Availability unchanged: only some channels support it
		if(in_array($channel['uniquekey'], array(VikChannelManagerConfig::BOOKING, VikChannelManagerConfig::EXPEDIA, VikChannelManagerConfig::AGODA, VikChannelManagerConfig::YCS50))) {
			?>
			vcm_rar_copy_daysel += "<br id=\"vcm-copyrar-copyskipav-sep\"/><select id=\"vcm-copyrar-copyskipav\"><option value=\"\" title=\"<?php echo addslashes(JText::_('VCMCOPYRATESINVNOSKIPEXPL')); ?>\"><?php echo addslashes(JText::_('VCMCOPYRATESINVNOSKIP')); ?></option><option value=\"1\" title=\"<?php echo addslashes(JText::_('VCMCOPYRATESINVSKIPAVEXPL')); ?>\" selected=\"selected\"><?php echo addslashes(JText::_('VCMCOPYRATESINVSKIPAV')); ?></option></select>";
			<?php
		}
		?>
			jQuery(".vcm-copyrar-left .vcm-copyrar-new").append(vcm_rar_copy_daysel);
			var vcm_rar_copy_cont = jQuery("#vcmcopyrateshelper").html();
			jQuery(".vcm-copyrar-left .vcm-copyrar-new #vcm-copyrar-fromday").remove();
			jQuery(".vcm-copyrar-left .vcm-copyrar-new #vcm-copyrar-copyskipav").remove();
			jQuery(".vcm-copyrar-left .vcm-copyrar-new #vcm-copyrar-copyskipav-sep").remove();
			jQuery(".vcm-info-overlay-content").html(vcm_rar_copy_cont);
			jQuery(".vcm-info-overlay-block").fadeIn();
			vcm_overlay_on = true;
		}
	});
	/* Calculate To-Date for copying the rates and inventory */
	jQuery("body").on("change", "#vcm-copyrar-fromday", function() {
		jQuery("#vcm-copyrar-todate").trigger("change");
	});
	jQuery("body").on("change", "#vcm-copyrar-todate", function() {
		var vcm_copy_from = jQuery("#vcm-copyrar-fromday").val();
		if(vcm_copy_from.length > 0) {
			var vcm_copy_to = vcmCalDateTo(vcm_copy_from, parseInt(jQuery(this).val()));
			if(vcm_copy_to !== false) {
				jQuery(this).parent().find(".vcm-copyrar-tocalc").text(vcm_copy_to);
			}else {
				jQuery(this).parent().find(".vcm-copyrar-tocalc").text("");
			}
		}
	});
	/* Apply To-Date for copying the rates and inventory */
	jQuery("body").on("click", ".vcm-copyrar-apply", function() {
		var vcm_copy_from = jQuery("#vcm-copyrar-fromday").val();
		var vcm_copy_skipav = jQuery("#vcm-copyrar-copyskipav").length ? jQuery("#vcm-copyrar-copyskipav").val() : '';
		var vcm_copy_skipav_text = '';
		if(jQuery("#vcm-copyrar-copyskipav").length) {
			vcm_copy_skipav_text = jQuery("#vcm-copyrar-copyskipav option:selected").text();
		}
		var vcm_copy_for = jQuery(".vcm-info-overlay-content").find("#vcm-copyrar-todate").val();
		var vcm_copy_where = 'ota';
		jQuery(".copywhereota").each(function() {
			if(!jQuery(".copywhereota").prop('checked')) {
				vcm_copy_where = 'ibe';
				return false;
			}
		});
		if(vcm_copy_from.length > 0 && vcm_copy_for.length > 0) {
			var vcm_copy_to = vcmCalDateTo(vcm_copy_from, parseInt(vcm_copy_for));
			if(vcm_copy_to !== false) {
				var vcm_copy_class = 'vcm-copyrar-applied-'+vcm_copy_from;
				if(jQuery("."+vcm_copy_class).length == 0) {
					jQuery(".vcm-copyrar-applied").append("<div class=\""+vcm_copy_class+"\" data-setcopyfrom=\""+vcm_copy_from+"\"><span class=\"vcm-copyrar-copyfrom\">"+vcm_copy_from+"</span>-<span class=\"vcm-copyrar-copyto\">"+vcm_copy_to+"</span>"+(vcm_copy_skipav.length && vcm_copy_skipav_text.length ? " <span class=\"vcm-copyrar-copyto\">("+vcm_copy_skipav_text+")</span>" : "")+"<span class=\"vcm-copyrar-copyunset\"> </span></div>");
					jQuery(".vcm-copyrar-applied").append("<input type=\"hidden\" name=\"copyinventory[]\" value=\""+vcm_copy_from+","+vcm_copy_to+"\" class=\""+vcm_copy_class+"\"/>");
					jQuery(".vcm-copyrar-applied").append("<input type=\"hidden\" name=\"copyinventorywhere[]\" value=\""+vcm_copy_where+"\" class=\""+vcm_copy_class+"\"/>");
					/* Some channels support the copy Rates Only function, if requested, disable the input number for the current Availability because it should not be submitted */
					if(vcm_copy_skipav.length) {
						jQuery("input[name^='inv_"+vcm_copy_from+"']").prop("disabled", true);
					}
					/* Trig the change event for having the checkbox for that date checked */
					jQuery("input[name^='inv_"+vcm_copy_from+"']").trigger("change");
					vcmCountCopyRates();
				}else {
					alert('<?php echo addslashes(JText::_('VCMCOPYRATESINVERREX')); ?>');
					return false;
				}
			}
		}
	});
	/* Unset To-Date for copying the ratens and inventory */
	jQuery("body").on("click", ".vcm-copyrar-copyunset", function() {
		var vcm_remove_class = jQuery(this).parent("div").attr("class");
		if(vcm_remove_class.length > 0) {
			/* The input type number may have been disabled for copying only the Rates so check it and enable it again */
			var vcm_set_copy_from = jQuery(this).parent("div").attr("data-setcopyfrom");
			if(vcm_set_copy_from.length) {
				if(jQuery("input[name^='inv_"+vcm_set_copy_from+"']").length) {
					if(jQuery("input[name^='inv_"+vcm_set_copy_from+"']").prop("disabled") === true) {
						jQuery("input[name^='inv_"+vcm_set_copy_from+"']").prop("disabled", false);
					}
				}
			}
			/* Unset the elements for copy */
			jQuery("."+vcm_remove_class).remove();
			vcmCountCopyRates();
		}
	});
	/* Toggle IBE comparison tabs of types of price */
	jQuery("body").on("click", ".vcm-compare-ratetab", function() {
		var rate_tab_id = jQuery(this).attr("id");
		jQuery(this).parent("div").find(".vcm-compare-ratetab").removeClass("vcm-compare-ratetab-active");
		jQuery(this).addClass("vcm-compare-ratetab-active");
		jQuery(this).parent("div").find(".vcm-compare-pricecols").removeClass("vcm-compare-pricecols-active");
		jQuery("."+rate_tab_id).addClass("vcm-compare-pricecols-active");
	});
	/* Copy costs from IBE */
	jQuery("body").on("click", ".vcm-compare-rates-copycost", function() {
		var price_id = jQuery(this).attr("id");
		var price_num = jQuery(this).parent("div").find(".vcm-compare-pricebox").text();
		var inp_field = jQuery("input[name=rateplan_"+price_id+"]");
		var price_len = (price_num.length * 2);
		inp_field.val(price_num);
		inp_field.focus();
		inp_field.trigger("change");
		inp_field[0].setSelectionRange(price_len, price_len);
	});
	/* Copy all costs from IBE */
	jQuery("body").on("click", ".vcm-compare-copyall", function() {
		jQuery(this).parent(".vcm-compare-pricecols").find(".vcm-compare-pricebox").each(function(){
			var price_id = jQuery(this).parent("div").find(".vcm-compare-rates-copycost").attr("id");
			var price_num = jQuery(this).text();
			var inp_field = jQuery("input[name=rateplan_"+price_id+"]");
			var price_len = (price_num.length * 2);
			inp_field.val(price_num);
			inp_field.focus();
			inp_field.trigger("change");
			inp_field[0].setSelectionRange(price_len, price_len);
		});
		jQuery(this).fadeOut();
	});
	/* Automatically Tick the checkboxe for the modified day */
	jQuery("body").on("change", "input", function() {
		if(jQuery(this).attr("type") != "checkbox" && !jQuery(this).hasClass("vcm-skip-inp-listener")) {
			var first = jQuery(this).closest("tr");
			var ckb = '';
			do {
				ckb = first.find("td").find(".vcm-rar-ckb");
				first = first.prev("tr");
			} while(ckb.length == 0 && first.length != 0);
			ckb.prop("checked", true);
			<?php echo $vik->checkboxOnClick('true'); ?>
		}
	});
	</script>
<?php
}
?>