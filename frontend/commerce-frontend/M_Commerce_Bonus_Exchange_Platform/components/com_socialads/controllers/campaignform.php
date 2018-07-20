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

/**
 * Campaign controller class.
 *
 * @since  1.6
 */
class SocialadsControllerCampaignForm extends SocialadsController
{
	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	public function addNew()
	{
		$this->edit();
	}

	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	public function edit()
	{
		$app        = JFactory::getApplication();
		$cid        = $app->input->get('cid', array(), 'array');

		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_socialads.edit.campaign.id');
		$editId     = $cid[0];

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_socialads.edit.campaign.id', $editId);

		// Get the model.
		$model = $this->getModel('CampaignForm', 'SocialadsModel');

		// Check out the item
		if ($editId)
		{
			$model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId)
		{
			$model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_socialads&view=campaignform&id=' . $editId . '&layout=edit', false));
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return    void
	 *
	 * @since    1.6
	 */
	public function save()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app   = JFactory::getApplication();
		$id    = $app->input->post->get('cid');
		$model = $this->getModel('CampaignForm', 'SocialadsModel');

		// Get the user data.
		$data = JFactory::getApplication()->input->get('jform', array(), 'array');

		// Validate the posted data.
		$form = $model->getForm();

		if (!$form)
		{
			throw new Exception($model->getError(), 500);
		}

		// Validate the posted data.
		$data = $model->validate($form, $data);

		// Check for errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			$input = $app->input;
			$jform = $input->get('jform', array(), 'ARRAY');

			// Save the data in the session.
			$app->setUserState('com_socialads.edit.campaign.data', $jform, array());

			// Redirect back to the edit screen.
			$this->setRedirect(JRoute::_('index.php?option=com_socialads&view=campaignform&layout=edit&id=' . $id, false));

			return false;
		}

		$data['id'] = $id;

		// Attempt to save the data.
		$return     = $model->save($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_socialads.edit.campaign.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_socialads.edit.campaign.id');
			$this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_socialads&view=campaignform&layout=edit&id=' . $id, false));

			return false;
		}

		// Check in the profile.
		if ($return)
		{
			$model->checkin($return);
		}

		// Clear the profile id from the session.
		$app->setUserState('com_socialads.edit.campaign.id', null);

		// Redirect to the list screen.
		$this->setMessage(JText::_('COM_SOCIALADS_ITEM_SAVED_SUCCESSFULLY'));
		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_socialads&view=campaigns' : $item->link);
		$this->setRedirect(JRoute::_($url, false));

		// Flush the data from the session.
		$app->setUserState('com_socialads.edit.campaign.data', null);
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
		$app = JFactory::getApplication();

		// Get the current edit id.
		$editId = (int) $app->getUserState('com_socialads.edit.campaign.id');

		// Get the model.
		$model = $this->getModel('CampaignForm', 'SocialadsModel');

		// Check in the item
		if ($editId)
		{
			$model->checkin($editId);
		}

		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_socialads&view=campaigns' : $item->link);
		$this->setRedirect(JRoute::_($url, false));
	}
}
