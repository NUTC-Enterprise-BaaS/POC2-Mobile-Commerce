<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );

class FsssViewTicketstatuss extends JViewLegacy
{
  
    function display($tpl = null)
    {
		JToolBarHelper::title( JText::_("TICKET_STATUS"), 'fss_ticketstatuss' );
        JToolBarHelper::deleteList();
        JToolBarHelper::editList();
        JToolBarHelper::addNew();
        JToolBarHelper::cancel('cancellist');
		FSSAdminHelper::DoSubToolbar();

        $this->lists = $this->get('Lists');
        $this->data = $this->get('Data');
        $this->pagination = $this->get('Pagination');

        parent::display($tpl);
    }
}



