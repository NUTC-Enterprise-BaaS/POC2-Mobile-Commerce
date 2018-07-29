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

class FsssViewField extends JViewLegacy
{
	function display($tpl = null)
	{
		$this->comments = new FSS_Comments(null,null);

		if (JRequest::getString('task') == "prods")
			return $this->displayProds();
		
		if (JRequest::getString('task') == "depts")
			return $this->displayDepts();
		
		if (JRequest::getString('task') == "plugin_form")
			return $this->pluginForm();
		
		$field 	= $this->get('Data');
		$isNew		= ($field->id < 1);

		$db	= JFactory::getDBO();

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("FIELD").': <small><small>[ ' . $text.' ]</small></small>', 'fss_customfields' );
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

		//  User product selection
		$this->assign('allprod', JHTML::_('select.booleanlist', 'allprods', 
			array('class' => "inputbox",
						'size' => "1", 
						'onclick' => "DoAllProdChange();"),
					intval($field->allprods)) );

		$query = "SELECT * FROM #__fss_prod ORDER BY title";
		$db->setQuery($query);
		$products = $db->loadObjectList();

		$query = "SELECT * FROM #__fss_field_prod WHERE field_id = " . FSSJ3Helper::getEscaped($db, $field->id);
		$db->setQuery($query);
		$selprod = $db->loadAssocList('prod_id');
		
		$this->assign('allprods',$field->allprods);
		
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

		// field permissions
        $fieldperms[] = JHTML::_('select.option', '0', JText::_("USER_CAN_SEE_AND_EDIT"), 'id', 'title');
        $fieldperms[] = JHTML::_('select.option', '1', JText::_("USER_CAN_SEE_ONLY_ADMIN_CAN_EDIT"), 'id', 'title');
        $fieldperms[] = JHTML::_('select.option', '2', JText::_("ONLY_ADMIN_CAN_SEE_AND_EDIT"), 'id', 'title');
        $fieldperms[] = JHTML::_('select.option', '4', JText::_("ONLY_SHOW_ON_TICKET_CREATE"), 'id', 'title');
        $fieldperms[] = JHTML::_('select.option', '5', JText::_("USER_CAN_CREATE_AND_SEE_ADMIN_CAN_EDIT"), 'id', 'title');
        $this->fieldperm = JHTML::_('select.genericlist',  $fieldperms, 'permissions', 'class="inputbox" size="1"', 'id', 'title', $field->permissions);

		$whichusers[] = JHTML::_('select.option', '0', JText::_("All Users"), 'id', 'title');
		$whichusers[] = JHTML::_('select.option', '1', JText::_("Registered Only"), 'id', 'title');
		$whichusers[] = JHTML::_('select.option', '2', JText::_("Unregistered Only"), 'id', 'title');
		$this->whichusers = JHTML::_('select.genericlist',  $whichusers, 'reghide', 'class="inputbox" size="1"', 'id', 'title', $field->reghide);

		//  User department selection
		$this->assign('alldept', JHTML::_('select.booleanlist', 'alldepts', 
			array('class' => "inputbox",
						'size' => "1", 
						'onclick' => "DoAllDeptChange();"),
					intval($field->alldepts)) );

		$query = "SELECT * FROM #__fss_ticket_dept ORDER BY title";
		$db->setQuery($query);
		$departments = $db->loadObjectList();

		$query = "SELECT * FROM #__fss_field_dept WHERE field_id = " . FSSJ3Helper::getEscaped($db, $field->id);
		$db->setQuery($query);
		$seldept = $db->loadAssocList('ticket_dept_id');
		
		$this->assign('alldepts',$field->alldepts);
		
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
		$this->departments = $deptcheck;

		// get field values	

		$query = "SELECT value, data FROM #__fss_field_values WHERE field_id = " . FSSJ3Helper::getEscaped($db, $field->id);
		$db->setQuery($query);
		$values = $db->loadObjectList();

		if ($field->type == "radio" || $field->type == "combo")
		{
			$res = "";
			foreach ($values as $value)
			{
				$value = $value->value;
				if (strpos($value,"|") == 2)
				{
					$value = substr($value,3);	
				} elseif (strpos($value,"|") == 3)
				{
					$value = substr($value,4);	
				}
				$res .= $value."\n";
			}
			$this->values = $res;
		}
		else
			$this->assign('values','');
		
