<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'settings.php');

class FSSAdminHelper
{
	static function PageSubTitle2($title,$usejtext = true)
	{
		// do something
		if ($usejtext)
			$title = JText::_($title);
		
		return str_replace("$1",$title,FSS_Settings::get('display_h3'));
	}
	
	static function IsFAQs()
	{
		if (JRequest::getVar('option') == "com_fsf")
			return true;
		return false;	
	}
	
	static function IsTests()
	{
		if (JRequest::getVar('option') == "com_fst")
			return true;
		return false;	
	}
	
	static function GetVersion($path = "")
	{
		
		global $fsj_version;
		if (empty($fsj_version))
		{
			if ($path == "") $path = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss';
			$file = $path.DS.'fss.xml';
			
			if (!file_exists($file))
				return FSS_Settings::get('version');
			
			$xml = simplexml_load_file($file);
			
			$fsj_version = $xml->version;
		}

		if ($fsj_version == "[VERSION]")
			return FSS_Settings::get('version');
			
		return $fsj_version;
	}	

	static function GetInstalledVersion()
	{
		return FSS_Settings::get('version');
	}

	static function toolbarHeader($name)
	{
		JSubMenuHelper::addEntry("<span class='side_header'>" . JText::_($name) . "</span>",'',false);
	}

	static function toolbarItem($name, $link, $view)
	{
		JSubMenuHelper::addEntry(
			JText::_($name),
			$link,
			JRequest::getCmd('view', 'fss') == $view || JRequest::getCmd('view', 'fsss') == $view . "s"
			);
	}
	
	static function DoSubToolbar($bare = false)
	{
		if (!$bare)
		{
			if (JFactory::getUser()->authorise('core.admin', 'com_fss'))    
			{        
				JToolBarHelper::preferences('com_fss');
			}
			JToolBarHelper::divider();
			JToolBarHelper::help("",false,"http://www.freestyle-joomla.com/comhelp/fss/admin-view-" . JRequest::getVar('view'));
		}
		
		self::toolbarItem("COM_FSS_OVERVIEW","index.php?option=com_fss","fss");

		self::toolbarHeader("SETTINGS");
		self::toolbarItem("SETTINGS","index.php?option=com_fss&view=settings","settings");
		self::toolbarItem("TEMPLATES","index.php?option=com_fss&view=templates","templates");
		self::toolbarItem("VIEW_SETTINGS","index.php?option=com_fss&view=settingsview","settingsview");
		
		self::toolbarHeader("GENERAL");
		self::toolbarItem("PERMISSIONS","index.php?option=com_fss&view=fusers","fuser");
		self::toolbarItem("EMAIL_TEMPLATES","index.php?option=com_fss&view=emails","email");
		self::toolbarItem("CUSTOM_FIELDS","index.php?option=com_fss&view=fields","field");
		self::toolbarItem("MAIN_MENU_ITEMS","index.php?option=com_fss&view=mainmenus","mainmenu");
		self::toolbarItem("MODERATION","index.php?option=com_fss&view=tests","test");
					
		self::toolbarHeader("SUPPORT_TICKETS");
		self::toolbarItem("PRODUCTS","index.php?option=com_fss&view=prods","prod");
		self::toolbarItem("CATEGORIES","index.php?option=com_fss&view=ticketcats","ticketcat");
		self::toolbarItem("DEPARTMENTS","index.php?option=com_fss&view=ticketdepts","ticketdept");
		self::toolbarItem("PRIORITIES","index.php?option=com_fss&view=ticketpris","ticketpri");
		self::toolbarItem("GROUPS","index.php?option=com_fss&view=ticketgroups","ticketgroup");
		self::toolbarItem("STATUSES","index.php?option=com_fss&view=ticketstatuss","ticketstatus");
		self::toolbarItem("TICKETS_EMAIL_ACCOUNTS","index.php?option=com_fss&view=ticketemails","ticketemail");
		self::toolbarItem("HELP_TEXT","index.php?option=com_fss&view=helptexts","helptext");
		
		self::toolbarHeader("OTHER");
		self::toolbarItem("COM_FSS_KB_CATS","index.php?option=com_fss&view=kbcats","kbcat");
		self::toolbarItem("COM_FSS_KB_ARTICLES","index.php?option=com_fss&view=kbarts","kbart");
		self::toolbarItem("COM_FSS_FAQ_CATEGORIES","index.php?option=com_fss&view=faqcats","faqcat");
		self::toolbarItem("COM_FSS_FAQS","index.php?option=com_fss&view=faqs","faq");
		self::toolbarItem("ANNOUNCEMENTS","index.php?option=com_fss&view=announces","announce");
		self::toolbarItem("GLOSSARY_ITEMS","index.php?option=com_fss&view=glossarys","glossary");
		
		self::toolbarHeader("COM_FSS_ADMIN");
		self::toolbarItem("LOG","index.php?option=com_fss&view=cronlog","cronlog");
		self::toolbarItem("EMAIL_LOG","index.php?option=com_fss&view=emaillog","emaillog");
		self::toolbarItem("TICKET_ATTACH_CLEANUP","index.php?option=com_fss&view=attachclean","attachclean");
		self::toolbarItem("COM_FSS_ADMIN","index.php?option=com_fss&view=backup","backup");
		self::toolbarItem("PLUGINS","index.php?option=com_fss&view=plugins","plugins");

	}	
	
	
	static function IncludeHelp($file)
	{
		$lang = JFactory::getLanguage();
		$tag = $lang->getTag();
		
		$path = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss'.DS.'help'.DS.$tag.DS.$file;
		if (file_exists($path))
			return file_get_contents($path);
		
		$path = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss'.DS.'help'.DS.'en-GB'.DS.$file;
		
		return file_get_contents($path);
	}
	
