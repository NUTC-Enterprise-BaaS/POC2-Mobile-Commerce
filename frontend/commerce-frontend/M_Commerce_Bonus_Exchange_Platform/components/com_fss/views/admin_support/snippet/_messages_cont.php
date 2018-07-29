<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

?>

<?php echo FSS_Helper::PageSubTitle("MESSAGES"); ?>

<?php if (!$this->print): ?>
	<p>
		<div class="pull-right">	
			<a class="btn btn-default fssTip audit_log" href="#" onclick='jQuery(".fss_support_msg_audit").toggle();return false;' title="<?php echo JText::_("AUDIT_LOG"); ?>">
				<i class="icon-database"></i> <span class='hidden-phone'><?php echo JText::_("AUDIT_LOG"); ?></span>
			</a>

			<a class="btn btn-default fssTip refresh" href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $this->ticket->id ); ?>' title="<?php echo JText::_("REFRESH"); ?>">
				<i class="icon-refresh"></i> <span class='hidden-phone'><?php echo JText::_("REFRESH"); ?></span>
			</a>

			<a class="btn btn-default fssTip reverse_order" title="<?php echo JText::_('REVERSE_MESSAGE_ORDER'); ?>" href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $this->ticket->id . "&sort=" . (1-FSS_Input::getInt('sort'))  ); ?>">
				<i class="icon-calendar"></i>
			</a>
		</div>

		<?php if (!$this->ticket->isLocked() && $this->can_Reply()): ?>
			<a class="btn btn-primary post_reply" href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=reply&ticketid=' . $this->ticket->id ); ?>'>
				<i class="icon-redo"></i> <span class='visible-phone'><?php echo JText::_("POST_REPLY_LINK_SHORT"); ?></span><span class='hidden-phone'><?php echo JText::_("POST_REPLY_LINK"); ?></span>
			</a>
		<?php endif; ?>

		<a class="btn btn-default post_private" href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=reply&type=private&ticketid=' . $this->ticket->id ); ?>'>
			<i class="icon-key"></i> <span class='visible-phone'><?php echo JText::_("ADD_PRIVATE_COMMENT_SHORT"); ?></span><span class='hidden-phone'><?php echo JText::_("ADD_PRIVATE_COMMENT"); ?></span>
		</a>

		<?php echo FSS_GUIPlugins::output("adminTicketReplyBar", array('ticket'=> $this->ticket)); ?>

	</p>
<?php endif; ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_messages.php'); ?>
<?php if (!$this->print) include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_messages_key.php'); ?>

<?php if (count($this->ticket->attach) > 0) : ?>
	<?php echo FSS_Helper::PageSubTitle("ATTACHEMNTS"); ?>
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_attachments.php'); ?>
<?php endif; ?>
