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
$notifications = $this->notifications;
$lim0 = $this->lim0;
$navbut = $this->navbut;

$active_channels = $this->activeChannels;

// load tooltip behavior
if( $config['dateformat'] == '%Y/%m/%d' ) {
	$df = 'Y-m-d H:i';
} else if( $df = '%d/%m/%Y') {
	$df = 'd-m-Y H:i';
} else {
	$df = 'm-d-Y H:i';
}

?>

<?php if( !$config['block_program'] ) { ?>
	
<?php if( $config['to_update'] ) { ?>
	<!-- SHOW NOTIFICATION [UPDATER AVAILABLE] -->
<?php } ?>
		
<div class="vcmdashdivleft">

	<h3 class="vcmdashdivlefthead"><i class="vboicn-power-cord"></i><?php echo JText::_('VCMDASHSTATUS'); ?></h3>
	
	<?php if (intval($config['to_update']) == 1) { ?>
        <p class="vcmupdater vcmnewupavaildash">
            <span><?php echo JText::_('VCMNEWOPTIONALUPDATEAV'); ?></span>
            <span class="vcmupdatedashbutton">
                <a href="index.php?option=com_vikchannelmanager&task=update_program" class="vcmupdatenowlink"><?php echo JText::_('VCMUPDATENOWBTN'); ?></a>
            </span>
       </p>
    <?php } ?>
	
	<?php if( $this->showSync ) { ?>
    	<?php if (intval($config['vikbookingsynch']) == 1) { ?>
    		<p class="vcmstatuson"><?php echo JText::_('VCMDASHVBSYNCHON'); ?></p>
    	<?php } else { ?>
    		<p class="vcmstatusoff"><?php echo JText::_('VCMDASHVBSYNCHOFF'); ?></p>
    	<?php }
    	
    	if( array_key_exists('expedialastsync', $config) ) { ?>
    		<p class="vcmok"><span><?php echo JText::_('VCMDASHLASTSYNCEXPEDIA'); ?>:</span> <?php echo date($df, $config['expedialastsync']); ?> &nbsp; <span>-</span> &nbsp; <span><?php echo JText::_('VCMDASHLASTNUMROOMSYNCEXPEDIA'); ?>:</span> <?php echo $config['expedialastnumroomsfetched']; ?></p>
    	<?php } ?>
    <?php } ?>
	
	<?php if( empty($config['apikey']) ) { ?>
		<p class="vcmfatal"><span><?php echo JText::_('VCMDASHEMPTYAPI'); ?></span></p>
	<?php } else { ?>
		<p class="vcmok"><span><?php echo JText::_('VCMDASHYOURAPI'); ?>:</span> <?php echo substr($config['apikey'], 0, (strlen($config['apikey']) - 4)).' &bull; &bull; &bull; &bull;'; ?></p>
	<?php }

	if( empty($config['emailadmin']) ) { ?>
		<p class="vcmwarning"><span><?php echo JText::_('VCMDASHEMPTYEMAILADMIN'); ?></span></p>
	<?php } else { ?>
		<p class="vcmok"><span><?php echo JText::_('VCMDASHYOUREMAILADMIN'); ?>:</span> <?php echo $config['emailadmin']; ?></p>
	<?php } ?>
	
	<div class="<?php echo (count($active_channels) > 0 ? 'vcmok' : 'vcmfatal'); ?>">
		<div class="vcmactivechannelslabel"><?php echo JText::_('VCMDASHYOURACTIVECHANNELS'); ?></div>
		<div class="vcmactivechannelscont">
			<?php foreach( VikChannelManagerConfig::$AVAILABLE_CHANNELS as $uniq => $name ) {
				?><div class="vcmchlogo<?php echo $name; ?> <?php echo (@in_array($uniq, $active_channels) ? '' : 'unactive-channel' ); ?>"></div><?php
			} ?>
		</div>
	</div>
	
	<?php //Expiring API Date Request
	if( !empty($config['apikey']) ) { ?>
		
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#vcmstartsynch").click(function(){
				jQuery(".vcmsynchspan").removeClass("vcmsynchspansuccess");
				jQuery(".vcmsynchspan").removeClass("vcmsynchspanerror").addClass("vcmsynchspanloading");
				jQuery("#vcmstartsynch").text('<?php echo addslashes(JText::_('VCMSCHECKAPIEXPDATELOAD')); ?>');
				jQuery("#vcmexprs").html("");
				var jqxhr = jQuery.ajax({
					type: "POST",
					url: "index.php",
					data: { option: "com_vikchannelmanager", task: "exec_exp", tmpl: "component" }
				}).done(function(res) { 
					jQuery("#vcmstartsynch").text('<?php echo addslashes(JText::_('VCMSCHECKAPIEXPDATE')); ?>');
					jQuery(".vcmsynchspan").removeClass("vcmsynchspanloading");
					if(res.substr(0, 9) == 'e4j.error') {
						jQuery(".vcmsynchspan").addClass("vcmsynchspanerror");
						jQuery("#vcmexprs").html("<pre class='vcmpreerror'>" + res.replace("e4j.error.", "") + "</pre>");
					}else {
						jQuery(".vcmsynchspan").addClass("vcmsynchspansuccess");
						jQuery("#vcmexprs").html(res);
					}
				}).fail(function() { 
					jQuery("#vcmstartsynch").text('<?php echo addslashes(JText::_('VCMSCHECKAPIEXPDATE')); ?>');
					jQuery(".vcmsynchspan").removeClass("vcmsynchspanloading").addClass("vcmsynchspanerror");
					alert("Error Performing Ajax Request"); 
				});
			});
			
			jQuery("#vcmstartchannel").click(function(){
				jQuery(".vcmchannelspan").removeClass("vcmchannelspansuccess");
				jQuery(".vcmchannelspan").removeClass("vcmchannelspanerror").addClass("vcmchannelspanloading");
				jQuery("#vcmstartchannel").text('<?php echo addslashes(JText::_('VCMSCHECKAPIEXPDATELOAD')); ?>');
				jQuery("#vcmchannelrs").html("");
				var jqxhr = jQuery.ajax({
					type: "POST",
					url: "index.php",
					data: { option: "com_vikchannelmanager", task: "exec_cha", tmpl: "component" }
				}).done(function(res) { 
					jQuery("#vcmstartchannel").text('<?php echo addslashes(JText::_('VCMGETCHANNELS')); ?>');
					jQuery(".vcmchannelspan").removeClass("vcmchannelspanloading");
					if(res.substr(0, 9) == 'e4j.error') {
						jQuery(".vcmchannelspan").addClass("vcmchannelspanerror");
						jQuery("#vcmchars").html("<pre class='vcmpreerror'>" + res.replace("e4j.error.", "") + "</pre>");
					}else {
						jQuery(".vcmchannelspan").addClass("vcmchannelspansuccess");
						jQuery("#vcmchars").html(res);
						setTimeout("document.location.href='index.php?option=com_vikchannelmanager'", 5000);
					}
					
				}).fail(function() { 
					jQuery("#vcmstartchannel").text('<?php echo addslashes(JText::_('VCMGETCHANNELS')); ?>');
					jQuery(".vcmchannelspan").removeClass("vcmchannelspanloading").addClass("vcmchannelspanerror");
					alert("Error Performing Ajax Request"); 
				});
			});
		});
		</script>
		
		<p class="vcmok">
			<span class="vcmsynchspan"><a class="vcmsyncha" href="javascript: void(0);" id="vcmstartsynch"><?php echo JText::_('VCMSCHECKAPIEXPDATE'); ?></a></span>
			<span class="vcmexprsdash" id="vcmexprs"></span>
		</p>
		
		<p class="vcmok">
			<span class="vcmchannelspan"><a class="vcmchannel" href="javascript: void(0);" id="vcmstartchannel"><?php echo JText::_('VCMGETCHANNELS'); ?></a></span>
			<span class="vcmchannelrsdash" id="vcmchars"></span>
		</p>
	<?php } ?>

