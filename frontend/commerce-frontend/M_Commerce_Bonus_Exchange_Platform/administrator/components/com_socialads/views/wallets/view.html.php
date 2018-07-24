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
 * View class for a list of Socialads.
 *
 * @since  1.0
 */
class SocialadsViewWallets extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   boolean  $tpl  used to get displayed value
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		SocialadsHelper::addSubmenu('wallets');
		$this->publish_states = array(
			'' => JText::_('JOPTION_SELECT_PUBLISHED'),
			'1'  => JText::_('JPUBLISHED'),
			'0'  => JText::_('JUNPUBLISHED')
		);
		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/socialads.php';

		$state = $this->get('State');
		$canDo = SocialadsHelper::getActions($state->get('filter.category_id'));

		if (JVERSION >= '3.0')
		{
			JToolBarHelper::title(JText::_('COM_SOCIALADS') . ': ' . JText::_('COM_SOCIALADS_TITLE_WALETS'), 'list');
		}
		else
		{
			JToolBarHelper::title(JText::_('COM_SOCIALADS') . ': ' . JText::_('COM_SOCIALADS_TITLE_WALETS'), 'wallets.png');
		}

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/adwallet';

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_socialads');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_socialads&view=wallets');
		$this->extra_sidebar = '';
	}

	/**
	 * Function to get a sorted list
	 *
	 * @return  void
	 */
	protected function getSortFields()
	{
		return array(
		'u.username' => JText::_('COM_SOCIALADS_ADWALETS_USERNAME'),
		'a.spent' => JText::_('COM_SOCIALADS_ADWALETS_SPENT'),
		'a.earn' => JText::_('COM_SOCIALADS_ADWALETS_EARN'),
		'a.balance' => JText::_('COM_SOCIALADS_ADWALETS_BALANCE'),
		);
	}
}
