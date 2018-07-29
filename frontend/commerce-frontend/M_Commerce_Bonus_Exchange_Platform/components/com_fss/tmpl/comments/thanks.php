<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class='fss_comment alert alert-success' id="fss_comment_<?php echo $this->comment['id'];?>">
	<?php if ($this->handler->short_thanks): ?>
		<?php echo JText::_("THANKS_FOR_YOUR_COMMENT_SHORT"); ?>
	<?php else: ?>
		<?php echo JText::sprintf("THANKS_FOR_YOUR_COMMENT",$this->handler->descriptions); ?>
	<?php endif; ?>
</div>
<div class='fss_clear'></div>