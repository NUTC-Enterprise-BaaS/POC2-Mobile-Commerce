<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Acmanager
 * @author     Parth Lawate <contact@techjoomla.com>
 * @copyright  Copyright (C) 2016. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Acmanager.
 *
 * @since  1.6
 */
class AcmanagerViewManageioscertificatess extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;
	
	protected $file;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->file = $this->get('file');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		AcmanagerHelper::addSubmenu('manageioscertificatess');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	
	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	/*protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/acmanager.php';

		$state = $this->get('State');
		$canDo = AcmanagerHelper::getActions($state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('COM_ACMANAGER_TITLE_MANAGEIOSCERTIFICATESS'), 'manageioscertificatess.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/manageioscertificates';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('manageioscertificates.add', 'JTOOLBAR_NEW');
				JToolbarHelper::custom('manageioscertificatess.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('manageioscertificates.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('manageioscertificatess.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('manageioscertificatess.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'manageioscertificatess.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('manageioscertificatess.archive', 'JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('manageioscertificatess.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'manageioscertificatess.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('manageioscertificatess.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_acmanager');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_acmanager&view=manageioscertificatess');

		$this->extra_sidebar = '';
	}*/
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user  = JFactory::getUser();
		//$isNew = ($this->item->id == 0);
		$isNew = 0;

		if (isset($this->item->checked_out))
		{
			$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		}
		else
		{
			$checkedOut = false;
		}

		$canDo = AcmanagerHelper::getActions();

		JToolBarHelper::title(JText::_('Manage ios certificates'), 'appuser.png');
/*
		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create'))))
		{
			JToolBarHelper::apply('manageioscertificatess.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('manageioscertificatess.save', 'JTOOLBAR_SAVE');
		}
/*
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create'))
		{
			JToolBarHelper::custom('manageioscertificatess.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
*/
		if (empty($this->item->id))
		{
			JToolBarHelper::cancel('manageioscertificatess.cancel', 'JTOOLBAR_CLOSE');
		}
		else
		{
			JToolBarHelper::cancel('manageioscertificatess.cancel', 'JTOOLBAR_CLOSE');
		}
	}

	/**
	 * Method to order fields 
	 *
	 * @return void 
	 */
	protected function getSortFields()
	{
		return array(
			'a.`id`' => JText::_('JGRID_HEADING_ID'),
			'a.`type`' => JText::_('COM_ACMANAGER_PUSHNOTIFICATIONCONFIGS_TYPE'),
			'a.`name`' => JText::_('COM_ACMANAGER_PUSHNOTIFICATIONCONFIGS_NAME'),
			'a.`active`' => JText::_('COM_ACMANAGER_PUSHNOTIFICATIONCONFIGS_ACTIVE'),
		);
	}
}
