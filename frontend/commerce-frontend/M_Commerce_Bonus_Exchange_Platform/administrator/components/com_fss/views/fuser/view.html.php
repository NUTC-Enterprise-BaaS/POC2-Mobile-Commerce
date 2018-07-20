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
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_users.php');
require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'permissions.php');
	
class FsssViewFuser extends JViewLegacy
{

	function display($tpl = null)
	{	
		$task = JRequest::getVar('task');
			
		if ($task == "save" || $task == "apply" || $task == "save2new")
			return $this->save($task);

		if ($task == "cancel")
			return $this->cancel();
		
		FSS_CSSParse::OutputCSS('components/com_fss/assets/css/bootstrap/bootstrap_fssonly.less');
		
		$model = $this->getModel();

		$user 	= $this->get('Data');
		$isNew		= ($user->id < 1);

		$model->user = $user;

		$db	= JFactory::getDBO();

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("USER").': <small><small>[ ' . $text.' ]</small></small>' , 'fss_users');
		
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

		$username = !empty($user->name) ? $user->name : "";
		$userid = !empty($user->user_id) ? $user->user_id : 0;

		$input = "<input type='hidden' name='user_id' id='user_id' value='" . $userid . "' class='input-mini' />";
		$input .= "<input type='text' name='user_name' id='user_name' value='" . $username . "' class='input-xlarge' disabled='disabled' />";

		$newLink = JRoute::_('index.php?option=com_fss&view=listusers&tmpl=component&tpl=fuser');
		if ($isNew) $input .= "&nbsp;<a href='$newLink' class='btn btn-default show_modal_iframe'>Choose User</a>";

		$this->users = $input;

		$this->user = $user;

		$this->sort_load_data();

		$this->form = $model->getForm();
		
		FSS_Helper::IncludeModal();

		parent::display($tpl);
	}
	
	function save($task)
	{
		$permissions = JRequest::getVar('jform');

		$this->clean_post($permissions, "com_fss");
		$this->clean_post($permissions, "faq");
		$this->clean_post($permissions, "kb");
		$this->clean_post($permissions, "glossary");
		$this->clean_post($permissions, "announce");
		$this->clean_post($permissions, "support_user");
		$this->clean_post($permissions, "support_admin");
		$this->clean_post($permissions, "support_admin_misc");
		$this->clean_post($permissions, "support_admin_ticket");
		$this->clean_post($permissions, "support_admin_ticket_cc");
		$this->clean_post($permissions, "support_admin_ticket_other");
		$this->clean_post($permissions, "support_admin_ticket_una");
		$this->clean_post($permissions, "view_products");
		$this->clean_post($permissions, "view_departments");
		$this->clean_post($permissions, "view_categories");
		$this->clean_post($permissions, "assign_products");
		$this->clean_post($permissions, "assign_departments");
		$this->clean_post($permissions, "assign_categories");
		
		$this->clean_post($permissions, "reports");
		$this->clean_post($permissions, "groups");
		$this->clean_post($permissions, "moderation");
		
		foreach ($permissions as $set => $values)
		{
			if (count($values) == 0)
				unset($permissions[$set]);	
		}

		$db	= JFactory::getDBO();

		$user_id = JRequest::getVar('user_id');

		SupportUsers::updateUserPermissions($permissions, $user_id);

		if ($task == "save")
		{
			$url = JRoute::_('index.php?option=com_fss&view=fusers', false);
		} else if ($task == "save2new")
		{
			$url = JRoute::_('index.php?option=com_fss&controller=fuser&task=edit', false);
		} else {
			$url = JRoute::_('index.php?option=com_fss&controller=fuser&task=edit&cid[]=' . $user_id, false);
		}
	
		$app = JFactory::getApplication();
		$app->redirect($url);
	}
	
	function clean_post(&$data, $set)
	{
		if (!isset($data[$set]))
			return;
		
		foreach ($data[$set] as $subsetset => &$values)
		{
			foreach ($values as $key => $value)
			{
				if ($value == "")
					unset($values[$key]);
				
			}
			
			if (count($values) == 0)
				unset($data[$set][$subsetset]);
			
			if (count($values) == 1)
				$data[$set][$subsetset] = reset($values);
		}	
	}

	function cancel()
	{
		$url = JRoute::_('index.php?option=com_fss&view=fusers', false);
		$app = JFactory::getApplication();
		$app->redirect($url);
	}


	function sort_load_data()
	{
		if (empty($this->user->rules))
			$this->user->rules = "";
		
		$rules = $this->user->rules;
		$rules = json_decode($rules);
		if ($rules)
		{
			foreach ($rules as $set => $values)
			{
				$this->user->$set = new stdClass();
			
				foreach ($values as $key =>	$value)
				{
					$this->user->$set->$key = new stdClass();
					$this->user->$set->$key = $value;	
				}
			}
		}
	}
}


