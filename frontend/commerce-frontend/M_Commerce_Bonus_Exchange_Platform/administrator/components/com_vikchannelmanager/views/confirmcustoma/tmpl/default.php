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

$cust_a = $this->cust_a;

$df = VikChannelManager::getClearDateFormat(true);

?>

<form action="index.php" name="adminForm" id="adminForm" method="post">
    
    <?php foreach( $cust_a as $idroom => $ca ) { ?>
        <div class="vcm-custa-roomname">
            <h2><?php echo $ca['rname']; ?></h2>
            <?php if( !empty($ca['rdesc']) ) { ?>
                <div class="vcm-custa-roomdesc">
                    <small><?php echo $ca['rdesc']; ?></small>
                </div>
            <?php } ?>
        </div>
        
        <div class="vcm-custa-roomcont">
            <div class="vcm-custa-roomcont-inner">
            
                <div class="vcm-custa-roomdetails">
                    <?php
                    $vbo_update = false;
                    foreach( $ca['details'] as $details ) { ?>
                        <div class="vcm-custa-roomblock">
                            <?php if( $details['endts'] != 0 ) { ?>
                                <span class="vcm-custa-from-label"><?php echo JText::_('VCMOSFROMDATE'); ?></span>
                                <span class="vcm-custa-from-value"><?php echo date( $df, $details['fromts'] ); ?></span>
                                <span class="vcm-custa-to-label"><?php echo JText::_('VCMOSTODATE'); ?></span>
                                <span class="vcm-custa-to-value"><?php echo date( $df, $details['endts'] ); ?></span>
                            <?php } else { ?>
                                <span class="vcm-custa-from-label"><?php echo JText::_('VCMOSSINGDATE'); ?></span>
                                <span class="vcm-custa-from-value"><?php echo date( $df, $details['fromts'] ); ?></span>
                                <span class="vcm-custa-to-label">&nbsp;</span>
                                <span class="vcm-custa-to-value">&nbsp;</span>
                            <?php } ?>
                            <span class="vcm-custa-units-label"><?php echo JText::_('VCMOSUNITSONDATE'); ?></span>
                            <span class="vcm-custa-units-value"><?php echo $details['units']; ?></span>
                        </div>
                        
                        <?php if( empty($ca['rdesc']) ) {
                            $vbo_update = $details['vbounits'] > $details['units'] ? true : $vbo_update;
                            $val = $idroom."-".$details['fromts']."-".(empty($details['endts']) ? $details['fromts'] : $details['endts'])."-".$details['units']."-".$details['vbounits'];
                            ?>
                            <input type="hidden" name="cust_av[]" value="<?php echo $val; ?>"/>
                        <?php } ?>
                        
                    <?php } ?>  
                </div>
                
                <div class="vcm-custa-roomchannels">
                    
                    <?php if( count($ca['channels']) > 0 ) { ?>
                        <div class="vcm-custa-channelhead">
                            <input type="button" value="<?php echo JText::_('VCMOSCHECKALL'); ?>" onClick="jQuery('.check-<?php echo $idroom; ?>').prop('checked', true);"/>
                            <input type="button" value="<?php echo JText::_('VCMOSUNCHECKALL'); ?>" onClick="jQuery('.check-<?php echo $idroom; ?>').prop('checked', false);"/>
                        </div>
                    <?php } ?>
                    
                    <?php foreach( $ca['channels'] as $ch ) { ?>
                        <div class="vcm-custa-roomch">
                            <input type="checkbox" value="<?php echo $ch['uniquekey']; ?>" name="channel[<?php echo $idroom; ?>][]" id="<?php echo "ch-".$ch['name']."-".$ca['rname']; ?>" class="check-<?php echo $idroom; ?>"/>
                            <label for="<?php echo "ch-".$ch['name']."-".$ca['rname']; ?>"><?php echo ucwords($ch['name']); ?></label>
                        </div>
                    <?php } ?>
                    <?php if( $vbo_update === true ) { ?>
                        <div class="vcm-custa-roomch vcm-custa-roomch-vbo">
                            <input type="checkbox" value="vbo" name="channel[<?php echo $idroom; ?>][]" id="<?php echo "ch-vbo-".$ca['rname']; ?>" class="check-<?php echo $idroom; ?>"/>
                            <label for="<?php echo "ch-vbo-".$ca['rname']; ?>"><?php echo JText::_('VCMUPDATEVBOBOOKINGS'); ?></label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        
    <?php } ?>

	<input type="hidden" name="task" value="">
	<input type="hidden" name="option" value="com_vikchannelmanager">
</form>

<div style="clear:both;"></div>

<div class="vcm-loading-overlay">
    <div class="vcm-loading-dot vcm-loading-dot1"></div>
    <div class="vcm-loading-dot vcm-loading-dot2"></div>
    <div class="vcm-loading-dot vcm-loading-dot3"></div>
    <div class="vcm-loading-dot vcm-loading-dot4"></div>
    <div class="vcm-loading-dot vcm-loading-dot5"></div>
</div>

<script type="text/javascript">
/* Show loading when sending CUSTA_RQ to prevent double submit */
Joomla.submitbutton = function(task) {
    if( task == 'sendCustomAvailabilityRequest' ) {
        vcmShowLoading();
    }
    Joomla.submitform(task, document.adminForm);
}
/* Loading Overlay */
function vcmShowLoading() {
    jQuery(".vcm-loading-overlay").show();
}
</script>