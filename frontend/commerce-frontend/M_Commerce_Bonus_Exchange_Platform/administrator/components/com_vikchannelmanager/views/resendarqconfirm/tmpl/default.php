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

$orders = $this->orders;

$saytask = "";

$valid = array();
$invalid = array();
$now = time();
foreach($orders as $k => $ord) {
	if ($ord['checkin'] <= $now) {
		$invalid[$k] = $orders[$k];
	} else {
		if (count($ord['notifications']) > 0) {
			$invalid[$k] = $orders[$k];
		} else if(!empty($ord['idorderota'])) {
			$invalid[$k] = $orders[$k];
		} else {
			$valid[$k] = $orders[$k];
		}
	}
}

$date_format = VikChannelManager::getDateFormat(true);

if( $date_format == "%d/%m/%Y") {
	$df = 'd/m/Y';
} else if( $date_format == "%Y/%m/%d") {
	$df = 'Y-m-d';
} else {
	$df = 'm/d/Y';
}

?>
<h3><?php echo JText::_('VCMRENOTIFYORDSTOOTA'); ?></h3>
<form name="adminForm" action="index.php" method="post" id="adminForm">
	<table class="adminform">
		<tr>
			<td width="20" align="center"><strong>ID</strong></td>
			<td width="110"><strong><?php echo JText::_( 'VCMPVIEWORDERSVBONE' ); ?></strong></td>
			<td width="200"><strong><?php echo JText::_( 'VCMPVIEWORDERSVBTWO' ); ?></strong></td>
			<td width="110"><strong><?php echo JText::_( 'VCMPVIEWORDERSVBFOUR' ); ?></strong></td>
			<td width="110"><strong><?php echo JText::_( 'VCMPVIEWORDERSVBFIVE' ); ?></strong></td>
			<td width="70" align="center"><strong><?php echo JText::_( 'VCMPVIEWORDERSVBSIX' ); ?></strong></td>
			<td width="50" align="center"><strong><?php echo JText::_( 'VCMRENOTIFIABLE' ); ?></strong></td>
		</tr>
		<?php
		foreach($orders as $k => $order) {
			$imgnotifiable = array_key_exists($k, $invalid) ? 'error.png' : 'enabled.png'; ?>
			<tr>
				<td align="center"><?php echo $order['id']; ?></td>
				<td><?php echo date($df.' H:i', $order['ts']); ?></td>
				<td><?php echo (!empty($order['custdata']) ? substr($order['custdata'], 0, 45)." ..." : ""); ?></td>
				<td><?php echo date($df.' H:i', $order['checkin']); ?></td>
				<td><?php echo date($df.' H:i', $order['checkout']); ?></td>
				<td align="center"><?php echo $order['days']; ?></td>
				<td align="center"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikchannelmanager/assets/css/images/<?php echo $imgnotifiable; ?>"/></td>
			</tr>	
		<?php } ?>
	</table>
	<?php if( count($valid) > 0 ) {
		$saytask = 'resend_arq';
		//Maximum 3 Order per time can be re-notified
		$max = 3;
		$turns = 1;
		foreach($valid as $ord) {
			if ($turns > $max) {
				break;
			}
			echo '<input type="hidden" name="cid[]" value="'.$ord['id'].'"/>'."\n";
			$turns ++;
		} ?>
		<p class="vcminfotext"><?php echo JText::_('VCMCONFIRMRENOTIFYORDSTEXT'); ?><br/><br/><input type="submit" name="start" value="<?php echo JText::_('VCMCONFIRMRENOTIFYORDSPROCEED'); ?>" class="vcmconfirmsubmit"/></p>
		<?php
	} else {
		$saytask = ''; ?>
		<p class="err"><?php echo JText::_('VCMNOVALIDORDSTORESENDOTA'); ?></p>
	<?php } ?>
	
	<input type="hidden" name="task" value="<?php echo $saytask; ?>">
	<input type="hidden" name="option" value="com_vikchannelmanager">
</form>

