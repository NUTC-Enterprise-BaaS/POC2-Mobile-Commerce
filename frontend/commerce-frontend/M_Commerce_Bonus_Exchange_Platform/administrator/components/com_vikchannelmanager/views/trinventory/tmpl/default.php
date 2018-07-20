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

JHTML::_('behavior.tooltip');

$rooms = $this->rooms;

$curr_symb = VikChannelManager::getCurrencySymb(true);

?>

<form action="index.php" name="adminForm" id="adminForm" method="POST">
	
	<div class="vcmactionstoolbar">
		
		<div class="vcmtriroomheadtitle"><?php echo JText::_('VCMTRIROOMSHEADTITLE'); ?></div>
		
		<div class="vcmtriroompuballdiv">
			<a href="javascript: void(0);" onClick="changeAllRoomsStatus(1);" class="vcmtrivagotatuslinkactive"><?php echo JText::_('VCMTACROOMBTNPUBALL'); ?></a>
		</div>
		
		<div class="vcmtriroomunpuballdiv">
			<a href="javascript: void(0);" onClick="changeAllRoomsStatus(0);" class="vcmtrivagostatuslinkunactive"><?php echo JText::_('VCMTACROOMBTNUNPUBALL'); ?></a>
		</div>
	</div>
	
	<?php if( count($rooms) == 0 ) { ?>
        <div class="vcminventorynoroom"><?php echo JText::_("VCMINVENTORYNOROOM"); ?></div>
    <?php } ?>

	<div class="vcmtriallrooms">
		
		<?php $i = 0; ?>
		<?php foreach( $rooms as $r ) { ?>
			
			<div class="vcmtriroomdiv <?php echo (($r['tri_room_id'] != 0) ? 'vcmtriroomactive' : 'vcmroomunactive'); ?>" id="vcmroom<?php echo $i; ?>">
			
				<div class="vcmtriroomtopdiv">
					<div class="vcmtriroomimagediv">
						<?php if( !empty($r['img']) ) { ?>
							<img style="max-height: 190px;" src="<?php echo JURI::root().'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS.$r['img']; ?>" />
						<?php } ?>
						<input type="hidden" name="image[]" value="<?php echo $r['img']; ?>"/>
					</div>
					
					<div class="vcmtriroomdetailsdiv">
						<div class="vcmtriroomnamediv">
							<input type="text" name="name[]" value="<?php echo $r['name']; ?>" placeholder="<?php echo JText::_('VCMTACROOMDETNAME'); ?>" class="vcmroomdetinput"/>
						</div>
						
						<label class="vcmtriroomcostlabel">
					     	<span class="vcmtriroomcostspan"><?php echo $curr_symb; ?></span>
					      	<input type="text" name="cost[]" value="<?php echo $r['cost']; ?>" placeholder="<?php echo JText::_('VCMTACROOMDETCOST'); ?>" class="vcmroomdetinput"/>
					    </label>
						
						<div class="vcmtriroomcodediv">
							<?php echo VikChannelManager::composeSelectRoomCodes('codes[]', VikChannelManagerConfig::$TRI_ROOM_CODES, $r['codes'], 'vcmroomdetinput'); ?>
						</div>
					</div>
				</div>
				
				<div class="vcmtriroomurldiv">
					<input type="text" name="url[]" value="<?php echo $r['url']; ?>" size="32" readonly placeholder="<?php echo JText::_('VCMTACROOMDETURL'); ?>" class="vcmroomdetinput"/>
				</div>
				
				<div class="vcmtriroomdescdiv">
					<textarea name="desc[]" placeholder="<?php echo JText::_('VCMTACROOMDETDESC'); ?>" class="vcmroomdetinput"><?php echo $r['smalldesc'] ?></textarea>
				</div>
				
				<div class="vcmtriroomtrivlogodiv">
					<a href="javascript: void(0);" onClick="changeRoomStatus(<?php echo $i; ?>);" id="vcmtrivstatuslink<?php echo $i; ?>" class="vcmtrivstatuslink"><?php echo JText::_(($r['tri_room_id'] != 0) ? 'VCMTACROOMPUBLISHED' : 'VCMTACROOMUNPUBLISHED'); ?></a>
				</div>
				
				<input type="hidden" name="status[]" value="<?php echo (($r['tri_room_id'] != 0) ? 1 : 0); ?>" id="vcmroomtristatus<?php echo $i; ?>" class="vcmroomtristatushidden" />
				<input type="hidden" name="vb_room_id[]" value="<?php echo $r['id']; ?>" />
				<input type="hidden" name="tri_room_id[]" value="<?php echo $r['tri_room_id']; ?>" id="vcmroomtriid<?php echo $i; ?>"/>
			
			</div>
			
			<?php $i++; ?>
			
		<?php } ?>
		
	</div>
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikchannelmanager" />
	
</form>

<script>
	
	function changeRoomStatus(id) {
		var status = (parseInt(jQuery('#vcmroomtristatus'+id).val()) + 1)%2;
		
		jQuery('#vcmroomtristatus'+id).val(status);
		if( status ) {
			jQuery('#vcmroom'+id).removeClass('vcmroomunactive');
			jQuery('#vcmroom'+id).addClass('vcmtriroomactive');
			jQuery('#vcmtrivstatuslink'+id).html('<?php echo addslashes(JText::_('VCMTACROOMPUBLISHED')); ?>');
		} else {
			jQuery('#vcmroom'+id).removeClass('vcmtriroomactive');
			jQuery('#vcmroom'+id).addClass('vcmroomunactive');
			jQuery('#vcmtrivstatuslink'+id).html('<?php echo addslashes(JText::_('VCMTACROOMUNPUBLISHED')); ?>');
		}
		
	}
	
	function changeAllRoomsStatus(status) {
		jQuery('.vcmroomtristatushidden').val(status);
		if( status ) {
			jQuery('.vcmtriroomdiv').removeClass('vcmroomunactive');
			jQuery('.vcmtriroomdiv').addClass('vcmtriroomactive');
			jQuery('.vcmtrivstatuslink').html('<?php echo addslashes(JText::_('VCMTACROOMPUBLISHED')); ?>');
		} else {
			jQuery('.vcmtriroomdiv').removeClass('vcmtriroomactive');
			jQuery('.vcmtriroomdiv').addClass('vcmroomunactive');
			jQuery('.vcmtrivstatuslink').html('<?php echo addslashes(JText::_('VCMTACROOMUNPUBLISHED')); ?>');
		}
	}
	
</script>

