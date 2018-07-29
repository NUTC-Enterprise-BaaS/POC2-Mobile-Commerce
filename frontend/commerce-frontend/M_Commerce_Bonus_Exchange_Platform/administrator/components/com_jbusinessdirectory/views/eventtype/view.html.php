<?php
/**
 * @package    JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2007 - 2015 CMS Junkie. All rights reserved.
 * @license     GNU General Public License version 2 or later; 
 */

defined('_JEXEC') or die;

/**
 * The HTML  View.
 */
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';
JBusinessUtil::includeValidation();

class JBusinessDirectoryViewEventType extends JBusinessDirectoryAdminView
{
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null){
	
		$this->item	 = $this->get('Item');
		$this->state = $this->get('State');
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$this->translations = JBusinessDirectoryTranslations::getAllTranslations(EVENT_TYPE_TRANSLATION,$this->item->id);
		$this->languages = JBusinessUtil::getLanguages();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$canDo = JBusinessDirectoryHelper::getActions();
		$user  = JFactory::getUser();
		
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);

		$user  = JFactory::getUser();
		$isNew = ($this->item->id == 0);

		JToolbarHelper::title(JText::_($isNew ? 'COM_JBUSINESSDIRECTORY_NEW_EVENT_TYPE' : 'COM_JBUSINESSDIRECTORY_EDIT_EVENT_TYPE'), 'menu.png');
		
		if ($canDo->get('core.edit')){
			JToolbarHelper::apply('eventtype.apply');
			JToolbarHelper::save('eventtype.save');
		}
		
		JToolbarHelper::cancel('eventtype.cancel', 'JTOOLBAR_CLOSE');
		
		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_JBUSINESSDIRECTORY_EVENT_TYPE_EDIT');
	}
}
