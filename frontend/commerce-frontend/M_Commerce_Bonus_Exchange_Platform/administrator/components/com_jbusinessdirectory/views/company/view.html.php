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

JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/manage.companies.js');
JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.upload.js');
JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/common.js');

JBusinessUtil::includeValidation();

 
JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/jquery.timepicker.css');
JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/font-awesome.css');
JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/common.css');
JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.timepicker.min.js');

JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/dropzone/dropzone.js');
JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/dropzone/jbusinessImageUploader.js');

JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/dropzone.css');
JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/basic.css');

JHtml::script('jui/fielduser.min.js', false, true, false, false, true);

class JBusinessDirectoryViewCompany extends JBusinessDirectoryAdminView
{
	protected $item;
	protected $state;
	protected $packages;
	protected $claimDetails;

	/**
	 * Display the view
	 */
	public function display($tpl = null){
		$this->item	 = $this->get('Item');
		$this->state = $this->get('State');
		
		$this->translations = JBusinessDirectoryTranslations::getAllTranslations(BUSSINESS_DESCRIPTION_TRANSLATION,$this->item->id);
		$this->translationsSlogan = JBusinessDirectoryTranslations::getAllTranslations(BUSSINESS_SLOGAN_TRANSLATION,$this->item->id);
		$this->languages = JBusinessUtil::getLanguages();
		
		$this->categoryOptions = JBusinessUtil::getCategoriesOptions(true, CATEGORY_TYPE_BUSINESS);
		$this->claimDetails = $this->get('ClaimDetails');
		
		//get all upgrade packages - cannot downgrade
		$price = 0;
		if(!empty($this->item->lastActivePackage) && $this->item->lastActivePackage->expired == false){
			$price = $this->item->lastActivePackage->price;
		}
		$this->packageOptions = JBusinessDirectoryHelper::getPackageOptions($price);
		
		$lang = JFactory::getLanguage()->getTag();
		$key="";
		if(!empty($this->appSettings->google_map_key))
			$key="&key=".$this->appSettings->google_map_key;
		JHtml::_('script', "https://maps.googleapis.com/maps/api/js?libraries=places&language=".$lang.$key);
		
		if($this->appSettings->enable_packages && empty($this->packageOptions)){
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_("LNG_NO_ACTIVE_PACKAGE"), 'warning');
			$app->redirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=companies',false));
		}
		
		$this->location = $this->get('Location');
	
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar($this->claimDetails);
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar($claimDetails)
	{	
		$canDo = JBusinessDirectoryHelper::getActions();
		$user  = JFactory::getUser();
		
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);

		$user  = JFactory::getUser();
		$isNew = ($this->item->id == 0);

		JToolbarHelper::title(JText::_($isNew ? 'COM_JBUSINESSDIRECTORY_NEW_COMPANY' : 'COM_JBUSINESSDIRECTORY_EDIT_COMPANY'), 'menu.png');

		if ($canDo->get('core.edit')){
			JToolbarHelper::apply('company.apply');	
			JToolbarHelper::save('company.save');
		}
		
		if($this->item->id > 0 && !(isset($claimDetails) && $claimDetails->status == 0)){
			JToolBarHelper::divider();
			JToolBarHelper::custom( 'company.aprove', 'publish.png', 'publish.png', JText::_("LNG_APPROVE"), false, false );
			JToolBarHelper::custom( 'company.disaprove', 'unpublish.png', 'unpublish.png', JText::_("LNG_DISAPPROVE"), false, false );
		}
			
		if(isset($claimDetails) && $claimDetails->status == 0){
			JToolBarHelper::divider();
			JToolBarHelper::custom( 'company.aproveClaim', 'publish.png', 'publish.png', JText::_("LNG_APPROVE_CLAIM"), false, false );
			JToolBarHelper::custom( 'company.disaproveClaim', 'unpublish.png', 'unpublish.png', JText::_("LNG_DISAPPROVE_CLAIM"), false, false );
			JToolBarHelper::divider();
		}
	
		JToolbarHelper::cancel('company.cancel', 'JTOOLBAR_CLOSE');
		
		JToolbarHelper::divider();
		JToolBarHelper::help('', false, DOCUMENTATION_URL.'businessdiradmin.html#manage-companies');
	}
	
}