		$area_width = 60;
		$area_height = 4;
		$text_min = 0;
		$text_max = 60;
		$text_size = 40;
		$plugin_name = '';
		$plugin_data = '';
		
		foreach ($values as $value)
		{
			$bits = explode("=",$value->value,2);
			if (count($bits == 2))
			{
				if ($bits[0] == "area_width")
					$area_width = $bits[1];
				if ($bits[0] == "area_height")
					$area_height = $bits[1];
				if ($bits[0] == "text_min")
					$text_min = $bits[1];
				if ($bits[0] == "text_max")
					$text_max = $bits[1];
				if ($bits[0] == "text_size")
					$text_size = $bits[1];
				if ($bits[0] == "plugin")
					$plugin_name = $bits[1];
				if ($bits[0] == "plugindata")
				{
					$plugin_data = $bits[1];
				}
			}

			if ($value->data)
			{
				$plugin_data = $value->data;
			}
		}

		$this->field = $field;
		$this->area_width = $area_width;
		$this->area_height = $area_height;
		$this->text_min = $text_min;
		$this->text_max = $text_max;
		$this->text_size = $text_size;

		// load plugin list
		$plugins = array();
		$this->plugins = FSSCF::get_plugins();
		$pllist = array();
		$pllist[] = JHTML::_('select.option', '', JText::_("Select a plugin"), 'id', 'title');
		foreach ($this->plugins as $id => &$plugins)
		{
			$pllist[] = JHTML::_('select.option', $id, $plugins->name, 'id', 'title');
		}
		$this->pllist = JHTML::_('select.genericlist',  $pllist, 'plugin', 'id="plugin" class="inputbox" size="1" onchange="plugin_changed()"', 'id', 'title', $plugin_name);
	
		$this->plugin_form = "";
		if ($plugin_name != '') // editing an existing plugin?
		{
			if (array_key_exists($plugin_name,$this->plugins))
			{
				$plugin = $this->plugins[$plugin_name];
				$this->plugin_form = $plugin->DisplaySettings($plugin_data);
			}
		}
	
		$idents = array();
		$idents[] = JHTML::_('select.option', '0', JText::_("TICKETS"), 'id', 'title');
		$idents[] = JHTML::_('select.option', '999', JText::_("ALL_COMMENTS"), 'id', 'title');
		$db	= JFactory::getDBO();
		foreach($this->comments->handlers as $handler)
			$idents[] = JHTML::_('select.option', $handler->ident, $handler->GetLongDesc(), 'id', 'title');
				
		$this->ident = JHTML::_('select.genericlist',  $idents, 'ident', ' class="inputbox" size="1" onchange="ident_changed()"', 'id', 'title', $field->ident);

		
		parent::display($tpl);
	}
	
	function pluginForm()
	{
		$plugin = JRequest::GetVar('plugin','');	
		if ($plugin == "")
			exit;
		
		$plugin = FSSCF::get_plugin($plugin);
		
		echo $plugin->DisplaySettings(null);
		exit;
	}

	function displayProds()
	{
		$field_id = JRequest::getInt('field_id',0);
		$db	= JFactory::getDBO();

		$query = "SELECT * FROM #__fss_field_prod as u LEFT JOIN #__fss_prod as p ON u.prod_id = p.id WHERE u.field_id = '".FSSJ3Helper::getEscaped($db, $field_id)."'";
		$db->setQuery($query);
		$products = $db->loadObjectList();
		
		$query = "SELECT * FROM #__fss_field WHERE id = '".FSSJ3Helper::getEscaped($db, $field_id)."'";
		$db->setQuery($query);
		$field = $db->loadObject();
	
		$this->field = $field;
		$this->products = $products;
		parent::display();
	}
	
	function displayDepts()
	{
		$field_id = JRequest::getInt('field_id',0);
		$db	= JFactory::getDBO();

		$query = "SELECT * FROM #__fss_field_dept as u LEFT JOIN #__fss_ticket_dept as p ON u.ticket_dept_id = p.id WHERE u.field_id = '".FSSJ3Helper::getEscaped($db, $field_id)."'";
		$db->setQuery($query);
		$departments = $db->loadObjectList();
		
		$query = "SELECT * FROM #__fss_field WHERE id = '".FSSJ3Helper::getEscaped($db, $field_id)."'";
		$db->setQuery($query);
		$field = $db->loadObject();
		
		$this->field = $field;
		$this->departments = $departments;
		parent::display();
	}
	
}


