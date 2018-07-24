<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

class RemoteSaAdEngineHelper extends SaAdEngineHelper
{
	/**
	 * Constructor
	 *
	 * @param   integer  $userid  User id
	 * @param   integer  $extra   Extra param
	 */
	public function __construct($engineType = 'remote', $userid = 0, $extra = 0)
	{
		parent::__construct($engineType, $userid, $extra);
	}

	/**
	 * @TODO - manoj- seems, it is no more needed
	 */
	public static function get($paramindex, $default)
	{
		$session  = JFactory::getSession();
		$userData = $session->get('userData', array());

		if (empty($userData['ads_params'][$paramindex]))
		{
			return $default;
		}
		else
		{
			return $userData['ads_params'][$paramindex];
		}
	}

	/**
	 * Get params
	 * This is needed for Ads in Email
	 * This returns accesses plugin param either from config or from SA email data tag
	 *
	 * @param   object  $params      Component paramters
	 * @param   string  $paramindex  Component param option
	 *
	 * @return  mixed
	 */
	public static function getParam($params, $paramindex)
	{
		return $params['ads_params'][$paramindex];
	}
}
