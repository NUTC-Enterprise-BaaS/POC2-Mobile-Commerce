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
<?php echo FSS_Helper::PageTitle("SUPPORT","NEW_SUPPORT_TICKET"); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_openheader.php'); ?>

<?php if ($this->type == 0) : ?>
	<?php if (!FSS_Settings::get('support_allow_unreg')): ?>
		<div class="alert alert-info"><?php echo JText::_("YOU_MUST_BE_LOGGED_IN_TO_CREATE_A_SUPPORT_TICKET"); ?></div>
	<?php endif; ?>
<?php elseif ($this->type == 1) : ?>
	<div class="alert alert-error"><?php echo JText::_("THIS_EMAIL_ADDRESS_IS_ALREADY_IN_USE_PLEASE_LOG_INTO_YOUR_ACCOUNT_BELOW"); ?></div>
<?php elseif ($this->type == 3) : ?>
	<div class="alert alert-error"><?php echo JText::_("YOU_HAVE_ENTERED_AN_INVALID_EMAIL_ADDRESS_PLEASE_ENTER_A_VAILD_ONE"); ?></div>
<?php endif; ?>

<?php FSS_Helper::HelpText("support_open_login_header"); ?>

<?php if (FSS_Settings::get('support_no_logon') == 0): ?>
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_login_form.php'); ?>
<?php endif; ?>

<?php if (FSS_Settings::get('support_no_register') != 1): ?>
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_register_form.php'); ?>
<?php endif; ?>

<?php if (FSS_Settings::get('support_allow_unreg') == 1): ?>
	<?php echo FSS_Helper::PageSubTitle("CREATE_WITHOUT_ACCOUNT"); ?>

	<p><?php echo JText::_("YOU_WILL_BE_ABLE_TO_ACCESS_YOUR_SUPPORT_TICKET_USING_THE_TICKET_REFERENCE_EMAIL_ADDRESS_AND_PASSWORD_PROVIDED"); ?></p>

	<form action="<?php echo FSSRoute::_("index.php?option=com_fss&view=ticket&layout=open&what=without"); ?>"  method="post" name="uregform" id="uregform" class="form-horizontal form-condensed">
		<?php echo FSS_Helper::openPassthrough(); ?>

		<div class="control-group">
			<label class="control-label" for="email"><?php echo JText::_("EMAIL_ADDRESS"); ?></label>
				<div class="controls">
				<input type="text" id="email" name="email" placeholder="<?php echo JText::_("EMAIL_ADDRESS"); ?>">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="name"><?php echo JText::_("NAME"); ?></label>
			<div class="controls">
				<input type="text" id="name" name="name" placeholder="<?php echo JText::_("NAME"); ?>">
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn btn-primary"><?php echo JText::_("CREATE_TICKET"); ?></button>
			</div>
		</div>

	</form>
<?php endif; ?>

<?php echo FSS_Helper::PageStyleEnd(); ?>