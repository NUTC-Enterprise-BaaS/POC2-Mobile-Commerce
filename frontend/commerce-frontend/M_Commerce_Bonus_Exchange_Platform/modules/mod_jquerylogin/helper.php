<?php
/**
 * @version		$Id: helper.php 21421 2011-06-03 07:21:02Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; 
 */

// no direct access
defined('_JEXEC') or die;

class modJQueryLoginHelper
{
	static function getReturnURL($params, $type)
	{
		$app  = JFactory::getApplication();
		$item = $app->getMenu()->getItem($params->get($type));

		if ($item)
		{
			$url = 'index.php?Itemid=' . $item->id;
		}
		else
		{
			// Stay on the same page
			$url = JUri::getInstance()->toString();
		}

		return base64_encode($url);
	}

/**
	 * Returns the current users type
	 *
	 * @return string
	 */
	public static function getType()
	{
		$user = JFactory::getUser();

		return (!$user->get('guest')) ? 'logout' : 'login';
	}

	/**
	 * Get list of available two factor methods
	 *
	 * @return array
	 */
	public static function getTwoFactorMethods()
	{
		require_once JPATH_ADMINISTRATOR . '/components/com_users/helpers/users.php';

		return UsersHelper::getTwoFactorMethods();
	}
}
