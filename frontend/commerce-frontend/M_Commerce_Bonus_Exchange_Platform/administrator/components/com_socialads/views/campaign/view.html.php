<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 *
 * @since  1.6
 */
class SocialadsViewCampaign extends JViewLegacy
{
	protected $state;

	protected $item;

	protected $form;

	/**
	 * Display the view
	 *
	 * @param   array  $tpl  An optional associative array.
	 *
	 * @return  array
	 *
	 * @since 1.6
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->item = $this->get('Item');
		$this->form = $this->get('Form');
		$user = JFactory::getUser();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		if (! JFactory::getUser($user->id)->authorise('core.edit', 'com_socialads'))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_SOCIALADS_AUTH_ERROR'), 'warning');
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  array
	 *
	 * @since 1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$user = JFactory::getUser();
		$isNew = ($this->item->id == 0);

		if ($isNew)
		{
			$viewTitle = JText::_('COM_SOCIALADS_ADS_AD_CAMPAGIN');
		}
		else
		{
			$viewTitle = JText::_('COM_SOCIALADS_ADS_EDIT_CAMPAGIN');
		}

		if (isset($this->item->checked_out))
		{
			$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		}
		else
		{
			$checkedOut = false;
		}

		$canDo = SocialadsHelper::getActions();

		if (JVERSION >= '3.0')
		{
			JToolbarHelper::title($viewTitle, 'pencil-2');
		}
		else
		{
			JToolbarHelper::title($viewTitle, 'campaign.png');
		}

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create'))))
		{
			JToolBarHelper::apply('campaign.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('campaign.save', 'JTOOLBAR_SAVE');
		}

		if (!$checkedOut && ($canDo->get('core.create')))
		{
			JToolBarHelper::custom('campaign.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}

		if (empty($this->item->id))
		{
			JToolBarHelper::cancel('campaign.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			JToolBarHelper::cancel('campaign.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
