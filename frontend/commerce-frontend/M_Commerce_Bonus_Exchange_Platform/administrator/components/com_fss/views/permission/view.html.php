<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'translate.php');
require_once (JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'form'.DS.'fields'.DS.'rules.php');
require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'permissions.php');

class FsssViewPermission extends JViewLegacy
{
	function display($tpl = null)
	{
		$task = JRequest::getVar('task');
		
		if ($task == "save" || $task == "apply")
			return $this->save($task);

		if ($task == "cancel")
			return $this->cancel();
		
		FSS_Helper::IncludeModal();

		FSS_CSSParse::OutputCSS('components/com_fss/assets/css/bootstrap/bootstrap_fssonly.less');

		$model = $this->getModel();
		$this->item = $this->get('Item');
		
		$this->section = $this->get('Section');
		$this->formid = $this->get('FormID');

		if ($this->section == "com_fss.support_admin" && JRequest::getVar('nojs') != 1)
		{
			$document = JFactory::getDocument();
			$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/perm.group.support_admin.js'); 
		}

		$this->getTexts();

		$this->form = $model->getForm($this->formid);

		JToolBarHelper::title(   JText::_("Permissions").': <small><small>[ ' . $this->title.' ]</small></small>', 'fss_prods' );

		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		JToolBarHelper::custom("nojs", "","", "Old Version", false);
	
		FSSAdminHelper::DoSubToolbar(true);
	
		parent::display($tpl);
	}
	
	function clean_post(&$data, $set)
	{
		if (!isset($data[$set]))
			return;
		
		foreach ($data[$set] as $set => &$values)
		{
			foreach ($values as $key => $value)
			{
				if ($value == "")
					unset($values[$key]);
			}
		}	
	}

	function my_parse_str($string, &$result) {
		if ($string==='') 
			return false;

		$result = array();
		// find the pairs "name=value"
		$pairs = explode('&', $string);

		foreach ($pairs as $pair) {
			// use the original parse_str() on each element
			parse_str($pair, $params);
			$k=key($params);

			if(!isset($result[$k]))
			{
				$result += $params;
			}
			else
			{
				$result[$k] = $this->array_merge_recursive_distinct($result[$k], $params[$k]);
			}
		}
		return true;
	}

	function array_merge_recursive_distinct ( array &$array1, array &$array2 )
	{
		$merged = $array1;
		foreach ( $array2 as $key => &$value )
		{
			if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
			{
				$merged [$key] = $this->array_merge_recursive_distinct ( $merged [$key], $value );
			}
			else
			{
				$merged [$key] = $value;
			}
		}

		return $merged;
	}

	function save($task)
	{
		$output = array();
		$this->my_parse_str(JRequest::getVar('data'), $output);
		$posted = $output['jform'];

		$this->clean_post($posted, "rules");
		$this->clean_post($posted, "rules_ticket");
		$this->clean_post($posted, "rules_misc");
		$this->clean_post($posted, "rules_ticket");
		$this->clean_post($posted, "rules_ticket_cc");
		$this->clean_post($posted, "rules_ticket_other");
		$this->clean_post($posted, "rules_ticket_una");
		$this->clean_post($posted, "view_products");
		$this->clean_post($posted, "view_departments");
		$this->clean_post($posted, "view_categories");
		$this->clean_post($posted, "assign_products");
		$this->clean_post($posted, "assign_departments");
		$this->clean_post($posted, "assign_categories");

		if (isset($posted["view_products"]))
			$posted['rules'] = array_merge($posted['rules'], $posted["view_products"]);

		if (isset($posted["rules_ticket"]))
			$posted['rules'] = array_merge($posted['rules'], $posted["rules_ticket"]);

		if (isset($posted["rules_misc"]))
			$posted['rules'] = array_merge($posted['rules'], $posted["rules_misc"]);

		if (isset($posted["rules_ticket"]))
			$posted['rules'] = array_merge($posted['rules'], $posted["rules_ticket"]);

		if (isset($posted["rules_ticket_cc"]))
			$posted['rules'] = array_merge($posted['rules'], $posted["rules_ticket_cc"]);

		if (isset($posted["rules_ticket_other"]))
			$posted['rules'] = array_merge($posted['rules'], $posted["rules_ticket_other"]);

		if (isset($posted["rules_ticket_una"]))
			$posted['rules'] = array_merge($posted['rules'], $posted["rules_ticket_una"]);

		if (isset($posted["view_departments"]))
			$posted['rules'] = array_merge($posted['rules'], $posted["view_departments"]);
	
		if (isset($posted["view_categories"]))
			$posted['rules'] = array_merge($posted['rules'], $posted["view_categories"]);
	
		if (isset($posted["assign_products"]))
			$posted['rules'] = array_merge($posted['rules'], $posted["assign_products"]);
	
		if (isset($posted["assign_departments"]))
			$posted['rules'] = array_merge($posted['rules'], $posted["assign_departments"]);
	
		if (isset($posted["assign_categories"]))
			$posted['rules'] = array_merge($posted['rules'], $posted["assign_categories"]);
		
		foreach ($posted['rules'] as $set => $values)
		{
			if (Count($values) == 0)
				unset($posted['rules'][$set]);
		}	

		$rules = new JAccessRules($posted['rules']);
		$section = JRequest::getVar('section');

		$rules = (string)$rules;

		if (strpos($section, "com_fss") !== false)
		{
			$qry = "UPDATE #__assets SET rules = '" . FSSJ3Helper::getEscaped($db, $rules) . "' WHERE name = '" . FSSJ3Helper::getEscaped($db, $section) . "'";
			$db->setQuery($qry);
			$db->Query();
		}
		
		if ($task == "save")
		{
			$url = JRoute::_('index.php?option=com_fss&view=fusers', false);
		} else {
			// apply, show form again
			$url = JRoute::_('index.php?option=com_fss&view=permission&section=' . $section, false);
		}
		
		$app = JFactory::getApplication();
		$app->redirect($url);
	}
	
	function cancel()
	{
		$url = JRoute::_('index.php?option=com_fss&view=fusers', false);
		$app = JFactory::getApplication();
		$app->redirect($url);
	}
	
	function getTexts()
	{
		switch ($this->section)
		{
			case 'com_fss':
				$this->title = "ALL_CONTENT";
				break;
			case 'com_fss.faq':
				$this->title = "FAQS";
				break;
			case 'com_fss.kb':
				$this->title = "KB_ARTICLES";
				break;
			case 'com_fss.announce':
				$this->title = "ANNOUNCEMENTS";
				break;
			case 'com_fss.glossary':
				$this->title = "GLOSSARY";
				break;
			case 'com_fss.moderation':
				$this->title = "MODERATION";
				break;
			case 'com_fss.groups':
				$this->title = "TICKET_GROUPS";
				break;
			case 'com_fss.reports':
				$this->title = "REPORTS";
				break;
			case 'com_fss.support_user':
				$this->title = 'SUPPORT_TICKETS_USERS';
				break;
			case 'com_fss.support_admin':
				$this->title = "SUPPORT_TICKETS___ADMINS_HANDLERS";
				break;
			default:
				$this->title = $this->section;
				break;
		}		
		$this->description = JText::_("PERMS_" . $this->title . "_DESC");

		$this->title = JText::_($this->title);
	}
}
