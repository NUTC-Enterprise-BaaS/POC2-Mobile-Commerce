<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Acmanager
 * @author     Parth Lawate <contact@techjoomla.com>
 * @copyright  Copyright (C) 2016. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Acmanager helper.
 *
 * @since  1.6
 */
class AcmanagerHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  string
	 *
	 * @return void
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_ACMANAGER_TITLE_APPUSERS'),
			'index.php?option=com_acmanager&view=appusers',
			$vName == 'appusers'
		);

JHtmlSidebar::addEntry(
			JText::_('COM_ACMANAGER_TITLE_PUSHNOTIFICATIONCONFIGS'),
			'index.php?option=com_acmanager&view=pushnotificationconfigs',
			$vName == 'pushnotificationconfigs'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_ACMANAGER_TITLE_MANAGEIOSCERTIFICATESS'),
			'index.php?option=com_acmanager&view=manageioscertificatess',
			$vName == 'manageioscertificatess'
		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return    JObject
	 *
	 * @since    1.6
	 */
	public static function getActions()
	{
		$user   = JFactory::getUser();
		$result = new JObject;

		$assetName = 'com_acmanager';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
