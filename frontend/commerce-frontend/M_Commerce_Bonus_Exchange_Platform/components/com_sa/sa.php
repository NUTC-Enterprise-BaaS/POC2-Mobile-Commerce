<?php
/**
 * @version    SVN: <svn_id>
 * @package    Sa
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Define wrapper class
define('SA_WRAPPER_CLASS', "sa-wrapper");

// Load defines.php
require_once JPATH_SITE . '/components/com_socialads/defines.php';

// Load common lang. file
$lang = JFactory::getLanguage();
$lang->load('com_socialads_common', JPATH_SITE, $lang->getTag(), true);
$lang->load('com_socialads', JPATH_SITE, $lang->getTag(), true);

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
JLoader::import('SaCommonHelper', JPATH_SITE . '/components/com_socialads/helpers/common.php');

// Load common lang. file
$lang = JFactory::getLanguage();
$lang->load('com_socialads_common', JPATH_SITE, $lang->getTag(), true);

// Call helper function
SaCommonHelper::getLanguageConstant();

// Define autoload function
spl_autoload_register('SaInit::autoLoadHelpers');

// Include dependancies
jimport('joomla.application.component.controller');

// Execute the task.
$controller = JControllerLegacy::getInstance('Sa');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
