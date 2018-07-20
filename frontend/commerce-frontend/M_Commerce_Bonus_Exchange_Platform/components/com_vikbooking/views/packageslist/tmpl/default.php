<?php
/**------------------------------------------------------------------------
 * com_vikbooking - VikBooking
 * ------------------------------------------------------------------------
 * author    Alessio Gaggii - e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') OR die('Restricted Area');

$packages = $this->packages;
$vbo_tn=$this->vbo_tn;
$navig=$this->navig;

$currencysymb = vikbooking::getCurrencySymb();
$nowdf = vikbooking::getDateFormat();
if ($nowdf == "%d/%m/%Y") {
	$df = 'd/m/Y';
}elseif ($nowdf == "%m/%d/%Y") {
	$df = 'm/d/Y';
}else {
	$df = 'Y/m/d';
}

if(!(count($packages) > 0)) {
	?>
<h3 class="vbclistheadt"><?php echo JText::_('VBONOPKGFOUND'); ?></h3>
	<?php
}else {
	?>
<h3 class="vbclistheadt"><?php echo JText::_('VBOPKGLIST'); ?></h3>
<div class="vbo-pkglist-container">
	<?php
	foreach ($packages as $pk => $package) {
		$costfor = array();
		if($package['perperson'] == 1) {
			$costfor[] = JText::_('VBOPKGCOSTPERPERSON');
		}
		if($package['pernight_total'] == 1) {
			$costfor[] = JText::_('VBOPKGCOSTPERNIGHT');
		}
		?>
	<div class="vbo-pkglist-pkg">
		<div class="vbo-pkglist-pkg-bone">
		<?php
		if(!empty($package['img'])) {
			?>
			<div class="vbo-pkglist-pkg-img">
				<img src="<?php echo JURI::root(); ?>components/com_vikbooking/resources/uploads/thumb_<?php echo $package['img']; ?>" alt="<?php echo $package['name']; ?>" />
			</div>
			<?php
		}
		?>
		</div>
		<div class="vbo-pkglist-pkg-btwo">
			<div class="vbo-pkglist-pkg-name"><?php echo $package['name']; ?></div>
			<div class="vbo-pkglist-pkg-dates-cont">
				<div class="vbo-pkglist-pkg-dates">
					<span class="vbo-pkglist-pkg-dates-lbl"><?php echo JText::_('VBOPKGVALIDATES'); ?></span>
					<span class="vbo-pkglist-pkg-dates-ft"><?php echo date($df, $package['dfrom']).($package['dfrom'] != $package['dto'] ? ' - '.date($df, $package['dto']) : ''); ?></span>
				</div>
			</div>
			<div class="vbo-pkglist-pkg-shortdescr"><?php echo $package['shortdescr']; ?></div>
		</div>
		<div class="vbo-pkglist-pkg-bthree">
			<div class="vbo-pkglist-pkg-cost">
				<span class="vbo-pkglist-pkg-price"><span class="vbo_currency"><?php echo $currencysymb; ?></span> <span class="vbo_price"><?php echo vikbooking::numberFormat($package['cost']); ?></span></span>
				<span class="vbo-pkglist-pkg-priceper"><?php echo implode(', ', $costfor); ?></span>
			</div>
			<div class="vbo-pkglist-pkg-details">
				<a href="<?php echo JRoute::_('index.php?option=com_vikbooking&view=packagedetails&pkgid='.$package['id']); ?>"><?php echo JText::_('VBOPKGMOREDETAILS'); ?></a>
			</div>
		<?php
		if(!empty($package['benefits'])) {
			?>
			<div class="vbo-pkglist-pkg-benefits">
				<?php echo $package['benefits']; ?>
			</div>
			<?php
		}
		?>
		</div>
	</div>
		<?php
	}
?>
</div>
<?php
}