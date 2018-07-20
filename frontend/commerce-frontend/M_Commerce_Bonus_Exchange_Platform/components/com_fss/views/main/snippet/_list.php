<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
	<div class="media">
		<div class="pull-left">
			<?php if ($item['icon'] && $this->hideicons == 0) : ?>
				<a href='<?php echo $this->getLink($item); ?>' class='pull-left' target='<?php echo $item['target']; ?>'>
					<img src='<?php echo JURI::base(); ?>images/fss/menu/<?php echo FSS_Helper::escape($item['icon']); ?>' width="<?php echo (int)$this->imagewidth; ?>" height="<?php echo (int)$this->imageheight; ?>" />
				</a>
			<?php endif; ?>
		</div>
		<div class="media-body">
			<h4 class="media-heading">
				<a href='<?php echo $this->getLink($item); ?>' target='<?php echo $item['target']; ?>'>
					<?php echo JText::_($item['title']); ?>
				</a>
			</h4>
			<?php if ($item['description'] && $this->show_desc): ?>
				<?php 
					$lang = JFactory::getLanguage();
					if ($lang->hasKey($item['description']))
					{
						echo JText::_($item['description']); 
					} else {
						echo $item['description']; 
					}
				?>
			<?php endif; ?>
		</div>
	</div>
