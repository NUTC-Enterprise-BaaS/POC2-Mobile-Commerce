<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access to this file
defined('_JEXEC') or die(';)');
JHtml::_('behavior.formvalidation');

// Added for JS toolbar inclusion
if (file_exists(JPATH_SITE . '/components/com_community') and $this->params->get('jomsocial_toolbar')==1)
{
	require_once JPATH_ROOT . '/components/com_community/libraries/toolbar.php';
	$toolbar    = CFactory::getToolbar();
	$tool = CToolbarLibrary::getInstance(); ?>
	<div id="proimport-wrap">
		<div id="community-wrap">
			<?php	echo $tool->getHTML();	?>
		</div>
	</div>
<?php
} //end for JS toolbar inclusion
?>

<div class="<?php echo SA_WRAPPER_CLASS;?> sa-ad-registration">
	<div class="row">
		<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
			<div class="page-header">
				<h2><?php echo JText::_('COM_SOCIALADS_REGISTRATION_USER_REGISTRATION');	?> </h2>
				<?php echo JText::_('COM_SOCIALADS_REGISTRATION_USER_UNREGISTRATION');?>
			</div>
			<form action="" method="post" name="adminForm" class="form-validate form-horizontal" id="adminForm">
				<div>
					<div class="form-group">
						<label class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"  for="user_name">
							<?php echo JText::_( 'COM_SOCIALADS_REGISTRATION_USER_NAME' ); ?>
						</label>
						<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
							<input class="inputbox required validate-name" type="text" name="user_name" id="user_name" maxlength="50" value="" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"  for="user_email">
							<?php echo JText::_( 'COM_SOCIALADS_REGISTRATION_USER_EMAIL' ); ?>
						</label>
						<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
							<input class="inputbox required validate-email" type="text" name="user_email" id="user_email" maxlength="100" value="" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"  for="confirm_user_email">
							<?php echo JText::_( 'COM_SOCIALADS_REGISTRATION_CONFIRM_USER_EMAIL' ); ?>
						</label>
						<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
							<input class="inputbox required validate-email" type="text" name="confirm_user_email" id="confirm_user_email"  maxlength="100" value="" />
						</div>
					</div>
					<div class="form-actions">
						<button class="btn btn-warning" type="button" onclick="submitbutton('registration.cancel');" name="cancel" id="cancel">
							<?php echo JText::_('COM_SOCIALADS_REGISTRATION_BUTTON_CANCEL');?>
						</button>
						<button class="btn btn-success validate" type="submit" onclick="submitbutton('registration.save');">
							<?php echo JText::_('COM_SOCIALADS_REGISTRATION_BUTTON_NEXT'); ?>
						</button>
					</div>
					<input type="hidden" name="option" value="com_socialads" />
					<input type="hidden" name="task" value="registration.save" />
					<input type="hidden" name="Itemid" value="<?php echo $this->input->get('Itemid',0,'INT');?>" />
				</div>
				<?php echo JHtml::_( 'form.token' ); ?>
			</form>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
			<div class="page-header">
				<h2><?php echo JText::_('COM_SOCIALADS_REGISTRATION_LOGIN');	?> </h2>
				<?php echo JText::_('COM_SOCIALADS_REGISTRATION_REGISTER');?>
			</div>
			<a href='<?php
				$msg=JText::_('LOGIN');
				$uri=$this->socialadsbackurl;
				$url=base64_encode($uri);
				echo JRoute::_('index.php?option=com_users&view=login&return='.$url); ?>'>
				<div style="margin-left:auto;margin-right:auto;" class="form-group">
					<input id="LOGIN" class="btn btn-large btn-success validate" type="button" value="<?php echo JText::_('COM_SOCIALADS_REGISTRATION_SIGN_IN'); ?>">
				</div>
			</a>
		</div>
	</div>
</div>
<script>
	Joomla.submitbutton = function(task)
	{
		sa.registration.submitButtonAction(task)
	}
</script>
