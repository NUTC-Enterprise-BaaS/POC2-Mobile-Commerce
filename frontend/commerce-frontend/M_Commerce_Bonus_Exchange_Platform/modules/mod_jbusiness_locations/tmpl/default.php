<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );


?>
<div id="businesslocations" class="businesslocations echo $moduleclass_sfx; ?>" >
	<div class="row-fluid ">
		<?php $k = 0;?>
		<?php foreach ($items as $item) {
			$k= $k+1;
		?>
			<div class="locations-content" style="width:<?php echo 100/$itemsPerRow ?>%">
				<ul>
				<?php 
					$c = 0;
					foreach($item as $location){ 
						$c++;
						if($c==1){?>
							<li class="location-region"><a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=search&regionSearch='.$location)?>"><?php echo $location?></a></li>
						<?php }else{?>
							<li class="location-city"><a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=search&citySearch='.$location)?>"><?php echo $location?></a></li>
						<?php } ?>
					<?php } ?>
				</ul>
			</div>
			
			<?php if($k%$itemsPerRow==0){?>
			</div>
			<div class="row-fluid">
		<?php }?>
		
	<?php 
		}
	?>
	</div>
</div>

