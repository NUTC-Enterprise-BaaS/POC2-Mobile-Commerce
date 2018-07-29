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

$MAX_DAYS = $this->maxDays;
$MAX_TO_DISPLAY = $this->maxDaysToDisplay;

$cookie = JFactory::getApplication()->input->cookie;

$room_max_units = array();

$nowts=getdate($this->tsstart);
$days_labels = array(
		JText::_('VBSUN'),
		JText::_('VBMON'),
		JText::_('VBTUE'),
		JText::_('VBWED'),
		JText::_('VBTHU'),
		JText::_('VBFRI'),
		JText::_('VBSAT')
);

$months_labels = array(
        JText::_('VCMMONTHONE'),
        JText::_('VCMMONTHTWO'),
        JText::_('VCMMONTHTHREE'),
        JText::_('VCMMONTHFOUR'),
        JText::_('VCMMONTHFIVE'),
        JText::_('VCMMONTHSIX'),
        JText::_('VCMMONTHSEVEN'),
        JText::_('VCMMONTHEIGHT'),
        JText::_('VCMMONTHNINE'),
        JText::_('VCMMONTHTEN'),
        JText::_('VCMMONTHELEVEN'),
        JText::_('VCMMONTHTWELVE')
);

foreach( $months_labels as $i => $v ) {
    $months_labels[$i] = mb_substr($v, 0, 3, 'UTF-8');
}

?>
<script type="text/javascript">
var vcm_acmp_obj;

var vcm_overlay_on = false;

var sel_format = "<?php echo VikChannelManager::getClearDateFormat(true); ?>";

if( sel_format == "Y/m/d") {
    Date.prototype.format = "yy/mm/dd";
} else if( sel_format == "m/d/Y" ) {
    Date.prototype.format = "mm/dd/yy";
} else {
    Date.prototype.format = "dd/mm/yy";
}
/* Loading Overlay */
function vcmShowLoading() {
    jQuery(".vcm-loading-overlay").show();
}
function vcmStopLoading() {
    jQuery(".vcm-loading-overlay").hide();
}
/* Modal Window */
function vcmCloseModal(dontaskagain) {
    jQuery(".vcm-info-overlay-block").fadeOut(400, function() {
        jQuery(this).attr("class", "vcm-info-overlay-block");
    });
    var nd = new Date();
    if(dontaskagain) {
        nd.setTime(nd.getTime() + (365*24*60*60*1000));
    }else {
        nd.setTime(nd.getTime() - (24*60*60*1000));
    }
    document.cookie = "vcmOverviewATip=1; expires=" + nd.toUTCString();
    vcm_overlay_on = false;
}
function vcmTipModal() {
    var vcm_modal_cont = "<h3><?php echo addslashes(JText::_('VCMTIPMODALTITLE')); ?></h3>";
    vcm_modal_cont += "<p style=\"text-align: center;\"><?php echo addslashes(JText::_('VCMTIPMODALTEXTAV')); ?></p><p style=\"text-align: center;\"><img src=\"<?php echo JURI::root(); ?>administrator/components/com_vikchannelmanager/assets/css/images/overview_tip_av.gif\" alt=\"Set Custom Availability Example\"/></p>";
    vcm_modal_cont += "<div class=\"vcm-tip-modal-done\"><button type=\"button\" class=\"btn btn-primary\" onclick=\"javascript: vcmCloseModal(false);\"><?php echo addslashes(JText::_('VCMTIPMODALOKREMIND')); ?></button> &nbsp;&nbsp; <button type=\"button\" class=\"btn btn-success\" onclick=\"javascript: vcmCloseModal(true);\"><?php echo addslashes(JText::_('VCMTIPMODALOK')); ?></button></div>";
    jQuery(".vcm-info-overlay-content").html(vcm_modal_cont);
    jQuery(".vcm-info-overlay-block").addClass("vcm-modal-tip").fadeIn();
    vcm_overlay_on = true;
}
jQuery(document).ready(function(){
    jQuery(document).mouseup(function(e) {
        if(!vcm_overlay_on) {
            return false;
        }
        var vcm_overlay_cont = jQuery(".vcm-info-overlay-content");
        if(!vcm_overlay_cont.is(e.target) && vcm_overlay_cont.has(e.target).length === 0) {
            vcmCloseModal(false);
        }
    });
    jQuery(document).keyup(function(e) {
        if (e.keyCode == 27 && vcm_overlay_on) {
            vcmCloseModal(true);
        }
    });
<?php
$cookie_ovavtip = $cookie->get('vcmOverviewATip', '', 'string');
if(empty($cookie_ovavtip)) {
    ?>
    vcmTipModal();
    <?php
}
?>
});
</script>

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

