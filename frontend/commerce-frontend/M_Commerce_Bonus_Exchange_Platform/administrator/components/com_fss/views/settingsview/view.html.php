<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss'.DS.'settings.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'parser.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'fields.php');


class FsssViewSettingsView extends JViewLegacy
{
	
	function display($tpl = null)
	{
		JHTML::_('behavior.modal');
		
		$document = JFactory::getDocument();
		$document->addScriptDeclaration("\nvar fss_settings_url = '" . JRoute::_('index.php?option=com_fss&view=settings', false) . "';\n");
		$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/settings.js'); 

		$what = JRequest::getString('what','');
		$this->tab = JRequest::getVar('tab');
		
		if (JRequest::getVar('task') == "cancellist")
		{
			$mainframe = JFactory::getApplication();
			$link = FSSRoute::_('index.php?option=com_fss&view=fsss',false);
			$mainframe->redirect($link);
			return;			
		}
		
		$settings = FSS_Settings::GetAllViewSettings(); // CHANGE
		$db	= JFactory::getDBO();

		if ($what == "save")
		{
			$data = JRequest::get('POST',JREQUEST_ALLOWRAW);

			foreach ($data as $setting => $value)
				if (array_key_exists($setting,$settings))
				{
					$settings[$setting] = $value;
				}
			
			foreach ($settings as $setting => $value)
			{
				if (!array_key_exists($setting,$data))
				{
					$settings[$setting] = 0;
					$value = 0;	
				}
				
				$qry = "REPLACE INTO #__fss_settings_view (setting, value) VALUES ('";
				$qry .= FSSJ3Helper::getEscaped($db, $setting) . "','";
				$qry .= FSSJ3Helper::getEscaped($db, $value) . "')";
				$db->setQuery($qry);$db->Query();
			}

			$link = 'index.php?option=com_fss&view=settingsview#' . $this->tab;
			
			if (JRequest::getVar('task') == "save")
				$link = 'index.php?option=com_fss';

			$mainframe = JFactory::getApplication();
			$mainframe->redirect($link, JText::_("View_Settings_Saved"));		
			exit;
		} else {
		
			$document = JFactory::getDocument();
			//$document->addStyleSheet(JURI::root().'administrator/components/com_fss/assets/css/js_color_picker_v2.css'); 
			//$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/color_functions.js'); 
			//$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/js_color_picker_v2.js'); 

			$this->settings = $settings;

			JToolBarHelper::title( JText::_("FREESTYLE_SUPPORT_PORTAL") .' - '. JText::_("VIEW_SETTINGS") , 'fss_viewsettings' );
			JToolBarHelper::apply();
			JToolBarHelper::save();
			JToolBarHelper::cancel('cancellist');
			FSSAdminHelper::DoSubToolbar();
			parent::display($tpl);
		}
	}

	function ParseParams(&$aparams)
	{
		$out = array();
		$bits = explode(";",$aparams);
		foreach ($bits as $bit)
		{
			if (trim($bit) == "") continue;
			$res = explode(":",$bit,2);
			if (count($res) == 2)
			{
				$out[$res[0]] = $res[1];	
			}
		}
		return $out;	
	}

}


