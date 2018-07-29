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



class FsssViewTicketdept extends JViewLegacy
{

	function display($tpl = null)
	{
		if (JRequest::getString('task') == "prods")
			return $this->displayProds();

		$ticketdept		= $this->get('Data');
		$isNew		= ($ticketdept->id < 1);

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("TICKET_DEPARTMENT").': <small><small>[ ' . $text.' ]</small></small>', 'fss_ticketdepts' );
		JToolBarHelper::custom('translate','translate', 'translate', 'Translate', false);
		JToolBarHelper::spacer();
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
		
		$lists['allprod'] = JHTML::_('select.booleanlist', 'allprods', 
			array('class' => "inputbox",
					'size' => "1", 
					'onclick' => "DoAllProdChange();"),
				intval($ticketdept->allprods));

		$query = "SELECT * FROM #__fss_prod ORDER BY title";
		$db->setQuery($query);
		$products = $db->loadObjectList();

		$query = "SELECT * FROM #__fss_ticket_dept_prod WHERE ticket_dept_id = " . FSSJ3Helper::getEscaped($db, $ticketdept->id);
		$db->setQuery($query);
		$selprod = $db->loadAssocList('prod_id');
		
		$this->assign('allprods',$ticketdept->allprods);
		
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
		$lists['products'] = $prodcheck;

		
		$path = JPATH_SITE.DS.'images'.DS.'fss'.DS.'departments';

		if (!file_exists($path))
			mkdir($path,0777,true);
		
		$files = JFolder::files($path,'(.png$|.jpg$|.jpeg$|.gif$)');
		
		$sections[] = JHTML::_('select.option', '', JText::_("NO_IMAGE"), 'id', 'title');
		foreach ($files as $file)
		{
			$sections[] = JHTML::_('select.option', $file, $file, 'id', 'title');
		}
				
		$lists['images'] = JHTML::_('select.genericlist',  $sections, 'image', 'class="inputbox" size="1" ', 'id', 'title', $ticketdept->image);



		$this->lists = $lists;

		$this->ticketdept = $ticketdept;

		parent::display($tpl);
	}
	
	function displayProds()
	{
		$ticket_dept_id = JRequest::getInt('ticket_dept_id',0);
		$db	= JFactory::getDBO();

		$query = "SELECT * FROM #__fss_ticket_dept_prod as a LEFT JOIN #__fss_prod as p ON a.prod_id = p.id WHERE a.ticket_dept_id = ".FSSJ3Helper::getEscaped($db, $ticket_dept_id);
		$db->setQuery($query);
		$products = $db->loadObjectList();
		
		$query = "SELECT * FROM #__fss_ticket_dept WHERE id = '".FSSJ3Helper::getEscaped($db, $ticket_dept_id)."'";
		$db->setQuery($query);
		$department = $db->loadObject();
		
		$this->department = $department;
		$this->products = $products;
		parent::display();
	}

}


