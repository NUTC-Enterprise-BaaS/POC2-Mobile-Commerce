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

$otarooms = $this->otarooms;

if( !is_array($otarooms) ) { ?>
	<form name="adminForm" action="index.php" method="post" id="adminForm">
		<p class="err"><?php echo JText::_('VCMNOOTAROOMSFOUNDCUSTA'); ?></p>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="option" value="com_vikchannelmanager">
	</form>
<?php } else {
	JHTML::_('behavior.calendar');
	$wsel = '<select name="roomsxref[]" id="vcmsel%d"><option value="">------</option>'."\n";
	$currentch = '';
	for( $k = 0; $k < count($otarooms); $k++ ) {
        $ota = $otarooms[$k];
        
		if ($currentch != $ota['channel']) {
			$wsel .= '<optgroup label="'.ucwords($ota['channel']).'">'."\n";
			$currentch = $ota['channel'];
		}
		$wsel .= '<option value="'.$ota['id'].'">'.$ota['otaroomname'].'</option>'."\n";
		if( $k == count($otarooms)-1 || $otarooms[($k + 1)]['channel'] != $ota['channel']) {
			$wsel .= '</optgroup>';
		}
	}
	$wsel .= '</select>'."\n";
	$df = VikChannelManager::getDateFormat(true);
	?>
	<script language="JavaScript" type="text/javascript">
	jQuery.noConflict();
	function vcmValidateField (el, id) {
		if(jQuery.trim(el.value).length) {
			var xrefid = jQuery("#vcmsel"+id).val();
			var rdate = jQuery("#custdate"+id).val();
			if(jQuery.trim(xrefid).length && jQuery.trim(rdate).length) {
				jQuery("#esitvalidate"+id).html("<img src='<?php echo addslashes(JURI::root().'administrator/components/com_vikchannelmanager/assets/css/images/enabled.png'); ?>' style='cursor: pointer;' onclick='javascript: vcmHideEsit(this);'/>");
				if(parseInt(el.value) == 0) {
					jQuery("#esitvalidate"+id).append("<span class='vcmesitinfo'><?php echo addslashes(JText::_('VCMROOMWILLBECLOSED')); ?></span>");
				}
			}else {
				jQuery("#esitvalidate"+id).html("<img src='<?php echo addslashes(JURI::root().'administrator/components/com_vikchannelmanager/assets/css/images/error.png'); ?>' style='cursor: pointer;' onclick='javascript: vcmHideEsit(this);'/>");
			}
		}else {
			jQuery("#esitvalidate"+id).html("");
		}
	}
	function vcmHideEsit (el) {
		jQuery(el).parents("td").html("");
	}
	</script>
	
	<p class="vcminfotext"><?php echo JText::_('VCMCUSTOMATEXT'); ?></p>
	<form name="adminForm" action="index.php" method="post" id="adminForm">
		<table class="adminform">
			<?php
			//maximum 14 days requests for the custa_rq
			for($i = 1; $i <= 14; $i++) { ?>
				<tr>
					<td width="150px" style="text-align: right;"><?php echo sprintf($wsel, $i); ?></td>
					<td width="150px" style="text-align: left;"><?php echo JText::_('VCMCUSTADATE'); ?> <?php echo JHTML::_('calendar', '', 'custdate[]', 'custdate'.$i, $df, array('class'=>'', 'size'=>'10',  'maxlength'=>'19')); ?></td>
					<td width="150px" style="text-align: left;"><?php echo JText::_('VCMCUSTAAVAILNUM'); ?> <input type="text" name="custavail[]" id="custavail<?php echo $i; ?>" value="" size="3" onblur="javascript: vcmValidateField(this, '<?php echo $i; ?>');"/></td>
					<td style="text-align: left;" id="esitvalidate<?php echo $i; ?>">&nbsp;</td>
				</tr>
			<?php } ?>
		</table>
		
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="option" value="com_vikchannelmanager" />
	</form>
<?php } ?>

