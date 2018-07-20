<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';

jimport('joomla.application.component.controller');

/**
 * Campaign controller class.
 *
 * @since  1.6
 */
class SocialadsControllerRegistration extends JControllerLegacy
{
	/**
	 * Method to save a user's profile data.
	 *
	 * @return    void
	 *
	 * @since    1.6
	 */
	public function save()
	{
		$input = JFactory::getApplication()->input;
		$id = $input->get('cid', 0, 'INT');
		$model = $this->getModel('registration');
		$session = JFactory::getSession();

		// Get data from request
		$app = JFactory::getApplication();
		$post = $input->post;
		$socialadsbackurl = $session->get('socialadsbackurl');

		// Let the model save it
		if ($model->store())
		{
			$message = "";
			$itemid = $input->get('Itemid', 0, 'INT');
			$this->setRedirect($socialadsbackurl, $message);
		}
		else
		{
			$message = $input->get('message', '', 'STRING');
			$itemid = $input->get('Itemid', 0, 'INT');
			$this->setRedirect('index.php?option=com_socialads&view=registration&Itemid=' . $itemid, $message);
		}
	}

	/**
	 * Method to set cancel edit operation
	 *
	 * @return void
	 *
	 * @since   2.2
	 */
	public function cancel()
	{
		$input = JFactory::getApplication()->input;
		$msg = JText::_('Operation Cancelled');
		$itemid = $input->get('Itemid', 0, 'INT');
		$this->setRedirect('index.php', $msg);
	}
}
