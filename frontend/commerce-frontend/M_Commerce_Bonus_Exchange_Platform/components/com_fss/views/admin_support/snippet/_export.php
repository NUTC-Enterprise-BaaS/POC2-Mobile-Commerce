<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php if (FSS_Permission::auth("core.create", "com_fss.kb")): ?>
	<div style="display: none;">
		<form id="ticket_to_kb" action="<?php echo JRoute::_("index.php?option=com_fss&view=admin_content&type=kb&what=new"); ?>" method="POST" target="_blank">
			<input name="option" value="com_fss" />
			<input name="view" value="admin_content" />
			<input name="type" value="kb" />
			<input name="what" value="new" />
			<input name="title" value="<?php echo FSS_Helper::escape($this->ticket->title); ?>" />
			<textarea name="body"><?php 
			foreach ($this->ticket->messages as $message)
			{
				if ($message->admin == 3) continue; 
				$msg = FSS_Helper::ParseBBCode($message->body, $message);
				echo FSS_Helper::escape($msg) . "\n";
				//echo "<hr />\n";
			}
			?></textarea>
		</form>
	</div>
<?php endif; ?>

<?php if (FSS_Permission::auth("core.create", "com_fss.faq")): ?>
	<div style="display: none;">
		<form id="ticket_to_faq" action="<?php echo JRoute::_("index.php?option=com_fss&view=admin_content&type=faqs&what=new"); ?>" method="POST" target="_blank">
			<input name="option" value="com_fss" />
			<input name="view" value="admin_content" />
			<input name="type" value="faqs" />
			<input name="what" value="new" />
			<input name="question" value="<?php echo FSS_Helper::escape($this->ticket->title); ?>" />
			<textarea name="answer"><?php 
			foreach ($this->ticket->messages as $message)
			{
				if ($message->admin == 3) continue; 
				$msg = FSS_Helper::ParseBBCode($message->body, $message);
				echo FSS_Helper::escape($msg) . "\n";
				//echo "<hr />\n";
			}
			?></textarea>
		</form>
	</div>
<?php endif; ?>
