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
 * @since  1.0
 */
class SocialadsViewZone extends JViewLegacy
{
	protected $state;

	protected $item;

	protected $form;

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
		$this->item = $this->get('Item');
		$this->form = $this->get('Form');
		$input = JFactory::getApplication()->input;
		$input->get('id', '', 'INT');
		$this->setLayout('edit');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		$params = JComponentHelper::getParams('com_socialads');

		if ($params->get('social_integration') == 'JomSocial')
		{
			if (file_exists(JPATH_ROOT . '/components/com_community'))
			{
				/*load language file for plugin frontend*/
				$lang = JFactory::getLanguage();
				$lang->load('com_community', JPATH_SITE);
			}
		}

		if (!empty($this->item->id))
		{
			JLoader::import('zones', JPATH_ADMINISTRATOR . '/components/com_socialads/models');
			$campaignsModel = new SocialadsModelZones;
			$this->recordsCount = $campaignsModel->getZoneaddatacount($this->item->id);
		}

		// JLoader::import('ad', JPATH_SITE.'components'.DS.'com_socialads'.DS.'models');
		$fields = SaIntegrationsHelper::getFields();
		$this->fields = $fields;
		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user = JFactory::getUser();
		$isNew = ($this->item->id == 0);

		if ($isNew)
		{
			$viewTitle = JText::_('COM_SOCIALADS_ADD_ZONE');
		}
		else
		{
			$viewTitle = JText::_('COM_SOCIALADS_EDIT_ZONE');
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
			JToolbarHelper::title($viewTitle, 'zone.png');
		}

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create'))))
		{
			JToolBarHelper::apply('zone.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('zone.save', 'JTOOLBAR_SAVE');
		}

		if (empty($this->item->id))
		{
			JToolBarHelper::cancel('zone.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			JToolBarHelper::cancel('zone.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
