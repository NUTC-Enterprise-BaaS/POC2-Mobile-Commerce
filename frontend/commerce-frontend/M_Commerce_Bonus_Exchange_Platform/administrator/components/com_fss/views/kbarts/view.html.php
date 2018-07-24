<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );



class FsssViewKbarts extends JViewLegacy
{
  
    function display($tpl = null)
    {
        JToolBarHelper::title( JText::_("KNOWLEDGE_BASE_ARTICLES"), 'fss_kb' );
		JToolBarHelper::custom('resetviews','resetviews', 'resetviews', 'KB_RESET_VIEWS', false);
		JToolBarHelper::custom('resetrating','resetrating', 'resetrating', 'KB_RESET_RATING', false);
		JToolBarHelper::custom('autosort','autosort', 'autosort', 'Auto Sort', false);
		JToolBarHelper::divider();
        JToolBarHelper::deleteList();
        JToolBarHelper::editList();
        JToolBarHelper::addNew();
        JToolBarHelper::cancel('cancellist');
		FSSAdminHelper::DoSubToolbar();

		$lists = $this->get('Lists');
        $this->data = $this->get('Data');
        $this->pagination = $this->get('Pagination');

		$query = 'SELECT id, title, parcatid, ordering FROM #__fss_kb_cat ORDER BY ordering';

		$db	= JFactory::getDBO();
		$categories[] = JHTML::_('select.option', '0', JText::_("SELECT_CATEGORY"), 'id', 'title');
		$db->setQuery($query);

		// nest the data
		$data = $db->loadObjectList();
		require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'nested.php');
		$data = FSS_Nested_Helper::BuildNest($data, "id", "parcatid", "ordering");

		foreach ($data as &$temp)
		{
			$temp->title = str_repeat("|&mdash;&thinsp;", $temp->level) . $temp->title;
		}	
		$categories = array_merge($categories, $data);

		$lists['cats'] = JHTML::_('select.genericlist',  $categories, 'kb_cat_id', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $lists['kb_cat_id']);

		$categories = array();
		$categories[] = JHTML::_('select.option', '-1', JText::_("IS_PUBLISHED"), 'id', 'title');
		$categories[] = JHTML::_('select.option', '1', JText::_("PUBLISHED"), 'id', 'title');
		$categories[] = JHTML::_('select.option', '0', JText::_("UNPUBLISHED"), 'id', 'title');
		$lists['published'] = JHTML::_('select.genericlist',  $categories, 'ispublished', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $lists['ispublished']);

		$query = 'SELECT id, title FROM #__fss_prod ORDER BY ordering';

		$db	= JFactory::getDBO();
		$categories = array();
		$categories[] = JHTML::_('select.option', '0', JText::_("SELECT_PRODUCT"), 'id', 'title');
		$db->setQuery($query);
		$categories = array_merge($categories, $db->loadObjectList());

		$lists['prods'] = JHTML::_('select.genericlist',  $categories, 'prod_id', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $lists['prod_id']);

		$this->lists = $lists;

		parent::display($tpl);
    }
}



