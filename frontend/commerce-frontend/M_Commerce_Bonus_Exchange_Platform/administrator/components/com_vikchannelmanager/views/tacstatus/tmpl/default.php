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

$contents = $this->contents;

?>

<?php if( !empty($contents['error']) ) { ?>
	<div class="vcmtacstatuserrdiv">
		<div class="vcmtacstatuserrtitlediv">
			<?php echo 'ERROR: '.$contents['error']['code']; ?>
		</div>
		<div class="vcmtacstatuserrmsgdiv">
			<?php echo $contents['error']['message']; ?>
		</div>
	</div>
<?php } else if( count($contents['data']['locations']) > 0 ) { 
	$status = $contents['data']['locations'][0];
	
	$date_format = VikChannelManager::getClearDateFormat(true);
	
	?>
	
	<div class="vcmtacstatusdiv">
		
		<?php if( !empty($status['partner_id']) && strlen($status['partner_id']) > 0 ) { ?>
			<p class="vcmstatusinfo">
				<span class="vcmstatusinfolabel"><?php echo JText::_('VCMTACSTATUSPID'); ?>:</span>
				<span class="vcmstatusinfoval"><?php echo $status['partner_id']; ?></span>
			</p>
		<?php } ?>
		
		<?php if( !empty($status['tripadvisor_id']) && strlen($status['tripadvisor_id']) > 0 ) { ?>
			<p class="vcmstatusinfo">
				<span class="vcmstatusinfolabel"><?php echo JText::_('VCMTACSTATUSTRIPID'); ?>:</span>
				<span class="vcmstatusinfoval"><?php echo $status['tripadvisor_id']; ?></span>
			</p>
		<?php } ?>
		
		<?php if( !empty($status['business_listing_since']) && strlen($status['business_listing_since']) > 0 ) { 
			$business_date = explode('-', $status['business_listing_since']);
			$business_date = date( $date_format, mktime(0, 0, 0, $business_date[1], $business_date[2], $business_date[0]) );
			?>
			<p class="vcmstatusinfo">
				<span class="vcmstatusinfolabel"><?php echo JText::_('VCMTACSTATUSBUSINESSDATE'); ?>:</span>
				<span class="vcmstatusinfoval"><?php echo $business_date; ?></span>
			</p>
		<?php } ?>
		
		<?php if( !empty($status['tripconnect_since']) && strlen($status['tripconnect_since']) > 0 ) { 
			$tripconnect_date = explode('-', $status['tripconnect_since']);
			$tripconnect_date = date( $date_format, mktime(0, 0, 0, $tripconnect_date[1], $tripconnect_date[2], $tripconnect_date[0]) );
			?>
			<p class="vcmstatusinfo">
				<span class="vcmstatusinfolabel"><?php echo JText::_('VCMTACSTATUSTRIPDATE'); ?>:</span>
				<span class="vcmstatusinfoval"><?php echo $tripconnect_date; ?></span>
			</p>
		<?php } ?>
		
		<?php if( !empty($status['tripconnect_last_active']) && strlen($status['tripconnect_last_active']) > 0 ) { 
			$lastactive_date = explode('-', $status['tripconnect_last_active']);
			$lastactive_date = date( $date_format, mktime(0, 0, 0, $lastactive_date[1], $lastactive_date[2], $lastactive_date[0]) );
			?>
			<p class="vcmstatusinfo">
				<span class="vcmstatusinfolabel"><?php echo JText::_('VCMTACSTATUSLASTACTIVEDATE'); ?>:</span>
				<span class="vcmstatusinfoval"><?php echo $lastactive_date; ?></span>
			</p>
		<?php } ?>
		
		<?php if( !empty($status['tripconnect_platforms']) ) { 
			$platforms = "";
			for( $i = 0; $i < count($status['tripconnect_platforms']); $i++ ) {
				if( !empty($platforms) ) {
					$platforms .= ', ';
				}
				$platforms .= $status['tripconnect_platforms'][$i];
			}
			?>
			<p class="vcmstatusinfo">
				<span class="vcmstatusinfolabel"><?php echo JText::_('VCMTACSTATUSPLATFORMS'); ?>:</span>
				<span class="vcmstatusinfoval"><?php echo $platforms; ?></span>
			</p>
		<?php } ?>
		
		<?php if( !empty($status['tripconnect_clicks']) && strlen($status['tripconnect_clicks']) > 0 ) { ?>
			<p class="vcmstatusinfo">
				<span class="vcmstatusinfolabel"><?php echo JText::_('VCMTACSTATUSNUMCLICKS'); ?>:</span>
				<span class="vcmstatusinfoval <?php echo 'vcmstatus'.(($status['tripconnect_clicks'] > 0) ? 'green' : 'red'); ?>"><?php echo $status['tripconnect_clicks']; ?></span>
			</p>
		<?php } ?>
		
		<?php if( !empty($status['tripconnect_conversions']) && strlen($status['tripconnect_conversions']) > 0 ) { ?>
			<p class="vcmstatusinfo">
				<span class="vcmstatusinfolabel"><?php echo JText::_('VCMTACSTATUSNUMCONVERSIONS'); ?>:</span>
				<span class="vcmstatusinfoval <?php echo 'vcmstatus'.(($status['tripconnect_conversions'] > 0) ? 'green' : 'red'); ?>"><?php echo $status['tripconnect_conversions']; ?></span>
			</p>
		<?php } ?>
		
		<?php if( !empty($status['review_express_opted_in']) && strlen($status['review_express_opted_in']) > 0 ) { ?>
			<p class="vcmstatusinfo">
				<span class="vcmstatusinfolabel"><?php echo JText::_('VCMTACSTATUSREVIEWEXPRESS'); ?>:</span>
				<span class="vcmstatusinfoval <?php echo 'vcmstatus'.(($status['review_express_opted_in']) ? 'green' : 'red'); ?>"><?php echo JText::_(($status['review_express_opted_in']) ? 'VCMENABLED' : 'VCMDISABLED'); ?></span>
			</p>
		<?php } ?>
		
	</div>
	
<?php } ?>

