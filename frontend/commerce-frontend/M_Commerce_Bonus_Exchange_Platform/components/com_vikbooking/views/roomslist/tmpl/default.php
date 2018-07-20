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

$rooms=$this->rooms;
$category=$this->category;
$vbo_tn=$this->vbo_tn;
$navig=$this->navig;

$currencysymb = vikbooking::getCurrencySymb();

if(is_array($category)) {
	?>
	<h3 class="vbclistheadt"><?php echo $category['name']; ?></h3>
	<?php
	if(strlen($category['descr']) > 0) {
		?>
		<div class="vbcatdescr">
			<?php echo $category['descr']; ?>
		</div>
		<?php
	}
}else {
	echo vikbooking::getFullFrontTitle();
}

?>
<div class="vblistcontainer">
<ul class="vblist">
<?php
foreach($rooms as $r) {
	$carats = vikbooking::getRoomCaratOriz($r['idcarat'], $vbo_tn);
	//BEGIN: Joomla Content Plugins Rendering
	JPluginHelper::importPlugin('content');
	$myItem =JTable::getInstance('content');
	$dispatcher =JDispatcher::getInstance();
	$myItem->text = $r['smalldesc'];
	$dispatcher->trigger('onContentPrepare', array('com_vikbooking.roomslist', &$myItem, &$params, 0));
	$r['smalldesc'] = $myItem->text;
	//END: Joomla Content Plugins Rendering
	?>
	<li class="room_result">
	<div class="vblistroomblock">
	<?php
	if(!empty($r['img'])) {
	?>
		<div class="vbimglistdiv">
			<img src="<?php echo JURI::root(); ?>components/com_vikbooking/resources/uploads/<?php echo $r['img']; ?>" alt="<?php echo $r['name']; ?>" class="vblistimg"/>
		</div>
	<?php
	}
	?>
		<div class="vbdescrlistdiv">
			<span class="vbrowcname"><?php echo $r['name']; ?></span>
			<span class="vblistroomcat"><?php echo vikbooking::sayCategory($r['idcat'], $vbo_tn); ?></span>
			<div class="vbrowcdescr"><?php echo $r['smalldesc']; ?></div>
		</div>
	<?php 
	if (!empty($carats)) {
		?>
		<div class="roomlist_carats">
		<?php echo $carats; ?>
		</div>
		<?php
	}
	?>
	</div>

		<div class="vbcontdivtot">
		<div class="vbdivtot">
		<div class="vbdivtotinline">
			<div class="vbsrowprice">
			<div class="vbrowroomcapacity">
		<?php
		for($i = 1; $i <= $r['toadult']; $i++) {
			?>
			<img src="<?php echo JURI::root(); ?>components/com_vikbooking/resources/images/person.png"/>
			<?php
		}
		?>
			</div>
		<?php
		$custprice = vikbooking::getRoomParam('custprice', $r['params']);
		$custpricetxt = vikbooking::getRoomParam('custpricetxt', $r['params']);
		$custpricetxt = empty($custpricetxt) ? JText::_('VBLISTPERNIGHT') : JText::_($custpricetxt);
		if($r['cost'] > 0 || !empty($custprice)) {
		?>
		<div class="vbsrowpricediv">
			<span class="room_cost"><span class="vbo_currency"><?php echo $currencysymb; ?></span> <span class="vbo_price"><?php echo (!empty($custprice) ? vikbooking::numberFormat($custprice) : vikbooking::numberFormat($r['cost'])); ?></span></span>
			<span class="vbliststartfrom"><?php echo $custpricetxt; ?></span>
		</div>
		<?php
		}
		?>
		
			</div>
			<div class="vbselectordiv">
				<div class="vbselectr"><a href="<?php echo JRoute::_('index.php?option=com_vikbooking&view=roomdetails&roomid='.$r['id']); ?>"><?php echo JText::_('VBSEARCHRESDETAILS'); ?></a></div>
			</div>
			
		</div>
		</div>
		</div>
	</li>
	<?php
}
?>
</ul>
</div>

<?php
//pagination
if(strlen($navig) > 0) {
	?>
	<div class="pagination">
	<?php
	echo $navig;
	?>
	</div>
	<?php
}
?>