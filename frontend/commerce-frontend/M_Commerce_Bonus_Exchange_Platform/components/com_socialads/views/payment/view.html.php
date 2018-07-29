<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the Aniket Component
 *
 * @since  1.6
 */
class SocialadsViewPayment extends JViewLegacy
{
	protected $form;

	/**
	 * Overwriting JView display method
	 *
	 * @param   boolean  $tpl  used to get displayed value
	 *
	 * @return  void
	 *
	 * @since  1.6
	 **/
	public function display($tpl = null)
	{
		$user       = JFactory::getUser();
		$this->form = $this->get('Form');
		$app = JFactory::getApplication();

		if (!$user->id)
		{
			if (!JFactory::getUser($user->id)->authorise('core.manage_ad', 'com_socialads'))
			{
				$app->enqueueMessage(JText::_('COM_SOCIALADS_PLEASE_LOGIN'), 'warning');

				return false;
			}
		}

		$params = JComponentHelper::getParams('com_socialads');
		$payment_mode = $params->get('payment_mode');

		// If payment mode is payperadd the restrict the access
		if ($payment_mode == 'pay_per_ad_mode')
		{
			$app->enqueueMessage(JText::_('COM_SOCIALADS_AUTH_ERROR'), 'warning');

			return false;
		}

		$gatewayplugin       = $this->get('APIpluginData');
		$this->gatewayplugin = $gatewayplugin;
		$this->setLayout('edit');
		parent::display($tpl);
	}
}
