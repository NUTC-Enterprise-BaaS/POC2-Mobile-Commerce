<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;


jimport( 'joomla.application.component.view' );



class FsssViewTicketgroup extends JViewLegacy
{

	function display($tpl = null)
	{
		$ticketgroup		= $this->get('Data');
		$isNew		= ($ticketgroup->id < 1);

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("TICKET_GROUP").': <small><small>[ ' . $text.' ]</small></small>', 'fss_groups' );
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::save2new();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		FSSAdminHelper::DoSubToolbar();
		$db	= JFactory::getDBO();
		
		
		$idents = array();
		$idents[] = JHTML::_('select.option', '0', JText::_("VIEW_NONE"), 'id', 'title');
		$idents[] = JHTML::_('select.option', '1', JText::_("VIEW"), 'id', 'title');
		$idents[] = JHTML::_('select.option', '2', JText::_("VIEW_REPLY"), 'id', 'title');			
		$idents[] = JHTML::_('select.option', '3', JText::_("VIEW_REPLY_CLOSE"), 'id', 'title');			
		$this->allsee = JHTML::_('select.genericlist',  $idents, 'allsee', ' class="inputbox" size="1"', 'id', 'title', $ticketgroup->allsee);

		$this->ticketgroup = $ticketgroup;


		$this->allprod = JHTML::_('select.booleanlist', 'allprods', 
			array('class' => "inputbox",
				'size' => "1", 
				'onclick' => "DoAllProdChange();"),
			 intval($ticketgroup->allprods));

		$query = "SELECT * FROM #__fss_prod ORDER BY title";
		$db->setQuery($query);
		$products = $db->loadObjectList();

		$query = "SELECT * FROM #__fss_ticket_group_prod WHERE group_id = " . FSSJ3Helper::getEscaped($db, $ticketgroup->id);
		$db->setQuery($query);
		$selprod = $db->loadAssocList('prod_id');
		
		$this->assign('allprods',$ticketgroup->allprods);
		
		$prodcheck = "";
		foreach($products as $product)
		{
			$checked = false;
			if (array_key_exists($product->id,$selprod))
			{
				$prodcheck .= "<input type='checkbox' name='prod_" . $product->id . "' checked />" . $product->title . "<br>";
			} else {
				$prodcheck .= "<input type='checkbox' name='prod_" . $product->id . "' />" . $product->title . "<br>";
			}
		}
		$this->products = $prodcheck;
	
		parent::display($tpl);
	}
}


