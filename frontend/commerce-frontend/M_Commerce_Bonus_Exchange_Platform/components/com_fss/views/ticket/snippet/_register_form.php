<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php if (JComponentHelper::getParams( 'com_users' )->get('allowUserRegistration')): ?>
	<?php echo FSS_Helper::PageSubTitle("REGISTER"); ?>
	<?php
		$return = FSS_Helper::getCurrentURLBase64();

		$register_url = FSSRoute::_("index.php?option=com_fss&view=login&layout=register&return=" . $return);
		
		if (property_exists($this, "return"))
			$register_url = FSSRoute::_("index.php?option=com_fss&view=login&layout=register&return=" . $this->return);
		
		if (JRequest::getVar('return'))
			$register_url = FSSRoute::_("index.php?option=com_fss&view=login&layout=register&return=" . JRequest::getVar('return'));

		if (FSS_Settings::get('support_custom_register'))
			$register_url = FSS_Settings::get('support_custom_register');
	?>	
	<p><?php echo JText::sprintf('IF_YOU_WOULD_LIKE_TO_CREATE_A_USER_ACCOUNT_PLEASE_REGISTER_HERE', $register_url); ?></p>
<?php endif; ?>