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

$module = $this->module;
$listings = $this->listings;

?>

<form action="index.php" name="adminForm" id="adminForm" method="post">

    <div class="vcmlistingscont">
        
        <?php foreach( $listings as $l ) { 
            $url_ok = ( !empty($l['retrieval_url']) && filter_var($l['retrieval_url'], FILTER_VALIDATE_URL) );
            ?>
            
            <div class="vcmsingleproperty">
                
                <!-- IMAGE ON LEFT -->
                <?php if( !empty($l['img']) ) { ?>
                    <div class="vcmpropertyleft">
                        <img src="<?php echo JURI::root().'components/com_vikbooking/resources/uploads/'.$l['img']; ?>" />
                    </div>
                <?php } ?>
                
                <!-- CENTER AND RIGHT SIDE -->
                <div class="vcmpropertydetails">
                    
                    <!-- DETAILS TITLE TOP -->
                    <div class="vcmpropertydetailstop">
                        <h3><?php echo $l['name']; ?></h3>
                    </div>
                    
                    <!-- DETAILS URL -->
                    <div class="vcmpropertydetailsmiddle">
                        <div class="vcmpropertyurle4j">
                            <div class="vcmpropertyinputblock" title="<?php echo JText::_('VCMLISTINGDURLTIP'.strtoupper($module['name'])); ?>">
                                <?php echo JText::_('VCMLISTINGLABELDOWNLOADURL'); ?>
                            </div>
                            <div class="vcmpropertyinputblock">
                                <input type="text" value="<?php echo $l['download_url']; ?>" onfocus="this.select();" readonly/>
                            </div>
                        </div>
                        
                        <div class="vcmpropertyurlcha">
                            <div class="vcmpropertyinputblock" title="<?php echo JText::_('VCMLISTINGRURLTIP'.strtoupper($module['name'])); ?>">
                                <?php echo JText::sprintf('VCMLISTINGLABELRETRIEVALURL', ucwords($module['name'])); ?>
                            </div>
                            <div class="vcmpropertyinputblock">
                                <input type="text" value="<?php echo $l['retrieval_url']; ?>" name="urls[]" 
                                style="<?php echo (!empty($l['retrieval_url']) && !$url_ok ? 'color: #AA0000' : ''); ?>"/>
                            </div>
                        </div>
                        
                         <div class="vcmpropertystatus">
                            <div class="vcmpropertyinputblock vcmpropertystatus<?php echo ($url_ok ? 'ok' : 'bad'); ?>">
                                <?php echo JText::_('VCMLISTINGSTATUS'.($url_ok ? 'OK' : 'BAD') ); ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
            </div>
            
            <input type="hidden" name="id_vb_rooms[]" value="<?php echo $l['id']; ?>" />
            <input type="hidden" name="id_assoc[]" value="<?php echo $l['id_assoc']; ?>" />
                
        <?php } ?>
        
    </div>

    <input type="hidden" name="task" value="listings" />
    <input type="hidden" name="option" value="com_vikchannelmanager" />
    <?php echo $this->navbut; ?>

</form>

<script>
    
    jQuery(document).ready(function(){
        jQuery('.vcmpropertyinputblock').tooltip();
    });
    
</script>

