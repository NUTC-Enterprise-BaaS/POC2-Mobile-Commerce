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

// Include dependancies
jimport('joomla.application.component.controller');

// Load defines.php
require_once JPATH_SITE . '/components/com_socialads/defines.php';

if (version_compare(JVERSION, '3.0', 'lt'))
{
	JHtml::_('behavior.tooltip');
}
else
{
	// Tabstate
	JHtml::_('behavior.tabstate');

	// Bootstrap tooltip and chosen js
	JHtml::_('bootstrap.tooltip');
	JHtml::_('behavior.multiselect');
}

// Load strapper
$tjStrapperPath = JPATH_SITE . '/media/techjoomla_strapper/tjstrapper.php';

if (JFile::exists($tjStrapperPath))
{
	require_once $tjStrapperPath;
	TjStrapper::loadTjAssets('com_socialads');
}

if (JVERSION < '3.0')
{
	// Define wrapper class
	define('SA_WRAPPER_CLASS', "sa-wrapper techjoomla-bootstrap");
}
else
{
	// Define wrapper class
	define('SA_WRAPPER_CLASS', "sa-wrapper");
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

$helperPath = JPATH_SITE . '/components/com_socialads/helpers/payment.php';

if (!class_exists('SocialadsPaymentHelper'))
{
	JLoader::register('SocialadsPaymentHelper', $helperPath);
	JLoader::load('SocialadsPaymentHelper');
}

$helperPath = JPATH_SITE . '/components/com_socialads/helpers/wallet.php';

if (!class_exists('SaWalletHelper'))
{
	JLoader::register('SaWalletHelper', $helperPath);
	JLoader::load('SaWalletHelper');
}

// Import helper for declaring language constant
JLoader::import('SaCommonHelper', JUri::root(true) . 'components/com_socialads/helpers/common.php');

// Load common lang. file
$lang = JFactory::getLanguage();
$lang->load('com_socialads_common', JPATH_SITE, $lang->getTag(), true);

// Call helper function
SaCommonHelper::getLanguageConstant();

$doc = JFactory::getDocument();

// $doc->addScript(JUri::root(true) . '/media/com_sa/js/socialads.js');

// Frontend css
$doc->addStyleSheet(JUri::root(true) . '/media/com_sa/css/sa.css');
$doc->addScript(JUri::root(true) . '/media/com_sa/js/sa.js');

// Responsive tables
$doc->addStyleSheet(JUri::root(true) . '/media/com_sa/css/sa-tables.css');

// Execute the task.
$controller = JControllerLegacy::getInstance('Socialads');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
