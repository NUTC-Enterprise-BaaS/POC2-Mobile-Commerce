<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access to this file
defined('_JEXEC') or die(';)');

jimport('joomla.application.component.view');

/**
 * HTML View class for the socialads Component
 *
 * @since  1.6
 */
class SocialadsViewImportfields extends JViewLegacy
{
	/**
	 * social_targetting view display method
	 *
	 * @param   integer  $tpl  pass value
	 *
	 * @return  void
	 *
	 * @since  1.6
	 **/
	public function display($tpl = null)
	{
		$adresult = $this->get('AdData');

		$this->assignRef('adcount', $adresult);
		$pluginresult = $this->get('PluginData');
		$this->assignRef('pluginresult', $pluginresult);
		$colfields = $this->get('colfields');
		$this->assignRef('colfields', $colfields);

		$mappinglistt[] = JHtml::_('select.option', '0', JText::_("COM_SOCIALADS_SOCIAL_TARGETING_DONT_MAP"));
		$mappinglistt[] = JHtml::_('select.option', 'textbox', JText::_("COM_SOCIALADS_SOCIAL_TARGETING_FREETEXT"));
		$mappinglistt[] = JHtml::_('select.option', 'numericrange', JText::_("COM_SOCIALADS_SOCIAL_TARGETING_NUMERIC_RANGE"));

		$mappinglista[] = JHtml::_('select.option', '0', JText::_("COM_SOCIALADS_SOCIAL_TARGETING_DONT_MAP"));
		$mappinglista[] = JHtml::_('select.option', 'textbox', JText::_("COM_SOCIALADS_SOCIAL_TARGETING_FREETEXT"));

		$mappinglists[] = JHtml::_('select.option', '0', JText::_("COM_SOCIALADS_SOCIAL_TARGETING_DONT_MAP"));
		$mappinglists[] = JHtml::_('select.option', 'singleselect', JText::_("COM_SOCIALADS_SOCIAL_TARGETING_SINGLE_SELECT"));
		$mappinglists[] = JHtml::_('select.option', 'multiselect', JText::_("COM_SOCIALADS_SOCIAL_TARGETING_MULTIPLE_SELECT"));

		$mappinglistd[] = JHtml::_('select.option', '0', JText::_("COM_SOCIALADS_SOCIAL_TARGETING_DONT_MAP"));
		$mappinglistd[] = JHtml::_('select.option', 'daterange', JText::_("COM_SOCIALADS_SOCIAL_TARGETING_DATE_RANGE"));
		$mappinglistd[] = JHtml::_('select.option', 'date', JText::_("COM_SOCIALADS_SOCIAL_TARGETING_DATE"));

		$mapall[] = JHtml::_('select.option', '0', JText::_("COM_SOCIALADS_SOCIAL_TARGETING_DONT_MAP"));
		$mapall[] = JHtml::_('select.option', 'textbox', JText::_("COM_SOCIALADS_SOCIAL_TARGETING_FREETEXT"));
		$mapall[] = JHtml::_('select.option', 'numericrange', JText::_("COM_SOCIALADS_SOCIAL_TARGETING_NUMERIC_RANGE"));
		$mapall[] = JHtml::_('select.option', 'singleselect', JText::_("COM_SOCIALADS_SOCIAL_TARGETING_SINGLE_SELECT"));
		$mapall[] = JHtml::_('select.option', 'multiselect', JText::_("COM_SOCIALADS_SOCIAL_TARGETING_MULTIPLE_SELECT"));
		$mapall[] = JHtml::_('select.option', 'daterange', JText::_("COM_SOCIALADS_SOCIAL_TARGETING_DATE_RANGE"));
		$mapall[] = JHtml::_('select.option', 'date', JText::_("COM_SOCIALADS_SOCIAL_TARGETING_DATE"));

		$this->assignRef('mappinglista', $mappinglista);
		$this->assignRef('mappinglistt', $mappinglistt);
		$this->assignRef('mappinglistd', $mappinglistd);
		$this->assignRef('mappinglists', $mappinglists);
		$this->assignRef('mapall', $mapall);
		$fields = $this->get('ImportFields');
		$this->assignRef('fields', $fields);

		$this->addToolbar();

		SocialadsHelper::addSubmenu('importfields');

		if (JVERSION >= 3.0)
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

		// JFactory::getApplication()->input->set('hidemainmenu', true);
		$user = JFactory::getUser();
		$state = $this->get('State');
		$canDo = SocialadsHelper::getActions($state->get('filter.category_id'));

		$viewTitle = JText::_('COM_SOCIALADS_TITLE_SOCIAL_TARGETING');

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_socialads');
		}

		if (JVERSION >= '3.0')
		{
			// Set sidebar action - New in 3.0
			JHtmlSidebar::setAction('index.php?option=com_socialads&view=importfields');
		}

		$this->extra_sidebar = '';

		if (JVERSION >= '3.0')
		{
			JToolbarHelper::title(JText::_('COM_SOCIALADS') . ': ' . $viewTitle, 'pencil-2');
		}
		else
		{
			JToolbarHelper::title(JText::_('COM_SOCIALADS') . ': ' . $viewTitle, 'importfields.png');
		}

		$style = '';
		$style1 = '';

		if (JVERSION < 3.0)
		{
			$style = '<span title="Save" class="icon-32-save"></span>';
			$style1 = '<span title="Save" class="icon-reset"></span>';
		}

		$params      = JComponentHelper::getParams('com_socialads');
		$integration = $params->get('social_integration');
		$button = '<a class="toolbar btn btn-small validate" type="submit" onclick="javascript:saAdmin.importfields.resetTargeting();"
		href="#"><i class="icon-remove ">' . $style1 . ' </i>Reset</a>';
		$bar = JToolBar::getInstance('toolbar');

		JToolBarHelper::back(JText::_('COM_SOCIALADS_SOCIAL_TARGETING_HOME'), 'index.php?option=com_socialads');

		if ($integration != "Joomla")
		{
			$bar->appendButton('Custom', $button);
		}

		$button = '<a class="toolbar btn btn-small validate" type="submit" onclick="javascript:saAdmin.importfields.saveTargeting()"
		href="#"><i class="icon-save "> ' . $style . '</i>Save</a>';
		$bar = JToolBar::getInstance('toolbar');

		if ($integration != "Joomla")
		{
			$bar->appendButton('Custom', $button);
		}
	}
}
