<?php
/**
 * @package		Login Register module for joomla
 * @subpackage  mod_loginregister
 * @author		www.joomdev.com
 * @author		Created on March 2016
 * @copyright	Copyright (C) 2009 - 2016 www.joomdev.com. All rights reserved.
 * @license		GNU GPL2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');
$type = 'mootools-core.js';
JHTML::_('behavior.modal');
JHtml::_('behavior.framework', $type);
$document	 = JFactory::getDocument();
$itemId = JFactory::getApplication()->getMenu()->getActive()->id;
$document->addScriptDeclaration('
        var itemId = "'.$itemId .'";
');
$error		 =	'';
$view   	 = (isset($_REQUEST['openview']) && !empty($_REQUEST['openview'])) ? $_REQUEST['openview'] : $params->get('view');
//$document->addScript(JURI::root() .'modules/mod_registerlogin/tmpl/assets/bootstrap.min.js');
$document->addScript(JURI::root() .'modules/mod_registerlogin/tmpl/assets/jquery.validate.js');
if($params->get('ajax_registration')){
	$document->addScript(JURI::root() .'modules/mod_registerlogin/tmpl/assets/registerloginajax.js');
}else{
	$document->addScript(JURI::root() .'modules/mod_registerlogin/tmpl/assets/registerlogin.js');
}
$usersConfig = JComponentHelper::getParams( 'com_users' );
// Register API keys at https://www.google.com/recaptcha/admin
//$siteKey = '6Lfnsh4TAAAAANQrnJ1mg-g8o-R3Ws1wlitO_CRA';
//$secret = '6Lfnsh4TAAAAAEGJNvzAlaGzLP1TL8Liy4uUaZAm';
$siteKey = $params->get('sitekey');
$secret = $params->get('secretkey');

// reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
$lang = 'tw';

 ?>
<link href="<?php echo JURI::root() .'modules/mod_registerlogin/tmpl/assets/registerlogin.css' ?>"  type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang; ?>"></script>
<div style="margin: 0px auto;">
<div id="error_message">
	<?php if($errorMessage){ ?>
		<div class="alert alert-error"><a data-dismiss="alert" class="close">X</a><div><p><?php echo $errorMessage; ?></p></div></div>
	<?php } ?>
</div><h2>
 <ul style="padding-left: 1%;" class="nav nav-pills">
  <li class="padtxt"><input style="display:none" class="view_" type="radio" value="1" <?php echo (isset($view) && $view  == 1) ? 'checked="checked"' : ''; ?> name="view" id="login_view" /><s></s></li>
  <li class="padtxt"><label class="showtxt" for="login_view"><?php echo JText::_('MOD_REGISTERLOGIN_LOGINLEBEL'); ?></label></li>
  <li class="padtxt"><input  style="display:none" class="view_"  <?php echo (isset($view ) && $view  == 2) ? 'checked="checked"' : '' ?> type="radio" value="2" name="view" id="register_view" /><s></s></li>
  <li class="padtxt"><label class="showtxt" for="register_view"><?php echo JText::_('MOD_REGISTERLOGIN_REGISTERLEBEL'); ?></label></li>
</ul></h2>
<div class="login_form" id="login_form" style="<?php echo (isset($view ) && $view  == 1) ? 'display : block' : 'display : none' ?>">
	<?php //echo (isset($loginResponse) && !empty($loginResponse)) ? $loginResponse : ''; ?>
	<form action="" method="post" id="login-form" name="josForm" class="form-validate form-horizontal">
		<div class="control-group">
			<?php if ($params->get('usetext')) : ?>
				<div>
					<span class="text"><label id="namemsg" for="username"><?php echo JText::_('COM_USERS_LOGIN_USERNAME_LABEL'); ?>*</label></span>
				</div>
			<?php endif; ?>
			<div>
				<div class="input-prepend">
					<span class="add-on">
						<span class="icon-user tip" title="User Name"></span>
					</span>
					<input id="modlgn-username" type="text" name="username" class="input-medium required" value="<?php echo (isset($_REQUEST['username']) && !empty($_REQUEST['username'])) ? $_REQUEST['username'] : ''; ?>" tabindex="0" size="18" placeholder="帳號(必填)" />
				</div>
			</div>
		</div>
		<div class="control-group">
			<?php if ($params->get('usetext')) : ?>
				<div>
					<span class="text"><label id="namemsg" for="password"><?php echo JText::_('COM_USERS_PROFILE_PASSWORD1_LABEL'); ?>*</label></span>
				</div>
			<?php endif; ?>
			<div>
				<div class="input-prepend">
					<span class="add-on">
						<span class="icon-lock tip" title="<?php echo JText::_('COM_USERS_PROFILE_PASSWORD1_LABEL') ?>"></span>
					</span>
					<input id="modlgn-passwd"  value="<?php echo (isset($_REQUEST['password']) && !empty($_REQUEST['password'])) ? $_REQUEST['password'] : ''; ?>" type="password" name="password" class="input-medium required" tabindex="0" size="18" placeholder="密碼(必填)" />
				</div>
			</div>
		</div>
		<div class="control-group">
			<div>
				<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
				<div id="form-login-remember" class="control-group checkbox">
					<label for="modlgn-remember"><input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/><?php echo JText::_('COM_USERS_LOGIN_REMEMBER_ME') ?></label>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<div id="form-login-submit" class="control-group">
			<div>
				<input type="hidden" value="login" name="module<?php echo $module->id; ?>">
				<button style="width: 300px;background-color: #fff" type="submit" tabindex="0" id="submit" name="Submit" class="btn btn-primary btn"><?php echo JText::_('MOD_REGISTERLOGIN_JLOGIN') ?></button><div id="laodingdiv" style="display:none"><img src="<?php echo JURI::root(); ?>/modules/mod_registerlogin/tmpl/assets/loader.gif"  /></div>
				<input type="hidden" value="" name="openview" id="openview">
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</div>
		<div class="control-group">
			<div>
				<ul class="unstyled">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
						  <?php echo JText::_('COM_USERS_LOGIN_REMIND'); ?></a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('COM_USERS_LOGIN_RESET'); ?></a>
					</li>

				</ul>
			</div>
		</div>
	</form>
</div>

<div class="registration_" id="registration_" style="<?php echo (isset($view ) && $view  == 2) ? 'display : block' : 'display : none' ?>">
	<form action="" method="post" id="registration_form" name="josForm" class="form-validate form-horizontal">
			<!--  -->
			<?php
			if (isset($_GET['itemId'])) {
				$db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select($db->quoteName(array('user_id','profile_value', 'ordering')))
                      ->from($db->quoteName('#__user_profiles').'AS p')
                      ->where($db->quoteName('ordering') . "=" . 2)
                      ->where($db->quoteName('user_id') . "=" . $_GET['itemId']);
                $db->setQuery($query);
                $userId = $db->loadObjectList();
			}?>
			<div class="control-group">
			<?php if ($params->get('usetext')) : ?>
				<div style="padding-top: 3px;">
					<span class="text"><label id="recommend" for="recommend"><?php echo JText::_('推薦人 系統審核後若已被推薦,則取消推薦'); ?></label></span>
				</div>
			<?php endif; ?>
			<div>
				<div class="input-prepend">
					<span class="add-on">
						<span class="icon-user tip" title="<?php echo JText::_('COM_USERS_REGISTER_RECOMMEND_LABEL'); ?>"></span>
					</span>
					<input tabindex="1" type="text" name="jform[recommend]" id="jform_recommend" size="20" value="<?php if (!empty($userId))  echo $userId[0]->profile_value; else echo "";?>"  disabled/>

					<?php
					if (isset($_GET['itemId'])) {
						$_REQUEST['jform']['recommendUser'] = $_GET['itemId'];
					} else {
						$_REQUEST['jform']['recommendUser'] = '';
					}?>
					<input tabindex="1" type="hidden" name="jform[recommendUser]" id="jform_recommendUser" size="20" value="<?php echo (isset($_REQUEST['jform']['recommendUser']) && !empty($_REQUEST['jform']['recommendUser'])) ? $_REQUEST['jform']['recommendUser'] : ''; ?>"/>
				</div>
			</div>
			</div>
			<!--  -->
			<div class="control-group">
			<?php if ($params->get('usetext')) : ?>
				<div>
					<span class="text"><label id="namemsg" for="name"><?php echo JText::_('COM_USERS_PROFILE_NAME_LABEL'); ?>*</label></span>
				</div>
			<?php endif; ?>
			<div>
				<div class="input-prepend">
					<span class="add-on">
						<span class="icon-user tip" title="<?php echo JText::_('COM_USERS_REGISTER_NAME_LABEL'); ?>"></span>
					</span>
					<input tabindex="1" placeholder="<?php echo JText::_('COM_USERS_REGISTER_NAME_LABEL'); ?>" type="text" name="jform[name]" id="jform_name" size="20" value="<?php echo (isset($_REQUEST['jform']['name']) && !empty($_REQUEST['jform']['name'])) ? $_REQUEST['jform']['name'] : ''; ?>" class="inputbox required" />
				</div>
			</div>
			</div>
			<!--  -->
			<div class="control-group">
				<?php if ($params->get('usetext')) { ?>
					<div>
						<span class="text"><label id="phone" for="phone"><?php echo JText::_('行動號碼'); ?> 09????????*</label></span>
					</div>
				<?php } ?>
				<div>
				<div class="input-prepend">
				<span class="add-on">
					<span class="icon-phone tip" title="<?php echo JText::_('COM_USERS_REGISTER_PHONE_LABEL'); ?>"></span>
				</span>
					<input tabindex="2" type="text" placeholder="<?php echo JText::_('請輸登入的行動號碼'); ?>" id="jform_phone" name="jform[phone]" size="20" value="<?php echo (isset($_REQUEST['jform']['phone']) && !empty($_REQUEST['jform']['phone'])) ? $_REQUEST['jform']['phone'] : ''; ?>" class="inputbox validate-phone required"  />
				</div>
				</div>
			</div>
			<!--  -->
					<input tabindex="2" type="hidden" placeholder="<?php echo JText::_('COM_USERS_REGISTER_USERNAME_DESC'); ?>" id="jform_username" name="jform[username]" size="20" value="<?php echo (isset($_REQUEST['jform'][email1]) && !empty($_REQUEST['jform'][email1])) ? $_REQUEST['jform'][email1] : ''; ?>" class="inputbox validate-username required"  />
				</div>
				</div>
			<div class="control-group">
				<?php if ($params->get('usetext')) { ?>
					<div>
						<span class="text"><label id="pwmsg" for="password"><?php echo JText::_('MOD_REGISTERLOGIN_PASSWORD'); ?>*</label></span>
					</div>
				<?php } ?>
				<div>
					<div class="input-prepend">
					<span class="add-on">
						<span class="icon-lock tip" title="<?php echo JText::_('COM_USERS_REGISTER_PASSWORD1_LABEL'); ?>"></span>
					</span>
						<input tabindex="3" placeholder="<?php echo JText::_('COM_USERS_REGISTER_PASSWORD1_LABEL'); ?>" class="inputbox validate-password required" type="password" id="jform_password1" name="jform[password1]" size="20" value=""  />
					</div>
				</div>
			</div>
			<div class="control-group">
				<?php if ($params->get('usetext')) { ?>
					<div>
						<span class="text"><label id="pw2msg" for="password2"><?php echo JText::_('COM_USERS_REGISTER_PASSWORD2_LABEL'); ?>*</label></span>
					</div>
				<?php } ?>
				<div>
					<div class="input-prepend">
					<span class="add-on">
						<span class="icon-lock tip" title="<?php echo JText::_('COM_USERS_REGISTER_PASSWORD2_DESC'); ?>"></span>
					</span>
						<input tabindex="4"  placeholder="<?php echo JText::_('COM_USERS_REGISTER_PASSWORD2_DESC'); ?>"   data-rule-equalTo="#jform_password1"  class="inputbox validate-password required" type="password" id="jform_password2" name="jform[password2]" size="20" value=""  />
					</div>
				</div>
			</div>
			<div class="control-group">
				<?php if ($params->get('usetext')) { ?>
					<div>
						<span class="text"><label id="emailmsg" for="email"><?php echo JText::_('COM_USERS_REGISTER_EMAIL1_LABEL'); ?>*</label></span>
					</div>
				<?php } ?>
				<div>
					<div class="input-prepend">
					<span class="add-on">
						<span class="icon-envelope tip" title="Email"></span>
					</span>
						<input tabindex="5"   placeholder="<?php echo JText::_('COM_USERS_REGISTER_EMAIL1_DESC'); ?>"  type="text" id="jform_email1" name="jform[email1]" size="20" value="<?php echo (isset($_REQUEST['jform']['email1']) && !empty($_REQUEST['jform']['email1'])) ? $_REQUEST['jform']['email1'] : ''; ?>" class="inputbox validate-email required email" />
					</div>
				</div>
			</div>
			<div class="control-group">
				<?php if ($params->get('usetext')) { ?>
					<div>
						<span class="text"><label id="email2msg" for="email2"><?php echo JText::_('COM_USERS_REGISTER_EMAIL2_LABEL'); ?>*</label></span>
					</div>
				<?php } ?>
				<div>
					<div class="input-prepend">
					<span class="add-on">
						<span class="icon-envelope tip" title="Verify Email"></span>
					</span>
						<input tabindex="6"  placeholder="<?php echo JText::_('COM_USERS_REGISTER_EMAIL2_DESC'); ?>" type="text" id="jform_email2" name="jform[email2]" size="20" value="<?php echo (isset($_REQUEST['jform']['email2']) && !empty($_REQUEST['jform']['email2'])) ? $_REQUEST['jform']['email2'] : ''; ?>" class="inputbox required email" data-rule-equalTo="#jform_email1" />
					</div>
				</div>
			</div>
			<!--  -->
			<div class="control-group">
				<?php if ($params->get('usetext')) { ?>
					<div>
						<span class="text"><label id="birthday" for="birthday"><?php echo JText::_('出生日期'); ?>*</label></span>
					</div>
				<?php } ?>
				<div>
				<div class="input-prepend">
				<span class="add-on">
					<span class="icon-calendar tip" title="<?php echo JText::_('COM_USERS_REGISTER_BIRTHDAY_LABEL'); ?>"></span>
				</span>
					<input tabindex="2" type="text" placeholder="<?php echo JText::_('YYYY/MM/DD'); ?>" id="jform_birthday" name="jform[birthday]" size="20" value="<?php echo (isset($_REQUEST['jform']['birthday']) && !empty($_REQUEST['jform']['birthday'])) ? $_REQUEST['jform']['birthday'] : ''; ?>" class="inputbox validate-birthday required"  />
				</div>
				</div>
			</div>
			<!--  -->
			<?php if ($params->get('enablecap_on_register')) { ?>
				<div class="control-group">
					<?php if ($params->get('usetext')) { ?>
						<div class="">
							<span class="text"><label id="captcha" for="captcha"><?php echo JText::_('COM_USERS_CAPTCHA_LABEL'); ?>*</label></span>
						</div>
					<?php } ?>
					<div style="width: 100%" class="">
					<?php
						if($siteKey){ ?>
							 <div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>"></div>
						<?php }
						else{
							JError::raiseWarning( 100, 'Please enter the ReCaptcha public and secret key' );
						}
					?>

					</div>
				</div>
			<?php } ?>
			<?php  if ($params->get('tou')) { ?>
			<div class="control-group">
				<div>
					&nbsp;
				</div>
				<div>
					<input name="terms" class="required" type="checkbox" <?php if($params->get('checkbox')) { echo "checked='checked'"; } ?>  id="tou" /> &nbsp
					<?php if($params->get('newwindow') == 'modal'){ ?>
						<a id="terms_" href="<?php echo JURI::root(); ?>index.php?option=com_content&view=article&id=<?php echo $params->get('articleid') ?>&tmpl=component" class="modal"><?php echo $params->get('title'); ?></a>
					<?php }else {  ?>
					<a id="terms_" href="<?php echo JURI::root(); ?>index.php?option=com_content&view=article&id=<?php echo $params->get('articleid') ?>" target="<?php echo $params->get('newwindow'); ?>"><?php echo $params->get('title'); ?></a>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			<div class="control-group">
				<div>
					&nbsp;
				</div>
				<div>				
					<input style="width: 275px" type="submit" id="register_submit"  name="Submit" class="btn btn-primary validate" value="<?php echo JText::_('MOD_REGISTERLOGIN_JREGISTER') ?>" /><div class="regload" style="display:none"><img src="<?php echo JURI::root(); ?>/modules/mod_registerlogin/tmpl/assets/loader.gif"  /></div>
					<input type="hidden" value="register" name="module<?php echo $module->id; ?>">
					<input type="hidden" value="" name="openview" id="openview">
					<?php echo JHTML::_('form.token'); ?>
				</div>
			</div>
	</form>
</div>
</div>
