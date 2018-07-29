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
$rowsmap = $this->rowsmap;
$rows = $this->rows;

$vik = new VikApplication(VersionListener::getID());

if( count($rows) == 0 ){ ?>
	<p class="vcmfatal"><?php echo JText::_('VCMNOROOMSASSOCFOUND'); ?></p>
	<br clear="all"/>
	<span class="vcmsynchspan">
		<a class="vcmsyncha" href="index.php?option=com_vikchannelmanager&amp;task=roomsynch"><?php echo JText::_('VCMGOSYNCHROOMS'); ?></a>
	</span>
	<form action="index.php?option=com_vikchannelmanager" method="post" name="adminForm" id="adminForm">
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="option" value="com_vikchannelmanager" />
	</form>
	<?php } else {
		$channels = array();
		$schema = array();
		$rooms_ch_map = array();
		foreach($rowsmap as $row) {
			$channels[$row['channel']] = $row['channel'];
			$schema[$row['idroomota'].'_'.$row['otaroomname']][] = $row;
			$rooms_ch_map[$row['idroomota'].'_'.$row['otaroomname']] = $row['channel'];
		}
		?>
		<h3 class="vcmlargeheading"><?php echo JText::_('VCMROOMSRELATIONS'); ?></h3>
		<div class="vcmrelationshema">
			<table class="vcmtableschema">
				<tr class="vcmtrbigheader">
					<td colspan="2"><div class="vcmrheadtype headtype_<?php echo count($channels) > 1 ? 'channels' : $channels[key($channels)]; ?>"><?php echo JText::_('VCMROOMSRELATIONSOTA'); ?></div></td>
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
			$lastvbidr = 0;
			$reln1 = false;
			$keys = array_keys($schema);
			$j = 0;
			foreach( $schema as $otak => $relval ) {
				$otaparts = explode('_', $otak);
				?>
				<tr class="vcmschemarow">
					<td><?php echo (array_key_exists($otak, $rooms_ch_map) ? '<span class="vcm-relation-label-small '.$rooms_ch_map[$otak].'">'.ucwords($rooms_ch_map[$otak]).'</span>' : '').$otaparts[0]; ?></td>
					<td><?php echo $otaparts[1]; ?></td>
				<?php
				foreach( $relval as $relk => $rel ) {
					$is_single = false;
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
							$is_single = true;
							//check rel OTA-VBO = n-1
							if(array_key_exists(($j + 1), $keys) && $keys[($j + 1)] != $otak && !(count($schema[$keys[($j + 1)]]) > 1)) {
								if($schema[$keys[($j + 1)]][0]['idroomvb'] == $rel['idroomvb']) {
									$relimg = 'rel_first_multi.png';
									if ($reln1 === true) {
										$relimg = 'rel_middle_reverse.png';
									}else {
										$reln1 = true;
										$lastvbidr = 0;
									}
								}elseif ($reln1 === true) {
									$relimg = 'rel_last_reverse.png';
									//$reln1 = true;
									$reln1 = false;
								}
							}else {
								if ($reln1 === true) {
									$relimg = 'rel_last_reverse.png';
								}
								$reln1 = false;
							}
							//end check rel OTA-VBO = n-1
						}
					} ?>
						<td><img src="<?php echo JURI::root(); ?>administrator/components/com_vikchannelmanager/assets/css/images/<?php echo $relimg; ?>" alt="<?php echo $relimg; ?>"/></td>
						<td><?php echo ($is_single === false || $rel['idroomvb'] !== $lastvbidr) ? $rel['name'] : ''; ?></td>
						<td><?php echo ($is_single === false || $rel['idroomvb'] !== $lastvbidr) ? $rel['idroomvb'] : ''; ?></td>
					</tr>
				<?php
					$lastvbidr = $rel['idroomvb'];
				}
				$j++;
			}
			?>
			</table>
		</div>
		<br clear="all"/>
				
		<form action="index.php?option=com_vikchannelmanager" method="post" name="adminForm" id="adminForm">
			<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
			<?php echo $vik->openTableHead(); ?>
				<tr>
					<th width="20">
						<?php echo $vik->getAdminToggle(count($rows)); ?>
					</th>
					<th class="title" width="200"><?php echo JText::_('VCMROOMNAMEOTA'); ?></th>
					<th class="title" width="200"><?php echo JText::_('VCMROOMNAMEVB'); ?></th>
					<th class="title center" width="100" align="center"><?php echo JText::_('VCMCHANNEL'); ?></th>
					<th class="title center" width="75" align="center"><?php echo JText::_('VCMACCOUNTCHANNELID'); ?></th>
					<th class="title center" width="75" align="center"><?php echo JText::_('VCMROOMCHANNELID'); ?></th>
					<th class="title center" width="75" align="center"><?php echo JText::_('VCMROOMVBID'); ?></th>
				</tr>
			<?php echo $vik->closeTableHead(); ?>
			<?php
			$k = 0;
			$i = 0;
			for( $i = 0, $n = count($rows); $i < $n; $i++ ) {
				$row = $rows[$i];
				$chaccount_param = json_decode($row['prop_params'], true);
				$chaccount_param = is_array($chaccount_param) ? $chaccount_param : array();
				$prop_id = array_key_exists('hotelid', $chaccount_param) ? $chaccount_param['hotelid'] : '';
				?>
				<tr class="row<?php echo $k; ?>">
					<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
					<td><?php echo $row['otaroomname']; ?></td>
					<td><?php echo $row['name']; ?></td>
	                <td class="center"><span class="vbotasp <?php echo $row['channel']; ?>"><?php echo ucwords($row['channel']); ?></span></td>
	                <td class="center"><?php echo (!empty($row['prop_name']) ? '<span'.(!empty($prop_id) ? ' title="ID '.$prop_id.'"' : '').'>'.$row['prop_name'].'</span>' : $prop_id); ?></td>
	                <td class="center"><?php echo $row['idroomota']; ?></td>
	                <td class="center"><?php echo $row['idroomvb']; ?></td>
	            </tr>  
				<?php
				$k = 1 - $k;
			}
			?>
			</table>
			<input type="hidden" name="option" value="com_vikchannelmanager" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		
<?php } ?>