<div class="vcm-oversight-toolbar-left">
    <form action="index.php?option=com_vikchannelmanager&amp;task=oversight" method="post" name="vboverview">
        <?php echo $this->wmonthsel; ?>
    </form>
    <div class="vcm-customa-changes">
        <span><?php echo JText::_('VCMCUSTOMACHANGES'); ?></span>
        <span id="vcm-totchanges">0</span>
    </div>
    <div class="vcm-customa-legend-block">
        <div class="vcm-customa-legend-entry">
            <span class="vcm-customa-legend-box vcm-customa-legend-green"> </span>
            <span class="vcm-customa-legend-label"><?php echo JText::_('VCMOVERSIGHTLEGENDGREEN'); ?></span>
        </div>
        <div class="vcm-customa-legend-entry">
            <span class="vcm-customa-legend-box vcm-customa-legend-purple"> </span>
            <span class="vcm-customa-legend-label"><?php echo JText::_('VCMOVERSIGHTLEGENDPURPLE'); ?></span>
        </div>
        <div class="vcm-customa-legend-entry">
            <span class="vcm-customa-legend-box vcm-customa-legend-greenself"> </span>
            <span class="vcm-customa-legend-label"><?php echo JText::_('VCMOVERSIGHTLEGENDGREENSELF'); ?></span>
        </div>
        <div class="vcm-customa-legend-entry">
            <span class="vcm-customa-legend-box vcm-customa-legend-red"> </span>
            <span class="vcm-customa-legend-label"><?php echo JText::_('VCMOVERSIGHTLEGENDRED'); ?></span>
        </div>
        <div class="vcm-customa-legend-entry">
            <span class="vcm-customa-legend-box vcm-customa-legend-sky"> </span>
            <span class="vcm-customa-legend-label"><?php echo JText::_('VCMOVERSIGHTLEGENDSKY'); ?></span>
        </div>
        <div class="vcm-customa-legend-entry">
            <span class="vcm-customa-legend-box vcm-customa-legend-pink"> </span>
            <span class="vcm-customa-legend-label"><?php echo JText::_('VCMOVERSIGHTLEGENDPINK'); ?></span>
        </div>
        <div class="vcm-customa-legend-entry">
            <span class="vcm-customa-legend-box vcm-customa-legend-dashed">--</span>
            <span class="vcm-customa-legend-label"><?php echo JText::_('VCMOVERSIGHTLEGENDDASHED'); ?></span>
        </div>
    </div>
