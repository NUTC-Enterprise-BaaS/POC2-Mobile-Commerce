<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
	<?php $ident = -1; $itemid = -1; $count = 0; ?>
	<?php if (is_array($this->_data))
	foreach ($this->_data as $ident => $articles): ?>
		<div class="media">
			<a class="pull-left" href="#">
				<img class="media-object" src="<?php echo JURI::root( true ); ?>/components/com_fsf/assets/images/blank_16.png">
			</a>
			<div class="media-body">
				<h4 class='media-heading'><?php echo $this->handlers[$ident]->GetDesc(); ?></h4>
					
				<?php foreach ($articles as $itemid => $comments): ?>
					
					
					<div class="media">
						<a class="pull-left" href="#">
							<img class="media-object" src="<?php echo JURI::root( true ); ?>/components/com_fsf/assets/images/blank_16.png">
						</a>
						<div class="media-body">
							<h4 class='media-heading'>
								<a href='<?php echo $this->handlers[$ident]->GetItemLink($itemid); ?>'><?php echo $this->handlers[$ident]->GetItemTitle($itemid); ?></a>
							</h4>
										
							<?php if ($comments && count($comments) > 0) foreach ($comments as $this->comment): ?>
								<?php $count++; include $this->tmplpath . DS .'comment.php' ?>
							<?php endforeach; ?>
							
						</div>
					</div>
					
					
				<?php endforeach; ?>
			</div>
		</div>
	<?php endforeach; ?>
	<?php if ($count == 0) : ?>
		<div class='fss_moderate_ident_title'><?php echo JText::_('NO_COMMENTS_FOUND'); ?></div>
	<?php endif; ?>