<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Js_Events
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

$lang = JFactory::getLanguage();
$lang->load('plg_socialadspromote_js_profile', JPATH_ADMINISTRATOR);

/**
 * Plugin class to promote JomSocial profile in Socialads.
 *
 * @since  1.6
 */
class PlgSocialadsPromoteJs_Profile extends JPlugin
{
	/**
	 * Methode to promote EasySocial profile
	 *
	 * @param   integer  $uid  users ID
	 *
	 * @return  array
	 *
	 * @since   1.6
	 */
	public function onPromoteList($uid = '')
	{
		$db = JFactory::getDbo();

		if ($uid)
		{
			$user = JFactory::getUser($uid);
		}
		else
		{
			$user = JFactory::getUser();
		}

		jimport('joomla.filesystem.file');
		$name = JFile::getName(__FILE__);
		$name = JFile::stripExt($name);

		$jschk = $this->checkForJsExtension();

		if (!empty($jschk))
		{
			$query = $db->getQuery(true);
			$query->select("CONCAT_WS('|', '" . $name . "', u.id) as value");
			$query->select("u.name AS text");
			$query->from($db->quoteName('#__users', 'u'));
			$query->join('LEFT', $db->quoteName('#__community_users', 'c') . 'ON' . $db->quoteName('u.id') . '=' . $db->quoteName('c.userid'));
			$query->where($db->quoteName('u.id') . " = " . $db->quote($user->id));
			$db->setQuery($query);
			$itemlist = $db->loadObjectlist();

			return $itemlist;
		}
	}

	/**
	 * Methode to get promotion data
	 *
	 * @param   integer  $id  Id of a event
	 *
	 * @return  array
	 *
	 * @since   1.6
	 */
	public function onPromoteData($id)
	{
		$db      = JFactory::getDbo();
		$fieldid = $this->params->get('js_field');
		$jschk   = $this->checkForJsExtension();

		if (!empty($jschk))
		{
			// Get user name
			$query = $db->getQuery(true);
			$query->select('u.name as title');
			$query->from($db->quoteName('#__users', 'u'));
			$query->join('LEFT', $db->quoteName('#__community_users', 'cu') . 'ON' . $db->quoteName('u.id') . '=' . $db->quoteName('cu.userid'));

			// Get about me
			if ($fieldid)
			{
				$query->select('cfv.value as bodytext');
				$query->join('LEFT', $db->quoteName('#__community_fields_values', 'cfv') . 'ON' . $db->quoteName('cu.userid') . '=' . $db->quoteName('cfv.user_id'));
				$query->join('LEFT', $db->quoteName('#__community_fields', 'cf') . 'ON' . $db->quoteName('cfv.field_id') . '=' . $db->quoteName('cf.id'));

				$query->where("cfv.field_id=" . $fieldid);
			}

			$query->where($db->quoteName('cu.userid') . " = " . $db->quote($id));

			$db->setQuery($query);
			$previewData = $db->loadObjectlist();

			// If about me field is not set
			if (!$fieldid)
			{
				$previewData[0]->bodytext = '';
			}

			// Get user avatar and profile URL
			jimport('techjoomla.jsocial.jsocial');
			jimport('techjoomla.jsocial.jomsocial');
			$jSocialObj = new JSocialJomsocial;
			$imagePath = $jSocialObj->getAvatar(JFactory::getUser($id));
			$link      = $jSocialObj->getProfileUrl(JFactory::getUser($id));

			$previewData[0]->image = $imagePath;
			$previewData[0]->url   = $link; // JUri::root() . substr(JRoute::_($link, false), strlen(JUri::base(true)) + 1); die;

			return $previewData;
		}
		else
		{
			return '';
		}
	}

	/**
	 * Methode to check if the extension folder is present
	 *
	 * @params  integer  $id  Id of a event
	 *
	 * @return  array
	 *
	 * @since   1.6
	 */
	public function checkForJsExtension()
	{
		jimport('joomla.filesystem.folder');
		$extpath = JPATH_ROOT . '/components/com_community';

		if (JFolder::exists($extpath))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
}
