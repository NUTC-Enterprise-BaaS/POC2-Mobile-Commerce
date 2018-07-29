<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  JSocial
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;
jimport('joomla.filesystem.file');
/**
 * Interface to handle Social Extensions
 *
 * @package     Joomla.Libraries
 * @subpackage  JSocial
 * @since       1.0
 */
class JSocialJoomla implements JSocial
{
	private $gravatar = true;

	private $gravatar_surl = 'https://secure.gravatar.com/avatar/';

	private $gravatar_url = 'http://www.gravatar.com/avatar/';

	private $gravatar_size = 50;

	private $gravatar_default = '';

	private $gravatar_rating = 'g';

	private $gravatar_secure = false;

	/**
	 * The constructor
	 *
	 * @since  1.0
	 */
	public function __construct()
	{
		require_once JPATH_SITE . '/components/com_users/helpers/route.php';
	}

	/**
	 * The function to get profile AVATAR of a User
	 *
	 * @param   MIXED  $user           JUser Objcet
	 *
	 * @param   INT    $gravatar_size  Size of the AVATAR
	 *
	 * @return  STRING
	 *
	 * @since   1.0
	 */
	public function getAvatar (JUser $user , $gravatar_size = 50)
	{
		$email = $user->email;
		$url = ($this->gravatar_secure) ? $this->gravatar_surl : $this->gravatar_url;
		$url .= md5($email) . '?d=' . $this->gravatar_default . '&rating=' . $this->gravatar_rating . '&s=' . $gravatar_size;

		return $url;
	}

	/**
	 * The function to get profile data of User
	 *
	 * @param   MIXED  $user  JUser Objcet
	 *
	 * @return  JUser Objcet
	 *
	 * @since   1.0
	 */
	public function getProfileData(JUser $user)
	{
	}

	/**
	 * The function to get profile link User
	 *
	 * @param   MIXED  $user  JUser Objcet
	 *
	 * @return  STRING
	 *
	 * @since   1.0
	 */
	public function getProfileUrl(JUser $user)
	{
	}

	/**
	 * The function to get friends of a User
	 *
	 * @param   MIXED  $user      JUser Objcet
	 * @param   INT    $accepted  Optional param, bydefault true to get only friends with request accepted
	 * @param   INT    $options   Optional array
	 *
	 * @return  Friends objects
	 *
	 * @since   1.0
	 */
	public function getFriends(JUser $user, $accepted=true, $options = array())
	{
	}

	/**
	 * The function to add provided users as Friends
	 *
	 * @param   MIXED  $connect_from_user  User who is requesting connection
	 * @param   INT    $connect_to_user    User whom to request
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function addFriend(JUser $connect_from_user, JUser $connect_to_user)
	{
	}

	/**
	 * The function to get Easysocial toolbar
	 *
	 * @return  toolbar HTML
	 *
	 * @since   1.0
	 */
	public function getToolbar()
	{
	}

	/**
	 * Add activity stream
	 *
	 * @param   INT     $actor_id         User against whom activity is added
	 * @param   STRING  $act_type         type of activity
	 * @param   STRING  $act_subtype      sub type of activity
	 * @param   STRING  $act_description  Activity description
	 * @param   STRING  $act_link         LInk of Activity
	 * @param   STRING  $act_title        Title of Activity
	 * @param   STRING  $act_access       Access level
	 *
	 * @return  true
	 *
	 * @since  1.0
	 */
	public function pushActivity($actor_id, $act_type, $act_subtype, $act_description, $act_link, $act_title, $act_access)
	{
	}

	/**
	 * The function to set status of a user
	 *
	 * @param   MIXED   $user     User whose status is to be set
	 * @param   STRING  $status   status to be set
	 * @param   MIXED   $options  status to be set
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setStatus(JUser $user, $status, $options)
	{
	}

	/**
	 * Send Notification
	 *
	 * @param   OBJECT  $sender        User who is sending notification
	 * @param   OBJECT  $receiver      User to whom notification is to send
	 * @param   STRING  $content       Main content of the notification
	 * @param   STRING  $options       Optional options
	 * @param   STRING  $emailOptions  Email options. If you do not want to send email, $emailOptions should be set to false
	 *
	 * @return  boolean
	 *
	 * @since  1.0
	 */
	public function sendNotification(JUser $sender, JUser $receiver, $content = "JS Notification", $options = array(), $emailOptions = false)
	{
	}

	/**
	 * The function to get registartion link for CB
	 *
	 * @param   ARRAY  $options  options
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function getRegistrationLink($options)
	{
	}

	/**
	 * The function
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function checkExists()
	{
	}

	/**
	 * The function add points to user
	 *
	 * @param   MIXED  $receiver  User to whom points to be added
	 * @param   ARRAY  $options   is array
	 *
	 * $options[command] for example invites sent
	 * options[extension] for example com_invitex
	 *
	 * @return ARRAY success 0 or 1
	 */
	public function addpoints(JUser $receiver, $options=array())
	{
	}

	/**
	 * The function to create a group
	 *
	 * @param   ARRAY  $data     Data
	 * @param   ARRAY  $options  Additional data
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function createGroup($data, $options=array())
	{
	}

	/**
	 * The function to add member to a group
	 *
	 * @param   ARRAY   $groupId      Data
	 * @param   OBJECT  $groupmember  User object
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function addMemberToGroup($groupId, JUser $groupmember)
	{
	}

	/**
	 * The function to add stream
	 *
	 * @param   Array  $streamOption  Stram array
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function advPushActivity($streamOption)
	{
	}
}
