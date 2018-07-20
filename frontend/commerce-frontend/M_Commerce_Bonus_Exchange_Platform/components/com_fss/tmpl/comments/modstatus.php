<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php if (is_array($this->_moderatecounts)): ?>
	<div class="fss_moderate_status">
		<ul>
			<?php  foreach ($this->_moderatecounts as $ident => $count) : ?>
				<li>
					<?php echo $this->handlers[$ident]->GetDesc(); ?>: 
					<b><?php echo $count['count']; ?></b>
- 
					<a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_moderate&ident=' . $ident ); ?>">
						<?php echo JText::_('VIEW_NOW'); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