	static $langs;
	static $lang_bykey;
	static function DisplayLanguage($language)
	{
		if (empty(FSSAdminHelper::$langs))
		{
			FSSAdminHelper::LoadLanguages();
		}
		
		if (array_key_exists($language, FSSAdminHelper::$lang_bykey))
			return FSSAdminHelper::$lang_bykey[$language]->text;
		
		return "";
	}
	
	static function LoadLanguages()
	{		
		$deflang = new stdClass();
		$deflang->value = "*";
		$deflang->text = JText::_('JALL');
		
		FSSAdminHelper::$langs = array_merge(array($deflang) ,JHtml::_('contentlanguage.existing'));
		
		foreach (FSSAdminHelper::$langs as $lang)
		{
			FSSAdminHelper::$lang_bykey[$lang->value] = $lang;	
		}		
	}
	
	static function GetLanguagesForm($value)
	{
		if (empty(FSSAdminHelper::$langs))
		{
			FSSAdminHelper::LoadLanguages();
		}
		
		return JHTML::_('select.genericlist',  FSSAdminHelper::$langs, 'language', 'class="inputbox" size="1" ', 'value', 'text', $value);
	}
	
	static $access_levels;
	static $access_levels_bykey;
	
	static function DisplayAccessLevel($access)
	{
		if (empty(FSSAdminHelper::$access_levels))
		{
			FSSAdminHelper::LoadAccessLevels();
		}
		
		if (array_key_exists($access, FSSAdminHelper::$access_levels_bykey))
			return FSSAdminHelper::$access_levels_bykey[$access];
		
		return "";
		
	}
	
	static function LoadAccessLevels()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('a.id AS value, a.title AS text');
		$query->from('#__viewlevels AS a');
		$query->group('a.id, a.title, a.ordering');
		$query->order('a.ordering ASC');
		$query->order($query->qn('title') . ' ASC');

		$key = '446c15feffb6cac88c37b7a0c5fb290c';

