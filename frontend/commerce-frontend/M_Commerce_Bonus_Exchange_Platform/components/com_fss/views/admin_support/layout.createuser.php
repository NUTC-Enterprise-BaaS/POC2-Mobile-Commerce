<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'pagination.php');

class FssViewAdmin_Support_createuser extends FssViewAdmin_Support
{
	function display($tpl = NULL)
	{
		$lang = JFactory::getLanguage();
		$lang->load("com_users");

		$this->data = new stdClass();
		$this->data->name = FSS_Input::getString("name");
		$this->data->username = FSS_Input::getString("username");
		$this->data->password = FSS_Input::getString("password");
		$this->data->password2 = FSS_Input::getString("password2");
		$this->data->email = FSS_Input::getString("email");

		$this->errors = array();
		$this->errors['name'] = 0;
		$this->errors['username'] = 0;
		$this->errors['password'] = 0;
		$this->errors['password2'] = 0;
		$this->errors['email'] = 0;
		
		$db = JFactory::getDBO();
		
		if (FSS_Input::getInt("create"))
		{
			// check form
			$ok = true;
			
			if (!$this->data->name)
			{
				$this->errors['name'] = 1;	
				$ok = false;
			}	
			
			if (!$this->data->username)
			{
				$this->errors['username'] = 1;	
				$ok = false;
			} else {
				$sql = "SELECT * FROM #__users WHERE username = '" . $db->escape($this->data->username) . "'";
				$db->setQuery($sql);
				$result = $db->loadObject();
				if ($result)
				{
					$this->errors['username'] = 1;	
					$ok = false;
				}
			}	
			
			if (!$this->data->password)
			{
				$this->errors['password'] = 1;	
				$ok = false;
			}	
			
			if (!$this->data->password2 || $this->data->password != $this->data->password2)
			{
				$this->errors['password2'] = 1;	
				$ok = false;
			}	
			
			if (!$this->data->email)
			{
				$this->errors['email'] = 1;	
				$ok = false;
			} else {
				$sql = "SELECT * FROM #__users WHERE email = '" . $db->escape($this->data->email) . "'";
				$db->setQuery($sql);
				$result = $db->loadObject();
				if ($result)
				{
					$this->errors['email'] = 1;	
					$ok = false;
				}
			}
			
			if ($ok)
			{
				$user = $this->doCreate();

				$this->assignToTickets($user);
				
				echo "<script>";
				echo "window.parent.PickUser('{$user->id}','{$user->username}','{$user->name}');";
				echo "</script>";

				exit;	
			}
		}

		parent::init();	
		
		$this->_display();
	}

	function assignToTickets($user)
	{
		$ticketid = JRequest::getVar('ticketid');
		if ($ticketid > 0)
		{
			$email = $user->email;
			$id = $user->id;

			$db = JFactory::getDBO();
			$qry = "UPDATE #__fss_ticket_ticket SET user_id = " . $id . " WHERE email = '" . $db->escape($email) . "'";
			$db->setQuery($qry);
			$db->Query();
		}
	}
	
	function doCreate()
	{
		$params = JComponentHelper::getParams('com_users');

		// Initialise the table with JUser.
		$user = new JUser();
		$data = array();

		// Prepare the data for the user object.
		$data['email'] = $this->data->email;
		if (class_exists("JStringPunycode")) $data['email'] = JStringPunycode::emailToPunycode($data['email']);
		$data['name'] = $this->data->name;
		$data['username'] = $this->data->username;
		$data['password'] = $this->data->password;
		$sendpassword = 1;

		$params = JComponentHelper::getParams('com_users');
		$data['groups'] = array();
		$system = $params->get('new_usertype', 2);
		$data['groups'][] = $system;


		// Bind the data.
		if (!$user->bind($data))
		{
			$this->setError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));
			return false;
		}

		// Load the users plugin group.
		JPluginHelper::importPlugin('user');

		// Store the data.
		if (!$user->save())
		{
			$this->setError(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $user->getError()));
			return false;
		}

		$config = JFactory::getConfig();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Compile the notification mail values.
		$data = $user->getProperties();
		$data['fromname'] = $config->get('fromname');
		$data['mailfrom'] = $config->get('mailfrom');
		$data['sitename'] = $config->get('sitename');
		$data['siteurl'] = JUri::root();

		$emailSubject = JText::sprintf(
			'COM_USERS_EMAIL_ACCOUNT_DETAILS',
			$data['name'],
			$data['sitename']
			);

		$emailBody = JText::sprintf(
			'COM_USERS_EMAIL_REGISTERED_BODY',
			$data['name'],
			$data['sitename'],
			$data['siteurl'],
			$data['username'],
			$data['password_clear']
			);

		// Add user to registered group

		
		// Send the registration email.
		JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);
		
		return $user;
	}	

}
