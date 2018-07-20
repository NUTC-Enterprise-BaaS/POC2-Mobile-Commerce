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
$rows = $this->rows;
$lim0 = $this->lim0;
$navbut = $this->navbut;

$df = "";
if( $config['dateformat'] == "%d/%m/%Y" ) {
	$df = 'd/m/Y';
} else if( $df == "%Y/%m/%d" ){
	$df = 'Y/m/d';
} else {
	$df = 'm/d/Y';
}

$currencysymb = $config['currencysymb'];

$vik = new VikApplication(VersionListener::getID());
		
if( count($rows) == 0 ){ ?>
	<p><?php echo JText::_('VCMNOORDERSFOUNDVB'); ?></p>
	<form action="index.php?option=com_vikchannelmanager" method="post" name="adminForm" id="adminForm">
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="option" value="com_vikchannelmanager" />
	</form>
<?php } else {
	JHTML::_('behavior.modal');
?>

<form action="index.php?option=com_vikchannelmanager" method="post" name="adminForm" id="adminForm">

	<p class="vbpfiltconfnumb">
		<input type="text" name="confirmnumber" size="20" placeholder="<?php echo (!empty($_POST['confirmnumber']) ? $_POST['confirmnumber'] : JText::_('VCMCONFIRMNUMB')); ?>" value="<?php echo (!empty($_POST['confirmnumber']) ? $_POST['confirmnumber'] : JText::_('VCMCONFIRMNUMB')); ?>" onfocus="javascript: if (this.defaultValue==this.value) this.value='';" onblur="javascript: if (this.value == '') this.value=this.defaultValue;"/> &nbsp; <input type="submit" value="<?php echo JText::_('VCMPVIEWORDERSVBSEARCHSUBM'); ?>"/>
	</p>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="title" width="20" align="center">ID</th>
				<th class="title" width="110"><?php echo JText::_( 'VCMPVIEWORDERSVBONE' ); ?></th>
				<th class="title" width="200"><?php echo JText::_( 'VCMPVIEWORDERSVBTWO' ); ?></th>
				<th class="title" width="50" align="center"><?php echo JText::_( 'VCMPVIEWORDERSVBTHREE' ); ?></th>
				<th class="title" width="150"><?php echo JText::_( 'VCMPVIEWORDERSVBPEOPLE' ); ?></th>
				<th class="title" width="110"><?php echo JText::_( 'VCMPVIEWORDERSVBFOUR' ); ?></th>
				<th class="title" width="110"><?php echo JText::_( 'VCMPVIEWORDERSVBFIVE' ); ?></th>
				<th class="title" width="70" align="center"><?php echo JText::_( 'VCMPVIEWORDERSVBSIX' ); ?></th>
				<th class="title" width="110" align="center"><?php echo JText::_( 'VCMPVIEWORDERSVBSEVEN' ); ?></th>
				<th class="title" width="100" align="center"><?php echo JText::_( 'VCMPVIEWORDERSVBEIGHT' ); ?></th>
				<th class="title" width="100" align="center"><?php echo JText::_( 'VCMPVIEWORDERSVBNINE' ); ?></th>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
	<?php
	$kk = 0;
	$i = 0;
	$nowtime = time();
	for ($i = 0, $n = count($rows); $i < $n; $i++) {
		$row = $rows[$i];
		$rooms = VikChannelManager::loadOrdersRoomsDataVb($row['id']);
		$peoplestr = "";
		if( is_array($rooms) ) {
			$totadults = 0;
			$totchildren = 0;
			foreach($rooms as $rr) {
				$totadults += $rr['adults'];
				$totchildren += $rr['children'];
			}
			$peoplestr .= $totadults." ".($totadults > 1 ? JText::_('VCMADULTS') : JText::_('VCMADULT')).($totchildren > 0 ? ", ".$totchildren." ".($totchildren > 1 ? JText::_('VCMCHILDREN') : JText::_('VCMCHILD')) : "");
		}
		$isdue = $row['total'];
		$ordernotifications = VikChannelManager::loadOrdersVbNotifications($row['id']);
		$checkboxdisabled = ( $row['checkin'] <= $nowtime ? 'disabled="disabled"' : '' );
		//$otachannel = '';
		$otacurrency = '';
		if( !empty($row['idorderota']) ) {
			//$channelparts = explode('_', $row['channel']);
			//$otachannel = (count($channelparts) > 1 ? $channelparts[1] : $channelparts[0]);
			$otacurrency = strlen($row['chcurrency']) > 0 ? $row['chcurrency'] : '';
		} ?>
		<tr class="row<?php echo $kk; ?>">
			<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"<?php echo $checkboxdisabled; ?>></td>
			<td align="center"><?php echo $row['id']; ?></td>
			<td><a href="index.php?option=com_vikbooking&task=editorder&cid[]=<?php echo $row['id']; ?>&tmpl=component" rel="{handler: 'iframe', size: {x: 750, y: 600}}" class="modal" target="_blank"><?php echo date($df.' H:i', $row['ts']); ?></a></td>
			<td><?php echo (!empty($row['custdata']) ? substr($row['custdata'], 0, 45)." ..." : ""); ?></td>
			<td align="center"><?php echo $row['roomsnum']; ?></td>
			<td><?php echo $peoplestr; ?></td>
			<td><?php echo date($df.' H:i', $row['checkin']); ?></td>
			<td><?php echo date($df.' H:i', $row['checkout']); ?></td>
            <td align="center"><?php echo $row['days']; ?></td>
            <td align="center"><?php echo (strlen($otacurrency) > 0 ? $otacurrency : $currencysymb)." ".number_format($isdue, 2).(!empty($row['totpaid']) ? " &nbsp;(".$currencysymb." ".number_format($row['totpaid'], 2).")" : ""); ?></td>
            <td align="center"><?php echo ($row['status']=="confirmed" ? "<span style=\"color: #4ca25a;font-weight:bold;\">".JText::_('VBCONFIRMED')."</span>" : "<span style=\"color: #e0a504;font-weight:bold;\">".JText::_('VBSTANDBY')."</span>"); ?></td>
            <td align="center"><?php echo (!empty($row['channel']) ? "<span class=\"vbotasp ".$row['channel']."\">".ucwords($row['channel'])."</span>" : ""); ?></td>
        </tr>
        <?php
        $kk = 1 - $kk;
		
	}
	?>
	
	</table>
	<input type="hidden" name="option" value="com_vikchannelmanager" />
	<input type="hidden" name="task" value="ordersvb" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>

<?php } ?>

