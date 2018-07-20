<?php
/**------------------------------------------------------------------------
 * com_vikchannelmanager - VikChannelManager
 * ------------------------------------------------------------------------
 * author    e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

defined('VIKCHANNELMANAGER_SOFTWARE_VERSION') or define('VIKCHANNELMANAGER_SOFTWARE_VERSION', '1.4.0');

if( !defined('DS') ) {
	define( 'DS', DIRECTORY_SEPARATOR );
}

// Access check.
if(!JFactory::getUser()->authorise('core.manage', 'com_vikchannelmanager')){
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// require helper files
require_once( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_vikchannelmanager' . DS . 'helpers' . DS . 'helper.php');
require_once ( JPATH_SITE . DS . 'components' . DS . 'com_vikchannelmanager'. DS. 'helpers' . DS . 'lib.vikchannelmanager.php');
require_once ( JPATH_SITE . DS . 'components' . DS . 'com_vikchannelmanager'. DS. 'helpers' . DS . 'vcm_config.php');

// import joomla controller library
jimport('joomla.application.component.controller');

new VersionListener();

new OrderingManager('com_vikchannelmanager', 'vcmordcolumn', 'vcmordtype');

// Add CSS file for all pages
VCM::load_css_js();

// Get an instance of the controller
$controller = JControllerLegacy::getInstance('VikChannelManager');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();

?>