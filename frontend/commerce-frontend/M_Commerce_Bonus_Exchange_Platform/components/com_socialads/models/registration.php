<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access.
defined('_JEXEC') or die(';)');

jimport('joomla.application.component.model');
jimport('joomla.database.table.user');

/**
 * Socialads model.
 *
 * @since  1.6
 */
class SocialadsModelRegistration extends JModelLegacy
{
	/**
	 * Method to get data
	 *
	 * @return  form data
	 *
	 * @since   2.2
	 */
	public function getData()
	{
		$input = JFactory::getApplication()->input;
		$id = $input->get('cid', 0, 'INT');
		$user = JFactory::getUser();

		return $this->_data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @return  mixed   The user id on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function store()
	{
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$input = JFactory::getApplication()->input;
		$id = $input->get('cid', 0, 'INT');
		$session = JFactory::getSession();
		$db = JFactory::getDBO();
		$data = JFactory::getApplication()->input->post;
		$user = JFactory::getUser();

		// Joomla user entry
		if (!$user->id)
		{
			$query = "SELECT id FROM #__users WHERE email = '" . $data->get('user_email', '', 'STRING')
					. "' or username = '" . $data->get('user_name', '', 'STRING') . "'";
			$this->_db->setQuery($query);
			$userexist = $this->_db->loadResult();
			$userid = "";
			$randomPassword = "";

			if (!$userexist)
			{
				// Generate the random password & create a new user
				$randomPassword = $this->rand_str(6);
				$userid  = $this->createnewuser($data, $randomPassword);
			}
			else
			{
				$message = JText::_('COM_SOCIALADS_REGISTRATION_USER_EXIST');
				$jinput = JFactory::getApplication()->input;
				$jinput = JFactory::getApplication()->input;
				$jinput->set('varname', $foo);

				return false;
			}

			if ($userid)
			{
				JPluginHelper::importPlugin('user');

				if (!$userexist)
				{
					$this->SendMailNewUser($data, $randomPassword);
				}

				$user = array();
				$options = array('remember' => JRequest::getBool('remember', false));

				// Tmp user details
				$user = array();
				$user['username'] = $data->get('user_name', '', 'STRING');
				$options['autoregister'] = 0;
				$user['email'] = $data->get('user_email', '', 'STRING');
				$user['password'] = $randomPassword;
				$mainframe->login(array('username' => $data->get('user_name', '', 'STRING'), 'password' => $randomPassword), array('silent' => true));
			}
		}

		return true;
	}

	/**
	 * Method to create a new user.
	 *
	 * @param   array   $data            The form data.
	 *
	 * @param   string  $randomPassword  password
	 *
	 * @return  mixed   The user id on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function createnewuser($data, $randomPassword)
	{
		jimport('joomla.user.helper');
		$authorize = JFactory::getACL();
		$user = clone JFactory::getUser();
		$user->set('username', $data->get('user_name', '', 'STRING'));
		$user->set('password1', $randomPassword);
		$user->set('name', $data->get('user_name', '', 'STRING'));
		$user->set('email', $data->get('user_email', '', 'STRING'));

		// Password encryption
		$salt  = JUserHelper::genRandomPassword(32);
		$crypt = JUserHelper::getCryptedPassword($user->password1, $salt);
		$user->password = "$crypt:$salt";
		$message = '';

		// User group/type
		$user->set('id', '');
		$user->set('usertype', 'Registered');

		if (JVERSION >= '1.6.0')
		{
			$params = JComponentHelper::getParams('com_socialads');
			$default_usergroup = $params->get('sa_usergroup', 2);
			$user->set('groups', array($default_usergroup));
		}
		else
		{
			$user->set('gid', $authorize->get_group_id('', 'Registered', 'ARO'));
		}

		$date = JFactory::getDate();
		$user->set('registerDate', $date->toSql());

		// True on success, false otherwise
		if (!$user->save())
		{
			echo $message = JText::_('COM_SOCIALADS_REGISTRATION_USER_EXIST') . $user->getError();

			return false;
		}
		else
		{
			$message = JText::sprintf('COM_SOCIALADS_REGISTRATION_MESSAGE1', $user->username);
		}

		return $user->id;
	}

	/**
	 * Create a random character generator for password
	 *
	 * @param   string  $length  Default length
	 *
	 * @param   string  $chars   Default character string
	 *
	 * @return  string
	 *
	 * @since    1.6
	 */
	public function rand_str($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
	{
		// Length of character list
		$chars_length = (strlen($chars) - 1);

		// Start our string
		$string = $chars{rand(0, $chars_length)};

		// Generate random string
		for ($i = 1; $i < $length; $i = strlen($string))
		{
			// Grab a random character from our list
			$r = $chars{rand(0, $chars_length)};

			// Make sure the same two characters don't appear next to each other
			if ($r != $string{$i - 1})
			{
				$string .= $r;
			}
		}
		// Return the string
		return $string;
	}

	/**
	 * Method to send Email
	 *
	 * @param   array   $data            The form data.
	 *
	 * @param   string  $randomPassword  Password
	 *
	 * @return  string
	 *
	 * @since    1.6
	 */
	public function SendMailNewUser($data, $randomPassword)
	{
		$app = JFactory::getApplication();
		$mailfrom = $app->getCfg('mailfrom');
		$fromname = $app->getCfg('fromname');
		$sitename = $app->getCfg('sitename');
		$email = $data->get('user_email', '', 'STRING');
		$subject = JText::_('COM_SOCIALADS_REGISTRATION_SUBJECT');
		$find1 = array('{sitename}');
		$replace1 = array($sitename);
		$subject = str_replace($find1, $replace1, $subject);
		$message = '';
		$message = JText::_('COM_SOCIALADS_REGISTRATION_USER');
		$find = array('{firstname}', '{sitename}', '{register_url}', '{username}', '{password}');
		$replace = array($data->get('user_name', '', 'STRING'), $sitename, JUri::root(), $data->get('user_name', '', 'STRING'), $randomPassword);
		$message = str_replace($find, $replace, $message);

		JFactory::getMailer()->sendMail($mailfrom, $fromname, $email, $subject, $message);
		$messageadmin = JText::_('COM_SOCIALADS_REGISTRATION_ADMIN');
		$find2 = array('{sitename}','{username}');
		$replace2 = array($sitename, $data->get('user_name', '', 'STRING'));
		$messageadmin = str_replace($find2, $replace2, $messageadmin);

		JFactory::getMailer()->sendMail($mailfrom, $fromname, $mailfrom, $subject, $messageadmin);

		return true;
	}
}
