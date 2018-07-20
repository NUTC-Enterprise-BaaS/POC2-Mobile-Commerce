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

JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery-ui.js'); 
JHtml::_('script', 'administrator/components/com_jbusinessdirectory/assets/js/ui.multiselect.js');
JHtml::_('stylesheet', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/themes/ui-lightness/jquery-ui.css');
JHtml::_('stylesheet', 'administrator/components/com_jbusinessdirectory/assets/css/ui.multiselect.css');
JBusinessUtil::includeValidation();

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class JBusinessDirectoryViewPoint extends JBusinessDirectoryAdminView
{
	protected $item;
	protected $state; 

	/**
	 * Display the view
	 */
	public function display($tpl = null){
	
		$this->item	 = $this->get('Item');
		$this->state = $this->get('State');

		$this->statuses	= JBusinessDirectoryHelper:: getStatuses();
		
		$this->features = JBusinessDirectoryHelper::getPackageFeatures();
		$this->customFeatures = JBusinessDirectoryHelper::getPackageCustomFeatures();
		
		$this->selectedFeatures = $this->get('SelectedFeatures');
		
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$this->translations = JBusinessDirectoryTranslations::getAllTranslations(PACKAGE_TRANSLATION,$this->item->id);
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
		$isNew = ($this->item->id == 0);

		JToolbarHelper::title(JText::_($isNew ? '使用者消費記錄' : 'COM_JBUSINESSDIRECTORY_EDIT_PACKAGE'), 'menu.png');

		JToolbarHelper::cancel('point.cancel', 'JTOOLBAR_CLOSE');
		
		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_JBUSINESSDIRECTORY_PACKAGE_EDIT');
	}
	
}