<?php
if($this->acmp_rq_enabled == 1) {
    ?>
    <div class="vcm-oversight-acmp-block">
        <span class="vcmsynchspan vcmsynchspan-acmp">
            <a id="vcmstartsynch" href="javascript: void(0);" class="vcmsyncha" data-startdate="<?php echo $this->acmp_rq_start; ?>"><?php echo JText::_('VCMOVERVIEWACMPRQLAUNCH'); ?></a>
        </span>
        <div class="vcm-oversight-acmp-response"></div>
    </div>
    <script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("#vcmstartsynch").click(function() {
            /* Show loading when sending ACMP_RQ to prevent double submit */
            vcmShowLoading();
            /*  */
            var acmp_fromdate = jQuery(this).attr("data-startdate");
            jQuery(".vcmsynchspan").removeClass("vcmsynchspansuccess");
            jQuery(".vcmsynchspan").removeClass("vcmsynchspanerror").addClass("vcmsynchspanloading");
            jQuery(".vcm-oversight-acmp-response").html("");
            jQuery(".vcm-acmp-channel-row").remove();
            var jqxhr = jQuery.ajax({
                type: "POST",
                url: "index.php",
                data: { option: "com_vikchannelmanager", task: "exec_acmp_rq", from: acmp_fromdate<?php echo isset($_REQUEST['e4j_debug']) && (int)$_REQUEST['e4j_debug'] == 1 ? ', e4j_debug: 1' : ''; ?>, tmpl: "component" }
            }).done(function(res) {
                jQuery(".vcmsynchspan").removeClass("vcmsynchspanloading");
                if(res.substr(0, 9) == 'e4j.error') {
                    jQuery(".vcmsynchspan").addClass("vcmsynchspanerror");
                    jQuery(".vcm-oversight-acmp-response").html("<pre class='vcmpreerror'>" + res.replace("e4j.error.", "") + "</pre>");
                }else {
                    jQuery(".vcmsynchspan").addClass("vcmsynchspansuccess");
                    vcm_acmp_obj = jQuery.parseJSON(res);
                    if(vcm_acmp_obj.hasOwnProperty('errors')) {
                        jQuery(".vcm-oversight-acmp-response").html("<pre class='vcmpreerror'>" + vcm_acmp_obj.errors + "</pre>");
                        delete vcm_acmp_obj.errors;
                    }
                    jQuery.each(vcm_acmp_obj, function(idroomvb, channels){
                        var vcm_room_row = jQuery("#vcm-acmp-row-"+idroomvb);
                        if(!vcm_room_row.length) {
                            return;
                        }
                        var tot_cells = vcm_room_row.find("td").length;
                        var row_cells = vcm_room_row.find("td");
                        jQuery.each(channels, function(channel_name, avdates) {
                            var channel_row = "<tr class=\"vcm-acmp-channel-row vcm-acmp-channel-"+channel_name.replace(".", "").toLowerCase()+"\">";
                            channel_row += "<td class=\"vcm-acmp-channel-cell\">"+channel_name+"</td>";
                            for(var tdind = 1; tdind < tot_cells; tdind++) {
                                var td_date = jQuery(row_cells[tdind]).attr("data-acmpdate");
                                var td_vbounits = jQuery(row_cells[tdind]).attr("data-vbounits");
                                var td_visible = jQuery(row_cells[tdind]).is(":visible") ? "" : " style=\"display: none;\"";
                                var td_date_parts = td_date.split("-");
                                if(avdates.hasOwnProperty(td_date)) {
                                    var td_roomclosed = parseInt(avdates[td_date]['Closed']) == 1 ? 'vcm-closedinventorytd ' : '';
                                    var td_unitsmismatch = parseInt(td_vbounits) != parseInt(avdates[td_date]['Inventory']) ? 'vcm-tdcomparemismatch ' : '';
                                    td_unitsmismatch = parseInt(avdates[td_date]['Closed']) == 1 && parseInt(td_vbounits) <= 0 ? '' : td_unitsmismatch;
                                    channel_row += "<td class=\""+td_roomclosed+td_unitsmismatch+"cell-"+parseInt(td_date_parts[2])+"-"+parseInt(td_date_parts[1])+"\""+td_visible+">"+avdates[td_date]['Inventory']+"</td>";
                                }else {
                                    channel_row += "<td class=\"vcm-noinventorytd cell-"+parseInt(td_date_parts[2])+"-"+parseInt(td_date_parts[1])+"\""+td_visible+">---</td>";
                                }
                            }
                            channel_row += "</tr>";
                            vcm_room_row.after(channel_row);
                        });
                    });
                }
                /* Stop loading when sending ACMP_RQ to prevent double submit */
                vcmStopLoading();
                /*  */
            }).fail(function() {
                jQuery(".vcmsynchspan").removeClass("vcmsynchspanloading").addClass("vcmsynchspanerror");
                alert("Error Performing Ajax Request");
                /* Stop loading when sending ACMP_RQ to prevent double submit */
                vcmStopLoading();
                /*  */
            });
        });
        <?php
    $force_acmp_load = JRequest::getInt('loadacmp', '', 'request');
    if($force_acmp_load == 1 && !empty($this->acmp_last_request)) {
        ?>
        jQuery("#vcmstartsynch").trigger("click");
        <?php
    }
    ?>
    });
    </script>
    <?php
}
?>
</div>

<br clear="all" />

