<?php
/*------------------------------------------------------------------------
# translation.php - OS Services Booking
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2014 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class OsAppscheduleTranslation{
	/**
	 * Default function
	 *
	 * @param unknown_type $option
	 */
	static function display($option,$task){
		global $mainframe;
		switch ($task){
			case "translation_list":
				self::translation_list($option);
			break;
			case "translation_save":
				self::translation_save($option);
			break;
				
		}
	}
	
	public static function getTotal($lang, $languageFile,$site){
		jimport('joomla.filesystem.file');
		$limitstart = JRequest::getInt('limitstart',0);
		$limit      = JRequest::getInt('limit',100);
		$app = JFactory::getApplication();
		$search = JRequest::getVar('search','');
		$search = JString::strtolower($search);
		$registry = new JRegistry();
		if($languageFile == "com_osservicesbooking"){
			if ($site == 1)
			{
				
				$languageFolder = JPATH_ROOT . '/administrator/language/';
			}
			else
			{
				$languageFolder = JPATH_ROOT . '/language/';
			}
		}else{
			$languageFolder = JPATH_ROOT . '/language/';
		}
		$path = $languageFolder . 'en-GB/en-GB.' . $languageFile . '.ini';
		
		$registry->loadFile($path, 'INI');
		$enGbItems = $registry->toArray();
		if ($search)
		{
			$search = strtolower($search);
			foreach ($enGbItems as $key => $value)
			{
				if (strpos(strtolower($key), $search) === false && strpos(strtolower($value), $search) === false)
				{
					unset($enGbItems[$key]);
				}
			}
		}
		
		return count($enGbItems);
	}
	
	/**
	 * Get pagination object
	 *
	 * @return JPagination
	 */
	public static function getPagination($lang, $item, $site)
	{
		// Lets load the content if it doesn't already exist
		if (empty($pagination))
		{
			jimport('joomla.html.pagination');
			$pagination = new JPagination(self::getTotal($lang, $item,$site), JRequest::getInt('limitstart',0), JRequest::getVar('limit',100));
		}
		
		return $pagination;
	}
	
	/**
	 * agent list
	 *
	 * @param unknown_type $option
	 */
	static function translation_list($option){
		
		global $mainframe;
		$db = JFactory::getDBO();
		$mainframe = & JFactory::getApplication() ;
		
		jimport('joomla.filesystem.file') ;
		jimport('joomla.filesystem.folder');
		$search				= JRequest::getVar('search','');
		$search				= JString::strtolower( $search );
		$lists['search'] = $search;
			
		$lang = JRequest::getVar('lang', '') ;
		if (!$lang)
			$lang = 'en-GB' ;
		$lists['lang'] = $lang;	
		$site = JRequest::getVar('site', 0) ;
		
		//$element = JRequest::getVar('element','com_osservicesbooking');
		
		$path = JPATH_ROOT.DS.'language' ;
		if ($site) $path = JPATH_ROOT.DS.'administrator'.DS.'language';
				
		$languages = self::getLanguages($path);		
		$options = array() ;
		$options[] = JHTML::_('select.option', '', JText::_('Select Language'))	;
		foreach ($languages as $language) {
			$options[] = JHTML::_('select.option', $language, $language) ;		
		}
		$lists['langs'] = JHTML::_('select.genericlist', $options, 'lang', ' class="input-small"  onchange="this.form.submit();" ', 'value', 'text', $lang) ;
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('Front-End')) ;
		$options[] = JHTML::_('select.option', 1, JText::_('Back-End')) ;
		$lists['site'] = JHTML::_('select.genericlist', $options, 'site', ' class="input-medium"  onchange="this.form.submit();" ', 'value', 'text', $site) ;
		
		$element = JRequest::getVar('element','com_osservicesbooking');
		$options = array();
		$options[] = JHtml::_('select.option','com_osservicesbooking','Component');
		if(JFolder::exists(JPATH_ROOT.'/modules/mod_osbsearch')){
			$options[] = JHtml::_('select.option','mod_osbsearch','Module OSB Search');
		}
		
		$lists['element_list'] = JHTML::_('select.genericlist', $options, 'element', ' class="input-medium"  onchange="this.form.submit();" ', 'value', 'text', $element) ;
		
		$item = JRequest::getVar('item', '') ;
		if (!$item) $item = 'com_osservicesbooking' ;
		$trans = self::getTrans($lang, $element, $site);
		
		$lists['item'] = $element;
		
		$pagination = self::getPagination($lang, $element,$site);
		
		HTML_OsAppscheduleTranslation::translation_list($option,$trans,$lists,$pagination);
	}
	
	
	/**
	 * get translate
	 *
	 * @param unknown_type $lang
	 * @param unknown_type $item
	 * @return unknown
	 */
	public static function getTrans($language, $languageFile,$site){
		jimport('joomla.filesystem.file');
		$limitstart = JRequest::getInt('limitstart',0);
		$limit      = JRequest::getInt('limit',100);
		$app = JFactory::getApplication();
		$search = JRequest::getVar('search','');
		$search = JString::strtolower($search);
		$registry = new JRegistry();
		if($languageFile == "com_osservicesbooking"){
			if ($site == 1)
			{
				
				$languageFolder = JPATH_ROOT . '/administrator/language/';
				//$languageFile = substr($languageFile, 6);
			}
			else
			{
				$languageFolder = JPATH_ROOT . '/language/';
			}
		}else{
			$languageFolder = JPATH_ROOT . '/language/';
		}
		
		$path = $languageFolder . 'en-GB/en-GB.' . $languageFile . '.ini';
		
		$registry->loadFile($path, 'INI');
		$enGbItems = $registry->toArray();
		
		if ($language != 'en-GB')
		{
			$translatedRegistry = new JRegistry();
			$translatedPath = $languageFolder . $language . '/' . $language . '.' . $languageFile . '.ini';
			if (JFile::exists($translatedPath))
			{
				$translatedRegistry->loadFile($translatedPath);
				$translatedLanguageItems = $translatedRegistry->toArray();
				//Remove unused language items
				$enGbKeys = array_keys($enGbItems);
				$changed = false;
				foreach ($translatedLanguageItems as $key => $value)
				{
					if (!in_array($key, $enGbKeys))
					{
						unset($translatedLanguageItems[$key]);
						$changed = true;
					}
				}
				if ($changed)
				{
					$translatedRegistry = new JRegistry();
					$translatedRegistry->loadArray($translatedLanguageItems);
				}
			}
			else
			{
				$translatedLanguageItems = array();
			}
			$translatedLanguageKeys = array_keys($translatedLanguageItems);
			foreach ($enGbItems as $key => $value)
			{
				if (!in_array($key, $translatedLanguageKeys))
				{
					$translatedRegistry->set($key, $value);
					$changed = true;
				}
			}
			JFile::write($translatedPath, $translatedRegistry->toString('INI'));
		}
		
		if ($search)
		{
			$search = strtolower($search);
			foreach ($enGbItems as $key => $value)
			{
				if (strpos(strtolower($key), $search) === false && strpos(strtolower($value), $search) === false)
				{
					unset($enGbItems[$key]);
				}
			}
		}
		//self::$_total = count($enGbItems);
		$data['en-GB'][$languageFile] = array_slice($enGbItems, $limitstart,$limit);
		if ($language != 'en-GB')
		{
			$path = $languageFolder . $language . '/' . $language . '.' . $languageFile . '.ini';
			
			if (JFile::exists($path))
			{
				$registry->loadFile($path);
				$languageItems = $registry->toArray();
				//$data[$language][$languageFile] = array_slice($languageItems, $limitstart, $limit);
				$translatedItems = array();
				foreach ($data['en-GB'][$languageFile] as $key => $value)
				{
					$translatedItems[$key] = isset($languageItems[$key]) ? $languageItems[$key] : '';
				}								
				$data[$language][$languageFile] = $translatedItems;
			}
			else
			{
				$data[$language][$languageFile] = array();
			}
		}
		return $data;
	}
	
	/**
	 * get option langguage of site
	 *
	 */
	static function getLanguages($path){
		jimport('joomla.filesystem.folder') ;
		$folders = JFolder::folders($path) ;
		$rets = array() ;
		foreach ($folders as $folder)
			if ($folder != 'pdf_fonts')
				$rets[] = $folder ;
		return $rets ;	
	}
	
	/**
	 * save agent
	 *
	 * @param unknown_type $option
	 */
	static function translation_save($option){
		global $mainframe,$configClass;
		$limitstart = JRequest::getInt('limitstart',0);
		$limit      = JRequest::getInt('limit',100);
		$site = JRequest::getVar('site','');
		$lang = JRequest::getVar('lang','');
		$search = JRequest::getVar('search','');
		$data = JRequest::get('post', JREQUEST_ALLOWHTML) ;
		jimport('joomla.filesystem.file');
		$language = $data['lang'];
		$languageFile = $data['element'];
		
		if($languageFile == "com_osservicesbooking"){
			if ($site == 1)
			{
				$languageFolder = JPATH_ROOT . '/administrator/language/';
			}
			else
			{
				$languageFolder = JPATH_ROOT . '/language/';
			}
		}else{
			$languageFolder = JPATH_ROOT . '/language/';
		}
		$registry = new JRegistry();
		$filePath = $languageFolder . $language . '/' . $language . '.' . $languageFile . '.ini';
		//echo $filePath;
		//die();
		if (JFile::exists($filePath))
		{
			$registry->loadFile($filePath, 'INI');
		}
		else
		{
			$registry->loadFile($languageFolder . 'en-GB/en-GB.' . $languageFile . '.ini', 'INI');
		}
		//Get the current language file and store it to array
		$keys = $data['keys'];
		$items = $data['items'];
		$content = "";
		foreach ($items as $item)
		{
			$item = trim($item);
			$value = trim($data['item_'.$item]);
			echo $keys[$item];
			echo "<BR />";
			$registry->set($keys[$item], $value);
		}
		if (isset($data['extra_keys']))
		{
			$keys = $data['extra_keys'];
			$values = $data['extra_values'];
			for ($i = 0, $n = count($keys); $i < $n; $i++)
			{
				$key = trim($keys[$i]);
				$value = trim($values[$i]);
				$registry->set($key, $value);
			}
		}
		
		if ($language != 'en-GB')
		{
			//We need to add new language items which are not existing in the current language
			$enRegistry = new JRegistry();
			$enRegistry->loadFile($languageFolder . 'en-GB/en-GB.' . $languageFile . '.ini', 'INI');
			$enLanguageItems = $enRegistry->toArray();
			$currentLanguageItems = $registry->toArray();
			foreach ($enLanguageItems as $key => $value)
			{
				$currentLanguageKeys = array_keys($currentLanguageItems);
				if (!in_array($key, $currentLanguageKeys))
				{					
					$registry->set($key, $value);
				}
			}
		}
		JFile::write($filePath, $registry->toString('INI'));
		$mainframe->redirect("index.php?option=com_osservicesbooking&task=translation_list&element=".$languageFile."&site=".$site."&lang=".$lang."&search=".$search."&limitstart=".$limitstart."&limit=".$limit);
	}
}
?>