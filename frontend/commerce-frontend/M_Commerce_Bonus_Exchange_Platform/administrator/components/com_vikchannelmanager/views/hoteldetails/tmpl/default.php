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

$params = $this->params;
$countries = $this->countries;

if( !empty($params['amenities']) ) {
	$params['amenities'] = explode(',', $params['amenities']);
} else {
	$params['amenities'] = array();
}

$all_empties = true;
foreach( $params as $k => $p ) {
	$all_empties = empty($p) && $all_empties;
}

$ta_room_amenities = VikChannelManagerConfig::$TA_HOTEL_AMENITIES;
sort($ta_room_amenities);

$select_amenities = VikChannelManager::composeSelectAmenities('amenities[]', $ta_room_amenities, $params['amenities']);

$countries_select = '<select name="country" id="vcmhcountry" onBlur="checkRequiredField(\'vcmhcountry\');" class="'.((!$all_empties && empty($params['country'])) ? 'vcmrequired': '').'">';
$countries_select .= '<option value="">--</option>';
foreach( $countries as $c ) {
    $countries_select .= '<option value="'.$c['country_name'].'" '.($this->params['country'] == $c['country_name'] ? 'selected="selected"' : '').'>'.$c['country_name'].'</option>';
}
$countries_select .= '</select>';

$user = JFactory::getUser();
		
?>
		
<?php if( !VikChannelManager::checkIntegrityHotelDetails() ) { ?>
    <p class="vcmhdparwarning"><?php echo JText::_('VCMHOTELDETAILSFIRSTBUILDING'); ?></p>
<?php } ?>

<form name="adminForm" id="adminForm" action="index.php" method="post">
	<table class="adminform">
		
		<tr>
			<td style="width: 150px;"><strong><?php echo JText::_('VCMTACHOTELNAME'); ?></strong>*:</td>
			<td><input type="text" name="name" value="<?php echo $params['name']; ?>" size="30" id="vcmhname" onBlur="checkRequiredField('vcmhname');" class="<?php echo ((!$all_empties && empty($params['name'])) ? 'vcmrequired': ''); ?>"/></td>
		</tr>
		
		<tr>
			<td style="width: 150px;"><strong><?php echo JText::_('VCMTACHOTELSTREET'); ?></strong>*:</td>
			<td><input type="text" name="street" value="<?php echo $params['street']; ?>" size="30" id="vcmhstreet" onBlur="checkRequiredField('vcmhstreet');" class="<?php echo ((!$all_empties && empty($params['street'])) ? 'vcmrequired': ''); ?>"/></td>
		</tr>
		
		<tr>
			<td style="width: 150px;"><strong><?php echo JText::_('VCMTACHOTELCITY'); ?></strong>*:</td>
			<td><input type="text" name="city" value="<?php echo $params['city']; ?>" size="30" id="vcmhcity" onBlur="checkRequiredField('vcmhcity');" class="<?php echo ((!$all_empties && empty($params['city'])) ? 'vcmrequired': ''); ?>"/></td>
		</tr>
		
		<tr>
			<td style="width: 150px;"><strong><?php echo JText::_('VCMTACHOTELZIP'); ?></strong>:</td>
			<td><input type="text" name="zip" value="<?php echo $params['zip']; ?>" size="5"/></td>
		</tr>
		
		<tr>
			<td style="width: 150px;"><strong><?php echo JText::_('VCMTACHOTELSTATE'); ?></strong>:</td>
			<td><input type="text" name="state" value="<?php echo $params['state']; ?>" size="15"/></td>
		</tr>
		
		<tr>
			<td style="width: 150px;"><strong><?php echo JText::_('VCMTACHOTELCOUNTRY'); ?></strong>*:</td>
			<td><?php echo $countries_select; ?></td>
		</tr>
		
		<tr>
			<td style="width: 150px;"><strong><?php echo JText::_('VCMTACHOTELLATITUDE'); ?></strong>:</td>
			<td><input type="text" name="latitude" value="<?php echo $params['latitude']; ?>" size="10"/></td>
		</tr>
		
		<tr>
			<td style="width: 150px;"><strong><?php echo JText::_('VCMTACHOTELLONGITUDE'); ?></strong>:</td>
			<td><input type="text" name="longitude" value="<?php echo $params['longitude']; ?>" size="10"/></td>
		</tr>
		
		<tr>
			<td style="width: 150px;"><strong><?php echo JText::_('VCMTACHOTELDESCRIPTION'); ?></strong>:</td>
			<td><textarea name="description" rows="4" cols="30"><?php echo $params['description']; ?></textarea></td>
		</tr>
		
		<tr>
			<td style="width: 150px;"><strong><?php echo JText::_('VCMTACHOTELAMENITIES'); ?></strong>:</td>
			<td><?php echo $select_amenities; ?></td>
		</tr>
		
		<tr>
			<td style="width: 150px;"><strong><?php echo JText::_('VCMTACHOTELURL'); ?></strong>*:</td>
			<td><input type="text" name="url" value="<?php echo ((empty($params['url'])) ? JURI::root() : $params['url']); ?>" size="30" id="vcmhurl" onBlur="checkRequiredField('vcmhurl');" class="<?php echo ((!$all_empties && empty($params['url'])) ? 'vcmrequired': ''); ?>"/></td>
		</tr>
		
		<tr>
			<td style="width: 150px;"><strong><?php echo JText::_('VCMTACHOTELEMAIL'); ?></strong>:</td>
			<td><input type="text" name="email" value="<?php echo ((empty($params['email'])) ? $user->email : $params['email']); ?>" size="30"/></td>
		</tr>
		
		<tr>
			<td style="width: 150px;"><strong><?php echo JText::_('VCMTACHOTELPHONE'); ?></strong>:</td>
			<td><input type="text" name="phone" value="<?php echo $params['phone']; ?>" size="30"/></td>
		</tr>
		
		<tr>
			<td style="width: 150px;"><strong><?php echo JText::_('VCMTACHOTELFAX'); ?></strong>:</td>
			<td><input type="text" name="fax" value="<?php echo $params['fax']; ?>" size="30"/></td>
		</tr>
		
	</table>
	
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_vikchannelmanager" />
</form>

<script>
	
	function checkRequiredField(id) {
		if( jQuery('#'+id).val().length > 0 ) {
			jQuery('#'+id).removeClass('vcmrequired');
			return true;
		} else {
			jQuery('#'+id).addClass('vcmrequired');
			return false;
		}
	}
	
</script>

