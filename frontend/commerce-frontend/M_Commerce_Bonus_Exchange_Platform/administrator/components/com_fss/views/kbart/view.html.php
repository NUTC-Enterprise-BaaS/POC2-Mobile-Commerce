<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );



class FsssViewKbart extends JViewLegacy
{

	function display($tpl = null)
	{
		if (JRequest::getString('task') == "prods")
			return $this->displayProds();
		
		$kbart		= $this->get('Data');
		$isNew		= ($kbart->id < 1);

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("KNOWLEDGE_BASE_ARTICLE").': <small><small>[ ' . $text.' ]</small></small>', 'fss_kb' );
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

		$this->kbart = $kbart;

		$query = 'SELECT id, title, parcatid, ordering' .
				' FROM #__fss_kb_cat' .
				' ORDER BY ordering';
		$db	= JFactory::getDBO();
		$db->setQuery($query);

		$sections[] = JHTML::_('select.option', '-1', '- '.JText::_("SELECT_CATEGORY").' -', 'id', 'title');
		$sections[] = JHTML::_('select.option', '0', JText::_("UNCATEGORIZED"), 'id', 'title');
		
		$data = $db->loadObjectList();
	
		// nest the data
		require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'nested.php');
		$data = FSS_Nested_Helper::BuildNest($data, "id", "parcatid", "ordering");

		foreach ($data as &$temp)
		{
			$temp->title = str_repeat("|&mdash;&thinsp;", $temp->level) . $temp->title;
		}	
		$sections = array_merge($sections, $data);

		$lists['catid'] = JHTML::_('select.genericlist',  $sections, 'kb_cat_id', 'class="inputbox" size="1" ', 'id', 'title', intval($kbart->kb_cat_id));
		$lists['allprod'] = JHTML::_('select.booleanlist', 'allprods', 
			array('class' => "inputbox",
				'size' => "1", 
				'onclick' => "DoAllProdChange();"),
			 intval($kbart->allprods));

		$query = "SELECT * FROM #__fss_prod ORDER BY title";
		$db->setQuery($query);
		$products = $db->loadObjectList();

		$query = "SELECT * FROM #__fss_kb_art_prod WHERE kb_art_id = " . FSSJ3Helper::getEscaped($db, $kbart->id);
		$db->setQuery($query);
		$selprod = $db->loadAssocList('prod_id');
		
		$this->assign('allprods',$kbart->allprods);
		
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
		
        if (!$isNew)
		{
			$db    = JFactory::getDBO();
			$query = 'SELECT *' .
				' FROM #__fss_kb_attach' .
				' WHERE kb_art_id = "' . FSSJ3Helper::getEscaped($db, $kbart->id) . '"' .
				' ORDER BY ordering, title ';
			
			$db->setQuery($query);
			
			$lists['files'] = $db->loadAssocList();
		} else {
			$lists['files'] = array();	
		}
       
 		$query = "SELECT a.id, a.title FROM #__fss_kb_art_related as r LEFT JOIN #__fss_kb_art as a ON r.related_id = a.id WHERE kb_art_id = " . FSSJ3Helper::getEscaped($db, $kbart->id);
		$db->setQuery($query);
		$lists['related'] = $db->loadAssocList();
		
		$this->lists = $lists;

		parent::display($tpl);
	}
	
	function displayProds()
	{
		$kb_art_id = JRequest::getInt('kb_art_id',0);
		$db	= JFactory::getDBO();

		$query = "SELECT * FROM #__fss_kb_art_prod as a LEFT JOIN #__fss_prod as p ON a.prod_id = p.id WHERE a.kb_art_id = '".FSSJ3Helper::getEscaped($db, $kb_art_id)."'";
		$db->setQuery($query);
		$products = $db->loadObjectList();
		
		$query = "SELECT * FROM #__fss_kb_art WHERE id = '".FSSJ3Helper::getEscaped($db, $kb_art_id)."'";
		$db->setQuery($query);
		$article = $db->loadObject();
				
		$this->article = $article;
		$this->products = $products;
		parent::display();
	}
}


		   	     	 		 