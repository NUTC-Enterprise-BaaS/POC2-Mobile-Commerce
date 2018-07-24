<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Sobi
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
$lang = JFactory::getLanguage();
$lang->load('plg_socialadspromote_js_groups', JPATH_ADMINISTRATOR);

/**
 * Plugin class to promote Sobi list
 *
 * @since  1.6
 */
class PlgSocialadsPromoteJs_Groups extends JPlugin
{
	/**
	 * Methode to promote JomSocial groups
	 *
	 * @param   integer  $uid  users ID
	 *
	 * @return  array
	 *
	 * @since   1.6
	 */
	public function onPromoteList($uid = '')
	{
			jimport('joomla.filesystem.file');
			$db = JFactory::getDbo();

			if ($uid)
			{
				$user = JFactory::getUser($uid);
			}
			else
			{
				$user = JFactory::getUser();
			}

			$name = JFile::getName(__FILE__);
			$name = JFile::stripExt($name);

			$jschk = $this->checkForJsExtension();

			if (!empty($jschk))
			{
				$query = $db->getQuery(true);
				$query->select("CONCAT_WS('|', '" . $name . "', g.id) as value");
				$query->select("g.name AS text");
				$query->from($db->quoteName('#__community_groups', 'g'));
				$query->join('LEFT', $db->quoteName('#__users', 'u') . 'ON' . $db->quoteName('g.ownerid') . '=' . $db->quoteName('u.id'));
				$query->where($db->quoteName('u.id') . " = " . $db->quote($user->id));
				$db->setQuery($query);
				$itemlist = $db->loadObjectlist();

				if (empty($itemlist))
				{
						$list = array();

						return $list;
				}
				else
				{
					return $itemlist;
				}
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
		$db = JFactory::getDbo();

		$groupid = $id;
		$Itemid = JRequest::getInt('Itemid');

		$jschk = $this->checkForJsExtension();

		if (!empty($jschk))
		{
			$query = $db->getQuery(true);
			$query->select('ownerid as id');
			$query->select('name as title');
			$query->select('avatar as image');
			$query->select('description as bodytext');
			$query->from($db->quoteName('#__community_groups'));
			$query->where($db->quoteName('id') . " = " . $db->quote($groupid));
			$db->setQuery($query);
			$previewdata = $db->loadObjectlist();

			// Include Jomsocial core
			$jspath = JPATH_ROOT . '/components/com_community';
			include_once $jspath . '/libraries/core.php';
			$previewdata[0]->url = JUri::root() .
									substr(
										CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $groupid),
										strlen(JUri::base(true)) + 1
									);

			if ($previewdata[0]->image == '')
			{
				$previewdata[0]->image = 'components/com_community/assets/group.png';
			}

			return $previewdata;
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
