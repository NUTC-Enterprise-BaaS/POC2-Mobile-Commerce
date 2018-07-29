<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;


jimport( 'joomla.application.component.view' );



class FsssViewTicketcat extends JViewLegacy
{

	function display($tpl = null)
	{
		if (JRequest::getString('task') == "prods")
			return $this->displayProds();

		if (JRequest::getString('task') == "depts")
			return $this->displayDepts();

		$ticketcat		= $this->get('Data');
		$isNew		= ($ticketcat->id < 1);

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("TICKET_CATEGORY").': <small><small>[ ' . $text.' ]</small></small>', 'fss_categories' );
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

		$this->ticketcat = $ticketcat;
		
		$db	= JFactory::getDBO();
		
		
		$lists['allprod'] = JHTML::_('select.booleanlist', 'allprods', 
			array('class' => "inputbox",
					'size' => "1", 
					'onclick' => "DoAllProdChange();"),
				intval($ticketcat->allprods));

		$query = "SELECT * FROM #__fss_prod ORDER BY title";
		$db->setQuery($query);
		$products = $db->loadObjectList();

		$query = "SELECT * FROM #__fss_ticket_cat_prod WHERE ticket_cat_id = " . FSSJ3Helper::getEscaped($db, $ticketcat->id);
		$db->setQuery($query);
		$selprod = $db->loadAssocList('prod_id');
		
		$this->assign('allprods',$ticketcat->allprods);
		
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
		
		
		
		$lists['alldept'] = JHTML::_('select.booleanlist', 'alldepts', 
			array('class' => "inputbox",
					'size' => "1", 
					'onclick' => "DoAllDeptChange();"),
				intval($ticketcat->alldepts));

		$query = "SELECT * FROM #__fss_ticket_dept ORDER BY title";
		$db->setQuery($query);
		$departments = $db->loadObjectList();

		$query = "SELECT * FROM #__fss_ticket_cat_dept WHERE ticket_cat_id = " . FSSJ3Helper::getEscaped($db, $ticketcat->id);
		$db->setQuery($query);
		$seldept = $db->loadAssocList('ticket_dept_id');
		
		$this->assign('alldepts',$ticketcat->alldepts);
		
		$deptcheck = "";
		foreach($departments as $department)
		{
			$checked = false;
			if (array_key_exists($department->id,$seldept))
			{
				$deptcheck .= "<input type='checkbox' name='dept_" . $department->id . "' checked />" . $department->title . "<br>";
			} else {
				$deptcheck .= "<input type='checkbox' name='dept_" . $department->id . "' />" . $department->title . "<br>";
			}
		}
		$lists['departments'] = $deptcheck;
		
		
		$this->lists = $lists;

		parent::display($tpl);
	}
	
	function displayProds()
	{
		$ticket_cat_id = JRequest::getInt('ticket_cat_id',0);
		$db	= JFactory::getDBO();

		$query = "SELECT * FROM #__fss_ticket_cat_prod as a LEFT JOIN #__fss_prod as p ON a.prod_id = p.id WHERE a.ticket_cat_id = '".FSSJ3Helper::getEscaped($db, $ticket_cat_id)."'";
		$db->setQuery($query);
		$products = $db->loadObjectList();
		
		$query = "SELECT * FROM #__fss_ticket_cat WHERE id = '".FSSJ3Helper::getEscaped($db, $ticket_cat_id)."'";
		$db->setQuery($query);
		$category = $db->loadObject();
		
		$this->category = $category;
		$this->products = $products;
		parent::display();
	}

	
	function displayDepts()
	{
		$ticket_cat_id = JRequest::getInt('ticket_cat_id',0);
		$db	= JFactory::getDBO();

		$query = "SELECT * FROM #__fss_ticket_cat_dept as a LEFT JOIN #__fss_ticket_dept as p ON a.ticket_dept_id = p.id WHERE a.ticket_cat_id = '".FSSJ3Helper::getEscaped($db, $ticket_cat_id)."'";
		$db->setQuery($query);
		$departments = $db->loadObjectList();
		
		$query = "SELECT * FROM #__fss_ticket_cat WHERE id = '".FSSJ3Helper::getEscaped($db, $ticket_cat_id)."'";
		$db->setQuery($query);
		$category = $db->loadObject();
		
		$this->category = $category;
		$this->departments = $departments;
		parent::display();
	}

}


