<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );


JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/manage.companies.js');
JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.upload.js');

JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/joomlatabs.css');

JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/jquery.timepicker.css');
JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.timepicker.min.js');
JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/chosen.jquery.min.js');
JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/chosen.css');

JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/dropzone/dropzone.js');
JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/dropzone/jbusinessImageUploader.js');

JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/dropzone.css');
JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/basic.css');

JBusinessUtil::includeValidation();

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class JBusinessDirectoryViewManageCompanyEvent extends JViewLegacy
{
	function __construct()
	{
		parent::__construct();
	}
	
	function display($tpl = null)
	{
		$this->companies = $this->get('UserCompanies');
		$this->item	 = $this->get('Item');
		$this->state = $this->get('State');
		$this->states = JBusinessDirectoryHelper::getStatuses();

		$this->translations = JBusinessDirectoryTranslations::getAllTranslations(EVENT_DESCRIPTION_TRANSLATION,$this->item->id);
		$this->languages = JBusinessUtil::getLanguages();
		$this->actions = JBusinessDirectoryHelper::getActions();
		
		//check if user has access to offer
		$user = JFactory::getUser();
		$found = false;
		foreach($this->companies as $company){
			if($company->userId == $user->id && $this->item->company_id == $company->id){
				$found = true;
			}
		}
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$this->categoryOptions = JBusinessUtil::getCategoriesOptions(true, CATEGORY_TYPE_EVENT);
		
		
		$lang = JFactory::getLanguage()->getTag();
		$key="";
		if(!empty($this->appSettings->google_map_key))
			$key="&key=".$this->appSettings->google_map_key;
		JHtml::_('script', "https://maps.googleapis.com/maps/api/js?libraries=places&language=".$lang.$key);
		
		//redirect if the user has no access and the event is not new
		if(!$found &&  $this->item->id !=0){
			$msg = JText::_("LNG_ACCESS_RESTRICTED");
			$app =JFactory::getApplication();
			$app->redirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanyevents', $msg));
		}
		
		parent::display($tpl);
	}
}
?>
