<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php if ($this->ticket->isLocked()): ?>
	<div class="alert alert-warning">
		<?php echo JText::sprintf("TICKET_LOCKED_INFO",$this->co_user->name, $this->co_user->email); ?>
	</div>
<?php endif; ?>

<?php if ($this->merge): ?>
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_merge_notice.php'); ?>
<?php endif; ?>

<?php if (is_array($this->merged) && count($this->merged) > 0): ?>
	<div class="alert alert-warning">
		<p><?php echo JText::_('TICKET_MERGED_NOTICE'); ?></p>
		<ul>
			<?php foreach ($this->merged as $mt): ?>
				<li>
					<a href="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $mt->id . "&no_redirect=1&Itemid=" . FSS_Input::getInt('Itemid'), false); ?>">
						<?php echo $mt->reference; ?> - <?php echo $mt->title; ?>
					</a>
				</li>	
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>

<?php if ($this->ticket->merged > 0): ?>
	<div class="alert alert-error">
		<p><?php echo JText::_('TICKET_MERGED_NOTICE_INTO'); ?></p>
		<ul>
			<?php 
			$ticket_m = new SupportTicket();
			if ($ticket_m->load($this->ticket->merged)) : ?>
				<li>
					<a href="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $ticket_m->id . "&Itemid=" . FSS_Input::getInt('Itemid'), false); ?>">
						<?php echo $ticket_m->reference; ?> - <?php echo $ticket_m->title; ?>
					</a>
				</li>	
			<?php endif; ?>
		</ul>
	</div>
<?php endif; ?>
