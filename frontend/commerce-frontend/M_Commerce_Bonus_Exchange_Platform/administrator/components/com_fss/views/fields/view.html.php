<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php');

class FsssViewFields extends JViewLegacy
{
 
    function display($tpl = null)
    {
		$this->comments = new FSS_Comments(null,null);
		
        JToolBarHelper::title( JText::_("FIELDS"), 'fss_customfields' );
        JToolBarHelper::deleteList();
        JToolBarHelper::editList();
        JToolBarHelper::addNew();
        JToolBarHelper::cancel('cancellist');
		FSSAdminHelper::DoSubToolbar();

		$lists = $this->get('Lists');

        $this->data = $this->get('Data');
        $this->pagination = $this->get('Pagination');
		
		$idents = array();
		$idents[] = JHTML::_('select.option', '-1', JText::_("ALL"), 'id', 'title');
		$idents[] = JHTML::_('select.option', '0', JText::_("TICKETS"), 'id', 'title');
		$idents[] = JHTML::_('select.option', '999', JText::_("ALL_COMMENTS"), 'id', 'title');
		$db	= JFactory::getDBO();
		foreach($this->comments->handlers as $handler)
			$idents[] = JHTML::_('select.option', $handler->ident, $handler->GetLongDesc(), 'id', 'title');
				
		$lists['ident'] = JHTML::_('select.genericlist',  $idents, 'ident', ' class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $lists['ident']);
	
        $this->lists = $lists;
        
		parent::display($tpl);
    }
	
	function GetIdentLabel($sectionid)
	{
		if ($sectionid < 1)
			return JText::_("TICKETS");		
			
		if ($sectionid == 999)
			return JText::_("ALL_COMMENTS");
			
		if (array_key_exists($sectionid,$this->comments->handlers))
			return $this->comments->handlers[$sectionid]->GetLongDesc();
			
		return "Unknown";
	}
	
	function GetTypeLabel($type, &$row)
	{
		if ($type == "checkbox")	
			return JText::_("CHECKBOX");
		if ($type == "text")	
			return JText::_("TEXT_ENTRY");
		if ($type == "radio")	
			return JText::_("RADIO_GROUP");
		if ($type == "combo")	
			return JText::_("COMBO_BOX");
		if ($type == "area")	
			return JText::_("TEXT_AREA");
		if ($type == "plugin")
		{
			$plugin = FSSCF::get_plugin_from_row($row);
			return JText::_("PLUGIN") . " - " . $plugin->name;
		}
		return $type;		
	}
}