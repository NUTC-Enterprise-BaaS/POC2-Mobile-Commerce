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
class SocialadsViewZones extends JViewLegacy
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
		$this->modules = $this->get('ZoneModules');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		SocialadsHelper::addSubmenu('zones');
		$this->publish_states = array(
			'' => JText::_('JOPTION_SELECT_PUBLISHED'),
			'1'  => JText::_('JPUBLISHED'),
			'0'  => JText::_('JUNPUBLISHED')
		);
		$this->addToolbar();

		if (JVERSION >= '3.0')
		{
			$this->sidebar = JHtmlSidebar::render();
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/socialads.php';

		$state = $this->get('State');
		$canDo = SocialadsHelper::getActions($state->get('filter.category_id'));

		if (JVERSION >= '3.0')
		{
			JToolBarHelper::title(JText::_('COM_SOCIALADS') . ': ' . JText::_('COM_SOCIALADS_TITLE_ZONES'), 'list');
		}
		else
		{
			JToolBarHelper::title(JText::_('COM_SOCIALADS') . ': ' . JText::_('COM_SOCIALADS_TITLE_ZONES'), 'zones.png');
		}

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/zone';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('zone.add', 'JTOOLBAR_NEW');
			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('zone.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('zones.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('zones.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('zones.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field

		if (isset($this->items[0]))
		{
			if ($canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'zones.delete', 'JTOOLBAR_DELETE');
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_socialads');
		}

		if (JVERSION >= '3.0')
		{
			// Set sidebar action - New in 3.0
			JHtmlSidebar::setAction('index.php?option=com_socialads&view=zones');
		}

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
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.state' => JText::_('JSTATUS'),
		'a.zone_name' => JText::_('COM_SOCIALADS_ZONES_ZONE_NAME'),
		'a.published' => JText::_('COM_SOCIALADS_ZONES_PUBLISHED'),
		'a.orientation' => JText::_('COM_SOCIALADS_ZONES_ORIENTATION'),
		'a.ad_type' => JText::_('COM_SOCIALADS_ZONES_AD_TYPE'),
		'a.max_title' => JText::_('COM_SOCIALADS_ZONES_MAX_TITLE'),
		'a.max_des' => JText::_('COM_SOCIALADS_ZONES_MAX_DES'),
		'a.img_width' => JText::_('COM_SOCIALADS_ZONES_IMG_WIDTH'),
		'a.img_height' => JText::_('COM_SOCIALADS_ZONES_IMG_HEIGHT'),
		'a.per_click' => JText::_('COM_SOCIALADS_ZONES_PER_CLICK'),
		'a.per_imp' => JText::_('COM_SOCIALADS_ZONES_PER_IMP'),
		'a.per_day' => JText::_('COM_SOCIALADS_ZONES_PER_DAY'),
		'a.num_ads' => JText::_('COM_SOCIALADS_ZONES_NUM_ADS'),
		'a.layout' => JText::_('COM_SOCIALADS_ZONES_LAYOUT'),
		);
	}
}