<div class="vcm-table-responsive">
    <table class="vcmoverviewtable vcm-table">
        <tr class="vcmoverviewtablerowone">
            <td class="bluedays">
                <form action="index.php?option=com_vikchannelmanager&amp;task=oversight" method="post" name="vcmoverview">
                    <input type="hidden" id="loadacmp" name="loadacmp" value="0" />
                    <a href="javascript: void(0);" onClick="prevWeek();" class="vcmosprevweek">&lt;&lt;</a>
                    <a href="javascript: void(0);" onClick="prevDay();" class="vcmosprevday">&lt;</a>
                    <input type="text" name="datepicker" id="vcmdatepicker" class="vcmdatepicker" value="<?php echo date(VikChannelManager::getClearDateFormat(true), $this->tsstart); ?>" autocomplete="off"/>
                    <a href="javascript: void(0);" onClick="nextDay();" class="vcmosnextday">&gt;</a>
                    <a href="javascript: void(0);" onClick="nextWeek();" class="vcmosnextweek">&gt;&gt;</a>
                </form>
            </td>
<?php
$mon=$nowts['mon'];
$cell_count = 0;

$start_day_id = 'cell-'.$nowts['mday'].'-'.$nowts['mon'];
$end_day_id = '';
while( $cell_count < $MAX_DAYS ) {
    $style = '';
    if( $cell_count >= $MAX_TO_DISPLAY ) {
        $style = 'style="display: none;"';
    } else {
        $end_day_id = 'cell-'.$nowts['mday'].'-'.$nowts['mon'];
    }
	echo '<td class="bluedays cell-'.$nowts['mday'].'-'.$nowts['mon'].'" '.$style.'><span class="vcm-oversight-tablemonth">'.$months_labels[$nowts['mon']-1].'</span><span class="vcm-oversight-tablemday">'.$nowts['mday'].'</span><span class="vcm-oversight-tablewday">'.$days_labels[$nowts['wday']].'</span></td>';
	$next=$nowts['mday'] + 1;
	//$dayts=mktime(0, 0, 0, ($nowts['mon'] < 10 ? "0".$nowts['mon'] : $nowts['mon']), ($next < 10 ? "0".$next : $next), $nowts['year']);
	$dayts=mktime(0, 0, 0, $nowts['mon'], $next, $nowts['year']);
	$nowts=getdate($dayts);
    $cell_count++;
}
?>
        </tr>
