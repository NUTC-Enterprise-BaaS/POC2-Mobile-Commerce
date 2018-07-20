<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php if (empty($this->_moderatecounts)) $this->_moderatecounts = array(); 
	if (count($this->_moderatecounts) > 0)
		foreach ($this->_moderatecounts as $ident => $count) : ?>
			<h4 class="margin-mini">
				<a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_moderate&ident=' . $ident ); ?>">
					<?php echo $this->handlers[$ident]->GetDesc(); ?> (<?php echo $count['count']; ?>)
				</a>
			</h4>
<?php endforeach; ?>
<?php if (count($this->_moderatecounts) == 0): ?>
			<h4 class="margin-mini">
				<?php echo JText::_("NO_COMMENTS_FOR_MOD"); ?>
			</h4>
<?php endif; ?>
