<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );


class FsssViewMainmenus extends JViewLegacy
{
    function display($tpl = null)
    {
        JToolBarHelper::title( JText::_("MAIN_MENU_ITEMS"), 'fss_menu' );
		JToolBarHelper::custom('convert','copy', 'copy', 'CONVERT_INI', false);
		JToolBarHelper::spacer();
        JToolBarHelper::deleteList();
        JToolBarHelper::editList();
        JToolBarHelper::addNew();
        JToolBarHelper::cancel('cancellist');
		FSSAdminHelper::DoSubToolbar();

        $this->lists = $this->get('Lists');
        $this->data = $this->get('Data');
        $this->pagination = $this->get('Pagination');

		$categories = array();
		$categories[] = JHTML::_('select.option', '-1', JText::_("IS_PUBLISHED"), 'id', 'title');
		$categories[] = JHTML::_('select.option', '1', JText::_("PUBLISHED"), 'id', 'title');
		$categories[] = JHTML::_('select.option', '0', JText::_("UNPUBLISHED"), 'id', 'title');
		$this->lists['published'] = JHTML::_('select.genericlist',  $categories, 'ispublished', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $this->lists['ispublished']);

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