<?php
foreach($this->rows as $room) {
	$nowts=getdate($this->tsstart);
	$mon=$nowts['mon'];
	echo '<tr class="vcmoverviewtablerow" id="vcm-acmp-row-'.$room['id'].'">';
	echo '<td class="roomname"><span class="vcm-oversight-roomunits">'.$room['units'].'</span> <span class="vcm-oversight-roomname">'.$room['name'].'</span></td>';
    
    $room_max_units[$room['id']] = $room['units'];
    
    $cell_count = 0;
	while( $cell_count < $MAX_DAYS ) {
	    
		$dclass="notbusy";
		$dalt="";
		$bid="";
		$totfound=0;
		if(@is_array($this->arrbusy[$room['id']])) {
			foreach($this->arrbusy[$room['id']] as $b){
				$tmpone=getdate($b['checkin']);
				$rit=($tmpone['mon'] < 10 ? "0".$tmpone['mon'] : $tmpone['mon'])."/".($tmpone['mday'] < 10 ? "0".$tmpone['mday'] : $tmpone['mday'])."/".$tmpone['year'];
				$ritts=strtotime($rit);
				$tmptwo=getdate($b['checkout']);
				$con=($tmptwo['mon'] < 10 ? "0".$tmptwo['mon'] : $tmptwo['mon'])."/".($tmptwo['mday'] < 10 ? "0".$tmptwo['mday'] : $tmptwo['mday'])."/".$tmptwo['year'];
				$conts=strtotime($con);
				//if ($nowts[0]>=$ritts && $nowts[0]<=$conts) {
				if ($nowts[0]>=$ritts && $nowts[0]<$conts) {
					$dclass="busy";
					$bid=$b['idorder'];
					if ($nowts[0]==$ritts) {
						$dalt=JText::_('VBPICKUPAT')." ".date('H:i', $b['checkin']);
					}elseif ($nowts[0]==$conts) {
						$dalt=JText::_('VBRELEASEAT')." ".date('H:i', $b['checkout']);
					}
					$totfound++;
				}
			}
		}
		$useday=($nowts['mday'] < 10 ? "0".$nowts['mday'] : $nowts['mday']);
        
        $style = '';
        if( $cell_count >= $MAX_TO_DISPLAY ) {
            $style = 'style="display: none;"';
        }

        $id_block = "cell-".$nowts['mday'].'-'.$nowts['mon']."-".$nowts['year']."-".$room['id'];
        $dclass .= ' day-block';
        
        if( $totfound > 0 && $totfound < $room['units'] ) {
            //$dlnk="<a href=\"index.php?option=com_vikbooking&task=choosebusy&vcm=1&idroom=".$room['id']."&ts=".$nowts[0]."\" style=\"color: #ffffff;\">".($room['units']-$totfound)."</a>";
            $dlnk = $room['units']-$totfound;
            $cal="<td align=\"center\" $style class=\"".$dclass." cell-".$nowts['mday'].'-'.$nowts['mon']."\" id=\"".$id_block."\" data-vbounits=\"".$dlnk."\" data-acmpdate=\"".date('Y-m-d', $nowts[0])."\">".$dlnk."</td>\n";
        } else if( $totfound >= $room['units'] ) {
            $dlnk = 0;
            $dclass = 'full day-block';
            $cal="<td align=\"center\" $style class=\"".$dclass." cell-".$nowts['mday'].'-'.$nowts['mon']."\" id=\"".$id_block."\" data-vbounits=\"".$dlnk."\" data-acmpdate=\"".date('Y-m-d', $nowts[0])."\">".$dlnk."</td>\n";
        } else {
            $dlnk = $room['units'];
            $cal="<td align=\"center\" $style class=\"".$dclass." cell-".$nowts['mday'].'-'.$nowts['mon']."\" id=\"".$id_block."\" data-vbounits=\"".$dlnk."\" data-acmpdate=\"".date('Y-m-d', $nowts[0])."\">".$dlnk."</td>\n";
        }
        
		echo $cal;
		$next=$nowts['mday'] + 1;
		$dayts=mktime(0, 0, 0, ($nowts['mon'] < 10 ? "0".$nowts['mon'] : $nowts['mon']), ($next < 10 ? "0".$next : $next), $nowts['year']);
		$nowts=getdate($dayts);
        
        $cell_count++;
	}
	echo '</tr>';
}
?>
    </table>
</div>

<form action="index.php?option=com_vikchannelmanager" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="task" value="oversight" />
<?php echo '<br/>'.$this->navbut; ?>
</form>

<div id="dialog-confirm" title="<?php echo JText::_('VCMOSDIALOGTITLE');?>" style="display: none;">
    <!--<span class="ui-icon ui-icon-locked" style="float: left; margin: 0 7px 20px 0;"></span>-->
    <div class="vcmos-dialog-line">
        <span class="vcmos-dialog-info"><?php echo JText::_('VCMOSFROMDATE'); ?></span>
        <span class="vcmos-dialog-value" id="vcmos-from"></span>
    </div>
    <div class="vcmos-dialog-line">
        <span class="vcmos-dialog-info"><?php echo JText::_('VCMOSTODATE'); ?></span>
        <span class="vcmos-dialog-value" id="vcmos-to"></span>
    </div>
    
    <br clear="all">
    
    <div class="vcmos-dialog-line">
        <span class="vcmos-dialog-param-info"><?php echo JText::_('VCMOSCLOSEUNITS'); ?></span>
        <span class="vcmos-dialog-param-check">
            <label for="vcmos-close-all"><?php echo JText::_('VCMOSCLOSEALL'); ?></label>
            <input type="checkbox" id="vcmos-close-all" onChange="closeAllValueChanged();" value="1"/>
        </span>
        <span class="vcmos-dialog-param-value">
            <input type="number" value="0" min="0" max="9999" id="vcmos-close-units" style="width: 100px !important;" onChange="closeUnitsValueChanged();"/>
            &nbsp;<?php echo JText::_('VCMOSUNITSLABEL'); ?>
        </span>
    </div>
    
    <div class="vcmos-dialog-line">
        <span class="vcmos-dialog-param-info"><?php echo JText::_('VCMOSOPENUNITS'); ?></span>
        <span class="vcmos-dialog-param-check">
            <label for="vcmos-open-all"><?php echo JText::_('VCMOSOPENALL'); ?></label>
            <input type="checkbox" id="vcmos-open-all" onChange="openAllValueChanged();" value="1"/>
        </span>
        <span class="vcmos-dialog-param-value">
            <input type="number" value="0" min="0" max="9999" id="vcmos-open-units" style="width: 100px !important;" onChange="openUnitsValueChanged();"/>
            &nbsp;<?php echo JText::_('VCMOSUNITSLABEL'); ?>
        </span>
    </div>
