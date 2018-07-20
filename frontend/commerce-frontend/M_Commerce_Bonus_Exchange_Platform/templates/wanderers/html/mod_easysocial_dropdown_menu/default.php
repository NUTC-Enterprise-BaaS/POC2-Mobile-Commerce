<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined( '_JEXEC' ) or die( 'Unauthorized Access' );
$useSSL = $params->get('use_secure_url', false) ? 1 : 0;
?>
<div class="theme-helper-user btn-group btn-group-sm pull-right">

	<?php if (!$my->guest) { ?>
	<div class="btn-group btn-group-sm">
		<a href="javascript:void(0);" class="btn btn-default btn-user dropdown-toggle" 
			data-module-dropdown-menu-wrapper
			data-popbox=""
			data-popbox-id="fd"
			data-popbox-component="es"
			data-popbox-type="toolbar-dropdown"
			data-popbox-toggle="click"
			data-popbox-target=".mod-popbox-dropdown-user"
			data-popbox-position="bottom-right"
			data-popbox-collision="<?php echo $params->get('popbox_collision', 'flip'); ?>"
			data-popbox-offset="<?php echo $params->get('popbox_offset', 10); ?>"
		>
			<img class="avatar" src="<?php echo $my->getAvatar();?>" alt="<?php echo $modules->html( 'string.escape' , $my->getName() );?>" style="width:20px" />
			<span class="visible-lg visible-md">
				<?php echo JText::_( 'MOD_EASYSOCIAL_DROPDOWN_MENU_HI' );?>, <b><?php echo $my->getName();?></b>
				<i class="fa fa-angle-down"></i>
			</span>
		</a>
		<div style="display:none;" data-module-dropdown-menu class="mod-popbox-dropdown-user">
			<ul class="list-unstyled">

				<?php if( $params->get( 'show_my_profile' , true ) ){ ?>
				<li>
					<a href="<?php echo $my->getPermalink();?>">
						<?php echo JText::_( 'MOD_EASYSOCIAL_DROPDOWN_MENU_MY_PROFILE' );?>
					</a>
				</li>
				<?php } ?>

				<?php if( $params->get( 'show_account_settings' , true ) ){ ?>
				<li>
					<a href="<?php echo FRoute::profile( array( 'layout' => 'edit' ) );?>">
						<?php echo JText::_( 'MOD_EASYSOCIAL_DROPDOWN_MENU_ACCOUNT_SETTINGS' );?>
					</a>
				</li>
				<?php } ?>

				<?php if( $items ){ ?>
					<?php foreach( $items as $item ){ ?>
					<li class="menu-<?php echo $item->id;?>">
						<a href="<?php echo $item->flink;?>"><?php echo $item->title;?></a>
					</li>
					<?php } ?>
				<?php } ?>	
			</ul>
		</div>
		
	</div>
	<?php } ?>

	<?php if ($params->get('show_sign_out', true) && !$my->guest) { ?>
	<a href="javascript:void(0);" onclick="document.getElementById('es-dropdown-logout-form').submit();" class="btn btn-default btn-logout"><i class="fa fa-power-off"></i></a>
	<form class="logout-form" id="es-dropdown-logout-form">
		<input type="hidden" name="return" value="<?php echo $logoutReturn;?>" />
		<input type="hidden" name="option" value="com_easysocial" />
		<input type="hidden" name="controller" value="account" />
		<input type="hidden" name="task" value="logout" />
		<?php echo $modules->html( 'form.token' ); ?>
	</form>
	<?php } ?>
	
	<?php if ($my->guest) { ?>
	
	<div class="dropdown btn-group btn-group-sm pull-right">
    	<!-- <a class="btn btn-default btn-login dropdown-toggle" role="button" data-toggle="dropdown" href="#"><i class="fa fa-lock"></i></a> -->

		<a href="javascript:void(0);" class="btn btn-default btn-login"
            data-module-dropdown-login-wrapper
            data-popbox=""
            data-popbox-id="fd"
            data-popbox-component="es"
            data-popbox-type="toolbar-dropdown"
            data-popbox-toggle="click"
            data-popbox-target=".mod-popbox-dropdown"
            data-popbox-position="bottom-right"
            data-popbox-collision="<?php echo $params->get('popbox_collision', 'flip'); ?>"
            data-popbox-offset="10"
        >
			<i class="fa fa-lock"></i>
		</a>

		<div style="display:none;" data-module-dropdown-login class="mod-popbox-dropdown">
	        <div class="toolbar-menu-login" role="menu" aria-labelledby="dLabel">
		        <form action="<?php echo JRoute::_('index.php');?>" method="post" id="login-form" class="">
		        	<div class="form-group">
		        		<input type="text" autocomplete="off" size="18" class="form-control input-sm" name="username" id="es-username" tabindex="101" placeholder="Username">
		        	</div>
		        	<div class="form-group">
		        		<input type="password" autocomplete="off" name="password" class="form-control input-sm" id="es-password" tabindex="102" placeholder="Password">
		        	</div>
					
					<div class="checkbox" <?php if (!$showRememberMe) { ?>style="display: none;"<?php } ?>>
				        <label>
				        <input type="checkbox" value="yes" class="pull-left mr-5" name="remember" id="remember" tabindex="104" <?php if ($checkRememberMe) { ?>checked="checked"<?php } ?> />
				        <?php echo JText::_('COM_EASYSOCIAL_TOOLBAR_REMEMBER_ME');?>
				        </label>
				     </div>

					<input type="submit" class="btn btn-primary" name="Submit" value="Login" tabindex="104" style="display:block;width:100%;">
					

					<?php if ($config->get('oauth.facebook.registration.enabled') && $facebook) { ?>
					<li class="item-social text-center">
						<?php echo $facebook->getLoginButton(FRoute::registration(array('layout' => 'oauthDialog' , 'client' => 'facebook', 'external' => true), false)); ?>
					</li>
					<?php } ?>


					<div class="toolbar-menu-login__footer">
						<ul class="unstyled">
							<?php if ($params->get('register_button', true) && $config->get('registrations.enabled') && (!$config->get('general.site.lockdown.enabled') || ($config->get('general.site.lockdown.enabled') && $config->get('general.site.lockdown.registration')))) { ?>
							<li>
								<a href="<?php echo FRoute::registration();?>" class="pull-" tabindex="106"><?php echo JText::_( 'COM_EASYSOCIAL_REGISTRATION_CREATE_NEW_ACCOUNT' );?></a>
							</li>
							<?php } ?>
							
							<?php if (!$config->get('registrations.emailasusername')) { ?>
							<li>
								<a href="<?php echo FRoute::account( array( 'layout' => 'forgetUsername' ) );?>" class="pull-" tabindex="107"><?php echo JText::_( 'COM_EASYSOCIAL_REGISTRATION_FORGOT_USERNAME' );?></a>
							</li>
							<?php } ?>
							<li>
								<i class="fa fa-help"></i>  <a href="<?php echo FRoute::account( array( 'layout' => 'forgetPassword' ) );?>" class="pull-" tabindex="108"><?php echo JText::_( 'COM_EASYSOCIAL_REGISTRATION_FORGOT_PASSWORD' );?></a>
							</li>
						</ul>
					</div>

					<input type="hidden" name="option" value="com_easysocial" />
					<input type="hidden" name="controller" value="account" />
					<input type="hidden" name="task" value="login" />
					<input type="hidden" name="return" value="<?php echo $loginReturn;?>" />
					<?php echo $modules->html('form.token');?>
				</form>
	        </div>
		</div>

        
	</div>
	<?php } ?>

</div>