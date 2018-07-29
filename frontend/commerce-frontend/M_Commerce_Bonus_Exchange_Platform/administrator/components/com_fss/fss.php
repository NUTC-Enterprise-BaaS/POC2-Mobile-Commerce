<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);

if (version_compare(phpversion(), '5.3.0', '<')) {
	
	echo "<div class='alert'><h4>PHP Version 5.3 or above required.</h4><p>Freestyle Support Portal requires PHP version 5.3 or above";
	echo " to work correctly. You are currently running " . phpversion() . ". Please update your version of php to be able to ";
	echo "to run this component</p></div>"; 
	
} else {
	
	// Require the base controller
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php' );
	require_once( JPATH_COMPONENT.DS.'controller.php' );
	require_once( JPATH_COMPONENT.DS.'settings.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'permission.php' );
	require_once( JPATH_COMPONENT.DS.'adminhelper.php' );

	// Require specific controller if requested
	if($controller = JRequest::getWord('controller')) {
		$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
		if (file_exists($path)) {
			require_once $path;
		} else {
			$controller = '';
		}
	}

	if (!function_exists("print_p"))
	{
		function print_p($var)
		{
			echo "<pre>";
			print_r($var);
			echo "</pre>";	
		}
	}
	
	// do version check
	$ver_inst = FSSAdminHelper::GetInstalledVersion();
	$ver_files = FSSAdminHelper::GetVersion();
	
	
	if (!JFactory::getUser()->authorise('core.manage', 'com_fss')) 
	{
		return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	}

	// if bad version display warning message
	if ($ver_files != $ver_inst)
	{
		$task = JRequest::getVar('task');	
		$view = JRequest::getVar('view');

		if ($task != "update" || $view != "backup")
		JError::raiseWarning( 100, JText::sprintf('INCORRECT_VERSION',FSSRoute::_('index.php?option=com_fss&view=backup&task=update')) );
		
		if ($view != "" && $view != "backup")
		JRequest::setVar('view','');
	}
	// if bad version and controller is not fsss dont display
	
	
	// Create the controller
	$controllername = $controller;
	$classname    = 'FsssController'.$controller;
	$controller   = new $classname( );

	FSS_Helper::StylesAndJS(array('force_jquery', 'tooltip', 'translate', 'admin_css'));

	// Perform the Request task
	$controller->execute( JRequest::getVar( 'task' ) );

	// Redirect if set by the controller
	$controller->redirect();
}