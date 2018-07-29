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

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

// Proceed further only if this component folder exists
if (JFolder::exists(JPATH_ROOT . '/components/com_socialads'))
{
	$lang = JFactory::getLanguage();
	$lang->load('mod_socialads', JPATH_SITE);

	// SocialAds config parameters
	$sa_params = JComponentHelper::getParams('com_socialads');

	// @TODO - chk if needed
	// $allowed_type = $sa_params->get('ad_type_allowed');

	// Load js assets
	$tjStrapperPath = JPATH_SITE . '/media/techjoomla_strapper/tjstrapper.php';

	if (JFile::exists($tjStrapperPath))
	{
		require_once $tjStrapperPath;
		TjStrapper::loadTjAssets('com_socialads');
	}

	// Load CSS & JS resources.
	if (JVERSION > '3.0')
	{
		$laod_boostrap = $sa_params->get('boostrap_manually');

		if (!empty($laod_boostrap))
		{
			// Load bootstrap CSS and JS.
			JHtml::_('bootstrap.loadcss');
			JHtml::_('bootstrap.framework');
		}
	}

	// Load module helper
	require_once dirname(__FILE__) . '/helper.php';

	$saInitClassPath = JPATH_SITE . '/components/com_socialads/init.php';

	if (!class_exists('SaInit'))
	{
		JLoader::register('SaInit', $saInitClassPath);
		JLoader::load('SaInit');
	}

	// Define autoload function
	spl_autoload_register('SaInit::autoLoadHelpers');

	// Get module id, zone id
	$moduleid = $module->id;
	$zone_id  = $params->get('zone', 0);

	// Get ad types for current zone
	// $modSocialadsHelper = new modSocialadsHelper;
	$ad_type = ModSocialadsHelper::getAdtypebyZone($zone_id);
	// print_r($ad_type);die;

	// Show create ad link in output?
	if ($params->get('create', 1))
	{
		//$socialadshelper = new SocialadsAdHelper;
		$Itemid = SaCommonHelper::getSocialadsItemid('adform');
		//print_r($Itemid);die;
	}

	// $ads = $adRetriever->getAdsForZoneExternally($params, $moduleid);
	$ads = SaAdEngineHelper::getInstance()->getAdsForZone($params, $moduleid);

	require JModuleHelper::getLayoutPath('mod_socialads', $params->get('layout', 'default'));
}
