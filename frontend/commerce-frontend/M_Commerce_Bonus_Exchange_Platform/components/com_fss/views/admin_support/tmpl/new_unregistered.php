<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle('SUPPORT_ADMIN',"NEW_SUPPORT_TICKET"); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php'); ?>

<?php echo FSS_Helper::PageSubTitle("CREATE_TICKET_FOR_UNREGISTERED_USER"); ?>

<form action="<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&layout=open&admincreate=2'); ?>" method="post" class="form-horizontal form-condensed">

	<div class="control-group">
		<label class="control-label"><?php echo JText::_("EMAIL_ADDRESS"); ?></label>
		<div class="controls">
			<input type="text" name="admin_create_email" class="inputbox" value="<?php echo FSS_Helper::escape(FSS_Input::getString('admin_create_email')); ?>">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label"><?php echo JText::_("NAME"); ?></label>
		<div class="controls">
			<input type="text" name="admin_create_name" class="inputbox" value="<?php echo FSS_Helper::escape(FSS_Input::getString('admin_create_name')); ?>">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label"></label>
		<div class="controls">
			<input class='btn btn-primary' type="submit" id="new_ticket" value="<?php echo JText::_("OPEN_TICKET_FOR_USER"); ?>">
			<a class='btn btn-default' href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support' ); ?>"><?php echo JText::_("CANCEL"); ?></a>
		</div>
	</div>
	
</form>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>
