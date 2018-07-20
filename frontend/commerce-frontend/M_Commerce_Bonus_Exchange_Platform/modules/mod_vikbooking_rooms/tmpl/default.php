<?php
/**------------------------------------------------------------------------
 * mod_vikbooking_rooms - VikBooking
 * ------------------------------------------------------------------------
 * author    Alessio Gaggii - Extensionsforjoomla.com
 * copyright Copyright (C) 2014 extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

// no direct access
defined('_JEXEC') or die;

$currencysymb = $params->get('currency');
$widthroom = $params->get('widthroom');
$showscrollbar = $params->get('scrollbar');
$dottednav = $params->get('dotted');
$arrows = $params->get('arrows');
$autoplayparam = $params->get('autoplay');
$autoplaytime = $params->get('autoplaytime');
$totalpeople = $params->get('shownumbpeople');
$showdetails = $params->get('showdetailsbtn');
$roomdesc = $params->get('showroomdesc');

$document = & JFactory :: getDocument();
if(intval($params->get('loadjq')) == 1 ) {
	JHtml::_('jquery.framework', true, true);
	JHtml::_('script', JURI::root().'modules/mod_vikbooking_rooms/src/jquery.min.js', false, true, false, false);
}
JHtml::_('script', JURI::root().'modules/mod_vikbooking_rooms/src/sly.min.js', false, true, false, false);
JHtml::_('script', JURI::root().'modules/mod_vikbooking_rooms/src/plugins.js', false, true, false, false);
JHtml::_('script', JURI::root().'modules/mod_vikbooking_rooms/src/device.js', false, true, false, false);


$decl="jQuery.noConflict();\n";
$decl.='jQuery(document).ready(function(){
			jQuery(function($){
				\'use strict\';
				(function () {
					var $frame = $(\'#cyclepages\');
					var $wrap  = $frame.parent();
					$frame.sly({
						horizontal: 1,
						itemNav: \'basic\',
						smart: 1,
						activateOn: \'click\',
						mouseDragging: 1,
						touchDragging: 1,
						releaseSwing: 1,
						startAt: 0,
						scrollBar: $wrap.find(\'.scrollbar\'),
						scrollBy: 1,
						pagesBar: $wrap.find(\'.pages\'),
						activatePageOn: \'click\',
						speed: 300,
						elasticBounds: 1,
						easing: \'easeOutExpo\',
						dragHandle: 1,
						dynamicHandle: 1,
						clickBar: 1,
						cycleBy: \'pages\',
						cycleInterval: '.$autoplaytime.',
						pauseOnHover: 1,
						startPaused: '.$autoplayparam.',
						prevPage: $wrap.find(\'.prevPage\'),
						nextPage: $wrap.find(\'.nextPage\')
					});
					$wrap.find(\'.pause\').on(\'click\', function () {
					$frame.sly(\'pause\');
				});
			$wrap.find(\'.resume\').on(\'click\', function () {
				$frame.sly(\'resume\');
			});
			$wrap.find(\'.toggle\').on(\'click\', function () {
				$frame.sly(\'toggle\');
			});
		}());
		});
		});';
$document->addScriptDeclaration($decl);
$rooms_css = ".frame ul.vbmodrooms > li {
	width:".$widthroom.";}";
$document->addStyleDeclaration($rooms_css);

?>
<div class="vbmodroomscontainer wrap">
	<!-- Scrollbar -->
	<?php if($showscrollbar == 0) { ?>
	<div class="scrollbar">
		<div class="handle" style="-webkit-transform: translateZ(0px) translateX(0px); width: 190px;">
			<div class="mousearea"></div>
		</div>
	</div>
	<?php } ?>
	<div class="frame" id="cyclepages" style="overflow: hidden;">
		<ul class="vbmodrooms" style="-webkit-transform: translateZ(0px); width: 6840px;">
			<?php
				foreach($rooms as $c) {
			?>
			<li>
				<div class="vbmodroomsboxdiv">	
					<?php
					if(!empty($c['img'])) {
					?>
						<img src="<?php echo JURI::root(); ?>components/com_vikbooking/resources/uploads/<?php echo $c['img']; ?>" class="vbmodroomsimg"/>
					<?php
					}
					?>
					<div class="vbinf">
						<div class="vbmodrooms-divblock">
					        <span class="vbmodroomsname"><?php echo $c['name']; ?></span>
					        <?php
							if($totalpeople == 1) {
							?>
					        	<span class="vbmodroomsbeds"><?php echo $c['totpeople']; ?> <?php echo JText::_('VBMODROOMSBEDS'); ?></span>	
					        <?php }
							?>	
						</div>
						<?php
						if($showcatname) {
						?>
							<span class="vbmodroomscat"><?php echo $c['catname']; ?></span>
						<?php
						}
						?>		
						<?php
						if($roomdesc) {
						?>	
							<span class="vbmodroomsdesc"><?php echo $c['smalldesc']; ?></span>		
						<?php
						}
						?>	
						<?php
						if($c['cost'] > 0) {
						?>
							<div class="vbmodroomsroomcost">
								<span class="vbo_currency"><?php echo $currencysymb; ?></span> 
								<span class="vbo_price"><?php echo $c['cost']; ?></span>
							<?php
							if(array_key_exists('custpricetxt', $c) && !empty($c['custpricetxt'])) {
							?>
								<span class="vbmodroomslabelcost"><?php echo $c['custpricetxt']; ?></span>
							<?php
							}
							?>
							</div>
						<?php
						}
						?>
			        </div>
					<?php
							if($showdetails == 1) {
							?>
					<span class="vbmodroomsview"><a href="<?php echo JRoute::_('index.php?option=com_vikbooking&view=roomdetails&roomid='.$c['id'].'&Itemid='.$params->get('itemid')); ?>"><?php echo JText::_('VBMODROOMSCONTINUE'); ?></a></span>
			        <?php } ?>
			        
				</div>	
			</li>
		<?php
		} ?>
		</ul>
	</div>
	<!-- Dotted Navigation -->
	<?php if($dottednav == 1) { ?>
		<ul class="pages">
		<?php
		foreach($rooms as $c) {
		?>
			<li></li>
		<?php } ?>
		</ul>
	<?php } ?>
	<!-- Arrow Navigation -->
<?php if($arrows == 1) { ?>
	<div class="vbmodrooms-controls center">
		<button class="btn prevPage disabled" disabled="" />
		<button class="btn nextPage" />
	</div>
<?php
	}
?>
</div>