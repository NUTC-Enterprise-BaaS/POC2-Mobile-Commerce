<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;


jimport( 'joomla.application.component.view' );

class fsssViewticketemail extends JViewLegacy
{

	function display($tpl = null)
	{
		global $mainframe;

		$document = JFactory::getDocument();
		//JHTML::_( 'behavior.mootools' );
 		//$document->addStyleSheet( JURI::base() . 'components/com_fss/assets/slimbox/slimbox.css' );
		//$document->addScript( JURI::base() .'components/com_fss/assets/slimbox/slimbox.js');

		$item		= $this->get('Data');
		$isNew		= ($item->id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'TICKET_EMAIL_ACCOUNT' ).': <small><small>[ ' . $text.' ]</small></small>', 'fss_emailaccounts' );
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::save2new();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		FSSAdminHelper::DoSubToolbar();

		$db	= JFactory::getDBO();

		$combo = array();
		$combo[] = JHTML::_('select.option', 'pop3', JText::_('POP3'), 'id', 'value');
		$combo[] = JHTML::_('select.option', 'imap', JText::_('IMAP'), 'id', 'value');
		$lists['type'] = JHTML::_('select.genericlist',  $combo, 'type', 'class="inputbox" size="1" ', 'id', 'value', $item->type);
			
		$combo = array();
		$combo[] = JHTML::_('select.option', 'markread', JText::_('MARK_EMAIL_AS_READ'), 'id', 'value');
		$combo[] = JHTML::_('select.option', 'delete', JText::_('DELETE_EMAIL'), 'id', 'value');
		$lists['onimport'] = JHTML::_('select.genericlist',  $combo, 'onimport', 'class="inputbox" size="1" ', 'id', 'value', $item->onimport);
			
		$combo = array();
		$combo[] = JHTML::_('select.option', 'registered', JText::_('REGISTERED_USERS_ONLY'), 'id', 'value');
		$combo[] = JHTML::_('select.option', 'everyone', JText::_('EVERYONE'), 'id', 'value');
		$lists['newticketsfrom'] = JHTML::_('select.genericlist',  $combo, 'newticketsfrom', 'class="inputbox" size="1" ', 'id', 'value', $item->newticketsfrom);
			
		$query = 'SELECT id, title' .
			' FROM #__fss_prod' .
			' ORDER BY title';
		$db->setQuery($query);

		$sections_prod_id = $db->loadObjectList();
		$prods = array();
		$prods[] = JHTML::_('select.option', '', JText::_('NO_PRODUCT'), 'id', 'title');
		$sections_prod_id = array_merge($prods,$sections_prod_id);
		$lists['prod_id'] = JHTML::_('select.genericlist',  $sections_prod_id, 'prod_id', 'class="inputbox" size="1" ', 'id', 'title', intval($item->prod_id));
	
		$query = 'SELECT id, title' .
			' FROM #__fss_ticket_dept' .
			' ORDER BY title';
		$db->setQuery($query);

		$sections_dept_id = $db->loadObjectList();
		$prods = array();
		$prods[] = JHTML::_('select.option', '', JText::_('NO_DEPARTMENT'), 'id', 'title');
		$sections_dept_id = array_merge($prods,$sections_dept_id);
		$lists['dept_id'] = JHTML::_('select.genericlist',  $sections_dept_id, 'dept_id', 'class="inputbox" size="1" ', 'id', 'title', intval($item->dept_id));

		$query = 'SELECT id, title' .
			' FROM #__fss_ticket_cat ' .
			' ORDER BY title';
		$db->setQuery($query);

		$sections_cat_id = $db->loadObjectList();
		$prods = array();
		$prods[] = JHTML::_('select.option', '', JText::_('NO_CATEGORY'), 'id', 'title');
		$sections_cat_id = array_merge($prods,$sections_cat_id);
		$lists['cat_id'] = JHTML::_('select.genericlist',  $sections_cat_id, 'cat_id', 'class="inputbox" size="1" ', 'id', 'title', intval($item->cat_id));
		$query = 'SELECT id, title' .
			' FROM #__fss_ticket_pri' .
			' ORDER BY id';
		$db->setQuery($query);

		$sections_pri_id = $db->loadObjectList();
		
		$lists['pri_id'] = JHTML::_('select.genericlist',  $sections_pri_id, 'pri_id', 'class="inputbox" size="1" ', 'id', 'title', intval($item->pri_id));
	
		$sections_handler = SupportUsers::getHandlers();
		$prods = array();
		$prods[] = JHTML::_('select.option', '', JText::_('LEAVE_UNASSIGNED'), 'id', 'name');
		$prods[] = JHTML::_('select.option', '-1', JText::_('AUTO_ASSIGN'), 'id', 'name');
		$sections_handler = array_merge($prods,$sections_handler);
		$lists['handler'] = JHTML::_('select.genericlist',  $sections_handler, 'handler', 'class="inputbox" size="1" ', 'id', 'name', intval($item->handler));
			
		$combo = array();
		$combo[] = JHTML::_('select.option', '0', JText::_('UNSET'), 'id', 'value');
		$combo[] = JHTML::_('select.option', '1', JText::_('USE_TLS'), 'id', 'value');
		$combo[] = JHTML::_('select.option', '2', JText::_('DONT_USE_TLS'), 'id', 'value');
		$lists['usetls'] = JHTML::_('select.genericlist',  $combo, 'usetls', 'class="inputbox" size="1" ', 'id', 'value', $item->usetls);


		$this->item = $item;
		$this->lists = $lists;

		parent::display($tpl);
	}
}

