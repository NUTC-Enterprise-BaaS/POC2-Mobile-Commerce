<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

$passwd_field = "password";
$users_com = "com_users";
$login_task = "user.login";

?>
	
<?php echo FSS_Helper::PageSubTitle("LOGIN"); ?>

<p><?php echo JText::_("LOG_IN_TO_AN_EXISTING_ACCOUNT"); ?></p>

<form action="<?php echo FSSRoute::_("index.php"); ?>"  method="post" name="com-login" id="com-form-login" class="form-horizontal form-condensed left_login">
	<?php echo FSS_Helper::openPassthrough(); ?>

	<div class="control-group">
		<label class="control-label" for="username"><?php echo JText::_("USERNAME"); ?></label>
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on">
					<span class="icon-user" title="<?php echo JText::_("USERNAME"); ?>"></span>
				</span>
				<input type="text" name="username" id="username" class="input-medium" placeholder="<?php echo JText::_("USERNAME"); ?>">
			</div>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="<?php echo $passwd_field; ?>"><?php echo JText::_("PASSWORD"); ?></label>
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on">
					<span class="icon-lock" title="<?php echo JText::_("PASSWORD"); ?>"></span>
				</span>
				<input type="password" name="<?php echo $passwd_field; ?>" id="<?php echo $passwd_field; ?>" class="input-medium" placeholder="<?php echo JText::_("PASSWORD"); ?>">
			</div>
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
			<label class="checkbox">
				<input type="checkbox" id="remember" name="remember" value="yes"> <?php echo JText::_("REMEMBER_ME"); ?>
			</label>
			<?php endif; ?>
			<button type="submit" class="btn <?php echo FSS_Settings::get('bootstrap_pribtn'); ?>"><?php echo JText::_("LOGIN"); ?></button>
		</div>
	</div>

<?php 
if (isset($this) && property_exists($this, 'return'))
	$return = $this->return;

if (!isset($return))
	$return = ""; 

?>
	<input name="option" value="<?php echo FSS_Helper::escape($users_com); ?>" type="hidden" />
	<input name="task" value="<?php echo FSS_Helper::escape($login_task); ?>" type="hidden" />
	<input name="return" value="<?php echo FSS_Helper::escape($return); ?>" type="hidden" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

<?php
$lost_pass = FSSRoute::_('index.php?option='.$users_com.'&view=reset');
if (FSS_Settings::get('support_custom_lost_password') != "")
	$lost_pass = JRoute::_(FSS_Settings::get('support_custom_lost_password'));

$lost_user = FSSRoute::_('index.php?option='.$users_com.'&view=remind' );
if (FSS_Settings::get('support_custom_lost_username') != "")
	$lost_user = JRoute::_(FSS_Settings::get('support_custom_lost_username'));
?>
<ul>
	<li>
		<a href="<?php echo $lost_pass; ?>">
			<?php echo JText::_("FORGOT_YOUR_PASSWORD"); ?>
		</a>
	</li>
	<li>
		<a href="<?php echo $lost_user ?>">
			<?php echo JText::_("FORGOT_YOUR_USERNAME"); ?>
		</a>
	</li>
</ul>
				 	 				   		