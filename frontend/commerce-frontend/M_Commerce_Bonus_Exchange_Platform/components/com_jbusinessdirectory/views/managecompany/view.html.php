<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php');

JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/joomlatabs.css');
JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/common.js');

JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/manage.companies.js');
JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.upload.js');

JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/jquery.timepicker.css');
JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.timepicker.min.js');

JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/chosen.jquery.min.js');
JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/chosen.css');

JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/dropzone/dropzone.js');
JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/dropzone/jbusinessImageUploader.js');

JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/dropzone.css');
JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/basic.css');

JBusinessUtil::includeValidation();

class JBusinessDirectoryViewManageCompany extends JViewLegacy
{

	function __construct()
	{
		parent::__construct();
	}
	
	
	function display($tpl = null)
	{
		$this->item = $this->get('Item'); 
		$this->state = $this->get('State');
		$this->total = $this->get('Total');
		
		$this->translations = JBusinessDirectoryTranslations::getAllTranslations(BUSSINESS_DESCRIPTION_TRANSLATION,$this->item->id);
		$this->translationsSlogan = JBusinessDirectoryTranslations::getAllTranslations(BUSSINESS_SLOGAN_TRANSLATION,$this->item->id);
		$this->languages = JBusinessUtil::getLanguages();
		
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$this->categoryOptions = JBusinessUtil::getCategoriesOptions(true, CATEGORY_TYPE_BUSINESS);
	
		//get all upgrade packages - cannot downgrade
		$price = 0;
		if(!empty($this->item->lastActivePackage) && $this->item->lastActivePackage->expired == false){
			$price = $this->item->lastActivePackage->price;
		}
		$this->packageOptions = JBusinessDirectoryHelper::getPackageOptions($price);
		$this->actions = JBusinessDirectoryHelper::getActions();
		
		$this->location = $this->get('Location');
		$user = JFactory::getUser();
		if($this->item->userId != $user->id && $this->item->id !=0){
			$msg = JText::_("LNG_ACCESS_RESTRICTED");
			$app =JFactory::getApplication();
			$app->redirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanies', $msg));
		}
		
		$lang = JFactory::getLanguage()->getTag();
		$key="";
		if(!empty($this->appSettings->google_map_key))
			$key="&key=".$this->appSettings->google_map_key;
		JHtml::_('script', "https://maps.googleapis.com/maps/api/js?libraries=places&language=".$lang.$key);
		
		$layout = JRequest::getVar("layout");

		if(!empty($layout))
			$this->setLayout($layout);
		
	
		if($this->total >= $this->appSettings->max_business && !empty($this->appSettings->max_business) && $this->item->id == 0){
			$msg = JText::_("LNG_MAX_BUSINESS_LISTING_REACHED");
			$app =JFactory::getApplication();
			$app->redirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanies', $msg));
		}
		
		if($this->appSettings->enable_packages && empty($this->packageOptions)){
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_("LNG_NO_ACTIVE_PACKAGE"), 'warning');
			$app->redirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanies',false));
		}
		
		parent::display($tpl);
	}
	
	function displayCompanyCategories($categories, $level){
		ob_start();
		?>
			
		<select class="category-box" id="category<?php echo $level ?>"
				onchange ="displaySubcategories('category<?php echo $level ?>',<?php echo $level ?>,4)">
			<option value=""></option>	
		<?php 
			foreach ($categories as $cat){
				if(isset($cat[0]->name)){?>
					<option value="<?php echo $cat[0]->id?>"><?php echo $cat[0]->name?></option>
						
					<?php  
					}
				}
			?>
			</select>
			<?php 
			$buff = ob_get_contents();
			ob_end_clean();
			return $buff;
	}
		
	function displayCompanyCategoriesOptions($categories){
		ob_start();
		foreach ($categories as $cat){
			if(isset($cat[0]->name)){?>
				<option value="<?php echo $cat[0]->id?>"><?php echo $cat[0]->name?></option>
				<?php  
				}
			}

		$buff = ob_get_contents();
		ob_end_clean();
		return $buff;
	}
}
?>
