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
$channel = VikChannelManager::getActiveModule(true);
$configok = true;

$validate = array('apikey');
foreach( $validate as $v ) {
	if( empty($config[$v]) ) {
		$configok = false;
	?>
	<p class="vcmfatal"><?php echo JText::_('VCMBASICSETTINGSNOTREADY'); ?></p>
	<?php break; }
}

if( $configok ) { ?>
	
	<script language="JavaScript" type="text/javascript">
	if(typeof window.JSON=='undefined') {
		window.JSON = {
			parseJSobject : function (object) {
				var temp = '{';
				var s = 0;
				for(i in object) {
					if(s) { temp+=','; }
					temp += '"'+i+'":';
					if(typeof object[i] == 'object') {
						temp += this.parseJSobject(object[i]);
					} else {
						temp += '"'+object[i]+'"';
					}
					s++;
				}
				temp += '}';
				return temp;
			},
			stringify : function(data){
				return this.parseJSobject(data);
			}
		};
	}
	jQuery(document).ready(function() {
		jQuery("#vcmstartsynch").click(function(){
			jQuery(".vcmsynchspan").removeClass("vcmsynchspansuccess");
			jQuery(".vcmsynchspan").removeClass("vcmsynchspanerror").addClass("vcmsynchspanloading");
			jQuery("#vcmroomsynchresponsebox").html("");
			var jqxhr = jQuery.ajax({
				type: "POST",
				url: "index.php",
				data: { option: "com_vikchannelmanager", task: "exec_par_products", tmpl: "component" }
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
		});
	});
	jQuery(document).on("click", ".vcmtableleftspkeyopen", function(){ 
		jQuery(this).addClass("vcmtableleftspkeyclose").removeClass("vcmtableleftspkeyopen");
		jQuery(this).next("div").show();
	});
	jQuery(document).on("click", ".vcmtableleftspkeyclose", function(){ 
		jQuery(this).addClass("vcmtableleftspkeyopen").removeClass("vcmtableleftspkeyclose");
		jQuery(this).next("div").hide();
	});
	function vcmRemoveLink(idrota) {
		jQuery("#vcmrowrelota"+idrota).remove();
		var vcmonlyidrota = idrota.split("_");
		jQuery("#vcmotarselector"+vcmonlyidrota[0]).removeClass("vcmselectedotaroom");
		jQuery("#vcmotarselector"+vcmonlyidrota[0]).text("<?php echo addslashes(JText::_('VCMSELECTOTAROOMTOLINK')); ?>");
		var vcmoldotaidr = jQuery("#vcmotahelper").val();
		if(vcmoldotaidr == vcmonlyidrota[0]) {
			jQuery("#vcmotahelper").val("");
			jQuery(".vcmselectvbroom").fadeOut();
		}
		jQuery("#inputotar"+idrota).remove();
		jQuery("#inputotarname"+idrota).remove();
		jQuery("#inputvbr"+idrota).remove();
		if(jQuery("#pricingotar"+idrota) != 'undefined') {
			jQuery("#pricingotar"+idrota).remove();
		}
	}
	function vcmStartLinking (idrota, namerota) {
		var vcmid = idrota;
		if(jQuery("#vcmrowrelota"+idrota).length > 0) {
			if(jQuery("#inputvbr"+idrota).length == 0) {
				jQuery("#vcmrowrelota"+idrota).remove();
				jQuery("#inputotar"+idrota).remove();
				jQuery("#inputotarname"+idrota).remove();
				jQuery("#inputvbr"+idrota).remove();
			}else {
				var date = new Date;
				vcmid += "_"+date.getMinutes()+"_"+date.getSeconds();
			}
		}
		var vcmoldotaidr = jQuery("#vcmotahelper").val();
		if(vcmoldotaidr != '') {
			var vcmoldattr = jQuery("#vcmotahelper").attr("rel");
			jQuery("#inputotar"+vcmoldattr).remove();
			jQuery("#inputotarname"+vcmoldattr).remove();
			jQuery("#inputvbr"+vcmoldattr).remove();
			jQuery("#vcmrowrelota"+vcmoldattr).remove();
		}
		jQuery("#vcmotahelper").val(idrota);
		jQuery("#vcmotahelper").attr("rel", vcmid);
		jQuery("#vcmroomsynchhelperbox").append("<input type='hidden' name='otaroomsids[]' value='"+idrota+"' id='inputotar"+vcmid+"' /><input type='hidden' name='otaroomsnames[]' value='"+namerota+"' id='inputotarname"+vcmid+"' />");

		for(var rkey in room_plans) {
			if(room_plans.hasOwnProperty(rkey) && rkey == 'r'+idrota) {
				jQuery("#vcmroomsynchhelperbox").append("<input type='hidden' name='otapricing[]' value='"+JSON.stringify(room_plans[rkey])+"' id='pricingotar"+vcmid+"' />");
				break;
			}
		}
		
		jQuery(".vcmtablemiddle").append("<tr class='vcmrowrelota' id='vcmrowrelota"+vcmid+"'><td>"+idrota+"</td><td>"+namerota+"</td><td><img class='vcmimgremovelink' src='<?php echo addslashes(JURI::root().'administrator/components/com_vikchannelmanager/assets/css/images/remove.png'); ?>' onclick='vcmRemoveLink(\""+vcmid+"\");'/></td></tr>");
		jQuery(".vcmselectvbroom").fadeIn();
		jQuery(".vcmselectotaroom").removeClass("vcmselectedotaroom");
		jQuery(".vcmselectotaroom").text("<?php echo addslashes(JText::_('VCMSELECTOTAROOMTOLINK')); ?>");
		jQuery("#vcmotarselector"+idrota).text("<?php echo addslashes(JText::_('VCMSELECTEDOTAROOMTOLINK')); ?>");
		jQuery("#vcmotarselector"+idrota).addClass("vcmselectedotaroom");
		jQuery("#vcmrowrelota"+vcmid).effect("highlight", {}, 2000);
	}
	function vcmEndLinking (idrvb, namervb) {
		var vcmoldotaidr = jQuery("#vcmotahelper").val();
		var vcmoldattr = jQuery("#vcmotahelper").attr("rel");
		var vcmonlyidrota = vcmoldotaidr.split("_");
		if(vcmoldotaidr != '') {
			jQuery("#vcmrowrelota"+vcmoldattr).append("<td>"+idrvb+"</td><td>"+namervb+"</td>");
			jQuery("#vcmroomsynchhelperbox").append("<input type='hidden' name='vbroomsids[]' value='"+idrvb+"' id='inputvbr"+vcmoldattr+"' />");
			jQuery("#vcmotarselector"+vcmonlyidrota[0]).removeClass("vcmselectedotaroom");
			jQuery("#vcmotarselector"+vcmonlyidrota[0]).text("<?php echo addslashes(JText::_('VCMSELECTOTAROOMTOLINK')); ?>");
			jQuery("#vcmotahelper").val("");
			jQuery("#vcmotahelper").attr("rel", "");
			jQuery("#vcmrowrelota"+vcmoldattr).effect("highlight", {}, 2000);
		}
		jQuery(".vcmselectvbroom").fadeOut();
	}
	</script>
	<span class="vcmsynchspan">
		<a class="vcmsyncha" href="javascript: void(0);" id="vcmstartsynch"><?php echo JText::sprintf('VCMSTARTSYNCHROOMS', ucwords($channel['name'])); ?></a>
	</span>
	<br clear="all"/><br clear="all"/>
	
<?php } ?>

<input type="hidden" id="vcmotahelper" value=""/>

<form name="adminForm" action="index.php" method="post" id="adminForm">
	
	<div id="vcmroomsynchhelperbox"></div>
	
	<div id="vcmroomsynchresponsebox"></div>
	
	<input type="hidden" name="task" value="">
	<input type="hidden" name="option" value="com_vikchannelmanager">
</form>

