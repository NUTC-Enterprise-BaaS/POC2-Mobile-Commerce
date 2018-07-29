<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Include main engine
$file 	= JPATH_ROOT . '/administrator/components/com_easysocial/includes/foundry.php';

jimport( 'joomla.filesystem.file' );

if (!JFile::exists($file)) {
	return;
}

// Include the engine file.
require_once($file);
require_once(dirname(__FILE__) . '/helper.php');

// Check if Foundry exists
if (!FD::exists()) {
	FD::language()->loadSite();
	echo JText::_( 'COM_EASYSOCIAL_FOUNDRY_DEPENDENCY_MISSING' );
	return;
}

FD::language()->loadSite();

// Get the current logged in user
$my = FD::user();

if (!$my->id && !$params->get('show_sign_in', true)){
	return;
}

// Load up the module engine
$modules = FD::modules('mod_easysocial_dropdown_menu');

// We need these packages
$modules->loadComponentScripts();
$modules->loadComponentStylesheets();
$modules->addDependency('css', 'javascript');

// Get menu items
$items = array();

if ($my->guest) {

    $config = FD::config();
    $facebook = FD::oauth('Facebook');
    $loginReturn = ModEasySocialDropdownMenuHelper::getReturnURL($params);
} else {

    if ($params->get('render_menus', false)) {
    	$items = ModEasySocialDropdownMenuHelper::getItems( $params );
    }

    // Get the logout return
    $logoutMenu = FD::config()->get( 'general.site.logout' );
    $logoutReturn = FRoute::getMenuLink($logoutMenu);
    $logoutReturn = base64_encode( $logoutReturn );

}
// Get the layout to use.
$layout = $params->get('layout' , 'default');
$suffix = $params->get('suffix', '');

$showRememberMe = $params->get('remember_me_style', 'visible_checked') == 'visible_checked' || $params->get('remember_me_style') == 'visible';
$checkRememberMe = $params->get('remember_me_style', 'visible_checked') == 'visible_checked' || $params->get('remember_me_style') == 'hidden_checked';

require( JModuleHelper::getLayoutPath('mod_easysocial_dropdown_menu', $layout));