</div>

<script type="text/javascript">

    var ROOM_MAX_UNITS = <?php echo json_encode($room_max_units); ?>;

    var TOT_CHANGES = 0;
    
    function vcmAcmpCheckData(date, obj) {
        <?php
        if(!empty($this->acmp_last_request)) :
        ?>
        var vcm_skip_date = jQuery.datepicker.formatDate(new Date().format, new Date("<?php echo $this->acmp_last_request; ?>"));
        //console.log(date+' - '+vcm_skip_date);
        if(date == vcm_skip_date) {
            jQuery('#loadacmp').val("1");
            document.vcmoverview.submit();
            return true;
        }
        if(confirm("<?php echo addslashes(JText::sprintf('VCMCONFIRMACMPSESSVAL', $this->acmp_last_request)); ?>")) {
            var acmp_last_date = new Date("<?php echo $this->acmp_last_request; ?>");
            jQuery('#vcmdatepicker:input').datepicker( "setDate", acmp_last_date );
            jQuery('#loadacmp').val("1");
            document.vcmoverview.submit();
        }else {
            document.vcmoverview.submit();
        }
        <?php
        else:
        ?>
        document.vcmoverview.submit();
        <?php
        endif;
        ?>
    }

    jQuery(document).ready(function(){
        
        jQuery('#vcmdatepicker:input').datepicker({
            dateFormat: new Date().format,
            onSelect: vcmAcmpCheckData
        });

    });
    
    function openDialog() {
        var format = new Date().format;
        
        jQuery('#vcmos-from').html(listener.first.toDate(format));
        jQuery('#vcmos-to').html(listener.last.toDate(format));
        jQuery('#vcmos-close-units').val('0');
        jQuery('#vcmos-close-units').prop('readonly', false);
        jQuery('#vcmos-open-units').val('0');
        jQuery('#vcmos-open-units').prop('readonly', false);
        jQuery('#vcmos-close-all').prop('checked', false);
        jQuery('#vcmos-open-all').prop('checked', false);
        
        jQuery( "#dialog-confirm" ).dialog({
            resizable: false,
            height:320,
            width:480,
            modal: true,
            close: function() {
                listener.clear();
                jQuery('.day-block').removeClass('block-picked-start block-picked-middle block-picked-end');
            },
            buttons: {
                "<?php echo JText::_('VCMOSDIALOGAPPLYBUTTON'); ?>": function() {
                    changeAvailability();
                    jQuery( this ).dialog( "close" );
                },
                "<?php echo JText::_('VCMOSDIALOGCANCBUTTON'); ?>": function() {
                    jQuery( this ).dialog( "close" );
                }
            }
        });
    }
    
    function closeUnitsValueChanged() {
        jQuery('#vcmos-open-units').prop('readonly', (jQuery('#vcmos-close-units').val() > 0 ? true : false) );
    }
    
    function openUnitsValueChanged() {
        jQuery('#vcmos-close-units').prop('readonly', (jQuery('#vcmos-open-units').val() > 0 ? true : false) );
    }
    
    function closeAllValueChanged() {
        var is = jQuery('#vcmos-close-all').is(':checked');
        jQuery('#vcmos-open-all').prop('checked', false);
        jQuery('#vcmos-close-units, #vcmos-open-units').prop('readonly', (is ? true : false));
        jQuery('#vcmos-close-units').val( ROOM_MAX_UNITS[listener.first.room] );
        jQuery('#vcmos-open-units').val(0);
    }
    
    function openAllValueChanged() {
        var is = jQuery('#vcmos-open-all').is(':checked');
        jQuery('#vcmos-close-all').prop('checked', false);
        jQuery('#vcmos-close-units, #vcmos-open-units').prop('readonly', (is ? true : false));
        jQuery('#vcmos-open-units').val( ROOM_MAX_UNITS[listener.first.room] );
        jQuery('#vcmos-close-units').val(0);
    }
    
    function changeAvailability() {
        var all_blocks = getAllBlocksBetween(listener.first, listener.last, true);
        if( all_blocks === false ) {
            return false;
        }
        
        var new_units = 0;
        var close_units = Math.max(0, parseInt(jQuery('#vcmos-close-units').val()) );
        var open_units = Math.max(0, parseInt(jQuery('#vcmos-open-units').val()) );
        if( close_units > 0 ) {
            new_units = close_units;
        } else {
            new_units -= open_units;
        }
        
        jQuery.each(all_blocks, function(k, v){
            var units = parseInt( v.html() );
            var res = Math.max( 0, Math.min( ROOM_MAX_UNITS[listener.first.room], (units-new_units) ) );
            v.html(res);
            
            v.removeClass('busy notbusy equal full');
            if( res == 0 ) {
                v.addClass('full');
            } else if( res == ROOM_MAX_UNITS[listener.first.room] ) {
                v.addClass('notbusy equal');
            } else {
                v.addClass('busy');
            }
            
            storeDay(v.attr('id'), res, v.attr('data-vbounits'));
        });
        
        increaseChanges();
        
    }
    
    function storeDay(id, units, vbounits) {
        var input = jQuery('#input-'+id);
        if( input.length > 0 ) {
            input.val(id+'-'+units+'-'+vbounits);
        } else {
            jQuery('#adminForm').append('<input type="hidden" name="cust_av[]" id="input-'+id+'" value="'+id+'-'+units+'-'+vbounits+'"/>');
        }
    }

    function increaseChanges() {
        TOT_CHANGES++;
        jQuery('#vcm-totchanges').text(TOT_CHANGES).parent('div').show();
    }
   
   /////////////////////////////////
    
    var _START_DAY_ = '<?php echo $start_day_id; ?>';
    var _END_DAY_ = '<?php echo $end_day_id; ?>';
    
    function prevDay() {
        if( canPrev(_START_DAY_) ) {
            jQuery('.'+_START_DAY_).prev().show();
            jQuery('.'+_END_DAY_).hide();
            
            if( canPrev(_START_DAY_) ) {
                var start = jQuery('.'+_START_DAY_).first();
                var end = jQuery('.'+_END_DAY_).first();
                
                _START_DAY_ = start.prev().prop('class').split(' ')[1];
                _END_DAY_ = end.prev().prop('class').split(' ')[1];
                
                return true;
            } 
        }
        
        return false;
    }
    
    function nextDay() {
        if( canNext(_END_DAY_) ) {
            jQuery('.'+_START_DAY_).hide();
            jQuery('.'+_END_DAY_).next().show();
            
            if( canNext(_END_DAY_) ) {
                var start = jQuery('.'+_START_DAY_).first();
                var end = jQuery('.'+_END_DAY_).first();
                
                _START_DAY_ = start.next().prop('class').split(' ')[1];
                _END_DAY_ = end.next().prop('class').split(' ')[1];
                
                return true;
            } 
        }
        
        return false;
    }
    
    function prevWeek() {
        var i = 0;
        while( i++ < 7 && prevDay() );
    }
    
    function nextWeek() {
        var i = 0;
        while( i++ < 7 && nextDay() );
    }
    
    function canPrev(start) {
        return ( jQuery('.'+start).first().prev().prop('class').split(' ').length > 1 );
    }
    
    function canNext(end) {
        return ( jQuery('.'+end).first().next().length > 0 );
    }
    
    /////////////////////////////////
    
    var listener = null;
    
    jQuery(document).ready(function(){
        
        listener = new CalendarListener();
        
        jQuery('.day-block').click(function(){
            pickBlock( jQuery(this).attr('id') );
        });
        
        jQuery('.day-block').hover(
            function() {
                if( listener.isFirstPicked() && !listener.isLastPicked() ) {
                    var struct = initBlockStructure(jQuery(this).attr('id'));
                    var all_blocks = getAllBlocksBetween(listener.first, struct, false);
                    if( all_blocks !== false ) {
                        jQuery.each(all_blocks, function(k, v){
                            if( !v.hasClass('block-picked-middle') ) {
                                v.addClass('block-picked-middle');
                            }
                        });
                        jQuery(this).addClass('block-picked-end');
                    }
                }
            },
            function() {
                if( !listener.isLastPicked() ) {
                    jQuery('.day-block').removeClass('block-picked-middle block-picked-end');
                }
            }
        );
        
        jQuery(document).keydown(function(e){
            if( e.keyCode == 27 ) {
                listener.clear();
                jQuery('.day-block').removeClass('block-picked-start block-picked-middle block-picked-end');
            }
        });
    });
    
    function pickBlock(id) {
        var struct = initBlockStructure(id);
        
        if( !listener.pickFirst(struct) ) {
            // first already picked
            if( ( listener.first.isBeforeThan(struct) || listener.first.isSameDay(struct) ) && listener.first.isSameRoom(struct) ) {
                // last > first : pick last
                if( listener.pickLast(struct) ) {
                    var all_blocks = getAllBlocksBetween(listener.first, listener.last, false);
                    if( all_blocks !== false ) {
                        jQuery.each(all_blocks, function(k, v){
                            if( !v.hasClass('block-picked-middle') ) {
                                v.addClass('block-picked-middle');
                            }
                        });
                        jQuery('#'+listener.last.id).addClass('block-picked-end');
                        openDialog();
                    }
                }
            } else {
                // last < first : clear selection
                listener.clear();
                jQuery('.day-block').removeClass('block-picked-start block-picked-middle block-picked-end');
            }
        } else {
            // first picked
            jQuery('#'+listener.first.id).addClass('block-picked-start');
        }
    }
    
    function getAllBlocksBetween(start, end, outers_included) {
        if( !start.isSameRoom(end) ) {
            return false;
        }
        
        if( start.isAfterThan(end) ) {
            return false;
        }
        
        var queue = new Array();
        
        if( outers_included ) {
            queue.push(jQuery('#'+start.id));
        }
        
        if( start.isSameDay(end) ) {
            return queue;
        }
        
        var node = jQuery('#'+end.id).prev();
        var start_id = jQuery('#'+start.id).attr('id');
        while( node.length > 0 && node.attr('id') != start_id ) {
            queue.push(node);
            node = node.prev();
        }
        
        if( outers_included ) {
            queue.push(jQuery('#'+end.id));
        }
        
        return queue;
    }
    
    function initBlockStructure(id) {
        var s = id.split("-");
        if( s.length != 5 ) {
            return {};
        }
        
        return {
            "day":parseInt(s[1]),
            "month":parseInt(s[2]),
            "year":parseInt(s[3]),
            "room":s[4],
            "id":id,
            "isSameDay" : function(block) {
                return ( this.month == block.month && this.day == block.day && this.year == block.year );
            },
            "isBeforeThan" : function(block) {
                return ( 
                    ( this.year < block.year ) || 
                    ( this.year == block.year && this.month < block.month ) || 
                    ( this.year == block.year &&  this.month == block.month && this.day < block.day ) );
            },
            "isAfterThan" : function(block) {
                return ( 
                    ( this.year > block.year ) || 
                    ( this.year == block.year && this.month > block.month ) || 
                    ( this.year == block.year && this.month == block.month && this.day > block.day ) );
            },
            "isSameRoom" : function(block) {
                return ( this.room == block.room );
            },
            "toDate" : function(format) {
                return format.replace(
                    'dd', ( this.day < 10 ? '0' : '' )+this.day
                ).replace(
                    'mm', ( this.month < 10 ? '0' : '' )+this.month
                ).replace(
                    'yy', this.year
                );
            }
        };
    }
    
    function CalendarListener() {
        this.first = null;
        this.last = null;
    }
    
    CalendarListener.prototype.pickFirst = function(struct) {
        if( !this.isFirstPicked() ) {
            this.first = struct;
            return true;
        }
        return false;
    }
    
    CalendarListener.prototype.pickLast = function(struct) {
        if( !this.isLastPicked() && this.isFirstPicked() ) {
            this.last = struct;
            return true;
        }
        return false;
    }
    
    CalendarListener.prototype.clear = function() {
        this.first = null;
        this.last = null;
    }
    
    CalendarListener.prototype.isFirstPicked = function() {
        return this.first != null;
    }
    
    CalendarListener.prototype.isLastPicked = function() {
        return this.last != null;
    }
    
</script>
