<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_socialads'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Load defines.php
require_once JPATH_SITE . '/components/com_socialads/defines.php';

// Lib load
if (JVERSION < '3.0')
{
	// Define wrapper class
	define('SA_WRAPPER_CLASS', "sa-wrapper techjoomla-bootstrap");

	// Other
	JHtml::_('behavior.tooltip');
}
else
{
	// Define wrapper class
	define('SA_WRAPPER_CLASS', "sa-wrapper");

	// Tabstate
	JHtml::_('behavior.tabstate');

	// Other
	JHtml::_('behavior.tooltip');

	// Bootstrap tooltip and chosen js
	JHtml::_('bootstrap.tooltip');
	JHtml::_('behavior.multiselect');
}

// Load assets
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root(true) . '/media/com_sa/css/sa_admin.css');
$document->addStyleSheet(JUri::root(true) . '/media/com_sa/css/sa-tables.css');
$document->addScript(JUri::root(true) . '/media/com_sa/js/sa.js');

// Load other assets via strapper
$tjStrapperPath = JPATH_SITE . '/media/techjoomla_strapper/tjstrapper.php';

if (JFile::exists($tjStrapperPath))
{
	require_once $tjStrapperPath;
	TjStrapper::loadTjAssets('com_socialads');
}

// Load backend helper
if (!class_exists('SocialadsHelper'))
{
	JLoader::register('SocialadsHelper', JPATH_COMPONENT . '/helpers/socialads.php');
	JLoader::load('SocialadsHelper');
}

$helperPath = JPATH_SITE . '/components/com_socialads/helpers';

if (!class_exists('SaCommonHelper'))
{
	require_once $helperPath . '/common.php';

	// JLoader::register('SaCommonHelper', $helperPath . '/common.php' );
	// JLoader::load('SaCommonHelper');
}

if (!class_exists('SaWalletHelper'))
{
	JLoader::register('SaWalletHelper', $helperPath . '/wallet.php');
	JLoader::load('SaWalletHelper');
}

if (!class_exists('SocialadsPaymentHelper'))
{
	JLoader::register('SocialadsPaymentHelper', $helperPath . '/payment.php');
	JLoader::load('SocialadsPaymentHelper');
}

// Load all required classes
$saInitClassPath = JPATH_SITE . '/components/com_socialads/init.php';

if (!class_exists('SaInit'))
{
	JLoader::register('SaInit', $saInitClassPath);
	JLoader::load('SaInit');
}

// Define autoload function
spl_autoload_register('SaInit::autoLoadHelpers');

// Import helper for declaring language constant
// JLoader::import('SocialadsHelper', JUri::root().'administrator/components/com_socialads/helpers/socialads.php');

// Load common lang. file
$lang = JFactory::getLanguage();
$lang->load('com_socialads_common', JPATH_SITE, $lang->getTag(), true);

// Call helper function
SocialadsHelper::getLanguageConstant();

// Execute task
$controller = JControllerLegacy::getInstance('Socialads');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
