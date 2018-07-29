<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
jimport('joomla.filesystem.folder');
require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss'.DS.'settings.php');



class FsssViewMainmenu extends JViewLegacy
{

	function display($tpl = null)
	{
		$mainmenu	= $this->get('Data');
		$isNew		= ($mainmenu->id < 1);

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("MENU_ITEM").': <small><small>[ ' . $text.' ]</small></small>', 'fss_menu' );
		JToolBarHelper::custom('translate','translate', 'translate', 'Translate', false);
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		FSSAdminHelper::DoSubToolbar();

		$this->mainmenu = $mainmenu;
		
		$path = JPATH_SITE.DS.'images'.DS.'fss'.DS.'menu';

		if (!file_exists($path))
			mkdir($path,0777,true);
		
		$files = JFolder::files($path,'(.png$|.jpg$|.jpeg$|.gif$)');
		
		$sections[] = JHTML::_('select.option', '', JText::_("NO_IMAGE"), 'id', 'title');
		foreach ($files as $file)
		{
			$sections[] = JHTML::_('select.option', $file, $file, 'id', 'title');
		}
				
		$lists['images'] = JHTML::_('select.genericlist',  $sections, 'icon', 'class="inputbox" size="1" ', 'id', 'title', $mainmenu->icon);

		if ($mainmenu->itemtype != 7)
		{
			$menus = FSS_GetMenus($mainmenu->itemtype);
			
			$menuitems = array();
			foreach ($menus as $menu)
			{
				$menuitems[] = JHTML::_('select.option', $menu->id . "|" . $menu->link, $menu->title . " (Itemid = ".$menu->id.")", 'id', 'title');
			}
			if (count($menuitems) > 1)
			{
				$lists['menuitems'] = JHTML::_('select.genericlist',  $menuitems, 'menuitem', 'class="inputbox" size="1" onchange="changeMenuItem();"', 'id', 'title', $mainmenu->itemid . "|" . $mainmenu->link);
			} else if (count($menuitems) == 1)
			{
				$lists['menuitems'] = JHTML::_('select.genericlist',  $menuitems, 'menuitem', 'class="inputbox" size="1" onchange="changeMenuItem();" style="display:none;"', 'id', 'title', $mainmenu->itemid . "|" . $mainmenu->link);
				$lists['menuitems'] = "<div><b>".JText::_('SINGLE_MENU_ITEM'). "</b> - " . $menuitems[0]->title . "</div>";	
			} else {
				$lists['menuitems'] = "<div><b>".JText::_('NO_MENU_ITEMS_FOUND')."</b></div>";	
			}
		}

		$types = array();
		$types[] = JHTML::_('select.option', '7', JText::_('IT_LINK'), 'id', 'title');
		for ($i = 1 ; $i < 11 ; $i++)
		{
			if ($i == 7) continue;
			$types[] = JHTML::_('select.option', $i, $this->ItemType($i), 'id', 'title');
		}
				
		$lists['types'] = JHTML::_('select.genericlist',  $types, 'itemtype', 'class="inputbox" size="1" ', 'id', 'title', $mainmenu->itemtype);
		
		$this->lists = $lists;

		parent::display($tpl);
	}
	
	function ItemType($id)
	{
		switch($id)
		{
		case 1:
			return JText::_("IT_KB");
		case 2:
			return JText::_("IT_FAQS");
		case 3:
			return JText::_("IT_TEST");
		case 4: 
			return JText::_("IT_NEW_TICKET");
		case 5:
			return JText::_("IT_VIEW_TICKET");
		case 6:
			return JText::_("IT_ANNOUNCE");
		case 7: 
			return JText::_("IT_LINK");
		case 8:
			return JText::_("IT_GLOSSARY");
		case 9:
			return JText::_("IT_ADMIN");
		case 10:
			return JText::_("IT_GROUPS");		
		}		
		return "Unknown";
	}
}


		 			 				   	