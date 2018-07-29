<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_SocialAds
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
 * @since  1.6
 */
class SocialadsViewForms extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

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
		// If any Ad id in session, clear it
		JFactory::getSession()->clear('ad_id');

		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		$zone_list = $this->get('Zonelist');

		JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');
		$campaigns = JFormHelper::loadFieldType('Campains', false);
		$zones = JFormHelper::loadFieldType('Zones', false);

		// Get campaigns list
		$this->campaignsoptions = $campaigns->getOptions();
		$this->zoneOptions = $zones->getOptions();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		SocialadsHelper::addSubmenu('forms');

		$this->publish_states = array(
			'' => JText::_('JOPTION_SELECT_PUBLISHED'),
			'1'  => JText::_('JPUBLISHED'),
			'0'  => JText::_('JUNPUBLISHED')
		);

		// Status select box
		$status = array();
		$status[] = JHtml::_('select.option', '0', JText::_('COM_SOCIALADS_AD_PENDING'));
		$status[] = JHtml::_('select.option', '1',  JText::_('COM_SOCIALADS_AD_CONFIRM'));
		$status[] = JHtml::_('select.option', '2', JText::_('COM_SOCIALADS_ADS_REJECTED'));
		$this->assignRef('status', $status);

		$this->ostatus = array();
		$this->ostatus[] = JHtml::_('select.option', '-1', JText::_('COM_SOCIALADS_ADS_APPROVAL_STATUS'));
		$this->ostatus[] = JHtml::_('select.option', '0',  JText::_('COM_SOCIALADS_AD_PENDING'));
		$this->ostatus[] = JHtml::_('select.option', '1',  JText::_('COM_SOCIALADS_AD_CONFIRM'));
		$this->ostatus[] = JHtml::_('select.option', '2',  JText::_('COM_SOCIALADS_ADS_REJECTED'));

		// For zone list
		$zone_ad = '';

		foreach ($zone_list as $selected_zone)
		{
			$zone_ad['0'] = JHtml::_('select.option', '0', 'Select');
			$i = 1;
			$zname = $selected_zone->zone_name;
			$zid = $selected_zone->id;
			$zone_ad[$i] = JHtml::_('select.option', $zid, $zname);
			$i++;
		}

		$this->zone_array = $zone_ad;
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
	 * @since  1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/socialads.php';
		$state = $this->get('State');
		$canDo = SocialadsHelper::getActions($state->get('filter.category_id'));

		if (JVERSION >= '3.0')
		{
			JToolBarHelper::title(JText::_('COM_SOCIALADS') . ': ' . JText::_('COM_SOCIALADS_TITLE_ADS'), 'list');
		}
		else
		{
			JToolBarHelper::title(JText::_('COM_SOCIALADS') . ': ' . JText::_('COM_SOCIALADS_TITLE_ADS'), 'ads.png');
		}

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/form';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('form.add', 'JTOOLBAR_NEW');
			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('form.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('forms.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('forms.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
				JToolBarHelper::custom('forms.adCsvExport', 'download', 'download', 'COM_SOCIALADS_ADS_CSV_EXPORT', false);
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('forms.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		if (isset($this->items[0]))
		{
			if ($canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'forms.delete', 'JTOOLBAR_DELETE');
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_socialads');
		}

		if (JVERSION >= '3.0')
		{
			// Set sidebar action - New in 3.0
			JHtmlSidebar::setAction('index.php?option=com_socialads&view=forms');
			$this->extra_sidebar = '';
		}
	}

	/**
	 * For sorting filter.
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	protected function getSortFields()
	{
		return array(
		'a.ad_id' => JText::_('JGRID_HEADING_ID'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.state' => JText::_('JSTATUS'),
		);
	}
}