		// Get the options.
		$db->setQuery($query);
		FSSAdminHelper::$access_levels = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());
			return null;
		}

		foreach (FSSAdminHelper::$access_levels as $al)
		{
			FSSAdminHelper::$access_levels_bykey[$al->value] = $al->text;
		}	
	}
	
	static function GetAccessForm($value)
	{
		return JHTML::_('access.level',	'access',  $value, 'class="inputbox" size="1"', false);
	}
	
	static $filter_lang;
	static $filter_access;
	static function LA_GetFilterState()
	{
		$mainframe = JFactory::getApplication();
		FSSAdminHelper::$filter_lang	= $mainframe->getUserStateFromRequest( 'la_filter.'.'fss_filter_language', 'fss_filter_language', '', 'string' );
		FSSAdminHelper::$filter_access	= $mainframe->getUserStateFromRequest( 'la_filter.'.'fss_filter_access', 'fss_filter_access', 0, 'int' );
	}
	
	static function LA_Filter($nolangs = false)
	{
		if (empty(FSSAdminHelper::$access_levels))
		{
			FSSAdminHelper::LoadAccessLevels();
		}
		
		if (!$nolangs && empty(FSSAdminHelper::$langs))
		{
			FSSAdminHelper::LoadLanguages();
		}
	
		if (empty(FSSAdminHelper::$filter_lang))
		{
			FSSAdminHelper::LA_GetFilterState();
		}
		
		$options = FSSAdminHelper::$access_levels;		
		array_unshift($options, JHtml::_('select.option', 0, JText::_('JOPTION_SELECT_ACCESS')));
		echo JHTML::_('select.genericlist',  $options, 'fss_filter_access', 'class="inputbox" size="1"  onchange="document.adminForm.submit( );"', 'value', 'text', FSSAdminHelper::$filter_access);
		
		if (!$nolangs)
		{
			$options = FSSAdminHelper::$langs;		
			array_unshift($options, JHtml::_('select.option', '', JText::_('JOPTION_SELECT_LANGUAGE')));
			echo JHTML::_('select.genericlist',  $options, 'fss_filter_language', 'class="inputbox" size="1"  onchange="document.adminForm.submit( );"', 'value', 'text', FSSAdminHelper::$filter_lang);
		}
	}
	
	static function LA_Header($obj, $nolangs = false)
	{
		if (!$nolangs)
		{
			?>
 			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'LANGUAGE', 'language', @$obj->lists['order_Dir'], @$obj->lists['order'] ); ?>
			</th>
			<?php
		}
			
		?>
 		<th width="1%" nowrap="nowrap">
			<?php echo JHTML::_('grid.sort',   'ACCESS_LEVEL', 'access', @$obj->lists['order_Dir'], @$obj->lists['order'] ); ?>
		</th>
		<?php
	}
	
	static function LA_Row($row, $nolangs = false)
	{
		if (!$nolangs)
		{
			?>
			<td>
				<?php echo FSSAdminHelper::DisplayLanguage($row->language); ?></a>
			</td>
			<?php
		}
			
		?>
		<td>
			<?php echo FSSAdminHelper::DisplayAccessLevel($row->access); ?></a>
		</td>
		<?php
	}
	
	static function LA_Form($item, $nolangs = false)
	{
		?>
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
					<?php echo JText::_("JFIELD_ACCESS_LABEL"); ?>:
				</label>
			</td>
			<td>
				<?php echo FSSAdminHelper::GetAccessForm($item->access); ?>
			</td>
		</tr>
			
		<?php
		if (!$nolangs)
		{
		?>

			<tr>
				<td width="135" align="right" class="key">
					<label for="title">
						<?php echo JText::_("JFIELD_LANGUAGE_LABEL"); ?>:
					</label>
				</td>
				<td>
					<?php echo FSSAdminHelper::GetLanguagesForm($item->language); ?>
				</td>
			</tr>
				
		<?php
		}
	}
	
	static function HTMLDisplay($text, $chars = 100)
	{
		$stripped = strip_tags($text);
		$output = substr($stripped, 0, $chars); 
		if (strlen($stripped) > $chars)	$output .= "&hellip;";	

		return $output;
	}
}