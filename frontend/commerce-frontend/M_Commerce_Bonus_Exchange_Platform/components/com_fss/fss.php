<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if (!function_exists("print_p"))
{
	function print_p($var)
	{
		echo "<div>";
		echo "<div style='height:1.2em;overflow:hidden;' onclick='jQuery(this).css(\"height\", \"\");jQuery(this).css(\"float\", \"none\");'><div style='float: right'>Stack Trace</div><br />" . dumpStack(1) . "</div>";
		echo "<pre>";
		print_r($var);
		echo "</pre>";	
		echo "</div>";
	}
}
 
if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);


if (version_compare(phpversion(), '5.3.0', '<')) {
	
	echo "<div class='alert'><h4>PHP Version 5.3 or above required.</h4><p>Freestyle Support Portal requires PHP version 5.3 or above";
	echo " to work correctly. You are currently running " . phpversion() . ". Please update your version of php to be able to ";
	echo "to run this component</p></div>"; 
	
} else {
	
	require_once( JPATH_COMPONENT.DS.'helper'.DS.'settings.php' );
	require_once( JPATH_COMPONENT.DS.'helper'.DS.'j3helper.php' );
	require_once( JPATH_COMPONENT.DS.'controller.php' );
	require_once( JPATH_COMPONENT.DS.'helper'.DS.'helper.php' );
	require_once(JPATH_COMPONENT.DS.'helper'.DS.'permission.php');

	$app = JFactory::getApplication();
	$menu = $app->getMenu();
	if ($menu->getActive() == $menu->getDefault() && !FSS_Settings::get('hide_warnings') && JRequest::getVar('view') != 'cron') {
		echo "<div class='alert alert-danger'><h4>Freestyle Support cannot be run as your default menu item,</h4><p>Freestyle Support Portal ";
		echo "cannot be the default menu item on a Joomla site. This is due to how Joomla handles default item urls not being compatible ";
		echo "with the Freestyle routing. Please create a new home item of the type 'Freestyle Support' -> 'Redirect to main menu' and set ";
		echo "this as your default menu item.";
		echo "</p></div>"; 
	}

	// Require specific controller if requested
	if($controller = FSS_Input::getCmd('controller')) {
		$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
		if (file_exists($path)) {
			require_once $path;
		} else {
			$controller = '';
		}
	}

	FSS_Helper::CheckTicketLink();

	// Create the controller
	$classname    = 'FssController'.$controller;
	$controller   = new $classname( );

	$view = FSS_Input::getCmd('view');

	if ($view != "csstest") FSS_Helper::StylesAndJS(array('tooltip'));

	// Perform the Request task
	$task = FSS_Input::getCmd( 'task' );
	if ($task == "captcha_image")
	{
		ob_clean();
		require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'captcha.php');
		$cap = new FSS_Captcha();
		$cap->GetImage();
		exit;
	} else {
		$controller->execute( $task );

		// Redirect if set by the controller
		$controller->redirect();
	}

}
