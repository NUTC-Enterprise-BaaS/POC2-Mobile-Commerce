<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php if (count($this->ticket->user_cc) == 0): ?>
	<?php echo JText::_('NONE_') ?>
<?php else: ?>
	<?php foreach($this->ticket->user_cc as $cc): ?>
		<div class="fss_tag label label-small-close fssTip <?php echo $cc->readonly || $cc->uremail ? 'label-warning' : 'label-success'; ?>"
				title="<?php echo $cc->readonly ? JText::_('READ_ONLY') : JText::_('FULL_ACCESS'); ?>" id="tag_<?php echo $cc->id; ?>">
			<?php if (JFactory::getUser()->id == $this->ticket->user_id): ?>
				<button class="close" onclick="removecc('<?php echo $cc->id; ?>');return false;">&times;</button>
			<?php endif; ?>
			<?php echo $cc->name ? $cc->name : $cc->uremail; ?>
		</div>
	<?php endforeach; ?>
<?php endif; ?>
