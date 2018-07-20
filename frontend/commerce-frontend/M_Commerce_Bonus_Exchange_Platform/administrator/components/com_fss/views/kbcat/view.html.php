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

class FsssViewKbcat extends JViewLegacy
{

	function display($tpl = null)
	{
		$kbcat		= $this->get('Data');
		$isNew		= ($kbcat->id < 1);

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("KNOWLEDGE_BASE_CATEGORY").': <small><small>[ ' . $text.' ]</small></small>', 'fss_categories' );
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

		$this->kbcat = $kbcat;
		
		$path = JPATH_SITE.DS.'images'.DS.'fss'.DS.'kbcats';

		if (!file_exists($path))
			mkdir($path,0777,true);
		
		$files = JFolder::files($path,'(.png$|.jpg$|.jpeg$|.gif$)');
		
		$sections = array();
		$sections[] = JHTML::_('select.option', '', JText::_("NO_IMAGE"), 'id', 'title');
		foreach ($files as $file)
		{
			$sections[] = JHTML::_('select.option', $file, $file, 'id', 'title');
		}
		
		$lists['images'] = JHTML::_('select.genericlist',  $sections, 'image', 'class="inputbox" size="1" ', 'id', 'title', $kbcat->image);

		$query = 'SELECT * ' .
				' FROM #__fss_kb_cat';
				
		if ($kbcat->id)
			$query .= " WHERE id != '".FSSJ3Helper::getEscaped($db, $kbcat->id)."' ";
			
		$query .= ' ORDER BY ordering';
		
		$db->setQuery($query);
		$sections = array();
		$sections[] = JHTML::_('select.option', '0', JText::_("NO_PARENT_CATEGORY"), 'id', 'title');
		
		$data = $db->loadObjectList();
		
		// nest the data
		require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'nested.php');
		$data = FSS_Nested_Helper::BuildNest($data, "id", "parcatid", "ordering");

		foreach ($data as &$temp)
		{
			$temp->title = str_repeat("|&mdash;&thinsp;", $temp->level) . $temp->title;
		}	
		
		$sections = array_merge($sections, $data);

		$lists['parcatid'] = JHTML::_('select.genericlist',  $sections, 'parcatid', 'class="inputbox" size="1" ', 'id', 'title', intval($kbcat->parcatid));


		$query = "SELECT * FROM #__fss_prod ORDER BY title";
		$db->setQuery($query);
		$products = $db->loadObjectList();

		$selprod = array();	
		
		if ($kbcat->prodids == "")
			$kbcat->allprods = 1;
		else
			$kbcat->allprods = 0;
		
		$this->allprods = $kbcat->allprods;
		
		$selprod = explode(";", $kbcat->prodids);
	
		$prodcheck = "";
		foreach($products as $product)
		{
			$checked = false;
			if (in_array($product->id,$selprod))
			{
				$prodcheck .= "<input type='checkbox' name='prod_" . $product->id . "' checked />" . $product->title . "<br>";
			} else {
				$prodcheck .= "<input type='checkbox' name='prod_" . $product->id . "' />" . $product->title . "<br>";
			}
		}
		$lists['products'] = $prodcheck;


		$lists['allprod'] = JHTML::_('select.booleanlist', 'allprods', 
		array('class' => "inputbox",
			'size' => "1", 
			'onclick' => "DoAllProdChange();"),
			intval($this->allprods));

		$this->lists = $lists;

		parent::display($tpl);
	}
}


