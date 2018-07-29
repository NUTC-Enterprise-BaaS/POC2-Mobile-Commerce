<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php if (count($this->ticket->tags) == 0): ?>
	<?php echo JText::_('NONE_') ?>
<?php else: ?>
	<?php foreach($this->ticket->tags as $tag): ?>
		<div class="fss_tag label label-info" id="tag_<?php echo FSS_Helper::escape($tag); ?>">
			<button class="close" onclick="tag_remove('<?php echo FSS_Helper::escape($tag); ?>');return false;">&times;</button>
			<?php echo $tag; ?>
		</div>
	<?php endforeach; ?>
<?php endif; ?>