</div>

<script type="text/javascript">

function vcmCheckAll(bx) {
	var cbs = document.getElementsByTagName('input');
	for(var i=0; i < cbs.length; i++) {
		if(cbs[i].type == 'checkbox') {
			cbs[i].checked = bx.checked;
		}
	}
}

</script>

<div class="vcmdashdivright">
	<h3 class="vcmdashdivrighthead"><i class="vboicn-cloud"></i><?php echo JText::_('VCMDASHNOTIFICATIONS'); ?></h3>
	<?php if(count($notifications) > 0) {
		JHTML::_('behavior.modal');
	?>
	
	<form name="adminForm" action="index.php" method="post" id="adminForm">
		<table class="vcmtablenots">
			<tr>
				<td>&nbsp;</td>
				<td><strong><?php echo JText::_('VCMDASHNOTSFROM'); ?></strong></td>
				<td><strong><?php echo JText::_('VCMDASHNOTSDATE'); ?></strong></td>
				<td><strong><?php echo JText::_('VCMDASHNOTSTEXT'); ?></strong></td>
				<td class="vcmnotstdright"><input type="submit" name="rmnotifications" value="<?php echo JText::_('VCMDASHNOTSRMSELECTED'); ?>" class="vcmremovenots"/></td>
				<td class="vcmnotstdright"><input type="checkbox" name="vcmnotsselall" value="1" onclick="javascript: vcmCheckAll(this);"/></td>
			</tr>
		<?php
		
		$kr = 0;
		foreach($notifications as $notify) {
			$txt_parts = explode("\n", $notify['cont']);
			$render_mess = VikChannelManager::getErrorFromMap(trim($txt_parts[0]), true);
			unset($txt_parts[0]);
			$notify['cont'] = $render_mess.(count($txt_parts) > 0 ? "\n".implode("\n", $txt_parts) : '');
			switch (intval($notify['type'])) {
				case 1:
					$imgtypenot = 'enabled_new.png';
					$imgtypenottitle = 'Success';
					break;
				case 2:
					$imgtypenot = 'warning_new.png';
					$imgtypenottitle = 'Success - Warning';
					break;
				default:
					$imgtypenot = 'error_new.png';
					$imgtypenottitle = 'Error';
					break;
			}
			$cont = explode("\n", $notify['cont']);
			$cont[0] = str_replace(":", " ", $cont[0]);
			$notify['children'] = !array_key_exists('children', $notify) ? array() : $notify['children'];
			?>
			<tr class="vcmnotsrow<?php echo $kr; ?><?php echo $notify['read'] == 0 ? ' vcm-notif-toberead' : ''; ?>">
				<td><img src="<?php echo JURI::root(); ?>administrator/components/com_vikchannelmanager/assets/css/images/<?php echo $imgtypenot; ?>" title="<?php echo $imgtypenottitle; ?>"/></td>
				<td><?php if (count($notify['children']) > 0) : ?><a href="javascript: void(0);" class="vcm-dash-openchildn" id="parentn<?php echo $notify['id']; ?>"><?php echo $notify['from']; ?></a><?php else: ?><?php echo $notify['from']; ?><?php endif; ?></td>
				<td><a href="index.php?option=com_vikchannelmanager&amp;task=notification&amp;cid[]=<?php echo $notify['id']; ?>&amp;tmpl=component" rel="{handler: 'iframe', size: {x: 750, y: 600}}" class="modal" target="_blank"><?php echo date($df, $notify['ts']); ?></a></td>
				<td colspan="2"><?php echo $cont[0].(!empty($notify['idordervb']) ? ' (VB.ID '.$notify['idordervb'].')' : ''); ?></td>
				<td class="vcmnotstdright"><input type="checkbox" name="notsids[]" value="<?php echo $notify['id']; ?>"/></td>
			</tr>
			<?php
			if (count($notify['children']) > 0) {
				foreach ($notify['children'] as $child) {
					$txt_parts = explode("\n", $child['cont']);
					$render_mess = VikChannelManager::getErrorFromMap(trim($txt_parts[0]), true);
					unset($txt_parts[0]);
					$child['cont'] = $render_mess.(count($txt_parts) > 0 ? "\n".implode("\n", $txt_parts) : '');
					switch (intval($child['type'])) {
						case 1:
							$imgtypenot = 'enabled_new.png';
							$imgtypenottitle = 'Success';
							break;
						case 2:
							$imgtypenot = 'warning_new.png';
							$imgtypenottitle = 'Success - Warning';
							break;
						default:
							$imgtypenot = 'error_new.png';
							$imgtypenottitle = 'Error';
							break;
					}
					$channel_info = VikChannelManager::getChannel($child['channel']);
					$cont = explode("\n", $child['cont']);
					$cont[0] = str_replace(":", " ", $cont[0]);
					//parse {hotelid n} for Multiple Accounts
					$account_id = '';
					if(strpos($child['cont'], '{hotelid') !== false) {
						$account_id = VikChannelManager::parseNotificationHotelId($child['cont'], $child['channel'], true);
					}
				?>
			<tr class="vcmnotsrow<?php echo $kr; ?> vcm-dash-hidden vcm-childrow<?php echo $notify['id']; ?>">
				<td>&nbsp;</td>
				<td colspan="2"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikchannelmanager/assets/css/images/<?php echo $imgtypenot; ?>" title="<?php echo $imgtypenottitle; ?>"/><span class="vcm-childnotif-otaname<?php echo (!empty($account_id) ? ' hasTooltip' : ''); ?>"<?php echo (!empty($account_id) ? ' title="'.$account_id.'"' : ''); ?>><?php echo count($channel_info) > 0 ? ucwords($channel_info['name']) : ''; ?></span></td>
				<td colspan="3"><?php echo $cont[0]; ?></td>
			</tr>
				<?php
				}
			}
			$kr = 1 - $kr;
		}
		?>
		</table>
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery(".vcm-dash-openchildn").click(function(){
				var parent_name = jQuery(this).attr("id");
				var parent_id = parent_name.split("parentn");
				if(jQuery(".vcm-childrow"+parent_id[1])) {
					jQuery(".vcm-childrow"+parent_id[1]).toggleClass("vcm-dash-hidden");
					jQuery(this).toggleClass("vcm-dash-closechildn");
				}
			});
			jQuery(".modal").click(function(){
				jQuery(this).parent("td").parent("tr").removeClass("vcm-notif-toberead");
			});
		});
		</script>
		<input type="hidden" name="option" value="com_vikchannelmanager"/>
		<?php echo $navbut; ?>
	</form>
	<?php
		VikChannelManager::readNotifications($notifications);
	}
	?>

</div>

<?php } ?>

