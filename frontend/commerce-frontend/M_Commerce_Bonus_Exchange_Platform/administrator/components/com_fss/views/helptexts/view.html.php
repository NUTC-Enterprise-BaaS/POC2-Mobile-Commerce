<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );


class FsssViewHelpTexts extends JViewLegacy
{
 
    function display($tpl = null)
    {
		JToolBarHelper::title( JText::_("Help Text Manager"), 'fss_helptexts' );
		
		JToolBarHelper::cancel('cancellist');
		
		FSSAdminHelper::DoSubToolbar();

        $lists =  $this->get('Lists');
        $this->data = $this->get('Data');
        $this->pagination = $this->get('Pagination');

		$query = 'SELECT `group` as id, `group` as title FROM #__fss_help_text GROUP BY `group` ORDER BY `group`';

		$db	= JFactory::getDBO();
		$categories[] = JHTML::_('select.option', '', JText::_("SELECT_CATEGORY"), 'id', 'title');
		$db->setQuery($query);
		$categories = array_merge($categories, $db->loadObjectList());

		$lists['groups'] = JHTML::_('select.genericlist',  $categories, 'group', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $lists['group']);

		$categories = array();
		$categories[] = JHTML::_('select.option', '-1', JText::_("IS_PUBLISHED"), 'id', 'title');
		$categories[] = JHTML::_('select.option', '1', JText::_("PUBLISHED"), 'id', 'title');
		$categories[] = JHTML::_('select.option', '0', JText::_("UNPUBLISHED"), 'id', 'title');
		$lists['published'] = JHTML::_('select.genericlist',  $categories, 'ispublished', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $lists['ispublished']);


		$this->lists = $lists;

        parent::display($tpl);
    }
}


