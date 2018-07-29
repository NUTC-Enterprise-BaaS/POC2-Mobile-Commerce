<?php
/**
 * @package   Foundry
 * @copyright Copyright (C) 2010-2013 Stack Ideas Sdn Bhd. All rights reserved.
 * @license   GNU/GPL, see LICENSE.php
 *
 * Foundry is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ROOT . '/media/foundry/4.0/joomla/framework.php');
class FD40_FoundryFramework {

	public static function defineFrameworkConstants($className='', $ns=null) {

		$NS = (empty($ns)) ? strtoupper($className) . '_' : $ns;

		define($NS.'JOOMLA_PATH' , JPATH_ROOT);
		define($NS.'JOOMLA_URI'  , rtrim(JURI::root(), '/'));
		define($NS.'MEDIA_PATH'  , constant($NS.'JOOMLA_PATH') . '/media');
		define($NS.'MEDIA_URI'   , constant($NS.'JOOMLA_URI') . '/media');
		define($NS.'BOOTCODE'    , 'FD40');
		define($NS.'VERSION'     , '4.0');
		define($NS.'LONG_VERSION', '4.0.41');
		define($NS.'PATH'        , constant($NS.'JOOMLA_PATH') . '/media/foundry/' . constant($NS.'VERSION'));
		define($NS.'URI'         , rtrim(JURI::root(), '/') . '/media/foundry/' . constant($NS.'VERSION'));
		define($NS.'HOSTED'      , 'http://foundry.stackideas.com/' .  constant($NS.'VERSION'));
		define($NS.'CLASSES'     , constant($NS.'PATH') . '/joomla');
		define($NS.'LIB'         , constant($NS.'PATH') . '/libraries');
	}

	public static function defineComponentConstants($className='', $ns=null) {

		$NS = (empty($ns)) ? strtoupper($className) . '_' : $ns;

		// Joomla
		define($NS.'JOOMLA'           , JPATH_ROOT);
		define($NS.'JOOMLA_URI'       , rtrim(JURI::root(), '/'));
		define($NS.'JOOMLA_ADMIN'     , constant($NS.'JOOMLA') . '/administrator');
		define($NS.'JOOMLA_ADMIN_URI' , constant($NS.'JOOMLA_URI') . '/administrator');
		define($NS.'JOOMLA_SITE_TEMPLATES'      , constant($NS.'JOOMLA') . '/templates' );
		define($NS.'JOOMLA_SITE_TEMPLATES_URI'  , constant($NS.'JOOMLA_URI') . '/templates' );
		define($NS.'JOOMLA_ADMIN_TEMPLATES'     , constant($NS.'JOOMLA_ADMIN') . '/templates' );
		define($NS.'JOOMLA_ADMIN_TEMPLATES_URI' , constant($NS.'JOOMLA_ADMIN_URI') . '/templates' );
		define($NS.'JOOMLA_MODULES'             , constant($NS.'JOOMLA') . '/modules' );
		define($NS.'JOOMLA_MODULES_URI'         , constant($NS.'JOOMLA_URI') . '/modules' );

		// Foundry
		define($NS.'FOUNDRY_VERSION'      , '4.0' );
		define($NS.'FOUNDRY_LONG_VERSION' , '4.0.41');
		define($NS.'FOUNDRY_BOOTCODE'     , 'FD40' );
		define($NS.'FOUNDRY'		      , constant($NS.'JOOMLA') . '/media/foundry/' . constant($NS.'FOUNDRY_VERSION'));
		define($NS.'FOUNDRY_URI'	      , constant($NS.'JOOMLA_URI') . '/media/foundry/' . constant($NS.'FOUNDRY_VERSION'));
		define($NS.'FOUNDRY_CONFIGURATION', constant($NS.'FOUNDRY') . '/joomla/configuration.php');

		// Component
		define($NS.'CLASS_NAME'    , $className);
		define($NS.'IDENTIFIER'    , strtolower($className));
		define($NS.'COMPONENT_NAME', 'com_' . constant($NS.'IDENTIFIER'));
		define($NS.'PREFIX'        , constant($NS.'IDENTIFIER') . '/');
		define($NS.'SITE' 	       , constant($NS.'JOOMLA') . '/components/' . constant($NS.'COMPONENT_NAME'));
		define($NS.'SITE_URI'      , constant($NS.'JOOMLA_URI') . '/components/' . constant($NS.'COMPONENT_NAME'));
		define($NS.'ADMIN'	       , constant($NS.'JOOMLA_ADMIN') . '/components/' . constant($NS.'COMPONENT_NAME'));
		define($NS.'ADMIN_URI'     , constant($NS.'JOOMLA_ADMIN_URI') . '/components/' . constant($NS.'COMPONENT_NAME'));
		define($NS.'MEDIA'	       , constant($NS.'JOOMLA') . '/media/' . constant($NS.'COMPONENT_NAME'));
		define($NS.'MEDIA_URI'     , constant($NS.'JOOMLA_URI') . '/media/' . constant($NS.'COMPONENT_NAME'));
		define($NS.'SCRIPTS'       , constant($NS.'MEDIA') . '/scripts' );
		define($NS.'SCRIPTS_URI'   , constant($NS.'MEDIA_URI') . '/scripts');
		define($NS.'RESOURCES'     , constant($NS.'MEDIA') . '/resources' );
		define($NS.'RESOURCES_URI' , constant($NS.'MEDIA_URI') . '/resources');
		define($NS.'CONFIG'        , constant($NS.'MEDIA') . '/config' );
		define($NS.'CONFIG_URI'    , constant($NS.'MEDIA_URI') . '/config');

		// Themes
		define($NS.'SITE_THEMES'      , constant($NS.'SITE') . '/themes');
		define($NS.'SITE_THEMES_URI'  , constant($NS.'SITE_URI') . '/themes');
		define($NS.'ADMIN_THEMES'	  , constant($NS.'ADMIN') . '/themes');
		define($NS.'ADMIN_THEMES_URI' , constant($NS.'ADMIN_URI') . '/themes');
		define($NS.'USER_THEMES'      , constant($NS.'MEDIA') . '/themes');
		define($NS.'USER_THEMES_URI'  , constant($NS.'MEDIA_URI') . '/themes');
	}

	public static function defineFrameworkCDNConstants($ns='', $cdnRoot, $passiveCdn=false) {

		static $executed;

		if ($executed) return;

		$NS = $ns . '_';

		define($NS.'JOOMLA_CDN'  , rtrim($cdnRoot, '/'));
		define($NS.'MEDIA_CDN'   , constant($NS.'JOOMLA_CDN') . '/media');
		define($NS.'CDN'         , constant($NS.'JOOMLA_CDN') . '/media/foundry/' . constant($NS.'VERSION'));
		define($NS.'PASSIVE_CDN' , $passiveCdn);

		$executed = true;
	}

	public static function defineComponentCDNConstants($ns='', $cdnRoot, $passiveCdn=false) {

		$NS = $ns . '_';

		// Also define framework constant
		FD40_FoundryFramework::defineFrameworkCDNConstants('FD40_FOUNDRY', $cdnRoot, $passiveCdn);

		// Joomla
		define($NS.'JOOMLA_CDN'       , rtrim($cdnRoot, '/'));
		define($NS.'JOOMLA_ADMIN_CDN' , constant($NS.'JOOMLA_CDN') . '/administrator');
		define($NS.'JOOMLA_SITE_TEMPLATES_CDN'  , constant($NS.'JOOMLA_CDN') . '/templates' );
		define($NS.'JOOMLA_ADMIN_TEMPLATES_CDN' , constant($NS.'JOOMLA_ADMIN_CDN') . '/templates' );
		define($NS.'JOOMLA_MODULES_CDN'         , constant($NS.'JOOMLA_CDN') . '/modules' );

		// Foundry
		define($NS.'FOUNDRY_CDN' , constant($NS.'JOOMLA_CDN') . '/media/foundry/' . constant($NS.'FOUNDRY_VERSION'));

		// Component
		define($NS.'SITE_CDN'      , constant($NS.'JOOMLA_CDN') . '/components/' . constant($NS.'COMPONENT_NAME'));
		define($NS.'ADMIN_CDN'     , constant($NS.'JOOMLA_ADMIN_CDN') . '/components/' . constant($NS.'COMPONENT_NAME'));
		define($NS.'MEDIA_CDN'     , constant($NS.'JOOMLA_CDN') . '/media/' . constant($NS.'COMPONENT_NAME'));
		define($NS.'SCRIPTS_CDN'   , constant($NS.'MEDIA_CDN') . '/scripts');
		define($NS.'RESOURCES_CDN' , constant($NS.'MEDIA_CDN') . '/resources');
		define($NS.'CONFIG_CDN'    , constant($NS.'MEDIA_CDN') . '/config');
		define($NS.'PASSIVE_CDN'   , $passiveCdn);

		// Themes
		define($NS.'SITE_THEMES_CDN'  , constant($NS.'SITE_CDN') . '/themes');
		define($NS.'ADMIN_THEMES_CDN' , constant($NS.'ADMIN_CDN') . '/themes');
		define($NS.'USER_THEMES_CDN'  , constant($NS.'MEDIA_CDN') . '/themes');
	}
}

FD40_FoundryFramework::defineFrameworkConstants('Foundry', 'FD40_FOUNDRY_');
