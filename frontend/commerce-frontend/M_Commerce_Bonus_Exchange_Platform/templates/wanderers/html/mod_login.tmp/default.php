<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_SITE . '/components/com_users/helpers/route.php';

JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');

?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" class="form-inline">
	<div class="panel-body">
		<div class="userdata">
			<div id="form-login-username" class="control-group">
				<div class="controls">
					<?php if (!$params->get('usetext')) : ?>
						<div class="input-prepend">
							<input id="modlgn-username" type="text" name="username" class="input-small" tabindex="0" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" />
						</div>
					<?php else: ?>
						<input id="modlgn-username" type="text" name="username" class="input-small" tabindex="0" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" />
					<?php endif; ?>
				</div>
			</div>
			<div id="form-login-password" class="control-group">
				<div class="controls">
					<?php if (!$params->get('usetext')) : ?>
						<div class="input-prepend">
							<input id="modlgn-passwd" type="password" name="password" class="input-small" tabindex="0" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
						</div>
					<?php else: ?>
						<input id="modlgn-passwd" type="password" name="password" class="input-small" tabindex="0" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
					<?php endif; ?>
				</div>
			</div>
			<?php if (count($twofactormethods) > 1): ?>
			<div id="form-login-secretkey" class="control-group">
				<div class="controls">
					<?php if (!$params->get('usetext')) : ?>
						<div class="input-prepend input-append">
							<span class="btn width-auto hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY_HELP'); ?>">
								<span class="icon-help"></span>
							</span>
						</div>
					<?php else: ?>
						<input id="modlgn-secretkey" autocomplete="off" type="text" name="secretkey" class="input-small" tabindex="0" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY') ?>" />
						<span class="btn width-auto hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY_HELP'); ?>">
							<span class="icon-help"></span>
						</span>
					<?php endif; ?>

				</div>
			</div>
			<?php endif; ?>
			<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
				
			<div id="form-login-remember" class="checkbox">
			<label for="modlgn-remember" class="control-label">
				<input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/> <?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?></label> 
			</div>
			<?php endif; ?>
			<div id="form-login-submit" class="control-group">
				<div class="controls">
					<button type="submit" tabindex="0" name="Submit" class="btn btn-primary"><?php echo JText::_('JLOGIN') ?></button>
				</div>
			</div>
		</div>

		<div class="user-account">
			<?php
				$usersConfig = JComponentHelper::getParams('com_users'); ?>
				<ul class="unstyled">
				<?php if ($usersConfig->get('allowUserRegistration')) : ?>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration&Itemid=' . UsersHelperRoute::getRegistrationRoute()); ?>">
						<?php echo JText::_('MOD_LOGIN_REGISTER'); ?> <span class="icon-arrow-right"></span></a>
					</li>
				<?php endif; ?>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind&Itemid=' . UsersHelperRoute::getRemindRoute()); ?>">
						<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset&Itemid=' . UsersHelperRoute::getResetRoute()); ?>">
						<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
					</li>
				</ul>
			<input type="hidden" name="option" value="com_users" />
			<input type="hidden" name="task" value="user.login" />
			<input type="hidden" name="return" value="<?php echo $return; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>

		<?php if ($params->get('posttext')) : ?>
			<div class="posttext">
				<p><?php echo $params->get('posttext'); ?></p>
			</div>
		<?php endif; ?>
	</div>
</form>
