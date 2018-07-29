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
$channelrooms = $this->channelrooms;

$rooms_rate_plans = array();

if( count($channelrooms['Hotel']) > 0 ) { ?>
	<div class="vcmdivhotel">
		<h3><?php echo JText::_('VCMHOTELINFORETURNED'); ?></h3>
		<?php
		$prop_name_found = false;
		foreach($channelrooms['Hotel'] as $hk => $hv) {
			//Property Name
			if($prop_name_found === false && stripos($hk, 'name') !== false) {
				$prop_name_found = true;
				?>
			<input type="hidden" name="prop_name" value="<?php echo $hv; ?>" />
				<?php
			}
			//
			end($channelrooms['Hotel']);
			?>
			<span><?php echo strtoupper($hk); ?>:</span> <?php echo $hv; ?><?php echo (key($channelrooms['Hotel']) != $hk ? '&nbsp; - &nbsp;' : ''); ?>
		<?php } ?>
	</div>
<?php } ?>

<div class="vcmdivleft">
	<h3><?php echo JText::_('VCMROOMSRETURNEDBYOTA'); ?></h3>
	<table class="vcmtableleft">
		<?php
		$tototarooms = 0;
		foreach($channelrooms['Rooms'] as $rk => $room) {
			if (!empty($room['id']) && !empty($room['name'])) {
				$tototarooms++;
			}
			$rate_plan = array();
			if (array_key_exists('RatePlan', $room) && count($room['RatePlan']) > 0) {
				foreach ($room['RatePlan'] as $plan) {
					$rate_plan[$plan['id']] = $plan;
				}
				$rooms_rate_plans[$room['id']]['RatePlan'] = $rate_plan;
				if (array_key_exists('RoomInfo', $room) && count($room['RoomInfo']) > 0) {
					$rooms_rate_plans[$room['id']]['RoomInfo'] = $room['RoomInfo'];
				}
			}
			?>
			<tr>
				<td class="vcmtableleftsecondtd">
				<?php
				if(count($room) > 0) {
					foreach($room as $keyr => $valr) {
						if(!is_array($valr)) { ?>
							<div class="vcmtableleftdivroomfield"><span class="vcmtableleftspkey"><?php echo ucwords($keyr); ?>:</span> <span class="vcmtableleftspval"><?php echo $valr; ?></span></div>
							<?php
						} else { ?>
							<div class="vcmtableleftdivroomfield"><span class="vcmtableleftspkeyopen"><?php echo $keyr; ?></span> 
								<div class="vcmtableleftsubdiv">
							<?php
							$rp_loop = 0;
							foreach($valr as $subrk => $subrv) {
								$rp_loop++;
								if (is_array($subrv)) {
									foreach($subrv as $srk => $srv) {
										?>
										<div class="vcmtableleftdivroomfield"><span class="vcmtableleftspkey"><?php echo $srk; ?>:</span> <span class="vcmtableleftspval"><?php echo $srv; ?></span></div>
										<?php
									}
									if($rp_loop < count($valr)) {
										?>
										<div class="vcmsubdivseparator"></div>
										<?php
									}
								}else { ?>
									<div class="vcmtableleftdivroomfield"><span class="vcmtableleftspkey"><?php echo $subrk; ?>:</span> <span class="vcmtableleftspval"><?php echo $subrv; ?></span></div>
									<?php
								}
							}
							?>
							</div>
						</div>
						<?php
						}
					}
				} ?>
			</td>
			<td class="vcmtableleftfirsttd"><span class="vcmselectotaroom" id="vcmotarselector<?php echo $room['id']; ?>" onclick="vcmStartLinking('<?php echo $room['id']; ?>', '<?php echo addslashes($room['name']); ?>');"><?php echo JText::_('VCMSELECTOTAROOMTOLINK'); ?></span></td>
		</tr>
		<?php } ?>
	</table>
</div>
	
<div class="vcmdivmiddle">
	<h3><?php echo JText::_('VCMROOMSRELATIONS'); ?></h3>
	<table class="vcmtablemiddle">
		<tr>
			<td colspan="2" style="width: 45%; text-align: center; font-weight: bold; border-bottom: 1px solid #dddddd;"><?php echo JText::_('VCMROOMSRELATIONSOTA'); ?></td>
			<td rowspan="2" style="width: 10%; vertical-align: middle; text-align: center;"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikchannelmanager/assets/css/images/link.png"/></td>
			<td colspan="2" style="width: 45%; text-align: center; font-weight: bold; border-bottom: 1px solid #dddddd;"><?php echo JText::_('VCMROOMSRELATIONSVB'); ?></td>
		</tr>
		<tr>
			<td style="text-align: center; font-weight: bold;"><?php echo JText::_('VCMROOMSRELATIONSID'); ?></td>
			<td style="text-align: center; font-weight: bold;"><?php echo JText::_('VCMROOMSRELATIONSNAME'); ?></td>
			<td style="text-align: center; font-weight: bold;"><?php echo JText::_('VCMROOMSRELATIONSID'); ?></td>
			<td style="text-align: center; font-weight: bold;"><?php echo JText::_('VCMROOMSRELATIONSNAME'); ?></td>
		</tr>
	</table>
</div>
	
<div class="vcmdivright">
	<h3><?php echo JText::_('VCMROOMSRETURNEDBYVB'); ?></h3>
	<table class="vcmtableright">
	<?php foreach($vbrooms as $vbroom) { ?>
		<tr>
			<td class="vcmvbroomtdlink" rowspan="2"><span class="vcmselectvbroom" id="vcmvbrselector<?php echo $vbroom['id']; ?>" onclick="vcmEndLinking('<?php echo $vbroom['id']; ?>', '<?php echo addslashes($vbroom['name']); ?>');"><?php echo JText::_('VCMSELECTVBROOMTOLINK'); ?></span></td>
			<td rowspan="2"><?php echo (!empty($vbroom['img']) ? '<img src="'.JURI::root().'components/com_vikbooking/resources/uploads/'.$vbroom['img'].'" class="vcmvbroomimg"/>' : ''); ?></td>
			<td class="vcmvbroomtdname"><?php echo $vbroom['name']; ?></td>
		</tr>
		<tr>
			<td colspan="3" class="vcmvbroomtdsmalldesc"><?php echo $vbroom['smalldesc']; ?></td>
		</tr>
		<?php } ?>
	</table>
</div>
	
<input type="hidden" name="tototarooms" value="<?php echo $tototarooms; ?>"/>

<?php
if(count($rooms_rate_plans) > 0) {
?>
<script type="text/javascript">
var room_plans = new Object();
<?php
	foreach ($rooms_rate_plans as $idr => $room_plan) {
		echo "room_plans.r".$idr." = ".json_encode($room_plan).";\n";
	}
?>
</script>
<?php
}

//Debug:
//echo '<br clear="all"/><br clear="all"/><br/><pre>'.print_r($channelrooms, true).'</pre><br/>';
?>
