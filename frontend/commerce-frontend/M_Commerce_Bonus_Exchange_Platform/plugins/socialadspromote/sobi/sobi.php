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
$lang->load('plg_socialadspromote_promote_sobi', JPATH_ADMINISTRATOR);

/**
 * Plugin class to promote Sobi list
 *
 * @since  1.6
 */
class PlgSocialadsPromoteSobi extends JPlugin
{
	/**
	 * Methode to to promote sobi
	 *
	 * @param   integer  $uid  users ID
	 *
	 * @return  array
	 *
	 * @since   1.6
	 */
	public function onPromoteList($uid = '')
	{
		if ($uid)
		{
			$user = JFactory::getUser($uid);
		}
		else
		{
			$user = JFactory::getUser();
		}

		jimport('joomla.filesystem.file');
		$db   = JFactory::getDBO();
		$name = JFile::getName(__FILE__);
		$name = JFile::stripExt($name);
		$sobichk = $this->checkForSobiExtension();

		if (!empty($sobichk))
		{
			$query = $db->getQuery(true);
			$query->select("CONCAT_WS('|', '" . $name . "', s.itemid) as value");
			$query->select("s.title as text");
			$query->from($db->quoteName('#__sobi2_item', 's'));
			$query->join('LEFT', $db->quoteName('#__users', 'u') . 'ON' . $db->quoteName('s.updating_user') . '=' . $db->quoteName('u.id'));
			$query->where($db->quoteName('u.id') . " = " . $db->quote($user->id));
			$query->order('itemid');
			$db->setQuery($query);
			$itemlist = $db->loadObjectlist();

			if (empty($itemlist))
			{
				$list[0]->value = $name . '|' . '0';
				$list[0]->text  = JText::_("NO_SOBILIST");

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
		$db	= JFactory::getDBO();

		$Itemid = JRequest::getInt('Itemid');

		$sobichk = $this->checkForSobiExtension();

		if (!empty($sobichk))
		{
			$query = $db->getQuery(true);
			$query->select('s.title as title');
			$query->select("CONCAT_WS('/', 'images/com_sobi2/clients', s.image) as image");
			$query->select('s.metadesc as bodytext');
			$query->join('LEFT', $db->quoteName('#__users', 'u') . 'ON' .
			$db->quoteName('s.updating_user') . '=' . $db->quoteName('u.id')
			);
			$query->from($db->quoteName('#__sobi2_item', 's'));
			$query->where($db->quoteName('itemid') . " = " . $db->quote($id));
			$query->where($db->quoteName('approved') . "='1'");
			$query->where($db->quoteName('published') . "='1'");
			$db->setQuery($query);
			$previewdata = $db->loadObjectlist();
			$previewdata[0]->url = JUri::root() .
					substr(
					JRoute::_('index.php?option=com_sobi2&sobi2Task=sobi2Details&sobi2Id=' . $id . '&Itemid=' . $Itemid),
							strlen(JUri::base(true)) + 1
					);
			$previewdata[0]->url = JUri::base() . 'index.php?option=com_sobi2&sobi2Task=sobi2Details&sobi2Id=' . $id . '&Itemid=' . $Itemid;

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
	public function checkForSobiExtension()
	{
		jimport('joomla.filesystem.folder');
		$sobipath = JPATH_ROOT . '/components/com_sobi2';

		if (JFolder::exists($sobipath))
		{
			return 1;
		}
		else
		{
			return '';
		}
	}
}
